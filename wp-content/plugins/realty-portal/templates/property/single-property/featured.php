<?php
/**
 * Box Featured
 *
 * @package Realty_Portal
 * @author  NooTeam <suppport@nootheme.com>
 */
global $property;
if ( !is_object( $property ) ) {
	return false;
}

$custom_feature_fields = rp_render_featured_amenities();
$property_features     = rp_render_featured_amenities();
$total_features        = count( $property_features );
unset( $custom_feature_fields[ '' ] );
if ( $total_features > 0 && ! empty( ! empty( $custom_feature_fields ) ) ) :
	?>
    <div class="rp-property-box">
        <h3 class="rp-title-box">
			<?php echo esc_html__( 'Feature', 'realty-portal' ); ?>
        </h3>
        <ul class="rp-content-box feature">
			<?php
			foreach ( $custom_feature_fields as $field ) {

				if ( ! is_array( $field ) || ! array_key_exists( 'name', $field ) ) {
					continue;
				}

				$name_field  = apply_filters( 'rp_property_post_type', 'rp_property' ) . esc_attr( $field[ 'name' ] );
				$value_field = get_post_meta( $property->ID, esc_attr( $name_field ), true );
				$checked     = '<i class="property-icon-status rp-icon-times" aria-hidden="true"></i>';
				if ( ! empty( $value_field ) ) {
					$checked = '<i class="property-icon-status rp-icon-check" aria-hidden="true"></i>';
				}
				?>
                <li class="rp-content-box-item">
					<?php if ( ! empty( $field[ 'icon' ] ) ) : ?>
                        <i class="property-icon fa <?php echo $field[ 'icon' ]; ?>" aria-hidden="true"></i>
					<?php endif; ?>
                    <label><?php echo( isset( $field[ 'label_translated' ] ) ? esc_html( $field[ 'label_translated' ] ) : esc_html( $field[ 'label' ] ) ) ?></label>
					<?php echo $checked; ?>
                </li>
				<?php
			}
			?>
        </ul><!-- /.rp-content-box .feature -->
    </div><!-- /.rp-property-box -->
	<?php
endif; 