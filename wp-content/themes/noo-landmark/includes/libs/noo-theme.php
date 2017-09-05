<?php
/**
 * Helper functions for NOO Framework.
 * Function for getting view files. There's two kind of view files,
 * one is default view from framework, the other is view from specific theme.
 * File from specific theme will override that from framework.
 *
 * @package    NOO Framework
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */

// Shorthand function get predefined layout
if ( ! function_exists( 'noo_landmark_func_get_layout' ) ) :
	function noo_landmark_func_get_layout( $slug, $name = '' ) {
		get_template_part( 'layouts/' . $slug, $name );
	}
endif;

// Function for getting image from theme option
// This function is initially created because of change from WordPress 4.1
if ( ! function_exists( 'noo_landmark_func_get_image_option' ) ):
	function noo_landmark_func_get_image_option( $option, $default ) {
		$image = get_theme_mod( $option );

		$image = ( $image === null || $image === '' ) ? $default : $image;
		$image = ( ! empty( $image ) && is_int( $image ) ) ? wp_get_attachment_url( $image ) : $image;

		return $image;
	}
endif;

if ( ! function_exists( 'noo_landmark_func_init_theme_default' ) ) :
	function noo_landmark_func_init_theme_default( $keys = null ) {
		global $noo_theme;
		if ( empty( $noo_theme ) ) {
			$noo_theme = apply_filters( 'noo_theme_default', array(
				'primary_color'        => '#114a82',
				'secondary_color'      => '#f9a11b',
				'font_family'          => 'Poppins',
				'text_color'           => '#000',
				'font_size'            => '14',
				'font_weight'          => '400',
				'headings_font_family' => 'Exo 2',
				'headings_color'       => '#000',
				'logo_color'           => '#000',
				'logo_font_family'     => 'Exo 2',
			) );
		}
	}
endif;

if ( ! function_exists( 'noo_landmark_func_set_theme_default' ) ) :
	function noo_landmark_func_set_theme_default( $keys = null, $value = null ) {
		global $noo_theme;
		noo_landmark_func_init_theme_default();
		if ( is_null( $keys ) ) {
			return;
		}
		if ( is_array( $keys ) ) {
			$noo_theme = array_merge( $noo_theme, $keys );
		}

		if ( is_string( $keys ) && ! is_null( $value ) ) {
			$noo_theme[ $keys ] = $value;
		}
	}
endif;

if ( ! function_exists( 'noo_landmark_func_get_theme_default' ) ) :
	function noo_landmark_func_get_theme_default( $key = '' ) {
		global $noo_theme;
		noo_landmark_func_init_theme_default();

		$return = '';
		if ( isset( $noo_theme[ $key ] ) ) {
			$return = $noo_theme[ $key ];
		}

		return apply_filters( 'noo_theme_default_' . $key, $return );
	}
endif;

if ( ! function_exists( 'noo_landmark_func_get_theme_preset' ) ) :
	function noo_landmark_func_get_theme_preset() {
		$preset_color = array(
			'preset_1' => array(
				'primary'   => '#114a82',
				'secondary' => '#f9a11b',
			),
			'preset_2' => array(
				'primary'   => '#3f788a',
				'secondary' => '#63bbd6',
			),
			'preset_3' => array(
				'primary'   => '#185a9b',
				'secondary' => '#25ade9',
			),
			'preset_4' => array(
				'primary'   => '#233354',
				'secondary' => '#f94f5b',
			),
			'preset_5' => array(
				'primary'   => '#20272b',
				'secondary' => '#5ab70e',
			),
		);

		return apply_filters( 'noo_theme_default_preset', $preset_color );
	}
endif;