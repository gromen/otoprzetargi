<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * RP_Shortcodes class
 *
 * @version     0.1
 * @author      NooTheme
 */
if ( ! class_exists( 'RP_Shortcodes' ) ) :

	class RP_Shortcodes {

		/**
		 * Init shortcodes.
		 */
		public static function init() {
			$shortcodes = apply_filters( 'rp_list_shortcode', array(
				'rp_property_list'  => __CLASS__ . '::property_list',
				'rp_saved_search'  => __CLASS__ . '::saved_search'
			) );

			foreach ( $shortcodes as $shortcode => $function ) {
				add_shortcode( apply_filters( "{$shortcode}_shortcode_tag", $shortcode ), $function );
			}
		}

		public static function conver_class( $str ) {
			$str = wp_strip_all_tags( $str );
			$str = strtolower( $str );
			$str = str_replace( '_', '-', $str );

			return $str;
		}

		/**
		 * Shortcode Wrapper.
		 *
		 * @param string[] $function
		 * @param array    $atts (default: array())
		 *
		 * @return string
		 */
		public static function shortcode_wrapper( $function, $atts = array(), $wrapper = array( 'class'  => 'rp-shortcode', 'before' => null, 'after'  => null ) ) {
			ob_start();

			echo empty( $wrapper[ 'before' ] ) ? '<div class="' . esc_attr( $wrapper[ 'class' ] ) . '">' : $wrapper[ 'before' ];
			echo '<div class="' . self::conver_class( $function[ 0 ] ) . '">';
			call_user_func( $function, $atts );
			echo '</div>';
			echo empty( $wrapper[ 'after' ] ) ? '</div>' : $wrapper[ 'after' ];

			return ob_get_clean();
		}

		/**
		 * Property List shortcode.
		 *
		 * @param mixed $atts
		 *
		 * @return string
		 */
		public static function property_list( $atts ) {
			return self::shortcode_wrapper( array(
				'RP_Shortcode_Property_List',
				'output',
			), $atts );
		}

		/**
		 * Saved Search shortcode.
		 *
		 * @param mixed $atts
		 *
		 * @return string
		 */
		public static function saved_search( $atts ) {
			return self::shortcode_wrapper( array(
				'RP_Shortcode_Saved_Search',
				'output',
			), $atts );
		}

	}

	add_action( 'init', array(
		'RP_Shortcodes',
		'init',
	) );

endif;