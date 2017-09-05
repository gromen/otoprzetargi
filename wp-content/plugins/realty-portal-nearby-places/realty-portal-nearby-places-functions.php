<?php
if ( ! function_exists( 'rp_addons_nearby_places_yelp_nearby' ) ) :

	function rp_addons_nearby_places_yelp_nearby( $property ) {

		$yelp_on = Realty_Portal::get_setting( 'nearby_places', 'yelp_on', false );

		$latitude  = get_post_meta( $property->ID, 'latitude', true );
		$longitude = get_post_meta( $property->ID, 'longitude', true );

		if ( empty( $latitude ) || empty( $longitude ) ) {
			return false;
		}

		$yelp_cll  = $latitude . ',' . $longitude;

		$yelp_categories = array(
			'active'             => array(
				'name' => esc_html__( 'Active Life', 'realty-portal-nearby-places' ),
				'icon' => 'fa fa-bicycle',
			),
			'arts'               => array(
				'name' => esc_html__( 'Arts & Entertainment', 'realty-portal-nearby-places' ),
				'icon' => 'fa fa-picture-o',
			),
			'auto'               => array(
				'name' => esc_html__( 'Automotive', 'realty-portal-nearby-places' ),
				'icon' => 'fa fa-car',
			),
			'beautysvc'          => array(
				'name' => esc_html__( 'Beauty & Spas', 'realty-portal-nearby-places' ),
				'icon' => 'fa fa-cutlery',
			),
			'education'          => array(
				'name' => esc_html__( 'Education', 'realty-portal-nearby-places' ),
				'icon' => 'fa fa-graduation-cap',
			),
			'eventservices'      => array(
				'name' => esc_html__( 'Event Planning & Services', 'realty-portal-nearby-places' ),
				'icon' => 'fa fa-birthday-cake',
			),
			'financialservices'  => array(
				'name' => esc_html__( 'Financial Services', 'realty-portal-nearby-places' ),
				'icon' => 'fa fa-money',
			),
			'food'               => array(
				'name' => esc_html__( 'Food', 'realty-portal-nearby-places' ),
				'icon' => 'fa fa-shopping-basket',
			),
			'health'             => array(
				'name' => esc_html__( 'Health & Medical', 'realty-portal-nearby-places' ),
				'icon' => 'fa fa-medkit',
			),
			'homeservices'       => array(
				'name' => esc_html__( 'Home Services', 'realty-portal-nearby-places' ),
				'icon' => 'fa fa-wrench',
			),
			'hotelstravel'       => array(
				'name' => esc_html__( 'Hotels & Travel', 'realty-portal-nearby-places' ),
				'icon' => 'fa fa-bed',
			),
			'localflavor'        => array(
				'name' => esc_html__( 'Local Flavor', 'realty-portal-nearby-places' ),
				'icon' => 'fa fa-coffee',
			),
			'localservices'      => array(
				'name' => esc_html__( 'Local Services', 'realty-portal-nearby-places' ),
				'icon' => 'fa fa-dot-circle-o',
			),
			'massmedia'          => array(
				'name' => esc_html__( 'Mass Media', 'realty-portal-nearby-places' ),
				'icon' => 'fa fa-television',
			),
			'nightlife'          => array(
				'name' => esc_html__( 'Nightlife', 'realty-portal-nearby-places' ),
				'icon' => 'fa fa-glass',
			),
			'pets'               => array(
				'name' => esc_html__( 'Pets', 'realty-portal-nearby-places' ),
				'icon' => 'fa fa-paw',
			),
			'professional'       => array(
				'name' => esc_html__( 'Professional Services', 'realty-portal-nearby-places' ),
				'icon' => 'fa fa-suitcase',
			),
			'publicservicesgovt' => array(
				'name' => esc_html__( 'Public Services & Government', 'realty-portal-nearby-places' ),
				'icon' => 'fa fa-university',
			),
			'realestate'         => array(
				'name' => esc_html__( 'Real Estate', 'realty-portal-nearby-places' ),
				'icon' => 'fa fa-building-o',
			),
			'religiousorgs'      => array(
				'name' => esc_html__( 'Religious Organizations', 'realty-portal-nearby-places' ),
				'icon' => 'fa fa-universal-access',
			),
			'restaurants'        => array(
				'name' => esc_html__( 'Restaurants', 'realty-portal-nearby-places' ),
				'icon' => 'fa fa-cutlery',
			),
			'shopping'           => array(
				'name' => esc_html__( 'Shopping', 'realty-portal-nearby-places' ),
				'icon' => 'fa fa-shopping-bag',
			),
			'transport'          => array(
				'name' => esc_html__( 'Transportation', 'realty-portal-nearby-places' ),
				'icon' => 'fa fa-bus',
			),
			'trainstations'      => array(
				'name' => esc_html__( 'Train Stations', 'realty-portal-nearby-places' ),
				'icon' => 'fa fa-train',
			),
		);

		if ( $yelp_on == 1 ) {

			$yelp_consumer_key     = Realty_Portal::get_setting( 'nearby_places', 'yelp_consumer_key', '' );
			$yelp_consumer_secrect = Realty_Portal::get_setting( 'nearby_places', 'yelp_consumer_secret', '' );
			$yelp_token            = Realty_Portal::get_setting( 'nearby_places', 'yelp_token', '' );
			$yelp_token_secrect    = Realty_Portal::get_setting( 'nearby_places', 'yelp_token_serect', '' );
			$yelp_term             = Realty_Portal::get_setting( 'nearby_places', 'yelp_term', array( 'multiple' => array( 'realestate' ) ) );

			$limit = Realty_Portal::get_setting( 'nearby_places', 'yelp_limit', '' );
			$unit  = Realty_Portal::get_setting( 'nearby_places', 'yelp_unit', 'mile' );

			if ( empty( $yelp_consumer_key ) || empty( $yelp_consumer_secrect ) || empty( $yelp_token ) || empty( $yelp_token_secrect ) ) {
				return;
			}

			// Get $consumer && $token
			$consumer = new OAuthConsumer( $yelp_consumer_key, $yelp_consumer_secrect );
			$token    = new OAuthToken( $yelp_token, $yelp_token_secrect );

			// Yelp uses HMAC SHA1 encoding
			$signature_method = new OAuthSignatureMethod_HMAC_SHA1();

			/**
			 * Show Yelp Nearby Places
			 */
			?>
			<div class="rp-property-yelp">

				<div class="rp-yelp-title">
					<h3 class="rp-title-box"><?php echo esc_html__( 'What\'s Nearby?', 'realty-portal-nearby-places' ); ?></h3>
					<div class="yelp-logo">
						<?php echo esc_html__( "powered by", "realty-portal-nearby-places" ); ?>
						<img src="<?php echo RP_ADDON_NEARBY_PLACES_ASSETS; ?>images/yelp-logo.png" alt="yelp" class="v-align-bottom">
					</div>
				</div>

				<?php
				if ( !empty( $yelp_term['multiple'] ) ) {
					foreach ( $yelp_term['multiple'] as $value ) {

						$term_id   = $value;
						$term_name = $yelp_categories[ $term_id ][ 'name' ];
						$term_icon = $yelp_categories[ $term_id ][ 'icon' ];

						$unsigned_url = "http://api.yelp.com/v2/";

						// Build URL Parameters
						$urlparams = array(
							'term'  => $term_id,
							'id'    => '',
							'limit' => $limit,
							'll'    => $yelp_cll,
						);

						// If ID param is set, use business method else use other parameters
						if ( $urlparams[ 'id' ] != '' ) {
							$urlparams[ 'method' ] = 'business/' . $urlparams[ 'id' ];
							unset( $urlparams[ 'term' ], $urlparams[ 'id' ] );
						} else {
							$urlparams[ 'method' ] = 'search';
							unset( $urlparams[ 'id' ] );
						}

						$unsigned_url = $unsigned_url . $urlparams[ 'method' ];

						unset( $urlparams[ 'method' ] );

						// Build OAuth Request using the OAuth PHP library. Uses the consumer and
						// token object created above.
						$oauthrequest = OAuthRequest::from_consumer_and_token( $consumer, $token, 'GET', $unsigned_url, $urlparams );

						// Sign the request
						$oauthrequest->sign_request( $signature_method, $consumer, $token );

						// Get the signed URL
						$signed_url = $oauthrequest->to_url();

						//No Cache option enabled
						$response = rp_addons_yelp_widget_curl( $signed_url );

						if ( isset( $response->businesses ) ) {

							$businesses = $response->businesses;
						} else {

							$businesses = array( $response );
						}

						$distance    = false;
						$current_lat = '';
						$current_lng = '';

						if ( isset( $response->region->center ) ) :

							$current_lat = $response->region->center->latitude;
							$current_lng = $response->region->center->longitude;
							$distance    = true;

						endif;

						// Show Result
						?>
						<div class="yelp-cat-item">

							<h4 class="cat-title">
								<span class="yelp-cat-icon"><i class="<?php echo $term_icon; ?>"></i></span>
								<?php echo $term_name; ?>
							</h4>

							<?php

							if ( sizeof( $businesses ) != 0 ) {

								rp_addons_nearby_places_yelp_nearby_term( $businesses, $distance, $current_lat, $current_lng, $unit );
							}

							?>

						</div>
						<?php
					}
				}
				?>

			</div><!-- /.rp-property-yelp -->
			<?php
		}
	}

	add_action( 'rp_after_single_property_summary', 'rp_addons_nearby_places_yelp_nearby', 100, 1 );

endif;

/**
 * Get Reponse
 */
if ( ! function_exists( 'rp_addons_yelp_widget_curl' ) ) {
	function rp_addons_yelp_widget_curl( $signed_url ) {

		// Send Yelp API Call using WP's HTTP API
		$data = wp_remote_get( $signed_url );

		//Use curl only if necessary
		if ( empty( $data[ 'body' ] ) ) {

			$ch = curl_init( $signed_url );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_HEADER, 0 );
			$data = curl_exec( $ch ); // Yelp response
			curl_close( $ch );
			$data     = noo_yelp_update_http_for_ssl( $data );
			$response = json_decode( $data );
		} else {

			$data     = noo_yelp_update_http_for_ssl( $data );
			$response = json_decode( $data[ 'body' ] );
		}

		// Handle Yelp response data
		return $response;
	}
}

/**
 * Function update http for SSL
 *
 */
if ( ! function_exists( 'noo_yelp_update_http_for_ssl' ) ) {
	function noo_yelp_update_http_for_ssl( $data ) {

		if ( ! empty( $data[ 'body' ] ) && is_ssl() ) {
			$data[ 'body' ] = str_replace( 'http:', 'https:', $data[ 'body' ] );
		} elseif ( is_ssl() ) {
			$data = str_replace( 'http:', 'https:', $data );
		}
		$data = str_replace( 'http:', 'https:', $data );

		return $data;
	}
}

/**
 * Get Yelp Nearby HTML
 */

if ( ! function_exists( 'rp_addons_nearby_places_yelp_nearby_term' ) ) {

	function rp_addons_nearby_places_yelp_nearby_term( $businesses, $distance, $current_lat, $current_lng, $unit ) {
		$yelp_term_img = Realty_Portal::get_setting( 'nearby_places', 'yelp_term_img', 1 );
		echo '<ul class="yelp-result-list">';
		foreach ( $businesses as $data ) {

			if ( property_exists( $data, 'error' ) ) {

				echo '<li><p>' . esc_html__( 'API unavailable in this location.', 'realty-portal-nearby-places' ) . '</p></li>';
				continue;
			}

			$location_distance = '';

			if ( $distance && isset( $data->location->coordinate ) ) :

				$location_lat      = $data->location->coordinate->latitude;
				$location_lng      = $data->location->coordinate->longitude;
				$theta             = $current_lng - $location_lng;
				$dist              = sin( deg2rad( $current_lat ) ) * sin( deg2rad( $location_lat ) ) + cos( deg2rad( $current_lat ) ) * cos( deg2rad( $location_lat ) ) * cos( deg2rad( $theta ) );
				$dist              = acos( $dist );
				$dist              = rad2deg( $dist );
				$miles             = $dist * 60 * 1.1515;
				$location_distance = '<span class="time-review"> (' . round( $miles, 2 ) . esc_html__( 'mi', 'realty-portal-nearby-places' ) . ') </span>';

				if ( $unit == 'kilo' ) {
					$miles             = $miles * 1.6093;
					$location_distance = '<span class="time-review"> (' . round( $miles, 2 ) . esc_html__( 'km', 'realty-portal-nearby-places' ) . ') </span>';
				}

			endif;

			$avatar = 'https://s3-media3.fl.yelpcdn.com/assets/srv0/yelp_styleguide/fe8c0c8725d3/assets/img/default_avatars/business_90_square.png';

			if ( property_exists( $data, 'image_url' ) ) {
				$avatar = $data->image_url;
			}

			if ( ! property_exists( $data, 'name' ) ) {
				continue;
			}

			?>
			<li>
				<div class="yelp-cat-detail rp-row">

					<?php if ( $yelp_term_img == 1 ): ?>

						<div class="avatar">
							<img src="<?php echo esc_url( $avatar ); ?>" alt="<?php echo esc_attr( $data->name ); ?>">
						</div>

					<?php endif; ?>

					<div class="story">

						<h5><?php echo $data->name; ?><?php echo $location_distance; ?></h5>

						<?php if ( property_exists( $data, 'rating_img_url' ) ) : ?>
							<p class="review">
								<img src="<?php echo esc_url( $data->rating_img_url ); ?>" alt="<?php echo esc_attr( $data->name ); ?>">
								<?php if ( property_exists( $data, 'review_count' ) ): ?>
									<span class="time-review"> <?php echo esc_html( $data->review_count ) . esc_html__( ' reviews', 'realty-portal-nearby-places' ); ?></span>
								<?php endif; ?>
							</p>
						<?php endif ?>

						<?php if ( property_exists( $data, 'location' ) && property_exists( $data->location, 'display_address' ) ): ?>
							<address>
								<?php echo esc_html__( implode( ', ', $data->location->display_address ) ); ?>
							</address>
						<?php endif; ?>

					</div>

				</div>
			</li>
			<?php
		}
		echo '</ul>';
	}
}

if ( ! function_exists( 'rp_addons_nearby_places_walkscore' ) ) :

	function rp_addons_nearby_places_walkscore( $property ) {

		$walkscore_on = Realty_Portal::get_setting( 'nearby_places', 'walkscore_on', false );

		if ( $walkscore_on == 1 ) {

			$walkscore_api_key = Realty_Portal::get_setting( 'nearby_places', 'walkscore_api_key', '' );

			if ( $walkscore_api_key == '' ) {
				return;
			}

			$lat     = get_post_meta( $property->ID, 'latitude', true );
			$long    = get_post_meta( $property->ID, 'longitude', true );
			$address = get_post_meta( $property->ID, 'address', true );
			$address = stripslashes( $address );
			$address = urlencode( $address );

			$url = "http://api.walkscore.com/score?format=json&address=$address";
			$url .= "&lat=$lat&lon=$long&wsapikey=$walkscore_api_key";

			$response = wp_remote_get( $url, array( 'timeout' => 120 ) );

			if ( is_array( $response ) ) {

				$body      = wp_remote_retrieve_body( $response ); // use the content
				$walkscore = json_decode( $body ); // json decode

				if ( ! property_exists( $walkscore, 'walkscore' ) ) {
					return;
				}

				?>
				<div class="rp-property-walkscore">
					<div class="rp-walkscore-title">
						<h3 class="rp-title-box"><?php echo esc_html__( 'Walkscore', 'realty-portal-nearby-places' ); ?></h3>
					</div>
					<div class="walkscore_details">
						<img src="https://cdn.walk.sc/images/api-logo.png" alt="walkscore" />
						<span>
							<?php echo esc_html( $walkscore->walkscore ); ?>
							/ <?php echo esc_html( $walkscore->description ); ?>
							<a href="<?php echo esc_url( $walkscore->ws_link ); ?>" target="_blank">
					            <?php echo esc_html__( 'more details here', 'realty-portal-nearby-places' ); ?>
				            </a>
				        </span>
					</div>
				</div>
				<?php
			}
		}
	}

	add_action( 'rp_after_single_property_summary', 'rp_addons_nearby_places_walkscore', 105, 1 );

endif;