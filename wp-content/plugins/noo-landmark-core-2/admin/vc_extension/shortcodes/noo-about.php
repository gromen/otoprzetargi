<?php
/**
 * Create shortcode: [noo_about]
 *
 * @package     Noo_LandMark_Core_2
 * @author      JAMES <luyentv@vietbrain.com>
 * @version     1.0
 */

if ( ! function_exists( 'noo_shortcode_about' ) ) :
    
    function noo_shortcode_about( $atts ){

        extract( shortcode_atts(array(
            'image' => '',
            'title' => '',
            'desc'  => ''
        ), $atts ) );

        ob_start();
        ?>
        <div class="noo-about">
            <?php
                if ( !empty( $title ) )
                    echo '<h3 class="noo-title">' . esc_html($title) . '</h3>';

                if ( !empty( $desc ) )
                    echo '<p>' . esc_html( $desc ) . '</p>';
                if ( !empty($image) )
                    echo '<img src="'.esc_attr( wp_get_attachment_url($image) ).'" alt="'.esc_attr( get_the_title($image) ).'" />';
            ?>
        </div>
        

        <?php $html = ob_get_contents();
        ob_end_clean();
        return $html;

    }

    add_shortcode( 'noo_about', 'noo_shortcode_about' );

endif;