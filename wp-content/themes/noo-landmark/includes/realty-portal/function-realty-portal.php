<?php
/**
 * Deactive plugin core old
 */
require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active( 'noo-landmark-core/noo-landmark-core.php' ) ) {
	deactivate_plugins( 'noo-landmark-core/noo-landmark-core.php' );
}

if ( is_plugin_active( 'noo-landmark-core-beta/noo-landmark-core-beta.php' ) ) {
	deactivate_plugins( 'noo-landmark-core-beta/noo-landmark-core-beta.php' );
}

/**
 * Check plugin Realty Portal active
 */
if ( noo_landmark_is_plugin_active( 'realty-portal/realty-portal.php' ) ) :

	/**
	 * Set pre get posts property
	 *
	 * @package     LandMark
	 * @author      KENT <tuanlv@vietbrain.com>
	 * @version     1.0
	 */
	if( !function_exists( 'noo_landmark_addon_pre_get_posts') ) :

		function noo_landmark_addon_pre_get_posts( $query ) {
			if( is_admin() ) {
				return $query;
			}
			if( $query->is_main_query() && $query->is_singular ) {
				return;
			}

			/**
			 * Set query in archive property
			 */
			if ( RP_Property::is_property() ) {

				$args = array();

				/**
				 * Check order
				 */
				$default_orderby  = isset( $query->query_vars['orderby'] ) ? $query->query_vars['orderby'] : get_theme_mod('noo_property_listing_orderby', 'date' );

				$orderby          = isset( $_GET['orderby'] ) ? sanitize_text_field( $_GET['orderby'] ) : $default_orderby;

				$orderby          = strtolower( $orderby );
				$order            = isset( $query->query_vars['order'] ) ? $query->query_vars['order'] : 'DESC';
				$args['orderby']  = $orderby;
				$args['order']    = $order == 'DESC' ? 'DESC' : 'ASC';
				$args['meta_key'] = '';

				switch ( $orderby ) {
					case 'rand' :
						$args['orderby']  = 'rand';
						break;
					case 'date' :
						$args['orderby']  = 'date';
						$args['order']    = $order == 'ASC' ? 'ASC' : 'DESC';
						break;
					case 'bath' :
						$args['orderby']  = "meta_value_num meta_value";
						$args['order']    = $order == 'DESC' ? 'DESC' : 'ASC';
						$args['meta_key'] = 'noo_property_bathrooms';
						break;
					case 'bed' :
						$args['orderby']  = "meta_value_num meta_value";
						$args['order']    = $order == 'DESC' ? 'DESC' : 'ASC';
						$args['meta_key'] = 'noo_property_bedrooms';
						break;
					case 'area' :
						$args['orderby']  = "meta_value_num meta_value";
						$args['order']    = $order == 'DESC' ? 'DESC' : 'ASC';
						$args['meta_key'] = 'noo_property_area';
						break;
					case 'price' :
						$args['orderby']  = "meta_value_num meta_value";
						$args['order']    = $order == 'DESC' ? 'DESC' : 'ASC';
						$args['meta_key'] = 'price';
						break;
					case 'featured' :
						$args['orderby']  = "meta_value";
						$args['order']    = $order == 'DESC' ? 'DESC' : 'ASC';
						$args['meta_key'] = '_featured';
						break;
					case 'name' :
						$args['orderby']  = 'title';
						$args['order']    = 'ASC'; // $order == 'DESC' ? 'DESC' : 'ASC';
						break;
				}

				$query->set( 'orderby', $args['orderby'] );
				$query->set( 'order', $args['order'] );

				if ( isset( $args['meta_key'] ) && !empty( $args['meta_key'] ) ) {
					$query->set( 'meta_key', $args['meta_key'] );
				}
				if ( isset( $args['meta_value'] ) && !empty( $args['meta_value'] ) ) {
					$query->set( 'meta_value', $args['meta_value'] );
				}
				if ( isset( $args['meta_query'] ) && !empty( $args['meta_query'] ) ) {
					$query->set( 'meta_query', $args['meta_query'] );
				}

				/**
				 * Set number show posts
				 */
				$property_per_page = get_theme_mod( 'noo_property_per_page', 10 );
				$query->set( 'posts_per_page', $property_per_page );
				$query->set( 'post_status', 'publish' );

			}

			/**
			 * Set query in archive agent
			 */
			if ( RP_Agent::is_archive_agent() ) {
				$agent_per_page = get_theme_mod( 'noo_agent_per_page', 10 );
				$query->set( 'posts_per_page', $agent_per_page );

				$agent_must_has_property =  Realty_Portal::get_setting( 'agent_setting', 'agent_must_has_property', false );
				if ( $agent_must_has_property === '1' ) {

					query_posts( 'post_type=noo_agent&posts_per_page=-1' );
					$agent_ids = array();
					while ( have_posts() ) : the_post();
						$agent_id       = get_the_ID();
						$user_id        = RP_Agent::get_id_user( $agent_id );
						if ( $user_id < 1 ) continue;
						$total_property = count_user_posts( $user_id, 'noo_property' );
						if ( $total_property > 0 ) {
							$agent_ids[] = $agent_id;
						}
					endwhile;
					wp_reset_query();
					$query->set('post__in', $agent_ids );

				}
			}

		}
		add_action( 'pre_get_posts', 'noo_landmark_addon_pre_get_posts' );
	endif;

	function noo_landmark_rp_sidebar() {
		register_sidebar( array(
			'name'          => __( 'Property Sidebar', 'noo-landmark' ),
			'id'            => 'property-sidebar',
			'description'   => __( 'Add widgets here to appear in your sidebar.', 'noo-landmark' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );

		register_sidebar( array(
			'name'          => __( 'Agent Sidebar', 'noo-landmark' ),
			'id'            => 'agent-sidebar',
			'description'   => __( 'Add widgets here to appear in your sidebar.', 'noo-landmark' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
	}

	add_action( 'widgets_init', 'noo_landmark_rp_sidebar' );

	function noo_landmark_rp_add_gallery_image() {

		if ( ! RP_Property::is_single_property() ) {
			return false;
		}

		$property_id    = get_the_ID();
		$header_show    = get_theme_mod( 'noo_property_post_header_show', 'gallery' );
		$gallery_active = 'active';
		$gallery_in     = 'in';
		$map_active     = '';
		$map_in         = '';
		if ( $header_show == 'map' ) {
			$gallery_active = '';
			$gallery_in     = '';
			$map_active     = 'active';
			$map_in         = 'in';
		}

		echo '<div class="noo-header-advance">';
		echo '<div class="noo-container">';
		echo '<div class="header-control noo-tab">';
		echo '<span class="' . esc_attr( $gallery_active ) . '" data-class="tab-gallery"><i class="fa fa-picture-o" aria-hidden="true"></i> ' . esc_html__( 'View Photo', 'noo-landmark' ) . '</span>';
		echo '<span class="' . esc_attr( $map_active ) . '" data-class="tab-map"><i class="fa fa-map-marker" aria-hidden="true"></i> ' . esc_html__( 'View Map', 'noo-landmark' ) . '</span>';
		echo '</div>';
		echo '</div>';
		echo '<div class="header-content noo-tab-content">';
		echo '<div class="content-tab tab-gallery ' . esc_attr( $gallery_in ) . '">';
		rp_gallery_image();
		echo '</div>';
		echo '<div class="content-tab tab-map ' . esc_attr( $map_in ) . '">';
		rp_box_location_on_map();
		echo '</div>';
		echo '</div>';
		echo '</div>';
	}

	add_action( 'noo_landmark_single_before', 'noo_landmark_rp_add_gallery_image' );

	function noo_landmark_rp_add_list_social() {
		$show_social = get_theme_mod( 'noo_property_social', true );
		if ( empty( $show_social ) ) {
			return false;
		}
		global $property;
		?>
		<div class="noo-property-sharing">
			<span>
				<i class="noo-tooltip-action ion-android-share-alt" data-id="<?php echo esc_attr( $property->ID ); ?>" data-process="share"></i>
			</span>
			<?php rp_social_sharing_property(); ?>
		</div>
		<?php
	}

	add_action( 'rp_property_list_more_action', 'noo_landmark_rp_add_list_social', 6 );

	function noo_landmark_rp_add_sidebar() {
		if ( !is_front_page() && !is_home() && !is_singular( 'noo_property' ) && !is_singular( 'noo_agent' ) && !is_tag() ) {
			RP_Template::get_template( 'menu-user.php', '', '', get_template_directory() . '/realty-portal/member' );
		}
	}

	add_action( 'noo_before_sidebar', 'noo_landmark_rp_add_sidebar', 0 );

	if ( ! function_exists( 'noo_landmark_custom_list_floor_plan' ) ) :

		function noo_landmark_custom_list_floor_plan( $property_id, $index = 1 ) {

			$floor_plans = get_post_meta( $property_id, 'floor_plans', true );
			if ( ! is_array( $floor_plans ) ) {
				return;
			}

			$floor_plans = array_values( $floor_plans );
			$floor_plan  = $floor_plans[ $index ];

			$plan_title       = array_key_exists( 'plan_title', $floor_plan ) ? $floor_plan[ 'plan_title' ] : '';
			$plan_bedrooms    = array_key_exists( 'plan_bedrooms', $floor_plan ) ? $floor_plan[ 'plan_bedrooms' ] : '';
			$plan_bathrooms   = array_key_exists( 'plan_bathrooms', $floor_plan ) ? $floor_plan[ 'plan_bathrooms' ] : '';
			$plan_price       = array_key_exists( 'plan_price', $floor_plan ) ? $floor_plan[ 'plan_price' ] : '';
			$plan_size        = array_key_exists( 'plan_size', $floor_plan ) ? $floor_plan[ 'plan_size' ] : '';
			$plan_description = array_key_exists( 'plan_description', $floor_plan ) ? $floor_plan[ 'plan_description' ] : '';
			$plan_image       = array_key_exists( 'plan_image', $floor_plan ) ? $floor_plan[ 'plan_image' ] : '';
			if ( ! empty( $plan_title ) ) {
				?>
				<div class="rp-property-floor-plan-item rp-row">
					<span class="loading fa fa-spinner fa-spin"></span>
					<div class="rp-left rp-md-5">
						<div class="rp-property-floor-plan">
							<div class="rp-property-floor-plan-wrapper">
								<?php
								if ( is_array( $plan_image ) ) {
									foreach ( $plan_image as $image ) { ?>
										<a class="floor-plan-item"
										   href="<?php echo esc_attr( rp_thumb_src_id( $image, 'full', '178x126' ) ) ?>">
											<img src="<?php echo esc_attr( rp_thumb_src_id( $image, 'thumbnail', '178x126' ) ) ?>"
											     alt="<?php the_title() ?>" />
										</a>
									<?php }
								}
								?>
							</div>
							<div class="rp-arrow-button">
								<i class="rp-arrow-back rp-icon-ion-ios-arrow-left"></i>
								<i class="rp-arrow-next rp-icon-ion-ios-arrow-right"></i>
							</div>
						</div><!-- /.rp-property-floor-plan -->
					</div>
					<div class="rp-right rp-md-7">
						<div class="floor-plan-content rp-row">
							<?php
							if ( ! empty( $plan_size ) ) {
								echo '<div class="rp-md-6">' . sprintf( wp_kses( __( '<strong>Size:</strong> %s', 'noo-landmark' ), rp_allowed_html() ), $plan_size ) . '</div>';
							}

							if ( ! empty( $plan_bedrooms ) ) {
								echo '<div class="rp-md-6">' . sprintf( wp_kses( __( '<strong>Rooms:</strong> %s', 'noo-landmark' ), rp_allowed_html() ), $plan_bedrooms ) . '</div>';
							}

							if ( ! empty( $plan_bathrooms ) ) {
								echo '<div class="rp-md-6">' . sprintf( wp_kses( __( '<strong>Baths:</strong> %s', 'noo-landmark' ), rp_allowed_html() ), $plan_bathrooms ) . '</div>';
							}

							if ( ! empty( $plan_price ) ) {
								echo '<div class="rp-md-6">' . sprintf( wp_kses( __( '<strong>Price:</strong> %s', 'noo-landmark' ), rp_allowed_html() ), $plan_price ) . '</div>';
							}
							?>
						</div>

						<?php
						if ( ! empty( $plan_description ) ) {
							echo sprintf( wp_kses( __( '<p class="floor-plan-description">%s</p>', 'noo-landmark' ), rp_allowed_html() ), $plan_description );
						}
						?>
					</div>
				</div><!-- /.rp-property-floor-plan-item -->
				<script>
					jQuery(document).ready(function ( $ ) {
						jQuery('body').find(".rp-property-floor-plan-wrapper").lightGallery({
							thumbnail         : true,
							animateThumb      : true,
							showThumbByDefault: true
						});
					});
				</script>
				<?php
			}
		}

	endif;

	function noo_custom_property_post_type( $post_type ) {
		return 'noo_property';
	}

	add_filter( 'rp_property_post_type', 'noo_custom_property_post_type' );

	function noo_custom_agent_post_type( $post_type ) {
		return 'noo_agent';
	}

	add_filter( 'rp_agent_post_type', 'noo_custom_agent_post_type' );

	function noo_custom_payment_post_type( $post_type ) {
		return 'noo_payment';
	}

	add_filter( 'rp_payment_post_type', 'noo_custom_payment_post_type' );

	function noo_custom_membership_post_type( $post_type ) {
		return 'noo_membership';
	}

	add_filter( 'rp_membership_post_type', 'noo_custom_membership_post_type' );

	function noo_custom_listing_type( $type ) {
		return 'property_type';
	}

	add_filter( 'rp_property_listing_type', 'noo_custom_listing_type' );

	function noo_custom_listing_offers( $type ) {
		return 'property_status';
	}

	add_filter( 'rp_property_listing_offers', 'noo_custom_listing_offers' );

	function noo_show_nav_menu() {
		return false;
	}

	add_filter( 'rp_show_nav_menu', 'noo_show_nav_menu' );

	function noo_landmark_pagination_filter( $defaults ) {
		$defaults[ 'next_text' ] = '';
		$defaults[ 'prev_text' ] = '';

		return $defaults;
	}

	add_filter( 'rp_pagination_loop_args_defaults', 'noo_landmark_pagination_filter', 99 );

	function noo_landmark_custom_page_layout( $layout ) {
		if ( RP_Agent::is_single_agent() ) {
			$layout = 'fullwidth';
		}

		return $layout;
	}

	add_filter( 'noo_page_layout', 'noo_landmark_custom_page_layout', 99 );

	function noo_landmark_custom_sidebar_id( $layout ) {
		if ( RP_Agent::is_single_agent() ) {
			$layout = '';
		}

		return $layout;
	}

	add_filter( 'noo_sidebar_id', 'noo_landmark_custom_sidebar_id', 99 );

	function noo_landmark_add_custom_field_type( $args ) {
		return array();
	}

	function noo_lankmark_custom_addon() {
		add_filter( 'RP_Custom_Fields/addField', 'noo_landmark_add_custom_field_type' );
	}

	add_action( 'init', 'noo_lankmark_custom_addon', 1 );

	remove_action( 'rp_property_list_more_action', 'RP_My_Favorites_Process::add_button_favorites', 5 );
	add_action( 'rp_property_list_more_action', 'noo_lankmark_custom_add_button_favorites', 5 );
	function noo_lankmark_custom_add_button_favorites( $property ) {
		$show_favories = get_theme_mod( 'noo_property_favories', true );
		if ( empty( $show_favories ) ) {
			return false;
		}
		if ( class_exists( 'RP_My_Favorites_Process' ) && class_exists( 'RP_AddOn_My_Favorites' ) && class_exists( 'RP_Agent' ) ) :
			?>
			<span>
			<i class="rp-event favorites noo-tooltip-action <?php echo RP_My_Favorites_Process::get_favorites( 'icon' ); ?>"
			   data-id="<?php echo $property->ID; ?>" data-user="<?php echo RP_Agent::is_user(); ?>"
			   data-process="favorites" data-status="<?php echo RP_My_Favorites_Process::get_favorites( 'class' ); ?>"
			   data-url="<?php echo RP_AddOn_My_Favorites::get_url_favorites(); ?>"
			   data-content="Favorites"></i>
		</span>
			<?php
		endif;
	}

	remove_action( 'rp_property_list_more_action', 'RP_Compare_Process::add_button_compare', 10 );
	add_action( 'rp_property_list_more_action', 'noo_lankmark_custom_add_button_compare', 10 );
	function noo_lankmark_custom_add_button_compare( $property ) {
		$show_compare = get_theme_mod( 'noo_property_compare', true );
		if ( empty( $show_compare ) ) {
			return false;
		}
		if ( class_exists( 'RP_Agent' ) && is_object( $property ) ) :
			?>
			<span class="compare">
			<i class="rp-event compare rp-icon-exchange noo-tooltip-action" aria-hidden="true"
			   data-id="<?php echo $property->ID; ?>" data-user="<?php echo RP_Agent::is_user(); ?>"
			   data-process="compare"
			   data-thumbnail="<?php echo $property->thumbnail( 'rp-agent-avatar', false ); ?>"
			   data-content="Compare"></i>
		</span>
			<?php
		endif;
	}

	remove_action( 'rp_before_main_agent_profile', 'RP_Package_Config_Dashboard_Setting::menu_sidebar', 999999 );

endif;