<?php

/**
 * Package Shortcodes
 *
 * @author        NooTheme
 * @version       0.1
 */

if ( ! class_exists( 'RP_Shortcode_Package' ) ) :

	class RP_Shortcode_Package extends RP_Shortcodes {

		/**
		 * RP_Shortcode_Package constructor.
		 */
		public function __construct() {
			add_filter( 'rp_list_shortcode', 'RP_Shortcode_Package::add_shortcode' );
		}

		/**
		 * Add shortcode
		 *
		 * @param $list_shortcode
		 *
		 * @return mixed
		 */
		public static function add_shortcode( $list_shortcode ) {
			$list_shortcode['rp_pricing_table'] = __CLASS__ . '::pricing_table';
			return $list_shortcode;
		}

		/**
		 * Pricing Table shortcode.
		 *
		 * @param mixed $atts
		 *
		 * @return string
		 */
		public static function pricing_table( $atts ) {
			$atts = shortcode_atts( array(
				'button_txt' => esc_html__( 'Buy now', 'realty-portal-package' ),
			), $atts );

			RP_Template::get_template( 'pricing-table.php', compact( 'atts', $atts ), '', RP_ADDON_PACKAGE_TEMPLATES );
		}

	}

	new RP_Shortcode_Package();

endif;
