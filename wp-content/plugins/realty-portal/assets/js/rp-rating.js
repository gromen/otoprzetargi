(function ( $ ) {
	'use strict';
	jQuery(document).ready(function ( $ ) {

		/**
		 * Process event comment property
		 */
		const property_comment = $('.rp-property-comment');
		if ( property_comment.length > 0 ) {

			property_comment.each(function () {

				var current_event = $(this);

				/**
				 * Process event hover rating
				 */
				current_event.find('.rp-loadmore-comment').on('click', 'span', function ( event ) {
					event.preventDefault();
					/**
					 * VAR
					 */
					var current_event  = $(this),
					    number_comment = current_event.data('number-comment'),
					    total_comment  = current_event.data('total-comment'),
					    curent_page    = current_event.data('curent-page'),
					    max_page       = current_event.data('max-page'),
					    property_id    = current_event.data('property-id'),

					    data           = {
						    action        : 'rp_property_loadmore_comment',
						    security      : RP_Rating.security,
						    number_comment: number_comment,
						    total_comment : total_comment,
						    max_page      : max_page,
						    curent_page   : curent_page,
						    property_id   : property_id
					    };

					/**
					 * Process
					 */
					$.ajax({
						url       : RP_Rating.ajax_url,
						type      : 'POST',
						dataType  : 'json',
						data      : data,
						beforeSend: function () {
							current_event.closest('.rp-loadmore-comment').addClass('loadmore').find('> span > i').removeClass('ion-chevron-down').addClass('rp-icon-spinner fa-spin');
						},
						success   : function ( loadmore_comment ) {

							try {

								if ( loadmore_comment.end_comment ) {

									current_event.closest('.rp-loadmore-comment').hide();

								} else {

									/**
									 * Add comment to list
									 */
									current_event.closest('.rp-property-comment').find('.rp-property-list-comment').append(loadmore_comment.html).fadeIn('slow');

									/**
									 * Remove class loadmore
									 */
									current_event.closest('.rp-loadmore-comment').removeClass('loadmore').find('> span > i').removeClass('rp-icon-spinner fa-spin').addClass('ion-chevron-down');

									/**
									 * Update number current page
									 */
									current_event.data('curent-page', loadmore_comment.curent_page);

								}

							} catch ( e ) {}

						}
					})

				});

				/**
				 * Process event when clicking button submit comment
				 */
				current_event.find('.rp-property-form-comment-wrap').on('click', 'button', function ( event ) {
					event.preventDefault();

					/**
					 * VAR
					 */
					var $_$  = $(this),
					    data = current_event.find('.rp-property-form-comment-wrap').serializeArray();

					data.push(
						{
							'name' : 'action',
							'value': 'rp_submit_comment'
						},
						{
							'name' : 'security',
							'value': RP_Rating.security
						}
					);

					/**
					 * Process
					 */
					$.ajax({
						url       : RP_Rating.ajax_url,
						type      : 'POST',
						dataType  : 'json',
						data      : data,
						beforeSend: function () {
							$_$.find('>i').removeClass('hide');
							$_$.find('.rp-property-notice').remove();
							$_$.find('.none-comment').remove();
						},
						success   : function ( comment ) {

							try {
								$_$.find('>i').addClass('success').removeClass('fa-spin');

								$.notifyBar({
									cssClass: comment.status,
									html    : comment.message,
									position: "bottom"
								});

								/**
								 * Add comment to list
								 */
								current_event.find('.rp-property-list-comment').find('.none-comment').remove();
								current_event.find('.rp-property-list-comment').prepend(comment.html).fadeIn('slow');

								setTimeout(function () {

									$_$.find('>i').removeClass('success').addClass('hide fa-spin');

								}, 5000);

							} catch ( e ) {}

						}
					})

				});

			});

		}

	});
})(jQuery);