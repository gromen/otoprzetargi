<?php
$noo_site_layout = get_theme_mod( 'noo_site_layout', 'fullwidth' );
if( is_page() && noo_landmark_func_get_post_meta(get_the_ID(), '_noo_wp_page_hide_title') || is_front_page() ){
    return false;
}

/**
* Required library
*/
wp_enqueue_script('parallax');

list($heading, $archive_title, $archive_desc) = noo_landmark_func_get_page_heading();
if( ! empty($heading) ) :
	$heading_image = noo_landmark_func_get_page_heading_image();

$class = apply_filters( 'noo_page_heading_class', '');

?>
<?php if ( get_theme_mod( 'noo_page_heading_parallax', true ) && $noo_site_layout == 'fullwidth' ) : ?>
	<section class="noo-page-heading noo-parallax<?php if($class != '') echo esc_attr($class); ?>" data-parallax="scroll" data-image-src="<?php echo esc_url($heading_image) ?>">
<?php else : ?>
	<section class="noo-page-heading<?php if($class != '') echo esc_attr($class); ?>" style="background-image: url('<?php echo esc_url($heading_image) ?>')">
<?php endif; ?>
		<div class="noo-container">

			<div class="wrap-page-title">
			
		        <h1 class="page-title"><?php echo esc_html($heading); ?></h1>
				<?php
					if( get_theme_mod( 'noo_breadcrumbs', true ) && !is_search() ):
						noo_landmark_func_get_layout( 'breadcrumb' );
					endif;
				?>
			</div><!-- /.wrap-page-title -->

		</div><!-- /.container-boxed -->
	</section>
<?php endif; ?>
