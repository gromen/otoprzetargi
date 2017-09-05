(function ( $ ) {
	'use strict';

	$(document).ready(function () {

		$(document).on('click', '.more-action .rp-event', function ( event ) {

			event.preventDefault();

			var current_event = $(this),
			    pprocess      = current_event.data('process');

			if ( 'compare' === pprocess ) {

				var property_id = current_event.data('id');

				var data_info = {
					action     : 'rp_compare_add_property',
					property_id: property_id
				};

				$.ajax({
					url       : RP_Compare.ajax_url,
					type      : "POST",
					dataType  : "json",
					data      : data_info,
					beforeSend: function () {
						current_event.addClass('fa fa-spinner fa-spin').css({
							'border' : 'none'
						});
					},
					success   : function ( response ) {
						var data_child = { action: 'rp_compare_update_basket' };
						$.post(RP_Compare.ajax_url, data_child, function ( response ) {
							$('div#compare-properties-basket').replaceWith(response);
							compare_panel();
							compare_panel_open();
							current_event.removeClass('fa fa-spinner fa-spin').css({
								'border' : '1px solid #e5e5f8'
							});
						});

					}
				});
			}

		});

		var compare_panel = function () {
			$('.panel-btn').on('click', function () {
				if ( $('.compare-panel').hasClass('panel-open') ) {
					$('.compare-panel').removeClass('panel-open');
				} else {
					$('.compare-panel').addClass('panel-open');
				}
			});
		}
		compare_panel();

		var compare_panel_open = function () {
			$('.compare-panel').addClass('panel-open');
		}

		$(document).on('click', '#compare-properties-basket .compare-property-remove', function ( e ) {
			e.preventDefault();

			var property_id = jQuery(this).parent().attr('property-id');

			var data_info = {
				action     : 'rp_compare_add_property',
				property_id: property_id
			};

			$.post(RP_Compare.ajax_url, data_info, function ( response ) {

				var data_child = { action: 'rp_compare_update_basket' };
				$.post(RP_Compare.ajax_url, data_child, function ( response ) {

					$('div#compare-properties-basket').replaceWith(response);
					compare_panel();

				});

			});

			return false;
		});

		$(document).on('click', '.compare-properties-button', function () {

			if ( '' !== RP_Compare.url_compare ) {
				window.location.href = RP_Compare.url_compare;
			} else {
				$.notifyBar({
					cssClass: 'error',
					html    : 'Compare not found',
					position: "bottom"
				});
			}
			return false;
		});

	});
})(jQuery);