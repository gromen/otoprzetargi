<?php
include(  dirname( __FILE__ ) . '/meta-boxes-functions.php' );
include(  dirname( __FILE__ ) . '/class-meta-boxes.php' );

if ( ! function_exists( 'rp_enqueue_meta_boxes_css' ) ) :
	function rp_enqueue_meta_boxes_css( $hook ) {

		if ( $hook != 'edit.php' && $hook != 'post.php' && $hook != 'post-new.php' ) {
			return;
		}

		wp_register_style( 'rp-meta-boxes', REALTY_PORTAL_ASSETS . '/css/meta-boxes.css', NULL, NULL, 'all' );
		wp_enqueue_style( 'rp-meta-boxes' );

	}
	add_action( 'admin_enqueue_scripts', 'rp_enqueue_meta_boxes_css' );
endif;