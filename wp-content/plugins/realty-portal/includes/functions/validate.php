<?php
/**
 * This field validate data
 *
 * @package     Realty_Portal
 * @author      NooTeam <suppport@nootheme.com>
 * @version     1.0
 */

if ( ! function_exists( 'rp_validate_data' ) ) :

	function rp_validate_data( $data, $type = '' ) {

		if ( empty( $data ) && $data != 0 ) {
			return false;
		}

		if ( ! is_array( $data ) ) {
			$data = wp_kses( trim( $data ), rp_allowed_html() );
		}

		$data = wp_kses( trim( $data ), rp_allowed_html() );

		switch ( $type ) {
			case 'int':
				$data = absint( $data );
				break;

			case 'strtolower':
				$data = str_replace( ' ', '_', strtolower( $data ) );
				break;

			case "datepicker" :
				$data = strtotime( $data );
				break;
		}

		return $data;
	}

endif;