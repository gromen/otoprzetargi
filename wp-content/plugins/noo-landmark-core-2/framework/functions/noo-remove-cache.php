<?php
/**
 * Remove cache query when edit, publish post
 *
 * @package 	LandMark
 * @author 		KENT <tuanlv@vietbrain.com>
 * @version 	1.0
 */

if ( ! function_exists( 'noo_remove_cache_query' ) ) :
	
	function noo_remove_cache_query() {

		// delete_transient( 'noo_list_property_markers' );
		delete_transient( 'rp_get_list_data_autocomplete' );
		delete_transient( 'rp_advanced_search_fields_' );
		delete_transient( 'rp_advanced_search_fields_noo_property_bedrooms' );
		delete_transient( 'rp_advanced_search_fields_noo_property_bathrooms' );
		delete_transient( 'rp_advanced_search_fields_noo_property_garages' );
		delete_transient( 'rp_advanced_search_fields_noo_property_year_built' );
		delete_transient( 'rp_advanced_search_fields_noo_property_flooring' );

	}

	add_action( 'save_post', 'noo_remove_cache_query' );

endif;