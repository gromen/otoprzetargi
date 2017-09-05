<?php
/**
 * Box Location on Map
 *
 * @package Realty_Portal
 * @author  NooTeam <suppport@nootheme.com>
 */
if ( RP_Property::get_setting( 'google_map', 'location_maps', true ) ) :
	
	global $property;
	if ( !is_object( $property ) ) {
		return false;
	}

	wp_enqueue_style( 'google-map-icon' );
	wp_enqueue_script( 'google-map' );
	$title       = wp_trim_words( get_the_title( $property->ID ), 7 );
	$url         = get_permalink( $property->ID );
	$image       = rp_thumb_src( $property->ID, 'rp-property-map', '180x150' );
	$price       = get_post_meta( $property->ID, 'price', true );
	$bathrooms   = get_post_meta( $property->ID, 'rp_property_bathrooms', true );
	$bedrooms    = get_post_meta( $property->ID, 'rp_property_bedrooms', true );
	$garages     = get_post_meta( $property->ID, 'rp_property_garages', true );
	$id_map      = uniqid( 'id-map' );
	?>
	<div class="rp-property-box rp-box-map small">
		<h3 class="rp-title-box">
			<?php echo esc_html__( 'Location On Map', 'realty-portal' ); ?>
		</h3>
		<div class="rp-content-box-map">
			<div class="rp-gmap" data-id="<?php echo esc_attr( $id_map ); ?>">
		      	<div id="<?php echo esc_attr( $id_map ); ?>" style="height: 455px;"></div>
		      	<input type="hidden" id="latitude" name="latitude" value="<?php echo get_post_meta( $property->ID, 'latitude', true ); ?>" />
		      	<input type="hidden" id="longitude" name="longitude" value="<?php echo get_post_meta( $property->ID, 'longitude', true ); ?>" />
		    </div>
		</div>
	</div>

<?php endif; ?>