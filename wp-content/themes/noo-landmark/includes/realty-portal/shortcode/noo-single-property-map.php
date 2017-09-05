<?php
/**
 * Shortcode Visual: Single Property Map
 *
 * @package     LandMark
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */
if ( !function_exists( 'noo_shortcode_single_property_map' ) ) :

    function noo_shortcode_single_property_map( $atts ) {

        extract( shortcode_atts( array(
            'height'      => Realty_Portal::get_setting( 'google_map', 'map_height', '800' ),
            'property_id' => ''
        ), $atts ) );

        ob_start();
        $id_map = uniqid( 'id-map' );
        wp_enqueue_style( 'google-map-icon' );
        wp_enqueue_script( 'google-map' );

        $title       = wp_trim_words( get_the_title( $property_id ), 7 );
        $url         = get_permalink( $property_id );
        $image       = noo_thumb_src( $property_id, 'noo-property-map', '180x150' );
        $price       = get_post_meta( $property_id, 'price', true );
        $bathrooms   = get_post_meta( $property_id, 'noo_property_bathrooms', true );
        $bedrooms    = get_post_meta( $property_id, 'noo_property_bedrooms', true );
        $garages     = get_post_meta( $property_id, 'noo_property_garages', true );

        ?>
        <div class="noo-property-box noo-box-map bottom">
            <div class="noo-content-box-map">
                <div class="rp-gmap"
                    data-id="<?php echo esc_attr( $id_map ); ?>"
                    data-property_id="<?php echo esc_attr( $property_id ) ?>"
                    data-url="<?php echo esc_attr( $url ) ?>"
                    data-title="<?php echo esc_attr( $title ) ?>"
                    data-image="<?php echo esc_attr( $image ) ?>"
                    data-price_html='<?php echo rp_property_price( $property_id ) ?>'
                    data-area="<?php echo rp_get_property_area_html( $property_id ) ?>"
                    data-bathrooms="<?php echo esc_attr( $bathrooms ) ?>"
                    data-bedrooms="<?php echo esc_attr( $bedrooms ) ?>"
                    data-garages="<?php echo esc_attr( $garages ) ?>"
                >
                <div id="<?php echo esc_attr( $id_map ); ?>" style="height: <?php echo esc_attr( $height ) ?>px;"></div>
                </div>
            </div>
        </div>
        <?php
        $html = ob_get_contents();
        ob_end_clean();
        return $html;

    }

    add_shortcode( 'noo_single_property_map', 'noo_shortcode_single_property_map' );

endif;