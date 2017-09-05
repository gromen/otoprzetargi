<?php
/**
 * Noo Map.
 *
 * @package     Noo Library
 * @author      H <hungnt@vietbrain.com>
 * @version     1.0
 */

if (!function_exists('noo_shortcode_map')) {
    function noo_shortcode_map($atts, $content = null)
    {
        extract(shortcode_atts(array(
            'latitude'       =>      '',
            'longitude'      =>      '',
            'icon'           =>      'fa fa-home',
            'height'         =>      '600',
            'title'          =>      '',
            'description'    =>      '',
        ), $atts));

        $google_map_api_key = Realty_Portal::get_setting( 'google_map', 'maps_api', '' );

        wp_enqueue_style( 'google-map-icon' );
        wp_register_script( 'google-map-custom',  NOO_PLUGIN_ASSETS_URI . '/js/noo-shortcode-map.js', array( 'googleapis', 'google-map-icon', 'google-infobox' ), null, true );

        wp_localize_script( 'google-map-custom', 'NooMapVars', array(
            'ajax_url'       => admin_url( 'admin-ajax.php', 'relative' ),
            'security'       => wp_create_nonce( 'noo-google-map' ),
            'no_results'     => esc_html__( 'No results found', 'noo-landmark' ),
            'geo_fail'       => esc_html__( 'Geocoder failed due to: ', 'noo-landmark' ),
            'background_map' => get_theme_mod( 'noo_site_secondary_color', noo_get_config( 'secondary_color' ) ),
            'lat'            => floatval( Realty_Portal::get_setting( 'google_map', 'latitude', '40.714398' ) ),
            'lng'            => floatval( Realty_Portal::get_setting( 'google_map', 'longitude', '-74.005279' ) ),
            'zoom'           => absint( Realty_Portal::get_setting( 'google_map', 'zoom', '17' ) )
        ) );
        
        wp_enqueue_script( 'google-map-custom' );
        ob_start();
        ?>

        <div class="noo-box-map">
            <?php if (!empty($google_map_api_key)): ?>

                <div data-id="gmap" class="googleMap"
                    data-title="<?php echo esc_attr($title); ?>"
                    data-desc="<?php echo esc_attr($description); ?>"
                    data-icon="<?php echo esc_attr($icon); ?>"
                    data-lat="<?php echo esc_attr($latitude); ?>"
                    data-lon="<?php echo esc_attr($longitude); ?>">
                    <div id="gmap" <?php if (isset($height) && !empty($height)): ?> style="height: <?php echo esc_attr($height) . 'px'; ?>" <?php endif; ?>></div>
                </div>
                
            <?php else: ?>
                <iframe width="100%" height="<?php echo $height; ?>" frameborder="0" scrolling="no" marginheight="0"
                        marginwidth="0"
                        src="https://maps.google.com/maps?q=<?php echo $latitude; ?>,<?php echo $longitude; ?>&hl=es;z=14&amp;output=embed"></iframe>
            <?php endif; ?> 
        </div>

        <?php
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }
}
add_shortcode('noo_map', 'noo_shortcode_map');