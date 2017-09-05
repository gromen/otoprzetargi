<?php
/**
 * Header Gallery
 *
 * @package Realty_Portal
 * @author  NooTeam <suppport@nootheme.com>
 */
global $property;
if ( !is_object( $property ) ) {
	return false;
}

if ( ! empty( $property->get_list_photo() ) ) {
	wp_enqueue_style( 'slick' );
	wp_enqueue_script( 'slick' );
	$list_id_photo = $property->get_list_photo();
	?>
    <div class="rp-property-gallery">
		<?php
		/**
		 * rp_before_property_gallery hook.
		 *
		 */
		do_action( 'rp_before_property_gallery' );
		?>
        <div class="property-gallery-top">
			<?php foreach ( $list_id_photo as $id_gallery ) : ?>
                <div class="property-gallery-item">
                    <img src="<?php echo esc_attr( rp_thumb_src_id( $id_gallery, 'rp-property-slider', '1920x800' ) ) ?>"
                         alt="<?php the_title() ?>"/>
                </div>
			<?php endforeach; ?>
        </div><!-- /.property-gallery-top -->

        <div class="rp-property-gallery-thumbnail-wrap">

            <div class="property-gallery-thumbnail">

                <div class="property-gallery-thumbnail-list">

					<?php foreach ( $list_id_photo as $id_gallery ) : ?>
                        <div class="property-gallery-item">
                            <img src="<?php echo esc_attr( rp_thumb_src_id( $id_gallery, 'rp-property-small', '128x70' ) ) ?>"
                                 alt="<?php the_title() ?>"/>
                        </div>
					<?php endforeach; ?>

                </div><!-- /.property-gallery-thumbnail-list -->

            </div><!-- /.property-gallery-thumbnail -->

        </div><!-- /.rp-property-gallery-thumbnail-wrap -->
        <div class="rp-arrow-button">
            <i class="rp-arrow-back rp-icon-ion-ios-arrow-back"></i>
            <i class="rp-arrow-next rp-icon-ion-ios-arrow-forward"></i>
        </div>
		<?php
		/**
		 * rp_after_property_gallery hook.
		 *
		 */
		do_action( 'rp_after_property_gallery' );
		?>

    </div><!-- /.rp-property-gallery -->
	<?php
}
