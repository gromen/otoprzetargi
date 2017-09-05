<?php
/**
 *
 * Plugin Name:       Realty Portal
 * Plugin URI:        https://nootheme.com
 * Description:       Fast. Flexible. Right solution for any Real Estate website. A feature-rich Property Listing plugin for WordPress with variety of Add-ons. It works with any theme!
 * Version:           0.3.2
 * Author:            NooTeam
 * Author URI:        https://nootheme.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       realty-portal
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
include ( dirname( __FILE__ ) . '/includes/class-rp.php' );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 */
function run_realty_portal() {

	$plugin = new Realty_Portal();
	$plugin->run();

}
run_realty_portal();

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-rp-activator.php
 */
function activate_realty_portal() {
	include (  dirname( __FILE__ ) . '/includes/class-rp-activator.php' );
	Realty_Portal_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-rp-deactivator.php
 */
function deactivate_realty_portal() {
	include (  dirname( __FILE__ ) . '/includes/class-rp-deactivator.php' );
	Realty_Portal_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_realty_portal' );
register_deactivation_hook( __FILE__, 'deactivate_realty_portal' );