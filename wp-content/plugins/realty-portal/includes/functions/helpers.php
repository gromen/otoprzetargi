<?php
/**
 * This function allow support tag html
 *
 * @package     Realty_Portal
 * @author      NooTeam <suppport@nootheme.com>
 * @version     1.0
 */

if ( ! function_exists( 'rp_allowed_html' ) ) :

	function rp_allowed_html() {

		return apply_filters( 'rp_allowed_html', array(
			'a'          => array(
				'href'   => array(),
				'target' => array(),
				'title'  => array(),
				'rel'    => array(),
				'class'  => array(),
				'style'  => array(),
			),
			'img'        => array(
				'src'   => array(),
				'class' => array(),
				'style' => array(),
			),
			'h1'         => array(
				'class' => array(),
				'style' => array(),
			),
			'h2'         => array(
				'class' => array(),
				'style' => array(),
			),
			'h3'         => array(
				'class' => array(),
				'style' => array(),
			),
			'h4'         => array(
				'class' => array(),
				'style' => array(),
			),
			'h5'         => array(
				'class' => array(),
				'style' => array(),
			),
			'p'          => array(
				'class' => array(),
				'style' => array(),
			),
			'br'         => array(
				'class' => array(),
				'style' => array(),
			),
			'hr'         => array(
				'class' => array(),
				'style' => array(),
			),
			'span'       => array(
				'class' => array(),
				'style' => array(),
			),
			'em'         => array(
				'class' => array(),
				'style' => array(),
			),
			'strong'     => array(
				'class' => array(),
				'style' => array(),
			),
			'small'      => array(
				'class' => array(),
				'style' => array(),
			),
			'b'          => array(
				'class' => array(),
				'style' => array(),
			),
			'i'          => array(
				'class' => array(),
				'style' => array(),
			),
			'u'          => array(
				'class' => array(),
				'style' => array(),
			),
			'ul'         => array(
				'class' => array(),
				'style' => array(),
			),
			'ol'         => array(
				'class' => array(),
				'style' => array(),
			),
			'li'         => array(
				'class' => array(),
				'style' => array(),
			),
			'blockquote' => array(
				'class' => array(),
				'style' => array(),
			),
			'iframe'     => array(
				'width'           => array(),
				'width'           => array(),
				'src'             => array(),
				'class'           => array(),
				'frameborder'     => array(),
				'allowfullscreen' => array(),
			),
		) );
	}

endif;

if ( ! function_exists( 'rp_get_plugin_path' ) ) :

	function rp_get_plugin_path( $name ) {

		$plugin_path = untrailingslashit( REALTY_PORTAL . '/' . $name );

		return $plugin_path;
	}

endif;

/**
 * Get id user by post_id
 *
 * @package     Realty_Portal
 * @author      NooTeam <suppport@nootheme.com>
 */

if ( ! function_exists( 'rp_get_author_by_post_id' ) ) :

	function rp_get_author_by_post_id( $post_id = 0 ) {

		$post = get_post( $post_id );

		return $post->post_author;
	}

endif;

/**
 * Retrieve the current user object.
 *
 * @package     Realty_Portal
 * @author      NooTeam <suppport@nootheme.com>
 * @version     1.0
 */

if ( ! function_exists( 'rp_get_current_user' ) ) :

	function rp_get_current_user( $show_info = false, $name_field = 'id' ) {

		if ( ! function_exists( 'wp_get_current_user' ) ) {
			require( ABSPATH . WPINC . '/pluggable.php' );
			require( ABSPATH . WPINC . '/pluggable-deprecated.php' );
		}

		$info_current_user = wp_get_current_user();

		if ( empty( $info_current_user ) ) {
			return false;
		}

		if ( ! empty( $show_info ) ) {
			if ( $name_field === 'id' && isset( $info_current_user->ID ) ) {
				return $info_current_user->ID;
			}

			return false;
		}

		return $info_current_user;
	}

endif;

if ( ! function_exists( 'rp_wp_editor' ) ):
	function rp_wp_editor( $content, $editor_id, $configs = array() ) {
		$configs_default = array(
			'editor_class'  => 'rp-editor',
			'media_buttons' => false,
			'editor_height' => 380,
			'textarea_name' => $editor_id,
		);
		$configs         = wp_parse_args( $configs, $configs_default );

		$configs = apply_filters( 'rp_editor_config', $configs );

		return wp_editor( $content, 'rp-field-item-' . $editor_id, $configs );
	}
endif;

/**
 * Find id page by template
 *
 * @package     Realty_Portal
 * @author      NooTeam <suppport@nootheme.com>
 * @version     1.0
 */

if ( ! function_exists( 'rp_get_page_by_template' ) ) :

	function rp_get_page_by_template( $page_template = '' ) {

		$pages = get_pages( array(
			'meta_key'   => '_wp_page_template',
			'meta_value' => $page_template,
		) );

		if ( $pages ) {
			return $pages[ 0 ]->ID;
		}

		return false;
	}

endif;

/**
 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
 * Non-scalar values are ignored.
 *
 * @param string|array $var
 *
 * @return string|array
 */
if ( ! function_exists( 'rp_clean' ) ) :

	function rp_clean( $var ) {
		if ( is_array( $var ) ) {
			return array_map( 'rp_clean', $var );
		} else {
			return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
		}
	}

endif;

/**
 * Format content to display shortcodes.
 *
 * @param  string $raw_string
 *
 * @return string
 */
if ( ! function_exists( 'rp_format_content' ) ) :

	function rp_format_content( $raw_string ) {
		return apply_filters( 'rp_format_content', wpautop( do_shortcode( wp_kses_post( $raw_string ) ) ), $raw_string );
	}

endif;

/**
 * taxonomies()
 *
 * Helper function that returns the
 * taxonomies used in the framework.
 *
 * @return array
 */
if ( ! function_exists( 'rp_taxonomies' ) ) :

	function rp_taxonomies( $output = 'objects' ) {

		// Set framework taxonomies

		$taxonomies = array(
			'listing_offers' => get_taxonomy( 'listing_offers' ),
			'listing_type'   => get_taxonomy( apply_filters( 'rp_property_listing_type', 'listing_type' ) ),
		);

		if ( 'names' == $output ) {
			$taxonomies = array_keys( $taxonomies );
		}

		return apply_filters( 'rp_taxonomies', $taxonomies, $output );
	}

endif;

if ( ! function_exists( 'rp_select' ) ) :

    function rp_select( $name_select, $list_option, $value, $default = '' ) {

		if ( isset( $name_select ) && ! empty( $name_select ) && is_array( $list_option ) ) {
			$value = !empty( $value ) ? $value : $default;
		    echo '<select name="' . esc_attr( $name_select ) . '">';
			foreach ( $list_option as $key => $name ) {
				echo '<option value="' . esc_attr( $key ) . '"' . selected( $key, $value, false ) . '>' . esc_html( $name ) . '</option>';
			}
		    echo '</select>';
		}

    }

endif;

if ( ! function_exists( 'rp_conver_field_option' ) ) :

	function rp_conver_field_option( $key_value = '', $list = '' ) {
		$list_value = explode("\n", $list);
		$list_values = array();
		foreach ($list_value as $key) {
			$list_values[sanitize_title($key)] = $key;
		}
		if ( !empty( $list_values[$key_value] ) ) {
			return $list_values[$key_value];
		}
		return false;
	}

endif;