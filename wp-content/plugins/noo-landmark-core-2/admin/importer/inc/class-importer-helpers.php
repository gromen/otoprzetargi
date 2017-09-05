<?php
/**
 * Static functions used in the OCDI plugin.
 *
 * @package Noo_Landmark_Core/Importer
 */

/**
 * Class with static helper functions.
 */
if ( !class_exists( 'Noo_Importer_Helpers' ) ) :

class Noo_Importer_Helpers {

	/**
	 * Helper function: get content from an url.
	 */
	private static function get_content_from_url( $url, $file_name = 'Import file' ) {

		// Test if the URL to the file is defined.
		if ( empty( $url ) ) {
			return new WP_Error(
				'url_not_defined',
				sprintf(
					__( 'URL for %s%s%s file is not defined!', 'noo-landmark-core' ),
					'<strong>',
					$file_name,
					'</strong>'
				)
			);
		}

		// Get file content from the server.
		$response = wp_remote_get(
			$url,
			array( 'timeout' => apply_filters( 'noo_import/timeout_for_downloading_import_file', 20 ) )
		);

		if ( is_wp_error( $response ) || 200 !== $response['response']['code'] ) {

			// Collect the right format of error data (array or WP_Error).
			$response_error = self::get_error_from_response( $response );

			return new WP_Error(
				'file_fetching_error',
				sprintf(
					__( 'An error occurred while fetching %s%s%s file from the server!%sReason: %s - %s.', 'noo-landmark-core' ),
					'<strong>',
					$file_name,
					'</strong>',
					'<br>',
					$response_error['error_code'],
					$response_error['error_message']
				) . '<br>' .
				apply_filters( 'noo_import/message_after_file_fetching_error', '' )
			);
		}

		// Return content retrieved from the URL.
		return wp_remote_retrieve_body( $response );
	}

	/**
	 * Helper function: get the right format of response errors
	 */
	private static function get_error_from_response( $response ) {
		$response_error = array();

		if ( is_array( $response ) ) {
			$response_error['error_code']    = $response['response']['code'];
			$response_error['error_message'] = $response['response']['message'];
		}
		else {
			$response_error['error_code']    = $response->get_error_code();
			$response_error['error_message'] = $response->get_error_message();
		}

		return $response_error;
	}

	/**
	 * Create content to file json
	 */
	public static function update_json( $content, $name_file, $show_path = false, $file_path = '' ) {

		// Verify WP file-system credentials.
		$verified_credentials = self::check_wp_filesystem_credentials();

		if ( is_wp_error( $verified_credentials ) ) {
			return $verified_credentials;
		}

		// By this point, the $wp_filesystem global should be working, so let's use it to create a file.
		global $wp_filesystem;

		if ( empty( $file_path ) ) {
			$file_path = self::get_path( $name_file, '.json' );
		}

		if ( !empty( $show_path ) ) {
			return $file_path;
		}

		$existing_data = '';
		if ( file_exists( $file_path ) ) {
			$existing_data = $wp_filesystem->get_contents( $file_path );
		}

		$content = ( !empty( $content ) ) ? json_encode( $content ) : '';

		if ( ! $wp_filesystem->put_contents( $file_path, $content, FS_CHMOD_FILE ) ) {
			return new WP_Error(
				'failed_writing_file_to_server',
				sprintf(
					__( 'An error occurred while writing file to your server! Tried to write a file to: %s%s.', 'noo-landmark-core' ),
					'<br>',
					$file_path
				)
			);
		}

		return true;
	}

	/**
	 * Append content to the file.
	 */
	public static function update_log( $content, $separator_text = '', $file_path = '' ) {

		// Verify WP file-system credentials.
		$verified_credentials = self::check_wp_filesystem_credentials();

		if ( is_wp_error( $verified_credentials ) ) {
			return $verified_credentials;
		}

		// By this point, the $wp_filesystem global should be working, so let's use it to create a file.
		global $wp_filesystem;

		if ( empty( $file_path ) ) {
			$file_path = self::get_path();
		}

		$existing_data = '';
		if ( file_exists( $file_path ) ) {
			$existing_data = $wp_filesystem->get_contents( $file_path );
		}

		// Style separator.
		$separator = '===== ' . $separator_text . ' =====' . PHP_EOL;
		$time_log  = '+ ' . sprintf( esc_html__( 'Time: %s', 'noo-landmark-core' ), current_time( 'd-m-Y H:i:s' ) ) . PHP_EOL;
		$content   = ( !empty( $content ) ) ? '+ ' . $content . PHP_EOL : '';

		if ( ! $wp_filesystem->put_contents( $file_path, $existing_data . $separator . $time_log . $content . PHP_EOL, FS_CHMOD_FILE ) ) {
			return new WP_Error(
				'failed_writing_file_to_server',
				sprintf(
					__( 'An error occurred while writing file to your server! Tried to write a file to: %s%s.', 'noo-landmark-core' ),
					'<br>',
					$file_path
				)
			);
		}

		return true;
	}

	/**
	 * Get data from a file
	 */
	public static function get_contents( $file_path = '' ) {

		// Verify WP file-system credentials.
		$verified_credentials = self::check_wp_filesystem_credentials();

		if ( is_wp_error( $verified_credentials ) ) {
			return $verified_credentials;
		}

		// By this point, the $wp_filesystem global should be working, so let's use it to read a file.
		global $wp_filesystem;
		$data = $wp_filesystem->get_contents( $file_path );

		if ( ! $data ) {
			/**
			 * Add this message to log file.
			 */
			Noo_Importer_Helpers::update_log(
				sprintf(
					__( 'An error occurred while reading a file from your server! Tried reading file from path: %s%s.', 'noo-landmark-core' ),
					"\n",
					$file_path
				), 
				esc_html__( 'Failed reading file from server!', 'noo-landmark-core' )
			);

			return '';
		}

		// Return the file data.
		return $data;
	}

	/**
	 * Get log file path
	 */
	public static function get_path( $name_file = 'log_file_', $extension = '.txt' ) {

		$upload_dir   = wp_upload_dir();
		$upload_path  = apply_filters( 'noo_import/upload_file_path', trailingslashit( $upload_dir['path'] ) );
		$current_date = current_time( 'd-m-Y' );

		$log_path = $upload_path . apply_filters( 'noo_import/log_file_prefix', esc_attr( $name_file ) ) . $current_date . apply_filters( 'noo_import/log_file_suffix_and_file_extension', esc_attr( $extension ) );

		return $log_path;
	}

	/**
	 * Get name file url
	 */
	public static function get_name_file( $name, $show_date = false ) {
		$current_date = !empty( $show_date ) ? current_time( 'd-m-Y' ) : '';
		$name         = esc_attr( $name ) . $current_date;
		return apply_filters( 'noo_import/get_name_file', $name );
	}

	/**
	 * Helper function: check for WP file-system credentials needed for reading and writing to a file.
	 */
	private static function check_wp_filesystem_credentials() {
		// Check if the file-system method is 'direct', if not display an error.
		if ( !function_exists( 'get_filesystem_method' ) ) {
			require_once(ABSPATH . 'wp-admin/includes/file.php');
		}

		if ( ! ( 'direct' === get_filesystem_method() ) ) {
			return new WP_Error(
				'no_direct_file_access',
				sprintf(
					__( 'This WordPress page does not have %sdirect%s write file access. This plugin needs it in order to save the demo import xml file to the upload directory of your site. You can change this setting with these instructions: %s.', 'noo-landmark-core' ),
					'<strong>',
					'</strong>',
					'<a href="http://gregorcapuder.com/wordpress-how-to-set-direct-filesystem-method/" target="_blank">How to set <strong>direct</strong> filesystem method</a>'
				)
			);
		}

		if ( false === ( $creds = request_filesystem_credentials(site_url() . '/wp-admin/', '', false, false, array()) ) ) {
			return new WP_error(
				'filesystem_credentials_could_not_be_retrieved',
				__( 'An error occurred while retrieving reading/writing permissions to your server (could not retrieve WP filesystem credentials)!', 'noo-landmark-core' )
			);
		}

		// Now we have credentials, try to get the wp_filesystem running.
		if ( ! WP_Filesystem( $creds ) ) {
			return new WP_Error(
				'wrong_login_credentials',
				__( 'Your WordPress login credentials don\'t allow to use WP_Filesystem!', 'noo-landmark-core' )
			);
		}

		return true;
	}

	/**
	 * Helper function: validate data
	 */
	public static function validate_data( $data, $type = '' ) {

        if ( empty( $data ) ) return;

        $data = wp_kses( trim( $data ), noo_allowed_html() );

        switch ($type) {
				
        	case 'int':
        		$data = absint( $data );
        		break;

        	case 'strtolower':
        		$data = str_replace( ' ', '_', strtolower( $data ) );
        		break;

        	case "datepicker" :
				$data = strtotime( $data );
				break;
        	
        	default:
        		$data = $data;
        		break;
        }
        
        return $data;

    }

    /**
     * Helper function: unchuck array
     */
    public static function unchuck( $array = array() ) {
    	if( count( $array ) >= 2 ) {
    		return call_user_func_array( 'array_merge', $array );
    	}

    	return $array;
    }

}

endif;