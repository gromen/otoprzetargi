<?php
if ( noo_landmark_is_plugin_active( 'realty-portal/realty-portal.php' ) && noo_landmark_is_plugin_active( 'js_composer/js_composer.php' ) ) {

	require_once get_template_directory() . '/includes/realty-portal/shortcode/map.php';

	require_once get_template_directory() . '/includes/realty-portal/shortcode/noo-agent.php';
	require_once get_template_directory() . '/includes/realty-portal/shortcode/noo-property-banner.php';
	require_once get_template_directory() . '/includes/realty-portal/shortcode/noo-map.php';
	require_once get_template_directory() . '/includes/realty-portal/shortcode/advanced-search-property.php';
	require_once get_template_directory() . '/includes/realty-portal/shortcode/noo-featured.php';
	require_once get_template_directory() . '/includes/realty-portal/shortcode/noo-pricing-table.php';
	require_once get_template_directory() . '/includes/realty-portal/shortcode/noo-property-detail.php';
	require_once get_template_directory() . '/includes/realty-portal/shortcode/noo-property-reviews.php';
	require_once get_template_directory() . '/includes/realty-portal/shortcode/noo-recent-property.php';
	require_once get_template_directory() . '/includes/realty-portal/shortcode/noo-single-agent.php';
	require_once get_template_directory() . '/includes/realty-portal/shortcode/noo-single-agent-contact.php';
	require_once get_template_directory() . '/includes/realty-portal/shortcode/noo-single-property-map.php';

}