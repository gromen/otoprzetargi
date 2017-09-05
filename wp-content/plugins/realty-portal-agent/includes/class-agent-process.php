<?php
/**
 * RP_Agent_Process Class
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'RP_Agent_Process' ) ) :

	class RP_Agent_Process {

		/**
		 *    Initialize class
		 */
		public function __construct() {
			add_action( 'wp_ajax_rp_agent_dashboard', array(
				$this,
				'rp_agent_dashboard',
			) );

			add_action( 'wp_ajax_nopriv_rp_agent_dashboard', array(
				$this,
				'rp_agent_dashboard',
			) );

			add_action( 'wp_ajax_rp_contact_agent', array(
				$this,
				'rp_contact_agent',
			) );

			add_action( 'wp_ajax_nopriv_rp_contact_agent', array(
				$this,
				'rp_contact_agent',
			) );

			add_action( 'wp_ajax_load_more_property_agent', array(
				$this,
				'load_more_property_agent',
			) );

			add_action( 'wp_ajax_nopriv_load_more_property_agent', array(
				$this,
				'load_more_property_agent',
			) );

			add_action( 'wp_ajax_rp_agent_profile', array(
				$this,
				'rp_agent_profile',
			) );

			add_action( 'wp_ajax_nopriv_rp_agent_profile', array(
				$this,
				'rp_agent_profile',
			) );

			add_action( 'wp_ajax_rp_user_profile', array(
				$this,
				'rp_user_profile',
			) );

			add_action( 'wp_ajax_nopriv_rp_user_profile', array(
				$this,
				'rp_user_profile',
			) );

			add_action( 'save_post', 'RP_Agent_Process::rp_create_agent' );

			add_action( 'save_post', 'RP_Agent_Process::rp_update_post_to_agent' );

		}

		public function rp_agent_dashboard() {

			/**
			 * Check security
			 */
			check_ajax_referer( 'rp-agent', 'security', esc_html__( 'Security Breach! Please contact admin!', 'realty-portal-agent' ) );

			/**
			 * Validate $_POST
			 */
			$_POST = wp_kses_post_deep( $_POST );

			/**
			 * Process data
			 */
			if ( ! empty( $_POST[ 'property_id' ] ) ) {

				if ( ! empty( $_POST[ 'type' ] ) ) {

					$type        = rp_validate_data( $_POST[ 'type' ] );
					$property_id = rp_validate_data( $_POST[ 'property_id' ] );

					if ( $type === 'remove' ) {
						rp_remove_post_media( $property_id );
						if ( ! wp_trash_post( $property_id ) ) {
							$response[ 'status' ]  = 'error';
							$response[ 'message' ] = esc_html__( 'Error when deleting.', 'realty-portal-agent' );
						} else {
							$response[ 'status' ]  = 'success';
							$response[ 'message' ] = esc_html__( 'Property removed successfully.', 'realty-portal-agent' );
						}
					}

					if ( $type === 'featured' ) {
						$membership_info = RP_MemberShip::get_membership_info();
						$featured_remain = absint( $membership_info[ 'data' ][ 'featured_remain' ] );
						if ( $featured_remain === 0 ) {
							$response[ 'status' ]  = 'error';
							$response[ 'message' ] = esc_html__( 'The number of added featured properties exceeded the limit. Please upgrade a new package.', 'realty-portal-agent' );
							wp_send_json( $response );
						}

						$featured = get_post_meta( $property_id, '_featured', true );

						if ( 'yes' === $featured ) {
							update_post_meta( $property_id, '_featured', 'no' );
							$response[ 'message' ] = esc_html__( 'Remove property featured success.', 'realty-portal-agent' );
						} else {
							update_post_meta( $property_id, '_featured', 'yes' );
							RP_MemberShip::decrease_featured_remain( $agent_id );
							$response[ 'message' ] = esc_html__( 'Set property featured success.', 'realty-portal-agent' );
						}

						$response[ 'status' ] = 'success';
					}

					if ( $type === 'sold' ) {
						$featured = get_post_meta( $property_id, 'stock', true );

						if ( 'available' === $featured ) {
							update_post_meta( $property_id, 'stock', 'unavailable' );
							$response[ 'message' ] = esc_html__( 'Set property unavailable success.', 'realty-portal-agent' );
						} else {
							update_post_meta( $property_id, 'stock', 'available' );
							$response[ 'message' ] = esc_html__( 'Set property available success.', 'realty-portal-agent' );
						}

						$response[ 'status' ] = 'success';
					}
				} else {

					$response[ 'status' ]  = 'error';
					$response[ 'message' ] = esc_html__( 'Can\'t support this type, please contact admin.', 'realty-portal-agent' );
				}
			} else {

				$response[ 'status' ]  = 'error';
				$response[ 'message' ] = esc_html__( 'Can\'t support this action, please contact admin.', 'realty-portal-agent' );
			}

			wp_send_json( $response );
		}

		public function rp_contact_agent() {

			/**
			 * Check security
			 */
			check_ajax_referer( 'rp-agent', 'security', esc_html__( 'Security Breach! Please contact admin!', 'realty-portal-agent' ) );

			/**
			 * Validate $_POST
			 */
			$_POST = wp_kses_post_deep( $_POST );

			/**
			 * Process data
			 */
			if ( ! empty( $_POST[ 'agent_id' ] ) ) {

				$agent_id    = isset( $_POST[ 'agent_id' ] ) ? rp_clean( $_POST[ 'agent_id' ] ) : '';
				$property_id = isset( $_POST[ 'property_id' ] ) ? rp_clean( $_POST[ 'property_id' ] ) : '';

				$is_property_contact = false;
				if ( ! empty( $property_id ) ) {
					$is_property_contact = true;
				}

				$name    = isset( $_POST[ 'name' ] ) ? rp_clean( $_POST[ 'name' ] ) : '';
				$email   = isset( $_POST[ 'email' ] ) ? rp_clean( $_POST[ 'email' ] ) : '';
				$message = isset( $_POST[ 'message' ] ) ? rp_clean( $_POST[ 'message' ] ) : '';

				if ( $agent = get_post( $agent_id ) ) {

					$blogname    = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
					$agent_email = get_post_meta( $agent_id, 'rp_agent_email', true );

					$headers = 'From: ' . $name . ' <' . $email . '>' . "\r\n";

					if ( $is_property_contact ) {
						$property_title = get_the_title( $property_id );
						$property_link  = get_permalink( $property_id );

						$email_content = sprintf( esc_html__( "%s just sent you a message via %s's page", 'realty-portal-agent' ), $name, $property_title ) . "<br/><br/>";
						$email_content .= esc_html__( "----------------------------------------------", 'realty-portal-agent' ) . "<br/><br/>";
						$email_content .= $message . "<br/><br/>";
						$email_content .= esc_html__( "----------------------------------------------", 'realty-portal-agent' ) . "<br/><br/>";
						$email_content .= sprintf( esc_html__( "You can reply to this email to respond or send email to %s", 'realty-portal-agent' ), $email ) . "<br/><br/>";
						$email_content .= sprintf( esc_html__( "Check %s's details at %s", 'realty-portal-agent' ), $property_title, $property_link ) . "<br/><br/>";
					} else {
						$agent_link = get_permalink( $agent_id );

						$email_content = sprintf( esc_html__( "%s just sent you a message via your profile", 'realty-portal-agent' ), $name ) . "<br/><br/>";
						$email_content .= esc_html__( "----------------------------------------------", 'realty-portal-agent' ) . "<br/><br/>";
						$email_content .= $message . "<br/><br/>";
						$email_content .= esc_html__( "----------------------------------------------", 'realty-portal-agent' ) . "<br/><br/>";
						$email_content .= sprintf( esc_html__( "You can reply to this email to respond or send email to %s", 'realty-portal-agent' ), $email ) . "<br/><br/>";
						$email_content .= sprintf( esc_html__( "Check your details at %s", 'realty-portal-agent' ), $agent_link ) . "<br/><br/>";
					}

					$email_content = apply_filters( 'rp_agent_contact_message', $email_content, $agent_id, $name, $email, $message );

					do_action( 'before_rp_agent_contact_send_mail', $agent_id, $name, $email, $message );

					$rp_cc_mail_to = RP_Property::get_setting( 'contact_email', 'cc_mail_to', '' );
					if ( ! empty( $rp_cc_mail_to ) ) {
						$agent_email    = $agent_email . ',' . $rp_cc_mail_to;
						$temp_headers   = array();
						$temp_headers[] = $headers;
						$temp_headers[] = 'Cc: ' . $rp_cc_mail_to;
						$headers        = $temp_headers;
					}

					rp_mail( $agent_email, sprintf( esc_html__( "[%s] New message from [%s]", 'realty-portal-agent' ), $blogname, $name ), $email_content, $headers );

					do_action( 'after_rp_agent_contact_send_mail', $agent_id, $name, $email, $message );
				}

				$response[ 'status' ]  = 'success';
				$response[ 'message' ] = esc_html__( 'Your message was sent successfully. Thanks.', 'realty-portal-agent' );
			} else {

				$response[ 'status' ]  = 'error';
				$response[ 'message' ] = esc_html__( 'Can\'t support this action, please contact admin.', 'realty-portal-agent' );
			}

			wp_send_json( $response );
		}

		public function load_more_property_agent() {

			/**
			 * Check security
			 */
			check_ajax_referer( 'rp-agent', 'security', esc_html__( 'Security Breach! Please contact admin!', 'realty-portal-agent' ) );

			/**
			 * Validate $_POST
			 */
			$_POST = wp_kses_post_deep( $_POST );

			/**
			 * Process data
			 */
			if ( ! empty( $_POST[ 'agent_id' ] ) ) {

				unset( $_POST[ 'action' ] );
				unset( $_POST[ 'security' ] );

				$agent_id       = ! empty( $_POST[ 'agent_id' ] ) ? rp_clean( $_POST[ 'agent_id' ] ) : '';
				$page_current   = ! empty( $_POST[ 'page_current' ] ) ? rp_clean( $_POST[ 'page_current' ] ) : 1;
				$max_page       = ! empty( $_POST[ 'max_page' ] ) ? rp_clean( $_POST[ 'max_page' ] ) : '';
				$posts_per_page = ! empty( $_POST[ 'posts_per_page' ] ) ? rp_clean( $_POST[ 'posts_per_page' ] ) : 2;

				if ( $page_current < $max_page ) {

					$args_property = array(
						'post_type'      => apply_filters( 'rp_property_post_type', 'rp_property' ),
						'post_status'    => 'publish',
						'meta_key'       => 'agent_responsible',
						'meta_value'     => $agent_id,
						'meta_compare'   => '=',
						'order'          => 'DESC',
						'posts_per_page' => $posts_per_page,
						'paged'          => absint( $page_current + 1 ),
					);

					ob_start();
					$query_property = new WP_Query( $args_property );

					if ( $query_property->have_posts() ) {
						while ( $query_property->have_posts() ) {
							$query_property->the_post();
							RP_Template::get_template( 'property/content-property.php' );
						}
					}
					wp_reset_postdata();

					$response[ 'html' ] = ob_get_clean();

					$response[ 'status' ]  = 'success';
					$response[ 'message' ] = esc_html__( 'Load more success.', 'realty-portal-agent' );
				} else {
					$response[ 'status' ]  = 'end';
					$response[ 'message' ] = esc_html__( 'Load more complete.', 'realty-portal-agent' );
				}
			} else {

				$response[ 'status' ]  = 'error';
				$response[ 'message' ] = esc_html__( 'This action can not be supported, please contact the admin.', 'realty-portal-agent' );
			}

			wp_send_json( $response );
		}

		public function rp_agent_profile() {

			/**
			 * Check security
			 */
			check_ajax_referer( 'rp-agent', 'security', esc_html__( 'Security Breach! Please contact admin!', 'realty-portal-agent' ) );

			/**
			 * Process data
			 */
			if ( ! empty( $_POST[ 'process' ] ) ) {

				$process = rp_validate_data( $_POST[ 'process' ] );

				unset( $_POST[ 'action' ] );
				unset( $_POST[ 'security' ] );
				unset( $_POST[ 'process' ] );

				if ( ! empty( $_POST[ 'agent_id' ] ) ) {

					$agent_id  = rp_validate_data( $_POST[ 'agent_id' ] );
					$user_id   = RP_Agent::id_user( $agent_id );
					$user_info = get_user_by( 'id', $user_id );

					unset( $_POST[ 'agent_id' ] );

					if ( ! empty( $user_info ) ) {

						if ( 'update_profile' == $process ) {

							foreach ( $_POST as $key => $value ) {

								$key   = rp_validate_data( $key );
								$value = rp_validate_data( $value );

								switch ( $key ) {
									case 'name':
										wp_update_post( array(
											'ID'         => $agent_id,
											'post_title' => $value,
										) );
										update_post_meta( $agent_id, 'name', $value );
										break;

									case 'avatar':
										set_post_thumbnail( $agent_id, $value );
										break;

									case 'about':
										update_post_meta( $agent_id, apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_about', $value );
										break;

									case 'website':
										update_post_meta( $agent_id, apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_website', $value );
										break;

									case 'address':
										update_post_meta( $agent_id, apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_address', $value );
										break;

									default:
										update_post_meta( $agent_id, $key, $value );
										break;
								}

								$response[ 'status' ] = 'success';
								$response[ 'msg' ]    = esc_html__( 'Update profile successfully.', 'realty-portal-agent' );
							}
						} elseif ( 'update_password' == $process ) {

							$old_pass         = rp_validate_data( $_POST[ 'old_password' ] );
							$new_pass         = rp_validate_data( $_POST[ 'new_password' ] );
							$confirm_new_pass = rp_validate_data( $_POST[ 'confirm_new_password' ] );

							if ( empty( $old_pass ) || empty( $new_pass ) || empty( $confirm_new_pass ) ) {

								$response[ 'status' ] = 'error';
								$response[ 'msg' ]    = esc_html__( 'Do not empty field. Please check again!', 'realty-portal-agent' );
							} elseif ( ! wp_check_password( $old_pass, $user_info->data->user_pass, $user_id ) ) {

								$response[ 'status' ] = 'error';
								$response[ 'msg' ]    = esc_html__( 'Old password not match new password. Please check again!', 'realty-portal-agent' );
								$response[ 'class' ]  = 'old_password';
							} elseif ( $new_pass !== $confirm_new_pass ) {

								$response[ 'status' ] = 'error';
								$response[ 'msg' ]    = esc_html__( 'Confirm password not match new password. Please check again!', 'realty-portal-agent' );
								$response[ 'class' ]  = 'new_password';
							} else {

								$agent_password = get_post_meta( $agent_id, 'user_password', true );

								if ( ! empty( $agent_password ) ) {
									update_post_meta( $agent_id, 'user_password', $new_pass );
								}

								wp_set_password( $new_pass, $user_id );
								$response[ 'status' ] = 'success';
								$response[ 'msg' ]    = esc_html__( 'Update new password successfully.', 'realty-portal-agent' );
							}
						} else {

							$response[ 'status' ] = 'error';
							$response[ 'msg' ]    = esc_html__( 'This process can not be supported, please contact the admin.', 'realty-portal-agent' );
						}
					} else {

						$response[ 'status' ] = 'error';
						$response[ 'msg' ]    = esc_html__( 'This agent is not found.', 'realty-portal-agent' );
					}
				} else {

					$response[ 'status' ] = 'error';
					$response[ 'msg' ]    = esc_html__( 'This agent is not found.', 'realty-portal-agent' );
				}
			} else {

				$response[ 'status' ] = 'error';
				$response[ 'msg' ]    = esc_html__( 'This action can not be supported, please contact the admin.', 'realty-portal-agent' );
			}

			wp_send_json( $response );
		}

		public function rp_user_profile() {

			/**
			 * Check security
			 */
			check_ajax_referer( 'rp-agent', 'security', esc_html__( 'Security Breach! Please contact admin!', 'realty-portal-agent' ) );

			/**
			 * Validate $_POST
			 */
			$_POST = wp_kses_post_deep( $_POST );

			/**
			 * Process data
			 */
			$response = array();
			if ( ! empty( $_POST[ 'process' ] ) ) {

				$process = rp_validate_data( $_POST[ 'process' ] );

				unset( $_POST[ 'action' ] );
				unset( $_POST[ 'security' ] );
				unset( $_POST[ 'process' ] );
				unset( $_POST[ '_wysihtml5_mode' ] );

				if ( ! empty( $_POST[ 'user_id' ] ) ) {

					$user_id   = rp_validate_data( $_POST[ 'user_id' ] );
					$user_info = get_user_by( 'id', $user_id );

					unset( $_POST[ 'user_id' ] );

					if ( ! empty( $user_info ) ) {

						if ( 'update_profile' == $process ) {

							foreach ( $_POST as $key => $value ) {

								$key   = rp_validate_data( $key );
								$value = rp_validate_data( $value );

								if ( $key === 'name' ) {

									wp_update_user( array(
										'ID'           => $user_id,
										'display_name' => $value,
									) );
								} elseif ( $key === 'website' ) {

									wp_update_user( array(
										'ID'       => $user_id,
										'user_url' => $value,
									) );
								} elseif ( $key === 'about' ) {

									wp_update_user( array(
										'ID'          => $user_id,
										'description' => $value,
									) );
								} else {

									update_user_meta( $user_id, $key, $value );
								}

								$response[ 'status' ] = 'success';
								$response[ 'msg' ]    = esc_html__( 'Update profile successfully.', 'realty-portal-agent' );
							}
						} elseif ( 'update_password' == $process ) {

							$old_pass         = rp_validate_data( $_POST[ 'old_password' ] );
							$new_pass         = rp_validate_data( $_POST[ 'new_password' ] );
							$confirm_new_pass = rp_validate_data( $_POST[ 'confirm_new_password' ] );

							if ( empty( $old_pass ) || empty( $new_pass ) || empty( $confirm_new_pass ) ) {

								$response[ 'status' ] = 'error';
								$response[ 'msg' ]    = esc_html__( 'Do not empty field. Please check again!', 'realty-portal-agent' );
							} elseif ( ! wp_check_password( $old_pass, $user_info->data->user_pass, $user_id ) ) {

								$response[ 'status' ] = 'error';
								$response[ 'msg' ]    = esc_html__( 'Old password not match new password. Please check again!', 'realty-portal-agent' );
								$response[ 'class' ]  = 'old_password';
							} elseif ( $new_pass !== $confirm_new_pass ) {

								$response[ 'status' ] = 'error';
								$response[ 'msg' ]    = esc_html__( 'Confirm password not match new password. Please check again!', 'realty-portal-agent' );
								$response[ 'class' ]  = 'new_password';
							} else {

								$user_password = get_post_meta( $user_id, 'user_password', true );

								if ( ! empty( $user_password ) ) {
									update_post_meta( $user_id, 'user_password', $new_pass );
								}

								wp_set_password( $new_pass, $user_id );
								$response[ 'status' ] = 'success';
								$response[ 'msg' ]    = esc_html__( 'Update new password successfully.', 'realty-portal-agent' );
							}
						} else {

							$response[ 'status' ] = 'error';
							$response[ 'msg' ]    = esc_html__( 'This process can not be supported, please contact the admin.', 'realty-portal-agent' );
						}
					} else {

						$response[ 'status' ] = 'error';
						$response[ 'msg' ]    = esc_html__( 'This user is not found.', 'realty-portal-agent' );
					}
				} else {

					$response[ 'status' ] = 'error';
					$response[ 'msg' ]    = esc_html__( 'This user is not found.', 'realty-portal-agent' );
				}
			} else {

				$response[ 'status' ] = 'error';
				$response[ 'msg' ]    = esc_html__( 'This action can not be supported, please contact the admin.', 'realty-portal-agent' );
			}

			wp_send_json( $response );
		}

		public static function rp_create_agent( $post_id ) {

			/**
			 * If this is just a revision, don't send the email.
			 */
			if ( wp_is_post_revision( $post_id ) ) {
				return false;
			}

			/**
			 * Check post type
			 */
			global $post;

			if ( empty( $post ) || $post->post_type != apply_filters( 'rp_agent_post_type', 'rp_agent' ) ) {
				return false;
			}

			/**
			 * Validate $_POST
			 */
			$_POST = wp_kses_post_deep( $_POST );

			/**
			 * Process data
			 */
			if ( array_key_exists( 'rp_meta_boxes', $_POST ) ) {

				$user_name     = ! empty( $_POST[ 'rp_meta_boxes' ][ 'user_name' ] ) ? esc_attr( $_POST[ 'rp_meta_boxes' ][ 'user_name' ] ) : '';
				$user_password = ! empty( $_POST[ 'rp_meta_boxes' ][ 'user_password' ] ) ? esc_attr( $_POST[ 'rp_meta_boxes' ][ 'user_password' ] ) : '';
				$user_email    = ! empty( $_POST[ 'rp_meta_boxes' ][ 'rp_agent_email' ] ) ? esc_attr( $_POST[ 'rp_meta_boxes' ][ 'rp_agent_email' ] ) : '';

				$create_user = ! empty( $_POST[ 'rp_meta_boxes' ][ 'create_user' ] ) ? esc_attr( $_POST[ 'rp_meta_boxes' ][ 'create_user' ] ) : '';
				$edit_user   = ! empty( $_POST[ 'rp_meta_boxes' ][ 'edit_user' ] ) ? esc_attr( $_POST[ 'rp_meta_boxes' ][ 'edit_user' ] ) : '';

				if ( empty( $user_name ) || empty( $user_password ) || empty( $user_email ) ) {
					return false;
				}

				$user_id = username_exists( $user_name );

				if ( ( ! $user_id && email_exists( $user_email ) === false && $create_user ) || ( ! empty( $edit_user ) && ! $user_id ) ) {

					$user_id = wp_create_user( $user_name, $user_password, $user_email );

					update_user_meta( $user_id, '_associated_agent_id', $post_id );

					update_post_meta( $post_id, '_associated_user_id', $user_id );

					wp_update_user( array( 'ID'   => $user_id,
					                       'role' => 'agent',
					) );
				} elseif ( ! empty( $edit_user ) && $user_id ) {

					$user_id = RP_Agent::get_id_user( $post_id );

					wp_update_user( array( 'ID'        => $user_id,
					                       'user_pass' => $user_password,
					) );

				} else {

					return esc_html__( 'User already exists.  Password inherited.', 'realty-portal-agent' );
				}
			}
		}

		public static function rp_update_post_to_agent( $post_id ) {

			/**
			 * If this is just a revision, don't send the email.
			 */
			if ( wp_is_post_revision( $post_id ) ) {
				return;
			}

			/**
			 * Check post type
			 */
			global $post;

			if ( empty( $post ) || $post->post_type != apply_filters( 'rp_property_post_type', 'rp_property' ) ) {
				return;
			}

			/**
			 * Process data
			 */
			if ( array_key_exists( 'rp_meta_boxes', $_POST ) ) {

				if ( isset( $_POST[ 'rp_meta_boxes' ][ 'agent_responsible' ] ) && $_POST[ 'rp_meta_boxes' ][ 'agent_responsible' ] !== 'none' ) {

					$agent_id = ! empty( $_POST[ 'rp_meta_boxes' ][ 'agent_responsible' ] ) ? absint( $_POST[ 'rp_meta_boxes' ][ 'agent_responsible' ] ) : '';
					$user_id  = RP_Agent::id_user( $agent_id );
					if ( ! empty( $user_id ) ) {

						remove_action( 'save_post', 'RP_Agent_Process::rp_update_post_to_agent' );

						$args = array(
							'ID'          => $post_id,
							'post_author' => $user_id,
						);
						wp_update_post( $args );

						add_action( 'save_post', 'RP_Agent_Process::rp_update_post_to_agent' );
					}
				}
			}

		}

	}

	new RP_Agent_Process();

endif;