<?php
/**
 * Shortcode Visual: Noo Blog Slider
 * Function show post in blog
 *
 * @package     Noo Library
 * @author      Henry <hungnt@vietbrain.com>
 * @version     1.0
 */

/* -------------------------------------------------------
 * Create functions noo_shortcode_blog_slider
 * ------------------------------------------------------- */


if (!function_exists('noo_shortcode_blog_slider')) :

    function noo_shortcode_blog_slider($atts)
    {

        extract(shortcode_atts(array(
            'title'          => '',
            'sub_title'      => '',
            'type_query'     =>   'cat',
            'categories'     =>   '',
            'tags'           =>   '',
            'include'        =>   '',
            'autoplay'       =>  'true',
            'layout_style'   =>   'creative',
            'columns'        =>   '2',
            'orderby'        =>   'latest',
            'posts_per_page' =>   10,
        ), $atts));

        ob_start();

        /**
         * Required library
         */
        wp_enqueue_style( 'carousel' );
        wp_enqueue_script( 'carousel' );

        /**
         * Check data order
         * @var string
         */
        $order = 'DESC';

        switch ($orderby) :

            case 'latest':
                $orderby = 'date';
                break;

            case 'oldest':
                $orderby = 'date';
                $order = 'ASC';
                break;

            case 'alphabet':
                $orderby = 'title';
                $order = 'ASC';
                break;

            case 'ralphabet':
                $orderby = 'title';
                break;

            default:
                $orderby = 'date';
                break;

        endswitch;

        /**
         * Create query
         * @var array
         */
        $args = array(
            'post_type' => 'post',
            'orderby' => $orderby,
            'order' => $order,
            'posts_per_page' => $posts_per_page,
        );

        /**
         * Get category id
         * @var [type]
         */

        if ($type_query == 'cat') :
            $cat_id = explode(',', $categories);
            if (!in_array('all', $cat_id)) {
                $args['cat'] = $categories;
            }
        endif;

        /**
         * Get tag id
         * @var [type]
         */
        if ($type_query == 'tag') :

            if ($tags != 'all'):

                $tag_id = explode(',', $tags);
                $args['tag__in'] = $tag_id;

            endif;

        endif;

        /**
         * Get list id
         * @var [type]
         */
        if ($type_query == 'post_id') :

            $posts_var = '';

            if (isset($include) && !empty($include)) :

                $posts_var = explode(',', $include);

            endif;

            $args['post__in'] = $posts_var;

        endif;

        /**
         * new query
         * @var WP_Query
         */
        $blog_query = new WP_Query($args);

        /**
         * Check and loop
         */
        if ($blog_query->have_posts()) :
            echo "<div class='noo-row noo-blog-slider'>";

            if ( $layout_style == 'creative' ) {
                $layout_style = 'list-single';
            } elseif ( $layout_style == 'classic' ) {
                $layout_style = 'grid';
            }
            $columns;
            $classes = 'noo-masonry-item ' . $layout_style;

            ?>

            <div class="noo-container noo-theme-wraptext">
                <div class="wrap-title">
                    <?php if ( !empty( $title ) ) : ?>
                        <div class="noo-theme-title-bg"></div>

                        <h3 class="noo-theme-title">
                            <?php
                                $title = explode( ' ', $title );
                                $title[0] = '<span class="first-word">' . esc_html( $title[0] ) . '</span>';
                                $title = implode( ' ', $title );
                            ?>
                            <?php echo $title; ?>
                        </h3>
                    <?php endif; ?>

                    <?php if ( !empty( $sub_title ) ) : ?>
                        <p class="noo-theme-sub-title">
                            <i class="icon-decotitle"></i>
                            <?php echo esc_html( $sub_title ); ?>
                        </p>
                    <?php endif; ?>
                </div><!-- End /.wrap-title -->
            
            </div><!-- End /.noo-theme-wraptext -->

            <?php

            
            echo '<div class="noo-owlslider" ';
            echo 'data-pagination="false" data-autoHeight="false" ';
            echo 'data-autoplay="' . $autoplay. '" ';
            echo 'data-column="' . $columns . '" ';
            echo 'data-textPrev="<i class=\'ion-ios-arrow-left\'></i>" ';
            echo 'data-textNext="<i class=\'ion-ios-arrow-right\'></i>" ';
            echo '>';
            echo '<div class="sliders">';

            while ($blog_query->have_posts()) : $blog_query->the_post();
                $format = get_post_format();
                ?>

                <div <?php post_class($classes); ?>>
                    <div class="blog-item">
                        <div class="noo-blog-featured">

                            <?php
                            switch ( $format ) :

                                case 'quote':

                                    echo '<div class="content-featured">';
                                        
                                        get_template_part( 'template-content-quote' );
                                    
                                    echo '</div>';

                                    break;

                                case 'audio':

                                    echo '<div class="content-featured">';

                                    echo noo_landmark_func_get_featured_audio();
                                    noo_landmark_func_tag_date(true);

                                    echo '</div>';

                                    break;

                                case 'gallery':

                                    do_action( 'noo_landmark_func_before_content_featured_gallery' );

                                     echo noo_landmark_func_get_featured_gallery('_noo_wp_post', get_the_ID(), true);
                                     noo_landmark_func_tag_date(true);

                                    do_action( 'noo_landmark_func_after_content_featured' );

                                    break;

                                case 'video':

                                    do_action( 'noo_landmark_func_before_content_featured' );

                                        echo noo_landmark_func_get_featured_video('_noo_wp_post', get_the_ID(), true);
                                        noo_landmark_func_tag_date(true);

                                    do_action( 'noo_landmark_func_after_content_featured' );

                                    break;

                                default:
                                    do_action( 'noo_landmark_func_before_content_featured' );
        
                                        echo noo_landmark_func_get_featured_default(get_the_ID(), true);
                                        noo_landmark_func_tag_date(true);

                                    do_action( 'noo_landmark_func_after_content_featured' );

                                    break;

                            endswitch;


                            ?>

                        </div> <!-- /.noo-blog-featured -->

                        <?php get_template_part( 'template-content' ); ?>

                    </div><!-- /.blog-item -->
                </div><!-- /.noo-masonry-item -->

            <?php endwhile;
            echo "</div><!-- /.sliders -->";
            echo "</div><!-- /.noo-owlslider -->";
            echo "</div><!-- /.noo-row -->";
            
            // === Restore original Post Data
            wp_reset_postdata();
            wp_reset_query();

        else :

            esc_html_e('Sorry, no posts matched your criteria.', 'noo-landmark-core');

        endif;

        $html = ob_get_contents();
        ob_end_clean();
        return $html;

    }

    add_shortcode('noo_blog_slider', 'noo_shortcode_blog_slider');

endif;

/** ====== END noo_shortcode_blog_slider ====== **/