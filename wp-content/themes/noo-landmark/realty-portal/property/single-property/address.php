<?php
/**
 * Box Address
 *
 * @package Realty_Portal
 * @author  NooTeam <suppport@nootheme.com>
 */
$show_address = get_theme_mod( 'noo_property_enable_address', true );
if ( empty( $show_address ) ) {
	return false;
}
global $post;
if ( !is_object( $post ) ) {
	return false;
}

$location_address      = RP_Property::get_setting( 'google_map', 'location_address', true );
$location_country      = RP_Property::get_setting( 'google_map', 'location_country', true );
$location_city         = RP_Property::get_setting( 'google_map', 'location_city', true );
$location_neighborhood = RP_Property::get_setting( 'google_map', 'location_neighborhood', true );
$location_zip          = RP_Property::get_setting( 'google_map', 'location_zip', true );

if ( $location_address || $location_country || $location_city || $location_neighborhood || $location_zip ) :
	$address = get_post_meta( $post->ID, 'address', true );
	$country           = get_post_meta( $post->ID, 'country', true );
	$city              = get_post_meta( $post->ID, 'city', true );
	$neighborhood      = get_post_meta( $post->ID, 'neighborhood', true );
	$zip               = get_post_meta( $post->ID, 'zip', true );

	$list_country = rp_list_country();
	$list_country = rp_list_country();
	if ( ! empty( $country ) ) {
		$key_country = array_search( $country, array_column( $list_country, 'value' ) );
		$country     = '';
		if ( ! empty( $list_country[ $key_country ][ 'label' ] ) ) {
			$country = $list_country[ $key_country ][ 'label' ];
		}
	}
	?>
	<div class="noo-property-box">
		<h3 class="noo-title-box">
			<?php echo esc_html__( 'Address', 'realty-portal' ); ?>
		</h3>
		<div class="noo-content-box address noo-row">
			<?php if ( $location_address || $location_country || $location_city ) : ?>

				<div class="noo-md-6 noo-column-left">
					<?php if ( ! empty( $address ) && $location_address ) : ?>
						<div class="noo-content-box-item">
							<label><?php echo esc_html__( 'Address', 'realty-portal' ); ?></label>
							<span><?php echo esc_attr( $address ) ?></span>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $country ) && $location_country ) : ?>
						<div class="noo-content-box-item">
							<label><?php echo esc_html__( 'Country', 'realty-portal' ); ?></label>
							<span><?php echo esc_attr( $country ) ?></span>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $city ) && $location_city ) : ?>
						<div class="noo-content-box-item">
							<label><?php echo esc_html__( 'City', 'realty-portal' ); ?></label>
							<span><?php echo esc_attr( $city ) ?></span>
						</div>
					<?php endif; ?>

				</div>

			<?php endif; ?>

			<?php if ( $location_neighborhood || $location_zip ) : ?>

				<div class="noo-md-6 noo-column-right">

					<?php if ( ! empty( $neighborhood ) && $location_neighborhood ) : ?>
						<div class="noo-content-box-item">
							<label><?php echo esc_html__( 'Neighborhood', 'realty-portal' ); ?></label>
							<span><?php echo esc_attr( $neighborhood ) ?></span>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $zip ) && $location_zip ) : ?>
						<div class="noo-content-box-item">
							<label><?php echo esc_html__( 'Zip', 'realty-portal' ); ?></label>
							<span><?php echo esc_attr( $zip ) ?></span>
						</div>
					<?php endif; ?>

				</div>

			<?php endif; ?>

		</div>
	</div>
<?php endif; ?>