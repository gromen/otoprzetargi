<?php
/**
 * Custom column Listing offers
 *
 * @package       Realty_Portal
 * @author        NooTeam <suppport@nootheme.com>
 * @version       1.0
 */

if ( ! function_exists( 'rp_add_table_columns_listing_offers' ) ) :

	function rp_add_table_columns_listing_offers( $columns ) {

		unset( $columns[ 'name' ] );
		unset( $columns[ 'description' ] );
		unset( $columns[ 'slug' ] );
		unset( $columns[ 'posts' ] );

		$columns[ 'color' ]       = esc_html__( 'Color', 'realty-portal' );
		$columns[ 'name' ]        = esc_html__( 'Type', 'realty-portal' );
		$columns[ 'description' ] = esc_html__( 'Description', 'realty-portal' );
		$columns[ 'posts' ]       = esc_html__( 'Count', 'realty-portal' );

		return apply_filters( 'rp_add_table_columns_listing_offers', $columns );
	}

	add_filter( 'manage_edit-' . apply_filters( 'rp_property_listing_offers', 'listing_offers' ) . '_columns', 'rp_add_table_columns_listing_offers' );

endif;

/**
 * Show custom column to Listing offers
 *
 * @package       Realty_Portal
 * @author        NooTeam <suppport@nootheme.com>
 * @version       1.0
 */

if ( ! function_exists( 'rp_show_table_columns_listing_offers' ) ) :

	function rp_show_table_columns_listing_offers( $c, $column_name, $term_id ) {

		switch ( $column_name ) {

			case 'color':

				$color = get_term_meta( $term_id, 'color', true );
				if ( empty( $color ) ) {
					$color = '#27ae60';
				}
				echo '<span style="width: 80px; height: 25px; background: ' . $color . '; display: inline-block;"></span>';
				break;
		}
	}

	add_action( 'manage_' . apply_filters( 'rp_property_listing_offers', 'listing_offers' ) . '_custom_column', 'rp_show_table_columns_listing_offers', 10, 3 );

endif;

/**
 * Add custom field to Listing offers
 *
 * @package       Realty_Portal
 * @author        NooTeam <suppport@nootheme.com>
 * @version       1.0
 */

if ( ! function_exists( 'rp_add_field_listing_offers' ) ) :

	function rp_add_field_listing_offers() {

		/**
		 * Create element color
		 */
		$args_color = array(
			'title' => esc_html__( 'Color', 'realty-portal' ),
			'name'  => 'color',
			'type'  => 'color_picker',
		);
		rp_create_element( $args_color, '#27ae60' );
	}

	add_action( apply_filters( 'rp_property_listing_offers', 'listing_offers' ) . '_add_form_fields', 'rp_add_field_listing_offers' );

endif;

/**
 * Process data when edit custom field to Listing offers
 *
 * @package       Realty_Portal
 * @author        NooTeam <suppport@nootheme.com>
 * @version       1.0
 */

if ( ! function_exists( 'rp_edit_field_listing_offers' ) ) :

	function rp_edit_field_listing_offers( $term, $taxonomy ) {
		/**
		 * VAR
		 */
		$transient_name = 'rp_edit_field_listing_offers_' . $term->term_id;
		if ( false === ( $color = get_transient( $transient_name ) ) ) {

			$color = get_term_meta( $term->term_id, 'color', true );

			if ( empty( $color ) ) {
				$color = '#27ae60';
			}

			set_transient( $transient_name, $color, YEAR_IN_SECONDS );
		}

		$args_color = array(
			'name' => 'color',
			'type' => 'color_picker',
		);

		/**
		 * Create element color
		 */
		?>
		<tr class="form-field">
			<th scope="row" valign="top"><label><?php echo esc_html__( 'Color', 'realty-portal' ); ?></label></th>
			<td>
				<?php rp_create_element( $args_color, $color ); ?>
			</td>
		</tr>
		<?php
	}

	add_action( apply_filters( 'rp_property_listing_offers', 'listing_offers' ) . '_edit_form_fields', 'rp_edit_field_listing_offers', 10, 2 );

endif;

/**
 * Process data when save custom field to Listing offers
 *
 * @package       Realty_Portal
 * @author        NooTeam <suppport@nootheme.com>
 * @version       1.0
 */

if ( ! function_exists( 'rp_save_field_listing_offers' ) ) :

	function rp_save_field_listing_offers( $term_id, $tt_id, $taxonomy ) {

		if ( isset( $_POST[ 'color' ] ) && ! empty( $_POST[ 'color' ] ) && preg_match( '/^#[a-f0-9]{6}$/i', $_POST[ 'color' ] ) ) {

			$transient_name = 'rp_edit_field_listing_offers_' . $term_id;

			delete_transient( $transient_name );

			update_term_meta( $term_id, 'color', esc_attr( $_POST[ 'color' ] ) );
		}
	}

	add_action( 'created_term', 'rp_save_field_' . apply_filters( 'rp_property_listing_offers', 'listing_offers' ), 10, 3 );
	add_action( 'edit_term', 'rp_save_field_' . apply_filters( 'rp_property_listing_offers', 'listing_offers' ), 10, 3 );

endif;