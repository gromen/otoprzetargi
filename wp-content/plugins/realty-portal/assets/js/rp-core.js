function rp_loading( elm, message ) {
	jQuery(elm).block({
		message   : ( message || null ),
		overlayCSS: {
			backgroundColor: '#fafafa',
			opacity        : 0.5,
			cursor         : 'wait'
		}
	});
}
(function ( $ ) {
	'use strict';

	console.log("Thanks for use plugin Realty Portal! Look more info in http://nootheme.com");

	const rp_validate_form = $('form.rp-validate-form');

	function RP_Update_TinyMCE() {
		$('textarea').each(function () {
			var content = RP_Content_TinyMCE(this);
			if ( content !== undefined ) {
				$(this).val(content);
			}
		});
	}

	function RP_Content_TinyMCE( content ) {
		if ( typeof tinyMCE === 'undefined') {
			return undefined;
		}
		var id = content.id || content.name;
		var i = 0;
		for ( i; i < tinyMCE.editors.length; i++ ) {
			if ( tinyMCE.editors[ i ].id == id ) {
				return tinyMCE.editors[ i ].getContent();
			}
		}
		return undefined;
	}

	if ( typeof $.validate !== 'undefined' ) {
		var pass = 0;
		$('.rp-editor.rp-validate').attr('data-validation', 'required');

		$('form').on('submit', RP_Update_TinyMCE);

		$.validate({
			form                : '.rp-validate-form',
			modules             : 'security, html5',
			validateHiddenInputs: true,
			scrollToTopOnError  : false,
			errorElementClass   : 'rp-validation-error',
			onError             : function ( $form ) {
				pass = 0;
			},
			onSuccess           : function ( $form ) {
				console.log('The form ' + $form.attr('id') + ' is valid!');
				pass = 0;
			},
			onValidate          : function ( $form ) {
				console.log('The form ' + $form.attr('id') + ' on Validate!');
				pass = 0;
			},
			onElementValidate   : function ( valid, $el, $form, errorMess ) {
				if ( !valid ) {
					if ( 0 === pass ) {
						$('html, body').animate({
							scrollTop: $form.find("#" + $el.attr('id')).closest('.rp-item-wrap').offset().top - 60
						}, 500);
						console.log("#" + $el.attr('id'));
					}
					pass++;
					console.log('Input ' + $el.attr('name') + ' is ' + ( valid ? 'VALID' : 'NOT VALID'));
				}
			},
			onModulesLoaded     : function () {
				var optionalConfig = {
					fontSize: '12pt',
					padding : '4px',
					bad     : 'Very bad',
					weak    : 'Weak',
					good    : 'Good',
					strong  : 'Strong'
				};

				$('input[type="password"].is_strength').displayPasswordStrength(optionalConfig);
			}
		});

	}

	rp_validate_form.submit(function () {
		$(this).find(":input:not([type=\"submit\"])").filter(function () {
			return !this.value;
		}).attr("disabled", "disabled");
		return true;
	});

	rp_validate_form.find(":input").prop("disabled", false);


})(jQuery);