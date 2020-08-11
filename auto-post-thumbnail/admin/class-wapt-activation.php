<?php
/**
 * Class of activation/deactivation of the plugin. Must be registered in file includes/class.plugin.php
 *
 * @author        Webcraftic <wordpress.webraftic@gmail.com>
 * @copyright (c) 02.12.2018, Webcraftic
 * @see           Wbcr_Factory431_Activator
 *
 * @version       1.0.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WAPT_Activation extends Wbcr_Factory431_Activator {

	/**
	 * Method is executed during the activation of the plugin.
	 *
	 * @since 1.0.0
	 */
	public function activate() {
		// Code to be executed during plugin activation
		$limit        = array(
			'count'   => 10,
			'expires' => time(),
		);
		$google_limit = WAPT_Plugin::app()->getOption( 'google_limit' );
		if ( ! $google_limit ) {
			WAPT_Plugin::app()->updateOption( 'google_limit', $limit );
		}

		//update_option( $this->plugin->getOptionName( 'whats_new_v360' ), 1 );
	}

	/**
	 * The method is executed during the deactivation of the plugin.
	 *
	 * @since 1.0.0
	 */
	public function deactivate() {
		$apt_ds = WAPT_Plugin::app()->getOption( 'delete_settings', false );

		if ( $apt_ds ) {
			// remove plugin options
			global $wpdb;
			$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE 'wbcr_apt_%';" );
		}
	}
}


