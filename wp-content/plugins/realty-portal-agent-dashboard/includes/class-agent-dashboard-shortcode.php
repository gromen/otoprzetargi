<?php

/**
 * Agent Dashboard Shortcodes
 *
 * @author        NooTheme
 * @version       0.1
 */

if ( ! class_exists( 'RP_Shortcode_Agent_Dashboard' ) ) :

	class RP_Shortcode_Agent_Dashboard extends RP_Shortcodes {

		/**
		 * RP_Shortcode_Agent_Dashboard constructor.
		 */
		public function __construct() {
			add_filter( 'rp_list_shortcode', 'RP_Shortcode_Agent_Dashboard::add_shortcode' );
		}

		/**
		 * Add shortcode
		 *
		 * @param $list_shortcode
		 *
		 * @return mixed
		 */
		public static function add_shortcode( $list_shortcode ) {
			$list_shortcode['rp_agent_dashboard'] = __CLASS__ . '::agent_dashboard';
			return $list_shortcode;
		}

		/**
		 * Get the shortcode content.
		 *
		 * @param array $atts
		 *
		 * @return string
		 */
		public static function get( $atts ) {
			return RP_Shortcodes::shortcode_wrapper( array(
				__CLASS__,
				'output',
			), $atts );
		}

		/**
		 * Output the shortcode.
		 *
		 * @param array $atts
		 */
		public static function output( $atts ) {
			RP_Template::get_template( 'agent-dashboard.php', compact( 'atts', $atts ), '', RP_ADDON_AGENT_DASHBOARD_TEMPLATES );
		}

		/**
		 * Agent Dashboard shortcode.
		 *
		 * @param mixed $atts
		 *
		 * @return string
		 */
		public static function agent_dashboard( $atts ) {
			return self::shortcode_wrapper( array(
				'RP_Shortcode_Agent_Dashboard',
				'output',
			), $atts );
		}

	}

	new RP_Shortcode_Agent_Dashboard();

endif;
