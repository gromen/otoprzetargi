<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://nootheme.com
 *
 * @package    Realty_Portal
 * @subpackage Realty_Portal/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @package    Realty_Portal
 * @subpackage Realty_Portal/includes
 * @author     NooTeam <suppport@nootheme.com>
 */
class Realty_Portal {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @var      Realty_Portal_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	public $loader;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 */
	public function __construct() {
		/**
		 * Register global
		 */
		if ( ! defined( 'RP_VERSION' ) ) {

			define( 'RP_VERSION', '0.1' );

		}

		if ( ! defined( 'RP_RUNNING' ) ) {

			define( 'RP_RUNNING', true );

		}

		if ( ! defined( 'RP_DOMAIN' ) ) {

			define( 'RP_DOMAIN', 'realty-portal' );
		}

		if ( ! defined( 'REALTY_PORTAL' ) ) {

			define( 'REALTY_PORTAL', dirname( dirname( __FILE__ ) ) );
		}

		if ( ! defined( 'REALTY_PORTAL_ASSETS' ) ) {

			define( 'REALTY_PORTAL_ASSETS', plugin_dir_url( dirname( __FILE__ ) ) . 'assets' );
		}

		if ( ! defined( 'REALTY_PORTAL_FRAMEWORK' ) ) {

			define( 'REALTY_PORTAL_FRAMEWORK', dirname( __FILE__ ) );
		}

		if ( ! defined( 'REALTY_PORTAL_FRAMEWORK_URI' ) ) {

			define( 'REALTY_PORTAL_FRAMEWORK_URI', plugin_dir_url( __FILE__ ) . 'framework' );
		}

		if ( ! defined( 'REALTY_PORTAL_FRAMEWORK_ASSETS' ) ) {

			define( 'REALTY_PORTAL_FRAMEWORK_ASSETS', plugin_dir_url( __FILE__ ) . 'framework/assets' );
		}

		if ( ! defined( 'REALTY_PORTAL_TEMPLATE' ) ) {

			define( 'REALTY_PORTAL_TEMPLATE', dirname( dirname( __FILE__ ) ) . '/templates' );
		}

		$this->load_dependencies();
		$this->set_locale();

		do_action_ref_array( 'rp_init', array( &$this ) );

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Realty_Portal_Loader. Orchestrates the hooks of the plugin.
	 * - Realty_Portal_i18n. Defines internationalization functionality.
	 * - Realty_Portal_Admin. Defines all hooks for the admin area.
	 * - Realty_Portal_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @access   public
	 */
	public function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		include (  dirname( __FILE__ ) . '/class-rp-loader.php' );

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		include (  dirname( __FILE__ ) . '/class-rp-i18n.php' );

		/**
		 * Load all function
		 */
		include(  dirname( __FILE__ ) . '/functions/loader.php' );

		include(  dirname( __FILE__ ) . '/libs/loader.php' );
		include(  dirname( __FILE__ ) . '/class-query.php' );
		include(  dirname( __FILE__ ) . '/class-template.php' );
		include(  dirname( __FILE__ ) . '/class-member.php' );
		include(  dirname( __FILE__ ) . '/class-property.php' );
		include(  dirname( __FILE__ ) . '/class-install.php' );
		include(  dirname( __FILE__ ) . '/class-welcome.php' );

		/**
		 * Admin dashboard
		 */
		include(  dirname( __FILE__ ) . '/admin/loader.php' );

		/**
		 * Include widgets
		 */
		include(  dirname( __FILE__ ) . '/widgets/featured-property.php' );
		include(  dirname( __FILE__ ) . '/widgets/property-listing.php' );
		include(  dirname( __FILE__ ) . '/widgets/property-taxonomies.php' );
		include(  dirname( __FILE__ ) . '/widgets/simple-search-property.php' );
		include(  dirname( __FILE__ ) . '/widgets/register-widget.php' );

		/**
		 * Load all shortcodes
		 */
		include (  dirname( __FILE__ ) . '/shortcodes/loader.php' );

		$this->loader = new Realty_Portal_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Realty_Portal_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @access   public
	 */
	public function set_locale() {

		$plugin_i18n = new Realty_Portal_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    Realty_Portal_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Check plugin active
	 *
	 * @param $plugin
	 *
	 * @return bool
	 */
	public static function is_plugin_active( $plugin ) {

		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		return is_plugin_active( $plugin );
	}

	/**
	 * Get value setting dashboard
	 */
	public static function get_setting( $name = '', $value = '', $default = '' ) {

		if ( empty( $name ) ) {
			return false;
		}

		$dashboard_setting = (array) get_option( esc_attr( $name ), array() );

		if ( array_key_exists( $value, $dashboard_setting ) ) {

			if ( ! empty( $value ) && ! empty( $dashboard_setting[ $value ] ) ) {
				return $dashboard_setting[ $value ];
			}
		}

		if ( empty( $value ) && ! empty( $dashboard_setting ) ) {
			return $dashboard_setting;
		}

		return $default;
	}

	/**
	 * Get post meta data
	 *
	 * @param        $post_id
	 * @param        $name_field
	 * @param string $default_value
	 *
	 * @return bool|mixed|string
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

	/**
	 * Get date format config
	 *
	 * @return mixed|void
	 */
	public static function get_date_format() {
		return apply_filters( 'rp_get_date_format', get_option( 'date_format', true ) );
	}

	/**
	 * Get the plugin url.
	 *
	 * @return string
	 */
	public static function plugin_url() {
		return untrailingslashit( plugins_url( '/', dirname( __FILE__ ) ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @return string
	 */
	public static function plugin_path() {
		return untrailingslashit( plugin_dir_path( dirname( __FILE__ ) ) );
	}

	/**
	 * Get the template path.
	 *
	 * @return mixed|void
	 */
	public static function template_path() {
		return apply_filters( 'rp_template_path', 'realty-portal/' );
	}

	public static function suffix_path() {
		return defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? 'min/' : '';
	}

	public static function suffix() {
		return defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.min' : '';
	}

}