<?php
/**
 * Show content main shortcode property list
 *
 * This template can be overridden by copying it to yourtheme/realty-portal/shortcode/property-list.php.
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
	'post_type'      => apply_filters( 'rp_property_post_type', 'rp_property' ),
	'post_status'    => 'publish',
	'posts_per_page' => $posts_per_page,
);

if ( ! empty( $listing_type ) || ! empty( $listing_offers ) ) {

	$tax_query               = array();
	$tax_query[ 'relation' ] = 'AND';

	if ( ! empty( $listing_offers ) ) {

		$tax_query[] = array(
			'taxonomy' => apply_filters( 'rp_property_listing_offers', 'listing_offers' ),
			'field'    => 'id',
			'terms'    => $listing_offers,
		);
	}

	if ( ! empty( $listing_type ) ) {

		$tax_query[] = array(
			'taxonomy' => apply_filters( 'rp_property_listing_type', 'listing_type' ),
			'field'    => 'id',
			'terms'    => $listing_type,
		);
	}

	$args[ 'tax_query' ] = $tax_query;
}

$wp_query = new WP_Query( RP_Query::order( $args, $orderby, $order ) );

if ( $wp_query->have_posts() ) :

	if ( isset( $title ) && ! empty( $title ) ) {
		echo '<h3 class="title">' . esc_html( $title ) . '</h3>';
	}

	rp_property_loop_start();

	while ( $wp_query->have_posts() ) : $wp_query->the_post();

		RP_Template::get_template( 'property/content-property.php' );

	endwhile;

	rp_property_loop_end();

	wp_reset_postdata();

else :

	RP_Template::get_template( 'loop/property-found.php' );

endif;