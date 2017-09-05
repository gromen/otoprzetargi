<?php
/**
 * Shortcode Visual: Noo Testimonial
 * Function show post in blog
 * 
 * @package     Noo Library
 * @author      JAMES <luyentv@vietbrain.com>
 * @version     1.0
 */

/* -------------------------------------------------------
 * Create functions noo_shortcode_testimonial
 * Function show all comment
 * ------------------------------------------------------- */

if ( ! function_exists( 'noo_shortcode_testimonial' ) ) :
	
	function noo_shortcode_testimonial($atts) {

		extract( shortcode_atts( array(
            'style'          => 'style-1',
            'navigation'     => 'false',
            'pagination'     => 'false',
            'header'         =>'show_icon',
            'icon'           => '',
            'image'          => '',
            'title'          => '',
            'sub_title'      => '',
            'posts_per_page' => 10,
            'items'          => 1,
            'autoplay'       => 'true',
            'timeout'        => '2000',
            'show_name'      => 'true',
            'show_position'  => 'true',
            'caption'        => ''
        ), $atts ) );

        ob_start();
        /*
         * Required library slick
         */
        wp_enqueue_style( 'slick' );
        wp_enqueue_script( 'slick' );

        /**
         * Create query
         * @var array
         */
        $args = array(
			'post_type'      => 'testimonial',
			'posts_per_page' => $posts_per_page,
    	);

    	/**
    	 * New Query
    	 * @var WP_Query
    	 */
    	$testimonial_query = New WP_Query($args);

    	/**
    	 * Display content
    	 */

    	if( $testimonial_query->have_posts() ) {
    		$prefix = '_noo_wp_testimonial';
            $class = uniqid('testimonial_');
            if( !empty( $caption ) ){
                echo '<div class="noo-sc-caption noo-testimonial" data-caption="'.esc_attr($caption).'">';
            } else {
                echo '<div class="noo-testimonial">';
            }
            if( $style != 'style-3' || $style != 'style-4' )
                echo '<div class="'.esc_attr($style).'">';

                if( !empty($icon) || !empty($image) || !empty($title) ){
                    echo '<div class="sc-header text-center">';
                        if( $header === 'show_icon' &&  !empty($icon) ){
                            echo '<i class="'.esc_attr($icon).'"></i>';
                        } elseif ($header === 'show_image' && !empty($image)) {
                            echo '<div class="noo-testimonial-image">';
                            echo wp_get_attachment_image( esc_attr($image) );
                            echo '</div><!-- /.noo-testimonial-image -->';
                        }
                        if( !empty($title) ) echo '<h3 class="sc-title">'.esc_html($title).'</h3>';
                        if( !empty($sub_title) ) echo '<p class="sc-sub-title">'.esc_html($sub_title).'</p>';
                    echo '</div><!-- /.sc-header -->';   
                }
                
                echo '<div class="testimonial-content '.esc_attr( $class ).'">';
                    // STYLE 1 - 2 
                    // Get Class, use it for Script
                    echo '<div class="noo-testimonial-wrap">';

                    while($testimonial_query->have_posts()) {
                        $testimonial_query->the_post();
                        /**
                         * var
                         */
                        $testimonial_id =  get_the_ID();
                        $content = get_the_content();
                        $image = (int)noo_landmark_func_get_post_meta($testimonial_id, "{$prefix}_image");
                        $name = noo_landmark_func_get_post_meta($testimonial_id, "{$prefix}_name");
                        $position = noo_landmark_func_get_post_meta($testimonial_id, "{$prefix}_position");
                        /**
                         * Display content
                         */

                        echo '<div class="noo-testimonial-item">';
                        ?>
                            <?php if( $style == 'style-2' ): ?>
                                <div class="box-user">
                                    <?php 
                                        if( !empty( $image ) )
                                            echo wp_get_attachment_image( esc_attr($image), array(95, 95));
                                        if( $show_name =='true' )
                                            echo '<h3 class="noo-testimonial-name">'.esc_html( $name ).'</h3>';
                                        if( $show_position =='true')
                                            echo '<span class="noo-testimonial-position">'.esc_html( $position ).'</span>';
                                    ?>
                                </div><!-- End /.box-user -->
                                <p class="noo-testimonial-content">
                                    <?php echo esc_html( $content );?>
                                </p>
                            <?php else: ?>
                                <?php 
                                    if( !empty( $image ) ) echo wp_get_attachment_image( esc_attr($image), array(95, 95));
                                    
                                    if( $show_name =='true' )
                                        echo '<h3 class="noo-testimonial-name">'.esc_html( $name ).'</h3>';
                                    
                                    if( $show_position =='true')
                                        echo '<span class="noo-testimonial-position">'.esc_html( $position ).'</span>';
                                ?>

                                <p class="noo-testimonial-content">
                                    <?php echo esc_html( $content );?>
                                </p>
                            <?php endif; ?>
                        <?php
                        echo "</div>";
                    }
                    echo '</div><!-- /.noo-testimonial-wrap-->';

                echo '</div><!-- /.testimonial-content -->';

    		echo "</div></div><!-- /.noo-testimonial -->";
    		wp_reset_postdata();
    	}
        ?>
        <script>
            jQuery(document).ready(function($){
                $('.<?php echo esc_attr( $class ); ?> .noo-testimonial-wrap').slick({
                    rtl: <?php echo is_rtl()? 'true': 'false'; ?>,
                    dots: <?php echo esc_attr( $pagination ); ?>,
                    arrows: <?php echo esc_attr( $navigation ); ?>,
                    prevArrow:  '<i class="bt-testimonial prev-testimonial ion-ios-arrow-left"></i>',
                    nextArrow:  '<i class="bt-testimonial next-testimonial ion-ios-arrow-right"></i>',
                    infinite: true,
                    slidesToShow: <?php echo esc_attr($items); ?>,
                    slidesToScroll: <?php echo esc_attr($items); ?>,
                    rows: 1,
                    autoplay: <?php echo ($autoplay) ?  'true' : 'false'; ?>,
                    autoplaySpeed: <?php echo $timeout; ?>,
                    centerPadding: 0,
                    adaptiveHeight: false,
                    responsive: [
                        <?php if( $items >= 3): ?>
                            {
                                breakpoint: 1024,
                                settings: {
                                    slidesToShow: 3,
                                    slidesToScroll: 3,
                                }
                            },
                        <?php endif; ?>
                        <?php if( $items >= 2): ?>
                        {
                            breakpoint: 770,
                            settings: {
                                slidesToShow: 2,
                                slidesToScroll: 2,
                            }
                        },
                        <?php endif; ?>
                        {
                            breakpoint: 480,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1,
                            }
                        }
                    ]
                });
            });
        </script> 
        <?php

        $testimonial = ob_get_contents();
        ob_end_clean();
		return $testimonial;

	}

	add_shortcode( 'noo_testimonial', 'noo_shortcode_testimonial' );

endif;

/** ====== END noo_shortcode_testimonial ====== **/