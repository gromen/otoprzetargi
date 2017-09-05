<?php
/**
 * Create class RP_Property
 * This class process property
 *
 * @package     Realty_Portal
 * @author      NooTeam <suppport@nootheme.com>
 */
if ( ! class_exists( 'RP_Property' ) ) :

	class RP_Property {

		/**
		 * A reference to an instance of this class.
		 */
		private static $instance;

		/**
		 * Returns an instance of this class.
		 */
		public static function get_instance() {

			if ( null == self::$instance ) {
				self::$instance = new RP_Property();
			}

			return self::$instance;
		}

		/**
		 * Initializes the plugin by setting filters and administration functions.
		 */
		private function __construct() {

			/**
			 * Loader action/filter
			 */
			add_action( 'init', 'RP_Property::register_property', 1 );

			add_action( 'init', 'RP_Property::register_size_image' );

			$this->load_library();

			add_action( 'add_meta_boxes', 'RP_Property::register_metabox' );
		}

		public static function query( $args = array() ) {
			global $wpdb;

			$defaults = array(
				'p'                   => '',
				'post__in'            => '',
				'offset'              => '',
				'post_status'         => '',
				'posts_per_page'      => get_query_var( 'nr' ) ? get_query_var( 'nr' ) : get_option( 'posts_per_page' ),
				'orderby'             => get_query_var( 'orderby' ) ? get_query_var( 'orderby' ) : 'date',
				'order'               => get_query_var( 'order' ) ? get_query_var( 'order' ) : 'DESC',
				'author'              => '',
				'tax_query'           => array(),
				'meta_query'          => array(),
				'ignore_sticky_posts' => 1,
				'show_panel'          => true,
				'show_paging'         => true,
			);

			// Add custom vars to $defaults
			$defaults = array_merge( $defaults, array() );

			// Get args from WP_Query object

			if ( is_object( $args ) && isset( $args->query_vars ) ) {
				$args = $args->query_vars;
			}

			// Merge $defaults with $args
			$args = wp_parse_args( $args, $defaults );

			// Make sure paging works

			if ( get_query_var( 'paged' ) ) {
				$paged = get_query_var( 'paged' );
			} elseif ( get_query_var( 'page' ) ) {
				$paged = get_query_var( 'page' );
			} else {
				$paged = 1;
			}

			if ( isset( $args[ 'paged' ] ) ) {
				$paged = absint( $args[ 'paged' ] );
			}

			if ( false === $args[ 'show_paging' ] ) {
				$paged = 1;
			}

			// Make sure nr arg works too
			if ( ! empty( $args[ 'nr' ] ) ) {
				$args[ 'posts_per_page' ] = intval( $args[ 'nr' ] );
			}

			// Make sure offset is intval or empty
			$args[ 'offset' ] = ! empty( $args[ 'offset' ] ) ? absint( $args[ 'offset' ] ) : '';

			$query_args = array(
				'p'                   => absint( $args[ 'p' ] ),
				'post__in'            => $args[ 'post__in' ],
				'post_type'           => apply_filters( 'rp_property_post_type', 'rp_property' ),
				'ignore_sticky_posts' => $args[ 'ignore_sticky_posts' ],
				'offset'              => $args[ 'offset' ],
				'posts_per_page'      => intval( $args[ 'posts_per_page' ] ),
				'orderby'             => $args[ 'orderby' ],
				'order'               => $args[ 'order' ],
				'tax_query'           => $args[ 'tax_query' ],
				'meta_query'          => $args[ 'meta_query' ],
				'paged'               => $paged,
				'post_status'         => $args[ 'post_status' ],
			);

			// Set post_status
			if ( empty( $args[ 'post_status' ] ) || ! is_user_logged_in() ) {
				$query_args[ 'post_status' ] = 'publish';
			} else {
				$query_args[ 'post_status' ] = $args[ 'post_status' ];
				if ( ! is_array( $args[ 'post_status' ] ) && strpos( $args[ 'post_status' ], ',' ) ) {
					$query_args[ 'post_status' ] = explode( ',', $args[ 'post_status' ] );
				}
			}

			if ( isset( $_GET[ 'keyword' ] ) && ! empty( $_GET[ 'keyword' ] ) ) {

				$query_args[ 's' ] = $_GET[ 'keyword' ];
			}

			// Set taxonomy query
			$tax_query = self::tax_query( $_GET );

			if ( ! empty( $tax_query ) ) {
				$tax_query[ 'relation' ] = 'AND';
				if ( is_object( $query ) && get_class( $query ) == 'WP_Query' ) {
					$query->tax_query->queries        = $tax_query;
					$query->query_vars[ 'tax_query' ] = $query->tax_query->queries;
				} elseif ( is_array( $query ) ) {
					$query[ 'tax_query' ] = $tax_query;
				}
			}

			$meta_query = array();
			if ( isset( $_GET[ 'min_area' ] ) && ! empty( $_GET[ 'min_area' ] ) ) {
				$min_area[ 'key' ]     = 'rp_property_area';
				$min_area[ 'value' ]   = intval( $_GET[ 'min_area' ] );
				$min_area[ 'type' ]    = 'NUMERIC';
				$min_area[ 'compare' ] = '>=';
				$meta_query[]          = $min_area;
			}
			if ( isset( $_GET[ 'max_area' ] ) && ! empty( $_GET[ 'max_area' ] ) ) {
				$max_area[ 'key' ]     = 'rp_property_area';
				$max_area[ 'value' ]   = intval( $_GET[ 'max_area' ] );
				$max_area[ 'type' ]    = 'NUMERIC';
				$max_area[ 'compare' ] = '<=';
				$meta_query[]          = $max_area;
			}
			if ( isset( $_GET[ 'min_price' ] ) && ! empty( $_GET[ 'min_price' ] ) ) {
				$min_price[ 'key' ]     = 'price';
				$min_price[ 'value' ]   = floatval( $_GET[ 'min_price' ] );
				$min_price[ 'type' ]    = 'NUMERIC';
				$min_price[ 'compare' ] = '>=';
				$meta_query[]           = $min_price;
			}
			if ( isset( $_GET[ 'max_price' ] ) && ! empty( $_GET[ 'max_price' ] ) ) {
				$max_price[ 'key' ]     = 'price';
				$max_price[ 'value' ]   = floatval( $_GET[ 'max_price' ] );
				$max_price[ 'type' ]    = 'NUMERIC';
				$max_price[ 'compare' ] = '<=';
				$meta_query[]           = $max_price;
			}

			$property_fields = rp_property_render_fields();

			if ( ! empty( $property_fields ) ) {
				unset( $property_fields[ '' ] );
				foreach ( $property_fields as $field ) {

					if ( ! array_key_exists( 'name', $field ) ) {
						continue;
					}

					$field_id = apply_filters( 'rp_property_post_type', 'rp_property' ) . rp_property_custom_fields_name( $field[ 'name' ] );

					if ( isset( $_GET[ $field_id ] ) && ! empty( $_GET[ $field_id ] ) ) {
						$value = rp_validate_data( $_GET[ $field_id ], $field );
						if ( is_array( $value ) ) {
							$temp_meta_query = array( 'relation' => 'OR' );
							foreach ( $value as $v ) {
								if ( empty( $v ) ) {
									continue;
								}
								$temp_meta_query[] = array(
									'key'     => $field_id,
									'value'   => '"' . $v . '"',
									'compare' => 'LIKE',
								);
							}
							$meta_query[] = $temp_meta_query;
						} else {
							$meta_query[] = array(
								'key'   => $field_id,
								'value' => esc_attr( $value ),
							);
						}
					} elseif ( ( isset( $field[ 'type' ] ) && 'datepicker' == $field[ 'type' ] ) && ( isset( $_GET[ $field_id . '_start' ] ) || isset( $_GET[ $field_id . '_end' ] ) ) ) {
						if ( $field_id == 'date' ) {
							$date_query = array();
							if ( isset( $_GET[ $field_id . '_start' ] ) && ! empty( $_GET[ $field_id . '_start' ] ) ) {
								$start                 = is_numeric( $_GET[ $field_id . '_start' ] ) ? date( 'Y-m-d', $_GET[ $field_id . '_start' ] ) : $_GET[ $field_id . '_start' ];
								$date_query[ 'after' ] = date( 'Y-m-d', strtotime( $start . ' -1 day' ) );
							}
							if ( isset( $_GET[ $field_id . '_end' ] ) && ! empty( $_GET[ $field_id . '_end' ] ) ) {
								$end                    = is_numeric( $_GET[ $field_id . '_end' ] ) ? date( 'Y-m-d', $_GET[ $field_id . '_end' ] ) : $_GET[ $field_id . '_end' ];
								$date_query[ 'before' ] = date( 'Y-m-d', strtotime( $end . ' +1 day' ) );
							}

							if ( is_object( $query ) && get_class( $query ) == 'WP_Query' ) {
								$query->query_vars[ 'date_query' ][] = $date_query;
							} elseif ( is_array( $query ) ) {
								$query[ 'date_query' ] = $date_query;
							}
						} else {
							$value_start = isset( $_GET[ $field_id . '_start' ] ) && ! empty( $_GET[ $field_id . '_start' ] ) ? rp_validate_data( $_GET[ $field_id . '_start' ], $field ) : 0;
							$value_start = ! empty( $value_start ) ? strtotime( "midnight", $value_start ) : 0;
							$value_end   = isset( $_GET[ $field_id . '_end' ] ) && ! empty( $_GET[ $field_id . '_end' ] ) ? rp_validate_data( $_GET[ $field_id . '_end' ], $field ) : 0;
							$value_end   = ! empty( $value_end ) ? strtotime( "tomorrow", strtotime( "midnight", $value_end ) ) - 1 : strtotime( '2090/12/31' );

							$meta_query[] = array(
								'key'     => $field_id,
								'value'   => array(
									$value_start,
									$value_end,
								),
								'compare' => 'BETWEEN',
								'type'    => 'NUMERIC',
							);
						}
					}
				}
			}

			/**
			 * Check request field location
			 */
			$list_location = array(
				'address',
				'country',
				'city',
				'neighborhood',
				'zip',
				'state',
				'latitude',
				'longitude',
			);

			foreach ( $list_location as $location_item ) {
				if ( ! empty( $_GET[ $location_item ] ) ) {
					$meta_query[] = array(
						'key'   => $location_item,
						'value' => sanitize_text_field( $_GET[ $location_item ] ),
					);
				}
			}

			$property_features = rp_render_featured_amenities();
			if ( ! empty( $property_features ) ) {

				foreach ( $property_features as $key => $feature ) {
					$field_id = apply_filters( 'rp_property_post_type', 'rp_property' ) . sanitize_title( $key );
					if ( isset( $_GET[ $field_id ] ) && ! empty( $_GET[ $field_id ] ) ) {
						$meta_query[] = array(
							'key'   => $field_id,
							'value' => '1',
						);
					}
				}
			}

			if ( ! empty( $meta_query ) ) {
				$meta_query[ 'relation' ]     = 'AND';
				$query_args[ 'meta_query' ][] = $meta_query;
			}

			foreach ( rp_taxonomies( 'names' ) as $k ) {

				if ( ! empty( $args[ $k ] ) ) {

					// Set operator
					$operator = 'IN';

					if ( is_array( $args[ $k ] ) ) {
						$args[ $k ] = implode( ',', $args[ $k ] );
					}

					// Check URL for multiple terms

					if ( strpos( $args[ $k ], ',' ) ) {
						$args[ $k ] = explode( ',', $args[ $k ] );
					} elseif ( strpos( $args[ $k ], '|' ) ) {
						$args[ $k ] = explode( '|', $args[ $k ] );
						$operator   = 'AND';
					}

					if ( ! empty( $args[ $k ] ) ) {

						$query_args[ 'tax_query' ][ $k ] = array(
							'taxonomy' => $k,
							'field'    => 'slug',
							'terms'    => $args[ $k ],
							'operator' => $operator,
						);
					}
				}
			}

			// Remove tax_query if empty

			if ( empty( $query_args[ 'tax_query' ] ) ) {
				unset( $query_args[ 'tax_query' ] );
			}

			/**
			 * Check order
			 */

			$orderby                  = isset( $_GET[ 'orderby' ] ) ? sanitize_text_field( $_GET[ 'orderby' ] ) : 'date';
			$orderby                  = strtolower( $orderby );
			$order                    = isset( $_GET[ 'order' ] ) ? sanitize_text_field( $_GET[ 'order' ] ) : 'DESC';
			$query_args[ 'orderby' ]  = $orderby;
			$query_args[ 'order' ]    = $order == 'DESC' ? 'DESC' : 'ASC';
			$query_args[ 'meta_key' ] = '';

			switch ( $orderby ) {
				case 'rand' :
					$query_args[ 'orderby' ] = 'rand';
					break;
				case 'date' :
					$query_args[ 'orderby' ] = 'date';
					$query_args[ 'order' ]   = $order == 'ASC' ? 'ASC' : 'DESC';
					break;
				case 'bath' :
					$query_args[ 'orderby' ]  = "meta_value_num meta_value";
					$query_args[ 'order' ]    = $order == 'DESC' ? 'DESC' : 'ASC';
					$query_args[ 'meta_key' ] = apply_filters( 'rp_property_post_type', 'rp_property' ) . '_bathrooms';
					break;
				case 'bed' :
					$query_args[ 'orderby' ]  = "meta_value_num meta_value";
					$query_args[ 'order' ]    = $order == 'DESC' ? 'DESC' : 'ASC';
					$query_args[ 'meta_key' ] = apply_filters( 'rp_property_post_type', 'rp_property' ) . '_bedrooms';
					break;
				case 'area' :
					$query_args[ 'orderby' ]  = "meta_value_num meta_value";
					$query_args[ 'order' ]    = $order == 'DESC' ? 'DESC' : 'ASC';
					$query_args[ 'meta_key' ] = apply_filters( 'rp_property_post_type', 'rp_property' ) . '_area';
					break;
				case 'price' :
					$query_args[ 'orderby' ]  = "meta_value_num meta_value";
					$query_args[ 'order' ]    = $order == 'DESC' ? 'DESC' : 'ASC';
					$query_args[ 'meta_key' ] = 'price';
					break;
				case 'featured' :
					$query_args[ 'orderby' ]  = "meta_value";
					$query_args[ 'order' ]    = $order == 'DESC' ? 'DESC' : 'ASC';
					$query_args[ 'meta_key' ] = '_featured';
					break;
				case 'name' :
					$query_args[ 'orderby' ] = 'title';
					$query_args[ 'order' ]   = 'ASC'; // $order == 'DESC' ? 'DESC' : 'ASC';
					break;
			}

			// Filter args
			$query_args = apply_filters( 'rp_get_property_query_args', $query_args, $args );

			do_action( 'rp_get_property_before', $query_args, $args );

			$result = new WP_Query( $query_args );

			do_action( 'rp_get_property_after', $query_args, $args );

			// Reset query
			wp_reset_query();

			return apply_filters( 'rp_get_property', $result, $query_args, $args );
		}

		/**
		 * Check archive property
		 *
		 * @return string
		 */
		public static function is_property() {

			if ( ! RP_Template::locate_template( 'property/archive-property.php' ) && ( is_post_type_archive( apply_filters( 'rp_property_post_type', 'rp_property' ) ) || is_tax( apply_filters( 'rp_property_listing_offers', 'listing_offers' ) ) || is_tax( apply_filters( 'rp_property_listing_type', 'listing_type' ) ) ) ) {
				return true;
			}

			return false;
		}

		/** Check single property
		 *
		 * @return string
		 */
		public static function is_single_property() {

			if ( is_singular( apply_filters( 'rp_property_post_type', 'rp_property' ) ) && ! RP_Template::locate_template( 'property/single-property.php' ) ) {
				return true;
			}

			return false;
		}

		public static function tax_query( $REQUEST, $filter = 'id' ) {

			$tax_query = array();
			$tax_list  = array(
				'types'  => apply_filters( 'rp_property_listing_type', 'listing_type' ),
				'status' => apply_filters( 'rp_property_listing_offers', 'listing_offers' ),
			);
			$tax_list  = apply_filters( 'rp_property_query_tax_list', $tax_list );
			if ( ! empty( $tax_list ) ) {

				foreach ( $tax_list as $tax_key => $term ) {
					if ( isset( $REQUEST[ $tax_key ] ) && ! empty( $REQUEST[ $tax_key ] ) ) {
						$tax_query[] = array(
							'taxonomy' => $term,
							'field'    => $filter,
							'terms'    => $REQUEST[ $tax_key ],
						);
					}
				}
			}

			$tax_query = apply_filters( 'rp_property_search_tax_query', $tax_query, $REQUEST );

			return $tax_query;
		}

		/**
		 * Register size image
		 */
		public static function register_size_image() {

			add_image_size( 'rp-agent-avatar', 268, 210, true );
			add_image_size( 'rp-property-slider', 1920, 800, true );
			add_image_size( 'rp-property-medium', 370, 210, true );
			add_image_size( 'rp-property-small', 128, 70, true );
			add_image_size( 'rp-property-map', 180, 150, true );
			add_image_size( 'rp-property-thumbnail', 240, 240, true );
			add_image_size( 'rp-property-floor-plan', 178, 126, true );
		}

		/**
		 * Get value setting property
		 */
		public static function get_setting( $name = '', $value = '', $default = '' ) {

			if ( empty( $name ) ) {
				return false;
			}

			$property_setting = (array) get_option( esc_attr( $name ), array() );

			if ( array_key_exists( $value, $property_setting ) ) {

				if ( ! empty( $value ) && ! empty( $property_setting[ $value ] ) ) {
					return $property_setting[ $value ];
				}
			}

			if ( empty( $value ) && ! empty( $property_setting ) ) {
				return $property_setting;
			}

			return $default;
		}

		/**
		 * Get path property
		 */
		public static function get_path( $name = '' ) {

			$property_path = untrailingslashit( plugin_dir_path( __FILE__ ) . 'property/' . $name );

			return $property_path;
		}

		/**
		 * Loader function
		 */
		public function load_library() {

			/**
			 * Load library
			 */
			$list_library = glob( self::get_path( 'library/*.php' ) );
			if ( ! empty( $list_library ) && is_array( $list_library ) ) {
				foreach ( $list_library as $filename ) {
					require $filename;
				}
			}

			$list_process = glob( self::get_path( 'process/*.php' ) );
			if ( ! empty( $list_process ) && is_array( $list_process ) ) {
				foreach ( $list_process as $filename ) {
					require $filename;
				}
			}

			/**
			 * Load fields
			 */
			include( dirname( __FILE__ ) . '/property/fields/property_featured/property_featured.php' );
			include( dirname( __FILE__ ) . '/property/fields/custom_fields/custom_fields.php' );
		}

		/**
		 * Create post type rp_property
		 */
		public static function register_property() {

			/**
			 * Check post type exits
			 */
			if ( post_type_exists( apply_filters( 'rp_property_post_type', 'rp_property' ) ) ) {
				return;
			}

			/**
			 * Check support icon
			 */
			$rp_icon = '';
			if ( floatval( get_bloginfo( 'version' ) ) >= 3.8 ) {
				$rp_icon = 'dashicons-location';
			}

			/**
			 * Register post type
			 */
			$labels = array(
				'name'               => _x( 'Properties', 'property', 'realty-portal' ),
				'singular_name'      => _x( 'Property', 'property', 'realty-portal' ),
				'add_new'            => _x( 'Add New', 'property', 'realty-portal' ),
				'add_new_item'       => _x( 'Add New Property', 'property', 'realty-portal' ),
				'edit_item'          => _x( 'Edit Property', 'property', 'realty-portal' ),
				'new_item'           => _x( 'New Property', 'property', 'realty-portal' ),
				'view_item'          => _x( 'View Property', 'property', 'realty-portal' ),
				'search_items'       => _x( 'Search Properties', 'property', 'realty-portal' ),
				'not_found'          => _x( 'No properties found', 'property', 'realty-portal' ),
				'not_found_in_trash' => _x( 'No properties found in Trash', 'property', 'realty-portal' ),
				'menu_name'          => _x( 'Properties', 'property', 'realty-portal' ),
			);

			$labels = apply_filters( 'rp_post_type_labels_property', $labels );

			$args = array(
				'label'               => _x( 'Properties', 'property', 'realty-portal' ),
				'description'         => _x( 'Searchable properties with detailed information about the corresponding item.', 'property', 'realty-portal' ),
				'labels'              => $labels,
				'hierarchical'        => false,
				'supports'            => array(
					'title',
					'editor',
					'author',
					'thumbnail',
				),
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'menu_position'       => 30,
				'menu_icon'           => $rp_icon,
				'publicly_queryable'  => true,
				'exclude_from_search' => false,
				'has_archive'         => true,
				'query_var'           => true,
				'can_export'          => true,
				'rewrite'             => array(
					'slug'       => self::get_setting( 'property_setting', 'archive_slug', 'properties' ),
					'with_front' => true,
				),
				'map_meta_cap'        => true,
			);

			$args = apply_filters( 'rp_post_type_args_property', $args );

			// Register post type
			register_post_type( apply_filters( 'rp_property_post_type', 'rp_property' ), $args );

			/**
			 * Register taxonomy
			 */
			$types_name     = apply_filters( 'rp_taxonomy_types_name', __( 'Listing Types', 'realty-portal' ) );
			$types_singular = apply_filters( 'rp_taxonomy_types_singular', __( 'Listing Type', 'realty-portal' ) );

			$types_labels = array(
				'name'                       => $types_name,
				'singular_name'              => $types_singular,
				'menu_name'                  => _x( 'Listing Types', 'taxonomy types', 'realty-portal' ),
				'all_items'                  => _x( 'All Listing Types', 'taxonomy types', 'realty-portal' ),
				'edit_item'                  => _x( 'Edit Listing Type', 'taxonomy types', 'realty-portal' ),
				'view_item'                  => _x( 'View Listing Type', 'taxonomy types', 'realty-portal' ),
				'update_item'                => _x( 'Update Listing Type', 'taxonomy types', 'realty-portal' ),
				'add_new_item'               => _x( 'Add New Listing Type', 'taxonomy types', 'realty-portal' ),
				'new_item_name'              => _x( 'New Listing Type Name', 'taxonomy types', 'realty-portal' ),
				'parent_item'                => _x( 'Parent Listing Type', 'taxonomy types', 'realty-portal' ),
				'parent_item_colon'          => _x( 'Parent Listing Type:', 'taxonomy types', 'realty-portal' ),
				'search_items'               => _x( 'Search listing types', 'taxonomy types', 'realty-portal' ),
				'popular_items'              => _x( 'Popular Listing Types', 'taxonomy types', 'realty-portal' ),
				'separate_items_with_commas' => _x( 'Separate listing types with commas', 'taxonomy types', 'realty-portal' ),
				'add_or_remove_items'        => _x( 'Add or remove listing types', 'taxonomy types', 'realty-portal' ),
				'choose_from_most_used'      => _x( 'Choose from the most used listing types', 'taxonomy types', 'realty-portal' ),
				'not_found'                  => _x( 'No listing type found', 'taxonomy types', 'realty-portal' ),
			);

			$types_args = array(
				'labels'       => $types_labels,
				'hierarchical' => true,
				'rewrite'      => array(
					'slug'       => self::get_setting( 'property_setting', apply_filters( 'rp_property_listing_type', 'listing_type' ), 'type' ),
					'with_front' => false,
				),
			);

			$types_args = apply_filters( 'rp_taxonomy_types_args', $types_args );

			register_taxonomy( apply_filters( 'rp_property_listing_type', 'listing_type' ), apply_filters( 'rp_property_post_type', 'rp_property' ), $types_args );

			$offers_name     = apply_filters( 'rp_taxonomy_offers_name', __( 'Listing Offers', 'realty-portal' ) );
			$offers_singular = apply_filters( 'rp_taxonomy_offers_singular', __( 'Offer', 'realty-portal' ) );

			$offers_labels = array(
				'name'                       => $offers_name,
				'singular_name'              => $offers_singular,
				'menu_name'                  => _x( 'Listing Offers', 'taxonomy offers', 'realty-portal' ),
				'all_items'                  => _x( 'All Listing Offers', 'taxonomy offers', 'realty-portal' ),
				'edit_item'                  => _x( 'Edit Offer', 'taxonomy offers', 'realty-portal' ),
				'view_item'                  => _x( 'View Offer', 'taxonomy offers', 'realty-portal' ),
				'update_item'                => _x( 'Update Offer', 'taxonomy offers', 'realty-portal' ),
				'add_new_item'               => _x( 'Add New Offer', 'taxonomy offers', 'realty-portal' ),
				'new_item_name'              => _x( 'New Offer Name', 'taxonomy offers', 'realty-portal' ),
				'parent_item'                => _x( 'Parent Offer', 'taxonomy offers', 'realty-portal' ),
				'parent_item_colon'          => _x( 'Parent Offer:', 'taxonomy offers', 'realty-portal' ),
				'search_items'               => _x( 'Search listing offers', 'taxonomy offers', 'realty-portal' ),
				'popular_items'              => _x( 'Popular Listing Offers', 'taxonomy offers', 'realty-portal' ),
				'separate_items_with_commas' => _x( 'Separate listing offers with commas', 'taxonomy offers', 'realty-portal' ),
				'add_or_remove_items'        => _x( 'Add or remove listing offers', 'taxonomy offers', 'realty-portal' ),
				'choose_from_most_used'      => _x( 'Choose from the most used listing offers', 'taxonomy offers', 'realty-portal' ),
				'not_found'                  => _x( 'No listing type found', 'taxonomy offers', 'realty-portal' ),
			);

			$offers_args = array(
				'labels'       => $offers_labels,
				'hierarchical' => true,
				'rewrite'      => array(
					'slug'       => self::get_setting( 'property_setting', 'listing_offers_slug', 'offers' ),
					'with_front' => false,
				),
			);

			$offers_args = apply_filters( 'rp_taxonomy_offers_args', $offers_args );

			register_taxonomy( apply_filters( 'rp_property_listing_offers', 'listing_offers' ), apply_filters( 'rp_property_post_type', 'rp_property' ), $offers_args );

			/**
			 * Register post status
			 */
			register_post_status( 'expired', array(
				'label'                     => esc_html__( 'Expired', 'realty-portal' ),
				'public'                    => true,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				'label_count'               => _n_noop( __( 'Expired <span class="count">(%s)</span>', 'realty-portal' ), __( 'Unread <span class="count">(%s)</span>', 'realty-portal' ) ),
			) );
		}

		/**
		 * Create metabox
		 */
		public static function register_metabox() {

			/**
			 * VAR
			 */
			$prefix        = apply_filters( 'rp_property_post_type', 'rp_property' );
			$custom_fields = rp_property_render_fields();

			$list_field = array();

			foreach ( $custom_fields as $field ) {

				if ( empty( $field[ 'name' ] ) || empty( $field[ 'type' ] ) || ! empty( $field[ 'disable' ] ) ) {
					unset( $field );
					continue;
				}

				$field[ 'id' ]  = esc_attr( $prefix . $field[ 'name' ] );
				$field[ 'std' ] = esc_html( $field[ 'value' ] );

				if ( in_array( $field[ 'type' ], rp_has_choice_field_types() ) ) {

					if ( 'checkbox' == $field[ 'type' ] ) {
						$field[ 'type' ] = 'multiple_checkbox';
					}
					if ( 'select' == $field[ 'type' ] ) {
						$field[ 'option_none' ] = true;
					}

					$list_options = explode( "\n", $field[ 'value' ] );
					foreach ( $list_options as $option ) {
						$field[ 'options' ][] = array(
							'label' => esc_html( $option ),
							'value' => sanitize_title( $option ),
						);
					}
					unset( $field[ 'std' ] );
				}

				unset( $field[ 'name' ] );
				unset( $field[ 'value' ] );
				unset( $field[ 'hide' ] );
				unset( $field[ 'disable' ] );
				unset( $field[ 'readonly' ] );
				unset( $field[ 'required' ] );

				$list_field[] = $field;
			}

			$helper = new RP_Meta_Boxes( $prefix, array(
				'page' => apply_filters( 'rp_property_post_type', 'rp_property' ),
			) );

			/**
			 * Create box: General Information
			 */
			$currency_symbol = rp_currency_symbol();

			$meta_box = array(
				'id'     => "{$prefix}_general_information",
				'title'  => esc_html__( 'General Information', 'realty-portal' ),
				'fields' => array(
					array(
						'id'    => 'price',
						'label' => sprintf( esc_html__( 'Price (%s)', 'realty-portal' ), $currency_symbol ),
						'type'  => 'number',
					),
					array(
						'id'    => 'before_price',
						'label' => esc_html__( 'Before Price Label', 'realty-portal' ),
						'type'  => 'text',
					),
					array(
						'id'    => 'after_price',
						'label' => esc_html__( 'After Price Label', 'realty-portal' ),
						'type'  => 'text',
					),
					array(
						'id'    => 'document',
						'label' => esc_html__( 'Document', 'realty-portal' ),
						'type'  => 'text',
						'std'   => esc_html__( 'Link http://', 'realty-portal' ),
					),
					array(
						'id'    => 'video',
						'label' => esc_html__( 'Video', 'realty-portal' ),
						'type'  => 'text',
						'std'   => esc_html__( 'Link http://', 'realty-portal' ),
					),
				),
			);

			$helper->add_meta_box( $meta_box );

			/**
			 * Create box: Additional Information
			 */
			$meta_box = array(
				'id'     => "{$prefix}_additional_information",
				'title'  => esc_html__( 'Additional Information', 'realty-portal' ),
				'fields' => $list_field,
			);

			$helper->add_meta_box( $meta_box );

			/**
			 * Create box: Property Features
			 */
			$property_features = rp_render_featured_amenities();
			$total_features    = count( $property_features );

			if ( $total_features > 1 ) {

				$list_features = array();

				foreach ( $property_features as $field ) {

					if ( empty( $field[ 'name' ] ) || ! empty( $field[ 'disable' ] ) ) {
						unset( $field );
						continue;
					}

					$value           = ! empty( $field[ 'value' ] ) ? esc_attr( $field[ 'value' ] ) : '';
					$field[ 'id' ]   = esc_attr( $prefix . $field[ 'name' ] );
					$field[ 'std' ]  = esc_html( $value );
					$field[ 'type' ] = 'checkbox';

					unset( $field[ 'name' ] );
					unset( $field[ 'value' ] );
					unset( $field[ 'hide' ] );

					$list_features[] = $field;
				}

				$meta_box = array(
					'id'     => "{$prefix}_property_features",
					'title'  => esc_html__( 'Property Features', 'realty-portal' ),
					'fields' => $list_features,
				);

				$helper->add_meta_box( $meta_box );
			}

			/**
			 * Create box: Place in Map
			 */
			$google_map = array();

			if ( RP_Property::get_setting( 'google_map', 'location_address', true ) ) {

				$google_map[] = array(
					'id'    => 'address',
					'label' => esc_html__( 'Address', 'realty-portal' ),
					'type'  => 'text',
				);
			}

			if ( RP_Property::get_setting( 'google_map', 'location_country', true ) ) {

				$google_map[] = array(
					'id'      => 'country',
					'label'   => esc_html__( 'Country', 'realty-portal' ),
					'type'    => 'select',
					'options' => rp_list_country(),
				);
			}

			if ( RP_Property::get_setting( 'google_map', 'location_city', true ) ) {

				$google_map[] = array(
					'id'    => 'city',
					'label' => esc_html__( 'City', 'realty-portal' ),
					'type'  => 'text',
				);
			}

			if ( RP_Property::get_setting( 'google_map', 'location_neighborhood', true ) ) {

				$google_map[] = array(
					'id'    => 'neighborhood',
					'label' => esc_html__( 'Neighborhood', 'realty-portal' ),
					'type'  => 'text',
				);
			}

			if ( RP_Property::get_setting( 'google_map', 'location_zip', true ) ) {

				$google_map[] = array(
					'id'    => 'zip',
					'label' => esc_html__( 'Zip', 'realty-portal' ),
					'type'  => 'text',
				);
			}

			if ( RP_Property::get_setting( 'google_map', 'location_state', true ) && in_array( RP_Property::get_setting( 'google_map', 'country_restriction', 'all' ), rp_get_country_support_state() ) ) {

				$google_map[] = array(
					'id'    => 'state',
					'label' => esc_html__( 'State', 'realty-portal' ),
					'type'  => 'text',
				);
			}

			if ( RP_Property::get_setting( 'google_map', 'location_latitude', true ) ) {

				$google_map[] = array(
					'id'    => 'latitude',
					'label' => esc_html__( 'Latitude', 'realty-portal' ),
					'type'  => 'text',
					'std'   => self::get_setting( 'google_map', 'latitude', '40.714398' ),
				);
			}

			if ( RP_Property::get_setting( 'google_map', 'location_longitude', true ) ) {

				$google_map[] = array(
					'id'    => 'longitude',
					'label' => esc_html__( 'Longitude', 'realty-portal' ),
					'type'  => 'text',
					'std'   => self::get_setting( 'google_map', 'longitude', '-74.005279' ),
				);
			}

			if ( RP_Property::get_setting( 'google_map', 'location_maps', true ) ) {

				$google_map[] = array(
					'id'    => 'gmap',
					'type'  => 'gmap',
					'label' => esc_html__( 'Maps', 'realty-portal' ),
				);
			}

			if ( count( $google_map ) > 0 ) {

				$meta_box = array(
					'id'     => "{$prefix}_google_map",
					'title'  => esc_html__( 'Listing Location', 'realty-portal' ),
					'fields' => $google_map,
				);

				$helper->add_meta_box( $meta_box );
			}

			/**
			 * Create box: Property Photo
			 */
			$meta_box = array(
				'id'     => "{$prefix}_property_photo",
				'title'  => esc_html__( 'Property Photo', 'realty-portal' ),
				'fields' => array(
					array(
						'label' => esc_html__( 'Property Photo', 'realty-portal' ),
						'id'    => 'property_photo',
						'type'  => 'gallery',
					),
				),
			);

			$helper->add_meta_box( $meta_box );

			/**
			 * Create box: Agent Responsible
			 */
			$meta_box = array(
				'id'       => 'agent_responsible',
				'title'    => esc_html__( 'Agent Responsible', 'realty-portal' ),
				'page'     => apply_filters( 'rp_property_post_type', 'rp_property' ),
				'context'  => 'side',
				'priority' => 'default',
				'fields'   => array(
					array(
						'label' => esc_html__( 'Agent Responsible', 'realty-portal' ),
						'id'    => 'agent_responsible',
						'type'  => 'agents',
					),
				),
			);

			$helper->add_meta_box( $meta_box );

			apply_filters( 'rp_metabox_property', $helper, $prefix );
		}

		/**
		 * Set Listings as expire for per listing
		 */
		public static function set_expired( $property_id = '' ) {

			if ( empty( $property_id ) ) {
				return;
			}

			$property_query = array(
				'ID'          => $property_id,
				'post_type'   => apply_filters( 'rp_property_post_type', 'rp_property' ),
				'post_status' => 'expired',
			);

			wp_update_post( $property_query );

			$user_id     = rp_get_author_by_post_id( $property_id );
			$agent_id    = get_user_meta( $user_id, '_associated_agent_id', true );
			$agent_email = get_post_meta( $agent_id, 'rp_agent_email', true );
			$blogname    = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
			rp_mail( $agent_email, esc_html__( 'Subject for Expired Property', 'realty-portal' ), sprintf( esc_html__( "Hi there,\nOne of your free listings on %s has \"expired\".\nThe property is %s.\nThank you!", 'realty-portal' ), $blogname, get_the_title( $property_id ) ) );
		}

		/**
		 * Check property expired
		 */
		public static function is_expired( $property_id = '' ) {

			if ( empty( $property_id ) ) {
				global $post;
				if ( ! empty( $post ) ) {
					$property_id = $post->ID;
				} else {
					return false;
				}
			}

			$property_status = get_post_status( $property_id );

			if ( ! empty( $property_status ) && $property_status !== 'expired' || current_user_can( 'publish_posts' ) ) {
				return true;
			}

			return false;
		}

	}

	RP_Property::get_instance();

endif;