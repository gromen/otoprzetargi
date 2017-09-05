<?php
/**
 * This function get Geocoding map
 *
 * @package       Realty_Portal
 * @author        NooTeam <suppport@nootheme.com>
 * @version       1.0
 */

if ( ! function_exists( 'rp_get_geocoding_map' ) ) :

	function rp_get_geocoding_map() {

		/**
		 * Check security
		 */
		check_ajax_referer( 'rp-google-map', 'security', esc_html__( 'Security Breach! Please contact admin!', 'realty-portal' ) );

		/**
		 * Process data
		 */
		if ( empty( $_POST[ 'lat' ] ) || empty( $_POST[ 'lng' ] ) ) {
			return;
		}

		$lat = floatval( $_POST[ 'lat' ] );
		$lng = floatval( $_POST[ 'lng' ] );

		$response = wp_remote_post( 'http://maps.googleapis.com/maps/api/geocode/json?latlng=' . $lat . ',' . $lng . '&sensor=false', array(
			'method'      => 'POST',
			'timeout'     => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => array(),
			'cookies'     => array(),
		) );

		if ( ! is_wp_error( $response ) ) {

			$data = json_decode( $response[ 'body' ], true );

			if ( 'OK' == $data[ 'status' ] ) {

				if ( ! empty( $data[ 'results' ][ 1 ] ) ) {

					$results = $data[ 'results' ][ 1 ][ 'address_components' ];

					$list_results = array();

					foreach ( $results as $item ) {

						if ( ! empty( $item[ 'types' ][ 0 ] ) ) {

							$type = esc_attr( $item[ 'types' ][ 0 ] );

							$list_results[ $type ] = esc_html( $item[ 'long_name' ] );
						}
					}

					$data_response[ 'status' ] = 'success';
					$data_response[ 'msg' ]    = esc_html__( 'Process data geocode success!', 'realty-portal' );
					$data_response[ 'data' ]   = $list_results;

					if ( ! empty( $data[ 'results' ][ 1 ][ 'formatted_address' ] ) ) {

						$data_response[ 'address' ] = esc_html( $data[ 'results' ][ 1 ][ 'formatted_address' ] );
					}
				}
			} else {
				$data_response[ 'status' ] = 'error';
				$data_response[ 'msg' ]    = esc_html__( 'Can\'t process data geocode!', 'realty-portal' );
			}

			wp_send_json( $data_response );
		}
	}

	add_action( 'wp_ajax_geocoding_map', 'rp_get_geocoding_map' );
	add_action( 'wp_ajax_nopriv_geocoding_map', 'rp_get_geocoding_map' );

endif;
