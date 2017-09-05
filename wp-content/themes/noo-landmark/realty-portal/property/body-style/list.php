<div class="noo-detail-header">
	<h2 class="noo-detail-title">
		<?php the_title(); ?>
	</h2>
	<?php echo $property->listing_offers() ?>
	<div class="noo-action-post more-action">
		<?php do_action( 'rp_property_list_more_action', $property ); ?>
	</div>
	<div class="noo-info">
		<?php echo $property->address(); ?>
		<?php echo $property->get_price_html(); ?>
	</div>

</div>