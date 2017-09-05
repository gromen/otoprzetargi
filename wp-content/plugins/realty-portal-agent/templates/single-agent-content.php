<?php
/**
 * Single Property Content
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

<?php
	/**
	 * rp_before_single_agent hook.
	 *
	 */
	 do_action( 'rp_before_single_agent' );

	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }
?>

<div id="rp-agent-<?php the_ID(); ?>" <?php post_class( 'rp-agent' ); ?>>

	<?php
		/**
		 * rp_before_single_agent_summary hook.
		 *
		 */
		do_action( 'rp_before_single_agent_summary' );
	?>

	<div class="entry-agent">

		<?php
			/**
			 * rp_single_agent_summary hook.
			 *
			 */
			do_action( 'rp_single_agent_summary' );
		?>

	</div><!-- .entry-agent -->

	<?php
		/**
		 * rp_after_single_agent_summary hook.
		 *
		 * @hooked rp_agent_list_property - 5
		 */
		do_action( 'rp_after_single_agent_summary' );
	?>

</div><!-- #rp-agent-<?php the_ID(); ?> -->

<?php do_action( 'rp_after_single_agent' ); ?>
