/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
jQuery( document ).ready( function($) {
    jQuery( "body" ).on( 'click', 'a.main-pin-activity-comment', function(e) {
        e.preventDefault();

        var target = $(this);

		var message_modal   = $( '#bb-pin-comment-confirmation-modal' );

        var activity_id = $(this).parents( "li" ).attr( "data-bp-activity-comment-id" );
        var url = pin_comment_object.unpin_url;
        var comment_class = 'pin-activity-comment';
        var comment_text = pin_comment_object.pin_text;

        /**
         * Check if the user is try to pin the comment
         */
        if( $( this ).hasClass( 'pin-activity-comment' ) ) {
            url = pin_comment_object.pin_url;
            var comment_class = 'unpin-activity-comment';
            var comment_text = pin_comment_object.unpin_text;
        }

        url = url + activity_id + '/';

        jQuery.ajax({
            type : "POST",
            url : url,
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-WP-Nonce', pin_comment_object.nonce );
            },
            success: function(response) {
                message_modal.find('.bb-action-popup-content').html( response.feedback );
                message_modal.show();

                target.removeClass( 'pin-activity-comment' );
                target.removeClass( 'unpin-activity-comment' );

                target.addClass( comment_class );
                target.find( 'span' ).text( comment_text );
            }
        });
    });
 });
/******/ })()
;