<?php
/**
 * This function get layout template
 *
 * @package     LandMark
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */

if ( ! function_exists( 'noo_get_template' ) ) :
    
    function noo_get_template( $template_name = '', $dir = 'templates' ) {

        if ( empty( $template_name ) ) return;
		$plugin_path   = untrailingslashit(plugin_dir_path( dirname( dirname( __FILE__ ) ) ));
		$template_path = $plugin_path . '/' . esc_attr( $dir ) . '/' . esc_attr( $template_name ) . '.php';
		$located       = '';

        if ( file_exists( $template_path ) ) {
        	$located = $template_path;
        }

        $located = apply_filters( 'noo_locate_template', $located, $template_name );

        return $located;

    }

endif;

/**
 * Find id page by template
 *
 * @package     LandMark
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */

if ( ! function_exists( 'noo_get_page_by_template' ) ) :
    
    function noo_get_page_by_template( $page_template = '' ) {

        $pages = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => $page_template
        ));

        if( $pages ){
            return $pages[0]->ID;
        }
        return false;

    }

endif;

/**
 * Get image customizer
 *
 * @package 	LandMark
 * @author 		KENT <tuanlv@vietbrain.com>
 * @version 	1.0
 */

if ( ! function_exists( 'noo_get_image_option' ) ) :
	
	function noo_get_image_option( $option, $default ) {

		$image = get_theme_mod( $option );

		$image = ( $image === null || $image === '' ) ? $default : $image;
		$image = ( !empty( $image ) && is_int($image) ) ? wp_get_attachment_url( $image ) : $image;

		return $image;

	}

endif;
/**
 * Custom heading
 *
 * @package 	LandMark
 * @author 		KENT <tuanlv@vietbrain.com>
 * @version 	1.0
 */

if ( ! function_exists( 'noo_get_heading' ) ) :
	
	function noo_get_heading( $heading ) {

		if ( is_home() || is_page() || is_front_page() || is_singular( 'post' ) ) {
			
			return $heading;
		
		} elseif ( ( is_single() && get_post_type() == 'noo_agent' ) || is_post_type_archive( 'noo_agent' ) || is_tax( 'agent_category' ) ) {
			
			return get_theme_mod( 'noo_agent_heading_title', esc_html__( 'Our Agent', 'noo-landmark-core' ) );
		
		} elseif ( is_tax( 'property_status' ) ) {
			
			return get_theme_mod( 'noo_property_status_heading_title', esc_html__( 'Property Status', 'noo-landmark-core' ) );
		
		} elseif ( is_tax( 'property_type' ) ) {
			
			return get_theme_mod( 'noo_property_type_heading_title', esc_html__( 'Property Type', 'noo-landmark-core' ) );
		
		} elseif ( ( is_single() && get_post_type() == 'noo_property' ) ) {
			
			return get_theme_mod( 'noo_property_heading_title', get_the_title() );

		} elseif ( is_post_type_archive( 'noo_property' ) ) {
			
			return get_theme_mod( 'noo_property_heading_title', esc_html__( 'Properties', 'noo-landmark-core' ) );

		}

		return $heading;

	}

	add_filter( 'noo_get_page_heading', 'noo_get_heading' );

endif;

/**
 * Custom heading image
 *
 * @package 	LandMark
 * @author 		KENT <tuanlv@vietbrain.com>
 * @version 	1.0
 */

if ( ! function_exists( 'noo_get_heading_image' ) ) :
	
	function noo_get_heading_image( $image ) {

		if ( is_home() || is_page() || is_front_page() || is_singular( 'post' ) ) {
			
			return $image;
		
		} elseif ( ( is_single() && get_post_type() == 'noo_agent' ) || is_post_type_archive( 'noo_agent' ) || is_tax( 'agent_category' ) ) {

			$image = noo_get_image_option( 'noo_agent_heading_image', '' );

		} elseif ( is_tax( 'property_status' ) ) {

			$image = noo_get_image_option( 'noo_property_status_heading_image', '' );
			
		} elseif ( is_tax( 'property_type' ) ) {

			$image = noo_get_image_option( 'noo_property_type_heading_image', '' );

		} elseif ( ( is_single() && get_post_type() == 'noo_property' ) ) {

			$image = noo_get_image_option( 'noo_property_post_heading_image', '' );
			$thumb  = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full');
			if ( isset($thumb) && !empty($thumb) ) {
                $image = $thumb[0];
            }
				
		} elseif( is_post_type_archive( 'noo_property' ) ) {

			$image = noo_get_image_option( 'noo_property_heading_image', '' );

		}

		return $image;

	}

	add_filter( 'noo_get_page_heading_image', 'noo_get_heading_image' );

endif;

/**
 * Custom layout
 *
 * @package 	LandMark
 * @author 		KENT <tuanlv@vietbrain.com>
 * @version 	1.0
 */

if ( ! function_exists( 'noo_get_page_layout' ) ) :
	
	function noo_get_page_layout( $layout ) {
		
		if ( is_home() || is_front_page() || is_singular( 'post' ) ) {
			return $layout;
		}

		/**
		 * Process Agent
		 */
			if ( is_post_type_archive( 'noo_agent' ) || is_tax( 'agent_category' ) ) {

	            return get_theme_mod( 'noo_agent_layout', 'fullwidth' );

			}

		/**
		 * Process Property
		 */
			if ( is_single() && get_post_type() == 'noo_property' ) {

				global $post;
				if ( !empty( $post->ID ) ) {
					$property_layout = get_post_meta( $post->ID, 'property_layout', true );
					if ( !empty( $property_layout ) && $property_layout != 'default' ) {
						return $property_layout;
					}
				}

				$property_layout = get_theme_mod( 'noo_property_post_layout_same', 'same_as_property' );
                if ( $property_layout == 'same_as_property' ) {
                    return get_theme_mod( 'noo_property_layout', 'fullwidth' );
                }
                return get_theme_mod( 'noo_property_post_layout', 'fullwidth' );

			} elseif ( 
				is_post_type_archive( 'noo_property' ) || 
                is_tax( 'property_status' ) || 
                is_tax( 'property_type' ) ||
                is_page( noo_get_page_by_template( 'property-search.php' ) )
            ) {

				return get_theme_mod( 'noo_property_layout', 'fullwidth' );

			}

		/**
		 * Property Search
		 */
			
			global $post;

			if ( empty( $post ) ) return $layout;

			$page_template = get_post_meta( $post->ID, '_wp_page_template', true );

			if( $page_template == 'property-search.php' ) {
				return get_theme_mod( 'noo_property_layout', 'fullwidth' );
			}

			return $layout;

	}

	add_filter( 'noo_page_layout', 'noo_get_page_layout' );

endif;

/**
 * Custom sidebar
 *
 * @package 	LandMark
 * @author 		KENT <tuanlv@vietbrain.com>
 * @version 	1.0
 */

if ( ! function_exists( 'noo_get_sidebar' ) ) :
	
	function noo_get_sidebar( $sidebar ) {

		if ( is_home() || is_front_page() || is_singular( 'post' ) ) {
			return $sidebar;
		}

		/**
		 * Process Agent
		 */
			elseif ( is_post_type_archive( 'noo_agent' ) || is_tax( 'agent_category' ) ) {

				$agent_layout = get_theme_mod( 'noo_agent_layout', 'fullwidth' );
	            if ( $agent_layout != 'fullwidth' ) {
	                return get_theme_mod( 'noo_agent_sidebar', 'noo-agent-sidebar' );
	            }
				return '';

			}

		/**
		 * Process Property
		 */
			elseif ( is_singular( 'noo_property' ) ) {

				global $post;
				if ( !empty( $post->ID ) ) {
					$property_sidebar = get_post_meta( $post->ID, 'property_sidebar', true );
					$property_layout = get_post_meta( $post->ID, 'property_layout', true );
					if ( !empty( $property_layout ) && $property_layout != 'fullwidth' && $property_layout != 'default' && !empty( $property_sidebar ) ) {
						return $property_sidebar;
					}
				}

				$property_layout = get_theme_mod( 'noo_property_post_layout_same', 'same_as_property' );
	            if ( $property_layout == 'same_as_property' ) {
					$property_layout = get_theme_mod( 'noo_property_layout', 'sidebar' );
		            if ( $property_layout != 'fullwidth' ) {
		                return get_theme_mod( 'noo_property_sidebar', 'noo-property-sidebar' );
		            } else {
						return '';
		            }
	            } else {
	            	$property_layout = get_theme_mod( 'noo_property_post_layout', 'sidebar' );
		            if ( $property_layout != 'fullwidth' ) {
		                return get_theme_mod( 'noo_property_post_sidebar', 'noo-property-sidebar' );
		            } else {
						return '';
		            }
	            }

                return '';

			} elseif ( 
				is_post_type_archive( 'noo_property' ) || 
                is_tax( 'property_status' ) || 
                is_tax( 'property_type' ) ||
                is_page( noo_get_page_by_template( 'property-search.php' ) )
			) {

				$property_layout = get_theme_mod( 'noo_property_layout', 'sidebar' );
	            if ( $property_layout != 'fullwidth' ) {
	                return get_theme_mod( 'noo_property_sidebar', 'noo-property-sidebar' );
	            } else {
					return '';
	            }

			}

		return $sidebar;

	}

	add_filter( 'noo_sidebar_id', 'noo_get_sidebar' );

endif;

/**
 * This function add sidebar extension
 *
 * @package 	LandMark
 * @author 		KENT <tuanlv@vietbrain.com>
 * @version 	1.0
 */

if ( ! function_exists( 'noo_add_sidebar_extension' ) ) :
	
	function noo_add_sidebar_extension() {

		if ( is_home() || is_page() || is_front_page() || is_singular( 'post' ) ) {
			return;
		}

		if ( 
			is_singular( 'noo_property' ) ||
			is_post_type_archive( 'noo_property' ) || 
            is_tax( 'property_status' ) || 
            is_tax( 'property_type' )
        ) {
		
			$sidebar = get_theme_mod( 'noo_property_sidebar_extension', '' );

		}

		if ( is_singular( 'noo_agent' ) || is_post_type_archive( 'noo_agent' ) || is_tax( 'agent_category' ) ) {
		
			$sidebar = get_theme_mod( 'noo_agent_sidebar_extension', '' );

		}

		
		if( ! empty( $sidebar ) ) : ?>
			<div class="noo-sidebar-wrap">
				<?php // Dynamic Sidebar
				if ( ! function_exists( 'dynamic_sidebar' ) || ! dynamic_sidebar( $sidebar ) ) : ?>
				<?php endif; // End Dynamic Sidebar sidebar-main ?>
			</div>
			<?php
		endif;

	}

	add_action( 'noo_after_sidebar', 'noo_add_sidebar_extension', 20 );

endif;

/**
 * Custom class tag body
 *
 * @package 	LandMark
 * @author 		KENT <tuanlv@vietbrain.com>
 * @version 	1.0
 */

if ( ! function_exists( 'noo_custom_class_tag_body' ) ) :
	
	function noo_custom_class_tag_body( $classes ) {

		if ( is_page() ) {
			global $post;
			$layout_search_template = get_post_meta( $post->ID, 'page_search_template', true );
			if ( $layout_search_template === 'half-map' ) {
				$classes[] = 'page-template-property-half-map';
			}
		}
        return $classes;

	}

	add_filter( 'body_class', 'noo_custom_class_tag_body' );

endif;