<?php

/**
 * Compare Shortcodes
 *
 * @author        NooTheme
 * @version       0.1
 */

if ( ! class_exists( 'RP_Shortcode_Compare' ) ) :

	class RP_Shortcode_Compare extends RP_Shortcodes {

		/**
		 * RP_Shortcode_Compare constructor.
		 */
		public function __construct() {
			add_filter( 'rp_list_shortcode', 'RP_Shortcode_Compare::add_shortcode' );
		}

		/**
		 * Add shortcode
		 *
		 * @param $list_shortcode
		 *
		 * @return mixed
		 */
		public static function add_shortcode( $list_shortcode ) {
			$list_shortcode['rp_compare'] = __CLASS__ . '::compare';
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
			RP_Template::get_template( 'compare-listing.php', compact( 'atts', $atts ), '', RP_ADDON_COMPARE_TEMPLATES );
		}

		/**
		 * Compare shortcode.
		 *
		 * @param mixed $atts
		 *
		 * @return string
		 */
		public static function compare( $atts ) {
			return self::shortcode_wrapper( array(
				'RP_Shortcode_Compare',
				'output',
			), $atts );
		}

	}

	new RP_Shortcode_Compare();

endif;
