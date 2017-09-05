<?php
/**
 * Show html Custom Fields page
 *
 * @author  NooTeam <suppport@nootheme.com>
 * @version 1.0
 */

/**
 * VAR
 */
	$custom_fields = rp_property_render_fields();

?><form id="tab-setting-custom-field-property-form" class="rp-validate-form">

	<div class="rp-setting-wrap">

		<?php require 'custom-field-template.php'; ?>

		<input type="hidden" name="name_option" value="property_custom_field" />

		<button class="button button-primary btn-property-submit" type="submit" name="submit" data-id-notice="<?php echo esc_attr( $id_notice ); ?>" data-id-form="tab-setting-custom-field-property-form" />
			<?php echo esc_html__( 'Save Changes', 'realty-portal' ); ?>
			<span class="hide dashicons dashicons-update"></span>
		</button>

	</div>

</form>

<form id="tab-setting-custom-field-property-primary-field-form" class="rp-validate-form">

	<div class="rp-setting-wrap-primary-field">

		<div class="rp-setting-wrap">

			<h1 class="rp-setting-title">
				<?php echo esc_html__( 'Primary Fields', 'realty-portal' ); ?>
			</h1>
			<p><?php echo esc_html__( 'Primary fields which will be show on the property listing page', 'realty-portal' ) ?></p>

			<?php
			$list_custom_field = rp_property_list_custom_fields( 'only_custom_field' );
			/**
			 * Create element: Primary Field #1
			 */
				$primary_field_1 = RP_Property::get_setting( 'primary_field', 'primary_field_1', '_area' );
				$args_primary_field_1 = array(
					'name'        => 'primary_field_1',
					'title'       => esc_html__( 'Primary Field #1', 'realty-portal' ),
					'type'        => 'select',
					'options'	  => $list_custom_field,
					'class'		  => 'rp-2-col'
				);
				rp_create_element( $args_primary_field_1, $primary_field_1, false );

			/**
			 * Create element: Image Icon
			 */
				$primary_field_icon_1 = RP_Property::get_setting( 'primary_field', 'primary_field_icon_1', 'rp-icon-ruler' );
				$args_primary_field_icon_1 = array(
					'name'        => 'primary_field_icon_1',
					'title'       => esc_html__( 'Image Icon', 'realty-portal' ),
					'type'        => 'icon',
					'class'		  => 'rp-2-col'
				);
				rp_create_element( $args_primary_field_icon_1, $primary_field_icon_1 );
				echo '<div class="clearfix"></div>';
			/**
			 * Create element: Primary Field #2
			 */
				$primary_field_2 = RP_Property::get_setting( 'primary_field', 'primary_field_2', '_bedrooms' );
				$args_primary_field_2 = array(
					'name'        => 'primary_field_2',
					'title'       => esc_html__( 'Primary Field #2', 'realty-portal' ),
					'type'        => 'select',
					'options'	  => $list_custom_field,
					'class'		  => 'rp-2-col'
				);
				rp_create_element( $args_primary_field_2, $primary_field_2, false );

			/**
			 * Create element: Image Icon
			 */
				$primary_field_icon_2 = RP_Property::get_setting( 'primary_field', 'primary_field_icon_2', 'rp-icon-bed' );
				$args_primary_field_icon_2 = array(
					'name'        => 'primary_field_icon_2',
					'title'       => esc_html__( 'Image Icon', 'realty-portal' ),
					'type'        => 'icon',
					'class'		  => 'rp-2-col'
				);
				rp_create_element( $args_primary_field_icon_2, $primary_field_icon_2 );
				echo '<div class="clearfix"></div>';

			/**
			 * Create element: Primary Field #3
			 */
				$primary_field_3 = RP_Property::get_setting( 'primary_field', 'primary_field_3', '_garages' );
				$args_primary_field_3 = array(
					'name'        => 'primary_field_3',
					'title'       => esc_html__( 'Primary Field #3', 'realty-portal' ),
					'type'        => 'select',
					'options'	  => $list_custom_field,
					'class'		  => 'rp-2-col'
				);
				rp_create_element( $args_primary_field_3, $primary_field_3, false );

			/**
			 * Create element: Image Icon
			 */
				$primary_field_icon_3 = RP_Property::get_setting( 'primary_field', 'primary_field_icon_3', 'rp-icon-garage' );
				$args_primary_field_3 = array(
					'name'        => 'primary_field_icon_3',
					'title'       => esc_html__( 'Image Icon', 'realty-portal' ),
					'type'        => 'icon',
					'class'		  => 'rp-2-col'
				);
				rp_create_element( $args_primary_field_3, $primary_field_icon_3 );
				echo '<div class="clearfix"></div>';

			/**
			 * Create element: Primary Field #4
			 */
				$primary_field_4 = RP_Property::get_setting( 'primary_field', 'primary_field_4', '_bathrooms' );
				$args_primary_field_4 = array(
					'name'        => 'primary_field_4',
					'title'       => esc_html__( 'Primary Field #4', 'realty-portal' ),
					'type'        => 'select',
					'options'	  => $list_custom_field,
					'class'		  => 'rp-2-col'
				);
				rp_create_element( $args_primary_field_4, $primary_field_4, false );

			/**
			 * Create element: Image Icon
			 */
				$primary_field_icon_4 = RP_Property::get_setting( 'primary_field', 'primary_field_icon_4', 'rp-icon-bath' );
				$args_primary_field_icon_4 = array(
					'name'        => 'primary_field_icon_4',
					'title'       => esc_html__( 'Image Icon', 'realty-portal' ),
					'type'        => 'icon',
					'class'		  => 'rp-2-col'
				);
				rp_create_element( $args_primary_field_icon_4, $primary_field_icon_4 );
			?>

		</div>
		<input type="hidden" name="name_option" value="primary_field" />

		<button class="button button-primary btn-property-submit" type="submit" name="submit" data-id-notice="<?php echo esc_attr( $id_notice ); ?>" data-id-form="tab-setting-custom-field-property-primary-field-form" />
			<?php echo esc_html__( 'Save Changes', 'realty-portal' ); ?>
			<span class="hide dashicons dashicons-update"></span>
		</button>

	</div>

</form>