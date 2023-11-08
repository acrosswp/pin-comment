<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://acrosswp.com
 * @since      1.0.0
 *
 * @package    Pin_Comment
 * @subpackage Pin_Comment/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Pin_Comment
 * @subpackage Pin_Comment/public
 * @author     AcrossWP <contact@acrosswp.com>
 */
class Pin_Comment_Public {

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
	 * The meta key for the comment
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $pin_comment_key    The meta key for the comment.
	 */
	public $pin_comment_key = '_pinned_comment';

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
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, PIN_COMMENT_PLUGIN_URL . 'assets/dist/css/frontend-style.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, PIN_COMMENT_PLUGIN_URL . 'assets/dist/js/frontend-script.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register the Localize Script for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function wp_localize_script() {

		wp_localize_script( $this->plugin_name, 'pin_comment_object',
			array( 
				'pin_text' => __( 'Pin Comment', 'pin-comment' ),
				'unpin_text' => __( 'Unpin Comment', 'pin-comment' ),
				'pin_url' => rest_url( 'pin-comment/v1/pin/' ),
				'unpin_url' => rest_url( 'pin-comment/v1/unpin/' ),
				'nonce' => wp_create_nonce( "wp_rest" ),
			)
		);
	}

	/**
	 * Filter to add the Pinned Comment on Activity
	 */
	public function activity_comment_bubble_buttons( $buttons, $activity_comment_id, $activity_id ) {

		$current_comment = bp_activity_current_comment();

		/**
		 * Check if this is a parent activity or not
		 */
		if ( $current_comment->item_id == $current_comment->secondary_item_id ) {
			$pinned_action_label = __( 'Pin Comment', 'pin-comment' );
			$pinned_action_class = 'pin-activity-comment main-pin-activity-comment';
			if( bp_activity_get_meta( $activity_comment_id, $this->pin_comment_key, true ) ) {
				$pinned_action_class = 'unpin-activity-comment main-pin-activity-comment';
				$pinned_action_label = __( 'Unpin Comment', 'pin-comment' );
			}
	
			$buttons['activity_pin'] = array(
				'id'                => 'activity_pin',
				'component'         => 'activity',
				'must_be_logged_in' => true,
				'button_attr'       => array(
					'id'            => '',
					'href'          => '',
					'class'         => 'button item-button bp-secondary-action ' . $pinned_action_class,
					'data-bp-nonce' => '',
				),
				'link_text'         => sprintf(
					'<span class="bp-screen-reader-text">%s</span><span class="delete-label">%s</span>',
					$pinned_action_label,
					$pinned_action_label
				),
			);
		}

		return $buttons;
	}

	/**
	 * Show the Pinned Comment first in the Activity Comment Area
	 */
	public function get_misc_sql( $misc_sql ) {

		$misc_sql = 'ORDER BY m.meta_value DESC, a.date_recorded ASC';

		return $misc_sql;
	}

	/**
	 * Show the Pinned Comment first in the Activity Comment Area
	 */
	public function get_join_sql( $from_sql ) {

		$bp = buddypress();

		$from_sql .= " LEFT JOIN {$bp->activity->table_name_meta} m ON ( m.activity_id = a.id )";

		return $from_sql;
	}

	/**
	 * Show the Pinned Comment first in the Activity Comment Area
	 */
	public function get_where_conditions( $where_sql ) {

		$bp = buddypress();

		$where_sql .= " AND ( m.meta_key = '_pinned_comment' )";

		return $where_sql;
	}

	/**
	 * Comment class inside the activity
	 */
	public function activity_comment_css_class( $class ) {

		$activity_comment_id = bp_get_activity_comment_id();
		if( bp_activity_get_meta( $activity_comment_id, $this->pin_comment_key, true ) ) {
			$class .= ' _pin_comment';
		}

		return $class;
	}

	/**
	 * Pin Activity Comment Popup
	 */
	public function popup_for_pin_comment() {
		add_action( 'bp_after_directory_activity_list', array( $this, 'bb_activity_comment_pinpost_modal' ) );
		add_action( 'bp_after_member_activity_content', array( $this, 'bb_activity_comment_pinpost_modal' ) );
		add_action( 'bp_after_group_activity_content', array( $this, 'bb_activity_comment_pinpost_modal' ) );
		add_action( 'bp_after_single_activity_content', array( $this, 'bb_activity_comment_pinpost_modal' ) );
	}


	/**
	 * Run the Pinn Post comment
	 */
	public function bb_activity_comment_pinpost_modal() {
		?>
		<div id="bb-pin-comment-confirmation-modal" class="bb-pin-comment-confirmation-modal bb-action-popup" style="display: none;">
			<transition name="modal">
				<div class="modal-mask bb-white bbm-model-wrap bbm-uploader-model-wrap">
					<div class="modal-wrapper">
						<div class="modal-container">
							<header class="bb-model-header">
								<h4><?php esc_html_e( 'Pin Activty Comment', 'pinn-comment' ); ?></h4>
								<a class="bb-close-action-popup bb-model-close-button" id="bp-confirmation-model-close" href="#">
									<span class="bb-icon-l bb-icon-times"></span>
								</a>
							</header>
							<div class="bb-action-popup-content">
							</div>
						</div>
					</div>
				</div>
			</transition>
		</div>
		<?php
	}
}
