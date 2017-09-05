<?php
/**
 * This function process data when submit comment form
 *
 * @package       Realty_Portal
 * @author        NooTeam <suppport@nootheme.com>
 * @version       1.0
 */

if ( ! function_exists( 'rp_submit_comment' ) ) :

	function rp_submit_comment() {

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
			$user_name   = rp_validate_data( $_POST[ 'user_name' ] );
			$user_email  = rp_validate_data( $_POST[ 'user_email' ] );
			$user_msg    = rp_validate_data( $_POST[ 'user_msg' ] );
			$rating      = rp_validate_data( $_POST[ 'rating' ], 'int' );
			$class_error = array();

			if ( empty( $user_name ) ) {
				$class_error[] = 'user_name';
				$end_process   = true;
			}

			if ( empty( $user_email ) ) {
				$class_error[] = 'user_email';
				$end_process   = true;
			}

			if ( empty( $user_msg ) ) {
				$class_error[] = 'user_msg';
				$end_process   = true;
			}

			if ( empty( $rating ) ) {
				$class_error[] = 'rating';
				$end_process   = true;
			}

			if ( ! empty( $end_process ) ) {

				$response[ 'status' ]  = 'error';
				$response[ 'message' ] = esc_html__( 'Can\'t empty field', 'realty-portal' );

				wp_send_json( $response );
			} else {

				$time = current_time( 'mysql' );

				$data = array(
					'comment_post_ID'      => $property_id,
					'comment_author'       => esc_html( $user_name ),
					'comment_author_email' => esc_attr( $user_email ),
					'comment_content'      => wp_kses( $user_msg, rp_allowed_html() ),
					'comment_date'         => $time,
					'comment_approved'     => 1,
				);

				$comment_id = wp_insert_comment( $data );

				update_comment_meta( $comment_id, 'user_rating', $rating );

				$response[ 'status' ]  = 'success';
				$response[ 'message' ] = esc_html__( 'Comment success!', 'realty-portal' );
				$response[ 'html' ]    = '<li id="rp-user-comment-' . esc_attr( $comment_id ) . '" class="rp-property-item-comment">
                    <div class="item-comment-header">
                        <div class="item-comment-header-left">
                            <h4 class="name-user-comment">
                                ' . esc_html( $user_name ) . '
                            </h4>
                            <time datetime="' . get_comment_date( 'd-m-Y', $comment_id ) . '">
                                ' . get_comment_date( 'M d, Y', $comment_id ) . '
                            </time>
                        </div>
                        <div class="item-comment-header-right">
                            <div class="rp-stars-rating">
                                <span style="width: ' . absint( $rating ) . '%"></span>
                            </div>
                        </div>
                    </div>
                    <p class="item-comment-content">
                        ' . wp_kses( $user_msg, rp_allowed_html() ) . '
                    </p>
                </li>';
			}
		} else {

			$response[ 'status' ]  = 'error';
			$response[ 'message' ] = esc_html__( 'Can\'t empty id property, please contact administration!', 'realty-portal' );
		}

		wp_send_json( $response );
	}

	add_action( 'wp_ajax_rp_submit_comment', 'rp_submit_comment' );
	add_action( 'wp_ajax_nopriv_rp_submit_comment', 'rp_submit_comment' );

endif;
