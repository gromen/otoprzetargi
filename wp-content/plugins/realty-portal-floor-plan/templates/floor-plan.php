<?php
/**
 * Box Floor Plan
 *
 * @package Realty_Portal
 * @author  NooTeam <suppport@nootheme.com>
 */
/**
 * Required library LightGallery
 */
global $property;
$floor_plans = get_post_meta( $property->ID, 'floor_plans', true );
if ( !empty( $floor_plans ) ) {
	
	wp_enqueue_style( 'lightgallery' );
	wp_enqueue_script( 'lightgallery' );
	
	if ( is_array( $floor_plans ) ) {
		$floor_plans = array_values( $floor_plans );
		if ( !empty( $floor_plans[1]['plan_title'] ) && array_key_exists( 'plan_title', $floor_plans[1] ) ) {
        ?>
		<div class="rp-property-box rp-property-floor-plan-wrap">
			<div class="rp-floor-plans-top">
				<h3 class="rp-title-box">
					<?php echo esc_html__( 'Floor Plan', 'realty-portal-floor-plan' ); ?>
					<div class="rp-box-select">
						<span></span>
						<select name="rp_floor_plan" class="rp-switch-floor-plan" data-property-id="<?php echo esc_attr( $property->ID ); ?>">
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
			<div class="rp-floor-plans-content">
		    	<?php rp_list_floor_plan( $property->ID ); ?>
		    </div>
		</div><!-- /.rp-property-floor-plan-wrap -->
	    <?php
		}
 	}              

} else {
	/**
	 * Support version < 1.2.0
	 */
	$floor_plan  = get_post_meta( $property->ID, 'floor_plan', true );
	if ( !empty( $floor_plan ) ) {
		$list_id_floor_plan = explode( ',', $floor_plan );
		if ( !empty( $list_id_floor_plan ) ) {
		?>

		<div class="rp-property-box rp-property-floor-plan-wrap">
			<h3 class="rp-title-box">
				<?php echo esc_html__( 'Floor Plan', 'realty-portal-floor-plan' ); ?>
			</h3>
			<div class="rp-property-floor-plan">
				<div class="rp-property-floor-plan-wrapper">
					<?php foreach ( $list_id_floor_plan as $floor_plan ) : ?>
						<a class="floor-plan-item" href="<?php echo esc_attr( rp_thumb_src_id( $floor_plan, 'full', '178x126' ) ) ?>">
							<img src="<?php echo esc_attr( rp_thumb_src_id( $floor_plan, 'rp-property-floor-plan', '178x126' ) ) ?>" alt="<?php the_title() ?>" />
						</a>
					<?php endforeach; ?>
				</div>
				<div class="rp-arrow-button">
					<i class="rp-arrow-back ion-ios-arrow-left"></i>
			        <i class="rp-arrow-next ion-ios-arrow-right"></i>
				</div>
			</div><!-- /.rp-property-floor-plan -->
		</div><!-- /.rp-property-floor-plan-wrap -->
		<script type="text/javascript">
	        jQuery(document).ready(function($){
	            $(".rp-property-floor-plan-wrapper").lightGallery({
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