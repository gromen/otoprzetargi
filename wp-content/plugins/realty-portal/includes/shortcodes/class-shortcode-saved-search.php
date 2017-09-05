<?php

/**
 * Saved Search Shortcodes
 *
 * @author        NooTheme
 * @version       0.1
 */
if ( ! class_exists( 'RP_Shortcode_Saved_Search' ) ) :

	class RP_Shortcode_Saved_Search {

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
			RP_Template::get_template( 'shortcode/saved-search.php', compact( 'atts', $atts ) );
		}

	}

endif;