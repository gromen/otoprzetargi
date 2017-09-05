<?php
/**
 * Shortcode Visual: Noo Blog Mansory
 * Function show post in blog
 *
 * @package     Noo Library
 * @author      Henry <hungnt@vietbrain.com>
 * @version     1.0
 */

/* -------------------------------------------------------
 * Create functions noo_shortcode_blog_mansory
 * ------------------------------------------------------- */


if (!function_exists('noo_shortcode_blog_mansory')) :

    function noo_shortcode_blog_mansory($atts)
    {

        extract(shortcode_atts(array(
            'title'          => '',
            'sub_title'      => '',
            'type_query'     =>   'cat',
            'categories'     =>   '',
            'filter'         =>   '',
            'tags'           =>   '',
            'include'        =>   '',
            'layout_style'   =>   'creative',
            'columns'        =>   '2',
            'orderby'        =>   'latest',
            'posts_per_page' =>   10,
            'loadmore'       =>   '',
            'pagination'     =>   'default',
        ), $atts));

        ob_start();

        /**
         * Required library
         */
        wp_enqueue_script('imagesloaded');
        if ($pagination != 'default') :

            wp_enqueue_script('infinitescroll');

        endif;
        wp_enqueue_script('isotope');

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
         * Check paged
         */
        if (is_front_page() || is_home()) :
            $paged = (get_query_var('paged')) ? get_query_var('paged') : ((get_query_var('page')) ? get_query_var('page') : 1);
        else :
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        endif;

        /**
         * Create query
         * @var array
         */
        $args = array(
            'post_type' => 'post',
            'orderby' => $orderby,
            'order' => $order,
            'paged' => $paged,
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

        $load_image = '';
        if ( !empty($loadmore) ) {
            $load_image = wp_get_attachment_url(esc_attr($loadmore));
        } else {
            $load_image = NOO_PLUGIN_ASSETS_URI . '/images/ajax-loader.gif';
        }
        if ($blog_query->have_posts()) :
            echo "<div class='noo-row'>";

            ?>
            <?php if ( !empty( $title ) || !empty( $sub_title ) ) : ?>
                
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
                
                </div>

            <?php endif; ?>

            <?php

            if (!empty($filter) && $filter == true):
                ?>
                <div class="noo-container">
                    <div class="masonry-filters noo-blog-filters">
                        <ul class="noo-blog-filter" data-option-key="filter">
                            <?php
                            $arr_cat = explode(',', $categories);

                            if ($categories == 'all') {
                                $list_cats = get_terms('category', array(
                                    'hide_empty' => true,
                                ));
                            } else {
                                $list_cats = get_terms('category', array(
                                    'include' => $arr_cat,
                                    'hide_empty' => true,
                                ));
                            }
                            ?>
                            <li>
                                <a data-option-value="*" href="#all"
                                   class="selected"><?php echo esc_html__('All', 'noo-landmark-core'); ?></a>
                            </li>
                            <?php
                            foreach ($list_cats as $category):
                                ?>
                                <li>
                                    <a data-option-value=".category-<?php echo $category->slug; ?>"
                                       href="#category-<?php echo $category->slug; ?>"><?php echo $category->name; ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <?php
            endif;

            if ( $layout_style == 'creative' ) {
                $layout_style = 'list-single';
            } elseif ( $layout_style == 'classic' ) {
                $layout_style = 'grid';
            }

            $classes = 'noo-sm-6 noo-masonry-item noo-md-' . absint((12 / $columns)) . ' ' . $layout_style;

            echo '<div class="noo-blog-masonry noo-blog-' . $pagination . '" data-img-loading="' . $load_image . '">';
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
            echo "</div><!-- /.noo-blog-masonry -->";
            echo "</div><!-- /.noo-row -->";
            if ($pagination != 'disable'):
                ?>
                <div class="blog-pagination <?php echo esc_attr($pagination); ?>">
                    <div class="noo-loading"></div>
                    <?php
                    if (function_exists('noo_landmark_func_pagination_normal')):

                        noo_landmark_func_pagination_normal(array(), $blog_query);

                    endif;
                    ?>
                </div>
                <?php
            endif;
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

    add_shortcode('noo_blog_mansory', 'noo_shortcode_blog_mansory');

endif;

/** ====== END noo_shortcode_blog_mansory ====== **/