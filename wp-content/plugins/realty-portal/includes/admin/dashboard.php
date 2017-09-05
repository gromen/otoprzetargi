<?php
/**
 * Layout dashboard setting
 * 
 * @author  NooTeam <suppport@nootheme.com>
 * @version 1.0
 */
wp_enqueue_style( 'qtip' );
wp_enqueue_script( 'qtip' );

/**
 * Create tab
 */
	$list_tab = apply_filters( 'RP_Tab_Setting/Config', array() );
	
	/**
	 * @hook RP_Tab_Setting_Content/Config_Before
	 *
	 */
	do_action( 'RP_Tab_Setting_Content/Config_Before', $list_tab );

	rp_tab_setting( $list_tab );
	
	/**
	 * @hook RP_Tab_Setting_Content/Config_After
	 *
	 * rp_form_property_setting - 5
	 * rp_form_advanced_search_setting - 10
	 * rp_form_google_map_setting - 15
	 * rp_form_contact_email_settings - 20
	 * rp_form_agent_settings - 25
	 */
	do_action( 'RP_Tab_Setting_Content/Config_After', $list_tab );