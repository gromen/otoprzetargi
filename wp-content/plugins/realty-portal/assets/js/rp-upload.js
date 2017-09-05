/**
 * This plugin is used to process information when you upload images on frontend and images are shown through jquery
 *
 * @package    Realty Portal
 * @author        NooTeam <suppport@nootheme.com>
 * @version    1.0
 */
(function ( $ ) {

	$.fn.rp_upload_form = function ( options ) {
		/**
		 * Set default
		 */
		var rp_default = {
			max_file_size: '10mb',
			runtimes     : 'html5,flash,silverlight,html4',
			multi_upload : true,
			browse_button: 'pickfiles',
			container    : 'rp-upload-wrap',
			name         : 'rp-upload-form',
			allow_format : 'jpg,jpeg,gif,png',
			multi_input  : false,
			drop_element : '',
			set_featured : false,
			slider       : true
		}

		options = $.extend(rp_default, options);

		var form_upload = $(this);

		/**
		 * Process
		 */
		var container_wrap = form_upload.find('.rp-upload-wrap');
		var uploader = new plupload.Uploader({
			runtimes: options.runtimes,

			browse_button: form_upload.find('.btn-upload')[ 0 ],
			container    : container_wrap[ 0 ],

			multi_upload: options.multi_upload,

			url: RPUpload.ajax_url,

			file_data_name: 'rp-upload-form',

			filters: {
				max_file_size: options.max_file_size,
				mime_types   : [
					{
						title     : '',
						extensions: options.allow_format
					}
				]
			},

			views           : {
				list  : true,
				thumbs: true, // Show thumbs
				active: 'thumbs'
			},
			dragdrop        : true,
			multiple_queues : true,
			urlstream_upload: true,
			multipart       : true,

			multi_selection: false,

			// Flash settings
			flash_swf_url: RPUpload.flash_swf_url,

			// Silverlight settings
			silverlight_xap_url: RPUpload.silverlight_xap_url,

			// additional post data to send to our ajax hook
			multipart_params: {
				'security': RPUpload.security,
				'action'  : 'rp_upload_form'
			},

			drop_element: form_upload.find('.rp-upload-left')[ 0 ],

			init: {
				PostInit: function () {

					if ( options.slider ) {

						container_wrap.find('.process-upload-media').html('');

						var ListImage = container_wrap.find('.rp-list-image').owlCarousel({
							items         : 3,
							navigation    : true,
							pagination    : false,
							mouseDrag     : false,
							touchDrag     : false,
							navigationText: [
								'<i class="rp-icon-ion-ios-arrow-left"></i>',
								'<i class="rp-icon-ion-ios-arrow-right"></i>'
							],
						})

						container_wrap.on('click', '.rp-upload-image', function ( event ) {
							uploader.start();
							return false;
						});

						var total_item = container_wrap.find('.rp-list-image').find('.owl-item').length;

						/**
						 * Process event load more and sort item
						 */
						if ( total_item > 3 ) {
							container_wrap.find('.upload-show-more').show();
							rp_upload_more_item(container_wrap);
						} else {
							container_wrap.find('.upload-show-more').hide();
						}

						/**
						 * Remove item
						 */
						container_wrap.on('click', '.remove-item', function ( event ) {
							event.preventDefault();

							$(this).closest('.owl-item').remove();

							/**
							 * Count all item slider
							 */
							if ( total_item < 1 ) {

								container_wrap.find('.rp-drop-file').show();

							} else {
								container_wrap.find('.rp-drop-file').hide();
							}

							var $$          = $(this),
							    id_img      = parseInt($$.data('id')),
							    id_featured = parseInt($('body').find('#set_featured').val());

							if ( typeof id_img !== 'undefined' && id_img !== '' ) {
								var data_image = {
									action  : 'rp_remove_media',
									security: RPUpload.security,
									id      : id_img
								}
								$.post(RPUpload.ajax_url, data_image, function ( data ) {
									if ( data.status === 'success' ) {
										container_wrap.find('input.value-image-' + id_img).remove();
										if ( typeof id_featured !== 'undefined' && id_featured === id_img ) {
											$('body').find('#set_featured').val('');
										}
									}
									container_wrap.find('.process-upload-media').html(data.msg);
									/**
									 * After 1 seconds remove text and hide notice
									 */
									setTimeout(function () {
										container_wrap.find('.process-upload-media').html('').hide('400');
									}, 2000);
								});
							}
						});

						/**
						 * Process remove/set featured image
						 */
						container_wrap.on('click', '.set-featured', function ( event ) {
							event.preventDefault();
							var $$            = $(this),
							    id_img        = parseInt($$.data('id')),
							    id_featured   = parseInt($('body').find('#set_featured').val()),
							    icon_featured = '<i class="item-featured rp-icon-ion-bookmark"></i>';

							$$.toggleClass('active');
							container_wrap.find('.item-featured').remove();
							$$.closest('.item-image').append(icon_featured);

							if ( typeof id_featured !== 'undefined' && id_featured !== id_img ) {

								$('body').find('#set_featured').val(id_img);

							}


						});

						container_wrap.find('.rp-list-image').sortable({
							cursor: 'move',
							items : '.owl-item',
							stop  : uploader.updateOrder
						});

					} else {


					}

				},

				FilesAdded: function ( up, files ) {

					if ( options.slider ) {

						plupload.each(files, function ( file ) {

							/**
							 * Create image
							 */
							var img_preview = new mOxie.Image();

							img_preview.onload = function () {
								container_wrap.find('.preview').empty();
								this.embed(container_wrap.find('.preview').get(0), {
									width : 150,
									height: 150
								});
							};

							img_preview.load(file.getSource());

							/**
							 * Process event when update image success
							 */
							img_preview.onembedded = function () {


								var url_img = container_wrap.find('.preview').find('canvas')[ 0 ].toDataURL();
								var id_img = file.id;

								/**
								 * Show drop box
								 */
								if ( typeof url_img !== 'undefined' || url !== '' ) {

									container_wrap.find('.rp-drop-file').hide();

								} else {

									container_wrap.find('.rp-drop-file').show();

								}

								/**
								 * Add new item to slider
								 */
								var content_img = '<div class="item-image" id="item-image-' + id_img + '"><img src="' + url_img + '"><i class="remove-item ion-trash-b"></i></div>';

								container_wrap.find('.rp-list-image').data('owlCarousel').addItem(content_img, 0);

								/**
								 * Process event remove item
								 */
								container_wrap.on('click', '.remove-item', function ( event ) {
									event.preventDefault();
									up.removeFile(file);

									$(this).closest('.owl-item').remove();

									/**
									 * Count all item slider
									 */
									var total_item = container_wrap.find('.rp-list-image').find('.owl-item').length;
									// console.log(total_item);
									if ( total_item < 1 ) {

										container_wrap.find('.rp-drop-file').show();

									} else {
										container_wrap.find('.rp-drop-file').hide();
									}

									var $$          = $(this),
									    id_img      = parseInt($$.data('id')),
									    id_featured = parseInt($('body').find('#set_featured').val());

									if ( typeof id_img !== 'undefined' && id_img !== '' ) {


										var data_image = {
											action  : 'rp_remove_media',
											security: RPUpload.security,
											id      : id_img
										}

										$.post(RPUpload.ajax_url, data_image, function ( data ) {

											if ( data.status === 'success' ) {

												container_wrap.find('input.value-image-' + id_img).remove();

												if ( typeof id_featured !== 'undefined' && id_featured == id_img ) {

													$('body').find('#set_featured').val('');

												}

											}

											container_wrap.find('.process-upload-media').html(data.msg);
											/**
											 * After 1 seconds remove text and hide notice
											 */
											setTimeout(function () {
												container_wrap.find('.process-upload-media').html('').hide('400');
											}, 2000);

										});

									}

								});

								/**
								 * Process remove/set featured image
								 */
								container_wrap.on('click', '.set-featured', function ( event ) {
									event.preventDefault();
									var $$            = $(this),
									    id_img        = parseInt($$.data('id')),
									    id_featured   = parseInt($('body').find('#set_featured').val()),
									    icon_featured = '<i class="item-featured rp-icon-ion-bookmark"></i>';

									$$.addClass('active');
									container_wrap.find('.item-featured').remove();
									$$.closest('.item-image').append(icon_featured);

									if ( typeof id_featured !== 'undefined' && id_featured !== id_img ) {

										$('body').find('#set_featured').val(id_img);

									}


								});

							}

						});

					} else {

						uploader.start();

					}

				},

				// FilesRemoved

				UploadProgress: function ( up, file ) {

					if ( options.slider ) {

						var total_file_upload   = up.total.uploaded,
						    total_upload_failed = up.total.failed,
						    total_file          = up.files.length,
						    filesPending        = total_file - ( total_file_upload + total_upload_failed),
						    maxCount            = up.getOption('filters').max_file_count || 0;

						container_wrap.find('.process-upload-media').show().html('Uploaded ' + total_file_upload + '/' + total_file + ' files - ( ' + plupload.formatSize(file.size) + ' )');
						// container_wrap.find( '.process-upload-media' ).html( '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b> - <span>' + file.percent + '%</span></div>' );

					}

				},

				UploadComplete: function ( up, file ) {

					if ( options.slider ) {

						container_wrap.find('.process-upload-media').html('Upload Success!!!');
						/**
						 * After 1 seconds remove text and hide notice
						 */
						setTimeout(function () {
							container_wrap.find('.process-upload-media').html('').hide('400');
						}, 2000);

					}

				},

				Error: function ( up, err ) {
					container_wrap.find('.process-upload-media').html("\nError #" + err.code + ": " + err.message);
				},

				FileUploaded: function ( up, file, response ) {

					if ( options.slider ) {

						/**
						 * Reset content process
						 */

						container_wrap.find('.process-upload-media').html('');

						/**
						 * Get data
						 */
						var data         = $.parseJSON(response.response),
						    html_results = '',
						    list_input   = (options.name).split('|');

						var id_img        = file.id,
						    id_image_wrap = 'item-image-' + id_img;

						if ( options.multi_input ) {
							$.each(list_input, function ( index ) {
								if ( options.multi_upload ) {

									html_results += '<input type="hidden" name="' + list_input[ index ] + '[]" value="' + data.id + '" class="value-image-' + data.id + '" />';

								} else {

									html_results += '<input type="hidden" name="' + list_input[ index ] + '" id="' + list_input + '" value="' + data.id + '" class="value-image-' + data.id + '" />';

								}
							});

						} else if ( options.multi_upload ) {
							html_results += '<input type="hidden" class="' + options.name + ' value-image-' + data.id + '" name="' + options.name + '[]"  value="' + data.id + '" />';
						} else {
							html_results += '<input type="hidden" class="' + options.name + ' value-image-' + data.id + '" name="' + options.name + '"  value="' + data.id + '"/>';
						}

						if ( data.status == 'success' ) {

							var icon_set_featured = '',
							    class_featured    = '';

							if ( options.set_featured ) {
								icon_set_featured = '<i class="set-featured rp-icon-ion-ios-star" data-id="' + data.id + '"></i>';
								class_featured = 'featured';
							}
							var icon_remove = '<i class="remove-item rp-icon-ion-trash-b" data-id="' + data.id + '"></i>';

							/**
							 * Find current image and add data
							 */
							if ( container_wrap.find('#' + id_image_wrap).length > 0 ) {
								container_wrap.find('#' + id_image_wrap).addClass(class_featured).html(
									'<i class="success rp-icon-check-circle"></i>' +
									'<img src="' + data.url + '">' + icon_set_featured + icon_remove + html_results
								);
							}

							if ( options.multi_upload ) {

								// container_wrap.append( html_results );

							} else {

							}
						} else {
							container_wrap.find('.rp-thumbnail-process').html(data.msg);
						}

						$('.rp-list-image').sortable({
							cursor: 'move',
							items : '.owl-item',
							stop  : uploader.updateOrder
						});

						rp_upload_more_item(container_wrap);

					} else {

						var data = $.parseJSON(response.response);

						if ( data.status == 'success' ) {

							container_wrap.find('.rp-upload-thumbnail').html(
								'<img src="' + data.url + '" />' +
								'<input type="hidden" class="' + options.name + ' value-image-' + data.id + '" name="' + options.name + '"  value="' + data.id + '"/>'
							);

						}


					}
				}
			}

		});

		uploader.init();

		function rp_upload_more_item( class_wrap ) {
			var class_wrap = $(class_wrap);
			class_wrap.find('.upload-show-more').on('click', function ( event ) {
				event.preventDefault();

				$('body').addClass('rp-cover-body');

				class_wrap.find('.rp-upload-left').addClass('upload-show-box-more');
				class_wrap.find('.owl-wrapper').append('<i class="upload-close-more ion-ios-close"></i>');

				class_wrap.on('click', '.upload-close-more', function ( event ) {
					event.preventDefault();

					$('body').removeClass('rp-cover-body');

					class_wrap.find('.rp-upload-left').removeClass('upload-show-box-more');
					$(this).remove();
				});
			});
		}

	}

})(jQuery);