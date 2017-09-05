<?php
/**
 * Class RP Query
 *
 * @author : NooTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'RP_Query' ) ) :

	class RP_Query {

		/**
		 * A reference to an instance of this class.
		 */
		private static $instance;

		/**
		 * Returns an instance of this class.
		 */
		public static function get_instance() {

			if ( null == self::$instance ) {
				self::$instance = new RP_Query();
			}

			return self::$instance;
		}

		/**
		 * Initializes the plugin by setting filters and administration functions.
		 */
		private function __construct() {
		}

		public static function order( $args, $orderby = 'date', $order = 'DESC' ) {
			if ( isset( $args ) && ! empty( $args ) ) {
				$args[ 'meta_key' ] = '';
				switch ( $orderby ) {
					case 'rand' :
						$args[ 'orderby' ] = 'rand';
						break;

					case 'date' :
						$args[ 'orderby' ] = 'date';
						$args[ 'order' ]   = $order;
						break;

					case 'bath' :
						$args[ 'orderby' ]  = "meta_value_num meta_value";
						$args[ 'order' ]    = $order;
						$args[ 'meta_key' ] = 'rp_property_bathrooms';
						break;

					case 'bed' :
						$args[ 'orderby' ]  = "meta_value_num meta_value";
						$args[ 'order' ]    = $order;
						$args[ 'meta_key' ] = 'rp_property_bedrooms';
						break;

					case 'area' :
						$args[ 'orderby' ]  = "meta_value_num meta_value";
						$args[ 'order' ]    = $order;
						$args[ 'meta_key' ] = 'rp_property_area';
						break;

					case 'price' :
						$args[ 'orderby' ]  = "meta_value_num meta_value";
						$args[ 'order' ]    = $order;
						$args[ 'meta_key' ] = 'price';
						break;

					case 'featured' :
						$args[ 'orderby' ]  = "meta_value";
						$args[ 'order' ]    = $order;
						$args[ 'meta_key' ] = '_featured';
						break;

					case 'name' :
						$args[ 'orderby' ] = 'title';
						$args[ 'order' ]   = 'ASC';
						break;
					case 'latest':
						$args[ 'orderby' ] = 'date';
						break;

					case 'oldest':
						$args[ 'orderby' ] = 'date';
						$args[ 'order' ]   = 'ASC';
						break;

					case 'alphabet':
						$args[ 'orderby' ] = 'title';
						$args[ 'order' ]   = 'ASC';
						break;

					case 'ralphabet':
						$args[ 'orderby' ] = 'title';
						break;
				}
			}

			return apply_filters( 'rp_query_order', $args );
		}
	}

	add_action( 'plugins_loaded', array(
		'RP_Query',
		'get_instance',
	) );

endif;