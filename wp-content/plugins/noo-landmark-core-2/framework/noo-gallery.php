<?php
/**
 * Create class Noo_Gallery
 * Function support Gallery to Noo Landmark
 *
 * @package 	Noo_Landmark_Core
 * @author 		James <luyentv@vietbrain.com>
 * @version 	1.0
 */

if ( !class_exists( 'Noo_Gallery' )) :

	class Noo_Gallery
	{

		/**
         * A reference to an instance of this class.
         */
        private static $instance;

        /**
         * The array of templates that this plugin using.
         */
        protected $noo_gallery;

        /**
         * Returns an instance of this class.
         */
        public static function get_instance() {

            if( null == self::$instance ) {
                self::$instance = new Noo_Gallery();
            }
            return self::$instance;

        }

        /**
         * Initializes the plugin by setting filters and administration functions.
         */
		private function __construct()
		{

			/**
			 * VAR
			 */
			$this->noo_gallery = array(
				'slug'         => 'noo_gallery',
				'rewrite_slug' => 'noo-gallery',
				'icon'         => 'dashicons-format-gallery',
				'prefix'       => '_noo_gallery'
			);

			/**
			 * Load action/filter
			 */
			add_action( 'init', array( $this, 'register_post_type' ) );

		}

		/**
		 * Register post type noo_gallery
		 */
		public  function register_post_type() 
		{
			
			/**
			 * @var array
			 */
			$team_labels = array(
				'name'                => esc_html__( 'Gallery', 'noo-landmark-core' ),
				'singular_name'       => esc_html__( 'Gallery', 'noo-landmark-core' ),
				'menu_name'           => esc_html__( 'Gallery', 'noo-landmark-core' ),
				'add_new'             => esc_html__( 'Add New Gallery', 'noo-landmark-core', 'noo-landmark-core' ),
				'add_new_item'        => esc_html__( 'Add New Gallery Item', 'noo-landmark-core' ),
				'edit_item'           => esc_html__( 'Edit Gallery', 'noo-landmark-core' ),
				'new_item'            => esc_html__( 'New Gallery', 'noo-landmark-core' ),
				'view_item'           => esc_html__( 'View Gallery', 'noo-landmark-core' ),
				'search_items'        => esc_html__( 'Search Gallery', 'noo-landmark-core' ),
				'not_found'           => esc_html__( 'No Gallery Items found', 'noo-landmark-core' ),
				'not_found_in_trash'  => esc_html__( 'No Gallery Items found in Trash', 'noo-landmark-core' ),
				'parent_item_colon'   => '',
			);
		
			$team_args = array(
				'labels'              => $team_labels,
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'menu_position'       => 5,
				'menu_icon'           => $this->noo_gallery['icon'],
				'has_archive'         => false,
				'rewrite'             => array(
					'slug'       => $this->noo_gallery['rewrite_slug'],
					'with_front' => true
				),
				'capability_type'     => 'post',
				'supports'            => array(
					'title',
					'author',
					'thumbnail',
				)
			);
		
			register_post_type( $this->noo_gallery['slug'], $team_args );
		}
		
	}

	Noo_Gallery::get_instance();

endif;