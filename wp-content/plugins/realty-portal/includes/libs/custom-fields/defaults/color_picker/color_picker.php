<?php
/**
 * Field: Color Picker
 *
 * @package     Custom Fields
 * @author      NooTheme
 * @version     1.0
 */

if ( ! function_exists( 'rp_render_color_picker_field' ) ) :
	function rp_render_color_picker_field( $field = array(), $value = null, $show_front_end = true ) {
		if ( empty( $field ) ) {
			return false;
		}
		wp_enqueue_script( 'wp-color-picker' );

		?>
		<input type="text" class="rp-color-picker" <?php echo RP_Custom_Fields::validate_field( $field ); ?> value="<?php echo esc_attr( $value ) ?>" />
		<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery( '.rp-color-picker' ).wpColorPicker();
			});
		</script>
		<?php
	}

	rp_add_custom_field_type( 'color_picker', __( 'Color Picker', 'realty-portal' ), array( 'form' => 'rp_render_color_picker_field' ), array(
			'can_search' => false,
			'is_system'  => true,
		) );
endif;