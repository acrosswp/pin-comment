<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://acrosswp.com
 * @since      1.0.0
 *
 * @package    Pin_Comment
 * @subpackage Pin_Comment/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Pin_Comment
 * @subpackage Pin_Comment/includes
 * @author     AcrossWP <contact@acrosswp.com>
 */
class Pin_Comment_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'pin-comment',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
