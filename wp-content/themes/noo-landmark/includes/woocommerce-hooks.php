<?php
/**
 * Noo Landmark Template Hooks
 *
 * Action/filter hooks used for WooCommerce functions/templates.
 *
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Remove Action
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_review_before_comment_meta', 'woocommerce_review_display_rating', 10 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
remove_action( 'woocommerce_review_meta', 'woocommerce_review_display_meta', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );

remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );

/**
 * Breadcrumb
 *
 * @see noo_landmark_func_woocommerce_breadcrumb_defaults()
 */
add_filter( 'woocommerce_breadcrumb_defaults', 'noo_landmark_func_woocommerce_breadcrumb_defaults' );


/**
 * @see noo_landmark_func_woocommerce_output_content_wrapper()
 * @see noo_landmark_func_woocommerce_output_content_wrapper_end()
 */
add_action( 'woocommerce_before_main_content', 'noo_landmark_func_woocommerce_output_content_wrapper', 10 );
add_action( 'woocommerce_after_main_content', 'noo_landmark_func_woocommerce_output_content_wrapper_end', 10 );


/**
 * @see noo_landmark_func_woocommerce_product_description_heading()
 * @see noo_landmark_func_woocommerce_product_additional_information_heading()
 * @see noo_landmark_func_woocommerce_product_review_comment_form_args()
 * @see noo_landmark_func_woocommerce_product_review_count()
 */
add_filter( 'woocommerce_product_description_heading', 'noo_landmark_func_woocommerce_product_description_heading' );
add_filter( 'woocommerce_product_additional_information_heading', 'noo_landmark_func_woocommerce_product_additional_information_heading' );
add_filter( 'woocommerce_product_review_comment_form_args', 'noo_landmark_func_woocommerce_product_review_comment_form_args' );
add_filter( 'woocommerce_product_get_review_count', 'noo_landmark_func_woocommerce_product_review_count', 11 );

/**
 * @see noo_landmark_func_woocommerce_product_tabs()
 */
add_filter( 'woocommerce_product_tabs', 'noo_landmark_func_woocommerce_product_tabs', 11 );

/**
 * @see noo_landmark_func_woocommerce_review_display_meta()
 */
add_action( 'woocommerce_review_meta', 'noo_landmark_func_woocommerce_review_display_meta', 10 );

/**
 * @see noo_landmark_func_woocommerce_template_before_single_price()
 * @see noo_landmark_func_woocommerce_template_after_single_price()
 * @see noo_landmark_func_woocommerce_template_single_price()
 * @see noo_landmark_func_woocommerce_template_single_rating()
 */
add_action( 'woocommerce_single_product_summary', 'noo_landmark_func_woocommerce_template_before_single_price', 9 );
add_action( 'woocommerce_single_product_summary', 'noo_landmark_func_woocommerce_template_after_single_price', 11 );
add_action( 'woocommerce_single_product_summary', 'noo_landmark_func_woocommerce_template_single_price', 11 );
add_action( 'woocommerce_single_product_summary', 'noo_landmark_func_woocommerce_template_single_rating', 8 );

/**
 * @see noo_landmark_func_woocommerce_show_option_variation()
 * @see noo_landmark_func_wc_print_before_notices()
 * @see noo_landmark_func_wc_print_after_notices()
 */
add_action( 'woocommerce_before_add_to_cart_button', 'noo_landmark_func_woocommerce_show_option_variation', 10 );
add_action( 'woocommerce_before_single_product', 'noo_landmark_func_wc_print_before_notices', 9 );
add_action( 'woocommerce_before_single_product', 'noo_landmark_func_wc_print_after_notices', 11 );

/**
 * @see noo_landmark_func_wc_add_yith_custom()
 */
add_action( 'woocommerce_after_single_variation', 'noo_landmark_func_wc_add_yith_custom', 11 );
add_action( 'woocommerce_after_add_to_cart_button', 'noo_landmark_func_wc_add_yith_custom', 10 );

/**
 * @see noo_landmark_func_alter_label_wishlist_browse()
 */
add_filter( 'yith-wcwl-browse-wishlist-label', 'noo_landmark_func_alter_label_wishlist_browse' );

/**
 * @see noo_landmark_func_wc_before_wrap_loop_item_action()
 * @see noo_landmark_func_wc_after_wrap_loop_item_action()
 */
add_action( 'woocommerce_after_shop_loop_item', 'noo_landmark_func_wc_before_wrap_loop_item_action', 9 );
add_action( 'woocommerce_after_shop_loop_item', 'noo_landmark_func_wc_after_wrap_loop_item_action', 21 );

/**
 * @see noo_landmark_func_woocommerce_get_price_html()
 */
add_filter( 'woocommerce_get_price_html', 'noo_landmark_func_woocommerce_get_price_html' );

/**
 * @see noo_landmark_func_wc_before_wrap_loop_item()
 * @see noo_landmark_func_wc_after_wrap_loop_item()
 */
add_action( 'woocommerce_before_shop_loop_item', 'noo_landmark_func_wc_before_wrap_loop_item', 8 );
add_action( 'woocommerce_after_shop_loop_item', 'noo_landmark_func_wc_after_wrap_loop_item', 30 );
add_action( 'woocommerce_before_subcategory', 'noo_landmark_func_wc_before_wrap_loop_item', 9 );
add_action( 'woocommerce_after_subcategory', 'noo_landmark_func_wc_after_wrap_loop_item', 11 );

/**
 * @see noo_landmark_func_woocommerce_get_price_html()
 */
add_filter( 'woocommerce_subcategory_count_html', 'noo_landmark_func_woocommerce_subcategory_count_html', 10, 2 );

/**
 * @see noo_landmark_func_wc_show_stock_in_loop()
 * @see noo_landmark_func_wc_show_featured_in_loop()
 * @see noo_landmark_func_wc_show_second_thumb()
 */
add_action( 'woocommerce_before_shop_loop_item_title', 'noo_landmark_func_wc_show_stock_in_loop', 8 );
add_action( 'woocommerce_before_shop_loop_item_title', 'noo_landmark_func_wc_show_featured_in_loop', 9 );

/**
 * @see noo_landmark_func_wc_after_wrap_loop_featured()
 * @see noo_landmark_func_wc_template_loop_info()
 * @see noo_landmark_func_wc_before_wrap_loop_featured()
 * @see noo_landmark_func_wc_show_loop_wishlist()
 * @see noo_landmark_func_wc_template_loop_add_to_cart()
 */
add_action( 'woocommerce_after_shop_loop_item', 'noo_landmark_func_wc_after_wrap_loop_featured', 21 );
add_action( 'woocommerce_after_shop_loop_item', 'noo_landmark_func_wc_template_loop_info', 21 );
add_action( 'woocommerce_before_shop_loop_item', 'noo_landmark_func_wc_before_wrap_loop_featured', 9 );
add_action( 'woocommerce_after_shop_loop_item', 'noo_landmark_func_wc_show_loop_wishlist', 20 );
add_action( 'woocommerce_after_shop_loop_item', 'noo_landmark_func_wc_template_loop_add_to_cart', 10 );

/**
 * @see noo_landmark_func_woocommerce_before_single_product_summary()
 */
add_action( 'woocommerce_before_single_product_summary', 'noo_landmark_func_woocommerce_before_single_product_summary', 9 );

/**
 * @see noo_landmark_func_woocommerce_after_single_product_summary()
 * @see noo_landmark_func_woocommerce_output_product_data_tabs()
 * @see noo_landmark_func_woocommerce_output_product_data_tabs_end()
 */
add_action( 'woocommerce_after_single_product_summary', 'noo_landmark_func_woocommerce_after_single_product_summary', 8 );
add_action( 'woocommerce_after_single_product_summary', 'noo_landmark_func_woocommerce_output_product_data_tabs', 9 );
add_action( 'woocommerce_after_single_product_summary', 'noo_landmark_func_woocommerce_output_product_data_tabs_end', 11 );

/**
 * @see noo_landmark_func_wc_before_wrap_upsell_display()
 * @see noo_landmark_func_wc_show_title_upsell_display()
 * @see noo_landmark_func_wc_before_container_related_products()
 * @see noo_landmark_func_wc_after_container_related_products()
 * @see noo_landmark_func_wc_after_wrap_upsell_display()
 */
add_action( 'woocommerce_after_single_product_summary', 'noo_landmark_func_wc_before_wrap_upsell_display', 12 );
add_action( 'woocommerce_after_single_product_summary', 'noo_landmark_func_wc_show_title_upsell_display', 12 );
add_action( 'woocommerce_after_single_product_summary', 'noo_landmark_func_wc_before_container_related_products', 12 );
add_action( 'woocommerce_after_single_product_summary', 'noo_landmark_func_wc_after_container_related_products', 16 );
add_action( 'woocommerce_after_single_product_summary', 'noo_landmark_func_wc_after_wrap_upsell_display', 16 );

/**
 * @see noo_landmark_func_wc_before_wrap_related_products()
 * @see noo_landmark_func_wc_show_title_related_products()
 * @see noo_landmark_func_wc_before_container_related_products()
 * @see noo_landmark_func_wc_after_container_related_products()
 * @see noo_landmark_func_wc_after_wrap_related_products()
 */
add_action( 'woocommerce_after_single_product_summary', 'noo_landmark_func_wc_before_wrap_related_products', 17 );
add_action( 'woocommerce_after_single_product_summary', 'noo_landmark_func_wc_show_title_related_products', 18 );
add_action( 'woocommerce_after_single_product_summary', 'noo_landmark_func_wc_before_container_related_products', 19 );
add_action( 'woocommerce_after_single_product_summary', 'noo_landmark_func_wc_after_container_related_products', 21 );
add_action( 'woocommerce_after_single_product_summary', 'noo_landmark_func_wc_after_wrap_related_products', 22 );

/**
 * @see noo_landmark_func_wc_output_related_products_args()
 */
add_filter( 'woocommerce_output_related_products_args', 'noo_landmark_func_wc_output_related_products_args' );

/**
 * @see woocommerce_template_single_meta()
 */
add_action( 'noo_woocommerce_after_other_images', 'woocommerce_template_single_meta', 5 );

/**
 * @see noo_landmark_func_wc_product_cat_class()
 * @see noo_landmark_func_wc_loop_shop_per_page()
 * @see noo_landmark_func_wc_loop_shop_columns()
 * @see noo_landmark_func_wc_related_products_columns()
 */
add_filter( 'product_cat_class', 'noo_landmark_func_wc_product_cat_class', 10, 3);
add_filter( 'loop_shop_per_page', 'noo_landmark_func_wc_loop_shop_per_page' );
add_filter( 'loop_shop_columns', 'noo_landmark_func_wc_loop_shop_columns' );
add_filter( 'woocommerce_related_products_columns', 'noo_landmark_func_wc_related_products_columns' );

/**
 * @see noo_landmark_func_wc_pagination_args()
 * @see noo_landmark_func_wc_show_page_title()
 */
add_filter( 'woocommerce_pagination_args', 'noo_landmark_func_wc_pagination_args' );
add_filter( 'woocommerce_show_page_title', 'noo_landmark_func_wc_show_page_title' );

/**
 * @see noo_landmark_func_wc_before_wrap_toolbar_products()
 * @see noo_landmark_func_wc_show_select_layout_products()
 * @see noo_landmark_func_wc_show_select_number_products()
 * @see noo_landmark_func_wc_after_wrap_toolbar_products()
 */
add_action( 'woocommerce_before_shop_loop', 'noo_landmark_func_wc_before_wrap_toolbar_products', 19 );
add_action( 'woocommerce_before_shop_loop', 'noo_landmark_func_wc_show_select_layout_products', 21 );
add_action( 'woocommerce_before_shop_loop', 'noo_landmark_func_wc_show_select_number_products', 31 );
add_action( 'woocommerce_before_shop_loop', 'noo_landmark_func_wc_after_wrap_toolbar_products', 32 );

/**
 * @see noo_landmark_func_wc_after_sidebar_shop()
 * @see noo_landmark_func_wc_cart_item_remove_link()
 */
add_action( 'noo_after_sidebar', 'noo_landmark_func_wc_after_sidebar_shop', 10 );
add_filter( 'woocommerce_cart_item_remove_link', 'noo_landmark_func_wc_cart_item_remove_link', 10, 2 );

/**
 * @see noo_landmark_func_wc_show_title_cart()
 * @see noo_landmark_func_wc_show_title_cart_totals()
 */
add_action( 'woocommerce_before_cart', 'noo_landmark_func_wc_show_title_cart');
add_action( 'woocommerce_before_cart_totals', 'noo_landmark_func_wc_show_title_cart_totals');

/**
 * @see noo_landmark_func_yith_wcwl_wishlist_title()
 */
add_filter( 'yith_wcwl_wishlist_title', 'noo_landmark_func_yith_wcwl_wishlist_title');

/**
 * @see noo_landmark_func_product_quick_view()
 */
add_action( 'wp_ajax_noo_landmark_func_product_quick_view', 'noo_landmark_func_product_quick_view' );
add_action( 'wp_ajax_nopriv_noo_landmark_func_product_quick_view', 'noo_landmark_func_product_quick_view' );

/**
 * @see noo_landmark_func_wc_before_mini_cart()
 * @see noo_landmark_func_wc_after_mini_cart()
 */
add_action( 'woocommerce_before_mini_cart', 'noo_landmark_func_wc_before_mini_cart' );
add_action( 'woocommerce_after_mini_cart', 'noo_landmark_func_wc_after_mini_cart' );

/**
 * @see noo_landmark_func_wc_widget_cart_item_quantity()
 * @see noo_landmark_func_wc_mini_cart_item_class()
 */
add_filter( 'woocommerce_widget_cart_item_quantity', 'noo_landmark_func_wc_widget_cart_item_quantity', 10, 3 );
add_filter( 'woocommerce_mini_cart_item_class', 'noo_landmark_func_wc_mini_cart_item_class', 10, 2 );
