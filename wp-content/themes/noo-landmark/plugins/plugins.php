<?php
require_once get_template_directory() . '/plugins/class-tgm-plugin-activation.php';

/**
 * Get purchase code
 *
 * @package       LandMark/Plugins
 * @author        KENT <tuanlv@vietbrain.com>
 * @version       1.0
 */

if ( ! function_exists( 'noo_landmark_get_purchase' ) ) :

	function noo_landmark_get_purchase() {

		$theme_options = get_option( 'noo-landmark-license-settings' );

		if ( $theme_options && isset( $theme_options[ 'license_key' ] ) ) {
			return $theme_options[ 'license_key' ];
		}

		return false;
	}

endif;

/**
 * Check plugin active
 *
 * @package       LandMark/Plugins
 * @author        KENT <tuanlv@vietbrain.com>
 * @version       1.0
 */

if ( ! function_exists( 'noo_landmark_is_plugin_active' ) ) :

	function noo_landmark_is_plugin_active( $plugin ) {

		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		return is_plugin_active( $plugin );
	}

endif;

/**
 * Register required plugin
 *
 * @package    LandMark/Plugins
 */
if ( ! function_exists( 'noo_landmark_register_required_plugins' ) ) :

	function noo_landmark_register_required_plugins() {

		if ( ! noo_landmark_get_purchase() ) {
			return;
		}

		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		$plugins = array(
			array(
				'name'               => esc_html__( 'NOO LandMark Core 2', 'noo-landmark' ),
				'slug'               => 'noo-landmark-core-2',
				'source'             => esc_url( 'http://update.nootheme.com/download/noo-landmark-core-2.zip' ),
				'required'           => true,
				'version'            => '',
				'force_activation'   => false,
				'force_deactivation' => false,
				'external_url'       => '',
			),
			array(
				'name'               => esc_html__( 'Visual Composer', 'noo-landmark' ),
				'slug'               => 'js_composer',
				'source'             => esc_url( 'http://update.nootheme.com/download/js_composer.zip' ),
				'required'           => true,
				'version'            => '',
				'force_activation'   => false,
				'force_deactivation' => false,
				'external_url'       => '',
			),
			array(
				'name'               => esc_html__( 'Revslider', 'noo-landmark' ),
				'slug'               => 'revslider',
				'source'             => esc_url( 'http://update.nootheme.com/download/revslider.zip' ),
				'required'           => false,
				'version'            => '',
				'force_activation'   => false,
				'force_deactivation' => false,
				'external_url'       => '',
			),
			array(
				'name'     => esc_html__( 'Realty Portal', 'noo-landmark' ),
				'slug'     => 'realty-portal',
				'required' => true,
			),
			array(
				'name'     => esc_html__( 'Realty Portal - Agent', 'noo-landmark' ),
				'slug'     => 'realty-portal-agent',
				'required' => false,
			),
			array(
				'name'     => esc_html__( 'Realty Portal - Agent Dashboard', 'noo-landmark' ),
				'slug'     => 'realty-portal-agent-dashboard',
				'required' => false,
			),
			array(
				'name'     => esc_html__( 'Realty Portal - Agent Profile', 'noo-landmark' ),
				'slug'     => 'realty-portal-agent-profile',
				'required' => false,
			),
			array(
				'name'     => esc_html__( 'Realty Portal - Compare', 'noo-landmark' ),
				'slug'     => 'realty-portal-compare',
				'required' => false,
			),
			array(
				'name'     => esc_html__( 'Realty Portal - Floor Plan', 'noo-landmark' ),
				'slug'     => 'realty-portal-floor-plan',
				'required' => false,
			),
			array(
				'name'     => esc_html__( 'Realty Portal - My Favorites', 'noo-landmark' ),
				'slug'     => 'realty-portal-my-favorites',
				'required' => false,
			),
			array(
				'name'     => esc_html__( 'Realty Portal - Package', 'noo-landmark' ),
				'slug'     => 'realty-portal-package',
				'required' => false,
			),
			array(
				'name'     => esc_html__( 'Realty Portal - Submit Property', 'noo-landmark' ),
				'slug'     => 'realty-portal-submit-property',
				'required' => false,
			),
			array(
				'name'     => esc_html__( 'Realty Portal - Advanced Search', 'noo-landmark' ),
				'slug'     => 'realty-portal-advanced-search',
				'required' => false,
			),
			array(
				'name'     => esc_html__( 'Realty Portal - Nearby Places', 'noo-landmark' ),
				'slug'     => 'realty-portal-nearby-places',
				'required' => false,
			),
			array(
				'name'     => esc_html__( 'Contact Form 7', 'noo-landmark' ),
				'slug'     => 'contact-form-7',
				'required' => false,
			),
			array(
				'name'     => esc_html__( 'Mailchimp For WP', 'noo-landmark' ),
				'slug'     => 'mailchimp-for-wp',
				'required' => false,
			),
			array(
				'name'     => esc_html__( 'Breadcrumb NavXT', 'noo-landmark' ),
				'slug'     => 'breadcrumb-navxt',
				'required' => false,
			),
			array(
				'name'     => esc_html__( 'WooCommerce', 'noo-landmark' ),
				'slug'     => 'woocommerce',
				'required' => false,
			),
		);

		/**
		 * Add-on support for Woocommerce
		 */
		if ( noo_landmark_is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			$plugins[] = array(
				'name'     => esc_html__( 'YITH WooCommerce Wishlist', 'noo-landmark' ),
				'slug'     => 'yith-woocommerce-wishlist',
				'required' => false,
			);
			$plugins[] = array(
				'name'     => esc_html__( 'YITH WooCommerce Compare', 'noo-landmark' ),
				'slug'     => 'yith-woocommerce-compare',
				'required' => false,
			);
		}

		/**
		 * Add-on support for IDX
		 */
		if ( noo_landmark_is_plugin_active( 'dsidxpress/dsidxpress.php' ) || noo_landmark_is_plugin_active( 'optima-express/iHomefinder.php' ) ) {
			$plugins[] = array(
				'name'               => esc_html__( 'Noo Landmark IDX Support', 'noo-landmark' ),
				'slug'               => 'noo-landmark-idx-support',
				'source'             => esc_url( 'http://update.nootheme.com/download/noo-landmark-idx-support.zip' ),
				'required'           => false,
				'version'            => '',
				'force_activation'   => false,
				'force_deactivation' => false,
				'external_url'       => '',
			);
		}

		$config = array(
			'domain'       => 'noo-landmark',
			'default_path' => '',
			'menu'         => 'install-required-plugins',
			'has_notices'  => true,
			'is_automatic' => true,
			'message'      => '',
			'strings'      => array(
				'page_title'                      => esc_html__( 'Install Required Plugins', 'noo-landmark' ),
				'menu_title'                      => esc_html__( 'Install Plugins', 'noo-landmark' ),
				'installing'                      => esc_html__( 'Installing Plugin: %s', 'noo-landmark' ),
				'oops'                            => esc_html__( 'Something went wrong with the plugin API.', 'noo-landmark' ),
				'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'noo-landmark' ),
				'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'noo-landmark' ),
				'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'noo-landmark' ),
				'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'noo-landmark' ),
				'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'noo-landmark' ),
				'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'noo-landmark' ),
				'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'noo-landmark' ),
				'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'noo-landmark' ),
				'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'noo-landmark' ),
				'activate_link'                   => _n_noop( 'Activate installed plugin', 'Activate installed plugins', 'noo-landmark' ),
				'return'                          => esc_html__( 'Return to Required Plugins Installer', 'noo-landmark' ),
				'plugin_activated'                => esc_html__( 'Plugin activated successfully.', 'noo-landmark' ),
				'complete'                        => esc_html__( 'All plugins installed and activated successfully. %s', 'noo-landmark' ),
				'nag_type'                        => 'updated',
			),
		);

		tgmpa( $plugins, $config );
	}

	add_action( 'tgmpa_register', 'noo_landmark_register_required_plugins' );

endif;

/**
 * Send request update plugin
 *
 * @package       LandMark/Plugins
 * @author        KENT <tuanlv@vietbrain.com>
 * @version       1.0
 */
if ( ! function_exists( 'noo_landmark_updater_plugin_load' ) ) :

	function noo_landmark_updater_plugin_load() {

		if ( ! current_user_can( 'update_plugins' ) ) {
			return;
		}

		if ( ! noo_landmark_get_purchase() ) {
			return;
		}

		if ( ! class_exists( 'TGM_Updater' ) ) {
			require get_template_directory() . '/plugins/class-tgm-updater.php';
		}

		/**
		 * Check version plugin Noo Landmark Core
		 */
		if ( noo_landmark_is_plugin_active( 'noo-landmark-core-2/noo-landmark-core-2.php' ) ) {

			$plugin_data = get_plugin_data( trailingslashit( WP_PLUGIN_DIR ) . 'noo-landmark-core-2/noo-landmark-core-2.php' );

			$data_plugin_update = array(
				'plugin_name' => esc_html__( 'NOO LandMark Core 2', 'noo-landmark' ),
				'plugin_slug' => 'noo-landmark-core-2',
				'plugin_path' => 'noo-landmark-core-2/noo-landmark-core-2.php',
				'plugin_url'  => trailingslashit( WP_PLUGIN_URL ) . 'noo-landmark-core-2',
				'remote_url'  => esc_url( 'http://update.nootheme.com/plugins/noo-landmark-core-2.json' ),
				'version'     => $plugin_data[ 'Version' ],
				'key'         => '',
			);

			$tgm_updater = new TGM_Updater( $data_plugin_update );
		}

		/**
		 * Check version plugin Noo Landmark IDX support
		 */
		if ( noo_landmark_is_plugin_active( 'noo-landmark-idx-support/noo-landmark-idx-support.php' ) ) {

			$plugin_data = get_plugin_data( trailingslashit( WP_PLUGIN_DIR ) . 'noo-landmark-idx-support/noo-landmark-idx-support.php' );

			$data_plugin_update = array(
				'plugin_name' => esc_html__( 'Noo Landmark IDX Support', 'noo-landmark' ),
				'plugin_slug' => 'noo-landmark-idx-support',
				'plugin_path' => 'noo-landmark-idx-support/noo-landmark-idx-support.php',
				'plugin_url'  => trailingslashit( WP_PLUGIN_URL ) . 'noo-landmark-idx-support',
				'remote_url'  => esc_url( 'http://update.nootheme.com/plugins/noo-landmark-idx-support.json' ),
				'version'     => $plugin_data[ 'Version' ],
				'key'         => '',
			);

			$tgm_updater = new TGM_Updater( $data_plugin_update );
		}

		/**
		 * Check version plugin Visual Composer
		 */
		if ( noo_landmark_is_plugin_active( 'js_composer/js_composer.php' ) ) {

			$plugin_data = get_plugin_data( trailingslashit( WP_PLUGIN_DIR ) . 'js_composer/js_composer.php' );

			$data_plugin_update = array(
				'plugin_name' => esc_html__( 'WPBakery Visual Composer', 'noo-landmark' ),
				'plugin_slug' => 'js_composer',
				'plugin_path' => 'js_composer/js_composer.php',
				'plugin_url'  => trailingslashit( WP_PLUGIN_URL ) . 'js_composer',
				'remote_url'  => esc_url( 'http://update.nootheme.com/plugins/js_composer.json' ),
				'version'     => $plugin_data[ 'Version' ],
				'key'         => '',
			);

			$tgm_updater = new TGM_Updater( $data_plugin_update );
		}

		/**
		 * Check version plugin Revolution Slider
		 */
		if ( noo_landmark_is_plugin_active( 'revslider/revslider.php' ) ) {

			$plugin_data = get_plugin_data( trailingslashit( WP_PLUGIN_DIR ) . 'revslider/revslider.php' );

			$data_plugin_update = array(
				'plugin_name' => esc_html__( 'Revolution Slider', 'noo-landmark' ),
				'plugin_slug' => 'revslider',
				'plugin_path' => 'revslider/revslider.php',
				'plugin_url'  => trailingslashit( WP_PLUGIN_URL ) . 'revslider',
				'remote_url'  => esc_url( 'http://update.nootheme.com/plugins/revslider.json' ),
				'version'     => $plugin_data[ 'Version' ],
				'key'         => '',
			);

			$tgm_updater = new TGM_Updater( $data_plugin_update );
		}
	}

	add_action( 'admin_init', 'noo_landmark_updater_plugin_load' );

endif;

/**
 * Send request validate to server
 *
 * @package       LandMark/Plugins
 * @author        KENT <tuanlv@vietbrain.com>
 * @version       1.0
 */

if ( ! function_exists( 'noo_landmark_upgrader_pre_download' ) ) :

	function noo_landmark_upgrader_pre_download( $reply, $package, $upgrader ) {

		if ( strpos( $package, 'nootheme.com' ) !== false ) {

			if ( ! noo_landmark_get_purchase() ) {
				return new WP_Error( 'noo_landmark_purchase_empty', sprintf( wp_kses( __( 'Purchase code verification failed. <a href="%s">Enter Purchase Code</a>', 'noo-landmark' ), array(
							'a' => array( 'href' => array() ),
							'LandMark',
						) ), esc_url( admin_url( 'admin.php?page=noo-setup' ) ) ) );
			}

			$data_request = wp_remote_get( add_query_arg( array(
				'code'           => noo_landmark_get_purchase(),
				'site_url'       => get_site_url(),
				'package'        => 'noo-landmark',
				'install-plugin' => true,
			), 'http://update.nootheme.com' ), array( 'timeout' => 60 ) );

			if ( is_wp_error( $data_request ) ) {
				return new WP_Error( 'noo_landmark_connection_failed', esc_html__( 'Some troubles with connecting to NooTheme server.', 'noo-landmark' ) );
			}
			$rp_data = json_decode( $data_request[ 'body' ], true );

			if ( ! ( is_array( $rp_data ) && isset( $rp_data[ 'status' ] ) && $rp_data[ 'status' ] ) ) {
				return new WP_Error( 'noo_landmark_purchase_error', sprintf( wp_kses( __( 'Purchase code verification failed. <a href="%s">Enter Purchase Code</a>', 'noo-landmark' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( admin_url( 'admin.php?page=noo-setup' ) ) ) );
			}
		}

		return $reply;
	}

	add_filter( 'upgrader_pre_download', 'noo_landmark_upgrader_pre_download', 10, 3 );

endif;

/**
 * Remove notice update visual composer
 *
 * @package       LandMark/Plugins
 * @author        KENT <tuanlv@vietbrain.com>
 * @version       1.0
 */

if ( ! function_exists( 'noo_landmark_enable_vc_auto_theme_update' ) ) :

	function noo_landmark_enable_vc_auto_theme_update() {

		if ( function_exists( 'vc_updater' ) ) {
			$vc_updater = vc_updater();
			remove_filter( 'upgrader_pre_download', array(
				$vc_updater,
				'preUpgradeFilter',
			), 10 );
			if ( function_exists( 'vc_license' ) ) {
				if ( ! vc_license()->isActivated() ) {
					remove_filter( 'pre_set_site_transient_update_plugins', array(
						$vc_updater->updateManager(),
						'check_update',
					), 10 );
				}
			}
			remove_filter( 'admin_notices', array(
				vc_license(),
				'adminNoticeLicenseActivation',
			) );
		}
	}

	add_action( 'vc_after_init', 'noo_landmark_enable_vc_auto_theme_update' );

endif;