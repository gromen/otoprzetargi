<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'RP_Member' ) ) :

	class RP_Member {

		/**
		 * A reference to an instance of this class.
		 */
		private static $instance;

		/**
		 * Returns an instance of this class.
		 */
		public static function get_instance() {

			if ( null == self::$instance ) {
				self::$instance = new RP_Member();
			}

			return self::$instance;
		}

		/**
		 * Initializes the plugin by setting filters and administration functions.
		 */
		private function __construct() {
		}

		public static function get_current_url( $encoded = false ) {

			global $wp;
			$current_url = esc_url( add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
			if ( $encoded ) {
				return urlencode( $current_url );
			}

			return $current_url;
		}

		/**
		 * Get url saved search page
		 *
		 * @return string
		 */
		public static function get_url_saved_search() {
			$url = Realty_Portal::get_setting( 'agent_setting', 'page_saved_search', '' );

			return apply_filters( 'get_url_saved_search', get_permalink( $url ) );
		}

		/**
		 * Check page agent profile
		 *
		 * @return string
		 */
		public static function is_saved_search() {
			global $post;

			if ( isset( $post->ID ) && ! empty( $post->ID ) ) {
				$page_id = Realty_Portal::get_setting( 'agent_setting', 'page_saved_search', '' );
				if ( $page_id == $post->ID ) {
					return true;
				}
			}

			return false;
		}

		/**
		 * Get url page search page
		 *
		 * @return string
		 */
		public static function get_url_search() {

			$id_page_search_template = rp_get_page_by_template( 'property-search.php' );

			$url_page_property_search = apply_filters( 'rp_url_page_property_search', ! empty( $id_page_search_template ) ? get_permalink( absint( $id_page_search_template ) ) : get_post_type_archive_link( apply_filters( 'rp_property_post_type', 'rp_property' ) ) );

			if ( empty( $url_page_property_search ) ) {
				$url_page_property_search = home_url();
			}

			return $url_page_property_search;
		}
	}

	add_action( 'plugins_loaded', array(
		'RP_Member',
		'get_instance',
	) );

endif;