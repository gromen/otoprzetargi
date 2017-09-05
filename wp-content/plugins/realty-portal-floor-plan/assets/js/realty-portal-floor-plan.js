jQuery(document).ready(function ( $ ) {
	'use strict';
	var floor_plan_wrap = $('#rp-item-floor_plan_wrap-wrap .rp-clone-floor-plan');
	var clone_element = $('#clone_element');

	var clone_floor_plan = function () {
		floor_plan_wrap.on('click', '.add-floor-plan', function ( event ) {
			event.preventDefault();
			var btn_clone     = $(this),
			    total         = btn_clone.data('total'),
			    content_clone = clone_element.clone(true).html();

			content_clone = content_clone.replace(/\[0\]/g, '[' + (total + 1) + ']');
			$('.rp-floor-plan-main').append('<div class="rp-floor-plans-wrap rp-md-12">' + content_clone + '</div>');
			btn_clone.data('total', total + 1);

			rp_load_upload();

		});

	}
	clone_floor_plan();


	var remove_floor_plan = function () {

		$('body').on('click', '.remove-floor-plan', function ( event ) {
			event.preventDefault();
			$(this).closest('.rp-floor-plans-wrap').remove();
		});

	}
	remove_floor_plan();

	/**
	 * Process slider floor plan
	 */
	var RP_Property_Floor_Plan_Wrap = $('.rp-property-floor-plan');
	var load_floor_plan_slider = function () {

		RP_Property_Floor_Plan_Wrap.each(function ( index, el ) {

			var floor_plan = $(this);

			floor_plan.find('.rp-property-floor-plan-wrapper').slick({
				slidesToShow: 1,
				speed       : 300,
				dots        : false,
				arrows      : false,
				responsive  : [
					{
						breakpoint: 768,
						settings  : {
							centerMode  : true,
							slidesToShow: 1
						}
					},
					{
						breakpoint: 480,
						settings  : {
							slidesToShow: 1
						}
					}
				]
			});

			/**
			 * Event prev/next floor plan
			 */
			floor_plan.find('.rp-arrow-next').on('click', function () {
				floor_plan.find('.rp-property-floor-plan-wrapper').slick('slickNext');
			});

			floor_plan.find('.rp-arrow-back').on('click', function () {
				floor_plan.find('.rp-property-floor-plan-wrapper').slick('slickPrev');
			});

		});

	}

	load_floor_plan_slider();

	/**
	 * Process event switch floor plan
	 */
	var rp_switch_floor_plan = function () {

		$('body').on('change', '.rp-switch-floor-plan', function ( event ) {
			event.preventDefault();
			var box_select  = $(this),
			    value       = box_select.val(),
			    property_id = box_select.data('property-id');

			$.ajax({
				url       : RP_Floor_Plan.ajax_url,
				type      : 'POST',
				dataType  : 'html',
				data      : {
					action     : 'rp_load_floor_plan',
					security   : RP_Property.security,
					index      : value,
					property_id: property_id
				},
				beforeSend: function () {
					$('.rp-property-floor-plan-item').addClass('show');
				},
				success   : function ( data_floor_plan ) {
					$('.rp-property-floor-plan-item').removeClass('show');
					$('.rp-floor-plans-content').html(data_floor_plan);

					$('.rp-property-floor-plan-wrapper').slick({
						slidesToShow: 1,
						speed       : 300,
						dots        : false,
						arrows      : false,
						responsive  : [
							{
								breakpoint: 768,
								settings  : {
									centerMode  : true,
									slidesToShow: 1
								}
							},
							{
								breakpoint: 480,
								settings  : {
									slidesToShow: 1
								}
							}
						]
					});

					/**
					 * Event prev/next floor plan
					 */
					$('.rp-arrow-next').on('click', function () {
						$('.rp-property-floor-plan-wrapper').slick('slickNext');
					});

					$('.rp-arrow-back').on('click', function () {
						$('.rp-property-floor-plan-wrapper').slick('slickPrev');
					});
				}
			})

		});

	}
	rp_switch_floor_plan();

	/**
	 * Process event when clicking tab floor plan (Shortcode Floor Plan)
	 */
	var rp_floor_plan = function () {

		if ( $('.rp-floor-plan').length > 0 ) {

			$('.rp-floor-plan').each(function ( index, el ) {

				var floor_plan = $(this);

				floor_plan.find('.rp-tab').on('click', '> span', function ( event ) {
					event.preventDefault();
					var tab        = $(this),
					    data_class = tab.data('class');

					floor_plan.find('.rp-tab > span').removeClass('active');
					tab.addClass('active');

					floor_plan.find('.content-tab').hide().removeClass('show');
					floor_plan.find('.' + data_class).slideToggle('300');

				});

			});

		}
	}

	rp_floor_plan();

});