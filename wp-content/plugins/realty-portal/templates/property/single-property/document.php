<?php
/**
 * Template Name: Document Property
 *
 * @package Realty_Portal
 * @author  NooTeam <suppport@nootheme.com>
 */
global $property;
if ( !is_object( $property ) ) {
	return false;
}

$document = get_post_meta( $property->ID, 'document', true );
if ( !empty( $document ) ) : ?>
	<button onclick="window.location.href='<?php echo esc_attr( $document ) ?>'">
		<i class="rp-icon-ion-archive"></i>
		<?php echo esc_html__( 'Document', 'realty-portal' ) ?>
	</button>
<?php endif;