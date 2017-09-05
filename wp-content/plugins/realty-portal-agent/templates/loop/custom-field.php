<?php
/**
 * Agent Custom Field
 *
 * @package Realty_Portal
 * @author  NooTeam <suppport@nootheme.com>
 */
global $agent;
if ( !is_object( $agent ) ) {
	return false;
}
foreach ( $agent->agent_custom_field() as $field ) {
	if ( ! is_array( $field ) && empty( $field[ 'name' ] ) ) {
		continue;
	}
	$field_name = apply_filters( 'rp_agent_post_type', 'rp_agent' ) . $field[ 'name' ];
	$value      = get_post_meta( $agent->ID, $field_name, true );
	if ( ! empty( $value ) ) {
		echo '<li class="' . esc_attr( $field_name ) . '">';
		switch ( $field[ 'name' ] ) {
		    case '_email':
			    echo '<a href="mailto:' . esc_html( $value ) . '" target="_top">' . esc_html( $value ) . '</a>';
		        break;

			case '_phone':
				echo '<a href="tel:' . absint( $value ) . '" target="_top">' . esc_html( $value ) . '</a>';
				break;

			case '_mobile':
				echo '<a href="tel:' . absint( $value ) . '" target="_top">' . esc_html( $value ) . '</a>';
				break;

			case '_website':
				echo '<a href="' . esc_attr( $value ) . '" target="_top">' . esc_html( $value ) . '</a>';
				break;

			default:
				echo esc_html( $value );
				break;
		}
		echo '</li>';
	}
}