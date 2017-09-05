<?php
/**
 * Display notices in admin.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( !class_exists( 'Noo_LandMark_Setup_Install' ) ) :

class Noo_LandMark_Setup_Install {

	private $product_id = 'noo-landmark';

	private $install_option_group = 'noo_option_install_group';

	private $install_option_name = 'noo_option_install_name';

	private $install_section_id = 'noo_option_section_id';

	private $option_metabox = array();

    public function __construct() {
        add_action( 'plugins_loaded', array( $this, 'my__construct' ) );
    }

	public function my__construct() {

		if ( current_user_can( 'manage_options' ) ) {

			add_action( 'admin_notices', array( $this, 'notice_html_install' ) );
			add_action( 'admin_menu', array( $this, 'admin_menus' ) );

			// -- Call setting fields
				add_action('admin_init', array( $this, 'noo_settings_fields'));
			// -- set ajax
				add_action( 'wp_ajax_noo_setup', array( $this, 'noo_setup' ) );
			if ( isset( $_GET['page'] ) == 'noo-setup' ) :

				add_action( 'admin_head', array( $this, 'load_enqueue_style_setup' ) );
				add_action( 'admin_enqueue_scripts', array( $this, 'load_enqueue_script_setup' ) );

			endif;

            require dirname( __FILE__ ) . '/noo-import.php';

		}
	}

	public function load_enqueue_style_setup() {

		?>
		<style type="text/css" media="screen">
			mark.yes {
    			background-color: transparent;
    			color: #7ad03a;
			}
			mark.error {
			    background-color: transparent;
			    color: #a00;
			}
			mark.error span:hover {
				cursor: pointer;
			}
			table.widefat {
				margin-bottom: 20px;
			}
		</style>
		<?php

	}

	public function load_enqueue_script_setup() {

		wp_register_script( 'setup-install', NOO_PLUGIN_ASSETS_URI . '/js/min/noo.setup.install.min.js', array( 'jquery'), null, true );
		wp_enqueue_script('setup-install');

		wp_register_script( 'setup-install-demo', NOO_ADMIN_ASSETS_URI . '/js/noo.setup.install.demo.js' );
		wp_enqueue_script('setup-install-demo');

		wp_register_style( 'setup-style', NOO_PLUGIN_ASSETS_URI . '/css/noo-setup.css' );
		wp_enqueue_style( 'setup-style' );


		wp_localize_script( 'setup-install', 'nooSetup', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
		wp_localize_script( 'setup-install-demo', 'nooSetupDemo', 
			array( 
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'notice'   => esc_html__( 'Do you want to continue this action?', 'noo-landmark-core' ),
				'warning'  => esc_html__( 'Please waiting, not exit page.', 'noo-landmark-core' ),
				'security' => wp_create_nonce( 'noo-install-demo' ),
				'img_ajax_load' => NOO_PLUGIN_ASSETS_URI . '/images/ajax-loader.gif'
			) 
		);

	}
	
	// -- Notice html
	
	public function notice_html_install() {
		if ( !$this->noo_landmark_func_get_option('disable_notice_install') && !isset($_GET['page']) == 'noo-setup' ) :

		?>
		<div id="message" class="updated notice is-dismissible">
			<p><strong><?php echo sprintf( esc_html__( 'Welcome to %s,', 'noo-landmark-core'), wp_get_theme($this->product_id)->get('Name') ); ?></strong></p>
			<p><?php echo esc_html__( 'If it is the first time you install this theme, you should go and check the basic setting.', 'noo-landmark-core'); ?></p>
			<p class="submit">
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=noo-setup' ) ); ?>" class="button-primary">
					<?php echo esc_html__( 'Go to Quick Setup', 'noo-landmark-core' ); ?>
				</a>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=noo-setup&action=skip' ) ); ?>" class="button">
					<?php echo esc_html__( 'Skip Setup', 'noo-landmark-core' ); ?>
				</a>
			</p>
		</div>
		<?php

		endif;
	}

	// -- Create page
		public function admin_menus() {
			add_theme_page(
				esc_html__( 'Noo Settings', 'noo-landmark-core' ),
				esc_html__( 'Noo Settings', 'noo-landmark-core' ),
				'edit_theme_options', 
				'noo-setup',
				array( $this, 'noo_page_setup' )
			);
		}

	// -- Processsing setup
		public function noo_setup() {
			global $wpdb;

			// ----
			
				$page['title'] = $_POST['title'];
				$page['content'] = isset( $_POST['content'] ) && !empty( $_POST['content'] ) ? $_POST['content'] : '';
				$page['page_template'] = isset( $_POST['page_template'] ) && !empty( $_POST['page_template'] ) ? $_POST['page_template'] : 'default';
				$page['setting_group'] = isset( $_POST['setting_group'] ) && !empty( $_POST['setting_group'] ) ? $_POST['setting_group'] : '';
				$page['setting_key'] = isset( $_POST['setting_key'] ) && !empty( $_POST['setting_key'] ) ? $_POST['setting_key'] : '';

			// -----

				$post_data = array(
					'post_title' => $page['title'],
					'post_content'  => $page['content'],
					'post_status' => 'publish',
					'post_type'   => 'page',
					'post_author' => get_current_user_id()
				);

				$id_page = wp_insert_post( $post_data ); // -- Insert post
			
				if( !is_wp_error( $id_page ) ) {
					update_post_meta($id_page, '_wp_page_template',  $page['page_template']); //- Set page template
					if( !empty( $page['setting_key'] ) ) {
						if( !empty( $page['setting_group'] ) ) {
							$setting_group = get_option( $page['setting_group'] );
							$setting_group[ $page['setting_key'] ] = $id_page;
							update_option( $page['setting_group'], $setting_group );
						} else {
							$setting_value = get_option( $page['setting_key'] );
							update_option( $page['setting_key'], $id_page );
						}
					}

					$post = get_post($id_page);
					echo json_encode( array( 'id' => esc_html__( 'Done', 'noo-landmark-core'), 'slug' => $post->post_name ) );
				}
				exit;
		}

	// -- Add option
		public function noo_add_option( $name ) {
			$options = array_merge( get_option( 'noo_setup', array() ), $name );
			update_option( 'noo_setup', $options );
		}

	// -- Get option
		public function noo_landmark_func_get_option( $name ) {
			$options = get_option( 'noo_setup' );
			return $options[$name];
		}

	// -- Tab menu
		public function noo_tab_menu( $current = 'general' ) {

			$tabs = array(
		        'general'   => esc_html__( 'Quick Setup', 'noo-landmark-core' ), 
		        'import_demo'  => esc_html__( 'Import Demo', 'noo-landmark-core' )
		    );
		    $html =  '<h2 class="nav-tab-wrapper">';
		    foreach( $tabs as $tab => $name ) :
		        
		        $class = ($tab == $current) ? 'nav-tab-active' : '';
		        $html .=  '<a class="nav-tab ' . $class . '" href="?page=noo-setup&tab=' . $tab . '">' . $name . '</a>';
		    
		    endforeach;
		    $html .= '</h2>';
		    echo $html;

		} 

	// -- Page setup
		public function noo_page_setup() {
		    
		    if ( isset( $_GET['page'] ) == 'noo-setup' ) :

		    	if ( isset( $_GET['tab'] ) ) :

		    		$tab = $_GET['tab'];

		    	else :

		    		$tab = 'general';

		    	endif;

		    	$this->noo_tab_menu( $tab );

		    	switch ( $tab ) {
		    		case 'general':
		    			$this->noo_general_options();
		    			break;
		    		case 'import_demo':
		    			$this->noo_import_demo_options();
		    			break;
		    		default:
		    			$this->noo_general_options();
		    			break;
		    	}

		    endif;
		}

	// -- OPTION
	
		// -- General options
		public function noo_general_options() {
			if ( isset( $_GET['action'] ) == 'skip' ) :
				$this->noo_add_option( array( 'disable_notice_install' => true ) );
				wp_redirect( admin_url() );
				die;
			endif;
			if ( isset( $_POST['license_key'] ) ) :
				$value_license = array(
					'license_key' => $_POST['license_key'],
					'email' => $_POST['email'],
				);
				update_option( $this->product_id . '-license-settings', $value_license );

				echo '
					<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"> 
						<p><strong>Settings saved.</strong></p>
						<button type="button" class="notice-dismiss">
							<span class="screen-reader-text">Dismiss this notice.</span>
						</button>
					</div>';

				$this->noo_add_option( array( 'disable_notice_install' => true ) );

			endif;
			echo '<div class="wrap">';
	        echo '<form method="post">';

			$this->noo_option_client();
			//$this->noo_option_page();

			echo '<p>';
			submit_button('', 'primary', '', false);
			echo '&nbsp;&nbsp;';
			echo '<a href="' . esc_url( admin_url( 'admin.php?page=noo-setup&action=skip' ) ) . '" class="button">';
			echo esc_html__( 'Cancel', 'noo-landmark-core' );
			echo '</a>';
			echo '</p>';

			echo '</form>';
			echo '</div>';

		}

			// -- Option client
			public function noo_option_client() {
				$settings_field_name = $this->get_product_field_name();
				$settings_group_id = $this->product_id . '-license-settings-group';
            	$options = get_option( $settings_field_name );
				?>
					<table class="widefat" cellspacing="0" id="client">
                        <thead>
                        <tr>
                            <th colspan="3" data-export-label="<?php esc_html_e( 'Automatic Update Theme', 'noo-landmark-core' ); ?>">
                                <strong>
                                    <?php esc_html_e( 'Automatic Update Theme', 'noo-landmark-core' ); ?>
                                </strong>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td data-export-label="<?php esc_html_e( 'Purchase Code', 'noo-landmark-core' ); ?>">
                                <?php esc_html_e( 'ThemeForest Purchase Code:', 'noo-landmark-core' ); ?><br/>
                                <small><?php esc_html_e( '(Optional)', 'noo-landmark-core' ); ?></small>
                            </td>
                            <td class="help">
                                <a href="#" title="<?php _e( 'This purchase code makes sure that our server recognizes your website thus allows you to download and install the new updates.', 'noo-landmark-core' ); ?>" class="help_tip"><span class="dashicons dashicons-editor-help"></span></a>
                            </td>
                            <td>
                                <input type='text' name='license_key' value='<?php echo esc_attr( $options['license_key'] ); ?>' class='regular-text'>
                                <input type='hidden' name='email' value='<?php echo str_replace( 'http://', '', home_url( )); ?>' class='regular-text'>
                                <span><?php echo sprintf( wp_kses( __( '<a target="_blank" href="%s">How to get License key?</a>', 'noo-landmark-core' ), noo_allowed_html() ), 'http://support.nootheme.com/wp-content/uploads/2015/07/HowToGetPurchaseCode.png' ) ?></span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
				<?php
			}

			// -- Option page
			public function noo_option_page() {
				
				?>
					<table class="widefat" cellspacing="0">
						<thead>
							<tr>
								<th colspan="5" data-export-label="<?php echo esc_html__( 'Required Pages', 'noo-landmark-core' ); ?>"><?php echo esc_html__( 'Required Pages', 'noo-landmark-core' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
								global $wpdb;

								$list_pages = array(
									array(
										'title'         => esc_html__( 'Front Page' , 'noo-landmark-core'),
										'content'       => '',
										'page_template' => '',
										'help'          => esc_html__( 'The Homepage', 'noo-landmark-core'),
										'setting'       => array( 
																'group' => '',
																'key'   => 'show_on_front',
																'value' => 'page',
																'url'   => admin_url( 'options-reading.php' )
															),
									),
									array(
										'title'         => esc_html__( 'Member' , 'noo-landmark-core'),
										'content'       => '[noo_member_account]',
										'page_template' => '',
										'help'          => esc_html__( 'The main page for all the action of Employer and Candidate', 'noo-landmark-core'),
										'setting'       => array( 
																'group' => 'noo_member',
																'key'   => 'manage_page_id',
																'url'   => admin_url( 'edit.php?post_type=noo_job&page=manage_noo_job&tab=member' )
															),
									),
									array(
										'title'         => esc_html__( 'Packages' , 'noo-landmark-core'),
										'content'       => '[noo_job_package_list]',
										'page_template' => '',
										'help'          => esc_html__( 'The page for Employer to choose Job Package', 'noo-landmark-core'),
										'setting'       => array( 
																'group' => 'job_package',
																'key'   => 'package_page_id',
																'url'   => admin_url( 'edit.php?post_type=noo_job&page=manage_noo_job&tab=job_package' )
															),
									),
									array(
										'title'         => esc_html__( 'Post Job' , 'noo-landmark-core'),
										'content'       => '',
										'page_template' => 'page-post-job.php',
										'help'          => esc_html__( 'The page for Job posting', 'noo-landmark-core'),
										'setting'       => array(),
									),
									array(
										'title'         => esc_html__( 'Post Resume' , 'noo-landmark-core'),
										'content'       => '',
										'page_template' => 'page-post-resume.php',
										'help'          => esc_html__( 'The page for Resume posting', 'noo-landmark-core'),
										'setting'       => array(),
									),
								);

								if( !Noo_Job::use_woocommerce_package() ) {
									unset($list_pages[2]);
								}

								foreach ($list_pages as $list_page => $page) :
									echo '<tr>';

									$get_id = null;
									$get_page = null;

									// Check the setting first
									$setting = $page['setting'];
									$setting_value = null;
									$setting_result = '';
									if( isset( $setting ) && !empty( $setting ) && is_array($setting) && !empty( $setting['key'] ) ) :
										if( !empty( $setting['group'] ) ) {
											$setting_group = get_option( $setting['group'] );
											$setting_value = is_array( $setting_group ) && isset( $setting_group[ $setting['key'] ] ) ? $setting_group[ $setting['key'] ] : null;
										} else {
											$setting_value = get_option( $setting['key'] );
										}

										if( $setting_value == null ) :
											$setting_result = 'missing';
										else :
											$setting_result = 'false';
											if( isset( $setting['value'] ) && $setting['value'] == $setting_value ) {
												$setting_result = 'true';
											} elseif( is_numeric( $setting_value ) ) {
												$get_id = absint( $setting_value );
												$get_page = get_post( $get_id );
												if( !empty( $get_page ) && $get_page->post_type == 'page' ) {

													// There's setting, check if the setting satisfy the other condition: page template and page content.
													if( ( isset( $page['page_template'] ) && !empty( $page['page_template'] ) && $page['page_template'] == noo_landmark_func_get_post_meta( $get_id, '_wp_page_template' ) ) 
														|| ( isset( $page['content'] ) && !empty( $page['content'] ) && strpos( $get_page->post_content, $page['content'] ) !== false  ) ) {
														$setting_result = 'true';
													}
												}
											}
										endif;


										if( !empty( $get_id ) ) {
											echo "<td data-export-label='{$page['title']}'>";
											echo "<a href='" . get_edit_post_link( $get_id ) . "' title='{$page['title']}' target='_blank'>{$page['title']}</a>";
											echo "</td>";
										} else {
											echo "<td data-export-label='{$page['title']}'>{$page['title']}</td>";
										}
										if( isset( $page['help'] ) && !empty( $page['help'] ) ) {
											echo '<td class="help">';
											echo '	<a href="#" class="help_tip" title="' . $page['help'] . '">[?]</a>';
											echo '</td>';
										}

										echo '<td>';
										if( $setting_result == 'missing' ) {
											echo "	<mark class='error'>" . esc_html__( 'Missing setting', 'noo-landmark-core' ) . "</mark>";
										} elseif( $setting_result == 'false' ) {
											echo "<mark class='error'>" . esc_html__( 'Wrong', 'noo-landmark-core' );
											if( !empty( $page['content'] ) ) {
												echo ' - ' . sprintf( esc_html__( 'Page should contains %s', 'noo-landmark-core' ), $page['content'] );
											} elseif ( !empty( $page['page_template'] ) ) {
												echo ' - ' . sprintf( esc_html__( 'Page should have template %s', 'noo-landmark-core' ), $page['page_template'] );
											}
											echo "</mark>";
										} else {
											echo "<mark class='yes'>" . esc_html__( 'Done', 'noo-landmark-core');
											if( !empty( $get_id ) && !empty( $get_page ) ) {
												echo " - /{$get_page->post_name}/";
											}
											echo '</mark>';
										}
										echo '</td>';

										echo '<td>';
										if( $setting_result !== 'true' && ( !empty( $page['content'] ) || !empty( $page['page_template'] ) ) ) {
											echo '	<div class="button button-primary">';
											echo '		<span class="correct-setting" data-title="' . $page['title'] . '" data-content="' . $page['content'] . '" data-page-template="' . $page['page_template'] . '" data-setting-group="' . $setting['group'] . '" data-setting-key="' . $setting['key'] . '">' . esc_html__( 'Correct now', 'noo-landmark-core') . '</span>';
											echo '	</div>';
										}
										echo '</td>';
										echo '<td>';
										if( $setting_result != 'true' && isset( $setting['url']) && !empty( $setting['url'] ) ) {
											echo '	<div class="button">';
											echo "		<a href='{$setting['url']}' title='{$page['title']}' target='_blank'>" . esc_html__('Edit setting', 'noo-landmark-core') . "</a>";
											echo '	</div>';
										}
										echo '</td>';

									else :
										if ( !empty($page['page_template']) ) :
											$get_id = $this->_get_page_id_by_template($page['page_template']);
											$get_page = !empty( $get_id ) ? get_post( $get_id ) : null;
											
											if( !empty( $get_id ) && !empty( $get_page ) ) {

												echo "<td data-export-label='{$page['title']}'>";
												echo "<a href='" . get_edit_post_link( $get_id ) . "' title='{$page['title']}' target='_blank'>{$page['title']}</a>";
												echo "</td>";
												if( isset( $page['help'] ) && !empty( $page['help'] ) ) {
													echo '<td class="help">';
													echo '	<a href="#" class="help_tip" title="' . $page['help'] . '">[?]</a>';
													echo '</td>';
												}
												echo '<td>';
												echo "	<mark class='yes'>" . esc_html__( 'Done', 'noo-landmark-core') . " - /{$get_page->post_name}/</mark>";
												echo '</td>';
											} else {
												echo "<td data-export-label='{$page['title']}'>{$page['title']}</td>";
												if( isset( $page['help'] ) && !empty( $page['help'] ) ) {
													echo '<td class="help">';
													echo '	<a href="#" class="help_tip" title="' . $page['help'] . '">[?]</a>';
													echo '</td>';
												}
												echo '<td>';
												echo "	<mark class='error'>" . sprintf( esc_html__( 'You need to create a page with template %s', 'noo-landmark-core' ), $page['page_template'] ) . "</mark>";
												echo '</td>';
												echo '<td>';
												echo '	<div class="button button-primary">';
												echo '		<span class="correct-setting" data-title="' . $page['title'] . '" data-content="' . $page['content'] . '" data-page-template="' . $page['page_template'] . '">' . esc_html__( 'Correct now', 'noo-landmark-core') . '</span>';
												echo '	</div>';
												echo '</td>';
												echo '<td></td>';
											}
										else :
											echo '<td colspan="4"></td>';
										endif;
									endif;

									echo '</tr>';
								endforeach;
								
							?>
						</tbody>
					</table>
				<?php
			}

		// -- Tools options
		public function noo_import_demo_options() {

			$list_demo = array(
	            array(
	                'name' => esc_html__( 'Landmark', 'noo-landmark-core' ),
	                'img'  => NOO_PLUGIN_PATH . 'admin/importer/data-demo/landmark/screenshot.jpg',
	                'file' => 'landmark'
	            )
			);

			?>
				<!-- <table class="widefat" cellspacing="0" style="width: 99%;">
					<thead>
						<tr>
							<th colspan="1" data-export-label="<?php echo esc_html__( 'Settings', 'noo-landmark-core' ); ?>">
								<label class="hide_main">
									<?php echo esc_html__( 'Settings', 'noo-landmark-core' ); ?>
								</label>
							</th>
						</tr>
					</thead>
					<tbody id="noo_main_select">
						<tr>
							<td>
								<input type='checkbox' data-id='import_post' id='import_post' value='1' checked /> <?php echo esc_html__( 'Import Post', 'noo-landmark-core' ); ?>
							</td>
						</tr>
						<tr>
							<td>
								<input type='checkbox' data-id='import_nav' id='import_nav' value='1' checked /> <?php echo esc_html__( 'Import Nav Menu', 'noo-landmark-core' ); ?>
							</td>
						</tr>
						<tr>
							<td>
								<input type='checkbox' data-id='import_comment' id='import_comment' value='1' checked /> <?php echo esc_html__( 'Import Comment', 'noo-landmark-core' ); ?>
							</td>
						</tr>
					</tbody>
				</table> -->
				<div id="noo_tools">

					<!-- [ MAIN ] -->
					<div id="process_import"></div>
					<div class="theme-browser rendered" style="margin-top: 20px;">
						<div class="themes">
							<?php foreach ($list_demo as $id => $demo) : ?>
								<div class="theme" tabindex="0">
									<div class="theme-screenshot">
										<img src="<?php echo esc_attr( $demo['img'] ); ?>" alt="" />
									</div>
									<span class="more-details" id="install_<?php echo esc_attr( $demo['file'] ); ?>"><?php esc_html_e( 'Install ' .$demo['name'], 'noo-landmark-core' ); ?></span>
									<h3 class="theme-name" id="noo-<?php echo esc_attr( $demo['file'] ); ?>-name"><?php echo esc_html( $demo['name'] ); ?></h3>
									<div class="noo-load-ajax"></div>
									<div class="theme-actions">
										<button class="install-demo button button-secondary button-primary activate"
										data-name="<?php echo esc_attr( $demo['file'] ); ?>"
										data-import-post="true"
										data-import-nav="true"
										data-import-comment="true"
										><?php esc_html_e( 'Install Demo', 'noo-landmark-core' ); ?></button>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
						<br class="clear">
					</div>
				
				</div><!-- /#noo_tools -->

			<?php

		}

	// -- Setting fields
		
		public function get_product_field_name() {
            return $this->product_id . '-license-settings';
        }
		
		public function noo_settings_fields() {

			register_setting( $this->install_option_group, $this->install_option_name );

		    add_settings_section( 
		    	$this->install_section_id, 
		    	'Title Section', 
		    	'',
		    	$this->install_option_group 
		    );
		    add_settings_field( 
		    	$this->product_id . '-page',
		    	'Page', 
		    	array( $this, 'render_settings_section' ),
		    	$this->install_option_group,
		    	$this->install_section_id 
		    );


		}

		/**
         * Renders the description for the settings section.
         */
        public function render_settings_section() {
        }

	// -- Options fields
		public function noo_options_fields() {

			$this->option_metabox[] = array(

				'id' => 'general_options',
				'title' => esc_html__( 'General Options', 'noo-landmark-core' )

			);

			return $this->option_metabox;			

		}

    private function _get_page_id_by_template( $page_template = '' ) {

        $pages = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => $page_template
        ));

        if( $pages ){
            return $pages[0]->ID;
        }
        return false;

    }

}
new Noo_LandMark_Setup_Install;

endif;