<?php
$property_name = esc_html__( 'Properties', 'realty-portal' );
$agent_name    = esc_html__( 'Agent', 'realty-portal' );

/**
 * Create shortcode: RP Saved Search
 *
 * @package     Realty_Portal
 * @author      NooTeam <suppport@nootheme.com>
 * @version     1.0
 */
ns_map( array(
	'name'     => esc_html__( 'RP Saved Search', 'realty-portal' ),
	'base'     => 'rp_saved_search',
	'icon'     => 'rp-saved-search',
	'category' => $agent_name,
	'params'   => array(),
) );

/**
 * Create shortcode: Property List
 *
 * @package     Realty_Portal
 * @author      NooTeam <suppport@nootheme.com>
 */
ns_map( array(
	'name'     => esc_html__( 'Property List', 'realty-portal' ),
	'base'     => 'rp_property_list',
	'icon'     => 'rp-property-list',
	'category' => $property_name,
	'params'   => array(
		array(
			'param_name'  => 'title',
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Title', 'realty-portal' ),
			'std'         => esc_html__( 'Property Listing', 'realty-portal' ),
		),
		array(
			'param_name' => 'listing_offers',
			'type'       => 'dropdown',
			'heading'    => esc_html__( 'Listing Offers', 'realty-portal' ),
			'value'      => rp_get_list_tax( apply_filters( 'rp_property_listing_offers', 'listing_offers' ), true, esc_html__( 'All', 'realty-portal' ) ),
		),
		array(
			'param_name' => 'listing_type',
			'type'       => 'dropdown',
			'heading'    => esc_html__( 'Listing Types', 'realty-portal' ),
			'value'      => rp_get_list_tax( apply_filters( 'rp_property_listing_type', 'listing_type' ), true, esc_html__( 'All', 'realty-portal' ) ),
		),
		array(
			'param_name'  => 'orderby',
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Orderby', 'realty-portal' ),
			'admin_label' => true,
			'std'         => 'date',
			'value'       => array(
				esc_html__( 'Featured', 'realty-portal' ) => 'featured',
				esc_html__( 'Date', 'realty-portal' )     => 'date',
				esc_html__( 'Price', 'realty-portal' )    => 'price',
				esc_html__( 'Name', 'realty-portal' )     => 'name',
				esc_html__( 'Area', 'realty-portal' )     => 'area',
				esc_html__( 'Random', 'realty-portal' )   => 'rand',
			),
		),
		array(
			'param_name'  => 'order',
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Order', 'realty-portal' ),
			'admin_label' => true,
			'std'         => 'DESC',
			'value'       => array(
				esc_html__( 'Recent First', 'realty-portal' ) => 'DESC',
				esc_html__( 'Older First', 'realty-portal' )  => 'ASC',
			),
		),
		array(
			'param_name' => 'posts_per_page',
			'type'       => 'textfield',
			'heading'    => esc_html__( 'Posts Per Page', 'realty-portal' ),
			'std'        => '10',
		),
	),
) );