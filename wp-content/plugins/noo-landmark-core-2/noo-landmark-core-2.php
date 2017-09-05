<?php
/*
Plugin Name: NOO LandMark Core 2
Plugin URI: http://nootheme.com/
Description: Plugin that adds all post types needed by our theme.
Version: 2.0.2
Author: NooTheme
Author URI: http://nootheme.com/
License: GPLv2 or later
*/
if ( ! class_exists( 'Noo_LandMark_Core_2' ) ):

	class Noo_LandMark_Core_2 {

		/*
		 * This method loads other methods of the class.
		 */
		public function __construct() {

			/**
			 * Check theme support
			 */
			if ( ! self::is_support_theme() ) {

				return false;
			}

			/* load languages */
			$this->load_languages();

			/*load all nootheme*/
			$this->load_nootheme();
		}

		/*
		 * Load the languages before everything else.
		 */
		private function load_languages() {
			add_action( 'plugins_loaded', array(
				$this,
				'load_textdomain',
			) );
		}

		/*
		 * Load the text domain.
		 */
		public function load_textdomain() {

			load_plugin_textdomain( 'noo-landmark-core', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
		}

		/*
		 * Load Nootheme on the 'after_setup_theme' action. Then filters will
		 */
		public function load_nootheme() {

			$this->constants();

			$this->includes();
		}

		/**
		 * Constants
		 */
		private function constants() {

			// @TODO: have to change the name of the constancies, those name will cause conflict if we we have 2 plugins from NOO.

			if ( ! defined( 'NOO_PLUGIN_PATH' ) ) {
				define( 'NOO_PLUGIN_PATH', plugin_dir_url( __FILE__ ) );
			}

			if ( ! defined( 'NOO_PLUGIN_ASSETS_URI' ) ) {
				define( 'NOO_PLUGIN_ASSETS_URI', plugin_dir_url( __FILE__ ) . 'assets' );
			}

			if ( ! defined( 'NOO_PLUGIN_SERVER_PATH' ) ) {
				define( 'NOO_PLUGIN_SERVER_PATH', dirname( __FILE__ ) );
			}

			if ( ! defined( 'NOO_FRAMEWORK' ) ) {
				define( 'NOO_FRAMEWORK', dirname( __FILE__ ) . '/framework' );
			}

			if ( ! defined( 'NOO_FRAMEWORK_URI' ) ) {
				define( 'NOO_FRAMEWORK_URI', plugin_dir_url( __FILE__ ) . 'framework' );
			}

			if ( ! defined( 'NOO_ADMIN_ASSETS_URI' ) ) {
				define( 'NOO_ADMIN_ASSETS_URI', plugin_dir_url( __FILE__ ) . 'admin_assets' );
			}

			if ( ! defined( 'NOO_ADMIN_ASSETS_IMG' ) ) {
				define( 'NOO_ADMIN_ASSETS_IMG', plugin_dir_url( __FILE__ ) . 'admin_assets/images' );
			}
		}

		/*
		 * Require file
		 */
		private function includes() {

			require_once NOO_PLUGIN_SERVER_PATH . '/admin/importer/noo-setup-install.php';
			require_once NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/vc_init.php';
			// Init Framework.
			require_once NOO_FRAMEWORK . '/_init.php';

			// Woo Extensions
			require_once NOO_FRAMEWORK . '/woo_extensions.php';
			include( 'noo-landmark-core-enqueue.php' );

		}

		/**
		 * List theme support
		 */
		public static function theme_support() {

			$list_themes = array(
				'noo-landmark',
			);

			return apply_filters( 'noo_landmark_core_theme_support', $list_themes );
		}

		/**
		 * Check support theme
		 */
		public static function is_support_theme() {

			$current_theme = get_option( 'template' );

			if ( in_array( $current_theme, self::theme_support() ) ) {
				return true;
			}

			return false;
		}

		/**
		 * Check plugin active
		 */
		public static function is_plugin_active( $plugin ) {

			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

			return is_plugin_active( $plugin );
		}

		/**
		 * Get post meta
		 */
		public static function get_post_meta( $post_id, $name_field, $default_value = '' ) {

			if ( empty( $post_id ) || empty( $name_field ) ) {
				return false;
			}

			$value_field = get_post_meta( $post_id, $name_field, true );

			if ( empty( $value_field ) ) {
				$value_field = $default_value;
			}

			return $value_field;
		}

	}

	$oj_nooplugin = new Noo_LandMark_Core_2();

endif;

// Add NOO-Customizer Menu
function noo_landmark_add_customizer_menu() {
	$customizer_icon = 'dashicons-admin-customizer';

	add_menu_page( esc_html__( 'Customizer', 'noo-landmark-core' ), esc_html__( 'Customizer', 'noo-landmark-core' ), 'edit_theme_options', 'customize.php', null, $customizer_icon, 61 );
	add_submenu_page( 'options.php', '', '', 'edit_theme_options', 'export_settings', 'noo_landmark_customizer_export_theme_settings' );
}

add_action( 'admin_menu', 'noo_landmark_add_customizer_menu' );

require_once dirname( __FILE__ ) . '/admin/smk-sidebar-generator/smk-sidebar-generator.php';
require_once dirname( __FILE__ ) . '/admin/twitter/twitteroauth.php';