<?php

/**
 * Fired during plugin activation
 *
 * @link       https://squareone.software
 * @since      1.1.0
 *
 * @package    Hetas_Certificate_Purchasing
 * @subpackage Hetas_Certificate_Purchasing/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.1.0
 * @package    Hetas_Certificate_Purchasing
 * @subpackage Hetas_Certificate_Purchasing/includes
 * @author     Elliott Richmond <elliott@squareonemd.co.uk>
 */
class Hetas_Certificate_Purchasing_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.1.0
	 */
	public static function activate($config) {

		/**
		* Detect plugin. For use in Admin area only.
		*/
		if ( !is_plugin_active( 'hetas-dynamics-crm/hetas-dynamics-crm.php' ) ) {
			wp_die('Please ensure the Cantata Dynamics CRM Plugin is active!');
		}

		$pages = $config['pages'];
		
		foreach ($pages as $page) {

			$title = $page['title'];
			$slug = $page['slug'];
			$author_id = 1;
			
			
			// If the page doesn't already exist, then create it
			if( null == get_page_by_title( $title ) ) {
		
				// Set the post ID so that we know the post was created successfully
				$plugin_page = wp_insert_post(
					array(
						'comment_status'	=>	'closed',
						'ping_status'		=>	'closed',
						'post_author'		=>	$author_id,
						'post_name'		=>	$slug,
						'post_title'		=>	$title,
						'post_status'		=>	'publish',
						'post_type'		=>	'page'
					)
				);
		
			// Otherwise, we'll stop
			} else {
		
	    		// Arbitrarily use -2 to indicate that the page with the title already exists
	    		$users_page = -2;
		
			} // end if

		}

	}

}
