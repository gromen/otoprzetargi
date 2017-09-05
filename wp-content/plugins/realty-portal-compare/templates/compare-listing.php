<?php
/**
 * Compare Listing
 *
 * This template can be overridden by copying it to yourtheme/realty-portal/compare-listing.php.
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

if ( isset( $_SESSION[ 'rp_compare_properties' ] ) && ! empty( $_SESSION[ 'rp_compare_properties' ] ) ) :

	$list_property = $_SESSION[ 'rp_compare_properties' ];
	if ( ( $key = array_search( 0, $list_property ) ) !== false ) {
		unset( $list_property[ $key ] );
	}

	if ( ! empty( $list_property ) ) :

		$args = array(
			'post_type'      => apply_filters( 'rp_property_post_type', 'rp_property' ),
			'post__in'       => $list_property,
			'post_status'    => 'publish',
			'posts_per_page' => sizeof( $list_property ),
		);

		$total_list_compare = count( $list_property );
		$class_compare      = ( ( $total_list_compare === 4 ) ? 'rp-md-15' : ( $total_list_compare === 3 ? 'rp-md-3' : ( $total_list_compare === 2 ? 'rp-md-4' : 'rp-md-6' ) ) );
		$compare_query      = New WP_Query( $args );
		if ( $compare_query->have_posts() ):
			?>
            <div class="rp-compare-listing">
                <div class="rp-compare-wrap">
                    <div class="rp-compare-item <?php echo esc_attr( $class_compare ) ?>"></div>
					<?php while ( $compare_query->have_posts() ): $compare_query->the_post();
						global $property; ?>
                        <div class="rp-compare-item <?php echo esc_attr( $class_compare ) ?>">

                            <a class="content-thumb" title="<?php echo $property->title(); ?>"
                               href="<?php echo $property->permalink() ?>">
                                <img src="<?php echo rp_thumb_src( $property->ID, 'rp-property-medium' ) ?>"
                                     alt="<?php echo $property->title(); ?>"/>
                            </a>

                            <h4 class="rp-compare-title">
                                <a class="content-thumb" title="<?php echo $property->title(); ?>"
                                   href="<?php echo $property->permalink() ?>">
									<?php echo $property->title(); ?>
                                </a>
                            </h4>

                            <div class="property-price">
                                <span><?php echo rp_property_price( $property->ID ) ?></span>
                            </div>

							<?php echo get_the_term_list( $property->ID, 'listing_type', '<div class="rp-compare-item-detail"><label>' . esc_html__( 'Type:', 'realty-portal-compare' ) . '</label><span>', ', ', '</span></div>' ); ?>

							<?php if ( ! empty( $city ) ) : ?>
                                <div class="rp-compare-item-detail">
                                    <label><?php echo esc_html__( 'City:', 'realty-portal-compare' ); ?></label>
                                    <span><?php echo esc_html( $city ) ?></span>
                                </div>
							<?php endif; ?>

							<?php echo get_the_term_list( $property->ID, 'listing_offers', '<div class="rp-compare-item-detail"><label>' . esc_html__( 'Offers:', 'realty-portal-compare' ) . '</label><span>', ', ', '</span></div>' ); ?>

                        </div><!-- /.rp-compare-item -->
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
                </div>
                <div class="rp-compare-list">

					<?php
					/**
					 * Show custom fields
					 */
					$custom_fields = rp_property_render_fields();
					unset( $custom_fields[ '' ] );

					foreach ( $custom_fields as $item ) {
						if ( ( isset( $item[ 'disable' ] ) && $item[ 'disable' ] !== '1' ) || ! isset( $item[ 'disable' ] ) ) {
							?>
                            <div class="compare-list-item">
                                <div class="<?php echo esc_attr( $class_compare ) ?> item-label"><?php echo esc_html( $item[ 'label' ] ); ?></div>

								<?php while ( $compare_query->have_posts() ) : $compare_query->the_post();
									global $property; ?>

									<?php
									echo '<div class="' . esc_attr( $class_compare ) . ' item-value">';
									if ( '_area' == $item[ 'name' ] ) {
										echo rp_get_property_area_html( $property->ID );
									} else {
										$name_field = apply_filters( 'rp_property_post_type', 'rp_property' ) . $item[ 'name' ];
										$value      = get_post_meta( $property->ID, esc_attr( $name_field ), true );
										if ( ! empty( $value ) ) {
											if ( is_array( $value ) ) {
												echo implode( ', ', $value );
											} else {
												echo esc_html( $value );
											}
										}
									}
									echo '</div>';
									?>
								<?php endwhile; ?>
								<?php wp_reset_postdata(); ?>
                            </div>
						<?php } ?>
					<?php } ?>
					<?php
					/**
					 * Show featured fields
					 */
					$features = (array) rp_render_featured_amenities();

					if ( ! empty( $features ) && is_array( $features ) ) {
						unset( $features[ '' ] );
						foreach ( $features as $field ) {

							if ( empty( $field[ 'label' ] ) ) {
								continue;
							}

							echo '<div class="compare-list-item">';

							echo '<div class="' . esc_attr( $class_compare ) . ' item-label">' . esc_html( $field[ 'label' ] ) . '</div>';

							while ( $compare_query->have_posts() ) : $compare_query->the_post();
								global $property;

								$name_field  = apply_filters( 'rp_property_post_type', 'rp_property' ) . $field[ 'name' ];
								$value_field = get_post_meta( $property->ID, esc_attr( $name_field ), true );
								echo '<div class="' . esc_attr( $class_compare ) . ' item-value">';
								if ( ! empty( $value_field ) ) {
									echo '<i class="rp-icon-ion-checkmark" aria-hidden="true"></i>';
								} else {
									echo '<i class="rp-icon-ion-close" aria-hidden="true"></i>';
								}
								echo '</div>';

							endwhile;
							wp_reset_postdata();
							echo '</div>';
						}
					}
					?>

                </div>
            </div>
		<?php endif; ?>

	<?php endif; ?>

<?php endif; ?>