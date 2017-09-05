<?php
if ( ! function_exists( 'rp_gallery_image' ) ) :
	function rp_gallery_image() {
		RP_Template::get_template( 'property/single-property/header-gallery.php' );
	}
endif;

if ( ! function_exists( 'rp_metabox_property' ) ) :
	function rp_metabox_property() {
		RP_Template::get_template( 'property/single-property/box-meta.php' );
	}
endif;

if ( ! function_exists( 'rp_metabox_field_meta' ) ) :
	function rp_metabox_field_meta() {
		RP_Template::get_template( 'property/single-property/box-field-meta.php' );
	}
endif;

if ( ! function_exists( 'rp_box_document' ) ) :
	function rp_box_document() {
		RP_Template::get_template( 'property/single-property/document.php' );
	}
endif;

if ( ! function_exists( 'rp_box_address' ) ) :
	function rp_box_address() {
		RP_Template::get_template( 'property/single-property/address.php' );
	}
endif;

if ( ! function_exists( 'rp_box_additional_details' ) ) :
	function rp_box_additional_details() {
		RP_Template::get_template( 'property/single-property/additional-details.php' );
	}
endif;

if ( ! function_exists( 'rp_box_featured' ) ) :
	function rp_box_featured() {
		RP_Template::get_template( 'property/single-property/featured.php' );
	}
endif;

if ( ! function_exists( 'rp_box_video' ) ) :
	function rp_box_video() {
		RP_Template::get_template( 'property/single-property/video.php' );
	}
endif;

if ( ! function_exists( 'rp_box_location_on_map' ) ) :
	function rp_box_location_on_map() {
		RP_Template::get_template( 'property/single-property/location-on-map.php' );
	}
endif;

if ( ! function_exists( 'rp_box_comment_property' ) ) :
	function rp_box_comment_property() {
		RP_Template::get_template( 'property/single-property/box-comment.php' );
	}
endif;

if ( ! function_exists( 'rp_property_loop_start' ) ) {

	/**
	 * Output the start of a property loop. By default this is a UL.
	 *
	 * @param bool $echo
	 *
	 * @return string
	 */
	function rp_property_loop_start( $echo = true ) {
		ob_start();
		RP_Template::get_template( 'loop/property-loop-start.php' );
		if ( $echo ) {
			echo ob_get_clean();
		} else {
			return ob_get_clean();
		}
	}
}

if ( ! function_exists( 'rp_property_loop_end' ) ) {

	/**
	 * Output the end of a property loop. By default this is a UL.
	 *
	 * @param bool $echo
	 *
	 * @return string
	 */
	function rp_property_loop_end( $echo = true ) {
		ob_start();
		RP_Template::get_template( 'loop/property-loop-end.php' );
		if ( $echo ) {
			echo ob_get_clean();
		} else {
			return ob_get_clean();
		}
	}
}

if ( ! function_exists( 'rp_property_result_count' ) ) {

	function rp_property_result_count() {
		ob_start();
		RP_Template::get_template( 'loop/result-count.php' );
		echo ob_get_clean();
	}
}

if ( ! function_exists( 'rp_pagination' ) ) {

	/**
	 * Output the pagination.
	 *
	 * @subpackage    Loop
	 */
	function rp_pagination( $query = array(), $args = array() ) {
		ob_start();
		RP_Template::get_template( 'loop/pagination.php', array(
			'args'  => $args,
			'query' => $query,
		) );
		echo ob_get_clean();
	}
}

if ( ! function_exists( 'rp_message_notices' ) ) {
	function rp_message_notices( $error ) {
		ob_start();
		RP_Template::get_template( "notices/{$error->get_error_code()}.php", array(
			'messages' => $error->get_error_message(),
		) );
		echo ob_get_clean();
	}
}

if ( ! function_exists( 'rp_saved_search_main_page' ) ) {
	function rp_saved_search_main_page() {
		ob_start();
		RP_Template::get_template( 'saved-search/main.php' );
		echo ob_get_clean();
	}
}

if ( ! function_exists( 'rp_saved_search_list_search' ) ) {
	function rp_saved_search_list_search() {
		ob_start();
		RP_Template::get_template( 'saved-search/list-search.php' );
		echo ob_get_clean();
	}
}

if ( ! function_exists( 'rp_property_detail_comment' ) ) :
	function rp_property_detail_comment( $comment, $args, $depth ) {
		RP_Template::get_template( 'property/comment/detail-comment.php', array(
			'comment' => $comment,
			'args'    => $args,
			'depth'   => $depth,
		) );
	}
endif;

if ( ! function_exists( 'rp_single_property_featured_item' ) ) :
	function rp_single_property_featured_item( $property ) {
		if ( $property->is_featured() ) {
			echo '<li class="featured-item">' . $property->is_featured() . '</li>';
		}
	}
endif;

if ( ! function_exists( 'rp_single_property_print' ) ) :
	function rp_single_property_print( $property ) {
		?>
		<li>
			<i class="rp-event rp-icon-ion-printer" aria-hidden="true" data-id="<?php echo esc_attr( $property->ID ); ?>"
			   data-process="print"></i>
		</li>
		<?php
	}
endif;

if ( ! function_exists( 'rp_single_property_share' ) ) :
	function rp_single_property_share( $property ) {
		?>
		<li class="rp-property-sharing">
			<i class="rp-event rp-icon-ion-android-share-alt" data-id="<?php echo esc_attr( $property->ID ); ?>"
			   data-process="share"></i>
			<?php rp_social_sharing_property( $property->ID ); ?>
		</li>
		<?php
	}
endif;