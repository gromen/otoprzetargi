<?php
/**
 * Shortcode Visual: Property Detail
 *
 * @package     LandMark
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */
if ( ! function_exists( 'noo_shortcode_property_detail' ) ) :

	function noo_shortcode_property_detail( $atts ) {

		extract( shortcode_atts( array(
			'title'       => '',
			'property_id' => '',
			'url_video'   => '',
			'image'       => '',
		), $atts ) );

		/**
		 * Required library LightGallery
		 */
		wp_enqueue_style( 'lightgallery' );
		wp_enqueue_script( 'lightgallery' );

		/**
		 * Query
		 */
		if ( empty( $property_id ) ) {
			return false;
		}
		$property_id     = absint( $property_id );
		$property_detail = get_post( $property_id );
		ob_start();
		?>
		<div class="noo-property-detail">

			<div class="noo-thumbnail">
				<?php
				/**
				 * Check thumbnail
				 */
				$image = ( ! empty( $image ) ? noo_thumb_src_id( $image, 'full' ) : noo_thumb_src( $property_id, 'full' ) );

				echo '<a href="' . esc_attr( $url_video ) . '" title="' . get_the_title( $property_id ) . '" style="background-image: url(' . esc_attr( $image ) . ')">';
				echo '  <span><span></span></span>';
				echo '</a>';
				?>
			</div>

			<div class="noo-content">
				<?php
				/**
				 * Check title
				 */
				if ( ! empty( $title ) ) {
					echo '<h3 class="noo-title">' . esc_html( $title ) . '</h3>';
				}

				$enable_additional_details = get_theme_mod( 'noo_property_enable_additional_details', true );
				$enable_feature            = get_theme_mod( 'noo_property_enable_feature', true );

				if ( ! empty( $enable_additional_details ) || ! empty( $enable_feature ) ) {

					echo '<div class="noo-row">';
					if ( ! empty( $enable_additional_details ) ) {
						$custom_fields = rp_property_render_fields();
						unset( $custom_fields[ '' ] );
						?>
						<div class="noo-md-6 noo-property-box">
							<h3 class="noo-title-box">
								<?php echo esc_html__( 'Additional Details', 'noo-landmark' ); ?>
							</h3>
							<div class="noo-content-box">

								<?php
								if ( ! empty( $custom_fields ) ) :

									foreach ( $custom_fields as $field ) {
										if ( ! is_array( $field ) || ! array_key_exists( 'name', $field ) ) {
											continue;
										}
										$name_field  = 'noo_property' . $field[ 'name' ];
										$value_field = get_post_meta( $property_id, esc_attr( $name_field ), true );
										if ( $field[ 'type' ] == 'select' ) {
											$value_field = rp_conver_field_option( $value_field, $field[ 'value' ] );
										}
										if ( ! empty( $value_field ) ) {
											?>
											<div class="noo-content-box-item">
												<label><?php echo( isset( $field[ 'label_translated' ] ) ? $field[ 'label_translated' ] : $field[ 'label' ] ) ?></label>
												<span><?php
													if ( is_array( $value_field ) ) {
														echo implode( ', ', $value_field );
													} else {
														echo esc_html( $value_field );
													}
													?></span>
											</div>
											<?php
										}
									}
								endif; ?>

							</div>
						</div>
						<?php
					}
					if ( ! empty( $enable_feature ) ) {
						$custom_feature_fields = rp_render_featured_amenities();
						$total_features        = count( $custom_feature_fields );
						if ( $total_features > 0 ) :
							unset($custom_feature_fields['']);

							?>
							<div class="noo-md-6 noo-property-box">
								<?php
								if ( !empty( $custom_feature_fields ) ) :
									$i = 1;
									foreach ($custom_feature_fields as $field) {
										if ( !is_array( $field ) || !array_key_exists( 'name', $field ) ) {
											continue;
										}

										$name_field  = 'noo_property' . esc_attr( $field['name'] );
										$value_field = get_post_meta( $property_id, esc_attr( $name_field ), true );

										if ( empty( $value_field ) ) $i++;
									} ?>
									<?php
									if ( $i != $total_features) { ?>
										<h3 class="noo-title-box">
											<?php echo esc_html__( 'Feature', 'noo-landmark' ); ?>
										</h3>
									<?php } ?>
								<?php endif; ?>
								<div class="noo-content-box feature noo-row">

									<?php
									if ( !empty( $custom_feature_fields ) ) :
										foreach ($custom_feature_fields as $field) {

											if ( !is_array( $field ) || !array_key_exists( 'name', $field ) ) {
												continue;
											}
											$name_field  = 'noo_property' . esc_attr( $field['name'] );
											$value_field = get_post_meta( $property_id, esc_attr( $name_field ), true );

											if ( !empty( $value_field ) ) {
												echo '<div class="noo-md-6">';
												?>
												<div class="noo-content-box-item">
													<i class="fa fa-check" aria-hidden="true"></i>
													<label><?php echo(isset( $field['label_translated'] ) ? $field['label_translated'] : $field['label'])  ?></label>
												</div>
												<?php
												echo '</div>';

											}
										}
									endif; ?>

								</div><!-- /.noo-content-box .feature -->
							</div><!-- /.noo-property-box -->
						<?php endif;
					}
					echo '</div>';
				}
				?>
			</div>

		</div><!-- /.noo-property-detail -->
		<script type="text/javascript">
			jQuery(document).ready(function ( $ ) {
				$(".noo-property-detail .noo-thumbnail").lightGallery({
					thumbnail         : true,
					animateThumb      : true,
					showThumbByDefault: true
				});
			});
		</script>
		<?php
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

	add_shortcode( 'noo_property_detail', 'noo_shortcode_property_detail' );

endif;