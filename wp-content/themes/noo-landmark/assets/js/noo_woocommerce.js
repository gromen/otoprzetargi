(function ($) {
    'use strict';

    jQuery(document).ready(function($) {

        if ( $('.noo-product-masonry').length > 0 ) {

            $('.noo-product-masonry').imagesLoaded(function () {
                $('.noo-product-masonry').isotope({
                    itemSelector: '.noo-product',
                    transitionDuration: '0.8s',
                    masonry: {
                        'gutter': 0
                    }
                });
            });
        }

        function noo_landmark_func_add_class_wishlist_table() {
            var _product_name = $( 'table.wishlist_table' ).find('th.product-name').text();
            var _product_price = $( 'table.wishlist_table' ).find('th.product-price').text();
            var _product_status = $( 'table.wishlist_table' ).find('th.product-stock-stauts').text();

            $( 'table.wishlist_table' ).find('td.product-name').attr('data-title', _product_name);
            $( 'table.wishlist_table' ).find('td.product-price').attr('data-title', _product_price);
            $( 'table.wishlist_table' ).find('td.product-stock-status').attr('data-title', _product_status);

            $( 'table.wishlist_table' ).addClass( 'shop_table_responsive' );
        }

        function noo_landmark_func_add_class_to_custom_action() {
            $( '.noo-loop-featured-item' ).find( '.custom-action' ).each(function(){
                var $count = 0;
                if ( $(this).find('.add_to_cart_button').length > 0 ) {
                    $count ++;
                }
                if ( $(this).find('.compare-button').length > 0 ) {
                    $count ++;
                }
                if ( $(this).find('.yith-wcwl-add-to-wishlist').length > 0 ) {
                    $count ++;
                }
                if ( $(this).find('.noo-quick-view').length > 0 ) {
                    $count ++;
                }
                $(this).addClass( 'button-'+$count );
            });
        }

        function noo_landmark_func_add_class_to_layout_shop() {

            $('span.noo-list').click(function(){
                $('span.noo-grid').removeClass('active');
                $(this).addClass('active');
                $('.archive.woocommerce ul.products').fadeOut(300, function(){
                    $(this).removeClass('product-grid').addClass('product-list');
                });
                $('.archive.woocommerce ul.products').fadeIn(300);
                setCookie("noo_landmark_cookie_product_list", 'list', 30);
                noo_removeCookie('noo_landmark_cookie_product_grid');
            });

            $('span.noo-grid').click(function(){
                $('span.noo-list').removeClass('active');
                $(this).addClass('active');
                $('.archive.woocommerce ul.products').fadeOut(300, function(){
                    $(this).removeClass('product-list').addClass('product-grid');
                });
                $('.archive.woocommerce ul.products').fadeIn(300);
                setCookie("noo_landmark_cookie_product_grid", 'grid', 30);
                noo_removeCookie('noo_landmark_cookie_product_list');
            });

            if( getCookie('noo_landmark_cookie_product_list') == 'list' ){
                $('span.noo-list').addClass('active');
                $('span.noo-grid').removeClass('active');
                $('.archive.woocommerce ul.products').removeClass('product-grid').addClass('product-list');
            }
            if( getCookie('noo_landmark_cookie_product_grid') == 'grid' ){
                $('span.noo-grid').addClass('active');
                $('span.noo-list').removeClass('active');
                $('.archive.woocommerce ul.products').removeClass('product-list').addClass('product-grid');
            }

        }

        function setCookie(name,value,days) {
            "use strict";
            if (days) {
                var date = new Date();
                date.setTime(date.getTime()+(days*24*60*60*1000));
                var expires = "; expires="+date.toGMTString();
            }
            else var expires = "";
                document.cookie = name+"="+value+expires+"; path=/";
        }

        function getCookie(cname) {
            "use strict";
            var name = cname + "=";
            var ca = document.cookie.split(';');
            for(var i=0; i<ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0)==' ') c = c.substring(1);
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }
        function noo_removeCookie(name) {
            "use strict";
            setCookie(name,"",-1);
        }

    	$( document ).on(
			'click button',
			'div.woocommerce > form .cart_item .quantity button',
			function() {
				$( 'div.woocommerce > form input[name="update_cart"]' ).prop( 'disabled', false );
			});

        $( 'body' )
            .off( 'click', '.wc-tabs li a, ul.tabs li a')

            .on( 'click', '.wc-tabs li a, ul.tabs li a', function( e ) {
                e.preventDefault();
                var $tab          = $( this );
                var $tabs_wrapper = $tab.closest( '.wc-tabs-wrapper, .woocommerce-tabs' );
                var $tabs         = $tabs_wrapper.find( '.wc-tabs, ul.tabs' );

                $tabs.find( 'li' ).removeClass( 'active' );
                $tabs_wrapper.find( '.wc-tab, .panel:not(.panel .panel)' ).slideUp(300);

                $tab.closest( 'li' ).addClass( 'active' );
                $tabs_wrapper.find( $tab.attr( 'href' ) ).slideDown(300);
            });

        //quick view
        $('.noo-quick-view').live('click',function(event){
            event.preventDefault();
            var p_id  = $(this).data('id');
            var $html = '';
            $html += '<div class="quick-view-overlay">';
            $html += '<p class="quick-loading"><i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i></p>';
            $html += '</div>';
            $html += '<div class="quick-content woocommerce">';
            $html +=  '<button class="quickview-close"></button>';
            $html += '</div>';
            
            $('body').append($html);

            $.ajax({
                type: 'post',
                url : nooL10n.ajax_url,
                data: ({
                    action: 'noo_landmark_func_product_quick_view',
                    p_id: p_id
                }),
                success: function(data){
                    if(data){
                        $('.quick-loading').remove();
                        $('.quick-content').append(data).addClass('eff');

                    }

                }
            })

        });

        $(document).keyup(function (e) {

            if( e.keyCode == 27 ){
                $('.quick-content').removeClass('eff');
                var myVar;
                myVar = setTimeout(function(){
                    $('.quick-view-overlay').remove();
                    $('.quick-content').remove();
                }, 200);
            }
        });

        $('body').on('click','.quickview-close, .quick-view-overlay',function(){
            $('.quick-content').removeClass('eff');
            var myVar;
            myVar = setTimeout(function(){
                $('.quick-view-overlay').remove();
                $('.quick-content').remove();
            }, 200);
        });
            
        noo_landmark_func_add_class_to_custom_action();
        noo_landmark_func_add_class_to_layout_shop();
        noo_landmark_func_add_class_wishlist_table();
    });

})(jQuery);