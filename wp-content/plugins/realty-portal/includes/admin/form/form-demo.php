<?php
/**
 * Show form demo
 *
 * @author         NooTeam <suppport@nootheme.com>
 * @version        0.1
 */
if ( ! function_exists( 'rp_form_demo_tab' ) ) :

	function rp_form_demo_tab() {

		rp_render_form_setting( array(
			'title'   => esc_html__( 'Demo Form', 'realty-portal' ),
			'name'    => 'demo_form_setting',
			'id_form' => 'tab-setting-demo',
			'fields'  => array(
				array(
					'title' => esc_html__( 'Field text', 'realty-portal' ),
					'name'  => 'field_text',
					'type'  => 'text',
					'std'   => 'default',
				),
			),
		) );
	}

	add_action( 'RP_Tab_Setting_Content/Config_After', 'rp_form_demo_tab' );

endif;