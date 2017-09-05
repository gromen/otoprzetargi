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

$title                = apply_filters( 'noo_agent_contact_info_title', get_theme_mod( 'noo_agent_contact_info_title', esc_html__( 'Contact Info', 'noo-landmark' ) ) );
$show_contact_info    = get_theme_mod( 'noo_agent_contact_info', true );
$contact_info_image   = get_theme_mod( 'noo_agent_contact_info_image', '' );
$show_latest_property = get_theme_mod( 'noo_agent_latest_properties', true );
?>

<div id="rp-agent-<?php the_ID(); ?>" <?php post_class( 'rp-agent' ); ?>>

	<div class="noo-row noo-agent-detail">

		<div class="noo-thumbnail noo-md-5">
			<?php echo $agent->agent_avatar(); ?>
		</div>

		<div class="noo-info-agent noo-md-7">
			<div class="noo-box-content">
				<h1 class="noo-title">
					<?php echo $agent->title(); ?>
				</h1>
				<div class="noo-box-info">
					<div class="item-info">
						<?php if ( ! empty( $agent->agent_info( 'position' ) ) ) : ?>
							<span class="position">
								<?php echo esc_html( $agent->agent_info( 'position' ) ); ?>
							</span>
						<?php endif; ?>
						<span class="total-property">
							<?php echo sprintf( esc_html__( '%s properties', 'noo-landmark' ), $agent->agent_info( 'total_property' ) ); ?>
						</span>
					</div>
					<ul class="item-info">
						<?php
						foreach ( $agent->agent_custom_field() as $field ) {
							if ( ! is_array( $field ) && empty( $field[ 'name' ] ) ) {
								continue;
							}
							$field_name = apply_filters( 'rp_agent_post_type', 'rp_agent' ) . $field[ 'name' ];
							$value      = get_post_meta( $agent->ID, $field_name, true );
							if ( ! empty( $value ) ) {
								switch ( $field[ 'name' ] ) {
									case '_email':
										echo '<li class="' . esc_attr( $field_name ) . '">';
										echo '	<a href="mailto:' . esc_html( $value ) . '" target="_top">' . esc_html( $value ) . '</a>';
										echo '</li>';
										break;

									case '_website' :
										echo '<li class="' . esc_attr( $field_name ) . '">';
										echo '	<a href="' . esc_attr( $value ) . '" target="_top">' . esc_html( $value ) . '</a>';
										echo '</li>';
										break;

									case '_phone' :
									case '_mobile' :
										echo '<li class="' . esc_attr( $field_name ) . '">';
										echo '	<a href="tel:' . absint( $value ) . '" target="_top">' . esc_html( $value ) . '</a>';
										echo '</li>';
										break;

									case '_about' :

										break;

									default:
										echo '<li class="' . esc_attr( $field_name ) . '">' . esc_html( $value ) . '</li>';
										break;
								}
							}
						}
						?>
					</ul>

					<?php if ( ! empty( $agent->agent_info( 'position' ) ) ) : ?>
						<div class="agent-about">
							<?php echo esc_html( $agent->agent_info( 'about' ) ); ?>
						</div>
					<?php endif; ?>


					<div class="agent-social">
						<?php
						foreach ( $agent->list_social_agent() as $item_social ) {
							$class_social = str_replace( apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_', '', $item_social[ 'id' ] );
							$value_social = get_post_meta( $agent->ID, $item_social[ 'id' ], true );
							if ( ! empty( $value_social ) ) {
								echo '<a class="' . esc_attr( $class_social ) . '" href="' . esc_attr( $value_social ) . '"></a>';
							}
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php if ( ! empty( $show_contact_info ) ) : ?>

		<div class="noo-container-fluid noo-agent-contact"<?php echo( ! empty( $contact_info_image ) ? ' style="background-image: url(' . esc_url( $contact_info_image ) . ')"' : '' ); ?>>

			<div class="noo-box-contact">
				<h2 class="noo-title"><?php echo esc_html( $title ); ?></h2>
				<div class="noo-box-form">
					<form class="rp-validate-form rp-box-contact-agent">

						<?php do_action( 'noo_before_field_contact_agent' ); ?>
						<div class="noo-row">
							<div class="noo-item-wrap noo-box-text-field">
								<input type="text" name="name" placeholder="<?php echo esc_html__( 'Name *', 'noo-landmark' ); ?>" />
							</div>
							<div class="noo-item-wrap noo-box-text-field">
								<input type="text" name="phone" placeholder="<?php echo esc_html__( 'Phone', 'noo-landmark' ); ?>" />
							</div>
							<div class="noo-item-wrap noo-box-text-field">
								<input type="text" name="email" placeholder="<?php echo esc_html__( 'Email *', 'noo-landmark' ); ?>" />
							</div>
						</div>
						<div class="noo-item-wrap noo-box-textarea-field">
							<textarea name="message" placeholder="<?php echo esc_html__( 'Your Message *', 'noo-landmark' ); ?>"></textarea>
						</div>

						<?php do_action( 'noo_after_field_contact_agent' ); ?>

						<div class="noo-form-action">
							<button type="submit" class="noo-submit">
								<span><?php echo esc_html__( 'Send Message', 'noo-landmark' ); ?></span>
								<i class="fa-li fa fa-spinner fa-spin hide"></i>
							</button>
						</div>
						<?php if ( ! empty( $agent_id ) ) : ?>
							<input type="hidden" name="agent_id" value="<?php echo esc_attr( $agent_id ) ?>">
						<?php endif; ?>

						<?php if ( ! empty( $property_id ) ) : ?>
							<input type="hidden" name="property_id" value="<?php echo esc_attr( $property_id ) ?>">
						<?php endif; ?>

					</form>

				</div>
			</div>

		</div>

	<?php endif; ?>

	<?php if ( ! empty( $show_latest_property ) ) : ?>
		<div class="noo-container noo-agent-property">
			<?php
			$posts_per_page = apply_filters( 'rp_property_agent_number', 2 );
			$title          = apply_filters( 'noo_title_agent_related_property', get_theme_mod( 'noo_agent_title_related_property', esc_html__( 'Related Property', 'noo-landmark' ) ) );
			$description    = apply_filters( 'noo_description_agent_related_property', get_theme_mod( 'noo_agent_sub_title_related_property', esc_html__( 'Lorem Ipsum is simply dummy text of the printing.', 'noo-landmark' ) ) );
			$args           = array(
				'posts_per_page' => $posts_per_page,
			);
			$args           = apply_filters( 'rp_after_agent_loop_properties_query', $args );
			$query_property = new WP_Query( $agent->query_property( $args ) );

			if ( $query_property->have_posts() ) {

				?>
				<div class="noo-box-property-slider" data-item="3">
					<div class="noo-title-header">
						<?php
						/**
						 * Render title
						 */
						noo_title_first_word( $title, $description );
						?>
						<div class="noo-action-slider">
							<i class="prev-property ion-ios-arrow-left"></i>
							<i class="next-property ion-ios-arrow-right"></i>
						</div>
					</div>
					<div class="noo-list-property style-grid">
						<?php while ( $query_property->have_posts() ) : $query_property->the_post(); global $property; ?>
							<div class="noo-property-item">

								<div class="noo-property-item-wrap">

									<div class="noo-item-head">

										<h4 class="item-title">
											<?php echo $property->is_featured() ?>
											<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
												<?php the_title(); ?>
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
											<?php do_action( 'rp_property_list_more_action', $property ); ?>
										</div>
									</div>

								</div><!-- /.noo-property-item-wrap -->

							</div><!-- /.noo-property-item -->
						<?php endwhile; ?>

					</div><!-- /.noo-list-property -->
				</div><!-- /.noo-box-property-slider -->

				<?php
				wp_reset_postdata();
			}
			?>
		</div>
	<?php endif; ?>

</div><!-- #rp-agent-<?php the_ID(); ?> -->

<?php do_action( 'rp_after_single_agent' ); ?>
