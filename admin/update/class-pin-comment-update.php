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
	private $plugin_name;

	/**
	 * The DB version slug of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name_db_version;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->plugin_name_db_version = '_' . $this->plugin_name . '_db_version';
		$this->version = $version;
	}

	/**
	 * Is this a fresh installation of Pin Comment?
	 *
	 * If there is no raw DB version, we infer that this is the first installation.
	 *
	 * @return bool True if this is a fresh BP install, otherwise false.
	 * @since Pin Comment 1.0.0
	 */
	public function is_install() {
		return ! $this->get_db_version_raw();
	}


	/**
	 * Get the DB version of Pin Comment
	 * 
	 * @since Pin Comment 1.0.0
	 */
	public function get_db_version_raw() {
		return get_option( $this->plugin_name_db_version, '0.0.1' );
	}

	/**
	 * Update the BP version stored in the database to the current version.
	 *
	 * @since Pin Comment 1.0.0
	 */
	function version_bump() {
		update_option( $this->plugin_name_db_version, PIN_COMMENT_VERSION );
	}

	/**
	 * Set up the Pin Comment updater.
	 *
	 * @since Pin Comment 1.0.0
	 */
	function setup_updater() {
		// Are we running an outdated version of Pin Comment?
		if ( ! $this->is_update() ) {
			return;
		}

		$this->version_updater();
	}

	/**
	 * Is this a Pin Comment update?
	 *
	 * Determined by comparing the registered Pin Comment version to the version
	 * number stored in the database. If the registered version is greater, it's
	 * an update.
	 *
	 * @return bool True if update, otherwise false.
	 * @since Pin Comment 1.0.0
	 */
	function is_update() {

		// Get current DB version.
		$current_db = $this->get_db_version_raw();

		// Get the raw database version.
		$current_live = PIN_COMMENT_VERSION;


		$is_update = false;
		if ( version_compare( $current_live, $current_db ) ) {
			$is_update = true;
		}

		// Return the product of version comparison.
		return $is_update;
	}


	/**
	 * Initialize an update or installation of Pin Comment.
	 *
	 * Pin Comment's version updater looks at what the current database version is,
	 * and runs whatever other code is needed - either the "update" or "install"
	 * code.
	 *
	 * This is most often used when the data schema changes, but should also be used
	 * to correct issues with Pin Comment metadata silently on software update.
	 *
	 * @since Pin Comment 1.0.0
	 */
	function version_updater() {

		// Get current DB version.
		$current_db = $this->get_db_version_raw();

		// Get the raw database version.
		$current_live = PIN_COMMENT_VERSION;

		if ( version_compare( '1.0.0', $current_db ) ) {
			pin_comment_to_1_0_0();
		}

		/**
		 * Update the version
		 */
		$this->version_bump();
	}
}


/**
 * Update function which run on everytime a plugin install for the firstime
 */
function pin_comment_to_1_0_0() {
}