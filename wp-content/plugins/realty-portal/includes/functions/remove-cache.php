<?php
/**
 * Remove cache query when edit, publish post
 *
 * @package       Realty_Portal
 * @author        NooTeam <suppport@nootheme.com>
 * @version       1.0
 */

if ( ! function_exists( 'rp_remove_cache_query' ) ) :

	function rp_remove_cache_query() {

		delete_transient( 'rp_get_list_data_autocomplete' );
		delete_transient( 'rp_advanced_search_fields_' );
		delete_transient( 'rp_advanced_search_fields_rp_property_bedrooms' );
		delete_transient( 'rp_advanced_search_fields_rp_property_bathrooms' );
		delete_transient( 'rp_advanced_search_fields_rp_property_garages' );
		delete_transient( 'rp_advanced_search_fields_rp_property_year_built' );
		delete_transient( 'rp_advanced_search_fields_rp_property_flooring' );
	}

	add_action( 'edit_post', 'rp_remove_cache_query' );
	add_action( 'save_post', 'rp_remove_cache_query' );

endif;