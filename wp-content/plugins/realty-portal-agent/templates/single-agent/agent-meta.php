<?php
/**
 * Agent Meta
 *
 * @package Realty_Portal
 * @author  NooTeam <suppport@nootheme.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $agent;
if ( !is_object( $agent ) ) {
	return false;
}
?>
<div class="rp-agent-meta">
	<div class="rp-thumbnail">
		<?php echo $agent->agent_avatar(); ?>
	</div>
	<div class="rp-content">
		<?php
			/**
			 * rp_before_agent_meta_content hook.
			 *
			 * @hooked rp_agent_meta_content_title - 5
			 */
			do_action( 'rp_before_agent_meta_content' );
		?>

		<?php
			/**
			 * rp_after_agent_meta_content hook.
			 *
			 */
			do_action( 'rp_after_agent_meta_content' );
		?>
	</div>
</div>