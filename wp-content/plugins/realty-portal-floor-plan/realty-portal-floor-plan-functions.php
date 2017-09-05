<?php
/**
 * Show info detail list floor plan
 */

if ( ! function_exists( 'rp_list_floor_plan' ) ) :

	function rp_list_floor_plan( $property_id, $index = 1 ) {

		$floor_plans = get_post_meta( $property_id, 'floor_plans', true );
		if ( ! is_array( $floor_plans ) ) {
			return;
		}

		$floor_plans = array_values( $floor_plans );
		$floor_plan  = $floor_plans[ $index ];

		$plan_title       = array_key_exists( 'plan_title', $floor_plan ) ? $floor_plan[ 'plan_title' ] : '';
		$plan_bedrooms    = array_key_exists( 'plan_bedrooms', $floor_plan ) ? $floor_plan[ 'plan_bedrooms' ] : '';
		$plan_bathrooms   = array_key_exists( 'plan_bathrooms', $floor_plan ) ? $floor_plan[ 'plan_bathrooms' ] : '';
		$plan_price       = array_key_exists( 'plan_price', $floor_plan ) ? $floor_plan[ 'plan_price' ] : '';
		$plan_size        = array_key_exists( 'plan_size', $floor_plan ) ? $floor_plan[ 'plan_size' ] : '';
		$plan_description = array_key_exists( 'plan_description', $floor_plan ) ? $floor_plan[ 'plan_description' ] : '';
		$plan_image       = array_key_exists( 'plan_image', $floor_plan ) ? $floor_plan[ 'plan_image' ] : '';
		if ( ! empty( $plan_title ) ) {
			?>
			<div class="rp-property-floor-plan-item rp-row">
				<span class="loading rp-icon-spinner fa-spin"></span>
				<div class="rp-left rp-md-5">
					<div class="rp-property-floor-plan">
						<div class="rp-property-floor-plan-wrapper">
							<?php
							if ( is_array( $plan_image ) ) {
								foreach ( $plan_image as $image ) { ?>
									<a class="floor-plan-item"
									   href="<?php echo esc_attr( rp_thumb_src_id( $image, 'full', '178x126' ) ) ?>">
										<img src="<?php echo esc_attr( rp_thumb_src_id( $image, 'full', '178x126' ) ) ?>"
										     alt="<?php the_title() ?>"/>
									</a>
								<?php }
							}
							?>
						</div>
						<div class="rp-arrow-button">
							<i class="rp-arrow-back rp-icon-ion-ios-arrow-left"></i>
							<i class="rp-arrow-next rp-icon-ion-ios-arrow-right"></i>
						</div>
					</div><!-- /.rp-property-floor-plan -->
				</div>
				<div class="rp-right rp-md-7">
					<div class="floor-plan-content rp-row">
						<?php
						if ( ! empty( $plan_size ) ) {
							echo '<div class="rp-md-6">' . sprintf( wp_kses( __( '<strong>Size:</strong> %s', 'realty-portal-floor-plan' ), rp_allowed_html() ), $plan_size ) . '</div>';
						}

						if ( ! empty( $plan_bedrooms ) ) {
							echo '<div class="rp-md-6">' . sprintf( wp_kses( __( '<strong>Rooms:</strong> %s', 'realty-portal-floor-plan' ), rp_allowed_html() ), $plan_bedrooms ) . '</div>';
						}

						if ( ! empty( $plan_bathrooms ) ) {
							echo '<div class="rp-md-6">' . sprintf( wp_kses( __( '<strong>Baths:</strong> %s', 'realty-portal-floor-plan' ), rp_allowed_html() ), $plan_bathrooms ) . '</div>';
						}

						if ( ! empty( $plan_price ) ) {
							echo '<div class="rp-md-6">' . sprintf( wp_kses( __( '<strong>Price:</strong> %s', 'realty-portal-floor-plan' ), rp_allowed_html() ), $plan_price ) . '</div>';
						}
						?>
					</div>

					<?php
					if ( ! empty( $plan_description ) ) {
						echo sprintf( wp_kses( __( '<p class="floor-plan-description">%s</p>', 'realty-portal-floor-plan' ), rp_allowed_html() ), $plan_description );
					}
					?>
				</div>
			</div><!-- /.rp-property-floor-plan-item -->
			<script>
				jQuery(document).ready(function ($) {
					jQuery('body').find(".rp-property-floor-plan-wrapper").lightGallery({
						thumbnail: true,
						animateThumb: true,
						showThumbByDefault: true
					});
				});
			</script>
			<?php
		}
	}

endif;

/**
 * Save data floor plan
 *
 * @package       Floor_Plan
 * @author        NooTeam <suppport@nootheme.com>
 * @version       0.1
 */

if ( ! function_exists( 'rp_save_floor_plan' ) ) :

	function rp_save_floor_plan( $property_id ) {

		if ( isset( $_POST[ 'floor_plans' ] ) && is_array( $_POST[ 'floor_plans' ] ) ) {
			update_post_meta( $property_id, 'floor_plans', array_values( $_POST[ 'floor_plans' ] ) );
		}
	}

	add_action( 'save_post', 'rp_save_floor_plan' );

endif;