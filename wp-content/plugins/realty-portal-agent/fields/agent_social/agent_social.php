<?php
/**
 * Field: Agent Social
 *
 * @package     Realty_Portal
 * @author      NooTeam <suppport@nootheme.com>
 * @version     1.0
 */
if ( ! function_exists( 'rp_render_agent_social_field' ) ) :

    function rp_render_agent_social_field( $field = array(), $value = null, $show_front_end = true ) {

		if ( empty( $field ) ) return false;

		$list_social = rp_get_list_social_agent();

		foreach ( $list_social as $social ) {
			$field['name']       = $social['id'];
			$field['title']      = $social['label'];
			$field['type']       = $social['type'];
			$field['class']      = $field['class_child'];
			
			$custom_fields_value = '';
			if ( !empty( $field['post_id'] ) ) {
				$custom_fields_value = get_post_meta( $field['post_id'], $field['name'], true );
			}
			rp_create_element( $field, $custom_fields_value );
		}

	}

    rp_add_custom_field_type( 'agent_social', __('Agent Social', 'realty-portal-agent'), array( 'form' => 'rp_render_agent_social_field' ), array( 'can_search' => false, 'is_system' => true )  );
endif;