<?php
/**
 * Create ajax process when user want print property
 *
 * @package       Realty_Portal
 * @author        NooTeam <suppport@nootheme.com>
 * @version       1.0
 */

if ( ! function_exists( 'rp_property_create_print' ) ) :

	function rp_property_create_print() {

		/**
		 * Check security
		 */
		check_ajax_referer( 'rp-property', 'security', esc_html__( 'Security Breach! Please contact admin!', 'realty-portal' ) );

		/**
		 * Validate $_POST
		 */
		$_POST = wp_kses_post_deep( $_POST );

		/**
		 * Process
		 */
		$property_id   = rp_validate_data( $_POST[ 'property_id' ] );
		$property_info = get_post( $property_id );

		if ( $property_info->post_type != apply_filters( 'rp_property_post_type', 'rp_property' ) || $property_info->post_status != 'publish' ) {
			exit();
		}
		$address        = get_post_meta( $property_id, 'address', true );
		$price          = rp_property_price( $property_id );
		$property_photo = get_post_meta( $property_id, 'property_photo', true );
		$list_id_photo  = ! empty( $property_photo ) ? explode( ',', $property_photo ) : '';

		$agent_responsible = get_post_meta( $property_id, 'agent_responsible', true );

		if ( ! empty( $agent_responsible ) && $agent_responsible !== 'none' ) {
			$agent_id = $agent_responsible;
		} else {
			$user_id = $property_info->post_author;

			if ( ! empty( $user_id ) ) {
				$agent_id = intval( get_user_meta( $user_id, '_associated_agent_id', true ) );
				$agent_id = ( $agent_id != 0 ) ? $agent_id : $user_id;
			}
		}
		apply_filters( 'wpml_object_id', $agent_id, apply_filters( 'rp_agent_post_type', 'rp_agent' ) );

		$id_avatar = get_post_thumbnail_id( $agent_id );
		$avatar    = rp_thumb_src_id( $id_avatar, 'thumbnail', '268x210' );

		$agent_custom_field = rp_agent_render_fields();

		unset( $agent_custom_field[ '' ] );
		unset( $agent_custom_field[ 'social_network' ] );
		unset( $agent_custom_field[ '_position' ] );
		unset( $agent_custom_field[ '_about' ] );

		$custom_feature_fields = rp_render_featured_amenities();
		$property_features     = rp_render_featured_amenities();
		$total_features        = count( $property_features );
		if ( $total_features > 0 ) {
			unset( $custom_feature_fields[ '' ] );
		}

		$custom_fields = rp_property_render_fields();
		unset( $custom_fields[ '' ] );
		?>
		<html>
		<head>
			<link href="<?php echo REALTY_PORTAL_ASSETS . '/css/realty-portal.css'; ?>" rel="stylesheet" type="text/css" />
			<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
			<script>$(window).load(function () {
					print();
				});</script>
		</head>
		<body id="rp-print">
		<header id="rp-header">
			<h1><?php echo get_bloginfo( 'name' ); ?></h1>
		</header><!-- /header -->
		<main id="rp-content">
			<section class="rp-info-property">
				<div class="rp-left">
					<h2><?php echo get_the_title( $property_id ); ?></h2>
					<address>
						<?php echo esc_html( $address ); ?>
					</address>
				</div>
				<div class="rp-right">
					<div class="rp-price">
						<?php echo wp_kses( $price, rp_allowed_html() ); ?>
					</div>
				</div>
			</section>

			<?php if ( ! empty( $list_id_photo ) ) : ?>
				<section class="rp-gallery">
					<img src="<?php echo esc_attr( rp_thumb_src_id( $list_id_photo[ 0 ], 'rp-property-slider', '1920x800' ) ) ?>" alt="<?php get_the_title( $property_id ) ?>" />
				</section>
			<?php endif; ?>

			<section class="rp-info-agent">
				<div class="rp-thumbnail">
					<img src="<?php echo esc_url( $avatar ) ?>" alt="<?php the_title() ?>" />
				</div>
				<div class="rp-info-agent-content">
					<h4><?php esc_html_e( 'Contact Agent', 'realty-portal' ); ?></h4>
					<ul>
						<?php
						foreach ( $agent_custom_field as $field ) {
							$field_name = apply_filters( 'rp_agent_post_type', 'rp_agent' ) . $field[ 'name' ];
							$value      = get_post_meta( $agent_id, $field_name, true );
							if ( ! empty( $value ) ) {
								switch ( $field[ 'name' ] ) {
									case '_email':
										echo '<li class="' . esc_attr( $field_name ) . '">';
										echo '	<a href="mailto:' . esc_html( $value ) . '" target="_top">' . esc_html( $value ) . '</a>';
										echo '</li>';
										break;

									case '_phone':
									case '_mobile':
										echo '<li class="' . esc_attr( $field_name ) . '">';
										echo '	<a href="tel:' . absint( $value ) . '" target="_top">' . esc_html( $value ) . '</a>';
										echo '</li>';
										break;

									case '_website':
										echo '<li class="' . esc_attr( $field_name ) . '">';
										echo '	<a href="' . esc_attr( $value ) . '" target="_top">' . esc_html( $value ) . '</a>';
										echo '</li>';
										break;

									default:
										echo '<li class="' . esc_attr( $field_name ) . '">' . esc_html( $value ) . '</li>';
										break;
								}
							}
						}
						?></ul>
				</div>
			</section>

			<section class="rp-property-description">
				<h3><?php echo esc_html__( 'Property Description', 'realty-portal' ); ?></h3>
				<?php echo $property_info->post_content; ?>
			</section>

			<section class="rp-property-detail">
				<h3><?php echo esc_html__( 'Additional Details', 'realty-portal' ); ?></h3>
				<ul>
					<?php
					/**
					 * Show field listing offers
					 */
					$listing_offers = get_the_terms( $property_id, 'listing_offers' );

					if ( ! empty( $listing_offers ) && ! is_wp_error( $listing_offers ) ) {
						$listing_offer = array();
						foreach ( $listing_offers as $offer ) {
							$listing_offer[] = $offer->name;
						}
						?>
						<li>
							<span><?php echo esc_html__( 'Offers', 'realty-portal' ); ?></span>
							<span><?php echo implode( ', ', $listing_offer ) ?></span>
						</li>
						<?php
					}

					/**
					 * Show field Listing type
					 */
					$listing_type = get_the_terms( $property_id, apply_filters( 'rp_property_listing_type', 'listing_type' ) );

					if ( ! empty( $listing_type ) && ! is_wp_error( $listing_type ) ) {
						$list_types = array();
						foreach ( $listing_type as $types ) {
							$list_types[] = $types->name;
						}
						?>
						<li>
							<span><?php echo esc_html__( 'Type', 'realty-portal' ); ?></span>
							<span><?php echo implode( ', ', $list_types ) ?></span>
						</li>
						<?php
					}
					?>
					<li>
						<span><?php echo esc_html__( 'Price', 'realty-portal' ); ?></span>
						<span><?php echo rp_property_price( $property_id ); ?></span>
					</li>
					<?php
					if ( ! empty( $custom_fields ) ) :
						foreach ( $custom_fields as $field ) {
							if ( ! is_array( $field ) || ! array_key_exists( 'name', $field ) ) {
								continue;
							}
							$name_field  = apply_filters( 'rp_property_post_type', 'rp_property' ) . $field[ 'name' ];
							$value_field = get_post_meta( $property_id, esc_attr( $name_field ), true );
							if ( ! empty( $value_field ) ) {
								?>
								<li>
									<span><?php echo esc_html( $field[ 'label' ] ) ?></span>
									<span><?php echo esc_html( $value_field ); ?></span>
								</li>
								<?php
							}
						}
					endif; ?>
				</ul>
			</section>

			<section class="rp-property-features">
				<h3><?php echo esc_html__( 'Property Features', 'realty-portal' ); ?></h3>
				<?php
				if ( ! empty( $custom_feature_fields ) ) {
					echo '<ul>';
					foreach ( $custom_feature_fields as $field ) {

						if ( ! is_array( $field ) || ! array_key_exists( 'name', $field ) ) {
							continue;
						}

						$name_field  = apply_filters( 'rp_property_post_type', 'rp_property' ) . $field[ 'name' ];
						$value_field = get_post_meta( $property_id, esc_attr( $name_field ), true );
						if ( ! empty( $value_field ) ) {

							echo '<li>' . esc_html( $field[ 'label' ] ) . '</li>';
						}
					}
					echo '</ul>';
				} ?>
			</section>

			<section class="rp-property-floor-plan">
				<h3><?php echo esc_html__( 'Floor Plans', 'realty-portal' ); ?></h3>
				<?php
				$floor_plan = get_post_meta( $property_id, 'floor_plan', true );
				if ( ! empty( $floor_plan ) ) {
					$list_id_floor_plan = explode( ',', $floor_plan );
					if ( ! empty( $list_id_floor_plan ) ) {
						foreach ( $list_id_floor_plan as $floor_plan ) {
							echo '<div><img src="' . esc_attr( rp_thumb_src_id( $floor_plan, 'rp-property-floor-plan', 'full' ) ) . '" /></div>';
						}
					}
				}
				?>
			</section>

		</main>
		</body>
		</html>
		<?php
		exit();
	}

	add_action( 'wp_ajax_rp_create_print', 'rp_property_create_print' );
	add_action( 'wp_ajax_nopriv_rp_create_print', 'rp_property_create_print' );

endif;