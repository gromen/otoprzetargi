<?php
/*
Template Name: Full Width - Blank
*/
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<!-- Favicon-->
<?php 
	$favicon = noo_get_image_option('noo_custom_favicon', '');
	if ($favicon != ''): ?>
		<link rel="shortcut icon" href="<?php echo $favicon; ?>" />
	<?php
	endif; 
?>

<?php if ( defined('WPSEO_VERSION') ) : ?>
	<title><?php wp_title(''); ?></title>
<?php else : ?>
	<title><?php wp_title(' - ', true, 'left'); ?></title>
<?php endif; ?>

<!--[if lt IE 9]>
<script src="<?php echo NOO_FRAMEWORK_URI . '/vendor/respond.min.js'; ?>"></script>
<![endif]-->
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<div class="site">

		<div class="page_fullwidth">

			<!-- Begin The loop -->
			<?php if ( have_posts() ) : ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<?php if( ! noo_landmark_func_get_post_meta(get_the_ID(), '_noo_wp_page_hide_title', false) ) : ?>
						<?php noo_landmark_func_get_layout( 'heading' ); ?>
					<?php endif; ?>
					<?php the_content(); ?>
				<?php endwhile; ?>
			<?php endif; ?>
			<!-- End The loop -->

		</div><!--/.page_fullwidth-->

	</div> <!-- /#top.site -->
	<?php wp_footer(); ?>
</body>
</html>