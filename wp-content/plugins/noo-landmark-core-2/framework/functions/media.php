<?php
/**
 * Get thumb src
 *
 * @package     LandMark
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */

if ( ! function_exists( 'noo_thumb_src' ) ) :
    
    function noo_thumb_src( $post_id = '', $size = 'noo-small', $default = '80x80' ) {

        global $post;
        if ( empty( $post_id ) ) :
            $post_id = $post->ID;
        endif;

        
        if ( has_post_thumbnail( $post_id ) ) {
            
            $image_id  = get_post_thumbnail_id( $post_id );  
            $image_url = wp_get_attachment_image_src( $image_id, $size ); 
            $image_url = $image_url[0];

        } else {
            
            $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/is', $post->post_content, $matches);
            if ( $output )
                $image_url = $matches[1][0];
        }

        if ( empty( $image_url ) ) return NOO_PLUGIN_ASSETS_URI . '/images/thumbnail-' . esc_attr( $default ) . '.png';

        return $image_url;

    }

endif;

/**
 * Get thumb src by id
 *
 * @package     LandMark
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */

if ( ! function_exists( 'noo_thumb_src_id' ) ) :
    
    function noo_thumb_src_id( $image_id = '', $size = 'noo-small', $default = '80x80' ) {

        global $post;
        $image_url = wp_get_attachment_image_src($image_id, $size );
        if ( empty( $image_url[0] ) ) {
            $image_url = NOO_PLUGIN_ASSETS_URI . '/images/thumbnail-' . esc_attr( $default ) . '.png';
        } else {
            $image_url = $image_url[0];
        }

        return $image_url;

    }

endif;

/**
 * This function find and remove all attachment image in post
 *
 * @package     LandMark
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */

if ( ! function_exists( 'noo_remove_post_media' ) ) :
    
    function noo_remove_post_media( $post_id = '' ) {

        if ( empty( $post_id ) ) return;

        $attachments = get_posts( array(
            'post_type'      => 'attachment',
            'posts_per_page' => -1,
            'post_status'    => 'any',
            'post_parent'    => $post_id
        ) );

        foreach ( $attachments as $attachment ) {
            if ( false === wp_delete_attachment( $attachment->ID ) ) {
                // Log failure to delete attachment.
            }
        }

    }

endif;
