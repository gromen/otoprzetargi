<?php
if ( ! function_exists( 'rp_get_property_bed_html' ) ) :

	/**
	 * This function show bed
	 *
	 * @param $property_id
	 *
	 * @return string
	 */
	function rp_get_property_bed_html( $property_id ) {

		if ( empty( $property_id ) ) {
			global $post;
			$property_id = $post->ID;
		}

		$bed        = get_post_meta( $property_id, apply_filters( 'rp_property_post_type', 'rp_property' ) . '_bedrooms', true );
		$field_item = rp_get_property_field( '_bedrooms' );

		return empty( $bed ) ? '' : esc_html( $bed ) . ' <span class="label-bedrooms-' . esc_attr( $property_id ) . '">' . esc_html( isset( $field_item['label_translated'] ) ? esc_html( $field_item['label_translated'] ) : esc_html( $field_item['label'] )  ) . '</span>';

	}

endif;

if ( ! function_exists( 'rp_get_property_garages_html' ) ) :

	/**
	 * This function show garages
	 *
	 * @param $property_id
	 *
	 * @return string
	 */
	function rp_get_property_garages_html( $property_id ) {

		if ( empty( $property_id ) ) {
			global $post;
			$property_id = $post->ID;
		}

		$garages    = get_post_meta( $property_id, apply_filters( 'rp_property_post_type', 'rp_property' ) . '_garages', true );
		$field_item = rp_get_property_field( '_garages' );

		return empty( $garages ) ? '' : esc_html( $garages ) . ' <span class="label-garages-' . esc_attr( $property_id ) . '">' . esc_html(  isset( $field_item['label_translated'] ) ? esc_html( $field_item['label_translated'] ) : esc_html( $field_item['label'] ) ) . '</span>';

	}

endif;

if ( ! function_exists( 'rp_get_property_bath_html' ) ) :

	/**
	 * This function show bath
	 *
	 * @param $property_id
	 *
	 * @return string
	 */
	function rp_get_property_bath_html( $property_id ) {

		if ( empty( $property_id ) ) {
			global $post;
			$property_id = $post->ID;
		}

		$bath       = get_post_meta( $property_id, apply_filters( 'rp_property_post_type', 'rp_property' ) . '_bathrooms', true );
		$field_item = rp_get_property_field( '_bathrooms' );

		return empty( $bath ) ? '' : esc_html( $bath ) . ' <span class="label-bathrooms-' . esc_attr( $property_id ) . '">' . esc_html( isset( $field_item['label_translated'] ) ? esc_html( $field_item['label_translated'] ) : esc_html( $field_item['label'] ) ) . '</span>';

	}

endif;

if ( ! function_exists( 'rp_get_data_field' ) ) :

	/**
	 * This function process data custom field
	 *
	 * @param      $property_id
	 * @param      $name_field
	 * @param bool $prefix
	 *
	 * @return bool|mixed|string
	 */
	function rp_get_data_field( $property_id, $name_field, $prefix = false ) {

		if ( empty( $property_id ) ) {
			global $post;
			$property_id = $post->ID;
		}

		if ( empty( $prefix ) ) {
			if ( '_area' == $name_field ) {
				return rp_get_property_area_html( $property_id );
			} elseif ( '_bedrooms' == $name_field ) {
				return rp_get_property_bed_html( $property_id );
			} elseif ( '_garages' == $name_field ) {
				return rp_get_property_garages_html( $property_id );
			} elseif ( '_bathrooms' == $name_field ) {
				return rp_get_property_bath_html( $property_id );
			}
		}

		$data_field    = get_post_meta( $property_id, apply_filters( 'rp_property_post_type', 'rp_property' ) . $name_field, true );
		$custom_fields = rp_property_render_fields();
		unset( $custom_fields[ '' ] );
		if ( isset( $custom_fields[ $name_field ][ 'type' ] ) && 'select' == $custom_fields[ $name_field ][ 'type' ] ) {
			$data_field = rp_conver( $data_field, $custom_fields[ $name_field ][ 'value' ] );
		}

		return $data_field;
	}

endif;

if ( ! function_exists( 'rp_get_data_field_icon' ) ) :

	/**
	 * This function process data custom field icon
	 *
	 * @param $name_icon
	 *
	 * @return string|void
	 */
	function rp_get_data_field_icon( $name_icon ) {

		if ( empty( $name_icon ) ) {
			return false;
		}

		return '<i class="' . $name_icon . '"></i>';
	}

endif;
