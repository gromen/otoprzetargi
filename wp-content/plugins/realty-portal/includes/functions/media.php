<?php
/**
 * Get thumb src
 *
 * @package     Realty_Portal
 * @author      NooTeam <suppport@nootheme.com>
 * @version     1.0
 */

if ( ! function_exists( 'rp_thumb_src' ) ) :

	function rp_thumb_src( $post_id = '', $size = 'thumbnail', $default = '80x80' ) {

		global $post;
		if ( empty( $post_id ) ) :
			$post_id = $post->ID;
		endif;

		if ( has_post_thumbnail( $post_id ) ) {

			$image_id  = get_post_thumbnail_id( $post_id );
			$image_url = wp_get_attachment_image_src( $image_id, $size );
			$image_url = $image_url[ 0 ];
		} else {

			$output = preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/is', $post->post_content, $matches );
			if ( $output ) {
				$image_url = $matches[ 1 ][ 0 ];
			}
		}

		if ( empty( $image_url ) ) {
			return REALTY_PORTAL_ASSETS . '/images/thumbnail-' . esc_attr( $default ) . '.png';
		}

		return $image_url;
	}

endif;

/**
 * Get thumb src by id
 *
 * @package     Realty_Portal
 * @author      NooTeam <suppport@nootheme.com>
 * @version     1.0
 */

if ( ! function_exists( 'rp_thumb_src_id' ) ) :

	function rp_thumb_src_id( $image_id = '', $size = 'rp-small', $default = '80x80' ) {

		$image_url = wp_get_attachment_image_src( $image_id, $size );

		if ( empty( $image_url[ 0 ] ) ) {
			$image_url = REALTY_PORTAL_ASSETS . '/images/thumbnail-' . esc_attr( $default ) . '.png';
		} else {
			$image_url = $image_url[ 0 ];
		}

		return $image_url;
	}

endif;

/**
 * This function find and remove all attachment image in post
 *
 * @package     Realty_Portal
 * @author      NooTeam <suppport@nootheme.com>
 * @version     1.0
 */

if ( ! function_exists( 'rp_remove_post_media' ) ) :

	function rp_remove_post_media( $post_id = '' ) {

		if ( empty( $post_id ) ) {
			return;
		}

		$attachments = get_posts( array(
			'post_type'      => 'attachment',
			'posts_per_page' => - 1,
			'post_status'    => 'any',
			'post_parent'    => $post_id,
		) );

		foreach ( $attachments as $attachment ) {
			if ( false === wp_delete_attachment( $attachment->ID ) ) {
				// Log failure to delete attachment.
			}
		}
	}

endif;
