jQuery(document).ready(function($) {
	// === << Hide main
		$('.hide_main').click(function(event) {
			$('#noo_main_select').toggle('fast');
		});
	// === << Check select setting
		// === << Change post
			$( "#import_post" ).change(function() {
			  	var $input = $( this );
			  	if ( $input.prop( "checked" ) ) {
			  		$('.install-demo').data('import-post', 'true');
			  	} else {
			  		$('.install-demo').data('import-post', 'false');
			  	}
			}).change();


		// === << Change comment
			$( "#import_comment" ).change(function() {
			  	var $input = $( this );
			  	if ( $input.prop( "checked" ) ) {
			  		$('.install-demo').data('import-comment', 'true');
			  	} else {
			  		$('.install-demo').data('import-comment', 'false');
			  	}
			}).change();


		// === << Change nav
			$( "#import_nav" ).change(function() {
			  	var $input = $( this );
			  	if ( $input.prop( "checked" ) ) {
			  		$('.install-demo').data('import-nav', 'true');
			  	} else {
			  		$('.install-demo').data('import-nav', 'false');
			  	}
			}).change();
	
	// --- Check select
		$('#noo_tools').on('click', '.item_tools', function(event) {
			event.preventDefault();
			
			var show = $(this).data('show');
			$('.'+ show).toggle('fast');

		});

	// --- Hover image
		// --- hiden default
			$('#button-1').hide();
		
		// --- Event hover
			$('.noo_hide').on('mouseover', '.item', function(event) {
				event.preventDefault();
				var demo = $(this).data('demo');

				$(this).find('.button-install').show().css({
					background: '#000',
					padding: '5px 10px',
					color: '#fff',
					cursor: 'pointer'
				});

				$(this).find('img').css({
					opacity: '0.7',
					filter: 'alpha(opacity=70)'
				});

				$(this).find('#img-' + demo).css('background', 'rgb(25, 255, 255, 0.8)');
			});

			$('.noo_hide').on('mouseout', '.item', function(event) {
				event.preventDefault();
				$(this).find('.button-install').hide();
				$(this).find('img').css({
					opacity: '1',
					filter: 'alpha(opacity=100)'
				});
			});

	// --- Event click install demo
		$('.theme').on('click', '.install-demo', function(event) {
			event.preventDefault();
			var btn_import 		 = $(this),
				box_import_demo  = btn_import.parent().parent(),
				name_demo_import = btn_import.data('name'),
				answer  		 = confirm ( nooSetupDemo.notice );

			if (answer) {

				function noo_process_import( type, message, task = '', index_post = 0 ) {
					if ( typeof type === 'undefined' || type === null || type == '' ) return false;
					/**
					 * Sent request process data
					 */
					var data_request = {
						action: 'process_data',
						security: nooSetupDemo.security,
						name: name_demo_import,
						type: type,
						task: task,
						index_post: index_post
					}
					$.ajax({
						url: nooSetupDemo.ajax_url,
						type: 'POST',
						dataType: 'html',
						data: data_request,
						beforeSend: function() {
							box_import_demo.find('.noo-load-ajax').addClass('noo-load-show');
							$('#process_import').html( '<img src="'+ nooSetupDemo.img_ajax_load +'" /><br />' + message ).css('text-align', 'center');
						},
						success: function(data_import) {

							data_import = $.parseJSON(data_import);

							if ( data_import.status === 'success' ) {


								if ( data_import.next_task === 'complete' ) {

									box_import_demo.find('.noo-load-ajax').removeClass('noo-load-show');
									$('#process_import').html( data_import.msg ).css('text-align', 'center');
									
								} else {

									noo_process_import( data_import.next_task, data_import.msg, data_import.task, data_import.index_post );

								}

							} else if ( data_import.status === 'error' ) {

								$('#process_import').html( data_import.msg ).css('text-align', 'center');
								
							}
							
						},
						timeout: 30000
					})
					
				}

			    var import_post 	= $(this).data('import-post');
			    var import_nav		= $(this).data('import-nav');
			    var import_comment  = $(this).data('import-comment');
			    
			    noo_process_import( 'start_import', 'Please wait...' );

			}
		});

});