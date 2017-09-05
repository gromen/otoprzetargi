<?php
/**
 * Box Address
 *
 * @package Realty_Portal
 * @author  NooTeam <suppport@nootheme.com>
 */
global $property;
if ( !is_object( $property ) ) {
	return false;
}

$location_address      = RP_Property::get_setting( 'google_map', 'location_address', true );
$location_country      = RP_Property::get_setting( 'google_map', 'location_country', true );
$location_city         = RP_Property::get_setting( 'google_map', 'location_city', true );
$location_neighborhood = RP_Property::get_setting( 'google_map', 'location_neighborhood', true );
$location_zip          = RP_Property::get_setting( 'google_map', 'location_zip', true );

if ( $location_address || $location_country || $location_city || $location_neighborhood || $location_zip ) :
	$address      = get_post_meta( $property->ID, 'address', true );
	$country      = get_post_meta( $property->ID, 'country', true );
	$city         = get_post_meta( $property->ID, 'city', true );
	$neighborhood = get_post_meta( $property->ID, 'neighborhood', true );
	$zip          = get_post_meta( $property->ID, 'zip', true );

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
    <div class="rp-property-box">
        <h3 class="rp-title-box">
			<?php echo esc_html__( 'Address', 'realty-portal' ); ?>
        </h3>
        <ul class="rp-content-box address">
			<?php if ( $location_address || $location_country || $location_city ) : ?>

				<?php if ( ! empty( $address ) && $location_address ) : ?>
                    <li class="rp-content-box-item">
                        <label><?php echo esc_html__( 'Address', 'realty-portal' ); ?></label>
                        <span><?php echo esc_attr( $address ) ?></span>
                    </li>
				<?php endif; ?>

				<?php if ( ! empty( $country ) && $location_country ) : ?>
                    <li class="rp-content-box-item">
                        <label><?php echo esc_html__( 'Country', 'realty-portal' ); ?></label>
                        <span><?php echo esc_attr( $country ) ?></span>
                    </li>
				<?php endif; ?>

				<?php if ( ! empty( $city ) && $location_city ) : ?>
                    <li class="rp-content-box-item">
                        <label><?php echo esc_html__( 'City', 'realty-portal' ); ?></label>
                        <span><?php echo esc_attr( $city ) ?></span>
                    </li>
				<?php endif; ?>

			<?php endif; ?>

			<?php if ( $location_neighborhood || $location_zip ) : ?>

				<?php if ( ! empty( $neighborhood ) && $location_neighborhood ) : ?>
                    <li class="rp-content-box-item">
                        <label><?php echo esc_html__( 'Neighborhood', 'realty-portal' ); ?></label>
                        <span><?php echo esc_attr( $neighborhood ) ?></span>
                    </li>
				<?php endif; ?>

				<?php if ( ! empty( $zip ) && $location_zip ) : ?>
                    <li class="rp-content-box-item">
                        <label><?php echo esc_html__( 'Zip', 'realty-portal' ); ?></label>
                        <span><?php echo esc_attr( $zip ) ?></span>
                    </li>
				<?php endif; ?>

			<?php endif; ?>

        </ul>
    </div>
<?php endif; ?>