<?php
/**
 *
 * @link              https://nootheme.com
 * @package           RP_AddOn_Agent_Dashboard
 *
 * Plugin Name:       Realty Portal: Agent Dashboard
 * Plugin URI:        https://nootheme.com
 * Description:       An add-on helps to Add, Edit or Delete properties in the front-end. Work with any theme!
 * Version:           0.3.1
 * Author:            NooTeam
 * Author URI:        https://nootheme.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       realty-portal-agent-dashboard
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'RP_AddOn_Agent_Dashboard' ) ) :

	class RP_AddOn_Agent_Dashboard {

		/**
		 * Constructor
		 */
		public function __construct() {

			if ( defined( 'RP_AGENT_RUNNING' ) ) {

				add_action( 'init', array(
					&$this,
					'load_plugin_textdomain',
				) );

				add_action( 'rp_agent_form_setting', 'RP_AddOn_Agent_Dashboard::dashboard_option', 10 );

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

			if ( ! isset( $rp_init->agent_dashboard ) ) {
				$rp_init->agent_dashboard = new self();
			}
			do_action_ref_array( 'rp_init_agent_dashboard', array( &$rp_init ) );

			return $rp_init->agent_dashboard;
		}

		public static function notice() {
			echo '<div class="error"><p>' . __( 'The <strong>Realty Portal: Agent Dashboard</strong> only works after <strong>Realty Portal: Agent</strong> is activated, please activate it', 'realty-portal-agent-dashboard' ) . '</p></div>';
		}

		/**
		 *  Set up localization for this plugin
		 *  loading the text domain.
		 */
		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'realty-portal-agent-dashboard', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}

		/**
		 * Callback for register_activation_hook
		 * to create some default options to be
		 * used by this plugin.
		 */
		public static function activation() {
			add_filter( 'rp_create_pages', 'RP_AddOn_Agent_Dashboard::setup_page' );

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
			if ( ! defined( 'RP_ADDON_AGENT_DASHBOARD_FILE' ) ) {
				define( 'RP_ADDON_AGENT_DASHBOARD_FILE', __FILE__ );
			}

			// Plugin Folder URL
			if ( ! defined( 'RP_ADDON_AGENT_DASHBOARD_URL' ) ) {
				define( 'RP_ADDON_AGENT_DASHBOARD_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin Folder Path
			if ( ! defined( 'RP_ADDON_AGENT_DASHBOARD_PATH' ) ) {
				define( 'RP_ADDON_AGENT_DASHBOARD_PATH', plugin_dir_path( __FILE__ ) );
			}

			// Plugin Sub-Directory Paths
			if ( ! defined( 'RP_ADDON_AGENT_DASHBOARD_PATH_INCLUDES' ) ) {
				define( 'RP_ADDON_AGENT_DASHBOARD_PATH_INCLUDES', RP_ADDON_AGENT_DASHBOARD_PATH . 'includes/' );
			}

			// Plugin Templates
			if ( ! defined( 'RP_ADDON_AGENT_DASHBOARD_TEMPLATES' ) ) {
				define( 'RP_ADDON_AGENT_DASHBOARD_TEMPLATES', RP_ADDON_AGENT_DASHBOARD_PATH . 'templates' );
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
			$list_pages[ 'page_agent_dashboard' ] = array(
				'name'    => _x( 'agent-dashboard', 'Page slug', 'realty-portal-agent-dashboard' ),
				'title'   => _x( 'Agent Dashboard', 'Page title', 'realty-portal-agent-dashboard' ),
				'content' => '[' . apply_filters( 'rp_agent_dashboard_shortcode_tag', 'rp_agent_dashboard' ) . ']',
			);

			return $list_pages;
		}

		/**
		 * Add new option to tab agent setting
		 *
		 * @param $list_form
		 *
		 * @return array
		 */
		public static function dashboard_option( $list_form ) {
			$list_form_new = array(
				array(
					'title' => esc_html__( 'Agent Dashboard', 'realty-portal-agent-dashboard' ),
					'name'  => 'page_agent_dashboard',
					'type'  => 'pages',
				)
			);
			return array_merge( $list_form, $list_form_new );
		}

		/**
		 * Get url agent dashboard
		 *
		 * @return string
		 */
		public static function get_url_agent_dashboard() {
			$url = Realty_Portal::get_setting( 'agent_setting', 'page_agent_dashboard', '' );

			return apply_filters( 'get_url_agent_dashboard', get_permalink( $url ) );
		}

		/**
		 * Include required files
		 *
		 * @access private
		 * @return void
		 */
		private static function includes() {
			include ( dirname( __FILE__ ) . '/includes/class-agent-dashboard-shortcode.php' );
			include ( dirname( __FILE__ ) . '/includes/class-agent-dashboard-process.php' );
		}

		/**
		 * Create shortcode
		 *
		 * @access private
		 * @return void
		 */
		public static function map_shortcode() {
			/**
			 * Create shortcode: RP Submit_Property
			 */
			ns_map( array(
				'name'     => esc_html__( 'RP Agent Dashboard', 'realty-portal-agent-dashboard' ),
				'base'     => 'rp_agent_dashboard',
				'icon'     => 'realty-portal-agent-dashboard',
				'category' => esc_html__( 'Agent', 'realty-portal-agent-dashboard' ),
				'params'   => array(),
			) );
		}

	}

	// Run activation hook
	register_activation_hook( __FILE__, array(
		'RP_AddOn_Agent_Dashboard',
		'activation',
	) );

	// Initialize plugin on rp_init
	add_action( 'rp_init_agent', array(
		'RP_AddOn_Agent_Dashboard',
		'init',
	) );

endif;