<?php
/**
 * This function create product when payment type is WooCommerce
 *
 * @package 	Realty_Portal
 * @author 		NooTeam <suppport@nootheme.com>
 * @version 	1.0
 */

if ( ! function_exists( 'rp_create_product' ) ) :
	
	function rp_create_product( $name_product, $price ) {

		if ( empty( $name_product ) || ( empty( $price ) && $price != 0 ) ) return;

		$name_product = rp_validate_data( $name_product );
		$price        = rp_validate_data( $price );

		global $wpdb;
      	$post_count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts WHERE `post_title` = '$name_product' AND `post_type` = 'product' AND `post_status` = 'publish'" );
      	$id = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE `post_title` = '$name_product' AND `post_type` = 'product' AND `post_status` = 'publish'" );
	    if ( $post_count > 0 ) {
	    	
	    	$post = array(
				'ID'         => $id,
				'post_title' => $name_product,
				'post_type'  => "product",
		    );
			$post_id = wp_update_post( $post, esc_html__( 'Cant not update product', 'realty-portal-package' ) );

	    } else {

	    	$post = array(
				'post_author'  => 1,
				'post_content' => '',
				'post_status'  => "publish",
				'post_title'   => $name_product,
				'post_parent'  => '',
				'post_type'    => "product",
		    );
	    	$terms_slug = get_option( 'rp_donate_slug' );
	    	//$terms = get_term($terms_id, 'product_cat');
     		$post_id = wp_insert_post( $post, esc_html__( 'Cant not create product', 'realty-portal-package' ) );
     		wp_set_object_terms( $post_id, $terms_slug, 'product_cat' );
			wp_set_object_terms( $post_id, $terms_slug, 'product_type' );
			add_post_meta( $post_id, 'check_donate', 1);
			update_post_meta( $post_id, '_price', $price );
			update_post_meta( $post_id, '_stock_status', 'instock');
			update_post_meta( $post_id, '_virtual', 'yes');
			update_post_meta( $post_id, '_downloadable', 'yes');
			update_post_meta( $post_id, '_sku', "");
			update_post_meta( $post_id, '_product_attributes', array());
			update_post_meta( $post_id, '_sold_individually', "" );
			update_post_meta( $post_id, '_manage_stock', "no" );
			update_post_meta( $post_id, '_backorders', "no" );
			update_post_meta( $post_id, '_stock', "" );
     	
     	}

     	return $post_id;

	}

endif;