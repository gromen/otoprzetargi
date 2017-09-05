<?php
/**
 * Get all data search form
 *
 * @package       Realty_Portal
 * @author        NooTeam <suppport@nootheme.com>
 * @version       1.0
 */
if ( ! function_exists( 'rp_save_search_property' ) ) :

	function rp_save_search_property( $REQUEST, $position_item = false ) {

		if ( is_array( $REQUEST ) ) {

			$search_parameters = $min_price = $max_price = $min_area = $max_area = '';
			if ( isset( $REQUEST[ 'types' ] ) && ! empty( $REQUEST[ 'types' ] ) ) {
				$types = get_term_by( 'id', absint( $REQUEST[ 'types' ] ), apply_filters( 'rp_property_listing_type', 'listing_type' ) );
				if ( ! is_wp_error( $types ) ) {
					$search_parameters .= $types->name . ', ';
				}
			}
			if ( isset( $REQUEST[ 'rp_property_bedrooms' ] ) && ! empty( $REQUEST[ 'rp_property_bedrooms' ] ) ) {
				$search_parameters .= $REQUEST[ 'rp_property_bedrooms' ] . ' ' . esc_html__( 'Bedrooms', 'realty-portal' ) . ', ';
			}
			if ( isset( $REQUEST[ 'rp_property_bathrooms' ] ) && ! empty( $REQUEST[ 'rp_property_bathrooms' ] ) ) {
				$search_parameters .= $REQUEST[ 'rp_property_bathrooms' ] . ' ' . esc_html__( 'Bathrooms', 'realty-portal' ) . ', ';
			}
			if ( isset( $REQUEST[ 'offers' ] ) && ! empty( $REQUEST[ 'offers' ] ) ) {
				$offers = get_term_by( 'id', absint( $REQUEST[ 'offers' ] ), 'listing_offers' );
				if ( ! is_wp_error( $offers ) ) {
					$search_parameters .= $offers->name . ', ';
				}
			}
			if ( isset( $REQUEST[ 'location' ] ) && ! empty( $REQUEST[ 'location' ] ) ) {
				$search_parameters .= esc_html__( 'in', 'realty-portal' ) . ' ' . $REQUEST[ 'location' ] . ', ';
			}
			if ( isset( $REQUEST[ 'rp_property_area' ] ) && ! empty( $REQUEST[ 'rp_property_area' ] ) ) {
				$search_parameters .= $REQUEST[ 'rp_property_area' ] . ', ';
			}
			if ( isset( $REQUEST[ 'keyword' ] ) && ! empty( $REQUEST[ 'keyword' ] ) ) {
				$search_parameters .= $REQUEST[ 'keyword' ] . ', ';
			}
			if ( isset( $REQUEST[ 'min_price' ] ) && ! empty( $REQUEST[ 'min_price' ] ) ) {
				$min_price = $REQUEST[ 'min_price' ];
			}
			if ( isset( $REQUEST[ 'max_price' ] ) && ! empty( $REQUEST[ 'max_price' ] ) ) {
				$max_price = $REQUEST[ 'max_price' ];
			}
			if ( isset( $REQUEST[ 'min_area' ] ) && ! empty( $REQUEST[ 'min_area' ] ) ) {
				$min_area = $REQUEST[ 'min_area' ];
			}
			if ( isset( $REQUEST[ 'max_area' ] ) && ! empty( $REQUEST[ 'max_area' ] ) ) {
				$max_area = $REQUEST[ 'max_area' ];
			}

			if ( ! empty( $min_price ) && ! empty( $max_price ) ) {
				$search_parameters .= esc_html__( 'From', 'realty-portal' ) . ' ' . rp_format_price( $min_price ) . ' ' . esc_html__( 'to', 'realty-portal' ) . ' ' . rp_format_price( $max_price ) . ', ';
			}
			if ( ! empty( $min_area ) && ! empty( $max_area ) ) {
				$search_parameters .= esc_html__( 'Area', 'realty-portal' ) . ' ' . rp_format_area( $min_area ) . ' ' . esc_html__( 'to', 'realty-portal' ) . ' ' . rp_format_area( $max_area );
			}
			if ( ! empty( $position_item ) ) :
				?>
				<div class="rp-saved-search-item">
					<div class="remove-search" data-position_item="<?php echo esc_attr( $position_item ); ?>">
						<i class="rp-icon-remove"></i>
					</div>
					<div class="rp-content">
						<h3>
							<?php echo esc_html__( 'Search Parameters:', 'realty-portal' ); ?>
						</h3>
						<div class="content">
							<?php echo wp_kses( $search_parameters, rp_allowed_html() ); ?>
						</div>
					</div>
					<button class="rp-button" onclick="window.location.href='<?php echo esc_url( add_query_arg( $REQUEST, RP_Member::get_url_search() ) ) ?>'"><?php echo esc_html__( 'Search', 'realty-portal' ); ?></button>
				</div>
			<?php else : ?>
				<div class="rp-box-results-search">
					<div class="text">
						<i class="rp-icon-ion-ios-search-strong"></i>
						<?php echo wp_kses( $search_parameters, rp_allowed_html() ); ?>
					</div>
					<span class="save" data-search='<?php echo json_encode( $REQUEST ); ?>'>
	            		<i class="rp-icon-ion-ios-download-outline"></i>
						<?php echo esc_html__( 'Save', 'realty-portal' ); ?>
	            	</span>
				</div>
				<?php
			endif;
		}
	}

endif;

/**
 * Process ajax save search
 *
 * @package       Realty_Portal
 * @author        NooTeam <suppport@nootheme.com>
 * @version       1.0
 */

if ( ! function_exists( 'rp_save_search_property_ajax' ) ) :

	function rp_save_search_property_ajax() {

		/**
		 * Check security
		 */
		check_ajax_referer( 'rp-property', 'security', esc_html__( 'Security Breach! Please contact admin!', 'realty-portal' ) );

		/**
		 * Validate $_POST
		 */
		$_POST = wp_kses_post_deep( $_POST );

		$user_id = rp_get_current_user( true );

		$list_search = get_user_meta( $user_id, 'list_search', true );
		$list_search = ! empty( $list_search ) ? $list_search : array();

		$list_search_new = array_merge( $list_search, array( $_POST[ 'results' ] ) );

		update_user_meta( $user_id, 'list_search', $list_search_new );

		$response[ 'status' ] = 'success';
		$response[ 'msg' ]    = esc_html__( 'Add new item saved search success', 'realty-portal' );

		wp_send_json( $response );
	}

	add_action( 'wp_ajax_rp_save_search', 'rp_save_search_property_ajax' );
	add_action( 'wp_ajax_nopriv_rp_save_search', 'rp_save_search_property_ajax' );

endif;

/**
 * Process ajax remove save search
 *
 * @package       Realty_Portal
 * @author        NooTeam <suppport@nootheme.com>
 * @version       1.0
 */

if ( ! function_exists( 'rp_remove_save_search_property_ajax' ) ) :

	function rp_remove_save_search_property_ajax() {

		/**
		 * Check security
		 */
		check_ajax_referer( 'rp-property', 'security', esc_html__( 'Security Breach! Please contact admin!', 'realty-portal' ) );

		/**
		 * Validate $_POST
		 */
		$_POST         = stripslashes_deep( $_POST );
		$position_item = rp_validate_data( $_POST[ 'position_item' ] );

		$user_id     = rp_get_current_user( true );
		$list_search = get_user_meta( $user_id, 'list_search', true );

		unset( $list_search[ $position_item - 1 ] );

		update_user_meta( $user_id, 'list_search', array_values( $list_search ) );

		$response[ 'status' ] = 'success';
		$response[ 'msg' ]    = esc_html__( 'Remove item saved search success', 'realty-portal' );

		wp_send_json( $response );
	}

	add_action( 'wp_ajax_rp_remove_saved_search', 'rp_remove_save_search_property_ajax' );
	add_action( 'wp_ajax_nopriv_rp_remove_saved_search', 'rp_remove_save_search_property_ajax' );

endif;