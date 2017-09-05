<?php
/**
 * Field: Password
 *
 * @package     Custom Fields
 * @author      NooTheme
 * @version     1.0
 */

if ( ! function_exists( 'rp_render_password_field' ) ) :
	function rp_render_password_field( $field = array(), $value = null, $show_front_end = true ) {
		if ( empty( $field ) ) {
			return false;
		}

		echo '<input type="password" ' . RP_Custom_Fields::validate_field( $field ) . ' value="' . esc_attr( $value ) . '" />';

	}

	rp_add_custom_field_type(
		'password',
		__( 'Password', 'realty-portal' ),
		array( 'form' => 'rp_render_password_field' ),
		array( 'can_search' => false, 'is_system'  => true )
	);
endif;