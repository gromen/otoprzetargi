<?php
/**
 * Field: Floor Plans
 */
if ( ! function_exists( 'rp_render_property_floor_plans' ) ) :

	function rp_render_property_floor_plans( $field = array(), $value = null, $show_front_end = true ) {

		if ( empty( $field ) ) {
			return false;
		}

		$floor_plan_default = array(
			'plan_title'       => '',
			'plan_bedrooms'    => '',
			'plan_bathrooms'   => '',
			'plan_price'       => '',
			'plan_size'        => '',
			'plan_description' => '',
			'floor_plan'       => '',
		);

		$floor_plans              = get_post_meta( $field[ 'property_id' ], 'floor_plans', true );
		$floor_plans              = ! empty( $floor_plans ) ? $floor_plans : array_merge( array( $floor_plan_default ), array( $floor_plan_default ) );
		if ( is_array( $floor_plans ) ) :
			foreach ( $floor_plans as $index => $floor_plan ) :
				$plan_title = ( array_key_exists( 'plan_title', $floor_plan ) && $index > 0 ) ? $floor_plan[ 'plan_title' ] : '';
				$plan_bedrooms    = ( array_key_exists( 'plan_bedrooms', $floor_plan ) && $index > 0 ) ? $floor_plan[ 'plan_bedrooms' ] : '';
				$plan_bathrooms   = ( array_key_exists( 'plan_bathrooms', $floor_plan ) && $index > 0 ) ? $floor_plan[ 'plan_bathrooms' ] : '';
				$plan_price       = ( array_key_exists( 'plan_price', $floor_plan ) && $index > 0 ) ? $floor_plan[ 'plan_price' ] : '';
				$plan_size        = ( array_key_exists( 'plan_size', $floor_plan ) && $index > 0 ) ? $floor_plan[ 'plan_size' ] : '';
				$plan_description = ( array_key_exists( 'plan_description', $floor_plan ) && $index > 0 ) ? $floor_plan[ 'plan_description' ] : '';
				$plan_image       = ( array_key_exists( 'plan_image', $floor_plan ) && $index > 0 ) ? $floor_plan[ 'plan_image' ] : '';

				?>
				<?php if ( $index > 0 ) {
				echo '<div class="rp-floor-plan-main">';
			} ?>
				<div <?php echo ( 0 == $index ) ? 'id="clone_element" style="display: none;" ' : '' ?>
						class="rp-floor-plans-wrap rp-md-12">
					<i class="remove-floor-plan rp-icon-remove <?php echo ( $index != 1 ) ? 'show-remove' : '' ?> "></i>
					<div id="rp-item-plan_title-wrap" class="rp-item-wrap ">
						<label><?php echo esc_html__( 'Plan Title', 'realty-portal-floor-plan' ); ?></label>
						<input type="text" name="floor_plans[<?php echo esc_attr( $index ) ?>][plan_title]"
						       value="<?php echo esc_attr( $plan_title ) ?>" />
					</div>

					<div class="rp-row">

						<div id="rp-item-plan_bedrooms-wrap" class="rp-item-wrap rp-md-6">
							<label><?php echo esc_html__( 'Plan Bedrooms', 'realty-portal-floor-plan' ); ?></label>
							<input type="text" name="floor_plans[<?php echo esc_attr( $index ) ?>][plan_bedrooms]"
							       value="<?php echo esc_attr( $plan_bedrooms ) ?>" />
						</div>

						<div id="rp-item-plan_bathrooms-wrap" class="rp-item-wrap rp-md-6">
							<label><?php echo esc_html__( 'Plan Bathrooms', 'realty-portal-floor-plan' ); ?></label>
							<input type="text" name="floor_plans[<?php echo esc_attr( $index ) ?>][plan_bathrooms]"
							       value="<?php echo esc_attr( $plan_bathrooms ) ?>" />
						</div>

					</div>

					<div class="rp-row">

						<div id="rp-item-plan_price-wrap" class="rp-item-wrap rp-md-6">
							<label><?php echo esc_html__( 'Plan Price', 'realty-portal-floor-plan' ); ?></label>
							<input type="text" name="floor_plans[<?php echo esc_attr( $index ) ?>][plan_price]"
							       value="<?php echo esc_attr( $plan_price ) ?>" />
						</div>

						<div id="rp-item-plan_size-wrap" class="rp-item-wrap rp-md-6">
							<label><?php echo esc_html__( 'Plan Size', 'realty-portal-floor-plan' ); ?></label>
							<input type="text" name="floor_plans[<?php echo esc_attr( $index ) ?>][plan_size]"
							       value="<?php echo esc_attr( $plan_size ) ?>" />
						</div>

					</div>

					<div id="rp-item-plan_description-wrap" class="rp-item-wrap ">
						<label><?php echo esc_html__( 'Plan Description', 'realty-portal-floor-plan' ); ?></label>
						<textarea
								name="floor_plans[<?php echo esc_attr( $index ) ?>][plan_description]"><?php echo esc_attr( $plan_description ) ?></textarea>
					</div>
					<?php
					/**
					 * Create element: Floor Plan
					 */
					$args_floor_image = array(
						'name'     => 'floor_plans[' . esc_attr( $index ) . '][plan_image]',
						'title'    => esc_html__( 'Plan Images', 'realty-portal-floor-plan' ),
						'type'     => 'upload_image',
						'btn_text' => esc_html__( 'Upload photo', 'realty-portal-floor-plan' ),
					);
					rp_create_element( $args_floor_image, $plan_image );
					?>
				</div>
				<?php if ( $index > 0 ) {
				echo '</div>';
			} ?>
				<?php
			endforeach;
			?>
			<div class="rp-clone-floor-plan">
				<div class="content-clone"></div>
				<button class="rp-button add-floor-plan" data-total="<?php echo count( $floor_plans ) ?>">
					<?php echo esc_html__( 'Add More', 'realty-portal-floor-plan' ) ?>
				</button>
			</div>
			<?php
		endif;
	}

	rp_add_custom_field_type( 'floor_plans', __( 'Floor Plans', 'realty-portal-floor-plan' ), array( 'form' => 'rp_render_property_floor_plans' ), array(
		'can_search' => false,
		'is_system'  => true,
	) );
endif;