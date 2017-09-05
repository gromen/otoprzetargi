<?php
/**
 * Shortcode Visual: Noo Partner
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

if ( ! function_exists( 'shortcode_noo_partner' ) ) :
	
	function shortcode_noo_partner($atts) {

		extract( shortcode_atts( array(
            'title'          => '',
            'sub_title'      => '',
            'partners'       => '',
            'items'          => 6,
            'rows'           => 1,
            'autoplay'       => 'false',
            'timeout'        => '2500'
        ), $atts ) );

        ob_start();
        $partners = vc_param_group_parse_atts( $partners );

        if ( !is_array( $partners ) || empty( $partners ) ) {
            return false;
        }

        $class = uniqid('noo_partner_');
        /*
         * Required library slick
         */
        wp_enqueue_style( 'slick' );
        wp_enqueue_script( 'slick' );

        /**
         * Display content
         */
        ?>
        <div class="sc-noo-partner-wrap noo-partner <?php echo esc_attr($class); ?>">
            
            <?php if ( !empty( $title ) || !empty( $sub_title ) ) : ?>
                <div class="noo-title-header">
                    <?php
                    /**
                     * Render title
                     */
                    noo_title_first_word( $title, $sub_title );
                    ?>
                    <div class="noo-action-slider">
                        <i class="prev-partner ion-ios-arrow-left"></i>
                        <i class="next-partner ion-ios-arrow-right"></i>
                    </div>
                </div>
            <?php endif; ?>

            <div class="noo-partner-wrap-item partners">
                <div class="partner-wapper">
                    <?php 
                        foreach ($partners as $value) {
                            echo '<div>';
                                echo '<a href="'.esc_url( $value['link'] ).'" title="'.esc_attr( $value['name'] ).'" target="_blank">';
                                    echo '<img src="'.esc_attr(wp_get_attachment_url($value['logo'])).'" alt="'.esc_attr(get_the_title($value['logo'])).'" />';
                                echo '</a>';
                            echo '</div>';
                        }
                    ?>
                </div>
            </div><!-- End /.noo-partner-wrap-item partners -->

        </div><!-- End /.sc-noo-partner-wrap noo-partner -->
        <script>
        jQuery(document).ready(function($) {
            $('.<?php echo esc_attr($class); ?> .partner-wapper').slick({
                rtl: <?php echo is_rtl()? 'true': 'false'; ?>,
                rows: <?php echo $rows; ?>,
                infinite: true,
                slidesToShow: 5,
                slidesToScroll: 1,
                autoplay: false,
                variableWidth: true,
                arrows: false,
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 4,
                            centerMode: true,
                            centerPadding: '60px',
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 3,
                            centerMode: true,
                            centerPadding: '60px',
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            centerMode: true,
                            centerPadding: '60px',
                        }
                    },
                ]
            });
            $('.<?php echo esc_attr($class); ?> .prev-partner').on('click', function(){
                $('.<?php echo esc_attr($class); ?> .partner-wapper').slick("slickPrev");
            })
            $('.<?php echo esc_attr($class); ?> .next-partner').on('click', function(){
                $('.<?php echo esc_attr($class); ?> .partner-wapper').slick("slickNext");
            })

        });
        </script>
        <?php
        $testimonial = ob_get_contents();
        ob_end_clean();
		return $testimonial;

	}

	add_shortcode( 'noo_partner', 'shortcode_noo_partner' );

endif;

/** ====== END shortcode_noo_partner ====== **/