<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! noo_landmark_is_plugin_active( 'noo-landmark-core/noo-landmark-core.php' ) ) :

	/**
	 * This class show theme option box
	 * Process send request purchase code to server NooTheme
	 *
	 * @author     KENT <tuanlv@vietbrain.com>
	 * @version    1.0
	 */
	if ( ! class_exists( 'Noo_LandMark_Setup_Install' ) ) :

		class Noo_LandMark_Setup_Install {

			private $theme_title;
			private $theme_logo;
			private $product_id;
			private $document_url;
			private $support_url;
			private $theme_url_rating;
			private $theme_name;

			public function __construct() {

				if ( is_admin() ) {

					$this->theme_logo       = get_template_directory_uri() . '/assets/images/Logo.png';
					$this->theme_title      = esc_html__( 'Thank you for purchasing Landmark - Premium Real Estate WordPress Theme', 'noo-landmark' );
					$this->product_id       = 'noo-landmark';
					$this->document_url     = 'https://nootheme.com/documentation/landmark/';
					$this->support_url      = 'https://nootheme.com/forums/forum/landmark-wordpress/';
					$this->theme_url_rating = 'https://nootheme.com/wp-content/uploads/2016/12/how-to-rate.png';
					$this->theme_name       = 'Landmark';

					add_action( 'admin_notices', array(
						&$this,
						'notice_html_install',
					) );
					add_action( 'admin_menu', array(
						&$this,
						'admin_menus',
					) );

					add_action( 'admin_enqueue_scripts', array(
						&$this,
						'enqueue_script',
					) );

					add_action( 'wp_ajax_noo_check_purchase_code', array(
						&$this,
						'noo_check_purchase_code',
					) );
				}
			}

			public function enqueue_script() {

				wp_register_style( 'noo-theme-option', get_template_directory_uri() . '/includes/theme-option/theme-option.css' );

				wp_register_script( 'noo-theme-option', get_template_directory_uri() . '/includes/theme-option/theme-option.js', array( 'jquery' ), null, true );

				wp_localize_script( 'noo-theme-option', 'Noo_Theme_Option', array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'security' => wp_create_nonce( 'noo-theme-option' ),
				) );
			}

			public function notice_html_install() {

				$theme_options = get_option( $this->get_product_field_name() );
				if ( ! empty( $theme_options ) && ! empty( $theme_options[ 'license_key' ] ) || ( isset( $_GET[ 'page' ] ) && $_GET[ 'page' ] != 'noo-setup' ) ) {
					return;
				}
				wp_enqueue_style( 'noo-theme-option' );
				wp_enqueue_script( 'noo-theme-option' );
				?>
				<form id="noo-purchase-code" class="notice">

					<h1 class="noo-logo">
						<img src="<?php echo esc_url( $this->theme_logo ) ?>" alt="*" />
						<p><?php echo esc_attr( $this->theme_title ) ?></p>
					</h1>

					<div class="noo-purchase-code-active">
						<p class="noo-notice">
							<?php echo sprintf( esc_html__( 'Input the ThemeForest purchase code to be able to download, update and fully access to %s', 'noo-landmark' ), esc_html( $this->theme_name ) ); ?>
						</p>
						<div class="noo-action">
							<input type="text" name="purchase_code" placeholder="<?php echo esc_html__( 'Enter purchase code, eg. abcd-efgh-eklm-eoui-123456', 'noo-landmark' ) ?>" value="<?php echo esc_attr( $theme_options[ 'license_key' ] ); ?>" />
							<button id="noo-active-code" type="submit">
								<?php echo esc_html__( 'Activate', 'noo-landmark' ); ?>
							</button>
						</div>
					</div>

					<div class="noo-features">

						<div class="noo-item">
							<i class="noo-item-icon dashicons dashicons-book-alt"></i>
							<a target="_blank" href="<?php echo esc_url( $this->document_url ) ?>" class="noo-item-title">
								<?php echo esc_html__( 'Online Documentation', 'noo-landmark' ) ?>
							</a>
							<p class="noo-item-description">
								<?php echo esc_html__( 'We recommend giving a glimpse at our online document before starting the actual work. It\'s well written and might save you plenty of time.', 'noo-landmark' ); ?>
							</p>
						</div>

						<div class="noo-item">
							<i class="noo-item-icon dashicons dashicons-awards"></i>
							<a target="_blank" href="<?php echo esc_url( $this->support_url ) ?>" class="noo-item-title">
								<?php echo esc_html__( 'Support Center', 'noo-landmark' ) ?>
							</a>
							<p class="noo-item-description">
								<?php echo esc_html__( 'We are a dedicated team who wants to bring best products and services to customers. If you have questions or need support, feel free to contact us.', 'noo-landmark' ); ?>
							</p>
						</div>

					</div>

					<div class="noo-rating-box">
						<h2>
							<?php echo sprintf( esc_html__( 'Rate %s', 'noo-landmark' ), esc_html( $this->theme_name ) ); ?>
						</h2>
						<p>
							<?php echo wp_kses( sprintf( __( 'If you like our work, please rate it 5 stars, it will motivate us a lot and help us keep on making better work. See how to rate <a target="_blank" href="%s">here</a>', 'noo-landmark' ), esc_attr( $this->theme_url_rating ) ), noo_landmark_func_allowed_html() ); ?>
						</p>
					</div>

				</form>
				<?php
			}

			/**
			 * Create sub page
			 */
			public function admin_menus() {
				add_theme_page( esc_html__( 'Noo Settings', 'noo-landmark' ), esc_html__( 'Noo Settings', 'noo-landmark' ), 'edit_theme_options', 'noo-setup', array(
					$this,
					'page_setup',
				) );
			}

			/**
			 * Update data theme option
			 */
			public function noo_add_option( $name ) {
				$options = array_merge( get_option( 'noo_setup', array() ), $name );
				update_option( 'noo_setup', $options );
			}

			/**
			 * Get option
			 */
			public function get_settings( $name ) {
				$options = get_option( 'noo_setup' );

				return $options[ $name ];
			}

			/**
			 * Tab menu
			 */
			public function tab_menu( $current = 'general' ) {

				$tabs = array(
					'general' => esc_html__( 'Quick Setup', 'noo-landmark' ),
				);
				$tabs = apply_filters( 'noo_tab_menu_setting', $tabs );
				$html = '<h2 class="nav-tab-wrapper">';
				foreach ( $tabs as $tab => $name ) :

					$class = ( $tab == $current ) ? 'nav-tab-active' : '';
					$html  .= '<a class="nav-tab ' . $class . '" href="?page=noo-setup&tab=' . $tab . '">' . $name . '</a>';

				endforeach;
				$html .= '</h2>';
				echo $html;
			}

			/**
			 * Page setup
			 */
			public function page_setup() {

				if ( isset( $_GET[ 'page' ] ) == 'noo-setup' ) :

					if ( isset( $_GET[ 'tab' ] ) ) :

						$tab = $_GET[ 'tab' ];

					else :

						$tab = 'general';

					endif;

					$this->tab_menu( $tab );

					switch ( $tab ) {
						case 'general':
							$this->general_options();
							break;
					}

				endif;
			}

			/**
			 * General options
			 */
			public function general_options() {
				if ( isset( $_GET[ 'action' ] ) == 'skip' ) :
					$this->noo_add_option( array( 'disable_notice_install' => true ) );
					wp_redirect( admin_url() );
					die;
				endif;
				if ( isset( $_POST[ 'license_key' ] ) ) :
					$value_license = array(
						'license_key' => esc_attr( $_POST[ 'license_key' ] ),
						'email'       => esc_attr( $_POST[ 'email' ] ),
					);

					update_option( $this->get_product_field_name(), $value_license );

					?>
					<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible">
						<p><strong><?php echo esc_html__( 'Settings saved.', 'noo-landmark' ); ?></strong></p>
						<button type="button" class="notice-dismiss">
							<span class="screen-reader-text">
								<?php echo esc_html__( 'Dismiss this notice.', 'noo-landmark' ); ?>
							</span>
						</button>
					</div>

					<?php
					$this->noo_add_option( array( 'disable_notice_install' => true ) );

				endif;
				echo '<div class="wrap">';
				echo '<form method="post">';

				$this->option_client();

				echo '<p>';
				submit_button( '', 'primary', '', false );
				echo '&nbsp;&nbsp;';
				echo '<a href="' . esc_url( admin_url( 'admin.php?page=noo-setup&action=skip' ) ) . '" class="button">';
				echo esc_html__( 'Cancel', 'noo-landmark' );
				echo '</a>';
				echo '</p>';

				echo '</form>';
				echo '</div>';
			}

			/**
			 * Option client
			 */
			public function option_client() {
				$settings_field_name = $this->get_product_field_name();
				$theme_options       = get_option( $settings_field_name );
				?>
				<table class="widefat" cellspacing="0" id="client">
					<thead>
					<tr>
						<th colspan="3" data-export-label="<?php esc_html_e( 'Activate Theme', 'noo-landmark' ); ?>">
							<strong>
								<?php esc_html_e( 'ThemeForest Purchase Code', 'noo-landmark' ); ?>
							</strong>
						</th>
					</tr>
					</thead>
					<tbody>
					<tr>
						<td data-export-label="<?php esc_html_e( 'Purchase Code', 'noo-landmark' ); ?>">
							<?php esc_html_e( 'ThemeForest Purchase Code:', 'noo-landmark' ); ?><br />
							<small>( <?php esc_html_e( 'Required', 'noo-landmark' ); ?> )</small>
						</td>
						<td class="help">
							<a href="#" title="<?php _e( 'This code helps us verify you as our customer and allows you to install the plugins from our server.', 'noo-landmark' ); ?>" class="help_tip"><span class="dashicons dashicons-editor-help"></span></a>
						</td>
						<td>
							<input type='text' name='license_key' value='<?php echo esc_attr( $theme_options[ 'license_key' ] ); ?>' class='regular-text'>
							<input type='hidden' name='email' value='<?php echo str_replace( 'http://', '', home_url() ); ?>' class='regular-text'>
							<span><?php echo sprintf( wp_kses( __( '<a target="_blank" href="%s">How to get License key?</a>', 'noo-landmark' ), noo_landmark_func_allowed_html() ), 'https://nootheme.com/wp-content/uploads/2015/07/HowToGetPurchaseCode.png' ) ?></span>
						</td>
					</tr>
					</tbody>
				</table>
				<?php
			}

			public function noo_check_purchase_code() {

				if ( isset( $_POST[ 'purchase_code' ] ) && ! empty( $_POST[ 'purchase_code' ] ) ) {

					$purchase_code = ! empty( $_POST[ 'purchase_code' ] ) ? esc_attr( $_POST[ 'purchase_code' ] ) : '';

					unset( $_POST[ 'action' ] );

					$data_request = wp_remote_get( add_query_arg( array(
						'purchase_code' => esc_attr( $purchase_code ),
						'site_url'      => get_site_url(),
					), 'http://update.nootheme.com/verify_code' ), array( 'timeout' => 60 ) );

					if ( is_wp_error( $data_request ) ) {

						delete_option( $this->get_product_field_name() );

						$response[ 'status' ]  = 'error';
						$response[ 'message' ] = esc_html__( 'Some troubles with connecting to NooTheme server.', 'noo-landmark' );
						wp_send_json( $response );
					}

					$rp_data = json_decode( $data_request[ 'body' ], true );

					if ( ! is_array( $rp_data ) || empty( $rp_data ) || $rp_data[ 'status' ] !== 'success' ) {

						delete_option( $this->get_product_field_name() );

						$response[ 'status' ]  = 'error';
						$response[ 'message' ] = esc_html__( 'Purchase code verification failed.', 'noo-landmark' );
						wp_send_json( $response );
					} else {

						$value_license = array(
							'license_key' => esc_attr( $purchase_code ),
							'email'       => esc_attr( str_replace( 'http://', '', home_url() ) ),
						);

						update_option( $this->get_product_field_name(), $value_license );

						$response[ 'status' ]  = 'success';
						$response[ 'message' ] = esc_html__( 'Purchase code is activated', 'noo-landmark' );
						wp_send_json( $response );
					}
				}

				$response[ 'status' ]  = 'error';
				$response[ 'message' ] = esc_html__( 'Please enter purchase code.', 'noo-landmark' );
				wp_send_json( $response );
			}

			/**
			 * Show message json
			 *
			 * @author  KENT <tuanlv@vietbrain.com>
			 * @version 1.0
			 */
			public function message() {

			}

			/**
			 * Setting fields
			 */
			public function get_product_field_name() {
				return $this->product_id . '-license-settings';
			}

		}

		new Noo_LandMark_Setup_Install;

	endif;

endif;