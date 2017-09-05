<?php
/**
 * Add new font icon
 *
 * @package 	LandMark
 * @author 		KENT <tuanlv@vietbrain.com>
 * @version 	1.0
 */

if ( ! function_exists( 'noo_add_new_font_visualcomposer' ) ) :
	
	function noo_add_new_font_visualcomposer( $icons ) {

		$font_landmark = array(
			'LandMark' => array(
				array( 'icon-price-house'   => 'Price House' ),
				array( 'icon-rent'          => 'Rent' ),
				array( 'icon-painting'      => 'Painting' ),
				array( 'icon-safe-house'    => 'Safe House' ),
				array( 'icon-user-add'      => 'User Add' ),
				array( 'icon-vertification' => 'Vertification' ),
				array( 'icon-worldmap'      => 'World Map' ),
				array( 'icon-mapmarker'     => 'Map Marker' ),
				array( 'icon-decotitle'     => 'Decotitle' ),
				array( 'icon-ruler'         => 'Ruler' ),
				array( 'icon-bed'           => 'Bed' ),
				array( 'icon-bath'          => 'Bath' ),
				array( 'icon-storage'       => 'Storage' ),
				array( 'icon-brick'         => 'Brick' ),
				array( 'icon-pool'          => 'Pool' ),
				array( 'icon-floor'         => 'Floor' ),
				array( 'icon-compass'       => 'Compass' ),
			)
		);

		return array_reverse( array_merge( $icons, $font_landmark ) );

	}

	add_filter( 'vc_iconpicker-type-landmark', 'noo_add_new_font_visualcomposer' );

endif;