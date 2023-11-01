<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://acrosswp.com
 * @since      1.0.0
 *
 * @package    Pin_Comment
 * @subpackage Pin_Comment/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Pin_Comment
 * @subpackage Pin_Comment/admin
 * @author     AcrossWP <contact@acrosswp.com>
 */
class Pin_Comment_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

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
		$this->version = $version;
	}

	/**
	 * Add integration class class and files
	 */
	public function register_integration() {
		require_once PIN_COMMENT_PLUGIN_PATH . 'admin/integration/buddyboss-integration.php';
		buddypress()->integrations['addon'] = new Pin_Comment_BuddyBoss_Integration( $this->plugin_name );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Pin_Comment_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Pin_Comment_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, PIN_COMMENT_PLUGIN_URL . 'assets/dist/css/backend-style.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Pin_Comment_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Pin_Comment_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, PIN_COMMENT_PLUGIN_URL . 'assets/dist/js/backend-script.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add Settings link to plugins area.
	 *
	 * @since    1.0.0
	 *
	 * @param array  $links Links array in which we would prepend our link.
	 * @param string $file  Current plugin basename.
	 * @return array Processed links.
	 */
	public function modify_plugin_action_links( $links, $file ) {

		// Return normal links if not BuddyPress.
		if ( PIN_COMMENT_PLUGIN_BASENAME !== $file ) {
			return $links;
		}

		// Add a few links to the existing links array.
		return array_merge(
			$links,
			array(
				'settings'      => '<a href="' . esc_url( bp_get_admin_url( 'admin.php?page=bp-settings' ) ) . '">' . esc_html__( 'Settings', 'pin-comment' ) . '</a>',
				'about'         => '<a href="' . esc_url( bp_get_admin_url( '?page=acrosswp' ) ) . '">' . esc_html__( 'About', 'pin-comment' ) . '</a>',
			)
		);
	}

}
