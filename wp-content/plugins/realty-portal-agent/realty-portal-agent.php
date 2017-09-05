<?php
/**
 *
 * @link              https://nootheme.com
 * @package           RP_AddOn_Agent
 *
 * Plugin Name:       Realty Portal: Agent
 * Plugin URI:        https://nootheme.com
 * Description:       An add-on to manage agents and their information right in the front-end.
 * Version:           0.3.1
 * Author:            NooTeam
 * Author URI:        https://nootheme.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       rp-realty-portal-agent
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'RP_AddOn_Agent' ) ) :

	class RP_AddOn_Agent {

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

				self::map_shortcode();

				self::includes();

				add_action( 'wp_enqueue_scripts', 'RP_AddOn_Agent::frontend_scripts' );

				add_action( 'rp_agent_form_setting', 'RP_AddOn_Agent::dashboard_option', 10 );
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
			if ( ! isset( $rp_init->agent ) ) {
				$rp_init->agent = new self();
			}
			do_action_ref_array( 'rp_init_agent', array( &$rp_init ) );

			return $rp_init->agent;
		}

		public static function notice() {
			echo '<div class="error"><p>' . __( 'The <strong>Realty Portal: Agent</strong> only works after <strong>Realty Portal</strong> is activated, please activate it', 'rp_realty-portal-agent' ) . '</p></div>';
		}

		/**
		 *  Set up localization for this plugin
		 *  loading the text domain.
		 */
		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'rp-realty-portal-agent', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
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

			if ( !is_user_logged_in() ) {
				wp_enqueue_style( 'rp-custombox', RP_AGENT_ASSETS . 'vendor/custombox/custombox.min.css' );
			}
			
			wp_register_script( 'rp-custombox-legacy', RP_AGENT_ASSETS . '/vendor/custombox/custombox.min.js' );
			wp_register_script( 'rp-custombox', RP_AGENT_ASSETS . 'vendor/custombox/custombox.min.js', array(
				'jquery',
				'rp-custombox-legacy',
			), null, true );

			// Script debugging?
			$suffix = SCRIPT_DEBUG ? '' : '.min';

			wp_enqueue_style( 'rp-agent', RP_AGENT_ASSETS . 'css/rp-agent.css' );

			wp_enqueue_script( 'rp-agent', RP_AGENT_ASSETS . 'js/rp-agent' . $suffix . '.js', apply_filters( 'rp_agent_frontend_scripts', array(
				'jquery',
				'rp-core',
				'rp-custombox',
			) ), RP_AGENT_VERSION, false );

			wp_localize_script( 'rp-agent', 'RP_Agent', apply_filters( 'rp_agent_frontend_scripts_localize', array(
				'ajax_url' => admin_url( 'admin-ajax.php', 'relative' ),
				'security' => wp_create_nonce( 'rp-agent' ),
			) ) );
		}

		/**
		 * Callback for register_activation_hook
		 * to create some default options to be
		 * used by this plugin.
		 */
		public static function activation() {
			if ( ! is_user_logged_in() ) {
				return;
			}

			if ( ! function_exists( 'wp_get_current_user' ) ) {
				require( ABSPATH . WPINC . '/pluggable.php' );
				require( ABSPATH . WPINC . '/pluggable-deprecated.php' );
			}

			$current_user = wp_get_current_user();

			if ( empty( $current_user ) ) {
				return;
			}

			$user_id  = $current_user->ID;
			$agent_id = intval( get_user_meta( $user_id, '_associated_agent_id', true ) );
			apply_filters( 'wpml_object_id', $agent_id, apply_filters( 'rp_agent_post_type', 'rp_agent' ) );

			if ( empty( $agent_id ) && current_user_can( 'remove_users' ) ) {

				$user_current = array(
					'post_title'   => $current_user->data->display_name,
					'post_content' => '',
					'post_status'  => 'publish',
					'post_type'    => apply_filters( 'rp_agent_post_type', 'rp_agent' ),
				);

				// Insert the post into the database
				$id_agent = wp_insert_post( $user_current );

				update_user_meta( $user_id, '_associated_agent_id', $id_agent );

				update_post_meta( $id_agent, '_associated_user_id', $user_id );
				update_post_meta( $id_agent, apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_email', $current_user->data->user_email );
			}

			add_filter( 'rp_create_pages', 'RP_AddOn_Member::setup_page' );

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
			if ( ! defined( 'RP_AGENT_RUNNING' ) ) {
				define( 'RP_AGENT_RUNNING', __FILE__ );
			}

			if ( ! defined( 'RP_AGENT_FILE' ) ) {
				define( 'RP_AGENT_FILE', __FILE__ );
			}

			if ( ! defined( 'RP_AGENT_VERSION' ) ) {
				define( 'RP_AGENT_VERSION', '0.1' );
			}

			// Plugin Folder URL
			if ( ! defined( 'RP_AGENT_URL' ) ) {
				define( 'RP_AGENT_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin Folder Path
			if ( ! defined( 'RP_AGENT_PATH' ) ) {
				define( 'RP_AGENT_PATH', plugin_dir_path( __FILE__ ) );
			}

			// Plugin assets
			if ( ! defined( 'RP_AGENT_ASSETS' ) ) {
				define( 'RP_AGENT_ASSETS', RP_AGENT_URL . 'assets/' );
			}

			// Plugin Sub-Directory Paths
			if ( ! defined( 'RP_AGENT_PATH_INCLUDES' ) ) {
				define( 'RP_AGENT_PATH_INCLUDES', RP_AGENT_PATH . 'includes/' );
			}

			// Plugin Templates
			if ( ! defined( 'RP_AGENT_TEMPLATES' ) ) {
				define( 'RP_AGENT_TEMPLATES', RP_AGENT_PATH . 'templates' );
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
			$list_pages[ 'page_member' ] = array(
				'name'    => _x( 'member', 'Page slug', 'addon-member' ),
				'title'   => _x( 'Member', 'Page title', 'addon-member' ),
				'content' => '[' . apply_filters( 'rp_member_shortcode_tag', 'rp_member' ) . ']',
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
					'title' => esc_html__( 'Login/Register', 'addon-member' ),
					'name'  => 'page_member',
					'type'  => 'pages',
				),
				array(
					'title' => esc_html__( 'Term of Service', 'addon-member' ),
					'name'  => 'page_term_of_service',
					'type'  => 'pages',
				),
			);

			return array_merge( $list_form, $list_form_new );
		}

		/**
		 * Get url forgot password
		 *
		 * @return string
		 */
		public static function get_url_forgot_password() {
			return apply_filters( 'rp_get_url_forgot_password', wp_lostpassword_url() );
		}

		/**
		 * Get url term of service
		 *
		 * @return string
		 */
		public static function get_url_term_of_service() {
			$url = Realty_Portal::get_setting( 'agent_setting', 'page_term_of_service', '' );

			return apply_filters( 'rp_get_url_forgot_password', get_permalink( $url ) );
		}

		/**
		 * Get url member login/rgister
		 *
		 * @return string
		 */
		public static function get_url_member() {
			$url = Realty_Portal::get_setting( 'agent_setting', 'page_member', '' );

			return apply_filters( 'rp_get_url_member', get_permalink( $url ) );
		}

		/**
		 * Get url login page
		 *
		 * @return string
		 */
		public static function get_url_login() {
			return apply_filters( 'rp_get_url_login', esc_url_raw( add_query_arg( array(
				'action' => 'login',
			), self::get_url_member() ) ) );
		}

		/**
		 * Get url register page
		 *
		 * @return string
		 */
		public static function get_url_register() {
			return apply_filters( 'rp_get_url_register', add_query_arg( array(
				'action' => 'register',
			), self::get_url_member() ) );
		}

		/**
		 * Include required files
		 *
		 * @access private
		 * @return void
		 */
		private static function includes() {
			// Include functions
			include( dirname( __FILE__ ) . '/realty-portal-agent-functions.php' );

			// Include agent
			include( dirname( __FILE__ ) . '/includes/class-agent.php' );
			include( dirname( __FILE__ ) . '/includes/class-agent-factory.php' );
			include( dirname( __FILE__ ) . '/includes/class-agent-templates.php' );
			include( dirname( __FILE__ ) . '/includes/class-agent-custom-field-config.php' );
			include( dirname( __FILE__ ) . '/includes/class-agent-dashboard.php' );
			include( dirname( __FILE__ ) . '/includes/class-agent-process.php' );
			include( dirname( __FILE__ ) . '/includes/class-agent-shortcode.php' );

			include( dirname( __FILE__ ) . '/includes/class-member-process.php' );
			include( dirname( __FILE__ ) . '/includes/class-member-shortcode.php' );
			include( dirname( __FILE__ ) . '/includes/class-member-nav-menu.php' );

			// Include new fields
			include( dirname( __FILE__ ) . '/fields/agent_custom_field/agent_custom_field.php' );
			include( dirname( __FILE__ ) . '/fields/agent_social/agent_social.php' );
		}

		/**
		 * Create shortcode
		 *
		 * @access private
		 * @return void
		 */
		public static function map_shortcode() {
			/**
			 * Create shortcode: Agent List
			 */
			ns_map( array(
				'name'     => esc_html__( 'Agent List', 'realty-portal-agent' ),
				'base'     => 'rp_agent_list',
				'icon'     => 'rp-agent-list',
				'category' => esc_html__( 'Agent', 'realty-portal-agent' ),
				'params'   => array(
					array(
						'param_name'  => 'title',
						'type'        => 'textfield',
						'admin_label' => true,
						'heading'     => esc_html__( 'Title', 'realty-portal-agent' ),
						'std'         => esc_html__( 'Agent Listing', 'realty-portal-agent' ),
					),
					array(
						'param_name' => 'agent_category',
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Agent Category', 'realty-portal-agent' ),
						'value'      => rp_get_list_tax( 'agent_category', true, esc_html__( 'All', 'realty-portal-agent' ) ),
					),
					array(
						'param_name'  => 'only_agent',
						'heading'     => esc_html__( 'Show only agent have property', 'realty-portal-agent' ),
						'description' => '',
						'type'        => 'dropdown',
						'std'         => 'no',
						'admin_label' => true,
						'value'       => array(
							esc_html__( 'Yes', 'realty-portal-agent' ) => 'yes',
							esc_html__( 'No', 'realty-portal-agent' )  => 'no',
						),
					),
					array(
						'param_name'  => 'posts_per_page',
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Posts Per Page', 'realty-portal-agent' ),
						'admin_label' => true,
						'std'         => '10',
					),
					array(
						'param_name'  => 'orderby',
						'heading'     => esc_html__( 'Order By', 'realty-portal-agent' ),
						'description' => '',
						'type'        => 'dropdown',
						'std'         => 'latest',
						'value'       => array(
							esc_html__( 'Recent First', 'realty-portal-agent' )            => 'latest',
							esc_html__( 'Older First', 'realty-portal-agent' )             => 'oldest',
							esc_html__( 'Title Alphabet', 'realty-portal-agent' )          => 'alphabet',
							esc_html__( 'Title Reversed Alphabet', 'realty-portal-agent' ) => 'ralphabet',
						),
					),
				),
			) );

			/**
			 * Create shortcode: RP Member
			 */
			ns_map( array(
				'name'     => esc_html__( 'RP Member', 'addon-member' ),
				'base'     => 'rp_member',
				'icon'     => 'addon-member',
				'category' => esc_html__( 'Agent', 'addon-member' ),
				'params'   => array(),
			) );
		}

	}

	// Run activation hook
	register_activation_hook( __FILE__, array(
		'RP_AddOn_Agent',
		'activation',
	) );

	add_action( 'rp_init', array(
		'RP_AddOn_Agent',
		'init',
	) );

endif;