<?php
/**
 * Show content main shortcode pricing table
 *
 * This template can be overridden by copying it to yourtheme/realty-portal/shortcode/pricing-table.php.
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

if ( RP_MemberShip::is_membership() ) :

	$args = array(
		'post_type'           => apply_filters( 'rp_membership_post_type', 'rp_membership' ),
		'post_status'         => 'publish',
		'posts_per_page'      => - 1,
		'ignore_sticky_posts' => 1,
		'order'               => 'ASC',
	);
	$membership_query = new WP_Query( $args );

	/**
	 * Check and loop
	 */

	if ( $membership_query->have_posts() ) :
		echo '<div class="rp-pricing-table">';

		/**
		 * VAR
		 */
		$total_package     = absint( $membership_query->found_posts );
		$interval_text_arr = array(
			'day'   => array(
				esc_html__( 'Day', 'realty-portal-package' ),
				esc_html__( 'Days', 'realty-portal-package' ),
			),
			'week'  => array(
				esc_html__( 'Week', 'realty-portal-package' ),
				esc_html__( 'Weeks', 'realty-portal-package' ),
			),
			'month' => array(
				esc_html__( 'Month', 'realty-portal-package' ),
				esc_html__( 'Months', 'realty-portal-package' ),
			),
			'year'  => array(
				esc_html__( 'Year', 'realty-portal-package' ),
				esc_html__( 'Years', 'realty-portal-package' ),
			),
		);

		while ( $membership_query->have_posts() ) : $membership_query->the_post();
			$package_id         = get_the_ID();
			$title              = get_the_title();
			$interval           = get_post_meta( $package_id, apply_filters( 'rp_membership_post_type', 'rp_membership' ) . '_interval', true );
			$interval_unit      = get_post_meta( $package_id, apply_filters( 'rp_membership_post_type', 'rp_membership' ) . '_interval_unit', true );
			$interval_unit_text = isset( $interval_text_arr[ $interval_unit ] ) ? $interval_text_arr[ $interval_unit ] : array(
				'',
				'',
			);
			$class_unlimited    = '';

			$properties_num           = get_post_meta( $package_id, apply_filters( 'rp_membership_post_type', 'rp_membership' ) . '_properties_num', true );
			$properties_num_unlimited = get_post_meta( $package_id, apply_filters( 'rp_membership_post_type', 'rp_membership' ) . '_properties_num_unlimited', true );
			if ( ! empty( $properties_num_unlimited ) ) {

				$properties_num  = esc_html__( 'Unlimited', 'realty-portal-package' );
				$class_unlimited = 'unlimited';
			}
			$limit_property = ! empty( $properties_num ) ? sprintf( esc_html( '%s Properties', 'realty-portal-package' ), $properties_num ) : '';

			$featured_num   = get_post_meta( $package_id, apply_filters( 'rp_membership_post_type', 'rp_membership' ) . '_featured_num', true );
			$limit_featured = ( ! empty( $featured_num ) || $featured_num == 0 ) ? sprintf( esc_html( '%s Featured Properties', 'realty-portal-package' ), $featured_num ) : '';

			$after_price = $interval_unit_text[ 0 ];

			if ( absint( $interval ) > 1 ) {
				$after_price = absint( $interval ) . ' ' . $interval_unit_text[ 1 ];
			}
			$price             = Realty_Portal::get_post_meta( $package_id, apply_filters( 'rp_membership_post_type', 'rp_membership' ) . '_price', '0' );
			$price             = RP_Payment::format_price( $price, 'text' );
			$expired_date      = get_post_meta( $package_id, apply_filters( 'rp_membership_post_type', 'rp_membership' ) . '_expire', true );
			$expired_unit_date = get_post_meta( $package_id, apply_filters( 'rp_membership_post_type', 'rp_membership' ) . '_expire_unit', true );

			$package_highlighted = get_post_meta( $package_id, apply_filters( 'rp_membership_post_type', 'rp_membership' ) . '_package_highlighted', true );
			?>
			<div class="rp-columns">
			<ul class="rp-price">
				<li class="header<?php echo( ! empty( $package_highlighted ) ? ' highlighted ' : '' ); ?>"><?php echo esc_html( $title ); ?></li>
				<?php
				if ( ! empty( $price ) ) {
					echo '<li class="grey">';
					echo wp_kses( $price, rp_allowed_html() );
					if ( ! empty( $after_price ) ) {
						echo '<span class="after-price">' . esc_html( $after_price ) . '</span>';
					}
					echo '</li>';
				}
				?>
				<?php
				if ( ! empty( $limit_property ) ) {
					echo '<li class="limit-property">' . $limit_property . '</li>';
				}
				if ( ! empty( $limit_featured ) ) {
					echo '<li class="limit-featured">' . $limit_featured . '</li>';
				}
				if ( ! empty( $expired_date ) ) {
					echo '<li class="expired-date">' . sprintf( esc_html__( '%s %ss Listing Duration', 'realty-portal-package' ), $expired_date, $expired_unit_date ) . '</li>';
				}

				$limit_info_package = apply_filters( 'rp_limit_info_package', 5 );

				for ( $i = 1; $i < $limit_info_package; $i ++ ) {
					$additional = get_post_meta( $package_id, apply_filters( 'rp_membership_post_type', 'rp_membership' ) . '_additional_info_' . $i, true );
					if ( ! empty( $additional ) ) {
						echo '<li class="limit-featured">' . $additional . '</li>';
					}
				}
				?>
				<li class="pricing-action grey">
					<button class="rp-button" data-package-id="<?php echo esc_attr( $package_id ); ?>"
					        data-agent-id="<?php echo RP_Agent::is_agent(); ?>">
						<?php echo esc_html( $atts[ 'button_txt' ] ); ?>
						<i class="fa-li rp-icon-spinner fa-spin hide"></i>
					</button>
				</li>
			</ul>
			</div><?php
		endwhile;
		echo '</div><!-- /.rp-pricing-table -->';
		wp_reset_postdata();
		wp_reset_query();

	else :

		echo esc_html__( 'Sorry, no package matched your criteria.', 'realty-portal-package' );

	endif;

else :
	echo esc_html( 'Price list only works when you activate member', 'realty-portal-package' );
endif;