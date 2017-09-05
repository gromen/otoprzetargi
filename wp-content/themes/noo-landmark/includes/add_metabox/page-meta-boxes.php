<?php
/**
 * NOO Meta Boxes Package
 *
 * Setup NOO Meta Boxes for Page
 * This file add Meta Boxes to WP Page edit page.
 *
 * @package    NOO Framework
 * @subpackage NOO Meta Boxes
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */
if (!function_exists('noo_landmark_func_page_meta_boxes')):
	function noo_landmark_func_page_meta_boxes() {
		// Declare helper object
		$prefix = '_noo_wp_page';
		$helper = new NOO_Meta_Boxes_Helper($prefix, array(
			'page' => 'page'
		));

		// Call Register sidebar
		global $wp_registered_sidebars;
        $sidebars_widgets = $wp_registered_sidebars;
        $arr_widget[] = array(
        	'label' => 'Select widget',
        	'value' => 'select_widget'
        	);
        foreach ($sidebars_widgets as $widget) {
            if( is_active_sidebar( $widget['id'] ) )
            	$arr_widget[] = array(
            		'label' => $widget['name'],
            		'value' => $widget['id']
        		);
        }

		// Page Settings
		$meta_box = array(
			'id'          => "{$prefix}_meta_box_page",
			'title'       => esc_html__( 'Page Settings', 'noo-landmark') ,
			'description' => esc_html__( 'Choose various setting for your Page.', 'noo-landmark') ,
			'fields'      => array(
				array(
					'label'        => esc_html__( 'Hide title', 'noo-landmark') ,
					'id'           => "{$prefix}_hide_title",
					'type'         => 'checkbox',
					'child-fields' => array(
						'off' => '_heading_image'
					)
				),
			)
		);

		if( get_theme_mod('noo_page_heading', true) ) {
			$meta_box['fields'][] = array(
				'id'    => '_heading_image',
				'label' => esc_html__( 'Heading Background Image', 'noo-landmark' ),
				'desc'  => esc_html__( 'An unique heading image for this page', 'noo-landmark'),
				'type'  => 'image',
			);
		}

		$array_add_child_woocommerce_link = array();
		$array_add_child_shopping_cart = array();
		if( defined('WOOCOMMERCE_VERSION') ) {
			$array_add_child_woocommerce_link = array(
				'label'        => esc_html__( 'Show Woocommerce Links', 'noo-landmark') ,
				'id'           => "{$prefix}_woocommerce",
				'type'         => 'checkbox',
				'std'          => 1
			);
			$array_add_child_shopping_cart = array(
				'label'        => esc_html__( 'Show Shopping Cart', 'noo-landmark') ,
				'id'           => "{$prefix}_cart",
				'type'         => 'checkbox',
				'std'          => 1
			);
    	}

    	// Choice Meta For Nav

    	$arr_button = array(
			array(
				'value' => 'nav_meta_customize',
				'label' => esc_html__('Using setting of customize', 'noo-landmark')
			),
			array(
				'value' => 'property',
				'label' => esc_html__('Show Button "Add Property"', 'noo-landmark')
			),
			array(
				'value' => 'phone',
				'label' => esc_html__('Show Phone Number', 'noo-landmark')
			),
			array(
				'value' => 'social',
				'label' => esc_html__('Show Social Network', 'noo-landmark')
			)
		);

		$child_meta = array(
			'phone'  => "{$prefix}_nav_meta_phone",
			'social' => "{$prefix}_nav_meta_social"
		);

		$link_buy = array();

		if( is_plugin_active( 'noo-landmark-landing/noo-landmark-landing.php' ) ){
			$arr_button[] = array(
				'value' => 'buy',
				'label' => esc_html__('Show button buy now', 'noo-landmark')
			);

			$link_buy = array(
				'label'        => esc_html__( 'Add link buy now', 'noo-landmark') ,
				'id'           => "{$prefix}_nav_meta_buy",
				'type'         => 'text'
			);

			$child_meta['buy'] = "{$prefix}_nav_meta_buy";

		}
		$array_add = array(
			array(
				'type' => 'divider',
			),
			array(
				'label'        => esc_html__( 'Hidden TopBar', 'noo-landmark') ,
				'id'           => "{$prefix}_topbar_hidden",
				'type'         => 'checkbox',
				'std'          => 0
			),
			array(
				'label'        => esc_html__( 'Show map on top header', 'noo-landmark') ,
				'id'           => "show_map_on_top",
				'type'         => 'checkbox',
				'std'          => 0
			),
			array(
	            'id'    => "{$prefix}_menu_style",
	            'label' => esc_html__( 'Header Style' , 'noo-landmark' ),
	            'desc'  => esc_html__( 'Header Style for this page.', 'noo-landmark' ),
	            'type'  => 'radio',
	            'std'   => 'menu_style_customize',
	            'options' => array(
	            	array(
						'value' => 'menu_style_customize',
						'label' => esc_html__( 'Using Menu Style in customize', 'noo-landmark' ) 
	                ),
	                array(
						'value' => 'default_menu_style',
						'label' => esc_html__( 'Header Default', 'noo-landmark' ) 
	                ),
	                array(
						'value' => 'default_basic',
						'label' => esc_html__( 'Header Basic Default', 'noo-landmark' ) 
	                ),
	                array( 
	                	'value' => 'style-1',
	                	'label' => esc_html__( 'Header Classic', 'noo-landmark' ) 
	                ),
	                array( 
	                	'value' => 'style-2',
	                	'label' => esc_html__( 'Header Full Classic', 'noo-landmark' ) 
	                ),
	                array( 
	                	'value' => 'style-3',
	                	'label' => esc_html__( 'Header Half Map', 'noo-landmark' ) 
	                ),
	                array( 
	                	'value' => 'style-4',
	                	'label' => esc_html__( 'Header Logo Center', 'noo-landmark' ) 
	                ),
	                array( 
	                	'value' => 'style-5',
	                	'label' => esc_html__( 'Header Fullwidth Logo Center', 'noo-landmark' ) 
	                ),
	                array( 
	                	'value' => 'style-6',
	                	'label' => esc_html__( 'Header Agency', 'noo-landmark' ) 
	                ),
	            ),
	            'child-fields' => array(
					'default_menu_style' => "{$prefix}_nav_meta,{$prefix}_nav_meta_phone,{$prefix}_nav_meta_social",
					'default_basic'      => "{$prefix}_nav_meta,{$prefix}_nav_meta_phone,{$prefix}_nav_meta_social,{$prefix}_woocommerce, {$prefix}_cart",
					'style-1'            => "{$prefix}_nav_meta,{$prefix}_nav_meta_phone,{$prefix}_nav_meta_social",
					'style-2'            => "{$prefix}_nav_meta,{$prefix}_nav_meta_phone,{$prefix}_nav_meta_social,{$prefix}_woocommerce, {$prefix}_cart",
					'style-4'            => "{$prefix}_nav_meta,{$prefix}_nav_meta_phone,{$prefix}_nav_meta_social",
					'style-5'            => "{$prefix}_nav_meta,{$prefix}_nav_meta_phone,{$prefix}_nav_meta_social,{$prefix}_woocommerce, {$prefix}_cart",
					'style-6'            => "{$prefix}_nav_meta,{$prefix}_nav_meta_phone,{$prefix}_nav_meta_social",
            	)
	        ),
			$array_add_child_woocommerce_link,
			$array_add_child_shopping_cart,
			array(
				'label'   => esc_html__('Choice Meta For Nav', 'noo-landmark'),
				'id'      => "{$prefix}_nav_meta",
				'type'    => 'radio',
				'std'     => 'nav_meta_customize',
				'options' => $arr_button,
				'child-fields' => $child_meta,
			),

			array(
				'label'        => esc_html__( 'Add Phone Number', 'noo-landmark') ,
				'id'           => "{$prefix}_nav_meta_phone",
				'type'         => 'text'
			),

			$link_buy,

			array(
				'label'   => esc_html__( 'Select widget', 'noo-landmark') ,
				'id'      => "{$prefix}_nav_meta_social",
				'type'    => 'select',
				'std'     => 'select_widget',
				'options' =>  $arr_widget
			),

			array(
				'type' => 'divider',
			),

			array(
				'id'    => "{$prefix}_menu_logo",
				'label' => esc_html__( 'Menu Logo' , 'noo-landmark' ),
				'desc'  => esc_html__( 'Menu Logo for this page.', 'noo-landmark' ),
				'type'  => 'image',
			),
	        array(
	            'id'    => "{$prefix}_nav_position",
	            'label' => esc_html__( 'Navbar Position' , 'noo-landmark' ),
	            'desc'  => esc_html__( 'Navbar Position for Page', 'noo-landmark' ),
	            'type'  => 'radio',
	            'std'   => 'default_position',
	            'options' => array(
	                array(
						'value' => 'default_position',
						'label' => esc_html__( 'Using Navbar Position in customizer', 'noo-landmark' ) 
	                ),
	                array(
	                	'value' => 'static_top',
	                	'label' => esc_html__( 'Static Top', 'noo-landmark' )
	                ),
	                array(
	                	'value' => 'fixed_top',
	                	'label' => esc_html__( 'Fixed Top', 'noo-landmark' )
	                ),
	                array(
	                	'value' => 'fixed_scroll_up',
	                	'label' => esc_html__( 'Fixed Top When Scroll Up', 'noo-landmark' )
	                )
	            ),
	        ),
	        array(
				'type' => 'divider'
			),

			array(
	            'id'    => "{$prefix}_footer_style",
	            'label' => esc_html__( 'Footer Style' , 'noo-landmark' ),
	            'desc'  => esc_html__( 'Footer Style for this page.', 'noo-landmark' ),
	            'type'  => 'radio',
	            'std'   => 'default_footer_style',
	            'options' => array(
	            	array(
						'value' => 'default_footer_style',
						'label' => esc_html__( 'Using Footer Style in customizer', 'noo-landmark' )
	                ),
	                array( 
	                	'value' => 'footer_1',
	                	'label' => esc_html__( 'Footer Basic', 'noo-landmark' ) 
	                ),
	                array( 
	                	'value' => 'footer_2',
	                	'label' => esc_html__( 'Footer Business', 'noo-landmark' ) 
	                ),
	                array( 
	                	'value' => 'footer_3',
	                	'label' => esc_html__( 'Footer Agency', 'noo-landmark' ) 
	                )
	            ),
	            'child-fields' => array(
	            	'footer_1' => "{$prefix}_footer_layout, {$prefix}_use_footer_background, {$prefix}_footer_fixed",
	            	'footer_2' => "{$prefix}_footer_layout, {$prefix}_use_footer_background, {$prefix}_footer_fixed",
	            	'footer_3' => "{$prefix}_use_footer_background, {$prefix}_footer_fixed"
            	)
	        ),

	        array(
				'label'        => esc_html__( 'Footer Position: Fixed', 'noo-landmark') ,
				'id'           => "{$prefix}_footer_fixed",
				'type'         => 'checkbox',
				'std'          => 0
			),

			array(
				'label'        => esc_html__( 'Use Image for background footer', 'noo-landmark') ,
				'id'           => "{$prefix}_use_footer_background",
				'type'         => 'checkbox',
				'std'          => 1,
				'child-fields' => array(
					'on' => "{$prefix}_footer_background, {$prefix}_footer_color"
				)
			),

			array(
				'id'    => "{$prefix}_footer_background",
				'label' => esc_html__( 'Footer background' , 'noo-landmark' ),
				'desc'  => esc_html__( 'Footer background for this page.', 'noo-landmark' ),
				'type'  => 'image',
			),

			array(
				'id'    => "{$prefix}_footer_color",
				'label' => esc_html__( 'Footer Background Color - Opacity', 'noo-landmark'),
				'desc'  => esc_html__( 'Set background color for footer', 'noo-landmark'),
				'type'  => 'alpha_color',
				'std'   => 'rgba(17,0,0,0.7)'
			),

			array(
				'label'   => esc_html__('Footer Content Box', 'noo-landmark'),
				'id'      => "{$prefix}_footer_layout",
				'type'    => 'radio',
				'std'     => '4',
				'options' => array(
                    '1' => array(
                        'image' => NOO_ADMIN_ASSETS_IMG . '/1col-full.png',
                        'value' => '1',
                    ),
                    '2' => array(
                        'image' => NOO_ADMIN_ASSETS_IMG . '/2col-full.png',
                        'value' => '2',
                    ),
                    '3' => array(
                        'image' => NOO_ADMIN_ASSETS_IMG . '/3col-full.png',
                        'value' => '3',
                    ),
                    '4' => array(
                        'image' => NOO_ADMIN_ASSETS_IMG . '/4col-full.png',
                        'value' => '4',
                    ),
                ),
                'child-fields' => array(
                	'1' => "{$prefix}_column_1",
                	'2' => "{$prefix}_column_1, {$prefix}_column_2",
                	'3' => "{$prefix}_column_1, {$prefix}_column_2, {$prefix}_column_3",
                	'4' => "{$prefix}_column_1, {$prefix}_column_2, {$prefix}_column_3, {$prefix}_column_4"
            	)

            ),

	        array(
	        	'id' => "{$prefix}_column_1",
	        	'label' => esc_html__('Footer Column 1 Width', 'noo-landmark'),
	        	'type' => 'ui_slider',
	        	'default' => 1,
	        	'options' => array(
	        		'data_min' => 1,
	        		'data_max' => 12,
	        		'data_step' => 1
        		)
        	),

        	array(
				'id'      => "{$prefix}_column_2",
				'label'   => esc_html__('Footer Column 2 Width', 'noo-landmark'),
				'type'    => 'ui_slider',
				'default' => 2,
				'options' => array(
	        		'data_min' => 1,
	        		'data_max' => 12,
	        		'data_step' => 1
        		)
        	),

        	array(
				'id'      => "{$prefix}_column_3",
				'label'   => esc_html__('Footer Column 3 Width', 'noo-landmark'),
				'type'    => 'ui_slider',
				'default' => 3,
				'options' => array(
					'data_min'  => 1,
					'data_max'  => 12,
					'data_step' => 1
        		)
        	),

        	array(
				'id'      => "{$prefix}_column_4",
				'label'   => esc_html__('Footer Column 4 Width', 'noo-landmark'),
				'type'    => 'ui_slider',
				'default' => 4,
				'options' => array(
					'data_min'  => 1,
					'data_max'  => 12,
					'data_step' => 1
        		)
        	),
		);

		$meta_box['fields'] = array_merge($meta_box['fields'], $array_add);

		$helper->add_meta_box( apply_filters( 'noo_metaboxes_pages_setting', $meta_box ) );

		// Page Sidebar
		$meta_box = array(
			'id'       => "{$prefix}_meta_box_sidebar",
			'title'    => esc_html__( 'Sidebar', 'noo-landmark'),
			'context'  => 'side',
			'priority' => 'default',
			'fields'   => array(
				array(
					'label' => esc_html__( 'Page Sidebar', 'noo-landmark') ,
					'id'    => "{$prefix}_sidebar",
					'type'  => 'sidebars',
					'std'   => 'sidebar-main'
				) ,
			)
		);

		$helper->add_meta_box( $meta_box );
	}
endif;

add_action('add_meta_boxes', 'noo_landmark_func_page_meta_boxes');
