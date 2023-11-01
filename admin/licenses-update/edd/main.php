<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Add EDD licences into the AcrossWP EDD licences menu
 */
function pin_comment_edd_plugins_licenses( $licenses ) {

    $licenses[1000] = array(
        'id' 		=> 705,
        'key' 		=> PIN_COMMENT_PLUGIN_NAME_SLUG,
        'version'	=> PIN_COMMENT_VERSION,
        'name' 		=> PIN_COMMENT_PLUGIN_NAME
    );

    return $licenses;
}
add_filter( 'acrosswp_edd_plugins_licenses', 'pin_comment_edd_plugins_licenses', 100, 1 );