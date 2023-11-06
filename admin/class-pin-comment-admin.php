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
				'settings'      => '<a href="' . esc_url( bp_get_admin_url( 'admin.php?page=bp-settings&tab=bp-activity#pc_activity_comment_setttings' ) ) . '">' . esc_html__( 'Settings', 'pin-comment' ) . '</a>',
				'about'         => '<a href="' . esc_url( bp_get_admin_url( '?page=acrosswp' ) ) . '">' . esc_html__( 'About', 'pin-comment' ) . '</a>',
			)
		);
	}

	/**
	 * Add setting
	 */
	public function register_fields( $bp_admin_settings_activity ) {

		$bp_admin_settings_activity->add_section( 'pc_activity_comment_setttings', __( 'Pin Comment', 'pin-comment' ) );

		// Allow scopes/tabs.
		$type['class'] = 'child-no-padding-first';
		$bp_admin_settings_activity->add_field( '_pc_enable_activity_comment_pinned_post_author', __( 'Pinned Comment', 'pin-comment' ), array( $this, 'activity_comment_pinned_post_author' ), 'intval', $type );

		$type['class'] = 'child-no-padding';
		$bp_admin_settings_activity->add_field( '_pc_enable_activity_comment_pinned_group_admin', '', array( $this, 'activity_comment_pinned_group_admin' ), 'intval', $type );

	}

	/**
	 * Allow pinned activity posts.
	 *
	 * @since BuddyBoss 1.0.0
	 */
	public function activity_comment_pinned_group_admin() {
		?>

		<input id="_pc_enable_activity_comment_pinned_group_admin" name="_pc_enable_activity_comment_pinned_group_admin" type="checkbox" value="1" <?php checked( Pin_Comment::instance()->activity_comment_pinned_group_admin() ); ?> />
		<label for="_pc_enable_activity_comment_pinned_group_admin"><?php esc_html_e( 'Allow group owners and moderators to pin comments in posts', 'buddyboss' ); ?></label>
		<?php
	}

	/**
	 * Allow pinned activity posts.
	 *
	 * @since BuddyBoss 1.0.0
	 */
	public function activity_comment_pinned_post_author() {
		?>

		<input id="_pc_enable_activity_comment_pinned_post_author" name="_pc_enable_activity_comment_pinned_post_author" type="checkbox" value="1" <?php checked( Pin_Comment::instance()->activity_comment_pinned_post_author() ); ?> />
		<label for="_pc_enable_activity_comment_pinned_post_author"><?php esc_html_e( 'Allow Post Author to pin comments in posts', 'buddyboss' ); ?></label>
		<?php
	}

	/**
	 * Allow pinned activity posts.
	 *
	 * @since BuddyBoss 1.0.0
	 */
	public function default_options( $options ) {

		// Enabled activity pinned posts.
		$options['_pc_enable_activity_comment_pinned_post_author'] = true;
		$options['_pc_enable_activity_comment_pinned_group_admin'] = false;

		return $options;
	}

}
