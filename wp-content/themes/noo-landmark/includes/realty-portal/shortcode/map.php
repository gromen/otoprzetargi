<?php
$category_name = esc_html__( 'LandMark', 'noo-landmark' );
$property_name = esc_html__( 'Properties', 'noo-landmark' );
$agent_name    = esc_html__( 'Agent', 'noo-landmark' );

if ( ! function_exists( 'noo_lankmark_add_field_advanced_search' ) ) :

    function noo_lankmark_add_field_advanced_search( $name, $field ) {

	    $class  = 'noo-md-6';
	    if ( ! empty( $field[ 'class' ] ) ) {
		    $class = esc_attr( $field[ 'class' ] );
	    }

		switch ( $name ) {
			case 'property_status':
				$listing_offers = rp_get_list_tax( 'property_status' );
				$offers         = ! empty( $_GET[ 'offers' ] ) ? rp_validate_data( $_GET[ 'offers' ] ) : '';
				$args_offers    = array(
					'name'             => 'offers',
					'title'            => '',
					'type'             => 'select',
					'placeholder'      => esc_html__( 'Offers', 'realty-portal-advanced-search' ),
					'class'            => $class,
					'options'          => $listing_offers,
					'show_none_option' => true,
				);
				rp_create_element( $args_offers, $offers );
				break;

			case 'property_type':
				$listing_type = rp_get_list_tax( 'property_type' );
				$types        = ! empty( $_GET[ 'types' ] ) ? rp_validate_data( $_GET[ 'types' ] ) : '';
				$args_types   = array(
					'name'             => 'types',
					'title'            => '',
					'type'             => 'select',
					'placeholder'      => esc_html__( 'Listing Types', 'realty-portal-advanced-search' ),
					'class'            => $class,
					'options'          => $listing_type,
					'show_none_option' => true,
				);
				rp_create_element( $args_types, $types );
				break;

		}

    }

    add_action( 'rp_advanced_search_fields', 'noo_lankmark_add_field_advanced_search', 3, 10 );

endif;

/**
 * Create element: Single Property Map
 *
 * @package     LandMark
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */
if ( class_exists( 'Realty_Portal' ) ) :

	vc_map( array(
		'name'     => esc_html__( 'Single Property Map', 'noo-landmark' ),
		'base'     => 'noo_single_property_map',
		'icon'     => 'noo_icon_single_property_map',
		'category' => $property_name,
		'params'   => array(
			array(
				'type'        => 'textfield',
				'admin_label' => true,
				'heading'     => esc_html__( 'ID Property', 'noo-landmark' ),
				'description' => esc_html__( 'Please enter ID Property do you want display...', 'noo-landmark' ),
				'param_name'  => 'property_id',
			),
			array(
				'param_name'  => 'height',
				'heading'     => esc_html__( 'Height', 'noo-landmark' ),
				'type'        => 'textfield',
				'admin_label' => true,
				'std'         => Realty_Portal::get_setting( 'google_map', 'height', '450' ),
			),
		),
	) );

endif;

/**
 * Create element: Noo Agent
 *
 * @package     LandMark
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */
if ( function_exists( 'noo_get_list_tax' ) ) :

	vc_map( array(
		'name'     => esc_html__( 'Noo Agent', 'noo-landmark' ),
		'base'     => 'noo_agent',
		'icon'     => 'noo_icon_agent',
		'category' => $agent_name,
		'params'   => array(
			array(
				'param_name'  => 'title',
				'type'        => 'textfield',
				'admin_label' => true,
				'heading'     => esc_html__( 'Title', 'noo-landmark' ),
			),
			array(
				'param_name'  => 'sub_title',
				'type'        => 'textfield',
				'admin_label' => true,
				'heading'     => esc_html__( 'Sub Title', 'noo-landmark' ),
			),
			array(
				'param_name' => 'agent_category',
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Agent Category', 'noo-landmark' ),
				'value'      => noo_get_list_tax( 'agent_category', true, esc_html__( 'All', 'noo-landmark' ) ),
			),
			array(
				'param_name'  => 'only_agent',
				'heading'     => esc_html__( 'Show only agent have property', 'noo-landmark' ),
				'description' => '',
				'type'        => 'dropdown',
				'std'         => 'no',
				'admin_label' => true,
				'value'       => array(
					esc_html__( 'Yes', 'noo-landmark' ) => 'yes',
					esc_html__( 'No', 'noo-landmark' )  => 'no',
				),
			),
			array(
				'param_name'  => 'posts_per_page',
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Posts Per Page', 'noo-landmark' ),
				'admin_label' => true,
				'std'         => '10',
			),
			array(
				'param_name'  => 'orderby',
				'heading'     => esc_html__( 'Order By', 'noo-landmark' ),
				'description' => '',
				'type'        => 'dropdown',
				'std'         => 'latest',
				'admin_label' => true,
				'value'       => array(
					esc_html__( 'Recent First', 'noo-landmark' )            => 'latest',
					esc_html__( 'Older First', 'noo-landmark' )             => 'oldest',
					esc_html__( 'Title Alphabet', 'noo-landmark' )          => 'alphabet',
					esc_html__( 'Title Reversed Alphabet', 'noo-landmark' ) => 'ralphabet',
				),
			),
		),
	) );

endif;

function noo_get_list_tax( $name_tax = '', $reverse = false, $option_null = '' ) {

	if ( empty( $name_tax ) ) {
		return;
	}

	$list_tax = array();
	$data_tax = (array) get_terms( esc_attr( $name_tax ), array(
		'orderby'    => 'title',
		'hide_empty' => 0,
	) );

	if ( isset( $data_tax ) && ! empty( $data_tax ) ) {

		if ( ! empty( $option_null ) ) {
			if ( $reverse ) {
				$list_tax[ $option_null ] = '';
			} else {
				$list_tax[ '' ] = $option_null;
			}
		}

		foreach ( $data_tax as $tax ) {
			if ( empty( $tax->name ) ) {
				continue;
			}
			if ( $reverse ) {
				$list_tax[ $tax->name ] = $tax->term_id;
			} else {
				$list_tax[ $tax->term_id ] = $tax->name;
			}
		}
	}

	return $list_tax;
}

/**
 * Create element: Property List
 *
 * @package     LandMark
 * @author      KENT <tuanlv@vietbrain.com>
 * @since       1.4.0
 */
if ( defined( 'NOO_PLUGIN_ASSETS_URI' ) ) :

	vc_map( array(
		'name'     => esc_html__( 'Property List', 'noo-landmark' ),
		'base'     => 'noo_recent_property',
		'icon'     => 'noo_icon_recent_property',
		'category' => $property_name,
		'params'   => array(
			array(
				'param_name'  => 'title',
				'type'        => 'textfield',
				'admin_label' => true,
				'heading'     => esc_html__( 'Title', 'noo-landmark' ),
			),
			array(
				'param_name'  => 'sub_title',
				'type'        => 'textfield',
				'admin_label' => true,
				'heading'     => esc_html__( 'Sub Title', 'noo-landmark' ),
			),
			array(
				'param_name' => 'style',
				'type'       => 'radio_image',
				'heading'    => esc_html__( 'Style', 'noo-landmark' ),
				'std'        => 'style-1',
				'value'      => array(
					NOO_PLUGIN_ASSETS_URI . '/images/property-list-1.jpg' => 'style-1',
					NOO_PLUGIN_ASSETS_URI . '/images/property-list-2.jpg' => 'style-2',
					NOO_PLUGIN_ASSETS_URI . '/images/property-list-3.jpg' => 'style-3',
					NOO_PLUGIN_ASSETS_URI . '/images/property-list-4.jpg' => 'style-4',
				),
			),
			array(
				'param_name' => 'property_status',
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Property Status', 'noo-landmark' ),
				'value'      => noo_get_list_tax( 'property_status', true, esc_html__( 'All', 'noo-landmark' ) ),
			),
			array(
				'param_name' => 'property_type',
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Property Types', 'noo-landmark' ),
				'value'      => noo_get_list_tax( 'property_type', true, esc_html__( 'All', 'noo-landmark' ) ),
			),
			array(
				'param_name'  => 'orderby',
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Orderby', 'noo-landmark' ),
				'std'         => 'date',
				'admin_label' => true,
				'value'       => array(
					esc_html__( 'Featured', 'noo-landmark' ) => 'featured',
					esc_html__( 'Date', 'noo-landmark' )     => 'date',
					esc_html__( 'Price', 'noo-landmark' )    => 'price',
					esc_html__( 'Name', 'noo-landmark' )     => 'name',
					esc_html__( 'Area', 'noo-landmark' )     => 'area',
					esc_html__( 'Random', 'noo-landmark' )   => 'rand',
				),
			),
			array(
				'param_name'  => 'order',
				'type'        => 'dropdown',
				'admin_label' => true,
				'heading'     => esc_html__( 'Order', 'noo-landmark' ),
				'std'         => 'DESC',
				'value'       => array(
					esc_html__( 'Recent First', 'noo-landmark' ) => 'DESC',
					esc_html__( 'Older First', 'noo-landmark' )  => 'ASC',
				),
			),
			array(
				'param_name' => 'row',
				'type'       => 'textfield',
				'heading'    => esc_html__( 'Number Row', 'noo-landmark' ),
				'std'        => '1',
			),
			array(
				'param_name' => 'column',
				'type'       => 'textfield',
				'heading'    => esc_html__( 'Number Column', 'noo-landmark' ),
				'std'        => '3',
			),
			array(
				'param_name' => 'posts_per_page',
				'type'       => 'textfield',
				'heading'    => esc_html__( 'Posts Per Page', 'noo-landmark' ),
				'std'        => '10',
			),
		),
	) );

endif;


/**
 * Create element: Advanced Search Property
 *
 * @package     LandMark
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */
if ( class_exists( 'Realty_Portal' ) ) :

	$params_advanced_search = array();
	/**
	 * Check if support 3rd party IDX plugin
	 */
	if ( class_exists( 'Noo_Landmark_IDX_Support' ) ) {
		$params_advanced_search[] = array(
			'param_name'  => 'source',
			'heading'     => esc_html__( 'Source', 'noo-landmark' ),
			'type'        => 'dropdown',
			'admin_label' => true,
			'std'         => 'property',
			'value'       => array(
				esc_html__( 'Property', 'noo-landmark' ) => 'property',
				esc_html__( 'IDX', 'noo-landmark' )      => 'idx',
			),
		);
	} else {
		$params_advanced_search[] = array(
			'param_name'  => 'source',
			'heading'     => esc_html__( 'Source', 'noo-landmark' ),
			'type'        => 'dropdown',
			'admin_label' => true,
			'std'         => 'property',
			'value'       => array(
				esc_html__( 'Property', 'noo-landmark' ) => 'property',
			),
		);
	}
	$params_advanced_search_default = array(

		array(
			'param_name'  => 'show_map',
			'heading'     => esc_html__( 'Show Map', 'noo-landmark' ),
			'type'        => 'dropdown',
			'admin_label' => true,
			'std'         => 'yes',
			'value'       => array(
				esc_html__( 'Yes', 'noo-landmark' ) => 'yes',
				esc_html__( 'No', 'noo-landmark' )  => 'no',
			),
		),
		array(
			'param_name'  => 'show_controls',
			'heading'     => esc_html__( 'Show Controls Map', 'noo-landmark' ),
			'type'        => 'dropdown',
			'admin_label' => true,
			'std'         => 'yes',
			'value'       => array(
				esc_html__( 'Yes', 'noo-landmark' ) => 'yes',
				esc_html__( 'No', 'noo-landmark' )  => 'no',
			),
			'dependency'  => array(
				'element' => 'show_map',
				'value'   => array( 'yes' ),
			),
		),
		array(
			'param_name'  => 'style',
			'heading'     => esc_html__( 'Search Layout', 'noo-landmark' ),
			'description' => esc_html__( 'Choose layout for Search form.', 'noo-landmark' ),
			'type'        => 'dropdown',
			'std'         => 'style-1',
			'value'       => array(
				esc_html__( 'Search Horizontal', 'noo-landmark' ) => 'style-1',
				esc_html__( 'Search Vertical', 'noo-landmark' )   => 'style-2',
				esc_html__( 'Search Top', 'noo-landmark' )        => 'style-3',
			),
			'dependency'  => array(
				'element' => 'show_map',
				'value'   => array( 'yes' ),
			),
		),
		array(
			'param_name' => 'latitude',
			'heading'    => esc_html__( 'Latitude', 'noo-landmark' ),
			'type'       => 'textfield',
			'std'        => Realty_Portal::get_setting( 'google_map', 'latitude', '40.714398' ),
			'dependency' => array(
				'element' => 'show_map',
				'value'   => array( 'yes' ),
			),
		),
		array(
			'param_name' => 'longitude',
			'heading'    => esc_html__( 'Longitude', 'noo-landmark' ),
			'type'       => 'textfield',
			'std'        => Realty_Portal::get_setting( 'google_map', 'longitude', '-74.005279' ),
			'dependency' => array(
				'element' => 'show_map',
				'value'   => array( 'yes' ),
			),
		),
		array(
			'param_name' => 'zoom',
			'heading'    => esc_html__( 'Zoom', 'noo-landmark' ),
			'type'       => 'textfield',
			'std'        => Realty_Portal::get_setting( 'google_map', 'zoom', '17' ),
			'dependency' => array(
				'element' => 'show_map',
				'value'   => array( 'yes' ),
			),
		),
		array(
			'param_name' => 'height',
			'heading'    => esc_html__( 'Height', 'noo-landmark' ),
			'type'       => 'textfield',
			'std'        => Realty_Portal::get_setting( 'google_map', 'height', '800' ),
			'dependency' => array(
				'element' => 'show_map',
				'value'   => array( 'yes' ),
			),
		),
		array(
			'param_name' => 'drag_map',
			'heading'    => esc_html__( 'Drag Map', 'noo-landmark' ),
			'type'       => 'dropdown',
			'std'        => 'true',
			'value'      => array(
				esc_html__( 'Yes', 'noo-landmark' ) => 'true',
				esc_html__( 'No', 'noo-landmark' )  => 'false',
			),
			'dependency' => array(
				'element' => 'show_map',
				'value'   => array( 'yes' ),
			),
		),
		array(
			'param_name' => 'fitbounds',
			'heading'    => esc_html__( 'Automatically Fit all Properties', 'noo-landmark' ),
			'type'       => 'dropdown',
			'std'        => 'true',
			'value'      => array(
				esc_html__( 'Yes', 'noo-landmark' ) => 'true',
				esc_html__( 'No', 'noo-landmark' )  => 'false',
			),
			'dependency' => array(
				'element' => 'show_map',
				'value'   => array( 'yes' ),
			),
		),
		array(
			'param_name'  => 'title',
			'heading'     => esc_html__( 'Title', 'noo-landmark' ),
			'type'        => 'textfield',
			'admin_label' => true,
			'std'         => esc_html__( 'Find Property', 'noo-landmark' ),
		),
		array(
			'param_name' => 'sub_title',
			'heading'    => esc_html__( 'Sub Title', 'noo-landmark' ),
			'type'       => 'textfield',
			'dependency' => array(
				'element' => 'show_map',
				'value'   => array( 'no' ),
			),
		),
		array(
			'param_name' => 'option_1',
			'heading'    => esc_html__( 'Option 1', 'noo-landmark' ),
			'type'       => 'custom_fields_property',
			'std'        => Realty_Portal::get_setting( 'advanced_search', 'option_1', 'keyword' ),
			'dependency' => array(
				'element' => 'source',
				'value'   => array( 'property' ),
			),
		),
		array(
			'param_name' => 'option_2',
			'heading'    => esc_html__( 'Option 2', 'noo-landmark' ),
			'type'       => 'custom_fields_property',
			'std'        => Realty_Portal::get_setting( 'advanced_search', 'option_2', 'property_status' ),
			'dependency' => array(
				'element' => 'source',
				'value'   => array( 'property' ),
			),
		),
		array(
			'param_name' => 'option_3',
			'heading'    => esc_html__( 'Option 3', 'noo-landmark' ),
			'type'       => 'custom_fields_property',
			'std'        => Realty_Portal::get_setting( 'advanced_search', 'option_3', 'property_type' ),
			'dependency' => array(
				'element' => 'source',
				'value'   => array( 'property' ),
			),
		),
		array(
			'param_name' => 'option_4',
			'heading'    => esc_html__( 'Option 4', 'noo-landmark' ),
			'type'       => 'custom_fields_property',
			'std'        => Realty_Portal::get_setting( 'advanced_search', 'option_4', 'property_country' ),
			'dependency' => array(
				'element' => 'source',
				'value'   => array( 'property' ),
			),
		),
		array(
			'param_name' => 'option_5',
			'heading'    => esc_html__( 'Option 5', 'noo-landmark' ),
			'type'       => 'custom_fields_property',
			'std'        => Realty_Portal::get_setting( 'advanced_search', 'option_5', '_bedrooms' ),
			'dependency' => array(
				'element' => 'source',
				'value'   => array( 'property' ),
			),
		),
		array(
			'param_name' => 'option_6',
			'heading'    => esc_html__( 'Option 6', 'noo-landmark' ),
			'type'       => 'custom_fields_property',
			'std'        => Realty_Portal::get_setting( 'advanced_search', 'option_6', '_bathrooms' ),
			'dependency' => array(
				'element' => 'source',
				'value'   => array( 'property' ),
			),
		),
		array(
			'param_name' => 'option_7',
			'heading'    => esc_html__( 'Option 7', 'noo-landmark' ),
			'type'       => 'custom_fields_property',
			'std'        => Realty_Portal::get_setting( 'advanced_search', 'option_7', '_garages' ),
			'dependency' => array(
				'element' => 'source',
				'value'   => array( 'property' ),
			),
		),
		array(
			'param_name' => 'option_8',
			'heading'    => esc_html__( 'Option 8', 'noo-landmark' ),
			'type'       => 'custom_fields_property',
			'std'        => Realty_Portal::get_setting( 'advanced_search', 'option_8', 'price' ),
			'dependency' => array(
				'element' => 'source',
				'value'   => array( 'property' ),
			),
		),
		array(
			'param_name' => 'show_features',
			'heading'    => esc_html__( 'Show Features & Amenities', 'noo-landmark' ),
			'type'       => 'checkbox',
			'std'        => 'true',
			'dependency' => array(
				'element' => 'source',
				'value'   => array( 'property' ),
			),
		),
		array(
			'param_name' => 'text_show_features',
			'heading'    => esc_html__( 'Text More', 'noo-landmark' ),
			'type'       => 'textfield',
			'std'        => esc_html__( 'More Filters', 'noo-landmark' ),
			'dependency' => array(
				'element' => 'show_features',
				'value'   => array( 'true' ),
			),
		),
		array(
			'param_name' => 'text_button_search',
			'heading'    => esc_html__( 'Text Button Search', 'noo-landmark' ),
			'type'       => 'textfield',
			'std'        => esc_html__( 'Search Property', 'noo-landmark' ),
		),
	);

	$params_advanced_search = array_merge( $params_advanced_search, $params_advanced_search_default );

	vc_map( array(
		'name'     => esc_html__( 'Advanced Search Property', 'noo-landmark' ),
		'base'     => 'noo_advanced_search_property',
		'icon'     => 'noo-advanced-search-property',
		'category' => $property_name,
		'params'   => $params_advanced_search,
	) );

endif;