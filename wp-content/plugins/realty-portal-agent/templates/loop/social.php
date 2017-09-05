<?php
/**
 * Social
 *
 * @package Realty_Portal
 * @author  NooTeam <suppport@nootheme.com>
 */
global $agent;
if ( !is_object( $agent ) ) {
	return false;
}
foreach ( $agent->list_social_agent() as $field ) {
	if ( ! is_array( $field ) && empty( $field[ 'id' ] ) ) {
		continue;
	}
	$field_name = rp_clean( $field[ 'id' ] );
	$icon       = rp_clean( $field[ 'icon' ] );
	$value      = get_post_meta( $agent->ID, $field_name, true );
	if ( ! empty( $value ) ) {
		echo '<li class="' . esc_attr( $field_name ) . '">';
		switch ( $field[ 'id' ] ) {
			case apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_email':
				echo '<a href="mailto:' . esc_html( $value ) . '" target="_top"><i class="' . $icon . '"></i></a>';
				break;

			case apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_phone':
				echo '<a href="tel:' . absint( $value ) . '" target="_top"><i class="' . $icon . '"></i></a>';
				break;

			case apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_mobile':
				echo '<a href="tel:' . absint( $value ) . '" target="_top"><i class="' . $icon . '"></i></a>';
				break;

			case apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_website':
			case apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_facebook':
			case apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_twitter':
			case apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_google_plus':
			case apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_pinterest':
			case apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_youtube':
			case apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_vimeo':
				echo '<a href="' . esc_attr( $value ) . '" target="_top"><i class="' . $icon . '"></i></a>';
				break;

			default:
				echo esc_html( $value );
				break;
		}
		echo '</li>';
	}
}