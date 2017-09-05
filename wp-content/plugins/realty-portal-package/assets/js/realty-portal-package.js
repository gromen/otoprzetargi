(function ( $ ) {
	'use strict';

	$(document).ready(function () {

		/**
		 * Event on shortcode pricing table
		 */
		if ( $('.rp-pricing-table').length > 0 ) {

			$('.rp-pricing-table').each(function ( index, el ) {

				var current_package = $(this);

				current_package.find('.pricing-action').on('click', 'button', function ( event ) {
					event.preventDefault();
					/**
					 * VAR
					 */
					var current_package_button = $(this),
					    package_id             = current_package_button.data('package-id'),
					    agent_id               = current_package_button.data('agent-id'),
					    data                   = {
						    action    : 'rp_buy_package',
						    security  : ADDON_PACKAGE.security,
						    package_id: package_id,
						    agent_id  : agent_id
					    };

					/**
					 * Process data
					 */
					$.ajax({
						url       : ADDON_PACKAGE.ajax_url,
						type      : 'POST',
						dataType  : 'json',
						data      : data,
						beforeSend: function () {
							current_package_button.find('>i').removeClass('hide');
							current_package.find('.pricing-action').find('.notice').remove();
						},
						success   : function ( pricing ) {
							current_package_button.find('>i').addClass('hide');

							if ( pricing.status === 'success' ) {
								window.location.replace(pricing.redirect);
							}

							current_package_button.closest('.pricing-action').append('<div class="notice">' + pricing.msg + '</div>');
						}
					})

				});

			});

		}

		/**
		 * Process event when agent change package
		 */
		if ( $('#rp-change-package').length > 0 ) {

			$('#rp-change-package').each(function ( index, el ) {

				var $$ = $(this);

				$$.on('click', 'button', function ( event ) {
					event.preventDefault();
					/**
					 * VAR
					 */
					var $_$  = $(this),
					    data = $$.serializeArray();

					data.push(
						{
							'name' : 'action',
							'value': 'rp_buy_package'
						},
						{
							'name' : 'security',
							'value': ADDON_PACKAGE.security
						}
					);

					/**
					 * Process data
					 */
					$.ajax({
						url       : ADDON_PACKAGE.ajax_url,
						type      : 'POST',
						dataType  : 'html',
						data      : data,
						beforeSend: function () {
							$_$.find('>i').removeClass('hide');
							$$.find('.notice').remove();
						},
						success   : function ( pricing ) {
							pricing = $.parseJSON(pricing);
							$_$.find('>i').addClass('hide');

							if ( pricing.status === 'success' ) {
								window.location.replace(pricing.redirect);
							}

							$$.append('<div class="notice">' + pricing.msg + '</div>');
						}
					})

				});

			});

		}


	});
})(jQuery);