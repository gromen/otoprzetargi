<?php
/**
 * Show content main shortcode agent list
 *
 * This template can be overridden by copying it to yourtheme/realty-portal/shortcode/agent-list.php.
 *
 * HOWEVER, on occasion NooTheme will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author        NooTheme
 * @version       0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

extract( $atts );

$args = array(
	'post_type'      => apply_filters( 'rp_agent_post_type', 'rp_agent' ),
	'post_status'    => 'publish', 
	'posts_per_page' => $posts_per_page,
);

if ( ! empty( $agent_category ) ) {

	$tax_query               = array();
	$tax_query[ 'relation' ] = 'AND';

	if ( ! empty( $agent_category ) ) {

		$tax_query[] = array(
			'taxonomy' => 'agent_category',
			'field'    => 'id',
			'terms'    => $agent_category,
		);
	}

	$args[ 'tax_query' ] = $tax_query;
}

if ( ! empty( $only_agent ) && $only_agent == 'yes' ) {
	query_posts( 'post_type=' . apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '&posts_per_page=-1' );
	$agent_ids = array();
	while ( have_posts() ) : the_post();
		$agent_id = get_the_ID();
		$user_id  = RP_Agent::get_id_user( $agent_id );
		if ( $user_id < 1 ) {
			continue;
		}
		if ( RP_Agent::total_property( $agent_id ) > 0 ) {
			$agent_ids[] = get_the_ID();
		}
	endwhile;
	wp_reset_query();
	$args[ 'post__in' ] = $agent_ids;
}

$wp_query = new WP_Query( RP_Query::order( $args, $orderby ) );
if ( $wp_query->have_posts() ) :

	if ( isset( $title ) && ! empty( $title ) ) {
		echo '<h3 class="title">' . esc_html( $title ) . '</h3>';
	}
	rp_agent_loop_start();
	while ( $wp_query->have_posts() ) : $wp_query->the_post();
		RP_Template::get_template( 'content-agent.php', compact( 'atts', $atts ), '', RP_AGENT_TEMPLATES );
	endwhile;
	rp_agent_loop_end();
	wp_reset_postdata();

else :

	RP_Template::get_template( 'loop/property-found.php' );

endif;