<?php
/**
 * Nav Menu
 *
 * This template can be overridden by copying it to yourtheme/realty-portal/nav-menu.php.
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

<?php if ( is_user_logged_in() ) : ?>
	<div class="noo-box-menu">
		<div class="noo-avatar-agent">
			<img src="<?php echo RP_Agent::get_avatar() ?>" alt="*">
		</div>
		<ul class="noo-list-menu">
			<?php if ( class_exists( 'RP_AddOn_Agent_Profile' ) ) : ?>
				<li class="item-my-profile">
					<a class="noo-item" href="<?php echo RP_AddOn_Agent_Profile::get_url_agent_profile() ?>" title="<?php echo esc_html__( 'My Profile', 'noo-landmark' ) ?>">
						<i class="ion-person"></i>
						<?php echo esc_html__( 'My Profile', 'noo-landmark' ) ?>
					</a>
				</li>
			<?php endif; ?>

			<?php if ( class_exists( 'RP_AddOn_Agent_Dashboard' ) ) : ?>
				<li class="item-my-profile">
					<a class="noo-item" href="<?php echo RP_AddOn_Agent_Dashboard::get_url_agent_dashboard() ?>" title="<?php echo esc_html__( 'My Properties', 'noo-landmark' ) ?>">
						<i class="ion-person"></i>
						<?php echo esc_html__( 'My Properties', 'noo-landmark' ) ?>
					</a>
				</li>
			<?php endif; ?>

			<?php if ( class_exists( 'RP_AddOn_Submit_Property' ) ) : ?>
				<li class="item-my-profile">
					<a class="noo-item" href="<?php echo RP_AddOn_Submit_Property::get_url_submit_property() ?>" title="<?php echo esc_html__( 'Submit Property', 'noo-landmark' ) ?>">
						<i class="ion-android-settings"></i>
						<?php echo esc_html__( 'Submit Property', 'noo-landmark' ) ?>
					</a>
				</li>
			<?php endif; ?>

			<?php if ( class_exists( 'RP_AddOn_My_Favorites' ) ) : ?>
				<li class="item-my-profile">
					<a class="noo-item" href="<?php echo RP_AddOn_My_Favorites::get_url_favorites() ?>" title="<?php echo esc_html__( 'My Favorites', 'noo-landmark' ) ?>">
						<i class="ion-heart"></i>
						<?php echo esc_html__( 'My Favorites', 'noo-landmark' ) ?>
					</a>
				</li>
			<?php endif; ?>

			<li class="item-button">
				<a class="noo-button" href="<?php echo esc_url( wp_logout_url( apply_filters( 'noo_logout_redirect', home_url( '/' ) ) ) ); ?>" title="<?php echo esc_html__( 'Log Out', 'noo-landmark' ) ?>">
					<?php echo esc_html__( 'Log Out', 'noo-landmark' ) ?>
				</a>
			</li>
		</ul>
	</div>

	<?php
	$membership_info = RP_MemberShip::get_membership_info();
	if( !empty( $membership_info ) && $membership_info['type'] == 'membership' ) :
		$expired_date = $membership_info['data']['expired_date'];
		$type_payment = RP_Payment::get_payment_type();
		?>
		<div class="noo-box-menu noo-box-current-package">
			<h3 class="noo-box-title">
				<?php echo apply_filters( 'noo_label_your_current_package', esc_html__( 'Your Current Package', 'noo-landmark' ) ); ?>
			</h3>
			<ul class="noo-box-content">
				<li class="package-title">
					<?php echo esc_html( $membership_info['data']['package_title'] ); ?>
				</li>
				<li class="listing-included">
					<?php echo esc_html__( 'Number of properties: ', 'noo-landmark' ); ?>
					<?php echo $membership_info['data']['number_property'] == -1 ? esc_html__( 'Unlimited', 'noo-landmark' ) : esc_html( $membership_info['data']['number_property'] ); ?>
				</li>
				<li class="listing-remain">
					<?php echo esc_html__( 'Remain: ', 'noo-landmark' ); ?>
					<?php echo $membership_info['data']['remaining_properties'] == -1 ? esc_html__( 'Unlimited', 'noo-landmark' ) : esc_html( $membership_info['data']['remaining_properties'] ); ?>
				</li>
				<li class="featured-included">
					<?php echo esc_html__( 'Featured properties: ', 'noo-landmark' ); ?>
					<?php echo esc_html( $membership_info['data']['featured_included'] ); ?>
				</li>
				<li class="featured-remain"><?php echo esc_html__( 'Remain: ', 'noo-landmark' ); ?>
					<?php echo esc_html( $membership_info['data']['featured_remain'] ); ?>
				</li>
				<?php if( $expired_date === -1 ) : ?>
					<li class="package-expired">
						<?php echo esc_html__( 'Your package is expired', 'noo-landmark' ); ?>
					</li>
				<?php else: ?>
					<li class="expired-date">
						<?php echo esc_html__( 'Expiration date: ', 'noo-landmark' ); ?>
						<?php echo esc_html( $expired_date ); ?>
					</li>
				<?php endif; ?>
			</ul>
		</div><!-- /.noo-box-current-package -->

		<div class="noo-box-menu noo-box-your-package">
			<h3 class="noo-box-title">
				<?php echo apply_filters( 'noo_label_change_your_package', esc_html__( 'Change Your Package', 'noo-landmark' ) ); ?>
			</h3>
			<form id="noo-change-package rp-change-package" class="noo-box-content">
				<select class="noo-chosen" id="noo-item-package-id" name="package_id" data-placeholder="<?php echo esc_html__( 'Choose a Package', 'noo-landmark' ); ?>">
					<option value=""><?php echo esc_html__( 'Select Package', 'noo-landmark' ) ?></option>
					<?php
					$args = array(
						'post_type'        => 'noo_membership',
						'posts_per_page'   => -1,
						'post_status'      => 'publish',
						'suppress_filters' => 0
					);
					$packages = get_posts ($args );
					if( !empty( $packages ) ) {
						foreach ( $packages as $package ) {
							$plan_price = get_post_meta( $package->ID, 'noo_membership_price', true );
							echo '<option value="' . esc_attr( $package->ID ) . '">' . esc_html( $package->post_title ) . '</option>';
						}
					}
					?>
				</select>

				<?php
				if ( is_plugin_active( 'woocommerce/woocommerce.php' ) && ( $type_payment == 'woocommerce' ) ) {
					$label_button_change_package = apply_filters( 'label_button_change_package_woocommerce', esc_html__( 'Payment now', 'noo-landmark' ) );
				} else {
					$label_button_change_package = apply_filters( 'label_button_change_package_paypal', esc_html__( 'Pay with paypal', 'noo-landmark' ) );
				}
				?>
				<button type="submit" class="noo-button">
					<?php echo esc_html( $label_button_change_package ); ?>
					<i class="fa-li fa fa-spinner fa-spin hide"></i>
				</button>
				<input type="hidden" name="agent_id" value="<?php echo esc_attr( RP_Agent::is_agent() ); ?>" />
				<?php
				$url_membership_page = Realty_Portal::get_setting( 'agent_setting', 'membership_page', '' );
				$url_membership_page = ( !empty( $url_membership_page ) ? get_permalink( $url_membership_page ) : '' );
				if( !empty( $url_membership_page ) ) {
					echo '<a target="_blank" class="noo-more-detail" href="' . esc_attr( $url_membership_page ) . '">' . esc_html__( 'View more detail', 'noo-landmark' ) . '</a>';
				}
				?>

			</form>

		</div>

	<?php elseif( !empty( $membership_info ) && $membership_info['type'] == 'submission' ) : ?>
		<div class="noo-box-menu noo-box-paid-submission">
			<h3 class="noo-box-title">
				<?php echo apply_filters( 'noo_label_your_current_package', esc_html__( 'Paid Submission', 'noo-landmark' ) ); ?>
			</h3>
			<ul class="noo-box-content">
				<li class="package-title">
					<?php echo esc_html__( 'This site uses paid submission model.', 'noo-landmark' ); ?>
				</li>
				<li>
					<?php echo sprintf( esc_html__( 'Listing Price: %s', 'noo-landmark' ), ( $membership_info['data']['listing_price_text'] ) ); ?>
				</li>
				<li>
					<?php echo sprintf( esc_html__( 'Featured Price (extra): %s', 'noo-landmark' ), ( $membership_info['data']['featured_price_text'] ) ); ?>
				</li>
			</ul>
		</div>
	<?php endif; ?>
<?php endif; ?>