<?php
/**
 * Template Name: Property
 *
 * @package Realty_Portal
 * @author  NooTeam <suppport@nootheme.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $agent;
if ( !is_object( $agent ) ) {
	return false;
}

$posts_per_page = apply_filters( 'rp_property_agent_number', 2 );

$args           = array(
	'posts_per_page' => $posts_per_page,
);
$args           = apply_filters( 'rp_after_agent_loop_properties_query', $args );
$query_property = new WP_Query( $agent->query_property( $args ) );

if ( $query_property->have_posts() ) {

	/**
	 * rp_before_agent_loop_properties hook.
	 *
	 * @hooked rp_agent_properties - 5
	 */
	do_action( 'rp_before_agent_loop_properties' );

	echo '<div class="rp-property-agent">';

	rp_property_loop_start();

	while ( $query_property->have_posts() ) {
		$query_property->the_post();
		RP_Template::get_template( 'property/content-property.php' );
	}

	rp_property_loop_end();
	if ( $query_property->max_num_pages > 1 ) {
		echo '<button class="loadmore-property-agent" data-agent-id="' . $agent->agent_info() . '" data-page-current="1" data-max-page="' . $query_property->max_num_pages . '" data-posts-per-page="' . $posts_per_page . '">' . esc_html__( 'Load More', 'realty-portal-agent' ) . '</button>';
	}

	echo '</div>';

	/**
	 * rp_after_agent_loop_properties hook.
	 *
	 */
	do_action( 'rp_after_agent_loop_properties', $query_property );

	wp_reset_postdata();
}