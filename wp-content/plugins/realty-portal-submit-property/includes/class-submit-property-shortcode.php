<?php

/**
 * Submit Property Shortcodes
 *
 * @author        NooTheme
 * @version       0.1
 */

if ( ! class_exists( 'RP_Shortcode_Submit_Property' ) ) :

	class RP_Shortcode_Submit_Property extends RP_Shortcodes {

		/**
		 * RP_Shortcode_Submit_Property constructor.
		 */
		public function __construct() {
			add_filter( 'rp_list_shortcode', 'RP_Shortcode_Submit_Property::add_shortcode' );
		}

		/**
		 * Add shortcode
		 *
		 * @param $list_shortcode
		 *
		 * @return mixed
		 */
		public static function add_shortcode( $list_shortcode ) {
			$list_shortcode['rp_submit_property'] = __CLASS__ . '::submit_property';
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
			RP_Template::get_template( 'submit-property.php', compact( 'atts', $atts ), '', RP_ADDON_SUBMIT_PROPERTY_PATH . 'templates' );
		}

		/**
		 * Submit Property shortcode.
		 *
		 * @param mixed $atts
		 *
		 * @return string
		 */
		public static function submit_property( $atts ) {
			return self::shortcode_wrapper( array(
				'RP_Shortcode_Submit_Property',
				'output',
			), $atts );
		}

	}

	new RP_Shortcode_Submit_Property();

endif;
