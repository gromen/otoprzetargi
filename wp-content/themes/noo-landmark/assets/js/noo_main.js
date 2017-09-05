(function ($) {
    'use strict';

    // Effect Menu Navbar
    jQuery(document).ready(function() {

        if ( $('.header_logo_center').length > 0 ) {

            $('.navbar-magic-line').each(function(){
                var $this = $(this);
                var _classDown = 'hide-line';
                $this.append("<li class='magic-line'></li>");

                var leftPos, newWidth;
                var $magicLine = $this.find(".magic-line");
                var leftActive, widthAcitve;

                leftActive = $magicLine.position().left;
                widthAcitve = $magicLine.width();

                if($this.children("li").hasClass('current-menu-item')){
                    leftActive  = $this.children("li.current-menu-item").position().left;
                    widthAcitve = $this.children("li.current-menu-item").width();
                }

                if($this.children("li").hasClass('current-menu-parent')){
                    leftActive  = $this.children("li.current-menu-parent").position().left;
                    widthAcitve = $this.children("li.current-menu-parent").width();
                }

                if($this.children("li").hasClass('current-menu-ancestor')){
                    leftActive  = $this.children("li.current-menu-ancestor").position().left; 
                    widthAcitve = $this.children("li.current-menu-ancestor").width();
                }

                if($this.children("li").hasClass('current_page_parent')){
                    leftActive  = $this.children("li.current_page_parent").position().left; 
                    widthAcitve = $this.children("li.current_page_parent").width();
                }


                $magicLine
                    .width(widthAcitve)
                    .css("left", leftActive)
                    .data("origLeft", leftActive)
                    .data("origWidth", widthAcitve);

                if ( $this.find('.current-menu-item').length > 0 
                    || $this.find('.current-menu-parent').length > 0
                    || $this.find('.current-menu-ancestor').length > 0
                    || $this.find('.current_page_parent').length > 0 ) {
                    _classDown = '';
                }

                $magicLine.addClass(_classDown);

                $this.hover(function(){
                    $(".magic-line").not($magicLine).addClass('down-line');
                    $magicLine.addClass('up-line');
                }, function(){
                    $(".magic-line").not($magicLine).removeClass('down-line');
                    $magicLine.removeClass('up-line');
                });

                //Hover
                $this.children("li").hover(function() {
                    newWidth = $(this).width();
                    leftPos = $(this).position().left;
                    $magicLine.stop()
                        .css('left', leftPos)
                        .css('width', newWidth);
                }, function() {
                    $magicLine.stop()
                        .css('left', $magicLine.data("origLeft"))
                        .css('width', $magicLine.data("origWidth"));
                });
            });
            
        }

        // Mega Menu
        var megaLeft, 
            megaRight,
            windowWidth = $(window).width();

        if ( $('.noo-main-menu li').hasClass('noo_megamenu') ){

            if( !$('header').hasClass('header_full') ){
                megaLeft = $('.noo-main-menu').position().left;
                $('.noo-main-menu li.noo_megamenu > .sub-menu').css({
                    'left': '-'+megaLeft+'px'
                });
            }

        }

        // Header Logo Center
        if ( $('header').hasClass('header_logo_center') ){

            if ( $('.noo-left-menu li').hasClass('noo_megamenu') ){
                megaLeft = $('.noo-left-menu > ul').position().left - 15;
                $('.noo-left-menu li.noo_megamenu > .sub-menu').css({
                    'left': '-'+megaLeft+'px'
                });
            }

            if ( $('.noo-right-menu').hasClass('noo_megamenu') ){

            }

        }
        
        // Header Agency
        if (windowWidth < 1350 && windowWidth > 1200 ){   

            if( $('header').hasClass('header_agency') ){

                megaLeft    = $('.noo-main-menu').position().left + $('.noo-main-menu').width();
                megaRight   = windowWidth - megaLeft - (windowWidth - $('.copyright .noo-container').width())/2;
                $('.noo-main-menu li.noo_megamenu > .sub-menu').css({
                    'right': '-'+megaRight+'px'
                });

                $(window).resize(function() {
                    megaLeft    = $('.noo-main-menu').position().left + $('.noo-main-menu').width();
                    megaRight   = windowWidth - megaLeft - (windowWidth - $('.copyright .noo-container').width())/2;
                    $('.noo-main-menu li.noo_megamenu > .sub-menu').css({
                        'right': '-'+megaRight+'px'
                    });
                });

            }
        }

        if( windowWidth < 1550 && windowWidth > 1200){

            $(window).resize(function() {
                windowWidth = $(window).width();
                // Header Full
                if( $('header').hasClass('header_logo_full_center') ){
                    megaLeft = $('.noo-main-menu').position().left - (windowWidth - $('.copyright .noo-container').width())/2 + 40;
                    $('.noo-main-menu li.noo_megamenu > .sub-menu').css({
                        'left': '-'+megaLeft+'px'
                    });
                } else if( !$('header').hasClass('header_transparent') ){
                    megaLeft    = $('.noo-main-menu').position().left + $('.noo-main-menu').width();
                    megaRight   = windowWidth - megaLeft - (windowWidth - $('.copyright .noo-container').width())/2 - 38;
                    $('.noo-main-menu li.noo_megamenu > .sub-menu').css({
                        'right': '-'+megaRight+'px'
                    });
                }
            });

            if( $('header').hasClass('header_logo_full_center') ){
                megaLeft = $('.noo-main-menu').position().left - (windowWidth - $('.copyright .noo-container').width())/2 + 40;
                $('.noo-main-menu li.noo_megamenu > .sub-menu').css({
                    'left': '-'+megaLeft+'px'
                });
            } else if( !$('header').hasClass('header_transparent') ){
                megaLeft    = $('.noo-main-menu').position().left + $('.noo-main-menu').width();
                megaRight   = windowWidth - megaLeft - (windowWidth - $('.copyright .noo-container').width())/2 - 38;
                $('.noo-main-menu li.noo_megamenu > .sub-menu').css({
                    'right': '-'+megaRight+'px'
                });
            }
        }

        // Footer Fixed
        // btn go to top
        noo_go_to_top('.go-to-top');

        // header fix top
        noo_scroll_header_animate('.fixed_top');
        // blog masonry
        var $container = $('.noo-blog-masonry');
        var $filter = $('.masonry-filters a');

        if ($container.length > 0) {
            noo_masonry($container, '.noo-masonry-item');
            noo_masonry_filter($container, $filter);
        }
        // blog masonry & infinitescroll load

        var $container = $('.noo-blog-infinitescroll');

        $container.each(function () {
            var $this = $(this);
            var $loading_img = $this.data('img-loading');
            $(function () {
                $this.infinitescroll({
                    navSelector: '.pagination a.page-numbers',
                    nextSelector: '.pagination a.next',
                    itemSelector: '.noo-masonry-item',
                    loading: {
                        msgText: '',
                        finishedMsg: '',
                        img: $loading_img,
                        selector: '.noo-loading'
                    }
                }, function (newElements) {
                    $this.isotope('appended', newElements);
                    noo_masonry($this, '.noo-masonry-item');
                });
            });
        });

        var _footer = $('.main-footer').height() + $('.copyright').height();
        var _width = $( window ).width();
        if( $('.wrap-footer').hasClass('fixed') && _width > 991 ) {
            $('.page_fullwidth').css({
                'margin-bottom': _footer,
                'position': 'relative',
                'z-index': 10
            });
            $('#primary').css({
                'margin-bottom': _footer,
                'position': 'relative',
                'z-index': 10
            });

            $('.noo-page-heading').css({
                'position': 'relative',
                'z-index': 10
            });
        }

        $( window ).resize(function() {
            _footer = $('.main-footer').height() + $('.copyright').height();
            _width = $( window ).width();
            if( $('.wrap-footer').hasClass('fixed') && _width > 991 ) {
                $('.page_fullwidth').css({
                    'margin-bottom': _footer,
                    'position': 'relative',
                    'z-index': 10
                });
                $('#primary').css({
                    'margin-bottom': _footer,
                    'position': 'relative',
                    'z-index': 10
                });
            } else {
                $('.page_fullwidth').css({
                    'margin-bottom': 0
                });
                $('#primary').css({
                    'margin-bottom': 0
                });
            }
        });
        
        // Check Sidbar empty
        $('.noo-sidebar-wrap').each(function(){
            if ( !$.trim( $(this).html() ) ) {
                $(this).addClass('hidden');
            }
        });

        $('.bg-primary-overlay-creative-1, .bg-primary-overlay-creative-2').each(function(){
            $(this).append('<span class="skew"></span>');
        });

        // Blog gallery

        if ( jQuery('.noo-owlslider').length > 0 ) {

            jQuery('.noo-owlslider .sliders, .woocommerce .noo-related-products .noo-owlslider ul.products, .woocommerce .noo-upsell-products .noo-owlslider ul.products').each(function(){

                var _autoplay   = true;
                var _column     = 1;
                var _navigation = true;
                var _pagination = true;
                var _slideSpeed = 350;
                var _autoHeight  = true;
                var _textPrev  = "prev";
                var _textNext  = "next";

                var _resItemDesktop      = 4;
                var _resItemDesktopSmall = 2;
                var _resItemTablet       = 2;
                var _resItemMobile       = 1;

                var nooOwl = jQuery(this).closest('.noo-owlslider');

                if ( nooOwl.attr('data-autoplay') !== undefined && nooOwl.attr('data-autoplay') !== '' ) {
                    _autoplay = nooOwl.attr('data-autoplay');
                    if ( _autoplay == "true" ) _autoplay = true;
                    if ( _autoplay == "false" ) _autoplay = false;
                }

                if ( nooOwl.attr('data-autoHeight') !== undefined && nooOwl.attr('data-autoHeight') !== '' ) {
                    _autoHeight = nooOwl.attr('data-autoHeight');
                    if ( _autoHeight == "true" ) _autoHeight = true;
                    if ( _autoHeight == "false" ) _autoHeight = false;
                }

                if ( nooOwl.attr('data-column') !== undefined && nooOwl.attr('data-column') !== '' ) {
                    _column = nooOwl.attr('data-column');
                }

                if ( nooOwl.attr('data-navigation') !== undefined && nooOwl.attr('data-navigation') !== '' ) {
                    _navigation = nooOwl.attr('data-navigation');
                    if ( _navigation == "true" ) _navigation = true;
                    if ( _navigation == "false" ) _navigation = false;
                }

                if ( nooOwl.attr('data-pagination') !== undefined && nooOwl.attr('data-pagination') !== '' ) {
                    _pagination = nooOwl.attr('data-pagination');
                    if ( _pagination == "true" ) _pagination = true;
                    if ( _pagination == "false" ) _pagination = false;
                }

                if ( nooOwl.attr('data-slideSpeed') !== undefined && nooOwl.attr('data-slideSpeed') !== '' ) {
                    _slideSpeed = nooOwl.attr('data-slideSpeed');
                }

                if ( nooOwl.attr('data-textPrev') !== undefined && nooOwl.attr('data-textPrev') !== '' ) {
                    _textPrev = nooOwl.attr('data-textPrev');
                }

                if ( nooOwl.attr('data-textNext') !== undefined && nooOwl.attr('data-textNext') !== '' ) {
                    _textNext = nooOwl.attr('data-textNext');
                }

                if ( nooOwl.attr('data-resItemDesktop') !== undefined && nooOwl.attr('data-resItemDesktop') !== '' ) {
                    _resItemDesktop = nooOwl.attr('data-resItemDesktop');
                }

                if ( nooOwl.attr('data-resItemDesktopSmall') !== undefined && nooOwl.attr('data-resItemDesktopSmall') !== '' ) {
                    _resItemDesktopSmall = nooOwl.attr('data-resItemDesktopSmall');
                }

                if ( nooOwl.attr('data-resItemTablet') !== undefined && nooOwl.attr('data-resItemTablet') !== '' ) {
                    _resItemTablet = nooOwl.attr('data-resItemTablet');
                }

                if ( nooOwl.attr('data-resItemMobile') !== undefined && nooOwl.attr('data-resItemMobile') !== '' ) {
                    _resItemMobile = nooOwl.attr('data-resItemMobile');
                }
                
                jQuery(this).owlCarousel({
                    autoPlay: _autoplay, //Set AutoPlay to 3 seconds
                    items : _column,
                    itemsDesktop : [1199, _resItemDesktop],
                    itemsDesktopSmall : [979, _resItemDesktopSmall],
                    itemsTablet : [768, _resItemTablet],
                    itemsTabletSmall : false,
                    itemsMobile : [479, _resItemMobile],
                    autoHeight: _autoHeight,
                    slideSpeed : _slideSpeed,
                    rewindSpeed : _slideSpeed,
                    paginationSpeed : _slideSpeed,
                    pagination: _pagination,
                    navigation : _navigation,
                    navigationText : [_textPrev, _textNext],
                });
            });
        }

        var is_touch = function(){
            return !!('ontouchstart' in window) || ( !! ('onmsgesturechange' in window) && !! window.navigator.maxTouchPoints);
        }

        if ( $('.noo-parallax').length > 0 ) {
            $('.noo-parallax').parallax();
        }

        /**
         * Process widget post slider
         */
        if ( $('.noo-widget-post-slider-wrap').length > 0 ) {

            $('.noo-widget-post-slider-wrap').each(function(index, el) {
               
                var post_slider = $(this),
                    tag_id      = post_slider.data('id'),
                    columns     = post_slider.data('columns');

                /**
                 * Process data
                 */
                var postSliderOptions = {
                        infinite: true,
                        circular: true,
                        responsive: true,
                        debug : false,
                        width: '100%',
                        height: 'variable',
                        scroll: {
                          items: columns,
                          duration: 600,
                          pauseOnHover: "resume",
                          fx: "scroll"
                        },
                        auto: {
                          timeoutDuration: 3000,
                          play: false
                        },

                        prev : {button:"#" + tag_id + " .noo_slider_prev"},
                        next : {button:"#" + tag_id + " .noo_slider_next"},
                        swipe: {
                          onTouch: true,
                          onMouse: true
                        },
                        items: {
                            visible: {
                              min: 1,
                              max: columns
                            },
                            height:'variable'
                        }
                    };
                    $('#' + tag_id + ' .widget-post-slider-content').carouFredSel(postSliderOptions);
                    imagesLoaded('#' + tag_id + ' .widget-post-slider-content',function(){
                        $('#' + tag_id + ' .widget-post-slider-content').trigger('updateSizes');
                    });
                    $(window).resize(function(){
                        $('#' + tag_id + ' .widget-post-slider-content').trigger("destroy").carouFredSel(postSliderOptions);
                    });

            });            

        }

        /**
         * Process event widget gallery
         */
        if ( $('.noo-widget-gallery-wrap').length > 0 ) {

            $('.noo-widget-gallery-wrap').each(function(index, el) {
               
                var noo_gallery = $(this),
                    tag_id      = noo_gallery.data('id');

                $("#" + tag_id + " .widget_gallery_wrap").lightGallery({
                    thumbnail:true,
                    animateThumb: true,
                    showThumbByDefault: true
                }); 

            });

        }

        /**
         * Process event on minicar
         */
        if ( $( '.noo-header-minicart .noo_wrap_minicart .cart_list .mini_cart_item' ).length > 4 ) {
            $( '.noo-header-minicart .noo_wrap_minicart .cart_list' ).bxSlider({
                mode: 'vertical',
                speed: 250,
                infiniteLoop: false,
                adaptiveHeight: true,
                pager: false,
                minSlides: 3,
                maxSlides: 3,
                prevText: '<i class="fa fa-chevron-up"></i>',
                nextText: '<i class="fa fa-chevron-down"></i>',
            });
        }

    });

    if ( $(".nav-collapse.navbar-nav").length > 0 ) {
        /**
         *  Effect Navbar Menu
         *  Add Magic Line markup via JavaScript, because it ain't gonna work without 
         */
        $(".nav-collapse.navbar-nav").append("<li class='magic-line'></li>");
        var $el, leftPos, newWidth;
        /* Cache it */
        var $magicLine = $(".magic-line");
        var leftActive, widthAcitve;
        var _classDown = 'hide-line';

        leftActive = $magicLine.position().left;
        widthAcitve = $magicLine.width();

        if($(".nav-collapse.navbar-nav > li").hasClass('current-menu-item')){
            leftActive  = $(".current-menu-item").position().left;
            widthAcitve = $(".current-menu-item").width();
        }

        if($(".nav-collapse.navbar-nav > li").hasClass('current-menu-parent')){
            leftActive  = $(".current-menu-parent").position().left;
            widthAcitve = $(".current-menu-parent").width();
        }

        if($(".nav-collapse.navbar-nav > li").hasClass('current-menu-ancestor')){
            leftActive  = $(".current-menu-ancestor").position().left; 
            widthAcitve = $(".current-menu-ancestor").width();
        }

        $magicLine.addClass(_classDown);

        $magicLine
            .width(widthAcitve)
            .css("left", leftActive)
            .data("origLeft", leftActive)
            .data("origWidth", widthAcitve);

        $(window).resize(function() {
            if($(".nav-collapse.navbar-nav > li").hasClass('current-menu-item')){
                leftActive  = $(".current-menu-item").position().left;
                widthAcitve = $(".current-menu-item").width();
            }

            if($(".nav-collapse.navbar-nav > li").hasClass('current-menu-parent')){
                leftActive  = $(".current-menu-parent").position().left;
                widthAcitve = $(".current-menu-parent").width();
            }

            if($(".nav-collapse.navbar-nav > li").hasClass('current-menu-ancestor')){
                leftActive  = $(".current-menu-ancestor").position().left; 
                widthAcitve = $(".current-menu-ancestor").width();
            }
            $magicLine
                .width(widthAcitve)
                .css("left", leftActive)
                .data("origLeft", $magicLine.position().left)
                .data("origWidth", $magicLine.width());
        });
            
        $(".nav-collapse.navbar-nav > li").hover(function() {
            $el = $(this);
            newWidth = $el.width();
            leftPos = $(this).position().left;
            $magicLine.stop()
                .css('left', leftPos)
                .css('width', newWidth);
        }, function() {
            $magicLine.stop()
                .css('left', $magicLine.data("origLeft"))
                .css('width', $magicLine.data("origWidth"));
        });

    }

    // Define functions

    function noo_masonry(container, element) {
        container.imagesLoaded(function () {
            container.isotope({
                itemSelector: element,
                transitionDuration: '0.8s',
                masonry: {
                    'gutter': 0
                }
            });
        });
    }

    function noo_masonry_filter(container, filter) {
        filter.click(function (e) {
            e.stopPropagation();
            e.preventDefault();
            var $this = $(this);
            // don't proceed if already selected
            if ($this.hasClass('selected')) {
                return false;
            }
            var filters = $this.closest('ul');
            filters.find('.selected').removeClass('selected');
            $this.addClass('selected');

            var options = {},
                key = filters.attr('data-option-key'),
                value = $this.attr('data-option-value');

            value = value === 'false' ? false : value;
            options[key] = value;

            container.isotope(options);

        });
    }

    // Go to top
    function noo_go_to_top(element) {
        $(window).scroll(function () {
            if ($(this).scrollTop() > 500) {
                $(element).addClass('on');
            }
            else {
                $(element).removeClass('on');
            }
        });
        $('body').on('click', element, function () {
            $("html, body").animate({
                scrollTop: 0
            }, 800);
            return false;
        });

    }

    function noo_scroll_header(element) {
        var _top = $(window).scrollTop();
        var _fixtop = $(element);
        var _heightHeader = _fixtop.height();
        if (_top > 0 && _fixtop.length > 0) {
            _fixtop.addClass('fixed_top_eff');
            _fixtop.next().css('padding-top', _heightHeader + 'px');
        } else {
            _fixtop.removeClass('fixed_top_eff');
            _fixtop.next().css('padding-top', '0');
        }
    }

    function noo_scroll_header_animate(element) {
        if( !$('body').hasClass('page-template-property-half-map') ){
            if( $('.noo-header').hasClass('fixed_top') && !$('.noo-header').hasClass('header-transparent') ){

                var $html_fixed    = '<div class="height-fixed"></div>';
                var $height_header = $('.fixed_top').height();
                $('.fixed_top').before($html_fixed);
                $('.height-fixed').height($height_header);

            }
        }
        var last_scroll = 0;
        $(window).scroll(function () {
            
            var _top = $(this).scrollTop();
            var _height = $(element).height();
            var _heightbar = _height - $('.navbar').height();

            // Fixed Top
            if( !$(element).hasClass('fixed_top_bar') ){

                if( !$(element).hasClass('fixed_scroll_up') ){
                    
                    if(_top != 0) {// Top Bar Not Fixed
                        if (!$(element).hasClass('fixed_top_eff')) {
                            $(element).addClass('fixed_top_eff').animate({
                                'marginTop': -_heightbar
                            });
                        }
                    } else {// Top Bar Fixed
                        $(element).addClass('eff');
                        $(element).removeClass('fixed_top_eff').css({
                            'marginTop': 0
                        });
                    }
                }

            } else {

                // Fixed Top Bar
                if (!$(element).hasClass('fixed_top_eff')) {
                    $(element).addClass('eff');
                    $(element).addClass('fixed_top_eff').css({
                        'marginTop': 0
                    });
                }
                if ( _top == 0 ){
                    $(element).removeClass('eff');
                } else {
                    $(element).addClass('eff');
                }

            }
            
            //Fixed Top When Scroll Up
            if( $(element).hasClass('fixed_scroll_up') ) {
                
                if(_top > last_scroll) { // Scroll down
                    if(_top > 200) {
                        $(element).addClass('eff');
                        $(element).css('marginTop', -_height);
                    }
                } else { //Scroll up
                    $(element).addClass('fixed_top_eff eff');
                    if( !$(element).hasClass('fixed_top_bar') ){// Top Bar Not Fixed
                        $(element).css({
                            'marginTop': -_heightbar
                        });
                    } else {// Top Bar Fixed
                        $(element).css({
                            'marginTop': 0
                        });
                    }

                    if(_top < _heightbar) {
                        $(element).addClass('eff');
                        $(element).css({
                            'marginTop': 0
                        });
                    }

                    if( _top == 0 ){
                        $(element).removeClass('eff');
                    }
                }

            }

            last_scroll = _top;
            
        });
    }

})(jQuery);