<?php
/**
 * Item Property
 *
 * @package LandMark
 * @author  KENT <tuanlv@vietbrain.com>
 */
$property_id    = get_the_ID();
$post_status    = get_post_status( $property_id );
$address        = get_post_meta( $property_id, 'address', true );
$area           = trim( get_post_meta( $property_id, 'noo_property_area', true ) );
$bed            = trim( get_post_meta( $property_id, 'noo_property_bedrooms', true ) );
$garages        = trim( get_post_meta( $property_id, 'noo_property_garages', true ) );
$bath           = trim( get_post_meta( $property_id, 'noo_property_bathrooms', true ) );
$featured       = get_post_meta( $property_id, '_featured', true );
$stock          = get_post_meta( $property_id, 'stock', true );

$class_featured = '';
if ( $featured === 'yes' ) $class_featured = ' is-featured';

$class_stock = '';
if ( $stock === 'unavailable' && $post_status !== 'pending' ) $class_stock = ' unavailable';

$display_style = ( !empty( $display_style ) ? $display_style : 'style-list' );

require noo_get_template( 'property/style-property/' . $display_style );