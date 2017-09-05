<?php get_header(); ?>

<div class="main-wrap">
	
	<div class="noo-container main-content">


        <div id="search-results">

            <?php if(have_posts()) : while(have_posts()) : the_post(); ?>

                <?php if( get_post_type($post->ID) == 'post' ){ ?>
                    <article class="result hentry">
                        <div class="content-featured">
                            <?php noo_landmark_func_featured_content( $post->ID ); ?>
                        </div>
                        <div class="entry-header">
                            <h2 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> <small><?php echo esc_html__( 'Blog Post', 'noo-landmark'); ?></small></h2>
                        </div>
                        <div class="entry-content">
                            <?php if(get_the_excerpt()) the_excerpt(); ?>
                        </div>
                    </article><!--/search-result-->
                <?php } else if( get_post_type($post->ID) == 'page' ){ ?>
                    <article class="result hentry">
                        <div class="entry-header">
                            <h2 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> <small><?php echo esc_html__( 'Page', 'noo-landmark'); ?></small></h2>
                        </div>
                        <div class="entry-content">
                            <?php if(get_the_excerpt()) the_excerpt(); ?>
                        </div>
                    </article><!--/search-result-->
                <?php } else if( get_post_type($post->ID) == 'product' ){ ?>
                    <article class="result hentry">
                        <?php if(has_post_thumbnail( $post->ID )) {
                            echo '<a href="'.get_permalink().'">'. get_the_post_thumbnail($post->ID, 'full', array('title' => '')).'</a>';
                        } ?>
                        <div class="entry-header">
                            <h2 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> <small><?php echo esc_html__( 'Product', 'noo-landmark'); ?></small></h2>
                        </div>
                    </article><!--/search-result-->
                <?php } else { ?>
                    <article class="result hentry">
                        <?php if(has_post_thumbnail( $post->ID )) {
                            echo '<a href="'.get_permalink().'">'.get_the_post_thumbnail($post->ID, 'full', array('title' => '')).'</a>';
                        } ?>
                        <div class="entry-header">
                            <h2 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        </div>
                        <div class="entry-content">
                            <?php if(get_the_excerpt()) the_excerpt(); ?>
                        </div>
                    </article><!--/search-result-->
                <?php } ?>

            <?php endwhile;
            else: echo "<p>" . esc_html__( 'No results found', 'noo-landmark') . "</p>"; endif;?>


        </div><!--/search-results-->


        <?php if( get_next_posts_link() || get_previous_posts_link() ) { ?>
            <div id="pagination">
                <div class="prev"><?php previous_posts_link('&laquo; Previous Entries') ?></div>
                <div class="next"><?php next_posts_link('Next Entries &raquo;','') ?></div>
            </div>
        <?php }?>
		
	</div>

</div>

<?php get_footer(); ?>