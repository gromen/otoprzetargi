<?php
if ( ! function_exists( 'rp_featured_amenities_default' ) ) :

	/**
	 * Set featured default
	 *
	 * @return mixed|void
	 */
	function rp_featured_amenities_default() {

		$field_default = array();

		$field_default[ '' ] = array(
			'name'  => '',
			'label' => '',
			'hide'  => true,
			'type'  => 'checkbox',
		);

		return apply_filters( 'rp_featured_amenities_default', $field_default );
	}

endif;

/**
 * Get all field featured & amenities
 *
 * @package       Realty_Portal
 * @author        NooTeam <suppport@nootheme.com>
 * @version       1.0
 */
if ( ! function_exists( 'rp_render_featured_amenities' ) ) :

	function rp_render_featured_amenities() {

		$field_default = rp_featured_amenities_default();

		$list_featured = RP_Property::get_setting( 'property_custom_featured', '', array() );

		$custom_fields = array_merge( $field_default, $list_featured );

		$custom_fields = apply_filters( 'rp_render_featured_amenities', $custom_fields );

		/**
		 * Recheck
		 */
		foreach ( $custom_fields as $key => $value ) {
			if ( ! is_array( $value ) ) {
				unset( $custom_fields[ $key ] );
			}
		}

		if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
			$wpml_prefix = empty( $wpml_prefix ) ? 'property_custom_featured_' : $wpml_prefix;
			foreach ( $custom_fields as $index => $custom_field ) {
				if ( ! is_array( $custom_field ) || empty( $custom_field[ 'name' ] ) ) {
					continue;
				}

				$custom_fields[ $index ][ 'label_translated' ] = isset( $custom_field[ 'label' ] ) ? apply_filters( 'wpml_translate_single_string', $custom_field[ 'label' ], 'RP Custom Fields', $wpml_prefix . sanitize_title( $custom_field[ 'name' ] ), apply_filters( 'wpml_current_language', null ) ) : '';
			}
		}

		return $custom_fields;
	}

endif;