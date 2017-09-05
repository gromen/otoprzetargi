<?php
/**
 * Show html setting agent
 *
 * @author   NooTeam <suppport@nootheme.com>
 * @version  0.1
 */
if ( ! function_exists( 'rp_show_html_setting_custom_field_property' ) ) :

	function rp_show_html_setting_custom_field_property( $list_tab ) {

		$list_tab[] = array(
			'name'     => esc_html__( 'Property', 'realty-portal' ),
			'id'       => 'tab-setting-custom-field-property',
			'class'    => 'active',
			'position' => 5,
		);

		return $list_tab;
	}

	add_filter( 'RP_Tab_Setting/Custom_Fields', 'rp_show_html_setting_custom_field_property', 5 );

endif;