<?php
/**
 * Success
 *
 * @package Realty_Portal
 * @author  NooTeam <suppport@nootheme.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( empty( $messages ) ) {
	return;
}
?>
<div class="rp-notice success">
	<?php echo $messages; ?>
</div>