<?php
/**
 * Field: Property Featured
 *
 * @package     Realty_Portal
 * @author      NooTeam <suppport@nootheme.com>
 * @version     1.0
 */
if ( ! function_exists( 'rp_render_property_featured_field' ) ) :

	function rp_render_property_featured_field( $field = array(), $value = null, $show_front_end = true ) {

		if ( empty( $field ) ) {
			return false;
		}

		$property_features = rp_render_featured_amenities();
		$prefix            = apply_filters( 'rp_property_post_type', 'rp_property' );

		foreach ( $property_features as $field_property ) {

			if ( empty( $field_property[ 'name' ] ) || ! empty( $field_property[ 'disable' ] ) ) {
				unset( $field_property );
				continue;
			}
			$name_custom_field = esc_attr( $prefix . $field_property[ 'name' ] );
			$field_property[ 'name' ] = $name_custom_field;
			$custom_fields_value = '';

			if ( ! empty( $field[ 'property_id' ] ) ) {
				$custom_fields_value = get_post_meta( $field[ 'property_id' ], $field_property[ 'name' ], true );
			}
			?>
            <div class="rp-item-checkbox">
                <input id="rp-item-<?php echo esc_attr( $field_property[ 'name' ] ); ?>" type="checkbox"
                       name="<?php echo esc_attr( $field_property[ 'name' ] ); ?>"
                       value="1" <?php checked( $custom_fields_value, '1', true ); ?>/>
                <label for="rp-item-<?php echo esc_attr( $field_property[ 'name' ] ); ?>"><?php echo esc_html( $field_property[ 'label' ] ); ?></label>
            </div>
			<?php
		}
	}

	rp_add_custom_field_type( 'property_featured', __( 'Custom Field', 'realty-portal' ), array( 'form' => 'rp_render_property_featured_field' ), array(
		'can_search' => false,
		'is_system'  => true,
	) );
endif;