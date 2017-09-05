<?php
/**
 * RP_Package_Config_Dashboard_Setting Class
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'RP_Package_Config_Dashboard_Setting' ) ) :

	class RP_Package_Config_Dashboard_Setting {

		/**
		 *    Initialize class
		 */
		public function __construct() {
			add_filter( 'RP_Tab_Setting/Config', 'RP_Package_Config_Dashboard_Setting::setting_agent', 15 );
			add_action( 'RP_Tab_Setting_Content/Config_After', 'RP_Package_Config_Dashboard_Setting::form_setting', 25 );
			add_filter( 'rp_agent_form_setting', 'RP_Package_Config_Dashboard_Setting::form_setting_agent' );

			add_action( 'rp_before_main_agent_profile', 'RP_Package_Config_Dashboard_Setting::menu_sidebar', 999999 );
		}

		public static function menu_sidebar() {
			$membership_info = RP_MemberShip::get_membership_info();
			if( !empty( $membership_info ) && $membership_info['type'] == 'membership' ) :
				$expired_date = $membership_info['data']['expired_date'];
				$type_payment = RP_Payment::get_payment_type();
				?>
				<div class="rp-box-menu">
					<h3 class="rp-box-title" style="margin-top: 30px">
						<?php echo apply_filters( 'noo_label_your_current_package', esc_html__( 'Your Current Package', 'realty-portal-package' ) ); ?>
					</h3>
					<ul class="rp-box-content">
						<li class="package-title">
							<?php echo esc_html( $membership_info['data']['package_title'] ); ?>
						</li>
						<li class="listing-included">
							<?php echo esc_html__( 'Number of properties: ', 'realty-portal-package' ); ?>
							<?php echo $membership_info['data']['number_property'] == -1 ? esc_html__( 'Unlimited', 'realty-portal-package' ) : esc_html( $membership_info['data']['number_property'] ); ?>
						</li>
						<li class="listing-remain">
							<?php echo esc_html__( 'Remain: ', 'realty-portal-package' ); ?>
							<?php echo $membership_info['data']['remaining_properties'] == -1 ? esc_html__( 'Unlimited', 'realty-portal-package' ) : esc_html( $membership_info['data']['remaining_properties'] ); ?>
						</li>
						<li class="featured-included">
							<?php echo esc_html__( 'Featured properties: ', 'realty-portal-package' ); ?>
							<?php echo esc_html( $membership_info['data']['featured_included'] ); ?>
						</li>
						<li class="featured-remain"><?php echo esc_html__( 'Remain: ', 'realty-portal-package' ); ?>
							<?php echo esc_html( $membership_info['data']['featured_remain'] ); ?>
						</li>
						<?php if( $expired_date === -1 ) : ?>
							<li class="package-expired">
								<?php echo esc_html__( 'Your package is expired', 'realty-portal-package' ); ?>
							</li>
						<?php else: ?>
							<li class="expired-date">
								<?php echo esc_html__( 'Expiration date: ', 'realty-portal-package' ); ?>
								<?php echo esc_html( $expired_date ); ?>
							</li>
						<?php endif; ?>
					</ul>
				</div><!-- /.rp-box-current-package -->

				<div class="rp-box-menu rp-box-your-package">
					<h3 class="rp-box-title" style="margin-top: 30px">
						<?php echo apply_filters( 'noo_label_change_your_package', esc_html__( 'Change Your Package', 'realty-portal-package' ) ); ?>
					</h3>
					<form id="rp-change-package rp-change-package" class="rp-box-content">
						<select class="rp-chosen" id="rp-item-package-id" name="package_id" data-placeholder="<?php echo esc_html__( 'Choose a Package', 'realty-portal-package' ); ?>">
							<option value=""><?php echo esc_html__( 'Select Package', 'realty-portal-package' ) ?></option>
							<?php
							$args = array(
								'post_type'        => 'rp_membership',
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
							$label_button_change_package = apply_filters( 'label_button_change_package_woocommerce', esc_html__( 'Payment now', 'realty-portal-package' ) );
						} else {
							$label_button_change_package = apply_filters( 'label_button_change_package_paypal', esc_html__( 'Pay with paypal', 'realty-portal-package' ) );
						}
						?>
						<button type="submit" class="rp-button">
							<?php echo esc_html( $label_button_change_package ); ?>
							<i class="fa-li fa fa-spinner fa-spin hide"></i>
						</button>
						<input type="hidden" name="agent_id" value="<?php echo esc_attr( RP_Agent::is_agent() ); ?>" />
						<?php
						$url_membership_page = Realty_Portal::get_setting( 'agent_setting', 'membership_page', '' );
						$url_membership_page = ( !empty( $url_membership_page ) ? get_permalink( $url_membership_page ) : '' );
						if( !empty( $url_membership_page ) ) {
							echo '<a target="_blank" class="rp-more-detail" href="' . esc_attr( $url_membership_page ) . '">' . esc_html__( 'View more detail', 'realty-portal-package' ) . '</a>';
						}
						?>

					</form>

				</div>

			<?php elseif( !empty( $membership_info ) && $membership_info['type'] == 'submission' ) : ?>
				<div class="rp-box-menu rp-box-paid-submission">
					<h3 class="rp-box-title">
						<?php echo apply_filters( 'noo_label_your_current_package', esc_html__( 'Paid Submission', 'realty-portal-package' ) ); ?>
					</h3>
					<ul class="rp-box-content">
						<li class="package-title">
							<?php echo esc_html__( 'This site uses paid submission model.', 'realty-portal-package' ); ?>
						</li>
						<li>
							<?php echo sprintf( esc_html__( 'Listing Price: %s', 'realty-portal-package' ), ( $membership_info['data']['listing_price_text'] ) ); ?>
						</li>
						<li>
							<?php echo sprintf( esc_html__( 'Featured Price (extra): %s', 'realty-portal-package' ), ( $membership_info['data']['featured_price_text'] ) ); ?>
						</li>
					</ul>
				</div>
			<?php endif;
		}

		/**
		 * Show html setting agent
		 *
		 * @param $list_tab
		 *
		 * @return array
		 */
		public static function setting_agent( $list_tab ) {

			$list_tab[] = array(
				'name'     => esc_html__( 'Payment', 'realty-portal-package' ),
				'id'       => 'tab-setting-payment',
				'position' => 30,
			);

			return $list_tab;
		}

		/**
		 * Show form setting
		 */
		public static function form_setting() {
			$field = array();
			if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {

				$field[] = array(
					'title'   => esc_html__( 'Select type of payment', 'realty-portal-package' ),
					'name'    => 'payment_type',
					'type'    => 'radio',
					'std'     => 'paypal',
					'options' => array(
						'paypal'      => esc_html__( 'PayPal', 'realty-portal-package' ),
						'woocommerce' => esc_html__( 'WooCommerce', 'realty-portal-package' ),
					),
					'class'   => 'box-payment-type',
				);
			}

			$field[] = array(
				'title' => esc_html__( 'PayPal Merchant Account (ID or Email)', 'realty-portal-package' ),
				'name'  => 'merchant_account',
				'type'  => 'text',
				'class' => 'payment_type type_paypal',
			);

			$field[] = array(
				'title' => esc_html__( 'Enable PayPal Sandbox Testing', 'realty-portal-package' ),
				'name'  => 'enable_sandbox',
				'type'  => 'checkbox',
				'std'   => '1',
				'class' => 'payment_type type_paypal',
			);

			$field[] = array(
				'title' => esc_html__( 'Disable SSL secure connection (Not recommended)', 'realty-portal-package' ),
				'name'  => 'disable_ssl',
				'type'  => 'checkbox',
				'class' => 'payment_type type_paypal',
			);

			$field[] = array(
				'title' => esc_html__( 'Email for sending payment notification', 'realty-portal-package' ),
				'name'  => 'notify_email',
				'type'  => 'text',
				'class' => 'payment_type type_paypal',
			);

			rp_render_form_setting( array(
				'title'   => esc_html__( 'Payment Setting', 'realty-portal-package' ),
				'name'    => 'payment_setting',
				'id_form' => 'tab-setting-payment',
				'fields'  => $field,
			) );
		}

		public static function form_setting_agent( $list_form ) {
			$list_form_new = array(
				array(
					'name' => 'agent_linebreak',
					'type' => 'line',
				),
				array(
					'title'   => esc_html__( 'Membership Type', 'addon-agent' ),
					'name'    => 'membership_type',
					'type'    => 'radio',
					'std'     => 'free',
					'options' => array(
						'none'       => esc_html__( 'No Membership (Agents created by Admin can still submit Property)', 'addon-agent' ),
						'free'       => esc_html__( 'Free for all Users', 'addon-agent' ),
						'membership' => esc_html__( 'Membership Packages', 'addon-agent' ),
						'submission' => esc_html__( 'Pay per Submission', 'addon-agent' ),
					),
					'class'   => 'box-membership-type',
				),
				array(
					'title'  => esc_html__( 'Number of Expire Days', 'addon-agent' ),
					'name'   => 'per_listing_expire',
					'type'   => 'text',
					'std'    => 30,
					'notice' => esc_html__( 'No of days until a listings will expire. Starts from the moment the property is published on the website', 'addon-agent' ),
				),
				array(
					'title' => esc_html__( 'Enable Freemium Membership', 'addon-agent' ),
					'name'  => 'membership_free',
					'type'  => 'checkbox',
					'class' => ' type_membership box-free-membership',
				),
				array(
					'title' => esc_html__( 'Number of Free Properties', 'addon-agent' ),
					'name'  => 'membership_freemium_properties_num',
					'type'  => 'text',
					'class' => 'membership_type membership_free',
					'std'   => '6',
				),
				array(
					'title' => esc_html__( 'Number of Free Featured Properties', 'addon-agent' ),
					'name'  => 'membership_freemium_featured_num',
					'type'  => 'text',
					'class' => 'membership_type membership_free',
					'std'   => '0',
				),
				array(
					'title' => esc_html__( 'Price per Submission', 'addon-agent' ),
					'name'  => 'membership_submission_listing_price',
					'type'  => 'text',
					'class' => 'membership_type type_submission',
				),
				array(
					'title' => esc_html__( 'Price for Featured Property', 'addon-agent' ),
					'name'  => 'membership_submission_featured_price',
					'type'  => 'text',
					'class' => 'membership_type type_submission',
				),
				array(
					'title' => esc_html__( 'Membership listing page (Page with pricing table)', 'addon-agent' ),
					'name'  => 'membership_page',
					'type'  => 'pages',
					'class' => 'membership_type type_membership',
				),
				array(
					'title'   => esc_html__( 'Submitted Properties need approve from admin?', 'addon-agent' ),
					'name'    => 'admin_approve',
					'type'    => 'radio',
					'std'     => 'add',
					'options' => array(
						'all'  => esc_html__( 'Yes, all newly added and edited properties', 'addon-agent' ),
						'add'  => esc_html__( 'Yes, but only newly submitted properties', 'addon-agent' ),
						'none' => esc_html__( 'Don\'t need Admin approval', 'addon-agent' ),
					),
					'class'   => 'membership_type type_free type_membership type_submission',
				),
			);

			return array_merge( $list_form, $list_form_new );
		}

	}

	new RP_Package_Config_Dashboard_Setting();

endif;