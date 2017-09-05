<?php
/**
 * Form popup login
 *
 * @package 	LandMark
 * @author 		KENT <tuanlv@vietbrain.com>
 * @version 	1.0
 */

if ( ! function_exists( 'noo_form_popup_login' ) ) :
	
	function noo_form_popup_login() {

		if ( is_user_logged_in() ) return false;

		$get_url_page_term		    = Realty_Portal::get_setting( 'agent_setting', 'page_term_of_service', '' );
		$url_register_member        = get_permalink( noo_get_page_by_template( 'register-member.php' ) );
		$url_page_term_of_service 	= apply_filters(
				                        'noo_url_page_term_of_service',
				                        ( !empty( $get_url_page_term ) ? get_permalink( $get_url_page_term ) : '#' )
				                    );

		$button_register_member     = apply_filters( 'button_register_member', esc_html__( 'Register', 'noo-landmark-core' ) );
		?>
		<div id="noo-box-login" class="noo-box-login">
		    <button type="button" class="close" onclick="Custombox.close();">
		        <span>&times;</span><span class="sr-only"><?php echo esc_html__( 'Close', 'noo-landmark-core' ); ?></span>
		    </button>

		    <h4 class="title"><?php echo esc_html__( 'Login', 'noo-landmark-core' ); ?></h4>
			
			<form method="POST" class="noo-login-member-container">
				<?php
				/**
				 * @hook noo_form_login_before
				 */
				do_action( 'noo_form_login_before' );
				?>
				<div class="noo-login-member-wrap">
					<div class="noo-register-member-left">
						<?php
							/**
							 * Create element: User name
							 */
								$args_user_name = array(
									'name'         => 'user_name_login',
									'title'        => esc_html__( 'User name', 'noo-landmark-core' ),
									'type'         => 'text',
									'placeholder'  => esc_html__( 'Please enter User name...', 'noo-landmark-core' ),
									'required'     => true,
									'after_notice' => wp_kses( __( '<span>Do not empty user name, please enter your user name...</span>', 'noo-landmark-core' ), noo_allowed_html() )
								);
								rp_create_element( $args_user_name, '' );

							/**
							 * Create element: Password
							 */
								$args_password = array(
									'name'         => 'password_login',
									'title'        => esc_html__( 'Password', 'noo-landmark-core' ),
									'type'         => 'password',
									'placeholder'  => esc_html__( 'Please enter Password...', 'noo-landmark-core' ),
									'required'     => true,
									'after_notice' => wp_kses( __( '<span>Do not empty password, please enter your password...</span>', 'noo-landmark-core' ), noo_allowed_html() )
								);
								rp_create_element( $args_password, '' );

						?>
						
					</div>
					
					<div class="noo-login-member-action">
						<button type="submit" name="login-account" class="noo-button">
							<?php echo esc_html__( 'Login', 'noo-landmark-core' ); ?>
							<i class="fa-li fa fa-spinner fa-spin hide"></i>
						</button>
						<div class="notice"></div>
						<p><?php echo wp_kses( sprintf( __( '<span>Not have an Account?</span> <a class="open-form-register" href="%s" title="%s">%s</a>', 'noo-landmark-core' ), $url_register_member, esc_html__( 'Register now!', 'noo-landmark-core' ), esc_html__( 'Register now!', 'noo-landmark-core' ) ), noo_allowed_html() ) ?></p>
					</div>

				</div>
				<?php
				/**
				 * @hook noo_form_login_after
				 */
				do_action( 'noo_form_login_after' );
				?>
	        </form><!--/.row-->

		    <form method="POST" class="noo-register-member-container">
				<div class="noo-register-member-wrap">
					<div class="noo-register-member-left">
						<?php
							/**
							 * Create element: Full name
							 */
								$args_full_name = array(
									'name'         => 'full_name',
									'title'        => esc_html__( 'Full name', 'noo-landmark-core' ),
									'type'         => 'text',
									'placeholder'  => esc_html__( 'Please enter Full name...', 'noo-landmark-core' ),
									'required'     => true,
									'after_notice' => wp_kses( __( '<span>6 or more characters, letters and numbers</span>', 'noo-landmark-core' ), noo_allowed_html() )
								);
								rp_create_element( $args_full_name, '' );

							/**
							 * Create element: User name
							 */
								$args_user_name = array(
									'name'         => 'user_name',
									'title'        => esc_html__( 'User name', 'noo-landmark-core' ),
									'type'         => 'text',
									'placeholder'  => esc_html__( 'Please enter User name...', 'noo-landmark-core' ),
									'required'     => true,
									'after_notice' => wp_kses( __( '<span>6 or more characters, letters and numbers</span>', 'noo-landmark-core' ), noo_allowed_html() )
								);
								rp_create_element( $args_user_name, '' );

							/**
							 * Create element: Password
							 */
								$args_password = array(
									'name'         => 'password',
									'title'        => esc_html__( 'Password', 'noo-landmark-core' ),
									'type'         => 'password',
									'placeholder'  => esc_html__( 'Please enter Password...', 'noo-landmark-core' ),
									'required'     => true,
									'after_notice' => wp_kses( __( '<span class="strength"></span><span>6 or more characters, letters and numbers.</span> <span>Must contain at least one number.</span>', 'noo-landmark-core' ), noo_allowed_html() )
								);
								rp_create_element( $args_password, '' );

							/**
							 * Create element: Confirm Password
							 */
								$args_confirm_password = array(
									'name'         => 'confirm_password',
									'title'        => esc_html__( 'Confirm Password', 'noo-landmark-core' ),
									'type'         => 'password',
									'placeholder'  => esc_html__( 'Please enter Confirm Password...', 'noo-landmark-core' ),
									'required'     => true,
									'after_notice' => wp_kses( __( '<span>The two passwords don\'t match.</span>', 'noo-landmark-core' ), noo_allowed_html() )
								);
								rp_create_element( $args_confirm_password, '' );

							/**
							 * Create element: Email Address
							 */
								$args_email_address = array(
									'name'         => 'email_address',
									'title'        => esc_html__( 'Email Address', 'noo-landmark-core' ),
									'type'         => 'text',
									'placeholder'  => esc_html__( 'Please enter Email Address...', 'noo-landmark-core' ),
									'required'     => true,
									'after_notice' => wp_kses( __( '<span>Invaild email address.</span>', 'noo-landmark-core' ), noo_allowed_html() )
								);
								rp_create_element( $args_email_address, '' );

							/**
							 * Create element: Type
							 */
								$args_type = array(
									'name'        => 'type',
									'title'       => esc_html__( 'Type', 'noo-landmark-core' ),
									'type'        => 'select',
									'placeholder' => esc_html__( 'Please choice Type...', 'noo-landmark-core' ),
									'required'    => true,
									'std'		  => 'user',
									'options'     => array(
										'user'  => esc_html__( 'User', 'noo-landmark-core' ),
										'agent' => esc_html__( 'Agent', 'noo-landmark-core' )
									),
									'after_notice' => wp_kses( __( '<span>Select the type of account.</span>', 'noo-landmark-core' ), noo_allowed_html() )
								);
								rp_create_element( $args_type, '' );
						?>
						
						<div id="noo-item-agree-term-of-service-wrap" class="noo-item-wrap">
							<div class="noo-item-checkbox">
		                        <div class="checked">
			                        <input id="noo-item-agree-term-of-service" type="checkbox" name="agree_term_of_service" value="1" class="required" checked />
									<label for="noo-item-agree-term-of-service"></label>
								</div>
								<label for="noo-item-agree-term-of-service"><?php echo apply_filters( 'noo_txt_agree_term_of_service', wp_kses( sprintf( __( 'I have read and agree to the <a target="_blank" href="%s" title="Term of Service">Term of Service</a>.', 'noo-landmark-core' ), esc_attr( $url_page_term_of_service ) ), noo_allowed_html() ) ); ?></label>
	                        </div>
	                    </div>
						
					</div>
					
					<div class="noo-register-member-action">
						<button type="submit" name="register-account" class="noo-button">
							<?php echo esc_html( $button_register_member ) ?>
							<i class="fa-li fa fa-spinner fa-spin hide"></i>
						</button>
						<div class="notice"></div>
						<p><?php echo wp_kses( sprintf( __( '<span>Already have an account?</span> <a class="open-form-login" href="%s" title="%s">%s</a>', 'noo-landmark-core' ), $url_register_member, esc_html__( 'Register now!', 'noo-landmark-core' ), esc_html__( 'Login now!', 'noo-landmark-core' ) ), noo_allowed_html() ) ?></p>
					</div>

				</div>
	        </form><!--/.row-->

		</div>

		<?php

	}

//	add_action( 'wp_footer', 'noo_form_popup_login' );

endif;