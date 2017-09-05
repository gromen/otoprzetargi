<?php
/**
 * NOO Customizer Package.
 *
 * Register Options
 * This file register options used in NOO-Customizer
 *
 * @package    NOO Framework
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */
// =============================================================================

// Action generate CSS in Customizer
noo_landmark_func_customizer_check_css();
function noo_landmark_func_customizer_check_css()
{
    if (function_exists('noo_landmark_func_customizer_css_generator')) {
        global $wp_customize;
        if (isset($wp_customize) || get_theme_mod('noo_use_inline_css', false)) {
            add_action('wp_head', 'noo_landmark_func_customizer_css_generator', 100, 0);
        }
    }
}

// 0. Remove Unused WP Customizer Sections
if (!function_exists('noo_landmark_func_customizer_remove_wp_native_sections')) :
    function noo_landmark_func_customizer_remove_wp_native_sections($wp_customize) {}
    add_action('customize_register', 'noo_landmark_func_customizer_remove_wp_native_sections');
endif;

//
// Register NOO Customizer Sections and Options
//

// 1. Site Enhancement options.
if ( ! function_exists( 'noo_landmark_func_customizer_register_options_general' ) ) :
    function noo_landmark_func_customizer_register_options_general( $wp_customize ) {

        // declare helper object.
        $helper = new NOO_Customizer_Helper( $wp_customize );

        // Section: Site Enhancement
        $helper->add_section(
            'noo_landmark_customizer_section_site_enhancement',
            esc_html__( 'Site Enhancement', 'noo-landmark' ),
            esc_html__( 'Enable/Disable some features for your site.', 'noo-landmark' )
        );

        // Control: Back to top
        $helper->add_control(
            'noo_back_to_top',
            'noo_switch',
            esc_html__( 'Back To Top Button', 'noo-landmark' ),
            1,
            array(
                'preview_type' => 'custom'
            )
        );

        // Control: Page heading
        $helper->add_control(
            'noo_page_heading',
            'noo_switch',
            esc_html__( 'Enable Page Heading', 'noo-landmark' ),
            1,
            array(
                'json' => array(
                    'on_child_options'   => 'noo_page_heading_parallax,noo_breadcrumbs,noo_page_heading_spacing',
                )
            )
        );

        // Control: Page heading
        $helper->add_control(
            'noo_page_heading_parallax',
            'noo_switch',
            esc_html__( 'Page Heading Parallax', 'noo-landmark' ),
            1
        );

        // Control: Breadcrumbs
        $helper->add_control(
            'noo_breadcrumbs',
            'noo_switch',
            esc_html__( 'Enable Breadcrumbs', 'noo-landmark' ),
            1
        );

        // Control: NavBar Link Spacing (px)
        $helper->add_control(
            'noo_page_heading_spacing',
            'ui_slider',
            esc_html__( 'Page Heading Spacing (px)', 'noo-landmark' ),
            '85',
            array(
                'json' => array(
                    'data_min' => 50,
                    'data_max' => 200,
                ),
                'preview_type' => 'custom'
            )
        );

    }
add_action( 'customize_register', 'noo_landmark_func_customizer_register_options_general' );
endif;

// 2. Design and Layout options.
if (!function_exists('noo_landmark_func_customizer_register_options_layout')) :
    function noo_landmark_func_customizer_register_options_layout($wp_customize)
    {

        // declare helper object.
        $helper = new NOO_Customizer_Helper($wp_customize);

        // Section: Layout
        $helper->add_section(
            'noo_landmark_func_customizer_section_layout',
            esc_html__('Design and Layout', 'noo-landmark'),
            esc_html__('Set Style and Layout for your site. Boxed Layout will come with additional setting options for background color and image.', 'noo-landmark')
        );

        noo_customizer_add_controls(
            $wp_customize,
            array(
                'noo_site_layout' => array(
                    'type'    => 'noo_radio',
                    'label'   => esc_html__('Site Layout', 'noo-landmark'),
                    'default' => 'fullwidth',
                    'control' => array(
                        'choices' => array(
                            'fullwidth' => esc_html__('Fullwidth', 'noo-landmark'),
                            'boxed' => esc_html__('Boxed', 'noo-landmark')
                        ),
                        'json'    => array(
                            'child_options' => array(
                                'boxed' => 'noo_layout_site_width
											,noo_layout_site_max_width
		                                    ,noo_layout_bg_image_sub_section
		                                    ,noo_layout_bg_image
		                                    ,noo_layout_bg_repeat
		                                    ,noo_layout_bg_align
		                                    ,noo_layout_bg_attachment
		                                    ,noo_layout_bg_cover
                                            ,noo_layout_bg_color'
                            )
                        ),
                        'preview_type' => 'custom'
                    )
                ),
                'noo_layout_site_width' => array(
                    'type'    => 'ui_slider',
                    'label'   => esc_html__('Site Width (%)', 'noo-landmark'),
                    'default' => '90',
                    'control' => array(
                        'json'         => array(
                            'data_min'     => 60,
                            'data_max'     => 100,
                        ),
                        'preview_type' => 'custom'
                    )
                ),
                'noo_layout_site_max_width' => array(
                    'type'    => 'ui_slider',
                    'label'   => esc_html__('Site Max Width (px)', 'noo-landmark'),
                    'default' => '1200',
                    'control' => array(
                        'json'         => array(
                            'data_min'     => 980,
                            'data_max'     => 1600,
                            'data_step'    => 10,
                        ),
                        'preview_type' => 'custom'
                    )
                ),
                'noo_layout_bg_color' => array(
                    'type'         => 'alpha_color',
                    'label'        => esc_html__('Background Color', 'noo-landmark'),
                    'default'      => '#ffffff',
                    'preview_type' => 'custom'
                )
            )
        );

        // Sub-section: Background Image
        $helper->add_sub_section(
            'noo_layout_bg_image_sub_section',
            esc_html__('Background Image', 'noo-landmark'),
            noo_landmark_func_kses(__('Upload your background image here, you have various settings for your image:<br/><strong>Repeat Image</strong>: enable repeating your image, you will need it when using patterned background.<br/><strong>Alignment</strong>: Set the position to align your background image.<br/><strong>Attachment</strong>: Make your image scroll with your site or fixed.<br/><strong>Auto resize</strong>: Enable it to ensure your background image always fit the windows.', 'noo-landmark'))
        );

        // Control: Background Image
        $helper->add_control(
            'noo_layout_bg_image',
            'noo_image',
            esc_html__('Background Image', 'noo-landmark'),
            '',
            array('preview_type' => 'custom')
        );

        // Control: Repeat Image
        $helper->add_control(
            'noo_layout_bg_repeat',
            'radio',
            esc_html__('Background Repeat', 'noo-landmark'),
            'no-repeat',
            array(
                'choices'      => array(
                    'repeat'       => esc_html__('Repeat', 'noo-landmark'),
                    'no-repeat'    => esc_html__('No Repeat', 'noo-landmark'),
                ),
                'preview_type' => 'custom'
            )
        );

        // Control: Align Image
        $helper->add_control(
            'noo_layout_bg_align',
            'select',
            esc_html__('BG Image Alignment', 'noo-landmark'),
            'left top',
            array(
                'choices' => array(
                    'left top'      => esc_html__('Left Top', 'noo-landmark'),
                    'left center'   => esc_html__('Left Center', 'noo-landmark'),
                    'left bottom'   => esc_html__('Left Bottom', 'noo-landmark'),
                    'center top'    => esc_html__('Center Top', 'noo-landmark'),
                    'center center' => esc_html__('Center Center', 'noo-landmark'),
                    'center bottom' => esc_html__('Center Bottom', 'noo-landmark'),
                    'right top'     => esc_html__('Right Top', 'noo-landmark'),
                    'right center'  => esc_html__('Right Center', 'noo-landmark'),
                    'right bottom'  => esc_html__('Right Bottom', 'noo-landmark'),
                ),
                'preview_type' => 'custom'
            )
        );

        // Control: Enable Scrolling Image
        $helper->add_control(
            'noo_layout_bg_attachment',
            'radio',
            esc_html__('BG Image Attachment', 'noo-landmark'),
            'fixed',
            array(
                'choices'      => array(
                    'fixed'        => esc_html__('Fixed Image', 'noo-landmark'),
                    'scroll'       => esc_html__('Scroll with Site', 'noo-landmark'),
                ),
                'preview_type' => 'custom'
            )
        );

        // Control: Auto Resize
        $helper->add_control(
            'noo_layout_bg_cover',
            'noo_switch',
            esc_html__('Auto Resize', 'noo-landmark'),
            0,
            array('preview_type' => 'custom')
        );

        // Sub-Section: Links Color
        $helper->add_sub_section(
            'noo_general_sub_section_links_color',
            esc_html__('Color', 'noo-landmark'),
            esc_html__('Here you can set the color for links and various elements on your site.', 'noo-landmark')
        );

        $helper->add_control(
            'noo_site_preset_color',
            'noo_radio_image',
            esc_html__('Preset Color', 'noo-landmark'),
            'preset_1',
            array(
                'choices' => noo_landmark_func_get_theme_preset(),
            )
        );

        $helper->add_control(
            'noo_site_use_custom_color',
            'noo_switch',
            esc_html__( 'Use Custom Color', 'noo-landmark' ),
            0,
            array(
                'json' => array(
                    'on_child_options'   => 'noo_site_primary_color,noo_site_secondary_color',
                )
            )
        );

        $helper->add_control(
            'noo_site_primary_color',
            'alpha_color',
            esc_html__('Primary Color', 'noo-landmark'),
            noo_landmark_func_get_theme_default('primary_color'),
            array(
                'preview_type'   => 'update_css',
                'preview_params' => array('css' => 'design'),
                'show_opacity'   => true,
                'palette'        => array(
                    '#114a82',
                    '#3f788a',
                    '#185a9b',
                    '#233354',
                    '#20272b'
                ),
            )
        );

        // Control: Site Secondary Links Hover Color
        $helper->add_control(
            'noo_site_secondary_color',
            'alpha_color',
            esc_html__( 'Secondary Color', 'noo-landmark' ),
            noo_landmark_func_get_theme_default( 'secondary_color' ),
            array(
                'preview_type'   => 'update_css',
                'preview_params' => array( 'css' => 'design' ),
                'show_opacity'   => true,
                'palette'        => array(
                    '#f9a11b',
                    '#63bbd6',
                    '#25ade9',
                    '#f94f5b',
                    '#5ab70e'
                ),
            )
        );
    }

    add_action('customize_register', 'noo_landmark_func_customizer_register_options_layout');
endif;

// 3. Typography options.
if (!function_exists('noo_landmark_func_customizer_register_options_typo')) :
    function noo_landmark_func_customizer_register_options_typo($wp_customize)
    {

       // declare helper object.
        $helper = new NOO_Customizer_Helper( $wp_customize );

        // Section: Typography
        $helper->add_section(
            'noo_customizer_section_typo',
            esc_html__( 'Typography', 'noo-landmark' ),
            noo_landmark_func_kses( __( 'Customize your Typography settings. Merito integrated all Google Fonts. See font preview at <a target="_blank" href="http://www.google.com/fonts/">Google Fonts</a>.', 'noo-landmark' ) )
        );

        // Sub-Section: Headings
        $helper->add_sub_section(
            'noo_typo_sub_section_headings',
            esc_html__( 'Headings', 'noo-landmark' )
        );

        // Control: Use Custom Fonts
        $helper->add_control(
            'noo_typo_use_custom_headings_font',
            'noo_switch',
            esc_html__( 'Use Custom Headings Font?', 'noo-landmark' ),
            0,
            array( 'json' => array( 
                'on_child_options'  => 'noo_typo_headings_font,
                                        noo_typo_headings_font_color,
                                        noo_typo_headings_uppercase'
                ),
                'preview_type' => 'update_css',
                'preview_params' => array( 'css' => 'typography' )
            )
        );

        // Control: Headings font
        $helper->add_control(
            'noo_typo_headings_font',
            'google_fonts',
            esc_html__( 'Headings Font', 'noo-landmark' ),
            noo_landmark_func_get_theme_default( 'headings_font_family' ),
            array(
                'preview_type' => 'custom'
            )
        );

        // Control: Headings Font Color
        $helper->add_control(
            'noo_typo_headings_font_color',
            'color_control',
            esc_html__( 'Font Color', 'noo-landmark' ),
            noo_landmark_func_get_theme_default( 'headings_color' ),
            array( 'preview_type' => 'custom' )
        );

        // Control: Headings Font Uppercase
        $helper->add_control(
            'noo_typo_headings_uppercase',
            'checkbox',
            esc_html__( 'Transform to Uppercase', 'noo-landmark' ),
            0,
            array( 'preview_type' => 'custom' )
        );

        // Sub-Section: Body
        $helper->add_sub_section(
            'noo_typo_sub_section_body',
            esc_html__( 'Body', 'noo-landmark' )
        );

        // Control: Use Custom Fonts
        $helper->add_control(
            'noo_typo_use_custom_body_font',
            'noo_switch',
            esc_html__( 'Use Custom Body Font?', 'noo-landmark' ),
            0,
            array( 'json' => array( 
                'on_child_options'  => 'noo_typo_body_font,
                                        noo_typo_body_font_color,
                                        noo_typo_body_font_size' 
                ),
                'preview_type' => 'update_css',
                'preview_params' => array( 'css' => 'typography' )
            )
        );
        
        // Control: Body font
        $helper->add_control(
            'noo_typo_body_font',
            'google_fonts',
            esc_html__( 'Body Font', 'noo-landmark' ),
            noo_landmark_func_get_theme_default( 'font_family' ),
            array( 'preview_type' => 'custom' )
        );

        // Control: Body Font Color
        $helper->add_control(
            'noo_typo_body_font_color',
            'color_control',
            esc_html__( 'Font Color', 'noo-landmark' ),
            noo_landmark_func_get_theme_default( 'text_color' ),
            array(
                'preview_type' => 'custom'
            )
        );

        // Control: Body Font Size
        $helper->add_control(
            'noo_typo_body_font_size',
            'font_size',
            esc_html__( 'Font Size (px)', 'noo-landmark' ),
            noo_landmark_func_get_theme_default( 'font_size' ),
            array( 'preview_type' => 'custom' )
        );
    }

    add_action('customize_register', 'noo_landmark_func_customizer_register_options_typo');
endif;


// 4. Header options.
if (!function_exists('noo_landmark_func_customizer_register_options_header')) :
    function noo_landmark_func_customizer_register_options_header($wp_customize)
    {

        global $wp_registered_sidebars;
        $sidebars_widgets = $wp_registered_sidebars;
        $arr_widget = array('select_widget'=>'Select widget');
        foreach ($sidebars_widgets as $widget) {
            if( is_active_sidebar( $widget['id'] ) )
                $arr_widget[$widget['id']] = $widget['name'];
        }

        // declare helper object.
        $helper = new NOO_Customizer_Helper($wp_customize);

        // Section: Header
        $helper->add_section(
            'noo_landmark_func_customizer_section_header',
            esc_html__('Header', 'noo-landmark'),
            esc_html__('Customize settings for your Header, including Navigation Bar (Logo and Navigation) and an optional Top Bar.', 'noo-landmark'),
            true
        );

        // Sub-section: General Options
        $helper->add_sub_section(
            'noo_header_sub_section_general',
            esc_html__('General Options', 'noo-landmark'),
            ''
        );

        // Sub-Section: Header Bar
        $helper->add_sub_section(
            'noo_header_sub_section_style',
            esc_html__('Header Style', 'noo-landmark'),
            esc_html__('Choose style for header', 'noo-landmark')
        );

        // Control: Header Style
        $helper->add_control(
            'noo_header_nav_style',
            'noo_radio',
            esc_html__('Header Style', 'noo-landmark'),
            'default_menu_style',
            array(
                'choices' => array(
                    'default_menu_style' => esc_html__('Header Default', 'noo-landmark'),
                    'default_basic'      => esc_html__('Header Basic Default', 'noo-landmark'),
                    'style-1'            => esc_html__('Header Classic Transparent', 'noo-landmark'),
                    'style-2'            => esc_html__('Header Full Classic', 'noo-landmark'),
                    'style-3'            => esc_html__('Header Half Map', 'noo-landmark'),
                    'style-4'            => esc_html__('Header Logo Center', 'noo-landmark'),
                    'style-5'            => esc_html__('Header Fullwidth Logo Center', 'noo-landmark'),
                    'style-6'            => esc_html__('Header Agency', 'noo-landmark')
                ),
                'json' => array(
                    'child_options' => array(
                        'default_menu_style' => 'noo_header_nav_meta,noo_header_nav_phone,noo_header_nav_widget,menu_style_5_option_cart',
                        'default_basic'      => 'noo_header_nav_meta,noo_header_nav_phone,noo_header_nav_widget,menu_style_5_option_woocommerce,menu_style_5_option_cart',
                        'style-1'            => 'noo_header_nav_meta,noo_header_nav_phone,noo_header_nav_widget',
                        'style-2'            => 'noo_header_nav_meta,noo_header_nav_phone,noo_header_nav_widget,menu_style_5_option_woocommerce,menu_style_5_option_cart',
                        'style-3'            => 'noo_header_nav_option_1, noo_header_nav_option_2, noo_header_nav_option_3, noo_header_nav_option_4, noo_header_nav_option_5, noo_header_nav_option_6, noo_header_nav_option_7, noo_header_nav_option_8',
                        'style-4'            => 'noo_header_nav_meta,noo_header_nav_phone,noo_header_nav_widget',
                        'style-5'            => 'noo_header_nav_meta,noo_header_nav_phone,noo_header_nav_widget,menu_style_5_option_off_canvas_menu,menu_style_5_option_woocommerce,menu_style_5_option_cart',
                        'style-6'            => 'noo_header_nav_meta,noo_header_nav_phone,noo_header_nav_widget'
                    )
                )
            )
        );

        $helper->add_control(
            'menu_style_5_option_off_canvas_menu',
            'noo_switch',
            esc_html__('Show Off-Canvas Menu', 'noo-landmark'),
            1
        );

        if( defined('WOOCOMMERCE_VERSION')) {
            $helper->add_control(
                'menu_style_5_option_woocommerce',
                'noo_switch',
                esc_html__('Show Woocommerce Links', 'noo-landmark'),
                1
            );

            $helper->add_control(
                'menu_style_5_option_cart',
                'noo_switch',
                esc_html__('Show Shopping Cart', 'noo-landmark'),
                1
            );
        }

        // Control: Navbar Meta
        $helper->add_control(
            'noo_header_nav_meta',
            'noo_radio',
            esc_html__('Choice Meta For Nav', 'noo-landmark'),
            'property',
            array(
                'choices' => array(
                    'property' => esc_html__('Show Button "Add Property"', 'noo-landmark'),
                    'phone' => esc_html__('Show Phone Number', 'noo-landmark'),
                    'social' => esc_html__('Show Social Network', 'noo-landmark')
                ),
                'json' => array(
                    'child_options' => array(
                        'phone' => 'noo_header_nav_phone',
                        'social' => 'noo_header_nav_widget'
                    )
                )
            )
        );

        // Control: Navbar Meta Phone
        $helper->add_control(
            'noo_header_nav_phone',
            'text',
            esc_html__('Add Phone Number', 'noo-landmark')
        );

        // Control: Navbar Meta Social Network
        $helper->add_control(
            'noo_header_nav_widget',
            'select',
            esc_html__('Select widget', 'noo-landmark'),
            'select_widget',
            array(
                'choices' => $arr_widget
            )
        );

        // Control: Navbar Field Fiter search for Header Half Map
        $helper->add_control(
            'noo_header_nav_option_1',
            'noo_switch',
            esc_html__('Show Position #1 (Keyword)', 'noo-landmark'),
            1
        );
        $helper->add_control(
            'noo_header_nav_option_2',
            'noo_switch',
            esc_html__('Show Position #2 (Neightborhood)', 'noo-landmark'),
            1
        );
        $helper->add_control(
            'noo_header_nav_option_3',
            'noo_switch',
            esc_html__('Show Position #3 (Types)', 'noo-landmark'),
            1
        );
        $helper->add_control(
            'noo_header_nav_option_4',
            'noo_switch',
            esc_html__('Show Position #4 (City)', 'noo-landmark'),
            1
        );
        $helper->add_control(
            'noo_header_nav_option_5',
            'noo_switch',
            esc_html__('Show Position #5 (Bedrooms)', 'noo-landmark'),
            0
        );
        $helper->add_control(
            'noo_header_nav_option_6',
            'noo_switch',
            esc_html__('Show Position #6 (Bathrooms)', 'noo-landmark'),
            0
        );
        $helper->add_control(
            'noo_header_nav_option_7',
            'noo_switch',
            esc_html__('Show Position #7 (Garages)', 'noo-landmark'),
            0
        );
        $helper->add_control(
            'noo_header_nav_option_8',
            'noo_switch',
            esc_html__('Show Position #8 (Price)', 'noo-landmark'),
            0
        );

        // Sub-Section: Navigation Bar
        $helper->add_sub_section(
            'noo_header_sub_section_nav',
            esc_html__('Navigation Bar', 'noo-landmark'),
            esc_html__('Adjust settings for Navigation Bar. You also can customize some settings for the Toggle Button on Mobile in this section.', 'noo-landmark')
        );

        // Control: NavBar Position
        $helper->add_control(
            'noo_header_nav_position',
            'noo_radio',
            esc_html__('NavBar Position', 'noo-landmark'),
            'static_top',
            array(
                'choices' => array(
                    'static_top'      => esc_html__('Static Top', 'noo-landmark'),
                    'fixed_top'       => esc_html__('Fixed Top', 'noo-landmark'),
                    'fixed_scroll_up' => esc_html__('Fixed Top When Scroll Up', 'noo-landmark')
                ),
                'json' => array(
                    'child_options' => array(
                        'fixed_top'       => 'noo_header_fixed_top_bar',
                        'fixed_scroll_up' => 'noo_header_fixed_top_bar'
                    )
                )
            )
        );

        $helper->add_control(
            'noo_header_fixed_top_bar',
            'noo_switch',
            esc_html__('Fixed Top Bar', 'noo-landmark'),
            0
        );

        // Control: Navigation background Color
        $helper->add_control(
            'noo_header_bg_color',
            'alpha_color',
            esc_html__('Background Navigation', 'noo-landmark'),
            '#ffffff',
            array(
                'preview_type' => 'custom'
            )
        );

        // Control: Divider 2
        $helper->add_control('noo_header_nav_divider_2', 'divider', '');

        // Control: Custom NavBar Font
        $helper->add_control(
            'noo_header_custom_nav_font',
            'noo_switch',
            esc_html__('Use Custom NavBar Font and Color?', 'noo-landmark'),
            0,
            array('json' => array(
                'on_child_options' => 'noo_header_nav_font,noo_header_nav_font_size,noo_header_nav_link_color,noo_header_nav_link_hover_color'
            ),
                'preview_type'   => 'update_css',
                'preview_params' => array('css' => 'header')
            )
        );

        // Control: NavBar font
        $helper->add_control(
            'noo_header_nav_font',
            'google_fonts',
            esc_html__('NavBar Font', 'noo-landmark'),
            noo_landmark_func_get_theme_default('headings_font_family'),
            array(
                'weight'       => '700',
                'style'        => 'normal',
                'preview_type' => 'custom',
            )
        );

        // Control: NavBar Font Size
        $helper->add_control(
            'noo_header_nav_font_size',
            'ui_slider',
            esc_html__('Font Size (px)', 'noo-landmark'),
            '18',
            array(
                'json' => array(
                    'data_min' => 9,
                    'data_max' => 30,
                ),
                'preview_type' => 'custom'
            )
        );

        // Control: NavBar Link Color
        $helper->add_control(
            'noo_header_nav_link_color',
            'alpha_color',
            esc_html__('Link Color', 'noo-landmark'),
            '#333333',
            array(
                'preview_type' => 'custom'
            )
        );

        // Control: NavBar Link Hover Color
        $helper->add_control(
            'noo_header_nav_link_hover_color',
            'alpha_color',
            esc_html__('Link Hover Color', 'noo-landmark'),
            noo_landmark_func_get_theme_default( 'primary_color' ),
            array(
                'preview_type' => 'custom'
            )
        );

        // Control: NavBar Height (px)
        $helper->add_control(
            'noo_header_nav_height',
            'ui_slider',
            esc_html__('NavBar Height (px)', 'noo-landmark'),
            '128',
            array(
                'json' => array(
                    'data_min' => 20,
                    'data_max' => 150,
                ),
                'preview_type' => 'custom'
            )
        );

        // Control: NavBar Link Spacing (px)
        $helper->add_control(
            'noo_header_nav_link_spacing',
            'ui_slider',
            esc_html__('NavBar Link Spacing (px)', 'noo-landmark'),
            '20',
            array(
                'json' => array(
                    'data_min' => 10,
                    'data_max' => 50,
                ),
                'preview_type' => 'custom'
            )
        );

        // Sub-Section: Logo
        $helper->add_sub_section(
            'noo_header_sub_section_logo',
            esc_html__('Logo', 'noo-landmark'),
            esc_html__('All the settings for Logo go here. If you do not use Image for Logo, plain text will be used.', 'noo-landmark')
        );

        // Control: Use Image for Logo
        $helper->add_control(
            'noo_header_use_image_logo',
            'noo_switch',
            esc_html__('Use Image for Logo?', 'noo-landmark'),
            0,
            array(
                'json' => array(
                    'on_child_options'  => 'noo_header_logo_image,noo_header_logo_image_height',
                    'off_child_options' => 'blogname
										,noo_header_logo_font
                                        ,noo_header_logo_font_size
                                        ,noo_header_logo_font_color
                                        ,noo_header_logo_uppercase'
                )
            )
        );

        // Control: Blog Name
        $helper->add_control(
            'blogname',
            'text',
            esc_html__('Blog Name', 'noo-landmark'),
            get_bloginfo('name'),
            array(
                'preview_type' => 'custom'
            )
        );

        // Control: Logo font
        $helper->add_control(
            'noo_header_logo_font',
            'google_fonts',
            esc_html__('Logo Font', 'noo-landmark'),
            noo_landmark_func_get_theme_default('logo_font_family'),
            array(
                'weight'       => '700',
                'style'        => 'normal',
                'preview_type' => 'custom'
            )
        );

        // Control: Logo Font Size
        $helper->add_control(
            'noo_header_logo_font_size',
            'ui_slider',
            esc_html__('Font Size (px)', 'noo-landmark'),
            '30',
            array(
                'json' => array(
                    'data_min' => 15,
                    'data_max' => 80,
                ),
                'preview_type' => 'custom'
            )
        );

        // Control: Logo Font Color
        $helper->add_control(
            'noo_header_logo_font_color',
            'alpha_color',
            esc_html__('Font Color', 'noo-landmark'),
            noo_landmark_func_get_theme_default('logo_color'),
            array(
                'preview_type' => 'custom'
            )
        );

        // Control: Logo Font Uppercase
        $helper->add_control(
            'noo_header_logo_uppercase',
            'checkbox',
            esc_html__('Transform to Uppercase', 'noo-landmark'),
            0,
            array(
                'preview_type' => 'custom'
            )
        );

        // Control: Logo Image

        $helper->add_control(
            'noo_header_logo_image',
            'noo_image_advanced',
            esc_html__('Upload Your Logo', 'noo-landmark'),
            '',
            array(
                'flex_width'    => true, // Allow any width, making the specified value recommended. False by default.
                'flex_height'   => true, // Require the resulting image to be exactly as tall as the height attribute (default).
                'width'         => 120,
                'height'        => 120,
                'button_labels' => array(
                    'select'       => esc_html__('Select logo', 'noo-landmark'),
                    'change'       => esc_html__('Change logo', 'noo-landmark'),
                    'remove'       => esc_html__('Remove', 'noo-landmark'),
                    'default'      => esc_html__('Default', 'noo-landmark'),
                    'placeholder'  => esc_html__('No logo selected', 'noo-landmark'),
                    'frame_title'  => esc_html__('Select logo', 'noo-landmark'),
                    'frame_button' => esc_html__('Choose logo', 'noo-landmark'),
                ),
                'preview_type' => 'custom'
            )
        );

        // Control: Logo Image Height
        $helper->add_control(
            'noo_header_logo_image_height',
            'ui_slider',
            esc_html__('Image Height (px)', 'noo-landmark'),
            '70',
            array(
                'json' => array(
                    'data_min' => 15,
                    'data_max' => 120,
                ),
                'preview_type' => 'custom'
            )
        );

        // Sub-Section: NavBar Top
        $helper->add_sub_section(
            'noo_header_sub_section_top',
            esc_html__('Top Bar', 'noo-landmark')
        );

        $helper->add_control(
            'noo_header_top_left_on_off',
            'noo_switch',
            esc_html__('Show Left Top bar', 'noo-landmark'),
            0,
            array(
                'json' => array(
                    'on_child_options' => 'noo_header_top_left_choices'
                )
            )
        );

        $left_top_arr = array();
        $left_top_arr['left_select_widget'] = esc_html__('Widget', 'noo-landmark');
        $left_top_arr['left_phone_number'] = esc_html__('Show Phone Number', 'noo-landmark');
        $left_top_arr['left_custom_html'] = esc_html__('Content', 'noo-landmark');
        $left_top_arr['left_select_menu'] = esc_html__('Quick Menu', 'noo-landmark');
        if( defined('WOOCOMMERCE_VERSION') ) {
            $left_top_arr['left_woocommerce_links'] = esc_html__('Show WooCommerce Links', 'noo-landmark');
        }

        $helper->add_control(
            'noo_header_top_left_choices',
            'noo_radio',
            esc_html__('Left Top bar Options', 'noo-landmark'),
            'left_phone_number',
            array(
                'choices' => $left_top_arr,
                'json' => array(
                    'child_options' => array(
                        'left_select_widget'     => 'noo_header_top_left_widget',
                        'left_phone_number'      => 'noo_header_top_left_phone',
                        'left_custom_html'       => 'noo_header_top_left_custom_html',
                        'left_select_menu'       => 'noo_header_top_left_quick_menu',
                        'left_woocommerce_links' => 'noo_header_top_left_show_user,noo_header_top_left_show_wishlist',
                    )
                )
            )
        );


        if( defined('WOOCOMMERCE_VERSION') ) {

            $helper->add_control(
                'noo_header_top_left_show_wishlist',
                'noo_switch',
                esc_html__('Show Wishlist', 'noo-landmark'),
                1
            );

            $helper->add_control(
                'noo_header_top_left_show_user',
                'noo_switch',
                esc_html__('Show User', 'noo-landmark'),
                1
            );

        }

        $helper->add_control(
            'noo_header_top_right_on_off',
            'noo_switch',
            esc_html__('Show Right Top bar', 'noo-landmark'),
            0,
            array(
                'json' => array(
                    'on_child_options' => 'noo_header_top_right_choices,
                                            noo_header_top_login_register'
                )
            )
        );

        // Controll: Top Bar Right Widget
        $helper->add_control(
            'noo_header_top_left_widget',
            'select',
            esc_html__('Select widget', 'noo-landmark'),
            'select_widget',
            array(
                'choices' => $arr_widget
            )
        );

        // Control: TopBar Meta Phone
        $helper->add_control(
            'noo_header_top_left_phone',
            'text',
            esc_html__('Add Phone Number', 'noo-landmark')
        );

        // Controll: Top Bar Left Content (HTML)
        $helper->add_control(
            'noo_header_top_left_custom_html',
            'textarea',
            esc_html__('Custom Content (HTML)', 'noo-landmark'),
            ''
        );

        // Control: Top Bar Select Quick Menu
        $menu_all = wp_get_nav_menus();
        $arr_menu = array('all_menu'=>esc_html__('Select Menu', 'noo-landmark'));
        foreach ($menu_all as $menu_item) {
            $menu_default = $menu_item->term_id;
            $arr_menu[$menu_item->term_id] = $menu_item->name;
        }

        $helper->add_control(
            'noo_header_top_left_quick_menu',
            'select',
            esc_html__('Select Quick Menu', 'noo-landmark'),
            'all_menu',
            array(
                'choices' => $arr_menu
            )
        );

        $right_top_arr = array();
        $right_top_arr['right_select_widget'] = esc_html__('Widget', 'noo-landmark');
        $right_top_arr['right_phone_number'] = esc_html__('Show Phone Number', 'noo-landmark');
        $right_top_arr['right_custom_html'] = esc_html__('Content', 'noo-landmark');
        $right_top_arr['right_select_menu'] = esc_html__('Quick Menu', 'noo-landmark');
        if( defined('WOOCOMMERCE_VERSION') ) {
            $right_top_arr['right_woocommerce_links'] = esc_html__('Show WooCommerce Links', 'noo-landmark');
        }
        
        $helper->add_control(
            'noo_header_top_right_choices',
            'noo_radio',
            esc_html__('Right Top bar options', 'noo-landmark'),
            'right_select_widget',
            array(
                'choices' => $right_top_arr,
                'json' => array(
                    'child_options' => array(
                        'right_select_widget'     => 'noo_header_top_right_widget',
                        'right_phone_number'      => 'noo_header_top_right_phone',
                        'right_custom_html'       => 'noo_header_top_right_custom_html',
                        'right_select_menu'       => 'noo_header_top_right_quick_menu',
                        'right_woocommerce_links' => 'noo_header_top_right_show_wishlist,noo_header_top_right_show_user'
                    )
                )
            )
        );

        if( defined('WOOCOMMERCE_VERSION') ) {

            $helper->add_control(
                'noo_header_top_right_show_wishlist',
                'noo_switch',
                esc_html__('Show Wishlist', 'noo-landmark'),
                1
            );

            $helper->add_control(
                'noo_header_top_right_show_user',
                'noo_switch',
                esc_html__('Show User', 'noo-landmark'),
                1
            );

        }

        // Controll: Top Bar Right Widget
        $helper->add_control(
            'noo_header_top_right_widget',
            'select',
            esc_html__('Select widget', 'noo-landmark'),
            'select_widget',
            array(
                'choices' => $arr_widget
            )
        );

        // Control: TopBar Meta Phone
        $helper->add_control(
            'noo_header_top_right_phone',
            'text',
            esc_html__('Add Phone Number', 'noo-landmark')
        );
        
        // Controll: Top Bar Right Content (HTML)
        $helper->add_control(
            'noo_header_top_right_custom_html',
            'textarea',
            esc_html__('Custom Content (HTML)', 'noo-landmark'),
            ''
        );

        $helper->add_control(
            'noo_header_top_right_quick_menu',
            'select',
            esc_html__('Select Quick Menu','noo-landmark'),
            'all_menu',
            array(
                'choices' => $arr_menu
            )
        );

        // if ( defined( 'Noo_LandMark_Core' ) ) {

            /**
             * Show Login/Register
             */
            $helper->add_control(
                'noo_header_top_login_register',
                'noo_switch',
                esc_html__('Show Login/Register', 'noo-landmark'),
                1,
                array(
                    'json' => array(
                        'on_child_options' => 'noo_header_top_label_login_register'
                    )
                )
            );

            // Control: Label Login/Register
            $helper->add_control(
                'noo_header_top_label_login_register',
                'text',
                esc_html__('Login/Register Label', 'noo-landmark'),
                esc_html__('Login / Register', 'noo-landmark')
            );

        // }

        // Control: Divider 2
        $helper->add_control('noo_header_nav_divider_2', 'divider', '');

        // Control: Top Bar Background
        $helper->add_control(
            'noo_header_top_bg_color',
            'alpha_color',
            esc_html__('Topbar background', 'noo-landmark')
        );

        // Control: TopBar Height (px)
        $helper->add_control(
            'noo_header_top_height',
            'ui_slider',
            esc_html__('Top Bar Height (px)', 'noo-landmark'),
            '35',
            array(
                'json' => array(
                    'data_min' => 45,
                    'data_max' => 100,
                ),
                'preview_type' => 'custom'
            )
        );

        // Control: Custom TopBar Font
        $helper->add_control(
            'noo_header_custom_top_font',
            'noo_switch',
            esc_html__('Use Custom TopBar Font and Color?', 'noo-landmark'),
            0,
            array('json' => array(
                'on_child_options' => 'noo_header_top_font,noo_header_top_link_color,noo_header_top_link_hover_color,noo_header_top_font_size,noo_header_top_font_size_right'
            ),
                'preview_type'   => 'update_css',
                'preview_params' => array('css' => 'header')
            )
        );

        // Control: TopBar font
        $helper->add_control(
            'noo_header_top_font',
            'google_fonts',
            esc_html__('TopBar Font', 'noo-landmark'),
            noo_landmark_func_get_theme_default('headings_font_family'),
            array(
                'weight'       => '700',
                'style'        => 'normal',
                'preview_type' => 'custom',
            )
        );

        // Control: TopBar Font Size
        $helper->add_control(
            'noo_header_top_font_size',
            'ui_slider',
            esc_html__('Font Size TopBar Left(px)', 'noo-landmark'),
            noo_landmark_func_get_theme_default('font_size'),
            array(
                'json' => array(
                    'data_min' => 9,
                    'data_max' => 30,
                ),
                'preview_type' => 'custom'
            )
        );
        $helper->add_control(
            'noo_header_top_font_size_right',
            'ui_slider',
            esc_html__('Font Size TopBar Right(px)', 'noo-landmark'),
            noo_landmark_func_get_theme_default('font_size'),
            array(
                'json' => array(
                    'data_min' => 9,
                    'data_max' => 30,
                ),
                'preview_type' => 'custom'
            )
        );

        // Control: TopBar Link Color
        $helper->add_control(
            'noo_header_top_link_color',
            'alpha_color',
            esc_html__('Color', 'noo-landmark'),
            '#000',
            array(
                'preview_type' => 'custom'
            )
        );

        // Control: TopBar Link Hover Color
        $helper->add_control(
            'noo_header_top_link_hover_color',
            'alpha_color',
            esc_html__('Link Hover Color', 'noo-landmark'),
            '',
            array(
                'preview_type' => 'custom'
            )
        );
    }

    add_action('customize_register', 'noo_landmark_func_customizer_register_options_header');
endif;

// 5. Footer options.
if (!function_exists('noo_landmark_func_customizer_register_options_footer')) :
    function noo_landmark_func_customizer_register_options_footer($wp_customize)
    {

        // declare helper object.
        $helper = new NOO_Customizer_Helper($wp_customize);

        // Section: Footer
        $helper->add_section(
            'noo_landmark_func_customizer_section_footer',
            esc_html__('Footer', 'noo-landmark'),
            esc_html__('Footer contains Widgetized area and Footer Bottom. You can change any parts.', 'noo-landmark')
        );

        // Control: Footer style
        $helper->add_sub_section(
            'noo_landmark_func_customizer_sub_section_footer_style',
            esc_html__('Footer Style', 'noo-landmark')
        );

        $helper->add_control(
            'noo_landmark_footer_style',
            'noo_radio',
            esc_html__('Choice footer style.', 'noo-landmark'),
            'footer_1',
            array(
                'choices' => array(
                    'footer_1' => esc_html__('Footer Basic', 'noo-landmark'),
                    'footer_2' => esc_html__('Footer Business', 'noo-landmark'),
                    'footer_3' => esc_html__('Footer Agency', 'noo-landmark')
                ),
                'json' => array(
                    'child_options' =>array(
                        'footer_1' => 'noo_landmark_func_customizer_sub_section_footer,
                                        noo_footer_widget_show, noo_footer_widgets,
                                        noo_landmark_func_customizer_sub_section_footer_width,
                                        noo_footer_1, noo_footer_2, noo_footer_3, noo_footer_4',
                        'footer_2' => 'noo_landmark_func_customizer_sub_section_footer,
                                        noo_footer_widget_show, noo_footer_widgets,
                                        noo_landmark_func_customizer_sub_section_footer_width,
                                        noo_footer_1, noo_footer_2, noo_footer_3, noo_footer_4'                          
                    )
                )
            )
        );

        // Control: Footer setting
        $helper->add_sub_section(
            'noo_landmark_func_customizer_sub_section_footer_setting',
            esc_html__('Footer Background Image', 'noo-landmark')
        );

        $helper->add_control(
            'noo_landmark_footer_fixed',
            'noo_switch',
            esc_html__('Footer Position: Fixed', 'noo-landmark'),
            0
        );

        $helper->add_control(
            'noo_landmark_footer_background_on_off',
            'noo_switch',
            esc_html__('Use Image for background footer', 'noo-landmark'),
            1,
            array(
                'json' => array(
                    'on_child_options' => 'noo_landmark_footer_background, noo_landmark_footer_color'
                )
            )
        );

        $helper->add_control(
            'noo_landmark_footer_background',
            'noo_image',
            esc_html__( 'Background Image', 'noo-landmark' )
        );

        $helper->add_control(
            'noo_landmark_footer_color',
            'alpha_color',
            esc_html__( 'Background Color & Opacity On Background Image', 'noo-landmark'),
            'rgba(255,255,255,0.8)'
        );

        // Control: Footer Columns (Widgetized)
        $helper->add_sub_section(
            'noo_landmark_func_customizer_sub_section_footer',
            esc_html__('Footer widget option', 'noo-landmark'),
            esc_html__('You can customize show/hide of the footer.', 'noo-landmark')
        );
        $helper->add_control(
            'noo_footer_widget_show',
            'noo_switch',
            esc_html__('Show footer column widget', 'noo-landmark'),
            1
        );
        $helper->add_control(
            'noo_footer_widgets',
            'noo_radio_image',
            esc_html__('Footer Content Box (Widgetized)', 'noo-landmark'),
            '4',
            array(
                'choices' => array(
                    1 => NOO_ADMIN_ASSETS_IMG . '/1col-full.png',
                    2 => NOO_ADMIN_ASSETS_IMG . '/2col-full.png',
                    3 => NOO_ADMIN_ASSETS_IMG . '/3col-full.png',
                    4 => NOO_ADMIN_ASSETS_IMG . '/4col-full.png',
                ),
                'json' => array(
                    'child_options' => array(
                        '1'   => 'noo_footer_1',
                        '2'   => 'noo_footer_1,noo_footer_2',
                        '3'   => 'noo_footer_1,noo_footer_2,noo_footer_3',
                        '4'   => 'noo_footer_1,noo_footer_2,noo_footer_3,noo_footer_4',
                    )
                )
            )
        );
        $helper->add_sub_section(
            'noo_landmark_func_customizer_sub_section_footer_width',
            esc_html__('Footer Column Width', 'noo-landmark'),
            esc_html__('You can customize width of the footer columns and total width of the footer columns is 12', 'noo-landmark')
        );
        $helper->add_control(
            'noo_footer_1',
            'ui_slider',
            esc_html__('Footer Column 1 Width', 'noo-landmark'),
            '3',
            array(
                'json' => array(
                    'data_min'  => 1,
                    'data_max'  => 12,
                    'data_step' => 1
                )
            )
        );
        $helper->add_control(
            'noo_footer_2',
            'ui_slider',
            esc_html__('Footer Column 2 Width', 'noo-landmark'),
            '3',
            array(
                'json' => array(
                    'data_min'  => 1,
                    'data_max'  => 12,
                    'data_step' => 1
                )
            )
        );
        $helper->add_control(
            'noo_footer_3',
            'ui_slider',
            esc_html__('Footer Column 3 Width', 'noo-landmark'),
            '3',
            array(
                'json' => array(
                    'data_min'  => 1,
                    'data_max'  => 12,
                    'data_step' => 1
                )
            )
        );
        $helper->add_control(
            'noo_footer_4',
            'ui_slider',
            esc_html__('Footer Column 4 Width', 'noo-landmark'),
            '3',
            array(
                'json' => array(
                    'data_min'  => 1,
                    'data_max'  => 12,
                    'data_step' => 1
                )
            )
        );

        // Control: Divider 2
        $helper->add_control('noo_footer_divider_2', 'divider', '');

        // Control: Bottom Bar Content
        $helper->add_control(
            'noo_bottom_bar_content',
            'textarea',
            esc_html__('Footer Bottom Content (HTML)', 'noo-landmark'),
            noo_landmark_func_html_content_filter( __( '&copy; 2016. Designed with <i class="fa fa-heart text-primary" ></i> by NooTheme', 'noo-landmark' ) ),
            array(
                'preview_type' => 'custom'
            )
        );
        $helper->add_control(
            'noo_bottom_social_on',
            'noo_switch',
            esc_html__('Show/Hide Social On Copyright', 'noo-landmark'),
            1
        );

    }

    add_action('customize_register', 'noo_landmark_func_customizer_register_options_footer');
endif;

// 6. WP Sidebar options.
if (!function_exists('noo_landmark_func_customizer_register_options_sidebar')) :
    function noo_landmark_func_customizer_register_options_sidebar($wp_customize)
    {

        global $wp_version;
        if ($wp_version >= 4.0) {
            // declare helper object.
            $helper = new NOO_Customizer_Helper($wp_customize);

            // Change the sidebar panel priority
            $widget_panel = $wp_customize->get_panel('widgets');
            if (!empty($widget_panel)) {
                $widget_panel->priority = $helper->get_new_section_priority();
            }
        }
    }

    add_action('customize_register', 'noo_landmark_func_customizer_register_options_sidebar');
endif;

// 7. Blog options.
if (!function_exists('noo_landmark_func_customizer_register_options_blog')) :
    function noo_landmark_func_customizer_register_options_blog($wp_customize)
    {

        // declare helper object.
        $helper = new NOO_Customizer_Helper($wp_customize);

        // Section: Blog
        $helper->add_section(
            'noo_landmark_func_customizer_section_blog',
            esc_html__('Blog', 'noo-landmark'),
            esc_html__('In this section you have settings for your Blog page, Archive page and Single Post page.', 'noo-landmark'),
            true
        );

        // Sub-section: Blog Page (Index Page)
        $helper->add_sub_section(
            'noo_blog_sub_section_blog_page',
            esc_html__('Post List', 'noo-landmark'),
            esc_html__('Choose Layout settings for your Post List', 'noo-landmark')
        );

        // Control: Blog Layout

        $helper->add_control(
            'noo_blog_layout',
            'noo_radio_image',
            esc_html__('Blog Layout', 'noo-landmark'),
            'sidebar',
            array(
                'choices' => array(
                    'fullwidth'    => NOO_ADMIN_ASSETS_IMG . '/1col.png',
                    'sidebar'      => NOO_ADMIN_ASSETS_IMG . '/2cr.png',
                    'left_sidebar' => NOO_ADMIN_ASSETS_IMG . '/2cl.png',
                ),
                'json' => array(
                    'child_options' => array(
                        'fullwidth' => '',
                        'sidebar' => 'noo_blog_sidebar',
                        'left_sidebar' => 'noo_blog_sidebar'
                    )
                )
            )
        );

        // Control: Blog Sidebar
        $helper->add_control(
            'noo_blog_sidebar',
            'widgets_select',
            esc_html__('Blog Sidebar', 'noo-landmark'),
            'sidebar-main'
        );

        // Control: Blog Style
        $helper->add_control(
            'noo_blog_style',
            'noo_radio',
            esc_html__( 'Blog Style', 'noo-landmark' ),
            'grid',
            array(
                'choices' => array(
                    'list'  => esc_html__( 'List', 'noo-landmark' ),
                    'list2' => esc_html__( 'List 2 Column', 'noo-landmark' ),
                    'grid'  => esc_html__( 'Grid Masonry', 'noo-landmark' ),
                ),
                'json' => array(
                    'child_options' => array(
                        'grid'   => 'noo_blog_grid_columns',
                    )
                ),
            ),
            array( 'transport' => 'postMessage' )
        );

        // Control: Grid Columns
        $helper->add_control(
            'noo_blog_grid_columns',
            'select',
            esc_html__( 'Grid Columns', 'noo-landmark' ),
            '2',
            array(
                'choices' => array(
                    2     => esc_html__( 'Two', 'noo-landmark' ),
                    3     => esc_html__( 'Three', 'noo-landmark' ),
                )
            ),
            array( 'transport' => 'postMessage' )
        );

        // Control: Divider 1
        $helper->add_control('noo_blog_divider_1', 'divider', '');

        // Control: Heading Title
        $helper->add_control(
            'noo_blog_heading_title',
            'text',
            esc_html__('Heading Title', 'noo-landmark'),
            esc_html__('Blog', 'noo-landmark')
        );

        // Control: Heading Image
        $helper->add_control(
            'noo_blog_heading_image',
            'noo_image',
            esc_html__('Heading Background Image', 'noo-landmark'),
            ''
        );

        // Control: Divider 2
        $helper->add_control('noo_blog_divider_2', 'divider', '');

        // Control: Show blog readmore
        $helper->add_control(
            'noo_blog_show_readmore',
            'noo_switch',
            esc_html__('Enable Readmore Button', 'noo-landmark'),
            1
        );

        // Control: Excerpt Length
        $helper->add_control(
            'noo_blog_excerpt_length',
            'text',
            esc_html__('Excerpt Length', 'noo-landmark'),
            '60'
        );

        // Control: Divider 2
        $helper->add_control('noo_blog_post_divider_2', 'divider', '');

        // Control: Enable Social Sharing
        $helper->add_control(
            'noo_blog_social',
            'noo_switch',
            esc_html__('Enable Social Sharing', 'noo-landmark'),
            1,
            array(
                'json' => array('on_child_options' => 'noo_blog_social_facebook,
                                                        noo_blog_social_twitter,
                                                        noo_blog_social_google,
                                                        noo_blog_social_pinterest,
                                                        noo_blog_social_linkedin'
                )
            )
        );

        // Control: Facebook Share
        $helper->add_control(
            'noo_blog_social_facebook',
            'checkbox',
            esc_html__('Facebook Share', 'noo-landmark'),
            1
        );

        // Control: Twitter Share
        $helper->add_control(
            'noo_blog_social_twitter',
            'checkbox',
            esc_html__('Twitter Share', 'noo-landmark'),
            1
        );

        // Control: Google+ Share
        $helper->add_control(
            'noo_blog_social_google',
            'checkbox',
            esc_html__('Google+ Share', 'noo-landmark'),
            1
        );

        // Control: Pinterest Share
        $helper->add_control(
            'noo_blog_social_pinterest',
            'checkbox',
            esc_html__('Pinterest Share', 'noo-landmark'),
            0
        );

        // Control: LinkedIn Share
        $helper->add_control(
            'noo_blog_social_linkedin',
            'checkbox',
            esc_html__('LinkedIn Share', 'noo-landmark'),
            0
        );

        // Sub-section: Single Post
        $helper->add_sub_section(
            'noo_blog_sub_section_post',
            esc_html__('Single Post', 'noo-landmark'),
            esc_html__('Choose Layout settings for your Single Post', 'noo-landmark')
        );

        // Control: Post Layout
        $helper->add_control(
            'noo_blog_post_layout_same',
            'noo_radio',
            esc_html__('Single Post Layout', 'noo-landmark'),
            'same_as_blog',
            array(
                'choices' => array(
                    'same_as_blog' => esc_html__('Same as Blog Layout', 'noo-landmark'),
                    'other_layout' =>  esc_html__('Select Layout', 'noo-landmark'),
                ),
                'json' => array(
                    'child_options' => array(
                        'same_as_blog' => '',
                        'other_layout' => 'noo_blog_post_layout',
                    )
                )
            )
        );
        $helper->add_control(
            'noo_blog_post_layout',
            'noo_radio_image',
            esc_html__('Post Layout', 'noo-landmark'),
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
                        'sidebar'      => 'noo_blog_post_sidebar',
                        'left_sidebar' => 'noo_blog_post_sidebar',
                    )
                )
            )
        );

        // Control: Post Sidebar
        $helper->add_control(
            'noo_blog_post_sidebar',
            'widgets_select',
            esc_html__('Post Sidebar', 'noo-landmark'),
            'sidebar-main'
        );

        // Control: Divider 1
        $helper->add_control('noo_blog_post_divider_1', 'divider', '');

        // Control: Show Post Meta
        $helper->add_control(
            'noo_blog_post_show_post_meta',
            'checkbox',
            esc_html__('Show Post Meta', 'noo-landmark'),
            1
        );


        // Control: Show Author Bio
        $helper->add_control(
            'noo_blog_post_author_bio',
            'checkbox',
            esc_html__('Show Author\'s Bio', 'noo-landmark'),
            1
        );
    }

    add_action('customize_register', 'noo_landmark_func_customizer_register_options_blog');
endif;

// 8. Custom Post Type options
if (!function_exists('noo_landmark_func_customizer_register_options_post_type')) :
    function noo_landmark_func_customizer_register_options_post_type($wp_customize)
    {
        global $noo_post_types;
        if (empty($noo_post_types)) return;

        // declare helper object.
        $helper = new NOO_Customizer_Helper($wp_customize);

        foreach ($noo_post_types as $post_type => $args) {
            if (!isset($args['customizer']) || empty($args['customizer']))
                continue;

            $pt_customizer = $args['customizer'];

            $pt_customizer['panel'] = isset($pt_customizer['panel']) ? $pt_customizer['panel'] : array('single');

            $helper->add_section(
                array(
                    'id'          => "noo_landmark_func_customizer_section_{$post_type}",
                    'label'       => $args['name'],
                    'description' => sprintf(esc_html__('Firstly assign a page as your %s page from dropdown list. %s page can be any page. Once you chose a page as %s Page, its slug will be your %s\'s main slug.', 'noo-landmark'), $args['name'], $args['name'], $args['name'], $args['name']),
                    'is_panel'    => count($pt_customizer['panel']) > 1
                )
            );

            if (in_array('list', $pt_customizer['panel'])) {
                // Sub-section: List
                $helper->add_sub_section(
                    "{$post_type}_archive_sub_section",
                    sprintf(esc_html__('List %s', 'noo-landmark'), $args['name'])
                );
            }

            if (in_array('page', $pt_customizer)) {
                // Control: Post type Page
                $helper->add_control(
                    array(
                        'id' => "{$post_type}_archive_page",
                        'type' => 'pages_select',
                        'label' => sprintf(esc_html__('%s Page', 'noo-landmark'), $args['name']),
                        'default' => '',
                    )
                );
            }

            if (in_array('heading-title', $pt_customizer)) {
                $default = isset($args['heading-title']) ? $args['heading-title'] : sprintf(esc_html__('%s List', 'noo-landmark'), $args['name']);

                // Control: Heading Title
                $helper->add_control(
                    array(
                        'id'      => "{$post_type}_heading_title",
                        'type'    => 'text',
                        'label'   => sprintf(esc_html__('%s Heading Title', 'noo-landmark'), $args['name']),
                        'default' => $default,
                    )
                );
            }

            if (in_array('heading-image', $pt_customizer)) {
                // Control: Heading Title
                $helper->add_control(
                    array(
                        'id'      => "{$post_type}_heading_image",
                        'type'    => 'noo_image',
                        'label'   => sprintf(esc_html__('%s Heading Background Image', 'noo-landmark'), $args['name']),
                        'default' => '',
                    )
                );
            }

            if (in_array('list-layout', $pt_customizer)) {
                // Control: List Layout
                $helper->add_control(
                    array(
                        'id'      => "{$post_type}_archive_layout",
                        'type'    => 'noo_radio',
                        'label'   => sprintf(esc_html__('%s List Layout', 'noo-landmark'), $args['name']),
                        'default' => 'sidebar',
                        'control' => array(
                            'choices' => array(
                                'fullwidth'    => esc_html__('Full-Width', 'noo-landmark'),
                                'sidebar'      => esc_html__('With Right Sidebar', 'noo-landmark'),
                                'left_sidebar' => esc_html__('With Left Sidebar', 'noo-landmark')
                            ),
                            'json' => array(
                                'child_options' => array(
                                    'fullwidth'    => '',
                                    'sidebar'      => "{$post_type}_archive_sidebar",
                                    'left_sidebar' => "{$post_type}_archive_sidebar"
                                )
                            )
                        ),
                    )
                );

                // Control: Event List Sidebar
                $helper->add_control(
                    array(
                        'id'      => "{$post_type}_archive_sidebar",
                        'type'    => 'widgets_select',
                        'label'   => sprintf(esc_html__('%s List Sidebar', 'noo-landmark'), $args['name']),
                        'default' => 'sidebar-main',
                    )
                );
            }

            if (in_array('layout', $pt_customizer)) {
                // Control: List Layout
                $helper->add_control(
                    array(
                        'id'      => "{$post_type}_archive_layout",
                        'type'    => 'noo_radio',
                        'label'   => sprintf(esc_html__('%s Layout', 'noo-landmark'), $args['name']),
                        'default' => 'sidebar',
                        'control' => array(
                            'choices' => array(
                                'fullwidth'    => esc_html__('Full-Width', 'noo-landmark'),
                                'sidebar'      => esc_html__('With Right Sidebar', 'noo-landmark'),
                                'left_sidebar' => esc_html__('With Left Sidebar', 'noo-landmark')
                            ),
                            'json' => array(
                                'child_options' => array(
                                    'fullwidth'    => '',
                                    'sidebar'      => "{$post_type}_archive_sidebar",
                                    'left_sidebar' => "{$post_type}_archive_sidebar"
                                )
                            )
                        ),
                    )
                );

                // Control: Event List Sidebar
                $helper->add_control(
                    array(
                        'id'      => "{$post_type}_archive_sidebar",
                        'type'    => 'widgets_select',
                        'label'   => sprintf(esc_html__('%s Sidebar', 'noo-landmark'), $args['name']),
                        'default' => 'sidebar-main',
                    )
                );
            }

            do_action("{$post_type}_archive_customizer", $wp_customize);

            if (in_array('list_num', $pt_customizer)) {
                // Control: Number of Item per Page
                $helper->add_control(
                    array(
                        'id'      => "{$post_type}_num",
                        'type'    => 'ui_slider',
                        'label'   => esc_html__('Items Per Page', 'noo-landmark'),
                        '8',
                        'control' => array(
                            'json' => array(
                                'data_min'  => '4',
                                'data_max'  => '50',
                                'data_step' => '2'
                            )
                        ),
                    )
                );
            }

            if (in_array('single', $pt_customizer['panel'])) {
                // Sub-section: Single
                $helper->add_sub_section(
                    "{$post_type}_single_sub_section",
                    sprintf(esc_html__('Single %s', 'noo-landmark'), $args['singular_name'])
                );
            }

            if (in_array('single-layout', $pt_customizer)) {
                // Control: Single Layout
                $helper->add_control(
                    array(
                        'id'      => "{$post_type}_single_layout",
                        'type'    => 'noo_same_as_radio',
                        'label'   => sprintf(esc_html__('Single %s Layout', 'noo-landmark'), $args['singular_name']),
                        'default' => "same_as_archive",
                        'control' => array(
                            'choices' => array(
                                "same_as_archive" => sprintf(esc_html__('Same as %s List Layout', 'noo-landmark'), $args['name']),
                                'fullwidth'       => esc_html__('Full-Width', 'noo-landmark'),
                                'sidebar'         => esc_html__('With Right Sidebar', 'noo-landmark'),
                                'left_sidebar'    => esc_html__('With Left Sidebar', 'noo-landmark'),
                            ),
                            'json' => array(
                                'child_options' => array(
                                    'fullwidth'    => '',
                                    'sidebar'      => "{$post_type}_single_sidebar",
                                    'left_sidebar' => "{$post_type}_single_sidebar",
                                )
                            )
                        ),
                    )
                );

                // Control: Single Sidebar
                $helper->add_control(
                    array(
                        'id'      => "{$post_type}_single_sidebar",
                        'type'    => 'widgets_select',
                        'label'   => sprintf(esc_html__('%s Sidebar', 'noo-landmark'), $args['singular_name']),
                        'default' => 'sidebar-main',
                    )
                );
            }


            do_action("{$post_type}_single_customizer", $wp_customize);

            if (in_array('single-social', $pt_customizer)) {
                $helper->add_control(
                    array(
                        'id'   => "{$post_type}_single_divider_1",
                        'type' => 'divider'
                    )
                );

                // Control: Enable Social Sharing
                $helper->add_control(
                    array(
                        'id'      => "{$post_type}_single_social",
                        'type'    => 'noo_switch',
                        'label'   => esc_html__('Enable Social Sharing', 'noo-landmark'),
                        'default' => 1,
                        'control' => array(
                            'json' => array('on_child_options' => "{$post_type}_single_social_facebook,
					                                                {$post_type}_single_social_twitter,
					                                                {$post_type}_single_social_google,
					                                                {$post_type}_single_social_pinterest,
					                                                {$post_type}_single_social_linkedin"
                            )
                        )
                    )
                );

                // Control: Facebook Share
                $helper->add_control(
                    array(
                        'id'      => "{$post_type}_single_social_facebook",
                        'type'    => 'noo_switch',
                        'label'   => esc_html__('Facebook Share', 'noo-landmark'),
                        'default' => 1,
                    )
                );

                // Control: Twitter Share
                $helper->add_control(
                    array(
                        'id'      => "{$post_type}_single_social_twitter",
                        'type'    => 'noo_switch',
                        'label'   => esc_html__('Twitter Share', 'noo-landmark'),
                        'default' => 1,
                    )
                );

                // Control: Google+ Share
                $helper->add_control(
                    array(
                        'id'      => "{$post_type}_single_social_google",
                        'type'    => 'noo_switch',
                        'label'   => esc_html__('Google+ Share', 'noo-landmark'),
                        'default' => 1,
                    )
                );

                // Control: Pinterest Share
                $helper->add_control(
                    array(
                        'id'      => "{$post_type}_single_social_pinterest",
                        'type'    => 'noo_switch',
                        'label'   => esc_html__('Pinterest Share', 'noo-landmark'),
                        'default' => 1,
                    )
                );

                // Control: LinkedIn Share
                $helper->add_control(
                    array(
                        'id'      => "{$post_type}_single_social_linkedin",
                        'type'    => 'noo_switch',
                        'label'   => esc_html__('LinkedIn Share', 'noo-landmark'),
                        'default' => 1,
                    )
                );
            }
        }

    }

    add_action('customize_register', 'noo_landmark_func_customizer_register_options_post_type');
endif;

// 9. Portfolio options.

// 10. WooCommerce options.
if (NOO_WOOCOMMERCE_EXIST) :
    if (!function_exists('noo_landmark_func_customizer_register_options_woocommerce')) :
        function noo_landmark_func_customizer_register_options_woocommerce($wp_customize)
        {

            // declare helper object.
            $helper = new NOO_Customizer_Helper($wp_customize);

            // Section: Revolution Slider
            $helper->add_section(
                'noo_landmark_func_customizer_section_shop',
                esc_html__('WooCommerce', 'noo-landmark'),
                '',
                true
            );

            // Sub-section: Shop Page
            $helper->add_sub_section(
                'noo_woocommerce_sub_section_shop_page',
                esc_html__('Shop Page', 'noo-landmark'),
                esc_html__('Choose Layout and Headline Settings for your Shop Page.', 'noo-landmark')
            );

            // Control: Shop Layout
            $helper->add_control(
                'noo_shop_layout',
                'noo_radio_image',
                esc_html__('Shop Layout', 'noo-landmark'),
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
                            'sidebar'      => 'noo_shop_sidebar,noo_shop_sidebar_extension',
                            'left_sidebar' => 'noo_shop_sidebar,noo_shop_sidebar_extension',
                        )
                    )
                )
            );

            // Control: Shop Sidebar
            $helper->add_control(
                'noo_shop_sidebar',
                'widgets_select',
                esc_html__('Shop Sidebar', 'noo-landmark'),
                ''
            );

            // Control: Shop Sidebar
            $helper->add_control(
                'noo_shop_sidebar_extension',
                'widgets_select',
                esc_html__('Shop Sidebar Extension', 'noo-landmark'),
                ''
            );

            // Control: Divider 1
            $helper->add_control('noo_shop_divider_1', 'divider', '');

            // Control: Heading Title
            $helper->add_control(
                'noo_shop_heading_title',
                'text',
                esc_html__('Shop Heading', 'noo-landmark'),
                esc_html__('Shop', 'noo-landmark')
            );

            // Control: Heading Image
            $helper->add_control(
                'noo_shop_heading_image',
                'noo_image',
                esc_html__('Heading Background Image', 'noo-landmark'),
                ''
            );

            $helper->add_control(
                'noo_shop_use_masonry_layout',
                'noo_radio',
                esc_html__('Shop Default View Style', 'noo-landmark'),
                'no',
                array(
                    'choices' => array(
                        'grid' => esc_html__('Grid', 'noo-landmark'),
                        'list' => esc_html__('List', 'noo-landmark'),
                    ),
                )
            );

            $helper->add_control(
                'noo_shop_use_masonry_layout',
                'noo_switch',
                esc_html__( 'Use Masonry Layout', 'noo-landmark' ),
                0,
                array(
                    'json' => array(
                        'off_child_options'   => 'noo_shop_default_layout',
                    )
                )
            );

            $helper->add_control(
                'noo_shop_default_layout',
                'noo_radio',
                esc_html__('Shop Default View Style', 'noo-landmark'),
                'grid',
                array(
                    'choices' => array(
                        'grid' => esc_html__('Grid', 'noo-landmark'),
                        'list' => esc_html__('List', 'noo-landmark'),
                    ),
                )
            );

            $helper->add_control(
                'noo_shop_grid_column',
                'ui_slider',
                esc_html__('Products Grid Columns', 'noo-landmark'),
                '4',
                array(
                    'json' => array(
                        'data_min'  => 1,
                        'data_max'  => 4,
                        'data_step' => 1
                    )
                )
            );

            // Control: Number of Product per Page
            $helper->add_control(
                'noo_shop_num',
                'ui_slider',
                esc_html__('Products Per Page', 'noo-landmark'),
                '12',
                array(
                    'json' => array(
                        'data_min'  => 4,
                        'data_max'  => 50,
                        'data_step' => 2
                    )
                )
            );

            // Sub-section: Single Product
            $helper->add_sub_section(
                'noo_woocommerce_sub_section_product',
                esc_html__('Single Product', 'noo-landmark')
            );

            // Control: Product Layout
            $helper->add_control(
                'noo_woocommerce_product_layout_same',
                'noo_radio',
                esc_html__('Single Product Layout', 'noo-landmark'),
                'same_as_shop',
                array(
                    'choices' => array(
                        'same_as_shop' => esc_html__('Same as Shop Layout', 'noo-landmark'),
                        'other_layout' => esc_html__('Select Layout', 'noo-landmark'),
                    ),
                    'json' => array(
                        'child_options' => array(
                            'fullwidth'    => '',
                            'other_layout' => 'noo_woocommerce_product_layout',
                        )
                    )
                )
            );

            $helper->add_control(
                'noo_woocommerce_product_layout',
                'noo_radio_image',
                esc_html__('Product Layout', 'noo-landmark'),
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
                            'sidebar'      => 'noo_woocommerce_product_sidebar',
                            'left_sidebar' => 'noo_woocommerce_product_sidebar',
                        )
                    )
                )
            );

            // Control: Product Sidebar
            $helper->add_control(
                'noo_woocommerce_product_sidebar',
                'widgets_select',
                esc_html__('Product Sidebar', 'noo-landmark'),
                ''
            );

            // config option
            $helper->add_control(
                'noo_woocommerce_single_header',
                'noo_radio',
                esc_html__( 'Using Header image', 'noo-landmark' ),
                '1',
                array(
                    'choices' => array(
                        '1'   => esc_html__( 'By Header Shop', 'noo-landmark' ),
                        '0'   => esc_html__( 'By Thumbnail', 'noo-landmark' ),
                    ),
                )
            );

            // Control: Products related
            $helper->add_control(
                'noo_woocommerce_product_related',
                'text',
                esc_html__('Related Products Count', 'noo-landmark'),
                '6'
            );

        }

        add_action('customize_register', 'noo_landmark_func_customizer_register_options_woocommerce');
    endif;
endif;

// 12. Custom Code
if (!function_exists('noo_landmark_func_customizer_register_options_custom_code')) :
    function noo_landmark_func_customizer_register_options_custom_code($wp_customize)
    {

        // declare helper object.
        $helper = new NOO_Customizer_Helper($wp_customize);

        // Section: Custom Code
        $helper->add_section(
            'noo_landmark_func_customizer_section_custom_code',
            esc_html__('Custom Code', 'noo-landmark'),
            esc_html__('In this section you can add custom JavaScript and CSS to your site.<br/>Your Google analytics tracking code should be added to Custom JavaScript field.', 'noo-landmark')
        );

        // Control: Custom JS (Google Analytics)
        $helper->add_control(
            'noo_custom_javascript',
            'textarea',
            esc_html__('Custom JavaScript', 'noo-landmark'),
            '',
            array('preview_type' => 'custom')
        );

        // Control: Custom CSS
        $helper->add_control(
            'noo_custom_css',
            'textarea',
            esc_html__('Custom CSS', 'noo-landmark'),
            '',
            array('preview_type' => 'custom')
        );
    }

    add_action('customize_register', 'noo_landmark_func_customizer_register_options_custom_code');
endif;

// 13. Import/Export Settings.
if (!function_exists('noo_landmark_func_customizer_register_options_tools')) :
    function noo_landmark_func_customizer_register_options_tools($wp_customize)
    {

        // declare helper object.
        $helper = new NOO_Customizer_Helper($wp_customize);

        // Section: Custom Code
        $helper->add_section(
            'noo_landmark_func_customizer_section_tools',
            esc_html__('Import/Export Settings', 'noo-landmark'),
            esc_html__('All themes from NooTheme share the same theme setting structure so you can export then import settings from one theme to another conveniently without any problem.', 'noo-landmark')
        );

        // Sub-section: Import Settings
        $helper->add_sub_section(
            'noo_tools_sub_section_import',
            esc_html__('Import Settings', 'noo-landmark'),
            noo_landmark_func_kses(__('Click Upload button then choose a JSON file (.json) from your computer to import settings to this theme.<br/>All the settings will be loaded for preview here and will not be saved until you click button "Save and Publish".', 'noo-landmark'))
        );

        // Control: Upload Settings
        $helper->add_control(
            'noo_tools_import',
            'import_settings',
            esc_html__('Upload', 'noo-landmark')
        );

        // Sub-section: Export Settings
        $helper->add_sub_section(
            'noo_tools_sub_section_export',
            esc_html__('Export Settings', 'noo-landmark'),
            noo_landmark_func_kses(__('Simply click Download button to export all your settings to a JSON file (.json).<br/>You then can use that file to restore theme settings to any theme of NooTheme.', 'noo-landmark'))
        );

        // Control: Download Settings
        $helper->add_control(
            'noo_tools_export',
            'export_settings',
            esc_html__('Download', 'noo-landmark')
        );

    }

    add_action('customize_register', 'noo_landmark_func_customizer_register_options_tools');
endif;

