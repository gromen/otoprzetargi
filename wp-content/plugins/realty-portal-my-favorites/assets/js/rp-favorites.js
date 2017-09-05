(function ( $ ) {
	'use strict';

	$(document).ready(function () {

		$(document).on('click', '.more-action .rp-event', function ( event ) {

			event.preventDefault();

			var current_event = $(this),
			    pprocess      = current_event.data('process'),
			    user_id       = current_event.data('user'),
			    property_id   = current_event.data('id');

			if ( 'favorites' === pprocess ) {

				/**
				 * Check user login
				 */
				if ( user_id === 0 ) {
					$.notifyBar({
						cssClass: 'error',
						html    : RP_Favorites.not_favorites,
						position: "bottom"
					});
					return false;
				}

				var status       = current_event.data('status'),
				    url_redirect = current_event.data('url');

				if ( 'add_favorites' === status ) {
					current_event.data('status', 'is_favorites');
				} else if ( status === 'is_favorites' ) {
					window.location.replace(RP_Favorites.url_favorites);
					return;
				}

				/**
				 * Call ajax process add favorites
				 */
				$.ajax({
					url       : RP_Favorites.ajax_url,
					type      : "POST",
					dataType  : "json",
					data      : {
						action     : 'rp_favorites',
						security   : RP_Favorites.security,
						status     : status,
						user_id    : user_id,
						property_id: property_id
					},
					beforeSend: function () {

					},
					success   : function ( favorites ) {
						try {
							if ( 'success' === favorites.status ) {

								if ( 'add_favorites' === status ) {

									current_event.removeClass('rp-icon-heart-o').addClass('rp-icon-heart');

								} else if ( 'is_favorites' === status ) {

									current_event.removeClass('rp-icon-heart').addClass('rp-icon-heart-o');

								}

								$.notifyBar({
									cssClass: 'success',
									html    : favorites.message,
									position: "bottom"
								});

							} else {

								$.notifyBar({
									cssClass: 'error',
									html    : favorites.message,
									position: "bottom"
								});

							}
						} catch ( err ) {
							$.notifyBar({
								cssClass: "error",
								html    : err,
								position: "bottom"
							});
						}
					}
				});

			} else if ( 'remove_favorites' === pprocess ) {
				/**
				 * Process
				 */
				$.ajax({
					url       : RP_Favorites.ajax_url,
					type      : 'POST',
					dataType  : 'json',
					data      : {
						action     : 'rp_favorites',
						security   : RP_Favorites.security,
						user_id    : user_id,
						property_id: property_id,
						status     : 'is_favorites'
					},
					beforeSend: function () {
					},
					success   : function ( response ) {
						try {
							if ( 'success' === response.status ) {
								current_event.closest('.property-item').remove();
							}
							$.notifyBar({
								cssClass: response.status,
								html    : response.message,
								position: "bottom"
							});
						} catch ( err ) {
							$.notifyBar({
								cssClass: "error",
								html    : err,
								position: "bottom"
							});
						}
					}
				})
			}

		});

	});
})(jQuery);