<?php
// Incremental ID Counter for Templates
if ( ! function_exists( 'noo_vc_elements_id_increment' ) ) :
	function noo_vc_elements_id_increment() {
		static $count = 0;
		$count ++;

		return $count;
	}
endif;
// Function for handle element's visibility
if ( ! function_exists( 'noo_visibility_class' ) ) :
	function noo_visibility_class( $visibility = '' ) {
		switch ( $visibility ) {
			case 'hidden-phone':
				return ' hidden-xs';
			case 'hidden-tablet':
				return ' hidden-sm';
			case 'hidden-pc':
				return ' hidden-md hidden-lg';
			case 'visible-phone':
				return ' visible-xs';
			case 'visible-tablet':
				return ' visible-sm';
			case 'visible-pc':
				return ' visible-md visible-lg';
			default:
				return '';
		}
	}
endif;
if ( class_exists( 'WPBakeryVisualComposerAbstract' ) ):
	function nootheme_includevisual() {
		require_once NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/icon-picker.php';

		require_once NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/map/new_params.php';
		require_once NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/map/map.php';
		require_once NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/noo-blog-mansory.php';
		require_once NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/noo-blog-slider.php';
		require_once NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/noo-testimonial.php';
		require_once NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/noo-partner.php';
		require_once NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/noo-gallery.php';
		require_once NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/noo-mailchimp.php';
		require_once NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/noo-hotline.php';
		require_once NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/noo-service.php';
		require_once NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/noo-about-property.php';
		require_once NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/noo-floor-plan.php';
		require_once NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/noo-ads-banner.php';
		require_once NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/noo-progress.php';
		require_once NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/noo-about.php';
		require_once NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/noo-video.php';
		require_once NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/noo-faq.php';
		require_once NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/noo-information.php';

		// VC Templates
		$vc_templates_dir = NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/shortcodes/vc_templates/';
		vc_set_shortcodes_templates_dir( $vc_templates_dir );

	}

	add_action( 'init', 'nootheme_includevisual', 20 );
endif;