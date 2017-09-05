<?php
/**
 * Initialize Theme functions for NOO Framework.
 * This file include the framework functions, it should remain intact between themes.
 * For theme specified functions, see file functions-<theme name>.php
 *
 * @package      NOO Framework
 * @version      1.0.0
 * @author       NooTheme
 * @license      http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link         https://nootheme.com
 */

// Initialize NOO Customizer
require_once NOO_FRAMEWORK . '/customizer/_init.php';

// Meta Boxes
require_once NOO_FRAMEWORK . '/meta-boxes/_init.php';

/**
 * Load all function
 */
foreach ( glob( NOO_FRAMEWORK . '/functions/*.php' ) as $filename ) {
	require $filename;
}

require_once NOO_FRAMEWORK . '/noo-gallery.php';
require_once NOO_FRAMEWORK . '/noo_testimonial.php';
require_once NOO_FRAMEWORK . '/3rd-party/loader.php';

foreach ( glob( NOO_FRAMEWORK . '/widgets/*.php' ) as $filename ) {
	require $filename;
}

// Enqueue style for admin
if ( ! function_exists( 'noo_enqueue_admin_assets' ) ) :
	function noo_enqueue_admin_assets() {

		wp_register_style( 'noo-admin-css', NOO_FRAMEWORK_URI . '/assets/css/noo-admin.css', null, null, 'all' );
		wp_enqueue_style( 'noo-admin-css' );

		wp_register_style( 'font-awesome', get_template_directory_uri() . '/assets/vendor/fontawesome/css/font-awesome.min.css', array(), '4.1.0' );
		wp_enqueue_style( 'font-awesome' );

		wp_register_style( 'noo-icon-bootstrap-modal-css', NOO_FRAMEWORK_URI . '/assets/css/noo-icon-bootstrap-modal.css', null, null, 'all' );
		wp_register_style( 'noo-jquery-ui-slider', NOO_FRAMEWORK_URI . '/assets/css/noo-jquery-ui.slider.css', null, '1.10.4', 'all' );
		wp_register_style( 'chosen-css', NOO_FRAMEWORK_URI . '/assets/css/noo-chosen.css', null, null, 'all' );

		wp_enqueue_style( 'chosen-css' );

		wp_register_style( 'alertify-core-css', NOO_FRAMEWORK_URI . '/assets/css/alertify.core.css', null, null, 'all' );
		wp_register_style( 'alertify-default-css', NOO_FRAMEWORK_URI . '/assets/css/alertify.default.css', array( 'alertify-core-css' ), null, 'all' );

		wp_register_style( 'datetimepicker', get_template_directory_uri() . '/assets/vendor/datetimepicker/jquery.datetimepicker.css', '2.4.0' );
		wp_register_script( 'datetimepicker', get_template_directory_uri() . '/assets/vendor/datetimepicker/jquery.datetimepicker.js', array( 'jquery' ), '2.4.0', true );

		// Main script
		wp_register_script( 'noo-admin-js', NOO_FRAMEWORK_URI . '/assets/js/noo-admin.js', array( 'jquery' ), null, true );
		wp_enqueue_script( 'noo-admin-js' );

		wp_register_script( 'noo-bootstrap-modal-js', NOO_FRAMEWORK_URI . '/assets/js/bootstrap-modal.js', array( 'jquery' ), '2.3.2', true );
		wp_register_script( 'noo-bootstrap-tab-js', NOO_FRAMEWORK_URI . '/assets/js/bootstrap-tab.js', array( 'jquery' ), '2.3.2', true );
		wp_register_script( 'noo-font-awesome-js', NOO_FRAMEWORK_URI . '/assets/js/font-awesome.js', array(
			'noo-bootstrap-modal-js',
			'noo-bootstrap-tab-js',
		), null, true );
		wp_register_script( 'chosen-js', NOO_FRAMEWORK_URI . '/assets/js/chosen.jquery.min.js', array( 'jquery' ), null, true );
		wp_register_script( 'fileDownload-js', NOO_FRAMEWORK_URI . '/assets/js/jquery.fileDownload.js', array( 'jquery' ), null, true );
		wp_register_script( 'alertify-js', NOO_FRAMEWORK_URI . '/assets/js/alertify.mod.min.js', null, null, true );
		wp_enqueue_script( 'chosen-js' );
	}

	add_action( 'admin_enqueue_scripts', 'noo_enqueue_admin_assets' );
endif;

if ( ! function_exists( 'noo_enqueue_frontend_assets' ) ):
	function noo_enqueue_frontend_assets() {
		wp_enqueue_script( 'noo-calculator', NOO_PLUGIN_ASSETS_URI . '/js/noo-calculator.js', array(), null, true );
	}

	add_action( 'wp_enqueue_scripts', 'noo_enqueue_frontend_assets' );
endif;