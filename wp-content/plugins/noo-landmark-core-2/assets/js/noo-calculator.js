jQuery(document).ready(function($){

	$('.noo-slider').each(function() {
	    var $this = $(this);

	    var $slider = $('<div>', {id: $this.attr("id") + "-slider"}).insertAfter($this);
	    $slider.slider({
	    	
	      range: "min",
	      value: $this.val() || $this.data('min') || 0,
	      min: $this.data('min') || 0,
	      max: $this.data('max') || 100,
	      step: $this.data('step') || 1,
	      slide: function(event, ui) {
	        $this.val(ui.value).attr('value', ui.value).change();
	      }

	    });

	    $this.change(function() {
	      $slider.slider( "option", "value", $this.val() );
	    });
	});

	var html = '<div class="quick-loading spinner"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>';

	var payment_calculator = function(){
		var price       	= $('#cl_price').val(),
			deposit         = $('#cl_deposit').val(),
			annual_interest = $('#cl_annual_interest').val(),
			year            = $('#cl_year').val(),
			currency        = $('.pament_result h5').data('currency'),
			load            = price - deposit,
			rate            = (annual_interest/100)/12,
			month           = year * 12,
			payment         = Math.round( (load*rate/(1 - Math.pow(1 + rate, (-1*month))))*100 )/100;

		if( annual_interest == 0 || !$.isNumeric(price) || !$.isNumeric(deposit) || !$.isNumeric(annual_interest) || !$.isNumeric(year) )
			payment = "NaN";

		$('.pament_result').css({
			display: 'block'
		});

		$('.pament_result h5 b').empty().append(html);
		setTimeout(function(){
			$('.pament_result h5 b').empty().append(currency+payment);
		}, 1800);
	}

	payment_calculator();

	$( "#noo-mortgage-payment .ui-slider" ).on( "slidechange", function( event, ui ) {

		payment_calculator();

	} );

	if( $( '#noo_mortgage_calculate' ).length ){

		$( '#noo_mortgage_calculate' ).on( 'click', function(event){

			payment_calculator();

		});
	}

});