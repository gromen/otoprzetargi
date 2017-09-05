<?php
/**
 * Create widget: Noo - Featured Agent
 *
 * @package       LandMark
 * @subpackage    Widget
 * @author        James <luyentv@gmail.com>
 * @version       1.0
 */

if ( ! class_exists( 'Noo_Widget_Featured_Agent' ) ):

	class Noo_Widget_Featured_Agent extends WP_Widget {

		public function __construct() {

			parent::__construct( 'noo_featured_agent', esc_html__( 'Noo - Featured Agent', 'noo-landmark-core' ), array(
				'description',
				esc_html__( 'Noo - Featured Agent', 'noo-landmark-core' ),
			) );
		}

		public function widget( $args, $instance ) {
			extract( $args );
			extract( $instance );
			echo $before_widget;

			$agent_args = array(
				'post_type'      => 'noo_agent',
				'posts_per_page' => $number,
				'meta_query'     => array(
					array(
						'key'   => '_featured',
						'value' => 'yes',
					),
				),
			);

			$agent_query = new WP_Query( $agent_args );

			if ( $agent_query->have_posts() ) {

				if ( ! empty( $title ) ) {
					echo $before_title . $title . $after_title;
				}

				echo '<div class="noo-featured-agent-wrap ">';

				while ( $agent_query->have_posts() ) : $agent_query->the_post();
					$agent_id       = get_the_ID();
					$total_property = count_user_posts( $agent_id, 'noo_property' );
					?>
					<div <?php post_class( 'noo-featured-agent-item' ); ?>>
						<div class="noo-thumbnail">
							<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
								<img src="<?php echo noo_thumb_src( $agent_id, 'thumbnail', '70x70' ); ?>" alt="<?php the_title(); ?>" />
							</a>
						</div>
						<div class="noo-content">
							<div class="item-title">
								<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
									<?php the_title(); ?>
								</a>
							</div>
							<span class="total-property">
                                <?php echo sprintf( esc_html__( '%s properties', 'noo-landmark-core' ), $total_property ); ?>
                            </span>
						</div>
					</div><!-- /.noo-featured-agent-item -->
					<?php

				endwhile;

				echo '</div><!-- /.noo-featured-agent-wrap -->';
			}

			echo $after_widget;
		}

		public function form( $instance ) {
			$instance = wp_parse_args( $instance, array(
				'title'  => '',
				'number' => '3',
			) );
			extract( $instance );

			/**
			 * Create element title
			 */
			$title_args = array(
				'id'    => $this->get_field_name( 'title' ),
				'name'  => $this->get_field_name( 'title' ),
				'type'  => 'text',
				'title' => esc_html__( 'Title', 'noo-landmark-core' ),
			);
			echo rp_create_element( $title_args, $title );

			/**
			 * Create element number
			 */
			$number_args = array(
				'id'    => $this->get_field_name( 'number' ),
				'name'  => $this->get_field_name( 'number' ),
				'type'  => 'text',
				'title' => esc_html__( 'Number', 'noo-landmark-core' ),
			);
			echo rp_create_element( $number_args, $number );
		}

		// method update
		public function update( $new_instance, $old_instance ) {
			$instance             = $old_instance;
			$instance[ 'title' ]  = strip_tags( $new_instance[ 'title' ] );
			$instance[ 'number' ] = strip_tags( $new_instance[ 'number' ] );

			return $instance;
		}
	}

endif;