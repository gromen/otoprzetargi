<?php get_header(); ?>

<div id="primary" class="content-area">
	<?php do_action( 'noo_landmark_single_before' ) ?>
	<main id="main" class="site-main noo-container">
        <div class="noo-row">
            <div class="<?php noo_landmark_func_main_class(); ?>">
                <?php
                    // Start the loop.
                    while ( have_posts() ) : the_post();
                        /*
                         * Include the Post-Format-specific template for the content.
                         * If you want to override this in a child theme, then include a file
                         * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                         */
                        get_template_part( 'content', get_post_format() );  ?>

                        <?php
                        if( get_theme_mod('noo_blog_post_author_bio', false) !== false ):
                            noo_landmark_func_bio_author();
                        endif;
                        ?>
                        <?php noo_landmark_func_post_nav();?>
                        <?php

                        // If comments are open or we have at least one comment, load up the comment template.
                        if ( comments_open() || get_comments_number() ) :
                            comments_template();
                        endif;

                        // End the loop.
                    endwhile;
                ?>
            </div>
            <?php get_sidebar(); ?>
        </div>
    </main><!-- .site-main -->
	<?php do_action( 'noo_landmark_single_after' ) ?>
</div><!-- .content-area -->
<?php get_footer(); ?>