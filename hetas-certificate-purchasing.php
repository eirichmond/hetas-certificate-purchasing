<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://squareone.software
 * @since             1.0.0
 * @package           Hetas_Certificate_Purchasing
 *
 * @wordpress-plugin
 * Plugin Name:       HETAS Certificate Purchasing
 * Plugin URI:        https://hetas.co.uk
 * Description:       Takes care of all certificate purchasing.
 * Version:           1.0.0
 * Author:            Elliott Richmond
 * Author URI:        https://squareone.software
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       hetas-certificate-purchasing
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define PayPal live client id
 */
if ( ! defined ( 'PAYPAL_CLIENT_ID' )) {
	define('PAYPAL_CLIENT_ID', 'ASEsoHEDbUhKROVr2HPB-Iz1ABWHuIQW749KWKvrwIIRjEtrl0sfU6nirDk6Yy25Twj4caOC8EcRpPlj');
}

/**
 * Define Sagepay 
 */
if (!defined('SAGEPAY_TEST_MODE')) {
	define('SAGEPAY_TEST_MODE', false);
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'HETAS_CERTIFICATE_PURCHASING_VERSION', '1.0.0' );

function ccp_config() {

	$config = array();
	$config['pages'] = array(
		array(
			'title' => 'HETAS Copy Certificate Search',
			'slug' => 'hetas-copy-certificate-search'
		),
		array(
			'title' => 'HETAS Copy Certificate Results',
			'slug' => 'hetas-copy-certificate-results'
		),
		array(
			'title' => 'HETAS Copy Certificate Notification Details',
			'slug' => 'hetas-copy-certificate-notification-details'
		),
		array(
			'title' => 'HETAS Copy Certificate Notification Purchase',
			'slug' => 'hetas-copy-certificate-notification-purchase'
		),
		array(
			'title' => 'HETAS Copy Certificate Notification Payment Sagepay',
			'slug' => 'hetas-copy-certificate-notification-payment-sagepay'
		),
		array(
			'title' => 'HETAS Copy Certificate Notification Payment Success',
			'slug' => 'hetas-copy-certificate-notification-payment-success'
		),
		array(
			'title' => 'HETAS Copy Certificate Notification Process PayPal',
			'slug' => 'hetas-copy-certificate-notification-process-paypal'
		),
	);

	return $config;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-hetas-certificate-purchasing-activator.php
 */
function activate_hetas_certificate_purchasing() {
	$config = ccp_config();
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-hetas-certificate-purchasing-activator.php';
	Hetas_Certificate_Purchasing_Activator::activate($config);
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-hetas-certificate-purchasing-deactivator.php
 */
function deactivate_hetas_certificate_purchasing() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-hetas-certificate-purchasing-deactivator.php';
	Hetas_Certificate_Purchasing_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_hetas_certificate_purchasing' );
register_deactivation_hook( __FILE__, 'deactivate_hetas_certificate_purchasing' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-hetas-certificate-purchasing.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_hetas_certificate_purchasing() {

	$plugin = new Hetas_Certificate_Purchasing();
	$plugin->run();

}
run_hetas_certificate_purchasing();
