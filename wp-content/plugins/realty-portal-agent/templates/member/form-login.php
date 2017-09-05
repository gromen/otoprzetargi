<?php
/**
 * Form Login
 *
 * This template can be overridden by copying it to yourtheme/realty-portal/form-login.php.
 *
 * HOWEVER, on occasion NooTheme will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author        NooTheme
 * @version       0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post;
?>

<?php
/**
 * rp_before_customer_login_form hook.
 *
 */
do_action( 'rp_before_customer_login_form' );
?>
    <form method="POST" id="rp-form-login-<?php echo $post->ID ?>" class="rp-validate-form rp-form-login">

		<?php
		/**
		 * rp_before_customer_login_form_main hook.
		 *
		 */
		do_action( 'rp_before_customer_login_form_main' );
		?>
		<?php
		$args_user_name = array(
			'name'        => 'user_name_login',
			'title'       => esc_html__( 'User name', 'realty-portal-agent' ),
			'type'        => 'text',
			'placeholder' => esc_html__( 'Please enter User name...', 'realty-portal-agent' ),
			'required'    => true,
			'validate'    => array(
				'data-validation'        => 'custom length',
				'data-validation-regexp' => "^([a-z0-9]+)$",
				'data-validation-length' => 'min5',
			),
		);
		rp_create_element( $args_user_name, '' );

		$args_password = array(
			'name'        => 'password_login',
			'title'       => esc_html__( 'Password', 'realty-portal-agent' ),
			'type'        => 'password',
			'placeholder' => esc_html__( 'Please enter Password...', 'realty-portal-agent' ),
			'required'    => true,
			'validate'    => array(
				'data-validation'        => 'length',
				'data-validation-length' => 'min5',
			),
		);
		rp_create_element( $args_password, '' );

		?>

		<?php
		/**
		 * rp_after_customer_login_form_main hook.
		 *
		 */
		do_action( 'rp_after_customer_login_form_main' );
		?>

        <div class="rp-login-member-action">
            <button type="submit" name="login-account" class="rp-button">
				<?php echo esc_html__( 'Login', 'realty-portal-agent' ); ?>
                <i class="fa-li rp-icon-spinner fa-spin hide"></i>
            </button>
            <div class="notice"></div>
        </div>

        <div class="rp-box-extend">
            <a class="forgot-password" href="<?php echo RP_AddOn_Agent::get_url_forgot_password(); ?>"
               title="<?php echo esc_html__( 'Forgot Password?', 'realty-portal-agent' ); ?>">
                <i class="rp-icon-question-circle"></i>
				<?php echo esc_html__( 'Forgot Password?', 'realty-portal-agent' ); ?>
            </a>
			<?php if ( RP_Agent::can_register() ): ?>
                <div class="register-link">
					<?php echo sprintf( __( 'Don\'t have an account yet? <a href="%s">Register Now <i class="rp-icon-long-arrow-right"></i></a>', 'realty-portal-agent' ), RP_AddOn_Agent::get_url_register() ) ?>
                </div>
			<?php endif; ?>
        </div>
    </form>

<?php
/**
 * rp_after_customer_login_form hook.
 *
 */
do_action( 'rp_after_customer_login_form' );
?>