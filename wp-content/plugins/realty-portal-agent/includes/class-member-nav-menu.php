<?php
/**
 * RP_Member_Nav_Menu Class
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'RP_Member_Nav_Menu' ) ) :

	class RP_Member_Nav_Menu {

		/**
		 *    Initialize class
		 */
		public function __construct() {

			add_filter( 'wp_nav_menu_items', 'RP_Member_Nav_Menu::nav_menu', 50, 2 );
			add_action( 'wp_footer', 'RP_Member_Nav_Menu::add_box_login' );
			add_action( 'wp_footer', 'RP_Member_Nav_Menu::add_box_register' );
		}

		public static function nav_menu( $items, $args ) {

			if ( apply_filters( 'rp_show_nav_menu', true ) ) {
				$theme_location = apply_filters( 'rp_theme_location', 'primary' );

				if ( $theme_location == $args->theme_location ) {
					ob_start();

					RP_Template::get_template( 'nav-menu.php', array(
						'items' => $items,
						'args'  => $args,
					), '', RP_AGENT_TEMPLATES . '/member' );

					$items .= ob_get_clean();
				}
			}

			return $items;
		}

		public static function add_box_login() {
			if ( is_user_logged_in() ) {
				return;
			}
			?>
			<div id="rp-box-login" class="rp-box-popup">
				<h4 class="rp-label">
					<?php echo apply_filters( 'rp_login_popup_label', esc_html__( 'Login', 'realty-portal-agent' ) ); ?>
				</h4>
				<?php RP_Template::get_template( 'form-login.php', '', '', RP_AGENT_TEMPLATES . '/member' ); ?>
			</div>
			<?php
		}

		public static function add_box_register() {
			if ( is_user_logged_in() ) {
				return;
			}
			?>
			<div id="rp-box-register" class="rp-box-popup">
				<h4 class="rp-label">
					<?php echo apply_filters( 'rp_register_popup_label', esc_html__( 'Register', 'realty-portal-agent' ) ) ?>
				</h4>
				<?php RP_Template::get_template( 'form-register.php', '', '', RP_AGENT_TEMPLATES . '/member' ); ?>
			</div>
			<?php
		}
	}

	new RP_Member_Nav_Menu();

endif;