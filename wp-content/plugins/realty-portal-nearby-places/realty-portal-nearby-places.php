<?php
/**
 *
 * @link              https://nootheme.com
 * @package           RP_AddOn_Nearby_Places
 *
 * Plugin Name:       Realty Portal: Nearby Places
 * Plugin URI:        https://nootheme.com
 * Description:       Quickly display places nearby the property. Get data from Walkscore and Yelp. Work with any theme.
 * Version:           0.3.2
 * Author:            NooTeam
 * Author URI:        https://nootheme.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       realty-portal-nearby-places
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'RP_AddOn_Nearby_Places' ) ) :

	class RP_AddOn_Nearby_Places {

		/**
		 * Constructor
		 */
		public function __construct() {

			if ( defined( 'RP_RUNNING' ) ) {

				add_action( 'init', array(
					&$this,
					'load_plugin_textdomain',
				) );

				self::setup_constants();

				self::includes();
				add_action( 'wp_enqueue_scripts', 'RP_AddOn_Nearby_Places::frontend_scripts' );
			} else {
				if ( is_multisite() ) {
					add_action( 'network_admin_notices', array(
						$this,
						'notice',
					) );
				} else {
					add_action( 'admin_notices', array(
						$this,
						'notice',
					) );
				}
			}
		}

		/**
		 * Initialize the plugin when Realty_Portal is loaded
		 *
		 * @param  object $rp_init
		 *
		 * @uses     do_action_ref_array()
		 * @return object
		 */
		public static function init( $rp_init ) {

			if ( ! isset( $rp_init->nearby_places ) ) {
				$rp_init->nearby_places = new self();
			}
			do_action_ref_array( 'rp_init_nearby_places', array( &$rp_init ) );

			return $rp_init->nearby_places;
		}

		public static function notice() {
			echo '<div class="error"><p>' . __( 'The <strong>Realty Portal: Nearby Places</strong> only works after <strong>Realty Portal</strong> is activated, please activate it', 'realty-portal-nearby-places' ) . '</p></div>';
		}

		/**
		 *  Set up localization for this plugin
		 *  loading the text domain.
		 */
		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'realty-portal-nearby-places', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}

		/**
		 * frontend_scripts()
		 *
		 * Register and enqueue scripts and css.
		 *
		 * @uses    wp_enqueue_style()
		 */
		public static function frontend_scripts() {

			// Only on front end

			if ( is_admin() ) {
				return false;
			}

			if ( RP_Property::is_single_property() ) {
				wp_enqueue_style( 'rp-nearby-places', RP_ADDON_NEARBY_PLACES_ASSETS . 'css/realty-portal-nearby-places.css' );
			}

		}

		/**
		 * Callback for register_activation_hook
		 * to create some default options to be
		 * used by this plugin.
		 */
		public static function activation() {
		}

		/**
		 * Setup plugin constants
		 *
		 * @access   private
		 */
		private static function setup_constants() {

			// Plugin File
			if ( ! defined( 'RP_ADDON_NEARBY_PLACES_FILE' ) ) {
				define( 'RP_ADDON_NEARBY_PLACES_FILE', __FILE__ );
			}

			// Plugin Folder URL
			if ( ! defined( 'RP_ADDON_NEARBY_PLACES_URL' ) ) {
				define( 'RP_ADDON_NEARBY_PLACES_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin Folder Path
			if ( ! defined( 'RP_ADDON_NEARBY_PLACES_PATH' ) ) {
				define( 'RP_ADDON_NEARBY_PLACES_PATH', plugin_dir_path( __FILE__ ) );
			}

			// Plugin Sub-Directory Paths
			if ( ! defined( 'RP_ADDON_NEARBY_PLACES_PATH_INCLUDES' ) ) {
				define( 'RP_ADDON_NEARBY_PLACES_PATH_INCLUDES', RP_ADDON_NEARBY_PLACES_PATH . 'includes/' );
			}

			// Plugin Templates
			if ( ! defined( 'RP_ADDON_NEARBY_PLACES_TEMPLATES' ) ) {
				define( 'RP_ADDON_NEARBY_PLACES_TEMPLATES', RP_ADDON_NEARBY_PLACES_PATH . 'templates' );
			}

			if ( ! defined( 'RP_ADDON_NEARBY_PLACES_ASSETS' ) ) {
				define( 'RP_ADDON_NEARBY_PLACES_ASSETS', RP_ADDON_NEARBY_PLACES_URL . 'assets/' );
			}
		}

		/**
		 * Include required files
		 *
		 * @access private
		 * @return void
		 */
		private static function includes() {
			require_once RP_ADDON_NEARBY_PLACES_PATH_INCLUDES . 'class-nearby-places-dashboard.php';
			require_once RP_ADDON_NEARBY_PLACES_PATH . 'realty-portal-nearby-places-functions.php';
		}

	}

	// Run activation hook
	register_activation_hook( __FILE__, array(
		'RP_AddOn_Nearby_Places',
		'activation',
	) );

	// Initialize plugin on rp_init
	add_action( 'rp_init', array(
		'RP_AddOn_Nearby_Places',
		'init',
	) );

endif;