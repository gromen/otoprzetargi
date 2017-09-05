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
?>

<div id="rp-property-<?php the_ID(); ?>" <?php post_class(); ?>>

    <div class="entry-property">

		<?php
		/**
		 * rp_before_single_property_summary hook.
		 *
		 */
		do_action( 'rp_before_single_property_summary' );
		?>

		<?php the_content() ?>

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
		do_action( 'rp_after_single_property_summary' );
		?>

    </div><!-- .entry-property -->

</div><!-- #rp-property-<?php the_ID(); ?> -->

<?php do_action( 'rp_after_single_property' ); ?>
