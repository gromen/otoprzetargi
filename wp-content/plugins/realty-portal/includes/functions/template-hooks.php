<?php
/**
 * Property single.
 *
 * @see rp_gallery_image()
 * @see rp_metabox_property()
 * @see rp_box_additional_details()
 * @see rp_box_featured()
 * @see rp_box_document()
 * @see rp_box_address()
 * @see rp_box_video()
 * @see rp_box_location_on_map()
 * @see rp_box_comment_property()
 * @see rp_box_contact_property()
 * @see rp_metabox_field_meta()
 */
add_action( 'rp_before_single_property_summary', 'rp_metabox_property', 5 );
add_action( 'rp_before_single_property_summary', 'rp_gallery_image', 10 );
add_action( 'rp_before_single_property_summary', 'rp_metabox_field_meta', 15 );

add_action( 'rp_after_single_property_summary', 'rp_box_document', 5 );
add_action( 'rp_after_single_property_summary', 'rp_box_address', 10 );
add_action( 'rp_after_single_property_summary', 'rp_box_additional_details', 15 );
add_action( 'rp_after_single_property_summary', 'rp_box_featured', 20 );
add_action( 'rp_after_single_property_summary', 'rp_box_video', 25 );
add_action( 'rp_after_single_property_summary', 'rp_box_location_on_map', 35 );
add_action( 'rp_after_single_property_summary', 'rp_box_comment_property', 40 );

/**
 * Property Archive
 *
 * @see rp_property_result_count()
 */
add_action( 'rp_before_property_loop', 'rp_property_result_count', 10 );

add_action( 'rp_after_property_loop', 'rp_pagination', 10 );

/**
 * Property Search
 *
 * @see rp_property_result_count()
 * @see rp_save_search_property()
 *
 * @see rp_pagination()
 */
add_action( 'rp_before_property_search_loop', 'rp_property_result_count', 10 );

add_action( 'rp_before_property_search_loop', 'rp_save_search_property', 15 );

add_action( 'rp_after_property_search_loop', 'rp_pagination', 10 );

/**
 * Saved Search
 *
 * @see rp_message_notices - 5
 *
 * @see rp_saved_search_list_search - 15
 */
add_action( 'rp_error_main_saved_search', 'rp_message_notices', 5 );

add_action( 'rp_main_saved_search', 'rp_saved_search_main_page', 5 );

add_action( 'rp_before_main_page_saved_search', 'rp_saved_search_list_search', 15 );

/**
 * Single property
 */
add_action( 'rp_single_property_box_meta', 'rp_single_property_featured_item', 5 );
add_action( 'rp_single_property_box_meta', 'rp_single_property_share', 10 );
add_action( 'rp_single_property_box_meta', 'rp_single_property_print', 15 );
