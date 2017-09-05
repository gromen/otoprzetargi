<?php
/**
 * Box Additional Details
 *
 * @package Realty_Portal
 * @author  NooTeam <suppport@nootheme.com>
 */
$show_additional_details = get_theme_mod( 'noo_property_enable_additional_details', true );
if ( empty( $show_additional_details ) ) {
	return false;
}
global $property;
if ( !is_object( $property ) ) {
	return false;
}

$custom_fields = rp_property_render_fields();

unset( $custom_fields[ '' ] );
?>
<div class="noo-row">
	<div class="noo-md-6 noo-property-box">
		<h3 class="noo-title-box">
			<?php echo apply_filters( 'rp_additional_details', esc_html__( 'Additional Details', 'noo-landmark' ) ); ?>
		</h3>
		<div class="noo-content-box details">

			<?php
			/**
			 * Show field listing offers
			 */
			echo $property->get_listing_offers_html();

			/**
			 * Show field Listing type
			 */
			echo $property->get_listing_type_html();
			?>

			<div class="noo-content-box-item">
				<label><?php echo esc_html__( 'Price', 'noo-landmark' ); ?></label>
				<span><?php echo $property->get_price_html(); ?></span>
			</div>

			<?php
			if ( ! empty( $custom_fields ) ) :

				foreach ( $custom_fields as $field ) {
					if ( ! is_array( $field ) || ! array_key_exists( 'name', $field ) ) {
						continue;
					}
					$name_field  = apply_filters( 'rp_property_post_type', 'rp_property' ) . $field[ 'name' ];
					$value_field = get_post_meta( $property->ID, esc_attr( $name_field ), true );

					if ( $field[ 'type' ] == 'select' ) {
						$value_field = rp_conver( $value_field, $field[ 'value' ] );
					}

					if ( ! empty( $value_field ) ) {
						?>
						<div class="noo-content-box-item">
							<label><?php echo( isset( $field[ 'label_translated' ] ) ? esc_html( $field[ 'label_translated' ] ) : esc_html( $field[ 'label' ] ) ) ?></label>
							<span><?php
								if ( is_array( $value_field ) ) {
									echo implode( ', ', $value_field );
								} else {
									echo esc_html( $value_field );
								}
								?></span>
						</div>
						<?php
					}
				}
			endif; ?>

		</div>
	</div>