<?php
/**
 * Create class RP_Paypal_Framework
 * This class process request via paypal
 *
 * @package     Realty_Portal
 * @author      NooTeam <suppport@nootheme.com>
 * @version     1.0
 */
if ( !class_exists( 'RP_Paypal_Framework' ) ) :

    class RP_Paypal_Framework {

        /**
         * A reference to an instance of this class.
         */
        private static $instance;

        /**
		 * @var array Plugin settings
		 */
		private $settings;

		/**
		 * @var array URLs for sandbox and live
		 */
		private $url = array(
			'sandbox'	=> 'https://www.sandbox.paypal.com/webscr',
			'live'		=> 'https://www.paypal.com/webscr'
		);

        /**
         * Returns an instance of this class. 
         */
        public static function get_instance() {

        	$type_payment = RP_Payment::get_payment_type();

        	if ( $type_payment === 'paypal' ) {

	            if( null == self::$instance ) {
	                self::$instance = new RP_Paypal_Framework();
	            } 
	            return self::$instance;

	        }

        }

        /**
         * Initializes the plugin by setting filters and administration functions.
         */
        private function __construct() {
            /**
             * VAR
             */
				$this->settings['notify_email']   = RP_Payment::get_setting( 'payment_setting', 'notify_email', '' );
				$this->settings['enable_sandbox'] = RP_Payment::get_setting( 'payment_setting', 'enable_sandbox', '' );
				$this->settings['sandbox']        = !empty( $this->settings['enable_sandbox'] ) ? 'sandbox' : 'live'; 

            /**
             * Add action/filter
             */
            add_action( 'wp_ajax_rp_payment_listener', array( &$this, 'listener' ) );
            add_action( 'wp_ajax_nopriv_rp_payment_listener', array( &$this, 'listener' ) );

        }

        /**
		 * This is our listener.  If the proper query var is set correctly it will attempt to handle the response.
		 *
		 * @package Realty_Portal
		 * @author  NooTeam
		 * @version 1.0
		 */
		public function listener() {
			/**
			 * Validate $_POST
			 */
				$_POST = stripslashes_deep( $_POST );

			/**
			 * Try to validate the response to make sure it's from PayPal
			 */
			if ( $this->validate_message() ) {
				$this->process_data();
			}

			/**
			 * Stop WordPress entirely
			 */
			exit;
		}

		/**
		 * Validate the message by checking with PayPal to make sure they really sent it
		 *
		 * @package Realty_Portal
		 * @author  NooTeam
		 * @version 1.0
		 */
		private function validate_message() {
			// Set the command that is used to validate the message
			$_POST['cmd'] = "_notify-validate";

			// We need to send the message back to PayPal just as we received it
			$params = array(
				'httpversion' => '1.1',
				'body'        => $_POST,
				'sslverify'   => apply_filters( 'paypal_framework_sslverify', false ),
				'timeout'     => 30,
			);

			/**
			 * Send the request
			 */
			$resp = wp_remote_post( $this->url[$this->settings['sandbox']], $params );

			/**
			 * Put the $_POST data back to how it was so we can pass it to the action
			 */
			unset( $_POST['cmd'] );
			$message  = esc_html__('URL:', 'realty-portal-package' );
			$message .= "\r\n".print_r($this->url[$this->settings['sandbox']], true)."\r\n\r\n";
			$message .= esc_html__('Options:', 'realty-portal-package' );
			$message .= "\r\n".print_r($this->settings, true)."\r\n\r\n";
			$message .= esc_html__('Response:', 'realty-portal-package' );
			$message .= "\r\n".print_r($resp, true)."\r\n\r\n";
			$message .= esc_html__('Post:', 'realty-portal-package' );
			$message .= "\r\n".print_r($_POST, true);

			/**
			 * If the response was valid, check to see if the request was valid
			 */
			if ( !is_wp_error($resp) && $resp['response']['code'] >= 200 && $resp['response']['code'] < 300 && (strcmp( $resp['body'], "VERIFIED") == 0)) {
				$this->sent_mail( esc_html__( 'IPN Listener Test - Validation Succeeded', 'realty-portal-package' ), $message );
				return true;
			} else {
				/**
				 * If we can't validate the message, assume it's bad
				 */
				$this->sent_mail( esc_html__( 'IPN Listener Test - Validation Failed', 'realty-portal-package' ), $message );
				return false;
			}
		}

		/**
		 * Throw an action based off the transaction type of the message
		 *
		 * @package Realty_Portal
		 * @author  NooTeam
		 * @version 1.0
		 */
		private function process_data() {

			do_action( 'rp_paypal_framework_process_data', $_POST );

			$actions = array( 'paypal-ipn' );
			$subject = sprintf( esc_html__( 'IPN Listener Test - %s', 'realty-portal-package' ), '_processMessage()' );

			if ( !empty( $_POST['txn_type'] ) ) {
				do_action( "rp-paypal-{$_POST['txn_type']}", $_POST );
				$actions[] = "rp-paypal-{$_POST['txn_type']}";
			}

			$message  = sprintf( esc_html__( 'Actions thrown: %s', 'realty-portal-package' ), implode( ', ', $actions ) );
			$message .= "\r\n\r\n";
			$message .= sprintf( esc_html__( 'Passed to actions: %s', 'realty-portal-package' ), "\r\n" . print_r( $_POST, true ) );
			$this->sent_mail( $subject, $message );

		}

		/**
		 * Sent email
		 *
		 * @package Realty_Portal
		 * @author  NooTeam
		 * @version 1.0
		 */
		private function sent_mail( $subject, $message ) {
			/**
			 * Sent notice to email
			 */
			if ( !empty( $this->settings['notify_email'] ) )
				wp_mail( $this->settings['notify_email'], $subject, $message );
		}

		/**
		 * Get the PayPal request URL for an order.
		 * 
		 * @package Realty_Portal
		 * @author  NooTeam
		 * @version 1.0
		 */
		public static function get_request_url( $order ) {

			$paypal_args   = http_build_query( self::get_paypal_args( $order ), '', '&' );
			$enabel_sanbox = RP_Payment::get_setting( 'payment_setting', 'enable_sandbox', '' );

			if ( isset( $enabel_sanbox ) && !empty( $enabel_sanbox ) ) {
				return 'https://www.sandbox.paypal.com/cgi-bin/webscr?test_ipn=1&' . $paypal_args;
			} else {
				return 'https://www.paypal.com/cgi-bin/webscr?' . $paypal_args;
			}
		}

		/**
		 * Get PayPal Args for passing to PP.
		 * 
		 * @package Realty_Portal
		 * @author  NooTeam
		 * @version 1.0
		 */
		public static function get_paypal_args( $order ) {
			$url_page_submit = RP_AddOn_Submit_Property::get_url_submit_property();
			$url_notify      = add_query_arg( array( 'action' => 'rp_payment_listener' ), admin_url('admin-ajax.php') );
			return apply_filters( 'rp_paypal_args',
				array(
					'cmd'           => '_cart',
					'paymentaction' => 'sale',
					'business'      => RP_Payment::get_setting( 'payment_setting', 'notify_email', '' ),
					'no_note'       => 1,
					'currency_code' => RP_Property::get_setting( 'property_setting', 'property_currency', 'USD' ),
					'charset'       => 'utf-8',
					'rm'            => is_ssl() ? 2 : 1,
					'upload'        => 1,
					'return'        => apply_filters( 'rp_url_paypal_return', esc_url_raw( $url_notify ) ),
					'cancel_return' => apply_filters( 'rp_url_paypal_cancel_return', esc_url_raw( $url_page_submit ) ),
					'bn'            => 'RP_Cart',
					'invoice'       => 'RP-' . esc_attr( $order['package_id'] ),
					'custom'        => $order['custom'],
					'notify_url'    => esc_url_raw( $url_notify ),
					'no_shipping'   => 1,
					'item_name'     => esc_html( $order['item_name'] ),
					'quantity'      => 1,
					'amount'        => esc_html( $order['amount'] ),
				)
			);
		}

    }

	// RP_Paypal_Framework::get_instance();
    // add_action( 'after_setup_theme', array( 'RP_Paypal_Framework', 'get_instance' ) );

endif;