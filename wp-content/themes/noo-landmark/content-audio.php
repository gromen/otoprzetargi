
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="blog-item">
        
        <!--Start featured-->
        <?php if( noo_landmark_func_has_featured_content()) : ?>

            <div class="content-featured">
            
                <?php noo_landmark_func_featured_audio(); ?>
                <?php noo_landmark_func_tag_date(); ?>

            </div>
            
        <?php else: ?>
            <?php do_action( 'noo_landmark_func_no_content_featured' ); ?>
        <?php endif; ?>
        <!--Start end featured-->
        
        <?php get_template_part( 'template-content' ); ?>

    </div> <!-- /.blog-item -->
</article> <!-- /#post- -->