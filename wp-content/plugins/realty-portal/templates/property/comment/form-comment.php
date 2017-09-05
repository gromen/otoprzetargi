<?php
/**
 * Box Form Comment
 *
 * @package Realty_Portal
 * @author  NooTeam <suppport@nootheme.com>
 */
wp_enqueue_script( 'rp-rating' );

$title_box_comment       = apply_filters( 'rp_property_title_box_comment', esc_html__( 'Your Review', 'realty-portal' ) );
$button_text_box_comment = apply_filters( 'rp_property_button_text_box_comment', esc_html__( 'Submit', 'realty-portal' ) );

$current_user = rp_get_current_user();

$user_name  = is_user_logged_in() ? $current_user->display_name : '';
$user_email = is_user_logged_in() ? $current_user->user_email : '';

$enable_allows_guests_review = get_theme_mod( 'rp_property_enable_allows_guests_review', true );
?>
<h3 class="rp-title-box">
	<?php echo wp_kses( $title_box_comment, rp_allowed_html() ); ?>
</h3>

<form class="rp-validate-form rp-property-form-comment-wrap" id="rp-form-comment">

	<?php
	/**
	 * Check enable allows guests review
	 */
	if ( ! empty( $enable_allows_guests_review ) || is_user_logged_in() ) :
		?>
        <div class="rp-property-form-comment">

			<?php do_action( 'rp_before_property_form_comment' ); ?>

			<?php
			$args_user_name = array(
				'name'        => 'user_name',
				'title'       => esc_html__( 'Name', 'realty-portal' ),
				'type'        => 'text',
				'placeholder' => esc_html__( 'Please enter your name...', 'realty-portal' ),
				'validate'    => array(
					'data-validation'           => 'length',
					'data-validation-length'    => 'min4',
					'data-validation-help'      => esc_html__( '4 or more characters, letters and numbers', 'realty-portal' ),
					'data-validation-error-msg' => esc_html__( 'Not be blank user name', 'realty-portal' ),
				),
			);
			rp_create_element( $args_user_name, $user_name );

			$args_user_email = array(
				'name'        => 'user_email',
				'title'       => esc_html__( 'Email', 'realty-portal' ),
				'type'        => 'text',
				'placeholder' => esc_html__( 'Please enter your email...', 'realty-portal' ),
				'validate'    => array(
					'data-validation' => 'email',
				),
			);
			rp_create_element( $args_user_email, $user_email );

			$args_user_msg = array(
				'name'        => 'user_msg',
				'title'       => esc_html__( 'Comment', 'realty-portal' ),
				'type'        => 'textarea',
				'placeholder' => esc_html__( 'Please enter your comment...', 'realty-portal' ),
				'validate'    => array(
					'data-validation'        => 'length',
					'data-validation-length' => 'min5',
				),
			);
			rp_create_element( $args_user_msg, '' );
			?>

			<?php RP_Template::get_template( 'property/comment/rating.php' ); ?>

			<?php do_action( 'rp_after_property_form_comment' ); ?>

        </div>

        <button class="rp-button" type="submit">
			<?php echo wp_kses( $button_text_box_comment, rp_allowed_html() ); ?>
            <i class="rp-icon-spinner fa-spin hide"></i>
        </button>

        <input type="hidden" name="property_id" value="<?php echo get_the_ID() ?>"/>

		<?php
	/**
	 * Check enable allows guests review
	 */ else :
		if ( ! is_user_logged_in() ) {
			echo '<p class="rp-message">';
			echo esc_html__( 'Only members are allowed to evaluate.', 'realty-portal' );
			echo '</p>';
		}
	endif; ?>

</form><!-- /.rp-property-form-comment-wrap -->