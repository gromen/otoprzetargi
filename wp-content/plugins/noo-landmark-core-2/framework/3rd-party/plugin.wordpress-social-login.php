<?php
/**
 * Create class Noo_Support_3rd_Party_WordPress_Social_Login
 * This class support all features of plugin WordPress Social Login
 *
 * @package     LandMark/Framework/3rd_Party
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */
if ( !class_exists( 'Noo_Support_3rd_Party_WordPress_Social_Login' ) ) :

    class Noo_Support_3rd_Party_WordPress_Social_Login {

        /**
         * A reference to an instance of this class.
         */
        private static $instance;

        /**
         * Returns an instance of this class. 
         */
        public static function get_instance() {

            if( null == self::$instance ) {
                self::$instance = new Noo_Support_3rd_Party_WordPress_Social_Login();
            } 
            return self::$instance;

        }

        /**
         * Initializes the plugin by setting filters and administration functions.
         */
        private function __construct() {
            
            if ( Noo_LandMark_Core_2::is_plugin_active( 'wordpress-social-login/wp-social-login.php' ) ) {

            	add_action( 'noo_form_login_before', array( &$this, 'wsl_login_form' ) );

            	add_action( 'register_form', array( &$this, 'register_form_extra_fields' ) );

            	add_action( 'register_new_user', array( &$this, 'save_data' ) );

				add_action( 'wsl_hook_process_login_after_wp_insert_user', array( &$this, 'save_data' ), 3, 10 );

            }

        }

        public function wsl_login_form() {
        	
        	do_action( 'wordpress_social_login' );

        }

        public function register_form_extra_fields() {

        	?>
        	<div class="noo-box-role" style="margin-bottom: 20px;">
				<p class="register-text">
					<?php echo esc_html__( 'Who you are', 'noo-landmark-core' ); ?>
				</p>
				<label class="radio" for="type_user" >
					<input id="type_user" type="radio" name="type_user" value="user" />
					<?php echo esc_html__( 'I\'m a normal user', 'noo-landmark-core' )?>
				</label><br/>
				<label class="radio" for="type_agent" >
					<input id="type_agent" type="radio" name="type_user" value="agent" checked="checked" />
					<?php echo esc_html__( 'I\'m an agent', 'noo-landmark-core' ); ?>
				</label>
			</div>
        	<?php

        }

        public function save_data( $user_id, $provider, $hybridauth_user_profile ) {

        	if ( isset( $_POST['type_user'] ) && !empty( $_POST['type_user'] ) ) {

        		$type_user = noo_validate_data( $_POST['type_user'] );

        		if ( $type_user == 'agent' ) {

        			$display_name = $hybridauth_user_profile->displayName;

					if( empty( $display_name ) ) {
						$display_name = $hybridauth_user_profile->firstName;
					}

					if( empty( $display_name ) ) {
						$display_name = strtolower( $provider ) . "_user";
					}

                    $agent_args = array(
                        'post_title'    => $display_name,
                        'post_content'  => '',
                        'post_status'   => 'publish',
                        'post_type'     => 'noo_agent'
                    );
                     
                    $agent_id = wp_insert_post( $agent_args );

                    update_user_meta( $user_id, '_associated_agent_id', $agent_id );

                    update_post_meta( $agent_id, '_associated_user_id', $user_id );

                    $info_user = get_user_by( 'id', $user_id );
                    update_post_meta( $agent_id, 'noo_agent_email', $info_user->user_email );
                    update_post_meta( $agent_id, 'noo_agent_facebook', '#' );
                    update_post_meta( $agent_id, 'noo_agent_twitter', '#' );
                    update_post_meta( $agent_id, 'noo_agent_google_plus', '#' );

                    wp_update_user( array( 'ID' => $user_id, 'role' => 'agent' ) );

                }

        	}

        }

    }

    add_action( 'plugins_loaded', array( 'Noo_Support_3rd_Party_WordPress_Social_Login', 'get_instance' ) );

endif;