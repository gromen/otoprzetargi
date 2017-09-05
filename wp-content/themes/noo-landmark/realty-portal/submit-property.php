<?php
/**
 * Show content main shortcode submit property
 *
 * This template can be overridden by copying it to yourtheme/realty-portal/shortcode/submit-property.php.
 *
 * HOWEVER, on occasion NooTheme will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author        NooTheme
 * @version       0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php if ( is_wp_error( RP_Agent::can_add() ) ) : ?>

	<?php
	/**
	 * rp_error_main_submit_property hook.
	 *
	 * @hooked rp_message_notices - 5
	 */
	do_action( 'rp_error_main_submit_property', RP_Agent::can_add() );
	?>

<?php else : ?>

	<?php
	wp_enqueue_script( 'google-map' );
	$property_id      = '';
	$btn_submit       = esc_html__( 'Submit property', 'noo-landmark' );
	$featured_value   = '';
	$process_property = 'create_property';
	if ( ! empty( $_GET[ 'edit-property' ] ) ) {
		$property_id      = rp_validate_data( $_GET[ 'edit-property' ], 'int' );
		$btn_submit       = esc_html__( 'Update property', 'noo-landmark' );
		$featured_value   = get_post_meta( $property_id, '_thumbnail_id', true );
		$process_property = 'edit_property';
	}
	?>

	<form method="POST" id="rp-property-submit" class="rp-validate-form rp-content">

		<?php
		$args_title = array(
			'name'        => 'title',
			'title'       => esc_html__( 'Property title', 'noo-landmark' ),
			'type'        => 'text',
			'class'       => 'noo-item-wrap',
			'placeholder' => esc_html__( 'Please enter property title...', 'noo-landmark' ),
			'validate'    => array(
				'data-validation'           => 'length',
				'data-validation-length'    => 'min6',
				'data-validation-help'      => esc_html__( '6 or more characters, letters and numbers', 'noo-landmark' ),
				'data-validation-error-msg' => esc_html__( 'Not be blank property title', 'noo-landmark' ),
			),
		);
		rp_create_element( $args_title, RP_Agent::get_value_submit( $property_id, 'title' ) );

		$args_description = array(
			'name'           => 'description',
			'title'          => '',
			'type'           => 'textarea',
			'class'          => 'noo-item-wrap',
			'support_editor' => true,
			'validate'       => array(
				'data-validation'           => 'length',
				'data-validation-length'    => 'min6',
				'data-validation-help'      => esc_html__( '6 or more characters, letters and numbers', 'noo-landmark' ),
				'data-validation-error-msg' => esc_html__( 'Not be blank Property title', 'noo-landmark' ),
			),
		);
		rp_create_element( $args_description, RP_Agent::get_value_submit( $property_id, 'description' ) );

		$args_property_photo = array(
			'name'         => 'property_photo',
			'title'        => esc_html__( 'Property photo', 'noo-landmark' ),
			'type'         => 'upload_image',
			'class'        => 'noo-item-wrap',
			'btn_text'     => esc_html__( 'Upload photo', 'noo-landmark' ),
			'set_featured' => 'true',
			'post_id'      => $property_id,
			'after_notice' => esc_html__( 'At least 1 image is required for a valid submission. The featured image will be used to dispaly on property listing page.', 'noo-landmark' ),
		);
		rp_create_element( $args_property_photo, RP_Agent::get_value_submit( $property_id, 'property_photo' ) );

		$listing_offers = rp_get_list_tax( 'listing_offers' );
		if ( isset( $listing_offers ) && ! empty( $listing_offers ) ) {
			$args_offers = array(
				'name'        => 'offers',
				'title'       => esc_html__( 'Offers', 'noo-landmark' ),
				'type'        => 'select',
				'class'       => 'noo-item-wrap',
				'placeholder' => esc_html__( 'Select offers', 'noo-landmark' ),
				'options'     => $listing_offers,
			);
			rp_create_element( $args_offers, RP_Agent::get_value_submit( $property_id, 'offers' ) );
		}

		$currency_symbol = rp_currency_symbol();
		$args_price      = array(
			'name'        => 'price',
			'title'       => sprintf( esc_html__( 'Price (%s)', 'noo-landmark' ), $currency_symbol ),
			'type'        => 'text',
			'class'       => 'noo-item-wrap',
			'placeholder' => esc_html__( 'Please enter price...', 'noo-landmark' ),
			'validate'    => array(
				'data-validation' => 'number',
			),
		);
		rp_create_element( $args_price, RP_Agent::get_value_submit( $property_id, 'price' ) );

		$args_before_price = array(
			'name'        => 'before_price',
			'title'       => esc_html__( 'Before price label', 'noo-landmark' ),
			'type'        => 'text',
			'class'       => 'noo-item-wrap',
			'placeholder' => esc_html__( 'From', 'noo-landmark' ),
		);
		rp_create_element( $args_before_price, RP_Agent::get_value_submit( $property_id, 'before_price' ) );

		$args_after_price = array(
			'name'        => 'after_price',
			'title'       => esc_html__( 'After price label', 'noo-landmark' ),
			'type'        => 'text',
			'class'       => 'noo-item-wrap',
			'placeholder' => esc_html__( 'Per Month', 'noo-landmark' ),
		);
		rp_create_element( $args_after_price, RP_Agent::get_value_submit( $property_id, 'after_price' ) );

		$listing_type = rp_get_list_tax( 'property_type' );
		if ( isset( $listing_type ) && ! empty( $listing_type ) ) {
			$args_type = array(
				'name'        => 'type',
				'title'       => esc_html__( 'Type', 'noo-landmark' ),
				'type'        => 'select',
				'class'       => 'noo-item-wrap',
				'placeholder' => esc_html__( 'Select type', 'noo-landmark' ),
				'options'     => $listing_type,
			);
			rp_create_element( $args_type, RP_Agent::get_value_submit( $property_id, 'type' ) );
		}

		$args_custom_field = array(
			'name'        => 'custom_fields',
			'type'        => 'custom_fields',
			'class'       => 'noo-item-wrap',
			'property_id' => $property_id,
		);
		rp_create_element( $args_custom_field, RP_Agent::get_value_submit( $property_id, 'custom_field' ) );

		$args_video = array(
			'name'        => 'video',
			'title'       => esc_html__( 'Property Video', 'noo-landmark' ),
			'type'        => 'text',
			'class'       => 'noo-item-wrap',
			'placeholder' => esc_html__( 'Link http://', 'noo-landmark' ),
		);
		rp_create_element( $args_video, RP_Agent::get_value_submit( $property_id, 'video' ) );

		$args_document = array(
			'name'        => 'document',
			'title'       => esc_html__( 'Property Video', 'noo-landmark' ),
			'type'        => 'text',
			'class'       => 'noo-item-wrap',
			'placeholder' => esc_html__( 'Link http://', 'noo-landmark' ),
		);
		rp_create_element( $args_document, RP_Agent::get_value_submit( $property_id, 'document' ) );

		$property_features = rp_render_featured_amenities();
		$total_features    = count( $property_features );
		if ( $total_features > 1 ) {

			$args_property_featured = array(
				'name'        => 'property_featured',
				'title'       => esc_html__( 'Property feature', 'noo-landmark' ),
				'type'        => 'property_featured',
				'class'       => 'noo-item-wrap',
				'property_id' => $property_id,
			);
			rp_create_element( $args_property_featured, RP_Agent::get_value_submit( $property_id, 'property_featured' ) );
		}

		$enable_floor_plan = get_theme_mod( 'noo_property_enable_floor_plan', true );

		if ( noo_landmark_is_plugin_active( 'realty-portal-floor-plan/realty-portal-floor-plan.php' ) && !empty( $enable_floor_plan ) ) {

			$args_floor_plan = array(
				'name'        => 'floor_plan_wrap',
				'title'       => esc_html__( 'Floor plans', 'noo-landmark' ),
				'type'        => 'floor_plans',
				'class'       => 'noo-item-wrap',
				'property_id' => $property_id,
			);
			rp_create_element( $args_floor_plan, '' );
		}
		$args_address = array(
			'name'        => 'address',
			'title'       => esc_html__( 'Address', 'noo-landmark' ),
			'type'        => 'text',
			'class'       => 'noo-item-wrap',
			'placeholder' => esc_html__( 'Please enter address...', 'noo-landmark' ),
			'validate'    => array(
				'data-validation'           => 'required',
				'data-validation-error-msg' => esc_html__( 'Not be blank address', 'noo-landmark' ),
			),
		);
		rp_create_element( $args_address, RP_Agent::get_value_submit( $property_id, 'address' ) );

		$args_country = array(
			'name'        => 'country',
			'title'       => esc_html__( 'Country', 'noo-landmark' ),
			'type'        => 'select',
			'class'       => 'noo-item-wrap',
			'placeholder' => esc_html__( 'Select Country', 'noo-landmark' ),
			'list'        => true,
			'options'     => rp_list_country(),
		);

		rp_create_element( $args_country, RP_Agent::get_value_submit( $property_id, 'country' ) );

		$args_city = array(
			'name'        => 'city',
			'title'       => esc_html__( 'City', 'noo-landmark' ),
			'type'        => 'text',
			'class'       => 'noo-item-wrap',
			'placeholder' => esc_html__( 'Please enter city...', 'noo-landmark' ),
			'required'    => true,
			'data_notice' => esc_html__( 'Not be blank City', 'noo-landmark' ),
		);
		rp_create_element( $args_city, RP_Agent::get_value_submit( $property_id, 'city' ) );

		$args_neighborhood = array(
			'name'        => 'neighborhood',
			'title'       => esc_html__( 'Neighborhood', 'noo-landmark' ),
			'type'        => 'text',
			'class'       => 'noo-item-wrap',
			'placeholder' => esc_html__( 'Please enter neighborhood...', 'noo-landmark' ),
			'data_notice' => esc_html__( 'Not be blank Neighborhood', 'noo-landmark' ),
		);
		rp_create_element( $args_neighborhood, RP_Agent::get_value_submit( $property_id, 'neighborhood' ) );

		$args_zip = array(
			'name'        => 'zip',
			'title'       => esc_html__( 'Zip', 'noo-landmark' ),
			'type'        => 'text',
			'class'       => 'noo-item-wrap',
			'placeholder' => esc_html__( 'Please enter zip...', 'noo-landmark' ),
			'data_notice' => esc_html__( 'Not be blank Zip', 'noo-landmark' ),
		);
		rp_create_element( $args_zip, RP_Agent::get_value_submit( $property_id, 'zip' ) );

		$args_state = array(
			'name'        => 'state',
			'title'       => esc_html__( 'State', 'noo-landmark' ),
			'type'        => 'text',
			'class'       => 'noo-item-wrap',
			'placeholder' => esc_html__( 'Please enter state...', 'noo-landmark' ),
			'data_notice' => esc_html__( 'Not be blank State', 'noo-landmark' ),
		);
		rp_create_element( $args_state, RP_Agent::get_value_submit( $property_id, 'state' ) );

		$args_latitude = array(
			'name'        => 'latitude',
			'title'       => esc_html__( 'Latitude', 'noo-landmark' ),
			'type'        => 'text',
			'class'       => 'noo-item-wrap',
			'placeholder' => esc_html__( 'Please enter latitude...', 'noo-landmark' ),
			'validate'    => array(
				'data-validation'           => 'required',
				'data-validation-error-msg' => esc_html__( 'Not be blank latitude', 'noo-landmark' ),
			),
		);
		rp_create_element( $args_latitude, RP_Agent::get_value_submit( $property_id, 'latitude' ) );

		$args_longitude = array(
			'name'        => 'longitude',
			'title'       => esc_html__( 'Longitude', 'noo-landmark' ),
			'type'        => 'text',
			'class'       => 'noo-item-wrap',
			'placeholder' => esc_html__( 'Please enter longitude...', 'noo-landmark' ),
			'validate'    => array(
				'data-validation'           => 'required',
				'data-validation-error-msg' => esc_html__( 'Not be blank longitude', 'noo-landmark' ),
			),
		);
		rp_create_element( $args_longitude, RP_Agent::get_value_submit( $property_id, 'longitude' ) );

		$args_map = array(
			'name'  => 'map',
			'title' => esc_html__( 'Set address on map', 'noo-landmark' ),
			'type'  => 'gmap',
			'class' => 'noo-item-wrap',
		);
		rp_create_element( $args_map, RP_Agent::get_value_submit( $property_id, 'map' ) );
		?>

		<?php
		/**
		 * rp_after_main_page_submit_property hook.
		 *
		 */
		do_action( 'rp_after_main_page_submit_property', $property_id );
		?>

		<div class="rp-property-action">
			<button type="submit" class="rp-button">
				<?php echo esc_html( $btn_submit ); ?>
				<i class="fa-li rp-icon-spinner fa-spin hide"></i>
			</button>
		</div>

		<input type="hidden" name="process_property" value="<?php echo esc_attr( $process_property ); ?>" />
		<input type="hidden" name="set_featured" id="set_featured" value="<?php echo esc_attr( $featured_value ); ?>" />
		<input type="hidden" name="agent_id" value="<?php echo RP_Agent::is_agent(); ?>" />
		<input type="hidden" name="property_id" value="<?php echo esc_attr( $property_id ); ?>" />

	</form>

<?php endif; ?>