jQuery( document ).ready( function($) {
    jQuery( "body" ).on( 'click', 'a.main-pin-activity-comment', function(e) {
        e.preventDefault();

        var target = $(this);

		var message_modal   = $( '#bb-pin-comment-confirmation-modal' );

        var parent_li = $(this).closest( "li" )

        var activity_id = parent_li.attr( "data-bp-activity-comment-id" );
        var url = pin_comment_object.unpin_url;
        var comment_class = 'pin-activity-comment';
        var comment_text = pin_comment_object.pin_text;
        var parent_class = '_unpin_comment';

        /**
         * Check if the user is try to pin the comment
         */
        if( $( this ).hasClass( 'pin-activity-comment' ) ) {
            url = pin_comment_object.pin_url;
            var comment_class = 'unpin-activity-comment';
            var comment_text = pin_comment_object.unpin_text;
            var parent_class = '_pin_comment';
        }

        url = url + activity_id + '/';

        jQuery.ajax({
            type : "POST",
            url : url,
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-WP-Nonce', pin_comment_object.nonce );
            },
            success: function(response) {

                /**
                 * Show popup
                 */
                message_modal.find('.bb-action-popup-content').html( response.feedback );
                message_modal.show();

                /**
                 * Update the class on the Button
                 */
                target.removeClass( 'pin-activity-comment' );
                target.removeClass( 'unpin-activity-comment' );

                target.addClass( comment_class );
                target.find( 'span' ).text( comment_text );


                /**
                 * Update the li class
                 */
                parent_li.removeClass( '_pin_comment' );
                parent_li.removeClass( '_unpin_comment' );
                parent_li.addClass( parent_class );
            }
        });
    });
 });