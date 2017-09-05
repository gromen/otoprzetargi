<?php
/**
 * This function create list property markers
 *
 * @package       Realty_Portal
 * @author        NooTeam <suppport@nootheme.com>
 * @version       1.0
 */

if ( ! function_exists( 'rp_list_property_markers' ) ) :

	function rp_list_property_markers( $args = array() ) {

		$defaults = array(
			'post_type'      => apply_filters( 'rp_property_post_type', 'rp_property' ),
			'post_status'    => 'publish',
			'posts_per_page' => - 1,
		);

		/**
		 * Get list location
		 */
		$list_country = rp_list_country();
		$location     = array();
		foreach ( $list_country as $country ) {
			$location[] = $country[ 'value' ];
		}

		$markers = array();

		$args = wp_parse_args( $args, $defaults );

		$wp_query = new WP_Query( $args );

		if ( $wp_query->have_posts() ) {
			while ( $wp_query->have_posts() ): $wp_query->the_post();
				$property_id = get_the_ID();
				$lat         = get_post_meta( $property_id, 'latitude', true );
				$long        = get_post_meta( $property_id, 'longitude', true );
				if ( empty( $lat ) || empty( $long ) ) {
					continue;
				}
				$title        = wp_trim_words( get_the_title( $property_id ), 7 );
				$image        = rp_thumb_src( $property_id, 'rp-property-medium', '180x150' );
				$price        = get_post_meta( $property_id, 'price', true );
				$country      = get_post_meta( $property_id, 'country', true );
				$city         = get_post_meta( $property_id, 'city', true );
				$neighborhood = get_post_meta( $property_id, 'neighborhood', true );
				$zip          = get_post_meta( $property_id, 'zip', true );

				/**
				 * Get list status
				 */
				$listing_offers       = array();
				$listing_offers_terms = get_the_terms( $property_id, apply_filters( 'rp_property_listing_offers', 'listing_offers' ) );
				if ( $listing_offers_terms && ! is_wp_error( $listing_offers_terms ) ) {
					foreach ( $listing_offers_terms as $status_term ) {
						if ( empty( $status_term->term_id ) ) {
							continue;
						}
						$listing_offers[] = $status_term->term_id;
					}
				}

				/**
				 * Get list Listing type
				 */
				$listing_type       = array();
				$listing_type_terms = get_the_terms( $property_id, apply_filters( 'rp_property_listing_type', 'listing_type' ) );
				$icon_markers       = 'fa-home';
				if ( $listing_type_terms && ! is_wp_error( $listing_type_terms ) ) {
					foreach ( $listing_type_terms as $type_term ) {
						if ( empty( $type_term->term_id ) ) {
							continue;
						}
						$listing_type[] = $type_term->term_id;
						$icon_markers   = get_term_meta( $type_term->term_id, 'icon_type', true );
						if ( empty( $icon_markers ) ) {
							$icon_markers = 'fa-home';
						}
					}
				}

				$marker = array(
					'latitude'     => $lat,
					'longitude'    => $long,
					'image'        => $image,
					'title'        => $title,
					'price'        => rp_format_price( $price, false ),
					'price_html'   => rp_property_price( $property_id ),
					'area'         => rp_get_property_area_html( $property_id ),
					'url'          => get_permalink( $property_id ),
					'icon_markers' => $icon_markers,
					'types'        => $listing_type,
					'offers'       => $listing_offers,
					'location'     => $country,
					'city'         => $city,
					'neighborhood' => $neighborhood,
					'zip'          => $zip,
					// 'icon'         => $listing_type_marker,
				);

				/**
				 * Show custom fields
				 */
				$custom_fields = rp_property_render_fields();
				$marker_merge  = array();
				foreach ( $custom_fields as $item ) :

					if ( empty( $item[ 'name' ] ) ) {
						continue;
					}

					$meta_key = apply_filters( 'rp_property_post_type', 'rp_property' ) . $item[ 'name' ];

					$value = get_post_meta( $property_id, $meta_key, true );

					if ( ! is_array( $value ) ) {
						$marker_merge[ $item[ 'name' ] ] = sanitize_title( $value );
					} else {
						$marker_merge[ $item[ 'name' ] ] = $value;
					}

				endforeach;

				$marker = array_merge( $marker, $marker_merge );

				$markers[] = $marker;
			endwhile;
		}
		wp_reset_query();
		wp_reset_postdata();

		return json_encode( $markers, 512 );
	}

endif;