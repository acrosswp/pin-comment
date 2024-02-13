<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://acrosswp.com
 * @since      1.0.0
 *
 * @package    Pin_Comment
 * @subpackage Pin_Comment/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Pin_Comment
 * @subpackage Pin_Comment/includes
 * @author     AcrossWP <contact@acrosswp.com>
 */
final class Pin_Comment {
	
	/**
	 * The single instance of the class.
	 *
	 * @var Pin_Comment
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Pin_Comment_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'pin-comment';

		$this->define_constants();

		if ( defined( 'PIN_COMMENT_VERSION' ) ) {
			$this->version = PIN_COMMENT_VERSION;
		} else {
			$this->version = '1.0.0';
		}

		$this->load_dependencies();

		$this->set_locale();

		$this->load_hooks();

	}

	/**
	 * Main Pin_Comment Instance.
	 *
	 * Ensures only one instance of WooCommerce is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Pin_Comment()
	 * @return Pin_Comment - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Define WCE Constants
	 */
	private function define_constants() {

		$this->define( 'PIN_COMMENT_PLUGIN_FILE', PIN_COMMENT_FILES );
		$this->define( 'PIN_COMMENT_PLUGIN_BASENAME', plugin_basename( PIN_COMMENT_FILES ) );
		$this->define( 'PIN_COMMENT_PLUGIN_PATH', plugin_dir_path( PIN_COMMENT_FILES ) );
		$this->define( 'PIN_COMMENT_PLUGIN_URL', plugin_dir_url( PIN_COMMENT_FILES ) );
		$this->define( 'PIN_COMMENT_PLUGIN_NAME_SLUG', $this->plugin_name );
		$this->define( 'PIN_COMMENT_PLUGIN_NAME', 'Pin Comment' );
		
		if( ! function_exists( 'get_plugin_data' ) ){
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}
		$plugin_data = get_plugin_data( PIN_COMMENT_PLUGIN_FILE );
		$version = $plugin_data['Version'];
		$this->define( 'PIN_COMMENT_VERSION', $version );

		$this->define( 'PIN_COMMENT_PLUGIN_URL', $version );
	}

	/**
	 * Define constant if not already set
	 * @param  string $name
	 * @param  string|bool $value
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Register all the hook once all the active plugins are loaded
	 *
	 * Uses the plugins_loaded to load all the hooks and filters
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	public function load_hooks() {

		/**
		 * Check if plugin can be loaded safely or not
		 * 
		 * @since    1.0.0
		 */
		if( apply_filters( 'pin-comment-load', true ) ) {
			$this->define_admin_hooks();
			$this->define_public_hooks();
		}

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Pin_Comment_Loader. Orchestrates the hooks of the plugin.
	 * - Pin_Comment_i18n. Defines internationalization functionality.
	 * - Pin_Comment_Admin. Defines all hooks for the admin area.
	 * - Pin_Comment_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * Add composer file
		 */
		require_once( PIN_COMMENT_PLUGIN_PATH . 'vendor/autoload.php' );

		if ( class_exists( 'AcrossWP_BuddyBoss_Platform_Dependency' ) ) {
			new AcrossWP_BuddyBoss_Platform_Dependency( $this->get_plugin_name(), PIN_COMMENT_FILES );
		}

		/**
		 * Load functions.php files
		 */
		require_once( PIN_COMMENT_PLUGIN_PATH . 'public/functions.php' );

		/**
		 * Check if the class does not exits then only allow the file to add
		 */
		if( class_exists( 'AcrossWP_Main_Menu' ) ) {
			AcrossWP_Main_Menu::instance();
		}

		/**
		 * Check if the class does not exits then only allow the file to add
		 */
		if( class_exists( 'AcrossWP_Plugin_Update' ) ) {

			/**
			 * The class responsible for defining all actions that occur in the admin area.
			 */
			require_once PIN_COMMENT_PLUGIN_PATH . 'admin/update/class-pin-comment-update.php';

			$plugin_update = new Pin_Comment_Update( $this->get_plugin_name(), $this->get_version() );

			$acrosswp_plugin_update = new AcrossWP_Plugin_Update( $this->get_plugin_name(), $this->get_version() );
		}

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once PIN_COMMENT_PLUGIN_PATH . 'includes/class-pin-comment-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once PIN_COMMENT_PLUGIN_PATH . 'includes/class-pin-comment-i18n.php';

		/**
		 * The file is reponsiable of updating the plugins zip
		 * of the plugin.
		 */
		require_once PIN_COMMENT_PLUGIN_PATH . 'admin/licenses-update/plugin-update-checker/main.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once PIN_COMMENT_PLUGIN_PATH . 'admin/class-pin-comment-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once PIN_COMMENT_PLUGIN_PATH . 'public/class-pin-comment-public.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once PIN_COMMENT_PLUGIN_PATH . 'public/class-pin-comment-rest-api.php';

		$this->loader = Pin_Comment_Loader::instance();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Pin_Comment_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Pin_Comment_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		
		$plugin_admin = new Pin_Comment_Admin( $this->get_plugin_name(), $this->get_version() );

		if( class_exists( 'AcrossWP_Plugin_Update_Checker_Github' ) ) {
			AcrossWP_Plugin_Update_Checker_Github::instance();;
		}

		$rest_api = new Pin_Comment_Rest_Controller( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'rest_api_init', $rest_api, 'register_routes', 1000 );

		$this->loader->add_action( 'bp_get_default_options', $plugin_admin, 'default_options', 100 );

		$this->loader->add_action( 'plugin_action_links', $plugin_admin, 'modify_plugin_action_links', 10, 2 );

		$this->loader->add_action( 'bp_admin_setting_activity_register_fields', $plugin_admin, 'register_fields', 100 );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Pin_Comment_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'bp_init', $plugin_public, 'popup_for_pin_comment' );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		/**
		 * Load the localize Script
		 */
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'wp_localize_script' );

		$this->loader->add_action( 'bb_nouveau_get_activity_comment_bubble_buttons', $plugin_public, 'activity_comment_bubble_buttons',100, 3 );

		$this->loader->add_action( 'bp_activity_comments_get_where_conditions', $plugin_public, 'get_where_conditions',1000 );

		$this->loader->add_action( 'bp_activity_comments_get_join_sql', $plugin_public, 'get_join_sql',1000 );

		$this->loader->add_action( 'bp_activity_comments_get_misc_sql', $plugin_public, 'get_misc_sql',1000 );

		/**
		 * Add class into the Comment
		 */
		$this->loader->add_filter( 'bp_get_activity_comment_css_class', $plugin_public, 'activity_comment_css_class',1000 );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Pin_Comment_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}


	/**
	 * Check whether activity pinned posts are enabled for post author.
	 *
	 * @since BuddyBoss 1.0.0
	 *
	 * @param bool $default Optional. Fallback value if not found in the database.
	 *                      Default: true.
	 *
	 * @return bool True    If activity pinned posts are enabled, otherwise false.
	 */
	function activity_comment_pinned_post_author( $default = true ) {
		return pin_comment_can_post_author_pin_setting( $default );
	}

	/**
	 * Check whether activity pinned posts are enabled for Group Admin.
	 *
	 * @since BuddyBoss 1.0.0
	 *
	 * @param bool $default Optional. Fallback value if not found in the database.
	 *                      Default: true.
	 *
	 * @return bool True    If activity pinned posts are enabled, otherwise false.
	 */
	function activity_comment_pinned_group_admin( $default = false ) {
		return pin_comment_can_group_admin_pin_setting( $default );
	}

}
