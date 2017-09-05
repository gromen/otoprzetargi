<?php
/**
 * Box Meta
 *
 * @package Realty_Portal
 * @author  NooTeam <suppport@nootheme.com>
 */
global $property;
if ( !is_object( $property ) ) {
	return false;
}

?>
<div class="nrs-property-head-meta">
	<div class="property-title">
		<?php echo $property->get_price_html(); ?>
		<h1 class="property-link">
			<a href="<?php echo $property->permalink() ?>" title="<?php echo $property->title() ?>">
				<?php echo $property->title() ?>
			</a>
		</h1>
		<?php echo $property->address() ?>
	</div>
	<div class="property-data">
		<ul class="rp-action-post more-action">
			<?php do_action( 'rp_single_property_box_meta', $property ); ?>
		</ul>
	</div>
</div>