<?php
/**
 * Set value to price order
 *
 * @package Realty_Portal
 * @author  NooTeam <suppport@nootheme.com>
 * @version 1.0
 */
if ( ! function_exists( 'rp_add_custom_price' ) ) :

	function rp_add_custom_price( $cart_object ) {
	    
	    foreach ( $cart_object->cart_contents as $key => $value ) {

	        if ( isset ($value['package_order_id']) ) {
	        
		        if ( isset($value['package_order_id']) && is_numeric($value['package_order_id']) ) {
		            $value['data']->price = $value['package_order_id'];
		        }

		        if ( isset($value['package_recurring_payment']) && is_numeric($value['package_recurring_payment']) ) {
		            $value['data']->price = $value['package_recurring_payment'];
		        }

		        if ( isset($value['package_agent_id']) && is_numeric($value['package_agent_id']) ) {
		            $value['data']->price = $value['package_agent_id'];
		        }

		        if ( isset($value['package_agent_price']) && is_numeric($value['package_agent_price']) ) {
		            $value['data']->price = $value['package_agent_price'];
		        }

		    }

		    if ( isset ($value['prop_id']) ) :

		    	if ( isset($value['prop_id']) && is_numeric($value['prop_id']) ) {
		            $value['data']->price = $value['prop_id'];
		        }
		        if ( isset($value['price_property']) && is_numeric($value['price_property']) ) {
		            $value['data']->price = $value['price_property'];
		        }

		    endif;

	    }
	}

	add_action( 'woocommerce_before_calculate_totals', 'rp_add_custom_price' );

endif;

/**
 * Store the custom field
 * Call on function rp_buy_package
 *
 * @package Realty_Portal
 * @author  NooTeam <suppport@nootheme.com>
 * @version 1.0
 */
if ( ! function_exists( 'rp_add_cart_item_custom_data_vase' ) ) :
	
	function rp_add_cart_item_custom_data_vase( $cart_item_meta, $product_id ) {

		global $woocommerce;
		  	
	  	if ( isset ( $_POST['package_id'] ) ) {

			$cart_item_meta['package_order_id']          = isset( $_POST['package_id'] ) ? intval( $_POST['package_id'] ) : '';
			$cart_item_meta['package_recurring_payment'] = isset( $_POST['recurring_payment'] ) ? (bool)( $_POST['recurring_payment'] ) : false;
			$cart_item_meta['package_agent_id']          = isset( $_POST['agent_id'] ) ? intval( $_POST['agent_id'] ) : '';
			$cart_item_meta['package_agent_price']       = isset( $_POST['price'] ) ? intval( $_POST['price'] ) : '';

		}

		if ( isset ( $_POST['prop_id'] ) ) :

			$cart_item_meta['prop_id'] = isset( $_POST['prop_id'] ) ? intval( $_POST['prop_id'] ) : '';
			$cart_item_meta['price_property'] = isset( $_POST['price_property'] ) ? floatval( $_POST['price_property'] ) : '';

		endif;

		return $cart_item_meta;

	}

	add_filter( 'woocommerce_add_cart_item_data', 'rp_add_cart_item_custom_data_vase', 10, 2 );

endif;

/**
 * Get it from the session and add it to the cart variable
 * 
 * @package 	Realty_Portal
 * @author 		NooTeam <suppport@nootheme.com>
 * @version 	1.0
 */

if ( ! function_exists( 'rp_get_cart_items_from_session' ) ) :
	
	function rp_get_cart_items_from_session( $item, $values, $key ) {

		if ( isset( $values['package_order_id'] ) && $values['package_order_id'] ) {

		    if ( array_key_exists( 'package_order_id', $values ) ) {
		        $item[ 'package_order_id' ] = $values['package_order_id'];
		    }

		    if ( array_key_exists( 'package_recurring_payment', $values ) ) {
		        $item[ 'package_recurring_payment' ] = $values['package_recurring_payment'];
		    }

		    if ( array_key_exists( 'package_agent_id', $values ) ) {
		        $item[ 'package_agent_id' ] = $values['package_agent_id'];
		    }
			
			if ( array_key_exists( 'package_agent_price', $values ) ) {
		        $item[ 'package_agent_price' ] = $values['package_agent_price'];
			}

		}

		if ( isset( $values['prop_id'] ) && $values['prop_id'] ) :

			if ( array_key_exists( 'prop_id', $values ) )
		        $item[ 'prop_id' ] = $values['prop_id'];

		    if ( array_key_exists( 'price_property', $values ) )
		        $item[ 'price_property' ] = $values['price_property'];

		endif;
	    return $item;

	}

	add_filter( 'woocommerce_get_cart_item_from_session', 'rp_get_cart_items_from_session', 1, 3 );

endif;

/**
 * Add new field to form checkout
 *
 * @package 	Realty_Portal
 * @author 		NooTeam <suppport@nootheme.com>
 * @version 	1.0
 */

if ( ! function_exists( 'rp_add_field_checkout' ) ) :
	
	function rp_add_field_checkout( $fields ) {

		global $woocommerce;
		$fields['billing']['package_id']['default']                   = '';
		$fields['billing']['package_order_id']['default']             = '';
		// $fields['billing']['package_recurring_payment']['default'] = '';
		$fields['billing']['package_agent_id']['default']             = '';
		
		
		$fields['billing']['submisson_agent_id']['default']           = '';
		$fields['billing']['submisson_prop_id']['default']            = '';
		$fields['billing']['submisson_order_id']['default']           = '';
		$fields['billing']['submisson_payment_type']['default']       = '';

	    return $fields;

	}

	add_filter( 'woocommerce_checkout_fields' , 'rp_add_field_checkout' );

endif;

/**
 * This function update order meta when checkout
 * Auto create new order payment
 *
 * @package 	Realty_Portal
 * @author 		NooTeam <suppport@nootheme.com>
 * @version 	1.0
 */

if ( ! function_exists( 'rp_checkout_update_order_meta' ) ) :
	
	function rp_checkout_update_order_meta( $order_id ) {
		global $woocommerce, $post_type;

		$user_id	= get_current_user_id();
		$agent_id	= get_user_meta( $user_id, '_associated_agent_id', true );
		$agent		= get_post( $agent_id );
		
		foreach ( $woocommerce->cart->cart_contents as $cart_item_key => $cart_item ) {
			
			if ( !empty( $cart_item['package_order_id'] ) ) {
				
				$billing_type = $cart_item['package_recurring_payment'] ? 'recurring' : 'onetime';
				$package_id   = $cart_item['package_order_id'];
				$total_price  = floatval( get_post_meta( $package_id, apply_filters( 'rp_membership_post_type', 'rp_membership' ) . '_price', true ) );
				
				$package      = get_post( $package_id );
				
				if( !$agent || !$package ) {
					return false;
				}

				$title = apply_filters( 'rp_title_new_order_checkout', sprintf( esc_html__( '%s - Purchase package: %s' ), $agent->post_title, $package->post_title ) );
				
				/**
				 * Create order new
				 */
			   		$new_order_ID = RP_Payment::create_new_order( 'membership', $billing_type, $package_id, $total_price, $agent_id, $title );
			    
			    /**
			     * update order id in meta
			     */
				    update_post_meta( $order_id, '_billing_package_id', sanitize_text_field( $package_id ) );
				    update_post_meta( $order_id, '_billing_package_order_id', sanitize_text_field( $new_order_ID ) );
				    // update_post_meta( $order_id, '_billing_package_recurring_payment', sanitize_text_field( $new_order_ID ) );
				    update_post_meta( $order_id, '_billing_package_agent_id', sanitize_text_field( $agent_id ) );

			}

				if ( !empty( $cart_item['prop_id'] ) ) :

					$paid_listing    = (bool) esc_attr( get_post_meta( $cart_item['prop_id'], '_paid_listing', true ) );
					$submit_featured = isset( $_POST['submission_featured'] ) ? (bool) $_POST['submission_featured'] : false;
					$featured        = esc_attr( get_post_meta( $prop_id, '_featured', true ) ) == 'yes';
					$payment_type    = '';
					
					$is_featured     = $submit_featured && !$featured;
					$is_publish      = !$paid_listing;

					if( $is_publish && $is_featured ) {
						$payment_type        = 'both';
					} elseif( $is_publish ) {
						$payment_type		= 'listing';
					} elseif( $is_featured) {
						$payment_type		= 'featured';
					}

					$property	= get_post( $cart_item['prop_id'] );
					if( !$agent || !$property ) {
						return false;
					}

					$title = apply_filters( 'rp_title_payment_checkout', sprintf( esc_html__( '%s - Payment for %s' ), $agent->post_title, $package->post_title ) );

					// creat order new

				   		//( $agent_id, $prop_id,  $total_price, !$paid_listing, $submit_featured && !$featured );
				   		$new_order_ID = RP_Payment::create_new_order( $payment_type, '', $cart_item['prop_id'], floatval( $cart_item['price_property'] ), $agent_id, $title);
				    
				    // update order id in meta

					    update_post_meta( $order_id, '_billing_submisson_prop_id', sanitize_text_field( $cart_item['prop_id'] ) );
					    update_post_meta( $order_id, '_billing_submisson_order_id', sanitize_text_field( $new_order_ID ) );
					    update_post_meta( $order_id, '_billing_submisson_agent_id', sanitize_text_field( $agent_id ) );
					    update_post_meta( $order_id, '_billing_submisson_payment_type', sanitize_text_field( $payment_type ) );
					  

				endif;
		}

	}

	add_action( 'woocommerce_checkout_update_order_meta', 'rp_checkout_update_order_meta' );

endif;

/**
 * This function auto complete order when agent payment successfully
 *
 * @package 	Realty_Portal
 * @author 		KENt <suppport@nootheme.com>
 * @version 	1.0
 */

if ( ! function_exists( 'rp_auto_complete_order' ) ) :
	
	function rp_auto_complete_order( $order_id ) {

		global $woocommerce, $wpdb;
		    	
		$package_id = get_post_meta( $order_id, '_billing_package_id', true );
		$prop_id    = get_post_meta( $order_id, '_billing_submisson_prop_id', true );

    	if ( $package_id ) :

			$package_order_id = get_post_meta( $order_id, '_billing_package_order_id', true );
			$agent_id         = get_post_meta( $order_id, '_billing_package_agent_id', true );
     	
     	endif;

     	if ( $prop_id ) :

			$submisson_order_id     = get_post_meta( $order_id, '_billing_submisson_order_id', true );
			$submisson_agent_id     = get_post_meta( $order_id, '_billing_submisson_agent_id', true );
			$submisson_payment_type = get_post_meta( $order_id, '_billing_submisson_payment_type', true );

     	endif;
     	if ( !$order_id ) return;
    
    	$order = new WC_Order( $order_id );
    	if ( $package_order_id ) :
    		$purchase_date = time();

    			update_post_meta( $package_order_id, '_payment_status', 'completed' );
    			RP_Agent::set_agent_membership( $agent_id, $package_id, $purchase_date );

    		//exit($package_id);
    	endif;

    	if ( $submisson_order_id ) :

    		update_post_meta( $submisson_order_id, '_payment_status', 'completed' );
    		RP_Agent::set_order_status( $submisson_agent_id, $prop_id, $submisson_payment_type );

    	endif;
    	$order->update_status( 'completed' );

	}

	add_action( 'woocommerce_order_status_completed', 'rp_auto_complete_order' );

endif;

/**
 * This function process when agent refund order
 *
 * @package 	Realty_Portal
 * @author 		NooTeam <suppport@nootheme.com>
 * @version 	1.0
 */

if ( ! function_exists( 'rp_auto_refunded_order' ) ) :
	
	function rp_auto_refunded_order( $order_id ) {

		global $woocommerce, $wpdb;
		    	
		$package_id = get_post_meta( $order_id, '_billing_package_id', true );
		$prop_id    = get_post_meta( $order_id, '_billing_submisson_prop_id', true );

    	if ( $package_id ) :

			$package_order_id = get_post_meta( $order_id, '_billing_package_order_id', true );
			$agent_id         = get_post_meta( $order_id, '_billing_package_agent_id', true );
     	
     	endif;

     	if ( $prop_id ) :

     		$submisson_order_id = get_post_meta( $order_id, '_billing_submisson_order_id', true );
     		$submisson_agent_id = get_post_meta( $order_id, '_billing_submisson_agent_id', true );

     	endif;
     	if ( !$order_id ) return;
    
    	$order = new WC_Order( $order_id );
    	if ( $package_order_id ) :

			update_post_meta( $package_order_id, '_payment_status', 'pending' );
			RP_Agent::revoke_agent_membership( $agent_id, $package_id );
    		
    	endif;


		if ( $submisson_order_id ) :

			update_post_meta( $submisson_order_id, '_payment_status', 'pending' );
			RP_Agent::revoke_property_status($submisson_agent_id, $prop_id);

		endif;
    	$order->update_status( 'refunded' );

	}

	add_action( 'woocommerce_order_status_refunded', 'rp_auto_refunded_order' );

endif;