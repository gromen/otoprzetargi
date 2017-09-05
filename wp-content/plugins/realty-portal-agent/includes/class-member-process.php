<?php
/**
 * RP_Member_Process Class
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'RP_Member_Process' ) ) :

	class RP_Member_Process {

		/**
		 *    Initialize class
		 */
		public function __construct() {

			add_action( 'wp_ajax_rp_register_member', 'RP_Member_Process::rp_register_member' );

			add_action( 'wp_ajax_nopriv_rp_register_member', 'RP_Member_Process::rp_register_member' );

			add_action( 'wp_ajax_rp_login_member', 'RP_Member_Process::rp_login_member' );

			add_action( 'wp_ajax_nopriv_rp_login_member', 'RP_Member_Process::rp_login_member' );

			add_action( 'login_redirect', 'RP_Member_Process::rp_login_redirect', 10, 3 );
		}

		public static function rp_register_member() {

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
			if ( ! empty( $_POST[ 'user_name' ] ) && ! empty( $_POST[ 'agree_term_of_service' ] ) ) {
				unset( $_POST[ 'action' ] );
				unset( $_POST[ 'security' ] );

				$full_name             = rp_validate_data( $_POST[ 'full_name' ] );
				$user_name             = rp_validate_data( $_POST[ 'user_name' ] );
				$password              = rp_validate_data( $_POST[ 'password' ] );
				$confirm_password      = rp_validate_data( $_POST[ 'confirm_password' ] );
				$email_address         = rp_validate_data( $_POST[ 'email_address' ] );
				$agree_term_of_service = rp_validate_data( $_POST[ 'agree_term_of_service' ] );
				$class_error           = array();
				$msg_error             = array();
				$response              = array();
				$end_process           = false;

				/**
				 * Validate field
				 */
				if ( strlen( $full_name ) < 6 ) {

					$response[ 'message' ] = esc_html__( 'Please enter 6 or more characters, letters and numbers.', 'realty-portal-agent' );
					$response[ 'status' ]  = 'error';
					$msg_error[]           = esc_html__( 'Please enter 6 or more characters, letters and numbers.', 'realty-portal-agent' );
					$class_error[]         = 'full_name';
					$end_process           = true;
				}

				if ( strlen( $user_name ) < 6 ) {

					$response[ 'message' ] = esc_html__( 'Please enter 6 or more characters, letters and numbers.', 'realty-portal-agent' );
					$response[ 'status' ]  = 'error';
					$msg_error[]           = esc_html__( 'Please enter 6 or more characters, letters and numbers.', 'realty-portal-agent' );
					$class_error[]         = 'user_name';
					$end_process           = true;
				} elseif ( strlen( $user_name ) >= 6 && $user_id = username_exists( $user_name ) ) {

					$response[ 'message' ] = esc_html__( 'User already exists.', 'realty-portal-agent' );
					$response[ 'status' ]  = 'error';
					$msg_error[]           = esc_html__( 'User already exists.', 'realty-portal-agent' );
					$class_error[]         = 'user_name';
					$end_process           = true;
				}

				if ( strlen( $password ) < 6 ) {

					$response[ 'message' ] = esc_html__( 'Please enter 6 or more characters, letters and numbers.', 'realty-portal-agent' );
					$response[ 'status' ]  = 'error';
					$msg_error[]           = esc_html__( 'Please enter 6 or more characters, letters and numbers.', 'realty-portal-agent' );
					$class_error[]         = 'password';
					$end_process           = true;
				}

				if ( $password !== $confirm_password ) {

					$response[ 'message' ] = esc_html__( 'The two passwords don\'t match.', 'realty-portal-agent' );
					$response[ 'status' ]  = 'error';
					$msg_error[]           = esc_html__( 'The two passwords don\'t match.', 'realty-portal-agent' );
					$class_error[]         = 'confirm_password';
					$end_process           = true;
				}

				if ( ! is_email( $email_address ) ) {

					$response[ 'message' ] = esc_html__( 'Invaild email address.', 'realty-portal-agent' );
					$response[ 'status' ]  = 'error';
					$msg_error[]           = esc_html__( 'Invaild email address.', 'realty-portal-agent' );
					$class_error[]         = 'email_address';
					$end_process           = true;
				} elseif ( is_email( $email_address ) && email_exists( $email_address ) ) {

					$response[ 'message' ] = esc_html__( 'Email already exists.', 'realty-portal-agent' );
					$response[ 'status' ]  = 'error';
					$msg_error[]           = esc_html__( 'Email already exists.', 'realty-portal-agent' );
					$class_error[]         = 'email_address';
					$end_process           = true;
				}

				if ( empty( $agree_term_of_service ) ) {

					$response[ 'message' ] = esc_html__( 'Please check agree Term of Service.', 'realty-portal-agent' );
					$response[ 'status' ]  = 'error';
					$msg_error[]           = esc_html__( 'Please check agree Term of Service.', 'realty-portal-agent' );
					$class_error[]         = 'rp-item-agree-term-of-service';
					$end_process           = true;
				}

				if ( ! empty( $end_process ) ) {
					$response[ 'message' ] = implode( "\n", $msg_error );
					wp_send_json( $response );
				}

				/**
				 * Create new user
				 */

				if ( false === email_exists( $email_address ) && ! $user_id ) {

					$agent_args = array(
						'post_title'   => $full_name,
						'post_content' => '',
						'post_status'  => 'publish',
						'post_type'    => apply_filters( 'rp_agent_post_type', 'rp_agent' ),
					);

					$agent_id = wp_insert_post( $agent_args );

					$user_id = wp_create_user( $user_name, $password, $email_address );

					if ( ! empty( $user_id ) ) {

						update_user_meta( $user_id, '_associated_agent_id', $agent_id );

						update_post_meta( $agent_id, '_associated_user_id', $user_id );
						update_post_meta( $agent_id, 'name', $full_name );
						update_post_meta( $agent_id, apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_email', $email_address );
						update_post_meta( $agent_id, apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_facebook', '#' );
						update_post_meta( $agent_id, apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_twitter', '#' );
						update_post_meta( $agent_id, apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_google_plus', '#' );

						wp_update_user( array(
							'ID'   => $user_id,
							'role' => 'agent',
						) );

						$url_redirect = apply_filters( 'rp_url_redirect_register_agent', home_url('/') );

						wp_update_user( array(
							'ID'           => $user_id,
							'display_name' => $full_name,
						) );

						/**
						 * Login
						 */
						$url_page_redirect = apply_filters( 'rp_url_redirect_register_member', $url_redirect );

						$info_user = array(
							'user_login'    => $user_name,
							'user_password' => $password,
							'remember'      => true,
						);

						wp_signon( $info_user, false );

						$response[ 'message' ]      = esc_html__( 'Create user successfully. You are logging into...', 'realty-portal-agent' );
						$response[ 'status' ]       = 'success';
						$response[ 'url_redirect' ] = $url_page_redirect;
					} else {

						$response[ 'message' ] = esc_html__( 'Can\'t insert user to database, please contact admin!', 'realty-portal-agent' );
						$response[ 'status' ]  = 'error';
					}
				}
			} else {

				$response[ 'message' ] = esc_html__( 'Don\'t empty user name!', 'realty-portal-agent' );
				$response[ 'status' ]  = 'error';
			}

			wp_send_json( $response );
		}

		public static function rp_login_member() {
			/**
			 * Check security
			 */
			check_ajax_referer( 'rp-agent', 'security', esc_html__( 'Security Breach! Please contact admin!', 'realty-portal-agent' ) );

			/**
			 * Process data
			 */
			if ( ! empty( $_POST[ 'user_name_login' ] ) && ! empty( $_POST[ 'password_login' ] ) ) {

				unset( $_POST[ 'action' ] );
				unset( $_POST[ 'security' ] );

				$user_name = rp_validate_data( $_POST[ 'user_name_login' ] );
				$password  = rp_validate_data( $_POST[ 'password_login' ] );

				$info_user = array(
					'user_login'    => $user_name,
					'user_password' => $password,
					'remember'      => true,
				);

				$user = wp_signon( $info_user, false );

				if ( is_wp_error( $user ) ) :

					$response[ 'message' ] = $user->get_error_message();
					$response[ 'status' ]  = 'error';

				else :
					$url_redirect = home_url( '/' );

					$user_info = get_user_by( 'login', $user_name );
					$agent_id  = intval( get_user_meta( absint( $user_info->ID ), '_associated_agent_id', true ) );
					apply_filters( 'wpml_object_id', $agent_id, apply_filters( 'rp_agent_post_type', 'rp_agent' ) );

					if ( ! empty( $agent_id ) ) {
						$url_redirect = apply_filters( 'rp_url_redirect_register_agent', RP_AddOn_Agent_Dashboard::get_url_agent_dashboard() );
					}

					$response[ 'url_redirect' ] = $url_redirect;
					$response[ 'message' ]      = esc_html__( 'Login successfully! You are redirecting...', 'realty-portal-agent' );
					$response[ 'status' ]       = 'success';

				endif;
			} else {

				$response[ 'message' ] = esc_html__( 'Don\'t empty user name!', 'realty-portal-agent' );
				$response[ 'status' ]  = 'error';
			}

			wp_send_json( $response );
		}

		public static function rp_login_redirect( $redirect_to, $request, $user ) {

			if ( isset( $user->roles ) && is_array( $user->roles ) ) {
				/**
				 * check for admins
				 */
				if ( in_array( 'administrator', $user->roles ) ) {
					/**
					 * redirect them to the default place
					 */
					return $redirect_to;
				} else {
					$url_page_redirect = apply_filters( 'rp_url_redirect_login_member', RP_AddOn_Agent_Profile::get_url_agent_profile() );

					return esc_url( $url_page_redirect );
				}
			} else {
				return $redirect_to;
			}
		}

	}

	new RP_Member_Process();

endif;