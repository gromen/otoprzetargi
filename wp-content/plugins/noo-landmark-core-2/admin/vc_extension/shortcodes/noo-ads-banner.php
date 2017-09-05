<?php
/**
 * Shortcode Visual: Noo Ads Banner
 *
 * @package     LandMark
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */
if ( !function_exists( 'noo_shortcode_ads_banner' ) ) :

    function noo_shortcode_ads_banner( $atts, $content ) {

        extract( shortcode_atts( array(
            'image'       => '',
            'button'      => ''
        ), $atts ) );

        ob_start();
        ?>
        <div class="noo-ads-banner noo-row">
            <?php
            /**
             * Check image
             */
            if ( !empty( $image ) ) {
                echo '<div class="noo-image noo-md-3 text-left">';
                echo '  <img src="' . noo_thumb_src_id( $image ) . '" alt="*" />';
                echo '</div>';
            }

            /**
             * Check content
             */
            if ( !empty( $content ) ) {
                echo '<p class="noo-content noo-md-6">';
                echo esc_html( $content );
                echo '</p>';
            }

            /**
             * Check link
             */
            if( isset( $button ) && !empty( $button )){
                $link = vc_build_link( $button );
                echo '<div class="noo-action noo-md-3 text-right">';
                echo '<a href="' . esc_url( $link['url'] ) . '" title="' . esc_html( $link['title'] ) . '"><span>' . esc_html( $link['title'] ) . '</span></a>';
                echo '</div>';
            }
            ?>
        </div>
        <?php
        $html = ob_get_contents();
        ob_end_clean();
        return $html;

    }

    add_shortcode( 'noo_ads_banner', 'noo_shortcode_ads_banner' );

endif;