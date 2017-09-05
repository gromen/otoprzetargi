<?php
/**
 * Product Loop Start
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-start.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

$classes = array();
$classes[] = 'products';

$porduct_layout = get_theme_mod('noo_shop_default_layout', 'grid');

if ( $porduct_layout != 'grid' ){
	$class_product = 'product-list';
} else {
	$class_product = 'product-grid';
}

if( is_product() ){
	$class_product = 'product-grid';
}

$classes[] = $class_product;

if ( get_theme_mod('noo_shop_use_masonry_layout', false) ) {
	wp_enqueue_script('imagesloaded');
	wp_enqueue_script('isotope');

	$classes[] = 'noo-product-masonry';
}
?>
<div class="noo-row">
	<ul class="<?php echo join( ' ', $classes ); ?>">