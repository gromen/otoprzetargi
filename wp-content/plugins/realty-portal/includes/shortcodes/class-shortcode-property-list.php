<?php

/**
 * Property List Shortcodes
 *
 * @author        NooTheme
 * @version       0.1
 */
if ( ! class_exists( 'RP_Shortcode_Property_List' ) ) :

	class RP_Shortcode_Property_List {

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

			$atts = shortcode_atts( array(
				'title'          => esc_html__( 'Property Listing', 'realty-portal' ),
				'posts_per_page' => '10',
				'listing_offers' => '',
				'listing_type'   => '',
				'orderby'        => 'date',
				'order'          => 'DESC',
			), $atts );

			RP_Template::get_template( 'shortcode/property-list.php', compact( 'atts', $atts ) );
		}

	}

endif;