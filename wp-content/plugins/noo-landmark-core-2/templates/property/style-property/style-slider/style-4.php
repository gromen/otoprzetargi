<?php global $property; ?>
<div class="noo-property-item swiper-slide">
	<div class="noo-property-item-wrap">
		<div class="noo-item-featured">
			<a href="<?php echo $property->permalink() ?>" title="<?php echo $property->title() ?>">
				<?php echo $property->thumbnail( 'rp-property-medium', true, '433x315' ); ?>
			</a>
			<?php echo $property->get_price_html(); ?>
		</div>
		<div class="noo-item-head">
			<h4 class="item-title">
				<?php echo $property->is_featured() ?>
				<a href="<?php echo $property->permalink() ?>" title="<?php echo $property->title() ?>">
					<?php echo $property->title() ?>
				</a>
			</h4>

			<?php echo $property->address(); ?>
		</div>
	</div>
</div>