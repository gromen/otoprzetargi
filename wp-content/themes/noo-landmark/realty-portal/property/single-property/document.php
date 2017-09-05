<?php
/**
 * Document Property
 *
 * @package Realty_Portal
 * @author  NooTeam <suppport@nootheme.com>
 */
$enable_document = get_theme_mod( 'noo_property_enable_document', true );

if ( empty( $enable_document ) ) {
	return false;
}
global $post;
if ( !is_object( $post ) ) {
	return false;
}

$document = get_post_meta( $post->ID, 'document', true );
if ( !empty( $document ) ) : ?>
	<a href="#" title="<?php echo esc_html__( 'Document', 'noo-landmark' ) ?>" class="noo-button" onclick="window.location.href='<?php echo esc_attr( $document ) ?>; return false;'">
		<span class="ion-archive"></span>
		<?php echo esc_html__( 'Document', 'noo-landmark' ) ?>
	</a>
<?php endif;