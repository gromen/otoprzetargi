<?php
/**
 * Box Field Meta
 *
 * @package Realty_Portal
 * @author  NooTeam <suppport@nootheme.com>
 */
global $property;
if ( !is_object( $property ) ) {
	return false;
}

?>
<div class="rp-box-field-meta">
	<?php echo $property->get_list_field_meta(); ?>
</div>