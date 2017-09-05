<?php
/**
 * Show content main shortcode my favorites
 *
 * This template can be overridden by copying it to yourtheme/realty-portal/shortcode/my-favorites.php.
 *
 * HOWEVER, on occasion NooTheme will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author        NooTheme
 * @version       0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php if ( is_wp_error( RP_Agent::can_view() ) ) : ?>

	<?php rp_message_notices( RP_Agent::can_view() ); ?>

<?php else : ?>

	<div class="rp-row rp-my-favorites">
		<?php
		/**
		 * rp_before_main_my_favorites hook.
		 *
		 */
		do_action( 'rp_before_main_my_favorites' );
		?>

		<?php
		$paged = ( get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1 );
		$args  = array(
			'paged'       => $paged,
			'post_type'   => apply_filters( 'rp_property_post_type', 'rp_property' ),
			'post_status' => 'publish',
			'post__in'    => RP_AddOn_My_Favorites::is_favorites(),
		);

		$agent_query = new WP_Query( apply_filters( 'rp_my_favorites_query', $args ) );

		if ( $agent_query->have_posts() ) :

			/**
			 * rp_before_loop_property_my_favorites hook.
			 *
			 */
			do_action( 'rp_before_loop_property_my_favorites' );

			?>
			<div class="noo-list-property style-grid column">
				<?php
				while ( $agent_query->have_posts() ) : $agent_query->the_post();
					global $property;
					if ( empty( $property ) ) {
						return;
					}
					?>
					<div class="noo-property-item property-item noo-md-6">

						<div class="noo-property-item-wrap">

							<div class="noo-item-head">
								<h4 class="item-title">
									<?php echo $property->is_featured() ?>
									<a href="<?php echo $property->permalink() ?>" title="<?php echo $property->title() ?>">
										<?php echo $property->title() ?>
									</a>
								</h4>
								<?php echo $property->address(); ?>
							</div>
							<div class="noo-item-featured">
								<a href="<?php echo $property->permalink() ?>" title="<?php echo $property->title() ?>">
									<?php echo $property->thumbnail(); ?>
								</a>
								<?php echo $property->listing_offers() ?>
							</div>

							<div class="noo-info">
								<?php echo $property->get_list_field_meta(); ?>
							</div>

							<div class="noo-action more-action">
								<?php echo $property->get_price_html(); ?>
								<div class="noo-action-post">
									<span class="noo-tooltip-action" data-content="<?php echo esc_html__( 'Remove', 'noo-landmark' ) ?>">
										<i class="ion-trash-a rp-event remove-property" data-id="<?php echo $property->ID ?>" data-user="<?php echo RP_Agent::is_user() ?>" data-process="remove_favorites" data-hasqtip="0"></i>
									</span>
									<div class="noo-property-sharing">
										<span>
											<i class="noo-tooltip-action ion-android-share-alt" data-id="<?php echo $property->ID; ?>" data-process="share"></i>
										</span>
										<?php rp_social_sharing_property(); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php endwhile; ?>
			</div>
			<?php

			if ( $agent_query->max_num_pages > 1 ) : ?>
				<div class="pagination">
					<?php echo rp_pagination_loop( $args, $agent_query ); ?>
				</div>
			<?php endif;

			wp_reset_postdata();
			wp_reset_query();

		else :

			RP_Template::get_template( 'loop/property-found.php' );

		endif;
		?>

		<?php
		/**
		 * rp_after_main_my_favorites hook.
		 *
		 */
		do_action( 'rp_after_main_my_favorites' );
		?>
	</div>

<?php endif; ?>
