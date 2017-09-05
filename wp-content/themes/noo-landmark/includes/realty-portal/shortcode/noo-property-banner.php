<?php
/**
 * Shortcode Visual: Noo Property Banner
 *
 * @package     LandMark
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */
if ( ! function_exists( 'noo_shortcode_property_banner' ) ) :

	function noo_shortcode_property_banner( $atts, $content ) {
		extract( shortcode_atts( array(
			'image'        => '',
			'layout_style' => 'style-1',
			'property_id'  => '',
		), $atts ) );

		$address = get_post_meta( $property_id, 'address', true );

		$primary_field_1      = rp_get_data_field( $property_id, Realty_Portal::get_setting( 'primary_field', 'primary_field_1', '_area' ) );
		$primary_field_icon_1 = rp_get_data_field_icon( Realty_Portal::get_setting( 'primary_field', 'primary_field_icon_1', 'icon-ruler' ) );

		$primary_field_2      = rp_get_data_field( $property_id, Realty_Portal::get_setting( 'primary_field', 'primary_field_2', '_bedrooms' ) );
		$primary_field_icon_2 = rp_get_data_field_icon( Realty_Portal::get_setting( 'primary_field', 'primary_field_icon_2', 'icon-bed' ) );

		$primary_field_3      = rp_get_data_field( $property_id, Realty_Portal::get_setting( 'primary_field', 'primary_field_3', '_garages' ) );
		$primary_field_icon_3 = rp_get_data_field_icon( Realty_Portal::get_setting( 'primary_field', 'primary_field_icon_3', 'icon-storage' ) );

		$primary_field_4      = rp_get_data_field( $property_id, Realty_Portal::get_setting( 'primary_field', 'primary_field_4', '_bathrooms' ) );
		$primary_field_icon_4 = rp_get_data_field_icon( Realty_Portal::get_setting( 'primary_field', 'primary_field_icon_4', 'icon-bath' ) );

		$custom_style = ! empty( $image ) ? ' background-image: url(' . rp_thumb_src_id( $image, 'full', '1920x800' ) . ') ' : '';
		ob_start();
		?>
		<div class="noo-property-banner <?php echo esc_attr( $layout_style ); ?>" style="<?php echo esc_attr( $custom_style ); ?>">
			<div class="noo-container noo-property-box-meta-wrap">

				<?php if ( get_post_status( $property_id ) ) : ?>

					<div class="noo-property-box-meta">

						<div class="noo-property-box-meta-content">

							<?php
							$property_status = get_the_terms( $property_id, 'property_status' );

							if ( ! empty( $property_status ) && ! is_wp_error( $property_status ) ) {
								$types = array();
								foreach ( $property_status as $status ) {
									$types[] = $status->name;
								}
								echo '<span class="property-status">' . implode( ', ', $types ) . '</span>';
							}
							?>

							<div class="noo-item-head">

								<h1 class="item-title">
									<a href="<?php echo get_permalink( $property_id ); ?>" title="<?php echo get_the_title( $property_id ); ?>">
										<?php echo get_the_title( $property_id ); ?>
									</a>
								</h1>

								<?php if ( ! empty( $address ) ) : ?>
									<span class="location">
                                        <?php echo esc_html( $address ); ?>
                                    </span>
								<?php endif; ?>

							</div>

							<div class="noo-info">
								<?php if ( ! empty( $primary_field_1 ) ) : ?>
									<span class="noo-primary-file-1">
                                        <?php echo wp_kses( $primary_field_icon_1, noo_allowed_html() ); ?>
										<span><?php echo wp_kses( $primary_field_1, noo_allowed_html() ); ?></span>
                                    </span>
								<?php endif; ?>
								<?php if ( ! empty( $primary_field_2 ) ) : ?>
									<span class="noo-primary-file-2">
                                        <?php echo wp_kses( $primary_field_icon_2, noo_allowed_html() ); ?>
										<span><?php echo wp_kses( $primary_field_2, noo_allowed_html() ); ?></span>
                                    </span>
								<?php endif; ?>
								<?php if ( ! empty( $primary_field_3 ) ) : ?>
									<span class="noo-primary-file-3">
                                        <?php echo wp_kses( $primary_field_icon_3, noo_allowed_html() ); ?>
										<span><?php echo wp_kses( $primary_field_3, noo_allowed_html() ); ?></span>
                                    </span>
								<?php endif; ?>
								<?php if ( ! empty( $primary_field_4 ) ) : ?>
									<span class="noo-primary-file-4">
                                        <?php echo wp_kses( $primary_field_icon_4, noo_allowed_html() ); ?>
										<span><?php echo wp_kses( $primary_field_4, noo_allowed_html() ); ?></span>
                                    </span>
								<?php endif; ?>
							</div>

							<div class="noo-price">
								<?php echo rp_property_price( $property_id ); ?>
							</div>

						</div><!-- /.noo-property-box-meta-content -->

					</div><!-- /.noo-property-box-meta -->

				<?php endif; ?>

			</div><!-- /.noo-property-box-meta-wrap -->
		</div>
		<?php
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

	add_shortcode( 'noo_property_banner', 'noo_shortcode_property_banner' );

endif;