<?php
/**
 * Noo Information
 *
 * @package     Noo Library
 * @author      H <hungnt@vietbrain.com>
 * @version     1.0
 */

if (!function_exists('noo_shortcode_information')) {
    function noo_shortcode_information($atts, $content = null)
    {
        extract(shortcode_atts(array(
            'title'          =>      '',
            'description'    =>      '',
            'mark_item'      =>      '',
            'title_contact'  =>      '',
            'custom_form'    =>      ''
        ), $atts));


        if (isset($mark_item) && !empty($mark_item)) {
            $latlon = array();
            $new_mark_item = vc_param_group_parse_atts($mark_item);

            foreach ($new_mark_item as $item) {
                if (isset($item['icon']) && !empty($item['icon']) && isset($item['info']) && !empty($item['info'])) {
                    $latlon[] = $item['icon'] . ',' . $item['info'];
                }
            }
        }

        ob_start();
        ?>

        <div class="noo-information">
            <div class="noo-row">
                <div class="noo-md-6">
                    <?php if ( !empty( $title ) ) : ?>
                        <h3 class="noo-title"><?php echo esc_attr($title); ?></h3>
                    <?php endif; ?>
                    <?php if ( !empty( $description ) ) : ?>
                        <p><?php echo esc_attr($description); ?></p>
                    <?php endif; ?>

                    <div class="wrap-info">
                        <?php
                            if (isset($mark_item) && !empty($mark_item)) {
                                $new_mark_item = vc_param_group_parse_atts($mark_item);
                                foreach ($new_mark_item as $item) :

                                    echo '<div class="info-item">';

                                    if ( isset($item['icon']) && !empty($item['icon']) ) {
                                        echo '<span class="info-icon"><i class="'.esc_attr( $item['icon'] ).'"></i></span>';
                                    }

                                    if ( isset($item['info']) && !empty($item['info']) ) {
                                        echo '<p class="info-text">'.noo_landmark_func_html_content_filter( $item['info'] ).'</p>';
                                    }

                                    echo '</div> <!--/.info-item -->';
                                endforeach;
                            }
                        ?>

                    </div> <!--/.wrap-info -->
                </div>
                <div class="noo-md-6">
                    <?php if ( !empty( $title_contact ) ) : ?>
                        <h3 class="noo-title"><?php echo esc_attr($title_contact); ?></h3>
                    <?php endif; ?>
                    <?php echo do_shortcode('[contact-form-7 id="'.esc_attr($custom_form).'"]'); ?>
                </div>
            </div>
        </div><!--/.noo-information -->

        <?php
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }
}
add_shortcode('noo_information', 'noo_shortcode_information');