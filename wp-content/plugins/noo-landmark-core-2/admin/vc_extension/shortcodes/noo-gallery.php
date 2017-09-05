<?php
/**
 * Shortcode Visual: Noo Gallery
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

if ( ! function_exists( 'shortcode_noo_gallery' ) ) :

	function shortcode_noo_gallery($atts)
	{

		extract( shortcode_atts( array(
			'title'     => '',
			'sub_title' => '',
			'orderby'   => 'date',
			'items'     => 8
		), $atts ) );

		ob_start();

		/*
         * Required library LightGallery
         */
        wp_enqueue_style( 'lightgallery' );
        wp_enqueue_script( 'lightgallery' );
        /**
         * A jQuery plugin that adds cross-browser mouse wheel support with Lightgallery
         */
        wp_enqueue_script( 'lightgallery_mousewheel' );

		/**
		 * VAR
		 */
		$order = 'DESC';
		switch ($orderby) {
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

			case 'rand':
				$orderby = 'rand';
				break;

			default:
				$orderby = 'date';
				break;
		}
		$args = array(
			'post_type' => 'noo_gallery',
			'orderby'           =>   $orderby,
			'order'             =>   $order,
			'posts_per_page' => $items			
		);

		$query = new WP_Query( $args );
		$class = uniqid('noo_gallery_');

		/**
		 * Display Content
		 */
		if ( $query->have_posts() ):
		?>
			<div class="sc-noo-gallery-wrap noo-gallery <?php echo esc_attr($class); ?>">

	            <?php if ( !empty( $title ) || !empty( $sub_title ) ) : ?>
		            <div class="noo-theme-wraptext">
		                <div class="wrap-title">
		                    <?php if ( !empty( $title ) ) : ?>
		                        <div class="noo-theme-title-bg"></div>

		                        <h3 class="noo-theme-title">
		                            <?php echo $title; ?>
		                        </h3>
		                    <?php endif; ?>

		                    <?php if ( !empty( $sub_title ) ) : ?>
		                        <p class="noo-theme-sub-title">
		                            <?php echo esc_html( $sub_title ); ?>
		                        </p>
		                    <?php endif; ?>
		                </div><!-- End /.wrap-title -->
		            </div><!-- End /.noo-theme-wraptext -->
	            <?php endif; ?>

	            <div class="noo-gallery-wrap-item galleries">
	            <?php
	            	while( $query->have_posts() ):
	            		$query->the_post();
            		?>
	            		<a class="gallery-item" data-src="<?php echo esc_url(get_the_post_thumbnail_url()); ?>" href="<?php echo esc_url(get_the_post_thumbnail_url()); ?>" title="<?php echo esc_attr(get_the_title()); ?>">
		            		<em class="gallery-content" style="background-image:url('<?php echo esc_url(get_the_post_thumbnail_url()); ?>');">
		            			<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'sr-only' ) ); ?>
		            		</em>
		            		<i class="fa fa-search-plus" aria-hidden="true"></i>
	            		</a>
            		<?php
	            	endwhile;
	            	wp_reset_postdata();
	            ?>           	
	            </div><!-- End /.noo-gallery-wrap-item galleries -->

	        </div><!-- End /.sc-noo-gallery-wrap noo-gallery -->
	        <script type="text/javascript">
			    jQuery(document).ready(function($){
			        $(".<?php echo esc_attr($class); ?> .galleries").lightGallery({
			        	thumbnail:true,
					    animateThumb: true,
					    showThumbByDefault: true
			        }); 
			    });
			</script>
		<?php
		endif; //End $query->have_posts()

		$galleries = ob_get_contents();
		ob_clean();
		return $galleries;

	}

	add_shortcode('noo_gallery', 'shortcode_noo_gallery');

endif;

/** ====== END shortcode_noo_partner ====== **/