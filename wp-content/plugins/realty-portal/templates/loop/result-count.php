<?php
/**
 * Result Count
 *
 * @author 		NooTeam
 * @package 	Realty_Portal/Templates
 * @version     0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $wp_query;

?>
<div class="property-result-count">
	<?php
	$paged    = max( 1, $wp_query->get( 'paged' ) );
	$per_page = $wp_query->get( 'posts_per_page' );
	$total    = $wp_query->found_posts;
	$first    = ( $per_page * $paged ) - $per_page + 1;
	$last     = min( $total, $wp_query->get( 'posts_per_page' ) * $paged );

	if ( $total <= $per_page || -1 === $per_page ) {
		printf( esc_html__( '%d Property(s) Found', 'realty-portal' ), $total );
	} else {
		printf( _nx( 'Showing the single result', 'Showing %1$d&ndash;%2$d of %3$d results', $total, '%1$d = first, %2$d = last, %3$d = total', 'realty-portal' ), $first, $last, $total );
	}
	?>
</div>