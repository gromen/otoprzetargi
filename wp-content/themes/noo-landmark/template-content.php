<?php if ( get_post_format() !== 'quote' ) : ?>

<div class="wrap-entry">
    <!--Start Header-->
    <header class="entry-header">
        <?php if ( is_single() ) : ?>
            <h1>
                <?php the_title(); ?>
            </h1>
        <?php else : ?>
            <h2>
                <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( esc_html__( 'Permanent link to: "%s"','noo-landmark' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a>
            </h2>
        <?php endif; ?>

        <?php noo_landmark_func_tag_date(true); ?>

        <?php if ( get_theme_mod( 'noo_blog_post_show_post_meta', true ) ) : ?>

            <div class="item-info">
            
                <!-- Start Author -->
                    <?php printf( '<span class="author vcard"><i class="fa fa-user"></i> <a class="url fn n" href="%1$s">%2$s</a></span>',
                            esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
                            get_the_author()
                        );
                    ?>
                <!-- Start end Author -->

                <!-- Start Date -->
                    <?php
                        if ( is_single() ) :
                            printf( '<span class="posted-on"><i class="fa fa-calendar"></i> %2$s</span>',
                                esc_url( get_permalink() ),
                                get_the_date()
                            );
                        else :
                            $tags_list = get_the_tag_list( '', ', ' );
                            if ( $tags_list ) :
                                echo '<span class="post-tag">';
                                echo '<i class="fa fa-tag"></i>';
                                echo noo_landmark_func_kses($tags_list);
                                echo '</span>';
                            endif;
                        endif;
                    ?>
                <!-- Start end Date -->

                <!-- Start Count Comment -->
                    <?php
                        $comments_count = wp_count_comments(get_the_id());
                        printf( '<span class="count-comment"><i class="fa fa-comment"></i> %1$s</span>', $comments_count->approved );
                    ?>
                <!-- Start end Count Comment -->

            </div><!-- /.item-info -->

        <?php endif; ?>

    </header>
    <!--Start end header-->

    <!--Start content-->
    <div class="entry-content">
        <?php if ( is_single() ) : ?>
            <?php the_content(); ?>
            <?php wp_link_pages(); ?>
        <?php else : ?>
            <?php if(get_the_excerpt()):?>
                <?php the_excerpt(); ?>
            <?php endif;?>
        <?php endif; ?>
    </div>
    <!--Start end content-->

    <!--Start footer-->
    <footer class="entry-footer">
        <?php noo_landmark_func_entry_meta(); ?>

        <?php if ( ! is_single() && get_theme_mod( 'noo_blog_post_show_post_meta', true ) ) : ?>

            <div class="item-info">
                <?php printf( '<span class="author vcard"><i class="fa fa-user"></i> <a class="url fn n" href="%1$s">%2$s</a></span>',
                        esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
                        get_the_author()
                    );
                ?>

                <!-- Start Count Comment -->
                <?php
                    $comments_count = wp_count_comments(get_the_id());
                    printf( '<span class="count-comment"><i class="fa fa-comment"></i> %1$s</span>', $comments_count->approved );
                ?>
                <!-- Start end Count Comment -->
            </div>
        <?php endif; ?>
    </footer>
    <!--Start end footer-->

</div><!--end wrap-entry-->


<?php endif; ?>