<?php
if ( ! function_exists( 'rp_add_table_columns_property' ) ) :

	/**
	 * Custom column property
	 *
	 * @param $columns
	 *
	 * @return mixed|void
	 */
	function rp_add_table_columns_property( $columns ) {

		unset( $columns[ 'description' ] );
		unset( $columns[ 'comments' ] );
		unset( $columns[ 'date' ] );
		unset( $columns[ 'title' ] );
		unset( $columns[ 'author' ] );
		unset( $columns[ 'ID' ] );

		$columns[ 'rp_offer' ]    = esc_html__( 'Offer', 'realty-portal' );
		$columns[ 'rp_id' ]       = esc_html__( 'ID', 'realty-portal' );
		$columns[ 'rp_property' ] = esc_html__( 'Property', 'realty-portal' );
		$columns[ 'rp_price' ]    = esc_html__( 'Price', 'realty-portal' );
		$columns[ 'rp_posted' ]   = esc_html__( 'Posted', 'realty-portal' );
		$columns[ 'rp_status' ]   = esc_html__( 'Status', 'realty-portal' );
		$columns[ 'rp_actions' ]  = esc_html__( 'Actions', 'realty-portal' );

		return apply_filters( 'rp_admin_property_columns', $columns );
	}

	add_filter( 'manage_edit-rp_property_columns', 'rp_add_table_columns_property' );

endif;

if ( ! function_exists( 'rp_show_table_columns_property' ) ) :

	/**
	 * Show custom column to property
	 *
	 * @param $column_name
	 */
	function rp_show_table_columns_property( $column_name ) {

		global $post, $property;
		switch ( $column_name ) {

			case 'rp_offer':
				$terms = wp_get_object_terms( $property->ID, apply_filters( 'rp_property_listing_offers', 'listing_offers' ), array( 'orderby' => 'term_order' ) );

				if ( empty( $terms ) ) {
					echo '<span class="na">&ndash;</span>';
				} else {
					foreach ( $terms as $term ) {
						$color = get_term_meta( $term->term_id, 'color', true );
						if ( empty( $color ) ) {
							$color = '#27ae60';
						}
						$term_link = get_term_link( $term );
						echo '<a style="background: ' . $color . '" href="' . esc_url( $term_link ) . '">' . $term->name . '</a>';
					}
				}
				break;

			case 'rp_id':
				echo absint( $property->ID );
				break;

			case 'rp_property' :
				// Display listing thumbnail (with edit link if not in trash)

				if ( $post->post_status !== 'trash' && current_user_can( 'edit_listing', $post->ID ) ) {
					echo '<a class="rp-thumbnail" href="' . get_edit_post_link( $post->ID ) . '"><img src="' . rp_thumb_src( $post->ID ) . '" /></a>';
				} else {
					echo '<div class="rp-thumbnail"><img src="' . rp_thumb_src( $post->ID ) . '" /></div>';
				}

				echo '<div class="rp-info">';

				echo '<div class="rp-title">';

				// Display listing title (with edit link if not in trash)

				if ( $post->post_status !== 'trash' && current_user_can( 'edit_listing', $post->ID ) ) {
					echo '<a href="' . admin_url( 'post.php?post=' . $post->ID . '&action=edit' ) . '" class="tips" data-tip="' . __( 'Edit', 'realty-portal' ) . '">' . $post->post_title . '</a>';
				} else {
					echo $post->post_title;
				}

				if ( $post->post_status !== 'trash' ) {

					if ( $property->is_featured() ) {
						echo ' <span class="rp-featured">&dash; ' . __( 'Featured', 'realty-portal' ) . '</span>';
					}
				}

				echo '</div>';

				if ( $property->get_listing_type_html() ) {
					echo $property->get_listing_type_html();
				}

				// Display listing title actions (edit, view)

				echo '<div class="actions">';

				$admin_actions_listing_title = array();

				if ( current_user_can( 'edit_listing', $post->ID ) ) {

					if ( $post->post_status !== 'trash' ) {

						$admin_actions_listing_title[ 'edit' ] = array(
							'icon' => 'rp-icon-ion-edit',
							'name' => __( 'Edit', 'realty-portal' ),
							'url'  => get_edit_post_link( $post->ID ),
						);

						$admin_actions_listing_title[ 'view' ] = array(
							'icon'   => 'rp-icon-eye',
							'name'   => __( 'View', 'realty-portal' ),
							'url'    => get_permalink( $post->ID ),
							'target' => '_blank',
						);
					}
				}

				$admin_actions_listing_title = apply_filters( 'rp_admin_actions_listing_title', $admin_actions_listing_title, $post );

				foreach ( $admin_actions_listing_title as $action ) {
					printf( '<a class="button tips" data-balloon="%3$s" data-balloon-pos="up" href="%2$s" target="%5$s"><i class="%1$s"></i></a>', $action[ 'icon' ], esc_url( $action[ 'url' ] ), esc_attr( $action[ 'name' ] ), esc_html( $action[ 'name' ] ), isset( $action[ 'target' ] ) ? esc_html( $action[ 'target' ] ) : false );
				}

				echo '</div>';

				echo '</div>';
				break;

			case 'rp_price' :
				echo $property->get_price_html();
				break;

			case 'rp_posted' :
				// Display listing publish date
				echo '<span class="rp-posted">' . date_i18n( Realty_Portal::get_date_format(), strtotime( $post->post_date ) ) . '</span>';

				$agent = '<a href="' . $property->agent_info( 'url' ) . '" title="' . $property->agent_info( 'name' ) . '">' . $property->agent_info( 'name' ) . '</a>';
				echo '<span class="rp-agent">' . ( empty( $property->agent_info( 'name' ) ) ? __( 'by a guest', 'realty-portal' ) : sprintf( __( 'by %s', 'realty-portal' ), $agent ) ) . '</span>';
				break;

			case 'rp_status':

				echo $property->get_status_html();
				break;

			case 'rp_actions':
				echo '<div class="actions">';
				$admin_actions = array();
				if ( $post->post_status == 'pending' && current_user_can( 'publish_post', $post->ID ) ) {
					$url                        = wp_nonce_url( admin_url( 'admin-ajax.php?action=rp_property_approve&property_id=' . $post->ID ), 'rp-property-approve' );
					$admin_actions[ 'approve' ] = array(
						'icon' => 'rp-icon-ion-checkmark-round',
						'name' => esc_html__( 'Approve', 'realty-portal' ),
						'url'  => $url,
					);
				}
				if ( $post->post_status !== 'trash' ) {

					$featured = get_post_meta( $post->ID, '_featured', true );

					$url_featured  = wp_nonce_url( admin_url( 'admin-ajax.php?action=rp_property_feature&property_id=' . $post->ID ), 'rp-property-feature' );
					$icon_featured = 'dashicons-star-empty';
					if ( 'yes' === $featured ) {
						$icon_featured = 'dashicons-star-filled';
					}

					$admin_actions[ 'featured' ] = array(
						'name' => esc_html__( 'Featured', 'realty-portal' ),
						'url'  => $url_featured,
						'icon' => $icon_featured,
					);

					$admin_actions[ 'delete' ] = array(
						'icon'     => 'rp-icon-ion-trash-a',
						'name'     => __( 'Trash', 'realty-portal' ),
						'url'      => get_delete_post_link( $post->ID ),
						'cap'      => 'delete_listing',
						'priority' => 30,
					);
				} else {
					$admin_actions[ 'untrash' ] = array(
						'icon'     => 'untrash',
						'name'     => __( 'Restore', 'realty-portal' ),
						'url'      => wp_nonce_url( admin_url( 'post.php?post=' . $post->ID . '&action=untrash' ), 'untrash-post_' . $post->ID ),
						'cap'      => 'delete_listing',
						'priority' => 10,
					);
				}

				$admin_actions = apply_filters( 'property_manager_admin_actions', $admin_actions, $post );
				$i             = 0;

				foreach ( $admin_actions as $action ) {

					$action[ 'cap' ] = isset( $action[ 'cap' ] ) ? $action[ 'cap' ] : 'read_listing';

					if ( current_user_can( $action[ 'cap' ], $post->ID ) ) {

						printf( '<a class="button tips" data-balloon="%3$s" data-balloon-pos="up" href="%2$s" target="%5$s"><i class="dashicons %1$s"></i></a>', $action[ 'icon' ], esc_url( $action[ 'url' ] ), esc_attr( $action[ 'name' ] ), esc_html( $action[ 'name' ] ), isset( $action[ 'target' ] ) ? esc_html( $action[ 'target' ] ) : false );

						$i ++;
					}
				}

				if ( 0 == $i && $post->post_status == 'publish' ) {
					printf( '<a class="button tips" data-balloon="%3$s" data-balloon-pos="up" href="%2$s" target="%5$s"><i class="rp-icon-ion-android-open"></i></a>', 'view', esc_url( get_permalink( $post->ID ) ), esc_attr( __( 'View', 'realty-portal' ) ), esc_html( __( 'View', 'realty-portal' ) ), '_blank' );
				}

				echo '</div>';
				break;
		}
	}

	add_action( 'manage_rp_property_posts_custom_column', 'rp_show_table_columns_property' );

endif;

if ( ! function_exists( 'rp_set_featured_property' ) ) :

	/**
	 * Set featured property
	 *
	 */
	function rp_set_featured_property() {

		if ( isset( $_GET[ 'action' ] ) && $_GET[ 'action' ] == 'rp_property_feature' ) {
			if ( ! current_user_can( 'edit_posts' ) ) {
				wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'realty-portal' ), '', array( 'response' => 403 ) );
			}

			if ( ! check_admin_referer( 'rp-property-feature' ) ) {
				wp_die( esc_html__( 'You have taken too long. Please go back and retry.', 'realty-portal' ), '', array( 'response' => 403 ) );
			}

			$post_id = ! empty( $_GET[ 'property_id' ] ) ? (int) $_GET[ 'property_id' ] : '';

			if ( ! $post_id || get_post_type( $post_id ) !== apply_filters( 'rp_property_post_type', 'rp_property' ) ) {
				die;
			}

			$featured = get_post_meta( $post_id, '_featured', true );

			if ( 'yes' === $featured ) {
				update_post_meta( $post_id, '_featured', 'no' );
			} else {
				update_post_meta( $post_id, '_featured', 'yes' );
			}

			wp_safe_redirect( esc_url_raw( remove_query_arg( array(
				'trashed',
				'untrashed',
				'deleted',
				'ids',
			), wp_get_referer() ) ) );
			die();
		}
	}

	add_action( 'admin_init', 'rp_set_featured_property' );

endif;

if ( ! function_exists( 'rp_admin_property_approve_action' ) ) :

	/**
	 * Process event approve property
	 */
	function rp_admin_property_approve_action() {

		if ( isset( $_GET[ 'action' ] ) && 'rp_property_approve' == $_GET[ 'action' ] ) {
			if ( ! current_user_can( 'edit_posts' ) ) {
				wp_die( __( 'You do not have sufficient permissions to access this page.', 'realty-portal' ), '', array( 'response' => 403 ) );
			}

			if ( ! check_admin_referer( 'rp-property-approve' ) ) {
				wp_die( __( 'You have taken too long. Please go back and retry.', 'realty-portal' ), '', array( 'response' => 403 ) );
			}

			$post_id = ! empty( $_GET[ 'property_id' ] ) ? (int) $_GET[ 'property_id' ] : '';

			if ( ! $post_id || apply_filters( 'rp_property_post_type', 'rp_property' ) !== get_post_type( $post_id ) ) {
				die;
			}

			$property_data = array(
				'ID'          => $post_id,
				'post_status' => 'publish',
			);
			wp_update_post( $property_data );
			do_action( 'rp_property_after_approve', $post_id );
			wp_safe_redirect( esc_url_raw( remove_query_arg( array(
				'trashed',
				'untrashed',
				'deleted',
				'ids',
			), wp_get_referer() ) ) );
			die();
		}
	}

	add_action( 'admin_init', 'rp_admin_property_approve_action' );
endif;