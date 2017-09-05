(function($, document)
{
    var storeKey = 'data-noowoo';

	var localCache = {

        timeout: 900000, // 15 minute
        data: {},
        remove: function (key) {
            delete localCache.data[key];
        },
        exist: function (key) {
            return !!localCache.data[key] && ((new Date().getTime() - localCache.data[key]._) < localCache.timeout);
        },
        get: function (key) {
            return localCache.data[key].data;
        },
        set: function ( key, cachedData, callback) {
            localCache.remove(key);
            localCache.data[key] = {
                _: new Date().getTime(),
                data: cachedData
            };
            if ($.isFunction(callback)) callback(cachedData);
        }

    };

   	function selected_img($tableCol) {
		
		var $selectedImg = [],
		    $gallery_field = $tableCol.find('.variation_other_images');

		$tableCol.find('.other-variation .image').each(function(){
			$selectedImg.push($(this).attr('data-attachment_id'));
		});

    	$gallery_field.val($selectedImg.join(','));
    	input_changed( $gallery_field );
	}

	function trigger_library_data() {

    	var $ImgUploadBtns = $('.woocommerce_variations .upload_image_button');

        localCache.set( storeKey, {} );

		$ImgUploadBtns.each(function(){

			var $uploadBtn = $(this),
			    varId = $uploadBtn.attr('rel'),
			    galleries = {};

			if (localCache.exist( storeKey )) {
        		var galleries = localCache.get( storeKey );
    		}

    		if( typeof(galleries[varId]) != "undefined" && galleries[varId] !== null ) {

                $('body').trigger( 'gallery_ready', [ $uploadBtn, varId ] );

    		} else {

    			var ajaxargs = {
    				'action': 		'admin_load_other_images',
    				'nonce':   		nooWooVars.nonce,
    				'varID': 		varId
    			}

    			$.ajax({
    				url: nooWooVars.ajaxurl,
    				data: ajaxargs,
    				context: this
    			}).success(function(data) {
        			var gallery = data;
            		galleries[varId] = gallery;
        			localCache.set( storeKey, galleries );
        			$('body').trigger( 'gallery_ready', [ $uploadBtn, varId ] );
                });
    		}

		});

		refresh_library_html();

	}

	function refresh_library_html() {

    	$('body').on('gallery_ready', function( event, $btn, varId ){

            var galleries = {};
    		if (localCache.exist( storeKey )) {
        		var galleries = localCache.get( storeKey );
    		}
    		if( typeof(galleries[varId]) != "undefined" && galleries[varId] !== null ) {

        		var galleryWrapperClass = 'other-variation-wrapper--'+varId;

        		$('.'+galleryWrapperClass).remove();

                var $other_variation = '<div class="other-variation-wrapper '+galleryWrapperClass+'"><h4>Other Images</h4>'+galleries[varId]+'<a href="#" class="add_other_img">Add other images</a></div>';
                $btn.after($other_variation);

            }

            // Sort
			$( ".other-variation" ).sortable({
			    deactivate: function(en, ui) {
			        var $tableCol = $(ui.item).closest('.upload_image');
					selected_img($tableCol);
			    },
			    placeholder: 'ui-state-highlight'
            });

			// Show tooltip remove
			$( '.tip-remove-img' ).tipTip({
				'attribute': 'data-tip',
				'fadeIn':    50,
				'fadeOut':   50,
				'delay':     200,
				'defaultPosition': 'left'
			});

		});

	}

	function input_changed( $input ) {
    	$input
            .closest( '.woocommerce_variation' )
            .addClass( 'variation-needs-update' );

        $( 'button.cancel-variation-changes, button.save-variation-changes' ).removeAttr( 'disabled' );

        $( '#variable_product_options' ).trigger( 'woocommerce_variations_input_changed' );
	}

	function init_variation_image()
	{
		trigger_library_data();

		var product_gallery_frame;

		$('.add_other_img').live( 'click', 'a', function( event ) {

			var $other_variation = $(this).siblings('.other-variation');
			var $image_gallery_ids = $(this).siblings('.variation_other_images');

			var $el = $(this);
			var attachment_ids = $image_gallery_ids.val();

			event.preventDefault();

			product_gallery_frame = wp.media.frames.downloadable_file = wp.media({
				
				title: 'Manage Variation Images',
				button: {
					text: 'Add to variation',
				},
				multiple: true
			});

			product_gallery_frame.on( 'select', function() {

				var selection = product_gallery_frame.state().get('selection');

				selection.map( function( attachment ) {

					attachment = attachment.toJSON();

					if ( attachment.id ) {
						attachment_ids = attachment_ids ? attachment_ids + "," + attachment.id : attachment.id;

						$other_variation.append('\
							<li class="image" data-attachment_id="' + attachment.id + '">\
								<a href="#" class="delete"><img src="' + attachment.url + '" /></a>\
							</li>');
					}

				} );

				$image_gallery_ids.val( attachment_ids );
				input_changed( $image_gallery_ids );
			});

			product_gallery_frame.open();

			return false;
		});

		$('.other-variation .delete').live("mouseenter mouseleave click", function(event){

			if (event.type == 'click') {
				var $tableCol = $(this).closest('.upload_image');
				$(this).closest('li').remove();
				selected_img($tableCol);
		        return false;
		    }

		});

		$( '#woocommerce-product-data' ).on( 'woocommerce_variations_loaded', function(){
    		trigger_library_data();
		});

		$('#variable_product_options').on('woocommerce_variations_added', function(){
    		trigger_library_data();
		});
	}

	$(document).ready(function(){
		init_variation_image();
	});

}(jQuery, document));
