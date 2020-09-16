<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://squareone.software
 * @since      1.0.0
 *
 * @package    Hetas_Certificate_Purchasing
 * @subpackage Hetas_Certificate_Purchasing/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Hetas_Certificate_Purchasing
 * @subpackage Hetas_Certificate_Purchasing/includes
 * @author     Elliott Richmond <elliott@squareonemd.co.uk>
 */
class Hetas_Certificate_Purchasing_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'hetas-certificate-purchasing',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
