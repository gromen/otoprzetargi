<?php
/**
 * Field: Icon
 *
 * @package     Custom Fields
 * @author      NooTheme
 * @version     1.0
 */

if ( ! function_exists( 'rp_render_icon_field' ) ) :
	function rp_render_icon_field( $field = array(), $value = null, $show_front_end = true ) {
		if ( empty( $field ) ) {
			return false;
		}
		wp_enqueue_script( 'rp-iconpicker' );
		?>
        <script>
			jQuery(document).ready(function ( $ ) {
				$('.rp-iconpicker-input').iconpicker({
					placement: 'top'
				});
			});
        </script>
        <div class="rp-iconpicker">
            <div class="rp-iconpicker-group">
                <input data-placement="bottomRight" class="rp-iconpicker-input" type="text" <?php echo RP_Custom_Fields::validate_field( $field ); ?> value="<?php echo esc_attr( $value ) ?>"/>
                <span class="input-group-addon"></span>
            </div>
        </div>
		<?php
	}

	rp_add_custom_field_type(
        'icon',
        __( 'Icon', 'realty-portal' ),
        array( 'form' => 'rp_render_icon_field' ),
        array( 'can_search' => false, 'is_system'  => true )
    );
endif;