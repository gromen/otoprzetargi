<?php
/**
 * Get iframe video
 *
 * @package     Realty_Portal
 * @author      NooTeam <suppport@nootheme.com>
 * @version     1.0
 */
if ( ! function_exists( 'rp_get_video' ) ) :

	function rp_get_video( $video_url = '' ) {

		if ( ! empty( $video_url ) ) {

			$wp_embed = new WP_Embed();
			$protocol = is_ssl() ? 'https' : 'http';

			if ( ! is_ssl() ) {
				$video_url = str_replace( 'https://', 'http://', $video_url );
			}
			$video_output = $wp_embed->run_shortcode( '[embed width="660" height="371.25"]' . esc_url( $video_url ) . '[/embed]' );

			if ( $video_output == '<a href="' . $video_url . '">' . $video_url . '</a>' ) :
				$width      = '660';
				$height     = '371.25';
				$video_link = @parse_url( $video_url );
				if ( empty( $video_link[ 'host' ] ) ) {
					return false;
				}

				if ( $video_link[ 'host' ] == 'www.youtube.com' || $video_link[ 'host' ] == 'youtube.com' ) :
					parse_str( @parse_url( $video_url, PHP_URL_QUERY ), $my_array_of_vars );
					$video_output = '';
					if ( !empty( $my_array_of_vars[ 'v' ] ) ) :
						$video        = $my_array_of_vars[ 'v' ];
						$video_output = '<iframe width="' . $width . '" height="' . $height . '" src="' . $protocol . '://www.youtube.com/embed/' . $video . '?rel=0&wmode=opaque" frameborder="0" allowfullscreen></iframe>';
					endif;

				elseif ( $video_link[ 'host' ] == 'www.youtu.be' || $video_link[ 'host' ] == 'youtu.be' ) :
					$video        = substr( @parse_url( $video_url, PHP_URL_PATH ), 1 );
					$video_output = '<iframe width="' . $width . '" height="' . $height . '" src="' . $protocol . '://www.youtube.com/embed/' . $video . '?rel=0&wmode=opaque" frameborder="0" allowfullscreen></iframe>';

				elseif ( $video_link[ 'host' ] == 'www.vimeo.com' || $video_link[ 'host' ] == 'vimeo.com' ) :
					$video        = (int) substr( @parse_url( $video_url, PHP_URL_PATH ), 1 );
					$video_output = '<iframe src="' . $protocol . '://player.vimeo.com/video/' . $video . '?wmode=opaque" width="' . $width . '" height="' . $height . '" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';

				elseif ( $video_link[ 'host' ] == 'www.dailymotion.com' || $video_link[ 'host' ] == 'dailymotion.com' ) :
					$video        = substr( @parse_url( $video_url, PHP_URL_PATH ), 7 );
					$video_id     = strtok( $video, '_' );
					$video_output = '<iframe frameborder="0" width="' . $width . '" height="' . $height . '" src="' . $protocol . '://www.dailymotion.com/embed/video/' . $video_id . '"></iframe>';
				endif;

			endif;

			echo $video_output;
		}
	}

endif;