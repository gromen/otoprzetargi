<?php
/**
 * RP_Agent Class
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'RP_Agent' ) ) :

	class RP_Agent {

		/**
		 *    Initialize class
		 */
		public static function init() {
			add_action( 'init', 'RP_Agent::definition' );

			add_action( 'init', 'RP_Agent::role_agent' );

			add_action( 'add_meta_boxes', 'RP_Agent::meta_box_agent' );

			// Columns
			add_filter( 'manage_edit-' . apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_columns', 'RP_Agent::edit_columns' );
			add_filter( 'manage_' . apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_posts_custom_column', 'RP_Agent::custom_column', 2 );
		}

		/**
		 *    definition()
		 *
		 *    Define the custom post type.
		 *
		 * @access    public
		 *
		 * @since     0.1
		 */
		public static function definition() {
			$labels = array(
				'name'               => __( 'Agents', 'realty-portal-agent' ),
				'singular_name'      => __( 'Agent', 'realty-portal-agent' ),
				'add_new'            => __( 'Add New', 'realty-portal-agent' ),
				'add_new_item'       => __( 'Add New Agent', 'realty-portal-agent' ),
				'edit_item'          => __( 'Edit Agent', 'realty-portal-agent' ),
				'new_item'           => __( 'New Agent', 'realty-portal-agent' ),
				'all_items'          => __( 'Agents', 'realty-portal-agent' ),
				'view_item'          => __( 'View Agent', 'realty-portal-agent' ),
				'search_items'       => __( 'Search Agent', 'realty-portal-agent' ),
				'not_found'          => __( 'No Agents found', 'realty-portal-agent' ),
				'not_found_in_trash' => __( 'No Items Found in Trash', 'realty-portal-agent' ),
				'parent_item_colon'  => '',
				'menu_name'          => __( 'Agents', 'realty-portal-agent' ),
			);

			register_post_type( apply_filters( 'rp_agent_post_type', 'rp_agent' ), array(
				'labels'        => $labels,
				'show_in_menu'  => true,
				'menu_position' => 51,
				'menu_icon'     => 'dashicons-businessman',
				'supports'      => array(
					'title',
					'thumbnail',
				),
				'public'        => true,
				'has_archive'   => true,
				'show_ui'       => true,
				'categories'    => array(),
				'rewrite'       => array(
					'slug'       => Realty_Portal::get_setting( 'agent_setting', 'archive_slug', 'agent' ),
					'with_front' => true,
				),
			) );

			/**
			 * Register taxonomy
			 */
			register_taxonomy( 'agent_category', apply_filters( 'rp_agent_post_type', 'rp_agent' ), array(
				'labels'       => array(
					'name'          => esc_html__( 'Agent Category', 'realty-portal-agent' ),
					'add_new_item'  => esc_html__( 'Add New Agent Category', 'realty-portal-agent' ),
					'new_item_name' => esc_html__( 'New Agent Category', 'realty-portal-agent' ),
				),
				'hierarchical' => true,
				'query_var'    => true,
				'rewrite'      => array(
					'slug'       => Realty_Portal::get_setting( 'agent_setting', 'agent_category_slug', 'agent_category' ),
					'with_front' => true,
				),
			) );
		}

		/**
		 *    role_agent()
		 *
		 *    Create role agent
		 *
		 * @access    public
		 */
		public static function role_agent() {
			$roles = array(

				'listing_admin' => array(
					'id'   => 'listing_admin',
					'name' => _x( 'Listing Admin', 'agent role', 'realty-portal-agent' ),
					'caps' => array(
						'read'                      => true,
						'upload_files'              => true,
						'unfiltered_html'           => true,
						'edit_listing'              => true,
						'edit_listing_id'           => true,
						'read_listing'              => true,
						'delete_listing'            => true,
						'edit_listings'             => true,
						'edit_others_listings'      => true,
						'publish_listings'          => true,
						'read_private_listings'     => true,
						'delete_listings'           => true,
						'delete_private_listings'   => true,
						'delete_published_listings' => true,
						'delete_others_listings'    => true,
						'edit_private_listings'     => true,
						'edit_published_listings'   => true,
						'manage_listing_terms'      => true,
						'edit_listing_terms'        => true,
						'delete_listing_terms'      => true,
						'assign_listing_terms'      => true,
					),
				),

				'listing_agent' => array(
					'id'   => 'listing_agent',
					'name' => _x( 'Listing Agent', 'agent role', 'realty-portal-agent' ),
					'caps' => array(
						'read'                 => true,
						'upload_files'         => true,
						'edit_listing'         => true,
						'read_listing'         => true,
						'delete_listing'       => true,
						'edit_listings'        => true,
						'delete_listings'      => true,
						'assign_listing_terms' => true,
					),
				),

				'listing_subscriber' => array(
					'id'   => 'listing_subscriber',
					'name' => _x( 'Listing Subscriber', 'agent role', 'realty-portal-agent' ),
					'caps' => array(
						'read'         => true,
						'read_listing' => true,
					),
				),

			);

			$agent_roles = apply_filters( 'rp_agent_roles', $roles );

			global $wp_roles;

			if ( class_exists( 'WP_Roles' ) && ! isset( $wp_roles ) ) {
				$wp_roles = new WP_Roles();
			}

			if ( is_object( $wp_roles ) ) {

				// Add listing_admin caps to administrator

				foreach ( $agent_roles[ 'listing_admin' ][ 'caps' ] as $cap => $v ) {
					$wp_roles->add_cap( 'administrator', $cap );
				}

				// Add agent roles with caps

				foreach ( $agent_roles as $role ) {

					add_role( $role[ 'id' ], $role[ 'name' ], $role[ 'caps' ] );

					/**
					 * Add level_1 to caps to show custom roles in author dropdown
					 *
					 * @see https://core.trac.wordpress.org/ticket/16841
					 */
					$user_role = get_role( $role[ 'id' ] );
					$user_role->add_cap( 'level_1' );
				}
			}
		}

		/**
		 *    edit_columns()
		 *
		 *    Add Thumbnail Column to the Agent Admin page
		 *
		 * @access    public
		 *
		 * @since     0.1
		 */
		public static function edit_columns( $columns ) {

			$new_columns                   = array();
			$new_columns[ 'cb' ]           = $columns[ 'cb' ];
			$new_columns[ 'rp_thumbnail' ] = esc_html__( 'Thumbnail', 'realty-portal-agent' );
			$new_columns[ 'title' ]        = $columns[ 'title' ];
			unset( $columns[ 'cb' ] );
			unset( $columns[ 'title' ] );

			return array_merge( $new_columns, $columns );
		}

		/**
		 *    custom_column()
		 *
		 *    Custom column agent
		 *
		 * @access    public
		 */
		public static function custom_column( $column ) {

			global $post;

			if ( 'rp_thumbnail' == $column ) {
				echo '<a href="' . get_edit_post_link() . '"><img width="80" height="80" src="' . self::get_avatar( $post->ID ) . '" /></a>';
			}
		}

		/**
		 * Query agent
		 *
		 * @param array $args
		 *
		 * @return mixed|void
		 */
		public static function query( $args = array() ) {
			global $wpdb;

			$defaults = array(
				'p'                   => '',
				'post__in'            => '',
				'offset'              => '',
				'post_status'         => '',
				'posts_per_page'      => get_option( 'posts_per_page' ),
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

			// Make sure offset is intval or empty
			$args[ 'offset' ] = ! empty( $args[ 'offset' ] ) ? absint( $args[ 'offset' ] ) : '';

			$query_args = array(
				'p'                   => absint( $args[ 'p' ] ),
				'post__in'            => $args[ 'post__in' ],
				'post_type'           => apply_filters( 'rp_agent_post_type', 'rp_agent' ),
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

				// When emtpy or unlogged user, set publish

				$query_args[ 'post_status' ] = 'publish';
			} else {

				$query_args[ 'post_status' ] = $args[ 'post_status' ];

				// When comma-separated, explode to array

				if ( ! is_array( $args[ 'post_status' ] ) && strpos( $args[ 'post_status' ], ',' ) ) {
					$query_args[ 'post_status' ] = explode( ',', $args[ 'post_status' ] );
				}
			}

//			$agent_must_has_property = (bool) Realty_Portal::get_setting( 'agent_setting', 'agent_must_has_property', false );
//			$agent_must_has_property = apply_filters( 'rp_agent_must_has_property', $agent_must_has_property );
//
//			if ( ! empty( $agent_must_has_property ) ) {
//
//				query_posts( 'post_type=' . apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '&posts_per_page=-1' );
//
//				$agent_ids = array();
//
//				while ( have_posts() ) : the_post();
//					$agent_id = get_the_ID();
//					$user_id  = self::get_id_user( $agent_id );
//					if ( $user_id < 1 ) {
//						continue;
//					}
//					$total_property = count_user_posts( $user_id, apply_filters( 'rp_property_post_type', 'rp_property' ) );
//					if ( $total_property > 0 ) {
//						$agent_ids[] = $agent_id;
//					}
//				endwhile;
//				wp_reset_query();
//			}

			// Set post__in with post IDs

			if ( ! empty( $agent_ids ) ) {
				$query_args[ 'post__in' ] = $agent_ids;
			}

			// Filter args
			$query_args = apply_filters( 'rp_get_agent_query_args', $query_args, $args );

			do_action( 'rp_get_agent_before', $query_args, $args );

			$result = new WP_Query( $query_args );

			do_action( 'rp_get_agent_after', $query_args, $args );

			// Reset query
			wp_reset_query();

			return apply_filters( 'rp_get_agent', $result, $query_args, $args );
		}

		/**
		 * Get query property
		 *
		 * @param array $args
		 *
		 * @return mixed|void
		 */
		public static function query_property( $args = array() ) {
			$args_default = array(
				'post_type'    => apply_filters( 'rp_property_post_type', 'rp_property' ),
				'post_status'  => 'publish',
				'meta_key'     => 'agent_responsible',
				'meta_value'   => self::is_agent(),
				'meta_compare' => '=',
				'order'        => 'DESC',
			);

			$args_property = wp_parse_args( $args, $args_default );

			return apply_filters( 'rp_agent_query_property', $args_property );
		}

		/**
		 *    add_meta_boxes()
		 *
		 *    Create agent meta box.
		 *
		 * @param    array $meta_boxes
		 *
		 */
		public static function meta_box_agent( $meta_boxes ) {
			/**
			 * VAR
			 */
			$prefix        = apply_filters( 'rp_agent_post_type', 'rp_agent' );
			$custom_fields = rp_agent_render_fields();

			$list_field = array();

			foreach ( $custom_fields as $field ) {

				if ( empty( $field[ 'name' ] ) ) {
					unset( $field );
					continue;
				}

				$field[ 'id' ]  = esc_attr( $prefix . $field[ 'name' ] );
				$field[ 'std' ] = esc_html( $field[ 'value' ] );

				unset( $field[ 'name' ] );
				unset( $field[ 'value' ] );
				unset( $field[ 'hide' ] );
				unset( $field[ 'disable' ] );
				unset( $field[ 'readonly' ] );
				unset( $field[ 'required' ] );

				$list_field[] = $field;
			}

			$helper = new RP_Meta_Boxes( $prefix, array(
				'page' => apply_filters( 'rp_agent_post_type', 'rp_agent' ),
			) );

			/**
			 * Create box: Detail Infomation
			 */
			$meta_box = array(
				'id'     => "{$prefix}_info_agent",
				'title'  => esc_html__( 'Detail Information', 'realty-portal-agent' ),
				'fields' => $list_field,
			);

			$helper->add_meta_box( $meta_box );

			/**
			 * Create box: Social Network
			 */
			$meta_box = array(
				'id'     => "{$prefix}_social_network",
				'title'  => esc_html__( 'Social Network', 'realty-portal-agent' ),
				'fields' => rp_get_list_social_agent(),
			);

			$helper->add_meta_box( $meta_box );

			/**
			 * Create box user login
			 */
			$meta_box = array(
				'id'          => "{$prefix}_meta_box_user",
				'title'       => esc_html__( 'Login Information', 'realty-portal-agent' ),
				'context'     => 'side',
				'priority'    => 'default',
				'description' => esc_html__( 'Manage Login Information of this agent', 'realty-portal-agent' ),
				'fields'      => array(
					array(
						'id'           => ( self::get_id_user( get_the_ID() ) ? 'edit_user' : 'create_user' ),
						'label'        => ( self::get_id_user( get_the_ID() ) ? esc_html__( 'Edit Login Info', 'realty-portal-agent' ) : esc_html__( 'Create a Login Account', 'realty-portal-agent' ) ),
						'type'         => 'checkbox',
						'std'          => 'off',
						'child-fields' => array(
							'on' => 'user_name,user_password',
						),
					),
					array(
						'id'    => 'user_name',
						'label' => esc_html__( 'User Name', 'realty-portal-agent' ),
						'type'  => 'text',
					),
					array(
						'id'    => 'user_password',
						'label' => esc_html__( 'Password', 'realty-portal-agent' ),
						'type'  => 'password',
					),
				),
			);

			$helper->add_meta_box( $meta_box );
		}

		/**
		 *    get_avatar()
		 *
		 *    Get avatar agent
		 *
		 * @access    public
		 */
		public static function get_avatar( $user_id = '', $args = array() ) {

			if ( empty( $user_id ) ) {
				$user_id = self::is_agent();
			}

			$width  = ! empty( $args[ 'width' ] ) ? esc_attr( $args[ 'width' ] ) : 80;
			$height = ! empty( $args[ 'height' ] ) ? esc_attr( $args[ 'height' ] ) : 80;

			$id_avatar = get_post_thumbnail_id( $user_id );

			$thumb = rp_thumb_src_id( $id_avatar, 'thumbnail', '150x150' );

			if ( empty( $thumb ) ) {
				if ( Realty_Portal::is_plugin_active( 'wordpress-social-login/wp-social-login.php' ) ) {
					$wsl_avatar = wsl_get_user_custom_avatar( $user_id );
					if ( ! empty( $wsl_avatar ) ) {
						$thumb = '<img src="' . $wsl_avatar . '" width="' . $width . '" height="' . $height . '" alt="' . get_the_title( $user_id ) . '" />';
					}
				}

				if ( empty( $thumb ) ) {
					$thumb = get_avatar( $user_id, $width );
				}
			}

			return $thumb;
		}

		/**
		 * Get name agent
		 *
		 * @return mixed|void
		 */
		public static function get_name() {
			return apply_filters( 'nr_agent_name', get_the_title( self::is_agent() ) );
		}

		/**
		 * Check archive agent
		 *
		 * @return string
		 */
		public static function is_archive_agent() {

			if ( ! RP_Template::locate_template( 'agents/archive-agent.php' ) && ( is_post_type_archive( apply_filters( 'rp_agent_post_type', 'rp_agent' ) ) || is_tax( 'agent_category' ) ) ) {
				return true;
			}

			return false;
		}

		/**
		 * Check single agent
		 *
		 * @return string
		 */
		public static function is_single_agent() {

			if ( is_singular( apply_filters( 'rp_agent_post_type', 'rp_agent' ) ) && ! RP_Template::locate_template( 'agents/single-agent.php' ) ) {
				return true;
			}

			return false;
		}

		/**
		 * Check user is agent
		 */
		public static function is_agent( $user_id = '' ) {

			if ( empty( $user_id ) ) {
				$user_id = rp_get_current_user( true );
			}
			$agent_id = intval( get_user_meta( $user_id, '_associated_agent_id', true ) );
			apply_filters( 'wpml_object_id', $agent_id, apply_filters( 'rp_agent_post_type', 'rp_agent' ) );

			if ( empty( $user_id ) ) {

				if ( $agent_id > 0 ) {
					return true;
				}

				return false;
			} else {

				return absint( $agent_id );
			}
		}

		/**
		 * Get id user
		 */
		public static function id_user( $agent_id = '' ) {

			if ( empty( $agent_id ) ) {
				return false;
			}

			$info_user = get_users( array(
				'meta_key'   => '_associated_agent_id',
				'meta_value' => $agent_id,
			) );

			return ! empty( $info_user[ 0 ]->ID ) ? absint( $info_user[ 0 ]->ID ) : 0;
		}

		/**
		 * Get id user
		 */
		public static function get_id_user( $agent_id = '' ) {

			if ( empty( $agent_id ) ) {
				return false;
			}

			$id_user = get_post_meta( $agent_id, '_associated_user_id', true );

			return is_numeric( $id_user ) ? absint( $id_user ) : 1;
		}

		/**
		 * Get id agent by id user
		 */
		public static function get_id_agent( $user_id = '' ) {

			if ( empty( $user_id ) ) {
				$user_id = rp_get_current_user( true );
			}

			return get_user_meta( $user_id, '_associated_agent_id', true );
		}

		/**
		 * Check is user admin
		 */
		public static function is_admin() {

			if ( current_user_can( 'administrator' ) ) {
				return true;
			}

			return false;
		}

		/**
		 * Check is user
		 */
		public static function is_user() {

			return rp_get_current_user( true );
		}

		/**
		 * Check agent can view
		 */
		public static function can_view() {

			if ( empty( self::is_agent() ) && empty( self::is_user() ) ) {
				return new WP_Error( 'error', __( 'You don\'t have access to this entry.', 'realty-portal-agent' ) );
			}

			return apply_filters( 'rp_agent_can_view', true );
		}

		/**
		 * Check agent can submit property
		 */
		public static function can_add() {

			if ( empty( self::is_agent() ) && empty( self::is_user() ) ) {
				return new WP_Error( 'error', __( 'You don\'t have access to this entry.', 'realty-portal-agent' ) );
			}

			if ( empty( self::is_agent() ) && ! empty( self::is_user() ) ) {
				return true;
			}

			return apply_filters( 'rp_agent_can_add', true );
		}

		/**
		 * Check agent can edit property
		 */
		public static function can_edit() {
			return apply_filters( 'rp_agent_can_edit', true );
		}

		/**
		 * Get value submit
		 */
		public static function get_value_submit( $agent_id, $name_meta, $value_meta = '' ) {
			if ( ! empty( $agent_id ) ) {
				if ( 'title' == $name_meta || 'name' == $name_meta ) {
					return get_the_title( $agent_id );
				} elseif ( 'description' == $name_meta ) {
					return get_post_field( 'post_content', $agent_id );
				} elseif ( 'country' == $name_meta ) {
					$country      = Realty_Portal::get_post_meta( $agent_id, $name_meta, $value_meta );
					$list_country = rp_list_country();
					if ( ! empty( $country ) ) {
						$key_country = array_search( $country, array_column( $list_country, 'value' ) );
						$country     = '';
						if ( ! empty( $list_country[ $key_country ][ 'value' ] ) ) {
							$country = $list_country[ $key_country ][ 'value' ];
						}
					} else {
						$agent_id = self::get_id_agent();
						$country  = Realty_Portal::get_post_meta( $agent_id, 'latest_country', '' );
					}

					return $country;
				}

				return Realty_Portal::get_post_meta( $agent_id, $name_meta, $value_meta );
			}

			return $value_meta;
		}

		/**
		 * Check can user register
		 */
		public static function can_register() {

			$can_register = get_option( 'users_can_register' );

			return apply_filters( 'rp_can_register', $can_register );
		}

	}

	RP_Agent::init();

endif;