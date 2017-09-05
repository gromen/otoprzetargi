<?php
/**
 * Field: Checkbox
 *
 * @package     Custom Fields
 * @author      NooTheme
 * @version     1.0
 */

if ( ! function_exists( 'rp_render_checkbox_field' ) ) :
	function rp_render_checkbox_field( $field = array(), $value = null, $show_front_end = true ) {
		if ( empty( $field ) ) {
			return false;
		}

		$value_field = isset( $field[ 'value' ] ) && ! empty( $field[ 'value' ] ) ? esc_attr( $field[ 'value' ] ) : '1';

		if ( ! empty( $show_front_end ) ) {

			foreach ( $field[ 'options' ] as $key => $value_option ) :
				$opt_checked = '';
				if ( ! empty( $value ) && is_array( $value ) ) {
					$opt_checked = in_array( $key, $value ) ? ' checked="checked"' : '';
				}
				?>
                <div class="rp-item-checkbox-wrap <?php echo esc_attr( $field[ 'class_child' ] ); ?>">
                    <input type="checkbox" <?php echo esc_attr( $opt_checked ); ?> <?php echo RP_Custom_Fields::validate_field( $field ) ?> value="<?php echo esc_attr( $key ); ?>"
                    />
                    <label for="rp-field-<?php echo esc_attr( $key ); ?>-label"><?php echo esc_html( $value_option ); ?></label>
                </div>
				<?php
			endforeach;
		} else {

			?>
            <input type="hidden" name="<?php echo esc_attr( $field[ 'name' ] ); ?>" value="null"/>
            <input type="checkbox" id="<?php echo esc_attr( $field[ 'name' ] ); ?>"
                   name="<?php echo esc_attr( $field[ 'name' ] ); ?>" value="<?php echo esc_attr( $value_field ) ?>" <?php checked( $value, $value_field, true ) ?> />
			<?php
			if ( ! empty( $field[ 'label' ] ) ) {
				echo '<p>' . esc_html( $field[ 'label' ] ) . '</p>';
			}
		}
	}

	rp_add_custom_field_type(
        'checkbox',
        __( 'Checkbox', 'realty-portal' ),
        array( 'form' => 'rp_render_checkbox_field' ),
        array( 'has_choice'  => true, 'is_multiple' => true )
    );
endif;
