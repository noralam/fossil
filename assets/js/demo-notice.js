/**
 * Demo Notice JS
 *
 * Handles the dismissal of the demo notice via AJAX.
 */
(function($) {
    'use strict';

    jQuery(document).ready(function($) {
    // Handle the dismiss link click for upgrade notice
    $('.fossil-dismiss-link').on('click', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: fossilUpgradeNotice.ajaxUrl,
            type: 'POST',
            data: {
                action: 'fossil_dismiss_upgrade_notice',
                nonce: fossilUpgradeNotice.nonce
            },
            success: function() {
                $('.fossil-upgrade-notice').fadeOut();
            }
        });
    });


     $('.fossil-dismiss-notice').on('click', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: ajaxurl, // WordPress global variable for admin-ajax.php
            type: 'POST',
            data: {
                action: 'fossil_dismiss_documentation_notice',
                nonce: $(this).data('nonce')
            },
            success: function() {
                $('.fossil-documentation-notice').fadeOut();
            }
        });
    });
});
    
})(jQuery);