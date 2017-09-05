<?php
/**
 * Single Property Content
 *
 * @package Realty_Portal
 * @author  NooTeam <suppport@nootheme.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $property;
?>

<?php
/**
 * rp_before_single_property hook.
 *
 */
do_action( 'rp_before_single_property' );

if ( post_password_required() ) {
	echo get_the_password_form();

	return;
}
$body_style = isset( $_GET[ 'content_style' ] ) ? $_GET[ 'content_style' ] : get_theme_mod( 'noo_property_post_content_style', 'default' );
$body_style = apply_filters( 'noo_property_body_style', $body_style, $property->ID );
if ( 'tab' == $body_style ) {
	remove_action( 'rp_after_single_property_summary', 'rp_box_address', 10 );
	remove_action( 'rp_after_single_property_summary', 'rp_box_additional_details', 15 );
	remove_action( 'rp_after_single_property_summary', 'rp_box_featured', 20 );
	remove_action( 'rp_after_single_property_summary', 'rp_box_address', 10 );
	remove_action( 'rp_after_single_property_summary', 'RP_Floor_Plan_Process::rp_box_floor_plan' , 30 );
	remove_action( 'rp_after_single_property_summary', 'rp_box_video', 25 );
	remove_action( 'rp_after_single_property_summary', 'rp_box_comment_property', 40 );

	add_action( 'noo_property_detail_tab_detail_feature', 'rp_box_additional_details', 5 );
	add_action( 'noo_property_detail_tab_detail_feature', 'rp_box_featured', 10 );
	add_action( 'noo_property_detail_tab_address', 'rp_box_address', 10 );
	add_action( 'noo_property_detail_tab_video', 'rp_box_video', 25 );
	add_action( 'noo_property_detail_tab_floor_plan', 'RP_Floor_Plan_Process::rp_box_floor_plan' , 30 );
	add_action( 'rp_after_single_property_summary', 'rp_box_comment_property', 150 );
}
?>
<div class="noo-single-property-detail <?php echo apply_filters( 'noo_property_post_body_style', $body_style, $property->ID ); ?>">
	<?php RP_Template::get_template( 'property/body-style/' . $body_style . '.php', array( 'property' => $property ), '', REALTY_PORTAL_TEMPLATE ); ?>

	<?php if ( 'tab' !== $body_style ) : ?>
		<div class="noo-detail-content">
			<?php the_content(); ?>
		</div>
	<?php endif; ?>

	<?php
	/**
	 * rp_after_single_property_summary hook.
	 *
	 * @hooked rp_box_document - 5
	 * @hooked rp_box_address - 10
	 * @hooked rp_box_additional_details - 15
	 * @hooked rp_box_featured - 20
	 * @hooked rp_box_video - 25
	 * @hooked rp_box_floor_plan - 30
	 * @hooked rp_box_location_on_map - 35
	 * @hooked rp_box_comment_property - 40
	 * @hooked rp_box_agent_contact - 45
	 */
	do_action( 'rp_after_single_property_summary', $property );
	?>
</div>