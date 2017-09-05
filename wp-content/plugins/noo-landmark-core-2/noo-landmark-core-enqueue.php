<?php
function noo_landmark_core_enqueue_script_site() {

	/**
	 * Register qtip
	 */
	wp_enqueue_style( 'qtip', NOO_ADMIN_ASSETS_URI . '/css/jquery.qtip.min.css' );
	wp_enqueue_script( 'qtip', NOO_ADMIN_ASSETS_URI . '/js/min/jquery.qtip.min.js', array( 'jquery' ), null, true );

	/**
	 * Register font
	 */
	wp_register_style( 'noo-font', NOO_PLUGIN_ASSETS_URI . '/fonts/noo-fonts/noo-font.css', array(), '1.0.0' );
	wp_enqueue_style( 'noo-font' );

	/**
	 * Enqueue library chosen
	 */
	wp_enqueue_style( 'chosen', NOO_PLUGIN_ASSETS_URI . '/vendor/chosen/chosen.css', array(), '1.6.2' );
	wp_enqueue_script( 'chosen', NOO_PLUGIN_ASSETS_URI . '/vendor/chosen/chosen.jquery.min.js', array( 'jquery' ), null, false );

	/**
	 * Enqueue library slick
	 */
	wp_register_style( 'slick', NOO_PLUGIN_ASSETS_URI . '/vendor/slick/slick.css', array(), '1.6.0' );
	if ( is_rtl() ) {
		wp_register_script( 'slick', NOO_PLUGIN_ASSETS_URI . '/vendor/slick/slick-rtl.min.js', array( 'jquery' ), null, false );
	} else {
		wp_register_script( 'slick', NOO_PLUGIN_ASSETS_URI . '/vendor/slick/slick.min.js', array( 'jquery' ), null, false );
	}
	wp_enqueue_style( 'slick' );

	if ( class_exists( 'Realty_Portal' ) ) :
		/**
		 * Google Map API
		 */
		$google_api = Realty_Portal::get_setting( 'google_map', 'maps_api', '' );

		$name_script_map = 'googleapis';
		if ( defined( 'DSIDXPRESS_PLUGIN_URL' ) ) {
			$name_script_map = 'googlemaps3';
		}

		wp_register_script( $name_script_map, 'http' . ( is_ssl() ? 's' : '' ) . '://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&libraries=places' . ( ! empty( $google_api ) ? '&key=' . $google_api : '' ), array( 'jquery' ), '1.0', false );

		wp_register_script( 'google-map-icon', NOO_PLUGIN_ASSETS_URI . '/js/map/map-icons.js', array(
			'jquery',
			$name_script_map,
		), null, true );
		wp_register_script( 'google-markerclusterer', NOO_PLUGIN_ASSETS_URI . '/js/map/markerclusterer.js', array( 'jquery' ), null, true );
		wp_register_script( 'google-infobox', NOO_PLUGIN_ASSETS_URI . '/js/map/infobox.js', array( 'jquery' ), null, true );

		wp_register_script( 'google-map', NOO_ADMIN_ASSETS_URI . '/js/google-map.js', array(
			'google-map-icon',
			'google-infobox',
		), null, true );

		wp_localize_script( 'google-map', 'Noo_Map', array(
			'ajax_url'             => admin_url( 'admin-ajax.php', 'relative' ),
			'security'             => wp_create_nonce( 'noo-google-map' ),
			'no_results'           => esc_html__( 'No results found', 'noo-landmark-core' ),
			'geo_fail'             => esc_html__( 'Geocoder failed due to: ', 'noo-landmark-core' ),
			'background_map'       => get_theme_mod( 'noo_site_secondary_color', noo_get_config( 'secondary_color' ) ),
			'lat'                  => floatval( Realty_Portal::get_setting( 'google_map', 'latitude', '40.714398' ) ),
			'lng'                  => floatval( Realty_Portal::get_setting( 'google_map', 'longitude', '-74.005279' ) ),
			'zoom'                 => absint( Realty_Portal::get_setting( 'google_map', 'zoom', '17' ) ),
			'enable_auto_complete' => Realty_Portal::get_setting( 'google_map', 'enable_auto_complete', true ),
			'country_restriction'  => Realty_Portal::get_setting( 'google_map', 'country_restriction', 'all' ),
			'location_type'        => Realty_Portal::get_setting( 'google_map', 'location_type', 'geocode' ),
		) );

		wp_register_script( 'google-map-search-property', NOO_PLUGIN_ASSETS_URI . '/js/map/search-property.js', array(
			'google-map-icon',
			'google-markerclusterer',
			'google-infobox',
		), null, true );

		$cluster_icon = Realty_Portal::get_setting( 'google_map', 'cluster_icon', '' );

		wp_localize_script( 'google-map-search-property', 'Noo_Map_Property', array(
			'ajax_url'          => admin_url( 'admin-ajax.php', 'relative' ),
			'url_idx'           => home_url( '/idx/' ),
			'security'          => wp_create_nonce( 'google-map-search-property' ),
			'background_map'    => get_theme_mod( 'noo_site_secondary_color', noo_get_config( 'secondary_color' ) ),
			'background_circle' => get_theme_mod( 'noo_site_primary_color', noo_get_config( 'primary_color' ) ),
			'lat'               => floatval( Realty_Portal::get_setting( 'google_map', 'latitude', '40.714398' ) ),
			'lng'               => floatval( Realty_Portal::get_setting( 'google_map', 'longitude', '-74.005279' ) ),
			'zoom'              => absint( Realty_Portal::get_setting( 'google_map', 'zoom', '17' ) ),
			'markers'           => rp_get_list_markers(),
			'markerClusterer'   => ( ! empty( $cluster_icon ) ? noo_thumb_src_id( $cluster_icon, 'full' ) : NOO_PLUGIN_ASSETS_URI . '/images/cloud.png' ),
			'label_fullscreen'  => esc_html__( 'Fullscreen', 'noo-landmark-core' ),
			'label_default'     => esc_html__( 'Default', 'noo-landmark-core' ),
		) );

		wp_register_script( 'noo-property', NOO_PLUGIN_ASSETS_URI . '/js/noo-property.js', array(
			'jquery',
			'chosen',
			'swiper',
			'rp-property',
		), null, true );
		wp_enqueue_script( 'tiny_mce' );
		wp_enqueue_script( 'noo-property' );

		wp_localize_script( 'noo-property', 'Noo_Property', array(
			'ajax_url'            => admin_url( 'admin-ajax.php', 'relative' ),
			'security'            => wp_create_nonce( 'noo-property' ),
			'currency'            => rp_currency_symbol(),
			'currency_position'   => Realty_Portal::get_setting( 'property_setting', 'property_currency_position', 'left_space' ),
			'decimal_sep'         => Realty_Portal::get_setting( 'property_setting', 'price_decimal_sep', '.' ),
			'num_decimals'        => Realty_Portal::get_setting( 'property_setting', 'price_num_decimals', '0' ),
			'thousands_sep'       => Realty_Portal::get_setting( 'property_setting', 'price_thousand_sep', ',' ),
			'area_unit'           => Realty_Portal::get_setting( 'property_setting', 'property_area_unit', 'm2' ),
			'noo_results'         => esc_html__( 'Oops, nothing found!', 'noo-landmark-core' ),
			'button_compare'      => esc_html__( 'Compare', 'noo-landmark-core' ),
			'url_page_compare'    => get_permalink( noo_get_page_by_template( 'compare-listing.php' ) ),
			'notice_max_compare'  => esc_html__( 'The maximum number of properties compared to the main property is 4', 'noo-landmark-core' ),
			'data_autocomplete'   => rp_get_list_data_autocomplete(),
			'print_label'         => esc_html__( 'Print Me', 'noo-landmark-core' ),
			'notice_property_img' => esc_html__( 'Please select a Property Photo as featured image', 'noo-landmark-core' ),
		) );

	endif;
}

add_action( 'wp_enqueue_scripts', 'noo_landmark_core_enqueue_script_site' );

function noo_landmark_core_enqueue_script_admin() {

	/**
	 * Register font
	 */
	wp_register_style( 'noo-font', NOO_PLUGIN_ASSETS_URI . '/fonts/noo-fonts/noo-font.css', array(), '1.0.0' );
	wp_enqueue_style( 'noo-font' );

	/**
	 * Register script/style
	 */
	wp_register_style( 'qtip', NOO_ADMIN_ASSETS_URI . '/css/jquery.qtip.min.css' );
	wp_register_script( 'qtip', NOO_ADMIN_ASSETS_URI . '/js/min/jquery.qtip.min.js', array( 'jquery' ), null, true );

	/**
	 * Enqueue library chosen
	 */
	wp_register_style( 'chosen', NOO_PLUGIN_ASSETS_URI . '/vendor/chosen/chosen.css', array(), '1.6.2' );
	wp_register_script( 'chosen', NOO_PLUGIN_ASSETS_URI . '/vendor/chosen/chosen.jquery.js', array( 'jquery' ), null, false );

	/**
	 * Enqueue library chosen
	 */
	wp_register_style( 'fontawesome-iconpicker', NOO_PLUGIN_ASSETS_URI . '/vendor/fontawesome-iconpicker/fontawesome-iconpicker.min.css', array(), '1.6.2' );
	wp_register_script( 'fontawesome-iconpicker', NOO_PLUGIN_ASSETS_URI . '/vendor/fontawesome-iconpicker/fontawesome-iconpicker.js', array( 'jquery' ), null, false );

	if ( class_exists( 'Realty_Portal' ) ) :

		/**
		 * Google Map API
		 */
		$google_api      = Realty_Portal::get_setting( 'google_map', 'maps_api', '' );
		$name_script_map = 'googleapis';
		if ( defined( 'DSIDXPRESS_PLUGIN_URL' ) ) {
			$name_script_map = 'googlemaps3';
		}

		wp_register_script( $name_script_map, 'http' . ( is_ssl() ? 's' : '' ) . '://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&libraries=places' . ( ! empty( $google_api ) ? '&key=' . $google_api : '' ), array( 'jquery' ), '1.0', false );

		wp_register_script( 'google-map-icon', NOO_PLUGIN_ASSETS_URI . '/js/map/map-icons.js', array(
			'jquery',
			$name_script_map,
		), null, true );
		wp_register_script( 'google-infobox', NOO_PLUGIN_ASSETS_URI . '/js/map/infobox.js', array( 'jquery' ), null, true );

		wp_register_script( 'google-map', NOO_ADMIN_ASSETS_URI . '/js/google-map.js', array(
			'google-map-icon',
			'google-infobox',
		), null, true );

		wp_localize_script( 'google-map', 'Noo_Map', array(
			'ajax_url'             => admin_url( 'admin-ajax.php', 'relative' ),
			'security'             => wp_create_nonce( 'noo-google-map' ),
			'no_results'           => esc_html__( 'No results found', 'noo-landmark-core' ),
			'geo_fail'             => esc_html__( 'Geocoder failed due to: ', 'noo-landmark-core' ),
			'background_map'       => get_theme_mod( 'noo_site_secondary_color', noo_get_config( 'secondary_color' ) ),
			'lat'                  => floatval( Realty_Portal::get_setting( 'google_map', 'latitude', '40.714398' ) ),
			'lng'                  => floatval( Realty_Portal::get_setting( 'google_map', 'longitude', '-74.005279' ) ),
			'zoom'                 => absint( Realty_Portal::get_setting( 'google_map', 'zoom', '17' ) ),
			'enable_auto_complete' => Realty_Portal::get_setting( 'google_map', 'enable_auto_complete', true ),
			'country_restriction'  => Realty_Portal::get_setting( 'google_map', 'country_restriction', 'all' ),
			'location_type'        => Realty_Portal::get_setting( 'google_map', 'location_type', 'geocode' ),
		) );

		/**
		 * Enqueue script/style
		 */
		wp_enqueue_style( 'Noo_Property_Admin', NOO_ADMIN_ASSETS_URI . '/css/noo-property.css', array( 'thickbox' ) );
		wp_enqueue_script( 'Noo_Property_Admin', NOO_ADMIN_ASSETS_URI . '/js/noo-property.js', array(
			'jquery',
			'jquery-ui-sortable',
			'chosen',
			'thickbox',
			'media-upload',
		), null, true );

		wp_localize_script( 'Noo_Property_Admin', 'Noo_Property_Admin', array(
			'ajax_url'    => admin_url( 'admin-ajax.php', 'relative' ),
			'security'    => wp_create_nonce( 'noo-property-backend' ),
			'wait_txt'    => esc_html__( 'Please wait...', 'noo-landmark-core' ),
			'enable_txt'  => esc_html__( 'Enable', 'noo-landmark-core' ),
			'disable_txt' => esc_html__( 'Disable', 'noo-landmark-core' ),
		) );

	endif;

	wp_enqueue_style( 'font-awesome-css' );
}

add_action( 'admin_enqueue_scripts', 'noo_landmark_core_enqueue_script_admin' );