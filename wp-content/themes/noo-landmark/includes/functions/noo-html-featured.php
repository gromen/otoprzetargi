<?php

if (!function_exists('noo_landmark_func_get_featured_content')):
	function noo_landmark_func_get_featured_content($post_id = null, $post_type = '', $post_format = '') {
		
		$post_id = (null === $post_id) ? get_the_id() : $post_id;
		$post_type = ('' === $post_type) ? get_post_type($post_id) : $post_type;
		$prefix = '';
		
		if ($post_type == 'post') {
			$prefix = '_noo_wp_post';
			$post_format = ('' === $post_format) ? get_post_format($post_id) : $post_format;
		}
		
		switch ($post_format) {
			case 'image':
				return noo_landmark_func_get_featured_image($prefix, $post_id);
			case 'gallery':
				return noo_landmark_func_get_featured_gallery($prefix, $post_id);
			case 'video':
				return noo_landmark_func_get_featured_video($prefix, $post_id);
			case 'audio':
				return noo_landmark_func_get_featured_audio($prefix, $post_id);
			case 'quote':
				return noo_landmark_func_get_featured_quote($prefix, $post_id);
			case 'link':
				return noo_landmark_func_get_featured_link($prefix, $post_id);
			default: // standard post format
				return noo_landmark_func_get_featured_default($post_id);
		}
		
		return '';
	}
endif;

if (!function_exists('noo_landmark_func_featured_content')):
	function noo_landmark_func_featured_content($post_id = null, $post_type = '', $post_format = '') {
		echo noo_landmark_func_get_featured_content( $post_id, $post_type, $post_format );
	}
endif;

if (!function_exists('noo_landmark_func_get_featured_image')):
	function noo_landmark_func_get_featured_image($prefix = '_noo_wp_post', $post_id = null, $is_shortcode = false) {
		$post_id = (null === $post_id) ? get_the_id() : $post_id;
		$html = array();
		$thumb = '';
		$post_thumbnail_id = 0;
		$main_image = noo_landmark_func_get_post_meta($post_id, "{$prefix}_main_image", 'featured');
		if( $main_image == 'featured') {
			$post_thumbnail_id = get_post_thumbnail_id( $post_id );
		} else {
			if (!is_singular() || $is_shortcode) {
				$preview_content = noo_landmark_func_get_post_meta($post_id, "{$prefix}_image_preview", 'image');
				if ($preview_content == 'featured') {
					$post_thumbnail_id = get_post_thumbnail_id( $post_id );
				}
			}

			if(empty($thumb)) {
				$post_thumbnail_id = (int) noo_landmark_func_get_post_meta($post_id, "{$prefix}_image", '');
				
			}
		}
		
		$thumb = !empty($post_thumbnail_id) ? wp_get_attachment_image( $post_thumbnail_id, 'noo-post-thumbnail') : '';
		$post_thumbnail_src= '';
		if(!empty($post_thumbnail_id)){
			$image = wp_get_attachment_image_src($post_thumbnail_id,'full');
			$post_thumbnail_src = @$image[0];
		}
		if(!empty($thumb)) {
			if (!is_singular() || $is_shortcode) {
				$html[] = '<a class="content-thumb" href="' . esc_url(get_permalink()) . '" title="' . esc_attr(sprintf(esc_html__('"%s"', 'noo-landmark') , the_title_attribute('echo=0'))) . '">';
				$html[] = $thumb;
				$html[] = '</a>';
			} else {
				$html[] = '<div class="content-thumb">';
				$html[] = $thumb;
				$html[] = '</div>';
			}
		}
		
		return implode($html, "\n");
	}
endif;

if (!function_exists('noo_landmark_func_featured_image')):
	function noo_landmark_func_featured_image($prefix = '_noo_wp_post', $post_id = null,$is_shortcode = false) {
		echo noo_landmark_func_get_featured_image($prefix, $post_id, $is_shortcode);
	}
endif;

if (!function_exists('noo_landmark_func_get_featured_gallery')):
	function noo_landmark_func_get_featured_gallery($prefix = '_noo_wp_post', $post_id = null, $is_shortcode = false) {
		$post_id = (null === $post_id) ? get_the_id() : $post_id;
		$html = array();
		$post_thumbnail_id = 0;
		
		if (!is_single()) {
			$preview_content = noo_landmark_func_get_post_meta($post_id, "{$prefix}_gallery_preview", 'slideshow');
			if ($preview_content == 'featured' && has_post_thumbnail( $post_id )) {
				$post_thumbnail_id = get_post_thumbnail_id( $post_id );
				
				$thumb = !empty($post_thumbnail_id) ? wp_get_attachment_image( $post_thumbnail_id, 'large') : '';
				
				if(!empty($thumb)) {
					$html[] = '<a class="content-thumb" href="' . esc_url( get_permalink() ) . '" title="' . esc_attr(sprintf(esc_html__('Permalink to: "%s"', 'noo-landmark') , the_title_attribute('echo=0'))) . '">';
					$html[] = $thumb;
					$html[] = '<span></span>';
					$html[] = '</a>';
				}

				echo implode($html, "\n");

				return;
			}

			if( $preview_content == 'first_image' ) {
				$gallery_ids = noo_landmark_func_get_post_meta($post_id, "{$prefix}_gallery", '');
				if(!empty($gallery_ids)) {
					$gallery_arr = explode(',', $gallery_ids);
					$image_id = (int) $gallery_arr[0];
					
					$thumb = !empty($image_id) ? wp_get_attachment_image( $image_id, 'large') : '';
					
					$post_thumbnail_src= '';
					if(!empty($image_id)){
						$image = wp_get_attachment_image_src($image_id,'full');
						$post_thumbnail_src = @$image[0];
					}
					
					if(!empty($thumb)) {
						$html[] = '<a class="content-thumb" href="' .esc_url( get_permalink() ) . '" title="' . esc_attr(sprintf(esc_html__('Permalink to: "%s"', 'noo-landmark') , the_title_attribute('echo=0'))) . '">';
						$html[] = $thumb;
						$html[] = '<span></span>';
						$html[] = '</a>';
					}

					echo implode($html, "\n");

					return;
				}
			}
		}

		$gallery_ids = noo_landmark_func_get_post_meta($post_id, "{$prefix}_gallery", '');
		if(!empty($gallery_ids)) {			
			
			$html[] = '<div id="noo-gallery-' . $post_id . '">';
			$html[] = '<div class="noo-owlslider" ';
			$html[] = 'data-navigation="false" ';
			$html[] = 'data-resItemDesktop="1" ';
			$html[] = 'data-resItemDesktopSmall="1" ';
			$html[] = 'data-resItemTablet="1" ';
			$html[] = 'data-resItemMobile="1" ';
			$html[] = 'data-text-nav="' . esc_html__('prev', 'noo-landmark') . ',' . esc_html__('next', 'noo-landmark') . '" ';
			$html[] = '>';
			$html[] = '<div class="sliders">';
			$gallery_arr = explode(',', $gallery_ids);
			foreach ($gallery_arr as $index => $image_id) {
				$thumb = !empty($image_id) ? wp_get_attachment_image( $image_id, 'large') : '';
				
				$post_thumbnail_src= '';
				if(!empty($image_id)){
					$image = wp_get_attachment_image_src($image_id, 'large');
					$post_thumbnail_src = "style=background-image:url(". esc_url(@$image[0]) .");";
				}
				
				$active = ($index == 0) ? 'active' : '';
				if(!empty($thumb)) {
					$html[] = '<div class="slide-item" ' . $post_thumbnail_src . '>';
					$html[] = $thumb;
					$html[] = '</div>';
				}
			}
			
			$html[] = '</div> <!-- /.sliders -->';
			$html[] = '</div> <!-- /.noo-owlslider -->';
			$html[] = '</div>';

			wp_enqueue_style( 'carousel' );
			wp_enqueue_script( 'carousel' );
		}

		return implode($html, "\n");
	}
endif;

if (!function_exists('noo_landmark_func_featured_gallery')):
	function noo_landmark_func_featured_gallery( $prefix = '_noo_wp_post', $post_id = null,$is_shortcode = false) {
		echo noo_landmark_func_get_featured_gallery( $prefix, $post_id, $is_shortcode);
	}
endif;

if (!function_exists('noo_landmark_func_get_featured_video')):
	function noo_landmark_func_get_featured_video($prefix = '_noo_wp_post', $post_id = null, $is_shortcode = false) {
		$post_id = (null === $post_id) ? get_the_id() : $post_id;
		$html = array();
		$preview_content = noo_landmark_func_get_post_meta($post_id, "{$prefix}_video_preview", 'video');
		
		$output = '';
		if (!is_singular() || $is_shortcode) {
			if ($preview_content == 'featured' && has_post_thumbnail( $post_id )) {
				$post_thumbnail_id = get_post_thumbnail_id( $post_id );
				
				$thumb = !empty($post_thumbnail_id) ? wp_get_attachment_image( $post_thumbnail_id, 'noo-post-thumbnail') : '';
				
				if(!empty($thumb)) {
					$html[] = '<a class="content-thumb" href="' . esc_url(get_permalink()) . '" title="' . esc_attr(sprintf(esc_html__('Permalink to: "%s"', 'noo-landmark') , the_title_attribute('echo=0'))) . '">';
					$html[] = $thumb;
					$html[] = '</a>';
				}
				
				return implode($html, "\n");
			}
		}

		$m4v   	= noo_landmark_func_get_post_meta( $post_id, "{$prefix}_video_m4v", '' );
		$ogv   	= noo_landmark_func_get_post_meta( $post_id, "{$prefix}_video_ogv", '' );
		$embed 	= noo_landmark_func_get_post_meta( $post_id, "{$prefix}_video_embed", '' );

		$ratio 	= noo_landmark_func_get_post_meta( $post_id, "{$prefix}_video_ratio", '' );
		$ratio_class = '';
		switch($ratio) {
			case '16:9':
				$ratio_class = '16-9-ratio';
				break;
			case '5:3':
				$ratio_class = '5-3-ratio';
				break;
			case '5:4':
				$ratio_class = '5-4-ratio';
				break;
			case '4:3':
				$ratio_class = '4-3-ratio';
				break;
			case '3:2':
				$ratio_class = '3-2-ratio';
				break;
		}

		// @TODO: add poster to embedded video.
		if ( $embed != '' ) {

			$html[] = '<div id="noo-video-container'.$post_id.'" class="noo-video-container ' . $ratio_class . '">';
			$html[] = '	<div class="video-inner">';
			if ($preview_content == 'both' && has_post_thumbnail( $post_id )) {
				$html[] = '    <div class="embed-poster">';
				$html[] = get_the_post_thumbnail($post_id, 'large');
				$html[] = '    </div>';
			}
			$html[] = stripslashes( htmlspecialchars_decode( $embed ) );
				
			$html[] = '	</div>';
			$html[] = '</div>';
		}
		
		$output .= implode($html, "\n");
		return $output;
	}
endif;

if (!function_exists('noo_landmark_func_featured_video')):
	function noo_landmark_func_featured_video($prefix = '_noo_wp_post', $post_id = null) {
		echo noo_landmark_func_get_featured_video( $prefix, $post_id );
	}
endif;

if (!function_exists('noo_landmark_func_get_featured_audio')):
	function noo_landmark_func_get_featured_audio($prefix = '_noo_wp_post', $post_id = null) {
		$post_id = (null === $post_id) ? get_the_id() : $post_id;

		$mp3   = noo_landmark_func_get_post_meta( $post_id, "{$prefix}_audio_mp3", '' );
		$oga   = noo_landmark_func_get_post_meta( $post_id, "{$prefix}_audio_oga", '' );
		$embed = noo_landmark_func_get_post_meta( $post_id, "{$prefix}_audio_embed", '' );
		$html  = array();

		if ( $embed != '' ) :

			$html[] = '<div class="video-inner">';
			$html[] = stripslashes( htmlspecialchars_decode( $embed ) );
			$html[] = '</div>';

		endif; // if - $embed

		return implode($html, "\n");
	}
endif;
if (!function_exists('noo_landmark_func_get_featured_quote')):
	function noo_landmark_func_get_featured_quote($prefix = '_noo_wp_post', $post_id = null) {
		$post_id = (null === $post_id) ? get_the_id() : $post_id;
		$twitter_url   = noo_landmark_func_get_post_meta( $post_id, "{$prefix}_quote", '' );
		$html  = array();
		if ( $twitter_url != '' ) :
			$bg_url= '';
			if(has_post_thumbnail()){
				// $bg_url = wp_get_attachment_url(get_post_thumbnail_id());
				$bg_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'noo-full');
				$bg_url = $bg_url[0];
			} else {
				$bg_url = get_template_directory_uri() . '/assets/images/bg-quote.jpg';
			}
			$html[] = '<div class="content-thumb"'.(!empty($bg_url) ?' style="background-image:url('.$bg_url.')"':'').'>';
			$html[] = '</div>';
		endif;
		return implode($html, "\n");
	}
endif;

if (!function_exists('noo_landmark_func_featured_audio')):
	function noo_landmark_func_featured_audio($prefix = '_noo_wp_post', $post_id = null) {
		echo noo_landmark_func_get_featured_audio( $prefix, $post_id );
	}
endif;

if (!function_exists('noo_landmark_func_get_featured_quote')):
	function noo_landmark_func_get_featured_quote($prefix = '_noo_wp_post', $post_id = null) {
		return noo_landmark_func_get_featured_default($post_id);
	}
endif;

if (!function_exists('noo_landmark_func_featured_quote')):
	function noo_landmark_func_featured_quote($prefix = '_noo_wp_post', $post_id = null) {
		echo noo_landmark_func_get_featured_quote( $prefix, $post_id );
	}
endif;

if (!function_exists('noo_landmark_func_get_featured_link')):
	function noo_landmark_func_get_featured_link($prefix = '_noo_wp_post', $post_id = null) {
		return noo_landmark_func_get_featured_default($post_id);
	}
endif;

if (!function_exists('noo_landmark_func_featured_link')):
	function noo_landmark_func_featured_link($prefix = '_noo_wp_post', $post_id = null) {
		echo noo_landmark_func_get_featured_link($prefix, $post_id);
	}
endif;

if (!function_exists('noo_landmark_func_get_featured_default')):
	function noo_landmark_func_get_featured_default($post_id = null,$is_shortcode = false) {
		$html = array();
		
		if (has_post_thumbnail()) {
			$thumb = get_the_post_thumbnail($post_id, 'large');
			if (is_singular() && !$is_shortcode) {
				$html[] = '<div class="content-thumb">';
				$html[] = $thumb;
				$html[] = '</div>';
			} else {
				$html[] = '<a class="content-thumb" href="' . esc_url(get_permalink()) . '" title="' . esc_attr(sprintf(esc_html__('Permalink to: "%s"', 'noo-landmark') , the_title_attribute('echo=0'))) . '">';
				$html[] = $thumb;
				$html[] = '<span></span>';
				$html[] = '</a>';
			}
		}
		
		return implode($html, "\n");
	}
endif;


if (!function_exists('noo_landmark_func_featured_default')):
	function noo_landmark_func_featured_default($post_id = null,$is_shortcode = false) {
		echo noo_landmark_func_get_featured_default($post_id,$is_shortcode);
	}
endif;

/**
 * Action ouput wraper
 */
/**
 * Content featured for gallery
 */
if ( ! function_exists('noo_landmark_func_wrap_start_content_featured_gallery' ) ) :
	function noo_landmark_func_wrap_start_content_featured_gallery() {

		$is_has_slider = '';
		$style_image = '';

		$blog_style = isset($_GET['style']) ? $_GET['style'] : get_theme_mod('noo_blog_style', 'grid');

		$preview_content = noo_landmark_func_get_post_meta(get_the_ID(), "_noo_wp_post_gallery_preview", 'slideshow');

		if( $preview_content == 'first_image' ) {
			$gallery_ids = noo_landmark_func_get_post_meta(get_the_ID(), "_noo_wp_post_gallery", '');
			if(!empty($gallery_ids)) {
				$gallery_arr = explode(',', $gallery_ids);
				$image_id = (int) $gallery_arr[0];

				$feat_image_url = wp_get_attachment_image_src( $image_id, array(400, 300) );

			}
		} elseif ($preview_content == 'featured') {
			$feat_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), array(400, 300) );
		} else {
			$is_has_slider = 'has-slider';
		}

		if( ! empty($feat_image_url) ) {
			$style_image = (is_single()) ? "" : "style=background-image:url(". esc_url($feat_image_url[0]) .");";
		}

		echo '<div class="content-featured ' . $is_has_slider . '" ' . esc_attr($style_image) . '>';

	}
	add_action( 'noo_landmark_func_before_content_featured_gallery', 'noo_landmark_func_wrap_start_content_featured_gallery', 10 );
endif;
/**
 * Content featured for standard
 */
if ( ! function_exists('noo_landmark_func_wrap_start_content_featured' ) ) :
	function noo_landmark_func_wrap_start_content_featured() {

		$style_image = '';

		$feat_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), array(400, 300) );
		$style_image = (is_single()) ? "" : "style=background-image:url(". esc_url($feat_image_url[0]) .");";
		
		echo '<div class="content-featured" ' . esc_attr($style_image) . '>';

	}
	add_action( 'noo_landmark_func_before_content_featured', 'noo_landmark_func_wrap_start_content_featured', 10 );
endif;

if ( ! function_exists('noo_landmark_func_wrap_end_content_featured' ) ) :
	function noo_landmark_func_wrap_end_content_featured() {
		echo '</div> <!-- /.content-featured -->';
	}
	add_action( 'noo_landmark_func_after_content_featured', 'noo_landmark_func_wrap_end_content_featured', 10 );
endif;


if ( ! function_exists('noo_landmark_func_wrap_start_container' ) ) :
	function noo_landmark_func_wrap_start_container() {

		$blog_style = isset($_GET['style']) ? $_GET['style'] : get_theme_mod('noo_blog_style', 'grid');

		if ( 'grid' === $blog_style ) {
		    wp_enqueue_script('imagesloaded');
		    wp_enqueue_script('isotope');
		    echo '<div class="noo-row"><div class="noo-blog-masonry">';
		}

	}
	add_action( 'noo_landmark_func_before_container_wrap', 'noo_landmark_func_wrap_start_container', 10 );
endif;

if ( ! function_exists('noo_landmark_func_wrap_end_container' ) ) :
	function noo_landmark_func_wrap_end_container() {
		
		$blog_style = isset($_GET['style']) ? $_GET['style'] : get_theme_mod('noo_blog_style', 'grid');

		if ( 'grid' === $blog_style ) {
		    echo '</div></div>';
		}

	}
	add_action( 'noo_landmark_func_after_container_wrap', 'noo_landmark_func_wrap_end_container', 10 );
endif;

if ( ! function_exists('noo_landmark_func_no_content_featured' ) ) :
	function noo_landmark_func_no_content_featured() {
		echo '<div class="noo-content-thumb"></div>';
	}
	add_action( 'noo_landmark_func_no_content_featured', 'noo_landmark_func_no_content_featured', 10 );
endif;