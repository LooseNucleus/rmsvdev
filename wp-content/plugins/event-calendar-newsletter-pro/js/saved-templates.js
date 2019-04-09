(function($){
    $(document).ready(function(){
        $('.clear_cache a').on('click', function(event){
            event.preventDefault();
            $.get( $(this).attr('href'), function(message) {
                alert(message);
            });
        });

        $('.feed_event_publication_date').on('change', function(){
            var self = this;
            $('#spinner-' + $(self).attr('data-id')).addClass('is-active');
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'ecn_pro_save_template_feed_event_publication_date',
                    'nonce': $('#wp_ecn_admin_nonce').val(),
                    'saved_template_id': $(self).attr('data-id'),
                    'feed_event_publication_date': $(self).val()
                },
                success: function(msg) {
                    $('#spinner-' + $(self).attr('data-id')).removeClass('is-active');
                    $('#feed_event_publication_date_message-' + $(self).attr('data-id')).html(msg);
                    setTimeout(function(){
                        $('#feed_event_publication_date_message-' + $(self).attr('data-id')).html('');
                    }, 5000);
                },
                error: function(v, msg) {
                    $('#feed_event_publication_date_message-' + $(self).attr('data-id')).html(msg);
                }
            });
        });
    });
})(jQuery);