<?php
/**
 * Show form contact & email
 *
 * @author         NooTeam <suppport@nootheme.com>
 * @version        0.1
 */
if ( ! function_exists( 'rp_form_contact_email_settings' ) ) :

	function rp_form_contact_email_settings() {

		$custom_fields_contact_email   = array();
		$custom_fields_contact_email[] = array(
			'title'       => esc_html__( 'CC all Property Emails to', 'realty-portal' ),
			'name'        => 'cc_mail_to',
			'type'        => 'text',
			'placeholder' => esc_html__( 'Enter your email...', 'realty-portal' ),
		);

		if ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) || defined( 'WPCF7_PLUGIN' ) ) {
			$cf7           = get_posts( 'post_type="wpcf7_contact_form"&numberposts=-1' );
			$contact_forms = array();
			if ( $cf7 ) {
				$contact_forms[] = esc_html__( 'None', 'realty-portal' );
				foreach ( $cf7 as $cform ) {
					$contact_forms[ $cform->ID ] = $cform->post_title;
				}

				$custom_fields_contact_email[] = array(
					'title'     => esc_html__( 'Custom Property Contact Form', 'realty-portal' ),
					'name'      => 'property_contact_form',
					'type'      => 'select',
					'options'   => $contact_forms,
					'translate' => true,
					'notice'    => '<p>' . esc_html__( 'Select a form you created with Contact Form 7 plugin to use for contact and send email on Property page.', 'realty-portal' ) . '</p>
								<p>' . esc_html__( 'Note:', 'realty-portal' ) . '</p>
								<p>' . esc_html__( ' - The contact form must include the fields: [your-name], [your-email] and [your-message]', 'realty-portal' ) . '</p>
								<p>' . esc_html__( ' - You can use the following tags in the email of that form: [agent-id], [agent-name] and [agent-url].', 'realty-portal' ) . '</p>',
				);

				$custom_fields_contact_email[] = array(
					'title'     => esc_html__( 'Custom Agent Contact Form', 'realty-portal' ),
					'name'      => 'agent_contact_form',
					'type'      => 'select',
					'options'   => $contact_forms,
					'translate' => true,
					'notice'    => '<p>' . esc_html__( 'Select a form you created with Contact Form 7 plugin to use for contact and send email on agent profile page.', 'realty-portal' ) . '</p>
								<p>' . esc_html__( 'Note:', 'realty-portal' ) . '</p>
								<p>' . esc_html__( ' - The contact form must include the fields: [your-name], [your-email] and [your-message]', 'realty-portal' ) . '</p>
								<p>' . esc_html__( ' - You can use the following tags in the email of that form: [agent-id], [agent-name] and [agent-url].', 'realty-portal' ) . '</p>',
				);
			}
		}

		rp_render_form_setting( array(
			'title'   => esc_html__( 'Contact & Email', 'realty-portal' ),
			'name'    => 'contact_email',
			'id_form' => 'tab-setting-contact-email',
			'fields'  => $custom_fields_contact_email,
		) );
	}

	add_action( 'RP_Tab_Setting_Content/Config_After', 'rp_form_contact_email_settings', 20 );

endif;