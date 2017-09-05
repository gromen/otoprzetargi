<?php
/**
 * Show form custom field property
 *
 * @author         NooTeam <suppport@nootheme.com>
 * @version        0.1
 */
if ( ! function_exists( 'rp_form_custom_field_property_tab' ) ) :

	function rp_form_custom_field_property_tab() {

		echo '<div class="rp-setting-form" id="tab-setting-custom-field-property">';
		include( REALTY_PORTAL_FRAMEWORK . '/admin/page/custom-field-property.php' );
		echo '</div>';
	}

	add_action( 'RP_Tab_Setting_Content/Custom_Fields_After', 'rp_form_custom_field_property_tab', 5 );

endif;