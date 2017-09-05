<?php
/**
 * Field: Pages
 *
 * @package     Custom Fields
 * @author      NooTheme
 * @version     1.0
 */

if ( ! function_exists( 'rp_render_pages_field' ) ) :
	function rp_render_pages_field( $field = array(), $value = null, $show_front_end = true ) {
		if ( empty( $field ) ) {
			return false;
		}

		if ( ! empty( $field[ 'post_type' ] ) ) {
			$post_type = $field[ 'post_type' ];
		} else {
			$post_type = 'page';
		}

		$pages = get_pages( array( 'post_type' => $post_type ) );

		if ( ! is_wp_error( $pages ) && ! empty( $pages ) ) {

			if ( ! empty( $show_front_end ) ) {

				?>
                <select <?php echo RP_Custom_Fields::validate_field( $field ) ?>>
					<?php
					foreach ( $pages as $page ) {
						echo '<option value="' . esc_attr( $page->ID ) . '"' . selected( $value, $page->ID, true ) . '>' . esc_html( $page->post_title ) . '</option>';
					}
					?>
                </select>
				<?php
			} else {

				echo '<select id="' . esc_attr( $field[ 'name' ] ) . '" name="' . esc_attr( $field[ 'name' ] ) . '">';
				foreach ( $pages as $page ) {
					echo '<option value="' . esc_attr( $page->ID ) . '"' . selected( $value, $page->ID, true ) . '>' . esc_html( $page->post_title ) . '</option>';
				}
				echo '</select>';
				if ( ! empty( $field[ 'notice' ] ) ) {
					echo '<div class="notice">' . wp_kses( $field[ 'notice' ], rp_allowed_html() ) . '</div>';
				}
			}
		}
	}

	rp_add_custom_field_type(
        'pages',
        __( 'Pages', 'realty-portal' ),
        array( 'form' => 'rp_render_pages_field' ),
        array( 'can_search' => false, 'is_system'  => true )
    );
endif;