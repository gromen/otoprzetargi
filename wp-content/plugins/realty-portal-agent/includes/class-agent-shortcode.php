<?php

/**
 * Agent Shortcodes
 *
 * @author        NooTheme
 * @version       0.1
 */

if ( ! class_exists( 'RP_Shortcode_Agent' ) ) :

	class RP_Shortcode_Agent extends RP_Shortcodes {

		/**
		 * RP_Shortcode_Agent constructor.
		 */
		public function __construct() {
			add_filter( 'rp_list_shortcode', 'RP_Shortcode_Agent::add_shortcode' );
		}

		/**
		 * Add shortcode
		 *
		 * @param $list_shortcode
		 *
		 * @return mixed
		 */
		public static function add_shortcode( $list_shortcode ) {
			$list_shortcode['rp_agent_list'] = __CLASS__ . '::agent_listing';
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
			RP_Template::get_template( 'shortcode-agent-list.php', compact( 'atts', $atts ), '', RP_AGENT_TEMPLATES );
		}

		/**
		 * Agent Listing shortcode.
		 *
		 * @param mixed $atts
		 *
		 * @return string
		 */
		public static function agent_listing( $atts ) {
			$atts = shortcode_atts( array(
				'title'          => esc_html__( 'Agent Listing', 'realty-portal-agent' ),
				'agent_category' => '',
				'only_agent'     => 'no',
				'posts_per_page' => '10',
				'orderby'        => 'latest',
			), $atts );

			return self::shortcode_wrapper( array(
				'RP_Shortcode_Agent',
				'output',
			), $atts );
		}

	}

	new RP_Shortcode_Agent();

endif;
