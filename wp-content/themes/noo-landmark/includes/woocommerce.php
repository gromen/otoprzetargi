<?php

// Wishlist
if ( ! function_exists( 'noo_landmark_func_woocommerce_wishlist_is_active' ) ) {

	/**
	 * Check yith-woocommerce-wishlist plugin is active
	 *
	 * @return boolean .TRUE is active
	 */
	function noo_landmark_func_woocommerce_wishlist_is_active() {
		$active_plugins = (array) get_option( 'active_plugins', array() );
		
		if ( is_multisite() )
			$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		
		return in_array( 'yith-woocommerce-wishlist/init.php', $active_plugins ) ||
			 array_key_exists( 'yith-woocommerce-wishlist/init.php', $active_plugins );
	}
}
if ( ! function_exists( 'noo_landmark_func_woocommerce_compare_is_active' ) ) {

	/**
	 * Check yith-woocommerce-compare plugin is active
	 *
	 * @return boolean .TRUE is active
	 */
	function noo_landmark_func_woocommerce_compare_is_active() {
		$active_plugins = (array) get_option( 'active_plugins', array() );
		
		if ( is_multisite() )
			$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		
		return in_array( 'yith-woocommerce-compare/init.php', $active_plugins ) ||
			 array_key_exists( 'yith-woocommerce-compare/init.php', $active_plugins );
	}
}

if ( class_exists( 'woocommerce' ) ) {

	if ( ! function_exists('noo_landmark_func_woocommerce_breadcrumb_defaults') ) {
		/**
		 * [noo_landmark_func_woocommerce_breadcrumb_defaults description]
		 * Filter parameter for display woocommerce breadcrumbs
		 */
		function noo_landmark_func_woocommerce_breadcrumb_defaults($args) {
			
			$args['delimiter']   = '<i class="icon ion-ios-arrow-forward"></i>';
			$args['wrap_before'] = '';
			$args['wrap_after']  = '';

			return $args;
		}
	}

	if ( ! function_exists('noo_landmark_func_woocommerce_output_content_wrapper') ) {
		/**
		 * Add output wrapper content by Theme
		 */
		function noo_landmark_func_woocommerce_output_content_wrapper() {
			?>

			<?php if ( is_product() ) : ?>
				<?php 
					$sidebar = noo_landmark_func_get_sidebar_id();
					if( ! empty( $sidebar ) ) :
				?>
				<div id="primary" class="content-area">
				    <main id="main" class="site-main noo-container">
				        <div class="noo-row">
				        	<div class="<?php noo_landmark_func_main_class(); ?>">
				<?php else : ?>
				<div id="primary" class="content-area">
				    <main id="main" class="site-main noo-container-fluid">
				        <div class="noo-row">
				        	<div class="noo-main noo-md-12">
				<?php endif; ?>
			<?php else : ?>
			<div id="primary" class="content-area">
			    <main id="main" class="site-main noo-container">
			        <div class="noo-row">
			        	<div class="<?php noo_landmark_func_main_class(); ?>">
			
			<?php endif;
		}

	}

	if ( ! function_exists('noo_landmark_func_woocommerce_output_content_wrapper_end') ) {
		/**
		 * Add output wrapper content by Theme
		 */
		function noo_landmark_func_woocommerce_output_content_wrapper_end() {
			?>
						</div>
						<?php woocommerce_get_sidebar(); ?>
			        </div>
			    </main><!-- .site-main -->
			</div><!-- .content-area -->
			<?php
		}
		

	}


	if ( ! function_exists('noo_landmark_func_woocommerce_before_single_product_summary') ) {
		function noo_landmark_func_woocommerce_before_single_product_summary() {

			$sidebar = noo_landmark_func_get_sidebar_id();
			if( ! empty( $sidebar ) ) :
				echo '<div>';
				echo '<div class="noo-single-product-header">';
			else :
				echo '<div class="noo-container">';
				echo '<div class="noo-single-product-header">';
			endif;
		}
		

	}

	if ( ! function_exists('noo_landmark_func_woocommerce_after_single_product_summary') ) {
		function noo_landmark_func_woocommerce_after_single_product_summary() {
			?>
				</div> <!-- .noo-single-product-header -->
			</div> <!-- .noo-container -->
			<?php
		}
		

	}

	if ( ! function_exists('noo_landmark_func_woocommerce_output_product_data_tabs') ) {
		function noo_landmark_func_woocommerce_output_product_data_tabs() {

			$sidebar = noo_landmark_func_get_sidebar_id();
			if( ! empty( $sidebar ) ) :
				echo '<div class="noo-single-product-content">';
				echo '<div class="">';
			else :
				echo '<div class="noo-single-product-content noo-row">';
				echo '<div class="noo-container">';
			endif;
		}
		

	}

	if ( ! function_exists('noo_landmark_func_woocommerce_output_product_data_tabs_end') ) {
		function noo_landmark_func_woocommerce_output_product_data_tabs_end() {
			?>
				</div> <!-- .noo-container -->
			</div> <!-- .noo-single-product-content -->
			<?php
		}
		

	}

	if ( ! function_exists('noo_landmark_func_woocommerce_product_description_heading') ) {
		function noo_landmark_func_woocommerce_product_description_heading($text) {
			$text = '';
			return $text;
		}
		

	}

	if ( ! function_exists('noo_landmark_func_woocommerce_product_additional_information_heading') ) {
		function noo_landmark_func_woocommerce_product_additional_information_heading($text) {
			$text = '';
			return $text;
		}
		

	}

	if ( ! function_exists('noo_landmark_func_woocommerce_product_review_comment_form_args') ) {
		function noo_landmark_func_woocommerce_product_review_comment_form_args($comment_form) {

			

			$commenter = wp_get_current_commenter();

			$comment_form['title_reply'] = have_comments() ? esc_html__( 'Add a review', 'noo-landmark' ) : sprintf( esc_html__( 'Be the first to review &ldquo;%s&rdquo;', 'noo-landmark' ), get_the_title() );

			$comment_form['fields']['author'] = '<div class="noo-row"><p class="comment-form-author noo-sm-6">' . '<label for="author">' . esc_html__( 'Your Name', 'noo-landmark' ) . ' <span class="required">*</span></label> ' .
										'<input id="author" class="form-control" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" aria-required="true" required /></p>';

			$comment_form['fields']['email'] = '<p class="comment-form-email noo-sm-6"><label for="email">' . esc_html__( 'Your Email', 'noo-landmark' ) . ' <span class="required">*</span></label> ' .
										'<input id="email" class="form-control" name="email" type="email" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" aria-required="true" required /></p></div><!--/.noo-row-->';

			$comment_form['submit_button'] = '<button type="submit" name="%1$s" id="%2$s" class="%3$s noo-button">%4$s</button>';

			return $comment_form;
		}
		

	}

	/**
	 * Show count review with 0 number before
	 */
	if ( ! function_exists('noo_landmark_func_woocommerce_product_tabs') ) {
		function noo_landmark_func_woocommerce_product_review_count($count) {
			if ( $count < 10 && $count != 0 )
				return '0' . $count;
			else
				return $count;
		}
		

	}

	/**
	 * Show count review with 0 number before tab
	 */
	if ( ! function_exists('noo_landmark_func_woocommerce_product_tabs') ) {
		function noo_landmark_func_woocommerce_product_tabs($tabs) {
			global $product;

			if ( $tabs && isset($tabs['reviews']) ) {
				if ( isset($tabs['reviews']['title']) ) {
					$tabs['reviews']['title'] = sprintf( esc_html__( 'Reviews (%s)', 'noo-landmark' ), $product->get_review_count() );
				}
			}
			return $tabs;
		}
		

	}

	
	
	if ( ! function_exists('noo_landmark_func_woocommerce_review_display_meta') ) {
		function noo_landmark_func_woocommerce_review_display_meta() {
			
			global $comment;
			$verified = wc_review_is_from_verified_owner( $comment->comment_ID );

			if ( '0' === $comment->comment_approved ) : ?>

				<p class="meta"><em><?php esc_attr_e( 'Your comment is awaiting approval', 'noo-landmark' ); ?></em></p>

			<?php else : ?>

				<div class="meta">
					<strong itemprop="author"><?php comment_author(); ?></strong> <?php

					if ( 'yes' === get_option( 'woocommerce_review_rating_verification_label' ) && $verified ) {
						echo '<em class="verified">(' . esc_attr__( 'verified owner', 'noo-landmark' ) . ')</em> ';
					}

					?>

					<?php woocommerce_review_display_rating(); ?>

					<time itemprop="datePublished" datetime="<?php echo get_comment_date( 'c' ); ?>"><?php echo get_comment_date( wc_date_format() ); ?></time>
				</div>

			<?php endif;

		}
		

	}

	

	/**
	 * Add wrapper for single price
	 */
	if ( ! function_exists('noo_landmark_func_woocommerce_template_before_single_price') ) {
		function noo_landmark_func_woocommerce_template_before_single_price() {
			echo '<div class="price_and_stock">';
		}
		

	}
	/**
	 * Add wrapper for single price
	 */
	if ( ! function_exists('noo_landmark_func_woocommerce_template_after_single_price') ) {
		function noo_landmark_func_woocommerce_template_after_single_price() {
			/**
			 * Show status available
			 */
			global $product;
			$status = $product->is_in_stock() ? esc_html__('In Stock', 'noo-landmark') : esc_html__('Out Of Stock', 'noo-landmark');
			echo '<div class="status-stock">' . esc_html__('Status: ', 'noo-landmark') . '<span class="' . ( $product->is_in_stock() ? '' : 'out' ) . '">' . $status . '</span>' . '</div>';
			echo '</div> <!-- /.price_and_stock -->';
		}
		

	}

	/**
	 * Add Hr after singe price
	 */
	if ( ! function_exists('noo_landmark_func_woocommerce_template_single_price') ) {
		function noo_landmark_func_woocommerce_template_single_price() {
			echo '<hr/>';
		}
		

	}

	

	if ( ! function_exists('noo_landmark_func_woocommerce_template_single_rating') ) {
		function noo_landmark_func_woocommerce_template_single_rating() {
			global $product;

			if ( get_option( 'woocommerce_enable_review_rating' ) === 'no' ) {
				return;
			}

			$rating_count = $product->get_rating_count();
			$review_count = $product->get_review_count();
			$average      = $product->get_average_rating();

			if ( $rating_count > 0 ) : ?>

				<div class="woocommerce-product-rating" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
					<div class="star-rating" title="<?php printf( esc_html__( 'Rated %s out of 5', 'noo-landmark' ), $average ); ?>">
						<span style="width:<?php echo ( ( $average / 5 ) * 100 ); ?>%">
							<strong itemprop="ratingValue" class="rating"><?php echo esc_html( $average ); ?></strong> <?php printf( esc_html__( 'out of %s5%s', 'noo-landmark' ), '<span itemprop="bestRating">', '</span>' ); ?>
							<?php printf( _n( 'based on %s customer rating', 'based on %s customer ratings', $rating_count, 'noo-landmark' ), '<span itemprop="ratingCount" class="rating">' . $rating_count . '</span>' ); ?>
						</span>
					</div>
					<?php if ( comments_open() ) : ?><a href="<?php echo get_the_permalink(); ?>#reviews" class="woocommerce-review-link" rel="nofollow"><?php printf( _n( '%s Review', '%s Reviews', (int) $review_count, 'noo-landmark' ), '<span itemprop="reviewCount" class="count">' . $review_count . '</span>' ); ?> / <?php echo esc_html__('Add Your Review', 'noo-landmark'); ?></a><?php endif ?>
				</div>

			<?php endif;

		}
		

	}

	/**
	 * Show option for variations
	 */
	if ( ! function_exists('noo_landmark_func_woocommerce_show_option_variation') ) {
		function noo_landmark_func_woocommerce_show_option_variation() {
			global $product;

			if ($product->is_type('variable')) :

				$attributes = $product->get_variation_attributes();
				?>
				<div class="noo_variations">
					<?php foreach ( $attributes as $attribute_name => $options ) : ?>

					<div class="pa_type <?php echo sanitize_title( $attribute_name ); ?>">
						<label><?php echo wc_attribute_label( $attribute_name ); ?></label>
						<div class="pa_item" data-attribute_name="<?php echo sanitize_title( $attribute_name ); ?>">
							<?php

								$selected = isset( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ? wc_clean( urldecode( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ) : $product->get_variation_default_attribute( $attribute_name );

								$values = wc_get_product_terms( $product->get_id(), $attribute_name, array( 'fields' =>  'all' ) );
								if( $values ) {
							        foreach ( $values as $term ) {

							        	if ( sanitize_title( $attribute_name ) == 'pa_color' ) {
							            	echo '<input '.checked( $selected, $term->slug, false ) .' type="checkbox" value="'.$term->slug.'" title="'.$term->name.'" style="background: '.strip_tags(term_description( $term->term_id, $term->taxonomy )).'" class="lights" />';

							        	} else {
							        		echo '<input '.checked( $selected, $term->slug, false ) .' type="checkbox" value="'.$term->slug.'" title="'.$term->name.'" />';
							        	}
							        }
								}
							?>
						</div>
					</div>
					<?php endforeach;?>

					<?php if ( $attributes ) : ?>
					<div class="pa_refresh">
						<a class="reset_variations" href="#"><i class="ion-refresh"></i></a>
					</div>
					<?php endif; ?>

				</div>

			<?php endif;
		}
		
	}

	/**
	 * Add class container
	 */
	if ( ! function_exists('noo_landmark_func_wc_print_before_notices') ) {
		function noo_landmark_func_wc_print_before_notices() {
			$sidebar = noo_landmark_func_get_sidebar_id();
			if( ! empty( $sidebar ) ) :
				echo '<div class="noo-wc-message">';
			else :
				echo '<div class="noo-container noo-wc-message">';
			endif;
		}
		
	}

	if ( ! function_exists('noo_landmark_func_wc_print_after_notices') ) {
		function noo_landmark_func_wc_print_after_notices() {
			echo '</div> <!-- /.noo-container -->';
		}
		
	}

	/**
	 * Yith for single product
	 */

	if ( ! function_exists('noo_landmark_func_wc_add_yith_custom') ) {
		function noo_landmark_func_wc_add_yith_custom() {

			if ( noo_landmark_func_woocommerce_wishlist_is_active() ) 
				echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
			if ( noo_landmark_func_woocommerce_compare_is_active() )
				echo do_shortcode( '[yith_compare_button]<span class="icon ion-arrow-swap"></span>[/yith_compare_button]' );

		}
		
	}

	if ( ! function_exists('noo_landmark_func_alter_label_wishlist_browse') ) {
		function noo_landmark_func_alter_label_wishlist_browse($label) {
			return '<span class="fa fa-heart"></span>';
		}
		
	}

	if ( ! function_exists('noo_landmark_func_wc_before_wrap_loop_item_action') ) {
		function noo_landmark_func_wc_before_wrap_loop_item_action() {
			echo '<div class="custom-action">';
		}
	}

	if ( ! function_exists('noo_landmark_func_wc_after_wrap_loop_item_action') ) {
		function noo_landmark_func_wc_after_wrap_loop_item_action() {
			echo '</div> <!-- /.custom-action -->';
		}
	}

	if ( ! function_exists('noo_landmark_func_woocommerce_get_price_html') ) {
		function noo_landmark_func_woocommerce_get_price_html($price) {
			return str_replace('&ndash;', '<span class="dash">&ndash;</span>', $price);
		}
	}

	if ( ! function_exists('noo_landmark_func_wc_before_wrap_loop_item') ) {
		function noo_landmark_func_wc_before_wrap_loop_item() {
			echo '<div class="noo-loop-item">';
		}
	}

	if ( ! function_exists('noo_landmark_func_wc_after_wrap_loop_item') ) {
		function noo_landmark_func_wc_after_wrap_loop_item() {
			echo '</div> <!-- /.noo-loop-item -->';
		}
	}

	if ( ! function_exists('noo_landmark_func_wc_show_featured_in_loop') ) {
		function noo_landmark_func_wc_show_featured_in_loop() {
			global $product;

			if ( $product->is_featured() ) {
				echo '<span class="isfeatured">' . esc_html__( 'Featured!', 'noo-landmark' ) . '</span>';
			}
		}
	}

	if ( ! function_exists('noo_landmark_func_wc_show_stock_in_loop') ) {
		function noo_landmark_func_wc_show_stock_in_loop() {
			global $product;

			if ( ! $product->is_in_stock() ) {
				echo '<span class="outofstocks">' . esc_html__( 'Out Of Stock!', 'noo-landmark' ) . '</span>';
			}
		}
	}

	if ( ! function_exists('noo_landmark_func_woocommerce_subcategory_count_html') ) {
		function noo_landmark_func_woocommerce_subcategory_count_html( $html_category, $category ) {
			$html_category = ' <mark class="count">' . $category->count . ' ' . esc_html__('Products', 'noo-landmark') . '</mark>';
			return $html_category;
		}
	}

	if ( ! function_exists('noo_landmark_func_wc_show_second_thumb') ) {
		function noo_landmark_func_wc_show_second_thumb() {
			global $product;
	        $attachment_ids = $product->get_gallery_attachment_ids();
	        if (isset($attachment_ids) && !empty($attachment_ids)) {
                echo wp_get_attachment_image(esc_attr($attachment_ids[0]), 'noo-thumbnail-product', false, array('class' => 'second-img'));
            }
		}
	}

	if ( ! function_exists('noo_landmark_func_wc_template_loop_info') ) {
		function noo_landmark_func_wc_template_loop_info() {

			echo '<a href="' . get_the_permalink() . '" class="woocommerce-LoopProduct-link noo-loop-info-item">';
				woocommerce_template_loop_product_title();
				woocommerce_template_loop_rating();
				woocommerce_template_loop_price();
			echo '</a>';

			echo '<div class="noo-loop-info-item">';

				echo '<a href="' . get_the_permalink() . '">';
				woocommerce_template_loop_product_title();
				echo '</a>';

				noo_landmark_func_woocommerce_template_single_rating();
				woocommerce_template_loop_price();
				echo '<hr/>';
				woocommerce_template_single_excerpt();
				echo '<div class="wrap-action">';
				woocommerce_template_loop_add_to_cart();
				noo_landmark_func_wc_show_loop_wishlist();
				echo '</div>';

			echo '</div>';
		}
	}

	if ( ! function_exists('noo_landmark_func_wc_before_wrap_loop_featured') ) {
		function noo_landmark_func_wc_before_wrap_loop_featured() {
			echo '<div class="noo-loop-featured-item">';
		}
	}

	if ( ! function_exists('noo_landmark_func_wc_after_wrap_loop_featured') ) {
		function noo_landmark_func_wc_after_wrap_loop_featured() {
			echo '</div> <!-- /.noo-loop-featured-item -->';
		}
	}

	if ( ! function_exists('noo_landmark_func_wc_show_loop_wishlist') ) {
		function noo_landmark_func_wc_show_loop_wishlist() {
			if ( noo_landmark_func_woocommerce_compare_is_active() )
				echo do_shortcode( '[yith_compare_button]<span class="icon ion-arrow-swap"></span>[/yith_compare_button]' );

			if ( noo_landmark_func_woocommerce_wishlist_is_active() ) 
				echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
			?>
			<a data-id="<?php the_ID(); ?>" class="noo-quick-view">
				<span class="icon ion-arrow-expand"></span>
			</a>
			<?php
		}
	}

	if ( ! function_exists('noo_landmark_func_wc_template_loop_add_to_cart') ) {
		function noo_landmark_func_wc_template_loop_add_to_cart($text) {
			global $product;
			$quantity = 1;
			$class = implode( ' ', array_filter( array(
					'button',
					'noo-button-cart',
					// 'product_type_' . $product->product_type,
					$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
					$product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : ''
			) ) );

			if ( $product->is_type('variable') ) {
				$span_class = '<span class="icon ion-android-options"></span>';
			} else {
				$span_class = '<span class="icon ion-android-cart"></span>';
			}

			echo sprintf( '<a rel="nofollow" href="%s" data-quantity="%s" data-product_id="%s" data-product_sku="%s" class="%s">%s</a>',
				esc_url( $product->add_to_cart_url() ),
				esc_attr( isset( $quantity ) ? $quantity : 1 ),
				esc_attr( $product->get_id() ),
				esc_attr( $product->get_sku() ),
				esc_attr( isset( $class ) ? $class : 'button' ),
				$span_class
			);
		}
	}

	if ( ! function_exists('noo_landmark_func_wc_show_title_related_products') ) {
		function noo_landmark_func_wc_show_title_related_products() {

			$title     = esc_html__('Related Products', 'noo-landmark');
			$sub_title = esc_html__('Lorem Ipsum is simply dummy text of the printing.', 'noo-landmark');
			
			$sidebar = noo_landmark_func_get_sidebar_id();
			if( ! empty( $sidebar ) ) {
				$class_wraptext = '';
			} else {
				$class_wraptext = 'noo-container';
			}
			noo_landmark_func_wc_title_html($title, $sub_title, $class_wraptext);
		}
	}

	if ( ! function_exists('noo_landmark_func_wc_show_title_upsell_display') ) {
		function noo_landmark_func_wc_show_title_upsell_display() {

			global $product;

			if ( ! $upsells = $product->get_upsell_ids() ) {
				return;
			}

			$title     = esc_html__('You may also like...', 'noo-landmark');
			$sub_title = esc_html__('Lorem Ipsum is simply dummy text of the printing.', 'noo-landmark');
			
			$sidebar = noo_landmark_func_get_sidebar_id();
			if( ! empty( $sidebar ) ) {
				$class_wraptext = '';
			} else {
				$class_wraptext = 'noo-container';
			}

			noo_landmark_func_wc_title_html($title, $sub_title, $class_wraptext);
		}
	}

	if ( ! function_exists('noo_landmark_func_wc_before_container_related_products') ) {
		function noo_landmark_func_wc_title_html($title = '', $sub_title = '', $class_wraptext = '') {

			?>
			<div class="<?php echo esc_attr($class_wraptext); ?> noo-theme-wraptext">
			    <div class="wrap-title">
			        <?php if ( !empty( $title ) ) : ?>
			            <div class="noo-theme-title-bg"></div>

			            <h3 class="noo-theme-title">
			                <?php
			                    $title = explode( ' ', $title );
			                    $title[0] = '<span class="first-word">' . esc_html( $title[0] ) . '</span>';
			                    $title = implode( ' ', $title );
			                ?>
			                <?php echo noo_landmark_func_html_content_filter( $title ); ?>
			            </h3>
			        <?php endif; ?>

			        <?php if ( !empty( $sub_title ) ) : ?>
			            <p class="noo-theme-sub-title">
			                <i class="icon-decotitle"></i>
			                <?php echo esc_html( $sub_title ); ?>
			            </p>
			        <?php endif; ?>
			    </div><!-- End /.wrap-title -->

			</div>
			<?php
		}
	}

	if ( ! function_exists('noo_landmark_func_wc_before_container_related_products') ) {
		function noo_landmark_func_wc_before_container_related_products() {

			$sidebar = noo_landmark_func_get_sidebar_id();
			if( ! empty( $sidebar ) ) {
				$column = 3;
				echo '<div>';
			} else {
				$column = 4;
				echo '<div class="noo-container">';
			}
				echo '<div class="noo-owlslider" ';
	            echo 'data-resItemDesktop="'.$column.'" ';
	            echo 'data-pagination="false" data-autoHeight="false" ';
	            echo 'data-autoHeight="false" ';
	            echo 'data-autoplay="false" ';
	            echo 'data-column="'.$column.'" ';
	            echo 'data-textPrev="<i class=\'ion-ios-arrow-left\'></i>" ';
	            echo 'data-textNext="<i class=\'ion-ios-arrow-right\'></i>" ';
	            echo '>';
		}
	}

	if ( ! function_exists('noo_landmark_func_wc_after_container_related_products') ) {
		function noo_landmark_func_wc_after_container_related_products() {
				echo '</div> <!-- /.noo-owlslider -->';
			echo '</div> <!-- /.noo-container -->';
		}
	}

	if ( ! function_exists('noo_landmark_func_wc_before_wrap_related_products') ) {
		function noo_landmark_func_wc_before_wrap_related_products() {
			wp_enqueue_style( 'carousel' );
        	wp_enqueue_script( 'carousel' );
			echo '<div class="noo-related-products">';
		}
	}

	if ( ! function_exists('noo_landmark_func_wc_after_wrap_related_products') ) {
		function noo_landmark_func_wc_after_wrap_related_products() {
			echo '</div> <!-- /.noo-related-products -->';
		}
	}

	if ( ! function_exists('noo_landmark_func_wc_output_related_products_args') ) {
		function noo_landmark_func_wc_output_related_products_args($args) {
			$args['posts_per_page'] = get_theme_mod('noo_woocommerce_product_related', 6);
			return $args;
		}
	}

	if ( ! function_exists('noo_landmark_func_wc_before_wrap_upsell_display') ) {
		function noo_landmark_func_wc_before_wrap_upsell_display() {

			global $product;

			if ( ! $upsells = $product->get_upsell_ids() ) {
				return;
			}

			wp_enqueue_style( 'carousel' );
        	wp_enqueue_script( 'carousel' );
			echo '<div class="noo-upsell-products">';
		}
	}

	if ( ! function_exists('noo_landmark_func_wc_after_wrap_upsell_display') ) {
		function noo_landmark_func_wc_after_wrap_upsell_display() {

			global $product;

			if ( ! $upsells = $product->get_upsell_ids() ) {
				return;
			}

			echo '</div> <!-- /.noo-upsell-products -->';
		}
	}


	if ( ! function_exists('noo_landmark_func_wc_get_layout_product_class') ) {
		function noo_landmark_func_wc_get_layout_product_class() {
			$grid_column = get_theme_mod( 'noo_shop_grid_column', 4 );

			if ( is_product() ) {
				
				$sidebar = noo_landmark_func_get_sidebar_id();
				if( ! empty( $sidebar ) ) {
					$grid_column = 3;
				} else {
					$grid_column = 4;
				}
			}

			return 'noo-product noo-xs-12 noo-sm-6 noo-md-' . absint( ( 12 / $grid_column ) );
		}
	}

	if ( ! function_exists('noo_landmark_func_wc_loop_shop_columns') ) {
		function noo_landmark_func_wc_loop_shop_columns() {
			return get_theme_mod( 'noo_shop_grid_column', 4 );
		}
	}

	if ( ! function_exists('noo_landmark_func_wc_related_products_columns') ) {
		function noo_landmark_func_wc_related_products_columns() {

			$sidebar = noo_landmark_func_get_sidebar_id();
			if( ! empty( $sidebar ) ) :
				return 3;
			else :
				return 4;
			endif;
		}
	}

	if ( ! function_exists('noo_landmark_func_wc_product_post_class') ) {
		function noo_landmark_func_wc_product_post_class() {

			global $product;

			$classes = get_post_class();
			$classes[] = noo_landmark_func_wc_get_layout_product_class();

			if ( ! $product->is_in_stock() ) {
				$classes[] = 'outofstock';
			}

			if ( false !== ( $key = array_search( 'product', $classes ) ) ) {
				unset( $classes[ $key ] );
			}
			echo 'class="' . join( ' ', $classes ) . '"';
		}
	}

	if ( ! function_exists('noo_landmark_func_wc_product_cat_class') ) {
		function noo_landmark_func_wc_product_cat_class( $classes, $class, $category ) {
			
			$classes[] = noo_landmark_func_wc_get_layout_product_class();

			if ( false !== ( $key = array_search( 'product', $classes ) ) ) {
				unset( $classes[ $key ] );
			}

			return $classes;
		}
	}

	if ( ! function_exists('noo_landmark_func_wc_loop_shop_per_page') ) {
		function noo_landmark_func_wc_loop_shop_per_page() {
			
			if (isset($_GET['product_per']) && !empty($_GET['product_per'])) {
	            return $_GET['product_per'];
	        } else {
	            return get_theme_mod( 'noo_shop_num', 12 );
	        }
		}
	}

	if ( ! function_exists('noo_landmark_func_wc_pagination_args') ) {
		function noo_landmark_func_wc_pagination_args($args) {

			$args['type']      = 'plain';
			$args['prev_text'] = '';
			$args['next_text'] = '';

			return $args;
		}
	}

	if ( ! function_exists('noo_landmark_func_wc_show_page_title') ) {
		function noo_landmark_func_wc_show_page_title() {
			return false;
		}
	}

	if ( ! function_exists('noo_landmark_func_wc_before_wrap_toolbar_products') ) {
		function noo_landmark_func_wc_before_wrap_toolbar_products() {
			echo '<div class="noo-toolbar-products">';
		}
	}

	if ( ! function_exists('noo_landmark_func_wc_after_wrap_toolbar_products') ) {
		function noo_landmark_func_wc_after_wrap_toolbar_products() {
			echo '</div> <!-- /.noo-toolbar-products -->';
		}
	}

	if ( ! function_exists('noo_landmark_func_wc_show_select_layout_products') ) {
		function noo_landmark_func_wc_show_select_layout_products() {

			if ( get_theme_mod('noo_shop_use_masonry_layout', false) ) {
				return;
			}

			$porduct_layout = get_theme_mod('noo_shop_default_layout', 'grid');

			?>
				<div class="product-style-control">
					<?php echo esc_html__('View:', 'noo-landmark'); ?>
					<span class="noo-grid <?php if( $porduct_layout == 'grid' ): echo 'active' ; endif; ?>"><i class="fa fa-th-large"></i></span>
					<span class="noo-list <?php if( $porduct_layout == 'list' ): echo 'active' ; endif; ?>"><i class="fa fa-th-list"></i></span>
				</div>
			<?php
		}
	}

	if ( ! function_exists('noo_landmark_func_wc_show_select_number_products') ) {
		function noo_landmark_func_wc_show_select_number_products() {

			$per_default = get_theme_mod( 'noo_shop_num', 12 );
			$noo_per     =  array(6, 12, 18, 24, 30, 36, 40, 46, 50);
			$noo_per[]   = $per_default;
			$new_pre     = array_unique($noo_per);
			sort($new_pre);

			if ( isset($_GET['product_per']) && !empty($_GET['product_per']) ){
			    $per_selected =  $_GET['product_per'];
			} else { 
			    $per_selected = get_theme_mod( 'noo_shop_num', 12 );
			}

			?>
				<form method="GET" class="woocommerce-product_per">
				    <label><?php echo esc_html__('Show:', 'noo-landmark'); ?></label>
				    <select name="product_per" onchange="this.form.submit()">
				        <?php foreach ( $new_pre as $per ) : ?>
				            <option value="<?php echo esc_attr( $per ); ?>" <?php selected( $per_selected, $per ); ?>><?php echo esc_html( $per ); ?></option>
				        <?php endforeach; ?>
				    </select>
				    <?php
				    // Keep query string vars intact
				    foreach ( $_GET as $key => $val ) {

				        if ( 'product_per' === $key || 'submit' === $key ) {
				            continue;
				        }
				        if ( is_array( $val ) ) {
				            foreach( $val as $innerVal ) {
				                echo '<input type="hidden" name="' . esc_attr( $key ) . '[]" value="' . esc_attr( $innerVal ) . '" />';
				            }
				        } else {
				            echo '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '" />';
				        }
				    }
				    ?>
				</form>
			<?php
		}
	}
}

if ( ! function_exists('noo_landmark_func_wc_after_sidebar_shop') ) {
	function noo_landmark_func_wc_after_sidebar_shop() {

		if ( ! NOO_WOOCOMMERCE_EXIST ) {
			return;
		}

		if ( is_shop() || is_product() || is_product_category() || is_product_tag() ) :

			$sidebar = get_theme_mod('noo_shop_sidebar_extension', '');
			if( ! empty( $sidebar ) ) :
			?>
			<div class="noo-sidebar-wrap">
				<?php // Dynamic Sidebar
				if ( ! function_exists( 'dynamic_sidebar' ) || ! dynamic_sidebar( $sidebar ) ) : ?>
				<?php endif; // End Dynamic Sidebar sidebar-main ?>
			</div>
				<?php
			endif;
		endif;
	}
}

if ( ! function_exists('noo_landmark_func_wc_cart_item_remove_link') ) {
	function noo_landmark_func_wc_cart_item_remove_link($tag_link_remove, $cart_item_key) {

		$noo_info_cart = WC()->cart->get_cart();

		$_product   = $noo_info_cart[$cart_item_key]['data'];
		$product_id = $noo_info_cart[$cart_item_key]['product_id'];

		return sprintf(
			'<a href="%s" class="remove" title="%s" data-product_id="%s" data-product_sku="%s"><i class="ion-close"></i></a>',
			esc_url( WC()->cart->get_remove_url( $cart_item_key ) ),
			esc_html__( 'Remove this item', 'noo-landmark' ),
			esc_attr( $product_id ),
			esc_attr( $_product->get_sku() )
		);
	}
}

if ( ! function_exists('noo_landmark_func_wc_show_title_cart') ) {
	function noo_landmark_func_wc_show_title_cart() {
		$title     = esc_html__('Cart', 'noo-landmark');
		noo_landmark_func_wc_title_html($title);
	}
}

if ( ! function_exists('noo_landmark_func_wc_show_title_cart_totals') ) {
	function noo_landmark_func_wc_show_title_cart_totals() {
		$title     = esc_html__('Cart Totals', 'noo-landmark');
		noo_landmark_func_wc_title_html($title);
	}
}

if ( ! function_exists('noo_landmark_func_yith_wcwl_wishlist_title') ) {
	function noo_landmark_func_yith_wcwl_wishlist_title($page_title) {
		$title = strip_tags($page_title);
		noo_landmark_func_wc_title_html($title);
	}
}

if ( ! function_exists('noo_landmark_func_product_quick_view') ) {
	function noo_landmark_func_product_quick_view(){

		$id = $_REQUEST['p_id'];
		if( !isset($id) && empty($id) ) return;

		$args = array(
			'post_type' =>  'product',
			'p'         =>  $id
		);
		$query = new WP_Query( $args );
		if( $query->have_posts() ):

			remove_action( 'woocommerce_after_add_to_cart_button', 'noo_landmark_func_wc_add_yith_custom', 10 );
			remove_action( 'woocommerce_after_single_variation', 'noo_landmark_func_wc_add_yith_custom', 11 );

			while( $query->have_posts() ):
				$query->the_post();
				global $product;
				
				?>
				<div class="quick-left">
					<a href="<?php echo get_the_permalink(); ?>">
						<?php the_post_thumbnail( 'full' ); ?>
					</a>
				</div>
				<div class="quick-right">
					<?php woocommerce_template_single_title(); ?>
					<?php noo_landmark_func_woocommerce_template_single_rating(); ?>
					<?php woocommerce_template_single_price(); ?>
					<hr/>
					<?php woocommerce_template_single_excerpt(); ?>
					<?php
						if( $product->is_type( 'variable' ) ){

							$text = $product->is_purchasable() && $product->is_in_stock() ? esc_html__( 'Select options', 'noo-landmark' ) : esc_html__( 'Read more', 'noo-landmark' );
							echo '<form class="cart">';
							echo '<a href="'.get_the_permalink().'" class="button">'.$text.'</a>';
							echo '</form>';
						} else {
							woocommerce_template_single_add_to_cart();
						}
					?>
					<?php woocommerce_template_single_meta(); ?>
				</div>
				<?php
			endwhile;
		endif; wp_reset_postdata();

		wp_die();
	}
}

if ( ! function_exists('noo_landmark_func_wc_before_mini_cart') ) {
	function noo_landmark_func_wc_before_mini_cart() {
		global $woocommerce;
		wp_enqueue_script( 'bxslider');
		/**
		 * Get count cart
		 */
		$cart_count = $woocommerce->cart->cart_contents_count;
		$cart_url   = $woocommerce->cart->get_cart_url();
		echo '<a class="minicart-link-hover" title="'.esc_html__('View cart', 'noo-landmark').'" href="' . esc_url( $cart_url ).'">';
		echo '<i class="ion-android-cart"></i>';
		echo '<span>' . $cart_count . '</span>';
		echo '</a>';

		/**
		 * Start wrap mini cart
		 */
		echo '<div class="noo_wrap_minicart">';
	}
}

if ( ! function_exists('noo_landmark_func_wc_after_mini_cart') ) {
	function noo_landmark_func_wc_after_mini_cart() {
		echo '</div> <!-- /.noo_wrap_minicart -->';
	}
}

if ( ! function_exists('noo_landmark_func_wc_cart_item_name') ) {
	function noo_landmark_func_wc_cart_item_name($product_title) {
		return '<span class="product-title">' . $product_title . '</span>';
	}
}

if ( ! function_exists('noo_landmark_func_wc_widget_cart_item_quantity') ) {
	function noo_landmark_func_wc_widget_cart_item_quantity($content, $cart_item, $cart_item_key) {
		$product_price = WC()->cart->get_product_price( $cart_item['data'] );
		$html = '<br/>';
		$html .= '<span class="quantity">';
		$html .= $product_price;
		$html .= '<span class="txt-quantity">' . esc_html__('Qty: ', 'noo-landmark');
		$html .= $cart_item['quantity'];
		$html .= '</span>';
		$html .= '</span>';
		return $html;
	}
}

if ( ! function_exists('noo_landmark_func_wc_mini_cart_item_class') ) {
	function noo_landmark_func_wc_mini_cart_item_class($class, $cart_item) {
		if ( ! empty( $cart_item['data']->variation_id ) && is_array( $cart_item['variation'] ) ) {
			$class .= ' is_variation';
		}
		return $class;
	}
}

