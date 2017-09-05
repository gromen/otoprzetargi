<?php
/**
 *
 * @link              https://nootheme.com
 * @package           RP_AddOn_Advanced_Search
 *
 * Plugin Name:       Realty Portal: Advanced Search
 * Plugin URI:        https://nootheme.com
 * Description:       A forward-thinking solution for every Real Estate website. Easy to update and customize elements of Search function. Work with any theme!
 * Version:           0.3.2
 * Author:            NooTeam
 * Author URI:        https://nootheme.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       realty-portal-advanced-search
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'RP_AddOn_Advanced_Search' ) ) :

	class RP_AddOn_Advanced_Search {

		/**
		 * Constructor
		 */
		public function __construct() {

			if ( defined( 'RP_RUNNING' ) ) {

				add_action( 'wp_enqueue_scripts', 'RP_AddOn_Advanced_Search::frontend_script' );

				add_action( 'init', array(
					&$this,
					'load_plugin_textdomain',
				) );

				add_action( 'widgets_init', array(
					&$this,
					'register_widgets',
				) );

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

			if ( ! isset( $rp_init->advanced_search ) ) {
				$rp_init->advanced_search = new self();
			}
			do_action_ref_array( 'rp_init_advanced_search', array( &$rp_init ) );

			return $rp_init->advanced_search;
		}

		public static function notice() {
			echo '<div class="error"><p>' . __( 'The <strong>Realty Portal: Advanced Search</strong> only works after <strong>Realty Portal: Realty Portal</strong> is activated, please activate it', 'realty-portal-advanced-search' ) . '</p></div>';
		}

		/**
		 *  Set up localization for this plugin
		 *  loading the text domain.
		 */
		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'realty-portal-advanced-search', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}

		/**
		 * Load script frontend
		 *
		 */
		public static function frontend_script() {

			wp_enqueue_style( 'realty-portal-advanced-search', RP_ADDON_ADVANCED_SEARCH_ASSETS . '/css/realty-portal-advanced-search.css' );
		}

		/**
		 *    register_widgets()
		 *
		 *    Register widgets
		 *
		 * @uses     register_widgets()
		 *
		 */

		public function register_widgets() {
			register_widget( 'RP_Widget_Advanced_Search_Property' );
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
			if ( ! defined( 'RP_ADDON_ADVANCED_SEARCH_FILE' ) ) {
				define( 'RP_ADDON_ADVANCED_SEARCH_FILE', __FILE__ );
			}

			// Plugin Folder URL
			if ( ! defined( 'RP_ADDON_ADVANCED_SEARCH_URL' ) ) {
				define( 'RP_ADDON_ADVANCED_SEARCH_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin Folder Path
			if ( ! defined( 'RP_ADDON_ADVANCED_SEARCH_PATH' ) ) {
				define( 'RP_ADDON_ADVANCED_SEARCH_PATH', plugin_dir_path( __FILE__ ) );
			}

			// Plugin Sub-Directory Paths
			if ( ! defined( 'RP_ADDON_ADVANCED_SEARCH_PATH_INCLUDES' ) ) {
				define( 'RP_ADDON_ADVANCED_SEARCH_PATH_INCLUDES', RP_ADDON_ADVANCED_SEARCH_PATH . 'includes/' );
			}

			// Plugin Templates
			if ( ! defined( 'RP_ADDON_ADVANCED_SEARCH_TEMPLATES' ) ) {
				define( 'RP_ADDON_ADVANCED_SEARCH_TEMPLATES', RP_ADDON_ADVANCED_SEARCH_PATH . 'templates' );
			}

			// Plugin Assets
			if ( ! defined( 'RP_ADDON_ADVANCED_SEARCH_ASSETS' ) ) {
				define( 'RP_ADDON_ADVANCED_SEARCH_ASSETS', RP_ADDON_ADVANCED_SEARCH_URL . 'assets' );
			}
		}

		/**
		 * Include required files
		 *
		 * @access private
		 * @return void
		 */
		private static function includes() {
			include( dirname( __FILE__ ) . '/realty-portal-advanced-search-functions.php' );

			include( dirname( __FILE__ ) . '/includes/class-advanced-search-shortcode.php' );
			include( dirname( __FILE__ ) . '/includes/class-advanced-search-widgets.php' );
			include( dirname( __FILE__ ) . '/includes/class-advanced-search-dashboard.php' );
		}

		/**
		 * Create shortcode
		 *
		 * @access private
		 * @return void
		 */
		public static function map_shortcode() {

			$params_advanced_search = array();
			/**
			 * Check if support 3rd party IDX plugin
			 */
			if ( class_exists( 'Addon_IDX_Support' ) ) {
				$params_advanced_search[] = array(
					'param_name'  => 'source',
					'heading'     => esc_html__( 'Source', 'realty-portal-advanced-search' ),
					'type'        => 'dropdown',
					'admin_label' => true,
					'std'         => 'property',
					'value'       => array(
						esc_html__( 'Property', 'realty-portal-advanced-search' ) => 'property',
						esc_html__( 'IDX', 'realty-portal-advanced-search' )      => 'idx',
					),
				);
			} else {
				$params_advanced_search[] = array(
					'param_name'  => 'source',
					'heading'     => esc_html__( 'Source', 'realty-portal-advanced-search' ),
					'type'        => 'dropdown',
					'admin_label' => true,
					'std'         => 'property',
					'value'       => array(
						esc_html__( 'Property', 'realty-portal-advanced-search' ) => 'property',
					),
				);
			}
			$params_advanced_search_default = array(

				array(
					'param_name'  => 'show_map',
					'heading'     => esc_html__( 'Show Map', 'realty-portal-advanced-search' ),
					'type'        => 'dropdown',
					'admin_label' => true,
					'std'         => 'yes',
					'value'       => array(
						esc_html__( 'Yes', 'realty-portal-advanced-search' ) => 'yes',
						esc_html__( 'No', 'realty-portal-advanced-search' )  => 'no',
					),
				),
				array(
					'param_name'  => 'show_controls',
					'heading'     => esc_html__( 'Show Controls Map', 'realty-portal-advanced-search' ),
					'type'        => 'dropdown',
					'admin_label' => true,
					'std'         => 'yes',
					'value'       => array(
						esc_html__( 'Yes', 'realty-portal-advanced-search' ) => 'yes',
						esc_html__( 'No', 'realty-portal-advanced-search' )  => 'no',
					),
					'dependency'  => array(
						'element' => 'show_map',
						'value'   => array( 'yes' ),
					),
				),
				array(
					'param_name'  => 'style',
					'heading'     => esc_html__( 'Search Layout', 'realty-portal-advanced-search' ),
					'description' => esc_html__( 'Choose layout for Search form.', 'realty-portal-advanced-search' ),
					'type'        => 'dropdown',
					'std'         => 'style-1',
					'value'       => array(
						esc_html__( 'Search Horizontal', 'realty-portal-advanced-search' ) => 'style-1',
						esc_html__( 'Search Vertical', 'realty-portal-advanced-search' )   => 'style-2',
						esc_html__( 'Search Top', 'realty-portal-advanced-search' )        => 'style-3',
					),
					'dependency'  => array(
						'element' => 'show_map',
						'value'   => array( 'yes' ),
					),
				),
				array(
					'param_name' => 'latitude',
					'heading'    => esc_html__( 'Latitude', 'realty-portal-advanced-search' ),
					'type'       => 'textfield',
					'std'        => RP_Property::get_setting( 'google_map', 'latitude', '40.714398' ),
					'dependency' => array(
						'element' => 'show_map',
						'value'   => array( 'yes' ),
					),
				),
				array(
					'param_name' => 'longitude',
					'heading'    => esc_html__( 'Longitude', 'realty-portal-advanced-search' ),
					'type'       => 'textfield',
					'std'        => RP_Property::get_setting( 'google_map', 'longitude', '-74.005279' ),
					'dependency' => array(
						'element' => 'show_map',
						'value'   => array( 'yes' ),
					),
				),
				array(
					'param_name' => 'zoom',
					'heading'    => esc_html__( 'Zoom', 'realty-portal-advanced-search' ),
					'type'       => 'textfield',
					'std'        => RP_Property::get_setting( 'google_map', 'zoom', '17' ),
					'dependency' => array(
						'element' => 'show_map',
						'value'   => array( 'yes' ),
					),
				),
				array(
					'param_name' => 'height',
					'heading'    => esc_html__( 'Height', 'realty-portal-advanced-search' ),
					'type'       => 'textfield',
					'std'        => RP_Property::get_setting( 'google_map', 'height', '800' ),
					'dependency' => array(
						'element' => 'show_map',
						'value'   => array( 'yes' ),
					),
				),
				array(
					'param_name' => 'drag_map',
					'heading'    => esc_html__( 'Drag Map', 'realty-portal-advanced-search' ),
					'type'       => 'dropdown',
					'std'        => 'true',
					'value'      => array(
						esc_html__( 'Yes', 'realty-portal-advanced-search' ) => 'true',
						esc_html__( 'No', 'realty-portal-advanced-search' )  => 'false',
					),
					'dependency' => array(
						'element' => 'show_map',
						'value'   => array( 'yes' ),
					),
				),
				array(
					'param_name' => 'fitbounds',
					'heading'    => esc_html__( 'Automatically Fit all Properties', 'realty-portal-advanced-search' ),
					'type'       => 'dropdown',
					'std'        => 'true',
					'value'      => array(
						esc_html__( 'Yes', 'realty-portal-advanced-search' ) => 'true',
						esc_html__( 'No', 'realty-portal-advanced-search' )  => 'false',
					),
					'dependency' => array(
						'element' => 'show_map',
						'value'   => array( 'yes' ),
					),
				),
				array(
					'param_name'  => 'title',
					'heading'     => esc_html__( 'Title', 'realty-portal-advanced-search' ),
					'type'        => 'textfield',
					'admin_label' => true,
					'std'         => esc_html__( 'Find Property', 'realty-portal-advanced-search' ),
				),
				array(
					'param_name' => 'sub_title',
					'heading'    => esc_html__( 'Sub Title', 'realty-portal-advanced-search' ),
					'type'       => 'textfield',
					'dependency' => array(
						'element' => 'show_map',
						'value'   => array( 'no' ),
					),
				),
				array(
					'param_name' => 'option_1',
					'heading'    => esc_html__( 'Option 1', 'realty-portal-advanced-search' ),
					'type'       => 'custom_fields_property',
					'std'        => RP_Property::get_setting( 'advanced_search', 'option_1', 'keyword' ),
					'dependency' => array(
						'element' => 'source',
						'value'   => array( 'property' ),
					),
				),
				array(
					'param_name' => 'option_2',
					'heading'    => esc_html__( 'Option 2', 'realty-portal-advanced-search' ),
					'type'       => 'custom_fields_property',
					'std'        => RP_Property::get_setting( 'advanced_search', 'option_2', 'listing_offers' ),
					'dependency' => array(
						'element' => 'source',
						'value'   => array( 'property' ),
					),
				),
				array(
					'param_name' => 'option_3',
					'heading'    => esc_html__( 'Option 3', 'realty-portal-advanced-search' ),
					'type'       => 'custom_fields_property',
					'std'        => RP_Property::get_setting( 'advanced_search', 'option_3', 'listing_type' ),
					'dependency' => array(
						'element' => 'source',
						'value'   => array( 'property' ),
					),
				),
				array(
					'param_name' => 'option_4',
					'heading'    => esc_html__( 'Option 4', 'realty-portal-advanced-search' ),
					'type'       => 'custom_fields_property',
					'std'        => RP_Property::get_setting( 'advanced_search', 'option_4', 'property_country' ),
					'dependency' => array(
						'element' => 'source',
						'value'   => array( 'property' ),
					),
				),
				array(
					'param_name' => 'option_5',
					'heading'    => esc_html__( 'Option 5', 'realty-portal-advanced-search' ),
					'type'       => 'custom_fields_property',
					'std'        => RP_Property::get_setting( 'advanced_search', 'option_5', '_bedrooms' ),
					'dependency' => array(
						'element' => 'source',
						'value'   => array( 'property' ),
					),
				),
				array(
					'param_name' => 'option_6',
					'heading'    => esc_html__( 'Option 6', 'realty-portal-advanced-search' ),
					'type'       => 'custom_fields_property',
					'std'        => RP_Property::get_setting( 'advanced_search', 'option_6', '_bathrooms' ),
					'dependency' => array(
						'element' => 'source',
						'value'   => array( 'property' ),
					),
				),
				array(
					'param_name' => 'option_7',
					'heading'    => esc_html__( 'Option 7', 'realty-portal-advanced-search' ),
					'type'       => 'custom_fields_property',
					'std'        => RP_Property::get_setting( 'advanced_search', 'option_7', '_garages' ),
					'dependency' => array(
						'element' => 'source',
						'value'   => array( 'property' ),
					),
				),
				array(
					'param_name' => 'option_8',
					'heading'    => esc_html__( 'Option 8', 'realty-portal-advanced-search' ),
					'type'       => 'custom_fields_property',
					'std'        => RP_Property::get_setting( 'advanced_search', 'option_8', 'price' ),
					'dependency' => array(
						'element' => 'source',
						'value'   => array( 'property' ),
					),
				),
				array(
					'param_name' => 'show_features',
					'heading'    => esc_html__( 'Show Features & Amenities', 'realty-portal-advanced-search' ),
					'type'       => 'checkbox',
					'std'        => 'true',
					'dependency' => array(
						'element' => 'source',
						'value'   => array( 'property' ),
					),
				),
				array(
					'param_name' => 'text_show_features',
					'heading'    => esc_html__( 'Text More', 'realty-portal-advanced-search' ),
					'type'       => 'textfield',
					'std'        => esc_html__( 'More Filters', 'realty-portal-advanced-search' ),
					'dependency' => array(
						'element' => 'show_features',
						'value'   => array( 'true' ),
					),
				),
				array(
					'param_name' => 'text_button_search',
					'heading'    => esc_html__( 'Text Button Search', 'realty-portal-advanced-search' ),
					'type'       => 'textfield',
					'std'        => esc_html__( 'Search Property', 'realty-portal-advanced-search' ),
				),
			);

			$params_advanced_search = array_merge( $params_advanced_search, $params_advanced_search_default );

			ns_map( array(
				'name'     => esc_html__( 'Advanced Search Property', 'realty-portal-advanced-search' ),
				'base'     => 'rp_advanced_search',
				'icon'     => 'rp-advanced-search-property',
				'category' => esc_html__( 'Properties', 'realty-portal-advanced-search' ),
				'params'   => $params_advanced_search,
			) );
		}

	}

	// Run activation hook
	register_activation_hook( __FILE__, array(
		'RP_AddOn_Advanced_Search',
		'activation',
	) );

	// Initialize plugin on rp_init
	add_action( 'rp_init_agent', array(
		'RP_AddOn_Advanced_Search',
		'init',
	) );

endif;