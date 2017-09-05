<?php
$primary_field_1      = rp_get_data_field( $property_id, Realty_Portal::get_setting( 'primary_field', 'primary_field_1', '_area' ) );
$primary_field_icon_1 = rp_get_data_field_icon( Realty_Portal::get_setting( 'primary_field', 'primary_field_icon_1', 'icon-ruler' ) );

$primary_field_2      = rp_get_data_field( $property_id, Realty_Portal::get_setting( 'primary_field', 'primary_field_2', '_bedrooms' ) );
$primary_field_icon_2 = rp_get_data_field_icon( Realty_Portal::get_setting( 'primary_field', 'primary_field_icon_2', 'icon-bed' ) );

$primary_field_3      = rp_get_data_field( $property_id, Realty_Portal::get_setting( 'primary_field', 'primary_field_3', '_garages' ) );
$primary_field_icon_3 = rp_get_data_field_icon( Realty_Portal::get_setting( 'primary_field', 'primary_field_icon_3', 'icon-storage' ) );

$primary_field_4      = rp_get_data_field( $property_id, Realty_Portal::get_setting( 'primary_field', 'primary_field_4', '_bathrooms' ) );
$primary_field_icon_4 = rp_get_data_field_icon( Realty_Portal::get_setting( 'primary_field', 'primary_field_icon_4', 'icon-bath' ) );
?>
<div class="noo-property-item <?php echo esc_attr( $class_column ) ?>">
	
	<div class="noo-property-item-wrap">

		<div class="noo-item-featured">
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
				<img src="<?php echo noo_thumb_src( $property_id, 'noo-property-medium' ); ?>" alt="<?php the_title(); ?>" />
			</a>
			<?php
				$property_status = get_the_terms( $property_id, 'property_status' );

	            if ( !empty( $property_status ) && ! is_wp_error( $property_status ) ) {
	                $types = array();
	                foreach( $property_status as $status ) {
	                    $types[] = $status->name;
	                }
	                echo '<span class="property-status">' . implode(', ', $types) . '</span>';
	            }
			?>
			<span class="noo-price">
				<?php echo rp_property_price( $property_id ); ?>
			</span>
		</div>
		
		<div class="noo-item-head">

			<h4 class="item-title">
				<?php
				/**
				 * Check property is feautred
				 */
				$featured = get_post_meta( $property_id, '_featured', true ); 
				if ( !empty( $featured ) && $featured === 'yes' ) {
					echo '<i class="ion-bookmark">' . esc_html__( 'Featured', 'noo-landmark-core' ) . '</i>';
				}
				?>
				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
					<?php the_title(); ?>
				</a>
			</h4>

			<?php if ( !empty( $address ) ) : ?>
				<span class="location">
					<?php echo esc_html( $address ); ?>
				</span>
			<?php endif; ?>

		</div>

		<div class="noo-info">
			<?php if ( !empty( $primary_field_1 ) ) : ?>
				<span class="noo-primary-file-1">
					<?php echo wp_kses( $primary_field_icon_1, noo_allowed_html() ); ?>
					<span><?php echo wp_kses( $primary_field_1, noo_allowed_html() ); ?></span>
				</span>
			<?php endif; ?>
			<?php if ( !empty( $primary_field_2 ) ) : ?>
				<span class="noo-primary-file-2">
					<?php echo wp_kses( $primary_field_icon_2, noo_allowed_html() ); ?>
					<span><?php echo wp_kses( $primary_field_2, noo_allowed_html() ); ?></span>
				</span>
			<?php endif; ?>
			<?php if ( !empty( $primary_field_3 ) ) : ?>
				<span class="noo-primary-file-3">
					<?php echo wp_kses( $primary_field_icon_3, noo_allowed_html() ); ?>
					<span><?php echo wp_kses( $primary_field_3, noo_allowed_html() ); ?></span>
				</span>
			<?php endif; ?>
			<?php if ( !empty( $primary_field_4 ) ) : ?>
				<span class="noo-primary-file-4">
					<?php echo wp_kses( $primary_field_icon_4, noo_allowed_html() ); ?>
					<span><?php echo wp_kses( $primary_field_4, noo_allowed_html() ); ?></span>
				</span>
			<?php endif; ?>
		</div>

	</div><!-- /.noo-property-item-wrap -->

</div><!-- /.noo-property-item -->