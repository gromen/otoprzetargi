<?php
/**
 * NOO Visual Composer Add-ons
 *
 * Customize Visual Composer to suite NOO Framework
 *
 * @package    NOO Framework
 * @subpackage NOO Visual Composer Add-ons
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */
//
// Variables.
//
$category_name = esc_html__( 'LandMark', 'noo-landmark-core' );
$property_name = esc_html__( 'Properties', 'noo-landmark-core' );
$agent_name    = esc_html__( 'Agent', 'noo-landmark-core' );

// custom [row]
vc_add_param( 'vc_row', array(
	"type"        => "checkbox",
	"admin_label" => true,
	"heading"     => "Using Container",
	"param_name"  => "container_width",
	"description" => esc_html__( 'If checked container will be set to width 1170px for content.', 'noo-landmark-core' ),
	'weight'      => 1,
	'value'       => array( esc_html__( 'Yes', 'noo-landmark-core' ) => 'yes' ),
) );

/**
 * Create element: Noo Blog Mansory
 *
 * @package     Noo Library
 * @author      Henry <hungnt@vietbrain.com>
 * @version     1.0
 */
vc_map( array(
	'name'        => esc_html__( 'Noo Blog Mansory', 'noo-landmark-core' ),
	'base'        => 'noo_blog_mansory',
	'description' => esc_html__( 'Display posts list', 'noo-landmark-core' ),
	'icon'        => 'noo_icon_blog',
	'category'    => $category_name,
	'params'      => array(
		array(
			'param_name'  => 'title',
			'heading'     => esc_html__( 'Title', 'noo-landmark-core' ),
			'description' => esc_html__( 'Enter text which will be used as element title. Leave blank if no title is needed.', 'noo-landmark-core' ),
			'type'        => 'textfield',
			'admin_label' => true,
		),
		array(
			'param_name'  => 'sub_title',
			'heading'     => esc_html__( 'Sub Title', 'noo-landmark-core' ),
			'description' => esc_html__( 'Enter text which will be used as sub title. Leave blank if no sub title is needed.', 'noo-landmark-core' ),
			'type'        => 'textfield',
		),
		array(
			'param_name'  => 'type_query',
			'heading'     => esc_html__( 'Data Source', 'noo-landmark-core' ),
			'description' => esc_html__( 'Select content type', 'noo-landmark-core' ),
			'type'        => 'dropdown',
			'admin_label' => true,
			'value'       => array(
				'Category' => 'cat',
				'Tags'     => 'tag',
				'Posts'    => 'post_id',
			),
		),

		array(
			'param_name'  => 'categories',
			'heading'     => esc_html__( 'Categories', 'noo-landmark-core' ),
			'description' => esc_html__( 'Select categories.', 'noo-landmark-core' ),
			'type'        => 'post_categories',
			'dependency'  => array(
				'element' => 'type_query',
				'value'   => array( 'cat' ),
			),
		),

		array(
			'param_name'  => 'tags',
			'heading'     => esc_html__( 'Tags', 'noo-landmark-core' ),
			'description' => esc_html__( 'Select Tags.', 'noo-landmark-core' ),
			'type'        => 'post_tags',
			'dependency'  => array(
				'element' => 'type_query',
				'value'   => array( 'tag' ),
			),
		),
		array(
			'param_name'  => 'filter',
			'heading'     => esc_html__( 'Show Category filter', 'noo-landmark-core' ),
			'description' => '',
			'type'        => 'checkbox',
			'admin_label' => true,
			'value'       => false,
		),
		array(
			'type'        => 'autocomplete',
			'heading'     => esc_html__( 'Include only', 'noo-landmark-core' ),
			'param_name'  => 'include',
			'description' => esc_html__( 'Add posts, pages, etc. by title.', 'noo-landmark-core' ),
			'settings'    => array(
				'multiple' => true,
				'sortable' => true,
				'groups'   => true,
			),
			'dependency'  => array(
				'element' => 'type_query',
				'value'   => array( 'post_id' ),
			),
		),

		array(
			'param_name'  => 'layout_style',
			'heading'     => esc_html__( 'Layout Style', 'noo-landmark-core' ),
			'type'        => 'dropdown',
			'admin_label' => true,
			'value'       => array(
				esc_html__( 'Creative', 'noo-landmark-core' ) => 'creative',
				esc_html__( 'Classic', 'noo-landmark-core' )  => 'classic',
			),
		),

		array(
			'param_name' => 'columns',
			'heading'    => esc_html__( 'Columns', 'noo-landmark-core' ),
			'type'       => 'ui_slider',
			'value'      => '2',
			'data_min'   => '1',
			'data_max'   => '4',
			'dependency' => array(
				'element' => 'layout_style',
				'value'   => array(
					'creative',
					'classic',
				),
			),
		),

		array(
			'param_name'  => 'orderby',
			'heading'     => esc_html__( 'Order By', 'noo-landmark-core' ),
			'description' => '',
			'type'        => 'dropdown',
			'std'         => 'latest',
			'value'       => array(
				esc_html__( 'Recent First', 'noo-landmark-core' )            => 'latest',
				esc_html__( 'Older First', 'noo-landmark-core' )             => 'oldest',
				esc_html__( 'Title Alphabet', 'noo-landmark-core' )          => 'alphabet',
				esc_html__( 'Title Reversed Alphabet', 'noo-landmark-core' ) => 'ralphabet',
			),
		),

		array(
			'param_name'  => 'pagination',
			'heading'     => esc_html__( 'Choose Style Pagination', 'noo-landmark-core' ),
			'description' => '',
			'type'        => 'dropdown',
			'std'         => 'default',
			'admin_label' => true,
			'value'       => array(
				esc_html__( 'Disable pagination', 'noo-landmark-core' ) => 'disable',
				esc_html__( 'Default', 'noo-landmark-core' )            => 'default',
				esc_html__( 'Infinitescroll', 'noo-landmark-core' )     => 'infinitescroll',
			),
		),
		array(
			'type'        => 'attach_image',
			'dependency'  => array(
				'element' => 'pagination',
				'value'   => array( 'infinitescroll' ),
			),
			'heading'     => esc_html__( 'Load More Icon', 'noo-landmark-core' ),
			'description' => esc_html__( 'This option allows to select icon which is used to load blog item', 'noo-landmark-core' ),
			'param_name'  => 'loadmore',
		),

		array(
			'param_name'  => 'posts_per_page',
			'heading'     => esc_html__( 'Posts Per Page', 'noo-landmark-core' ),
			'description' => '',
			'type'        => 'textfield',
			'value'       => 10,
		),
	),
) );

/**
 * Create element: Noo Blog Slider
 *
 * @package     Noo Library
 * @author      Henry <hungnt@vietbrain.com>
 * @version     1.0
 */
vc_map( array(
	'name'        => esc_html__( 'Noo Blog Slider', 'noo-landmark-core' ),
	'base'        => 'noo_blog_slider',
	'description' => esc_html__( 'Display posts list slider', 'noo-landmark-core' ),
	'icon'        => 'noo_icon_blog',
	'category'    => $category_name,
	'params'      => array(
		array(
			'param_name'  => 'title',
			'heading'     => esc_html__( 'Title', 'noo-landmark-core' ),
			'description' => esc_html__( 'Enter text which will be used as element title. Leave blank if no title is needed.', 'noo-landmark-core' ),
			'type'        => 'textfield',
			'admin_label' => true,
		),
		array(
			'param_name'  => 'sub_title',
			'heading'     => esc_html__( 'Sub Title', 'noo-landmark-core' ),
			'description' => esc_html__( 'Enter text which will be used as sub title. Leave blank if no sub title is needed.', 'noo-landmark-core' ),
			'type'        => 'textfield',
		),
		array(
			'param_name'  => 'type_query',
			'heading'     => esc_html__( 'Data Source', 'noo-landmark-core' ),
			'description' => esc_html__( 'Select content type', 'noo-landmark-core' ),
			'type'        => 'dropdown',
			'admin_label' => true,
			'value'       => array(
				'Category' => 'cat',
				'Tags'     => 'tag',
				'Posts'    => 'post_id',
			),
		),

		array(
			'param_name'  => 'categories',
			'heading'     => esc_html__( 'Categories', 'noo-landmark-core' ),
			'description' => esc_html__( 'Select categories.', 'noo-landmark-core' ),
			'type'        => 'post_categories',
			'dependency'  => array(
				'element' => 'type_query',
				'value'   => array( 'cat' ),
			),
		),

		array(
			'param_name'  => 'tags',
			'heading'     => esc_html__( 'Tags', 'noo-landmark-core' ),
			'description' => esc_html__( 'Select Tags.', 'noo-landmark-core' ),
			'type'        => 'post_tags',
			'dependency'  => array(
				'element' => 'type_query',
				'value'   => array( 'tag' ),
			),
		),
		array(
			'type'        => 'autocomplete',
			'heading'     => esc_html__( 'Include only', 'noo-landmark-core' ),
			'param_name'  => 'include',
			'description' => esc_html__( 'Add posts, pages, etc. by title.', 'noo-landmark-core' ),
			'settings'    => array(
				'multiple' => true,
				'sortable' => true,
				'groups'   => true,
			),
			'dependency'  => array(
				'element' => 'type_query',
				'value'   => array( 'post_id' ),
			),
		),

		array(
			'param_name'  => 'autoplay',
			'heading'     => esc_html__( 'Auto Play Slider', 'noo-landmark-core' ),
			'type'        => 'dropdown',
			'admin_label' => true,
			'value'       => array(
				esc_html__( 'Yes', 'noo-landmark-core' ) => 'true',
				esc_html__( 'No', 'noo-landmark-core' )  => 'false',
			),
		),

		array(
			'param_name'  => 'layout_style',
			'heading'     => esc_html__( 'Layout Style', 'noo-landmark-core' ),
			'type'        => 'dropdown',
			'admin_label' => true,
			'value'       => array(
				esc_html__( 'Creative', 'noo-landmark-core' ) => 'creative',
				esc_html__( 'Classic', 'noo-landmark-core' )  => 'classic',
			),
		),

		array(
			'param_name' => 'columns',
			'heading'    => esc_html__( 'Columns', 'noo-landmark-core' ),
			'type'       => 'ui_slider',
			'value'      => '2',
			'data_min'   => '1',
			'data_max'   => '4',
			'dependency' => array(
				'element' => 'layout_style',
				'value'   => array(
					'creative',
					'classic',
				),
			),
		),

		array(
			'param_name'  => 'orderby',
			'heading'     => esc_html__( 'Order By', 'noo-landmark-core' ),
			'description' => '',
			'type'        => 'dropdown',
			'std'         => 'latest',
			'value'       => array(
				esc_html__( 'Recent First', 'noo-landmark-core' )            => 'latest',
				esc_html__( 'Older First', 'noo-landmark-core' )             => 'oldest',
				esc_html__( 'Title Alphabet', 'noo-landmark-core' )          => 'alphabet',
				esc_html__( 'Title Reversed Alphabet', 'noo-landmark-core' ) => 'ralphabet',
			),
		),

		array(
			'param_name'  => 'posts_per_page',
			'heading'     => esc_html__( 'Posts Per Page', 'noo-landmark-core' ),
			'description' => '',
			'type'        => 'textfield',
			'value'       => 10,
		),
	),
) );

/**
 * Create Element: [noo_testimonial]
 *
 * @package     Noo Library
 * @author      JAMES <luyentv@vietbrain.com>
 * @version     1.0
 */
vc_map( array(
	'name'        => esc_html__( 'Noo Testimonial', 'noo-landmark-core' ),
	'base'        => 'noo_testimonial',
	'description' => esc_html__( 'Display Testimonial', 'noo-landmark-core' ),
	'icon'        => 'noo_icon_testimonial',
	'category'    => $category_name,
	'params'      => array(

		array(
			'param_name'  => 'style',
			'heading'     => esc_html__( 'Choose Style', 'noo-landmark-core' ),
			'description' => esc_html__( 'Choose style for Testimonial.', 'noo-landmark-core' ),
			'type'        => 'dropdown',
			'std'         => 'style-1',
			'admin_label' => true,
			'value'       => array(
				esc_html__( 'Style 1', 'noo-landmark-core' ) => 'style-1',
				esc_html__( 'Style 2', 'noo-landmark-core' ) => 'style-2',
			),
		),
		array(
			'param_name'  => 'navigation',
			'heading'     => esc_html__( 'Use Navigation', 'noo-landmark-core' ),
			'description' => '',
			'type'        => 'checkbox',
			'admin_label' => true,
			'dependency'  => array(
				'element' => 'style',
				'value'   => array(
					'style-1',
					'style-2',
				),
			),
		),
		array(
			'param_name'  => 'pagination',
			'heading'     => esc_html__( 'Use Pagination', 'noo-landmark-core' ),
			'description' => '',
			'type'        => 'checkbox',
			'admin_label' => true,
			'dependency'  => array(
				'element' => 'style',
				'value'   => array(
					'style-1',
					'style-2',
				),
			),
		),
		array(
			'param_name'  => 'header',
			'heading'     => esc_html__( 'Choose Header', 'noo-landmark-core' ),
			'description' => esc_html__( 'You can choose Icon or Image which show it on header of Testimonial.', 'noo-landmark-core' ),
			'type'        => 'dropdown',
			'std'         => 'show_icon',
			'value'       => array(
				esc_html__( 'Icon', 'noo-landmark-core' )  => 'show_icon',
				esc_html__( 'Image', 'noo-landmark-core' ) => 'show_image',
			),
		),
		array(
			'param_name'  => 'icon',
			'heading'     => esc_html__( 'Icon', 'noo-landmark-core' ),
			'description' => '',
			'type'        => 'iconpicker',
			'dependency'  => array(
				'element' => 'header',
				'value'   => array( 'show_icon' ),
			),
		),
		array(
			'param_name'  => 'image',
			'heading'     => esc_html__( 'Upload Image', 'noo-landmark-core' ),
			'description' => '',
			'type'        => 'attach_image',
			'dependency'  => array(
				'element' => 'header',
				'value'   => array( 'show_image' ),
			),
		),
		array(
			'param_name'  => 'title',
			'heading'     => esc_html__( 'Title', 'noo-landmark-core' ),
			'description' => esc_html__( 'Enter text which will be used as element title. Leave blank if no title is needed.', 'noo-landmark-core' ),
			'type'        => 'textfield',
			'admin_label' => true,
		),
		array(
			'param_name'  => 'sub_title',
			'heading'     => esc_html__( 'Sub Title', 'noo-landmark-core' ),
			'description' => esc_html__( 'Enter text which will be used as sub title. Leave blank if no sub title is needed.', 'noo-landmark-core' ),
			'type'        => 'textfield',
			'admin_label' => false,
		),
		array(
			'param_name'  => 'posts_per_page',
			'heading'     => esc_html__( 'Testimonial Per Page', 'noo-landmark-core' ),
			'description' => '',
			'type'        => 'textfield',
			'admin_label' => true,
			'std'         => 10,
		),
		array(
			'param_name'  => 'items',
			'heading'     => esc_html__( 'Items', 'noo-landmark-core' ),
			'description' => esc_html__( 'The number of items you want to see on the screen.', 'noo-landmark-core' ),
			'type'        => 'dropdown',
			'admin_label' => true,
			'std'         => '1',
			'value'       => array(
				esc_html__( '1', 'noo-landmark-core' ) => 1,
				esc_html__( '2', 'noo-landmark-core' ) => 2,
				esc_html__( '3', 'noo-landmark-core' ) => 3,
				esc_html__( '4', 'noo-landmark-core' ) => 4,
			),
			'dependency'  => array(
				'element' => 'style',
				'value'   => array( 'style-1' ),
			),
		),
		array(
			'param_name'  => 'autoplay',
			'heading'     => esc_html__( 'Auto Play', 'noo-landmark-core' ),
			'description' => '',
			'type'        => 'checkbox',
			'admin_label' => true,
			'std'         => 'false',
			'value'       => 'true',
		),
		array(
			'param_name'  => 'timeout',
			'heading'     => esc_html__( 'Slider Timeout', 'noo-landmark-core' ),
			'type'        => 'textfield',
			'description' => esc_html__( 'With Milliseconds Unit (1000 = 1 second), default 2000', 'noo-landmark-core' ),
			'admin_label' => true,
			'value'       => '',
			'dependency'  => array(
				'element' => 'autoplay',
				'value'   => array( 'true' ),
			),
		),
		array(
			'param_name'  => 'show_name',
			'heading'     => esc_html__( 'Show Name', 'noo-landmark-core' ),
			'description' => '',
			'type'        => 'checkbox',
			'admin_label' => true,
			'std'         => 'true',
			'value'       => 'true',
		),
		array(
			'param_name'  => 'show_position',
			'heading'     => esc_html__( 'Show Position', 'noo-landmark-core' ),
			'description' => '',
			'type'        => 'checkbox',
			'admin_label' => true,
			'std'         => 'true',
			'value'       => 'true',
		),
		array(
			'param_name'  => 'caption',
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Caption', 'noo-landmark-core' ),
			'description' => esc_html__( 'Display title with large size on background image of shortcode.', 'noo-landmark-core' ),
			'admin_label' => true,
		),
	),
) );

/**
 * Create Element: [noo_partner]
 *
 * @package     Noo Library
 * @version     1.0
 */
vc_map( array(
	'name'        => esc_html__( 'Noo Partner', 'noo-landmark-core' ),
	'base'        => 'noo_partner',
	'description' => esc_html__( 'Display Partner', 'noo-landmark-core' ),
	'icon'        => 'noo_icon_partner',
	'category'    => $category_name,
	'params'      => array(
		array(
			'param_name'  => 'title',
			'heading'     => esc_html__( 'Title', 'noo-landmark-core' ),
			'description' => esc_html__( 'Enter text which will be used as element title. Leave blank if no title is needed.', 'noo-landmark-core' ),
			'type'        => 'textfield',
			'admin_label' => true,
		),
		array(
			'param_name'  => 'sub_title',
			'heading'     => esc_html__( 'Sub Title', 'noo-landmark-core' ),
			'description' => esc_html__( 'Enter text which will be used as sub title. Leave blank if no sub title is needed.', 'noo-landmark-core' ),
			'type'        => 'textfield',
			'admin_label' => false,
		),
		array(
			'type'       => 'param_group',
			'heading'    => esc_html__( 'Partners', 'noo-landmark-core' ),
			'param_name' => 'partners',
			'params'     => array(
				array(
					'heading'     => esc_html__( 'Logo', 'noo-landmark-core' ),
					'description' => '',
					'type'        => 'attach_image',
					'param_name'  => 'logo',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Name', 'noo-landmark-core' ),
					'param_name'  => 'name',
					'admin_label' => true,
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Website', 'noo-landmark-core' ),
					'param_name'  => 'link',
					'admin_label' => true,
				),
			),
		),
		array(
			'param_name'  => 'items',
			'heading'     => esc_html__( 'Items', 'noo-landmark-core' ),
			'description' => esc_html__( 'The number items of row you want to see on the screen.', 'noo-landmark-core' ),
			'type'        => 'dropdown',
			'std'         => '6',
			'admin_label' => true,
			'value'       => array(
				esc_html__( '4', 'noo-landmark-core' ) => 4,
				esc_html__( '5', 'noo-landmark-core' ) => 5,
				esc_html__( '6', 'noo-landmark-core' ) => 6,
			),
		),
		array(
			'param_name'  => 'rows',
			'heading'     => esc_html__( 'Rows', 'noo-landmark-core' ),
			'description' => esc_html__( 'The number rows you want to see on the screen.', 'noo-landmark-core' ),
			'type'        => 'dropdown',
			'std'         => '1',
			'admin_label' => true,
			'value'       => array(
				esc_html__( '1', 'noo-landmark-core' ) => 1,
				esc_html__( '2', 'noo-landmark-core' ) => 2,
				esc_html__( '3', 'noo-landmark-core' ) => 3,
			),
		),
		array(
			'param_name'  => 'autoplay',
			'heading'     => esc_html__( 'Auto Play', 'noo-landmark-core' ),
			'description' => '',
			'type'        => 'checkbox',
			'std'         => 'false',
			'value'       => 'true',
			'admin_label' => true,
		),
		array(
			'param_name'  => 'timeout',
			'heading'     => esc_html__( 'Slider Timeout', 'noo-landmark-core' ),
			'type'        => 'textfield',
			'description' => esc_html__( 'With Milliseconds Unit (1000 = 1 second), default 1000', 'noo-landmark-core' ),
			'value'       => '2500',
			'admin_label' => true,
			'dependency'  => array(
				'element' => 'autoplay',
				'value'   => array( 'true' ),
			),
		),
	),
) );

/**
 * Create Element: [noo_gallery]
 *
 * @package     Noo Library
 * @version     1.0
 */
vc_map( array(
	'name'        => esc_html__( 'Noo Gallery', 'noo-landmark-core' ),
	'base'        => 'noo_gallery',
	'description' => esc_html__( 'Display Gallery', 'noo-landmark-core' ),
	'icon'        => 'noo_icon_gallery',
	'category'    => $category_name,
	'params'      => array(
		array(
			'param_name'  => 'title',
			'heading'     => esc_html__( 'Title', 'noo-landmark-core' ),
			'description' => esc_html__( 'Enter text which will be used as element title. Leave blank if no title is needed.', 'noo-landmark-core' ),
			'type'        => 'textfield',
			'admin_label' => true,
		),
		array(
			'param_name'  => 'sub_title',
			'heading'     => esc_html__( 'Sub Title', 'noo-landmark-core' ),
			'description' => esc_html__( 'Enter text which will be used as sub title. Leave blank if no sub title is needed.', 'noo-landmark-core' ),
			'type'        => 'textfield',
			'admin_label' => false,
		),
		array(
			'param_name'  => 'orderby',
			'heading'     => esc_html__( 'Orderby', 'noo-landmark-core' ),
			'description' => esc_html__( 'You can select value for "Orderby" to display images by Date, Title, Random.', 'noo-landmark-core' ),
			'type'        => 'dropdown',
			'std'         => 'latest',
			'value'       => array(
				esc_html__( 'Latest', 'noo-landmark-core' )    => 'latest',
				esc_html__( 'Oldest', 'noo-landmark-core' )    => 'oldest',
				esc_html__( 'Alphabet', 'noo-landmark-core' )  => 'alphabet',
				esc_html__( 'Ralphabet', 'noo-landmark-core' ) => 'ralphabet',
				esc_html__( 'Random', 'noo-landmark-core' )    => 'rand',
			),
		),
		array(
			'param_name'  => 'items',
			'heading'     => esc_html__( 'Limit Images For Gallery', 'noo-landmark-core' ),
			'type'        => 'textfield',
			'description' => esc_html__( 'Enter number which will be max images in Gallery.', 'noo-landmark-core' ),
		),
	),
) );

/**
 * Create element: Noo Pricing Table
 *
 * @package     LandMark
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */
vc_map( array(
	'name'     => esc_html__( 'Noo Pricing Table', 'noo-landmark-core' ),
	'base'     => 'noo_pricing_table',
	'icon'     => 'noo-pricing-table',
	'category' => $category_name,
	'params'   => array(
		array(
			'param_name'  => 'featured_item',
			'heading'     => esc_html__( 'Featured Item', 'noo-landmark-core' ),
			'type'        => 'textfield',
			'description' => esc_html__( 'Enter the no. of the Package that is featured.', 'noo-landmark-core' ),
			'admin_label' => true,
			'std'         => '3',
		),
		array(
			'param_name'  => 'button_txt',
			'heading'     => esc_html__( 'Button Text', 'noo-landmark-core' ),
			'type'        => 'textfield',
			'admin_label' => true,
			'std'         => esc_html__( 'Buy now', 'noo-landmark-core' ),
		),
	),
) );

/**
 * Create Element: [noo_mailchimp]
 *
 * @package     Noo Maichimp
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */
$value_mail_list = array( esc_html__( '--- Select ---', 'noo-landmark-core' ) => 'none' );
if ( class_exists( 'MC4WP_MailChimp' ) ) {

	$mailchimp = new MC4WP_MailChimp();
	$lists     = $mailchimp->fetch_lists();

	if ( ! empty( $lists ) ) {
		foreach ( (array) $lists as $key => $value ) {
			if ( !empty( $value->name ) ) {
				$value_mail_list[ $value->name ] = $key;
			}
		}
	}
}
vc_map( array(
	'name'        => esc_html__( 'Noo Mailchimp', 'noo-landmark-core' ),
	'base'        => 'noo_mailchimp',
	'description' => esc_html__( 'Displays your MailChimp for WordPress sign-up form', 'noo-landmark-core' ),
	'icon'        => 'noo_icon_mailchimp',
	'category'    => $category_name,
	'params'      => array(
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Title', 'noo-landmark-core' ),
			'param_name'  => 'title',
			'admin_label' => true,
			'value'       => '',
		),
		array(
			'type'       => 'textfield',
			'heading'    => esc_html__( 'Sub title', 'noo-landmark-core' ),
			'param_name' => 'sub_title',
			'value'      => '',
		),
		array(
			'type'       => 'attach_image',
			'heading'    => esc_html__( 'Image', 'noo-landmark-core' ),
			'param_name' => 'image',
			'value'      => '',
		),
		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Subscribers', 'noo-landmark-core' ),
			'description' => esc_html__( 'Select the list(s) to which people who submit this form should be subscribed.', 'noo-landmark-core' ),
			'param_name'  => 'subscribers',
			'std'         => 'none',
			'value'       => $value_mail_list,
		),

	),
) );

/**
 * Create Element: [noo_hotline]
 *
 * @package     Noo Hotline
 * @author      JAMES <luyentv@vietbrain.com>
 * @version     1.0
 */
vc_map( array(
	'name'        => esc_html__( 'Noo Hotline', 'noo-landmark-core' ),
	'base'        => 'noo_hotline',
	'description' => esc_html__( 'Displays your hotline', 'noo-landmark-core' ),
	'icon'        => 'noo_icon_hotline',
	'category'    => $category_name,
	'params'      => array(
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Title', 'noo-landmark-core' ),
			'param_name'  => 'title',
			'admin_label' => true,
			'value'       => '',
		),
		array(
			'type'       => 'textfield',
			'heading'    => esc_html__( 'Sub title', 'noo-landmark-core' ),
			'param_name' => 'sub_title',
			'value'      => '',
		),
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Phone number', 'noo-landmark-core' ),
			'param_name'  => 'phone',
			'admin_label' => true,
			'value'       => '',
		),
		array(
			'type'       => 'attach_image',
			'heading'    => esc_html__( 'Image', 'noo-landmark-core' ),
			'param_name' => 'image',
			'value'      => '',
		),
	),
) );

/**
 * Create Element: [noo_service]
 *
 * @package     Noo Service
 * @author      JAMES <luyentv@vietbrain.com>
 * @version     1.0
 */
vc_map( array(
	'name'        => esc_html__( 'Noo Service', 'noo-landmark-core' ),
	'base'        => 'noo_service',
	'description' => esc_html__( 'Displays your service', 'noo-landmark-core' ),
	'icon'        => 'noo_icon_service',
	'category'    => $category_name,
	'params'      => array(
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Title', 'noo-landmark-core' ),
			'param_name'  => 'title',
			'admin_label' => true,
			'value'       => '',
		),
		array(
			'type'       => 'textfield',
			'heading'    => esc_html__( 'Sub title', 'noo-landmark-core' ),
			'param_name' => 'sub_title',
			'value'      => '',
		),
		array(
			'param_name'  => 'style',
			'heading'     => esc_html__( 'Choose Style', 'noo-landmark-core' ),
			'description' => esc_html__( 'Choose style for Service.', 'noo-landmark-core' ),
			'type'        => 'dropdown',
			'std'         => 'style-1',
			'admin_label' => true,
			'value'       => array(
				esc_html__( 'Style 1', 'noo-landmark-core' ) => 'style-1',
				esc_html__( 'Style 2', 'noo-landmark-core' ) => 'style-2',
			),
		),
		array(
			'param_name'  => 'column',
			'heading'     => esc_html__( 'Columns', 'noo-landmark-core' ),
			'description' => esc_html__( 'Choose number of columns.', 'noo-landmark-core' ),
			'type'        => 'dropdown',
			'std'         => '2',
			'admin_label' => true,
			'value'       => array(
				esc_html__( '2', 'noo-landmark-core' ) => '2',
				esc_html__( '3', 'noo-landmark-core' ) => '3',
				esc_html__( '4', 'noo-landmark-core' ) => '4',
			),
		),
		array(
			'type'       => 'param_group',
			'heading'    => esc_html__( 'Content box', 'noo-landmark-core' ),
			'param_name' => 'box',
			'params'     => array(
				array(
					'heading'     => esc_html__( 'Icon', 'noo-landmark-core' ),
					'description' => '',
					'type'        => 'iconpicker',
					'param_name'  => 'box_icon',
					'settings'    => array(
						'emptyIcon' => false,
						'type'      => 'landmark',
					),
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Service title', 'noo-landmark-core' ),
					'param_name' => 'box_title',
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Service content', 'noo-landmark-core' ),
					'param_name' => 'box_content',
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Service URL', 'noo-landmark-core' ),
					'param_name' => 'box_url',
				),
			),
		),
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Button title', 'noo-landmark-core' ),
			'param_name'  => 'btname',
			'admin_label' => false,
			'value'       => '',
		),
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Link button', 'noo-landmark-core' ),
			'param_name'  => 'readmore',
			'admin_label' => false,
			'value'       => '',
		),
	),
) );

/**
 * Create element: Noo Featured
 *
 * @package     LandMark
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */
vc_map( array(
	'name'     => esc_html__( 'Single Property Featured', 'noo-landmark-core' ),
	'base'     => 'noo_featured',
	'icon'     => 'noo_icon_featured',
	'category' => $property_name,
	'params'   => array(
		array(
			'param_name' => 'id',
			'type'       => 'custom_property_featured',
			'heading'    => esc_html__( 'Select property featured.', 'noo-landmark-core' ),
		),
		array(
			'param_name'  => 'caption',
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Caption', 'noo-landmark-core' ),
			'description' => esc_html__( 'Display title with large size on background image of shortcode.', 'noo-landmark-core' ),
		),
	),
) );

/**
 * Create element: Single Property
 *
 * @package     LandMark
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */
vc_map( array(
	'name'     => esc_html__( 'Single Property', 'noo-landmark-core' ),
	'base'     => 'noo_about_property',
	'icon'     => 'noo_icon_about_property',
	'category' => $property_name,
	'params'   => array(
		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Title', 'noo-landmark-core' ),
			'param_name'  => 'title',
		),
		array(
			'type'        => 'textarea',
			'admin_label' => true,
			'heading'     => esc_html__( 'Description', 'noo-landmark-core' ),
			'param_name'  => 'description',
		),
		array(
			'type'        => 'param_group',
			'admin_label' => false,
			'heading'     => esc_html__( 'Content box', 'noo-landmark-core' ),
			'param_name'  => 'box',
			'params'      => array(
				array(
					'heading'     => esc_html__( 'Icon', 'noo-landmark-core' ),
					'description' => '',
					'type'        => 'iconpicker',
					'param_name'  => 'icon',
					'settings'    => array(
						'emptyIcon' => false,
						'type'      => 'landmark',
					),
					'dependency'  => array(
						'element' => 'icon_type',
						'value'   => 'landmark',
					),
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Title', 'noo-landmark-core' ),
					'param_name' => 'box_title',
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Content', 'noo-landmark-core' ),
					'param_name' => 'box_content',
				),
			),
		),
	),
) );

/**
 * Create element: Single Property Detail
 *
 * @package     LandMark
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */
vc_map( array(
	'name'     => esc_html__( 'Single Property Detail', 'noo-landmark-core' ),
	'base'     => 'noo_property_detail',
	'icon'     => 'noo_icon_property_detail',
	'category' => $property_name,
	'params'   => array(
		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Title', 'noo-landmark-core' ),
			'param_name'  => 'title',
		),
		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'ID Property', 'noo-landmark-core' ),
			'description' => esc_html__( 'Please enter ID Property do you want display...', 'noo-landmark-core' ),
			'param_name'  => 'property_id',
		),
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'URL Video', 'noo-landmark-core' ),
			'description' => esc_html__( 'Please enter url video... (support: Youtube, Vimeo)', 'noo-landmark-core' ),
			'param_name'  => 'url_video',
		),
		array(
			'param_name'  => 'image',
			'heading'     => esc_html__( 'Background Image', 'noo-landmark-core' ),
			'description' => '',
			'type'        => 'attach_image',
		),
	),
) );

/**
 * Create element: Single Floor Plan
 *
 * @package     LandMark
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */
vc_map( array(
	'name'     => esc_html__( 'Single Floor Plan', 'noo-landmark-core' ),
	'base'     => 'noo_floor_plan',
	'icon'     => 'noo_icon_floor_plan',
	'category' => $property_name,
	'params'   => array(
		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'Title', 'noo-landmark-core' ),
			'param_name'  => 'title',
		),
		array(
			'type'        => 'textarea',
			'admin_label' => true,
			'heading'     => esc_html__( 'Description', 'noo-landmark-core' ),
			'param_name'  => 'description',
		),
		array(
			'param_name'  => 'image',
			'heading'     => esc_html__( 'Image', 'noo-landmark-core' ),
			'description' => '',
			'type'        => 'attach_image',
		),
		array(
			'type'        => 'param_group',
			'admin_label' => false,
			'heading'     => esc_html__( 'Content box', 'noo-landmark-core' ),
			'param_name'  => 'box',
			'params'      => array(
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Title', 'noo-landmark-core' ),
					'param_name' => 'box_title',
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Content', 'noo-landmark-core' ),
					'param_name' => 'box_content',
				),
				array(
					'param_name'  => 'box_link',
					'heading'     => esc_html__( 'Link Download', 'sky-game-core' ),
					'description' => '',
					'type'        => 'vc_link',
				),
				array(
					'type'        => 'param_group',
					'admin_label' => false,
					'heading'     => esc_html__( 'Content features', 'noo-landmark-core' ),
					'param_name'  => 'box_features',
					'params'      => array(
						array(
							'type'       => 'textfield',
							'heading'    => esc_html__( 'Item', 'noo-landmark-core' ),
							'param_name' => 'box_features_item',
						),
					),
				),
			),
		),
	),
) );

/**
 * Create element: Single Agent
 *
 * @package     LandMark
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */
vc_map( array(
	'name'     => esc_html__( 'Single Agent', 'noo-landmark-core' ),
	'base'     => 'noo_single_agent',
	'icon'     => 'noo_icon_about_agent',
	'category' => $agent_name,
	'params'   => array(
		array(
			'type'        => 'agent_list',
			'admin_label' => true,
			'heading'     => esc_html__( 'Choose a agent', 'noo-landmark-core' ),
			'param_name'  => 'agent_id',
		),
	),
) );

/**
 * Create element: Single Agent Contact
 *
 * @package     LandMark
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */
vc_map( array(
	'name'     => esc_html__( 'Single Agent Contact', 'noo-landmark-core' ),
	'base'     => 'noo_single_agent_contact',
	'icon'     => 'noo_icon_single_agent_contact',
	'category' => $agent_name,
	'params'   => array(
		array(
			'type'        => 'agent_list',
			'admin_label' => true,
			'heading'     => esc_html__( 'Choose a agent', 'noo-landmark-core' ),
			'param_name'  => 'agent_id',
		),
	),
) );

/**
 * Create element: Noo Ads Banner
 *
 * @package     LandMark
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */
vc_map( array(
	'name'     => esc_html__( 'Noo Ads Banner', 'noo-landmark-core' ),
	'base'     => 'noo_ads_banner',
	'icon'     => 'noo_icon_ads_banner',
	'category' => $category_name,
	'params'   => array(
		array(
			'param_name'  => 'image',
			'heading'     => esc_html__( 'Image', 'noo-landmark-core' ),
			'type'        => 'attach_image',
			'admin_label' => true,
		),
		array(
			'param_name'  => 'content',
			'heading'     => esc_html__( 'Content', 'noo-landmark-core' ),
			'type'        => 'textfield',
			'admin_label' => true,
		),
		array(
			'param_name' => 'button',
			'heading'    => esc_html__( 'Button', 'noo-landmark-core' ),
			'type'       => 'vc_link',
		),
	),
) );

/**
 * Create element: Single Property Banner
 *
 * @package     LandMark
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */
vc_map( array(
	'name'     => esc_html__( 'Single Property Banner', 'noo-landmark-core' ),
	'base'     => 'noo_property_banner',
	'icon'     => 'noo_icon_property_banner',
	'category' => $property_name,
	'params'   => array(
		array(
			'param_name'  => 'image',
			'heading'     => esc_html__( 'Image', 'noo-landmark-core' ),
			'type'        => 'attach_image',
			'admin_label' => true,
		),
		array(
			'type'        => 'textfield',
			'admin_label' => true,
			'heading'     => esc_html__( 'ID Property', 'noo-landmark-core' ),
			'description' => esc_html__( 'Please enter ID Property do you want display...', 'noo-landmark-core' ),
			'param_name'  => 'property_id',
		),
		array(
			'heading'     => esc_html__( 'Choose style', 'noo-landmark-core' ),
			'type'        => 'dropdown',
			'param_name'  => 'layout_style',
			'admin_label' => true,
			'value'       => array(
				esc_html__( 'Style 1', 'noo-landmark-core' ) => 'style-1',
				esc_html__( 'Style 2', 'noo-landmark-core' ) => 'style-2',
			),
		),
	),
) );

/**
 * Create element: Noo Progress
 *
 * @package     LandMark
 * @author      JAMES <luyentv@vietbrain.com>
 * @version     1.0
 */
vc_map( array(
	'name'     => esc_html__( 'Noo Progress', 'noo-landmark-core' ),
	'base'     => 'noo_progress',
	'icon'     => 'noo_icon_progress_banner',
	'category' => $category_name,
	'params'   => array(
		array(
			'param_name' => 'image',
			'heading'    => esc_html__( 'Image', 'noo-landmark-core' ),
			'type'       => 'attach_image',
		),
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Title', 'noo-landmark-core' ),
			'param_name'  => 'title',
			'admin_label' => true,
		),
		array(
			'param_name'  => 'sub_title',
			'heading'     => esc_html__( 'Sub Title', 'noo-landmark-core' ),
			'description' => esc_html__( 'Enter text which will be used as sub title. Leave blank if no sub title is needed.', 'noo-landmark-core' ),
			'type'        => 'textfield',
		),
		array(
			'type'       => 'param_group',
			'heading'    => esc_html__( 'Progress Bar', 'noo-landmark-core' ),
			'param_name' => 'progress',
			'params'     => array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Label', 'noo-landmark-core' ),
					'description' => esc_html__( 'Enter text used as title of bar.', 'noo-landmark-core' ),
					'param_name'  => 'label',
					'admin_label' => true,
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Value', 'noo-landmark-core' ),
					'description' => esc_html__( 'Enter value of bar.', 'noo-landmark-core' ),
					'param_name'  => 'value',
					'admin_label' => true,
				),
			),
		),
	),
) );

/**
 * Create element: Noo About
 *
 * @package     LandMark
 * @author      JAMES <luyentv@vietbrain.com>
 * @version     1.0
 */
vc_map( array(
	'name'     => esc_html__( 'Noo About', 'noo-landmark-core' ),
	'base'     => 'noo_about',
	'icon'     => 'noo_icon_about_banner',
	'category' => $category_name,
	'params'   => array(
		array(
			'param_name' => 'image',
			'heading'    => esc_html__( 'Image', 'noo-landmark-core' ),
			'type'       => 'attach_image',
		),
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Title', 'noo-landmark-core' ),
			'param_name'  => 'title',
			'admin_label' => true,
		),
		array(
			'type'       => 'textarea',
			'param_name' => 'desc',
			'heading'    => esc_html__( 'Description', 'noo-landmark-core' ),
		),
	),
) );

/**
 * Create element: Noo Video
 *
 * @package     LandMark
 * @author      JAMES <luyentv@vietbrain.com>
 * @version     1.0
 */
vc_map( array(
	'name'     => esc_html__( 'Noo Video', 'noo-landmark-core' ),
	'base'     => 'noo_video',
	'icon'     => 'noo_icon_video_banner',
	'category' => $category_name,
	'params'   => array(
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Title', 'noo-landmark-core' ),
			'param_name'  => 'title',
			'admin_label' => true,
		),
		array(
			'param_name'  => 'sub_title',
			'heading'     => esc_html__( 'Sub Title', 'noo-landmark-core' ),
			'description' => esc_html__( 'Enter text which will be used as sub title. Leave blank if no sub title is needed.', 'noo-landmark-core' ),
			'type'        => 'textfield',
		),
		array(
			'type'       => 'attach_image',
			'heading'    => esc_html__( 'Background Image Video', 'noo-landmark-core' ),
			'param_name' => 'img_video',
		),
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'URL Video', 'noo-landmark-core' ),
			'description' => esc_html__( 'Please enter url video... (support: Youtube, Vimeo)', 'noo-landmark-core' ),
			'param_name'  => 'url_video',
			'admin_label' => true,
		),
	),
) );

/**
 * Create element: Noo FAQ
 *
 * @package     LandMark
 * @author      JAMES <luyentv@vietbrain.com>
 * @version     1.0
 */
vc_map( array(
	'name'     => esc_html__( 'Noo FAQ', 'noo-landmark-core' ),
	'base'     => 'noo_faq',
	'icon'     => 'noo_icon_faq_banner',
	'category' => $category_name,
	'params'   => array(
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Title', 'noo-landmark-core' ),
			'param_name'  => 'title',
			'admin_label' => true,
		),
		array(
			'param_name'  => 'sub_title',
			'heading'     => esc_html__( 'Sub Title', 'noo-landmark-core' ),
			'description' => esc_html__( 'Enter text which will be used as sub title. Leave blank if no sub title is needed.', 'noo-landmark-core' ),
			'type'        => 'textfield',
		),
		array(
			'type'       => 'param_group',
			'value'      => '',
			'param_name' => 'noo_faq_group',
			'params'     => array(
				array(
					'param_name'  => 'open',
					'heading'     => esc_html__( 'Open FAQ', 'noo' ),
					'description' => '',
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( 'Hide', 'noo' ) => 'hide_faq',
						esc_html__( 'Show', 'noo' ) => 'open_faq',
					),
					'admin_label' => true,
				),
				array(
					'type'        => 'textfield',
					'value'       => '',
					'heading'     => esc_html__( 'Title', 'noo' ),
					'param_name'  => 'title',
					'admin_label' => true,
				),
				array(
					'type'       => 'textarea',
					'heading'    => esc_html__( 'Description', 'noo' ),
					'param_name' => 'description',
				),
			),
		),
	),
) );

/**
 * Create element: Noo Map
 *
 * @package     LandMark
 * @author      H <hungnt@vietbrain.com>
 * @version     1.0
 */
vc_map( array(
	'base'        => 'noo_map',
	'name'        => esc_html__( 'Noo Map', 'noo-landmark-core' ),
	'icon'        => 'noo_icon_map',
	'description' => esc_html__( 'Map block', 'noo-landmark-core' ),
	'category'    => $category_name,
	'params'      => array(
		array(
			'param_name'  => 'latitude',
			'heading'     => esc_html__( 'Latitude', 'noo-landmark-core' ),
			'type'        => 'textfield',
			'admin_label' => true,
		),
		array(
			'param_name'  => 'longitude',
			'heading'     => esc_html__( 'Longitude', 'noo-landmark-core' ),
			'type'        => 'textfield',
			'admin_label' => true,
		),
		array(
			'param_name'  => 'icon',
			'type'        => 'iconpicker',
			'heading'     => esc_html__( 'Icon', 'noo-landmark-core' ),
			'admin_label' => true,
		),
		array(
			'param_name' => 'height',
			'heading'    => esc_html__( 'Height Map', 'noo-landmark-core' ),
			'type'       => 'textfield',
			'value'      => '600',
		),
		array(
			'type'       => 'textfield',
			'heading'    => esc_html__( 'Title', 'noo-landmark-core' ),
			'param_name' => 'title',
		),
		array(
			'type'       => 'textarea',
			'heading'    => esc_html__( 'Description', 'noo-landmark-core' ),
			'param_name' => 'description',
		),
	),
) );

/**
 * Create element: Noo Information
 *
 * @package     LandMark
 * @author      H <hungnt@vietbrain.com>
 * @version     1.0
 */
$noo_cf7 = get_posts( 'post_type="wpcf7_contact_form"&posts_per_page=-1' );

$noo_contact_forms                                                     = array();
$noo_contact_forms[ __( 'Choose contact form', 'noo-landmark-core' ) ] = '';
if ( $noo_cf7 ) {
	foreach ( $noo_cf7 as $cform ) {
		$noo_contact_forms[ $cform->post_title ] = $cform->ID;
	}
} else {
	$noo_contact_forms[ esc_html__( 'No contact forms found', 'noo-landmark-core' ) ] = 0;
}

vc_map( array(
	'base'        => 'noo_information',
	'name'        => esc_html__( 'Noo Information', 'noo-landmark-core' ),
	'icon'        => 'noo_icon_info',
	'description' => esc_html__( 'Information Block and Contact Form', 'noo-landmark-core' ),
	'category'    => $category_name,
	'params'      => array(
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Title', 'noo-landmark-core' ),
			'param_name'  => 'title',
			'admin_label' => true,
		),
		array(
			'type'       => 'textarea',
			'heading'    => esc_html__( 'Description', 'noo-landmark-core' ),
			'param_name' => 'description',
		),
		array(
			'type'       => 'param_group',
			'value'      => '',
			'param_name' => 'mark_item',
			'params'     => array(
				array(
					'param_name'  => 'icon',
					'type'        => 'iconpicker',
					'heading'     => esc_html__( 'Icon', 'noo-landmark-core' ),
					'admin_label' => true,
				),
				array(
					'param_name'  => 'info',
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Information', 'noo-landmark-core' ),
					'value'       => '',
					'admin_label' => true,
				),
			),
		),
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Title Contact Form', 'noo-landmark-core' ),
			'param_name'  => 'title_contact',
			'admin_label' => true,
		),
		array(
			'param_name'  => 'custom_form',
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Select contact form', 'noo-landmark-core' ),
			'value'       => $noo_contact_forms,
			'description' => esc_html__( 'Choose previously created contact form from the drop down list.', 'noo-landmark-core' ),
		),
	),
) );

/**
 * Create Element: [noo_property_reviews]
 *
 * @package     Noo Library
 * @author      JAMES <luyentv@vietbrain.com>
 * @version     1.0
 */
vc_map( array(
	'name'        => esc_html__( 'Noo Property Reviews', 'noo-landmark-core' ),
	'base'        => 'noo_property_reviews',
	'description' => esc_html__( 'Display Property Reviews', 'noo-landmark-core' ),
	'icon'        => 'noo_icon_proper',
	'category'    => $property_name,
	'params'      => array(

		array(
			'param_name'  => 'style',
			'heading'     => esc_html__( 'Choose Style', 'noo-landmark-core' ),
			'description' => esc_html__( 'Choose style for Property Reviews.', 'noo-landmark-core' ),
			'type'        => 'dropdown',
			'std'         => 'style-1',
			'admin_label' => true,
			'value'       => array(
				esc_html__( 'Style 1', 'noo-landmark-core' ) => 'style-1',
				esc_html__( 'Style 2', 'noo-landmark-core' ) => 'style-2',
			),
		),
		array(
			'param_name'  => 'navigation',
			'heading'     => esc_html__( 'Use Navigation', 'noo-landmark-core' ),
			'description' => '',
			'type'        => 'checkbox',
			'admin_label' => true,
			'dependency'  => array(
				'element' => 'style',
				'value'   => array(
					'style-1',
					'style-2',
				),
			),
		),
		array(
			'param_name'  => 'pagination',
			'heading'     => esc_html__( 'Use Pagination', 'noo-landmark-core' ),
			'description' => '',
			'type'        => 'checkbox',
			'admin_label' => true,
			'dependency'  => array(
				'element' => 'style',
				'value'   => array(
					'style-1',
					'style-2',
				),
			),
		),
		array(
			'param_name'  => 'header',
			'heading'     => esc_html__( 'Choose Header', 'noo-landmark-core' ),
			'description' => esc_html__( 'You can choose Icon or Image which show it on header of Property Reviews.', 'noo-landmark-core' ),
			'type'        => 'dropdown',
			'std'         => 'show_icon',
			'value'       => array(
				esc_html__( 'Icon', 'noo-landmark-core' )  => 'show_icon',
				esc_html__( 'Image', 'noo-landmark-core' ) => 'show_image',
			),
		),
		array(
			'param_name'  => 'icon',
			'heading'     => esc_html__( 'Icon', 'noo-landmark-core' ),
			'description' => '',
			'type'        => 'iconpicker',
			'dependency'  => array(
				'element' => 'header',
				'value'   => array( 'show_icon' ),
			),
		),
		array(
			'param_name'  => 'image',
			'heading'     => esc_html__( 'Upload Image', 'noo-landmark-core' ),
			'description' => '',
			'type'        => 'attach_image',
			'dependency'  => array(
				'element' => 'header',
				'value'   => array( 'show_image' ),
			),
		),
		array(
			'param_name'  => 'title',
			'heading'     => esc_html__( 'Title', 'noo-landmark-core' ),
			'description' => esc_html__( 'Enter text which will be used as element title. Leave blank if no title is needed.', 'noo-landmark-core' ),
			'type'        => 'textfield',
			'admin_label' => true,
		),
		array(
			'param_name'  => 'sub_title',
			'heading'     => esc_html__( 'Sub Title', 'noo-landmark-core' ),
			'description' => esc_html__( 'Enter text which will be used as sub title. Leave blank if no sub title is needed.', 'noo-landmark-core' ),
			'type'        => 'textfield',
			'admin_label' => false,
		),
		array(
			'param_name'  => 'posts_per_page',
			'heading'     => esc_html__( 'Number Of Property Reviews', 'noo-landmark-core' ),
			'description' => '',
			'type'        => 'textfield',
			'admin_label' => true,
			'std'         => 10,
		),
		array(
			'param_name'  => 'items',
			'heading'     => esc_html__( 'Items', 'noo-landmark-core' ),
			'description' => esc_html__( 'The number of items you want to see on the screen.', 'noo-landmark-core' ),
			'type'        => 'dropdown',
			'admin_label' => true,
			'std'         => '1',
			'value'       => array(
				esc_html__( '1', 'noo-landmark-core' ) => 1,
				esc_html__( '2', 'noo-landmark-core' ) => 2,
				esc_html__( '3', 'noo-landmark-core' ) => 3,
				esc_html__( '4', 'noo-landmark-core' ) => 4,
			),
			'dependency'  => array(
				'element' => 'style',
				'value'   => array( 'style-1' ),
			),
		),
		array(
			'param_name'  => 'autoplay',
			'heading'     => esc_html__( 'Auto Play', 'noo-landmark-core' ),
			'description' => '',
			'type'        => 'checkbox',
			'admin_label' => true,
			'std'         => 'false',
			'value'       => 'true',
		),
		array(
			'param_name'  => 'timeout',
			'heading'     => esc_html__( 'Slider Timeout', 'noo-landmark-core' ),
			'type'        => 'textfield',
			'description' => esc_html__( 'With Milliseconds Unit (1000 = 1 second), default 2000', 'noo-landmark-core' ),
			'admin_label' => true,
			'value'       => '',
			'dependency'  => array(
				'element' => 'autoplay',
				'value'   => array( 'true' ),
			),
		),
		array(
			'param_name'  => 'show_name',
			'heading'     => esc_html__( 'Show Name', 'noo-landmark-core' ),
			'description' => '',
			'type'        => 'checkbox',
			'admin_label' => true,
			'std'         => 'true',
			'value'       => 'true',
		),
		array(
			'param_name'  => 'show_position',
			'heading'     => esc_html__( 'Show Rate', 'noo-landmark-core' ),
			'description' => '',
			'type'        => 'checkbox',
			'admin_label' => true,
			'std'         => 'true',
			'value'       => 'true',
		),
		array(
			'param_name'  => 'caption',
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Caption', 'noo-landmark-core' ),
			'description' => esc_html__( 'Display title with large size on background image of shortcode.', 'noo-landmark-core' ),
			'admin_label' => true,
		),
	),
) );