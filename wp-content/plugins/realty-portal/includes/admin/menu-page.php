<?php
/**
 * Register menu page
 */
/**
 * Register a custom menu page.
 */
if ( !function_exists( 'rp_menu_setting_page' ) ) :

function rp_menu_setting_page() {
    add_menu_page(
        __( 'Realty Portal', 'realty-portal' ),
        __( 'Realty Portal', 'realty-portal' ),
        null,
        'realty-portal-setting',
        null,
        'dashicons-hammer',
        61
    );

	add_submenu_page(
		'realty-portal-setting',
		esc_html__( 'Settings', 'realty-portal' ),
		esc_html__( 'Settings', 'realty-portal' ),
		'manage_options',
		'realty-portal-config',
		'rp_load_dashboard_template'
	);

	add_submenu_page(
		'realty-portal-setting',
		esc_html__( 'Custom Fields', 'realty-portal' ),
		esc_html__( 'Custom Fields', 'realty-portal' ),
		'manage_options',
		'property-custom-fields',
		'rp_load_page_custom_field_template'
	);

	add_submenu_page(
		'realty-portal-setting',
		esc_html__( 'Features & Amenities', 'realty-portal' ),
		esc_html__( 'Features & Amenities', 'realty-portal' ),
		'manage_options',
		'property-features-amenities',
		'rp_load_page_features_amenities_template'
	);

	add_submenu_page(
		'realty-portal-setting',
		esc_html__( 'Add-Ons', 'realty-portal' ),
		esc_html__( 'Add-Ons', 'realty-portal' ),
		'manage_options',
		'realty-portal-addon',
		'rp_load_page_addon_template'
	);

}
add_action( 'admin_menu', 'rp_menu_setting_page', 90 );

endif;

if ( !function_exists( 'rp_load_dashboard_template' ) ) :

	function rp_load_dashboard_template() {
		require 'dashboard.php';
	}

endif;

if ( !function_exists( 'rp_load_page_custom_field_template' ) ) :

	function rp_load_page_custom_field_template() {
		require 'custom-fields.php';
	}

endif;

if ( !function_exists( 'rp_load_page_features_amenities_template' ) ) :

	function rp_load_page_features_amenities_template() {
		require 'page/features-amenities.php';
	}

endif;

if ( !function_exists( 'rp_load_page_addon_template' ) ) :

	function rp_load_page_addon_template() {
		require 'addons/addon-display.php';
	}

endif;

function wporg_current_screen_example( $current_screen ) {
	if ( 'realty-portal_page_realty-portal-addon' == $current_screen->id ) {
		$current_screen->id = 'plugin-install';
	}
	return $current_screen;
}
add_action( 'current_screen', 'wporg_current_screen_example' );