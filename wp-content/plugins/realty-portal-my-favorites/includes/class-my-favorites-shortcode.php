<?php

/**
 * My Favorites Shortcodes
 *
 * @author        NooTheme
 * @version       0.1
 */

if ( ! class_exists( 'RP_Shortcode_My_Favorites' ) ) :

	class RP_Shortcode_My_Favorites extends RP_Shortcodes {

		/**
		 * RP_Shortcode_My_Favorites constructor.
		 */
		public function __construct() {
			add_filter( 'rp_list_shortcode', 'RP_Shortcode_My_Favorites::add_shortcode' );
		}

		/**
		 * Add shortcode
		 *
		 * @param $list_shortcode
		 *
		 * @return mixed
		 */
		public static function add_shortcode( $list_shortcode ) {
			$list_shortcode['rp_my_favorites'] = __CLASS__ . '::my_favorites';
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
			RP_Template::get_template( 'my-favorites.php', compact( 'atts', $atts ), '', RP_ADDON_MY_FAVORITES_TEMPLATES );
		}

		/**
		 * My Favorites shortcode.
		 *
		 * @param mixed $atts
		 *
		 * @return string
		 */
		public static function my_favorites( $atts ) {
			return self::shortcode_wrapper( array(
				'RP_Shortcode_My_Favorites',
				'output',
			), $atts );
		}

	}

	new RP_Shortcode_My_Favorites();

endif;
