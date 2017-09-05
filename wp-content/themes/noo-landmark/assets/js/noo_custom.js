(function ($) {
    'use strict';

    // resize window
    // Header Fullwidth Logo Center
    resize_window();
    jQuery( window ).resize(function() {
        "use strict";
        resize_window();
    });
    function resize_window() {

        "use strict";
        
        // Header Fullwidth Logo Center
        if ( jQuery('.header_full').length > 0 ) {
            if ( jQuery( window ).width() < 1400 || jQuery('.header_full').width() < 1400 ) {
                if ( jQuery('header').find('.noo-menu-option').find('li').length > 0 )
                    jQuery('header').find('.noo-menu-option').addClass('collapse');
            } else {
                jQuery('header').find('.noo-menu-option').removeClass('collapse');
            }
        }
    }

    jQuery('.button-menu-extend').click(function(){
        jQuery('.noo-menu-extend-overlay').fadeIn(1, function(){
            jQuery('.noo-menu-extend').addClass('show');    
        }).addClass('show');
        return false;
    });
    jQuery('.menu-closed, .noo-menu-extend-overlay').click(function(){
        jQuery('.noo-menu-extend-overlay').removeClass('show').hide();
        jQuery('.noo-menu-extend').removeClass('show');
    });
    // Header Agency
    $('.menu-header3').click(function(){
        $('.header_agency .navbar').toggleClass('eff');
        $(this).toggleClass('eff');
    });

})(jQuery);