<?php
/**
 * Error
 *
 * @package Realty_Portal
 * @author  NooTeam <suppport@nootheme.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( !empty( $args['messages'] ) ) {
	$messages = $args['messages'];
}

if ( empty( $messages ) ) {
	return;
}
?>
<div class="rp-notice error show">
	<?php echo $messages; ?>
</div>