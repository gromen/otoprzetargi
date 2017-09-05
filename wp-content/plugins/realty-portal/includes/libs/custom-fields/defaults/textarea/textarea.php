<?php
/**
 * Field: Textarea
 *
 * @package     Custom Fields
 * @author      NooTheme
 * @version     1.0
 */

if ( ! function_exists( 'rp_render_textarea_field' ) ) :
	function rp_render_textarea_field( $field = array(), $value = null, $show_front_end = true ) {
		if ( empty( $field ) ) {
			return false;
		}
		if ( ! empty( $field[ 'support_editor' ] ) ) {
			$config_editor = ! empty( $field[ 'editor' ] ) ? $field[ 'editor' ] : '';

			if ( isset( $field[ 'validate' ] ) && ! empty( $field[ 'validate' ] ) ) {
				$config_editor['editor_class'] = 'rp-editor rp-validate';
			}
			rp_wp_editor( wp_kses_post( $value ), esc_attr( $field[ 'name' ] ), $config_editor );
		} else {
			echo '<textarea name="' . esc_attr( $field[ 'name' ] ) . '" ' . RP_Custom_Fields::validate_field( $field ) . '>' . wp_kses_post( $value ) . '</textarea>';
		}
	}

	rp_add_custom_field_type(
		'textarea',
		__( 'Textarea', 'realty-portal' ),
		array( 'form' => 'rp_render_textarea_field' ),
		array( 'can_search' => false )
	);
endif;