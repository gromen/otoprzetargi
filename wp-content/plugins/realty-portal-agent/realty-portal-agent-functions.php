<?php
if ( ! function_exists( 'rp_agent_loop_start' ) ) {

	/**
	 * Output the start of a agent loop. By default this is a UL.
	 *
	 * @param bool $echo
	 *
	 * @return string
	 */
	function rp_agent_loop_start( $echo = true ) {
		ob_start();
		RP_Template::get_template( 'loop/agent-loop-start.php', '', '', RP_AGENT_TEMPLATES );
		if ( $echo ) {
			echo ob_get_clean();
		} else {
			return ob_get_clean();
		}
	}
}

if ( ! function_exists( 'rp_agent_loop_end' ) ) {

	/**
	 * Output the end of a agent loop. By default this is a UL.
	 *
	 * @param bool $echo
	 *
	 * @return string
	 */
	function rp_agent_loop_end( $echo = true ) {
		ob_start();
		RP_Template::get_template( 'loop/agent-loop-end.php', '', '', RP_AGENT_TEMPLATES );
		if ( $echo ) {
			echo ob_get_clean();
		} else {
			return ob_get_clean();
		}
	}
}

if ( ! function_exists( 'rp_agent_custom_field_default' ) ) :

	/**
	 * Set custom field default
	 *
	 * @package       Realty_Portal
	 * @author        NooTeam <suppport@nootheme.com>
	 * @version       1.0
	 */
	function rp_agent_custom_field_default() {

		$default_field = array();

		$default_field[ '' ] = array(
			'hide'     => true,
			'name'     => '',
			'label'    => '',
			'value'    => '',
			'type'     => '',
			'required' => '',
		);

		$default_field[ '_position' ] = array(
			'name'     => '_position',
			'label'    => esc_html__( 'Position', 'realty-portal-agent' ),
			'value'    => '',
			'type'     => 'text',
			'required' => '',
			'readonly' => true,
			'disable'  => false,
		);

		$default_field[ '_email' ] = array(
			'name'     => '_email',
			'label'    => esc_html__( 'Email', 'realty-portal-agent' ),
			'value'    => '',
			'type'     => 'text',
			'required' => '',
			'readonly' => true,
			'disable'  => false,
		);

		$default_field[ '_phone' ] = array(
			'name'     => '_phone',
			'label'    => esc_html__( 'Phone', 'realty-portal-agent' ),
			'value'    => '',
			'type'     => 'text',
			'required' => '',
			'readonly' => true,
			'disable'  => false,
		);

		$default_field[ '_mobile' ] = array(
			'name'     => '_mobile',
			'label'    => esc_html__( 'Mobile', 'realty-portal-agent' ),
			'value'    => '',
			'type'     => 'text',
			'required' => '',
			'readonly' => true,
			'disable'  => false,
		);

		$default_field[ '_skype' ] = array(
			'name'     => '_skype',
			'label'    => esc_html__( 'Skype', 'realty-portal-agent' ),
			'value'    => '',
			'type'     => 'text',
			'required' => '',
			'readonly' => true,
			'disable'  => false,
		);

		$default_field[ '_website' ] = array(
			'name'     => '_website',
			'label'    => esc_html__( 'Website', 'realty-portal-agent' ),
			'value'    => '',
			'type'     => 'text',
			'required' => '',
			'readonly' => true,
			'disable'  => false,
		);

		$default_field[ '_address' ] = array(
			'name'     => '_address',
			'label'    => esc_html__( 'Address', 'realty-portal-agent' ),
			'value'    => '',
			'type'     => 'text',
			'required' => '',
			'readonly' => true,
			'disable'  => false,
		);

		$default_field[ '_about' ] = array(
			'name'     => '_about',
			'label'    => esc_html__( 'About', 'realty-portal-agent' ),
			'value'    => '',
			'type'     => 'textarea',
			'required' => '',
			'readonly' => true,
			'disable'  => false,
		);

		return apply_filters( 'rp_agent_custom_field_default', $default_field );
	}

endif;

if ( ! function_exists( 'rp_agent_render_fields' ) ) :

	/**
	 * Render custom fields and Combine them with default fields
	 *
	 * @package       Realty_Portal
	 * @author        NooTeam <suppport@nootheme.com>
	 * @version       1.0
	 */
	function rp_agent_render_fields() {

		$default_field = rp_agent_custom_field_default();

		$custom_fields = Realty_Portal::get_setting( 'agent_custom_field', '', array() );

		$custom_fields = array_merge( $default_field, $custom_fields );

		$custom_fields = apply_filters( 'rp_agent_render_fields', $custom_fields );

		if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
			$wpml_prefix = empty( $wpml_prefix ) ? 'agent_custom_field_' : $wpml_prefix;
			foreach ( $custom_fields as $index => $custom_field ) {
				if ( ! is_array( $custom_field ) || ! isset( $custom_field[ 'name' ] ) ) {
					continue;
				}

				$custom_fields[ $index ][ 'label_translated' ] = isset( $custom_field[ 'label' ] ) ? apply_filters( 'wpml_translate_single_string', $custom_field[ 'label' ], 'RP Agent Custom Fields', $wpml_prefix . sanitize_title( $custom_field[ 'name' ] ), apply_filters( 'wpml_current_language', null ) ) : '';
			}
		}

		return $custom_fields;
	}

endif;

if ( ! function_exists( 'rp_social_agent_default' ) ) :

	/**
	 * Set default social
	 *
	 * @package       Realty_Portal
	 * @author        NooTeam <suppport@nootheme.com>
	 * @version       1.0
	 */
	function rp_social_agent_default() {

		$list_social_default = array(
			apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_facebook',
			apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_google_plus',
			apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_twitter',
			apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_pinterest',
			apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_vimeo',
			apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_youtube',
		);

		return apply_filters( 'rp_social_agent_default', $list_social_default );
	}

endif;

if ( ! function_exists( 'rp_agent_custom_social_agent' ) ) :

	/**
	 * Set custom social agent
	 *
	 * @package       Realty_Portal
	 * @author        NooTeam <suppport@nootheme.com>
	 * @version       1.0
	 */
	function rp_agent_custom_social_agent() {

		$list_social = array(
			array(
				'id'    => apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_facebook',
				'label' => esc_html__( 'Facebook', 'realty-portal-agent' ),
				'type'  => 'text',
				'icon'  => 'rp-icon-ion-email',
			),
			array(
				'id'    => apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_twitter',
				'label' => esc_html__( 'Twitter', 'realty-portal-agent' ),
				'type'  => 'text',
				'icon'  => 'rp-icon-ion-social-twitter',
			),
			array(
				'id'    => apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_instagram',
				'label' => esc_html__( 'Instagram', 'realty-portal-agent' ),
				'type'  => 'text',
				'icon'  => 'rp-icon-ion-social-instagram',
			),
			array(
				'id'    => apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_linkedin',
				'label' => esc_html__( 'LinkedIn', 'realty-portal-agent' ),
				'type'  => 'text',
				'icon'  => 'rp-icon-ion-social-linkedin',
			),
			array(
				'id'    => apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_google_plus',
				'label' => esc_html__( 'Google+', 'realty-portal-agent' ),
				'type'  => 'text',
				'icon'  => 'rp-icon-google-plus-square',
			),
			array(
				'id'    => apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_pinterest',
				'label' => esc_html__( 'Pinterest', 'realty-portal-agent' ),
				'type'  => 'text',
				'icon'  => 'rp-icon-pinterest-square',
			),
			array(
				'id'    => apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_youtube',
				'label' => esc_html__( 'Youtube', 'realty-portal-agent' ),
				'type'  => 'text',
				'icon'  => 'rp-icon-youtube-square',
			),
			array(
				'id'    => apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_tumblr',
				'label' => esc_html__( 'Tumblr', 'realty-portal-agent' ),
				'type'  => 'text',
				'icon'  => 'rp-icon-tumblr-square',
			),
			array(
				'id'    => apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_behance',
				'label' => esc_html__( 'Behance', 'realty-portal-agent' ),
				'type'  => 'text',
				'icon'  => 'rp-icon-behance-square',
			),
			array(
				'id'    => apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_flickr',
				'label' => esc_html__( 'Flickr', 'realty-portal-agent' ),
				'type'  => 'text',
				'icon'  => 'rp-icon-flickr',
			),
			array(
				'id'    => apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_vimeo',
				'label' => esc_html__( 'Vimeo', 'realty-portal-agent' ),
				'type'  => 'text',
				'icon'  => 'rp-icon-vimeo-square',
			),
			array(
				'id'    => apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_github',
				'label' => esc_html__( 'Github', 'realty-portal-agent' ),
				'type'  => 'text',
				'icon'  => 'rp-icon-github-square',
			),
			array(
				'id'    => apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_vk',
				'label' => esc_html__( 'VKontakte', 'realty-portal-agent' ),
				'type'  => 'text',
				'icon'  => 'rp-icon-vk',
			),
		);

		return apply_filters( 'rp_agent_custom_social_agent', $list_social );
	}

endif;

if ( ! function_exists( 'rp_get_list_social_agent' ) ) :

	/**
	 * Get list social agent
	 *
	 * @package       Realty_Portal
	 * @author        NooTeam <suppport@nootheme.com>
	 * @version       1.0
	 */
	function rp_get_list_social_agent() {

		$social_allow = Realty_Portal::get_setting( 'agent_custom_field', 'social_network', rp_social_agent_default() );
		$list_social  = rp_agent_custom_social_agent();

		foreach ( $list_social as $index => $item ) {
			if ( ! in_array( $item[ 'id' ], $social_allow ) ) {
				unset( $list_social[ $index ] );
			}
		}

		return $list_social;
	}

endif;

if ( ! function_exists( 'rp_metabox_add_field_agents' ) ) :
	function rp_metabox_add_field_agents( $post, $id, $type, $meta, $std, $field ) {
		switch ( $type ) {
			case 'agents':
				$value = $meta ? $meta : $std;
				$html  = array();
				echo '<select name="rp_meta_boxes[' . $id . ']" id="' . $id . '">';
				echo '<option value="none"' . selected( $value, 'none', false ) . '>' . esc_html__( '-- No Agent --', 'realty-portal' ) . '</option>';

				$args = array(
					'post_type'        => apply_filters( 'rp_agent_post_type', 'rp_agent' ),
					'posts_per_page'   => - 1,
					'post_status'      => 'publish',
					'suppress_filters' => 0,
				);

				$agents = get_posts( $args );
				if ( ! empty( $agents ) ) {
					foreach ( $agents as $agent ) {
						// $user_id = RP_Agent::id_user( $agent->ID );
						echo '<option value="' . $agent->ID . '"' . selected( $value, $agent->ID, false ) . '>' . $agent->post_title . '</option>';
					}
				}
				echo '</select>';

				break;
		}
	}
	add_action( 'rp_render_metabox_fields', 'rp_metabox_add_field_agents', 6, 10 );
endif;