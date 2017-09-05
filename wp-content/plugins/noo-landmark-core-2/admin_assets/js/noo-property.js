(function ( $ ) {
	'use strict';
	
	/**
	 * Create function get all field
	 */
	$.fn.get_all_field = function () {
	    var field = {};

	    $('input',this).each(function () { 
	        field[this.name] = $(this).val(); 
	    });
	    return $.param(field);
	}

	$(document).ready(function() {
		
		/**
		 * Process box login
		 */
			if ( $('#noo_agent_meta_box_user').length > 0 ) {

				var user_name = $('#noo_agent_meta_box_user').find('#user_name').val();

				if ( typeof user_name !== 'undefined' && user_name !== '' ) {
					$('#noo_agent_meta_box_user').find('#user_name').prop( "readonly", true );
				}

			}

		/**
		 * Process event when click disable google auto complete
		 */
			var noo_hide_disable_auto_complete = function() {
				
				if ( $('#disable_auto_complete').length > 0 ) {
					var disable_auto_complete = $( 'input[name="disable_auto_complete"]' ).val();
					if ( typeof disable_auto_complete === 'undefined' ) {

						$('.disable_auto_complete').hide();

					} else {

						$('.disable_auto_complete').show();

					}
					$('#disable_auto_complete').change(function() {
						
						if ( $(this).is(':checked') ) {
							
							$('.disable_auto_complete').hide();
							
						} else {

							$('.disable_auto_complete').show();
							
						}

					});

				}

			}
			noo_hide_disable_auto_complete();

		/**
		 * Process when click element help
		 */
		    if ( $('.noo-help').length > 0 ) {

		      $('.noo-help').each(function(index, el) {
		        
		        $(this).on('click', function(event) {
		          	event.preventDefault();

		            $(this).closest('div.notice').find('.content-help').toggle('slow');

		        });

		      });

		    }

		var clone_floor_plan = function() {

			$('#noo-item-floor_plan_wrap-wrap .noo-clone-floor-plan').on('click', '.add-floor-plan', function(event) {
				event.preventDefault();
				var btn_clone     = $(this),
					total         = btn_clone.data('total'),
					content_clone = $('#clone_element').clone(true).html();

				content_clone = content_clone.replace( /\[0\]/g, '[' + (total+1) + ']' );
				$('.content-clone').append( '<div class="noo-floor-plans-wrap noo-md-12">' + content_clone + '</div>' );
				btn_clone.data( 'total', total+1 );						

			});

		}
		clone_floor_plan();


		var remove_floor_plan = function() {

			$('body').on('click', '.remove-floor-plan', function(event) {
				event.preventDefault();
				$(this).closest('.noo-floor-plans-wrap').remove();
			});

		}
		remove_floor_plan();

		/**
		 * Process event upload floor plan image
		 */
		var upload_floor_plan_image = function() {

            $('body').on('click', '.floor_plans_upload', function (event) {
                event.preventDefault();

                var noo_upload_btn = $(this);

                var box_floor_plan  = noo_upload_btn.closest( '.floor-item' ),
					data_floor_plan = box_floor_plan.find('.floor_plan_data'),
					gallery_state   = data_floor_plan.data('gallery-state'),
					plan_image      = data_floor_plan.data('plan-image'),
					plan_id         = data_floor_plan.data('plan-id'),
					plan_index      = data_floor_plan.data('index'),
					attachment;

                // Hide the Clear Gallery button if there's no image.
                if ( typeof plan_image == undefined || plan_image == '' ) {
                    $('body').find('.' + plan_id + '_clear' ).hide();
                } 

                // if media frame exists, reopen
                if (wp_media_frame) {
                    wp_media_frame.setState(gallery_state);
                    wp_media_frame.open();
                    return;
                }

                // create new media frame
                // I decided to create new frame every time to control the Library state as well as selected images
                var wp_media_frame = wp.media.frames.wp_media_frame = wp.media({
                    title: 'NOO Gallery', // it has no effect but I really want to change the title
                    frame: "post",
                    toolbar: 'main-gallery',
                    state: gallery_state,
                    library: {type: 'image'},
                    multiple: true
                });

                // when open media frame, add the selected image to Gallery
                wp_media_frame.on('open', function () {
                    var selected_ids = box_floor_plan.find('.' + plan_id ).val();
                    if (!selected_ids)
                        return;
                    selected_ids = selected_ids.split(',');
                    var library = wp_media_frame.state().get('library');
                    selected_ids.forEach(function (id) {
                        attachment = wp.media.attachment(id);
                        attachment.fetch();
                        library.add(attachment ? [attachment] : []);
                    });
                });

                // when click Insert Gallery, run callback
                wp_media_frame.on('update', function () {

                    var library = wp_media_frame.state().get('library');
                    var images = [];
                    var noo_thumb_wraper = box_floor_plan.find('.noo-thumb-wrapper');
                    noo_thumb_wraper.html('');

                    library.map(function (attachment) {
                        attachment = attachment.toJSON();
                        images.push(attachment.id);
                        noo_thumb_wraper.append(
                        	'<img src="' + attachment.url + '" alt="" />' +
                        	'<input type="hidden" name="floor_plans[' + plan_index + '][plan_image][]" value="' + attachment.id + '" />'
                        );
                    });

                    gallery_state = 'gallery-edit';

                    noo_upload_btn.attr('value', 'Edit Gallery');
                    $('body').find('.' + plan_id + '_clear' ).css('display', 'inline-block');
                });

                // open media frame
                wp_media_frame.open();
            });

            // Clear button, clear all the images and reset the gallery
            $('body').find('.floor_plans_button_clear' ).on('click', function (event) {
                gallery_state = 'gallery-library';
                var noo_clear_btn = $(this);
                noo_clear_btn.hide();
                $('body').find('.floor_plans_upload' ).attr('value', 'Add Images');
                noo_clear_btn.siblings($('body').find('.floor_plans')).val('');
                // noo_clear_btn.siblings( $('body').find('.' + plan_id ) + '_ids').val('');
                noo_clear_btn.siblings('.noo-thumb-wrapper').html('');
            });

		}
		upload_floor_plan_image();

		$('.rp-select').chosen({
            width: '100%',
            disable_search_threshold: 10,
        });

	});

})( jQuery );