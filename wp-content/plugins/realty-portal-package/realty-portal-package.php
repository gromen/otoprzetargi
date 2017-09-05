<?php
/**
 *
 * @link              https://nootheme.com
 * @package           RP_AddOn_Package
 *
 * Plugin Name:       Realty Portal: Package
 * Plugin URI:        https://nootheme.com
 * Description:       The add-on manages your Membership type. Work with any theme.
 * Version:           0.3.1
 * Author:            NooTeam
 * Author URI:        https://nootheme.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       realty-portal-package
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'RP_AddOn_Package' ) ) :

	class RP_AddOn_Package {

		/**
		 * Constructor
		 */
		public function __construct() {

			if ( defined( 'RP_AGENT_RUNNING' ) ) {

				add_action( 'init', array(
					&$this,
					'load_plugin_textdomain',
				) );

				add_action( 'wp_enqueue_scripts', 'RP_AddOn_Package::frontend_script' );
				add_action( 'admin_enqueue_scripts', 'RP_AddOn_Package::backend_script' );

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

			if ( ! isset( $rp_init->package ) ) {
				$rp_init->package = new self();
			}
			do_action_ref_array( 'rp_init_package', array( &$rp_init ) );

			return $rp_init->package;
		}

		public static function notice() {
			echo '<div class="error"><p>' . __( 'The <strong>Realty Portal: Package</strong> only works after <strong>Realty Portal: Agent</strong> is activated, please activate it', 'realty-portal-package' ) . '</p></div>';
		}

		/**
		 *  Set up localization for this plugin
		 *  loading the text domain.
		 */
		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'realty-portal-package', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}

		/**
		 * Load script frontend
		 */
		public static function frontend_script() {

			wp_enqueue_style( 'realty-portal-package', RP_ADDON_PACKAGE_ASSETS . '/css/realty-portal-package.css' );

			wp_enqueue_script( 'realty-portal-package', RP_ADDON_PACKAGE_ASSETS . '/js/realty-portal-package.js', array(
				'jquery',
				'rp-core',
			), null, true );

			wp_localize_script( 'realty-portal-package', 'ADDON_PACKAGE', array(
				'ajax_url'     => admin_url( 'admin-ajax.php', 'relative' ),
				'security'     => wp_create_nonce( 'rp-member' ),
				'type_payment' => RP_Payment::get_payment_type(),
			) );
		}

		/**
		 * Load script backend
		 */
		public static function backend_script() {

			wp_enqueue_script( 'realty-portal-package-admin', RP_ADDON_PACKAGE_ASSETS . '/js/realty-portal-package-admin.js', array( 'jquery' ), null, true );

			wp_localize_script( 'realty-portal-package-admin', 'ADDON_PACKAGE_ADMIN', array(
				'ajax_url' => admin_url( 'admin-ajax.php', 'relative' ),
				'security' => wp_create_nonce( 'rp-membership' ),
			) );
		}

		/**
		 * Callback for register_activation_hook
		 * to create some default options to be
		 * used by this plugin.
		 */
		public static function activation() {
			add_filter( 'rp_create_pages', 'RP_AddOn_Package::setup_page' );

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
			if ( ! defined( 'RP_ADDON_PACKAGE_FILE' ) ) {
				define( 'RP_ADDON_PACKAGE_FILE', __FILE__ );
			}

			// Plugin Folder URL
			if ( ! defined( 'RP_ADDON_PACKAGE_URL' ) ) {
				define( 'RP_ADDON_PACKAGE_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin Folder Path
			if ( ! defined( 'RP_ADDON_PACKAGE_PATH' ) ) {
				define( 'RP_ADDON_PACKAGE_PATH', plugin_dir_path( __FILE__ ) );
			}

			// Plugin Sub-Directory Paths
			if ( ! defined( 'RP_ADDON_PACKAGE_PATH_INCLUDES' ) ) {
				define( 'RP_ADDON_PACKAGE_PATH_INCLUDES', RP_ADDON_PACKAGE_PATH . 'includes/' );
			}

			if ( ! defined( 'RP_ADDON_PACKAGE_PATH_LIB' ) ) {
				define( 'RP_ADDON_PACKAGE_PATH_LIB', RP_ADDON_PACKAGE_PATH . 'lib/' );
			}

			if ( ! defined( 'RP_ADDON_PACKAGE_TEMPLATES' ) ) {
				define( 'RP_ADDON_PACKAGE_TEMPLATES', RP_ADDON_PACKAGE_PATH . 'templates' );
			}

			if ( ! defined( 'RP_ADDON_PACKAGE_ASSETS' ) ) {
				define( 'RP_ADDON_PACKAGE_ASSETS', RP_ADDON_PACKAGE_URL . 'assets' );
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
			$list_pages[ 'page_membership_page' ] = array(
				'name'    => _x( 'pricing-table', 'Page slug', 'addon-compare' ),
				'title'   => _x( 'Pricing Table', 'Page title', 'addon-compare' ),
				'content' => '[' . apply_filters( 'rp_pricing_table_shortcode_tag', 'rp_pricing_table' ) . ']',
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
			// Load includes
			require_once RP_ADDON_PACKAGE_PATH_INCLUDES . 'class-membership.php';
			require_once RP_ADDON_PACKAGE_PATH_INCLUDES . 'class-payment.php';
			require_once RP_ADDON_PACKAGE_PATH_INCLUDES . 'class-package-process.php';

			// Load lib
			require_once RP_ADDON_PACKAGE_PATH_LIB . 'class-paypal-framework.php';
			require_once RP_ADDON_PACKAGE_PATH_LIB . 'paypal-framework.php';
			require_once RP_ADDON_PACKAGE_PATH_LIB . 'product-package.php';
			require_once RP_ADDON_PACKAGE_PATH_LIB . 'woocommerce-checkout.php';

			require_once RP_ADDON_PACKAGE_PATH_INCLUDES . 'class-package-dashboard.php';

			require_once RP_ADDON_PACKAGE_PATH_INCLUDES . 'class-package-shortcode.php';
		}

		/**
		 * Create shortcode
		 *
		 * @access private
		 * @return void
		 */
		public static function map_shortcode() {
			/**
			 * Create shortcode: RP Pricing Table
			 */
			ns_map( array(
				'name'     => esc_html__( 'RP Pricing Table', 'realty-portal-package' ),
				'base'     => 'rp_pricing_table',
				'icon'     => 'rp-pricing-table',
				'category' => esc_html__( 'Agent', 'realty-portal-package' ),
				'params'   => array(
					array(
						'param_name'  => 'button_txt',
						'heading'     => esc_html__( 'Button Text', 'realty-portal-package' ),
						'type'        => 'textfield',
						'admin_label' => true,
						'std'         => esc_html__( 'Buy now', 'realty-portal-package' ),
					),
				),
			) );
		}

	}

	// Run activation hook
	register_activation_hook( __FILE__, array(
		'RP_AddOn_Package',
		'activation',
	) );

	// Initialize plugin on rp_init
	add_action( 'rp_init_agent', array(
		'RP_AddOn_Package',
		'init',
	) );

endif;