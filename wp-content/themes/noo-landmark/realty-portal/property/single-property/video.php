<?php
/**
 * Box Property Video
 *
 * @package Realty_Portal
 * @author  NooTeam <suppport@nootheme.com>
 */
global $post;

if ( !is_object( $post ) ) {
	return false;
}

$video = get_post_meta( $post->ID, 'video', true );
if ( !empty( $video ) ) :
?>
<div class="noo-property-box">
	<h3 class="noo-title-box">
		<?php echo esc_html__( 'Property Video', 'realty-portal' ); ?>
	</h3>
	<div class="noo-content-box video">
		<?php echo rp_get_video( $video ); ?>
	</div>
</div>
<?php endif;