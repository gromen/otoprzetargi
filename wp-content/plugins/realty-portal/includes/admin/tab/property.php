<?php
/**
 * Show html setting property
 *
 * @author  NooTeam <suppport@nootheme.com>
 * @verion  0.1
 */
if ( ! function_exists( 'rp_show_html_setting_property' ) ) :

	function rp_show_html_setting_property( $list_tab ) {

		$list_tab[] = array(
			'name'     => esc_html__( 'Property', 'realty-portal' ),
			'id'       => 'tab-setting-property',
			'class'    => 'active',
			'position' => 5,
		);

		$list_tab[] = array(
			'name'     => esc_html__( 'Map & Location', 'realty-portal' ),
			'id'       => 'tab-setting-map-location',
			'position' => 20,
		);

		$list_tab[] = array(
			'name'     => esc_html__( 'Contact & Email', 'realty-portal' ),
			'id'       => 'tab-setting-contact-email',
			'position' => 25,
		);

		return $list_tab;
	}

	add_filter( 'RP_Tab_Setting/Config', 'rp_show_html_setting_property', 5 );

endif;