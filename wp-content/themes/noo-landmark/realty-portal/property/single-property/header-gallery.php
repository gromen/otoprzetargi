<?php
/**
 * Header Gallery
 *
 * @package Realty_Portal
 * @author  NooTeam <suppport@nootheme.com>
 */
global $post;
if ( !is_object( $post ) ) {
	return false;
}
$property_photo = get_post_meta( $post->ID, 'property_photo', true );
if ( isset( $property_photo ) && ! empty( $property_photo ) ) {
	wp_enqueue_style( 'swiper' );
	wp_enqueue_script( 'swiper' );

	$body_style = isset( $_GET[ 'content_style' ] ) ? $_GET[ 'content_style' ] : get_theme_mod( 'noo_property_post_content_style', 'default' );

	$list_id_photo = explode( ',', $property_photo );
	if ( ! empty( $list_id_photo ) ) {
		?>
		<div class="noo-property-gallery">

			<div class="property-gallery-top">
				<?php foreach ( $list_id_photo as $id_gallery ) : ?>
					<div class="property-gallery-item">
						<img src="<?php echo esc_attr( noo_thumb_src_id( $id_gallery, 'rp-property-slider', '1920x800' ) ) ?>" alt="<?php the_title() ?>" />
					</div>
				<?php endforeach; ?>
			</div><!-- /.property-gallery-top -->

			<div class="noo-property-gallery-thumbnail-wrap">

				<div class="noo-container property-gallery-thumbnail">

					<div class="noo-row property-gallery-thumbnail-list">

						<?php foreach ( $list_id_photo as $id_gallery ) : ?>
							<div class="property-gallery-item">
								<img src="<?php echo esc_attr( noo_thumb_src_id( $id_gallery, 'rp-property-small', '128x70' ) ) ?>" alt="<?php the_title() ?>" />
							</div>
						<?php endforeach; ?>

					</div><!-- /.property-gallery-thumbnail-list -->

					<div class="noo-arrow-button">
						<i class="noo-arrow-back ion-ios-arrow-back"></i>
						<i class="noo-arrow-next ion-ios-arrow-forward"></i>
					</div>

				</div><!-- /.property-gallery-thumbnail -->

			</div><!-- /.noo-property-gallery-thumbnail-wrap -->

		</div><!-- /.noo-property-gallery -->
		<?php
	}
}
