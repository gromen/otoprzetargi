<?php

/**
 * Advanced Search Shortcodes
 *
 * @author        NooTheme
 * @version       0.1
 */

if ( ! class_exists( 'RP_Shortcode_Advanced_Search' ) ) :

	class RP_Shortcode_Advanced_Search extends RP_Shortcodes {

		/**
		 * RP_Shortcode_Advanced_Search constructor.
		 */
		public function __construct() {
			add_filter( 'rp_list_shortcode', 'RP_Shortcode_Advanced_Search::add_shortcode' );
		}

		/**
		 * Add shortcode
		 *
		 * @param $list_shortcode
		 *
		 * @return mixed
		 */
		public static function add_shortcode( $list_shortcode ) {
			$list_shortcode[ 'rp_advanced_search' ] = __CLASS__ . '::advanced_search';

			return $list_shortcode;
		}

		/**
		 * Get the shortcode content.
		 *
		 * @param array $atts
		 *
		 * @return string
		 */
		public static function get( $atts ) {
			return RP_Shortcodes::shortcode_wrapper( array(
				__CLASS__,
				'output',
			), $atts );
		}

		/**
		 * Output the shortcode.
		 *
		 * @param array $atts
		 */
		public static function output( $atts ) {
			$atts = shortcode_atts( array(
				'source'                => 'property',
				'show_map'              => 'yes',
				'show_controls'         => 'yes',
				'style'                 => 'style-1',
				'latitude'              => RP_Property::get_setting( 'google_map', 'latitude', '40.714398' ),
				'longitude'             => RP_Property::get_setting( 'google_map', 'longitude', '-74.005279' ),
				'zoom'                  => RP_Property::get_setting( 'google_map', 'zoom', '17' ),
				'height'                => RP_Property::get_setting( 'google_map', 'map_height', '800' ),
				'drag_map'              => 'true',
				'fitbounds'             => 'true',
				'disable_auto_complete' => RP_Property::get_setting( 'google_map', 'disable_auto_complete', false ),
				'country_restriction'   => RP_Property::get_setting( 'google_map', 'country_restriction', 'all' ),
				'location_type'         => RP_Property::get_setting( 'google_map', 'location_type', 'geocode' ),
				'option_1'              => RP_Property::get_setting( 'advanced_search', 'option_1', 'keyword' ),
				'option_2'              => RP_Property::get_setting( 'advanced_search', 'option_2', apply_filters( 'rp_property_listing_offers', 'listing_offers' ) ),
				'option_3'              => RP_Property::get_setting( 'advanced_search', 'option_3', apply_filters( 'rp_property_listing_type', 'listing_type' ) ),
				'option_4'              => RP_Property::get_setting( 'advanced_search', 'option_4', 'city' ),
				'option_5'              => RP_Property::get_setting( 'advanced_search', 'option_5', '_bedrooms' ),
				'option_6'              => RP_Property::get_setting( 'advanced_search', 'option_6', '_bathrooms' ),
				'option_7'              => RP_Property::get_setting( 'advanced_search', 'option_7', '_garages' ),
				'option_8'              => RP_Property::get_setting( 'advanced_search', 'option_8', 'price' ),
				'show_features'         => RP_Property::get_setting( 'advanced_search', 'show_features', 'true' ),
				'text_show_features'    => esc_html__( 'More Filters', 'realty-portal-advanced-search' ),
				'text_button_search'    => esc_html__( 'Search Property', 'realty-portal-advanced-search' ),
			), $atts );

			RP_Template::get_template( 'shortcode/advanced-search.php', compact( 'atts', $atts ), '', RP_ADDON_ADVANCED_SEARCH_TEMPLATES );
		}

		/**
		 * Advanced Search shortcode.
		 *
		 * @param mixed $atts
		 *
		 * @return string
		 */
		public static function advanced_search( $atts ) {
			return self::shortcode_wrapper( array(
				'RP_Shortcode_Advanced_Search',
				'output',
			), $atts );
		}

	}

	new RP_Shortcode_Advanced_Search();

endif;
