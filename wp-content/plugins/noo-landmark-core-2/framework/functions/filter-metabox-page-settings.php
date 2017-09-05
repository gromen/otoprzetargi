<?php
/**
 * This function add new field to box Page Settings
 *
 * @package 	LandMark
 * @author 		KENT <tuanlv@vietbrain.com>
 * @version 	1.0
 */

if ( ! function_exists( 'noo_add_metabox_page_setting' ) ) :
	
	function noo_add_metabox_page_setting( $meta_box ) {

		$prefix = 'noo_';

		$helper = new NOO_Meta_Boxes_Helper( $prefix, array(
            'page' => 'page'
        ));

		/**
         * Create box: Page Layout
         */
            $meta_box = array(
				'id'       => "{$prefix}page_layout",
				'title'    => esc_html__( 'Page Layout', 'noo-landmark-core' ),
				'context'  => 'side',
				'priority' => 'low',
				'fields'   => array(
                    array(
                        'id'      => 'noo_property_submit_style',
                        'label'   => esc_html__( 'Property Submit Style', 'noo-landmark' ),
                        'type'    => 'radio',
                        'std'     => 'sidebar',
                        'options' => array(
                            array(
                                'value' => 'fullwidth',
                                'label' => esc_html__( 'Fullwidth', 'noo-landmark' ) 
                            ),
                            array(
                                'value' => 'sidebar',
                                'label' => esc_html__( 'Sidebar', 'noo-landmark' ) 
                            ),
                        )
                    ),
                    array(
                        'id'      => 'page_search_template',
                        'label'   => esc_html__( 'Page Search Template', 'noo-landmark' ),
                        'type'    => 'radio',
                        'std'     => '',
                        'options' => array(
                            array(
                                'value' => '',
                                'label' => esc_html__( 'Default', 'noo-landmark' ) 
                            ),
                            array(
                                'value' => 'half-map',
                                'label' => esc_html__( 'Half Map', 'noo-landmark' ) 
                            ),
                        )
                    ),
                    array(
                        'id'    => 'menu_header',
                        'label' => esc_html__( 'Menu Header', 'noo-landmark' ),
                        'type'  => 'menus',
                    ),
                    array(
                        'id'    => 'menu_footer',
                        'label' => esc_html__( 'Menu Footer', 'noo-landmark' ),
                        'type'  => 'menus',
                    )
                )
            );

            $helper->add_meta_box( $meta_box );

	}

	add_action( 'add_meta_boxes', 'noo_add_metabox_page_setting' );

endif;