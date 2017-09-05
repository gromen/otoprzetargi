<?php

/**
 * Fired during plugin activation
 *
 * @link       https://nootheme.com
 *
 * @package    Realty_Portal
 * @subpackage Realty_Portal/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 */
class Realty_Portal_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 */
	public static function activate() {

		RP_Install::create_page_required();

		// Add Upgraded From Option
		$current_version = get_option( 'rp_version' );
		if ( $current_version != '' ) {
			update_option( 'rp_version_upgraded_from', $current_version );
		} else {
			$rp_data = get_plugin_data( REALTY_PORTAL . '/realty-portal.php' );
			update_option( 'rp_version', $rp_data[ 'Version' ] );
		}

		// Bail if activating from network, or bulk
		if ( is_network_admin() || isset( $_GET[ 'activate-multi' ] ) ) {
			return;
		}

		// Add the transient to redirect
		set_transient( 'rp_activation_redirect', true, 30 );
	}

}
