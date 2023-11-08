<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Check if the login user can pin the comment
 */
function pin_comment_can_user_pin( $activity_comment ) {
    
    $current_user_id = get_current_user_id();

    if ( $current_user_id ) { 

        /**
         * If user is site admin
         */
        if( current_user_can('administrator') ) {
            return true;
        }

        if ( ! empty( $activity_comment->item_id ) ) {
            $activity = new BP_Activity_Activity( $activity_comment->item_id );

            /**
             * Check if the post author is allow to pin comment
             */
            if ( 
                pin_comment_can_group_admin_pin_setting()
                && 'groups' === $activity->component
                && (
                    groups_is_user_admin( $current_user_id, $activity->item_id )
                    || groups_is_user_mod( $current_user_id, $activity->item_id )
                )
            ) {
                return true;
            }

            /**
             * Check if the post author is allow to pin comment
             */
            if( pin_comment_can_post_author_pin_setting() ) {
                if( $current_user_id == $activity->user_id ) {
                    return true;
                }
            }
        }
    }

    return false;
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
function pin_comment_can_post_author_pin_setting( $default = true ) {
    return (bool) bp_get_option( '_pc_enable_activity_comment_pinned_post_author', $default );
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
function pin_comment_can_group_admin_pin_setting( $default = false ) {
    return (bool) bp_get_option( '_pc_enable_activity_comment_pinned_group_admin', $default );
}