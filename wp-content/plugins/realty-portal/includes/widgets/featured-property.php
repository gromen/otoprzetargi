<?php
/**
 * Create widget: RP - Featured Property
 *
 * @package       Realty_Portal
 * @subpackage    Widget
 * @author        NooTeam <thietke4rum@gmail.com>
 * @version       1.0
 */

if ( ! class_exists( 'RP_Widget_Featured_Property' ) ):

	class RP_Widget_Featured_Property extends WP_Widget {

		public function __construct() {

			parent::__construct( 'rp_featured_property', esc_html__( 'RP - Featured Property', 'realty-portal' ), array(
				'description',
				esc_html__( 'RP - Featured Property', 'realty-portal' ),
			) );
		}

		public function widget( $args, $instance ) {
			extract( $args );
			extract( $instance );
			echo $before_widget;

			$property_args = array(
				'post_type'           => apply_filters( 'rp_property_post_type', 'rp_property' ),
				'post_status'         => 'publish',
				'posts_per_page'      => $number,
				'meta_key'            => '_featured',
				'meta_value'          => 'yes',
				'ignore_sticky_posts' => 1,
			);

			if ( ! empty( $listing_type ) || ! empty( $listing_offers ) ) {

				$tax_query               = array();
				$tax_query[ 'relation' ] = 'AND';

				if ( ! empty( $listing_offers ) ) {

					$tax_query[] = array(
						'taxonomy' => apply_filters( 'rp_property_listing_offers', 'listing_offers' ),
						'field'    => 'id',
						'terms'    => $listing_offers,
					);
				}

				if ( ! empty( $listing_type ) ) {

					$tax_query[] = array(
						'taxonomy' => apply_filters( 'rp_property_listing_type', 'listing_type' ),
						'field'    => 'id',
						'terms'    => $listing_type,
					);
				}

				$property_args[ 'tax_query' ] = $tax_query;
			}

			$property_query = new WP_Query( $property_args );

			if ( $property_query->have_posts() ) {

				wp_enqueue_style( 'slick' );

				if ( ! empty( $title ) ) {
					echo $before_title . $title . $after_title;
				}

				echo '<div class="rp-featured-property-wrap">';

				while ( $property_query->have_posts() ) : $property_query->the_post();
					global $property;
					?>
					<div class="rp-thumbnail-wrap">
						<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
							<?php echo $property->thumbnail( 'rp-property-medium' ) ?>
							<div class="more-content">
								<?php echo $property->is_featured() ?>
								<?php echo $property->listing_offers() ?>
							</div>
							<?php echo $property->get_list_photo( 'total' ); ?>
							<?php echo $property->get_price_html(); ?>
						</a>
					</div>
					<?php

				endwhile;

				echo '</div><!-- /.rp-featured-property-wrap -->';
			}

			echo $after_widget;
		}

		public function form( $instance ) {
			$instance = wp_parse_args( $instance, array(
				'title'          => '',
				'number'         => '3',
				'listing_offers' => '',
				'listing_type'   => '',
			) );
			extract( $instance );

			/**
			 * Create element title
			 */
			$title_args = array(
				'id'    => $this->get_field_name( 'title' ),
				'name'  => $this->get_field_name( 'title' ),
				'type'  => 'text',
				'title' => esc_html__( 'Title', 'realty-portal' ),
			);
			echo rp_create_element( $title_args, $title );

			/**
			 * Create element number
			 */
			$number_args = array(
				'id'    => $this->get_field_name( 'number' ),
				'name'  => $this->get_field_name( 'number' ),
				'type'  => 'text',
				'title' => esc_html__( 'Number', 'realty-portal' ),
			);
			echo rp_create_element( $number_args, $number );

			/**
			 * Create element listing offers
			 */
			$listing_offers_args = array(
				'id'      => $this->get_field_name( 'listing_offers' ),
				'name'    => $this->get_field_name( 'listing_offers' ),
				'type'    => 'select',
				'title'   => esc_html__( 'Listing Offers', 'realty-portal' ),
				'options' => rp_get_list_tax( 'listing_offers', false, esc_html__( 'All', 'realty-portal' ) ),
			);
			echo rp_create_element( $listing_offers_args, $listing_offers, false );

			/**
			 * Create element Listing Types
			 */
			$listing_type_args = array(
				'id'      => $this->get_field_name( 'listing_type' ),
				'name'    => $this->get_field_name( 'listing_type' ),
				'type'    => 'select',
				'title'   => esc_html__( 'Listing Types', 'realty-portal' ),
				'options' => rp_get_list_tax( apply_filters( 'rp_property_listing_type', 'listing_type' ), false, esc_html__( 'All', 'realty-portal' ) ),
			);
			echo rp_create_element( $listing_type_args, $listing_type, false );
		}

		// method update
		public function update( $new_instance, $old_instance ) {
			$instance                     = $old_instance;
			$instance[ 'title' ]          = strip_tags( $new_instance[ 'title' ] );
			$instance[ 'number' ]         = strip_tags( $new_instance[ 'number' ] );
			$instance[ 'listing_offers' ] = strip_tags( $new_instance[ 'listing_offers' ] );
			$instance[ 'listing_type' ]   = strip_tags( $new_instance[ 'listing_type' ] );

			return $instance;
		}
	}

endif;