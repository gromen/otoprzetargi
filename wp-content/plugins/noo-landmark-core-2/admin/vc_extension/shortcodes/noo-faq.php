<?php
/**
 * Create shortcode: [noo_featured]
 *
 * @package     Noo_LandMark_Core_2
 * @author      JAMES <luyentv@vietbrain.com>
 * @version     1.0
 */


    if( !function_exists('noo_shortcode_faq') ){
        function noo_shortcode_faq($atts){
            extract(shortcode_atts(array(
                'title'         => '',
                'sub_title'     => '',
                'noo_faq_group' =>  ''
            ),$atts));

            $noo_faq_new = vc_param_group_parse_atts( $noo_faq_group );

            ob_start();

            $class = uniqid('noo_faq_group_');

            ?>
            <div class="noo-faq">
                <?php
                    if ( !empty( $title ) ){
                        echo '<h3 class="noo-title">';
                        echo '<span class="noo-sub-title">' . esc_html( $sub_title ) . '</span>';
                        if ( !empty($title) ) {
                            $str    = explode( ' ', $title );
                            $str[0] = '<span class="first-word">' . esc_html( $str[0] ) . '</span>';
                            $arr_first = array(
                                0 => $str[0],
                                1 => $str[1],
                            );
                            $str_first = implode( '', $arr_first );
                            $str[0] = $str_first;
                            unset($str[1]);
                            $str    = implode( ' ', $str );
                        }
                        echo $str;
                        echo '</h3>';
                    }

                ?>

                <div class="noo_faq_group <?php echo esc_attr($class); ?>">
                    <?php   
                    if( isset($noo_faq_new) && !empty($noo_faq_new) ):
                    foreach( $noo_faq_new as $item ){
                        ?>
                        <div class="noo_faq_item <?php echo esc_attr($item['open']); ?>">
                            <h4 class="noo_faq_control">
                                <?php
                                    echo esc_html($item['title']);
                                ?>
                            </h4>
                            <div class="noo_faq_content"><?php echo noo_landmark_func_html_content_filter( $item['description'] ); ?></div>
                        </div>
                    <?php
                    }
                    endif;
                    ?>
                </div>
            </div>
            <script>
                jQuery(document).ready(function($) {
                    $('.hide_faq .noo_faq_content').slideUp(0);
                    $('.<?php echo esc_attr($class)  ?> .noo_faq_item .noo_faq_control').click(function() {
                        var $this = $(this).parent('.noo_faq_item');
                        $('.<?php echo esc_attr($class)  ?> .noo_faq_item').removeClass('open_faq').addClass('hide_faq');
                        $('.hide_faq .noo_faq_content').slideUp(300);

                        $this.removeClass('hide_faq');
                        $this.addClass('open_faq');
                        $this.find('.noo_faq_content').slideDown(300);
                    });
                });
            </script>
            <?php
            $faq = ob_get_contents();
            ob_end_clean();
            return $faq;
        }
        add_shortcode('noo_faq','noo_shortcode_faq');
    }

?>