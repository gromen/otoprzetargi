<?php
/**
 * Mail do not reply
 *
 * @package 	LandMark
 * @author 		KENT <tuanlv@vietbrain.com>
 * @version 	1.0
 */

if ( ! function_exists( 'noo_mail_do_not_reply' ) ) :
	
	function noo_mail_do_not_reply() {

		$sitename = strtolower( $_SERVER['SERVER_NAME'] );
        if ( substr( $sitename, 0, 4 ) === 'www.' ) {
            $sitename = substr( $sitename, 4 );
        }
        return apply_filters( 'noo_mail_do_not_reply', 'noreply@' . $sitename );

	}

endif;


/**
 * This function sent mail
 *
 * @package     LandMark
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */

if ( ! function_exists( 'noo_mail' ) ) :
    
    function noo_mail( $to = '', $subject = '', $body = '', $headers = '', $key = '', $attachments = '' ) {

        if( empty( $headers ) ) {
            $headers = array();
            $from_email = noo_mail_do_not_reply();

            if( empty( $from_name ) ) {
                if ( is_multisite() ) {
                    $from_name = $GLOBALS['current_site']->site_name;
                } else {
                    $from_name = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
                }
            }

            if( !empty( $from_name ) && !empty( $from_email ) ) {
                $headers[] = 'From: ' . $from_name . ' <' . strtolower( $from_email ) . '>';
            }
        }

        $headers   = apply_filters( $key . '_header', apply_filters( 'noo_mail_header', $headers ) );

        if( !empty( $key ) ) {
            $subject = apply_filters( $key . '_subject', apply_filters( 'noo_mail_subject', $subject ) );
            $body = apply_filters( $key . '_body', apply_filters( 'noo_mail_body', $body ) );
        }

        // RTL HTML email
        if( is_rtl() ) {
            $body = '<div dir="rtl">' . $body . '</div>';
        }
        
        add_filter( 'wp_mail_content_type', 'noo_mail_set_html_content' );

        $result = wp_mail( $to, $subject, $body, $headers, $attachments );

        // Reset content-type to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578
        remove_filter( 'wp_mail_content_type', 'noo_mail_set_html_content' );

        return $result;

    }

endif;

if ( ! function_exists( 'noo_mail_set_html_content' ) ) :

    function noo_mail_set_html_content() {
    
        return 'text/html';
    
    }
    
endif;