<?php
/**
 * Create ajax process filter property map
 *
 * @package       RP_Realty_Portal
 * @author        NooTeam <suppport@nootheme.com>
 * @version       1.0
 */

if ( ! function_exists( 'rp_loadmore_property_request' ) ) :

	function rp_loadmore_property_request() {
		global $query;
		/**
		 * Check security
		 */
		check_ajax_referer( 'google-map-search-property', 'security', esc_html__( 'Security Breach! Please contact admin!', 'realty-portal' ) );

		/**
		 * Validate $_POST
		 */
		$_POST = wp_kses_post_deep( $_POST );

		/**
		 * Process
		 */
		unset( $_POST[ 'security' ] );
		unset( $_POST[ 'action' ] );

		$current_page = ( ! empty( $_POST[ 'current_page' ] ) && $_POST[ 'current_page' ] !== 'NaN' ) ? absint( $_POST[ 'current_page' ] ) : 1;

		$args = array(
			'post_status' => 'publish',
			'post_type'   => apply_filters( 'rp_property_post_type', 'rp_property' ),
			'paged'       => $current_page,
		);

		if ( function_exists( 'pll_current_language' ) ) {
			$args[ 'lang' ] = pll_current_language();
		}

		$meta_query = array();

		if ( isset( $_POST[ 'keyword' ] ) && ! empty( $_POST[ 'keyword' ] ) ) {
			$args[ 's' ] = $_POST[ 'keyword' ];
		}

		$tax_query = RP_Property::tax_query( $_POST );

		if ( ! empty( $tax_query ) ) {
			$tax_query[ 'relation' ] = 'AND';
			if ( is_object( $query ) && get_class( $query ) == 'WP_Query' ) {
				$query->tax_query->queries        = $tax_query;
				$query->query_vars[ 'tax_query' ] = $query->tax_query->queries;
			} elseif ( is_array( $query ) ) {
				$query[ 'tax_query' ] = $tax_query;
			}
		}

		/**
		 * Check request field location
		 */
		$list_location = array(
			'address',
			'country',
			'city',
			'neighborhood',
			'zip',
			'state',
			'latitude',
			'longitude',
		);

		foreach ( $list_location as $location_item ) {
			if ( ! empty( $_POST[ $location_item ] ) ) {
				$meta_query[] = array(
					'key'   => $location_item,
					'value' => sanitize_text_field( $_POST[ $location_item ] ),
				);
			}
		}

		if ( isset( $_POST[ 'min_area' ] ) && ! empty( $_POST[ 'min_area' ] ) ) {
			$min_area[ 'key' ]     = 'rp_property_area';
			$min_area[ 'value' ]   = intval( $_POST[ 'min_area' ] );
			$min_area[ 'type' ]    = 'NUMERIC';
			$min_area[ 'compare' ] = '>=';
			$meta_query[]          = $min_area;
		}
		if ( isset( $_POST[ 'max_area' ] ) && ! empty( $_POST[ 'max_area' ] ) ) {
			$max_area[ 'key' ]     = 'rp_property_area';
			$max_area[ 'value' ]   = intval( $_POST[ 'max_area' ] );
			$max_area[ 'type' ]    = 'NUMERIC';
			$max_area[ 'compare' ] = '<=';
			$meta_query[]          = $max_area;
		}
		if ( isset( $_POST[ 'min_price' ] ) && ! empty( $_POST[ 'min_price' ] ) ) {
			$min_price[ 'key' ]     = 'price';
			$min_price[ 'value' ]   = floatval( $_POST[ 'min_price' ] );
			$min_price[ 'type' ]    = 'NUMERIC';
			$min_price[ 'compare' ] = '>=';
			$meta_query[]           = $min_price;
		}
		if ( isset( $_POST[ 'max_price' ] ) && ! empty( $_POST[ 'max_price' ] ) ) {
			$max_price[ 'key' ]     = 'price';
			$max_price[ 'value' ]   = floatval( $_POST[ 'max_price' ] );
			$max_price[ 'type' ]    = 'NUMERIC';
			$max_price[ 'compare' ] = '<=';
			$meta_query[]           = $max_price;
		}

		$property_features = rp_render_featured_amenities();
		if ( ! empty( $property_features ) ) {

			foreach ( $property_features as $key => $feature ) {
				$field_id = apply_filters( 'rp_property_post_type', 'rp_property' ) . sanitize_title( $key );
				if ( isset( $_POST[ $field_id ] ) && ! empty( $_POST[ $field_id ] ) ) {
					$meta_query[] = array(
						'key'   => $field_id,
						'value' => '1',
					);
				}
			}
		}

		$property_fields = rp_property_render_fields();

		if ( ! empty( $property_fields ) ) {
			unset( $property_fields[ '' ] );
			foreach ( $property_fields as $field ) {

				if ( ! array_key_exists( 'name', $field ) ) {
					continue;
				}

				$field_id = apply_filters( 'rp_property_post_type', 'rp_property' ) . rp_property_custom_fields_name( $field[ 'name' ] );

				if ( isset( $_POST[ $field_id ] ) && ! empty( $_POST[ $field_id ] ) ) {
					$value = rp_validate_data( $_POST[ $field_id ], $field );
					if ( is_array( $value ) ) {
						$temp_meta_query = array( 'relation' => 'OR' );
						foreach ( $value as $v ) {
							if ( empty( $v ) ) {
								continue;
							}
							$temp_meta_query[] = array(
								'key'     => $field_id,
								'value'   => '"' . $v . '"',
								'compare' => 'LIKE',
							);
						}
						$meta_query[] = $temp_meta_query;
					} else {
						$meta_query[] = array(
							'key'   => $field_id,
							'value' => esc_attr( $value ),
						);
					}
				} elseif ( ( isset( $field[ 'type' ] ) && 'datepicker' == $field[ 'type' ] ) && ( isset( $_POST[ $field_id . '_start' ] ) || isset( $_POST[ $field_id . '_end' ] ) ) ) {
					if ( $field_id == 'date' ) {
						$date_query = array();
						if ( isset( $_POST[ $field_id . '_start' ] ) && ! empty( $_POST[ $field_id . '_start' ] ) ) {
							$start                 = is_numeric( $_POST[ $field_id . '_start' ] ) ? date( 'Y-m-d', $_POST[ $field_id . '_start' ] ) : $_POST[ $field_id . '_start' ];
							$date_query[ 'after' ] = date( 'Y-m-d', strtotime( $start . ' -1 day' ) );
						}
						if ( isset( $_POST[ $field_id . '_end' ] ) && ! empty( $_POST[ $field_id . '_end' ] ) ) {
							$end                    = is_numeric( $_POST[ $field_id . '_end' ] ) ? date( 'Y-m-d', $_POST[ $field_id . '_end' ] ) : $_POST[ $field_id . '_end' ];
							$date_query[ 'before' ] = date( 'Y-m-d', strtotime( $end . ' +1 day' ) );
						}

						if ( is_object( $query ) && get_class( $query ) == 'WP_Query' ) {
							$query->query_vars[ 'date_query' ][] = $date_query;
						} elseif ( is_array( $query ) ) {
							$query[ 'date_query' ] = $date_query;
						}
					} else {
						$value_start = isset( $_POST[ $field_id . '_start' ] ) && ! empty( $_POST[ $field_id . '_start' ] ) ? rp_validate_data( $_POST[ $field_id . '_start' ], $field ) : 0;
						$value_start = ! empty( $value_start ) ? strtotime( "midnight", $value_start ) : 0;
						$value_end   = isset( $_POST[ $field_id . '_end' ] ) && ! empty( $_POST[ $field_id . '_end' ] ) ? rp_validate_data( $_POST[ $field_id . '_end' ], $field ) : 0;
						$value_end   = ! empty( $value_end ) ? strtotime( "tomorrow", strtotime( "midnight", $value_end ) ) - 1 : strtotime( '2090/12/31' );

						$meta_query[] = array(
							'key'     => $field_id,
							'value'   => array(
								$value_start,
								$value_end,
							),
							'compare' => 'BETWEEN',
							'type'    => 'NUMERIC',
						);
					}
				}
			}
		}

		if ( ! empty( $meta_query ) ) {
			$meta_query[ 'relation' ] = 'AND';
			$args[ 'meta_query' ][]   = $meta_query;
		}

		if ( ! empty( $_POST[ 'orderby' ] ) ) {
			$orderby = sanitize_text_field( $_POST[ 'orderby' ] );
			$order   = isset( $_POST[ 'order' ] ) ? sanitize_text_field( $_POST[ 'order' ] ) : 'DESC';
			switch ( $orderby ) {
				case 'rand' :
					$args['orderby']  = 'rand';
					break;
				case 'date' :
					$args['orderby']  = 'date';
					$args['order']    = $order == 'ASC' ? 'ASC' : 'DESC';
					break;
				case 'bath' :
					$args['orderby']  = "meta_value_num meta_value";
					$args['order']    = $order == 'DESC' ? 'DESC' : 'ASC';
					$args['meta_key'] = apply_filters( 'rp_property_post_type', 'rp_property' ) . '_bathrooms';
					break;
				case 'bed' :
					$args['orderby']  = "meta_value_num meta_value";
					$args['order']    = $order == 'DESC' ? 'DESC' : 'ASC';
					$args['meta_key'] = apply_filters( 'rp_property_post_type', 'rp_property' ) . '_bedrooms';
					break;
				case 'area' :
					$args['orderby']  = "meta_value_num meta_value";
					$args['order']    = $order == 'DESC' ? 'DESC' : 'ASC';
					$args['meta_key'] = apply_filters( 'rp_property_post_type', 'rp_property' ) . '_area';
					break;
				case 'price' :
					$args['orderby']  = "meta_value_num meta_value";
					$args['order']    = $order == 'DESC' ? 'DESC' : 'ASC';
					$args['meta_key'] = 'price';
					break;
				case 'featured' :
					$args['orderby']  = "meta_value";
					$args['order']    = $order == 'DESC' ? 'DESC' : 'ASC';
					$args['meta_key'] = '_featured';
					break;
				case 'name' :
					$args['orderby']  = 'title';
					$args['order']    = 'ASC';
					break;
			}
		}

		$wp_query = new WP_Query( apply_filters( 'rp_loadmore_property_request', $args ) );

		if ( $wp_query->found_posts <= 0 ) :

			wp_die( '<div class="no_results">' . esc_html__( 'We found no results', 'realty-portal' ) . '</div>' );

		endif;

		$style_show_ajax    = isset( $_POST[ 'style' ] ) ? rp_validate_data( $_POST[ 'style' ] ) : '';
		$show_loadmore_ajax = isset( $_POST[ 'loadmore' ] ) ? rp_validate_data( $_POST[ 'loadmore' ] ) : true;

		if ( empty( $style_show_ajax ) ) {
			echo '<div class="rp-list-property">';
		}

		if ( $wp_query->have_posts() ) {
			if ( $current_page === 1 ) {
				echo sprintf( __( '<p class="rp-results">%s listings found</p>', 'realty-portal' ), $wp_query->found_posts );
			}
			rp_property_loop_start();

			while ( $wp_query->have_posts() ) : $wp_query->the_post();

				RP_Template::get_template( 'property/content-property.php' );

			endwhile;

			rp_property_loop_end();

			if ( ! empty( $show_loadmore_ajax ) ) {
				$max_num_pages = intval( $wp_query->max_num_pages );
				if ( $max_num_pages > 1 && $current_page < $max_num_pages ) {
					echo '<div class="loadmore-results-wrap">';
					echo '<button class="loadmore-results rp-button" data-current-page="' . $current_page . '" data-max-page="' . $max_num_pages . '">' . esc_html__( 'Load more', 'realty-portal' ) . '</button>';
					echo '</div><!-- /.loadmore-results-wrap -->';
				}
			} else {
				rp_pagination_loop( array(), $wp_query );
			}
			wp_reset_postdata();
		} else {
			echo '<div class="rp-found">' . esc_html__( 'Nothing Found!', 'realty-portal' ) . '</div>';
		}
		if ( empty( $style_show_ajax ) ) {
			echo '</div><!-- /.rp-list-property -->';
		}

		wp_die();
	}

	add_action( 'wp_ajax_loadmore_property_request', 'rp_loadmore_property_request' );
	add_action( 'wp_ajax_nopriv_loadmore_property_request', 'rp_loadmore_property_request' );

endif;