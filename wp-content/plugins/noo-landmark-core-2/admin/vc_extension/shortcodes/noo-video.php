<?php
/**
 * Create shortcode: [noo_video]
 *
 * @package     Noo_LandMark_Core_2
 * @author      JAMES <luyentv@vietbrain.com>
 * @version     1.0
 */

if ( ! function_exists( 'noo_shortcode_video' ) ) :
    
    function noo_shortcode_video( $atts ){

        extract( shortcode_atts(array(
            'image'     => '',
            'title'     => '',
            'sub_title' => '',
            'img_video' => '',
            'url_video' => ''
        ), $atts ) );

         /**
         * Required library LightGallery
         */
        wp_enqueue_style( 'lightgallery' );
        wp_enqueue_script( 'lightgallery' );

        ob_start();
        $class = uniqid('noo-video_');
        ?>
        <div class="noo-video <?php echo esc_attr($class) ?>">
            <?php
                /**
                 * Check thumbnail
                 */
                $img_video = !empty( $img_video ) ? noo_thumb_src_id( $img_video, 'full' ) : '';
                
                echo '<a href="' . esc_attr( $url_video ) . '" title="' . esc_attr( $title ) . '" style="background-image: url('.esc_attr( $img_video ).')">';
                // echo '  <img src="' . esc_attr( $img_video ) . '" alt="' . esc_attr( $title ) . '" />';
                echo '  <span><span></span></span>';
                echo '</a>';
            ?>
        </div>

        <script type="text/javascript">
            jQuery(document).ready(function($){

                $(".<?php echo esc_attr($class) ?>").lightGallery({
                    thumbnail:true,
                    animateThumb: true,
                    showThumbByDefault: true
                }); 

            });
        </script>

        <?php $html = ob_get_contents();
        ob_end_clean();
        return $html;

    }

    add_shortcode( 'noo_video', 'noo_shortcode_video' );

endif;