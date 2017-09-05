<?php
/**
 * Template Name: Box Property Video
 *
 * @package Realty_Portal
 * @author  NooTeam <suppport@nootheme.com>
 */
global $property;
if ( !is_object( $property ) ) {
	return false;
}

$video = get_post_meta( $property->ID, 'video', true );
if ( !empty( $video ) ) :
?>
<div class="rp-property-box">
	<h3 class="rp-title-box">
		<?php echo esc_html__( 'Property Video', 'realty-portal' ); ?>
	</h3>
	<div class="rp-content-box video">
		<?php echo rp_get_video( $video ); ?>
	</div>
</div>
<?php endif;