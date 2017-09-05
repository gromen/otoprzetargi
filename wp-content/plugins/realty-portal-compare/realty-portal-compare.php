<?php
/**
 *
 * @link              https://nootheme.com
 * @package           RP_AddOn_Compare
 *
 * Plugin Name:       Realty Portal: Compare
 * Plugin URI:        https://nootheme.com
 * Description:       An add-on that provides properties comparison to your site. Work with any theme.
 * Version:           0.3.1
 * Author:            NooTeam
 * Author URI:        https://nootheme.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       realty-portal-compare
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'RP_AddOn_Compare' ) ) :

	class RP_AddOn_Compare {

		/**
		 * Constructor
		 */
		public function __construct() {

			if ( defined( 'RP_RUNNING' ) ) {

				add_action( 'init', array(
					&$this,
					'load_plugin_textdomain',
				) );

				add_action( 'wp_enqueue_scripts', 'RP_AddOn_Compare::frontend_script' );

				self::setup_constants();

				self::map_shortcode();

				self::includes();
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

			if ( ! isset( $rp_init->compare ) ) {
				$rp_init->compare = new self();
			}
			do_action_ref_array( 'rp_init_compare', array( &$rp_init ) );

			return $rp_init->compare;
		}

		public static function notice() {
			echo '<div class="error"><p>' . __( 'The <strong>Realty Portal: Compare</strong> only works after <strong>Realty Portal</strong> is activated, please activate it', 'realty-portal-compare' ) . '</p></div>';
		}

		/**
		 *  Set up localization for this plugin
		 *  loading the text domain.
		 */
		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'realty-portal-compare', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}

		/**
		 * Load script frontend
		 */
		public static function frontend_script() {

			wp_enqueue_style( 'rp-compare', RP_ADDON_COMPARE_ASSETS . 'css/realty-portal-compare.css' );

			wp_enqueue_script( 'rp-compare', RP_ADDON_COMPARE_ASSETS . 'js/rp-compare.js', array(
				'jquery',
				'rp-core',
			), null, true );

			wp_localize_script( 'rp-compare', 'RP_Compare', array(
				'ajax_url'    => admin_url( 'admin-ajax.php', 'relative' ),
				'security'    => wp_create_nonce( 'rp-compare' ),
				'url_compare' => RP_Compare_Process::get_url_compare(),
			) );
		}

		/**
		 * Callback for register_activation_hook
		 * to create some default options to be
		 * used by this plugin.
		 */
		public static function activation() {
			add_filter( 'rp_create_pages', 'RP_AddOn_Compare::setup_page' );

			require_once REALTY_PORTAL . '/includes/class-rp-activator.php';
			Realty_Portal_Activator::activate();
		}

		/**
		 * Setup plugin constants
		 *
		 * @access   private
		 */
		private static function setup_constants() {

			// Plugin File
			if ( ! defined( 'RP_ADDON_COMPARE_FILE' ) ) {
				define( 'RP_ADDON_COMPARE_FILE', __FILE__ );
			}

			// Plugin Folder URL
			if ( ! defined( 'RP_ADDON_COMPARE_URL' ) ) {
				define( 'RP_ADDON_COMPARE_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin Folder Path
			if ( ! defined( 'RP_ADDON_COMPARE_PATH' ) ) {
				define( 'RP_ADDON_COMPARE_PATH', plugin_dir_path( __FILE__ ) );
			}

			// Plugin Sub-Directory Paths
			if ( ! defined( 'RP_ADDON_COMPARE_PATH_INCLUDES' ) ) {
				define( 'RP_ADDON_COMPARE_PATH_INCLUDES', RP_ADDON_COMPARE_PATH . 'includes/' );
			}

			if ( ! defined( 'RP_ADDON_COMPARE_ASSETS' ) ) {
				define( 'RP_ADDON_COMPARE_ASSETS', RP_ADDON_COMPARE_URL . 'assets/' );
			}

			if ( ! defined( 'RP_ADDON_COMPARE_TEMPLATES' ) ) {
				define( 'RP_ADDON_COMPARE_TEMPLATES', RP_ADDON_COMPARE_PATH . 'templates' );
			}
		}

		/**
		 * Setup page
		 *
		 * @param $list_pages
		 *
		 * @return mixed
		 */
		public static function setup_page( $list_pages ) {
			$list_pages[ 'page_compare_properties' ] = array(
				'name'    => _x( 'compare-properties', 'Page slug', 'realty-portal-compare' ),
				'title'   => _x( 'Compare Properties', 'Page title', 'realty-portal-compare' ),
				'content' => '[' . apply_filters( 'rp_compare_shortcode_tag', 'rp_compare' ) . ']',
			);

			return $list_pages;
		}

		/**
		 * Include required files
		 *
		 * @access private
		 * @return void
		 */
		private static function includes() {
			require_once RP_ADDON_COMPARE_PATH_INCLUDES . 'class-compare-shortcode.php';
			require_once RP_ADDON_COMPARE_PATH_INCLUDES . 'class-compare-process.php';
		}

		/**
		 * Create shortcode
		 *
		 * @access private
		 * @return void
		 */
		public static function map_shortcode() {

			ns_map( array(
				'name'     => esc_html__( 'RP Compare', 'realty-portal-compare' ),
				'base'     => 'rp_compare',
				'icon'     => 'realty-portal-compare',
				'category' => esc_html__( 'Agent', 'realty-portal-compare' ),
				'params'   => array(),
			) );
		}

	}

	// Run activation hook
	register_activation_hook( __FILE__, array(
		'RP_AddOn_Compare',
		'activation',
	) );

	// Initialize plugin on rp_init
	add_action( 'rp_init', array(
		'RP_AddOn_Compare',
		'init',
	) );

endif;