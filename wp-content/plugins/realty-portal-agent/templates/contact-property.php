<?php
/**
 * Contact Property
 *
 * @package Realty_Portal
 * @author  NooTeam <suppport@nootheme.com>
 */
global $property;
if ( !is_object( $property ) ) {
	return false;
}
$agent_id = RP_Agent::is_agent();
?>
<div class="rp-box-contact">
    <div class="rp-box-form">
		<?php do_action( 'rp_before_contact_property' ); ?>
		<?php
		$cf7_id = RP_Property::get_setting( 'contact_email', 'property_contact_form', '' );
		$cf7_id = apply_filters( 'wpml_object_id', $cf7_id, 'wpcf7_contact_form1' );

		$contact_form = ! empty( $cf7_id ) ? wpcf7_contact_form( $cf7_id ) : false;
		?>
		<?php if ( $contact_form ) :
			echo '<div class="rp-box-contact-form-7">';
			$atts = array(
				'id'         => $cf7_id,
				'title'      => '',
				'html_id'    => '',
				'html_name'  => '',
				'html_class' => '',
				'output'     => 'form',
			);

			$form_html = $contact_form->form_html( $atts );

			if ( ! empty( $agent_id ) ) :
				$hidden_fields[] = '<input type="hidden" name="_wpcf7_agent_id" value="' . esc_attr( $agent_id ) . '">';
			endif;

			if ( ! empty( $property->ID ) ) :
				$hidden_fields[] = '<input type="hidden" name="_wpcf7_property_id" value="' . esc_attr( $property->ID ) . '">';
			endif;

			$form_html = str_replace( '</form></div>', implode( '', $hidden_fields ) . '</form></div>', $form_html );

			echo $form_html;
			echo '</div><!-- /.rp-box-contact-form-7 -->';
		else :
			?>
            <form class="rp-validate-form rp-box-contact-agent" id="rp-content-agent">

				<?php do_action( 'rp_before_field_contact_property' ); ?>

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

				<?php do_action( 'rp_after_field_contact_property' ); ?>

                <div class="rp-form-action">
                    <button type="submit" class="rp-submit rp-button">
                        <span><?php echo esc_html__( 'Send Message', 'realty-portal-agent' ); ?></span>
                        <i class="rp-icon-spinner fa-spin hide"></i>
                    </button>
                </div>
				<?php if ( ! empty( $property->agent_info() ) ) : ?>
                    <input type="hidden" name="agent_id" value="<?php echo $property->agent_info() ?>">
				<?php endif; ?>

				<?php if ( ! empty( $property->ID ) ) : ?>
                    <input type="hidden" name="property_id" value="<?php echo esc_attr( $property->ID ) ?>">
				<?php endif; ?>

            </form><!-- /.rp-box-contact-agent -->

		<?php endif; // === end check contact form ?>

		<?php do_action( 'rp_after_contact_property' ); ?>

    </div>
</div>