<?php
/**
 * Custom column Listing type
 *
 * @package       Realty_Portal
 * @author        NooTeam <suppport@nootheme.com>
 * @version       1.0
 */

if ( ! function_exists( 'rp_add_table_columns_listing_type' ) ) :

	function rp_add_table_columns_listing_type( $columns ) {

		unset( $columns[ 'name' ] );
		unset( $columns[ 'description' ] );
		unset( $columns[ 'slug' ] );
		unset( $columns[ 'posts' ] );

		$columns[ 'icon' ]        = esc_html__( 'Icon', 'realty-portal' );
		$columns[ 'name' ]        = esc_html__( 'Type', 'realty-portal' );
		$columns[ 'description' ] = esc_html__( 'Description', 'realty-portal' );
		$columns[ 'posts' ]       = esc_html__( 'Count', 'realty-portal' );

		return apply_filters( 'rp_add_table_columns_listing_type', $columns );
	}

	add_filter( 'manage_edit-' . apply_filters( 'rp_property_listing_type', 'listing_type' ) . '_columns', 'rp_add_table_columns_listing_type' );

endif;

/**
 * Show custom column to Listing type
 *
 * @package       Realty_Portal
 * @author        NooTeam <suppport@nootheme.com>
 * @version       1.0
 */

if ( ! function_exists( 'rp_show_table_columns_listing_type' ) ) :

	function rp_show_table_columns_listing_type( $c, $column_name, $term_id ) {

		global $post;
		switch ( $column_name ) {

			case 'icon':

				$icon = get_term_meta( $term_id, 'icon_type', true );
				if ( empty( $icon ) ) {
					$icon = 'rp-icon-home';
				}
				echo '<i class="' . esc_attr( $icon ) . '"></i>';
				break;
		}
	}

	add_action( 'manage_' . apply_filters( 'rp_property_listing_type', 'listing_type' ) . '_custom_column', 'rp_show_table_columns_listing_type', 10, 3 );

endif;

/**
 * Add custom field to Listing type
 *
 * @package       Realty_Portal
 * @author        NooTeam <suppport@nootheme.com>
 * @version       1.0
 */

if ( ! function_exists( 'rp_add_field_listing_type' ) ) :

	function rp_add_field_listing_type() {

		/**
		 * Create element icon
		 */
		$args_icon = array(
			'title' => esc_html__( 'Icon', 'realty-portal' ),
			'name'  => 'icon_type',
			'type'  => 'icon',
		);
		rp_create_element( $args_icon, 'rp-icon-home' );
	}

	add_action( apply_filters( 'rp_property_listing_type', 'listing_type' ) . '_add_form_fields', 'rp_add_field_listing_type' );

endif;

/**
 * Process data when edit custom field to Listing type
 *
 * @package       Realty_Portal
 * @author        NooTeam <suppport@nootheme.com>
 * @version       1.0
 */

if ( ! function_exists( 'rp_edit_field_listing_type' ) ) :

	function rp_edit_field_listing_type( $term, $taxonomy ) {
		/**
		 * VAR
		 */
		$transient_name = 'rp_edit_field_listing_type_' . $term->term_id;
		if ( false === ( $icon = get_transient( $transient_name ) ) ) {

			$icon = get_term_meta( $term->term_id, 'icon_type', true );

			if ( empty( $icon ) ) {
				$icon = 'rp-icon-home';
			}

			set_transient( $transient_name, $icon, YEAR_IN_SECONDS );
		}

		$args_icon = array(
			'name' => 'icon_type',
			'type' => 'icon',
		);

		/**
		 * Create element icon
		 */
		?>
		<tr class="form-field">
			<th scope="row" valign="top"><label><?php echo esc_html__( 'Icon', 'realty-portal' ); ?></label></th>
			<td>
				<?php rp_create_element( $args_icon, $icon ); ?>
			</td>
		</tr>
		<?php
	}

	add_action( apply_filters( 'rp_property_listing_type', 'listing_type' ) . '_edit_form_fields', 'rp_edit_field_listing_type', 10, 2 );

endif;

/**
 * Process data when save custom field to Listing type
 *
 * @package       Realty_Portal
 * @author        NooTeam <suppport@nootheme.com>
 * @version       1.0
 */

if ( ! function_exists( 'rp_save_field_listing_type' ) ) :

	function rp_save_field_listing_type( $term_id, $tt_id, $taxonomy ) {

		if ( isset( $_POST[ 'icon_type' ] ) ) {

			$transient_name = 'rp_edit_field_listing_type_' . $term_id;

			delete_transient( $transient_name );

			update_term_meta( $term_id, 'icon_type', esc_attr( $_POST[ 'icon_type' ] ) );
		}
	}

	add_action( 'created_term', 'rp_save_field_listing_type', 10, 3 );
	add_action( 'edit_term', 'rp_save_field_listing_type', 10, 3 );

endif;