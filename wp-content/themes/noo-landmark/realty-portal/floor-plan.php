<?php
/**
 * Box Floor Plan
 *
 * @package Realty_Portal
 * @author  NooTeam <suppport@nootheme.com>
 */
$enable_floor_plan = get_theme_mod( 'noo_property_enable_floor_plan', true );

if ( empty( $enable_floor_plan ) ) {
	return false;
}
global $property;
$floor_plans = get_post_meta( $property->ID, 'floor_plans', true );
if ( !empty( $floor_plans ) ) {

	wp_enqueue_style( 'lightgallery' );
	wp_enqueue_script( 'lightgallery' );

	if ( is_array( $floor_plans ) ) {
		$floor_plans = array_values( $floor_plans );
		if ( !empty( $floor_plans[1]['plan_title'] ) && array_key_exists( 'plan_title', $floor_plans[1] ) ) {
        ?>
		<div class="noo-property-box noo-property-floor-plan-wrap">
			<div class="noo-floor-plans-top">
				<h3 class="noo-title-box">
					<?php echo esc_html__( 'Floor Plan', 'noo-landmark' ); ?>
					<div class="noo-box-select">
						<span></span>
						<select name="rp_floor_plan" class="rp-switch-floor-plan noo-switch-floor-plan" data-property-id="<?php echo esc_attr( $property->ID ); ?>">
							<?php
							foreach ($floor_plans as $key => $floor_plan_item) {
								$plan_title      = array_key_exists( 'plan_title', $floor_plan_item ) ? $floor_plan_item['plan_title'] : '';
								if ( empty( $plan_title ) ) {
									continue;
								}
								echo "<option value='" . ( $key+1 ) . "'>{$plan_title}</option>";
							}
							?>
						</select>
					</div>
				</h3>
			</div>
			<div class="rp-floor-plans-content noo-floor-plans-content">
		    	<?php noo_landmark_custom_list_floor_plan( $property->ID ); ?>
		    </div>
		</div>
	    <?php
		}
 	}

} else {
	/**
	 * Support version < 1.2.0
	 */
	wp_enqueue_style( 'lightgallery' );
	wp_enqueue_script( 'lightgallery' );
	$floor_plan  = get_post_meta( $property->ID, 'floor_plan', true );
	if ( !empty( $floor_plan ) ) {
		$list_id_floor_plan = explode( ',', $floor_plan );
		if ( !empty( $list_id_floor_plan ) ) {
		?>

		<div class="noo-property-box noo-property-floor-plan-wrap">
			<h3 class="noo-title-box">
				<?php echo esc_html__( 'Floor Plan', 'noo-landmark' ); ?>
			</h3>
			<div class="noo-property-floor-plan">
				<div class="noo-property-floor-plan-wrapper">
					<?php foreach ( $list_id_floor_plan as $floor_plan ) : ?>
						<a class="floor-plan-item" href="<?php echo esc_attr( rp_thumb_src_id( $floor_plan, 'full', '178x126' ) ) ?>">
							<img src="<?php echo esc_attr( rp_thumb_src_id( $floor_plan, 'rp-property-floor-plan', '178x126' ) ) ?>" alt="<?php the_title() ?>" />
						</a>
					<?php endforeach; ?>
				</div>
				<div class="noo-arrow-button">
					<i class="noo-arrow-back ion-ios-arrow-left"></i>
			        <i class="noo-arrow-next ion-ios-arrow-right"></i>
				</div>
			</div><!-- /.noo-property-floor-plan -->
		</div><!-- /.noo-property-floor-plan-wrap -->
		<script type="text/javascript">
	        jQuery(document).ready(function($){
	            $(".noo-property-floor-plan-wrapper").lightGallery({
	                thumbnail:true,
	                animateThumb: true,
	                showThumbByDefault: true
	            });
	        });
	    </script>
		<?php
		}
	}
}