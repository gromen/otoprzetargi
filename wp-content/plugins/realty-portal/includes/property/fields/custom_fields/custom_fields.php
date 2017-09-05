<?php
/**
 * Field: Custom Field
 *
 * @package     Realty_Portal
 * @author      NooTeam <suppport@nootheme.com>
 * @version     1.0
 */
if ( ! function_exists( 'rp_render_custom_fields_field' ) ) :

	function rp_render_custom_fields_field( $field = array(), $value = null, $show_front_end = true ) {

		if ( empty( $field ) ) {
			return false;
		}

		$custom_fields = rp_property_render_fields();
		$prefix        = apply_filters( 'rp_property_post_type', 'rp_property' );

		foreach ( $custom_fields as $field_item ) {

			if ( empty( $field_item[ 'name' ] ) || empty( $field_item[ 'type' ] ) || ! empty( $field_item[ 'disable' ] ) ) {
				unset( $field_item );
				continue;
			}

			$name_custom_field_item  = esc_attr( $prefix . $field_item[ 'name' ] );
			$value_custom_field_item = esc_html( $field_item[ 'value' ] );

			$label_custom_field_item = '';
			if ( ! empty( $field_item[ 'label' ] ) ) {
				$label_custom_field_item = esc_html( $field_item[ 'label' ] );
			}

			$std_custom_field_item = '';
			if ( ! empty( $field_item[ 'std' ] ) ) {
				$std_custom_field_item = esc_html( $field_item[ 'std' ] );
			}

			$custom_field_items_value = '';
			if ( ! empty( $field[ 'property_id' ] ) ) {
				$custom_field_items_value = get_post_meta( $field[ 'property_id' ], $name_custom_field_item, true );
			}

			$field_item[ 'name' ]        = $name_custom_field_item;
			$field_item[ 'title' ]       = $label_custom_field_item;
			$field_item[ 'placeholder' ] = $std_custom_field_item;
			$field_item[ 'class' ]       = $field[ 'class' ];
			$field_item[ 'class_child' ] = $field[ 'class_child' ];

			if ( $field_item[ 'name' ] === 'rp_agent_email' ) {
				$field_item[ 'readonly' ] = true;
			} else {
				$field_item[ 'readonly' ] = false;
			}

			if ( in_array( $field_item[ 'type' ], rp_has_choice_field_types() ) ) {
				$list_options = explode( "\n", $field_item[ 'value' ] );

				foreach ( $list_options as $option ) {
					$field_item[ 'options' ][ sanitize_title( $option ) ] = esc_html( $option );
				}

				unset( $field_item[ 'std' ] );
			} else {
				$field_item[ 'placeholder' ] = $value_custom_field_item;
			}

			unset( $field_item[ 'label' ] );
			unset( $field_item[ 'value' ] );

			rp_create_element( $field_item, $custom_field_items_value );
		}
	}

	rp_add_custom_field_type( 'custom_fields', __( 'Custom Field', 'realty-portal' ), array( 'form' => 'rp_render_custom_fields_field' ), array(
		'can_search' => false,
		'is_system'  => true,
	) );
endif;