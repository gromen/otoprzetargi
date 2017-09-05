<?php
/**
 * RP_Package_Process Class
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'RP_Package_Process' ) ) :

	class RP_Package_Process {

		/**
		 *    Initialize class
		 */
		public function __construct() {
			add_action( 'wp_ajax_rp_create_membership', array(
				$this,
				'create_membership',
			) );

			add_action( 'wp_ajax_nopriv_rp_create_membership', array(
				$this,
				'create_membership',
			) );

			add_action( 'wp_ajax_rp_buy_package', array(
				$this,
				'buy_package',
			) );

			add_action( 'wp_ajax_nopriv_rp_buy_package', array(
				$this,
				'buy_package',
			) );

			add_action( 'rp_paypal_framework_process_data', array(
				$this,
				'buy_via_paypal',
			) );

			add_action( 'save_post', array(
				$this,
				'recheck_order',
			) );

			add_action( 'rp_agent_can_add', 'RP_Package_Process::can_edit' );
		}

		public static function can_edit() {

			$membership_info = RP_MemberShip::get_membership_info();

			$expired_date = ! empty( $membership_info[ 'data' ][ 'expired_date' ] ) ? $membership_info[ 'data' ][ 'expired_date' ] : '';

			$remaining_properties = isset( $membership_info[ 'data' ][ 'remaining_properties' ] ) ? absint( $membership_info[ 'data' ][ 'remaining_properties' ] ) : '';
			$url_membership_page  = Realty_Portal::get_setting( 'agent_setting', 'membership_page', '' );
			$url_membership_page  = ( ! empty( $url_membership_page ) ? get_permalink( $url_membership_page ) : '' );
			if ( $expired_date === - 1 ) {
				return new WP_Error( 'error', sprintf( __( 'Your package expired. Please upgrade a new package <a href="%s">here</a>.', 'noo-landmark-core' ), $url_membership_page ) );
			} elseif ( $remaining_properties === 0 && 'Never' !== $expired_date ) {
				return new WP_Error( 'error', sprintf( __( 'The number of added properties exceeded the limit. Please upgrade a new package <a href="%s">here</a>.', 'noo-landmark-core' ), $url_membership_page ) );
			}
		}

		public function create_membership() {

			/**
			 * Check security
			 */
			check_ajax_referer( 'rp-membership', 'security', esc_html__( 'Security Breach! Please contact admin!', 'realty-portal-package' ) );

			/**
			 * Validate $_POST
			 */
			$_POST = wp_kses_post_deep( $_POST );

			/**
			 * Process data
			 */
			if ( ! empty( $_POST[ 'title' ] ) ) {

				$prefix = apply_filters( 'rp_membership_post_type', 'rp_membership' );

				$new_package = array(
					'post_title'  => rp_validate_data( $_POST[ 'title' ] ),
					'post_type'   => apply_filters( 'rp_membership_post_type', 'rp_membership' ),
					'post_status' => 'publish',
				);
				$new_post_ID = wp_insert_post( $new_package );

				if ( $new_post_ID ) {
					update_post_meta( $new_post_ID, "{$prefix}_interval", rp_validate_data( $_POST[ 'interval' ] ) );
					update_post_meta( $new_post_ID, "{$prefix}_interval_unit", rp_validate_data( $_POST[ 'interval_unit' ] ) );
					update_post_meta( $new_post_ID, "{$prefix}_price", rp_validate_data( $_POST[ 'price' ] ) );
					update_post_meta( $new_post_ID, "{$prefix}_properties_num", rp_validate_data( $_POST[ 'properties_num' ] ) );
					update_post_meta( $new_post_ID, "{$prefix}_properties_num_unlimited", rp_validate_data( $_POST[ 'properties_num_unlimited' ] ) );
					update_post_meta( $new_post_ID, "{$prefix}_featured_num", rp_validate_data( $_POST[ 'featured_num' ] ) );

					$response[ 'package_id' ] = $new_post_ID;
					$response[ 'status' ]     = 'success';
					$response[ 'msg' ]        = esc_html__( 'Create new membership successfully.', 'realty-portal-package' );
				} else {

					$response[ 'status' ] = 'error';
					$response[ 'msg' ]    = esc_html__( 'Can not create new membership.', 'realty-portal-package' );
				}
			} else {

				$response[ 'status' ] = 'error';
				$response[ 'msg' ]    = esc_html__( 'Can\'t support this action, please contact admin.', 'realty-portal-package' );
			}

			wp_send_json( $response );
		}

		public function buy_package() {

			/**
			 * Check security
			 */
			check_ajax_referer( 'rp-member', 'security', esc_html__( 'Security Breach! Please contact admin!', 'realty-portal-package' ) );

			/**
			 * Validate $_POST
			 */
			$_POST = wp_kses_post_deep( $_POST );

			/**
			 * Process data
			 */
			if ( ! is_user_logged_in() ) {

				$response[ 'status' ] = 'error';
				$response[ 'msg' ]    = esc_html__( 'You are not logged in yet.', 'realty-portal-package' );

				wp_send_json( $response );
			}

			if ( ! empty( $_POST[ 'agent_id' ] ) ) {

				if ( empty( $_POST[ 'package_id' ] ) ) {

					$response[ 'status' ] = 'error';
					$response[ 'msg' ]    = esc_html__( 'You have no packages. Please select a package.', 'realty-portal-package' );

					wp_send_json( $response );
				}

				$agent_id     = rp_validate_data( $_POST[ 'agent_id' ] );
				$package_id   = rp_validate_data( $_POST[ 'package_id' ] );
				$type_payment = RP_Payment::get_payment_type();
				$price        = get_post_meta( $package_id, 'rp_membership_price', true );
				$title_plan   = get_the_title( $package_id );

				$is_recurring = isset( $_POST[ 'recurring_payment' ] ) ? (bool) ( $_POST[ 'recurring_payment' ] ) : false;

				$recurring_time = isset( $_POST[ 'recurring_time' ] ) ? intval( $_POST[ 'recurring_time' ] ) : 0;

				/**
				 * Set info to cart
				 */
				$_POST[ 'price' ]             = $price;
				$_POST[ 'recurring_payment' ] = 0;

				unset( $_POST[ 'action' ] );
				unset( $_POST[ 'security' ] );

				$response[ 'type_payment' ] = $type_payment;

				if ( 'woocommerce' == $type_payment ) {

					global $woocommerce;

					$product_id = rp_create_product( $title_plan, $price );

					$woocommerce->cart->empty_cart();
					if ( $woocommerce->cart->add_to_cart( $product_id ) ) {
						$woo_url                = $woocommerce->cart->get_checkout_url();
						$response[ 'status' ]   = 'success';
						$response[ 'msg' ]      = esc_html__( 'Your purchase is successful.', 'realty-portal-package' );
						$response[ 'redirect' ] = apply_filters( 'rp_redirect_woocommerce_by_package', esc_attr( $woo_url ) );
					} else {

						$response[ 'status' ] = 'error';
						$response[ 'msg' ]    = esc_html__( 'Your purchase is not successful.', 'realty-portal-package' );
					}
				} else {

					$paypal_url = RP_MemberShip::getMembershipPaymentURL( $agent_id, $package_id, $is_recurring, $recurring_time );

					$response[ 'redirect' ] = $paypal_url;

					if ( ! empty( $response[ 'redirect' ] ) ) {
						$response[ 'status' ] = 'success';
						$response[ 'msg' ]    = esc_html__( 'You are redirecting to Paypal...', 'realty-portal-package' );
					} else {
						$response[ 'status' ] = 'error';
						$response[ 'msg' ]    = esc_html__( 'Can not verify via paypal, please contact administration.', 'realty-portal-package' );
					}
				}
			} else {

				$response[ 'status' ] = 'error';
				$response[ 'msg' ]    = esc_html__( 'Can\'t support this action, please contact admin.', 'realty-portal-package' );
			}

			wp_send_json( $response );
		}

		public function buy_via_paypal( $POST ) {

			/**
			 * Validate $_POST
			 */
			$_POST = wp_kses_post_deep( $_POST );

			$has_err     = false;
			$err_message = array();

			$order_id       = intval( $POST[ 'custom' ] );
			$txn_id         = esc_attr( $POST[ 'txn_id' ] );
			$txn_type       = esc_attr( $POST[ 'txn_type' ] );
			$payment_status = esc_attr( $POST[ 'payment_status' ] );

			$receiver_id    = esc_attr( $POST[ 'receiver_id' ] );
			$receiver_email = esc_attr( $POST[ 'receiver_email' ] );
			$mc_gross       = floatval( $POST[ 'mc_gross' ] );
			$mc_currency    = esc_attr( $POST[ 'mc_currency' ] );

			if ( $receiver_email != Realty_Portal::get_setting( 'payment_setting', 'merchant_account' ) && $receiver_id != Realty_Portal::get_setting( 'payment_setting', 'merchant_account' ) ) {
				$has_err       = true;
				$err_message[] = esc_html__( 'Different Receiver', 'realty-portal-package' );
			}
			if ( empty( $order_id ) ) {
				$has_err       = true;
				$err_message[] = esc_html__( 'Empty Order ID', 'realty-portal-package' );
			}

			$order               = array();
			$order[ 'agent_id' ] = intval( get_post_meta( $order_id, '_agent_id', true ) );
			if ( empty( $order[ 'agent_id' ] ) ) {
				$has_err       = true;
				$err_message[] = esc_html__( 'Order does not have Agent ID', 'realty-portal-package' );
			}
			$order[ 'item_id' ] = intval( get_post_meta( $order_id, '_item_id', true ) );
			if ( empty( $order[ 'item_id' ] ) ) {
				$has_err       = true;
				$err_message[] = esc_html__( 'Order does not have Item ID', 'realty-portal-package' );
			}
			$order[ 'total_price' ]   = floatval( get_post_meta( $order_id, '_total_price', true ) );
			$order[ 'currency_code' ] = esc_attr( get_post_meta( $order_id, '_currency_code', true ) );
			if ( $mc_gross != round( $order[ 'total_price' ], 2 ) || strtoupper( $mc_currency ) != strtoupper( $order[ 'currency_code' ] ) ) {
				$has_err       = true;
				$err_message[] = esc_html__( 'Price or Currency does not match', 'realty-portal-package' );
			}
			$order[ 'total_price' ] = rp_format_price( $order[ 'total_price' ], 'text' );

			$order[ 'recurring' ] = esc_attr( get_post_meta( $order_id, '_billing_type', true ) ) == 'recurring';
			$order[ 'status' ]    = esc_attr( get_post_meta( $order_id, '_payment_status', true ) );
			$order_status         = '';
			if ( ! $order[ 'recurring' ] ) {
				$order_status = RP_Payment::payment_status( $payment_status );
			} else {
				if ( preg_match( "#(subscr_payment)#i", $txn_type ) ) {
					$order_status = RP_Payment::payment_status( $payment_status );
				}
				// elseif(preg_match( "#(subscr_signup)#i", $txn_type)) {
				// 	$order_status	= "pending";
				// }
				// elseif(preg_match( "#(subscr_cancel)#i", $txn_type)) {
				// 	$order_status	= "canceled";
				// }
				// elseif(preg_match( "#(subscr_failed)#i", $txn_type)) {
				// 	$order_status	= "failed";
				// }
			}

			if ( empty( $order_status ) ) {
				$has_err       = true;
				$err_message[] = esc_html__( 'Unknown order status!', 'realty-portal-package' );
			}

			if ( $has_err ) {
				// echo implode('<br/>', $err_message );
				// rp_mail( Realty_Portal::get_setting( 'payment_setting', 'notify_email' ),  esc_html__( 'Error when processing Order', 'realty-portal-package' ), implode('<br/>', $err_message ) );
				return false;
			}

			if ( $order[ 'status' ] != $order_status ) {
				update_post_meta( $order_id, '_payment_status', $order_status );

				if ( $order[ 'status' ] == 'completed' ) {

					if ( RP_Membership::is_membership() ) {
						/**
						 * Check if current membership is activated by this order
						 */
						$activation_date = get_post_meta( $order[ 'agent_id' ], '_activation_date', true );
						$purchase_date   = get_post_meta( $order_id, '_purchase_date', true );
						if ( $activation_date == $purchase_date ) {
							RP_MemberShip::revoke_agent_membership( $order[ 'agent_id' ], $order[ 'item_id' ] );
						}

						if ( $order[ 'recurring' ] ) {
							$recurring_count = intval( get_post_meta( $order_id, '_recurring_count', true ) );
							$recurring_count = max( 0, $recurring_count - 1 );
							update_post_meta( $order_id, '_recurring_count', $recurring_count );
						}
					} elseif ( RP_Membership::is_submission() ) {
						$order[ 'payment_type' ] = esc_attr( get_post_meta( $order_id, '_payment_type', true ) );
						RP_MemberShip::revoke_property_status( $order[ 'agent_id' ], $order[ 'item_id' ], $order[ 'payment_type' ] );
					}
				}

				if ( $order_status == 'completed' ) {
					$purchase_date = time();
					update_post_meta( $order_id, '_purchase_date', time() );
					update_post_meta( $order_id, '_txn_id', $txn_id );

					if ( RP_Membership::is_membership() ) {
						RP_MemberShip::set_agent_membership( $order[ 'agent_id' ], $order[ 'item_id' ], $purchase_date );

						if ( $order[ 'recurring' ] ) {
							$recurring_count = intval( get_post_meta( $order_id, '_recurring_count', true ) );
							update_post_meta( $order_id, '_recurring_count', $recurring_count + 1 );
						}

						// Email
						$admin_email = Realty_Portal::get_setting( 'payment_setting', 'notify_email' );
						if ( empty( $admin_email ) ) {
							$admin_email = get_option( 'admin_email' );
						}

						$user_name    = get_the_title( $order[ 'agent_id' ] );
						$user_email   = get_post_meta( $order[ 'agent_id' ], apply_filters( 'rp_agent_post_type', 'rp_agent' ) . "_email", true );
						$package_name = get_the_title( $order[ 'item_id' ] );
						$site_name    = get_option( 'blogname' );

						// Admin email
						$message = sprintf( esc_html__( "You have received a new payment for membership on %s", 'realty-portal-package' ), $site_name ) . "<br/><br/>";
						$message .= esc_html__( "----------------------------------------------", 'realty-portal-package' ) . "<br/><br/>";
						$message .= sprintf( esc_html__( "User's name: %s", 'realty-portal-package' ), $user_name ) . "<br/><br/>";
						$message .= sprintf( esc_html__( "Email address: %s", 'realty-portal-package' ), $user_email ) . "<br/><br/>";
						$message .= sprintf( esc_html__( "Package: %s", 'realty-portal-package' ), $package_name ) . "<br/><br/>";
						$message .= sprintf( esc_html__( "Amount: %s", 'realty-portal-package' ), $order[ 'total_price' ] ) . "<br/><br/>";
						$message .= sprintf( esc_html__( "Transaction #: %s", 'realty-portal-package' ), $txn_id ) . "<br/><br/>";
						$message .= esc_html__( "----------------------------------------------", 'realty-portal-package' ) . "<br/><br/>";
						$message .= esc_html__( "You may review your invoice history at any time by logging in to backend.", 'realty-portal-package' ) . "<br/><br/>";
						rp_mail( $admin_email, sprintf( esc_html__( '[%s] New Payment received for Membership purchase', 'realty-portal-package' ), $site_name ), $message );

						// Agent email
						$message = sprintf( esc_html__( "Hi %s,", 'realty-portal-package' ), $user_name ) . "<br/><br/>";
						$message .= sprintf( esc_html__( "You have paid %s for %s membership on %s.", 'realty-portal-package' ), $order[ 'total_price' ], $package_name, $site_name ) . "<br/><br/>";
						$message .= sprintf( esc_html__( "Your transaction ID is: %s,", 'realty-portal-package' ), $txn_id ) . "<br/><br/>";
						$message .= esc_html__( "Thank you and enjoy listing,", 'realty-portal-package' ) . "<br/><br/>";
						rp_mail( $user_email, sprintf( esc_html__( '[%s] Payment for membership successfully processed', 'realty-portal-package' ), $site_name ), $message );
					} elseif ( RP_Membership::is_submission() ) {
						$order[ 'payment_type' ] = esc_attr( get_post_meta( $order_id, '_payment_type', true ) );
						RP_MemberShip::set_property_status( $order[ 'agent_id' ], $order[ 'item_id' ], $order[ 'payment_type' ] );

						/**
						 * Get email payment
						 */
						$admin_email = Realty_Portal::get_setting( 'payment_setting', 'notify_email' );
						if ( empty( $admin_email ) ) {
							$admin_email = get_option( 'admin_email' );
						}

						$property_link       = get_permalink( $order[ 'item_id' ] );
						$property_admin_link = admin_url( 'post.php?post=' . $order[ 'item_id' ] ) . '&action=edit';

						$user_name      = get_the_title( $order[ 'agent_id' ] );
						$user_email     = get_post_meta( $order[ 'agent_id' ], apply_filters( 'rp_agent_post_type', 'rp_agent' ) . "_email", true );
						$property_title = get_the_title( $order[ 'item_id' ] );
						$site_name      = get_option( 'blogname' );

						// Admin email
						$message = '';
						$title   = '';
						if ( $order[ 'payment_type' ] == 'listing' ) {
							$message .= sprintf( esc_html__( "You have received a new payment for Paid Submission on %s", 'realty-portal-package' ), $site_name ) . "<br/><br/>";
							$title   .= sprintf( esc_html__( '[%s] New Payment received for Paid Property Submission', 'realty-portal-package' ), $site_name );
						} elseif ( $order[ 'payment_type' ] == 'featured' ) {
							$message .= sprintf( esc_html__( "You have received a new payment for Featured property on %s", 'realty-portal-package' ), $site_name ) . "<br/><br/>";
							$title   .= sprintf( esc_html__( '[%s] New Payment received for Featured property', 'realty-portal-package' ), $site_name );
						} elseif ( $order[ 'payment_type' ] == 'both' ) {
							$message .= sprintf( esc_html__( "You have received a new payment for Paid Submission and Featured property on %s", 'realty-portal-package' ), $site_name ) . "<br/><br/>";
							$title   .= sprintf( esc_html__( '[%s] New Payment received for Paid Submission and Featured property', 'realty-portal-package' ), $site_name );
						}
						$message .= esc_html__( "----------------------------------------------", 'realty-portal-package' ) . "<br/><br/>";
						$message .= sprintf( esc_html__( "User's name: %s", 'realty-portal-package' ), $user_name ) . "<br/><br/>";
						$message .= sprintf( esc_html__( "Email address: %s", 'realty-portal-package' ), $user_email ) . "<br/><br/>";
						$message .= sprintf( esc_html__( "Amount: %s", 'realty-portal-package' ), $order[ 'total_price' ] ) . "<br/><br/>";
						$message .= sprintf( esc_html__( "Transaction #: %s", 'realty-portal-package' ), $txn_id ) . "<br/><br/>";
						$message .= sprintf( esc_html__( "Property link: %s", 'realty-portal-package' ), $property_admin_link ) . "<br/><br/>";
						$message .= esc_html__( "----------------------------------------------", 'realty-portal-package' ) . "<br/><br/>";
						$message .= esc_html__( "You may review your invoice history at any time by logging in to backend.", 'realty-portal-package' ) . "<br/><br/>";
						rp_mail( $admin_email, $title, $message );

						// Agent email
						$message = sprintf( esc_html__( "Hi %s,", 'realty-portal-package' ), $user_name ) . "<br/><br/>";
						$message .= sprintf( esc_html__( "You have paid %s for %s property on %s. This is the link to the listing: %s", 'realty-portal-package' ), $order[ 'total_price' ], $property_title, $site_name, $property_link ) . "<br/><br/>";
						$message .= sprintf( esc_html__( "Your transaction ID is: %s,", 'realty-portal-package' ), $txn_id ) . "<br/><br/>";
						$message .= esc_html__( "Thank you and best regards,", 'realty-portal-package' ) . "<br/><br/>";
						rp_mail( $user_email, sprintf( esc_html__( '[%s] Payment for Property listing successfully processed', 'realty-portal-package' ), $site_name ), $message );
					}
				}
			} else {
				if ( $order[ 'recurring' ] && 'completed' == $order_status ) {
					$purchase_date   = time();
					$recurring_count = intval( get_post_meta( $order_id, '_recurring_count', true ) );
					update_post_meta( $order_id, '_purchase_date', $purchase_date );
					update_post_meta( $order_id, '_txn_id', $txn_id );
					update_post_meta( $order_id, '_recurring_count', $recurring_count + 1 );

					if ( RP_Membership::is_membership() ) {
						RP_MemberShip::set_agent_membership( $order[ 'agent_id' ], $order[ 'item_id' ], $purchase_date );

						// Email
						$admin_email = Realty_Portal::get_setting( 'payment_setting', 'notify_email' );
						if ( empty( $admin_email ) ) {
							$admin_email = get_option( 'admin_email' );
						}

						$user_name    = get_the_title( $order[ 'agent_id' ] );
						$user_email   = get_post_meta( $order[ 'agent_id' ], apply_filters( 'rp_agent_post_type', 'rp_agent' ) . "_email", true );
						$package_name = get_the_title( $order[ 'item_id' ] );
						$site_name    = get_option( 'blogname' );

						// Admin email
						$message = sprintf( esc_html__( "You have received a new recurring payment for membership on %s", 'realty-portal-package' ), $site_name ) . "<br/><br/>";
						$message .= esc_html__( "----------------------------------------------", 'realty-portal-package' ) . "<br/><br/>";
						$message .= sprintf( esc_html__( "User's name: %s", 'realty-portal-package' ), $user_name ) . "<br/><br/>";
						$message .= sprintf( esc_html__( "Email address: %s", 'realty-portal-package' ), $user_email ) . "<br/><br/>";
						$message .= sprintf( esc_html__( "Package: %s", 'realty-portal-package' ), $package_name ) . "<br/><br/>";
						$message .= sprintf( esc_html__( "Amount: %s", 'realty-portal-package' ), $order[ 'total_price' ] ) . "<br/><br/>";
						$message .= sprintf( esc_html__( "Transaction #: %s", 'realty-portal-package' ), $txn_id ) . "<br/><br/>";
						$message .= esc_html__( "----------------------------------------------", 'realty-portal-package' ) . "<br/><br/>";
						$message .= esc_html__( "You may review your invoice history at any time by logging in to backend.", 'realty-portal-package' ) . "<br/><br/>";
						rp_mail( $admin_email, sprintf( esc_html__( '[%s] New Payment received for Membership purchase', 'realty-portal-package' ), $site_name ), $message );

						// Agent email
						$message = sprintf( esc_html__( "Hi %s,", 'realty-portal-package' ), $user_name ) . "<br/><br/>";
						$message .= sprintf( esc_html__( "You have paid %s for %s membership on %s.", 'realty-portal-package' ), $order[ 'total_price' ], $package_name, $site_name ) . "<br/><br/>";
						$message .= sprintf( esc_html__( "Your transaction ID is: %s,", 'realty-portal-package' ), $txn_id ) . "<br/><br/>";
						$message .= esc_html__( "Thank you and enjoy listing,", 'realty-portal-package' ) . "<br/><br/>";
						rp_mail( $user_email, sprintf( esc_html__( '[%s] Payment for membership successfully processed', 'realty-portal-package' ), $site_name ), $message );
					}
				}
			}
		}

		public function recheck_order( $post_id ) {

			if ( wp_is_post_revision( $post_id ) ) {
				return;
			}

			$post_type = get_post_type( $post_id );
			if ( empty( $post_type ) || $post_type != apply_filters( 'rp_payment_post_type', 'rp_payment' ) ) {
				return;
			}

			/**
			 * Validate $_POST
			 */
			$_POST = wp_kses_post_deep( $_POST );

			/**
			 * Process data
			 */
			if ( ! empty( $_POST[ 'rp_meta_boxes' ] ) && is_array( $_POST[ 'rp_meta_boxes' ] ) ) {
				$order_info = $_POST[ 'rp_meta_boxes' ];
				$agent_id   = rp_validate_data( $order_info[ '_agent_id' ], 'int' );
				$item_id    = rp_validate_data( $order_info[ '_item_id' ], 'int' );
				RP_MemberShip::set_agent_membership( $agent_id, $item_id, time(), true );
				update_post_meta( $_POST[ 'ID' ], '_purchase_date', time() );
			}
		}

	}

	new RP_Package_Process();

endif;