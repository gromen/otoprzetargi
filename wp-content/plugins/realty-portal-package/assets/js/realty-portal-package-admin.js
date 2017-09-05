(function ( $ ) {
	'use strict';
	$(document).ready(function ( $ ) {
		$(".rp-add-toggle").click(function () {
			$(this).closest(".rp-add-parent").toggleClass("wp-hidden-children");
			return false;
		});

		// Unlimited listing
		$("input[name=rp-membership-packages-properties_num_unlimited]").click(function () {
			if ( $(this).is(":checked") ) {
				$("input[name=rp-membership-packages-properties_num").prop("disabled", true);
			} else {
				$("input[name=rp-membership-packages-properties_num").prop("disabled", false);
			}
		});

		$("#rp-membership-packages-add-submit").click(function () {
			var form = $("#rp-membership-packages-add");
			var title = form.find("#rp-membership-packages-title:input").val();
			if ( "" === title ) {
				form.find("#rp-membership-packages-title:input").focus();
				return;
			}
			var interval = form.find("#rp-membership-packages-interval:input").val();
			if ( "" === interval ) {
				form.find("#rp-membership-packages-interval:input").focus();
				return;
			}
			var interval_unit = form.find("#rp-membership-packages-interval_unit option:selected").val();
			if ( "" === interval_unit ) {
				form.find("#rp-membership-packages-interval_unit:input").focus();
				return;
			}
			var price = form.find("#rp-membership-packages-price:input").val();
			if ( "" === price ) {
				form.find("#rp-membership-packages-price:input").focus();
				return;
			}
			var properties_num = form.find("#rp-membership-packages-properties_num:input").val();
			var properties_num_unlimited = form.find("#rp-membership-packages-properties_num_unlimited:input").is(":checked");
			if ( "" === properties_num && !properties_num_unlimited ) {
				form.find("#rp-membership-packages-properties_num:input").focus();
				return;
			}
			var featured_num = form.find("#rp-membership-packages-featured_num:input").val();
			if ( "" === featured_num ) {
				form.find("#rp-membership-packages-featured_num:input").focus();
				return;
			}

			var data = {
				'action'                  : 'rp_create_membership',
				'security'                : ADDON_PACKAGE_ADMIN.security,
				'title'                   : title,
				'interval'                : interval,
				'interval_unit'           : interval_unit,
				'price'                   : price,
				'properties_num'          : properties_num,
				'properties_num_unlimited': properties_num_unlimited ? "1" : "0",
				'featured_num'            : featured_num
			};

			$.post(ajaxurl, data, function ( data ) {

				if ( 'success' === data.status ) {
					var package_select_el = $("#rp-membership-packages-add").closest('.rp-form-group').find('.rp_package_select');
					package_select_el.find("option:selected").attr('selected', '');
					package_select_el.append($('<option/>').val(data.package_id).text(title).attr('selected', 'selected'));

					// Clear form
					form.find("#rp-membership-packages-title:input").val('');
					form.find("#rp-membership-packages-interval:input").val('');
					form.find("#rp-membership-packages-interval_unit option:selected").attr('selected', '');
					form.find("#rp-membership-packages-interval_unit option:first-child").attr('selected', 'selected');
					form.find("#rp-membership-packages-price:input").val('');
					form.find("#rp-membership-packages-properties_num:input").val('');
					form.find("#rp-membership-packages-properties_num_unlimited:input").attr('checked', false);
					form.find("#rp-membership-packages-featured_num:input").val('');
				} else {
					return false;
				}
			});
		});

		$('.add_membership_add_info').click(function () {
			var $this       = $(this),
			    $container  = $this.siblings('.rp-membership-additional'),
			    $max_fields = $container.attr('data-max'),
			    name        = $container.attr('data-name'),
			    count       = $container.find('.additional-field').length;
			if ( count >= $max_fields ) {
				$this.attr('disabled', 'disabled');
				return;
			}
			count++;

			$container.append(
				$('<div class="additional-field"></div>')
					.append('<input type="text" value="" name="rp_meta_boxes[' + name + '_' + count + ']" style="max-width:350px;padding-right: 10px;display: inline-block;float: left;" />')
					.append('<input class="button button-secondary delete_membership_add_info" type="button" value="Delete" style="display: inline-block;float: left;" />')
					.append('<br/>'));

			if ( count >= $max_fields ) {
				$this.attr('disabled', 'disabled');
			}
		});

		$('.rp-membership-additional').on("click", ".delete_membership_add_info", function () {
			var $this       = $(this),
			    $container  = $this.parents('.rp-membership-additional'),
			    $max_fields = $container.attr('data-max'),
			    name        = $container.attr('data-name'),
			    count       = $container.find('.additional-field').length;

			$this.parent('.additional-field').remove();
			count--;

			if ( count < $max_fields ) {
				$this.removeAttr('disabled');
			}

			var count_field = 1;
			$container.find('.additional-field').each(function () {
				$(this).find('input[type=text]').attr('name', 'rp_meta_boxes[' + name + '_' + count_field + ']');
				count_field++;
			});
		});

		/**
		 * Process event choice membership type
		 */
		var RP_Box_Membership_Type = $('.box-membership-type');
		var RP_Membership_Type = $('.membership_type');
		var RP_Free_Membership = $('.box-free-membership');

		if ( RP_Box_Membership_Type.length > 0 ) {

			var membership_type = RP_Box_Membership_Type.find('input[name="membership_type"]:checked').val();

			RP_Membership_Type.each(function ( index, el ) {
				if ( $(this).hasClass('type_' + membership_type) ) {
					$(this).show();
				} else {
					$(this).hide();
				}
			});

			RP_Box_Membership_Type.find('input[name="membership_type"]').change(function () {
				var membership_type = this.value;

				RP_Membership_Type.each(function ( index, el ) {
					if ( $(this).hasClass('type_' + membership_type) ) {
						$(this).show();
					} else {
						$(this).hide();
					}
				});
			});

			var enable_free_membership = RP_Free_Membership.find('input[name="membership_free"]:checked').val();
			RP_Membership_Type.each(function ( index, el ) {
				if ( enable_free_membership && $(this).hasClass('membership_free') ) {
					$(this).show();
				} else {
					$(this).hide();
				}
			});

			RP_Free_Membership.find('input[name="membership_free"]').change(function () {
				var enable_free_membership = $(this).prop('checked');

				RP_Membership_Type.each(function ( index, el ) {
					if ( enable_free_membership && $(this).hasClass('membership_free') ) {
						$(this).show();
					} else {
						$(this).hide();
					}
				});
			});

		}

		/**
		 * Process event choice payment type
		 */
		var RP_Box_Payment_Type = $('.box-payment-type');
		var RP_Payment_Type = $('.payment_type');
		if ( RP_Box_Payment_Type.length > 0 ) {

			var payment_type = RP_Box_Payment_Type.find('input[name="payment_type"]:checked').val();

			RP_Payment_Type.each(function ( index, el ) {
				if ( $(this).hasClass('type_' + payment_type) ) {
					$(this).show();
				} else {
					$(this).hide();
				}
			});

			RP_Box_Payment_Type.find('input[name="payment_type"]').change(function () {
				var payment_type = this.value;

				RP_Payment_Type.each(function ( index, el ) {
					if ( $(this).hasClass('type_' + payment_type) ) {
						$(this).show();
					} else {
						$(this).hide();
					}
				});
			});

		}

	});
})(jQuery);