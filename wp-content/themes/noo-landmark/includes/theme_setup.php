<?php
/**
 * Initialize Theme functions for NOO Themes.
 *
 * @package    NOO Themes
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */

// Content Width
if ( ! isset( $content_width ) ) :
	$content_width = 970;
endif;

// Initialize Theme
if (!function_exists('noo_landmark_func_init_theme')):
	function noo_landmark_func_init_theme() {
		load_theme_textdomain( 'noo-landmark', get_template_directory() . '/languages' );

		require_once 'theme-option/noo-setup-install.php';
		require_once 'theme-option/noo-check-version.php';
 
        if ( is_admin() ) {     
            $license_manager = new Noo_Landmark_Class_Check_Version(
                'noo-landmark',
                'Noo LandMark',
                'http://update.nootheme.com/api/license-manager/v1',
                'theme',
                '',
                false
            );
        }

		// Title Tag -- From WordPress 4.1.
		add_theme_support('title-tag');
		// @TODO: Automatic feed links.
		add_theme_support('automatic-feed-links');
		// Add support for some post formats.
		add_theme_support('post-formats', array(
			'image',
			'gallery',
			'video',
			'audio',
			'quote'
		));

		add_theme_support( 'woocommerce' );

		// WordPress menus location.
		$menu_list = array();
		
		$menu_list['primary'] = esc_html__( 'Primary Menu', 'noo-landmark');
		$menu_list['primary-left'] = esc_html__( 'Main Menu Left', 'noo-landmark');
		$menu_list['primary-right'] = esc_html__( 'Main Menu Right', 'noo-landmark');

		if (get_theme_mod( 'noo_header_top_bar', false ) && (get_theme_mod('noo_top_bar_type', 'menu') == 'menu') ) {
			$menu_list['top-menu'] = esc_html__( 'Top Menu', 'noo-landmark');
		}
		
		if (get_theme_mod('noo_footer_top', false)) {
			$menu_list['footer-menu'] = esc_html__( 'Footer Menu', 'noo-landmark');
		}

		// Register Menu
		register_nav_menus( apply_filters( 'noo_landmark_menu_list', $menu_list ) );

		// Define image size
		add_theme_support('post-thumbnails');
		add_image_size('noo-thumbnail-product', 300, 300, true);

		$default_values = array( 
			'primary_color'        => '#114a82',
			'secondary_color'      => '#f9a11b',
			'font_family'          => 'Poppins',
			'text_color'           => '#000',
			'font_size'            => '14',
			'font_weight'          => '400',
			'headings_font_family' => 'Exo 2',
			'nav_font_family'      => 'Exo 2',
			'headings_color'       => '#000',
			'logo_color'           => '#000',
			'logo_font_family'     => 'Exo 2',
		);
		noo_landmark_func_set_theme_default( $default_values );
	}
	add_action('after_setup_theme', 'noo_landmark_func_init_theme');
endif;