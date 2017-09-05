<?php
if ( !class_exists( 'Noo_Importer_Menu' ) ) :

class Noo_Importer_Menu {
	
	static $missing_menu_items = array();
	static $processed_menu_items = array();
	static $menu_item_orphans = array();

	/**
	 * Attempt to create a new menu item from import data
	 *
	 * Fails for draft, orphaned menu items and those without an associated nav_menu
	 * or an invalid nav_menu term. If the post type or term object which the menu item
	 * represents doesn't exist then the menu item will not be imported (waits until the
	 * end of the import to retry again before discarding).
	 *
	 * @param array $item Menu item details from WXR file
	 */
	public static function menu_item( $item ) {

		if ( 'nav_menu_item' !== $item['post_type'] || 'draft' === $item['status'] ) return false;

		$menu_slug = false;
		if ( isset( $item['terms'] ) ) {
			// loop through terms, assume first nav_menu term is correct menu
			foreach ( $item['terms'] as $term ) {
				if ( 'nav_menu' == $term['domain'] ) {
					$menu_slug = $term['slug'];
					break;
				}
			}
		}

		// no nav_menu term associated with this menu item
		if ( ! $menu_slug ) {
			/**
			 * Add this message to log file.
			 */
			Noo_Importer_Helpers::update_log(
				sprintf(
					esc_html__( 
						'Menu item skipped due to missing menu slug %s', 
						'noo-landmark-core'
					), 
					esc_html(
						$menu_slug
					)
				), 
				sprintf(
					esc_html__( 'Menu item skipped: %s.', 'noo-landmark-core' ),
					esc_html(
						$menu_slug
					)
				)
			);
			return;
		}

		$menu_id = term_exists( $menu_slug, 'nav_menu' );
		if ( ! $menu_id ) {
			/**
			 * Add this message to log file.
			 */
			Noo_Importer_Helpers::update_log(
				sprintf(
					esc_html__( 
						'Menu item skipped due to invalid menu slug %s', 
						'noo-landmark-core'
					), 
					esc_html(
						$menu_slug
					)
				), 
				sprintf(
					esc_html__( 'Menu item skipped: %s.', 'noo-landmark-core' ),
					esc_html(
						$menu_slug
					)
				)
			);
			return;
		} else {
			$menu_id = is_array( $menu_id ) ? $menu_id['term_id'] : $menu_id;
		}

		$_menu_item_type                    = '';
		$_menu_item_object_id               = '';
		$_menu_item_menu_item_parent        = '';
		$_menu_item_object                  = '';
		$_menu_item_target                  = '';
		$_menu_item_classes                 = '';
		$_menu_item_xfn                     = '';
		$_menu_item_url                     = '';
		$_menu_item_megamenu                = '';
		$_menu_item_megamenu_col            = '';
		$_menu_item_fly_menu                = '';
		$_menu_item_megamenu_icon           = '';
		$_menu_item_megamenu_icon_color     = '';
		$_menu_item_megamenu_icon_size      = '';
		$_menu_item_megamenu_icon_alignment = '';
		$_menu_item_megamenu_widgetarea     = '';
		$_menu_item_megamenu_heading        = '';

		foreach ( $item['postmeta'] as $meta ) {

			if ( empty( $meta['key'] ) ) continue;
			/**
			 * Process meta key
			 */
			switch ( $meta['key'] ) {
				case '_menu_item_type':
					$_menu_item_type = $meta['value'];
					break;

				case '_menu_item_object_id':
					$_menu_item_object_id = $meta['value'];
					break;
					
				case '_menu_item_menu_item_parent':
					$_menu_item_menu_item_parent = $meta['value'];
					break;
					
				case '_menu_item_object':
					$_menu_item_object = $meta['value'];
					break;
					
				case '_menu_item_target':
					$_menu_item_target = $meta['value'];
					break;
					
				case '_menu_item_classes':
					$_menu_item_classes = $meta['value'];
					break;
					
				case '_menu_item_xfn':
					$_menu_item_xfn = $meta['value'];
					break;
					
				case '_menu_item_url':
					$_menu_item_url = $meta['value'];
					break;
					
				case '_menu_item_megamenu':
					$_menu_item_megamenu = $meta['value'];
					break;
					
				case '_menu_item_megamenu_col':
					$_menu_item_megamenu_col = $meta['value'];
					break;
					
				case '_menu_item_fly_menu':
					$_menu_item_fly_menu = $meta['value'];
					break;
					
				case '_menu_item_megamenu_icon':
					$_menu_item_megamenu_icon = $meta['value'];
					break;
					
				case '_menu_item_megamenu_icon_color':
					$_menu_item_megamenu_icon_color = $meta['value'];
					break;
					
				case '_menu_item_megamenu_icon_size':
					$_menu_item_megamenu_icon_size = $meta['value'];
					break;
					
				case '_menu_item_megamenu_icon_alignment':
					$_menu_item_megamenu_icon_alignment = $meta['value'];
					break;

				case '_menu_item_megamenu_widgetarea':
					$_menu_item_megamenu_widgetarea = $meta['value'];
					break;

				case '_menu_item_megamenu_heading':
					$_menu_item_megamenu_heading = $meta['value'];
					break;
			}

		}

		/**
		 * Get data posts
		 * @var json
		 */
		$data_processed_posts = Noo_Importer_Helpers::get_contents( Noo_Importer_Helpers::get_path( 'processed_posts_', '.json' ) );
		$data_processed_posts = !empty( $data_processed_posts ) ? json_decode( $data_processed_posts, true ) : array();
		
		$data_processed_terms = Noo_Importer_Helpers::get_contents( Noo_Importer_Helpers::get_path( 'processed_terms_', '.json' ) );
		$data_processed_terms = !empty( $data_processed_terms ) ? json_decode( $data_processed_terms, true ) : array();

		/**
		 * Process menu item type
		 */
		switch ( $_menu_item_type ) {
			case 'taxonomy':
				if ( isset( $data_processed_terms[intval($_menu_item_object_id)] ) ) {
					$_menu_item_object_id = $data_processed_terms[intval($_menu_item_object_id)];
				}
				break;
			case 'post_type':
				if ( isset( $data_processed_posts[intval($_menu_item_object_id)] ) ) {
					$_menu_item_object_id = $data_processed_posts[intval($_menu_item_object_id)];
				}
				break;
		}

		if ( $_menu_item_type !== 'custom' && $_menu_item_type !== 'taxonomy' && $_menu_item_type !== 'post_type' ) {

			/**
			 * Update process lists menu items
			 */
				$data_missing_menu_items = Noo_Importer_Helpers::get_contents( Noo_Importer_Helpers::get_path( 'missing_menu_items_', '.json' ) );
				$data_missing_menu_items = !empty( $data_missing_menu_items ) ? json_decode( $data_missing_menu_items, true ) : array();

				if ( !is_array( $data_missing_menu_items ) ) {
					$data_missing_menu_items = array();
				}

				/**
				 * associated object is missing or not imported yet, we'll retry later
				 */
				$data_missing_menu_items[] = $item;

				Noo_Importer_Helpers::update_json(
					$data_missing_menu_items,
					Noo_Importer_Helpers::get_name_file( 'missing_menu_items_' )
				);

			return;
		}

		/**
		 * Get menu items
		 */
			$data_processed_menu_items = Noo_Importer_Helpers::get_contents( Noo_Importer_Helpers::get_path( 'processed_menu_items_', '.json' ) );
			$data_processed_menu_items = !empty( $data_processed_menu_items ) ? json_decode( $data_processed_menu_items, true ) : array();

			if ( !is_array( $data_processed_menu_items ) ) {
				$data_processed_menu_items = array();
			}

		/**
		 * Get menu item orphans
		 */
			$data_menu_item_orphans = Noo_Importer_Helpers::get_contents( Noo_Importer_Helpers::get_path( 'menu_item_orphans_', '.json' ) );
			$data_menu_item_orphans = !empty( $data_menu_item_orphans ) ? json_decode( $data_menu_item_orphans, true ) : array();

			if ( !is_array( $data_menu_item_orphans ) ) {
				$data_menu_item_orphans = array();
			}

		if ( isset( $data_processed_menu_items[intval($_menu_item_menu_item_parent)] ) ) {
			$_menu_item_menu_item_parent = $data_processed_menu_items[intval($_menu_item_menu_item_parent)];
		} else if ( $_menu_item_menu_item_parent ) {
			$data_menu_item_orphans[intval( $item['post_id'] )] = (int) $_menu_item_menu_item_parent;
			$_menu_item_menu_item_parent = 0;
		}

		// wp_update_nav_menu_item expects CSS classes as a space separated string
		$_menu_item_classes = maybe_unserialize( $_menu_item_classes );
		if ( is_array( $_menu_item_classes ) ) {
			$_menu_item_classes = implode( ' ', $_menu_item_classes );
		}

		$args = array(
			'menu-item-object-id'               => $_menu_item_object_id,
			'menu-item-object'                  => $_menu_item_object,
			'menu-item-parent-id'               => $_menu_item_menu_item_parent,
			'menu-item-position'                => intval( $item['menu_order'] ),
			'menu-item-type'                    => $_menu_item_type,
			'menu-item-title'                   => $item['post_title'],
			'menu-item-url'                     => $_menu_item_url,
			'menu-item-description'             => $item['post_content'],
			'menu-item-attr-title'              => $item['post_excerpt'],
			'menu-item-target'                  => $_menu_item_target,
			'menu-item-classes'                 => $_menu_item_classes,
			'menu-item-xfn'                     => $_menu_item_xfn,
			'menu-item-status'                  => $item['status']
		);
		if ( isset( $_menu_item_megamenu ) ) {
			$args['menu-item-megamenu'] = $_menu_item_megamenu;
		}
		if ( isset( $_menu_item_megamenu_col ) ) {
			$args['menu-item-megamenu_columns'] = $_menu_item_megamenu_col;
		}
		if ( isset( $_menu_item_megamenu_heading ) ) {
			$args['menu-item-megamenu_heading'] = $_menu_item_megamenu_heading;
		}
		if ( isset( $_menu_item_megamenu_widgetarea ) ) {
			$args['menu-item-megamenu_widgetarea'] = $_menu_item_megamenu_widgetarea;
		}
		if ( isset( $_menu_item_megamenu_icon ) ) {
			$args['menu-item-megamenu_icon'] = $_menu_item_megamenu_icon;
		}
		if ( isset( $_menu_item_megamenu_icon_color ) ) {
			$args['menu-item-megamenu_icon_color'] = $_menu_item_megamenu_icon_color;
		}
		if ( isset( $_menu_item_megamenu_icon_size ) ) {
			$args['menu-item-megamenu_icon_size'] = $_menu_item_megamenu_icon_size;
		}
		if ( isset( $_menu_item_megamenu_icon_alignment ) ) {
			$args['menu-item-megamenu_icon_alignment'] = $_menu_item_megamenu_icon_alignment;
		}

		$id = self::nav_menu_item( $menu_id, 0, $args );
		if ( $id && ! is_wp_error( $id ) ) {
			$data_processed_menu_items[intval($item['post_id'])] = (int) $id;
		}

		/**
		 * Update process lists menu item
		 */
			Noo_Importer_Helpers::update_json(
				$data_processed_menu_items,
				Noo_Importer_Helpers::get_name_file( 'processed_menu_items_' )
			);

		/**
		 * Update process lists menu item orphans
		 */
			Noo_Importer_Helpers::update_json(
				$data_menu_item_orphans,
				Noo_Importer_Helpers::get_name_file( 'menu_item_orphans_' )
			);

	}

	public static function nav_menu_item( $menu_id = 0, $menu_item_db_id = 0, $menu_item_data = array() ) {
	
		$menu_id         = (int) $menu_id;
		$menu_item_db_id = (int) $menu_item_db_id;
	 
	    // make sure that we don't convert non-nav_menu_item objects into nav_menu_item objects
	    if ( ! empty( $menu_item_db_id ) && ! is_nav_menu_item( $menu_item_db_id ) ) {
	    	/**
			 * Add this message to log file.
			 */
			Noo_Importer_Helpers::update_log(
				sprintf(
					esc_html__( 
						'The given object ID is not that of a menu item: %s', 
						'noo-landmark-core'
					), 
					esc_html(
						$menu_id
					)
				), 
				sprintf(
					esc_html__( 'Update Nav Menu Item Failed: %s.', 'noo-landmark-core' ),
					esc_html(
						$menu_id
					)
				)
			);
			return false;
	    }
	 
	    $menu = wp_get_nav_menu_object( $menu_id );
	 
	    if ( ! $menu && 0 !== $menu_id ) {
	    	/**
			 * Add this message to log file.
			 */
			Noo_Importer_Helpers::update_log(
				sprintf(
					esc_html__( 
						'Invalid menu ID: %s', 
						'noo-landmark-core'
					), 
					esc_html(
						$menu_id
					)
				), 
				sprintf(
					esc_html__( 'Invalid Menu ID: %s.', 'noo-landmark-core' ),
					esc_html(
						$menu_id
					)
				)
			);

			return false;

	    }
	 
	    if ( is_wp_error( $menu ) ) {
	    	/**
			 * Add this message to log file.
			 */
			Noo_Importer_Helpers::update_log(
				'', 
				sprintf(
					esc_html__( 'ERROR Create Menu: %s.', 'noo-landmark-core' ),
					esc_html(
						$menu
					)
				)
			);

			return false;

	    }
	 
	    $defaults = array(
			'menu-item-db-id'                   => $menu_item_db_id,
			'menu-item-object-id'               => 0,
			'menu-item-object'                  => '',
			'menu-item-parent-id'               => 0,
			'menu-item-position'                => 0,
			'menu-item-type'                    => 'custom',
			'menu-item-title'                   => '',
			'menu-item-url'                     => '',
			'menu-item-description'             => '',
			'menu-item-attr-title'              => '',
			'menu-item-target'                  => '',
			'menu-item-classes'                 => '',
			'menu-item-xfn'                     => '',
			'menu-item-status'                  => '',
			'menu-item-megamenu'                => '',
			'menu-item-megamenu_columns'        => '',
			'menu-item-megamenu_heading'        => '',
			'menu-item-megamenu_widgetarea'     => '',
			'menu-item-megamenu_icon'           => '',
			'menu-item-megamenu_icon_color'     => '',
			'menu-item-megamenu_icon_size'      => '',
			'menu-item-megamenu_icon_alignment' => '',
	    );
	 
	    $args = wp_parse_args( $menu_item_data, $defaults );
	 
	    if ( 0 == $menu_id ) {
	        $args['menu-item-position'] = 1;
	    } elseif ( 0 == (int) $args['menu-item-position'] ) {
			$menu_items                 = 0 == $menu_id ? array() : (array) wp_get_nav_menu_items( $menu_id, array( 'post_status' => 'publish,draft' ) );
			$last_item                  = array_pop( $menu_items );
			$args['menu-item-position'] = ( $last_item && isset( $last_item->menu_order ) ) ? 1 + $last_item->menu_order : count( $menu_items );
	    }
	 
	    $original_parent = 0 < $menu_item_db_id ? get_post_field( 'post_parent', $menu_item_db_id ) : 0;
	 
	    if ( 'custom' != $args['menu-item-type'] ) {
	        /* if non-custom menu item, then:
	            * use original object's URL
	            * blank default title to sync with original object's
	        */
	 
	        $args['menu-item-url'] = '';
	 
	        $original_title = '';
	        if ( 'taxonomy' == $args['menu-item-type'] ) {
				$original_parent = get_term_field( 'parent', $args['menu-item-object-id'], $args['menu-item-object'], 'raw' );
				$original_title  = get_term_field( 'name', $args['menu-item-object-id'], $args['menu-item-object'], 'raw' );
	        } elseif ( 'post_type' == $args['menu-item-type'] ) {
	 
	            $original_object = get_post( $args['menu-item-object-id'] );
	            $original_parent = (int) $original_object->post_parent;
	            $original_title = $original_object->post_title;
	        }
	 
	        if ( $args['menu-item-title'] == $original_title ) {
	            $args['menu-item-title'] = '';
	        }
	 
	        // hack to get wp to create a post object when too many properties are empty
	        if ( '' ==  $args['menu-item-title'] && '' == $args['menu-item-description'] ) {
	            $args['menu-item-description'] = ' ';
	        }
	    }
	 
	    // Populate the menu item object
	    $post = array(
			'menu_order'   => $args['menu-item-position'],
			'ping_status'  => 0,
			'post_content' => $args['menu-item-description'],
			'post_excerpt' => $args['menu-item-attr-title'],
			'post_parent'  => $original_parent,
			'post_title'   => $args['menu-item-title'],
			'post_type'    => 'nav_menu_item',
	    );
	 
	    $update = 0 != $menu_item_db_id;
	 
	    // New menu item. Default is draft status
	    if ( ! $update ) {
	        $post['ID'] = 0;
	        $post['post_status'] = 'publish' == $args['menu-item-status'] ? 'publish' : 'draft';
	        $menu_item_db_id = wp_insert_post( $post );
	        if ( ! $menu_item_db_id || is_wp_error( $menu_item_db_id ) ) {
	        	/**
				 * Add this message to log file.
				 */
				Noo_Importer_Helpers::update_log(
					'', 
					sprintf(
						esc_html__( 'ERROR Update Menu: %s.', 'noo-landmark-core' ),
						esc_html(
							$menu_item_db_id
						)
					)
				);
	            return $menu_item_db_id;
	        }
	    }
	 
	    // Associate the menu item with the menu term
	    // Only set the menu term if it isn't set to avoid unnecessary wp_get_object_terms()
	     if ( $menu_id && ( ! $update || ! is_object_in_term( $menu_item_db_id, 'nav_menu', (int) $menu->term_id ) ) ) {
	        wp_set_object_terms( $menu_item_db_id, array( $menu->term_id ), 'nav_menu' );
	    }
	 
	    if ( 'custom' == $args['menu-item-type'] ) {
	        $args['menu-item-object-id'] = $menu_item_db_id;
	        $args['menu-item-object'] = 'custom';
	    }
	 
	    $menu_item_db_id = (int) $menu_item_db_id;
	 	
	    update_post_meta( $menu_item_db_id, '_menu_item_type', sanitize_key($args['menu-item-type']) );
	    update_post_meta( $menu_item_db_id, '_menu_item_menu_item_parent', strval( (int) $args['menu-item-parent-id'] ) );
	    update_post_meta( $menu_item_db_id, '_menu_item_object_id', strval( (int) $args['menu-item-object-id'] ) );
	    update_post_meta( $menu_item_db_id, '_menu_item_object', sanitize_key($args['menu-item-object']) );
	    update_post_meta( $menu_item_db_id, '_menu_item_target', sanitize_key($args['menu-item-target']) );
	 
	    $args['menu-item-classes'] = array_map( 'sanitize_html_class', explode( ' ', $args['menu-item-classes'] ) );
	    $args['menu-item-xfn'] = implode( ' ', array_map( 'sanitize_html_class', explode( ' ', $args['menu-item-xfn'] ) ) );
	    update_post_meta( $menu_item_db_id, '_menu_item_classes', $args['menu-item-classes'] );
	    update_post_meta( $menu_item_db_id, '_menu_item_xfn', $args['menu-item-xfn'] );
	    update_post_meta( $menu_item_db_id, '_menu_item_url', esc_url_raw($args['menu-item-url']) );
		
		update_post_meta( $menu_item_db_id, '_menu_item_megamenu', sanitize_key($args['menu-item-megamenu']) );
	    update_post_meta( $menu_item_db_id, '_menu_item_megamenu_col', sanitize_key($args['menu-item-megamenu_columns']) );
	    update_post_meta( $menu_item_db_id, '_menu_item_megamenu_heading', sanitize_key($args['menu-item-megamenu_heading']) );
	    update_post_meta( $menu_item_db_id, '_menu_item_megamenu_widgetarea', sanitize_key($args['menu-item-megamenu_widgetarea']) );
	    update_post_meta( $menu_item_db_id, '_menu_item_megamenu_icon', sanitize_key($args['menu-item-megamenu_icon']) );
	    update_post_meta( $menu_item_db_id, '_menu_item_megamenu_icon_color', sanitize_key($args['menu-item-megamenu_icon_color']) );
	    update_post_meta( $menu_item_db_id, '_menu_item_megamenu_icon_size', sanitize_key($args['menu-item-megamenu_icon_size']) );
	    update_post_meta( $menu_item_db_id, '_menu_item_megamenu_icon_alignment', sanitize_key($args['menu-item-megamenu_icon_alignment']) );

	    if ( 0 == $menu_id ) {
	        update_post_meta( $menu_item_db_id, '_menu_item_orphaned', (string) time() );
	    } elseif ( get_post_meta( $menu_item_db_id, '_menu_item_orphaned' ) ) {
	        delete_post_meta( $menu_item_db_id, '_menu_item_orphaned' );
	    }
	 
	    // Update existing menu item. Default is publish status
	    if ( $update ) {
	        $post['ID'] = $menu_item_db_id;
	        $post['post_status'] = 'draft' == $args['menu-item-status'] ? 'draft' : 'publish';
	        wp_update_post( $post );
	    }
	 	
	 	/**
		 * Add this message to log file.
		 */
		Noo_Importer_Helpers::update_log(
			'', 
			sprintf(
				esc_html__( 'Success Update Menu: %s+Menu name: %s%s+Menu ID: %s.', 'noo-landmark-core' ),
				"\n",
				esc_html( $args['menu-item-title'] ),
				"\n",
				esc_html( $menu_item_db_id )
			)
		);

	    return $menu_item_db_id;
	}

}

endif;