jQuery(document).ready(function ( $ ) {
	'use strict';
	const body = $('body');
	$('#rp-property-submit').submit(function ( event ) {
		event.preventDefault();

		/**
		 * Check upload images
		 */
		var set_featured = $('#set_featured').val();
		if ( set_featured == '' || typeof set_featured == 'undefined' ) {
			$.notifyBar({
				cssClass: 'error',
				html    : RP_Submit_Property.error_photo,
				position: "bottom"
			}); return false;
		}

		body.append('<div class="rp-notice-message"></div>');
		body.find('.rp-notice-message').html('');

		var $$   = $(this),
		    data = $('#rp-property-submit').serializeArray();

		data.push(
			{
				'name' : 'action',
				'value': 'rp_submit_property'
			},
			{
				'name' : 'security',
				'value': RP_Property.security
			},
			{
				'name' : 'description',
				'value': tinymce.get('rp-field-item-description').getContent()
			}
		);

		$.ajax({
			url       : RP_Property.ajax_url,
			type      : 'POST',
			dataType  : 'json',
			data      : data,
			beforeSend: function () {
				$$.find('.rp-property-action .rp-button >i').removeClass('hide');
			},
			success   : function ( property ) {
				try {
					$$.find('.rp-property-action .rp-button >i').addClass('hide');
					if ( 'success' === property.status ) {

						$.notifyBar({
							cssClass: property.status,
							html    : property.message,
							position: "bottom"
						});

						if ( typeof property.url !== 'undefined' && property.url !== '' ) {
							window.location.href = property.url;
							console.log(property.url);
						}
					}

					$.notifyBar({
						cssClass: property.status,
						html    : property.message,
						position: "bottom"
					});

				} catch ( err ) {
					$.notifyBar({
						cssClass: 'error',
						html    : property.message,
						position: "bottom"
					});
				}
			}
		})

		body.find('.rp-notice-message').find('.rp-notice-message-item').each(function ( index, el ) {
			setTimeout(function () {
				$(this).remove();
			}, 2000);
		});

	});

});