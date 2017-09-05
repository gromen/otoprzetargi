<?php
/**
 * Show content main shortcode agent dashboard
 *
 * This template can be overridden by copying it to yourtheme/realty-portal/shortcode/agent-dashboard.php.
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

	<div class="rp-dashboard">
		<?php
		/**
		 * rp_before_main_agent_dashboard hook.
		 *
		 */
		do_action( 'rp_before_main_agent_dashboard' );
		?>

		<?php
		$paged       = ( get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1 );
		$post_status = array(
			'publish',
			'pending',
			'expired',
		);

		$keyword = '';
		if ( ! empty( $_GET[ 'keyword' ] ) ) {
			$keyword = rp_validate_data( $_GET[ 'keyword' ] );
		}

		$status = 'publish';
		if ( ! empty( $_GET[ 'status' ] ) ) {
			$status = rp_validate_data( $_GET[ 'status' ] );
		}

		$args = RP_Agent::query_property( array(
			's'           => $keyword,
			'post_status' => $status,
			'paged'       => absint( $paged ),
		) );

		$order_by = '';
		if ( ! empty( $_GET[ 'orderby' ] ) ) {
			$order_by   = rp_validate_data( $_GET[ 'orderby' ] );
			$order_by_r = RP_Query::order( $args, $order_by );
			$args       = array_merge( $args, $order_by_r );
		}

		$agent_query = new WP_Query( apply_filters( 'rp_agent_dasboard_property_query', $args ) );

		/**
		 * rp_before_loop_agent_dashboard hook.
		 *
		 */
		do_action( 'rp_before_loop_agent_dashboard' );
		?>
		<form method="GET" class="rp-menu-action rp-row">
			<div class="rp-col rp-md-4">
				<input type="text" name="keyword" placeholder="<?php echo esc_html( 'Enter your keyword...', 'realty-portal-agent-dashboard' ); ?>" value="<?php echo $keyword; ?>" />
			</div>
			<div class="rp-col rp-md-3">
				<?php
				rp_select( 'status', array(
					'publish' => esc_html( 'Publish', 'realty-portal-agent-dashboard' ),
					'pending' => esc_html( 'Pending', 'realty-portal-agent-dashboard' ),
					'expired' => esc_html( 'Expired', 'realty-portal-agent-dashboard' ),
				), $status );
				?>
			</div>
			<div class="rp-col rp-md-3">
				<?php
				rp_select( 'orderby', array(
					'latest'    => esc_html( 'Recent First', 'realty-portal-agent-dashboard' ),
					'oldest'    => esc_html( 'Older First', 'realty-portal-agent-dashboard' ),
					'alphabet'  => esc_html( 'Title Alphabet', 'realty-portal-agent-dashboard' ),
					'ralphabet' => esc_html( 'Title Reversed Alphabet', 'realty-portal-agent-dashboard' ),
				), $order_by );
				?>
			</div>
			<div class="rp-col rp-md-2">
				<button type="submit"><i class="rp-icon-ion-ios-search-strong"></i></button>
			</div>
		</form>

		<div class="rp-property-dashboard">
			<?php if ( $agent_query->have_posts() ) : ?>
				<div class="rp-head-dashboard">
					<div class="rp-col-info rp-md-5 rp-sm-5">
						<?php echo esc_html( 'Information', 'realty-portal-agent-dashboard' ); ?>
					</div>
					<div class="rp-col-details rp-md-4 rp-sm-4">
						<?php echo esc_html( 'Details', 'realty-portal-agent-dashboard' ); ?>
					</div>
					<div class="rp-col-action rp-md-3 rp-sm-3">
						<?php echo esc_html( 'Action', 'realty-portal-agent-dashboard' ); ?>
					</div>
				</div>
				<?php
				rp_property_loop_start();

				while ( $agent_query->have_posts() ) : $agent_query->the_post();

					global $property;

					// Ensure visibility
					if ( empty( $property ) ) {
						return;
					}

					$list_actions = apply_filters( 'rp_agent_dashboard_list_action', array(
						array(
							'icon'        => 'rp-icon-ion-edit',
							'property_id' => $property->ID,
							'action'      => 'edit',
							'other'       => 'onclick="window.location.href=\'' . $property->edit() . '\'"',
							'tags'        => 'i',
						),
						array(
							'icon'        => 'rp-icon-ion-close',
							'property_id' => $property->ID,
							'action'      => 'remove',
							'tags'        => 'i',
						),
						array(
							'icon'        => 'nre-icon-ion-ios-star',
							'property_id' => $property->ID,
							'action'      => 'featured',
							'tags'        => 'i',
						),
					) );

					?>
					<li class="property-item-dashboard">
						<?php do_action( 'rp_agent_dashboard_property_item_before', $property ); ?>
						<div class="rp-property-item">
							<div class="rp-info rp-md-5">
								<div class="rp-thumbnail">
									<a href="<?php echo $property->permalink() ?>"
									   title="<?php echo $property->title() ?>">
										<?php echo $property->thumbnail(); ?>
									</a>
								</div>
								<div class="rp-content">
									<div class="rp-title">
										<a href="<?php echo $property->permalink() ?>"
										   title="<?php echo $property->title() ?>">
											<?php echo $property->title() ?>
										</a>
									</div>
									<?php echo date_i18n( Realty_Portal::get_date_format(), strtotime( $property->post->post_date ) ) ?>

								</div>
							</div>
							<div class="rp-details rp-md-4">
								<div class="more-content">

									<?php echo $property->listing_offers() ?>
								</div>
								<?php echo $property->get_price_html(); ?>
								<?php echo $property->get_status_html(); ?>
							</div>
							<div class="rp-md-3 more-action">
								<?php
								foreach ( $list_actions as $item ) {
									$other = ! empty( $item[ 'other' ] ) ? $item[ 'other' ] : '';
									echo '<' . $item[ 'tags' ] . ' class="rp-event ' . $item[ 'icon' ] . '" data-id="' . $item[ 'property_id' ] . '" data-process="' . $item[ 'action' ] . '" ' . $other . '></' . $item[ 'tags' ] . '>';
								}
								?>
							</div>
						</div>
						<?php do_action( 'rp_agent_dashboard_property_item_after', $property ); ?>
					</li>
					<?php

				endwhile;

				rp_property_loop_end();

				?>
				<?php
				wp_reset_postdata();
				wp_reset_query();

			else :

				RP_Template::get_template( 'loop/property-found.php' );

			endif;
			?>
		</div>
		<?php if ( $agent_query->max_num_pages > 1 ) : ?>
			<div class="pagination">
				<?php echo rp_pagination_loop( $args, $agent_query ); ?>
			</div>
		<?php endif; ?>

		<?php
		/**
		 * rp_after_main_agent_dashboard hook.
		 *
		 */
		do_action( 'rp_after_main_agent_dashboard' );
		?>
	</div>

<?php endif; ?>
