<?php
/**
 * @package Import demo
 */

/**
 * Load Importer API
 */
require_once ABSPATH . 'wp-admin/includes/import.php';

if ( ! class_exists( 'WP_Importer' ) ) {
	$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
	if ( file_exists( $class_wp_importer ) ) {
		require $class_wp_importer;
	}
}

class Noo_Import_Demo extends WP_Importer{
	var $id; // attachment ID

	// information to import from WXR file
	var $authors    = array();
	var $posts      = array();
	var $terms      = array();
	var $categories = array();
	var $tags       = array();
	var $base_url   = '';

	// mappings from old information to new
	var $processed_authors    = array();
	var $author_mapping       = array();
	var $processed_terms      = array();
	var $processed_posts      = array();
	var $post_orphans         = array();
	var $processed_menu_items = array();
	var $menu_item_orphans    = array();
	var $missing_menu_items   = array();

	var $list_nav_menu        = array();
	var $list_attachment      = array();
	
	var $fetch_attachments    = true;
	var $url_remap            = array();
	var $featured_images      = array();

	public function __construct() {
		if( !defined( 'NOO_IMPORTER_URI' ) ) {
			define('NOO_IMPORTER_URI', plugin_dir_url( __FILE__ ));
		}

		if( !defined( 'NOO_IMPORTER' ) ) {
			define('NOO_IMPORTER', dirname( __FILE__ ) );
		}

		require dirname( __FILE__ ) . '/inc/class-importer-helpers.php';
		require dirname( __FILE__ ) . '/inc/class-importer-menu.php';

		/**
		 * Register ajax
		 */
			add_action( 'wp_ajax_process_data', array( $this, 'process_data' ), 1 );
			
	}

	public function process_data() {
		
		/**
		 * Check security
		 */
			check_ajax_referer( 'noo-install-demo', 'security', esc_html__( 'Security Breach! Please contact admin!', 'noo-landmark-core' ) );

		/**
		 * Validate $_POST
		 */
			$_POST = stripslashes_deep( $_POST );

			if ( empty( $_POST['type'] ) || empty( $_POST['name'] ) ) return false;

			$type_event = Noo_Importer_Helpers::validate_data( $_POST['type'] );
			$name_demo  = Noo_Importer_Helpers::validate_data( $_POST['name'] );

			$file_demo_url  = NOO_IMPORTER_URI . "data-demo/{$name_demo}/content.xml";
			$widget_url     = NOO_IMPORTER_URI . "data-demo/{$name_demo}/widgets.wie";
			$option_url     = NOO_IMPORTER_URI . "data-demo/{$name_demo}/option.json";

			$file_demo        = NOO_IMPORTER . "/data-demo/{$name_demo}/content.xml";
			$widget           = NOO_IMPORTER . "/data-demo/{$name_demo}/widgets.wie";
			$option           = NOO_IMPORTER . "/data-demo/{$name_demo}/option.json";
			$source_revslider = NOO_IMPORTER . "/data-demo/{$name_demo}/slider.zip";

			switch ( $type_event ) {
				case 'start_import':
					/**
					 * Create reponsse data
					 */
						$response['msg']       = esc_html__( 'Start process importer data, please wait process posts...', 'noo-landmark-core' );
						$response['status']    = 'success';
						$response['next_task'] = 'check_files_systems';

					/**
					 * Add this message to log file.
					 */
					Noo_Importer_Helpers::update_log( $response['msg'], esc_html__( 'Start Importer', 'noo-landmark-core' ) );

					wp_send_json( $response );

					break;

				case 'check_files_systems':
					/**
					 * Add this message to log file.
					 */
					Noo_Importer_Helpers::update_log( '', esc_html__( 'Start Check File Systems', 'noo-landmark-core' ) );

					$message_file = array( esc_html__( 'Check File Systems', 'noo-landmark-core' ) );
					
					/**
					 * Check file content.xml
					 */
						if ( is_file( $file_demo ) ) {
							$message_file[] = esc_html__( 'Check file content.xml -> OK', 'noo-landmark-core' );
						} else {
							$message_file[] = esc_html__( 'Check file content.xml -> ERROR', 'noo-landmark-core' );
						}

					/**
					 * Check file widgets.wie
					 */
						if ( is_file( $widget ) ) {
							$message_file[] = esc_html__( 'Check file widgets.wie -> OK', 'noo-landmark-core' );
						} else {
							$message_file[] = esc_html__( 'Check file widgets.wie -> ERROR', 'noo-landmark-core' );
						}

					/**
					 * Check file option.json
					 */
						if ( is_file( $option ) ) {
							$message_file[] = esc_html__( 'Check file option.json -> OK', 'noo-landmark-core' );
						} else {
							$message_file[] = esc_html__( 'Check file option.json -> ERROR', 'noo-landmark-core' );
						}

					/**
					 * Check file slider.zip
					 */
						if ( file_exists( $source_revslider ) ) {
							$message_file[] = esc_html__( 'Check file slider.zip -> OK', 'noo-landmark-core' );
						} else {
							$message_file[] = esc_html__( 'Check file slider.zip -> ERROR', 'noo-landmark-core' );
						}


					$response['msg']       = implode( '<br />', $message_file );
					$response['status']    = 'success';
					$response['next_task'] = 'get_content';

					/**
					 * Add this message to log file.
					 */
					Noo_Importer_Helpers::update_log( $response['msg'], esc_html__( 'Check File Systems', 'noo-landmark-core' ) );

					wp_send_json( $response );

					break;

				case 'get_content':
					/**
					 * Add this message to log file.
					 */
					Noo_Importer_Helpers::update_log( '', esc_html__( 'Start Process Posts', 'noo-landmark-core' ) );

					if ( is_file( $file_demo ) ) {
						$task       = !empty( $_POST['task'] ) ? Noo_Importer_Helpers::validate_data( $_POST['task'] ) : '';
						$index_post = !empty( $_POST['index_post'] ) ? Noo_Importer_Helpers::validate_data( $_POST['index_post'] ) : 1;
						$this->process_import( $file_demo_url, $task, absint( $index_post ) );

						$response['msg']       = esc_html__( 'Import all posts successfully, please wait process widgets...', 'noo-landmark-core' );

					} else {
						$response['msg']       = esc_html__( 'Skip process post, because a content.xml file does not exist on the system Please wait process widgets...', 'noo-landmark-core' );
					}
					$response['status']    = 'success';
					$response['next_task'] = 'get_widgets';

					/**
					 * Add this message to log file.
					 */
					Noo_Importer_Helpers::update_log( $response['msg'], esc_html__( 'End Process Posts', 'noo-landmark-core' ) );

					wp_send_json( $response );

					break;


				case 'get_widgets':
					/**
					 * Add this message to log file.
					 */
					Noo_Importer_Helpers::update_log( '', esc_html__( 'Start Process widgets', 'noo-landmark-core' ) );

					if ( is_file( $widget ) ) {
						$this->import_widgets( $widget_url );
						$response['msg']       = esc_html__( 'Import all widgets successfully, please wait process posts...', 'noo-landmark-core' );
					} else {
						$response['msg']       = esc_html__( 'Skip process widgets, because a widgets.wie file does not exist on the system Please wait process options...', 'noo-landmark-core' );
					}
					$response['status']    = 'success';
					$response['next_task'] = 'get_option';

					/**
					 * Add this message to log file.
					 */
					Noo_Importer_Helpers::update_log( $response['msg'], esc_html__( 'End Process widgets', 'noo-landmark-core' ) );

					wp_send_json( $response );

					break;

				case 'get_option':
					/**
					 * Add this message to log file.
					 */
					Noo_Importer_Helpers::update_log( '', esc_html__( 'Start Process Options', 'noo-landmark-core' ) );

					if ( is_file( $option ) ) {
						$this->process_option( $option_url );
						$response['msg']       = esc_html__( 'Import all option successfully!', 'noo-landmark-core' );
					} else {
						$response['msg']       = esc_html__( 'Skip process options, because a option.json file does not exist on the system.', 'noo-landmark-core' );
					}

					$response['status']    = 'success';
					$response['next_task'] = 'RevSlider';

					/**
					 * Add this message to log file.
					 */
					Noo_Importer_Helpers::update_log( $response['msg'], esc_html__( 'End Process Options', 'noo-landmark-core' ) );

					wp_send_json( $response );

					break;

				case 'RevSlider':
					/**
					 * Add this message to log file.
					 */
					Noo_Importer_Helpers::update_log( '', esc_html__( 'Start Process Import RevSlider', 'noo-landmark-core' ) );

					if ( class_exists( 'RevSlider' ) && file_exists( $source_revslider ) ) {
						$this->import_RevSlider( $source_revslider );
						$response['msg'] = esc_html__( 'Import revslider successfully!', 'noo-landmark-core' );
					} else {
						$response['msg'] = esc_html__( 'Skip process import revslider.', 'noo-landmark-core' );
					}

					$response['status']    = 'success';
					$response['next_task'] = 'end_task';

					/**
					 * Add this message to log file.
					 */
					Noo_Importer_Helpers::update_log( $response['msg'], esc_html__( 'End Process Import RevSlider', 'noo-landmark-core' ) );

					wp_send_json( $response );

					break;

				case 'end_task':
					$response['msg']       = esc_html__( 'Import successfully! Thank you!', 'noo-landmark-core' );
					$response['status']    = 'success';
					$response['next_task'] = 'complete';

					/**
					 * Add this message to log file.
					 */
					Noo_Importer_Helpers::update_log( $response['msg'], esc_html__( 'Complete Importer', 'noo-landmark-core' ) );

					wp_send_json( $response );

					break;
				
				default:
					/**
					 * Message default
					 */
					$response['msg'] = esc_html__( 'Error! Cant process file content, please check again!', 'noo-landmark-core' );
					$response['status'] = 'error';
					$response['task'] = '';

					wp_send_json( $response );

					break;

			}


	}

	/**
	 * The main controller for the actual import stage.
	 *
	 * @param string $file Path to the WXR file for importing
	 */
	function process_import( $file, $task = '', $index_post = 0 ) {
		add_filter( 'import_post_meta_key', array( $this, 'is_valid_meta_key' ) );
        add_filter( 'http_request_timeout', array( $this, 'import_http_timeout') );

		wp_suspend_cache_invalidation( true );

		switch ( $task ) {
			case 'get_author':
				/**
				 * Add this message to log file.
				 */
				Noo_Importer_Helpers::update_log( '', esc_html__( 'Start Get Author', 'noo-landmark-core' ) );

				/**
				 * Process data author
				 */
				$this->process_authors();

				$response['msg']       = esc_html__( 'Obtaining data author successfully, please wait process get category...', 'noo-landmark-core' );
				$response['next_task'] = 'get_content';
				$response['status']    = 'success';
				$response['task']      = 'get_category';
				
				/**
				 * Add this message to log file.
				 */
				Noo_Importer_Helpers::update_log( $response['msg'], esc_html__( 'End Get Author', 'noo-landmark-core' ) );

				wp_send_json( $response );

				break;
			
			case 'get_category':
				/**
				 * Add this message to log file.
				 */
				Noo_Importer_Helpers::update_log( '', esc_html__( 'Start Get Category', 'noo-landmark-core' ) );

				$this->process_categories();
				$response['msg']       = esc_html__( 'Obtaining data categories successfully, please wait process get tags...', 'noo-landmark-core' );
				$response['next_task'] = 'get_content';
				$response['status']    = 'success';
				$response['task']      = 'get_tags';

				/**
				 * Add this message to log file.
				 */
				Noo_Importer_Helpers::update_log( $response['msg'], esc_html__( 'End Get Category', 'noo-landmark-core' ) );

				wp_send_json( $response );

				break;
			
			case 'get_tags':
				/**
				 * Add this message to log file.
				 */
				Noo_Importer_Helpers::update_log( '', esc_html__( 'Start Get Tags', 'noo-landmark-core' ) );

				$this->process_tags();
				$response['msg']       = esc_html__( 'Obtaining data tags successfully, please wait process get term...', 'noo-landmark-core' );
				$response['next_task'] = 'get_content';
				$response['status']    = 'success';
				$response['task']      = 'get_terms';

				/**
				 * Add this message to log file.
				 */
				Noo_Importer_Helpers::update_log( $response['msg'], esc_html__( 'End Get Tags', 'noo-landmark-core' ) );

				wp_send_json( $response );

				break;
			
			case 'get_terms':
				/**
				 * Add this message to log file.
				 */
				Noo_Importer_Helpers::update_log( '', esc_html__( 'Start Get Term', 'noo-landmark-core' ) );

				$this->process_terms();

				// $option     = NOO_PLUGIN_SERVER_PATH . "/admin/importer/data-demo/{$name_demo}/option.json";
				// $this->process_option( $option );
				
				$response['msg']       = esc_html__( 'Obtaining data terms successfully, please wait process get posts...', 'noo-landmark-core' );
				$response['next_task'] = 'get_content';
				$response['status']    = 'success';
				$response['task']      = 'get_posts';

				/**
				 * Add this message to log file.
				 */
				Noo_Importer_Helpers::update_log( $response['msg'], esc_html__( 'End Get Term', 'noo-landmark-core' ) );

				wp_send_json( $response );

				break;
			
			case 'get_posts':
				/**
				 * Add this message to log file.
				 */
				Noo_Importer_Helpers::update_log( '', esc_html__( 'Start Get Posts', 'noo-landmark-core' ) );

				$this->process_posts();
				$response['msg']       = esc_html__( 'Obtaining data posts successfully, please wait process get lists posts...', 'noo-landmark-core' );
				$response['next_task'] = 'get_content';
				$response['status']    = 'success';
				$response['task'] 	   = 'get_list_posts';

				/**
				 * Add this message to log file.
				 */
				Noo_Importer_Helpers::update_log( $response['msg'], esc_html__( 'End Get Posts', 'noo-landmark-core' ) );

				wp_send_json( $response );
				break;
			
			case 'get_list_posts':
				/**
				 * Add this message to log file.
				 */
				if ( !empty( $index_post ) && $index_post == 0 ) {
					Noo_Importer_Helpers::update_log( '', esc_html__( 'Start Get Lists Posts', 'noo-landmark-core' ) );
				}

				$response_list_post = $this->process_list_post();

				if ( !empty( $response_list_post ) && $response_list_post === 'end_task' ) {

					$response['msg']        = esc_html__( 'Obtaining lists posts successfully, please wait process attachment image...', 'noo-landmark-core' );
					$response['next_task']  = 'get_content';
					$response['status']     = 'success';
					$response['task']       = 'get_attachment';
					$response['index_post'] = 0;

					/**
					 * Add this message to log file.
					 */
					Noo_Importer_Helpers::update_log(
						$response['msg'],
						esc_html__( 'End Get Lists Posts', 'noo-landmark-core' )
					);

				} else {

					$response['msg']        = sprintf( esc_html__( 'Please wait process post #%s...', 'noo-landmark-core' ), $index_post++ );
					$response['next_task']  = 'get_content';
					$response['status']     = 'success';
					$response['task']       = 'get_list_posts';
					$response['index_post'] = $index_post;

					/**
					 * Add this message to log file.
					 */
					Noo_Importer_Helpers::update_log(
						$response['msg'],
						sprintf(
							esc_html__( 'Process Lists Posts #%s', 'noo-landmark-core' ),
							$index_post
						)
					);

				}

				wp_send_json( $response );

				break;
			
			case 'get_attachment':
				if ( !empty( $index_post ) && $index_post == 0 ) {
					/**
					 * Add this message to log file.
					 */
					Noo_Importer_Helpers::update_log( '', esc_html__( 'Start Get Attachment', 'noo-landmark-core' ) );
				}

				$response_attachment = $this->process_attachment();

				if ( !empty( $response_attachment ) && $response_attachment === 'end_task' ) {

					$response['msg']        = esc_html__( 'Obtaining attachment image successfully, please wait process nav menu...', 'noo-landmark-core' );
					$response['next_task']  = 'get_content';
					$response['status']     = 'success';
					$response['task']       = 'get_nav_menu';
					$response['index_post'] = 0;

					/**
					 * Add this message to log file.
					 */
					Noo_Importer_Helpers::update_log(
						$response['msg'],
						esc_html__( 'End Get Attachment', 'noo-landmark-core' )
					);

				} else {

					$response['msg']        = sprintf( esc_html__( 'Please wait process attachment image #%s...', 'noo-landmark-core' ), $index_post++ );
					$response['next_task']  = 'get_content';
					$response['status']     = 'success';
					$response['task']       = 'get_attachment';
					$response['index_post'] = $index_post;

					/**
					 * Add this message to log file.
					 */
					Noo_Importer_Helpers::update_log(
						$response['msg'],
						sprintf(
							esc_html__( 'Process attachment image #%s', 'noo-landmark-core' ),
							$index_post
						)
					);

				}

				wp_send_json( $response );

				break;
			
			case 'get_nav_menu':
				/**
				 * Add this message to log file.
				 */
				if ( !empty( $index_post ) && $index_post == 0 ) {
					Noo_Importer_Helpers::update_log( '', esc_html__( 'Start Get Nav Menu', 'noo-landmark-core' ) );
				}

				$response_list_post = $this->process_nav_menu();

				if ( !empty( $response_list_post ) && $response_list_post === 'end_task' ) {

					$response['msg']        = esc_html__( 'Obtaining data nav menu successfully, please wait process recheck all data post...', 'noo-landmark-core' );
					$response['next_task']  = 'get_content';
					$response['status']     = 'success';
					$response['task']       = 'recheck';
					$response['index_post'] = 0;

					/**
					 * Add this message to log file.
					 */
					Noo_Importer_Helpers::update_log(
						$response['msg'],
						esc_html__( 'End Get Nav Menu', 'noo-landmark-core' )
					);

				} else {

					$response['msg']        = sprintf( esc_html__( 'Please wait process nav menu #%s...', 'noo-landmark-core' ), $index_post++ );
					$response['next_task']  = 'get_content';
					$response['status']     = 'success';
					$response['task']       = 'get_nav_menu';
					$response['index_post'] = $index_post;

					/**
					 * Add this message to log file.
					 */
					Noo_Importer_Helpers::update_log(
						$response['msg'],
						sprintf(
							esc_html__( 'Process Nav Menu #%s', 'noo-landmark-core' ),
							$index_post
						)
					);

				}

				wp_send_json( $response );

				break;

			case 'recheck':
				/**
				 * Add this message to log file.
				 */
				Noo_Importer_Helpers::update_log( '', esc_html__( 'Start Recheck Data Post', 'noo-landmark-core' ) );

				$this->backfill_parents();
				$this->backfill_attachment_urls();
				$this->remap_featured_images();

				$response['msg'] = esc_html__( 'Check all data post success, please wait process options...', 'noo-landmark-core' );

				/**
				 * Add this message to log file.
				 */
				Noo_Importer_Helpers::update_log( $response['msg'], esc_html__( 'End Recheck Data Post', 'noo-landmark-core' ) );

				break;
			
			case '':

				$this->import_start( $file );
				
				break;

		}

		$this->import_end();

		wp_suspend_cache_invalidation( false );
	}

	function import_http_timeout($timeout){
		return 600;
	}

	/**
	 * Parses the WXR file and prepares us for the task of processing parsed data
	 *
	 * @param string $file Path to the WXR file for importing
	 */
	function import_start( $file ) {

		require dirname( __FILE__ ) . '/inc/class-importer-parse.php';

		$import_data = Noo_Importer_Parse::filters_data( $file );

		if ( is_wp_error( $import_data ) ) {

			$response['status'] = 'error';
			$response['msg']    = esc_html__( 'Sorry, there has been an error.', 'noo-landmark-core' );
			$response['msg']    .= '<br />' . $import_data->get_error_message();

			/**
			 * Add this message to log file.
			 */
			Noo_Importer_Helpers::update_log( $response['msg'], esc_html__( 'Error Import Data', 'noo-landmark-core' ) );

			wp_send_json( $response );

		}

		/**
		 * Add this message to log file.
		 */
		Noo_Importer_Helpers::update_log( '', esc_html__( 'Start Import Data', 'noo-landmark-core' ) );

		wp_defer_term_counting( true );
		wp_defer_comment_counting( true );

		$response['msg']       = esc_html__( 'Begin the process of obtaining content data, please wait process get author...', 'noo-landmark-core' );
		$response['status']    = 'success';
		$response['next_task'] = 'get_content';
		$response['task']      = 'get_author';

		/**
		 * Add this message to log file.
		 */
		Noo_Importer_Helpers::update_log( $response['msg'], esc_html__( 'Start Get Content', 'noo-landmark-core' ) );
		
		wp_send_json( $response );

	}

	/**
	 * Performs post-import cleanup of files and the cache
	 */
	function import_end() {
		wp_import_cleanup( $this->id );

		wp_cache_flush();
		foreach ( get_taxonomies() as $tax ) {
			delete_option( "{$tax}_children" );
			_get_term_hierarchy( $tax );
		}

		wp_defer_term_counting( false );
		wp_defer_comment_counting( false );

	}

	/**
	 * Retrieve authors from parsed NOO data
	 *
	 * Uses the provided author information from NOO 1.1 files
	 * or extracts info from each post for NOO 1.0 files
	 *
	 * @param array $import_data Data returned by a NOO parser
	 */
	function process_authors() {
		/**
		 * Get info author
		 * @var json
		 */
		$data_author = Noo_Importer_Helpers::get_contents( Noo_Importer_Helpers::get_path( 'authors_', '.json' ) );

		if ( ! empty( $data_author ) ) {

			$data_author = json_decode( $data_author, true );

			/**
			 * Add this message to log file.
			 */
			Noo_Importer_Helpers::update_log(
				'',
				esc_html__( 'Get List Author Success!', 'noo-landmark-core' )
			);

			if ( is_array( $data_author ) ) {

				foreach ( $data_author as $user_name => $info ) {
					/**
					 * Create info data user
					 * @var array
					 */
					$user_data   = array(
						'user_login'   => esc_html( $user_name ),
						'user_pass'    => wp_generate_password(),
						'user_email'   => !empty( $info['author_email'] ) ? esc_attr( $info['author_email'] ) : '',
						'display_name' => !empty( $info['author_display_name'] ) ? esc_attr( $info['author_display_name'] ) : '',
						'first_name'   => !empty( $info['author_first_name'] ) ? esc_attr( $info['author_first_name'] ) : '',
						'last_name'    => !empty( $info['author_last_name'] ) ? esc_attr( $info['author_last_name'] ) : '',
					);

					$user_id = wp_insert_user( $user_data );

					if ( is_wp_error( $user_id ) ) {
						/**
						 * Add this message to log file.
						 */
						Noo_Importer_Helpers::update_log(
							sprintf(
								esc_html__( 'Failed to create new user for %s. Their posts will be attributed to the current user.%s %s', 'noo-landmark-core' ),
								esc_html( $user_name ),
								'<br />',
								$user_id->get_error_message()
							),
							sprintf(
								esc_html__( 'ERROR: Create new user "%s"', 'noo-landmark-core' ),
								esc_html( $user_name )
							)
						);

					} else {
						/**
						 * Add this message to log file.
						 */
						Noo_Importer_Helpers::update_log(
							'',
							sprintf(
								esc_html__( 'SUCCESS: Create new user "%s"', 'noo-landmark-core' ),
								esc_html( $user_name )
							)
						);

					}

				}

			}

		} else {
			/**
			 * Get data posts and get info author
			 * @var json
			 */
			$data_posts = Noo_Importer_Helpers::get_contents( Noo_Importer_Helpers::get_path( 'posts_', '.json' ) );
			
			if ( empty( $data_posts ) ) {
				return;
			}

			$data_posts = json_decode( $data_posts, true );

			foreach ( $data_posts as $post ) {
				$login = sanitize_user( $post['post_author'], true );
				if ( empty( $login ) ) {
					/**
					 * Add this message to log file.
					 */
					Noo_Importer_Helpers::update_log(
						$response['msg'],
						sprintf(
							esc_html__( 'Failed to import author %s.', 'noo-landmark-core' ),
							esc_html( $post['post_author'] )
						)
					);

					continue;
				}

				$user_id = wp_create_user( $login, wp_generate_password() );

				/**
				 * Add this message to log file.
				 */
				Noo_Importer_Helpers::update_log(
					'',
					sprintf(
						esc_html__( 'SUCCESS: Create new user "%s"', 'noo-landmark-core' ),
						esc_html( $login )
					)
				);

			}
		}
	}

	/**
	 * Create new categories based on import information
	 *
	 * Doesn't create a new category if its slug already exists
	 */
	function process_categories() {
		$data_categories = Noo_Importer_Helpers::get_contents( Noo_Importer_Helpers::get_path( 'categories_', '.json' ) );
		
		if ( empty( $data_categories ) ) {
			return;
		}

		$data_categories = json_decode( $data_categories, true );

		foreach ( $data_categories as $cat ) {
			/**
			 * if the category already exists leave it alone
			 */
			$term_id = term_exists( $cat['category_nicename'], 'category' );
			if ( $term_id ) {
				if ( is_array($term_id) ) {
					$term_id = $term_id['term_id'];
				}

				if ( isset($cat['term_id']) ) {
					$processed_terms[intval($cat['term_id'])] = (int) $term_id;
				}

				/**
				 * Add this message to log file.
				 */
				Noo_Importer_Helpers::update_log(
					'', 
					sprintf(
						esc_html__( 'ERROR: Category exists "%s" have ID is: %s.', 'noo-landmark-core' ),
						esc_html( $cat['category_nicename'] ),
						absint( $term_id )
					)
				);
				continue;
			}

			$category_parent      = empty( $cat['category_parent'] ) ? 0 : category_exists( $cat['category_parent'] );
			$category_description = isset( $cat['category_description'] ) ? $cat['category_description'] : '';
			$data_cat             = array(
				'category_nicename'    => $cat['category_nicename'],
				'category_parent'      => $category_parent,
				'cat_name'             => $cat['cat_name'],
				'category_description' => $category_description
			);

			$category_id = wp_insert_category( $data_cat );
			if ( ! is_wp_error( $category_id ) ) {
				/**
				 * Add this message to log file.
				 */
				Noo_Importer_Helpers::update_log(
					'', 
					sprintf(
						esc_html__( 'SUCCESS: Create category "%s" success have ID is: %s.', 'noo-landmark-core' ),
						esc_html( $cat['category_nicename'] ),
						absint( $category_id )
					)
				);
			} else {
				/**
				 * Add this message to log file.
				 */
				Noo_Importer_Helpers::update_log(
					sprintf(
						esc_html__( 
							'Failed to import category %s. %s %s', 
							'noo-landmark-core'
						), 
						esc_html(
							$cat['category_nicename']
						),
						'<br />',
						$category_id->get_error_message()
					), 
					sprintf(
						esc_html__( 'ERROR: Create category "%s".', 'noo-landmark-core' ),
						esc_html(
							$cat['category_nicename']
						)
					)
				);

				continue;
			}
		}

		if ( !empty( $processed_terms ) ) {

			Noo_Importer_Helpers::update_log( sprintf( esc_html__( 'Number: %s' ) , count( $processed_terms ) ), esc_html__( 'Total Processed Terms' ) );
			Noo_Importer_Helpers::update_json( $processed_terms, Noo_Importer_Helpers::get_name_file( 'processed_terms_' ) );

		}

	}

	/**
	 * Create new post tags based on import information
	 *
	 * Doesn't create a tag if its slug already exists
	 */
	function process_tags() {
		$data_tags = Noo_Importer_Helpers::get_contents( Noo_Importer_Helpers::get_path( 'tags_', '.json' ) );
		
		if ( empty( $data_tags ) ) {
			return;
		}

		$data_tags = json_decode( $data_tags, true );

		foreach ( $data_tags as $tag ) {
			/**
			 * if the tag already exists leave it alone
			 */
			$term_id = term_exists( $tag['tag_slug'], 'post_tag' );
			if ( $term_id ) {
				if ( is_array( $term_id ) ) {
					$term_id = $term_id['term_id'];
				}

				/**
				 * Add this message to log file.
				 */
				Noo_Importer_Helpers::update_log(
					'', 
					sprintf(
						esc_html__( 'ERROR: Tags exists "%s" have ID is: %s.', 'noo-landmark-core' ),
						esc_html( $tag['tag_name'] ),
						absint( $term_id )
					)
				);

				continue;
			}

			$tag_desc = isset( $tag['tag_description'] ) ? $tag['tag_description'] : '';
			$tagarr   = array( 'slug' => $tag['tag_slug'], 'description' => $tag_desc );

			$tag_id = wp_insert_term( $tag['tag_name'], 'post_tag', $tagarr );
			if ( ! is_wp_error( $tag_id ) ) {
				/**
				 * Add this message to log file.
				 */
				Noo_Importer_Helpers::update_log(
					'', 
					sprintf(
						esc_html__( 'SUCCESS: Create tags "%s" success have ID is: %s.', 'noo-landmark-core' ),
						esc_html( $tag['tag_name'] ),
						absint( $tag_id )
					)
				);

			} else {
				/**
				 * Add this message to log file.
				 */
				Noo_Importer_Helpers::update_log(
					sprintf(
						esc_html__( 
							'Failed to import post tag %s. %s %s', 
							'noo-landmark-core'
						), 
						esc_html(
							$tag['tag_name']
						),
						'<br />',
						$tag_id->get_error_message()
					), 
					sprintf(
						esc_html__( 'Failed to create post tag: %s.', 'noo-landmark-core' ),
						esc_html(
							$tag['tag_name']
						)
					)
				);

				continue;
			}
		}

	}

	/**
	 * Create new terms based on import information
	 *
	 * Doesn't create a term its slug already exists
	 */
	function process_terms() {
		$data_terms = Noo_Importer_Helpers::get_contents( Noo_Importer_Helpers::get_path( 'terms_', '.json' ) );
		
		if ( empty( $data_terms ) ) {
			return;
		}

		$data_terms = json_decode( $data_terms, true );

		foreach ( $data_terms as $term ) {
			/**
			 * if the term already exists in the correct taxonomy leave it alone
			 */
			$term_id = term_exists( $term['slug'], $term['term_taxonomy'] );
			if ( $term_id ) {
				if ( is_array($term_id) ) {
					$term_id = $term_id['term_id'];
				}

				/**
				 * Add this message to log file.
				 */
				Noo_Importer_Helpers::update_log(
					'', 
					sprintf(
						esc_html__( 'ERROR: Term exists "%s" have ID is: %s.', 'noo-landmark-core' ),
						esc_html( $term['term_name'] ),
						absint( $term_id )
					)
				);

				continue;
			}

			if ( empty( $term['term_parent'] ) ) {
				$parent = 0;
			} else {
				$parent = term_exists( $term['term_parent'], $term['term_taxonomy'] );
				if ( is_array( $parent ) ) {
					$parent = $parent['term_id'];
				}
			}
			$description = isset( $term['term_description'] ) ? $term['term_description'] : '';
			$termarr     = array( 
				'slug'        => $term['slug'], 
				'description' => $description, 
				'parent'      => intval($parent)
			);

			$term_id = wp_insert_term( $term['term_name'], $term['term_taxonomy'], $termarr );
			if ( ! is_wp_error( $term_id ) ) {
				/**
				 * Add this message to log file.
				 */
				Noo_Importer_Helpers::update_log(
					'', 
					sprintf(
						esc_html__( 'SUCCESS: Create term "%s" success have ID is: %s.', 'noo-landmark-core' ),
						esc_html( $term['term_name']),
						absint( $term_id )
					)
				);

			} else {
				/**
				 * Add this message to log file.
				 */
				Noo_Importer_Helpers::update_log(
					sprintf(
						esc_html__( 
							'Failed to import %s. %s %s', 
							'noo-landmark-core'
						), 
						esc_html(
							$term['term_taxonomy']
						),
						"\n",
						$term_id->get_error_message()
					), 
					sprintf(
						esc_html__( 'Failed to import: %s.', 'noo-landmark-core' ),
						esc_html(
							$term['term_taxonomy']
						)
					)
				);

				continue;
			}
		}

	}

	/**
	 * Create new posts based on import information
	 *
	 * Posts marked as having a parent which doesn't exist will become top level items.
	 * Doesn't create a new post if: the post type doesn't exist, the given post ID
	 * is already noted as imported or a post with the same title and date already exists.
	 * Note that new/updated terms, comments and meta are imported for the last of the above.
	 */
	function process_posts() {

		$data_posts = Noo_Importer_Helpers::get_contents( Noo_Importer_Helpers::get_path( 'posts_', '.json' ) );
		
		if ( empty( $data_posts ) ) {
			return false;
		}

		$data_posts = json_decode( $data_posts, true );

		if ( !is_array( $data_posts ) ) {
			return false;
		}

		$list_post = array_chunk($data_posts, 2, false);

		foreach ( $data_posts as $post ) {

			if ( ! post_type_exists( $post['post_type'] ) ) {
				/**
				 * Add this message to log file.
				 */
				Noo_Importer_Helpers::update_log(
					sprintf(
						esc_html__( 
							'Failed to import &#8220;%s&#8221;: Invalid post type %s.', 
							'noo-landmark-core'
						), 
						esc_html(
							$post['post_title']
						),
						esc_html(
							$post['post_type']
						)
					), 
					sprintf(
						esc_html__( 'Failed to import: %s.', 'noo-landmark-core' ),
						esc_html(
							$post['post_title']
						)
					)
				);

				continue;

			}

			if ( $post['status'] == 'auto-draft' ) {
				continue;
			}

			if ( 'nav_menu_item' == $post['post_type'] ) {
				$this->list_nav_menu[] = $post;
				continue;
			}

			$post_type_object = get_post_type_object( $post['post_type'] );

			$post_exists = post_exists( $post['post_title'], '', $post['post_date'] );
			if ( $post_exists && get_post_type( $post_exists ) == $post['post_type'] ) {
				/**
				 * Add this message to log file.
				 */
				Noo_Importer_Helpers::update_log(
					sprintf(
						esc_html__( 
							'%s &#8220;%s&#8221; already exists.', 
							'noo-landmark-core'
						), 
						esc_html(
							$post_type_object->labels->singular_name
						),
						esc_html(
							$post['post_title']
						)
					), 
					sprintf(
						esc_html__( 'Failed to import: %s.', 'noo-landmark-core' ),
						esc_html(
							$post['post_title']
						)
					)
				);

				$comment_post_ID = $post_id = $post_exists;

			} else {
				$post_parent = (int) $post['post_parent'];
				if ( $post_parent ) {
					// if ( isset( $this->processed_posts[$post_parent] ) ) {
						/**
						 * if we already know the parent, map it to the new local ID
						 */
						// $post_parent = $this->processed_posts[$post_parent];
					// } else {
						/**
						 * otherwise record the parent for later
						 */
						$this->post_orphans[intval($post['post_id'])] = $post_parent;
						$post_parent = 0;
					// }
				}

				// map the post author
				$author = sanitize_user( $post['post_author'], true );
				$author = get_user_by( 'login', $author );
				if ( !empty( $author ) && !empty( $author->ID ) ) {
					$author = $author->ID;
				} else {
					$author = (int) get_current_user_id();
				}

				$postdata = array(
					'post_id'        => $post['post_id'],
					'import_id'      => $post['post_id'],
					'post_author'    => $author,
					'post_date'      => $post['post_date'],
					'post_date_gmt'  => $post['post_date_gmt'],
					'post_content'   => $post['post_content'],
					'post_excerpt'   => $post['post_excerpt'],
					'post_title'     => $post['post_title'],
					'post_status'    => $post['status'],
					'post_name'      => $post['post_name'],
					'comment_status' => $post['comment_status'],
					'ping_status'    => $post['ping_status'],
					'guid'           => $post['guid'],
					'post_parent'    => $post_parent,
					'menu_order'     => $post['menu_order'],
					'post_type'      => $post['post_type'],
					'post_password'  => $post['post_password'],
					'terms'		     => $post['terms'],
					'comments'		 => $post['comments'],
					'postmeta'		 => $post['postmeta'],
					'is_sticky'		 => $post['is_sticky']
				);

				$original_post_ID = $post['post_id'];
				$postdata         = apply_filters( 'wp_import_post_data_processed', $postdata, $post );

				if ( 'attachment' == $postdata['post_type'] ) {
					$remote_url = ! empty($post['attachment_url']) ? $post['attachment_url'] : $post['guid'];
					// try to use _wp_attached file for upload folder placement to ensure the same location as the export site
					// e.g. location is 2003/05/image.jpg but the attachment post_date is 2010/09, see media_handle_upload()
					$postdata['upload_date'] = $post['post_date'];
					if ( isset( $post['postmeta'] ) ) {
						foreach( $post['postmeta'] as $meta ) {
							if ( $meta['key'] == '_wp_attached_file' ) {
								if ( preg_match( '%^[0-9]{4}/[0-9]{2}%', $meta['value'], $matches ) ) {
									$postdata['upload_date'] = $matches[0];
								}
								break;
							}
						}
					}
					$this->list_attachment[] = $postdata;
					continue;
				} else {
					$this->list_posts[] = $postdata;
					continue;
				}

			}

		}

		if ( !empty( $this->list_nav_menu ) ) {

			Noo_Importer_Helpers::update_log( sprintf( esc_html__( 'Number: %s' ) , count( $this->list_nav_menu ) ), esc_html__( 'Total Nav Menu' ) );
			Noo_Importer_Helpers::update_json( $this->list_nav_menu, Noo_Importer_Helpers::get_name_file( 'nav_menu_' ) );

		}

		if ( !empty( $this->list_attachment ) ) {

			Noo_Importer_Helpers::update_log( sprintf( esc_html__( 'Number: %s' ) , count( $this->list_attachment ) ), esc_html__( 'Total Attachment File' ) );
			Noo_Importer_Helpers::update_json( $this->list_attachment, Noo_Importer_Helpers::get_name_file( 'attachment_' ) );

		}

		if ( !empty( $this->post_orphans ) ) {

			Noo_Importer_Helpers::update_log( sprintf( esc_html__( 'Number: %s' ) , count( $this->post_orphans ) ), esc_html__( 'Total Post Parent' ) );
			Noo_Importer_Helpers::update_json( $this->post_orphans, Noo_Importer_Helpers::get_name_file( 'post_orphans_' ) );

		}

		if ( !empty( $this->list_posts ) ) {

			Noo_Importer_Helpers::update_log( sprintf( esc_html__( 'Number: %s' ) , count( $this->list_posts ) ), esc_html__( 'Total Post' ) );
			Noo_Importer_Helpers::update_json( $this->list_posts, Noo_Importer_Helpers::get_name_file( 'list_posts_' ) );

		}

	}

	/**
	 * Get list nav menu in json file and create new menu item
	 */
	function process_nav_menu() {
		error_reporting(0);
		/**
		 * Get data posts
		 * @var json
		 */
		$data_nav_menu = Noo_Importer_Helpers::get_contents( Noo_Importer_Helpers::get_path( 'nav_menu_', '.json' ) );
		
		if ( empty( $data_nav_menu ) ) {
			return 'end_task';
		}

		$data_nav_menu = json_decode( $data_nav_menu, true );

		if ( !is_array( $data_nav_menu ) ) {
			return 'end_task';
		}

		$nav_menu = array_chunk( $data_nav_menu, 2, false );

		if ( empty( $nav_menu[0] ) ) {
			return 'end_task';
		}

		foreach ( $nav_menu[0] as $menu ) {

			Noo_Importer_Menu::menu_item( $menu );

		}

		unset( $nav_menu[0] );

		/**
		 * Unlink file nav menu
		 */
		unlink( Noo_Importer_Helpers::update_json( '', Noo_Importer_Helpers::get_name_file( 'nav_menu_' ), true ) );

		/**
		 * Update list nav menu
		 */
		Noo_Importer_Helpers::update_json(
			Noo_Importer_Helpers::unchuck( $nav_menu ),
			Noo_Importer_Helpers::get_name_file( 'nav_menu_' )
		);

	}


	/**
	 * Get list post in json file and create new post
	 */
	function process_list_post() {
		// error_reporting(0);
		/**
		 * Get data posts
		 * @var json
		 */
		$data_list_posts = Noo_Importer_Helpers::get_contents( Noo_Importer_Helpers::get_path( 'list_posts_', '.json' ) );
		
		if ( empty( $data_list_posts ) ) {
			return 'end_task';
		}

		$data_list_posts = json_decode( $data_list_posts, true );

		if ( !is_array( $data_list_posts ) ) {
			return 'end_task';
		}

		$list_post = array_chunk( $data_list_posts, 2, false );

		if ( empty( $list_post[0] ) ) {
			return 'end_task';
		}

		foreach ( $list_post[0] as $post ) {

			$comment_post_ID  = $post_id = wp_insert_post( $post, true );
			$post_type_object = get_post_type_object( $post['post_type'] );

			if ( is_wp_error( $post_id ) || empty( $post_id ) ) {
				/**
				 * Add this message to log file.
				 */
				Noo_Importer_Helpers::update_log(
					sprintf(
						esc_html__( 
							'Failed to import %s &#8220;%s&#8221; %s %s', 
							'noo-landmark-core'
						), 
						esc_html(
							$post_type_object->labels->singular_name
						),
						esc_html(
							$post['post_title']
						),
						"\n",
						$post_id->get_error_message()
					), 
					sprintf(
						esc_html__( 'Failed to import: %s.', 'noo-landmark-core' ),
						esc_html(
							$post['post_title']
						)
					)
				);

				continue;
			}

			/**
			 * Add this message to log file.
			 */
			Noo_Importer_Helpers::update_log(
				sprintf(
					esc_html__( 
						'Create new posts: %s.', 
						'noo-landmark-core'
					),
					$post['post_title']
				), 
				esc_html__( 'Create Posts', 'noo-landmark-core' )
			);

			if ( !empty( $post['is_sticky'] ) && $post['is_sticky'] == 1 ) {
				stick_post( $post_id );
			}

			// map pre-import ID to local ID
			$this->processed_posts[intval($post['post_id'])] = (int) $post_id;

			if ( ! isset( $post['terms'] ) ) {
				$post['terms'] = array();
			}

			$post['terms'] = apply_filters( 'wp_import_post_terms', $post['terms'], $post_id, $post );

			// add categories, tags and other terms
			if ( ! empty( $post['terms'] ) ) {
				$terms_to_set = array();
				foreach ( $post['terms'] as $term ) {
					// back compat with NOO 1.0 map 'tag' to 'post_tag'
					$taxonomy    = ( 'tag' == $term['domain'] ) ? 'post_tag' : $term['domain'];
					$term_exists = term_exists( $term['slug'], $taxonomy );
					$term_id     = is_array( $term_exists ) ? $term_exists['term_id'] : $term_exists;
					if ( ! $term_id ) {
						$t = wp_insert_term( $term['name'], $taxonomy, array( 'slug' => $term['slug'] ) );
						if ( ! is_wp_error( $t ) ) {
							$term_id = $t['term_id'];
							do_action( 'wp_import_insert_term', $t, $term, $post_id, $post );
						} else {
							/**
							 * Add this message to log file.
							 */
							Noo_Importer_Helpers::update_log(
								sprintf(
									esc_html__( 
										'Failed to import %s %s %s %s', 
										'noo-landmark-core'
									), 
									esc_html(
										$taxonomy
									),
									esc_html(
										$term['name']
									),
									"\n",
									$t->get_error_message()
								), 
								sprintf(
									esc_html__( 'Failed to import: %s.', 'noo-landmark-core' ),
									esc_html(
										$term['name']
									)
								)
							);

							do_action( 'wp_import_insert_term_failed', $t, $term, $post_id, $post );
							continue;
						}
					}
					$terms_to_set[$taxonomy][] = intval( $term_id );
				}

				foreach ( $terms_to_set as $tax => $ids ) {
					$tt_ids = wp_set_post_terms( $post_id, $ids, $tax );
					do_action( 'wp_import_set_post_terms', $tt_ids, $ids, $tax, $post_id, $post );
				}
				unset( $post['terms'], $terms_to_set );
			}

			if ( ! isset( $post['comments'] ) ) {
				$post['comments'] = array();
			}

			$post['comments'] = apply_filters( 'wp_import_post_comments', $post['comments'], $post_id, $post );

			// add/update comments
			if ( ! empty( $post['comments'] ) /**&& $_POST['import_comment'] == 'true'**/ ) {
				$num_comments = 0;
				$inserted_comments = array();
				foreach ( $post['comments'] as $comment ) {
					$comment_id	= $comment['comment_id'];
					$newcomments[$comment_id]['comment_post_ID']      = $comment_post_ID;
					$newcomments[$comment_id]['comment_author']       = $comment['comment_author'];
					$newcomments[$comment_id]['comment_author_email'] = $comment['comment_author_email'];
					$newcomments[$comment_id]['comment_author_IP']    = $comment['comment_author_IP'];
					$newcomments[$comment_id]['comment_author_url']   = $comment['comment_author_url'];
					$newcomments[$comment_id]['comment_date']         = $comment['comment_date'];
					$newcomments[$comment_id]['comment_date_gmt']     = $comment['comment_date_gmt'];
					$newcomments[$comment_id]['comment_content']      = $comment['comment_content'];
					$newcomments[$comment_id]['comment_approved']     = $comment['comment_approved'];
					$newcomments[$comment_id]['comment_type']         = $comment['comment_type'];
					$newcomments[$comment_id]['comment_parent'] 	  = $comment['comment_parent'];
					$newcomments[$comment_id]['commentmeta']          = isset( $comment['commentmeta'] ) ? $comment['commentmeta'] : array();
					if ( isset( $this->processed_authors[$comment['comment_user_id']] ) )
						$newcomments[$comment_id]['user_id'] = $this->processed_authors[$comment['comment_user_id']];
				}
				ksort( $newcomments );

				foreach ( $newcomments as $key => $comment ) {
					// if this is a new post we can skip the comment_exists() check
					if ( ! $post_exists || ! comment_exists( $comment['comment_author'], $comment['comment_date'] ) ) {
						if ( isset( $inserted_comments[$comment['comment_parent']] ) ) {
							$comment['comment_parent'] = $inserted_comments[$comment['comment_parent']];
						}
						$comment                 = wp_filter_comment( $comment );
						$inserted_comments[$key] = wp_insert_comment( $comment );
						do_action( 'wp_import_insert_comment', $inserted_comments[$key], $comment, $comment_post_ID, $post );

						foreach( $comment['commentmeta'] as $meta ) {
							$value = maybe_unserialize( $meta['value'] );
							add_comment_meta( $inserted_comments[$key], $meta['key'], $value );
						}

						$num_comments++;
					}
				}
				unset( $newcomments, $inserted_comments, $post['comments'] );
			}

			if ( ! isset( $post['postmeta'] ) ) {
				$post['postmeta'] = array();
			}

			if ( ! empty( $post['postmeta'] ) ) {
				foreach ( $post['postmeta'] as $meta ) {
					$key   = apply_filters( 'import_post_meta_key', $meta['key'], $post_id, $post );
					$value = false;

					if ( '_edit_last' == $key ) {
						if ( isset( $this->processed_authors[intval($meta['value'])] ) )
							$value = $this->processed_authors[intval($meta['value'])];
						else
							$key = false;
					}

					if ( $key ) {
						// export gets meta straight from the DB so could have a serialized string
						if ( ! $value ) {
							$value = maybe_unserialize( $meta['value'] );
						}

						add_post_meta( $post_id, $key, $value );
						do_action( 'import_post_meta', $post_id, $key, $value );

						// if the post has a featured image, take note of this in case of remap
						if ( '_thumbnail_id' == $key )
							$this->featured_images[$post_id] = (int) $value;
					}
				}
			}

		}
		unset( $list_post[0] );

		/**
		 * Unlink file posts
		 */
		unlink( Noo_Importer_Helpers::update_json( '', Noo_Importer_Helpers::get_name_file( 'list_posts_' ), true ) );

		/**
		 * Update process lists posts
		 */
			$data_processed_posts = Noo_Importer_Helpers::get_contents( Noo_Importer_Helpers::get_path( 'processed_posts_', '.json' ) );
			$data_processed_posts = !empty( $data_processed_posts ) ? json_decode( $data_processed_posts, true ) : array();

			if ( !is_array( $data_processed_posts ) ) {
				$data_processed_posts = array();
			}
			$processed_posts = array_merge( $this->processed_posts, $data_processed_posts );

			Noo_Importer_Helpers::update_log( sprintf( esc_html__( 'Number: %s' ), count( $processed_posts ) ), esc_html__( 'Total Processed Posts' ) );

			Noo_Importer_Helpers::update_json(
				$processed_posts,
				Noo_Importer_Helpers::get_name_file( 'processed_posts_' )
			);

		/**
		 * Update process lists featured image
		 */
			$data_featured_images = Noo_Importer_Helpers::get_contents( Noo_Importer_Helpers::get_path( 'featured_images_', '.json' ) );
			$data_featured_images = !empty( $data_featured_images ) ? json_decode( $data_featured_images, true ) : array();

			if ( !is_array( $data_featured_images ) ) {
				$data_featured_images = array();
			}
			$featured_images = array_merge( $this->featured_images, $data_featured_images );

			Noo_Importer_Helpers::update_log( sprintf( esc_html__( 'Number: %s' ) , count( $featured_images ) ), esc_html__( 'Total Featured Images' ) );

			Noo_Importer_Helpers::update_json(
				$featured_images,
				Noo_Importer_Helpers::get_name_file( 'featured_images_' )
			);

		
		/**
		 * Update list posts
		 */
		Noo_Importer_Helpers::update_json(
			Noo_Importer_Helpers::unchuck( $list_post ),
			Noo_Importer_Helpers::get_name_file( 'list_posts_' )
		);

	}

	/**
	 * If fetching attachments is enabled then attempt to create a new attachment
	 */
	function process_attachment() {
		if ( ! $this->fetch_attachments ) {
			/**
			 * Add this message to log file.
			 */
			Noo_Importer_Helpers::update_log(
				esc_html__( 
					'Fetching attachments is not enabled.', 
					'noo-landmark-core'
				), 
				esc_html__( 'Attachment Processing Error', 'noo-landmark-core' )
			);

			return 'end_task';
		}

		/**
		 * Get data posts and get list attachment
		 * @var json
		 */
		$data_attachment = Noo_Importer_Helpers::get_contents( Noo_Importer_Helpers::get_path( 'attachment_', '.json' ) );
		$base_url        = Noo_Importer_Helpers::get_contents( Noo_Importer_Helpers::get_path( 'base_site_url_', '.json' ) );
		
		if ( empty( $data_attachment ) || empty( $base_url ) ) {
			return 'end_task';
		}

		$data_attachment = json_decode( $data_attachment, true );
		$base_url        = json_decode( $base_url, true );

		if ( !is_array( $data_attachment ) ) {
			return 'end_task';
		}
		$list_attachment = array_chunk( $data_attachment, 2, false );

		if ( empty( $list_attachment[0] ) ) {
			return 'end_task';
		}

		/**
		 * Required library image
		 */
		if ( !function_exists( 'wp_generate_attachment_metadata' ) ) {
			require ( ABSPATH . 'wp-admin/includes/image.php' );
		}

		foreach ( $list_attachment[0] as $post ) {

			if ( empty( $post['guid'] ) ) continue;

			$url_image = ! empty($post['attachment_url']) ? $post['attachment_url'] : $post['guid'];

			/**
			 * if the URL is absolute, but does not contain address, then upload it assuming base_site_url
			 */
			if ( preg_match( '|^/[\w\W]+$|', $url_image ) ) {
				$url_image = rtrim( $base_url, '/' ) . $url_image;
			}

			$upload = $this->fetch_remote_file( $url_image, $post );
			
			if ( is_wp_error( $upload ) ) {
				continue;
			}

			if ( $info = wp_check_filetype( $upload['file'] ) ) {
				$post['post_mime_type'] = $info['type'];
			} else {
				/**
				 * Add this message to log file.
				 */
				Noo_Importer_Helpers::update_log(
					esc_html__( 
						'Invalid file type.', 
						'noo-landmark-core'
					), 
					esc_html__( 'Attachment Processing Error', 'noo-landmark-core' )
				);

				continue;
			}

			$post['guid'] = $upload['url'];

			// as per wp-admin/includes/upload.php
			$post_id = wp_insert_attachment( $post, $upload['file'] );
			wp_update_attachment_metadata( $post_id, wp_generate_attachment_metadata( $post_id, $upload['file'] ) );

			// remap resized image URLs, works by stripping the extension and remapping the URL stub.
			if ( preg_match( '!^image/!', $info['type'] ) ) {
				$parts     = pathinfo( $url_image );
				$name      = basename( $parts['basename'], ".{$parts['extension']}" ); // PATHINFO_FILENAME in PHP 5.2
				
				$parts_new = pathinfo( $upload['url'] );
				$name_new  = basename( $parts_new['basename'], ".{$parts_new['extension']}" );

				$this->url_remap[$parts['dirname'] . '/' . $name] = $parts_new['dirname'] . '/' . $name_new;
			}

			/**
			 * Add this message to log file.
			 */
			Noo_Importer_Helpers::update_log(
				sprintf(
					esc_html__( 
						'Upload image to media success!%s%s', 
						'noo-landmark-core'
					),
					"\n" . sprintf( esc_html__( '+ Source: %s', 'noo-landmark-core' ), $url_image ),
					"\n" . sprintf( esc_html__( '+ URL: %s', 'noo-landmark-core' ), $upload['url'] )
				), 
				esc_html__( 'Upload Attachment Image', 'noo-landmark-core' )
			);

		}

		unset( $list_attachment[0] );

		/**
		 * Unlink file attachment
		 */
		unlink( Noo_Importer_Helpers::update_json( '', Noo_Importer_Helpers::get_name_file( 'attachment_' ), true ) );
		
		/**
		 * Update list attachment
		 */
		Noo_Importer_Helpers::update_json(
			Noo_Importer_Helpers::unchuck( $list_attachment ),
			Noo_Importer_Helpers::get_name_file( 'attachment_' )
		);

		if ( !empty( $this->url_remap ) ) {

			Noo_Importer_Helpers::update_log( sprintf( esc_html__( 'Number: %s' ) , count( $this->url_remap ) ), esc_html__( 'Total Url Remap' ) );
			Noo_Importer_Helpers::update_json( $this->url_remap, Noo_Importer_Helpers::get_name_file( 'url_remap_' ) );

		}

	}

	/**
	 * Attempt to download a remote file attachment
	 */
	function fetch_remote_file( $url, $post ) {
		error_reporting(0);
		if ( empty( $post['upload_date'] ) ) {
			$post['upload_date'] = current_time( 'Y/m' );
		}
		// extract the file name and extension from the url
		$file_name = basename( $url );

		// get placeholder file in the upload dir with a unique, sanitized filename
		$upload = wp_upload_bits( $file_name, 0, '', $post['upload_date'] );
		if ( $upload['error'] )
			return new WP_Error( 'upload_dir_error', $upload['error'] );

		// fetch the remote url and write it to the placeholder file
		$headers = wp_get_http( $url, $upload['file'] );

		// request failed
		if ( ! $headers ) {
			@unlink( $upload['file'] );
			/**
			 * Add this message to log file.
			 */
			Noo_Importer_Helpers::update_log(
				esc_html__( 
					'Remote server did not respond.', 
					'noo-landmark-core'
				), 
				esc_html__( 'Import File Error', 'noo-landmark-core' )
			);

			return;
		}

		// make sure the fetch was successful
		if ( $headers['response'] != '200' ) {
			@unlink( $upload['file'] );
			/**
			 * Add this message to log file.
			 */
			Noo_Importer_Helpers::update_log(
				sprintf(
					esc_html__( 'Remote server returned error response %1$d %2$s', 'noo-landmark-core' ),
					esc_html($headers['response']),
					get_status_header_desc( $headers['response'] )
				),
				esc_html__( 'Import File Error', 'noo-landmark-core' )
			);

			return;
		}

		$filesize = filesize( $upload['file'] );

		if ( isset( $headers['content-length'] ) && $filesize != $headers['content-length'] ) {
			@unlink( $upload['file'] );

			/**
			 * Add this message to log file.
			 */
			Noo_Importer_Helpers::update_log(
				esc_html__( 
					'Remote file is incorrect size.', 
					'noo-landmark-core'
				), 
				esc_html__( 'Import File Error', 'noo-landmark-core' )
			);

			return;
		}

		if ( 0 == $filesize ) {
			@unlink( $upload['file'] );

			/**
			 * Add this message to log file.
			 */
			Noo_Importer_Helpers::update_log(
				esc_html__( 'Zero size file downloaded', 'noo-landmark-core' ), 
				esc_html__( 'Import File Error', 'noo-landmark-core' )
			);

			return;
		}

		$max_size = (int) $this->max_attachment_size();
		if ( ! empty( $max_size ) && $filesize > $max_size ) {
			@unlink( $upload['file'] );

			/**
			 * Add this message to log file.
			 */
			Noo_Importer_Helpers::update_log(
				sprintf(
					esc_html__( 'Remote file is too large, limit is %s', 'noo-landmark-core' ), 
					size_format( $max_size )
				),
				esc_html__( 'Import File Error', 'noo-landmark-core' )
			);

			return;
		}

		// keep track of the old and new urls so we can substitute them later
		$url_remap = Noo_Importer_Helpers::get_contents( Noo_Importer_Helpers::get_path( 'url_remap_', '.json' ) );
		$url_remap = !empty( $url_remap ) ? json_decode( $url_remap, true ) : array();

		$url_remap[$url]          = $upload['url'];
		$url_remap[$post['guid']] = $upload['url']; // r13735, really needed?
		// keep track of the destination if the remote url is redirected somewhere else
		if ( isset($headers['x-final-location']) && $headers['x-final-location'] != $url ) {
			$url_remap[$headers['x-final-location']] = $upload['url'];
		}

		Noo_Importer_Helpers::update_log( sprintf( esc_html__( 'Number: %s' ) , count( $url_remap ) ), esc_html__( 'Total Url Remap' ) );
		Noo_Importer_Helpers::update_json( $this->url_remap, Noo_Importer_Helpers::get_name_file( 'url_remap_' ) );

		return $upload;
	}

	/**
	 * Attempt to associate posts and menu items with previously missing parents
	 *
	 * An imported post's parent may not have been imported when it was first created
	 * so try again. Similarly for child menu items and menu items which were missing
	 * the object (e.g. post) they represent in the menu
	 */
	function backfill_parents() {
		global $wpdb;

		/**
		 * Get data posts and get info author
		 * @var json
		 */
		$post_orphans = Noo_Importer_Helpers::get_contents( Noo_Importer_Helpers::get_path( 'post_orphans_', '.json' ) );
		
		if ( empty( $post_orphans ) ) {
			return;
		}

		$post_orphans = json_decode( $post_orphans, true );

		/**
		 * Find parents for post orphans
		 */
		$processed_posts = Noo_Importer_Helpers::get_contents( Noo_Importer_Helpers::get_path( 'processed_posts_', '.json' ) );
		$processed_posts = json_decode( $processed_posts, true );

		if ( is_array( $post_orphans ) ) {
			
			foreach ( $post_orphans as $child_id => $parent_id ) {
				$local_child_id = $local_parent_id = false;
				if ( isset( $processed_posts[$child_id] ) ) {
					$local_child_id = $processed_posts[$child_id];
				}
				if ( isset( $processed_posts[$parent_id] ) ) {
					$local_parent_id = $processed_posts[$parent_id];
				}

				if ( $local_child_id && $local_parent_id ) {
					$wpdb->update( $wpdb->posts, array( 'post_parent' => $local_parent_id ), array( 'ID' => $local_child_id ), '%d', '%d' );
				}
			}

		}

		/**
		 * All other posts/terms are imported, retry menu items with missing associated object
		 */
		$missing_menu_items = Noo_Importer_Helpers::get_contents( Noo_Importer_Helpers::get_path( 'missing_menu_items_', '.json' ) );
		$missing_menu_items = json_decode( $missing_menu_items, true );
		
		if ( !empty( $missing_menu_items ) && is_array( $missing_menu_items ) ) {
			foreach ( $missing_menu_items as $item ) {
				Noo_Importer_Menu::menu_item( $item );
			}
		}

		/**
		 * Find parents for menu item orphans
		 */
		$menu_item_orphans = Noo_Importer_Helpers::get_contents( Noo_Importer_Helpers::get_path( 'menu_item_orphans_', '.json' ) );
		$menu_item_orphans = json_decode( $menu_item_orphans, true );
		if ( !empty( $menu_item_orphans ) && is_array( $menu_item_orphans ) ) {
			$processed_menu_items = Noo_Importer_Helpers::get_contents( Noo_Importer_Helpers::get_path( 'processed_menu_items_', '.json' ) );
			$processed_menu_items = json_decode( $processed_menu_items, true );
			foreach ( $menu_item_orphans as $child_id => $parent_id ) {
				$local_child_id = $local_parent_id = 0;
				if ( isset( $processed_menu_items[$child_id] ) ) {
					$local_child_id = $processed_menu_items[$child_id];
				}
				if ( isset( $processed_menu_items[$parent_id] ) ) {
					$local_parent_id = $processed_menu_items[$parent_id];
				}

				if ( $local_child_id && $local_parent_id ) {
					update_post_meta( $local_child_id, '_menu_item_menu_item_parent', (int) $local_parent_id );
				}
			}
		}
	}

	/**
	 * Use stored mapping information to update old attachment URLs
	 */
	function backfill_attachment_urls() {
		global $wpdb;
		$url_remap = Noo_Importer_Helpers::get_contents( Noo_Importer_Helpers::get_path( 'url_remap_', '.json' ) );
		$url_remap = json_decode( $url_remap, true );

		if ( !is_array( $url_remap ) ) return;

		// make sure we do the longest urls first, in case one is a substring of another
		uksort( $url_remap, array(&$this, 'cmpr_strlen') );

		foreach ( $url_remap as $from_url => $to_url ) {
			// remap urls in post_content
			$wpdb->query( $wpdb->prepare("UPDATE {$wpdb->posts} SET post_content = REPLACE(post_content, %s, %s)", $from_url, $to_url) );
			// remap enclosure urls
			$result = $wpdb->query( $wpdb->prepare("UPDATE {$wpdb->postmeta} SET meta_value = REPLACE(meta_value, %s, %s) WHERE meta_key='enclosure'", $from_url, $to_url) );
		}
	}

	/**
	 * Update _thumbnail_id meta to new, imported attachment IDs
	 */
	function remap_featured_images() {
		// cycle through posts that have a featured image
		$featured_images = Noo_Importer_Helpers::get_contents( Noo_Importer_Helpers::get_path( 'featured_images_', '.json' ) );
		$featured_images = json_decode( $featured_images, true );

		if ( is_array( $featured_images ) ) {

			$processed_posts = Noo_Importer_Helpers::get_contents( Noo_Importer_Helpers::get_path( 'processed_posts_', '.json' ) );
			$processed_posts = json_decode( $processed_posts, true );
			foreach ( $featured_images as $post_id => $value ) {
				if ( isset( $processed_posts[$value] ) ) {
					$new_id = $processed_posts[$value];
					// only update if there's a difference
					if ( $new_id != $value ) {
						update_post_meta( $post_id, '_thumbnail_id', $new_id );
					}
				}
			}

		}
	}

	/**
	 * Decide if the given meta key maps to information we will want to import
	 *
	 * @param string $key The meta key to check
	 * @return string|bool The key if we do want to import, false if not
	 */
	function is_valid_meta_key( $key ) {
		// skip attachment metadata since we'll regenerate it from scratch
		// skip _edit_lock as not relevant for import
		if ( in_array( $key, array( '_wp_attached_file', '_wp_attachment_metadata', '_edit_lock' ) ) ) {
			return false;
		}
		return $key;
	}

	/**
	 * Decide whether or not the importer should attempt to download attachment files.
	 * Default is true, can be filtered via import_allow_fetch_attachments. The choice
	 * made at the import options screen must also be true, false here hides that checkbox.
	 *
	 * @return bool True if downloading attachments is allowed
	 */
	function allow_fetch_attachments() {
		return apply_filters( 'import_allow_fetch_attachments', true );
	}

	/**
	 * Decide what the maximum file size for downloaded attachments is.
	 * Default is 0 (unlimited), can be filtered via import_attachment_size_limit
	 *
	 * @return int Maximum attachment file size to import
	 */
	function max_attachment_size() {
		return apply_filters( 'import_attachment_size_limit', 0 );
	}

	/**
	 * Added to http_request_timeout filter to force timeout at 60 seconds during import
	 * @return int 60
	 */
	

	// return the difference in length between two strings
	function cmpr_strlen( $a, $b ) {
		return strlen($b) - strlen($a);
	}

	// --- [ WIDGET ]

	function available_widgets() {

		global $wp_registered_widget_controls;

		$widget_controls = $wp_registered_widget_controls;

		$available_widgets = array();

		foreach ( $widget_controls as $widget ) {

			if ( ! empty( $widget['id_base'] ) && ! isset( $available_widgets[$widget['id_base']] ) ) { // no dupes

				$available_widgets[$widget['id_base']]['id_base'] = $widget['id_base'];
				$available_widgets[$widget['id_base']]['name'] = $widget['name'];

			}

		}

		return apply_filters( 'available_widgets', $available_widgets );

	}
	/**
	 * Process import file
	 *
	 * This parses a file and triggers importation of its widgets.
	 *
	 * @since 0.3
	 * @param string $file Path to ._noo file uploaded
	 * @global string $import_data_results
	 */
	function import_widgets( $file ) {

		global $import_data_results;

		// File exists?
		// if ( ! file_exists( $file ) ) {
		// 	wp_die(
		// 		esc_html__( 'Import file could not be found. Please try again.', 'noo-landmark-core' ),
		// 		'',
		// 		array( 'back_link' => true )
		// 	);
		// }

		// Get file contents and decode
		$data = Noo_Importer_Helpers::get_contents( $file );
		$data = json_decode( $data );

		// Delete import file
		// unlink( $file );

		// Import the widget data
		// Make results available for display on import/export page
		$import_data_results = $this->import_data_widgets( $data );

	}

	/**
	 * Import widget JSON data
	 *
	 * @since 0.4
	 * @global array $wp_registered_sidebars
	 * @param object $data JSON widget data from ._noo file
	 * @return array Results array
	 */
	function import_data_widgets( $data ) {

		global $wp_registered_sidebars;

		// Have valid data?
		// If no data or could not decode
		if ( empty( $data ) || ! is_object( $data ) ) {
			/**
			 * Add this message to log file.
			 */
			Noo_Importer_Helpers::update_log(
				esc_html__( 'Import data could not be read. Please try a different file.', 'noo-landmark-core' ), 
				esc_html__( 'Import Data Widgets', 'noo-landmark-core' )
			);
			return false;
		}

		/**
		 * Hook before import
		 */
		do_action( 'noo_import/before_import_data_widgets' );

		$data = apply_filters( 'noo_import/import_data_widgets', $data );

		/**
		 * Get all available widgets site supports
		 */
		$available_widgets = $this->available_widgets();

		/**
		 * Get all existing widget instances
		 */
		$widget_instances = array();
		foreach ( $available_widgets as $widget_data ) {
			$widget_instances[$widget_data['id_base']] = get_option( 'widget_' . $widget_data['id_base'] );
		}

		/**
		 * Begin results
		 */
		$results = array();

		/**
		 * Loop import data's sidebars
		 */
		foreach ( $data as $sidebar_id => $widgets ) {

			// Skip inactive widgets
			// (should not be in export file)
			if ( 'wp_inactive_widgets' == $sidebar_id ) {
				continue;
			}

			// Check if sidebar is available on this site
			// Otherwise add widgets to inactive, and say so
			if ( isset( $wp_registered_sidebars[$sidebar_id] ) ) {
				$sidebar_available    = true;
				$use_sidebar_id       = $sidebar_id;
				$sidebar_message_type = 'success';
				$sidebar_message      = '';
			} else {
				$sidebar_available    = false;
				$use_sidebar_id       = 'wp_inactive_widgets'; // add to inactive if sidebar does not exist in theme
				$sidebar_message_type = 'error';
				$sidebar_message      = esc_html__( 'Sidebar does not exist in theme (using Inactive)', 'noo-landmark-core' );
			}

			// Result for sidebar
			$results[$sidebar_id]['name']         = ! empty( $wp_registered_sidebars[$sidebar_id]['name'] ) ? $wp_registered_sidebars[$sidebar_id]['name'] : $sidebar_id; // sidebar name if theme supports it; otherwise ID
			$results[$sidebar_id]['message_type'] = $sidebar_message_type;
			$results[$sidebar_id]['message']      = $sidebar_message;
			$results[$sidebar_id]['widgets']      = array();

			// Loop widgets
			foreach ( $widgets as $widget_instance_id => $widget ) {

				$fail = false;

				// Get id_base (remove -# from end) and instance ID number
				$id_base            = preg_replace( '/-[0-9]+$/', '', $widget_instance_id );
				$instance_id_number = str_replace( $id_base . '-', '', $widget_instance_id );

				// Does site support this widget?
				if ( ! $fail && ! isset( $available_widgets[$id_base] ) ) {
					$fail                = true;
					$widget_message_type = 'error';
					$widget_message      = esc_html__( 'Site does not support widget', 'noo-landmark-core' ); // explain why widget not imported
				}

				// Filter to modify settings object before conversion to array and import
				// Leave this filter here for backwards compatibility with manipulating objects (before conversion to array below)
				// Ideally the newer _noo_widget_settings_array below will be used instead of this
				$widget = apply_filters( '_noo_widget_settings', $widget ); // object

				// Convert multidimensional objects to multidimensional arrays
				// Some plugins like Jetpack Widget Visibility store settings as multidimensional arrays
				// Without this, they are imported as objects and cause fatal error on Widgets page
				// If this creates problems for plugins that do actually intend settings in objects then may need to consider other approach: https://wordpress.org/support/topic/problem-with-array-of-arrays
				// It is probably much more likely that arrays are used than objects, however
				$widget = json_decode( json_encode( $widget ), true );

				// Filter to modify settings array
				// This is preferred over the older _noo_widget_settings filter above
				// Do before identical check because changes may make it identical to end result (such as URL replacements)
				$widget = apply_filters( '_noo_widget_settings_array', $widget );

				// Does widget with identical settings already exist in same sidebar?
				if ( ! $fail && isset( $widget_instances[$id_base] ) ) {

					// Get existing widgets in this sidebar
					$sidebars_widgets = get_option( 'sidebars_widgets' );
					$sidebar_widgets  = isset( $sidebars_widgets[$use_sidebar_id] ) ? $sidebars_widgets[$use_sidebar_id] : array(); // check Inactive if that's where will go

					// Loop widgets with ID base
					$single_widget_instances = ! empty( $widget_instances[$id_base] ) ? $widget_instances[$id_base] : array();
					foreach ( $single_widget_instances as $check_id => $check_widget ) {

						// Is widget in same sidebar and has identical settings?
						if ( in_array( "$id_base-$check_id", $sidebar_widgets ) && (array) $widget == $check_widget ) {

							$fail                = true;
							$widget_message_type = 'warning';
							$widget_message      = esc_html__( 'Widget already exists', 'noo-landmark-core' ); // explain why widget not imported

							break;

						}

					}

				}

				// No failure
				if ( ! $fail ) {

					// Add widget instance
					$single_widget_instances   = get_option( 'widget_' . $id_base ); // all instances for that widget ID base, get fresh every time
					$single_widget_instances   = ! empty( $single_widget_instances ) ? $single_widget_instances : array( '_multiwidget' => 1 ); // start fresh if have to
					$single_widget_instances[] = $widget; // add it

						// Get the key it was given
						end( $single_widget_instances );
						$new_instance_id_number = key( $single_widget_instances );

						// If key is 0, make it 1
						// When 0, an issue can occur where adding a widget causes data from other widget to load, and the widget doesn't stick (reload wipes it)
						if ( '0' === strval( $new_instance_id_number ) ) {
							$new_instance_id_number = 1;
							$single_widget_instances[$new_instance_id_number] = $single_widget_instances[0];
							unset( $single_widget_instances[0] );
						}

						// Move _multiwidget to end of array for uniformity
						if ( isset( $single_widget_instances['_multiwidget'] ) ) {
							$multiwidget = $single_widget_instances['_multiwidget'];
							unset( $single_widget_instances['_multiwidget'] );
							$single_widget_instances['_multiwidget'] = $multiwidget;
						}

						// Update option with new widget
						update_option( 'widget_' . $id_base, $single_widget_instances );

					// Assign widget instance to sidebar
					$sidebars_widgets = get_option( 'sidebars_widgets' ); // which sidebars have which widgets, get fresh every time
					$new_instance_id = $id_base . '-' . $new_instance_id_number; // use ID number from new widget instance
					$sidebars_widgets[$use_sidebar_id][] = $new_instance_id; // add new instance to sidebar
					update_option( 'sidebars_widgets', $sidebars_widgets ); // save the amended data

					// After widget import action
					$after_widget_import = array(
						'sidebar'           => $use_sidebar_id,
						'sidebar_old'       => $sidebar_id,
						'widget'            => $widget,
						'widget_type'       => $id_base,
						'widget_id'         => $new_instance_id,
						'widget_id_old'     => $widget_instance_id,
						'widget_id_num'     => $new_instance_id_number,
						'widget_id_num_old' => $instance_id_number
					);
					do_action( '_noo_after_widget_import', $after_widget_import );

					// Success message
					if ( $sidebar_available ) {
						$widget_message_type = 'success';
						$widget_message = esc_html__( 'Imported', 'noo-landmark-core' );
					} else {
						$widget_message_type = 'warning';
						$widget_message = esc_html__( 'Imported to Inactive', 'noo-landmark-core' );
					}

				}

				// Result for widget instance
				$results[$sidebar_id]['widgets'][$widget_instance_id]['name'] = isset( $available_widgets[$id_base]['name'] ) ? $available_widgets[$id_base]['name'] : $id_base; // widget name or ID if name not available (not supported by site)
				$results[$sidebar_id]['widgets'][$widget_instance_id]['title'] = ! empty( $widget['title'] ) ? $widget['title'] : esc_html__( 'No Title', 'noo-landmark-core' ); // show "No Title" if widget instance is untitled
				$results[$sidebar_id]['widgets'][$widget_instance_id]['message_type'] = $widget_message_type;
				$results[$sidebar_id]['widgets'][$widget_instance_id]['message'] = $widget_message;

			}

		}

		/**
		 * Hook after import
		 */
		do_action( 'noo_import/after_import_data_widgets' );

		// Return results
		return apply_filters( 'noo_import/import_data_results', $results );

	}

	/**
	 * Load import option
	 */
	function process_option( $file ) {
		$file_contents     = Noo_Importer_Helpers::get_contents( $file );
		$data_options      = json_decode( $file_contents, true );
		$options_to_import = $this->get_whitelist_options();
		
		$hash              = '048f8580e913efe41ca7d402cc51e848';

		// Allow others to prevent their options from importing
			// $blacklist = $this->get_blacklist_options();
		if ( empty( $options_to_import ) ) return false;
		
		foreach ( (array) $options_to_import as $option_name ) {
			if ( isset( $data_options['options'][ $option_name ] ) ) {
				
				// we're going to use a random hash as our default, to know if something is set or not
				// $old_value    = get_option( $option_name, $hash );
				$old_value    = $data_options['options'][ $option_name ];
				$option_value = maybe_unserialize( $old_value );

				/**
				 * Process widget nav menu
				 */
				if ( $option_name === 'widget_nav_menu' ) {

					$data_widget_menu = get_option( 'widget_nav_menu' );

					foreach ( $data_widget_menu as $index_widget => $widget ) {
						/**
						 * Skip element _multiwidget
						 */
						if ( $widget === '_multiwidget' ) {
							continue;
						}

						if ( !empty( $widget['title'] ) ) {
							/**
							 * Find data widget item
							 */
								$find_widget = wp_get_nav_menu_object( $widget['title'] );
								if ( empty( $find_widget ) ) {
									continue;
								}
								$widget_id   = $find_widget->term_id;

							/**
							 * Reupdate value new for list widget
							 */
							$data_widget_menu[$index_widget]['nav_menu'] = $widget_id;

						}
					}

					$option_value = $data_widget_menu;

				}

				if ( in_array( $option_name, $data_options['no_autoload'] ) ) {
					delete_option( $option_name );
					add_option( $option_name, $option_value, '', 'no' );
				} else {
					update_option( $option_name, $option_value );
				}
			}

			$nav_menu_locations = get_theme_mod( 'nav_menu_locations', array() );

			if( $primary_menu = wp_get_nav_menu_object( 'Menu Primary' ) ) {
				$nav_menu_locations['primary'] = $primary_menu->term_id;
				/**
				 * Add this message to log file.
				 */
				Noo_Importer_Helpers::update_log(
					sprintf(
						esc_html__( 'ID Menu Primary is: %s.', 'noo-landmark-core' ),
						$primary_menu->term_id
					),
					esc_html__( 'Nav Menu Locations', 'noo-landmark-core' )
				);
			}

			if( $main_menu_left = wp_get_nav_menu_object( 'Main Menu Left' ) ) {
				$nav_menu_locations['primary-left'] = $main_menu_left->term_id;
				/**
				 * Add this message to log file.
				 */
				Noo_Importer_Helpers::update_log(
					sprintf(
						esc_html__( 'ID Main Menu Left is: %s.', 'noo-landmark-core' ),
						$main_menu_left->term_id
					),
					esc_html__( 'Nav Menu Locations', 'noo-landmark-core' )
				);
			}

			if( $main_menu_right = wp_get_nav_menu_object( 'Main Menu Right' ) ) {
				/**
				 * Add this message to log file.
				 */
				Noo_Importer_Helpers::update_log(
					sprintf(
						esc_html__( 'ID Main Menu Right is: %s.', 'noo-landmark-core' ),
						$main_menu_right->term_id
					),
					esc_html__( 'Nav Menu Locations', 'noo-landmark-core' )
				);
				$nav_menu_locations['primary-right'] = $main_menu_right->term_id;
			}

			set_theme_mod( 'nav_menu_locations', $nav_menu_locations );
		}
	} 

	/**
	 * Load import revslider
	 */
	function import_RevSlider( $source_revslider ) {
		
		if ( file_exists( $source_revslider ) ) {
			$revslider = new RevSlider();
			$revslider->importSliderFromPost( true, true, $source_revslider );
		}
		
	}

	/**
	 * Get an array of blacklisted options which we never want to import.
	 *
	 * @return array
	 */
	private function get_blacklist_options() {
		return apply_filters( 'options_import_blacklist', array() );
	}

	/**
	 * Get an array of known options which we would want checked by default when importing.
	 *
	 * @return array
	 */
	private function get_whitelist_options() {
		return apply_filters( 'options_import_whitelist', array(
			'nav_menu_options',
			'page_for_posts',
			'page_on_front',
			'show_on_front',
			'widget_nav_menu',
			'theme_mods_noo-landmark',
			'theme_mods_noo-landmark-child',
			'mc4wp_default_form_id'
		) );
	}


}

function noo_load_import_demo() {

	new Noo_Import_Demo();

}
add_action( 'init', 'noo_load_import_demo', 1 );
