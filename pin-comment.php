<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://acrosswp.com
 * @since             1.0.0
 * @package           Pin_Comment
 *
 * @wordpress-plugin
 * Plugin Name:       Pin Comment
 * Plugin URI:        https://acrosswp.com
 * Description:       Pin Comment by AcrossWP
 * Version:           1.0.0
 * Author:            AcrossWP
 * Author URI:        https://acrosswp.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       pin-comment
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PIN_COMMENT_FILES', __FILE__ );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-pin-comment-activator.php
 */
function pin_comment_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pin-comment-activator.php';
	Pin_Comment_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-pin-comment-deactivator.php
 */
function pin_comment_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pin-comment-deactivator.php';
	Pin_Comment_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'pin_comment_activate' );
register_deactivation_hook( __FILE__, 'pin_comment_deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-pin-comment.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function pin_comment_run() {

	$plugin = Pin_Comment::instance();

	/**
	 * Run this plugin on the plugins_loaded functions
	 */
	add_action( 'plugins_loaded', array( $plugin, 'run' ), 0 );

}
pin_comment_run();