<?php
/**
 * Field: Email
 *
 * @package     Custom Fields
 * @author      NooTheme
 * @version     1.0
 */

if ( ! function_exists( 'rp_render_email_field' ) ) :
	function rp_render_email_field( $field = array(), $value = null, $show_front_end = true ) {
		if ( empty( $field ) ) {
			return false;
		}

		echo '<input type="text" ' . RP_Custom_Fields::validate_field( $field ) . ' value="' . esc_attr( $value ) . '" />';

		if ( ! empty( $field[ 'notice' ] ) && empty( $show_front_end ) ) {
			echo '<p class="notice">' . wp_kses( $field[ 'notice' ], rp_allowed_html() ) . '</p>';
		}
	}

	rp_add_custom_field_type( 'email', __( 'Email', 'realty-portal' ), array( 'form' => 'rp_render_email_field' ) );
endif;