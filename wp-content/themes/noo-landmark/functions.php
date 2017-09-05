<?php
/**
 * Theme functions for NOO Framework.
 * This file include the framework functions, it should remain intact between themes.
 * For theme specified functions, see file functions-<theme name>.php
 *
 * @package    NOO Framework
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */

// Set global constance
// Functions for specific theme
$theme_name = basename( get_template_directory() );

if ( ! defined( 'NOO_THEME_NAME' ) ) {
	define( 'NOO_THEME_NAME', $theme_name );
}

if ( ! defined( 'NOO_WOOCOMMERCE_EXIST' ) ) {
	define( 'NOO_WOOCOMMERCE_EXIST', class_exists( 'WC_API' ) );
}

//
// Plugins
// First we'll check if there's any plugins inluded
//
require_once get_template_directory() . '/plugins/plugins.php';

// Initialize NOO Libraries

require_once get_template_directory() . '/includes/libs/noo-theme.php';
require_once get_template_directory() . '/includes/libs/noo-layout.php';
require_once get_template_directory() . '/includes/libs/noo-post-type.php';
require_once get_template_directory() . '/includes/libs/noo-css.php';
require_once get_template_directory() . '/includes/libs/noo-customize.php';

// Support plugin realty portal
require_once get_template_directory() . '/includes/realty-portal/function-realty-portal.php';
require_once get_template_directory() . '/includes/realty-portal/functions-layouts.php';
require_once get_template_directory() . '/includes/realty-portal/custom-column-property-admin.php';

// Theme setup
require_once get_template_directory() . '/includes/theme_setup.php';

//
// Customize, metabox
//
if ( class_exists( 'Noo_Landmark_Core_2' ) ) :

	require_once get_template_directory() . '/includes/customizer/options.php';
	require_once get_template_directory() . '/includes/add_metabox/function-init.php';

endif;

//
// Google Font
//

function noo_landmark_fonts_url() {
	// Enqueue Fonts.

	$body_font_family     = noo_landmark_func_get_theme_default( 'font_family' );
	$headings_font_family = noo_landmark_func_get_theme_default( 'headings_font_family' );
	$nav_font_family      = noo_landmark_func_get_theme_default( 'nav_font_family' );
	$logo_font_family     = noo_landmark_func_get_theme_default( 'logo_font_family' );
	$fonts_url            = '';
	$subsets              = 'latin,latin-ext';

	$font_families = array();

	$noo_typo_use_custom_headings_font = get_theme_mod( 'noo_typo_use_custom_headings_font', false );
	$noo_typo_use_custom_body_font     = get_theme_mod( 'noo_typo_use_custom_body_font', false );
	$nav_custom_font                   = get_theme_mod( 'noo_header_custom_nav_font', false );
	$use_image_logo                    = get_theme_mod( 'noo_header_use_image_logo', false );

	if ( $noo_typo_use_custom_body_font != false ) {
		$body_font_family = get_theme_mod( 'noo_typo_body_font', $body_font_family );
	}

	if ( $noo_typo_use_custom_headings_font != false ) {
		$headings_font_family = get_theme_mod( 'noo_typo_headings_font', $headings_font_family );
	}

	if ( $nav_custom_font != false ) {
		$nav_font_family = get_theme_mod( 'noo_header_nav_font', $nav_font_family );
	}

	if ( $use_image_logo == false ) {
		$logo_font_family = get_theme_mod( 'noo_header_logo_font', $logo_font_family );
	}

	$body_trans = _x( 'on', 'Body font: on or off', 'noo-landmark' );

	$heading_trans = _x( 'on', 'Heading font: on or off', 'noo-landmark' );

	$nav_trans = _x( 'on', 'Nav font: on or off', 'noo-landmark' );

	$logo_trans = _x( 'on', 'Logo font: on or off', 'noo-landmark' );

	if ( 'off' !== $body_trans ) {
		$font_families[] = $body_font_family . ':' . '100,300,400,500,600,700,900,300italic,400italic,700italic,900italic';
	}

	if ( 'off' !== $heading_trans ) {

		$font_families[] = $headings_font_family . ':' . '100,300,400,500,600,700,900,300italic,400italic,700italic,900italic';
	}

	if ( 'off' !== $nav_trans && $nav_custom_font != false ) {

		$font_families[] = $nav_font_family . ':' . '100,300,400,500,600,700,900,300italic,400italic,700italic,900italic';
	}

	if ( 'off' !== $logo_trans && $use_image_logo == false && ! empty( $logo_font_family ) ) {

		$font_families[] = $logo_font_family . ':' . '100,300,400,500,600,700,900,300italic,400italic,700italic,900italic';
	}

	$subset = _x( 'no-subset', 'Add new subset (greek, cyrillic, devanagari, vietnamese)', 'noo-landmark' );

	if ( 'cyrillic' == $subset ) {
		$subsets .= ',cyrillic,cyrillic-ext';
	} elseif ( 'greek' == $subset ) {
		$subsets .= ',greek,greek-ext';
	} elseif ( 'devanagari' == $subset ) {
		$subsets .= ',devanagari';
	} elseif ( 'vietnamese' == $subset ) {
		$subsets .= ',vietnamese';
	}

	if ( $font_families ) {
		$fonts_url = add_query_arg( array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( $subsets ),
		), 'https://fonts.googleapis.com/css' );
	}

	return esc_url_raw( $fonts_url );
}

/**
 * Enqueue script
 *
 */

if ( ! function_exists( 'noo_landmark_func_enqueue_scripts' ) ) :

	function noo_landmark_func_enqueue_scripts() {

		if ( ! is_admin() ) {

			if ( is_file( noo_landmark_func_upload_dir() . '/custom.css' ) ) {
				wp_register_style( 'noo-custom-style', noo_landmark_func_upload_url() . '/custom.css', null, null, 'all' );
			}

			/**
			 * Enqueue library owl-carousel
			 */
			wp_register_style( 'carousel', get_template_directory_uri() . '/assets/vendor/owl.carousel.css', array(), '2.0.0' );

			/**
			 * Enqueue library swiper
			 */
			wp_register_style( 'swiper', get_template_directory_uri() . '/assets/vendor/swiper/css/swiper.css', array(), '3.3.1' );

			/**
			 * Enqueue library LightGallery
			 */
			wp_register_style( 'lightgallery', get_template_directory_uri() . '/assets/vendor/lightgallery/dist/css/lightgallery.css', array(), '1.2.22' );

			/**
			 * Enqueue library wysihtml
			 */
			wp_register_script( 'wysihtml5', get_template_directory_uri() . '/assets/vendor/wysihtml5/wysihtml5.js', array( 'jquery' ), null, false );

			// Vendors
			// Font Awesome
			wp_register_style( 'font-awesome-css', get_template_directory_uri() . '/assets/vendor/fontawesome/css/font-awesome.min.css', array(), '4.2.0' );
			wp_enqueue_style( 'font-awesome-css' );

			// Font ionicons
			wp_register_style( 'ionicons-css', get_template_directory_uri() . '/assets/vendor/ionicons/css/ionicons.min.css', array(), '4.2.0' );
			wp_enqueue_style( 'ionicons-css' );

			wp_enqueue_style( 'noo-landmark-fonts', noo_landmark_fonts_url(), array(), null );
			wp_enqueue_style( 'noo-css', get_template_directory_uri() . '/assets/css/noo.css', array(), null, null );

			if ( NOO_WOOCOMMERCE_EXIST ) {
				wp_enqueue_style( 'noo-woocommerce-css', get_template_directory_uri() . '/assets/css/noo.woocommerce.css', array(), null, null );
			}

			if ( ! get_theme_mod( 'noo_use_inline_css', false ) && wp_style_is( 'noo-custom-style', 'registered' ) ) {
				global $wp_customize;
				if ( ! isset( $wp_customize ) ) {
					wp_enqueue_style( 'noo-custom-style' );
				}
			}

			/*
			 * RTL Support
			 */

			if ( is_rtl() ) :

				wp_enqueue_style( 'noo-style-rtl', get_template_directory_uri() . '/rtl.css' );

			endif;

			// Main style
			wp_enqueue_style( 'noo-style', get_stylesheet_uri() );
		}

		// Main script

		// vendor script
		wp_register_script( 'modernizr', get_template_directory_uri() . '/assets/vendor/modernizr-2.7.1.min.js', null, null, false );

		wp_register_script( 'imagesloaded', get_template_directory_uri() . '/assets/vendor/imagesloaded.pkgd.min.js', null, null, true );
		wp_register_script( 'isotope', get_template_directory_uri() . '/assets/vendor/jquery.isotope.min.js', array( 'imagesloaded' ), null, true );
		wp_register_script( 'masonry', get_template_directory_uri() . '/assets/vendor/masonry.pkgd.min.js', array( 'imagesloaded' ), null, true );
		wp_register_script( 'infinitescroll', get_template_directory_uri() . '/assets/vendor/infinitescroll-2.0.2.min.js', null, null, true );
		wp_register_script( 'bxslider', get_template_directory_uri() . '/assets/vendor/bxslider/jquery.bxslider.js', null, null, true );

		wp_register_script( 'touchSwipe', get_template_directory_uri() . '/assets/vendor/jquery.touchSwipe.js', array( 'jquery' ), null, true );
		wp_register_script( 'carouFredSel', get_template_directory_uri() . '/assets/vendor/carouFredSel/jquery.carouFredSel-6.2.1-packed.js', array(
			'jquery',
			'touchSwipe',
			'imagesloaded',
		), null, true );

		wp_register_script( 'jplayer', get_template_directory_uri() . '/assets/vendor/jplayer/jplayer-2.5.0.min.js', array( 'jquery' ), null, true );
		wp_register_script( 'nivo-lightbox-js', get_template_directory_uri() . '/assets/vendor/nivo-lightbox/nivo-lightbox.min.js', array( 'jquery' ), null, true );
		wp_register_script( 'fancybox-lightbox-js', get_template_directory_uri() . '/assets/vendor/fancybox-lightbox/source/jquery.fancybox.pack.js', array( 'jquery' ), null, true );
		wp_register_script( 'parallax', get_template_directory_uri() . '/assets/vendor/parallax.js', array( 'jquery' ), null, true );
		wp_register_script( 'noo-category', get_template_directory_uri() . '/assets/js/noo_category.js', array( 'jquery' ), null, true );
		wp_register_script( 'carousel', get_template_directory_uri() . '/assets/vendor/owl.carousel.js', array( 'jquery' ), null, true );
		wp_register_script( 'swiper', get_template_directory_uri() . '/assets/vendor/swiper/js/swiper.jquery.js', array( 'jquery' ), null, false );
		wp_register_script( 'lightgallery', get_template_directory_uri() . '/assets/vendor/lightgallery/dist/js/lightgallery-all.js', array( 'jquery' ), null, false );
		wp_register_script( 'lightgallery_mousewheel', get_template_directory_uri() . '/assets/vendor/lightgallery/lib/jquery.mousewheel.min.js', array( 'jquery' ), null, false );
		wp_register_script( 'wow', get_template_directory_uri() . '/assets/vendor/wow/wow.min.js', array( 'jquery' ), null, true );

		wp_register_script( 'noo-script', get_template_directory_uri() . '/assets/js/noo_main.js', array(
			'jquery',
			'carouFredSel',
			'bxslider',
		), null, true );
		wp_register_script( 'noo-custom', get_template_directory_uri() . '/assets/js/noo_custom.js', array( 'jquery' ), null, true );
		wp_register_script( 'noo-woocommerce', get_template_directory_uri() . '/assets/js/noo_woocommerce.js', array( 'jquery' ), null, true );

		if ( ! is_admin() ) {
			wp_enqueue_script( 'modernizr' );

			// Required for nested reply function that moves reply inline with JS
			if ( is_singular() ) {
				wp_enqueue_script( 'comment-reply' );
			}

			$is_shop = NOO_WOOCOMMERCE_EXIST && is_shop();
			$nooL10n = array(
				'ajax_url'                => admin_url( 'admin-ajax.php', 'relative' ),
				'ajax_finishedMsg'        => esc_html__( 'All posts displayed', 'noo-landmark' ),
				'home_url'                => esc_attr( home_url( '/' ) ),
				'is_blog'                 => is_home() ? 'true' : 'false',
				'is_archive'              => is_post_type_archive( 'post' ) ? 'true' : 'false',
				'is_single'               => is_single() ? 'true' : 'false',
				'is_shop'                 => NOO_WOOCOMMERCE_EXIST && is_shop() ? 'true' : 'false',
				'is_product'              => NOO_WOOCOMMERCE_EXIST && is_product() ? 'true' : 'false',
				'infinite_scroll_end_msg' => esc_html__( 'All posts displayed', 'noo-landmark' ),
				'noo_results'             => esc_html__( 'Oops, nothing found!', 'noo-landmark' ),
			);

			global $noo_post_types;
			if ( ! empty( $noo_post_types ) ) {
				foreach ( $noo_post_types as $post_type => $args ) {
					$nooL10n[ 'is_' . $post_type . '_archive' ] = is_post_type_archive( $post_type ) ? 'true' : 'false';
					$nooL10n[ 'is_' . $post_type . '_single' ]  = is_singular( $post_type ) ? 'true' : 'false';
				}
			}

			wp_localize_script( 'noo-script', 'nooL10n', $nooL10n );
			wp_enqueue_script( 'infinitescroll' );

			wp_enqueue_script( 'noo-cabas', get_template_directory_uri() . '/assets/js/off-cavnass.js', array(), null, true );
			wp_enqueue_script( 'noo-new', get_template_directory_uri() . '/assets/js/noo_new.js', array(), null, true );
			wp_enqueue_script( 'noo-script' );

			if ( NOO_WOOCOMMERCE_EXIST ) {
				wp_enqueue_script( 'noo-woocommerce' );
			}
		}

		/**
		 * Upload
		 */
		wp_register_script( 'noo-upload', get_template_directory_uri() . '/assets/js/noo-upload.js', array(
			'jquery',
			'plupload-all',
			'jquery-ui-sortable',
		), null, false );
		wp_localize_script( 'noo-upload', 'NooUpload', array(
			'ajax_url'             => admin_url( 'admin-ajax.php' ),
			'security'             => wp_create_nonce( 'noo-upload' ),
			'text_max_size_upload' => wp_create_nonce( 'noo-upload' ),
			'remove_image'         => esc_html__( 'Remove image', 'noo-landmark' ),
			'allow_format'         => 'jpg,jpeg,gif,png',
			'flash_swf_url'        => includes_url( 'js/plupload/plupload.flash.swf' ),
			'silverlight_xap_url'  => includes_url( 'js/plupload/plupload.silverlight.xap' ),
		) );
	}

	add_action( 'wp_enqueue_scripts', 'noo_landmark_func_enqueue_scripts' );

endif;

// Helper functions
require_once get_template_directory() . '/includes/functions/noo-html.php';
require_once get_template_directory() . '/includes/functions/noo-utilities.php';
require_once get_template_directory() . '/includes/functions/noo-style.php';
require_once get_template_directory() . '/includes/functions/noo-wp-style.php';

// Mega Menu
require_once get_template_directory() . '/includes/mega-menu/noo_mega_menu.php';

// WooCommerce

require_once get_template_directory() . '/includes/woocommerce.php';
require_once get_template_directory() . '/includes/woocommerce-hooks.php';

//
// Widgets
//
$widget_path = get_template_directory() . '/widgets';

if ( file_exists( $widget_path . '/widgets_init.php' ) ) {
	require_once $widget_path . '/widgets_init.php';
	require_once $widget_path . '/widgets.php';
}

if ( ! function_exists( 'noo_loader_init' ) ) :

    function noo_loader_init() {

	    require_once get_template_directory() . '/includes/realty-portal/shortcode/loader.php';
	    require_once get_template_directory() . '/includes/realty-portal/customizer.php';

	    remove_shortcode( 'rp_property_list', 'RP_Shortcodes::property_list' );

	    if ( function_exists( 'vc_remove_element' ) ) {
		    vc_remove_element( "rp_property_list" );
		    vc_remove_element( "rp_advanced_search" );
	    }

    }

    add_action( 'init', 'noo_loader_init', 99999 );

endif;