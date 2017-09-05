<?php
/**
 * Enqueue script front-end
 *
 * @package       Realty_Portal
 * @author        NooTheme
 * @version       1.0
 */
if ( ! function_exists( 'realty_portal_script' ) ) :

	function realty_portal_script() {
		/**
		 * Register library
		 */
		wp_register_script( 'modernizr', REALTY_PORTAL_ASSETS . '/vendor/modernizr/modernizr-2.7.1.min.js', array( 'jquery' ), null, false );
		wp_enqueue_script( 'modernizr' );

		wp_register_script( 'carousel', REALTY_PORTAL_ASSETS . '/vendor/owl-carousel/owl.carousel.min.js', array( 'jquery' ), null, true );

		wp_register_script( 'lightgallery', REALTY_PORTAL_ASSETS . '/vendor/lightgallery/dist/js/lightgallery-all.js', array( 'jquery' ), null, false );
		wp_register_script( 'lightgallery_mousewheel', REALTY_PORTAL_ASSETS . '/vendor/lightgallery/lib/jquery.mousewheel.min.js', array( 'jquery' ), null, false );
		wp_register_script( 'rp-form-validator', REALTY_PORTAL_ASSETS . '/vendor/jquery.form-validator.min.js', array( 'jquery' ), null, true );

		wp_register_script( 'slick', REALTY_PORTAL_ASSETS . '/vendor/slick/slick.min.js', array( 'jquery' ), null, false );

		wp_register_script( 'notifyBar', REALTY_PORTAL_ASSETS . '/vendor/notifyBar/jquery.notifyBar.js', array( 'jquery' ), null, false );

		/**
		 * Register script core
		 */
		wp_register_script( 'rp-core', REALTY_PORTAL_ASSETS . '/js/rp-core.js', array(
			'jquery',
			'notifyBar',
			'rp-form-validator',
		), null, true );

		wp_localize_script( 'rp-core', 'RP_CORE', array(
			'ajax_url' => admin_url( 'admin-ajax.php', 'relative' ),
			'security' => wp_create_nonce( 'rp-core' ),
		) );

		/**
		 * Google Map API
		 */
		$google_api = RP_Property::get_setting( 'google_map', 'maps_api', '' );

		$name_script_map = 'googleapis';
		if ( defined( 'DSIDXPRESS_PLUGIN_URL' ) ) {
			$name_script_map = 'googlemaps3';
		}

		wp_register_script( $name_script_map, 'http' . ( is_ssl() ? 's' : '' ) . '://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&libraries=places' . ( ! empty( $google_api ) ? '&key=' . $google_api : '' ), array( 'jquery' ), '1.0', false );

		wp_register_script( 'google-map-icon', REALTY_PORTAL_ASSETS . '/js/map/map-icons.js', array(
			'jquery',
			$name_script_map,
		), null, true );
		wp_register_script( 'google-infobox', REALTY_PORTAL_ASSETS . '/js/map/infobox.js', array( 'jquery' ), null, true );

		wp_register_script( 'google-map', REALTY_PORTAL_ASSETS . '/js/google-map.js', array(
			'google-map-icon',
			'google-infobox',
		), null, true );

		wp_localize_script( 'google-map', 'RP_Map', array(
			'ajax_url'             => admin_url( 'admin-ajax.php', 'relative' ),
			'security'             => wp_create_nonce( 'rp-google-map' ),
			'no_results'           => esc_html__( 'No results found', 'realty-portal' ),
			'geo_fail'             => esc_html__( 'Geocoder failed due to: ', 'realty-portal' ),
			'lat'                  => floatval( RP_Property::get_setting( 'google_map', 'latitude', '40.714398' ) ),
			'lng'                  => floatval( RP_Property::get_setting( 'google_map', 'longitude', '-74.005279' ) ),
			'zoom'                 => absint( RP_Property::get_setting( 'google_map', 'zoom', '17' ) ),
			'enable_auto_complete' => RP_Property::get_setting( 'google_map', 'enable_auto_complete', true ),
			'country_restriction'  => RP_Property::get_setting( 'google_map', 'country_restriction', 'all' ),
			'location_type'        => RP_Property::get_setting( 'google_map', 'location_type', 'geocode' ),
		) );

		wp_register_script( 'google-map-search-property', REALTY_PORTAL_ASSETS . '/js/map/search-property.js', array(
			'google-map-icon',
			'google-infobox',
		), null, true );

		wp_localize_script( 'google-map-search-property', 'RP_Map_Property', array(
			'ajax_url'         => admin_url( 'admin-ajax.php', 'relative' ),
			'url_idx'          => home_url( '/idx/' ),
			'security'         => wp_create_nonce( 'google-map-search-property' ),
			'lat'              => floatval( RP_Property::get_setting( 'google_map', 'latitude', '40.714398' ) ),
			'lng'              => floatval( RP_Property::get_setting( 'google_map', 'longitude', '-74.005279' ) ),
			'zoom'             => absint( RP_Property::get_setting( 'google_map', 'zoom', '17' ) ),
			'markers'          => rp_get_list_markers(),
			'label_fullscreen' => esc_html__( 'Fullscreen', 'realty-portal' ),
			'label_default'    => esc_html__( 'Default', 'realty-portal' ),
		) );

		wp_enqueue_script( 'rp-property', REALTY_PORTAL_ASSETS . '/js/rp-property.js', array(
			'jquery',
			'rp-core',
			'jquery-ui-slider',
			'jquery-ui-tooltip',
			'jquery-ui-autocomplete',
			'slick',
		), null, true );

		wp_localize_script( 'rp-property', 'RP_Property', array(
			'ajax_url'            => admin_url( 'admin-ajax.php', 'relative' ),
			'security'            => wp_create_nonce( 'rp-property' ),
			'currency'            => rp_currency_symbol(),
			'currency_position'   => RP_Property::get_setting( 'property_setting', 'property_currency_position', 'left_space' ),
			'decimal_sep'         => RP_Property::get_setting( 'property_setting', 'price_decimal_sep', '.' ),
			'num_decimals'        => RP_Property::get_setting( 'property_setting', 'price_num_decimals', '0' ),
			'thousands_sep'       => RP_Property::get_setting( 'property_setting', 'price_thousand_sep', ',' ),
			'area_unit'           => RP_Property::get_setting( 'property_setting', 'property_area_unit', 'm2' ),
			'data_autocomplete'   => rp_get_list_data_autocomplete(),
			'print_label'         => esc_html__( 'Print Me', 'realty-portal' ),
			'notice_property_img' => esc_html__( 'Please select a Property Photo as featured image', 'realty-portal' ),
		) );

		/**
		 * Enqueue script rating
		 */
		wp_register_script( 'rp-rating', REALTY_PORTAL_ASSETS . '/js/rp-rating.js', array( 'jquery' ), null, true );

		wp_localize_script( 'rp-rating', 'RP_Rating', array(
			'ajax_url' => admin_url( 'admin-ajax.php', 'relative' ),
			'security' => wp_create_nonce( 'rp-rating' ),
		) );

		/**
		 * Enqueue script Upload
		 */
		wp_register_script( 'rp-upload', REALTY_PORTAL_ASSETS . '/js/rp-upload.js', array(
			'jquery',
			'plupload-all',
			'jquery-ui-sortable',
		), null, false );

		wp_localize_script( 'rp-upload', 'RPUpload', array(
			'ajax_url'             => admin_url( 'admin-ajax.php' ),
			'security'             => wp_create_nonce( 'rp-upload' ),
			'text_max_size_upload' => wp_create_nonce( 'rp-upload' ),
			'remove_image'         => esc_html__( 'Remove image', 'realty-portal' ),
			'allow_format'         => 'jpg,jpeg,gif,png',
			'flash_swf_url'        => includes_url( 'js/plupload/plupload.flash.swf' ),
			'silverlight_xap_url'  => includes_url( 'js/plupload/plupload.silverlight.xap' ),
		) );
	}

	add_action( 'wp_enqueue_scripts', 'realty_portal_script' );

endif;

/**
 * Enqueue script back-end
 *
 * @package       Realty_Portal
 * @author        NooTheme
 * @version       1.0
 */
if ( ! function_exists( 'realty_portal_script_admin' ) ) :

	function realty_portal_script_admin() {

		/**
		 * Register script
		 */
		wp_register_script( 'rp-form-validator', REALTY_PORTAL_ASSETS . '/vendor/jquery.form-validator.min.js', array( 'jquery' ), null, true );
		wp_register_script( 'rp-iconpicker', REALTY_PORTAL_ASSETS . '/js/rp-iconpicker.js', array( 'jquery' ), null, false );

		wp_register_script( 'rp-addon', REALTY_PORTAL_ASSETS . '/js/addon.js', array( 'jquery' ), null, true );
		wp_localize_script( 'rp-addon', 'RP_Addon', array(
			'ajax_url' => admin_url( 'admin-ajax.php', 'relative' ),
			'security' => wp_create_nonce( 'rp-addon' ),
		) );

		/**
		 * Google Map API
		 */
		$google_api      = RP_Property::get_setting( 'google_map', 'maps_api', '' );
		$name_script_map = 'googleapis';
		if ( defined( 'DSIDXPRESS_PLUGIN_URL' ) ) {
			$name_script_map = 'googlemaps3';
		}

		wp_register_script( $name_script_map, 'http' . ( is_ssl() ? 's' : '' ) . '://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&libraries=places' . ( ! empty( $google_api ) ? '&key=' . $google_api : '' ), array( 'jquery' ), '1.0', false );

		wp_register_script( 'google-map-icon', REALTY_PORTAL_ASSETS . '/js/map/map-icons.js', array(
			'jquery',
			$name_script_map,
		), null, true );
		wp_register_script( 'google-infobox', REALTY_PORTAL_ASSETS . '/js/map/infobox.js', array( 'jquery' ), null, true );

		wp_register_script( 'google-map', REALTY_PORTAL_ASSETS . '/js/google-map.js', array(
			'google-map-icon',
			'google-infobox',
		), null, true );

		wp_localize_script( 'google-map', 'RP_Map', array(
			'ajax_url'             => admin_url( 'admin-ajax.php', 'relative' ),
			'security'             => wp_create_nonce( 'rp-google-map' ),
			'no_results'           => esc_html__( 'No results found', 'realty-portal' ),
			'geo_fail'             => esc_html__( 'Geocoder failed due to: ', 'realty-portal' ),
			'lat'                  => floatval( RP_Property::get_setting( 'google_map', 'latitude', '40.714398' ) ),
			'lng'                  => floatval( RP_Property::get_setting( 'google_map', 'longitude', '-74.005279' ) ),
			'zoom'                 => absint( RP_Property::get_setting( 'google_map', 'zoom', '17' ) ),
			'enable_auto_complete' => RP_Property::get_setting( 'google_map', 'enable_auto_complete', true ),
			'country_restriction'  => RP_Property::get_setting( 'google_map', 'country_restriction', 'all' ),
			'location_type'        => RP_Property::get_setting( 'google_map', 'location_type', 'geocode' ),
		) );

		/**
		 * Enqueue script property
		 */
		wp_enqueue_style( 'RP_Property_Admin', REALTY_PORTAL_ASSETS . '/css/rp-property.css', array( 'thickbox' ) );
		wp_enqueue_script( 'realty-portal-admin', REALTY_PORTAL_ASSETS . '/js/admin.js', array(
			'jquery',
			'jquery-ui-sortable',
		), '0.1', false );

		wp_localize_script( 'realty-portal-admin', 'RP_Property_Admin', array(
			'ajax_url'    => admin_url( 'admin-ajax.php', 'relative' ),
			'security'    => wp_create_nonce( 'rp-property-backend' ),
			'wait_txt'    => esc_html__( 'Please wait...', 'realty-portal' ),
			'enable_txt'  => esc_html__( 'Enable', 'realty-portal' ),
			'disable_txt' => esc_html__( 'Disable', 'realty-portal' ),
		) );
	}

	add_action( 'admin_enqueue_scripts', 'realty_portal_script_admin' );

endif;

/**
 * Enqueue style front-end
 *
 * @package       Realty_Portal
 * @author        NooTheme
 * @version       1.0
 */
if ( ! function_exists( 'realty_portal_style' ) ) :

	function realty_portal_style() {

		wp_register_style( 'carousel', REALTY_PORTAL_ASSETS . '/vendor/owl-carousel/owl.carousel.css' );
		wp_register_style( 'lightgallery', REALTY_PORTAL_ASSETS . '/vendor/lightgallery/dist/css/lightgallery.css', array(), '1.2.22' );
		wp_register_style( 'slick', REALTY_PORTAL_ASSETS . '/vendor/slick/slick.css' );

		wp_enqueue_style( 'notifyBar', REALTY_PORTAL_ASSETS . '/vendor/notifyBar/jquery.notifyBar.css' );

		wp_enqueue_style( 'rpicons', REALTY_PORTAL_ASSETS . '/fonts/rpicons.css', array(), '0.1', 'all' );

		wp_enqueue_style( 'realty-portal', REALTY_PORTAL_ASSETS . '/css/realty-portal.css', array(), '0.1', 'all' );
	}

	add_action( 'wp_enqueue_scripts', 'realty_portal_style' );

endif;

/**
 * Enqueue style back-end
 *
 * @package       Realty_Portal
 * @author        NooTheme
 * @version       1.0
 */
if ( ! function_exists( 'realty_portal_style_admin' ) ) :

	function realty_portal_style_admin() {

		/**
		 * Register font
		 */
		wp_enqueue_style( 'rpicons', REALTY_PORTAL_ASSETS . '/fonts/rpicons.css', array(), '0.1', 'all' );

		/**
		 * Enqueue style
		 */
		wp_enqueue_style( 'RP_Property_Admin', REALTY_PORTAL_ASSETS . '/css/rp-property.css' );
		wp_enqueue_style( 'realty-portal-admin', REALTY_PORTAL_ASSETS . '/css/rp-admin.css', array(), '0.1', 'all' );
	}

	add_action( 'admin_enqueue_scripts', 'realty_portal_style_admin' );

endif;