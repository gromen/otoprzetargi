<?php
/**
 * Default
 *
 * @package LandMark
 * @author  KENT <tuanlv@vietbrain.com>
 */
?>
<div class="noo-detail-header">
	<h3 class="noo-detail-title">
		<?php echo esc_html__( 'Description', 'noo-landmark' ); ?>
	</h3>
	<div class="noo-action-post more-action">

		<?php do_action( 'rp_property_list_more_action', $property ); ?>

	</div>
</div>