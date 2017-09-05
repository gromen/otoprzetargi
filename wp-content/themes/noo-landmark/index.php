<?php
/**
 * The main template file
 *
 */

get_header();
?>

<div id="primary" class="content-area">
	<main id="main" class="site-main noo-container">
		<div class="noo-row">
			<div class="<?php noo_landmark_func_main_class(); ?>">
				<?php if ( have_posts() ) : ?>

					<?php do_action( 'noo_landmark_func_before_container_wrap' ); ?>

					<?php
					// Start the loop.
					while ( have_posts() ) : the_post();
						/*
						 * Include the Post-Format-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						get_template_part( 'content', get_post_format() );

						// End the loop.
					endwhile;
					?>

					<?php do_action( 'noo_landmark_func_after_container_wrap' ); ?>

					<?php
					if ( ! class_exists( 'RP_Property' ) || ( class_exists( 'RP_Property' ) && ! RP_Property::is_property() ) ) :
						noo_landmark_func_pagination_normal();
					endif;

				// If no content, include the "No posts found" template.
				else :
					get_template_part( 'content', 'none' );
				endif;
				?>
			</div>
			<?php get_sidebar(); ?>
		</div>


	</main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_footer(); ?>
