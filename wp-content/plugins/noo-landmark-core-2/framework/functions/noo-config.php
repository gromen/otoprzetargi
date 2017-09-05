<?php
/**
 * Set default value
 *
 * @package 	Noo_Real_Estate
 * @author 		KENT <tuanlv@vietbrain.com>
 * @version 	1.0
 */

if ( ! function_exists( 'noo_config' ) ) :
	
	function noo_config() {

		$noo_config = apply_filters( 'noo_config', array( 
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

		return $noo_config;

	}

endif;

/**
 * Get config default
 *
 * @package 	Noo_Real_Estate
 * @author 		KENT <tuanlv@vietbrain.com>
 * @version 	1.0
 */

if ( ! function_exists( 'noo_get_config' ) ) :
	
	function noo_get_config( $key = '' ) {

		$noo_config = noo_config();
		$return     = '';
		
		if( isset( $noo_config[$key] ) ) $return = $noo_config[$key];
		
		return apply_filters( 'noo_config_default_' . $key, $return );

	}

endif;