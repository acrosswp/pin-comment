<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * The rest-api-facing functionality of the plugin.
 *
 * @link       https://acrosswp.com
 * @since      1.0.0
 *
 * @package    Pin_Comment
 * @subpackage Pin_Comment/rest-api
 */

 
 /**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Pin_Comment
 * @subpackage Pin_Comment/Pin_Comment_Rest_Controller
 * @author     AcrossWP <contact@acrosswp.com>
 */
class Pin_Comment_Rest_Controller extends WP_REST_Controller {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
        $this->namespace     = '/'. $this->plugin_name .'/v1';
		$this->resource_name_pin = 'pin';
		$this->resource_name_unpin = 'unpin';

	}

    // Register our routes.
	public function register_routes() {

		register_rest_route(
			$this->namespace,
			'/' . $this->resource_name_pin . '/(?P<id>[\d]+)',
			array(
				// Here we register the readable endpoint for collections.
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'pinned' ),
					'permission_callback' => array( $this, 'permissions_check' ),
				)
			)
		);

        register_rest_route(
			$this->namespace,
			'/' . $this->resource_name_unpin . '/(?P<id>[\d]+)',
			array(
				// Here we register the readable endpoint for collections.
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'unpinned' ),
					'permission_callback' => array( $this, 'permissions_check' ),
                )
            )
		);
	}

    /**
     * Get the Activity Object
     */
    public function get_activity_object( $request ) {
        $activity_id = is_numeric( $request ) ? $request : (int) $request['id'];

        $activity = new BP_Activity_Activity( $activity_id );
        if ( is_object( $activity ) && ! empty( $activity->id ) ) {
            return $activity;
        }

        return false;
    }


    public function permissions_check( $request ) {

        $retval = new WP_Error(
			'pin_comment_rest_authorization_required',
			__( 'Sorry, you are not allowed to perform this action.', 'pin-comment' ),
			array(
				'status' => rest_authorization_required_code(),
			)
		);

        $activity_comment = $this->get_activity_object( $request->get_param( 'id' ) );

		if ( 
            empty( $activity_comment->id ) 
            || 'activity_comment' != $activity_comment->type
            || $activity_comment->item_id != $activity_comment->secondary_item_id
        ) {
			return new WP_Error(
				'pin_comment_rest_invalid_id',
				__( 'Invalid Comment ID.', 'pin-commnet' ),
				array(
					'status' => 404,
				)
			);
		}

        if ( pin_comment_can_user_pin( $activity_comment ) ) {
			return true;
		}

        return $retval;
    }


	/**
	 * Add Activity Meta to pin the comment in the Activity
	 *
	 * @param WP_REST_Request $request Current request.
	 */
	public function pinned( $request ) {
		$activity_comment_id   = $request->get_param( 'id' );
        bp_activity_update_meta( $activity_comment_id, '_pinned_comment', 1 );

        $retval = array(
			'feedback' => __( 'Comment is successfully pinned', 'pin-comment' ),
			'activity_comment_id' => $activity_comment_id,
		);

		return rest_ensure_response( $retval );
	}

    /**
	 * Remove Activity Meta to pin the comment in the Activity
	 *
	 * @param WP_REST_Request $request Current request.
	 */
	public function unpinned( $request ) {
		$activity_comment_id   = $request->get_param( 'id' );
        bp_activity_update_meta( $activity_comment_id, '_pinned_comment', 0 );

        $retval = array(
			'feedback' => __( 'Comment is successfully unpinned', 'pin-comment' ),
			'activity_id' => $activity_comment_id,
		);

		return rest_ensure_response( $retval );
	}

}