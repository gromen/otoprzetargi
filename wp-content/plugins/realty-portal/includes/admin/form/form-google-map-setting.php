<?php
/**
 * Show form gooogle map
 *
 * @author         NooTeam <suppport@nootheme.com>
 * @version        0.1
 */
if ( ! function_exists( 'rp_form_google_map_setting' ) ) :

	function rp_form_google_map_setting() {

		rp_render_form_setting( array(
			'title'   => esc_html__( 'Google Map', 'realty-portal' ),
			'name'    => 'google_map',
			'id_form' => 'tab-setting-map-location',
			'fields'  => array(
				array(
					'is_section' => true,
					'title'      => __( 'Map Displaying', 'realty-portal' ),
				),
				array(
					'title'       => esc_html__( 'Google Maps API', 'realty-portal' ),
					'name'        => 'maps_api',
					'type'        => 'text',
					'placeholder' => esc_html__( 'Please enter your google map api', 'realty-portal' ),
					'notice'      => '<p class="rp-help"><strong>Google</strong> requires that you register an API Key to display <strong>Maps</strong> on from your website. To know how to create this application, <a href="https://nootheme.com/knowledge-base/get-google-maps-api/">click here and follow the steps</a>.</p>',
				),
				array(
					'title' => esc_html__( 'Starting Point Latitude', 'realty-portal' ),
					'name'  => 'latitude',
					'type'  => 'text',
					'std'   => '40.714398',
				),
				array(
					'title' => esc_html__( 'Starting Point Longitude', 'realty-portal' ),
					'name'  => 'longitude',
					'type'  => 'text',
					'std'   => '-74.005279',
				),
				array(
					'title' => esc_html__( 'Default Zoom Level', 'realty-portal' ),
					'name'  => 'zoom',
					'type'  => 'text',
					'std'   => '17',
				),
				array(
					'title' => esc_html__( 'Default Map Height (px)', 'realty-portal' ),
					'name'  => 'map_height',
					'type'  => 'text',
					'std'   => '800',
				),
				array(
					'title' => esc_html__( 'Automatically Fit all Properties', 'realty-portal' ),
					'name'  => 'auto_fit_property',
					'type'  => 'checkbox',
					'label' => esc_html__( 'Enable this option and all your listings will fit your map automatically.', 'realty-portal' ),
				),
				array(
					'title' => esc_html__( 'Drag Map', 'realty-portal' ),
					'name'  => 'drag_map',
					'type'  => 'checkbox',
					'label' => esc_html__( 'Tick this box to make map draggable.', 'realty-portal' ),
				),
				array(
					'name' => 'google_map_linebreak',
					'type' => 'line',
				),
				array(
					'is_section' => true,
					'title'      => esc_html__( 'Location Input', 'realty-portal' ),
				),
				array(
					'title' => esc_html__( 'Google Map Auto-Complete', 'realty-portal' ),
					'name'  => 'enable_auto_complete',
					'type'  => 'checkbox',
					'std'   => true,
					'label' => esc_html__( 'Using Auto-Complete from Google Map for your location input.', 'realty-portal' ),
				),
				array(
					'title'   => esc_html__( 'Country Restriction', 'realty-portal' ),
					'name'    => 'country_restriction',
					'type'    => 'select',
					'std'     => 'all',
					'options' => rp_get_country_ISO_code(),
					'class'   => 'enable_auto_complete',
					'notice'  => '<p>' . esc_html__( 'Select your country will limit all suggestions to your local locations. Select All to use all the countries around the world. Note: you should disable Country field if you enable this function.', 'realty-portal' ) . '</p>',
				),
				array(
					'title'   => esc_html__( 'Location Type', 'realty-portal' ),
					'name'    => 'location_type',
					'type'    => 'select',
					'std'     => 'geocode',
					'class'   => 'enable_auto_complete',
					'options' => rp_map_location_type(),
					'notice'  => '<p>' . esc_html__( 'Select the location type that matches your business.', 'realty-portal' ) . '</p>',
				),
				array(
					'title' => esc_html__( 'Address', 'realty-portal' ),
					'name'  => 'location_address',
					'type'  => 'checkbox',
					'std'   => true,
					'label' => esc_html__( 'Enable', 'realty-portal' ),
				),
				array(
					'title' => esc_html__( 'Country', 'realty-portal' ),
					'name'  => 'location_country',
					'type'  => 'checkbox',
					'std'   => true,
					'label' => esc_html__( 'Enable', 'realty-portal' ),
				),
				array(
					'title' => esc_html__( 'State', 'realty-portal' ),
					'name'  => 'location_state',
					'type'  => 'checkbox',
					'std'   => false,
					'label' => esc_html__( 'Enable', 'realty-portal' ),
				),
				array(
					'title' => esc_html__( 'City', 'realty-portal' ),
					'name'  => 'location_city',
					'type'  => 'checkbox',
					'std'   => true,
					'label' => esc_html__( 'Enable', 'realty-portal' ),
				),
				array(
					'title' => esc_html__( 'Neighborhood', 'realty-portal' ),
					'name'  => 'location_neighborhood',
					'type'  => 'checkbox',
					'std'   => true,
					'label' => esc_html__( 'Enable', 'realty-portal' ),
				),
				array(
					'title' => esc_html__( 'Zip Code', 'realty-portal' ),
					'name'  => 'location_zip',
					'type'  => 'checkbox',
					'std'   => true,
					'label' => esc_html__( 'Enable', 'realty-portal' ),
				),
				array(
					'title' => esc_html__( 'Latitude & Longitude', 'realty-portal' ),
					'name'  => 'location_latlong',
					'type'  => 'checkbox',
					'std'   => true,
					'label' => esc_html__( 'Enable', 'realty-portal' ),
				),
				array(
					'title' => esc_html__( 'Maps', 'realty-portal' ),
					'name'  => 'hide_maps',
					'type'  => 'checkbox',
					'std'   => true,
					'label' => esc_html__( 'Enable', 'realty-portal' ),
				),
			),
		) );
	}

	add_action( 'RP_Tab_Setting_Content/Config_After', 'rp_form_google_map_setting', 15 );

endif;