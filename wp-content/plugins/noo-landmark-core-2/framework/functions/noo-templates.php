<?php
/**
 * Register page template
 *
 * @package 	LandMark
 * @author 		KENT <tuanlv@vietbrain.com>
 * @version 	1.1
 */

if ( !class_exists( 'Noo_Page_Template' ) ) :

	class Noo_Page_Template {

        /**
         * A reference to an instance of this class.
         */
	        private static $instance;

        /**
         * Returns an instance of this class. 
         */
        public static function get_instance() {

            if( null == self::$instance ) {
                self::$instance = new Noo_Page_Template();
            }

            return self::$instance;

        } 

        /**
         * Initializes the plugin by setting filters and administration functions.
         */
        private function __construct() {

            /**
             * Register page template
             */
            add_filter( 'theme_page_templates', array( &$this, 'register_templates')  );

            /**
             * Add a filter to the template include to determine if the page has our
             */
            add_filter( 'template_include', array( $this, 'views_templates') );
				
        }

        /**
         * Get list page templates
         */
        public function list_template() {

        	return apply_filters( 'noo_add_template', array(
				'user-profile.php'      => esc_html__( 'User Profile', 'noo-landmark-core' ),
				'compare-listing.php'   => esc_html__( 'Compare Listing', 'noo-landmark-core' ),
				'register-member.php'   => esc_html__( 'Register Member', 'noo-landmark-core' ),
				'property-search.php'   => esc_html__( 'Property Search', 'noo-landmark-core' ),
				'property-half-map.php' => esc_html__( 'Property Half Map', 'noo-landmark-core' ),
				'saved-search.php'      => esc_html__( 'Saved Search', 'noo-landmark-core' )
            ) );

        }

        /**
         * Adds our template to the pages cache in order to trick WordPress
         * into thinking the template file exists where it doens't really exist.
         */
        public function register_templates( $post_templates ) {

            return array_merge( $post_templates, $this->list_template() );

        } 

        /**
         * Checks if the template is assigned to the page
         */
        public function views_templates( $template ) {

            global $post;

            /**
             * Check is post
             */
            	if ( empty( $post ) || empty( $post->ID ) ) {

            		return $template;

            	}

            /**
             * Check is template
             */
                $template_name = get_post_meta( $post->ID, '_wp_page_template', true );
                $list_template = $this->list_template();

                if ( empty( $template_name ) || !isset( $list_template[$template_name] ) || empty( $list_template[$template_name] ) ) {
					
                    return $template;
						
                }

			
            /**
             * Just to be safe, we check if the file exist first
             */
                $file_template = NOO_PLUGIN_SERVER_PATH . '/templates/' . $template_name;
                if( file_exists( $file_template ) ) {
                    return $file_template;
                }

            return $template;

        } 

	}

	add_action( 'plugins_loaded', array( 'Noo_Page_Template', 'get_instance' ) );
	
endif;