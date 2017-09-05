<?php
if ( ! function_exists( 'rp_name_markers' ) ) :

	/**
	 * Get file name markers
	 *
	 * @return string
	 */
	function rp_name_markers() {

		if ( function_exists( 'icl_translate' ) ) {
			$language = apply_filters( 'wpml_current_language', 'en' );
		} else {
			$language = '';
		}

		global $wp_filesystem;
		if ( empty( $wp_filesystem ) ) {
			require_once( ABSPATH . '/wp-admin/includes/file.php' );
			WP_Filesystem();
		}
		$markers_dir = rp_create_upload_dir( $wp_filesystem );

		return $markers_dir . '/markers' . ( ! empty( $language ) ? '-' . esc_attr( $language ) : '' ) . '.txt';
	}

endif;

if ( ! function_exists( 'rp_property_save_json' ) ) :

	/**
	 * Save data json
	 *
	 * @param $property_id
	 * @param $post
	 */
	function rp_property_save_json( $property_id, $post ) {

		if ( apply_filters( 'rp_property_post_type', 'rp_property' ) != $post->post_type ) {
			return;
		}

		global $wp_filesystem;
		if ( empty( $wp_filesystem ) ) {
			require_once( ABSPATH . '/wp-admin/includes/file.php' );
			WP_Filesystem();
		}

		$file = rp_name_markers();

		if ( ! $file || ! $wp_filesystem->put_contents( $file, rp_list_property_markers(), FS_CHMOD_FILE ) ) {
			wp_die( "error saving file!", '', array( 'response' => 403 ) );
		} else {
			set_theme_mod( 'rp_use_inline_css', false );
		}
	}

	add_action( 'save_post', 'rp_property_save_json', 10, 3 );

endif;

if ( ! function_exists( 'rp_get_list_markers' ) ) :

	/**
	 * Get list markers
	 *
	 * @return mixed|string|void
	 */
	function rp_get_list_markers() {
		$file = rp_name_markers();
		if ( file_exists( $file ) ) {
			return file_get_contents( $file );
		} else {
			return rp_list_property_markers();
		}
	}

endif;