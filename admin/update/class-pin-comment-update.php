<?php
/**
 * Pin Comment.
 *
 * @package Pin_Comment\Updater
 * @since Pin Comment 1.0.0
 */

  
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * The Updater-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Pin_Comment
 * @subpackage Pin_Comment/Updater
 * @author     AcrossWP <contact@acrosswp.com>
 */
class Pin_Comment_Update {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	public $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	public $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name		= $plugin_name;
		$this->version_compare	= $version;

		/**
		 * Called all the action and filter inside this functions
		 */
		$this->hooks();
	}

	/**
	 * This contain all the action and filter that are using for updating the plugins
	 */
	public function hooks() {

		add_action( 'acrosswp_plugin_update_' . $this->plugin_name, array( $this, 'plugin_update' ) );
	}

	/**
	 * Main Plugin Update 
	 */
	public function plugin_update( $acrosswp_plugin_update ) {

		/**
		 * Main Update
		 */
		$this->version_1_0_0();
	}

	/**
	 * Update to version 1.0.0
	 */
	public function version_1_0_0() {

		/**
		 * Stop the latest version update in DB
		 */
		$acrosswp_plugin_update->update_is_running();

		/**
		 * Allow the latest version update in DB
		 */
		$acrosswp_plugin_update->update_is_completed();
	}
}