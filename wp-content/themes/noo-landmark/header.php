<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<div class="site">

	<?php do_action( 'noo_before_main_site' ); ?>

	<header class="noo-header <?php noo_landmark_func_header_class(); ?>">
		<?php noo_landmark_func_get_layout( 'navbar' ); ?>
	</header>
<?php
if ( ! is_singular( 'noo_property' ) ) {
	noo_landmark_func_get_layout( 'heading' );
}
?>