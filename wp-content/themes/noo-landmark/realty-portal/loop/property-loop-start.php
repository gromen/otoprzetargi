<?php
/**
 * Property Loop Start
 *
 * @package Realty_Portal
 * @author  NooTeam <suppport@nootheme.com>
 */
$display_style 		= get_theme_mod( 'noo_property_listing_style', 'style-grid' );
$property_layout    = get_theme_mod( 'noo_property_layout', 'sidebar' );
$class_grid		    = '';
if ( $display_style === 'style-grid' ) {
	$class_grid    = 'style-grid column';
}
?>
<div class="noo-list-property <?php echo esc_attr( $class_grid ); ?>">
