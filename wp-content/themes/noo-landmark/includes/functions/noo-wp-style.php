<?php
/**
 * WP Element Functions.
 * This file contains functions related to Wordpress base elements.
 * It mostly contains functions for improving trivial issue on Wordpress.
 *
 * @package    NOO Framework
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */


// Excerpt Length
// --------------------------------------------------------

if ( ! function_exists( 'noo_landmark_func_excerpt_length' ) ) :
	function noo_landmark_func_excerpt_length( $length ) {
		$excerpt_length = get_theme_mod('noo_blog_excerpt_length', 60);

		return (empty($excerpt_length) ? 60 : $excerpt_length); 
	}
	add_filter( 'excerpt_length', 'noo_landmark_func_excerpt_length' );
endif;


if(!function_exists('noo_landmark_func_the_excerpt')){
	function noo_landmark_func_the_excerpt($excerpt=''){
		return str_replace('&nbsp;', '', $excerpt);
	}
	add_filter('the_excerpt', 'noo_landmark_func_the_excerpt');
}


// Excerpt Read More
// --------------------------------------------------------

if ( ! function_exists( 'noo_landmark_func_excerpt_read_more' ) ) :
	function noo_landmark_func_excerpt_read_more( $more ) {

		return '...<div>' . noo_landmark_func_get_readmore_link() . '</div>';
	}

	add_filter( 'excerpt_more', 'noo_landmark_func_excerpt_read_more' );
endif;


// Content Read More
// --------------------------------------------------------

if ( ! function_exists( 'noo_landmark_func_content_read_more' ) ) :
	function noo_landmark_func_content_read_more( $more ) {

		return noo_landmark_func_get_readmore_link();
	}

	add_filter( 'the_content_more_link', 'noo_landmark_func_content_read_more' );
endif;

