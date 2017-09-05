<?php

/**
 * Member Shortcodes
 *
 * @author        NooTheme
 * @version       0.1
 */

if ( ! class_exists( 'RP_Shortcode_Member' ) ) :

	class RP_Shortcode_Member extends RP_Shortcodes {

		/**
		 * RP_Shortcode_Member constructor.
		 */
		public function __construct() {
			add_filter( 'rp_list_shortcode', 'RP_Shortcode_Member::add_shortcode' );
		}

		/**
		 * Add shortcode
		 *
		 * @param $list_shortcode
		 *
		 * @return mixed
		 */
		public static function add_shortcode( $list_shortcode ) {
			$list_shortcode['rp_member'] = __CLASS__ . '::member';
			return $list_shortcode;
		}
		/**
		 * Get the shortcode content.
		 *
		 * @param array $atts
		 *
		 * @return string
		 */
		public static function get( $atts ) {
			return RP_Shortcodes::shortcode_wrapper( array(
				__CLASS__,
				'output',
			), $atts );
		}

		/**
		 * Output the shortcode.
		 *
		 * @param array $atts
		 */
		public static function output( $atts ) {
			global $wp;

			if ( ! is_user_logged_in() || RP_Agent::is_admin() ) {

				if ( isset( $wp->query_vars[ 'lost-password' ] ) ) {
					self::lost_password();
				} else {
					$type_action = '';
					if ( isset( $_GET[ 'action' ] ) && ! empty( $_GET[ 'action' ] ) ) {
						$type_action = rp_validate_data( $_GET[ 'action' ] );
					}

					if ( 'register' == $type_action ) {
						RP_Template::get_template( 'form-register.php', compact( 'atts', $atts ), '', RP_AGENT_TEMPLATES . '/member' );
					} else {
						RP_Template::get_template( 'form-login.php', compact( 'atts', $atts ), '', RP_AGENT_TEMPLATES . '/member' );
					}
				}
			} else {

				if ( RP_Agent::is_agent() ) {
					$url_redirect = home_url();
				}

				?>
                <script type="text/javascript">
					window.location.replace("<?php echo esc_url( $url_redirect ) ?>");
                </script>
				<?php
			}
		}

		/**
		 * My account page shortcode.
		 *
		 * @param mixed $atts
		 *
		 * @return string
		 */
		public static function member( $atts ) {
			return self::shortcode_wrapper( array(
				'RP_Shortcode_Member',
				'output',
			), $atts );
		}

	}

	new RP_Shortcode_Member();

endif;
