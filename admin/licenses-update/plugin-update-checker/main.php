<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Add Github Plugin update checker into the AcrossWP Github Plugin Update Checker
 */
function pin_comment_plugins_update_checker_github( $packages ) {

    $packages[1000] = array(
        'repo' 		        => 'https://github.com/acrosswp/pin-comment',
        'file_path' 		=> PIN_COMMENT_FILES,
        'plugin_name_slug'	=> PIN_COMMENT_PLUGIN_NAME_SLUG,
        'release_branch' 	=> 'main'
    );

    return $packages;
}
add_filter( 'acrosswp_plugins_update_checker_github', 'pin_comment_plugins_update_checker_github', 100, 1 );
