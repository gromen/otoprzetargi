<?php
if (!function_exists('noo_landmark_func_the_breadcrumb')):
	
	function noo_landmark_func_the_breadcrumb() {
		global $post,$wp_query;
		echo '<ul class="breadcrumb">';
		if (!is_home()) {
			
			echo '<li>';
			echo '	<a href="' . esc_attr( home_url() ) . '" title="' . esc_html__('Home', 'noo-landmark') . '">';
			echo 		esc_html__('Home', 'noo-landmark');
			echo '	</a>';
			echo '</li>';
			if (is_category()) {
				$the_cat = get_category(get_query_var('cat') , false);
				if ($the_cat->parent != 0) {
					echo '<li>' . get_category_parents($the_cat->parent, true, '</li><li>');
					echo '</li>';
				}
				echo '<li class="active"><span>';
				echo single_cat_title('', false);
				echo '</span></li>';
			}elseif ( is_tax( 'product_cat' ) ){
				$current_term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
				$ancestors = array_reverse( get_ancestors( $current_term->term_id, get_query_var( 'taxonomy' ) ) );
				foreach ( $ancestors as $ancestor ) {
					$ancestor = get_term( $ancestor, get_query_var( 'taxonomy' ) );
					echo '<li>';
					echo '<a href="' . get_term_link( $ancestor->slug, get_query_var( 'taxonomy' ) ) . '">' . esc_html( $ancestor->name ) . '</a>';
					echo '</li>';
						
				}
				echo '<li class="active"><span>';
				echo esc_html( $current_term->name );
				echo '</span></li>';
			}elseif ( is_tax( 'product_lookbook' ) ) {
				global $wp_query;
				$queried_object = $wp_query->get_queried_object();
				echo '<li class="active"><span>' . esc_html__('Products in Lookbook: ', 'noo-landmark') . '&#8220;' . $queried_object->name. '&#8221;' . '</span></li>';
			}elseif ( is_tax( 'product_tag' ) ) {
				global $wp_query;
				$queried_object = $wp_query->get_queried_object();
				echo '<li class="active"><span>' . esc_html__('Products tagged as ', 'noo-landmark') . '&#8220;' . $queried_object->name. '&#8221;' . '</span></li>';
			}elseif (is_tag()) {
				echo '<li class="active"><span>' . esc_html__('Posts Tagged as ', 'noo-landmark') . '&#8220;' . single_tag_title('', false) . '&#8221;' . '</span></li>';
			}elseif(is_tax()) {
				
				$current_term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
				$ancestors = array_reverse( get_ancestors( $current_term->term_id, get_query_var( 'taxonomy' ) ) );
				foreach ( $ancestors as $ancestor ) {
					$ancestor = get_term( $ancestor, get_query_var( 'taxonomy' ) );
					echo '<li>';
					echo '<a href="' . get_term_link( $ancestor->slug, get_query_var( 'taxonomy' ) ) . '">' . esc_html( $ancestor->name ) . '</a>';
					echo '</li>';
				
				}
				echo '<li class="active"><span>';
				echo esc_html( $current_term->name );
				echo '</span></li>';
			
			} elseif (is_singular('portfolio_project')) {
				$trainer_page = get_theme_mod('noo_trainer_page', '');
				echo '<li>';
				echo '<a href="' . ( esc_attr( home_url( '/' ) ) . ( !$trainer_page ? get_post( $trainer_page )->post_name : 'noo-trainer' ) ) . '" title="' . esc_attr( __('View All Portfolio', 'noo-landmark')) . '">' . get_theme_mod('noo_trainer_heading_title', __( 'My Portfolio', 'noo-landmark' )) . '</a> ';
				echo '</li>';
				echo '<li class="active"><span>';
				the_title();
				echo '</span></li>';
			}elseif (NOO_WOOCOMMERCE_EXIST && is_post_type_archive( 'product' )){
				if (!is_search()) {
					echo '<li class="active"><span>' . esc_html__('Shop', 'noo-landmark');
					echo '</span></li>';
				} else {
					echo '<li class="active"><span>' . get_the_title() . '</span></li>';
				}
			}elseif (is_page()) {
				
				if ($post->post_parent) {
					$anc = get_post_ancestors($post->ID);
					$title = get_the_title();
					foreach ($anc as $ancestor) {
						echo '<li><a href="' . get_permalink($ancestor) . '" title="' . get_the_title($ancestor) . '">' . get_the_title($ancestor) . '</a></li>';
					}
					echo '<li class="active"><span>' . $title . '</span></li>';
				} else {
					echo '<li class="active"><span>' . get_the_title() . '</span></li>';
				}
			}elseif (is_singular()) {
				if ($post->post_parent) {
					echo '<li>';
					the_category('</li><li>');
					echo '</li>';
				}
				echo '<li class="active"><span>';
				the_title();
				echo '</span></li>';
			}elseif (is_author()){
				global $author;
				$userdata = get_userdata($author);
				echo '<li class="active"><span>' . esc_html__('Posts by ', 'noo-landmark') . '&#8220;' . $userdata->display_name . '&#8221;' . '</span></li>';
				
			}elseif (is_day()) {
				echo '<li class="active"><span>' . esc_html__('Archive for', 'noo-landmark');
				the_time('F j, Y');
				echo '</span></li>';
			} elseif (is_month()) {
				echo '<li class="active"><span>' . esc_html__('Archive for', 'noo-landmark');
				the_time('F, Y');
				echo '</span></li>';
			} elseif (is_year()) {
				echo '<li class="active"><span>' . esc_html__('Archive for', 'noo-landmark');
				the_time('Y');
				echo '</span></li>';
			} elseif (get_query_var('paged')) {
				echo '<li class="active"><span>' . esc_html__('Blog Archives', 'noo-landmark');
				echo '</span></li>';
			} elseif (is_search()) {
				echo '<li class="active"><span>' . esc_html__('Search Results', 'noo-landmark');
				echo '</span></li>';
			}
			
		}else{
			echo '<li>';
			echo '<a href="' . esc_attr( home_url() ) . '">';
			echo esc_html__('Home', 'noo-landmark');
			echo '</a>';
			echo '</li>';
			$home_page = get_page( $wp_query->get_queried_object_id() );
			echo '<li class="active"><span>' . get_the_title( $home_page->ID );
			echo '</span></li>';
		}
		echo '</ul>';
	}
endif;
