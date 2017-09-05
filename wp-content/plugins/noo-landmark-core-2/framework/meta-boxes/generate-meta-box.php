<?php
/**
 * NOO Meta-Boxes Package
 *
 * NOO Meta-Boxes Register Function
 * This file register add_meta_boxes and save_post actions.
 *
 * @package    NOO Framework
 * @subpackage NOO Meta-Boxes
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */

// Create meta box base on inputted value
function noo_create_meta_box($post, $meta_box)
{

    if (!is_array($meta_box))
        return false;

    $prefix = '_noo_wp_post';

    if (isset($meta_box['description']) && $meta_box['description'] != '')
        echo '<p>' . $meta_box['description'] . '</p>';

    wp_nonce_field(basename(__FILE__), 'noo_meta_box_nonce');

    foreach ($meta_box['fields'] as $field) {

        if (!isset($field['type']) || empty($field['type']))
            continue;

        // If it's divider, add a hr
        if ($field['type'] == 'divider') {
            echo '<hr/>';
            continue;
        }

        if (!isset($field['id']) || empty($field['id']))
            continue;

        $id = $field['id'];
        $meta = noo_landmark_func_get_post_meta($post->ID, $id);
        $label = isset($field['label']) && !empty($field['label']) ? '<strong>' . $field['label'] . '</strong>' : '';
        $std = isset($field['std']) ? $field['std'] : '';
        $class = "noo-control ";
        $class = isset($field['class']) && !empty($field['class']) ? ' class="' . $class . $field['class'] . '"' : ' class="' . $class . '"';
        $value = '';

        echo '<div class="noo-form-group ' . $id . '">';

        if ($field['type'] != 'checkbox' || $meta_box['context'] != 'side') {
            if (!empty($label)) {
                echo '<label for="' . $field['id'] . '">' . $label;
                if (isset($field['desc']) && !empty($field['desc']))
                    echo '<div class="field-desc">' . $field['desc'] . '</div>';
                echo '</label>';
            }
        } else {
            $field['inline_label'] = true;
        }

        echo '<div ' . $class . '>';

        if (isset($field['callback']) && !empty($field['callback'])) {
            call_user_func($field['callback'], $post, $id, $field['type'], $meta, $std, $field);
        } else {
            noo_render_metabox_fields($post, $id, $field['type'], $meta, $std, $field);
        }

        echo '</div>'; // div.noo-control
        echo '</div>'; // div.noo-form-group

    } // foreach - $meta_box['fields']
} // function - noo_create_meta_box

function noo_render_metabox_fields($post, $id, $type, $meta, $std, $field = null)
{
    switch ($type) {
        case 'text':
        case 'url':
        case 'number':
        case 'password':
            $value = $meta ? ' value="' . $meta . '"' : '';
            $value = empty($value) && ($std != null && $std != '') ? ' placeholder="' . $std . '"' : $value;
            echo '<input id=' . $id . ' type="' . esc_attr( $type ) . '" name="noo_meta_boxes[' . $id . ']" ' . $value . ' />';
            break;

        case 'textarea':
            echo '<textarea id=' . $id . ' name="noo_meta_boxes[' . $id . ']" placeholder="' . $std . '">' . ($meta ? $meta : $std) . '</textarea>';
            break;

        case 'gmap':
            wp_enqueue_style( 'google-map-icon' );
            wp_enqueue_script( 'google-map' );
            ?>
            <div class="noo-box-map">
                <div data-id="<?php echo esc_attr( $id ) ?>" class="noo-gmap">
                    <div id="<?php echo esc_attr( $id ) ?>" style="height: 500px;"></div>
                </div>
            </div>
            <?php
            break;

        case 'agents':
            $value  = $meta ? $meta : $std;
            $html   = array();
            echo '<select name="noo_meta_boxes[' . $id . ']" id="' . $id . '">';
            echo '<option value="none"' . selected( $value, 'none', false ) . '>' . esc_html__( '-- No Agent --', 'noo-landmark-core' ) . '</option>';

            $args = array(
                'post_type'        => 'noo_agent',
                'posts_per_page'   => -1,
                'post_status'      => 'publish',
                'suppress_filters' => 0
            );
            
            $agents = get_posts( $args );
            if( !empty( $agents ) ){
                foreach ($agents as $agent){
                    // $user_id = Noo_Agent::id_user( $agent->ID );
                    echo '<option value="' . $agent->ID . '"' . selected( $value, $agent->ID, false ) . '>' . $agent->post_title . '</option>';
                }
            }
            echo '</select>';

            break;

        case 'template_image':
            $html = array( '<div class="noo_radio_image">' );
            $meta = $meta ? $meta : $std;
            foreach ( $field['options'] as $key => $img ) {
                
                $html[] = '<label>';
                $html[] = '    <input class=' . $id . ' type="radio" name="noo_meta_boxes[' . $id . ']" value="' . $key . '" ' . ( checked( $meta, $key, false ) ) . '/>';
                $html[] = '    <img src="' . $img . '" />';
                $html[] = '</label>';

            }

            $html[]     = '  </div>';

            echo implode( "\n", $html );
            break;

        case 'gallery':
            $meta = $meta ? $meta : $std;
            $output = '';
            if ($meta != '') {
                $image_ids = explode(',', $meta);
                foreach ($image_ids as $image_id) {
                    $output .= wp_get_attachment_image($image_id, 'thumbnail');
                }
            }

            $btn_text = !empty($meta) ? esc_html__('Edit Gallery', 'noo-landmark-core') : esc_html__('Add Images', 'noo-landmark-core');
            echo '<input type="hidden" name="noo_meta_boxes[' . $id . ']" id="' . $id . '" value="' . $meta . '" />';
            echo '<input type="button" class="button button-primary" name="' . $id . '_button_upload" id="' . $id . '_upload" value="' . $btn_text . '" />';
            echo '<input type="button" class="button" name="' . $id . '_button_clear" id="' . $id . '_clear" value="' . esc_html__('Clear Gallery', 'noo-landmark-core') . '" />';
            echo '<div class="noo-thumb-wrapper">' . $output . '</div>';
            ?>
            <script>
                jQuery(document).ready(function ($) {

                    // gallery state: add new or edit.
                    var gallery_state = '<?php echo empty ($meta) ? 'gallery-library' : 'gallery-edit'; ?>';

                    // Hide the Clear Gallery button if there's no image.
                    <?php if ( empty ($meta) ) : ?> $('#<?php echo esc_attr($id); ?>_clear').hide(); <?php endif; ?>

                    $('#<?php echo esc_attr($id); ?>_upload').on('click', function (event) {
                        event.preventDefault();

                        var noo_upload_btn = $(this);

                        // if media frame exists, reopen
                        if (wp_media_frame) {
                            wp_media_frame.setState(gallery_state);
                            wp_media_frame.open();
                            return;
                        }

                        // create new media frame
                        // I decided to create new frame every time to control the Library state as well as selected images
                        var wp_media_frame = wp.media.frames.wp_media_frame = wp.media({
                            title: 'NOO Gallery', // it has no effect but I really want to change the title
                            frame: "post",
                            toolbar: 'main-gallery',
                            state: gallery_state,
                            library: {type: 'image'},
                            multiple: true
                        });

                        // when open media frame, add the selected image to Gallery
                        wp_media_frame.on('open', function () {
                            var selected_ids = noo_upload_btn.siblings('#<?php echo esc_attr($id); ?>').val();
                            if (!selected_ids)
                                return;
                            selected_ids = selected_ids.split(',');
                            var library = wp_media_frame.state().get('library');
                            selected_ids.forEach(function (id) {
                                attachment = wp.media.attachment(id);
                                attachment.fetch();
                                library.add(attachment ? [attachment] : []);
                            });
                        });

                        // when click Insert Gallery, run callback
                        wp_media_frame.on('update', function () {

                            var library = wp_media_frame.state().get('library');
                            var images = [];
                            var noo_thumb_wraper = noo_upload_btn.siblings('.noo-thumb-wrapper');
                            noo_thumb_wraper.html('');

                            library.map(function (attachment) {
                                attachment = attachment.toJSON();
                                images.push(attachment.id);
                                noo_thumb_wraper.append('<img src="' + attachment.url + '" alt="" />');
                            });

                            gallery_state = 'gallery-edit';

                            noo_upload_btn.siblings('#<?php echo esc_attr($id); ?>').val(images.join(','));

                            noo_upload_btn.attr('value', '<?php echo esc_html__('Edit Gallery', 'noo-landmark-core'); ?>');
                            $('#<?php echo esc_attr($id); ?>_clear').css('display', 'inline-block');
                        });

                        // open media frame
                        wp_media_frame.open();
                    });

                    // Clear button, clear all the images and reset the gallery
                    $('#<?php echo esc_attr($id); ?>_clear').on('click', function (event) {
                        gallery_state = 'gallery-library';
                        var noo_clear_btn = $(this);
                        noo_clear_btn.hide();
                        $('#<?php echo esc_attr($id); ?>_upload').attr('value', '<?php echo esc_html__('Add Images', 'noo-landmark-core'); ?>');
                        noo_clear_btn.siblings('#<?php echo esc_attr($id); ?>').val('');
                        noo_clear_btn.siblings('#<?php echo esc_attr($id); ?>_ids').val('');
                        noo_clear_btn.siblings('.noo-thumb-wrapper').html('');
                    });
                });
            </script>

            <?php
            break;
        case 'application_upload':
        case 'media':
            if (function_exists('wp_enqueue_media')) {
                wp_enqueue_media();
            } else {
                wp_enqueue_style('thickbox');
                wp_enqueue_script('media-upload');
                wp_enqueue_script('thickbox');
            }
            $val = $meta ? $meta : $std;
            $btn_text = !empty($val) ? esc_html__('Change File', 'noo-landmark-core') : esc_html__('Select File', 'noo-landmark-core');
            echo '<input type="text" name="noo_meta_boxes[' . $id . ']" id="' . $id . '" value="' . ($meta ? $meta : $std) . '" style="margin-bottom:10px" />';
            echo '<input type="button" class="button button-primary" name="' . $id . '_button_upload" id="' . $id . '_upload" value="' . $btn_text . '" />';
            echo '<input type="button" class="button" name="' . $id . '_button_clear" id="' . $id . '_clear" value="' . esc_html__('Clear File', 'noo-landmark-core') . '" />';
            ?>
            <script>
                jQuery(document).ready(function ($) {

                    <?php if ( empty ($meta) ) : ?> $('#<?php echo esc_attr($id); ?>_clear').css('display', 'none'); <?php endif; ?>

                    $('#<?php echo esc_attr($id); ?>_upload').on('click', function (event) {
                        event.preventDefault();

                        var noo_upload_btn = $(this);

                        // if media frame exists, reopen
                        if (wp_media_frame) {
                            wp_media_frame.open();
                            return;
                        }

                        // create new media frame
                        // I decided to create new frame every time to control the selected images
                        var wp_media_frame = wp.media.frames.wp_media_frame = wp.media({
                            title: "<?php echo esc_html__('Select or Upload your File', 'noo-landmark-core'); ?>",
                            button: {
                                text: "<?php echo esc_html__('Select', 'noo-landmark-core'); ?>"
                            },
                            <?php if($type == 'media'):?>
                            library: {type: 'video,audio'},
                            <?php endif;?>
                            <?php if($type == 'application_upload'):?>
                            library: {type: 'application'},
                            <?php endif;?>
                            multiple: false
                        });

                        // when image selected, run callback
                        wp_media_frame.on('select', function () {
                            var attachment = wp_media_frame.state().get('selection').first().toJSON();
                            noo_upload_btn.siblings('#<?php echo esc_attr($id); ?>').val(attachment.url);
                            noo_upload_btn.attr('value', '<?php echo esc_html__('Change File', 'noo-landmark-core'); ?>');
                            $('#<?php echo esc_attr($id); ?>_clear').css('display', 'inline-block');
                        });

                        // open media frame
                        wp_media_frame.open();
                    });

                    $('#<?php echo esc_attr($id); ?>_clear').on('click', function (event) {
                        var noo_clear_btn = $(this);
                        noo_clear_btn.hide();
                        $('#<?php echo esc_attr($id); ?>_upload').attr('value', '<?php echo esc_html__('Select File', 'noo-landmark-core'); ?>');
                        noo_clear_btn.siblings('#<?php echo esc_attr($id); ?>').val('');
                    });
                });
            </script>
            <?php
            break;
        case 'image':
            if (function_exists('wp_enqueue_media')) {
                wp_enqueue_media();
            } else {
                wp_enqueue_style('thickbox');
                wp_enqueue_script('media-upload');
                wp_enqueue_script('thickbox');
            }
            $image_id = $meta ? $meta : $std;
            $image = wp_get_attachment_image($image_id, 'thumbnail');
            $output = !empty($image_id) ? $image : '';
            $btn_text = !empty($image_id) ? esc_html__('Change Image', 'noo-landmark-core') : esc_html__('Select Image', 'noo-landmark-core');
            echo '<input type="hidden" name="noo_meta_boxes[' . $id . ']" id="' . $id . '" value="' . ($meta ? $meta : $std) . '" />';
            echo '<input type="button" class="button button-primary" name="' . $id . '_button_upload" id="' . $id . '_upload" value="' . $btn_text . '" />';
            echo '<input type="button" class="button" name="' . $id . '_button_clear" id="' . $id . '_clear" value="' . esc_html__('Clear Image', 'noo-landmark-core') . '" />';
            echo '<div class="noo-thumb-wrapper">' . $output . '</div>';
            ?>
            <script>
                jQuery(document).ready(function ($) {

                    <?php if ( empty ($meta) ) : ?> $('#<?php echo esc_attr($id); ?>_clear').css('display', 'none'); <?php endif; ?>

                    $('#<?php echo esc_attr($id); ?>_upload').on('click', function (event) {
                        event.preventDefault();

                        var noo_upload_btn = $(this);

                        // if media frame exists, reopen
                        if (wp_media_frame) {
                            wp_media_frame.open();
                            return;
                        }

                        // create new media frame
                        // I decided to create new frame every time to control the selected images
                        var wp_media_frame = wp.media.frames.wp_media_frame = wp.media({
                            title: "<?php echo esc_html__('Select or Upload your Image', 'noo-landmark-core'); ?>",
                            button: {
                                text: "<?php echo esc_html__('Select', 'noo-landmark-core'); ?>"
                            },
                            library: {type: 'image'},
                            multiple: false
                        });

                        // when open media frame, add the selected image
                        wp_media_frame.on('open', function () {
                            var selected_id = noo_upload_btn.siblings('#<?php echo esc_attr($id); ?>').val();
                            if (!selected_id)
                                return;
                            var selection = wp_media_frame.state().get('selection');
                            var attachment = wp.media.attachment(selected_id);
                            attachment.fetch();
                            selection.add(attachment ? [attachment] : []);
                        });

                        // when image selected, run callback
                        wp_media_frame.on('select', function () {
                            var attachment = wp_media_frame.state().get('selection').first().toJSON();
                            noo_upload_btn.siblings('#<?php echo esc_attr($id); ?>').val(attachment.id);

                            noo_thumb_wraper = noo_upload_btn.siblings('.noo-thumb-wrapper');
                            noo_thumb_wraper.html('');
                            noo_thumb_wraper.append('<img src="' + attachment.url + '" alt="" />');

                            noo_upload_btn.attr('value', '<?php echo esc_html__('Change Image', 'noo-landmark-core'); ?>');
                            $('#<?php echo esc_attr($id); ?>_clear').css('display', 'inline-block');
                        });

                        // open media frame
                        wp_media_frame.open();
                    });

                    $('#<?php echo esc_attr($id); ?>_clear').on('click', function (event) {
                        var noo_clear_btn = $(this);
                        noo_clear_btn.hide();
                        $('#<?php echo esc_attr($id); ?>_upload').attr('value', '<?php echo esc_html__('Select Image', 'noo-landmark-core'); ?>');
                        noo_clear_btn.siblings('#<?php echo esc_attr($id); ?>').val('');
                        noo_clear_btn.siblings('.noo-thumb-wrapper').html('');
                    });
                });
            </script>

            <?php
            break;
        case 'datepicker':
        case 'datetimepicker':
            wp_enqueue_script('datetimepicker');
            wp_enqueue_style('datetimepicker');
            $date_format = get_option('date_format');
            if ($type == 'datetimepicker') {
                $date_format = $date_format . ' ' . get_option('time_format');
            }

            $date_text = !empty($meta) ? date($date_format, $meta) : '';

            echo '<div>';
            echo '<input type="text" readonly class="input_text" name="noo_meta_boxes[' . $id . ']" id="' . $id . '" value="' .
                esc_attr($date_text) . '" /> ';
            echo '<input type="hidden" name="noo_meta_boxes[' . $id . ']" value="' .
                esc_attr($meta) . '" /> ';
            echo '</div>';
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    $('#<?php echo $id; ?>').datetimepicker({
                        format: "<?php echo esc_html($date_format); ?>",
                        step: 15,
                        <?php if( $type == 'datepicker' ) : ?>
                        timepicker: false,
                        <?php endif; ?>
                        onChangeDateTime: function (dp, $input) {
                            $input.next('input[type="hidden"]').val(parseInt(dp.getTime() / 1000) - 60 * dp.getTimezoneOffset());
                        }
                    });
                });
            </script>
            <?php
            break;

        case 'select':
        case 'multiple_select':
            $meta = $meta ? $meta : $std;

            $array_field = ( $type === 'multiple_select' ? '[]' : '' );
            echo '<input type="hidden" name="noo_meta_boxes[' . $id . ']" value="0" />';
            echo '<select id=' . $id . ' name="noo_meta_boxes[' . $id . ']' . esc_attr( $array_field ) . '"' . ( $type === 'multiple_select' ? ' multiple="multiple"' : '' ) . '>';
            if ( !empty( $field['option_none'] ) ) {
                echo '<option>' . sprintf( esc_html__( 'Select %s', 'noo' ), $field['label'] ) . '</option>';
            }
            foreach ($field['options'] as $option) {
                $opt_value = @$option['value'];
                $opt_label = @$option['label'];

                if( is_array( $meta ) ) {
                    $selected = in_array($opt_value, $meta) ? 'selected="selected"' : '';
                } else {
                    $selected = ( $opt_value == $meta ) ? 'selected="selected"' : '';
                }

                echo '<option value="' . $opt_value . '" ' . esc_attr( $selected ) . '>' . esc_html( $opt_label ) . '</option>';
            }
            echo '</select>';
            break;

        case 'radio':
            $meta = $meta ? $meta : $std;
            echo '<ul class="noo-radio-image" id="' . $id . '">';
            foreach ($field['options'] as $index => $option) {
                $opt_value = $option['value'];
                $opt_label = isset( $option['label'] ) ? $option['label'] : '';
                $opt_img = isset( $option['image'] ) ? $option['image'] : '';
                $opt_checked = '';
                $opt_class_checked = '';


                if ($meta == $opt_value) $opt_checked = ' checked="checked"';
                if ($meta == $opt_value) $opt_class_checked = 'noo-radio-image-selected';

                $opt_id = isset($option['id']) ? ' ' . $option['id'] : $id . '_' . $index;
                $opt_value_for = ' for="' . $opt_id . '"';
                $opt_class = isset($option['class']) ? ' class="' . $option['class'] . '"' : '';
                if( empty($opt_img) ){
                    echo '<input id="' . $opt_id . '" type="radio" name="noo_meta_boxes[' . $id . ']" value="' . $opt_value . '" class="radio"' . $opt_checked . '/>';
                    echo '<label' . $opt_value_for . $opt_class . '>' . $opt_label . '</label>'; 
                    echo '<br/>';
                } else {
                    echo '<li class="noo-radio-image-li ' . $opt_class_checked . '">';
                    echo '<label' . $opt_value_for . $opt_class . '>';
                    echo '<input id="' . $opt_id . '" type="radio" name="noo_meta_boxes[' . $id . ']" value="' . $opt_value . '" class="noo-radio-image-input"' . $opt_checked . '/>';
                    echo '<img src=' . $opt_img . ' />';
                    echo '</label>';
                    echo '</li>';
                }
            }
            echo '</ul>';

            if (!empty($field['child-fields']) && is_array($field['child-fields'])) :
                $child_boxes = $field['child-fields'];
                ?>
                <script>
                    jQuery(document).ready(function ($) {
                        <?php
                        foreach ( $child_boxes as $option_value => $boxes ) :
                        if (empty($boxes)) continue;
                        $boxes = explode(',', $boxes);
                        foreach ( $boxes as $child_box ) :
                        if (trim($child_box) == "") continue;
                        ?>
                        $('.<?php echo trim($child_box); ?>').addClass('child_<?php echo esc_attr($id); ?> val_<?php echo esc_attr($option_value); ?>');
                        <?php
                        endforeach;
                        endforeach;
                        ?>

                        var parentField = $('.<?php echo esc_attr($id); ?>').closest('.noo-form-group');

                        parentField.bind("toggle_children", function () {
                            $this = jQuery(this);
                            if ($this.hasClass("hide-option")) {
                                jQuery('.child_<?php echo esc_attr($id); ?>').addClass("hide-option").trigger("toggle_children");

                                return;
                            }

                            var checkedElement = $this.find('input:checked');
                            $('.child_<?php echo esc_attr($id); ?>:not(.val_' + checkedElement.val() + ')').addClass("hide-option").trigger("toggle_children");
                            $('.child_<?php echo esc_attr($id); ?>.val_' + checkedElement.val()).removeClass("hide-option").trigger("toggle_children");
                        });

                        parentField.find('input').click(function () {
                            parentField.trigger("toggle_children");
                        });

                        parentField.trigger("toggle_children");

                    });

                </script>

            <?php endif; ?>

            <script>
                // Check radio image
                jQuery(document).ready(function ($) {
                    $('#<?php echo esc_attr($id); ?> li').each(function () {
                        $(this).click(function () {
                            $('.noo-radio-image-li').removeClass('noo-radio-image-selected');
                            $(this).addClass('noo-radio-image-selected');
                        })
                    })
                });
            </script>

            <?php
            break;

        case 'checkbox':
            $opt_value = '';

            if ($meta === null || $meta === '') {
                if ($std && $std !== 'off')
                    $opt_value = ' checked="checked"';
            } else {
                if ($meta && $meta !== 'off')
                    $opt_value = ' checked="checked"';
            }

            echo '<input type="hidden" name="noo_meta_boxes[' . $id . ']" value="0" />';
            if (isset($field['inline_label']) && $field['inline_label']) {
                echo '<label>';
                echo '<input type="checkbox" id="' . $id . '" name="noo_meta_boxes[' . $id . ']" value="1"' . $opt_value . ' /> ';
                echo(isset($field['label']) && !empty($field['label']) ? '<strong>' . $field['label'] . '</strong>' : '');
                echo '</label>';
            } else {
                echo '<input type="checkbox" id="' . $id . '" name="noo_meta_boxes[' . $id . ']" value="1"' . $opt_value . ' /> ';
            }


            if (!empty($field['child-fields']) && is_array($field['child-fields'])) :
                $child_fields = $field['child-fields'];
                ?>
                <script>
                    jQuery(document).ready(function ($) {
                        <?php
                        if ( isset($child_fields['on']) ) :
                            $fields = explode(',', $child_fields['on']);
                            foreach ( $fields as $child_field ) :
                                if (trim($child_field) == "") continue;
                                ?>
                                $('.<?php echo trim($child_field); ?>').addClass('child_<?php echo esc_attr($id); ?> val_on hide-option');
                                <?php
                            endforeach;
                        endif;

                        if ( isset($child_fields['off']) ) :
                            $fields = explode(',', $child_fields['off']);
                            foreach ( $fields as $child_field ) :
                                if (trim($child_field) == "") continue;
                                ?>
                                $('.<?php echo trim($child_field); ?>').addClass('child_<?php echo esc_attr($id); ?> val_off');
                                <?php
                            endforeach;
                        endif;
                        ?>

                        var parentField = $('.<?php echo esc_attr($id); ?>').closest('.noo-form-group');

                        parentField.bind("toggle_children", function () {
                            $this = jQuery(this);
                            if ($this.hasClass("hide-option")) {
                                jQuery('.child_<?php echo esc_attr($id); ?>').addClass("hide-option").trigger("toggle_children");

                                return;
                            }
                            jQuery('.child_<?php echo esc_attr($id); ?>').addClass("hide-option").trigger("toggle_children");  

                            var checkboxEl = $('.<?php echo esc_attr($id); ?>').find('input:checkbox');

                            if (checkboxEl.is(':checked')) {
                                $('.child_<?php echo esc_attr($id); ?>.val_on').removeClass("hide-option").trigger("toggle_children");
                            } else {
                                $('.child_<?php echo esc_attr($id); ?>.val_off').removeClass("hide-option").trigger("toggle_children");
                            }
                            
                            checkboxEl.click(function () {
                                $this = $(this);
                                $('.child_<?php echo esc_attr($id); ?>').addClass("hide-option").trigger("toggle_children");
                                if ($this.is(':checked')) {
                                    $('.child_<?php echo esc_attr($id); ?>.val_on').removeClass("hide-option").trigger("toggle_children");
                                } else {
                                    $('.child_<?php echo esc_attr($id); ?>.val_off').removeClass("hide-option").trigger("toggle_children");
                                }
                            });
                            
                        });

                        parentField.find('input').click(function () {
                            parentField.trigger("toggle_children");
                        });

                        parentField.trigger("toggle_children");

                    });
                </script>
            <?php endif;
            break;
        case 'multiple_checkbox':

            $meta = $meta ? noo_json_decode($meta) : ( is_array( $std ) ? $std : array( $std ) );
            if( isset( $field['options'] ) && !empty( $field['options'] ) ) {
                foreach ( $field['options'] as $index => $option ) {
                    $opt_value  = $option['value'];
                    $opt_label  = $option['label'];
                    $opt_checked = in_array($opt_value, $meta) ? ' checked="checked"' : '';

                    $opt_id   = isset( $option['id'] ) ? ' '.$option['id'] : $id . '_' . $index;
                    $opt_value_for = ' for="' . $opt_id . '"';
                    $opt_class  = isset( $option['class'] ) ? ' class="'.$option['class'].'"' : '';

                    echo '<label' . $opt_value_for . $opt_class . '>';
                    echo '<input type="checkbox" id="' . $opt_id . '" name="noo_meta_boxes[' . $id . '][]" value="' . $opt_value . '" ' . $opt_checked . ' />';
                    echo ( isset( $option['label'] ) && !empty( $option['label'] ) ? esc_html( $option['label'] ) : '' );
                    echo '</label>';
                    echo '<br/>';
                }
            }


            break;
        case 'label':
            $value = empty($meta) && ($std != null && $std != '') ? $std : $meta;
            echo '<label id=' . $id . ' >' . $value . '</label>';
            break;

        case 'page_layout':
            $post_layout = get_theme_mod('noo_blog_post_layout', 'same_as_blog');
            $sidebar = '';
            if ($post_layout == 'same_as_blog') {
                $post_layout = get_theme_mod('noo_blog_layout', 'sidebar');
                $sidebar = get_theme_mod('noo_blog_sidebar', 'sidebar-main');
            } else {
                $sidebar = get_theme_mod('noo_blog_post_sidebar', 'sidebar-main');
            }

            $post_layout_text = '';
            switch ($post_layout) {
                case 'fullwidth':
                    $post_layout_text = esc_html__('Full-Width Page', 'noo-landmark-core');
                    break;
                case 'sidebar':
                    $post_layout_text = esc_html__('Page With Right Sidebar', 'noo-landmark-core');
                    break;
                case 'left_sidebar':
                    $post_layout_text = esc_html__('Page With Left Sidebar', 'noo-landmark-core');
                    break;
            }

            echo '<p>' . sprintf(noo_landmark_func_kses(__('Global setting for the Layout of Single Post page is: <strong>%s</strong>', 'noo-landmark-core')), $post_layout_text) . '</p>';
            if ($post_layout != 'fullwidth') {
                $sidebar_text = noo_landmark_func_get_sidebar_name($sidebar);
                echo '<p>' . sprintf(noo_landmark_func_kses(__('And the Sidebar is: <strong>%s</strong>', 'noo-landmark-core')), $sidebar_text) . '</p>';
            }

            break;

        case 'sidebars':
            $meta = !empty($meta) ? $meta : $std;
            $widget_list = smk_get_all_sidebars();
            echo '<select name="noo_meta_boxes[' . $id . ']" >';
            foreach ($widget_list as $widget_id => $name) {
                echo '<option value="' . $widget_id . '"';
                if ($meta == $widget_id) echo ' selected="selected"';
                echo '>' . $name . '</option>';
            }
            echo '</select>';

            break;

        case 'menus':
            $meta = !empty($meta) ? $meta : $std;
            $menu_list = get_terms('nav_menu');

            echo '<select name="noo_meta_boxes[' . $id . ']" >';
            echo '	<option value="" ' . selected($meta, '', true) . '>' . esc_html__('Don\'t Need Menu', 'noo-landmark-core') . '</option>';
            foreach ($menu_list as $menu) {
                echo '<option value="' . $menu->term_id . '"';
                selected($meta, $menu->term_id, true);
                echo '>' . $menu->name . '</option>';
            }
            echo '</select>';

            break;

        case 'users':
            $meta = !empty($meta) ? $meta : $std;
            $user_list = get_users();

            echo '<select name="noo_meta_boxes[' . $id . ']" >';
            echo '	<option value="" ' . selected($meta, '', true) . '>' . esc_html__('No User', 'noo-landmark-core') . '</option>';
            foreach ($user_list as $user) {
                echo '<option value="' . $user->id . '"';
                selected($meta, $user->id, true);
                echo '>' . $user->display_name . '</option>';
            }
            echo '</select>';

            break;

        case 'pages':
            $meta = !empty($meta) ? $meta : $std;
            $dropdown = wp_dropdown_pages(
                array(
                    'name' => 'noo_meta_boxes[' . $id . ']',
                    'echo' => 0,
                    'show_option_none' => ' ',
                    'option_none_value' => '',
                    'selected' => $meta,
                )
            );

            echo $dropdown;

        case 'rev_slider':
            $rev_slider = new RevSlider();
            $sliders = $rev_slider->getArrSliders();
            echo '<select name="noo_meta_boxes[' . $id . ']">';
            echo '<option value="">' . esc_html__(' - No Slider - ', 'noo-landmark-core') . '</option>';
            foreach ($sliders as $slider) {
                echo '<option value="' . $slider->getAlias() . '"';
                if ($meta == $slider->getAlias()) echo ' selected="selected"';
                echo '>' . $slider->getTitle() . '</option>';
            }
            echo '</select>';

            break;

        case 'button_groups':
            $meta = $meta ? $meta : $std;
            echo '<ul class="noo-button-groups" id="' . $id . '">';

            foreach ($field['options'] as $index => $option) {
                $opt_value = $option['value'];
                $opt_label = $option['label'];
                $opt_checked = '';
                $opt_class_checked = '';

                if ($meta == $opt_value) $opt_checked = ' checked="checked"';
                if ($meta == $opt_value) $opt_class_checked = 'noo-button-groups-selected';

                $opt_id = isset($option['id']) ? ' ' . $option['id'] : $id . '_' . $index;
                $opt_value_for = ' for="' . $opt_id . '"';
                $opt_class = isset($option['class']) ? ' class="' . $option['class'] . '"' : '';

                $opt_id = isset($option['id']) ? ' ' . $option['id'] : $id . '_' . $index;
                echo '<li class="noo-button-groups-li ' . $opt_class_checked . '">';
                echo '<label' . $opt_value_for . $opt_class . '>';
                echo '<input id="' . $opt_id . '" type="radio" name="noo_meta_boxes[' . $id . ']" value="' . $opt_value . '" class="noo-button-groups-input"' . $opt_checked . '/>';
                echo $opt_label;
                echo '</label>';
                echo '</li>';
            }
            echo '</ul>';
            ?>
            <script>
                jQuery(document).ready(function ($) {
                    $('#<?php echo esc_attr($id); ?> li').each(function () {
                        $(this).click(function () {
                            $('.noo-button-groups-li').removeClass('noo-button-groups-selected');
                            $(this).addClass('noo-button-groups-selected');
                        })
                    })
                });
            </script>
            <?php
            break;
        case 'taxonomy':
            $meta = !empty($meta) ? $meta : $std;
            $tax_slug = isset($field['tax_slug']) ? $field['tax_slug'] : 'category';
            $multiple = isset($field['multiple']) ? $field['multiple'] : '';
            if (!empty($multiple) && $multiple == 'true') {
                $multiple = 'multiple';
            }
            $taxonomies = get_terms(
                $tax_slug,
                array(
                    'hide_empty' => false,
                )
            );
            echo '<select class="noo-taxonomy-select" ' . $multiple . ' name="noo_meta_boxes[' . $id . ']" >';
            echo '<option value="">' . esc_html__('Select', 'noo-landmark-core') . ' ' . $field['label'] . '</option>';
            foreach ($taxonomies as $taxonomy) {
                echo '<option value="' . $taxonomy->slug . '"';
                if ($meta == $taxonomy->slug) echo ' selected="selected"';
                echo '>' . $taxonomy->name . '</option>';
            }
            echo '</select>';

            ?>
            <script>
                jQuery(document).ready(function ($) {
                    $(".noo-taxonomy-select").chosen({
                    });
                });
            </script>
            <?php
            break;
        case 'ui_slider':
            $meta = !empty($meta) ? $meta : '';
            $value = $meta ? ' value="' . $meta . '"' : '';
            $data_min = ($field['options']['data_min']) ? 'data-min="'.$field['options']['data_min'].'"' : 'data-min="0"';
            $data_max = ($field['options']['data_max']) ? 'data-max="'.$field['options']['data_max'].'"' : 'data-max="100"';
            $data_step = ($field['options']['data_step']) ? 'data-step ="'.$field['options']['data_step'].'"' : 'data-step="1"';
            $max_txt = ($field['options']['data_max']) ? $field['options']['data_max'] : '100';
            ?>
            <label class="noo-control">
                <input id="noo-ui-slider-<?php echo esc_attr($id); ?>" name="noo_meta_boxes[<?php echo esc_attr($id); ?>]" type="text"
                    class="noo-slider"
                    <?php 
                        if(empty($value)) { 
                            echo 'value="'.esc_textarea($field['default']).'"'; 
                        } else {
                            echo $value;
                        } 
                        echo($data_min . ' ' . $data_max . ' ' . $data_step); 
                    ?>
               />
                <span class="noo-ui-slider-max">/<?php echo $max_txt; ?></span>
                <div style="clear: both"></div>
            </label>
            <?php
            break;
        case 'alpha_color':
            wp_enqueue_script(
                'alpha-color-picker',
                NOO_FRAMEWORK_URI . '/assets/alpha-color-picker/alpha-color-picker.js',
                array('jquery', 'wp-color-picker'),
                '1.0.0',
                true
            );
            wp_enqueue_style(
                'alpha-color-picker',
                NOO_FRAMEWORK_URI . '/assets/alpha-color-picker/alpha-color-picker.css',
                array('wp-color-picker'),
                '1.0.0'
            );
            // Process the palette
            $meta = !empty($meta) ? $meta : '';
            $value = $meta ? ' value="' . $meta . '"' : 'value="'.$std.'"';

            $palette = true;
            if ( isset($field['options']['palette']) && is_array($field['options']['palette']) ){
                $palette = implode('|', $field['options']['palette']);
            }
            // Begin the output. ?>
            <label>
                <input class="alpha-color-control" type="text" name="noo_meta_boxes[<?php echo esc_attr($id); ?>]" 
                    <?php 
                        echo $value;
                    ?>
                    data-show-opacity="true"
                    data-palette="<?php echo esc_attr($palette); ?>"
                    data-default-color="<?php echo esc_attr($field['std']); ?>" />
            </label>
            <?php
            break;
        case 'floor_plans':
            // $value = $meta ? ' value="' . $meta . '"' : '';
            // $value = empty($value) && ($std != null && $std != '') ? ' placeholder="' . $std . '"' : $value;
            // echo '<input id=' . $id . ' type="' . esc_attr( $type ) . '" name="noo_meta_boxes[' . $id . ']" ' . $value . ' />';
            $floor_plan_default = array(
                'plan_title'       => '',
                'plan_bedrooms'    => '',
                'plan_bathrooms'   => '',
                'plan_price'       => '',
                'plan_size'        => '',
                'plan_description' => '',
                'floor_plan'       => '',
            );

            $floor_plans = get_post_meta( $post->ID, 'floor_plans', true );
            $floor_plans = !empty( $floor_plans ) ? array_merge( $floor_plans ) : array_merge( array( $floor_plan_default ), array( $floor_plan_default ) );
            if ( is_array( $floor_plans ) ) :
                echo '<div id="noo-item-floor_plan_wrap-wrap">';
                    foreach ( $floor_plans as $index => $floor_plan ) :
                        $plan_title       = ( array_key_exists( 'plan_title', $floor_plan ) && $index > 0 ) ? $floor_plan['plan_title'] : '';
                        $plan_bedrooms    = ( array_key_exists( 'plan_bedrooms', $floor_plan ) && $index > 0 ) ? $floor_plan['plan_bedrooms'] : '';
                        $plan_bathrooms   = ( array_key_exists( 'plan_bathrooms', $floor_plan ) && $index > 0 ) ? $floor_plan['plan_bathrooms'] : '';
                        $plan_price       = ( array_key_exists( 'plan_price', $floor_plan ) && $index > 0 ) ? $floor_plan['plan_price'] : '';
                        $plan_size        = ( array_key_exists( 'plan_size', $floor_plan ) && $index > 0 ) ? $floor_plan['plan_size'] : '';
                        $plan_description = ( array_key_exists( 'plan_description', $floor_plan ) && $index > 0 ) ? $floor_plan['plan_description'] : '';
                        $plan_image       = ( array_key_exists( 'plan_image', $floor_plan ) && $index > 0 ) ? $floor_plan['plan_image'] : '';
                        
                        ?>
                        <div <?php echo ( $index === 0 ) ? 'id="clone_element" style="display: none;" ' : '' ?> class="noo-floor-plans-wrap noo-md-12 <?php echo ( $index > 0 ) ? ' floor-item' : '' ?>">
                            <i class="remove-floor-plan fa fa-remove <?php echo ( $index != 1 ) ? 'show-remove' : '' ?> "></i>
                            <div class="noo-form-group">
                                <label><strong><?php echo esc_html__( 'Plan Title', 'noo-landmark-core' ); ?></strong></label>
                                <div class="noo-control">
                                    <input type="text" name="floor_plans[<?php echo esc_attr( $index ) ?>][plan_title]" value="<?php echo esc_attr( $plan_title ) ?>" />
                                </div>
                            </div>
                            
                                
                            <div class="noo-form-group">
                                <label><strong><?php echo esc_html__( 'Plan Bedrooms', 'noo-landmark-core' ); ?></strong></label>
                                <div class="noo-control">
                                    <input type="text" name="floor_plans[<?php echo esc_attr( $index ) ?>][plan_bedrooms]" value="<?php echo esc_attr( $plan_bedrooms ) ?>" />
                                </div>
                            </div>

                            <div class="noo-form-group">
                                <label><strong><?php echo esc_html__( 'Plan Bathrooms', 'noo-landmark-core' ); ?></strong></label>
                                <div class="noo-control">
                                    <input type="text" name="floor_plans[<?php echo esc_attr( $index ) ?>][plan_bathrooms]" value="<?php echo esc_attr( $plan_bathrooms ) ?>" />
                                </div>
                            </div>

                            <div class="noo-form-group">
                                <label><strong><?php echo esc_html__( 'Plan Price', 'noo-landmark-core' ); ?></strong></label>
                                <div class="noo-control">
                                    <input type="text" name="floor_plans[<?php echo esc_attr( $index ) ?>][plan_price]" value="<?php echo esc_attr( $plan_price ) ?>" />
                                </div>
                            </div>

                            <div class="noo-form-group">
                                <label><strong><?php echo esc_html__( 'Plan Size', 'noo-landmark-core' ); ?></strong></label>
                                <div class="noo-control">
                                    <input type="text" name="floor_plans[<?php echo esc_attr( $index ) ?>][plan_size]" value="<?php echo esc_attr( $plan_size ) ?>" />
                                </div>
                            </div>

                            <div class="noo-form-group">
                                <label><strong><?php echo esc_html__( 'Plan Description', 'noo-landmark-core' ); ?></strong></label>
                                <div class="noo-control">
                                    <textarea name="floor_plans[<?php echo esc_attr( $index ) ?>][plan_description]"><?php echo esc_attr( $plan_description ) ?></textarea>
                                </div>
                            </div>
                            <div class="noo-form-group">
                                <label><strong><?php echo esc_html__( 'Plan Images', 'noo-landmark-core' ); ?></strong></label>
                                <div class="noo-control">
                                    <?php
                                    $output = '';
                                    if ( !empty( $plan_image ) && is_array( $plan_image ) ) {
                                        foreach ($plan_image as $image_id) {
                                            $output .= wp_get_attachment_image($image_id, 'thumbnail');
                                            $output .= '<input type="hidden" name="floor_plans[' . esc_attr( $index ) . '][plan_image][]" value="' . esc_attr( $image_id ) . '" />';
                                        }
                                    } else {
                                        $plan_image = array();
                                    }

                                    $btn_text = !empty($plan_image) ? esc_html__('Edit Gallery', 'noo-landmark-core') : esc_html__('Add Images', 'noo-landmark-core');
                                    echo '<input type="button" class="button button-primary ' . $id . '_upload" name="' . $id . '_button_upload" value="' . $btn_text . '" />';
                                    echo '<input type="button" class="button ' . $id . '_clear" name="' . $id . '_button_clear" value="' . esc_html__('Clear Gallery', 'noo-landmark-core') . '" />';
                                    echo '<div class="noo-thumb-wrapper" data-index="' . $index . '">' . $output . '</div>';
                                    ?>
                                </div>
                            </div>
                            <?php
                            /**
                             * Get info data floor plan
                             */
                            $gallery_state = empty ($plan_image) ? 'gallery-library' : 'gallery-edit';
                            $plan_image    = empty ($plan_image) ? false : true;
                            ?>
                            <span class="floor_plan_data" data-gallery-state="<?php echo esc_attr( $gallery_state ); ?>" data-plan-image="<?php echo esc_attr( $plan_image ); ?>" data-plan-id="<?php echo $id; ?>" data-index="[<?php echo esc_attr( $index ); ?>]"></span>
                        </div>
                        <?php
                    endforeach;
                    ?>
                    <div class="noo-clone-floor-plan">
                        <div class="content-clone"></div>
                        <button class="noo-button add-floor-plan" data-total="<?php echo count( $floor_plans ) ?>" data-id="<?php echo $id; ?>">
                            <?php echo esc_html__( 'Add More', 'noo-landmark-core' ) ?>
                        </button>
                    </div>
                </div>
                <?php
            endif;
            break;
    } // switch - $field['type']
}


// Save the Post Meta Boxes
function noo_save_meta_box($post_id)
{

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;

    if (!isset($_POST['noo_meta_boxes']) || !isset($_POST['noo_meta_box_nonce']) || !wp_verify_nonce($_POST['noo_meta_box_nonce'], basename(__FILE__)))
        return;

    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) return;
    } else {
        if (!current_user_can('edit_post', $post_id)) return;
    }

    foreach ($_POST['noo_meta_boxes'] as $key => $val) {
        if ( is_array( $val ) ) {
          
            $key = str_replace( array( '[', ']' ), array( '', '' ), $key );
            update_post_meta( $post_id, $key, $val );
        
        } else {

            update_post_meta($post_id, $key, stripslashes(htmlspecialchars($val)));

        }
    }

}

add_action('save_post', 'noo_save_meta_box');

if (!function_exists('noo_json_decode')) :
    function noo_json_decode($json_str = '')
    {
        if (!is_string($json_str)) return $json_str;
        $maybe_json = json_decode($json_str);
        if (empty($maybe_json) && !is_array($maybe_json)) return array($json_str);

        return $maybe_json;
    }
endif;