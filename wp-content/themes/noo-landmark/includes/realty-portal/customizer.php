<?php
/**
 * This function register customizer
 *
 * @package 	LandMark
 * @author 		KENT <tuanlv@vietbrain.com>
 * @version 	1.0
 */
if ( ! function_exists( 'noo_register_property_customizer' ) ) :

	function noo_register_property_customizer( $wp_customize ) {

		/**
		 * declare helper object.
		 * @var NOO_Customizer_Helper
		 */
		$helper = new NOO_Customizer_Helper($wp_customize);

		/**
		 * Section: Property
		 */
		$helper->add_section(
			'noo_customizer_section_property',
			esc_html__( 'Property', 'noo-landmark' ),
			'',
			true
		);

		/**
		 * Sub-section: Propertys listing
		 */
		$helper->add_sub_section(
			'noo_sub_section_property_listing',
			esc_html__( 'Propertys listing', 'noo-landmark' ),
			''
		);

		/**
		 * Control: Type style
		 */
		$helper->add_control(
			'noo_property_listing_style',
			'noo_radio',
			esc_html__( 'Display style', 'noo-landmark' ),
			'style-list',
			array(
				'choices' => array(
					'style-list' => esc_html__( 'List', 'noo-landmark' ),
					'style-grid' => esc_html__( 'Grid', 'noo-landmark' ),
				)
			)
		);

		/**
		 * Control: Property Layout
		 */
		$helper->add_control(
			'noo_property_layout',
			'noo_radio_image',
			esc_html__( 'Property Layout', 'noo-landmark' ),
			'sidebar',
			array(
				'choices' => array(
					'fullwidth'    => NOO_ADMIN_ASSETS_IMG . '/1col.png',
					'sidebar'      => NOO_ADMIN_ASSETS_IMG . '/2cr.png',
					'left_sidebar' => NOO_ADMIN_ASSETS_IMG . '/2cl.png',
				),
				'json' => array(
					'child_options' => array(
						'fullwidth'    => '',
						'sidebar'      => 'noo_property_sidebar,noo_property_sidebar_extension',
						'left_sidebar' => 'noo_property_sidebar,noo_property_sidebar_extension'
					)
				)
			)
		);

		/**
		 * Control: Property Sidebar
		 */
		$helper->add_control(
			'noo_property_sidebar',
			'widgets_select',
			esc_html__( 'Property Sidebar', 'noo-landmark' ),
			'noo-property-sidebar'
		);

		/**
		 * Control: Property Sidebar Extension
		 */
		$helper->add_control(
			'noo_property_sidebar_extension',
			'widgets_select',
			esc_html__( 'Property Sidebar Extension', 'noo-landmark' ),
			'noo-property-sidebar-extension'
		);

		/**
		 * Control: Property Sidebar
		 */
		$helper->add_control(
			'noo_property_listing_orderby',
			'select',
			esc_html__( 'Sort Properties By', 'noo-landmark' ),
			'date',
			array(
				'choices' => array(
					'featured' => esc_html__( 'Featured', 'noo-landmark' ),
					'date'     => esc_html__( 'Date', 'noo-landmark' ),
					'price'    => esc_html__( 'Price', 'noo-landmark' ),
					'name'     => esc_html__( 'Name', 'noo-landmark' ),
					'area'     => esc_html__( 'Area', 'noo-landmark' ),
					'rand'     => esc_html__( 'Random', 'noo-landmark' ),
				)
			)
		);

		/**
		 * Control: Number property per page
		 */
		$helper->add_control(
			'noo_property_per_page',
			'ui_slider',
			esc_html__( 'Number property per page', 'noo-landmark' ),
			'10',
			array(
				'json' => array(
					'data_min' => 1,
					'data_max' => 50,
				)
			)
		);

		/**
		 * Control: Divider
		 */
		$helper->add_control( 'noo_property_divider_1', 'divider', '' );

		/**
		 * Control: Heading Title
		 */
		$helper->add_control(
			'noo_property_heading_title',
			'text',
			esc_html__( 'Heading Title', 'noo-landmark' ),
			esc_html__( 'Properties', 'noo-landmark' )
		);

		/**
		 * Control: Heading Image
		 */
		$helper->add_control(
			'noo_property_heading_image',
			'noo_image',
			esc_html__( 'Heading Background Image', 'noo-landmark' ),
			''
		);

		/**
		 * Control: Status Heading Title
		 */
		$helper->add_control(
			'noo_property_status_heading_title',
			'text',
			esc_html__( 'Status Heading Title', 'noo-landmark' ),
			esc_html__( 'Property Status', 'noo-landmark' )
		);

		/**
		 * Control: Status Heading Image
		 */
		$helper->add_control(
			'noo_property_status_heading_image',
			'noo_image',
			esc_html__( 'Status Heading Background Image', 'noo-landmark' ),
			''
		);

		/**
		 * Control: Type Heading Title
		 */
		$helper->add_control(
			'noo_property_type_heading_title',
			'text',
			esc_html__( 'Type Heading Title', 'noo-landmark' ),
			esc_html__( 'Property Type', 'noo-landmark' )
		);

		/**
		 * Control: Type Heading Image
		 */
		$helper->add_control(
			'noo_property_type_heading_image',
			'noo_image',
			esc_html__( 'Type Heading Background Image', 'noo-landmark' ),
			''
		);

		/**
		 * Sub-section: Property Detail
		 */
		$helper->add_sub_section(
			'noo_sub_section_property_detail',
			esc_html__( 'Property Detail', 'noo-landmark' ),
			''
		);

		// Control: Property Layout
		$helper->add_control(
			'noo_property_post_layout_same',
			'noo_radio',
			esc_html__( 'Single Property Layout', 'noo-landmark' ),
			'same_as_property',
			array(
				'choices' => array(
					'same_as_property' => esc_html__( 'Same as Property Layout', 'noo-landmark' ),
					'other_layout'     =>  esc_html__( 'Select Layout', 'noo-landmark' ),
				),
				'json' => array(
					'child_options' => array(
						'same_as_property' => '',
						'other_layout'     => 'noo_property_post_layout',
					)
				)
			)
		);

		$helper->add_control(
			'noo_property_post_layout',
			'noo_radio_image',
			esc_html__( 'Property Layout', 'noo-landmark' ),
			'fullwidth',
			array(
				'choices' => array(
					'fullwidth'    => NOO_ADMIN_ASSETS_IMG . '/1col.png',
					'sidebar'      => NOO_ADMIN_ASSETS_IMG . '/2cr.png',
					'left_sidebar' => NOO_ADMIN_ASSETS_IMG . '/2cl.png',
				),
				'json' => array(
					'child_options' => array(
						'fullwidth'    => '',
						'sidebar'      => 'noo_property_post_sidebar',
						'left_sidebar' => 'noo_property_post_sidebar',
					)
				)
			)
		);

		/**
		 * Control: Property Sidebar
		 */
		$helper->add_control(
			'noo_property_post_sidebar',
			'widgets_select',
			esc_html__( 'Property Sidebar', 'noo-landmark' ),
			'noo-property-sidebar'
		);

		/**
		 * Control: Divider
		 */
		$helper->add_control( 'noo_property_divider_2', 'divider', '' );

		/**
		 * Control: Property Header
		 */
		$helper->add_control(
			'noo_property_post_header_layout',
			'noo_radio',
			esc_html__( 'Property Top Content Layout', 'noo-landmark' ),
			'fullwidth',
			array(
				'choices' => array(
					'fullwidth' => esc_html__( 'Fullwidth', 'noo-landmark' ),
					'boxed'     => esc_html__( 'Boxed', 'noo-landmark' )
				),
				'json' => array(
					'child_options' => array(
						'fullwidth' => '',
						'boxed'     => 'noo_property_post_enable_heading, noo_property_post_heading_image'
					)
				)
			)
		);

		/**
		 * Control: Property Heading
		 */
		$helper->add_control(
			'noo_property_post_enable_heading',
			'noo_switch',
			esc_html__( 'Enable Page Heading', 'noo-landmark' ),
			0,
			array(
				'json' => array(
					'on_child_options' => 'noo_property_post_heading_image'
				)
			)
		);

		/**
		 * Control: Heading Image
		 */
		$helper->add_control(
			'noo_property_post_heading_image',
			'noo_image',
			esc_html__( 'Heading Background Image', 'noo-landmark' ),
			''
		);

		/**
		 * Control: Property Content Style
		 */
		$helper->add_control(
			'noo_property_post_content_style',
			'noo_radio',
			esc_html__( 'Property Content Style', 'noo-landmark' ),
			'default',
			array(
				'choices' => array(
					'default' => esc_html__( 'Default', 'noo-landmark' ),
					'list'    => esc_html__( 'List Style', 'noo-landmark' ),
					'tab'     => esc_html__( 'Tabs Style', 'noo-landmark' )
				),
				'json' => array(
					'child_options' => array(
						'default' => '',
						'list'    => 'noo_property_post_header_show',
						'tab'     => 'noo_property_post_header_show',
					)
				)
			)
		);

		/**
		 * Control: Property Header
		 */
		$helper->add_control(
			'noo_property_post_header_show',
			'select',
			esc_html__( 'Property Top Default View', 'noo-landmark' ),
			'gallery',
			array(
				'choices' => array(
					'gallery' => esc_html__( 'Gallery', 'noo-landmark' ),
					'map'     => esc_html__( 'Map', 'noo-landmark' )
				)
			)
		);

		/**
		 * Control: Divider
		 */
		$helper->add_control( 'noo_property_divider_3', 'divider', '' );

		/**
		 * Control: Enable Related Property
		 */
		$helper->add_control(
			'noo_property_enable_related_property',
			'noo_switch',
			esc_html__( 'Enable Related Property', 'noo-landmark' ),
			1,
			array(
				'json' => array(
					'on_child_options' => 'noo_property_title_related_property,
                                               noo_property_sub_title_related_property'
				)
			)
		);

		/**
		 * Control: Title Related Property
		 */
		$helper->add_control(
			'noo_property_title_related_property',
			'text',
			esc_html__( 'Title Related Property', 'noo-landmark' ),
			esc_html__( 'Related Property', 'noo-landmark' )
		);

		/**
		 * Control: Sub Title Related Property
		 */
		$helper->add_control(
			'noo_property_sub_title_related_property',
			'text',
			esc_html__( 'Sub Title Related Property', 'noo-landmark' ),
			esc_html__( 'Lorem Ipsum is simply dummy text of the printing.', 'noo-landmark' )
		);

		/**
		 * Control: Divider
		 */
		$helper->add_control( 'noo_property_related_property_divider', 'divider', '' );

		/**
		 * Control: Enable Box Review
		 */
		$helper->add_control(
			'noo_property_enable_comment',
			'noo_switch',
			esc_html__( 'Enable Review Box', 'noo-landmark' ),
			1,
			array(
				'json' => array(
					'on_child_options' => 'noo_property_number_comment,
                                               noo_property_enable_allows_guests_review,
                                               noo_property_label_list_comment,
                                               noo_property_label_form_comment,
                                               noo_property_txt_loadmore_comment,
                                               noo_property_txt_button_comment,
                                               noo_property_number_comment_divider'
				)
			)
		);

		/**
		 * Control: Enable Allows Guests Review
		 */
		$helper->add_control(
			'noo_property_enable_allows_guests_review',
			'noo_switch',
			esc_html__( 'Enable Allows Guests Review', 'noo-landmark' ),
			1,
			array()
		);

		/**
		 * Control: Review List Label
		 */
		$helper->add_control(
			'noo_property_label_list_comment',
			'text',
			esc_html__( 'Review List Label', 'noo-landmark' ),
			esc_html__( 'Reviews', 'noo-landmark' )
		);

		/**
		 * Control: Review Form Label
		 */
		$helper->add_control(
			'noo_property_label_form_comment',
			'text',
			esc_html__( 'Review Form Label', 'noo-landmark' ),
			esc_html__( 'Your Review', 'noo-landmark' )
		);

		/**
		 * Control: Load More Text
		 */
		$helper->add_control(
			'noo_property_txt_loadmore_comment',
			'text',
			esc_html__( 'Load More Text', 'noo-landmark' ),
			esc_html__( 'Load More', 'noo-landmark' )
		);

		/**
		 * Control: Button Submit Text
		 */
		$helper->add_control(
			'noo_property_txt_button_comment',
			'text',
			esc_html__( 'Button Submit Text', 'noo-landmark' ),
			esc_html__( 'Submit', 'noo-landmark' )
		);

		/**
		 * Control: Number of comment per page
		 */
		$helper->add_control(
			'noo_property_number_comment',
			'ui_slider',
			esc_html__( 'Number of comment per page', 'noo-landmark' ),
			'3',
			array(
				'json' => array(
					'data_min' => 1,
					'data_max' => 20,
				)
			)
		);

		/**
		 * Control: Divider
		 */
		$helper->add_control( 'noo_property_number_comment_divider', 'divider', '' );

		/**
		 * Control: Enable Agent Info Box
		 */
		$helper->add_control(
			'noo_property_enable_agent_info',
			'noo_switch',
			esc_html__( 'Enable Agent Info Box', 'noo-landmark' ),
			1,
			array()
		);

		/**
		 * Control: Enable Address Box
		 */
		$helper->add_control(
			'noo_property_enable_address',
			'noo_switch',
			esc_html__( 'Enable Address Box', 'noo-landmark' ),
			1,
			array()
		);

		/**
		 * Control: Enable Additional Details Box
		 */
		$helper->add_control(
			'noo_property_enable_additional_details',
			'noo_switch',
			esc_html__( 'Enable Additional Details Box', 'noo-landmark' ),
			1,
			array()
		);

		/**
		 * Control: Enable Feature Box
		 */
		$helper->add_control(
			'noo_property_enable_feature',
			'noo_switch',
			esc_html__( 'Enable Feature Box', 'noo-landmark' ),
			1,
			array()
		);

		/**
		 * Control: Enable Video Box
		 */
		$helper->add_control(
			'noo_property_enable_video',
			'noo_switch',
			esc_html__( 'Enable Video Box', 'noo-landmark' ),
			1,
			array()
		);

		/**
		 * Control: Enable Video Document
		 */
		$helper->add_control(
			'noo_property_enable_document',
			'noo_switch',
			esc_html__( 'Enable Document Box', 'noo-landmark' ),
			1,
			array()
		);

		/**
		 * Control: Enable Floor Plan Box
		 */
		$helper->add_control(
			'noo_property_enable_floor_plan',
			'noo_switch',
			esc_html__( 'Enable Floor Plan Box', 'noo-landmark' ),
			1,
			array()
		);

		/**
		 * Control: Enable Location On Map Box
		 */
		$helper->add_control(
			'noo_property_enable_map',
			'noo_switch',
			esc_html__( 'Enable Location On Map Box', 'noo-landmark' ),
			1,
			array()
		);

		/**
		 * Control: Enable Compare
		 */
		$helper->add_control(
			'noo_property_compare',
			'noo_switch',
			esc_html__( 'Enable Compare', 'noo-landmark' ),
			1,
			array()
		);

		/**
		 * Control: Enable Favories
		 */
		$helper->add_control(
			'noo_property_favories',
			'noo_switch',
			esc_html__( 'Enable Favories', 'noo-landmark' ),
			1,
			array()
		);

		/**
		 * Control: Enable Print
		 */
		$helper->add_control(
			'noo_property_print',
			'noo_switch',
			esc_html__( 'Enable Print', 'noo-landmark' ),
			1,
			array()
		);

		/**
		 * Control: Enable Social Sharing
		 */
		$helper->add_control(
			'noo_property_social',
			'noo_switch',
			esc_html__( 'Enable Social Sharing', 'noo-landmark' ),
			1,
			array(
				'json' => array(
					'on_child_options' => 'noo_property_social_facebook,
    		                                   noo_property_social_twitter,
    		                                   noo_property_social_google,
    		                                   noo_property_social_pinterest,
    		                                   noo_property_social_linkedin'
				)
			)
		);

		/**
		 * Control: Facebook Share
		 */
		$helper->add_control(
			'noo_property_social_facebook',
			'checkbox',
			esc_html__( 'Facebook Share', 'noo-landmark' ),
			1
		);

		/**
		 * Control: Twitter Share
		 */
		$helper->add_control(
			'noo_property_social_twitter',
			'checkbox',
			esc_html__( 'Twitter Share', 'noo-landmark' ),
			1
		);

		/**
		 * Control: Google+ Share
		 */
		$helper->add_control(
			'noo_property_social_google',
			'checkbox',
			esc_html__( 'Google+ Share', 'noo-landmark' ),
			1
		);

		/**
		 * Control: Pinterest Share
		 */
		$helper->add_control(
			'noo_property_social_pinterest',
			'checkbox',
			esc_html__( 'Pinterest Share', 'noo-landmark' ),
			0
		);

		/**
		 * Control: LinkedIn Share
		 */
		$helper->add_control(
			'noo_property_social_linkedin',
			'checkbox',
			esc_html__( 'LinkedIn Share', 'noo-landmark' ),
			0
		);

		do_action( 'noo_register_property_customizer_after', $helper );

	}

	add_action( 'customize_register', 'noo_register_property_customizer' );

endif;

/**
 * This function register customizer
 *
 * @package 	LandMark
 * @author 		KENT <tuanlv@vietbrain.com>
 * @version 	1.0
 */
if ( ! function_exists( 'noo_register_agent_customizer' ) ) :

	function noo_register_agent_customizer( $wp_customize ) {

		/**
		 * declare helper object.
		 * @var NOO_Customizer_Helper
		 */
		$helper = new NOO_Customizer_Helper($wp_customize);

		/**
		 * Section: Agent
		 */
		$helper->add_section(
			'noo_customizer_section_agent',
			esc_html__( 'Agents', 'noo-landmark' ),
			'',
			true
		);

		/**
		 * Sub-section: Agents listing
		 */
		$helper->add_sub_section(
			'noo_sub_section_agent_listing',
			esc_html__( 'Agents listing', 'noo-landmark' ),
			''
		);

		/**
		 * Control: Type style
		 */
		$helper->add_control(
			'noo_agent_listing_style',
			'noo_radio',
			esc_html__( 'Display style', 'noo-landmark' ),
			'list',
			array(
				'choices' => array(
					'list' => esc_html__( 'List', 'noo-landmark' ),
					'grid' => esc_html__( 'Grid', 'noo-landmark' ),
				)
			)
		);

		/**
		 * Control: Agent Layout
		 */
		$helper->add_control(
			'noo_agent_layout',
			'noo_radio_image',
			esc_html__( 'Agent Layout', 'noo-landmark' ),
			'fullwidth',
			array(
				'choices' => array(
					'fullwidth'    => NOO_ADMIN_ASSETS_IMG . '/1col.png',
					'sidebar'      => NOO_ADMIN_ASSETS_IMG . '/2cr.png',
					'left_sidebar' => NOO_ADMIN_ASSETS_IMG . '/2cl.png',
				),
				'json' => array(
					'child_options' => array(
						'fullwidth'    => '',
						'sidebar'      => 'noo_agent_sidebar',
						'left_sidebar' => 'noo_agent_sidebar'
					)
				)
			)
		);

		/**
		 * Control: Agent Sidebar
		 */
		$helper->add_control(
			'noo_agent_sidebar',
			'widgets_select',
			esc_html__( 'Agent Sidebar', 'noo-landmark' ),
			'noo-agent-sidebar'
		);

		/**
		 * Control: Agent Sidebar Extension
		 */
		$helper->add_control(
			'noo_agent_sidebar_extension',
			'widgets_select',
			esc_html__( 'Agent Sidebar Extension', 'noo-landmark' ),
			'noo-agent-sidebar-extension'
		);

		/**
		 * Control: Number agent per page
		 */
		$helper->add_control(
			'noo_agent_per_page',
			'ui_slider',
			esc_html__( 'Number agent per page', 'noo-landmark' ),
			'10',
			array(
				'json' => array(
					'data_min' => 1,
					'data_max' => 50,
				)
			)
		);

		/**
		 * Control: Divider
		 */
		$helper->add_control( 'noo_agent_divider_1', 'divider', '');

		/**
		 * Control: Heading Title
		 */
		$helper->add_control(
			'noo_agent_heading_title',
			'text',
			esc_html__( 'Heading Title', 'noo-landmark' ),
			esc_html__( 'Our Agent', 'noo-landmark' )
		);

		/**
		 * Control: Heading Image
		 */
		$helper->add_control(
			'noo_agent_heading_image',
			'noo_image',
			esc_html__( 'Heading Background Image', 'noo-landmark' ),
			''
		);


		/**
		 * Sub-section: Agent Detail
		 */
		$helper->add_sub_section(
			'noo_sub_section_agent_detail',
			esc_html__( 'Agent Detail', 'noo-landmark' ),
			''
		);

		// Control: Agent Layout
		//    $helper->add_control(
		//        'noo_agent_post_layout_same',
		//        'noo_radio',
		//        esc_html__( 'Single Agent Layout', 'noo-landmark' ),
		//        'same_as_agent',
		//        array(
		//            'choices' => array(
		// 'same_as_agent' => esc_html__( 'Same as Agent Layout', 'noo-landmark' ),
		// 'other_layout'  =>  esc_html__( 'Select Layout', 'noo-landmark' ),
		//            ),
		//            'json' => array(
		//                'child_options' => array(
		// 	'same_as_agent' => '',
		// 	'other_layout'  => 'noo_agent_post_layout',
		//                )
		//            )
		//        )
		//    );

		//    $helper->add_control(
		//        'noo_agent_post_layout',
		//        'noo_radio_image',
		//        esc_html__( 'Agent Layout', 'noo-landmark' ),
		//        'sidebar',
		//        array(
		//            'choices' => array(
		//                'fullwidth'    => NOO_ADMIN_ASSETS_IMG . '/1col.png',
		//                'sidebar'      => NOO_ADMIN_ASSETS_IMG . '/2cr.png',
		//                'left_sidebar' => NOO_ADMIN_ASSETS_IMG . '/2cl.png',
		//            ),
		//            'json' => array(
		//                'child_options' => array(
		//                    'fullwidth'    => '',
		//                    'sidebar'      => 'noo_agent_post_sidebar',
		//                    'left_sidebar' => 'noo_agent_post_sidebar',
		//                )
		//            )
		//        )
		//    );

		/**
		 * Control: Agent Sidebar
		 */
		// $helper->add_control(
		//     'noo_agent_post_sidebar',
		//     'widgets_select',
		//     esc_html__( 'Agent Sidebar', 'noo-landmark' ),
		//     'noo-agent-sidebar'
		// );

		/**
		 * Control: Divider
		 */
		$helper->add_control('noo_agent_post_divider_1', 'divider', '');

		/**
		 * Control: Contact Info
		 */
		$helper->add_control(
			'noo_agent_contact_info',
			'noo_switch',
			esc_html__( 'Enable Contact Info', 'noo-landmark' ),
			1,
			array(
				'json' => array(
					'on_child_options'   => 'noo_agent_contact_info_image, noo_agent_contact_info_title',
				)
			)
		);

		/**
		 * Control: Contact Info Background Image
		 */
		$helper->add_control(
			'noo_agent_contact_info_image',
			'noo_image',
			esc_html__( 'Contact Info Background Image', 'noo-landmark' ),
			''
		);

		/**
		 * Control: Title Contact Info
		 */
		$helper->add_control(
			'noo_agent_contact_info_title',
			'text',
			esc_html__( 'Title Contact Info', 'noo-landmark' ),
			esc_html__( 'Contact Info', 'noo-landmark' )
		);

		/**
		 * Control: Related Properties
		 */
		$helper->add_control(
			'noo_agent_related_properties',
			'noo_switch',
			esc_html__( 'Enable Related Properties', 'noo-landmark' ),
			1,
			array(
				'json' => array(
					'on_child_options' => 'noo_agent_title_related_property,
                                               noo_agent_sub_title_related_property'
				)
			)
		);

		/**
		 * Control: Title Related Property
		 */
		$helper->add_control(
			'noo_agent_title_related_property',
			'text',
			esc_html__( 'Title Related Property', 'noo-landmark' ),
			esc_html__( 'Related Property', 'noo-landmark' )
		);

		/**
		 * Control: Sub Title Related Property
		 */
		$helper->add_control(
			'noo_agent_sub_title_related_property',
			'text',
			esc_html__( 'Sub Title Related Property', 'noo-landmark' ),
			esc_html__( 'Lorem Ipsum is simply dummy text of the printing.', 'noo-landmark' )
		);

	}

	add_action( 'customize_register', 'noo_register_agent_customizer' );

endif;