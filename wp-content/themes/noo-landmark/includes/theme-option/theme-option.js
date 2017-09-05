(function ( $ ) {
	'use strict';
	jQuery(document).ready(function($) {
		
		if ( $('#noo-purchase-code').length > 0 ) {

			$('#noo-purchase-code').submit(function(event) {
				
				event.preventDefault();
				
				var form_purchase_code = $(this),
					data_purchase_code = form_purchase_code.serializeArray();

					data_purchase_code.push(
						{
							'name': 'action',
							'value': 'noo_check_purchase_code'	
						}
					);
				
				$.ajax({
					url: Noo_Theme_Option.ajax_url,
					type: 'POST',
					dataType: 'html',
					data: data_purchase_code,
					beforeSend: function() {
						$('#noo-active-code').append('<span class="dashicons dashicons-update noo-spin-icon"></span>');
					},
					success: function( res_purchase_code ) {

						res_purchase_code = $.parseJSON( res_purchase_code );
						
						$('#noo-active-code').find('.dashicons-update').remove();

						if ( res_purchase_code.status === 'success' ) {
							$('body').append('<div class="noo-notice-fixed success">' + res_purchase_code.message + '</div>');
						} else {
							$('body').append('<div class="noo-notice-fixed warning">' + res_purchase_code.message + '</div>');
						}

						setTimeout(function(){
							$('body').find('.noo-notice-fixed').remove();
						}, 5000);

					}
				})

			});

		}

	});
})( jQuery );