<?php
/**
 * Show content main shortcode agent profile
 *
 * This template can be overridden by copying it to yourtheme/realty-portal/shortcode/agent-profile.php.
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
?>

<?php if ( is_wp_error( RP_Agent::can_view() ) ) : ?>

	<?php rp_message_notices( RP_Agent::can_view() ); ?>

<?php else : ?>

	<?php
	$agent_id = RP_Agent::is_agent();
	?>
    <div class="rp-profile">
		<?php
		/**
		 * rp_before_main_agent_profile hook.
		 *
		 */
		do_action( 'rp_before_main_agent_profile' );
		?>

        <form method="POST" id="rp-agent-profile" class="rp-validate-form rp-agent-profile rp-content">
			<?php
			$args_name = array(
				'name'        => 'name',
				'title'       => esc_html__( 'Name', 'realty-portal-agent-profile' ),
				'type'        => 'text',
				'placeholder' => esc_html__( 'Please enter name...', 'realty-portal-agent-profile' ),
				'validate'    => array(
					'data-validation'        => 'length',
					'data-validation-length' => 'min6',
					'data-validation-help'   => esc_html__( '6 or more characters, letters and numbers', 'realty-portal-agent-profile' ),
				),
			);
			rp_create_element( $args_name, RP_Agent::get_value_submit( $agent_id, 'name' ) );

			$args_custom_field = array(
				'name'    => 'agent_custom_field',
				'type'    => 'agent_custom_field',
				'post_id' => $agent_id,
			);
			rp_create_element( $args_custom_field, '' );

			$args_avatar = array(
				'name'     => 'avatar',
				'title'    => esc_html__( 'Avatar', 'realty-portal-agent-profile' ),
				'type'     => 'upload_image',
				'btn_text' => esc_html__( 'Choose image', 'realty-portal-agent-profile' ),
				'slider'   => 'false',
				'notice'   => esc_html__( 'Recommended size: 268x210', 'realty-portal-agent-profile' ),
			);
			rp_create_element( $args_avatar, RP_Agent::get_value_submit( $agent_id, 'avatar' ) );

			$args_about = array(
				'name'        => 'about',
				'title'       => esc_html__( 'About', 'realty-portal-agent-profile' ),
				'type'        => 'textarea',
				'placeholder' => esc_html__( 'Please enter about...', 'realty-portal-agent-profile' ),
				'editor'      => array(
					'editor_height' => 280,
				),
			);
			rp_create_element( $args_about, RP_Agent::get_value_submit( $agent_id, apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_about' ) );

			$args_website = array(
				'name'        => 'website',
				'title'       => esc_html__( 'Website', 'realty-portal-agent-profile' ),
				'type'        => 'text',
				'placeholder' => esc_html__( 'Please enter website...', 'realty-portal-agent-profile' ),
			);
			rp_create_element( $args_website, RP_Agent::get_value_submit( $agent_id, apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_website' ) );

			$args_address = array(
				'name'        => 'address',
				'title'       => esc_html__( 'Address', 'realty-portal-agent-profile' ),
				'type'        => 'text',
				'placeholder' => esc_html__( 'Please enter address...', 'realty-portal-agent-profile' ),
			);
			rp_create_element( $args_address, RP_Agent::get_value_submit( $agent_id, apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_address' ) );

			$args_custom_social = array(
				'name'    => 'agent_social',
				'type'    => 'agent_social',
				'post_id' => $agent_id,
			);

			rp_create_element( $args_custom_social, '' );
			?>

            <input type="hidden" name="agent_id" value="<?php echo esc_attr( $agent_id ); ?>"/>
            <input type="hidden" name="process" value="update_profile"/>

            <div class="rp-profile-action">
                <button type="submit" class="rp-button">
					<?php echo esc_html__( 'Update', 'realty-portal-agent-profile' ); ?>
                    <i class="fa-li rp-icon-spinner fa-spin hide"></i>
                </button>
            </div>
        </form>

        <div class="rp-page-wrap agent-profile-password">
            <h2 class="rp-welcome"><?php echo esc_html__( 'Change password', 'realty-portal-agent-profile' ); ?></h2>
            <form method="POST" id="rp-agent-password" class="rp-validate-form rp-agent-profile rp-content">
                <div class="rp-notice"></div>
				<?php
				$args_old_password = array(
					'name'        => 'old_password',
					'title'       => esc_html__( 'Old password', 'realty-portal-agent-profile' ),
					'type'        => 'password',
					'placeholder' => esc_html__( 'Please enter old password...', 'realty-portal-agent-profile' ),
					'validate'    => array(
						'data-validation'        => 'length',
						'data-validation-length' => 'min6',
						'data-validation-help'   => esc_html__( 'Please enter your password...', 'realty-portal-agent-profile' ),
					),
				);
				rp_create_element( $args_old_password, '' );

				$args_new_password = array(
					'name'        => 'new_password',
					'title'       => esc_html__( 'New password', 'realty-portal-agent-profile' ),
					'type'        => 'password',
					'placeholder' => esc_html__( 'Please enter new password...', 'realty-portal-agent-profile' ),
					'strength'    => true,
					'validate'    => array(
						'data-validation'        => 'length strength',
						'data-validation-length' => 'min6',
						'data-validation-help'   => esc_html__( '6 or more characters, letters and numbers', 'realty-portal-agent-profile' ),
						'validation-strength'    => '2',
					),
				);
				rp_create_element( $args_new_password, '' );

				$args_confirm_new_password = array(
					'name'        => 'confirm_new_password',
					'title'       => esc_html__( 'Confirm new password', 'realty-portal-agent-profile' ),
					'type'        => 'password',
					'placeholder' => esc_html__( 'Please enter confirm new password...', 'realty-portal-agent-profile' ),
					'validate'    => array(
						'data-validation'         => 'confirmation',
						'data-validation-confirm' => 'password',
					),
				);
				rp_create_element( $args_confirm_new_password, '' );
				?>
                <input type="hidden" name="agent_id" value="<?php echo RP_Agent::is_agent(); ?>"/>
                <input type="hidden" name="process" value="update_password"/>

                <div class="rp-profile-action">
                    <button type="submit" class="rp-button">
						<?php echo esc_html__( 'Change password', 'realty-portal-agent-profile' ); ?>
                        <i class="fa-li rp-icon-spinner fa-spin hide"></i>
                    </button>
                </div>
            </form>
        </div>

		<?php
		/**
		 * rp_after_main_agent_profile hook.
		 *
		 */
		do_action( 'rp_after_main_agent_profile' );
		?>
    </div>

<?php endif; ?>
