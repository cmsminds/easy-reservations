jQuery(document).ready(function ($) {
	'use strict';

	// Localized variables.
	var ajaxurl        = ERSRV_Public_Script_Vars.ajaxurl;
	var remove_sidebar = ERSRV_Public_Script_Vars.remove_sidebar;
	var is_product     = ERSRV_Public_Script_Vars.is_product;
	var is_checkout    = ERSRV_Public_Script_Vars.is_checkout;

	// Custom variables defined.
	var reservation_item_id = $('.ersrv-reservation-container').data('item');
	var reservation_calendar = document.getElementById('calendar');

	// If sidebar is to be removed on reservation single page.
	if ('yes' === remove_sidebar) {
		$('.secondary').remove();
		$('.primary').css('width', '100%');
	}

	if ( 'yes' === is_product ) {
		var datepicker_date_format = 'yyyy-mm-dd';
		$('.datepicker-inline').datepicker({
			numberOfMonths: 2,
			format: datepicker_date_format,
		});

		$('.date-control').datepicker({
			numberOfMonths: 1
		});

		// range slider
		$("#slider-range").slider({
			range: true,
			min: 0,
			max: 500,
			values: [75, 300],
			slide: function (event, ui) {
				$(".price-value").html("$" + ui.values[0] + " to $" + ui.values[1]);
			}
		});
		$(".price-value").html("$" + $("#slider-range").slider("values", 0) + " to $" + $("#slider-range").slider("values", 1));
	}

	/**
	 * Add the reservation to google calendar.
	 */
	$(document).on('click', '.add-to-gcal', function () {
		var this_button = $(this);
		var order_id = this_button.parent('.ersrv-reservation-calendars-container').data('oid');

		// Return false, if the order id is invalid.
		if (-1 === is_valid_number(order_id)) {
			return false;
		}

		// Send the AJAX now.
		block_element($('.ersrv-reservation-calendars-container'));

		// Send the AJAX now.
		$.ajax({
			dataType: 'JSON',
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'add_reservation_to_gcal',
				order_id: order_id,
			},
			success: function (response) {
				// Check for invalid ajax request.
				if (0 === response) {
					console.log('easy reservations: invalid ajax request');
					return false;
				}

				// Check for invalid order ID.
				if (-1 === response) {
					console.log('easy reservations: invalid order ID');
					return false;
				}

				if ('reservation_added-to-gcal' !== response.data.code) {
					return false;
				}

				// Unblock the element.
				unblock_element($('.ersrv-reservation-calendars-container'));
			},
		});
	} );

	/**
	 * Add the reservation to google calendar.
	 */
	if ( 'yes' === is_product ) {
		$( document ).on( 'click', '.add-to-ical', function () {
			var goto = $( this ).data( 'goto' );
			console.log( 'goto', goto );
		} );
	}

	/**
	 * Proceed with reservation details and add the details to the cart.
	 */
	$( document ).on( 'click', '.ersrv-proceed-with-reservation-details', function () {
		// Gather the amenities, if selected.
		if ($('.card.amenities').length) {
			$('.card.amenities .amenity').each(function () {
			});
		}
	} );

	/**
	 * Block element.
	 *
	 * @param {string} element
	 */
	function block_element(element) {
		element.addClass('non-clickable');
	}

	/**
	 * Unblock element.
	 *
	 * @param {string} element
	 */
	function unblock_element(element) {
		element.removeClass('non-clickable');
	}

	/**
	 * Check if a number is valid.
	 * 
	 * @param {number} data 
	 */
	function is_valid_number(data) {
		if ('' === data || undefined === data || isNaN(data) || 0 === data) {
			return -1;
		}

		return 1;
	}

	/**
	 * Check if a string is valid.
	 * 
	 * @param {string} data 
	 */
	function is_valid_string(data) {
		if ('' === data || undefined === data || !isNaN(data) || 0 === data) {
			return -1;
		}

		return 1;
	}
});
