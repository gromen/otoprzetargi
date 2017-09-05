<?php
/**
 * Process field search property
 *
 * @package       Realty_Portal
 * @author        NooTeam <suppport@nootheme.com>
 * @version       1.0
 */
if ( ! function_exists( 'rp_advanced_search_fields' ) ) :

	function rp_advanced_search_fields( $name, $field = array() ) {

		if ( empty( $name ) || 'none' == $name ) {
			return;
		}
		$prefix = apply_filters( 'rp_property_post_type', 'rp_property' );
		$class  = 'rp-md-6';
		if ( ! empty( $field[ 'class' ] ) ) {
			$class = esc_attr( $field[ 'class' ] );
		}
		switch ( $name ) {
			case 'keyword':
				$keyword      = ! empty( $_GET[ 'keyword' ] ) ? rp_validate_data( $_GET[ 'keyword' ] ) : '';
				$args_keyword = array(
					'name'        => 'keyword',
					'title'       => '',
					'type'        => 'text',
					'placeholder' => esc_html__( 'Enter Your Keyword...', 'realty-portal-advanced-search' ),
					'class'       => $class,
				);
				rp_create_element( $args_keyword, $keyword );
				break;

			case 'listing_offers':
				$listing_offers = rp_get_list_tax( 'listing_type' );
				$offers         = ! empty( $_GET[ 'offers' ] ) ? rp_validate_data( $_GET[ 'offers' ] ) : '';
				$args_offers    = array(
					'name'             => 'offers',
					'title'            => '',
					'type'             => 'select',
					'placeholder'      => esc_html__( 'Offers', 'realty-portal-advanced-search' ),
					'class'            => $class,
					'options'          => $listing_offers,
					'show_none_option' => true,
				);
				rp_create_element( $args_offers, $offers );
				break;

			case 'listing_type':
				$listing_type = rp_get_list_tax( 'listing_type' );
				$types        = ! empty( $_GET[ 'types' ] ) ? rp_validate_data( $_GET[ 'types' ] ) : '';
				$args_types   = array(
					'name'             => 'types',
					'title'            => '',
					'type'             => 'select',
					'placeholder'      => esc_html__( 'Listing Types', 'realty-portal-advanced-search' ),
					'class'            => $class,
					'options'          => $listing_type,
					'show_none_option' => true,
				);
				rp_create_element( $args_types, $types );
				break;

			case 'property_country':
				$list_country = rp_list_country();
				$country      = ! empty( $_GET[ 'country' ] ) ? rp_validate_data( $_GET[ 'country' ] ) : '';
				$args_country = array(
					'name'             => 'country',
					'title'            => '',
					'type'             => 'select',
					'placeholder'      => esc_html__( 'Country', 'realty-portal-advanced-search' ),
					'class'            => $class,
					'list'             => true,
					'options'          => $list_country,
					'show_none_option' => true,
				);
				rp_create_element( $args_country, $country );
				break;

			case 'city':
			case 'neighborhood':
			case 'zip':
				$data_location = ! empty( $_GET[ $name ] ) ? rp_validate_data( $_GET[ $name ] ) : '';
				global $wpdb;

				$data_meta = $wpdb->get_col( $wpdb->prepare( '
						SELECT DISTINCT meta_value
						FROM %1$s
						LEFT JOIN %2$s ON %1$s.post_id = %2$s.ID
						WHERE meta_key = \'%3$s\' AND post_type = \'%4$s\' AND post_status = \'%5$s\'
						', $wpdb->postmeta, $wpdb->posts, $name, apply_filters( 'rp_property_post_type', 'rp_property' ), 'publish' ) );

				$label = '';

				switch ( $name ) {
				    case 'city':
					    $label = esc_html__( 'City', 'realty-portal-advanced-search' );
				        break;

				    case 'neighborhood':
					    $label = esc_html__( 'Neighborhood', 'realty-portal-advanced-search' );
				        break;

				    case 'zip':
					    $label = esc_html__( 'Zip', 'realty-portal-advanced-search' );
				        break;
				}

				$args_location = array(
					'name'             => $name,
					'title'            => '',
					'type'             => 'select',
					'placeholder'      => sprintf( esc_html__( '%s', 'realty-portal-advanced-search' ), $label ),
					'class'            => $class,
					'show_none_option' => true,
				);

				$args_location[ 'options' ] = array_combine( $data_meta, $data_meta );

				rp_create_element( $args_location, $data_location );
				break;

			case 'price':
				rp_property_render_price_search_field( array(
					'class' => $class,
					'label' => esc_html__( 'Price', 'realty-portal-advanced-search' ),
				) );
				break;

			case 'rp_property_area':
				rp_property_render_area_search_field( array(
					'class' => $class,
					'label' => esc_html__( 'Area', 'realty-portal-advanced-search' ),
				) );
				break;

			default:
				do_action( 'rp_advanced_search_fields', $name, $field );
				$field_item = rp_get_property_field( $name );

				if ( ! empty( $field_item ) ) {

					/**
					 * Check type is area
					 */
					if ( '_area' == $field_item[ 'name' ] ) {
						rp_property_render_area_search_field( array( 'class' => $class ) );
					} else {

						$name_custom_field_item = esc_attr( $prefix . $field_item[ 'name' ] );

						$label_custom_field_item = '';
						if ( ! empty( $field_item[ 'label' ] ) ) {
							$label_custom_field_item = isset( $field_item[ 'label_translated' ] ) ? esc_html( $field_item[ 'label_translated' ] ) : esc_html( $field_item[ 'label' ] );
						}

						$field_item[ 'name' ]  = $name_custom_field_item;
						$field_item[ 'title' ] = '';
						$field_item[ 'class' ] = $class;

						if ( in_array( $field_item[ 'type' ], rp_has_choice_field_types() ) ) {
							$list_options = explode( "\n", $field_item[ 'value' ] );

							foreach ( $list_options as $option ) {
								$field_item[ 'options' ][ sanitize_title( $option ) ] = esc_html( $option );
							}

							unset( $field_item[ 'std' ] );
						} else {
							$field_item[ 'placeholder' ] = $label_custom_field_item;
						}

						/**
						 * Conver type text -> select
						 */
						if ( 'text' == $field_item[ 'type' ] ) {
							$field_item[ 'type' ] = 'select';

							$transient_name = 'rp_advanced_search_fields_' . $name_custom_field_item;

							if ( false === ( $data_meta = get_transient( $transient_name ) ) ) {

								global $wpdb;

								$data_meta = $wpdb->get_col( $wpdb->prepare( '
										SELECT DISTINCT meta_value
										FROM %1$s
										LEFT JOIN %2$s ON %1$s.post_id = %2$s.ID
										WHERE meta_key = \'%3$s\' AND post_type = \'%4$s\' AND post_status = \'%5$s\'
										ORDER BY meta_value', $wpdb->postmeta, $wpdb->posts, $name_custom_field_item, apply_filters( 'rp_property_post_type', 'rp_property' ), 'publish' ) );

								set_transient( $transient_name, $data_meta, DAY_IN_SECONDS );
							}

							$field_item[ 'options' ] = array_combine( $data_meta, $data_meta );
						}

						if ( 'select' == $field_item[ 'type' ] || 'multiple_select' == $field_item[ 'type' ] ) {
							$field_item[ 'placeholder' ] = sprintf( esc_html__( '%s', 'realty-portal-advanced-search' ), esc_html( isset( $field_item[ 'label_translated' ] ) ? esc_html( $field_item[ 'label_translated' ] ) : esc_html( $field_item[ 'label' ] ) ) );

							$field_item[ 'show_none_option' ] = true;
						}

						unset( $field_item[ 'label' ] );
						unset( $field_item[ 'value' ] );
						unset( $field_item[ 'readonly' ] );

						$field_value = ! empty( $_GET[ $field_item[ 'name' ] ] ) ? rp_validate_data( $_GET[ $field_item[ 'name' ] ] ) : '';

						rp_create_element( $field_item, $field_value );
					}
				}

				break;
		}
	}

endif;