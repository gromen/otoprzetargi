jQuery(document).ready(function($) {
  // image upload 
  $(document).on('click', '.noo-wpmedia', function(e) {
    e.preventDefault();
    var $this = $(this);
    var custom_uploader = wp.media({
        title: 'Select Image',
        button: {
            text: 'Insert image'
        },
        multiple: false  // Set this to true to allow multiple files to be selected
    })
    .on('select', function() {
        var attachment = custom_uploader.state().get('selection').first().toJSON();
        $this.val(attachment.id).change();
        
    })
    .open();
  });

  $('.parent-control').change(function() {
    var $this = $(this);
    var parent_active = false;
    var parent_type = $this.attr('type');
    var parent_id   = $this.attr('id');
    if(parent_type == 'text') {
      parent_active = ($this.val() !== '');
    } else if(parent_type == 'checkbox') {
      parent_active = ($this.is(':checked'));
    }

    if(parent_active) {
      $('.' + parent_id + '-child').show().find('input.parent-control').change();
    } else {
      $('.' + parent_id + '-child').hide().find('input.parent-control').change();
    }
  });

  $('.noo-slider').each(function() {
    var $this = $(this);

    var $slider = $('<div>', {id: $this.attr("id") + "-slider"}).insertAfter($this);
    $slider.slider(
    {
      range: "min",
      value: $this.val() || $this.data('min') || 0,
      min: $this.data('min') || 0,
      max: $this.data('max') || 100,
      step: $this.data('step') || 1,
      slide: function(event, ui) {
        $this.val(ui.value).attr('value', ui.value).change();
      }
    }
    );

    $this.change(function() {
      $slider.slider( "option", "value", $this.val() );
    });
  });


  // preloader customizer
  $("#noo-customizer-preloader").delay(850).fadeOut("slow");
  
  /**
   * Process event when clicking select icon
   */
    if ( $('.noo-icon-wrap').length > 0 ) {

      $('.noo-icon-wrap').each(function(index, el) {
        
        var $$ = $(this);

        $$.find('.find-icon').on('click', '> i', function(event) {
          event.preventDefault();
          
          var $_$ = $(this);

          $('.noo-icon-list').toggleClass('show');

          $('.noo-icon-list').on('click', '> i', function(event) {
            event.preventDefault();
            

            var $_$_$         = $(this),
                old_icon      = $$.find('.find-icon i').data('icon'),
                id_input      = $_$.data('id'),
                current_icon  = $_$_$.data('icon');
            
            $('.noo-icon-list').removeClass('show');

            $$.find('.find-icon i').removeClass(old_icon).addClass(current_icon).data('icon', current_icon);
            console.log(id_input);
            $('#' + id_input).val(current_icon);

          });

        });

      });      

    }
    

});
