<?php
/**
 * This field validate data
 *
 * @package     LandMark
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */

if ( ! function_exists( 'noo_validate_data' ) ) :
    
    function noo_validate_data( $data, $type = '' ) {

        if ( empty( $data ) && $data != 0 ) return;
        if ( !is_array( $data ) ) {
            $data = wp_kses( trim( $data ), noo_allowed_html() );
        }

        switch ($type) {
   //      	case "multiple_select":
			// case "radio" :
			// case "checkbox" :
			// 	if( is_array( $data ) ) {
			// 		foreach ($data as $k => $v) {
			// 			$v = wp_kses( $v, array() );
			// 			$data[$k] = $v;
			// 		}
			// 	} else {
			// 		$data = wp_kses( $data, array() );
			// 	}
			// 	break;
				
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

endif;