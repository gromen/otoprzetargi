(function ( $ ) {
	function noo_number_format(number, decimals, dec_point, thousands_sep) {
		'use strict';
	  	number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
		var n = !isFinite(+number) ? 0 : +number,
		prec  = !isFinite(+decimals) ? 0 : Math.abs(decimals),
		sep   = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
		dec   = (typeof dec_point === 'undefined') ? '.' : dec_point,
		s     = '',
	    toFixedFix = function(n, prec) {
	      	var k = Math.pow(10, prec);
	      	return '' + (Math.round(n * k) / k).toFixed(prec);
	    };
	  	// Fix for IE parseFloat(0.55).toFixed(0) = 0;
	  	s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
	  	if (s[0].length > 3) {
	    	s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	  	}
	  	if ((s[1] || '').length < prec) {
	    	s[1] = s[1] || '';
	    	s[1] += new Array(prec - s[1].length + 1).join('0');
	  	}
	  	return s.join(dec);
	}

	function noo_price(price){
		'use strict';
		var $currency_position = Noo_Property.currency_position,
			$format;
		switch ( $currency_position ) {
			case 'left' :
				$format = '%1$s%2$s';
				break;
			case 'right' :
				$format = '%2$s%1$s';
				break;
			case 'left_space' :
				$format = '%1$s&nbsp;%2$s';
				break;
			case 'right_space' :
				$format = '%2$s&nbsp;%1$s';
				break;
		}
		price = noo_number_format( price, Noo_Property.num_decimals, Noo_Property.decimal_sep, Noo_Property.thousands_sep )
		return $format.replace('%1$s', Noo_Property.currency ).replace( '%2$s',price );
	}

	function noo_notice( txt, status ) {
		'use strict';
		$('body').find('.noo-notice-message').append('<div class="noo-notice-message-item ' + status + '">' + txt + '</div>');
		setTimeout(function() {
			$('body').find('.noo-notice-message').html('');
		}, 5000);
	}

	jQuery(document).ready(function($) {
		'use strict';

	    /**
	     * Process event when submit property
	     */
	    	if ( $('#noo-property-submit').length ) {

				$('#noo-property-submit').find('.noo-property-action').on('click', 'button', function(event) {
					event.preventDefault();

					$('body').append('<div class="noo-notice-message"></div>');
					$('body').find('.noo-notice-message').html('');

					var $$          = $(this),
						data 	    = $('#noo-property-submit').serializeArray(),
						description = tinyMCE.activeEditor.getContent();

					/**
					 * Validate form field
					 */
						var status_validate = false;
						$('#noo-property-submit').find( '.noo-item-wrap' ).removeClass('validate-error').find('.noo-tooltip').hide();

						/**
						 * Validate input
						 */
							$('#noo-property-submit').find( '.noo-item-wrap' ).find('input.required:not([type=radio], [type=checkbox])').each(function(index, el) {
								var $$ 		    = $(this),
									value_field = $$.val();

								if ( typeof value_field === 'undefined' || value_field == '' ) {
									$$.closest('.noo-item-wrap').addClass('validate-error').find('.noo-tooltip').show();

									status_validate = true;

									/**
									 * Show message
									 */
									noo_notice( $$.closest('.noo-item-wrap').find('>label').data('notice'), 'error' );
								}

							});

						/**
						 * Validate radio
						 */
							$('#noo-property-submit').find( '.noo-item-wrap' ).find('input[type=radio].required').each(function(index, el) {
								var $$ 			  = $(this),
									value_checked = $$.prop('checked');

								if ( typeof value_checked === 'undefined' || value_checked == '' || value_checked === 'false' ) {
									$$.closest('.noo-item-wrap').addClass('validate-error').find('.noo-tooltip').show();

									status_validate = true;

									/**
									 * Show message
									 */
									noo_notice( $$.closest('.noo-item-wrap').find('>label').data('notice'), 'error' );

								}

							});

						/**
						 * Validate checkbox
						 */
							$('#noo-property-submit').find( '.noo-item-wrap' ).find('input[type=checkbox].required').each(function(index, el) {
								var $$ 			  = $(this),
									value_checkbox = $$.prop('checked');

								// if ( typeof value_checkbox === 'undefined' || value_checkbox == '' || value_checkbox === 'false' ) {
								// 	$$.closest('.noo-item-wrap').addClass('validate-error').find('.noo-tooltip').show();

								// 	status_validate = true;
								// }

							});

						/**
						 * Validate select
						 */
							$('#noo-property-submit').find( '.noo-item-wrap' ).find('select.required').each(function(index, el) {
								var $$ 			  = $(this),
									value_selected = $$.find("option:selected");

								if ( typeof value_selected === 'undefined' || value_selected == '' || value_selected === 'false' ) {
									$$.closest('.noo-item-wrap').addClass('validate-error').find('.noo-tooltip').show();

									status_validate = true;

									/**
									 * Show message
									 */
									noo_notice( $$.closest('.noo-item-wrap').find('>label').data('notice'), 'error' );

								}

							});


						/**
						 * Validate featured image
						 */
							if ( $('body').find('#set_featured').val() === '' ) {
								$('#noo-item-property_photo-wrap').addClass('validate-error').find('.noo-tooltip').show();

								status_validate = true;

								/**
								 * Show message
								 */
								noo_notice( Noo_Property.notice_property_img, 'error' );

							}

						if ( status_validate ) {
							setTimeout(function(){
					            $('#noo-property-submit').focus();
					        }, 1);
					        $('body').find('.noo-notice-message').find('.noo-notice-message-item').each(function(index, el) {
					        	var remove_item = $(this);
								setTimeout(function() {
									remove_item.remove();
									remove_item.hide();
								}, 6000 * (index+1));
							});
							return;
						}

					data.push(
						{
							'name' : 'description',
							'value' : description
						},
						{
							'name' : 'action',
							'value' : 'noo_submit_property'
						},
						{
							'name' : 'security',
							'value' : Noo_Property.security
						}
					)

					$.ajax({
						url: Noo_Property.ajax_url,
						type: 'POST',
						dataType: 'html',
						data: data,
						beforeSend: function() {
							$$.find('>i').removeClass('hide');
						},
						success: function(property) {
							$$.find('>i').addClass('hide');
							property = $.parseJSON(property);
							if ( property.status === 'success' ) {

								/**
								 * Show message
								 */
								noo_notice( property.msg, property.status );

								if ( typeof property.url !== 'undefined' && property.url !== '' ) {
									window.location.href = property.url;
									console.log(property.url);
								}
							}
						}
					})

					$('body').find('.noo-notice-message').find('.noo-notice-message-item').each(function(index, el) {
						setTimeout(function() {
							$(this).remove();
						}, 2000);
					});

				});

				var clone_floor_plan = function() {

					$('#noo-item-floor_plan_wrap-wrap .noo-clone-floor-plan').on('click', '.add-floor-plan', function(event) {
						event.preventDefault();
						var btn_clone     = $(this),
							total         = btn_clone.data('total'),
							content_clone = $('#clone_element').clone(true).html();

						content_clone = content_clone.replace( /\[0\]/g, '[' + (total+1) + ']' );
						$('.content-clone').append( '<div class="noo-floor-plans-wrap noo-md-12">' + content_clone + '</div>' );
						btn_clone.data( 'total', total+1 );

						noo_load_upload();

					});

				}
				clone_floor_plan();


				var remove_floor_plan = function() {

					$('body').on('click', '.remove-floor-plan', function(event) {
						event.preventDefault();
						$(this).closest('.noo-floor-plans-wrap').remove();
					});

				}
				remove_floor_plan();

	    	}

	    /**
	     * Process property slider
	     */
	    	if ( $('.noo-box-property-slider').length > 0 ) {

	    		$('.noo-box-property-slider').each(function(index, el) {

	    			var property_slider  = $(this),
	    				item 	         = property_slider.data('item');

	    // 			var property_slider = $$.find('.noo-list-property').owlCarousel({

					//     items : item,
					//     itemsDesktop : [1199,item],
					//     itemsDesktopSmall : [979,item],

					//     autoHeight : true,
					//     pagination : false,

					// });

					// $$.find('.next-property').click(function(){
					//     property_slider.trigger('owl.next');
					// })
					// $$.find('.prev-property').click(function(){
					//     property_slider.trigger('owl.prev');
					// })

					// var total_item = $$.find('.owl-item').length;
	    // 			if ( total_item < 4 ) {
	    // 				$$.find('.noo-action-slider').hide();
	    // 			}

	    			property_slider.find( '.noo-list-property' ).slick({
						slidesToShow: item,
						speed: 300,
						dots: false,
						arrows: false,
						responsive: [
					    {
					      	breakpoint: 991,
					      	settings: {
					        	slidesToShow: 1
					      	}
					    }
					  ]
					});

					/**
					 * Event prev/next floor plan
					 */
					property_slider.find( '.next-property' ).on('click', function() {
					  	property_slider.find( '.noo-list-property' ).slick('slickNext');
					});

					property_slider.find( '.prev-property' ).on('click', function() {
					  	property_slider.find( '.noo-list-property' ).slick('slickPrev');
					});

	    		});

	    	}

		/**
		 * Process slider in single property
		 */
		if ( $('.noo-property-gallery').length > 0 ) {

			$('.noo-property-gallery').each(function(index, el) {
				var property_gallary = $(this);

				property_gallary.find( '.property-gallery-top' ).slick({
					slidesToShow: 1,
					slidesToScroll: 1,
					arrows: false,
					fade: true,
					speed: 300,
					infinite: true,
					asNavFor: property_gallary.find( '.property-gallery-thumbnail-list' ),
					adaptiveHeight: true
				});

				property_gallary.find( '.property-gallery-thumbnail-list' ).slick({
					slidesToShow: 2,
					slidesToScroll: 1,
					variableWidth: true,
					infinite: true,
					speed: 300,
					centerMode: true,
					asNavFor: property_gallary.find( '.property-gallery-top' ),
					dots: false,
					focusOnSelect: true,
					arrows: false,
					responsive: [
					    {
					      	breakpoint: 991,
					      	settings: {
					        	centerMode: false,
					      	}
					    }
					]
				});

				/**
				 * Event prev/next property
				 */
				property_gallary.find( '.noo-arrow-next' ).on('click', function() {
				  	property_gallary.find( '.property-gallery-top' ).slick('slickNext');
				  	property_gallary.find( '.property-gallery-thumbnail-list' ).slick('slickNext');
				});

				property_gallary.find( '.noo-arrow-back' ).on('click', function() {
				  	property_gallary.find( '.property-gallery-top' ).slick('slickPrev');
				  	property_gallary.find( '.property-gallery-thumbnail-list' ).slick('slickPrev');
				});

			});

		}

		/**
		 * Process slider floor plan
		 */
			var load_floor_plan_slider = function() {

				if ( $('.noo-property-floor-plan-wrap').length > 0 ) {

					$('.noo-property-floor-plan-wrap').each(function(index, el) {

						var floor_plan = $(this);

						floor_plan.find( '.noo-property-floor-plan-wrapper' ).slick({
							slidesToShow: 1,
							speed: 300,
							dots: false,
							arrows: false,
							responsive: [
						    {
						      	breakpoint: 768,
						      	settings: {
						        	centerMode: true,
						        	slidesToShow: 1
						      	}
						    },
						    {
								breakpoint: 480,
								settings: {
						        	slidesToShow: 1
						      	}
						    }
						  ]
						});

						/**
						 * Event prev/next floor plan
						 */
						floor_plan.find( '.noo-arrow-next' ).on('click', function() {
						  	floor_plan.find( '.noo-property-floor-plan-wrapper' ).slick('slickNext');
						});

						floor_plan.find( '.noo-arrow-back' ).on('click', function() {
						  	floor_plan.find( '.noo-property-floor-plan-wrapper' ).slick('slickPrev');
						});

					});

				}

			}
			if( $(".tab-floor-plan").length == 0 ){
				load_floor_plan_slider();
			}


		/**
       	 * Process event when clicking tab Property detail
       	 */
	       	var noo_tab_content = function(content_tab){
	       		content_tab.find('.noo-tab').on('click', '> span', function(event) {
   					event.preventDefault();
   					var tab 	   		= $(this),
   						data_class 		= tab.data('class'),
   						total_width_tab = 0;

 					content_tab.find('.noo-tab > span').removeClass('active');
   					tab.addClass('active');

   					content_tab.find('.content-tab').hide().removeClass('show');
   					content_tab.find('.' + data_class).slideToggle('300');

   					if( data_class == "tab-floor-plan" ){
						load_floor_plan_slider();
   					}

   				});
	       	}

       		if ( $('.noo-detail-tabs').length > 0 ) {

       			$('.noo-detail-tabs').each(function(index, el) {

       				var content_tab = $(this);

       				noo_tab_content(content_tab);

       			});

       		}

       		if ( $('.noo-header-advance').length > 0 ) {

       			$('.noo-header-advance').each(function(index, el) {

       				var content_tab = $(this);

       				content_tab.find('.noo-tab').on('click', '> span', function(event) {
	   					event.preventDefault();
	   					var tab 	   		= $(this),
	   						data_class 		= tab.data('class'),
	   						total_width_tab = 0;

	 					content_tab.find('.noo-tab > span').removeClass('active');
	   					tab.addClass('active');

	   					content_tab.find('.content-tab').removeClass('in');
	   					content_tab.find('.' + data_class).addClass('in');

	   					$('.noo-header-advance').animate({height: $('.in').height() + 'px'});
	   					setTimeout(function(){
	   						if( $('.in').hasClass('tab-gallery') ){
	   							$('.noo-header-advance').css('height', 'auto');
	   						}
	   					},1000);

	   				});
	   				if( $('.in').hasClass('tab-map') ){
						$('.noo-header-advance').animate({height: $('.in').height() + 'px'});
	   				}

       			});

       		}

		/**
		 * Process form advanced search property
		 */
			if ( $('.noo-advanced-search-property-form').length > 0 ) {

				$('.noo-advanced-search-property-form').each(function(index, el) {

					var form_search_property = $(this);

					/**
					 * Process price slider
					 */
						if ( $('.noo-price').length > 0 ) {

							$('.noo-price').each(function(index, el) {

								var price         = $(this),
								min_price         = price.find('.price_min').data('min'),
								max_price         = price.find('.price_max').data('max'),
								current_min_price = price.find('.price_min').val(),
								current_max_price = price.find('.price_max').val();

								price.find( ".price-slider-range" ).slider({
									range: true,
									animate: true,
									min: min_price,
									max: max_price,
									values: [ current_min_price, current_max_price ],
									create : function( event, ui ) {
										// $this.tooltip({title:$this.text()});
										var controls = $(this).find('.ui-slider-handle');

										// $(controls[0]).tooltip({title:noo_price(current_min_price),placement:'bottom',container:'body',html:true});
										// $(controls[1]).tooltip({title:noo_price(current_max_price) ,placement:'bottom',container:'body',html:true});
									},
									change: function( event, ui ) {
										var controls = $(this).find('.ui-slider-handle');
										var ol_vl = ui.value;
										if( ol_vl == ui.values[0] ) {
											price.find( 'input.price_min' ).val( ui.values[0] ).trigger('change');

											price.find('.price-results').find('.min-price').html( noo_price( ui.values[0] ) );

											// console.log(ui.values[0]);
											// $(controls[0]).attr('data-original-title', noo_price(ui.values[0])).tooltip('fixTitle').tooltip('show');
										}

										if( ol_vl == ui.values[1] ) {
											price.find( 'input.price_max' ).val( ui.values[1] ).trigger('change');
											price.find('.price-results').find('.max-price').html( noo_price( ui.values[1] ) );
											// console.log(ui.values[1]);
											// $(controls[1]).attr('data-original-title', noo_price(ui.values[1])).tooltip('fixTitle').tooltip('show');
										}
									}
								});

							});

						}

					/**
					 * Process area slider
					 */
						if ( $('.noo-area').length > 0 ) {

							$('.noo-area').each(function(index, el) {

								var gsearch_area = $(this),
								min_area         = gsearch_area.find('.area_min').data('min'),
								max_area         = gsearch_area.find('.area_max').data('max'),
								current_min_area = gsearch_area.find('.area_min').val(),
								current_max_area = gsearch_area.find('.area_max').val();

								// current_min_area = parseInt( min_area, 10 );
								// current_max_area = parseInt( max_area, 10 );
								gsearch_area.find( ".area-slider-range" ).slider({
									range: true,
									animate: true,
									min: min_area,
									max: max_area,
									values: [ current_min_area, current_max_area ],
									create : function( event, ui ) {
										// $this.tooltip({title:$this.text()});
										var controls = $(this).find('.ui-slider-handle');
										// $(controls[0]).tooltip({title:current_min_area+ ' ' + Noo_Property.area_unit,placement:'bottom',container:'body',trigger:'hover focus',html:true});
										// $(controls[1]).tooltip({title:current_max_area + ' ' + Noo_Property.area_unit ,placement:'bottom',container:'body',trigger:'hover focus',html:true});
									},
									slide: function( event, ui ) {
										var controls = $(this).find('.ui-slider-handle');
										if( ui.value == ui.values[0] ) {
											gsearch_area.find( 'input.area_min' ).val( ui.values[0] ).trigger('change');
											gsearch_area.find('.area-results').find('.min-area').html( ui.values[0] + ' ' + Noo_Property.area_unit );
										}

										if( ui.value == ui.values[1] ) {
											gsearch_area.find( 'input.area_max' ).val( ui.values[1] ).trigger('change');
											gsearch_area.find('.area-results').find('.max-area').html( ui.values[1] + ' ' + Noo_Property.area_unit );
										}
									}


								});

							});

						}

					/**
					 * Process event when clicking button more filter search property
					 */
						if ( $('.box-show-features').length > 0 ) {

							$('.box-show-features').each(function(index, el) {

								var $$ = $(this);

								$$.on('click', '.show-features', function(event) {
									event.preventDefault();
									form_search_property.find('.noo-box-features').slideToggle('slow');
								});

							});

						}

				});

			}

	    /**
		 * Show tooltip
		 */
			if ( $('.noo-tooltip').length > 0 ) {

				$('.noo-tooltip').each(function(index, el) {

					var $$ = $(this),
						content = $$.data('content');

					$$.qtip({
					    content: content
					});

				});

			}

			if ( $('.noo-tooltip-action').length > 0 ) {

				$('.noo-tooltip-action').each(function(index, el) {

					var $$ = $(this),
						content = $$.data('content');

					$$.qtip({
					    content: content,
					    position: {
					        my: 'bottom center',
					        at: 'top center'
					    }
					});

				});

			}

		$('.rp-select').chosen({
            width: '100%',
            // disable_search_threshold: 10,
            allow_single_deselect:true,
            no_results_text: "Oops, nothing found!"
        });

       	/**
       	 * Process shortcode: Recent Property
       	 */
       		var noo_recent_property = function() {

	       		if ( $('.noo-recent-property').length > 0 ) {

	       			$('.noo-recent-property').find('.noo-list-property').each(function(index, el) {

	       				var box_slider          = $(this),
	       				    box_recent_property = $(this).closest('.noo-recent-property'),
	       					style 				= box_recent_property.data('style'),
	       					row 				= box_recent_property.data('row'),
	       					column 				= box_recent_property.data('column');

	       				var spaceBetween = 30;

	   					if ( style === 'style-3' ) spaceBetween = 10;
	   					if ( style === 'style-4' ) spaceBetween = 5;

	   					if ( style === 'style-4' ) {

	   						box_slider.slick({
								dots: true,
								arrows: false,
								infinite: true,
								slidesToShow: column,
								slidesToScroll: column,
								rows: row,
								centerPadding: 0,
								adaptiveHeight: true,
								responsive: [
								    {
								      	breakpoint: 1024,
								      	settings: {
									        slidesToShow: 1,
									        slidesToScroll: 1,
									        infinite: true,
									        dots: true
								      	}
								    },
								    {
								      	breakpoint: 600,
								      	settings: {
									        slidesToShow: 1,
									        slidesToScroll: 1
								      	}
								    },
								    {
								      	breakpoint: 480,
								      	settings: {
									        slidesToShow: 1,
									        slidesToScroll: 1
								      	}
								    }
								]
							});

	   					} else {

	   						box_slider.slick({
								dots: false,
								arrows: false,
								infinite: true,
								slidesToShow: column,
								slidesToScroll: column,
								rows: row,
								centerPadding: 0,
								adaptiveHeight: true,
								responsive: [
								    {
								      	breakpoint: 1024,
								      	settings: {
									        slidesToShow: 1,
									        slidesToScroll: 1
								      	}
								    },
								    {
								      	breakpoint: 600,
								      	settings: {
									        slidesToShow: 1,
									        slidesToScroll: 1
								      	}
								    },
								    {
								      	breakpoint: 480,
								      	settings: {
									        slidesToShow: 1,
									        slidesToScroll: 1
								      	}
								    }
								]
							});

	   						/**
	   						 * Event prev/next property
	   						 */
	   						box_recent_property.find('.next-property').on('click', function() {
							  	box_slider.slick('slickNext');
							});

							box_recent_property.find('.prev-property').on('click', function() {
							  	box_slider.slick('slickPrev');
							});

	   					}

	       			});

	       		}

	       	}

	       	noo_recent_property();

       	/**
       	 * Process event when clicking tab floor plan (Shortcode Floor Plan)
       	 */
       		var noo_floor_plan = function() {

	       		if ( $('.noo-floor-plan').length > 0 ) {

	       			$('.noo-floor-plan').each(function(index, el) {

	       				var floor_plan = $(this);

	       				floor_plan.find('.noo-tab').on('click', '> span', function(event) {
	       					event.preventDefault();
	       					var tab 	   		= $(this),
	       						data_class 		= tab.data('class'),
	       						total_width_tab = 0;

	     					floor_plan.find('.noo-tab > span').removeClass('active');
	       					tab.addClass('active');

	       					floor_plan.find('.content-tab').hide().removeClass('show');
	       					floor_plan.find('.' + data_class).slideToggle('300');

	       				});

	       			});

	       		}
	       	}

	       	noo_floor_plan();

		/**
		 * Process shortcode: Noo Agent
		 */
		if ( $('.noo-list-agent').length > 0 ) {

			$('.noo-list-agent').each(function(index, el) {

				var box_agent = $(this);

				box_agent.slick({
					dots: false,
					arrows: false,
					infinite: true,
					slidesToShow: 4,
					slidesToScroll: 4,
					centerPadding: 0,
					adaptiveHeight: true,
					responsive: [
						{
							breakpoint: 991,
							settings: {
								slidesToShow: 3,
								slidesToScroll: 3
							}
						},
						{
							breakpoint: 767,
							settings: {
								slidesToShow: 2,
								slidesToScroll: 2
							}
						},
						{
							breakpoint: 600,
							settings: {
								slidesToShow: 1,
								slidesToScroll: 1
							}
						},
						{
							breakpoint: 480,
							settings: {
								slidesToShow: 1,
								slidesToScroll: 1
							}
						}
					]
				});

				/**
				 * Event prev/next agent
				 */
				box_agent.closest('.noo-agent').find('.next-agent').on('click', function() {
					box_agent.slick('slickNext');
				});

				box_agent.closest('.noo-agent').find('.prev-agent').on('click', function() {
					box_agent.slick('slickPrev');
				});

			});

		}

	});

})( jQuery );
