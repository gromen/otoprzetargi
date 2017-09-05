<?php
/**
 * RP_Advanced_Search_Config_Dashboard_Setting Class
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'RP_Advanced_Search_Config_Dashboard_Setting' ) ) :

	class RP_Advanced_Search_Config_Dashboard_Setting {

		/**
		 *    Initialize class
		 */
		public function __construct() {
			add_filter( 'RP_Tab_Setting/Config', 'RP_Advanced_Search_Config_Dashboard_Setting::tab_setting', 20 );
			add_action( 'RP_Tab_Setting_Content/Config_After', 'RP_Advanced_Search_Config_Dashboard_Setting::form_setting', 25 );
		}

		/**
		 * Show html setting agent
		 *
		 * @param $list_tab
		 *
		 * @return array
		 */
		public static function tab_setting( $list_tab ) {

			$list_tab[] = array(
				'name'     => esc_html__( 'Advanced Search', 'realty-portal-advanced-search' ),
				'id'       => 'tab-setting-advanced-search',
				'position' => 15,
			);

			return $list_tab;
		}

		/**
		 * Show form setting
		 */
		public static function form_setting() {

			$list_custom_field = rp_property_list_custom_fields();
			rp_render_form_setting( array(
				'title'   => esc_html__( 'Search Field Position', 'realty-portal-advanced-search' ),
				'name'    => 'advanced_search',
				'id_form' => 'tab-setting-advanced-search',
				'fields'  => array(
					array(
						'title'   => esc_html__( 'Position #1', 'realty-portal-advanced-search' ),
						'name'    => 'option_1',
						'type'    => 'select',
						'std'     => 'keyword',
						'options' => $list_custom_field,
					),
					array(
						'title'   => esc_html__( 'Position #2', 'realty-portal-advanced-search' ),
						'name'    => 'option_2',
						'type'    => 'select',
						'std'     => apply_filters( 'rp_property_listing_offers', 'listing_offers' ),
						'options' => $list_custom_field,
					),
					array(
						'title'   => esc_html__( 'Position #3', 'realty-portal-advanced-search' ),
						'name'    => 'option_3',
						'type'    => 'select',
						'std'     => apply_filters( 'rp_property_listing_type', 'listing_type' ),
						'options' => $list_custom_field,
					),
					array(
						'title'   => esc_html__( 'Position #4', 'realty-portal-advanced-search' ),
						'name'    => 'option_4',
						'type'    => 'select',
						'std'     => 'city',
						'options' => $list_custom_field,
					),
					array(
						'title'   => esc_html__( 'Position #5', 'realty-portal-advanced-search' ),
						'name'    => 'option_5',
						'type'    => 'select',
						'std'     => '_bedrooms',
						'options' => $list_custom_field,
					),
					array(
						'title'   => esc_html__( 'Position #6', 'realty-portal-advanced-search' ),
						'name'    => 'option_6',
						'type'    => 'select',
						'std'     => '_bathrooms',
						'options' => $list_custom_field,
					),
					array(
						'title'   => esc_html__( 'Position #7', 'realty-portal-advanced-search' ),
						'name'    => 'option_7',
						'type'    => 'select',
						'std'     => '_garages',
						'options' => $list_custom_field,
					),
					array(
						'title'   => esc_html__( 'Position #8', 'realty-portal-advanced-search' ),
						'name'    => 'option_8',
						'type'    => 'select',
						'std'     => 'price',
						'options' => $list_custom_field,
					),
				),
			) );
		}

	}

	new RP_Advanced_Search_Config_Dashboard_Setting();

endif;