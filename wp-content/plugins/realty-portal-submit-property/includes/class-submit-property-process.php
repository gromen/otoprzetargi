<?php
/**
 * RP_Submit_Property_Process Class
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if ( ! class_exists( 'RP_Submit_Property_Process' ) ) :

	class RP_Submit_Property_Process {

		/**
		 *    Initialize class
		 */
		public function __construct() {
			add_action( 'wp_ajax_rp_submit_property', 'RP_Submit_Property_Process::submit_property' );
			add_action( 'wp_ajax_nopriv_rp_submit_property', 'RP_Submit_Property_Process::submit_property' );
			add_action( 'nre_nav_menu_profile_before', 'RP_Submit_Property_Process::add_menu_item', 5 );
		}

		/**
		 * Add button submit property
		 */
		public static function add_menu_item() {
			?>
			<li id="menu-item-submit-property" class="menu-item-submit-property">
				<a href="<?php echo RP_AddOn_Submit_Property::get_url_submit_property(); ?>"><?php echo esc_html__( 'Submit Property', 'realty-portal-submit-property' ); ?></a>
			</li>
			<?php
		}

		public static function submit_property() {
			/**
			 * Check security
			 */
			check_ajax_referer( 'rp-property', 'security', esc_html__( 'Security Breach! Please contact admin!', 'realty-portal-submit-property' ) );

			/**
			 * Validate $_POST
			 */
			$_POST = wp_kses_post_deep( $_POST );

			$response = array();

			/**
			 * Process data
			 */
			if ( ! empty( $_POST[ 'process_property' ] ) ) {

				/**
				 * VAR
				 */
				$process_property = rp_validate_data( $_POST[ 'process_property' ] );
				$title            = rp_validate_data( $_POST[ 'title' ] );
				$description      = rp_validate_data( $_POST[ 'description' ] );
				$agent_id         = rp_validate_data( $_POST[ 'agent_id' ], 'int' );
				$user_id          = RP_Agent::id_user( $agent_id );
				$property_id      = rp_validate_data( $_POST[ 'property_id' ], 'int' );

				unset( $_POST[ 'title' ] );
				unset( $_POST[ 'description' ] );
				unset( $_POST[ 'property_id' ] );
				unset( $_POST[ 'process_property' ] );
				unset( $_POST[ 'action' ] );
				unset( $_POST[ 'security' ] );

				/**
				 * Data create post
				 */
				$user          = wp_get_current_user();
				$skip_role     = Realty_Portal::get_setting( 'property_setting', 'skip_role', 'administrator' );
				$allowed_roles = array_map( 'trim', explode( ',', $skip_role ) );
				$post_status   = 'pending';
				if ( array_intersect( $allowed_roles, $user->roles ) ) {
					$post_status = 'publish';
				}

				/**
				 * Check status approve
				 */
				$admin_approve = Realty_Portal::get_setting( 'agent_setting', 'admin_approve', 'add' );
				if ( ! empty( $admin_approve ) && $admin_approve == 'none' ) {
					$post_status = 'publish';
				}

				$args_data = array(
					'post_type'    => apply_filters( 'rp_property_post_type', 'rp_property' ),
					'post_title'   => $title,
					'post_content' => wp_kses_post( $description ),
					'post_author'  => $user_id,
					'post_status'  => esc_attr( $post_status ),
				);

				/**
				 * Process event create new property
				 */
				if ( 'create_property' == $process_property ) {

					$property_id = wp_insert_post( $args_data, true );

					if ( is_wp_error( $property_id ) ) {

						$response[ 'status' ]  = 'error';
						$response[ 'message' ] = esc_html__( 'Can\'t create new property, please contact administration!', 'realty-portal-submit-property' );
					} else {

						// Email
						$admin_email         = get_option( 'admin_email' );
						$site_name           = get_option( 'blogname' );
						$property_admin_link = admin_url( 'post.php?post=' . $property_id ) . '&action=edit';

						if ( $need_approve ) {
							$message .= sprintf( __( "A user has just submitted a listing on %s and it's now waiting for your approval. To approve or reject it, please follow this link: %s", 'realty-portal-submit-property' ), $site_name, $property_admin_link ) . "<br/><br/>";
							rp_mail( $admin_email, sprintf( __( '[%s] New submission needs approval', 'realty-portal-submit-property' ), $site_name ), $message );
						} else {
							$message .= sprintf( __( "A user has just submitted a listing on %s. You can check it at %s", 'realty-portal-submit-property' ), $site_name, $property_admin_link ) . "<br/><br/>";
							rp_mail( $admin_email, sprintf( __( '[%s] New property submission', 'realty-portal-submit-property' ), $site_name ), $message );
						}

						$response[ 'status' ]  = 'success';
						$response[ 'message' ] = esc_html__( 'Create property success!', 'realty-portal-submit-property' );
						$response[ 'url' ]     = apply_filters( 'rp_submit_property_redirect', home_url() );
					}

					do_action( 'rp_submit_property_create', $property_id, $agent_id );
				} elseif ( 'edit_property' == $process_property ) {

					$args_data[ 'ID' ] = $property_id;

					/**
					 * Check status approve
					 */
					if ( ! empty( $admin_approve ) && $admin_approve == 'all' ) {
						$args_data[ 'post_status' ] = 'pending';
					}

					wp_update_post( $args_data );

					$response[ 'status' ]  = 'success';
					$response[ 'message' ] = esc_html__( 'Update property success!', 'realty-portal-submit-property' );
					do_action( 'rp_submit_property_edit', $property_id, $agent_id );
				}

				if ( ! empty( $property_id ) ) {

					foreach ( $_POST as $key => $value ) {

						$key = rp_validate_data( $key );
						if ( ! is_array( $value ) ) {
							$value = rp_validate_data( $value );
						}

						switch ( $key ) {
							case 'offers':
								$value = array( absint( $value ) );
								wp_set_object_terms( $property_id, $value, 'listing_offers' );
								break;

							case 'type' :
								$value = array( absint( $value ) );
								wp_set_object_terms( $property_id, $value, 'listing_type' );
								break;

							case 'set_featured' :
								set_post_thumbnail( $property_id, $value );
								break;

							case 'floor_plan' :
							case 'property_photo' :
								$value = implode( ',', $value );
								update_post_meta( $property_id, $key, $value );
								break;

							case 'country' :
								update_post_meta( $agent_id, 'latest_country', $value );
								break;

							default:
								if ( is_array( $value ) ) {
									$value = array_values( $value );
								}

								update_post_meta( $property_id, $key, $value );

								if ( 'country' == $key ) {
									update_post_meta( $agent_id, 'latest_country', $value );
								}

								break;
						}
					}

					update_post_meta( $property_id, 'agent_responsible', $agent_id );
				} else {

					$response[ 'status' ]  = 'error';
					$response[ 'message' ] = esc_html__( 'Can\'t update property, please contact administration!', 'realty-portal-submit-property' );
				}
			} else {

				$response[ 'message' ] = esc_html__( 'Don\'t empty title, please check again!', 'realty-portal-submit-property' );
				$response[ 'status' ]  = 'error';
			}

			wp_send_json( $response );
		}

	}

	new RP_Submit_Property_Process();

endif;