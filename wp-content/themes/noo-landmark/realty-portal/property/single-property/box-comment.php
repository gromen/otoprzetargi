<?php
/**
 * Box Comment
 *
 * @package Realty_Portal
 * @author  NooTeam <suppport@nootheme.com>
 */
$enable_comment = get_theme_mod( 'noo_property_enable_comment', true );

if ( empty( $enable_comment ) ) {
	return false;
}
?>
<div class="rp-property-comment noo-property-comment">
	<div class="noo-md-6 noo-property-box list-comment">
		<?php RP_Template::get_template( 'property/comment/list-comment.php' ); ?>
	</div>
	<div class="noo-md-6 noo-property-box form-comment">
		<?php RP_Template::get_template( 'property/comment/form-comment.php' ); ?>
	</div>
</div>