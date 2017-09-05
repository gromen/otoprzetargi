<?php
/**
 * Template Name: Agent Contact
 *
 * @package Realty_Portal
 * @author  NooTeam <suppport@nootheme.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $agent;
if ( !is_object( $agent ) ) {
	return false;
}

$agent_id = $agent->ID;
?>
<div class="rp-box-contact">
	<h2 class="rp-title"><?php echo esc_html__( 'Contact me', 'realty-portal-agent' ); ?></h2>
	<div class="rp-box-form">
		<?php do_action( 'rp_before_contact_agent' ); ?>
		<form class="rp-validate-form rp-box-contact-agent">

			<?php do_action( 'rp_before_field_contact_agent' ); ?>

			<?php
			$args_name = array(
				'name'        => 'name',
				'title'       => esc_html__( 'Name', 'realty-portal-agent' ),
				'type'        => 'text',
				'placeholder' => esc_html__( 'Please enter your name...', 'realty-portal-agent' ),
				'validate'    => array(
					'data-validation'           => 'length',
					'data-validation-length'    => 'min4',
					'data-validation-help'      => esc_html__( '4 or more characters, letters and numbers', 'realty-portal-agent' ),
					'data-validation-error-msg' => esc_html__( 'Not be blank user name', 'realty-portal-agent' ),
				),
			);
			rp_create_element( $args_name, '' );

			$args_phone = array(
				'name'        => 'phone',
				'title'       => esc_html__( 'Phone', 'realty-portal-agent' ),
				'type'        => 'text',
				'placeholder' => esc_html__( 'Please enter your phone...', 'realty-portal-agent' ),
			);
			rp_create_element( $args_phone, '' );

			$args_user_email = array(
				'name'        => 'user_email',
				'title'       => esc_html__( 'Email', 'realty-portal-agent' ),
				'type'        => 'text',
				'placeholder' => esc_html__( 'Please enter your email...', 'realty-portal-agent' ),
				'validate'    => array(
					'data-validation' => 'email',
				),
			);
			rp_create_element( $args_user_email, '' );

			$args_message = array(
				'name'        => 'message',
				'title'       => esc_html__( 'Message', 'realty-portal-agent' ),
				'type'        => 'textarea',
				'placeholder' => esc_html__( 'Please enter your message...', 'realty-portal-agent' ),
				'validate'    => array(
					'data-validation'        => 'length',
					'data-validation-length' => 'min5',
				),
			);
			rp_create_element( $args_message, '' );
			?>

			<?php do_action( 'rp_after_field_contact_agent' ); ?>

			<div class="rp-form-action">
				<button type="submit" class="rp-submit">
					<span><?php echo esc_html__( 'Send Message', 'realty-portal-agent' ); ?></span>
					<i class="fa-li rp-icon-spinner fa-spin hide"></i>
				</button>
			</div>
			<?php if ( ! empty( $agent_id ) ) : ?>
				<input type="hidden" name="agent_id" value="<?php echo esc_attr( $agent_id ) ?>">
			<?php endif; ?>

			<?php if ( ! empty( $property_id ) ) : ?>
				<input type="hidden" name="property_id" value="<?php echo esc_attr( $property_id ) ?>">
			<?php endif; ?>

		</form><!-- /.rp-box-contact-agent -->
		<?php do_action( 'rp_after_contact_agent' ); ?>

	</div>
</div>