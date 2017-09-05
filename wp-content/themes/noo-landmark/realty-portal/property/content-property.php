<?php
/**
 * Content Property
 *
 * @package Realty_Portal
 * @author  NooTeam <suppport@nootheme.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $property;

// Ensure visibility
if ( empty( $property ) ) {
	return;
}
?>
<div class="noo-property-item noo-md-6">
	<div class="noo-property-item-wrap">
		<div class="noo-item-head">
			<h4 class="item-title">
				<?php echo $property->is_featured() ?>
				<a href="<?php echo $property->permalink() ?>" title="<?php echo $property->title() ?>">
					<?php echo $property->title() ?>
				</a>
			</h4>
			<?php echo $property->address(); ?>
		</div>
		<div class="noo-item-featured">
			<a href="<?php echo $property->permalink() ?>" title="<?php echo $property->title() ?>">
				<?php echo $property->thumbnail( 'rp-property-medium', true, '433x315' ); ?>
			</a>
			<?php echo $property->listing_offers() ?>
		</div>

		<div class="noo-info">
			<?php echo $property->get_list_field_meta(); ?>
		</div>

		<div class="noo-action">
			<?php echo $property->get_price_html(); ?>
			<div class="noo-action-post more-action">
				<?php do_action( 'rp_property_list_more_action', $property ); ?>
			</div>
		</div>
	</div>
</div>