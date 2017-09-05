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

			rp_property_loop_start();

			while ( $agent_query->have_posts() ) : $agent_query->the_post();

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
                                <button class="rp-event remove-property" data-process="remove_favorites"
                                        data-user="<?php echo RP_Agent::is_user() ?>"
                                        data-id="<?php echo $property->ID ?>">
		                            <?php echo esc_html__( 'Remove', 'realty-portal-my-favorites' ) ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </li>
				<?php

			endwhile;

			rp_property_loop_end();

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
