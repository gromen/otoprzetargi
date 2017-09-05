<?php
/**
 * Support plugin Delete Me
 *
 * @package 	Realty_Portal/3rd Party
 * @author 		NooTeam <suppport@nootheme.com>
 * @version 	1.0
 */

if ( ! function_exists( 'rp_support_3rd_party_delete_me' ) ) :
	
	function rp_support_3rd_party_delete_me() {

		if( !class_exists( 'plugin_delete_me' ) ) {
			return;
		}

		echo do_shortcode( '[plugin_delete_me class="rp-button-error"/]' );

	}

	add_action( 'rp_after_list_menu_agent', 'rp_support_3rd_party_delete_me' );
	add_action( 'rp_after_list_menu_user', 'rp_support_3rd_party_delete_me' );

endif;