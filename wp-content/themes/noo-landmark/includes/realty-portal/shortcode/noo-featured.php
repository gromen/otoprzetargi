<?php
/**
 * Create shortcode: [noo_featured]
 *
 * @package     Noo_LandMark_Core_2
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */

if ( ! function_exists( 'noo_shortcode_featured' ) ) :

	function noo_shortcode_featured( $atts ) {

		extract( shortcode_atts( array(
			'title'     => '',
			'sub_title' => '',
			'id'        => '',
			'caption'   => esc_html__( 'Featured', 'noo-landmark' ),
		), $atts ) );

		ob_start();

		?>
		<div class="noo-sc-caption noo-featured" data-caption="<?php echo esc_attr( $caption ); ?>">
			<?php
			if ( ! empty( $title ) ) {
				echo '<h3 class="noo-title">' . esc_html( $title ) . '</h3>';
			}

			if ( ! empty( $sub_title ) ) {
				echo '<span class="noo-sub-title">' . esc_html( $sub_title ) . '</span>';
			}

			if ( ! empty( $id ) && isset( $id ) ) {
				global $post;
				$post    = get_post( $id );
				$address = get_post_meta( $id, 'address', true );

				$primary_field_1      = rp_get_data_field( $id, Realty_Portal::get_setting( 'primary_field', 'primary_field_1', '_area' ) );
				$primary_field_icon_1 = rp_get_data_field_icon( Realty_Portal::get_setting( 'primary_field', 'primary_field_icon_1', 'icon-ruler' ) );

				$primary_field_2      = rp_get_data_field( $id, Realty_Portal::get_setting( 'primary_field', 'primary_field_2', '_bedrooms' ) );
				$primary_field_icon_2 = rp_get_data_field_icon( Realty_Portal::get_setting( 'primary_field', 'primary_field_icon_2', 'icon-bed' ) );

				$primary_field_3      = rp_get_data_field( $id, Realty_Portal::get_setting( 'primary_field', 'primary_field_3', '_garages' ) );
				$primary_field_icon_3 = rp_get_data_field_icon( Realty_Portal::get_setting( 'primary_field', 'primary_field_icon_3', 'icon-storage' ) );

				$primary_field_4      = rp_get_data_field( $id, Realty_Portal::get_setting( 'primary_field', 'primary_field_4', '_bathrooms' ) );
				$primary_field_icon_4 = rp_get_data_field_icon( Realty_Portal::get_setting( 'primary_field', 'primary_field_icon_4', 'icon-bath' ) );

				$price = rp_property_price( $id );
				/**
				 * Get Thumbnail
				 */
				$id_img = get_post_thumbnail_id( $id );
				$img    = wp_get_attachment_image_url( $id_img, 'large' );
				?>

				<div class="noo-featured-main">
					<div class="noo-meta">
						<h3 class="title">
							<?php echo esc_html( the_title() ); ?>
						</h3>
						<span class="location">
                                <i class="fa fa-map-marker"></i>
							<?php echo esc_html( $address ); ?>
                            </span>
						<span class="price"><?php echo wp_kses( $price, noo_allowed_html() ); ?></span>
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
					<div class="box-image">
						<div class="box-image-inner">
							<div class="box-image-content" style="background-image: url('<?php echo esc_url( $img ) ?>');">
							</div>
						</div>
					</div>

					<div class="noo-view">
						<?php
						if ( ! empty( $post->post_content ) && $excerpt = wp_trim_words( $post->post_content, 30, '...' ) ) {
							echo '<p>' . esc_html( $excerpt ) . '</p>';
						}
						echo '<a class="noo-readmore" href="' . esc_url( get_permalink() ) . '">' . esc_html__( 'Read more', 'noo-landmark' ) . '</a>';
						?>
					</div>
				</div><!-- End /.noo-featured-main -->

				<?php
			} else {
				echo '<p>' . esc_html__( 'Nothings!', 'noo-landmark' ) . '</p>';
			}

			?>
		</div>

		<?php $html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

	add_shortcode( 'noo_featured', 'noo_shortcode_featured' );

endif;