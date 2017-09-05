<?php
/*
Template Name: Page with Left Sidebar
*/
?>
<?php get_header(); ?>


<div id="primary" class="content-area">
    <main id="main" class="site-main noo-container">
        <div class="noo-row">
            <?php get_sidebar(); ?>
            <div class="<?php noo_landmark_func_main_class(); ?>">
                <?php
                // Start the loop.
                while ( have_posts() ) : the_post();

                    // Include the page content template.
                    get_template_part( 'content', 'page' );

                    // If comments are open or we have at least one comment, load up the comment template.
                    if ( comments_open() || get_comments_number() ) :
                        comments_template();
                    endif;

                    // End the loop.
                endwhile;
                ?>
            </div>
        </div><!--/.row-->


    </main><!-- .site-main -->
</div><!-- .content-area -->


<?php get_footer(); ?>
