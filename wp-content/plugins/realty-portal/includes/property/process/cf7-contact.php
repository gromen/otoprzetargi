<?php
/**
 * This function process data when submit contact form
 *
 * @package       Realty_Portal
 * @author        NooTeam <suppport@nootheme.com>
 * @version       1.0
 */
if ( ! function_exists( 'rp_wpcf7_contact_agent' ) ) :

	function rp_wpcf7_contact_agent( $contact_form = null ) {
		if ( empty( $contact_form ) ) {
			return;
		}

		$submission = WPCF7_Submission::get_instance();

		if ( $submission ) {
			$posted_data = $submission->get_posted_data();
		}
		$no_html     = array();
		$agent_id    = isset( $posted_data[ '_wpcf7_agent_id' ] ) ? wp_kses( $posted_data[ '_wpcf7_agent_id' ], $no_html ) : '';
		$property_id = isset( $posted_data[ '_wpcf7_property_id' ] ) ? wp_kses( $posted_data[ '_wpcf7_property_id' ], $no_html ) : '';

		if ( ! empty( $agent_id ) && $agent = get_post( $agent_id ) ) {
			$name  = isset( $posted_data[ 'your-name' ] ) ? wp_kses( $posted_data[ 'your-name' ], $no_html ) : '';
			$email = isset( $posted_data[ 'your-email' ] ) ? wp_kses( $posted_data[ 'your-email' ], $no_html ) : '';
			// $message        = isset( $posted_data['your-message'] ) ? wp_kses( $posted_data['your-message'], $no_html ) : '';

			$mail = $contact_form->prop( 'mail' );

			$blogname          = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
			$agent_email       = get_post_meta( $agent_id, 'rp_agent_email', true );
			$mail[ 'subject' ] = ! empty( $mail[ 'subject' ] ) ? $mail[ 'subject' ] : sprintf( __( "[%s] New message from [%s]", 'realty-portal' ), $blogname, $name );
			$mail[ 'sender' ]  = ! empty( $mail[ 'sender' ] ) ? $mail[ 'sender' ] : $email;

			$mail[ 'recipient' ] = $agent_email;
			if ( ! empty( $property_id ) ) {

				$rp_cc_mail_to = RP_Property::get_setting( 'contact_email', 'cc_mail_to', '' );
				if ( ! empty( $rp_cc_mail_to ) ) {
					$mail[ 'recipient' ] .= ',' . $rp_cc_mail_to;
				}
			}

			$property_title = ! empty( $property_id ) ? get_the_title( $property_id ) : '';
			$property_link  = ! empty( $property_id ) ? get_permalink( $property_id ) : '';
			$agent_link     = get_permalink( $agent_id );

			if ( ! empty( $mail[ 'body' ] ) ) {
				$placeholders = array(
					'[property-id]',
					'[property-name]',
					'[property-url]',
					'[agent-id]',
					'[agent-name]',
					'[agent-url]',
				);
				$replaces     = array(
					$property_id,
					$property_title,
					$property_link,
					$agent_id,
					get_the_title( $agent_id ),
					$agent_link,
				);

				$mail[ 'body' ] = str_replace( $placeholders, $replaces, $mail[ 'body' ] );
			} else {
				$mail_body = '';
				$message   = isset( $posted_data[ 'your-message' ] ) ? wp_kses( $posted_data[ 'your-message' ], $no_html ) : '';
				if ( ! empty( $property_id ) ) {
					$mail_body = sprintf( __( "%s just sent you a message via %s's page", 'realty-portal' ), $name, $property_title ) . "\n\n";
					$mail_body .= __( "----------------------------------------------", 'realty-portal' ) . "\n\n";
					$mail_body .= $message . "\n\n";
					$mail_body .= __( "----------------------------------------------", 'realty-portal' ) . "\n\n";
					$mail_body .= sprintf( __( "You can reply to this email to respond or send email to %s", 'realty-portal' ), $email ) . "\n\n";
					$mail_body .= sprintf( __( "Check %s's details at %s", 'realty-portal' ), $property_title, $property_link ) . "\n\n";
				} else {
					$agent_link = get_permalink( $agent_id );

					$mail_body = sprintf( __( "%s just sent you a message via your profile", 'realty-portal' ), $name ) . "\n\n";
					$mail_body .= __( "----------------------------------------------", 'realty-portal' ) . "\n\n";
					$mail_body .= $message . "\n\n";
					$mail_body .= __( "----------------------------------------------", 'realty-portal' ) . "\n\n";
					$mail_body .= sprintf( __( "You can reply to this email to respond or send email to %s", 'realty-portal' ), $email ) . "\n\n";
					$mail_body .= sprintf( __( "Check your details at %s", 'realty-portal' ), $agent_link ) . "\n\n";
				}

				$mail[ 'body' ] = $mail_body;
			}

			$contact_form->set_properties( array( 'mail' => $mail ) );
		}
	}

	// contact form 7
	add_action( 'wpcf7_before_send_mail', 'rp_wpcf7_contact_agent' );
endif;
