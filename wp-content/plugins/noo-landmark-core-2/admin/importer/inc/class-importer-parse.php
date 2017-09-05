<?php
/**
 * This class parse xml file and process it
 *
 * @package Noo_Landmark_Core/Importer
 * @author  KENT <tuanlv@vietbrain.com>
 */
if ( !class_exists( 'Noo_Importer_Parse' ) ) :

class Noo_Importer_Parse {

	public static function filters_data( $file ) {

		$response = wp_remote_get( $file );
		
		if( is_array( $response ) ) {
			$source_code = $response['body'];
			/**
			 * Get base site url
			 */
				preg_match( '|<wp:base_site_url>(.*?)</wp:base_site_url>|is', $source_code, $url );
				$base_url = $url[1];
				Noo_Importer_Helpers::update_log( $base_url, esc_html__( 'Get Base Site Url' ) );
				Noo_Importer_Helpers::update_json( array( $base_url ), Noo_Importer_Helpers::get_name_file( 'base_site_url_' ) );

			/**
			 * Get all category
			 */
				preg_match_all( '|<wp:category>(.*?)</wp:category>|is', $source_code, $list_category );
				if ( !empty( $list_category[1] ) ) {
					foreach ($list_category[1] as $category) {
						$categories[] = Noo_Importer_Parse::process_category( $category );
					}
					Noo_Importer_Helpers::update_log( sprintf( esc_html__( 'Number: %s' ), count( $categories ) ), esc_html__( 'Total Category' ) );
					Noo_Importer_Helpers::update_json( $categories, Noo_Importer_Helpers::get_name_file( 'categories_' ) );
				}

			/**
			 * Get all term
			 */
				preg_match_all( '|<wp:term>(.*?)</wp:term>|is', $source_code, $list_term );
				if ( !empty( $list_term[1] ) ) {
					foreach ($list_term[1] as $term) {
						$terms[] = Noo_Importer_Parse::process_term( $term );
					}
					Noo_Importer_Helpers::update_log( sprintf( esc_html__( 'Number: %s' ) , count( $terms ) ), esc_html__( 'Total Term' ) );
					Noo_Importer_Helpers::update_json( $terms, Noo_Importer_Helpers::get_name_file( 'terms_' ) );
				}

			/**
			 * Get all tags
			 */
				preg_match_all( '|<wp:tag>(.*?)</wp:tag>|is', $source_code, $list_tag );
				if ( !empty( $list_tag[1] ) ) {
					foreach ($list_tag[1] as $tag) {
						$tags[] = Noo_Importer_Parse::process_tag( $tag );
					}
					Noo_Importer_Helpers::update_log( sprintf( esc_html__( 'Number: %s' ) , count( $tags ) ), esc_html__( 'Total Tags' ) );
					Noo_Importer_Helpers::update_json( $tags, Noo_Importer_Helpers::get_name_file( 'tags_' ) );
				}

			/**
			 * Get all author
			 */
				preg_match_all( '|<wp:author>(.*?)</wp:author>|is', $source_code, $list_author );
				if ( !empty( $list_author[1] ) ) {
					foreach ( $list_author[1] as $author ) {
						$user_info = Noo_Importer_Parse::process_author( $author );
						$authors[$user_info['author_login']] = $user_info;
					}
					Noo_Importer_Helpers::update_log( sprintf( esc_html__( 'Number: %s' ) , count( $authors ) ), esc_html__( 'Total Author' ) );
					Noo_Importer_Helpers::update_json( $authors, Noo_Importer_Helpers::get_name_file( 'authors_' ) );
				}

			/**
			 * Get all item
			 */
				preg_match_all( '|<item>(.*?)</item>|is', $source_code, $list_post );
				if ( !empty( $list_post[1] ) ) {
					foreach ( $list_post[1] as $post ) {
						$posts[] = Noo_Importer_Parse::process_post( $post );
					}
					Noo_Importer_Helpers::update_log( sprintf( esc_html__( 'Number: %s' ) , count( $posts ) ), esc_html__( 'Total Posts' ) );
					Noo_Importer_Helpers::update_json( $posts, Noo_Importer_Helpers::get_name_file( 'posts_' ) );
				}

			/**
			 * Return data
			 */
			return array(
				'authors'    => $authors,
				'posts'      => $posts,
				'categories' => $categories,
				'tags'       => $tags,
				'terms'      => $terms,
				'base_url'   => $base_url
			);

		} else {
			wp_send_json_error();
		}

	}

	public static function get_tag( $string, $tag ) {
		preg_match( "|<$tag.*?>(.*?)</$tag>|is", $string, $return );
		if ( isset( $return[1] ) ) {
			if ( substr( $return[1], 0, 9 ) == '<![CDATA[' ) {
				if ( strpos( $return[1], ']]]]><![CDATA[>' ) !== false ) {
					preg_match_all( '|<!\[CDATA\[(.*?)\]\]>|s', $return[1], $matches );
					$return = '';
					foreach( $matches[1] as $match )
						$return .= $match;
				} else {
					$return = preg_replace( '|^<!\[CDATA\[(.*)\]\]>$|s', '$1', $return[1] );
				}
			} else {
				$return = $return[1];
			}
		} else {
			$return = '';
		}
		return $return;
	}

	public static function process_category( $c ) {
		return array(
			'term_id'              => Noo_Importer_Parse::get_tag( $c, 'wp:term_id' ),
			'cat_name'             => Noo_Importer_Parse::get_tag( $c, 'wp:cat_name' ),
			'category_nicename'    => Noo_Importer_Parse::get_tag( $c, 'wp:category_nicename' ),
			'category_parent'      => Noo_Importer_Parse::get_tag( $c, 'wp:category_parent' ),
			'category_description' => Noo_Importer_Parse::get_tag( $c, 'wp:category_description' ),
		);
	}

	public static function process_tag( $t ) {
		return array(
			'term_id'         => Noo_Importer_Parse::get_tag( $t, 'wp:term_id' ),
			'tag_name'        => Noo_Importer_Parse::get_tag( $t, 'wp:tag_name' ),
			'tag_slug'        => Noo_Importer_Parse::get_tag( $t, 'wp:tag_slug' ),
			'tag_description' => Noo_Importer_Parse::get_tag( $t, 'wp:tag_description' ),
		);
	}

	public static function process_term( $t ) {
		return array(
			'term_id'          => Noo_Importer_Parse::get_tag( $t, 'wp:term_id' ),
			'term_taxonomy'    => Noo_Importer_Parse::get_tag( $t, 'wp:term_taxonomy' ),
			'slug'             => Noo_Importer_Parse::get_tag( $t, 'wp:term_slug' ),
			'term_parent'      => Noo_Importer_Parse::get_tag( $t, 'wp:term_parent' ),
			'term_name'        => Noo_Importer_Parse::get_tag( $t, 'wp:term_name' ),
			'term_description' => Noo_Importer_Parse::get_tag( $t, 'wp:term_description' ),
		);
	}

	public static function process_author( $a ) {
		return array(
			'author_id'           => Noo_Importer_Parse::get_tag( $a, 'wp:author_id' ),
			'author_login'        => Noo_Importer_Parse::get_tag( $a, 'wp:author_login' ),
			'author_email'        => Noo_Importer_Parse::get_tag( $a, 'wp:author_email' ),
			'author_display_name' => Noo_Importer_Parse::get_tag( $a, 'wp:author_display_name' ),
			'author_first_name'   => Noo_Importer_Parse::get_tag( $a, 'wp:author_first_name' ),
			'author_last_name'    => Noo_Importer_Parse::get_tag( $a, 'wp:author_last_name' ),
		);
	}

	public static function process_post( $post ) {
		$post_id        = Noo_Importer_Parse::get_tag( $post, 'wp:post_id' );
		$post_title     = Noo_Importer_Parse::get_tag( $post, 'title' );
		$post_date      = Noo_Importer_Parse::get_tag( $post, 'wp:post_date' );
		$post_date_gmt  = Noo_Importer_Parse::get_tag( $post, 'wp:post_date_gmt' );
		$comment_status = Noo_Importer_Parse::get_tag( $post, 'wp:comment_status' );
		$ping_status    = Noo_Importer_Parse::get_tag( $post, 'wp:ping_status' );
		$status         = Noo_Importer_Parse::get_tag( $post, 'wp:status' );
		$post_name      = Noo_Importer_Parse::get_tag( $post, 'wp:post_name' );
		$post_parent    = Noo_Importer_Parse::get_tag( $post, 'wp:post_parent' );
		$menu_order     = Noo_Importer_Parse::get_tag( $post, 'wp:menu_order' );
		$post_type      = Noo_Importer_Parse::get_tag( $post, 'wp:post_type' );
		$post_password  = Noo_Importer_Parse::get_tag( $post, 'wp:post_password' );
		$is_sticky      = Noo_Importer_Parse::get_tag( $post, 'wp:is_sticky' );
		$guid           = Noo_Importer_Parse::get_tag( $post, 'guid' );
		$post_author    = Noo_Importer_Parse::get_tag( $post, 'dc:creator' );

		$post_excerpt = Noo_Importer_Parse::get_tag( $post, 'excerpt:encoded' );
		$post_excerpt = preg_replace_callback( '|<(/?[A-Z]+)|', array( 'Noo_Importer_Parse', '_normalize_tag' ), $post_excerpt );
		$post_excerpt = str_replace( '<br>', '<br />', $post_excerpt );
		$post_excerpt = str_replace( '<hr>', '<hr />', $post_excerpt );

		$post_content = Noo_Importer_Parse::get_tag( $post, 'content:encoded' );
		$post_content = preg_replace_callback( '|<(/?[A-Z]+)|', array( 'Noo_Importer_Parse', '_normalize_tag' ), $post_content );
		$post_content = str_replace( '<br>', '<br />', $post_content );
		$post_content = str_replace( '<hr>', '<hr />', $post_content );

		$postdata = compact( 
			'post_id',
			'post_author',
			'post_date',
			'post_date_gmt',
			'post_content',
			'post_excerpt',
			'post_title',
			'status',
			'post_name',
			'comment_status',
			'ping_status',
			'guid',
			'post_parent',
			'menu_order',
			'post_type',
			'post_password',
			'is_sticky'
		);

		$attachment_url = Noo_Importer_Parse::get_tag( $post, 'wp:attachment_url' );
		if ( $attachment_url )
			$postdata['attachment_url'] = $attachment_url;

		preg_match_all( '|<category domain="([^"]+?)" nicename="([^"]+?)">(.+?)</category>|is', $post, $terms, PREG_SET_ORDER );
		foreach ( $terms as $t ) {
			$post_terms[] = array(
				'slug'   => $t[2],
				'domain' => $t[1],
				'name'   => str_replace( array( '<![CDATA[', ']]>' ), '', $t[3] ),
			);
		}
		if ( ! empty( $post_terms ) ) {
			$postdata['terms'] = $post_terms;
		}

		preg_match_all( '|<wp:comment>(.+?)</wp:comment>|is', $post, $comments );
		$comments = $comments[1];
		if ( $comments ) {
			foreach ( $comments as $comment ) {
				preg_match_all( '|<wp:commentmeta>(.+?)</wp:commentmeta>|is', $comment, $commentmeta );
				$commentmeta = $commentmeta[1];
				$c_meta = array();
				foreach ( $commentmeta as $m ) {
					$c_meta[] = array(
						'key' => Noo_Importer_Parse::get_tag( $m, 'wp:meta_key' ),
						'value' => Noo_Importer_Parse::get_tag( $m, 'wp:meta_value' ),
					);
				}

				$post_comments[] = array(
					'comment_id'           => Noo_Importer_Parse::get_tag( $comment, 'wp:comment_id' ),
					'comment_author'       => Noo_Importer_Parse::get_tag( $comment, 'wp:comment_author' ),
					'comment_author_email' => Noo_Importer_Parse::get_tag( $comment, 'wp:comment_author_email' ),
					'comment_author_IP'    => Noo_Importer_Parse::get_tag( $comment, 'wp:comment_author_IP' ),
					'comment_author_url'   => Noo_Importer_Parse::get_tag( $comment, 'wp:comment_author_url' ),
					'comment_date'         => Noo_Importer_Parse::get_tag( $comment, 'wp:comment_date' ),
					'comment_date_gmt'     => Noo_Importer_Parse::get_tag( $comment, 'wp:comment_date_gmt' ),
					'comment_content'      => Noo_Importer_Parse::get_tag( $comment, 'wp:comment_content' ),
					'comment_approved'     => Noo_Importer_Parse::get_tag( $comment, 'wp:comment_approved' ),
					'comment_type'         => Noo_Importer_Parse::get_tag( $comment, 'wp:comment_type' ),
					'comment_parent'       => Noo_Importer_Parse::get_tag( $comment, 'wp:comment_parent' ),
					'comment_user_id'      => Noo_Importer_Parse::get_tag( $comment, 'wp:comment_user_id' ),
					'commentmeta'          => $c_meta,
				);
			}
		}
		if ( ! empty( $post_comments ) ) $postdata['comments'] = $post_comments;

		preg_match_all( '|<wp:postmeta>(.+?)</wp:postmeta>|is', $post, $postmeta );
		$postmeta = $postmeta[1];
		if ( $postmeta ) {
			foreach ( $postmeta as $p ) {
				$post_postmeta[] = array(
					'key'   => Noo_Importer_Parse::get_tag( $p, 'wp:meta_key' ),
					'value' => Noo_Importer_Parse::get_tag( $p, 'wp:meta_value' ),
				);
			}
		}
		if ( ! empty( $post_postmeta ) ) $postdata['postmeta'] = $post_postmeta;

		return $postdata;
	}

	public static function _normalize_tag( $matches ) {
		return '<' . strtolower( $matches[1] );
	}

}

endif;