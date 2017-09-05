<?php
/**
 * Field: Radio
 *
 * @package     Custom Fields
 * @author      NooTheme
 * @version     1.0
 */

if ( ! function_exists( 'rp_render_radio_field' ) ) :
	function rp_render_radio_field( $field = array(), $value = null, $show_front_end = true ) {
		if ( empty( $field ) ) {
			return false;
		}

		if ( ! empty( $show_front_end ) ) {

			foreach ( $field[ 'options' ] as $key => $value_option ) :
				?>
                <div class="rp-item-radio <?php echo esc_attr( $field[ 'class_child' ] ); ?>">
                    <input type="radio" <?php echo RP_Custom_Fields::validate_field( $field ) ?> value="<?php echo esc_attr( $key ); ?>" />
                    <label for="rp-field-item-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value_option ); ?></label>
                </div>
				<?php
			endforeach;

		} else {

			if ( ! empty( $field[ 'options' ] ) ) {
				echo '<div class="rp-box-item-wrap">';
				foreach ( $field[ 'options' ] as $key => $label_option ) {
					?>
                    <div class="rp-item-radio">
                        <input id="rp-item-<?php echo esc_attr( $key ); ?>" type="radio"
                               name="<?php echo esc_attr( $field[ 'name' ] ); ?>"
                               value="<?php echo esc_attr( $key ); ?>" <?php checked( $value, $key, true ) ?> />
                        <label for="rp-item-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label_option ); ?></label>
                    </div>
					<?php
				}
				echo '</div>';
			} else {

				echo '<input type="radio" id="' . esc_attr( $field[ 'name' ] ) . '" name="' . esc_attr( $field[ 'name' ] ) . '" value="' . esc_attr( $field[ 'name' ] ) . '" ' . checked( $value, '1', true ) . ' />';
			}
			if ( ! empty( $field[ 'label' ] ) ) {
				echo '<p>' . esc_html( $field[ 'label' ] ) . '</p>';
			}
		}
	}

	rp_add_custom_field_type(
        'radio',
        __( 'Radio', 'realty-portal' ),
        array( 'form' => 'rp_render_radio_field' ),
        array( 'has_choice' => true )
    );
endif;