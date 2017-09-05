<?php
/**
 * Shortcode Visual: Recent Property
 *
 * @package     LandMark
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */
if ( ! function_exists( 'noo_shortcode_recent_property' ) ) :

	function noo_shortcode_recent_property( $atts ) {

		extract( shortcode_atts( array(
			'title'           => '',
			'sub_title'       => '',
			'style'           => 'style-1',
			'row'             => '1',
			'column'          => '3',
			'posts_per_page'  => '10',
			'property_status' => '',
			'property_types'  => '',
			'orderby'         => 'date',
			'order'           => 'DESC',
		), $atts ) );
		/**
		 * Enqueue script
		 */
		wp_enqueue_style( 'slick' );
		wp_enqueue_script( 'slick' );

		/**
		 * Get info user
		 */
		$user_id = noo_get_current_user( true );

		/**
		 * Query
		 */
		$property_args = array(
			'post_type'      => 'noo_property',
			'post_status'    => 'publish',
			'posts_per_page' => $posts_per_page,
		);

		if ( ! empty( $property_types ) || ! empty( $property_status ) ) {

			$tax_query               = array();
			$tax_query[ 'relation' ] = 'AND';

			if ( ! empty( $property_status ) ) {

				$tax_query[] = array(
					'taxonomy' => 'property_status',
					'field'    => 'id',
					'terms'    => $property_status,
				);
			}

			if ( ! empty( $property_types ) ) {

				$tax_query[] = array(
					'taxonomy' => 'property_type',
					'field'    => 'id',
					'terms'    => $property_types,
				);
			}

			$property_args[ 'tax_query' ] = $tax_query;
		}

		$number_item_display = ! empty( $number_item_display ) ? $number_item_display : 3;

		$property_args[ 'order' ]    = $order == 'DESC' ? 'DESC' : 'ASC';
		$property_args[ 'meta_key' ] = '';

		switch ( $orderby ) {
			case 'rand' :
				$property_args[ 'orderby' ] = 'rand';
				break;
			case 'date' :
				$property_args[ 'orderby' ] = 'date';
				$property_args[ 'order' ]   = $order == 'ASC' ? 'ASC' : 'DESC';
				break;
			case 'bath' :
				$property_args[ 'orderby' ]  = "meta_value_num meta_value";
				$property_args[ 'order' ]    = $order == 'DESC' ? 'DESC' : 'ASC';
				$property_args[ 'meta_key' ] = 'noo_property_bathrooms';
				break;
			case 'bed' :
				$property_args[ 'orderby' ]  = "meta_value_num meta_value";
				$property_args[ 'order' ]    = $order == 'DESC' ? 'DESC' : 'ASC';
				$property_args[ 'meta_key' ] = 'noo_property_bedrooms';
				break;
			case 'area' :
				$property_args[ 'orderby' ]  = "meta_value_num meta_value";
				$property_args[ 'order' ]    = $order == 'DESC' ? 'DESC' : 'ASC';
				$property_args[ 'meta_key' ] = 'noo_property_area';
				break;
			case 'price' :
				$property_args[ 'orderby' ]  = "meta_value_num meta_value";
				$property_args[ 'order' ]    = $order == 'DESC' ? 'DESC' : 'ASC';
				$property_args[ 'meta_key' ] = 'price';
				break;
			case 'featured' :
				$property_args[ 'orderby' ]  = "meta_value";
				$property_args[ 'order' ]    = $order == 'DESC' ? 'DESC' : 'ASC';
				$property_args[ 'meta_key' ] = '_featured';
				break;
			case 'name' :
				$property_args[ 'orderby' ] = 'title';
				$property_args[ 'order' ]   = 'ASC'; // $order == 'DESC' ? 'DESC' : 'ASC';
				break;
		}

		$property_query = new WP_Query( $property_args );

		ob_start();
		?>
		<div class="noo-recent-property-wrap <?php echo esc_attr( $style ); ?>">
			<div class="noo-recent-property" data-item="<?php echo absint( $number_item_display ); ?>" data-style="<?php echo esc_attr( $style ); ?>" data-column="<?php echo esc_attr( $column ); ?>" data-row="<?php echo esc_attr( $row ); ?>">
				<div class="noo-title-header">
					<?php
					/**
					 * Render title
					 */
					noo_title_first_word( $title, $sub_title );
					?>
					<?php if ( $style != 'style-4' ) : ?>
						<div class="noo-action-slider">
							<i class="prev-property ion-ios-arrow-left"></i>
							<i class="next-property ion-ios-arrow-right"></i>
						</div>
					<?php endif; ?>
				</div>
				<div class="noo-list-property <?php echo ( $style !== 'style-2' ) ? 'style-grid' : '' ?>">
					<?php
					if ( $property_query->have_posts() ) {
						$display_style = 'style-slider/' . esc_attr( $style );
						while ( $property_query->have_posts() ) : $property_query->the_post();
							$property_id = get_the_ID();
							require noo_get_template( 'property/item-property' );

						endwhile;
						wp_reset_postdata();
					} else {
						echo '<div class="not_found">' . esc_html__( 'Sorry, no posts matched your criteria.', 'noo-landmark' ) . '</div>';
					}
					?>
				</div><!-- /.noo-list-property -->
				<?php if ( $style === 'style-4' ) : ?>
					<div class="noo-slider-pagination"></div>
				<?php endif; ?>
			</div>
		</div>
		<?php
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

	add_shortcode( 'noo_recent_property', 'noo_shortcode_recent_property' );

endif;