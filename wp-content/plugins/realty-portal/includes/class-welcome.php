<?php
/**
 * class-rp-welcome.php
 *
 * @author  : NooTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'RP_Welcome' ) ) :

	class RP_Welcome {

		/**
		 * @var string The capability users should have to view the page
		 */
		public $minimum_capability = 'edit_published_posts';

		/**
		 * A reference to an instance of this class.
		 */
		private static $instance;

		/**
		 * Returns an instance of this class.
		 */
		public static function get_instance() {

			if ( null == self::$instance ) {
				self::$instance = new RP_Welcome();
			}

			return self::$instance;
		}

		/**
		 * Initializes the plugin by setting filters and administration functions.
		 */
		private function __construct() {
			add_action( 'admin_init', array(
				&$this,
				'welcome',
			) );

			add_action( 'admin_menu', array(
				&$this,
				'admin_menus',
			) );

			add_action( 'admin_head', array(
				&$this,
				'admin_head',
			) );
		}

		/**
		 * Sends user to the Welcome page on first activation of EPL as well as each
		 * time EPL is upgraded to a new version
		 *
		 * @access public
		 * @since  1.0
		 * @return void
		 */
		public function welcome() {
			// Bail if no activation redirect
			if ( ! get_transient( 'rp_activation_redirect' ) ) {
				return;
			}

			// Delete the redirect transient
			delete_transient( 'rp_activation_redirect' );

			// Bail if activating from network, or bulk
			if ( is_network_admin() || isset( $_GET[ 'activate-multi' ] ) ) {
				return;
			}

			$upgrade = get_option( 'rp_version_upgraded_from' );

			if ( ! $upgrade ) { // First time install
				die( wp_safe_redirect( admin_url( 'index.php?page=rp-getting-started' ) ) );
			} else { // Update
				die( wp_safe_redirect( admin_url( 'index.php?page=rp-about' ) ) );
			}
		}

		/**
		 * Register the Dashboard Pages which are later hidden but these pages
		 * are used to render the Welcome and Credits pages.
		 *
		 * @access public
		 * @return void
		 */
		public function admin_menus() {

			// About Page
			add_dashboard_page( esc_html__( 'About', 'realty-portal' ), esc_html__( 'About', 'realty-portal' ), $this->minimum_capability, 'rp-about', array(
				$this,
				'about_screen',
			) );

			// Getting Started Page
			add_dashboard_page( esc_html__( 'Getting started', 'realty-portal' ), esc_html__( 'Getting started', 'realty-portal' ), $this->minimum_capability, 'rp-getting-started', array(
				$this,
				'getting_started_screen',
			) );
		}

		/**
		 * Hide Individual Dashboard Pages
		 *
		 * @access public
		 * @since  1.0
		 * @return void
		 */
		public function admin_head() {
			remove_submenu_page( 'index.php', 'rp-getting-started' );
			remove_submenu_page( 'index.php', 'rp-about' );
		}

		/**
		 * Render Getting Started Screen
		 *
		 * @access public
		 * @return void
		 */
		public function getting_started_screen() {
			?>
			<div class="wrap about-wrap epl-about-wrap">
				<h1><?php printf( esc_html__( 'Welcome to Realty Portal %s', 'realty-portal' ), RP_VERSION ); ?></h1>
				<div class="about-text"><?php printf( esc_html__( 'Thank you for updating to the latest version! Realty Portal %s is ready to make your real estate website faster, safer and better!', 'realty-portal' ), RP_VERSION ); ?></div>
			</div>
			<?php
		}

		/**
		 * Render About Screen
		 *
		 * @access public
		 * @since  1.0
		 * @return void
		 */
		public function about_screen() {
			?>
			<div class="wrap about-wrap epl-about-wrap">
				<h1><?php printf( esc_html__( 'Welcome to Realty Portal %s', 'realty-portal' ), RP_VERSION ); ?></h1>
				<div class="about-text"><?php printf( esc_html__( 'Thank you for updating to the latest version! Realty Portal %s is ready to make your real estate website faster, safer and better!', 'realty-portal' ), RP_VERSION ); ?></div>
			</div>
			<?php
		}

	}

	add_action( 'plugins_loaded', array(
		'RP_Welcome',
		'get_instance',
	) );

endif;
