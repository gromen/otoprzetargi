<?php
/**
 * Create widget: RP - Simple Search Property
 *
 * @package       Realty_Portal
 * @subpackage    Widget
 * @author        NooTeam <thietke4rum@gmail.com>
 * @version       1.0
 */

if ( ! class_exists( 'RP_Widget_Simple_Search_Property' ) ):

	class RP_Widget_Simple_Search_Property extends WP_Widget {

		public function __construct() {

			parent::__construct( 'rp_simple_search_property', esc_html__( 'RP - Simple Search Property', 'realty-portal' ), array(
				'description',
				esc_html__( 'RP - Simple Search Property', 'realty-portal' ),
			) );
		}

		public function widget( $args, $instance ) {
			extract( $args );
			extract( $instance );
			echo $before_widget;
			if ( ! empty( $title ) ) {
				echo $before_title . $title . $after_title;
			}
			?>
			<form role="search" method="get" class="search-form" action="<?php echo apply_filters( 'rp_widget_simple_search_url', home_url( '/' ) ); ?>">
				<label>
					<span class="screen-reader-text"><?php echo esc_html__( 'Search for:', 'realty-portal' ); ?></span>
					<input type="search" class="search-field" placeholder="<?php echo esc_html__( 'Search …', 'realty-portal' ); ?>" value="" name="s" />
				</label>
				<button type="submit" class="search-submit">
					<span class="screen-reader-text"><?php echo esc_html__( 'Search', 'realty-portal' ); ?></span>
				</button>
				<input type="hidden" name="post_type" value="rp_property" />
			</form>
			<?php
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
		}

		// method update
		public function update( $new_instance, $old_instance ) {
			$instance            = $old_instance;
			$instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );

			return $instance;
		}
	}

endif;