jQuery(document).ready(function(){
    "use strict";

    //Navbar mobile
    jQuery('#off-canvas-nav li.menu-item-has-children').append('<i class="fa fa-angle-down"></i>');
    jQuery('#off-canvas-nav li.menu-item-has-children i').on("click", function (e) {
        var link_i = jQuery(this); //preselect the link
        link_i.prev().slideToggle(300);
        link_i.parent().toggleClass('active');
    });
    
});