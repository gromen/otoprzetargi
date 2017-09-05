<?php
/**
 * Create widget: RP - Property Taxonomies
 *
 * @package       Realty_Portal
 * @subpackage    Widget
 * @author        NooTeam <thietke4rum@gmail.com>
 * @version       1.0
 */

if ( ! class_exists( 'RP_Widget_Property_Taxonomies' ) ):

	class RP_Widget_Property_Taxonomies extends WP_Widget {

		public function __construct() {

			parent::__construct( 'rp_property_taxonomies', esc_html__( 'RP - Property Taxonomies', 'realty-portal' ), array(
				'description',
				esc_html__( 'RP - Property Taxonomies', 'realty-portal' ),
			) );
		}

		public function widget( $args, $instance ) {
			extract( $args );
			extract( $instance );
			echo $before_widget;

			if ( ! empty( $title ) ) {
				echo $before_title . $title . $after_title;
			}

			$show_count = false;
			if ( 'yes' == $tax_count ) {
				$show_count = true;
			}

			$show_child = false;
			if ( 'yes' == $tax_child ) {
				$show_child = true;
			}

			$this->property_taxonomies( $taxonomy, $show_child, $show_count );

			echo $after_widget;
		}

		public function form( $instance ) {
			$instance = wp_parse_args( $instance, array(
				'title'     => '',
				'taxonomy'  => '',
				'tax_count' => '',
				'tax_child' => '',
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
			 * Create element Taxonomy
			 */
			$taxonomy_args = array(
				'id'               => $this->get_field_name( 'taxonomy' ),
				'name'             => $this->get_field_name( 'taxonomy' ),
				'type'             => 'select',
				'title'            => esc_html__( 'Taxonomy', 'realty-portal' ),
				'show_none_option' => false,
				'options'          => array(
					apply_filters( 'rp_property_listing_type', 'listing_type' )   => esc_html__( 'Listing Types', 'realty-portal' ),
					'listing_offers' => esc_html__( 'Listing Offers', 'realty-portal' ),
				),
			);
			echo rp_create_element( $taxonomy_args, $taxonomy, false );

			/**
			 * Create element Count
			 */
			$count_args = array(
				'id'               => $this->get_field_name( 'tax_count' ),
				'name'             => $this->get_field_name( 'tax_count' ),
				'type'             => 'select',
				'title'            => esc_html__( 'Count', 'realty-portal' ),
				'show_none_option' => false,
				'options'          => array(
					'yes' => esc_html__( 'Show Count', 'realty-portal' ),
					'no'  => esc_html__( 'Hide Count', 'realty-portal' ),
				),
			);
			echo rp_create_element( $count_args, $tax_count, false );

			/**
			 * Create element Child
			 */
			$child_args = array(
				'id'               => $this->get_field_name( 'tax_child' ),
				'name'             => $this->get_field_name( 'tax_child' ),
				'type'             => 'select',
				'title'            => esc_html__( 'Child', 'realty-portal' ),
				'show_none_option' => false,
				'options'          => array(
					'yes' => esc_html__( 'Show Child', 'realty-portal' ),
					'no'  => esc_html__( 'Hide Child', 'realty-portal' ),
				),
			);
			echo rp_create_element( $child_args, $tax_child, false );
		}

		// method update
		public function update( $new_instance, $old_instance ) {
			$instance                = $old_instance;
			$instance[ 'title' ]     = strip_tags( $new_instance[ 'title' ] );
			$instance[ 'taxonomy' ]  = $new_instance[ 'taxonomy' ];
			$instance[ 'tax_count' ] = $new_instance[ 'tax_count' ];
			$instance[ 'tax_child' ] = $new_instance[ 'tax_child' ];

			return $instance;
		}

		public function property_taxonomies( $taxonomy, $show_child, $show_count ) {
			$terms = get_terms( $taxonomy, array( 'parent' => 0 ) );
			$count = count( $terms );
			if ( $count > 0 ) {
				$this->show_hierarchical_property_types( $terms, $taxonomy, $show_child, $show_count );
			}
		}

		public function show_hierarchical_property_types( $terms, $taxonomy, $show_child, $show_count ) {
			$count = count( $terms );
			if ( $count > 0 ) {

				if ( $show_child ) {
					echo '<ul>';
				} else {
					echo '<ul class="children">';
				}

				foreach ( $terms as $term ) {
					echo '<li><a href="' . esc_url( get_term_link( $term->slug, $term->taxonomy ) ) . '">' . esc_attr( $term->name ) . '</a>';
					if ( $show_child ) {
						$child_terms = get_terms( $taxonomy, array( 'parent' => $term->term_id ) );
						if ( $child_terms ) {
							$this->show_hierarchical_property_types( $child_terms, $taxonomy, false, $show_count );
						}
					}

					if ( $show_count ) {
						echo '<span class="cat-count">(' . esc_attr( $term->count ) . ')</span>';
					}
					echo '</li>';
				}
				echo '</ul>';
			}
		}
	}

endif;