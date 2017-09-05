<?php
/**
 *
 * @link              https://nootheme.com
 * @package           RP_AddOn_My_Favorites
 *
 * Plugin Name:       Realty Portal: My Favorites
 * Plugin URI:        https://nootheme.com
 * Description:       The add-on allows user to save your properties as favorite. Work with any theme!
 * Version:           0.3.1
 * Author:            NooTeam
 * Author URI:        https://nootheme.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       realty-portal-my-favorites
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'RP_AddOn_My_Favorites' ) ) :

	class RP_AddOn_My_Favorites {

		/**
		 * Constructor
		 */
		public function __construct() {

			if ( defined( 'RP_AGENT_RUNNING' ) ) {

				add_action( 'init', array(
					&$this,
					'load_plugin_textdomain',
				) );

				add_action( 'rp_agent_form_setting', 'RP_AddOn_My_Favorites::dashboard_option', 10 );

				add_action( 'wp_enqueue_scripts', 'RP_AddOn_My_Favorites::frontend_script' );
				add_action( 'rp_single_property_box_meta', 'RP_AddOn_My_Favorites::favorite_icon_single' );

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

			if ( ! isset( $rp_init->my_favorites ) ) {
				$rp_init->my_favorites = new self();
			}
			do_action_ref_array( 'rp_init_my_favorites', array( &$rp_init ) );

			return $rp_init->my_favorites;
		}

		public static function notice() {
			echo '<div class="error"><p>' . __( 'The <strong>Realty Portal: My Favorites</strong> only works after <strong>Realty Portal: Agent</strong> is activated, please activate it', 'realty-portal-my-favorites' ) . '</p></div>';
		}

		/**
		 *  Set up localization for this plugin
		 *  loading the text domain.
		 */
		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'realty-portal-my-favorites', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}

		/**
		 * Load script frontend
		 *
		 */
		public static function frontend_script() {

			wp_enqueue_script( 'rp-favorites', RP_ADDON_MY_FAVORITES_ASSETS . 'js/rp-favorites.js', array(
				'jquery',
				'rp-core',
			), null, true );

			wp_localize_script( 'rp-favorites', 'RP_Favorites', array(
				'ajax_url'      => admin_url( 'admin-ajax.php', 'relative' ),
				'security'      => wp_create_nonce( 'rp-favorites' ),
				'url_favorites' => RP_AddOn_My_Favorites::get_url_favorites(),
			) );
		}

		/**
		 * Callback for register_activation_hook
		 * to create some default options to be
		 * used by this plugin.
		 */
		public static function activation() {
			add_filter( 'rp_create_pages', 'RP_AddOn_My_Favorites::setup_page' );

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
			if ( ! defined( 'RP_ADDON_MY_FAVORITES_FILE' ) ) {
				define( 'RP_ADDON_MY_FAVORITES_FILE', __FILE__ );
			}

			// Plugin Folder URL
			if ( ! defined( 'RP_ADDON_MY_FAVORITES_URL' ) ) {
				define( 'RP_ADDON_MY_FAVORITES_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin Folder Path
			if ( ! defined( 'RP_ADDON_MY_FAVORITES_PATH' ) ) {
				define( 'RP_ADDON_MY_FAVORITES_PATH', plugin_dir_path( __FILE__ ) );
			}

			// Plugin Sub-Directory Paths
			if ( ! defined( 'RP_ADDON_MY_FAVORITES_PATH_INCLUDES' ) ) {
				define( 'RP_ADDON_MY_FAVORITES_PATH_INCLUDES', RP_ADDON_MY_FAVORITES_PATH . 'includes/' );
			}

			// Plugin Templates
			if ( ! defined( 'RP_ADDON_MY_FAVORITES_TEMPLATES' ) ) {
				define( 'RP_ADDON_MY_FAVORITES_TEMPLATES', RP_ADDON_MY_FAVORITES_PATH . 'templates' );
			}

			if ( ! defined( 'RP_ADDON_MY_FAVORITES_ASSETS' ) ) {
				define( 'RP_ADDON_MY_FAVORITES_ASSETS', RP_ADDON_MY_FAVORITES_URL . 'assets/' );
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
			$list_pages[ 'page_my_favorites' ] = array(
				'name'    => _x( 'my-favorites', 'Page slug', 'realty-portal-my-favorites' ),
				'title'   => _x( 'My Favorites', 'Page title', 'realty-portal-my-favorites' ),
				'content' => '[' . apply_filters( 'rp_my_favorites_shortcode_tag', 'rp_my_favorites' ) . ']',
			);

			return $list_pages;
		}

		/**
		 * Get url favorites page
		 *
		 * @return string
		 */
		public static function get_url_favorites() {
			$url = Realty_Portal::get_setting( 'agent_setting', 'page_my_favorites', '' );

			return apply_filters( 'get_url_agent_favorites', get_permalink( $url ) );
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
					'title' => esc_html__( 'My Favorites', 'realty-portal-my-favorites' ),
					'name'  => 'page_my_favorites',
					'type'  => 'pages',
				)
			);
			return array_merge( $list_form, $list_form_new );
		}

		/**
		 * Include required files
		 *
		 * @access private
		 * @return void
		 */
		private static function includes() {
			require_once RP_ADDON_MY_FAVORITES_PATH_INCLUDES . 'class-my-favorites-shortcode.php';
			require_once RP_ADDON_MY_FAVORITES_PATH_INCLUDES . 'class-my-favorites-process.php';
		}

		/**
		 * Create shortcode
		 *
		 * @access private
		 * @return void
		 */
		public static function map_shortcode() {
			/**
			 * Create shortcode: RP My Favorites
			 */
			ns_map( array(
				'name'     => esc_html__( 'RP My Favorites', 'realty-portal-my-favorites' ),
				'base'     => 'rp_my_favorites',
				'icon'     => 'realty-portal-my-favorites',
				'category' => esc_html__( 'Agent', 'realty-portal-my-favorites' ),
				'params'   => array(),
			) );
		}

		/**
		 * Get list property in favorites
		 *
		 * @return array|mixed
		 */
		public static function is_favorites() {
			$favorites = get_user_meta( RP_Agent::is_user(), 'is_favorites', true );
			if ( ! empty( $favorites ) && is_array( $favorites ) ) {
				return $favorites;
			}

			return array();
		}

		/**
		 * Add favorites icon
		 *
		 * @param $property
		 */
		public static function favorite_icon_single( $property ) {
			?>
			<li>
				<i class="rp-event <?php echo RP_My_Favorites_Process::get_favorites( 'icon' ); ?>"
				   data-id="<?php echo esc_attr( $property->ID ); ?>" data-user="<?php echo RP_Agent::is_user(); ?>"
				   data-process="favorites" data-status="<?php echo RP_My_Favorites_Process::get_favorites( 'class' ); ?>"
				   data-content="<?php echo RP_My_Favorites_Process::get_favorites( 'text' ); ?>"
				   data-url="<?php echo RP_AddOn_My_Favorites::get_url_favorites(); ?>"></i>
			</li>
			<?php
		}

	}

	// Run activation hook
	register_activation_hook( __FILE__, array(
		'RP_AddOn_My_Favorites',
		'activation',
	) );

	// Initialize plugin on rp_init
	add_action( 'rp_init', array(
		'RP_AddOn_My_Favorites',
		'init',
	) );

endif;