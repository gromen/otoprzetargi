<?php
/**
 * RP_My_Favorites_Process Class
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if ( ! class_exists( 'RP_My_Favorites_Process' ) ) :

	class RP_My_Favorites_Process {

		/**
		 *    Initialize class
		 */
		public function __construct() {

			add_action( 'wp_ajax_rp_favorites', 'RP_My_Favorites_Process::favorites' );
			add_action( 'wp_ajax_nopriv_rp_favorites', 'RP_My_Favorites_Process::favorites' );

			add_action( 'rp_property_list_more_action', 'RP_My_Favorites_Process::add_button_favorites', 5 );

			add_action( 'nre_nav_menu_profile_before', 'RP_My_Favorites_Process::add_menu_item', 15 );
		}

		/**
		 * Add button favorites
		 */
		public static function add_menu_item() {
			?>
			<li id="menu-item-favorites" class="menu-item-favorites">
				<a href="<?php echo RP_AddOn_My_Favorites::get_url_favorites(); ?>"><?php echo esc_html__( 'My Favorites', 'realty-portal-my-favorites' ); ?></a>
			</li>
			<?php
		}

		/**
		 * Add button favorites
		 *
		 * @param $property
		 */
		public static function add_button_favorites( $property ) {
			?>
			<span>
			<i class="rp-event favorites <?php echo self::get_favorites( 'icon' ); ?>"
			   data-id="<?php echo $property->ID; ?>" data-user="<?php echo RP_Agent::is_user(); ?>"
			   data-process="favorites" data-status="<?php echo self::get_favorites( 'class' ); ?>"
			   data-url="<?php echo RP_AddOn_My_Favorites::get_url_favorites(); ?>"></i>
			</span>
			<?php
		}

		/**
		 * Get status favorites
		 *
		 * @param bool $show
		 *
		 * @return bool|string
		 */
		public static function get_favorites( $show = false ) {
			global $property;
			$user_id        = rp_get_current_user( true );
			$list_favorites = get_user_meta( $user_id, 'is_favorites', true );
			if ( empty( $list_favorites ) ) {
				$list_favorites = array();
			}

			if ( ( in_array( $property->ID, array_map( 'intval', $list_favorites ) ) && ! empty( $list_favorites ) ) ) {
				$check_is_favorites = true;
			} else {
				$check_is_favorites = false;
			}

			switch ( $show ) {
				case 'class':
					$html = $check_is_favorites ? 'is_favorites' : 'add_favorites';
					break;

				case 'text':
					$html = $check_is_favorites ? esc_html__( 'View favorites', 'realty-portal-my-favorites' ) : esc_html__( 'Add to favorites', 'realty-portal-my-favorites' );
					break;

				case 'icon':
					$html = $check_is_favorites ? 'rp-icon-heart' : 'rp-icon-heart-o';
					break;

				default:
					$html = $check_is_favorites;
					break;
			}

			return $html;
		}

		/**
		 * Check page agent profile
		 *
		 * @return string
		 */
		public static function is_favorites() {
			global $post;

			if ( isset( $post->ID ) && ! empty( $post->ID ) ) {
				$page_id = Realty_Portal::get_setting( 'agent_setting', 'page_my_favorites', '' );
				if ( $page_id == $post->ID ) {
					return true;
				}
			}

			return false;
		}

		public static function favorites() {

			/**
			 * Check security
			 */
			check_ajax_referer( 'rp-favorites', 'security', esc_html__( 'Security Breach! Please contact admin!', 'realty-portal-my-favorites' ) );

			/**
			 * Validate $_POST
			 */
			$_POST = wp_kses_post_deep( $_POST );

			/**
			 * Process
			 */
			$property_id = rp_validate_data( $_POST[ 'property_id' ] );

			if ( ! empty( $property_id ) ) {

				$status  = rp_validate_data( $_POST[ 'status' ] );
				$user_id = rp_validate_data( $_POST[ 'user_id' ] );

				$is_favorites            = get_user_meta( $user_id, 'is_favorites', true );
				$check_is_favorites      = ( ! empty( $is_favorites ) && is_array( $is_favorites ) ) ? true : false;
				$list_property_favorites = $check_is_favorites ? $is_favorites : array();

				if ( 'add_favorites' == $status ) {

					$list_property_favorites[] = $property_id;

					$response[ 'message' ] = esc_html__( 'Add favorites success!', 'realty-portal-my-favorites' );
				} elseif ( $status === 'is_favorites' ) {

					if ( ( $key = array_search( $property_id, $list_property_favorites ) ) !== false ) {
						unset( $list_property_favorites[ $key ] );
					}

					$response[ 'message' ] = esc_html__( 'Remove favorites success!', 'realty-portal-my-favorites' );
				}

				update_user_meta( $user_id, 'is_favorites', array_unique( $list_property_favorites ) );
				$response[ 'status' ] = 'success';
			} else {

				$response[ 'status' ]  = 'error';
				$response[ 'message' ] = esc_html__( 'Not empty id property, please check again!', 'realty-portal-my-favorites' );
			}

			wp_send_json( $response );
		}

	}

	new RP_My_Favorites_Process();

endif;