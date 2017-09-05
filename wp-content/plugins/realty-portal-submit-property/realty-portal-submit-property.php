<?php
/**
 *
 * @link              https://nootheme.com
 * @package           RP_AddOn_Submit_Property
 *
 * Plugin Name:       Realty Portal: Submit Property
 * Plugin URI:        https://nootheme.com
 * Description:       The add-on helps to submit properties right in front-end. It works with any theme!
 * Version:           0.3.2
 * Author:            NooTeam
 * Author URI:        https://nootheme.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       realty-portal-submit-property
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'RP_AddOn_Submit_Property' ) ) :

	class RP_AddOn_Submit_Property {

		/**
		 * Constructor
		 */
		public function __construct() {

			if ( defined( 'RP_AGENT_RUNNING' ) ) {

				add_action( 'wp_enqueue_scripts', 'RP_AddOn_Submit_Property::frontend_script' );

				add_action( 'init', array(
					&$this,
					'load_plugin_textdomain',
				) );

				add_action( 'rp_agent_form_setting', 'RP_AddOn_Submit_Property::dashboard_option', 10 );

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

			if ( ! isset( $rp_init->submit_property ) ) {
				$rp_init->submit_property = new self();
			}
			do_action_ref_array( 'rp_init_submit_property', array( &$rp_init ) );

			return $rp_init->submit_property;
		}

		public static function notice() {
			echo '<div class="error"><p>' . __( 'The <strong>Realty Portal: Submit Property</strong> only works after <strong>Realty Portal: Agent</strong> is activated, please activate it', 'realty-portal-submit-property' ) . '</p></div>';
		}

		/**
		 *  Set up localization for this plugin
		 *  loading the text domain.
		 */
		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'realty-portal-submit-property', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}

		/**
		 * Load script frontend
		 */
		public static function frontend_script() {

			wp_enqueue_style( 'realty-portal-submit-property', RP_ADDON_SUBMIT_PROPERTY_ASSETS . '/css/realty-portal-submit-property.css' );

			wp_enqueue_script( 'realty-portal-submit-property', RP_ADDON_SUBMIT_PROPERTY_ASSETS . '/js/realty-portal-submit-property.js', array( 'rp-core' ), '0.1', true );

			wp_localize_script( 'realty-portal-submit-property', 'RP_Submit_Property', apply_filters( 'rp_submit_property_frontend_scripts_localize', array(
				'ajax_url'    => admin_url( 'admin-ajax.php', 'relative' ),
				'security'    => wp_create_nonce( 'realty-portal-submit-property' ),
				'error_photo' => esc_html__( 'You are required to upload at least one image', 'realty-portal-submit-property' ),
			) ) );
		}

		/**
		 * Callback for register_activation_hook
		 * to create some default options to be
		 * used by this plugin.
		 */
		public static function activation() {
			add_filter( 'rp_create_pages', 'RP_AddOn_Submit_Property::setup_page' );

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
			if ( ! defined( 'RP_ADDON_SUBMIT_PROPERTY_FILE' ) ) {
				define( 'RP_ADDON_SUBMIT_PROPERTY_FILE', __FILE__ );
			}

			// Plugin Folder URL
			if ( ! defined( 'RP_ADDON_SUBMIT_PROPERTY_URL' ) ) {
				define( 'RP_ADDON_SUBMIT_PROPERTY_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin Folder Path
			if ( ! defined( 'RP_ADDON_SUBMIT_PROPERTY_PATH' ) ) {
				define( 'RP_ADDON_SUBMIT_PROPERTY_PATH', plugin_dir_path( __FILE__ ) );
			}

			// Plugin Sub-Directory Paths
			if ( ! defined( 'RP_ADDON_SUBMIT_PROPERTY_PATH_INCLUDES' ) ) {
				define( 'RP_ADDON_SUBMIT_PROPERTY_PATH_INCLUDES', RP_ADDON_SUBMIT_PROPERTY_PATH . 'includes/' );
			}

			// Plugin Assets
			if ( ! defined( 'RP_ADDON_SUBMIT_PROPERTY_ASSETS' ) ) {
				define( 'RP_ADDON_SUBMIT_PROPERTY_ASSETS', RP_ADDON_SUBMIT_PROPERTY_URL . 'assets' );
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
			$list_pages[ 'page_submit_property' ] = array(
				'name'    => _x( 'agent-profile', 'Page slug', 'realty-portal-submit-property' ),
				'title'   => _x( 'Submit Property', 'Page title', 'realty-portal-submit-property' ),
				'content' => '[' . apply_filters( 'rp_submit_property_shortcode_tag', 'rp_submit_property' ) . ']',
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
					'title' => esc_html__( 'Submit Property', 'realty-portal-submit-property' ),
					'name'  => 'page_submit_property',
					'type'  => 'pages',
				),
			);

			return array_merge( $list_form, $list_form_new );
		}

		/**
		 * Get url submit property page
		 *
		 * @return string
		 */
		public static function get_url_submit_property() {
			$url = Realty_Portal::get_setting( 'agent_setting', 'page_submit_property', '' );

			return apply_filters( 'get_url_submit_property', get_permalink( $url ) );
		}

		/**
		 * Include required files
		 *
		 * @access private
		 * @return void
		 */
		private static function includes() {
			include( dirname( __FILE__ ) . '/includes/class-submit-property-shortcode.php' );
			include( dirname( __FILE__ ) . '/includes/class-submit-property-process.php' );
			include( dirname( __FILE__ ) . '/includes/class-submit-property-templates.php' );
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
				'name'     => esc_html__( 'RP Submit Property', 'realty-portal-submit-property' ),
				'base'     => 'rp_submit_property',
				'icon'     => 'realty-portal-submit-property',
				'category' => esc_html__( 'Agent', 'realty-portal-submit-property' ),
				'params'   => array(),
			) );
		}

	}

	// Run activation hook
	register_activation_hook( __FILE__, array(
		'RP_AddOn_Submit_Property',
		'activation',
	) );

	// Initialize plugin on rp_init
	add_action( 'rp_init_agent', array(
		'RP_AddOn_Submit_Property',
		'init',
	) );

endif;