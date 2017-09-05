<?php
/**
 * RP_Nearby_Places_Config_Dashboard_Setting Class
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'RP_Nearby_Places_Config_Dashboard_Setting' ) ) :

	class RP_Nearby_Places_Config_Dashboard_Setting {

		/**
		 *    Initialize class
		 */
		public function __construct() {
			add_filter( 'RP_Tab_Setting/Config', 'RP_Nearby_Places_Config_Dashboard_Setting::tab_setting', 25 );
			add_action( 'RP_Tab_Setting_Content/Config_After', 'RP_Nearby_Places_Config_Dashboard_Setting::form_setting', 30 );
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
				'name'     => esc_html__( 'Nearby Places', 'realty-portal-nearby-places' ),
				'id'       => 'tab-setting-nearby-places',
				'position' => 15,
			);

			return $list_tab;
		}

		/**
		 * Show form setting
		 */
		public static function form_setting() {

			rp_render_form_setting( array(
				'title'   => esc_html__( 'Search Field Position', 'realty-portal-nearby-places' ),
				'name'    => 'nearby_places',
				'id_form' => 'tab-setting-nearby-places',
				'fields'  => array(
					array(
						'name' => 'yelp_linebreak',
						'type' => 'line',
					),
					array(
						'is_section' => true,
						'title'      => __( 'Yelp', 'realty-portal-nearby-places' ),
					),
					array(
						'title' => esc_html__( 'Enable/Disable', 'realty-portal-nearby-places' ),
						'name'  => 'yelp_on',
						'type'  => 'checkbox',
						'label' => esc_html__( 'Show yelp on property detail page.', 'realty-portal-nearby-places' ),
					),
					array(
						'title'       => esc_html__( 'Consumer Key', 'realty-portal-nearby-places' ),
						'name'        => 'yelp_consumer_key',
						'type'        => 'text',
						'placeholder' => esc_html__( 'Please enter your Consumer Key', 'realty-portal-nearby-places' ),
						'notice'      => '<p class="noo-help"><strong>Yelp</strong> requires that you register an API Key to display <strong>Yelp</strong> on from your website. To know how to create this application, <a href="https://www.yelp.com/developers/api_console">click here and follow the steps</a>.</p>',
					),
					array(
						'title'       => esc_html__( 'Consumer Secret', 'realty-portal-nearby-places' ),
						'name'        => 'yelp_consumer_secret',
						'type'        => 'text',
						'placeholder' => esc_html__( 'Please enter your Consumer Secret', 'realty-portal-nearby-places' ),
					),
					array(
						'title'       => esc_html__( 'Token', 'realty-portal-nearby-places' ),
						'name'        => 'yelp_token',
						'type'        => 'text',
						'placeholder' => esc_html__( 'Please enter your Token', 'realty-portal-nearby-places' ),
					),
					array(
						'title'       => esc_html__( 'Token Secret', 'realty-portal-nearby-places' ),
						'name'        => 'yelp_token_serect',
						'type'        => 'text',
						'placeholder' => esc_html__( 'Please enter your Token Serect', 'realty-portal-nearby-places' ),
					),
					array(
						'title'   => esc_html__( 'Select Term', 'realty-portal-nearby-places' ),
						'name'    => 'yelp_term',
						'type'    => 'multiple_select',
						'std'     => array( 'multiple' => array( 'realestate' ) ),
						'options' => array(
							'active'             => esc_html__( 'Active Life', 'realty-portal-nearby-places' ),
							'arts'               => esc_html__( 'Arts & Entertainment', 'realty-portal-nearby-places' ),
							'auto'               => esc_html__( 'Automotive', 'realty-portal-nearby-places' ),
							'beautysvc'          => esc_html__( 'Beauty & Spas', 'realty-portal-nearby-places' ),
							'education'          => esc_html__( 'Education', 'realty-portal-nearby-places' ),
							'eventservices'      => esc_html__( 'Event Planning & Services', 'realty-portal-nearby-places' ),
							'financialservices'  => esc_html__( 'Financial Services', 'realty-portal-nearby-places' ),
							'food'               => esc_html__( 'Food', 'realty-portal-nearby-places' ),
							'health'             => esc_html__( 'Health & Medical', 'realty-portal-nearby-places' ),
							'homeservices'       => esc_html__( 'Home Services ', 'realty-portal-nearby-places' ),
							'hotelstravel'       => esc_html__( 'Hotels & Travel', 'realty-portal-nearby-places' ),
							'localflavor'        => esc_html__( 'Local Flavor', 'realty-portal-nearby-places' ),
							'localservices'      => esc_html__( 'Local Services', 'realty-portal-nearby-places' ),
							'massmedia'          => esc_html__( 'Mass Media', 'realty-portal-nearby-places' ),
							'nightlife'          => esc_html__( 'Nightlife', 'realty-portal-nearby-places' ),
							'pets'               => esc_html__( 'Pets', 'realty-portal-nearby-places' ),
							'professional'       => esc_html__( 'Professional Services', 'realty-portal-nearby-places' ),
							'publicservicesgovt' => esc_html__( 'Public Services & Government', 'realty-portal-nearby-places' ),
							'realestate'         => esc_html__( 'Real Estate', 'realty-portal-nearby-places' ),
							'religiousorgs'      => esc_html__( 'Religious Organizations', 'realty-portal-nearby-places' ),
							'restaurants'        => esc_html__( 'Restaurants', 'realty-portal-nearby-places' ),
							'shopping'           => esc_html__( 'Shopping', 'realty-portal-nearby-places' ),
							'transport'          => esc_html__( 'Transportation', 'realty-portal-nearby-places' ),
							'trainstations'      => esc_html__( 'Train Stations', 'realty-portal-nearby-places' ),
						),
					),
					array(
						'title'  => esc_html__( 'Result Limit', 'realty-portal-nearby-places' ),
						'name'   => 'yelp_limit',
						'notice' => '<p class="noo-help">' . esc_html__( 'Yelp result limit of Term.', 'realty-portal-nearby-places' ) . '</p>',
						'type'   => 'text',
						'std'    => 3,
					),
					array(
						'title' => esc_html__( 'Show/Hidden', 'realty-portal-nearby-places' ),
						'name'  => 'yelp_term_img',
						'type'  => 'checkbox',
						'std'   => 1,
						'label' => esc_html__( 'Show images Yelp place on property detail page.', 'realty-portal-nearby-places' ),
					),
					array(
						'title'   => esc_html__( 'Distance Unit', 'realty-portal-nearby-places' ),
						'name'    => 'yelp_unit',
						'type'    => 'select',
						'std'     => 'mile',
						'options' => array(
							'mile' => esc_html__( 'Miles', 'realty-portal-nearby-places' ),
							'kilo' => esc_html__( 'Kilometer', 'realty-portal-nearby-places' ),
						),
					),

					array(
						'name' => 'walkscore_linebreak',
						'type' => 'line',
					),
					array(
						'is_section' => true,
						'title'      => __( 'Walkscore', 'realty-portal-nearby-places' ),
					),
					array(
						'title' => esc_html__( 'Enable/Disable', 'realty-portal-nearby-places' ),
						'name'  => 'walkscore_on',
						'type'  => 'checkbox',
						'label' => esc_html__( 'Show Walkscore on property detail page.', 'realty-portal-nearby-places' ),
					),
					array(
						'title'       => esc_html__( 'Walkscore API Key', 'realty-portal-nearby-places' ),
						'name'        => 'walkscore_api_key',
						'type'        => 'text',
						'placeholder' => esc_html__( 'Please enter your Walkscore API Key', 'realty-portal-nearby-places' ),
						'notice'      => '<p class="noo-help"><strong>Walk Score</strong> requires that you register an API Key to display <strong>Walk Score</strong> on from your website. To know how to create this application, <a href="https://www.walkscore.com/professional/api.php">click here and follow the steps</a>.</p>',
					),
				),
			) );
		}

	}

	new RP_Nearby_Places_Config_Dashboard_Setting();

endif;