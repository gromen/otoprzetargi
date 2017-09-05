<?php 
/**
 * Tabs
 *
 * @package LandMark
 * @author  JAMES <luyentv@vietbrain.com>
 */
	/**
	 * Var
	 */
		$address                   = get_post_meta( $property->ID, 'address', true );
		$price                     = rp_property_price( $property->ID );
		$property_status           = get_the_terms( $property->ID, 'property_status' );
		
		$enable_address            = get_theme_mod( 'noo_property_enable_address', true );
		$enable_additional_details = get_theme_mod( 'noo_property_enable_additional_details', true );
		$enable_feature            = get_theme_mod( 'noo_property_enable_feature', true );
		$enable_floor_plan         = get_theme_mod( 'noo_property_enable_floor_plan', true );
		$enable_video              = get_theme_mod( 'noo_property_enable_video', true );

	/**
	 * @hook noo_before_property_detail_content
	 */
		do_action( 'noo_before_property_detail_content', $property->ID );
?>

<div class="noo-detail-header">
	<h2 class="noo-detail-title">
		<?php the_title(); ?>
	</h2>
	<?php 
		if ( !empty( $property_status ) && ! is_wp_error( $property_status ) ) {
            $types = array();
            foreach( $property_status as $status ) {
                $types[] = $status->name;
            }
			echo '<span class="property-status">' . implode(', ', $types) . '</span>'; 
        }
	?>
	<div class="noo-action-post">

		<?php if ( !empty( $show_favories ) ) : ?>
			<i class="noo-tooltip-action fa <?php echo esc_attr( $icon_favorites ); ?>" data-id="<?php echo esc_attr( $property->ID ); ?>" data-user="<?php echo esc_attr( $user_id ); ?>" data-process="favorites" data-status="<?php echo esc_attr( $class_favorites ); ?>" data-content="<?php echo esc_html__( 'Favorites', 'noo-landmark' ) ?>" data-url="<?php echo esc_attr( $url_page_favorites ); ?>"></i>
		<?php endif; ?>
					
		<?php if ( !empty( $show_social ) ) : ?>
			<div class="noo-property-sharing">
				<span>
					<i class="noo-tooltip-action ion-android-share-alt" data-id="<?php echo esc_attr( $property->ID ); ?>" data-process="share"></i>
				</span>
				<?php rp_social_sharing_property(); ?>
			</div>
		<?php endif; ?>

		<?php if ( !empty( $show_print ) ) : ?>
			<i class="noo-tooltip-action ion-printer" aria-hidden="true"  data-id="<?php echo esc_attr( $property->ID ); ?>" data-process="print"></i>
		<?php endif; ?>

	</div>
	<div class="noo-info">
		<?php if ( !empty( $address ) ) : ?>
			<span class="location">
				<?php echo esc_html( $address ); ?>
			</span>
		<?php endif; ?>
		<div class="noo-price">
			<?php echo wp_kses( $price, noo_allowed_html() ); ?>
		</div>		
	</div>

</div>

<div class="noo-detail-tabs">
	
	<div class="noo-tab">
	    <?php
		    if( get_the_content() )
		    	echo '<span class="active" data-class="tab-description">'.esc_html__( 'Description', 'noo-landmark' ).'</span>';

		    if( $enable_address )
		    	echo '<span class="" data-class="tab-address">'.esc_html__( 'Address', 'noo-landmark' ).'</span>';
	    	
			if( $enable_additional_details || $enable_feature )
		    	echo '<span class="" data-class="tab-detail-feature">'.esc_html__( 'Detail/Feature', 'noo-landmark' ).'</span>';

		    $floor_plans = get_post_meta( $property->ID, 'floor_plans', true );
		    $check_floor_plans = false;
		    if( $floor_plans && is_array( $floor_plans ) ){

			    foreach ($floor_plans as $key => $value) {
			    	if( array_filter($floor_plans[$key]) ){
			    		$check_floor_plans = true;
			    		break;
			    	}
			    }
			    
		    }
		    $floor_plan  = get_post_meta( $property->ID, 'floor_plan', true );
		    if( $enable_floor_plan && ( $check_floor_plans || !empty($floor_plan)) )
		    	echo '<span class="" data-class="tab-floor-plan">'.esc_html__( 'Floor Plan', 'noo-landmark' ).'</span>';
		    
		    $video = get_post_meta( $property->ID, 'video', true );
		    if( $enable_video && $video )
		    	echo '<span class="" data-class="tab-video">'.esc_html__( 'Video', 'noo-landmark' ).'</span>';
		?>
	</div>
	
	<div class="noo-tab-content">
	    <div class="content-tab tab-description show">
	        <div class="noo-detail-content">
				<?php
					the_content();
					noo_property_add_document( $property->ID );
				?>
			</div>
	    </div>
	    <div class="content-tab tab-address">
	        <?php 
	        	// Hook: @noo_property_add_address
	        	do_action( 'noo_property_detail_tab_address', $property->ID );
        	?>
	    </div>
	    <div class="content-tab tab-detail-feature">
	        <?php
	        	// Hook: @noo_property_add_custom_field
	        	do_action( 'noo_property_detail_tab_detail_feature', $property->ID );
        	?>
	    </div>
	    <div class="content-tab tab-floor-plan">
	        <?php
	        	// Hook: @noo_property_add_floor_plan
	        	do_action( 'noo_property_detail_tab_floor_plan', $property->ID );
        	?>
	    </div>
	    <div class="content-tab tab-video">
	        <?php
	        	// Hook: @noo_property_add_video
	        	do_action( 'noo_property_detail_tab_video', $property->ID );
        	?>
	    </div>
	</div>
	
</div>

<?php
	do_action( 'noo_after_property_detail_tab_content', $property->ID );
?>