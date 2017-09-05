<?php
/**
 * Show all field support search autocomplete
 *
 * @package       Realty_Portal
 * @author        NooTeam <suppport@nootheme.com>
 * @version       1.0
 */

if ( ! function_exists( 'rp_get_list_data_autocomplete' ) ) :

	function rp_get_list_data_autocomplete() {

		$args = array(
			'post_type'      => apply_filters( 'rp_property_post_type', 'rp_property' ),
			'post_status'    => 'publish',
			'posts_per_page' => - 1,
		);

		$name_transient = 'rp_get_list_data_autocomplete';
		if ( false === ( $list_data = get_transient( $name_transient ) ) ) {

			$list_data         = array();
			$data_autocomplete = new WP_Query( $args );

			if ( $data_autocomplete->have_posts() ) {

				while ( $data_autocomplete->have_posts() ): $data_autocomplete->the_post();

					$property_id = get_the_ID();
					$lat         = get_post_meta( $property_id, 'latitude', true );
					$long        = get_post_meta( $property_id, 'longitude', true );
					if ( empty( $lat ) || empty( $long ) ) {
						continue;
					}

					$title   = get_the_title( $property_id );
					$address = get_post_meta( $property_id, 'address', true );
					$zip     = get_post_meta( $property_id, 'zip', true );

					$list_data[] = $title;
					if ( ! empty( $address ) ) {
						$list_data[] = $address;
					}
					if ( ! empty( $zip ) ) {
						$list_data[] = $zip;
					}

				endwhile;
			}
			wp_reset_query();
			wp_reset_postdata();

			set_transient( $name_transient, $list_data, 7 * DAY_IN_SECONDS );
		}

		return json_encode( $list_data, 256 );
	}

endif;