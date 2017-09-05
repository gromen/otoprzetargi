(function ( $ ) {
	'use strict';
	$(document).ready(function () {
		$(".rp-addon-not-installed").click(function () {
			var current_event = $(this),
			    is_buy = current_event.data('is-buy');

			if ( current_event.data('is-buy') ) {
				window.location.replace(current_event.data('url'));
			}

			$.ajax({
				url    : RP_Addon.ajax_url,
				type   : "post",
				data   : {
					action  : "rp_install_plugin",
					security: RP_Addon.security,
					plugin  : current_event.data('plugin')
				},
				success: function ( status ) {
					switch ( status ) {
						case "0":
							console.log("Something Went Wrong");
							break;
						case "1":
							location.reload();
							break;
						case "-1":
							console.log("Nonce missing")
					}
				},
				error  : function ( err ) {
					console.log("Something Went Wrong");
				}
			})
		})

		$(".rp-addon-not-activated").click(function () {
			var current_event = $(this);
			$.ajax({
				url    : RP_Addon.ajax_url,
				type   : "post",
				data   : {
					action  : "rp_activate_plugin",
					security: RP_Addon.security,
					plugin  : current_event.data('plugin')
				},
				success: function ( status ) {
					switch ( status ) {
						case "0":
							console.log("Something Went Wrong");
							break;
						case "1":
							console.log("Plugin activated");
							location.reload();
							break;
						case "-1":
							console.log("Nonce missing")
					}
				},
				error  : function ( err ) {
					console.log("Something Went Wrong");
				}
			});
		});

		$(".rp-dash-deactivate-addon").click(function () {
			var current_event = $(this);
			$.ajax({
				url    : RP_Addon.ajax_url,
				type   : "post",
				data   : {
					action  : "rp_deactivate_plugin",
					security: RP_Addon.security,
					plugin  : current_event.data('plugin')
				},
				success: function ( status ) {
					switch ( status ) {
						case "0":
							console.log("Something Went Wrong");
							break;
						case "1":
							console.log("Plugin deactivated");
							location.reload();
							break;
						case "-1":
							console.log("Nonce missing")
					}
				},
				error  : function ( err ) {
					console.log("Something Went Wrong");
				}
			});
		})
	});
})(jQuery);