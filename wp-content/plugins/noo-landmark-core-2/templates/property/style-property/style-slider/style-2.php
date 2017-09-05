<?php
/**
 * Style Slider Property
 *
 * @package LandMark
 * @author  KENT <tuanlv@vietbrain.com>
 */
global $property;
?>
<div class="noo-property-item-wrap swiper-slide">
	<div class="noo-item-featured">
		<a href="<?php echo $property->permalink() ?>" title="<?php echo $property->title() ?>">
			<?php echo $property->thumbnail( 'rp-property-thumbnail', true, '150x150' ); ?>
			<span></span>
		</a>
		<?php echo $property->listing_offers() ?>
	</div>

	<div class="noo-item-content">
		<div class="noo-item-head">
			<h4 class="item-title">
				<?php echo $property->is_featured() ?>
				<a href="<?php echo $property->permalink() ?>" title="<?php echo $property->title() ?>">
					<?php echo $property->title() ?>
				</a>
			</h4>
			<?php echo $property->address(); ?>
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