<?php
if ( ! function_exists( 'rp_property_custom_field_default' ) ) :

	/**
	 * Set custom field default
	 *
	 * @return mixed|void
	 */
	function rp_property_custom_field_default() {

		$property_area_unit = RP_Property::get_setting( 'property_setting', 'property_area_unit', 'm2' );

		$default_field = array();

		$default_field[ '' ] = array(
			'hide'     => true,
			'name'     => '',
			'label'    => '',
			'value'    => '',
			'type'     => '',
			'required' => '',
		);

		$default_field[ '_area' ] = array(
			'name'     => '_area',
			'label'    => sprintf( esc_html__( 'Area (%s)', 'realty-portal' ), esc_attr( $property_area_unit ) ),
			'value'    => '',
			'type'     => 'text',
			'required' => '',
			'readonly' => true,
			'disable'  => false,
		);

		$default_field[ '_bedrooms' ] = array(
			'name'     => '_bedrooms',
			'label'    => esc_html__( 'Bedrooms', 'realty-portal' ),
			'value'    => '',
			'type'     => 'text',
			'required' => '',
			'readonly' => true,
			'disable'  => false,
		);

		$default_field[ '_bathrooms' ] = array(
			'name'     => '_bathrooms',
			'label'    => esc_html__( 'Bathrooms', 'realty-portal' ),
			'value'    => '',
			'type'     => 'text',
			'required' => '',
			'readonly' => true,
			'disable'  => false,
		);

		$default_field[ '_garages' ] = array(
			'name'     => '_garages',
			'label'    => esc_html__( 'Garages', 'realty-portal' ),
			'value'    => '',
			'type'     => 'text',
			'required' => '',
			'readonly' => true,
			'disable'  => false,
		);

		$default_field[ '_year_built' ] = array(
			'name'     => '_year_built',
			'label'    => esc_html__( 'Year Built', 'realty-portal' ),
			'value'    => '',
			'type'     => 'text',
			'required' => '',
			'readonly' => true,
			'disable'  => false,
		);

		$default_field[ '_flooring' ] = array(
			'name'     => '_flooring',
			'label'    => esc_html__( 'Flooring', 'realty-portal' ),
			'value'    => '',
			'type'     => 'text',
			'required' => '',
			'readonly' => true,
			'disable'  => false,
		);

		return apply_filters( 'rp_property_custom_field_default', $default_field );
	}

endif;

/**
 * Render custom fields and Combine them with default fields
 *
 * @package       Realty_Portal
 * @author        NooTeam <suppport@nootheme.com>
 * @since         0.1
 */

if ( ! function_exists( 'rp_property_render_fields' ) ) :

	function rp_property_render_fields( $wpml_prefix = '' ) {

		$default_field = rp_property_custom_field_default();

		$custom_fields = RP_Property::get_setting( 'property_custom_field', '', array() );

		$custom_fields = rp_merge_custom_fields( $default_field, $custom_fields );

		$custom_fields = apply_filters( 'rp_property_render_fields', $custom_fields );

		/**
		 * Recheck
		 */
		foreach ( $custom_fields as $key => $value ) {
			if ( ! is_array( $value ) ) {
				unset( $custom_fields[ $key ] );
			}
		}

		if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
			$wpml_prefix = empty( $wpml_prefix ) ? 'property_custom_field_' : $wpml_prefix;
			foreach ( $custom_fields as $index => $custom_field ) {
				if ( ! is_array( $custom_field ) || ! isset( $custom_field[ 'name' ] ) ) {
					continue;
				}

				$custom_fields[ $index ][ 'label_translated' ] = isset( $custom_field[ 'label' ] ) ? apply_filters( 'wpml_translate_single_string', $custom_field[ 'label' ], 'RP Custom Fields', $wpml_prefix . sanitize_title( $custom_field[ 'name' ] ), apply_filters( 'wpml_current_language', null ) ) : '';
			}
		}

		return $custom_fields;
	}

endif;

/**
 * Set list custom fields property default
 *
 * @package       Realty_Portal
 * @author        NooTeam <suppport@nootheme.com>
 */
if ( ! function_exists( 'rp_list_custom_fields_property_default' ) ) :

	function rp_list_custom_fields_property_default() {

		$list_fields = array(
			array(
				'name'  => '',
				'label' => esc_html__( 'None', 'realty-portal' ),
			),
			array(
				'name'  => 'keyword',
				'label' => esc_html__( 'Keyword', 'realty-portal' ),
			),
			array(
				'name'  => 'price',
				'label' => esc_html__( 'Price', 'realty-portal' ),
			),
			array(
				'name'  => apply_filters( 'rp_property_listing_offers', 'listing_offers' ),
				'label' => esc_html__( 'Offers', 'realty-portal' ),
			),
			array(
				'name'  => apply_filters( 'rp_property_listing_type', 'listing_type' ),
				'label' => esc_html__( 'Types', 'realty-portal' ),
			),
			array(
				'name'  => 'property_country',
				'label' => esc_html__( 'Country', 'realty-portal' ),
			),
			array(
				'name'  => 'city',
				'label' => esc_html__( 'City', 'realty-portal' ),
			),
			array(
				'name'  => 'neighborhood',
				'label' => esc_html__( 'Neighborhood', 'realty-portal' ),
			),
			array(
				'name'  => 'zip',
				'label' => esc_html__( 'Zip', 'realty-portal' ),
			),
		);

		return apply_filters( 'rp_list_custom_fields_property_default', $list_fields );
	}

endif;

/**
 * Get property field
 *
 * @package       Realty_Portal
 * @author        NooTeam <suppport@nootheme.com>
 * @since         0.1
 */
if ( ! function_exists( 'rp_get_property_field' ) ) :

	function rp_get_property_field( $field_name = '' ) {

		$custom_fields = rp_property_render_fields();
		if ( isset( $custom_fields[ $field_name ] ) ) {
			return $custom_fields[ $field_name ];
		}

		$field_name = rp_property_custom_fields_name( $field_name );
		if ( isset( $custom_fields[ $field_name ] ) ) {
			return $custom_fields[ $field_name ];
		}

		return array();
	}

endif;

/**
 * Get property field name
 *
 * @package       Realty_Portal
 * @author        NooTeam <suppport@nootheme.com>
 */
if ( ! function_exists( 'rp_property_custom_fields_name' ) ) :

	function rp_property_custom_fields_name( $field_name = '' ) {
		if ( empty( $field_name ) ) {
			return '';
		}

		return apply_filters( 'rp_property_custom_fields_name', sanitize_title( $field_name ) );
	}

endif;

/**
 * Get list custom field
 *
 * @package       Realty_Portal
 * @author        NooTeam <suppport@nootheme.com>
 * @since         1.4.0
 */
if ( ! function_exists( 'rp_property_list_custom_fields' ) ) :

	function rp_property_list_custom_fields( $show = 'all' ) {
		$list_custom_field = array( 'none' => esc_html__( 'None', 'realty-portal' ) );
		/**
		 * Show list custom field default
		 */
		if ( 'all' == $show ) {
			$list_fields_default = rp_list_custom_fields_property_default();
			foreach ( $list_fields_default as $key => $val ) {

				$name  = ! empty( $list_fields_default[ $key ][ 'name' ] ) ? esc_attr( $list_fields_default[ $key ][ 'name' ] ) : '';
				$label = ! empty( $list_fields_default[ $key ][ 'label' ] ) ? esc_html( $list_fields_default[ $key ][ 'label' ] ) : '';
				if ( empty( $name ) || empty( $label ) ) {
					continue;
				}

				$list_custom_field[ $name ] = $label;
			}
		}

		/**
		 * Show list custom field
		 */
		$custom_fields = rp_property_render_fields();
		foreach ( $custom_fields as $key => $val ) {

			$name  = ! empty( $custom_fields[ $key ][ 'name' ] ) ? esc_attr( $custom_fields[ $key ][ 'name' ] ) : '';
			$label = ! empty( $custom_fields[ $key ][ 'label' ] ) ? esc_html( $custom_fields[ $key ][ 'label' ] ) : '';
			if ( empty( $name ) || empty( $label ) ) {
				continue;
			}

			$list_custom_field[ $name ] = sprintf( esc_html__( 'Custom Field: %s', 'realty-portal' ), $label );
		}

		return $list_custom_field;
	}

endif;