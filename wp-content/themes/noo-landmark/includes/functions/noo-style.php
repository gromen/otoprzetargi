<?php
/**
 * Style Functions for NOO Framework.
 * This file contains functions for calculating style (normally it's css class) base on settings from admin side.
 *
 * @package    NOO Framework
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */

if (!function_exists('noo_landmark_func_body_class')):
	function noo_landmark_func_body_class($output) {
		global $wp_customize;
		if (isset($wp_customize)) {
			$output[] = 'is-customize-preview';
		}

		// Preload
		if( get_theme_mod( 'noo_preload', false ) ) {
			$output[] = 'enable-preload';
		}

		$page_layout = noo_landmark_func_get_page_layout();
		if ($page_layout == 'fullwidth') {
			$output[] = ' page-fullwidth';
		} elseif ($page_layout == 'left_sidebar') {
			$output[] = ' page-left-sidebar';
		} else {
			$output[] = ' page-right-sidebar';
		}
		
		switch (get_theme_mod('noo_site_layout', 'fullwidth')) {
			case 'boxed':
				// if(get_page_template_slug() != 'page-full-width.php')
				$output[] = 'boxed-layout';
			break;
			default:
				$output[] = 'full-width-layout';
			break;
		}
		
		return $output;
	}
endif;
add_filter('body_class', 'noo_landmark_func_body_class');

if( !function_exists( 'noo_landmark_func_body_class_half_map' ) ):
	function noo_landmark_func_body_class_half_map($output) {
		global $wp_customize;
		if (isset($wp_customize)) {
			$output[] = 'is-customize-preview';
		}

		// Preload
		$header_style = get_theme_mod('noo_header_nav_style', 'default_menu_style');
		if( is_page() ){
			$headerpage = noo_landmark_func_get_post_meta(get_the_ID(),'_noo_wp_page_menu_style');

			if( !empty($headerpage) && $headerpage != 'menu_style_customize' ){
				$header_style = $headerpage;
			}
		}

		if( $header_style == 'style-3' ) {
			$output[] = 'page-template-header-half-map';
		}
		
		return $output;
	}
endif;
add_filter('body_class', 'noo_landmark_func_body_class_half_map');

if (!function_exists('noo_landmark_func_header_class')):
	function noo_landmark_func_header_class() {
		$class = '';
		$navbar_position = get_theme_mod('noo_header_nav_position', 'static_top');
        $menu_style = get_theme_mod('noo_header_nav_style', 'default_menu_style');
		$navbar_topbar = get_theme_mod('noo_header_fixed_top_bar', false);

        if( is_page() ){
        	// NAVBAR POSITION
        	$navbar_position_page = noo_landmark_func_get_post_meta(get_the_ID(),'_noo_wp_page_nav_position');
        	if( !empty($navbar_position_page) && $navbar_position_page != 'default_position' ) {
        		$navbar_position = $navbar_position_page;
        	}
        	// HEADER STYLE
        	$headerpage = noo_landmark_func_get_post_meta(get_the_ID(),'_noo_wp_page_menu_style');
        	if( !empty($headerpage) && $headerpage != 'menu_style_customize' ){
        		$menu_style = $headerpage;
        	}
        }

        if($navbar_position == 'fixed_top') {
        	if($navbar_topbar == false) {
				$class = 'fixed_top ';
        	} else {
				$class = 'fixed_top fixed_top_bar ';
        	}
        } elseif ($navbar_position == 'fixed_scroll_up') {
        	if($navbar_topbar == false) {
				$class = 'fixed_top fixed_scroll_up ';	
        	} else {
        		$class = 'fixed_top fixed_scroll_up fixed_top_bar ';	
        	}
		}

        if( $menu_style == 'default_menu_style' ){
        	$class .= 'header_default';
        }elseif( $menu_style == 'default_basic' ){
        	$class .= ' header_basic header_full';
        }elseif( $menu_style == 'style-1' ){
        	$class .= 'header_transparent';
        }elseif ( $menu_style == 'style-2' ){
        	$class .= " header_full header_classic";
        }elseif ( $menu_style == 'style-3' ){
        	$class .= " header_half_map";
        }elseif ( $menu_style == 'style-4' ){
        	$class .= "header_logo_center";
        }elseif ( $menu_style == 'style-5' ){
        	$class .= " header_full header_logo_full_center";
        }elseif ( $menu_style == 'style-6' ){
        	$class .= "header_full header_agency";
        }

		echo esc_attr($class);
	}
endif;


if (!function_exists('noo_landmark_func_main_class')):
	function noo_landmark_func_main_class() {
		$class = 'noo-main';
		$page_layout = noo_landmark_func_get_page_layout();

		if ($page_layout == 'fullwidth') {
			$class.= ' noo-md-12';
		} elseif ($page_layout == 'left_sidebar') {
			$class.= ' noo-md-9 pull-right';
		} else {
			$class.= ' noo-md-9';
		}

		if ( RP_Property::is_single_property() || RP_Property::is_property() ) {
			$class.= ' noo-md-9';
		}
		
		echo esc_attr($class);
	}
endif;

if (!function_exists('noo_landmark_func_sidebar_class')):
	function noo_landmark_func_sidebar_class() {
		$class = ' noo-sidebar noo-md-3';
		$page_layout = noo_landmark_func_get_page_layout();
		
		if ( $page_layout == 'left_sidebar' ) {
			$class .= ' noo-sidebar-left pull-left';
		}
		
		echo esc_attr($class);
	}
endif;

if (!function_exists('noo_landmark_func_post_class')):
	function noo_landmark_func_post_class($output) {
		if (noo_landmark_func_has_featured_content()) {
			$output[] = 'has-featured';
		} else {
			$output[] = 'no-featured';
		}

		if ( 'post' === get_post_type() ) {

			$blog_class = '';

			if ( ! is_singular() ) {

			    // Get options
			    $blog_style = isset($_GET['style']) ? $_GET['style'] : get_theme_mod('noo_blog_style', 'grid');
			    $blog_column = get_theme_mod('noo_blog_grid_columns', '2');

			    // Process    
			    if ( $blog_style === 'list2' ) {
			        $blog_class = 'list-2-column';
			    } elseif ( $blog_style === 'grid' ) {
			        $blog_class = 'grid noo-masonry-item noo-sm-6 noo-md-' . absint((12 / $blog_column));
			    } else {
			    	$blog_class = 'list-single';
			    }

			    $output[] = $blog_class;
			}
		}
		
		return $output;
	}
	
	add_filter('post_class', 'noo_landmark_func_post_class');
endif;

// Function add class to Footer
if(!function_exists('noo_landmark_func_footer_class')) {
	function noo_landmark_func_footer_class() {
		$class = '';
		$footer_style = get_theme_mod('noo_landmark_footer_style','footer_1');

		// Check Option Footer Of Page
		if( is_page() ){
			$footer_page = noo_landmark_func_get_post_meta(get_the_ID(),'_noo_wp_page_footer_style');
			if( !empty($footer_page) && $footer_page != 'default_footer_style') {
				$footer_style = $footer_page;
			}
		}

		// Add Class With Footer Style
		if($footer_style == 'footer_1') {
			$class .= ' footer_1';
		} elseif ($footer_style == 'footer_2') {
			$class .= ' footer_2';
		} elseif ($footer_style == 'footer_3') {
			$class .= ' footer_3';
		}
		echo esc_attr($class);
	}
}

// Set color on page setting for footer
if(!function_exists('noo_landmark_func_footer_color')) {
	function noo_landmark_func_footer_color() {
		$footer_page = noo_landmark_func_get_post_meta(get_the_ID(),'_noo_wp_page_footer_style');
		$footer_background_on = noo_landmark_func_get_post_meta(get_the_ID(), '_noo_wp_page_use_footer_background');
    	if( !empty($footer_page) && $footer_page != 'default_footer_style' ){
			$noo_landmark_footer_color_page = noo_landmark_func_get_post_meta(get_the_ID(),'_noo_wp_page_footer_color');
			if($footer_background_on == true) { ?>
	    		<style>
	    			.main-footer::before {
						background: <?php echo esc_attr($noo_landmark_footer_color_page); ?>;
					}
	    		</style>
	    	<?php }  else { ?>
	    		<style>
	    			.main-footer::before {
						background: none;
					}
	    		</style>
    		<?php }
    	}
	}

	add_action( 'wp_head', 'noo_landmark_func_footer_color' );
}