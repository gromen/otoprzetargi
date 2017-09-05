<?php
/**
 * Get value setting property
 *
 * @author   KENT <tuanlv@vietbrain.com>
 * @version  1.0
 */
if ( !function_exists( 'noo_landmark_get_setting' ) ) :
	
	function noo_landmark_get_setting( $name = '', $value = '', $default = '' ) {

		if ( empty( $name ) ) return;

		$property_setting = (array)get_option( esc_attr( $name ), array() );

		if ( array_key_exists( $value, $property_setting) ) {

			if ( !empty( $value ) && !empty( $property_setting[$value] ) ) {
	    		return $property_setting[$value];
			}

		}

		if ( empty( $value ) && !empty( $property_setting ) ) return $property_setting;

		return $default;

	}

endif;
if (!function_exists('noo_landmark_func_get_page_heading')):
	function noo_landmark_func_get_page_heading() {
		$heading = '';
		$archive_title = '';
		$archive_desc = '';
		if( ! get_theme_mod( 'noo_page_heading', true ) ) {
			return array($heading, $archive_title, $archive_desc);
		}

		if ( is_home() ) {
			$heading = get_theme_mod( 'noo_blog_heading_title', esc_html__( 'Our Blog', 'noo-landmark' ) );
		} elseif ( NOO_WOOCOMMERCE_EXIST && ( is_shop()  || is_product_category() || is_product_tag() )) {

            if (is_shop()) {
            	$heading = get_theme_mod( 'noo_shop_heading_title', esc_html__( 'Shop', 'noo-landmark' ) );
            } else {
            	$heading = single_cat_title( '', false );
            }
		} elseif ( is_search() ) {
			$heading = esc_html__( 'Search', 'noo-landmark' );
		} elseif ( is_author() ) {
			$curauth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));
			$heading = esc_html__( 'Author Archive','noo-landmark');
		} elseif ( is_year() ) {
    		$heading = esc_html__( 'Post Archive by Year: ', 'noo-landmark' ) . get_the_date( 'Y' );
		} elseif ( is_month() ) {
    		$heading = esc_html__( 'Post Archive by Month: ', 'noo-landmark' ) . get_the_date( 'F,Y' );
		} elseif ( is_day() ) {
    		$heading = esc_html__( 'Post Archive by Day: ', 'noo-landmark' ) . get_the_date( 'F j, Y' );
		} elseif ( is_404() ) {
    		$heading = esc_html__( 'Oops! We could not find anything to show to you.', 'noo-landmark' );
    		$archive_title =  esc_html__( 'Would you like going else where to find your stuff.', 'noo-landmark' );
		} elseif ( is_archive() ) {
			$heading = esc_html__('Our Blog', 'noo-landmark');			
		} elseif ( is_singular( 'product' ) ) {
			$heading = get_theme_mod( 'noo_woocommerce_product_disable_heading', false ) ? '' : get_the_title();
		} elseif ( is_single() ) {
			$heading = esc_html__('Our Blog', 'noo-landmark');
		} elseif( is_page() ) {
			if( ! noo_landmark_func_get_post_meta(get_the_ID(), '_noo_wp_page_hide_page_title', false) ) {
				$heading = get_the_title();
			}
		}

		$heading = apply_filters( 'noo_get_page_heading', $heading );

		return array($heading, $archive_title, $archive_desc);
	}
endif;

if (!function_exists('noo_landmark_func_get_page_heading_image')):
	function noo_landmark_func_get_page_heading_image() {
		$image = '';

		if( ! get_theme_mod( 'noo_page_heading', true ) ) {
			return $image;
		}
		if( NOO_WOOCOMMERCE_EXIST && is_shop() ) {
			$image = noo_landmark_func_get_image_option( 'noo_shop_heading_image', '' );
		} elseif (NOO_WOOCOMMERCE_EXIST && is_product()) {
			$option = get_theme_mod('noo_woocommerce_single_header', 1);
			$image  = noo_landmark_func_get_image_option('noo_shop_heading_image', '');
			$thumb  = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full');
            if (isset($thumb) && !empty($thumb) && $option != 1) {
                $image = $thumb[0];
            }
        } elseif ( is_home() ) {
			$image = noo_landmark_func_get_image_option( 'noo_blog_heading_image', '' );
			$page_obj  = get_queried_object();
            if( $page_obj->ID == (int)get_option( 'page_for_posts' ) && !empty( noo_landmark_func_get_post_meta( $page_obj->ID, '_heading_image', '' ) ) ){
                $image = noo_landmark_func_get_post_meta( $page_obj->ID, '_heading_image', '' );
            }
		} elseif( is_category() || is_tag() ) {
			$queried_object = get_queried_object();
			$image			= noo_landmark_func_get_term_meta( $queried_object->term_id, 'heading_image', '' );
			$image			= empty( $image ) ? noo_landmark_func_get_image_option( 'noo_blog_heading_image', '' ) : $image;
		} elseif( NOO_WOOCOMMERCE_EXIST && ( is_product_category() || is_product_tag() ) ) {
			$queried_object = get_queried_object();
			$image			= noo_landmark_func_get_term_meta( $queried_object->term_id, 'heading_image', '' );
			$image			= empty( $image ) ? noo_landmark_func_get_image_option( 'noo_shop_heading_image', '' ) : $image;
		} elseif ( is_page() ) {
			$image = noo_landmark_func_get_post_meta(get_the_ID(), '_heading_image', '');
		} elseif ( is_single()) {
			if ( noo_landmark_func_get_post_meta(get_the_ID(), '_heading_image', '')) {
				$image = noo_landmark_func_get_post_meta(get_the_ID(), '_heading_image', '');
			} else {
				$image = noo_landmark_func_get_image_option( 'noo_blog_heading_image', '' );
			}	
		}

		$image = apply_filters( 'noo_get_page_heading_image', $image );
		if( !empty( $image ) && is_numeric( $image ) ) $image = wp_get_attachment_url( $image );

		return $image;
	}
endif;

if (!function_exists('noo_landmark_func_has_featured_content')):
	function noo_landmark_func_has_featured_content($post_id = null) {
		$post_id = (null === $post_id) ? get_the_ID() : $post_id;

		$post_type = get_post_type($post_id);
		$prefix = '';
		$post_format = '';
		
		if ($post_type == 'post') {
			$prefix = '_noo_wp_post';
			$post_format = get_post_format($post_id);
		}
		
		switch ($post_format) {
			case 'image':
				$main_image = noo_landmark_func_get_post_meta($post_id, "{$prefix}_main_image", 'featured');
				if( $main_image == 'featured') {
					return has_post_thumbnail($post_id);
				}

				return has_post_thumbnail($post_id) || ( (bool)noo_landmark_func_get_post_meta($post_id, "{$prefix}_image", '') );
			case 'gallery':
				if (!is_singular()) {
					$preview_content = noo_landmark_func_get_post_meta($post_id, "{$prefix}_gallery_preview", 'slideshow');
					if ($preview_content == 'featured') {
						return has_post_thumbnail($post_id);
					}
				}
				
				return (bool)noo_landmark_func_get_post_meta($post_id, "{$prefix}_gallery", '');
			case 'video':
				if (!is_singular()) {
					$preview_content = noo_landmark_func_get_post_meta($post_id, "{$prefix}_preview_video", 'both');
					if ($preview_content == 'featured') {
						return has_post_thumbnail($post_id);
					}
				}
				
				$m4v_video = (bool)noo_landmark_func_get_post_meta($post_id, "{$prefix}_video_m4v", '');
				$ogv_video = (bool)noo_landmark_func_get_post_meta($post_id, "{$prefix}_video_ogv", '');
				$embed_video = (bool)noo_landmark_func_get_post_meta($post_id, "{$prefix}_video_embed", '');
				
				return $m4v_video || $ogv_video || $embed_video;
			case 'link':
			case 'quote':
				return false;
				
			case 'audio':
				$mp3_audio = (bool)noo_landmark_func_get_post_meta($post_id, "{$prefix}_audio_mp3", '');
				$oga_audio = (bool)noo_landmark_func_get_post_meta($post_id, "{$prefix}_audio_oga", '');
				$embed_audio = (bool)noo_landmark_func_get_post_meta($post_id, "{$prefix}_audio_embed", '');
				return $mp3_audio || $oga_audio || $embed_audio;
			default: // standard post format
				return has_post_thumbnail($post_id);
		}
		
		return false;
	}
endif;

// Get allowed HTML tag.
if( !function_exists('noo_landmark_func_allowed_html') ) :
	function noo_landmark_func_allowed_html() {
		return apply_filters( 'noo_landmark_func_allowed_html', array(
			'a' => array(
				'href' => array(),
				'target' => array(),
				'title' => array(),
				'rel' => array(),
				'class' => array(),
				'style' => array(),
			),
			'img' => array(
				'src' => array(),
				'class' => array(),
				'style' => array(),
			),
			'h1' => array(),
			'h2' => array(),
			'h3' => array(),
			'h4' => array(),
			'h5' => array(),
			'p' => array(
				'class' => array(),
				'style' => array()
			),
			'br' => array(
				'class' => array(),
				'style' => array()
			),
			'hr' => array(
				'class' => array(),
				'style' => array()
			),
			'span' => array(
				'class' => array(),
				'style' => array()
			),
			'em' => array(
				'class' => array(),
				'style' => array()
			),
			'strong' => array(
				'class' => array(),
				'style' => array()
			),
			'small' => array(
				'class' => array(),
				'style' => array()
			),
			'b' => array(
				'class' => array(),
				'style' => array()
			),
			'i' => array(
				'class' => array(),
				'style' => array()
			),
			'u' => array(
				'class' => array(),
				'style' => array()
			),
			'ul' => array(
				'class' => array(),
				'style' => array()
			),
			'ol' => array(
				'class' => array(),
				'style' => array()
			),
			'li' => array(
				'class' => array(),
				'style' => array()
			),
			'blockquote' => array(
				'class' => array(),
				'style' => array()
			),
		) );
	}
endif;

// Allow only unharmed HTML tag.
if( !function_exists('noo_landmark_func_html_content_filter') ) :
	function noo_landmark_func_html_content_filter( $content = '' ) {
		return wp_kses( $content, noo_landmark_func_allowed_html() );
	}
endif;

// escape language with HTML.
if( !function_exists('noo_landmark_func_kses') ) :
	function noo_landmark_func_kses( $text = '' ) {
		return wp_kses( $text, noo_landmark_func_allowed_html() );
	}
endif;

/* -------------------------------------------------------
 * Create functions noo_landmark_func_get_page_id_by_template
 * ------------------------------------------------------- */

if ( ! function_exists( 'noo_landmark_func_get_page_id_by_template' ) ) :
	
	function noo_landmark_func_get_page_id_by_template( $page_template = '' ) {

		$pages = get_pages(array(
			'meta_key' => '_wp_page_template',
			'meta_value' => $page_template
		));

		if( $pages ){
			return $pages[0]->ID;
		}
		return false;

	}

endif;

/** ====== END noo_landmark_func_get_page_id_by_template ====== **/

/* -------------------------------------------------------
 * Create functions noo_landmark_func_bio_author
 * ------------------------------------------------------- */

if ( ! function_exists( 'noo_landmark_func_bio_author' ) ) :
	
	function noo_landmark_func_bio_author() {

		?>
			<div class="meta-author">
				<hr />
	            
	            <div class="box-author-info">
	            	<?php echo get_avatar( get_the_author_meta( 'user_email', 90 ) ) ; ?>
	                <h5>
	                    <a title="<?php printf( esc_html__( 'Post by %s','noo-landmark'), get_the_author() ); ?>" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
	                        <?php echo get_the_author() ?>
	                    </a>
	                </h5>
	                <p>
	                    <?php the_author_meta( 'description' ) ?>
	                </p>
	                <ul class="author-social">
						<?php
							$google_profile = get_the_author_meta( 'google_profile' );
							if ( !empty( $google_profile ) ) {
								echo '<li class="google"><a href="' . esc_url($google_profile) . '" rel="author"><i class="fa fa-google"></i></a></li>';
							}
							
							$facebook_profile = get_the_author_meta( 'facebook_profile' );
							if ( !empty( $facebook_profile ) ) {
								echo '<li class="facebook"><a href="' . esc_url($facebook_profile) . '"><i class="fa fa-facebook"></i></a></li>';
							}
							
							$twitter_profile = get_the_author_meta( 'twitter_profile' );
							if ( !empty( $twitter_profile ) ) {
								echo '<li class="twitter"><a href="' . esc_url($twitter_profile) . '"><i class="fa fa-twitter"></i></a></li>';
							}
							
							$linkedin_profile = get_the_author_meta( 'linkedin_profile' );
							if ( !empty( $linkedin_profile ) ) {
								echo '<li class="linkedin"><a href="' . esc_url($linkedin_profile) . '"><i class="fa fa-linkedin"></i></a></li>';
							}
						?>
					</ul>
	            </div><!-- /.box-info-author -->
	        </div>
		<?php

	}

endif;

/** ====== END noo_landmark_func_bio_author ====== **/


/* -------------------------------------------------------
 * Create functions noo_landmark_func_post_nav
 * ------------------------------------------------------- */

if ( ! function_exists( 'noo_landmark_func_post_nav' ) ) :

	function noo_landmark_func_post_nav() {
		global $post;
	    // Don't print empty markup if there's nowhere to navigate.
	    $previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
	    $next     = get_adjacent_post( false, '', false );

	    if ( ! $next && ! $previous )
	        return;

	    ?>
	    <?php $prev_link = get_previous_post_link( '%link', _x( '%title', 'Previous post link', 'noo-landmark' ) ); ?>
	    <?php $next_link = get_next_post_link( '%link', _x( '%title', 'Next post link', 'noo-landmark' ) ); ?>
	    <nav class="post-navigation<?php echo( (!empty($prev_link) || !empty($next_link) ) ? ' post-navigation-line':'' )?>">
	        <?php if($prev_link):?>
	            <?php
	                $url_img = wp_get_attachment_image_src(get_post_thumbnail_id( $previous->ID ), 'large');
	            ?>
	            <div class="prev-post <?php echo ( ! $next ) ? 'full-nav': ''; ?>">
	                <div class="bg-prev-post" style="background-image: url(<?php echo esc_url($url_img[0]); ?>);"></div>
	                <span><?php esc_html_e('Previous Post', 'noo-landmark'); ?></span>
	                <?php echo ($prev_link);?>
	            </div>
	        <?php endif;?>
	                
	        <?php if(!empty($next_link)):?>
	            <?php
	                $url_img = wp_get_attachment_image_src(get_post_thumbnail_id( $next->ID ), 'large');
	            ?>
	            <div class="next-post <?php echo ( ! $previous ) ? 'full-nav': ''; ?>">
	                <div class="bg-next-post" style="background-image: url(<?php echo esc_url($url_img[0]); ?>);"></div>
	                <span><?php esc_html_e('Next Post', 'noo-landmark'); ?></span>
	                <?php echo ($next_link);?>
	            </div>
	        <?php endif;?>
	    </nav>
    <?php
    }

endif;

/** ====== END noo_landmark_func_post_nav ====== **/

/**
 * This function check page current
 *
 * @package 	LandMark
 * @author 		KENT <tuanlv@vietbrain.com>
 * @version 	1.0
 */

if ( ! function_exists( 'noo_current_url' ) ) :
	
	function noo_current_url( $encoded = false ) {

		global $wp;
		$current_url = esc_url( add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
		if( $encoded ) {
			return urlencode($current_url);
		}
		return $current_url;

	}

endif;