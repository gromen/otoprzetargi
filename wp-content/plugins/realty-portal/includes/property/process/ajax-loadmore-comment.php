<?php
/**
 * This function process data when loadmore comment
 *
 * @package       Realty_Portal
 * @author        NooTeam <suppport@nootheme.com>
 * @version       1.0
 */

if ( ! function_exists( 'rp_property_loadmore_comment' ) ) :

	function rp_property_loadmore_comment() {

		/**
		 * Check security
		 */
		check_ajax_referer( 'rp-rating', 'security', esc_html__( 'Security Breach! Please contact admin!', 'realty-portal' ) );

		/**
		 * Validate $_POST
		 */
		$_POST = wp_kses_post_deep( $_POST );

		/**
		 * Process data
		 */
		if ( ! empty( $_POST[ 'property_id' ] ) ) {

			$property_id = rp_validate_data( $_POST[ 'property_id' ], 'int' );

			unset( $_POST[ 'security' ] );
			unset( $_POST[ 'action' ] );
			unset( $_POST[ 'property_id' ] );

			/**
			 * VAR
			 */
			$number_comment = rp_validate_data( $_POST[ 'number_comment' ], 'int' );
			$total_comment  = rp_validate_data( $_POST[ 'total_comment' ], 'int' );
			$max_page       = rp_validate_data( $_POST[ 'max_page' ], 'int' );
			$curent_page    = rp_validate_data( $_POST[ 'curent_page' ], 'int' );

			if ( empty( $number_comment ) || empty( $total_comment ) || empty( $max_page ) || empty( $curent_page ) ) {
				$end_process = true;
			}

			if ( ! empty( $end_process ) ) {

				$response[ 'status' ] = 'error';
				$response[ 'msg' ]    = esc_html__( 'Can\'t empty field', 'realty-portal' );

				wp_send_json( $response );
			} else {

				$response[ 'end_comment' ] = false;

				if ( $max_page > 1 ) {
					$curent_page ++;
				}

				if ( $curent_page > $max_page ) {
					$response[ 'end_comment' ] = true;
				}

				if ( $max_page == 1 ) {
					$curent_page ++;
				}

				if ( empty( $response[ 'end_comment' ] ) ) {

					$args_comment  = array(
						'post_id' => $property_id,
					);
					$list_comments = get_comments( $args_comment );

					$response[ 'html' ] = wp_list_comments( array(
						'page'     => $curent_page,
						'per_page' => $number_comment,
						'callback' => 'rp_property_detail_comment',
						'echo'     => false,
					), $list_comments );

					$response[ 'curent_page' ] = $curent_page;
					$response[ 'status' ]      = 'success';
					$response[ 'msg' ]         = esc_html__( 'Loadmore comment success!', 'realty-portal' );
				} else {

					$response[ 'status' ] = 'success';
					$response[ 'msg' ]    = esc_html__( 'Load complete comment!', 'realty-portal' );
				}
			}
		} else {

			$response[ 'status' ] = 'error';
			$response[ 'msg' ]    = esc_html__( 'Can\'t empty id property, please contact administration!', 'realty-portal' );
		}

		wp_send_json( $response );
	}

	add_action( 'wp_ajax_rp_property_loadmore_comment', 'rp_property_loadmore_comment' );
	add_action( 'wp_ajax_nopriv_rp_property_loadmore_comment', 'rp_property_loadmore_comment' );

endif;
