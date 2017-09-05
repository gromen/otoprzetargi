<?php
/**
 * RP_Addon_Submit_Property_Templates Class
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'RP_Addon_Submit_Property_Templates' ) ) :

	class RP_Addon_Submit_Property_Templates {

		/**
		 *    Initialize class
		 */
		public function __construct() {
			add_action( 'rp_error_main_submit_property', 'rp_message_notices', 5 );
		}

	}

	new RP_Addon_Submit_Property_Templates();

endif;