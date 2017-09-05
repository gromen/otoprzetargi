<?php
/**
 * Add new font icon
 *
 * @package 	Realty_Portal
 * @author 		NooTeam <suppport@nootheme.com>
 * @version 	1.0
 */

if ( ! function_exists( 'rp_add_new_font_visualcomposer' ) ) :
	
	function rp_add_new_font_visualcomposer( $icons ) {

		$font_plugin = array(
			'Realty_Portal' => array(
				array( 'icon-price-house'   => 'Price House' ),
				array( 'icon-rent'          => 'Rent' ),
				array( 'icon-painting'      => 'Painting' ),
				array( 'icon-safe-house'    => 'Safe House' ),
				array( 'icon-user-add'      => 'User Add' ),
				array( 'icon-vertification' => 'Vertification' ),
				array( 'icon-worldmap'      => 'World Map' ),
				array( 'icon-mapmarker'     => 'Map Marker' ),
				array( 'icon-decotitle'     => 'Decotitle' ),
				array( 'rp-icon-ruler'         => 'Ruler' ),
				array( 'rp-icon-bed'           => 'Bed' ),
				array( 'rp-icon-bath'          => 'Bath' ),
				array( 'rp-icon-storage'       => 'Storage' ),
				array( 'icon-brick'         => 'Brick' ),
				array( 'icon-pool'          => 'Pool' ),
				array( 'icon-floor'         => 'Floor' ),
				array( 'icon-compass'       => 'Compass' ),
			)
		);

		return array_reverse( array_merge( $icons, $font_plugin ) );

	}

	add_filter( 'vc_iconpicker-type-realty-portal', 'rp_add_new_font_visualcomposer' );

endif;