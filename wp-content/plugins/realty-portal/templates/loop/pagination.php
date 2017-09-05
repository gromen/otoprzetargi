<?php
/**
 * Pagination - Show numbered pagination for catalog pages
 *
 * @author        NooTeam
 * @package       Realty_Portal/Templates
 * @version       0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $wp_query;

$args  = ! empty( $args ) ? $args : array();
$query = ! empty( $query ) ? $query : null;

$max_num_pages = ! empty( $query->max_num_pages ) ? $query->max_num_pages : $wp_query->max_num_pages;
if ( $max_num_pages <= 1 ) {
	return false;
}
?>
<div class="pagination">
	<?php
	echo rp_pagination_loop( $args, $query );
	?>
</div>
