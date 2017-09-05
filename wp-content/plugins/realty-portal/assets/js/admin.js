(function ( $ ) {
	'use strict';

	/**
	 * Create function get all field
	 */
	$.fn.get_all_field = function () {
		var field = {};

		$('input', this).each(function () {
			field[ this.name ] = $(this).val();
		});
		return $.param(field);
	}

	$(document).ready(function () {

		/**
		 * Process event when clicking submit button
		 */
		var btn_property_submit = $('.btn-property-submit');

		if ( btn_property_submit.length > 0 ) {

			btn_property_submit.each(function ( index, el ) {

				/**
				 * VAR
				 */
				var current_event = $(this);

				current_event.on('click', function ( event ) {
					event.preventDefault();

					/**
					 * VAR
					 */
					var $_$     = $(this),
					    id_form = $_$.data('id-form');

					/**
					 * Process
					 */
					/**
					 * Remove disable field
					 */
					$('#' + id_form + ' .rp-setting-item-field').each(function ( index, el ) {

						var current_event = $(this),
						    div_input     = current_event.find('.rp-col-name > input[type="text"]'),
						    value_input   = div_input.val();

						/**
						 * Conver name input -> toLowerCase
						 */
						div_input.val(( value_input.toLowerCase() ).replace(/ /g, '_'));
						if ( current_event.hasClass('disable') ) {
							current_event.find('input, select, textarea').each(function ( index, el ) {
								$(this).prop("disabled", false);
							});
						}
					});

					/**
					 * Get data form field
					 */
					var data = $('#' + id_form).serializeArray();

					data.push(
						{
							'name' : 'action',
							'value': 'property_settings'
						},
						{
							'name' : 'security',
							'value': RP_Property_Admin.security
						}
					);

					// console.log(data);

					$.ajax({
						url       : RP_Property_Admin.ajax_url,
						type      : 'POST',
						dataType  : 'html',
						data      : data,
						beforeSend: function () {
							$('html').append('<div class="notice_body"><div>' + RP_Property_Admin.wait_txt + '</div></div>');
							$_$.find('> span').addClass('spin').removeClass('hide');
						},
						complete  : function () {

							$('html').find('.notice_body').remove();
							$_$.find('> span').removeClass('spin').addClass('hide');

							/**
							 * Add disable field
							 */
							$('#' + id_form + ' .rp-setting-item-field').each(function ( index, el ) {

								var current_event = $(this);

								if ( current_event.hasClass('disable') ) {
									current_event.find('input, select, textarea').each(function ( index, el ) {
										$(this).prop("disabled", true);
									});
								}
							});
						}
					})

				});

			});

			$('.remove-field').each(function ( index, el ) {

				$(this).click(function ( event ) {

					var current_event = $(this),
					    id            = current_event.data('id'),
					    label         = current_event.data('label'),
					    input         = current_event.data('id-input'),
					    id_event      = $('#' + id);

					if ( current_event.hasClass('disable') ) {

						current_event.html(RP_Property_Admin.enable_txt).removeClass('disable').addClass('enable');
						$('#' + input).val('1');
						id_event.addClass('disable');
						id_event.find('input, select, textarea').each(function ( index, el ) {
							$(this).prop("disabled", true);
						});

					} else if ( current_event.hasClass('enable') ) {

						current_event.html(RP_Property_Admin.disable_txt).removeClass('enable').addClass('disable');
						$('#' + input).val('');
						id_event.removeClass('disable');

						id_event.find('input, select, textarea').each(function ( index, el ) {
							$(this).prop("disabled", false);
						});

					} else {
						id_event.remove();
					}

				});

			});

			/**
			 * Check disable field
			 */
			var rp_setting_item_field = $('.rp-setting-item-field');
			if ( rp_setting_item_field.length > 0 ) {
				rp_setting_item_field.each(function ( index, el ) {

					var current_event = $(this);

					if ( current_event.hasClass('disable') ) {
						current_event.find('input, select, textarea').each(function ( index, el ) {
							$(this).prop("disabled", true);
						});
					}
				});
			}

			var rp_setting_content = $('.rp-setting-content');
			$('.rp-setting-clone').on('click', '> span', function ( event ) {
				event.preventDefault();

				/**
				 * VAR
				 */
				var current_event = $(this),
				    curent_form   = current_event.closest('form'),
				    clone_field   = curent_form.find('.clone-field'),
				    index         = clone_field.data('index');

				/**
				 * L1: Clone field default
				 * L2: Find new field clone and remove class hide, clone-field
				 * L3: Set data index new to field default
				 */
				clone_field.clone().appendTo('.show-clone-field');
				curent_form.find('.show-clone-field').find('.rp-setting-item-field').removeClass('hide clone-field').removeAttr('id');

				clone_field.data('index', index + 1);

				/**
				 * Find element field clone and replace name
				 * If is type checkbox then find and replace id, label
				 */
				clone_field.find('input, select, textarea').each(function ( i, e ) {
					var name = $(this).attr('name'),
					    type = $(this).attr('type');

					if ( typeof name === 'undefined' || name === '' ) {
						return;
					}

					$(this).attr('name', (name.replace('[' + index + ']', '[' + (index + 1) + ']')));

					if ( typeof type !== 'undefined' && type === 'checkbox' ) {

						$(this).attr('id', 'switch-' + index);
						$(this).next('label').attr('for', 'switch-' + index);

					}

				});

				/**
				 * Find all type hidden of field clone and set value default is empty
				 */

				$('.show-clone-field').find('.rp-setting-item-field').find('[type="hidden"]').each(function ( i, e ) {
					$(this).attr('value', '');
				});

				/**
				 * Support drag element
				 */
				rp_setting_content.sortable();
				rp_setting_content.disableSelection();

			});

			rp_setting_content.sortable();
			rp_setting_content.disableSelection();

			$(".rp-setting-content").on('click', 'input', function ( event ) {
				$(this).focus();
			});

		}

		/**
		 * Process event when click tab setting
		 */
		var rp_tab_setting = $('.rp-tab-setting');
		var rp_setting_form = $('.rp-setting-form');
		if ( rp_tab_setting.length > 0 ) {

			rp_tab_setting.each(function ( index, el ) {

				var current_event = $(this);

				rp_setting_form.hide();
				rp_setting_form.first().show();

				current_event.on('click', '.rp-tab-item', function ( event ) {
					event.preventDefault();
					/**
					 * VAR
					 */
					var $_$ = $(this),
					    id  = $_$.data('id');

					/**
					 * Process event
					 */
					current_event.find('.rp-tab-item').removeClass('active');
					$_$.addClass('active');

					rp_setting_form.hide();
					$('#' + id).show();

				});

			});

		}

		/**
		 * Process box login
		 */
		var rp_agent_meta_box_user = $('#rp_agent_meta_box_user');
		if ( rp_agent_meta_box_user.length > 0 ) {

			var user_name = rp_agent_meta_box_user.find('#user_name').val();

			if ( typeof user_name !== 'undefined' && user_name !== '' ) {
				rp_agent_meta_box_user.find('#user_name').prop("readonly", true);
			}

		}

		/**
		 * Process event when click disable google auto complete
		 */
		var rp_hide_disable_auto_complete = function () {

			var disable_auto_complete = $('#disable_auto_complete');
			var disable_auto_complete_cl = $('.disable_auto_complete');

			if ( disable_auto_complete.length > 0 ) {
				var disable_auto_complete = $('input[name="disable_auto_complete"]').val();
				if ( typeof disable_auto_complete === 'undefined' ) {

					disable_auto_complete_cl.hide();

				} else {

					disable_auto_complete_cl.show();

				}
				disable_auto_complete.change(function () {

					if ( $(this).is(':checked') ) {

						disable_auto_complete_cl.hide();

					} else {

						disable_auto_complete_cl.show();

					}

				});

			}

		}
		rp_hide_disable_auto_complete();

		/**
		 * Process when click element help
		 */
		var rp_help = $('.rp-help');
		if ( rp_help.length > 0 ) {

			rp_help.each(function ( index, el ) {

				$(this).on('click', function ( event ) {
					event.preventDefault();

					$(this).closest('div.notice').find('.content-help').toggle('slow');

				});

			});

		}

		var clone_floor_plan = function () {

			$('#rp-item-floor_plan_wrap-wrap .rp-clone-floor-plan').on('click', '.add-floor-plan', function ( event ) {
				event.preventDefault();
				var btn_clone     = $(this),
				    total         = btn_clone.data('total'),
				    content_clone = $('#clone_element').clone(true).html();

				content_clone = content_clone.replace(/\[0\]/g, '[' + (total + 1) + ']');
				$('.content-clone').append('<div class="rp-floor-plans-wrap rp-md-12">' + content_clone + '</div>');
				btn_clone.data('total', total + 1);

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
		 * Process event upload floor plan image
		 */
		var upload_floor_plan_image = function () {

			$('body').on('click', '.floor_plans_upload', function ( event ) {
				event.preventDefault();

				var rp_upload_btn = $(this);

				var box_floor_plan  = rp_upload_btn.closest('.floor-item'),
				    data_floor_plan = box_floor_plan.find('.floor_plan_data'),
				    gallery_state   = data_floor_plan.data('gallery-state'),
				    plan_image      = data_floor_plan.data('plan-image'),
				    plan_id         = data_floor_plan.data('plan-id'),
				    plan_index      = data_floor_plan.data('index'),
				    attachment;

				// Hide the Clear Gallery button if there's no image.
				if ( typeof plan_image == undefined || plan_image == '' ) {
					$('body').find('.' + plan_id + '_clear').hide();
				}

				// if media frame exists, reopen
				if ( wp_media_frame ) {
					wp_media_frame.setState(gallery_state);
					wp_media_frame.open();
					return;
				}

				// create new media frame
				// I decided to create new frame every time to control the Library state as well as selected images
				var wp_media_frame = wp.media.frames.wp_media_frame = wp.media({
					title   : 'RP Gallery', // it has no effect but I really want to change the title
					frame   : "post",
					toolbar : 'main-gallery',
					state   : gallery_state,
					library : { type: 'image' },
					multiple: true
				});

				// when open media frame, add the selected image to Gallery
				wp_media_frame.on('open', function () {
					var selected_ids = box_floor_plan.find('.' + plan_id).val();
					if ( !selected_ids ) {
						return;
					}
					selected_ids = selected_ids.split(',');
					var library = wp_media_frame.state().get('library');
					selected_ids.forEach(function ( id ) {
						attachment = wp.media.attachment(id);
						attachment.fetch();
						library.add(attachment ? [ attachment ] : []);
					});
				});

				// when click Insert Gallery, run callback
				wp_media_frame.on('update', function () {

					var library = wp_media_frame.state().get('library');
					var images = [];
					var rp_thumb_wraper = box_floor_plan.find('.rp-thumb-wrapper');
					rp_thumb_wraper.html('');

					library.map(function ( attachment ) {
						attachment = attachment.toJSON();
						images.push(attachment.id);
						rp_thumb_wraper.append(
							'<img src="' + attachment.url + '" alt="" />' +
							'<input type="hidden" name="floor_plans[' + plan_index + '][plan_image][]" value="' + attachment.id + '" />'
						);
					});

					gallery_state = 'gallery-edit';

					rp_upload_btn.attr('value', 'Edit Gallery');
					$('body').find('.' + plan_id + '_clear').css('display', 'inline-block');
				});

				// open media frame
				wp_media_frame.open();
			});

			// Clear button, clear all the images and reset the gallery
			$('body').find('.floor_plans_button_clear').on('click', function ( event ) {
				var rp_clear_btn = $(this);
				rp_clear_btn.hide();
				$('body').find('.floor_plans_upload').attr('value', 'Add Images');
				rp_clear_btn.siblings($('body').find('.floor_plans')).val('');
				// rp_clear_btn.siblings( $('body').find('.' + plan_id ) + '_ids').val('');
				rp_clear_btn.siblings('.rp-thumb-wrapper').html('');
			});

		}
		upload_floor_plan_image();

	});

})(jQuery);