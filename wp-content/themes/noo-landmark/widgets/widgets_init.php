<?php
/**
 * This file initialize widgets area used in this theme.
 *
 *
 * @package    NOO Framework
 * @subpackage Widget Initiation
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */

if ( ! function_exists( 'noo_landmark_func_widgets_init' ) ) :

	function noo_landmark_func_widgets_init() {

		// Default Sidebar (WP main sidebar)
		register_sidebar( array(
			'name'          => esc_html__( 'Main Sidebar', 'noo-landmark' ),
			'id'            => 'sidebar-main',
			'description'   => esc_html__( 'Default Blog Sidebar.', 'noo-landmark' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		) );
		register_sidebar( array(
			'name'          => esc_html__( 'Secondary Sidebar', 'noo-landmark' ),
			'id'            => 'sidebar-secondary',
			'description'   => esc_html__( 'Display widget on Header Fullwidth Logo Center.', 'noo-landmark' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );

		// NavBar Widget
		register_sidebar( array(
			'name'          => esc_html__( 'Nav Bar', 'noo-landmark' ),
			'id'            => 'sidebar-navbar',
			'description'   => esc_html__( 'Display widget on Nav Bar.', 'noo-landmark' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );

		// Topbar Widget
		register_sidebar( array(
			'name'          => esc_html__( 'Top Bar Right', 'noo-landmark' ),
			'id'            => 'sidebar-topbar',
			'description'   => esc_html__( 'Display widget on Top Bar.', 'noo-landmark' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );

		// Footer Widget Top
		register_sidebar( array(
			'name'          => esc_html__( 'NOO - Footer top', 'noo-landmark' ),
			'id'            => 'noo-footer-top',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		) );

		// Footer Columns (Widgetized)
		$num = ( get_theme_mod( 'noo_footer_widgets' ) == '' ) ? 4 : get_theme_mod( 'noo_footer_widgets' );
		for ( $i = 1; $i <= $num; $i ++ ) :
			register_sidebar( array(
				'name'          => esc_html__( 'NOO - Footer Column #', 'noo-landmark' ) . $i,
				'id'            => 'noo-footer-' . $i,
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			) );
		endfor;

		// Footer Copyright
		register_sidebar( array(
			'name'          => esc_html__( 'NOO - Footer Copyright', 'noo-landmark' ),
			'id'            => 'noo-footer-copyright',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		) );
	}

	add_action( 'widgets_init', 'noo_landmark_func_widgets_init' );

endif;