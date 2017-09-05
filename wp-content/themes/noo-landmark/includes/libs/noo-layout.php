<?php
/**
 * Utilities Functions for NOO Framework.
 * This file contains various functions for getting and preparing data.
 *
 * @package    NOO Framework
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */

if ( ! function_exists( 'noo_landmark_func_get_page_layout' ) ):
	function noo_landmark_func_get_page_layout() {
		$layout = 'fullwidth';

		// Single post page
		if ( is_single() ) {

			// WP post,
			// check if there's override setting in this post.
			$post_id          = get_the_ID();
			$override_setting = noo_landmark_func_get_post_meta( $post_id, '_noo_wp_post_override_layout', 0 );

			if ( $override_setting == 0 ) {
				//                check same as blog
				$is_same_blog = get_theme_mod( 'noo_blog_post_layout_same', 'same_as_blog' );

				if ( $is_same_blog == 'same_as_blog' ) {
					$post_layout = get_theme_mod( 'noo_blog_layout', 'sidebar' );
				} else {
					$post_layout = get_theme_mod( 'noo_blog_post_layout', 'sidebar' );
				}
				$layout = $post_layout;
			} else {
				// override
				$layout = noo_landmark_func_get_post_meta( $post_id, '_noo_wp_post_layout', 'sidebar' );
			}
		}

		// Normal Page or Static Front Page
		if ( is_page() || ( is_front_page() && get_option( 'show_on_front' ) == 'page' ) ) {
			// WP page,
			// get the page template setting
			$page_id       = get_the_ID();
			$page_template = noo_landmark_func_get_post_meta( $page_id, '_wp_page_template', 'default' );

			if ( strpos( $page_template, 'sidebar' ) !== false ) {
				if ( strpos( $page_template, 'left' ) !== false ) {
					$layout = 'left_sidebar';
				}

				$layout = 'sidebar';
			}
		}

		// Index or Home
		if ( is_home() || is_archive() || ( is_front_page() && get_option( 'show_on_front' ) == 'posts' ) ) {

			$layout = get_theme_mod( 'noo_blog_layout', 'sidebar' );
		}

		// WooCommerce
		if ( NOO_WOOCOMMERCE_EXIST ) {
			if ( is_shop() || is_product_category() || is_product_tag() ) {
				$layout = get_theme_mod( 'noo_shop_layout', 'fullwidth' );
			}

			if ( is_product() ) {
				$is_same_shop = get_theme_mod( 'noo_woocommerce_product_layout_same', 'same_as_shop' );

				if ( $is_same_shop == 'same_as_shop' ) {
					$product_layout = get_theme_mod( 'noo_shop_layout', 'fullwidth' );
				} else {
					$product_layout = get_theme_mod( 'noo_woocommerce_product_layout', 'sidebar' );
				}
				$layout = $product_layout;
			}
		}

		return apply_filters( 'noo_page_layout', $layout );
	}
endif;

if ( ! function_exists( 'noo_landmark_func_get_sidebar_id' ) ):
	function noo_landmark_func_get_sidebar_id() {
		$sidebar = '';

		// Normal Page or Static Front Page
		if ( is_page() || ( is_front_page() && get_option( 'show_on_front' ) == 'page' ) ) {
			// Get the sidebar setting from
			$sidebar = noo_landmark_func_get_post_meta( get_the_ID(), '_noo_wp_page_sidebar', '' );
		}

		// Single post page
		if ( is_single() ) {
			// Check if there's overrode setting in this post.
			$post_id          = get_the_ID();
			$override_setting = noo_landmark_func_get_post_meta( $post_id, '_noo_wp_post_override_layout', false );

			if ( $override_setting ) {
				// overrode
				$overrode_layout = noo_landmark_func_get_post_meta( $post_id, '_noo_wp_post_layout', 'fullwidth' );
				if ( $overrode_layout != 'fullwidth' ) {
					$sidebar = noo_landmark_func_get_post_meta( $post_id, '_noo_wp_post_sidebar', 'sidebar-main' );
				}
			} else {

				$is_same_blog = get_theme_mod( 'noo_blog_post_layout_same', 'same_as_blog' );

				if ( $is_same_blog == 'same_as_blog' ) {

					$post_layout = get_theme_mod( 'noo_blog_layout', 'sidebar' );

					if ( $post_layout != 'fullwidth' ) {
						$sidebar = get_theme_mod( 'noo_blog_sidebar', 'sidebar-main' );
					}
				} else {

					$post_layout = get_theme_mod( 'noo_blog_post_layout', 'sidebar' );

					if ( $post_layout != 'fullwidth' ) {
						$sidebar = get_theme_mod( 'noo_blog_post_sidebar', 'sidebar-main' );
					}
				}
			}
		}

		// Archive, Index or Home
		if ( is_home() || is_archive() || ( is_front_page() && get_option( 'show_on_front' ) == 'posts' ) ) {

			$blog_layout = get_theme_mod( 'noo_blog_layout', 'sidebar' );
			if ( $blog_layout != 'fullwidth' ) {
				$sidebar = get_theme_mod( 'noo_blog_sidebar', 'sidebar-main' );
			}
		}

		// WooCommerce Product
		if ( NOO_WOOCOMMERCE_EXIST ) {
			if ( is_product() ) {

				$is_same_shop = get_theme_mod( 'noo_woocommerce_product_layout_same', 'same_as_shop' );

				$sidebar = '';
				if ( $is_same_shop == 'same_as_shop' ) {
					$product_layout = get_theme_mod( 'noo_shop_layout', 'fullwidth' );
					$sidebar        = get_theme_mod( 'noo_shop_sidebar', '' );
				} else {
					$product_layout = get_theme_mod( 'noo_woocommerce_product_layout', 'sidebar' );
					$sidebar        = get_theme_mod( 'noo_woocommerce_product_sidebar', '' );
				}

				if ( $product_layout == 'fullwidth' ) {
					$sidebar = '';
				}
			}

			// Shop, Product Category, Product Tag, Cart, Checkout page
			if ( is_shop() || is_product_category() || is_product_tag() ) {
				$shop_layout = get_theme_mod( 'noo_shop_layout', 'fullwidth' );
				if ( $shop_layout != 'fullwidth' ) {
					$sidebar = get_theme_mod( 'noo_shop_sidebar', '' );
				} else {
					$sidebar = '';
				}
			}
		}

		global $noo_post_types;
		if ( ! empty( $noo_post_types ) ) {
			foreach ( $noo_post_types as $post_type => $args ) {
				if ( noo_landmark_func_is_archive( $post_type ) ) {
					if ( isset( $args[ 'customizer' ] ) ) {
						if ( in_array( 'layout', $args[ 'customizer' ] ) || in_array( 'list-layout', $args[ 'customizer' ] ) ) {
							$layout = get_theme_mod( "{$post_type}_archive_layout", 'fullwidth' );
							if ( $layout != 'fullwidth' ) {
								$sidebar = get_theme_mod( "{$post_type}_archive_sidebar", 'sidebar-main' );
							}
						}
					}

					break;
				}

				if ( is_singular( $post_type ) ) {
					if ( isset( $args[ 'customizer' ] ) ) {
						if ( in_array( 'single-layout', $args[ 'customizer' ] ) ) {
							$layout = get_theme_mod( "{$post_type}_single_layout" );
							if ( $layout != 'fullwidth' ) {
								$sidebar = get_theme_mod( "{$post_type}_single_sidebar", 'sidebar-main' );
							}
						}

						if ( $layout == 'same_as_archive' ) {
							if ( in_array( 'layout', $args[ 'customizer' ] ) || in_array( 'list-layout', $args[ 'customizer' ] ) ) {
								$sidebar = get_theme_mod( "{$post_type}_archive_sidebar", 'sidebar-main' );
							}
						}
					}

					break;
				}
			}
		}

		return apply_filters( 'noo_sidebar_id', $sidebar );
	}
endif;

if ( ! function_exists( 'smk_get_all_sidebars' ) ):
	function smk_get_all_sidebars() {
		global $wp_registered_sidebars;
		$sidebars      = array();
		$none_sidebars = array();
		for ( $i = 1; $i <= 4; $i ++ ) {
			$none_sidebars[] = "noo-top-{$i}";
			$none_sidebars[] = "noo-footer-{$i}";
		}
		if ( $wp_registered_sidebars && ! is_wp_error( $wp_registered_sidebars ) ) {

			foreach ( $wp_registered_sidebars as $sidebar ) {
				// Don't include Top Bar & Footer Widget Area
				if ( in_array( $sidebar[ 'id' ], $none_sidebars ) ) {
					continue;
				}

				$sidebars[ $sidebar[ 'id' ] ] = $sidebar[ 'name' ];
			}
		}

		return $sidebars;
	}
endif;

if ( ! function_exists( 'noo_landmark_func_get_sidebar_name' ) ):
	function noo_landmark_func_get_sidebar_name( $id = '' ) {
		if ( empty( $id ) ) {
			return '';
		}

		global $wp_registered_sidebars;
		if ( $wp_registered_sidebars && ! is_wp_error( $wp_registered_sidebars ) ) {
			foreach ( $wp_registered_sidebars as $sidebar ) {
				if ( $sidebar[ 'id' ] == $id ) {
					return $sidebar[ 'name' ];
				}
			}
		}

		return '';
	}
endif;

// Backwards compatibility for wp_title
if ( ! function_exists( 'noo_landmark_func_render_title' ) ) {
	function noo_landmark_func_render_title() {
		global $wp_version;
		if ( $wp_version < 4.4 ) {
			?><title><?php wp_title( '-', true, 'right' ); ?></title><?php
		}
	}

	add_action( 'wp_head', 'noo_landmark_func_render_title' );
}