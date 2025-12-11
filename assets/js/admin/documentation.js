jQuery(document).ready(function($) {
    // Handle plugin installation/activation
    $('.fossil-plugin-button').on('click', function(e) {
        e.preventDefault();
        
        var button = $(this);
        var action = button.data('action');
        var slug = button.data('slug');
        
        if (button.hasClass('disabled')) {
            return;
        }
        
        button.addClass('updating-message').text(action === 'install' ? 'Installing...' : 'Activating...');
        
        $.ajax({
            url: fossilDocs.ajaxUrl,
            type: 'POST',
            data: {
                action: 'fossil_handle_plugin_action',
                plugin_action: action,
                plugin_slug: slug,
                nonce: fossilDocs.nonce
            },
            success: function(response) {
                if (response.success) {
                    button.removeClass('updating-message')
                          .addClass('disabled')
                          .text('Already Active')
                          .attr('disabled', 'disabled');
                } else {
                    button.removeClass('updating-message').text('Error');
                    alert(response.data.message || 'An error occurred.');
                }
            },
            error: function() {
                button.removeClass('updating-message').text('Error');
                alert('An error occurred. Please try again.');
            }
        });
    });
    
   

    // Also handle the WordPress dismiss button
    $(document).on('click', '.fossil-documentation-notice .notice-dismiss', function() {
        $.ajax({
            url: ajaxurl, // WordPress global variable for admin-ajax.php
            type: 'POST',
            data: {
                action: 'fossil_dismiss_documentation_notice',
                nonce: $('.fossil-dismiss-notice').data('nonce')
            }
        });
    });
});