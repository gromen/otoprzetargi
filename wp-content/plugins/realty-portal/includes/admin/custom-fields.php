<?php
/**
 * Layout custom fields setting
 * 
 * @author  NooTeam <suppport@nootheme.com>
 * @version 1.0
 */

/**
 * Create tab
 */
	$list_tab = apply_filters( 'RP_Tab_Setting/Custom_Fields', array() );
	
	/**
	 * @hook RP_Tab_Setting_Content/Custom_Fields_Before
	 * 
	 * rp_show_html_setting_custom_field_property - 5
	 */
	do_action( 'RP_Tab_Setting_Content/Custom_Fields_Before', $list_tab );

	rp_tab_setting( $list_tab );
	
	/**
	 * @hook RP_Tab_Setting_Content/Custom_Fields_After
	 *
	 * rp_form_custom_field_property_tab - 5
	 */
	do_action( 'RP_Tab_Setting_Content/Custom_Fields_After', $list_tab );