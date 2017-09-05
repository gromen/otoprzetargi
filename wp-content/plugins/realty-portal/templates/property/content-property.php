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
<li id="property-item-<?php echo $property->ID ?>" class="property-item">
	<div class="img-holder">
		<a href="<?php echo $property->permalink() ?>" title="<?php echo $property->title() ?>">
			<?php echo $property->thumbnail(); ?>
		</a>
		<div class="more-content">
			<?php echo $property->is_featured() ?>
			<?php echo $property->listing_offers() ?>
		</div>
		<?php echo $property->address(); ?>
		<?php echo $property->get_list_photo( 'total' ); ?>
	</div>
	<div class="property-content">
		<div class="property-title">
			<h4>
				<a href="<?php echo $property->permalink() ?>" title="<?php echo $property->title() ?>">
					<?php echo $property->title() ?>
				</a>
			</h4>
		</div>
		<?php echo $property->get_price_html(); ?>

		<p class="description"><?php echo $property->get_content(); ?></p>

		<div class="property-meta">
			<?php echo $property->get_list_field_meta(); ?>
		</div>

		<div class="property-footer">
			<div class="agent-info">
				<div class="agent-image"
				     style="background-image:url('<?php echo $property->agent_info( 'avatar' ) ?>')"></div>
				<a href="<?php echo $property->agent_info( 'url' ) ?>"><?php echo $property->agent_info( 'name' ) ?></a>
			</div>

			<div class="more-action">
				<?php do_action( 'rp_property_list_more_action', $property ); ?>
			</div>
		</div>
	</div>
</li>
