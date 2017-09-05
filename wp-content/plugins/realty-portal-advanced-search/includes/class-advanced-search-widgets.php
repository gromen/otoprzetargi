<?php
/**
 * Create widget: RP - Advanced Search Property
 *
 * @package       Realty_Portal
 * @subpackage    Widget
 * @author        NooTeam <thietke4rum@gmail.com>
 * @version       1.0
 */

if ( ! class_exists( 'RP_Widget_Advanced_Search_Property' ) ):

	class RP_Widget_Advanced_Search_Property extends WP_Widget {

		public function __construct() {

			parent::__construct( 'rp_advanced_search_property', esc_html__( 'RP - Advanced Search Property', 'realty-portal-advanced-search' ), array(
				'description',
				esc_html__( 'RP - Advanced Search Property', 'realty-portal-advanced-search' ),
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
			<div class="rp-advanced-search-property">
				<form class="rp-validate-form rp-advanced-search-property-form" action="<?php echo RP_Member::get_url_search(); ?>" method="get" accept-charset="utf-8">
					<div class="rp-advanced-search-property-wrap">
						<div class="rp-box-field">
							<?php
							/**
							 * Process option
							 */
							rp_advanced_search_fields( $option_1, array( 'class' => 'rp-field-' . $option_1 ) );
							rp_advanced_search_fields( $option_2, array( 'class' => 'rp-field-' . $option_2 ) );
							rp_advanced_search_fields( $option_3, array( 'class' => 'rp-field-' . $option_3 ) );
							rp_advanced_search_fields( $option_4, array( 'class' => 'rp-field-' . $option_4 ) );
							rp_advanced_search_fields( $option_5, array( 'class' => 'rp-field-' . $option_5 ) );
							rp_advanced_search_fields( $option_6, array( 'class' => 'rp-field-' . $option_6 ) );
							rp_advanced_search_fields( $option_7, array( 'class' => 'rp-field-' . $option_7 ) );
							rp_advanced_search_fields( $option_8, array( 'class' => 'rp-field-' . $option_8 ) );
							?>
						</div><!-- /.rp-box-field -->

						<button type="submit" class="rp-button">
							<?php echo esc_html__( 'Search', 'realty-portal-advanced-search' ) ?>
						</button>
					</div><!-- /.rp-advanced-search-property-wrap -->
				</form>
			</div>
			<?php
			echo $after_widget;
		}

		public function form( $instance ) {
			$instance = wp_parse_args( $instance, array(
				'title'    => '',
				'option_1' => 'keyword',
				'option_2' => apply_filters( 'rp_property_listing_offers', 'listing_offers' ),
				'option_3' => apply_filters( 'rp_property_listing_type', 'listing_type' ),
				'option_4' => 'city',
				'option_5' => '_bedrooms',
				'option_6' => '_bathrooms',
				'option_7' => '_garages',
				'option_8' => 'price',
			) );
			extract( $instance );

			$list_field   = array_reverse( array_merge( rp_list_custom_fields_property_default(), rp_property_render_fields() ) );
			$list_options = array();
			foreach ( $list_field as $item => $value_item ) {
				if ( empty( $value_item ) || ! is_array( $value_item ) ) {
					continue;
				}
				$list_options[ $value_item[ 'name' ] ] = $value_item[ 'label' ];
			}

			/**
			 * Create element title
			 */
			$title_args = array(
				'id'    => $this->get_field_name( 'title' ),
				'name'  => $this->get_field_name( 'title' ),
				'type'  => 'text',
				'title' => esc_html__( 'Title', 'realty-portal-advanced-search' ),
			);
			echo rp_create_element( $title_args, $title );

			/**
			 * Create element Option 1
			 */
			$option_1_args = array(
				'id'               => $this->get_field_name( 'option_1' ),
				'name'             => $this->get_field_name( 'option_1' ),
				'type'             => 'select',
				'show_none_option' => false,
				'placeholder'      => esc_html__( 'None', 'realty-portal-advanced-search' ),
				'title'            => esc_html__( 'Option 1', 'realty-portal-advanced-search' ),
				'options'          => $list_options,
			);
			echo rp_create_element( $option_1_args, $option_1, false );

			/**
			 * Create element Option 2
			 */
			$option_2_args = array(
				'id'               => $this->get_field_name( 'option_2' ),
				'name'             => $this->get_field_name( 'option_2' ),
				'type'             => 'select',
				'show_none_option' => false,
				'placeholder'      => esc_html__( 'None', 'realty-portal-advanced-search' ),
				'title'            => esc_html__( 'Option 2', 'realty-portal-advanced-search' ),
				'options'          => $list_options,
			);
			echo rp_create_element( $option_2_args, $option_2, false );

			/**
			 * Create element Option 3
			 */
			$option_3_args = array(
				'id'               => $this->get_field_name( 'option_3' ),
				'name'             => $this->get_field_name( 'option_3' ),
				'type'             => 'select',
				'show_none_option' => false,
				'placeholder'      => esc_html__( 'None', 'realty-portal-advanced-search' ),
				'title'            => esc_html__( 'Option 3', 'realty-portal-advanced-search' ),
				'options'          => $list_options,
			);
			echo rp_create_element( $option_3_args, $option_3, false );

			/**
			 * Create element Option 4
			 */
			$option_4_args = array(
				'id'               => $this->get_field_name( 'option_4' ),
				'name'             => $this->get_field_name( 'option_4' ),
				'type'             => 'select',
				'show_none_option' => false,
				'placeholder'      => esc_html__( 'None', 'realty-portal-advanced-search' ),
				'title'            => esc_html__( 'Option 4', 'realty-portal-advanced-search' ),
				'options'          => $list_options,
			);
			echo rp_create_element( $option_4_args, $option_4, false );

			/**
			 * Create element Option 5
			 */
			$option_5_args = array(
				'id'               => $this->get_field_name( 'option_5' ),
				'name'             => $this->get_field_name( 'option_5' ),
				'type'             => 'select',
				'show_none_option' => false,
				'placeholder'      => esc_html__( 'None', 'realty-portal-advanced-search' ),
				'title'            => esc_html__( 'Option 5', 'realty-portal-advanced-search' ),
				'options'          => $list_options,
			);
			echo rp_create_element( $option_5_args, $option_5, false );

			/**
			 * Create element Option 6
			 */
			$option_6_args = array(
				'id'               => $this->get_field_name( 'option_6' ),
				'name'             => $this->get_field_name( 'option_6' ),
				'type'             => 'select',
				'show_none_option' => false,
				'placeholder'      => esc_html__( 'None', 'realty-portal-advanced-search' ),
				'title'            => esc_html__( 'Option 6', 'realty-portal-advanced-search' ),
				'options'          => $list_options,
			);
			echo rp_create_element( $option_6_args, $option_6, false );

			/**
			 * Create element Option 7
			 */
			$option_7_args = array(
				'id'               => $this->get_field_name( 'option_7' ),
				'name'             => $this->get_field_name( 'option_7' ),
				'type'             => 'select',
				'show_none_option' => false,
				'placeholder'      => esc_html__( 'None', 'realty-portal-advanced-search' ),
				'title'            => esc_html__( 'Option 7', 'realty-portal-advanced-search' ),
				'options'          => $list_options,
			);
			echo rp_create_element( $option_7_args, $option_7, false );

			/**
			 * Create element Option 8
			 */
			$option_8_args = array(
				'id'               => $this->get_field_name( 'option_8' ),
				'name'             => $this->get_field_name( 'option_8' ),
				'type'             => 'select',
				'show_none_option' => false,
				'placeholder'      => esc_html__( 'None', 'realty-portal-advanced-search' ),
				'title'            => esc_html__( 'Option 8', 'realty-portal-advanced-search' ),
				'options'          => $list_options,
			);
			echo rp_create_element( $option_8_args, $option_8, false );
		}

		// method update
		public function update( $new_instance, $old_instance ) {
			$instance               = $old_instance;
			$instance[ 'title' ]    = strip_tags( $new_instance[ 'title' ] );
			$instance[ 'option_1' ] = strip_tags( $new_instance[ 'option_1' ] );
			$instance[ 'option_2' ] = strip_tags( $new_instance[ 'option_2' ] );
			$instance[ 'option_3' ] = strip_tags( $new_instance[ 'option_3' ] );
			$instance[ 'option_4' ] = strip_tags( $new_instance[ 'option_4' ] );
			$instance[ 'option_5' ] = strip_tags( $new_instance[ 'option_5' ] );
			$instance[ 'option_6' ] = strip_tags( $new_instance[ 'option_6' ] );
			$instance[ 'option_7' ] = strip_tags( $new_instance[ 'option_7' ] );
			$instance[ 'option_8' ] = strip_tags( $new_instance[ 'option_8' ] );

			return $instance;
		}
	}

endif;