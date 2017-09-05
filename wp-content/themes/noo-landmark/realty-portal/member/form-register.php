<?php
/**
 * Form Register
 *
 * This template can be overridden by copying it to yourtheme/realty-portal/form-register.php.
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
 * rp_before_customer_register_form hook.
 *
 */
do_action( 'rp_before_customer_register_form' );
?>
<form method="POST" id="rp-form-register-<?php echo $post->ID ?>" class="rp-validate-form rp-form-register">

	<?php
	/**
	 * rp_before_customer_register_form_main hook.
	 *
	 */
	do_action( 'rp_before_customer_register_form_main' );
	?>
	<?php
	/**
	 * Create element: Full name
	 */
	$args_full_name = array(
		'name'        => 'full_name',
		'title'       => esc_html__( 'Full name', 'realty-portal-agent' ),
		'type'        => 'text',
		'placeholder' => esc_html__( 'Please enter Full name...', 'realty-portal-agent' ),
		'required'    => true,
		'validate'    => array(
			'data-validation'        => 'length',
			'data-validation-length' => 'min6',
			'data-validation-help'   => esc_html__( '6 or more characters, letters and numbers', 'realty-portal-agent' ),
		),
	);
	rp_create_element( $args_full_name, '' );

	/**
	 * Create element: User name
	 */
	$args_user_name = array(
		'name'        => 'user_name',
		'title'       => esc_html__( 'User name', 'realty-portal-agent' ),
		'type'        => 'text',
		'placeholder' => esc_html__( 'Please enter User name...', 'realty-portal-agent' ),
		'required'    => true,
		'validate'    => array(
			'data-validation'        => 'alphanumeric custom length',
			'data-validation-regexp' => '[A-Za-z0-9]{5,31}',
			'data-validation-length' => 'min6',
			'data-validation-help'   => esc_html__( '6 or more characters, letters and numbers', 'realty-portal-agent' ),
		),
	);
	rp_create_element( $args_user_name, '' );

	/**
	 * Create element: Password
	 */
	$args_password = array(
		'name'        => 'password',
		'title'       => esc_html__( 'Password', 'realty-portal-agent' ),
		'type'        => 'password',
		'placeholder' => esc_html__( 'Please enter Password...', 'realty-portal-agent' ),
		'strength'    => true,
		'validate'    => array(
			'data-validation'        => 'length strength',
			'data-validation-length' => 'min6',
			'data-validation-help'   => esc_html__( '6 or more characters, letters and numbers', 'realty-portal-agent' ),
			'validation-strength'    => '2',
		),
	);
	rp_create_element( $args_password, '' );

	/**
	 * Create element: Confirm Password
	 */
	$args_confirm_password = array(
		'name'        => 'confirm_password',
		'title'       => esc_html__( 'Confirm Password', 'realty-portal-agent' ),
		'type'        => 'password',
		'placeholder' => esc_html__( 'Please enter Confirm Password...', 'realty-portal-agent' ),
		'required'    => true,
		'validate'    => array(
			'data-validation'         => 'confirmation',
			'data-validation-confirm' => 'password',
		),
	);
	rp_create_element( $args_confirm_password, '' );

	/**
	 * Create element: Email Address
	 */
	$args_email_address = array(
		'name'        => 'email_address',
		'title'       => esc_html__( 'Email Address', 'realty-portal-agent' ),
		'type'        => 'text',
		'placeholder' => esc_html__( 'Please enter Email Address...', 'realty-portal-agent' ),
		'required'    => true,
		'validate'    => array(
			'data-validation'           => 'email',
			'data-validation-error-msg' => esc_html__( 'You did not enter a valid e-mail', 'realty-portal-agent' ),
		),
	);
	rp_create_element( $args_email_address, '' );
	?>

    <div id="rp-item-agree-term-of-service-wrap" class="rp-item-wrap">
        <div class="rp-item-checkbox">
            <input id="rp-item-agree-term-of-service" type="checkbox" name="agree_term_of_service" value="1"
                   class="required" checked data-validation="required"
                   data-validation-error-msg="<?php echo esc_html__( 'You are required to accept the terms', 'realty-portal-agent' ); ?>"/>
            <label for="rp-item-agree-term-of-service">
				<?php echo apply_filters( 'rp_txt_agree_term_of_service', wp_kses( sprintf( __( 'I have read and agree to the <a target="_blank" href="%s" title="Term of Service">Term of Service</a>.', 'realty-portal-agent' ), RP_AddOn_Agent::get_url_term_of_service() ), rp_allowed_html() ) ); ?>
            </label>
        </div>
    </div>

	<?php
	/**
	 * rp_after_customer_register_form_main hook.
	 *
	 */
	do_action( 'rp_after_customer_register_form_main' );
	?>

    <div class="rp-register-member-action">
        <button type="submit" name="register-account" class="rp-button">
			<?php echo esc_html__( 'Register', 'realty-portal-agent' ); ?>
            <i class="fa-li rp-icon-spinner fa-spin hide"></i>
        </button>
        <div class="notice"></div>
    </div>

    <div class="rp-box-extend">
        <span class="login-link"><?php echo sprintf( __( 'Already have an account? <a href="%s">Login Now <i class="rp-icon-long-arrow-right"></i></a>', 'realty-portal-agent' ), RP_AddOn_Agent::get_url_login() ) ?></span>
    </div>
</form>
<?php
/**
 * rp_after_customer_register_form hook.
 *
 */
do_action( 'rp_after_customer_register_form' );
?>
