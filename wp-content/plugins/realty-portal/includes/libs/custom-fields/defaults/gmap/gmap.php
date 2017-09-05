<?php
/**
 * Field: Gmap
 *
 * @package     Custom Fields
 * @author      NooTheme
 * @version     1.0
 */

if ( ! function_exists( 'rp_render_gmap_field' ) ) :
	function rp_render_gmap_field( $field = array(), $value = null, $show_front_end = true ) {
		if ( empty( $field ) ) {
			return false;
		}

		wp_enqueue_style( 'google-map-icon' );
		wp_enqueue_script( 'google-map' );
		?>
        <div data-id="<?php echo esc_attr( $field[ 'name' ] ) ?>" class="rp-gmap rp-box-map small">
            <div id="<?php echo esc_attr( $field[ 'name' ] ) ?>" style="height: 385px;"></div>
        </div>
		<?php
	}

	rp_add_custom_field_type(
        'gmap',
        __( 'GMap', 'realty-portal' ),
        array( 'form' => 'rp_render_gmap_field' ),
        array( 'can_search' => false, 'is_system'  => true )
    );
endif;