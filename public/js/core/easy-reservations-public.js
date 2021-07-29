jQuery(document).ready(function ($) {
	'use strict';

	// Localized variables.
	var ajaxurl                                      = ERSRV_Public_Script_Vars.ajaxurl;
	var remove_sidebar                               = ERSRV_Public_Script_Vars.remove_sidebar;
	var is_product                                   = ERSRV_Public_Script_Vars.is_product;
	var is_checkout                                  = ERSRV_Public_Script_Vars.is_checkout;
	var is_search_page                               = ERSRV_Public_Script_Vars.is_search_page;
	var reservation_item_details                     = ERSRV_Public_Script_Vars.reservation_item_details;
	var woo_currency                                 = ERSRV_Public_Script_Vars.woo_currency;
	var reservation_guests_err_msg                   = ERSRV_Public_Script_Vars.reservation_guests_err_msg;
	var reservation_only_kids_guests_err_msg         = ERSRV_Public_Script_Vars.reservation_only_kids_guests_err_msg;
	var reservation_guests_count_exceeded_err_msg    = ERSRV_Public_Script_Vars.reservation_guests_count_exceeded_err_msg;
	var reservation_checkin_checkout_missing_err_msg = ERSRV_Public_Script_Vars.reservation_checkin_checkout_missing_err_msg;
	var reservation_checkin_missing_err_msg          = ERSRV_Public_Script_Vars.reservation_checkin_missing_err_msg;
	var reservation_checkout_missing_err_msg         = ERSRV_Public_Script_Vars.reservation_checkout_missing_err_msg;
	var reservation_lesser_reservation_days_err_msg  = ERSRV_Public_Script_Vars.reservation_lesser_reservation_days_err_msg;
	var reservation_greater_reservation_days_err_msg = ERSRV_Public_Script_Vars.reservation_greater_reservation_days_err_msg;
	var search_reservations_page_url                 = ERSRV_Public_Script_Vars.search_reservations_page_url;

	// Custom variables.
	var datepicker_date_format = 'yyyy-mm-dd';

	// If sidebar is to be removed on reservation single page.
	if ('yes' === remove_sidebar) {
		$( '#secondary' ).remove();
		$( '#primary' ).css( 'width', '100%' );
	}

	// Search page checkin and checkout dates.
	if ( $( '#ersrv-search-checkin' ).length ) {
		$( '#ersrv-search-checkin, #ersrv-search-checkout' ).datepicker( {
			onSelect: function ( selected_date, instance ) {
				if ( 'ersrv-search-checkin' === instance.id ) {
					// Min date for checkout should be on/after the checkin date.
					$( '#ersrv-search-checkout' ).datepicker( 'option', 'minDate', selected_date );
					setTimeout( function() {
						$( '#ersrv-search-checkout' ).datepicker( 'show' );
					}, 16 );
				}
			},
			format: datepicker_date_format,
		} );
	}

	// If it's the product page.
	if ( 'yes' === is_product ) {
		var reserved_dates    = reservation_item_details.reserved_dates;
		var current_date      = new Date();
		var current_month     = ( ( '0' + ( current_date.getMonth() + 1 ) ).slice( -2 ) );
		var current_date_date = ( ( '0' + ( current_date.getDate() ) ).slice( -2 ) );
		var today_formatted   = current_date.getFullYear() + '-' + current_month + '-' + current_date_date;
		var blocked_dates     = [];

		// Prepare the blocked out dates in a separate array.
		if ( 0 < reserved_dates.length ) {
			for ( var i in reserved_dates ) {
				blocked_dates.push( reserved_dates[i].date );
			}
		}

		// Availability calendar 2 months.
		$( '.ersrv-item-availability-calendar' ).datepicker( {
			beforeShowDay: function( date ) {
				var loop_date           = ( ( '0' + ( date.getDate() ) ).slice( -2 ) );
				var loop_month          = ( ( '0' + ( date.getMonth() + 1 ) ).slice( -2 ) );
				var loop_date_formatted = date.getFullYear() + '-' + loop_month + '-' + loop_date;
				var date_enabled        = true;
				var date_class          = '';

				// If not the past date.
				if ( today_formatted <= loop_date_formatted ) {
					// Add custom class to the active dates of the current month.
					var key = $.map( blocked_dates, function( val, i ) {
						if ( val === loop_date_formatted ) {
							return i;
						}
					} );

					// If the loop date is a blocked date.
					if ( 0 < key.length ) {
						date_class = 'ui-datepicker-unselectable ui-state-disabled ersrv-date-disabled';
					} else {
						date_class = 'ersrv-date-active';
					}
				} else {
					date_class = 'ui-datepicker-unselectable ui-state-disabled ersrv-date-disabled';
				}

				// Return the datepicker day object.
				return [ date_enabled, date_class ];
			},
			numberOfMonths: 2,
			format: datepicker_date_format,
		} );

		// Checkin and checkout datepicker.
		$( '#ersrv-single-reservation-checkin-datepicker, #ersrv-single-reservation-checkout-datepicker' ).datepicker( {
			beforeShowDay: function( date ) {
				var loop_month          = ( ( '0' + ( date.getMonth() + 1 ) ).slice( -2 ) );
				var loop_date_formatted = date.getFullYear() + '-' + loop_month + '-' + date.getDate();
				var date_enabled        = true;
				var date_class          = '';

				// If not the past date.
				if ( today_formatted <= loop_date_formatted ) {
					// Add custom class to the active dates of the current month.
					var key = $.map( blocked_dates, function( val, i ) {
						if ( val === loop_date_formatted ) {
							return i;
						}
					} );

					// If the loop date is a blocked date.
					if ( 0 < key.length ) {
						date_class = 'ui-datepicker-unselectable ui-state-disabled ersrv-date-disabled';
					} else {
						date_class = 'ersrv-date-active';
					}
				} else {
					date_class = 'ui-datepicker-unselectable ui-state-disabled ersrv-date-disabled';
				}

				// Return the datepicker day object.
				return [ date_enabled, date_class ];
			},
			onSelect: function ( selected_date, instance ) {
				if ( 'ersrv-single-reservation-checkin-datepicker' === instance.id ) {
					// Min date for checkout should be on/after the checkin date.
					$( '#ersrv-single-reservation-checkout-datepicker' ).datepicker( 'option', 'minDate', selected_date );
					setTimeout( function() {
						$( '#ersrv-single-reservation-checkout-datepicker' ).datepicker( 'show' );
					}, 16 );
				}
			},
			numberOfMonths: 1,
			format: datepicker_date_format,
		} );
	}

	// If it's the search page or product page.
	if ( 'yes' === is_product || 'yes' === is_search_page ) {
		// Price range slider.
		$( '.ersrv-search-item-price-range' ).slider( {
			range: true,
			min: 0,
			max: 500,
			values: [75, 300],
			slide: function (event, ui) {
				$( '.price-value' ).html( woo_currency + ui.values[0] + ' to ' + woo_currency + ui.values[1]);
			}
		} );
		// $( '.price-value' ).html( woo_currency + $( '.ersrv-search-item-price-range' ).slider( values, 0 ) + ' to ' + woo_currency + $( '.ersrv-search-item-price-range' ).slider( values, 1 ) );
	}

	/**
	 * Accomodation adult charge.
	 */
	 $( document ).on( 'keyup click', '#adult-accomodation-count', function() {
		var this_input       = $( this );
		var adult_count      = parseInt( this_input.val() );
		adult_count          = ( -1 === is_valid_number( adult_count ) ) ? 0 : adult_count;
		var per_adult_charge = parseFloat( $( '#adult-charge' ).val() );
		var total_charge     = adult_count * per_adult_charge;
		total_charge         = total_charge.toFixed( 2 );
		$( 'tr.item-price-summary td span' ).html( woo_currency + total_charge );

		// Calculate the total cost.
		ersrv_calculate_reservation_total_cost();
	} );

	/**
	 * Accomodation kids charge.
	 */
	 $( document ).on( 'keyup click', '#kid-accomodation-count', function() {
		var this_input     = $( this );
		var kids_count     = parseInt( this_input.val() );
		kids_count         = ( -1 === is_valid_number( kids_count ) ) ? 0 : kids_count;
		var per_kid_charge = parseFloat( $( '#kid-charge' ).val() );
		var total_charge   = kids_count * per_kid_charge;
		total_charge       = total_charge.toFixed( 2 );
		$( 'tr.kids-charge-summary td span' ).html( woo_currency + total_charge );

		// Calculate the total cost.
		ersrv_calculate_reservation_total_cost();
	} );

	/**
	 * Amenities charge summary.
	 */
	$( document ).on( 'click', '.ersrv-new-reservation-single-amenity', function() {
		var amenities_summary_cost = 0.0;

		// Collect the amenities and their charges.
		$( '.ersrv-new-reservation-single-amenity' ).each ( function() {
			var this_element = $( this );
			var is_checked = this_element.is( ':checked' );
			if ( true === is_checked ) {
				var amenity_cost = this_element.parents( '.ersrv-single-amenity-block' ).data( 'cost' );
				amenities_summary_cost += parseFloat( amenity_cost );
			}
		} );

		// Limit to 2 decimal places.
		amenities_summary_cost = amenities_summary_cost.toFixed( 2 );

		// Paste the final cost.
		$( 'tr.amenities-summary td span' ).html( woo_currency + amenities_summary_cost );

		// Calculate the total cost.
		ersrv_calculate_reservation_total_cost();
	} );

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
	if ( 'yes' === is_checkout ) {
		$( document ).on( 'click', '.add-to-ical', function () {
			var goto = $( this ).data( 'goto' );
			
			if ( -1 !== is_valid_string( goto ) ) {
				window.location.href = goto;
			}
		} );
	}

	/**
	 * Fire the AJAX to load the reservation items on search page.
	 */
	if ( 'yes' === is_search_page ) {
		// Send the AJAX now.
		$.ajax( {
			dataType: 'JSON',
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'get_reservation_items',
			},
			success: function ( response ) {
				// Check for invalid ajax request.
				if ( 0 === response ) {
					console.log( 'easy reservations: invalid ajax request' );
					return false;
				}
				
				if ( 'items-found' === response.data.code ) { // If items are found.
					$( '.ersrv-search-reservations-items-container' ).html( response.data.html );
					$( '.ersrv-loadmore-container' ).html( response.data.load_more_html );
				} else if ( 'no-items-found' === response.data.code ) { // If items are found.
				}
			},
		} );

		/**
		 * Load more reservation items.
		 */
		$( document ).on( 'click', '.ersrv-loadmore-container a', function() {
			var this_button  = $( this );
			var current_page = parseInt( $( '#ersrv-posts-page' ).val() );
			var next_page    = current_page + 1;

			// Block the element now.
			block_element( this_button );

			// Send the AJAX now.
			$.ajax( {
				dataType: 'JSON',
				url: ajaxurl,
				type: 'POST',
				data: {
					action: 'loadmore_reservation_items',
					page: next_page,
				},
				success: function ( response ) {
					// Check for invalid ajax request.
					if ( 0 === response ) {
						console.log( 'easy reservations: invalid ajax request' );
						return false;
					}

					// Unblock the element now.
					unblock_element( this_button );
					
					// If there is a valid response.
					if ( 'items-found' === response.data.code ) { // If items are found.
						$( '.ersrv-search-reservations-items-container' ).append( response.data.html );

						// Update the posts page number.
						$( '#ersrv-posts-page' ).val( next_page );
					} else if ( 'no-items-found' === response.data.code ) { // If items are found.
						// Hide the load more button.
						$( '.ersrv-loadmore-container' ).hide();
					}
				},
			} );
		} );
	}

	/**
	 * Mark any reservation item as favourite item.
	 */
	$( document ).on( 'click', '.ersrv-mark-reservation-favourite', function() {
		var this_button = $( this );
		var item_id     = this_button.parents( '.ersrv-reservation-item-block' ).data( 'item' );
		var action      = 'mark_fav';

		// Check, if the item is already marked as favoutite.
		if ( this_button.hasClass( 'selected' ) ) {
			action = 'unmark_fav';
		}

		// Exit, if the item id is not a valid number.
		if ( -1 === is_valid_number( item_id ) ) {
			console.log( 'easy reservations: invalid item id, cannot mark item as favourite' );
			return false;
		}

		// Block the element now.
		block_element( this_button );

		// Send the AJAX now.
		$.ajax( {
			dataType: 'JSON',
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'item_favourite',
				do: action,
				item_id: item_id,
			},
			success: function ( response ) {
				// Check for invalid ajax request.
				if ( 0 === response ) {
					console.log( 'easy reservations: invalid ajax request' );
					return false;
				} else if ( 'item-favourite-done' === response.data.code ) { // If items are found.
					// Unblock the element.
					unblock_element( this_button );

					// If the action was to unmark fav, remove the selected class from the button.
					if ( 'unmark_fav' === action ) {
						this_button.removeClass( 'selected' );
					} else if ( 'mark_fav' === action ) {
						this_button.addClass( 'selected' );
					}
				}
			},
		} );
	} );

	/**
	 * Open the item quick view.
	 */
	$( document ).on( 'click', '.ersrv-quick-view-item', function() {
		$( '#ersrv-item-quick-view-modal' ).fadeIn( 'slow' );
	} );

	/**
	 * Close modal.
	 */
	$( document ).on( 'click', '.ersrv-close-modal', function() {
		$( '.ersrv-modal' ).fadeOut( 'slow' );
	} );

	/**
	 * Close the modal when clicked outside the window.
	 */
	$( 'body' ).click( function( evt ) {
		if ( 'ersrv-item-quick-view-modal' === evt.target.id ) {
			$( '.ersrv-modal' ).fadeOut( 'slow' );
		}
	} );

	$( document ).on( 'click', '#liveToastBtn', function() {
		$( '#liveToast' ).toast( 'show' );
	} );

	/**
	 * Proceed with reservation details and add the details to the cart.
	 */
	$( document ).on( 'click', '.ersrv-proceed-to-checkout-single-reservation-item', function() {
		var this_button            = $( this );
		var item_id                = $( '.single-reserve-page' ).data( 'item' );

		var accomodation_limit     = parseInt( $( '#accomodation-limit' ).val() );
		var checkin_date           = $( '#ersrv-single-reservation-checkin-datepicker' ).val();
		var checkout_date          = $( '#ersrv-single-reservation-checkout-datepicker' ).val();
		var adult_count            = parseInt( $( '#adult-accomodation-count' ).val() );
		adult_count                = ( -1 !== is_valid_number( adult_count ) ) ? adult_count : 0;
		var kid_count              = parseInt( $( '#kid-accomodation-count' ).val() );
		kid_count                  = ( -1 !== is_valid_number( kid_count ) ) ? kid_count : 0;
		var guests                 = adult_count + kid_count;
		var process_reservation    = true;
		var amenities              = [];
		var min_reservation_period = parseInt( $( '#min-reservation-period' ).val() );
		var max_reservation_period = parseInt( $( '#max-reservation-period' ).val() );

		// Vacate the error message.
		$( '.ersrv-reservation-error' ).text( '' );

		// Guests count.
		if ( -1 === is_valid_number( adult_count ) && -1 === is_valid_number( kid_count ) ) {
			process_reservation = false;
			$( '.ersrv-reservation-error.accomodation-error' ).text( reservation_guests_err_msg );
		} else if ( -1 === is_valid_number( adult_count ) && -1 !== is_valid_number( kid_count ) ) {
			process_reservation = false;
			$( '.ersrv-reservation-error.accomodation-error' ).text( reservation_only_kids_guests_err_msg );
		} else if ( accomodation_limit < guests ) {
			process_reservation = false;
			$( '.ersrv-reservation-error.accomodation-error' ).text( reservation_guests_count_exceeded_err_msg );
		}

		// If the checkin and checkout dates are not available.
		if ( '' === checkin_date && '' === checkout_date ) {
			process_reservation = false;
			$( '.ersrv-reservation-error.checkin-checkout-dates-error' ).text( reservation_checkin_checkout_missing_err_msg );
		} else if ( '' === checkin_date ) {
			process_reservation = false;
			$( '.ersrv-reservation-error.checkin-checkout-dates-error' ).text( reservation_checkin_missing_err_msg );
		} else if ( '' === checkout_date ) {
			process_reservation = false;
			$( '.ersrv-reservation-error.checkin-checkout-dates-error' ).text( reservation_checkout_missing_err_msg );
		} else {
			/**
			 * If the reservation period is more than allowed.
			 * Get the dates between checkin and checkout dates.
			 */
			var reservation_dates = ersrv_get_dates_between_2_dates( checkin_date, checkout_date );
			var reservation_days  = reservation_dates.length;
			if ( min_reservation_period > reservation_days ) {
				process_reservation = false;
				$( '.ersrv-reservation-error.checkin-checkout-dates-error' ).text( reservation_lesser_reservation_days_err_msg.replace( 'XX', min_reservation_period ) );
			} else if ( max_reservation_period < reservation_days ) {
				process_reservation = false;
				$( '.ersrv-reservation-error.checkin-checkout-dates-error' ).text( reservation_greater_reservation_days_err_msg.replace( 'XX', max_reservation_period ) );
			}
		}

		// Collect the amenities and their charges.
		$( '.ersrv-new-reservation-single-amenity' ).each( function() {
			var this_element = $( this );
			var is_checked = this_element.is( ':checked' );
			if ( true === is_checked ) {
				amenities.push(
					{
						amenity: this_element.parents( '.ersrv-single-amenity-block' ).data( 'amenity' ),
						cost: this_element.parents( '.ersrv-single-amenity-block' ).data( 'cost' ),
					}
				);
			}
		} );

		// Exit, if we cannot process the reservation.
		if ( false === process_reservation ) {
			return false;
		}

		// Block element.
		block_element( this_button );

		// Send AJAX creating a reservation.
		var data = {
			action: 'add_reservation_to_cart',
			item_id: item_id,
			checkin_date: checkin_date,
			checkout_date: checkout_date,
			adult_count: adult_count,
			kid_count: kid_count,
			amenities: amenities,
			item_subtotal: ersrv_get_reservation_item_subtotal(),
			kids_subtotal: ersrv_get_reservation_kids_subtotal(),
			security_subtotal: ersrv_get_security_subtotal(),
			amenities_subtotal: ersrv_get_amenities_subtotal(),
			item_total: ersrv_get_item_total(),
		};

		$.ajax( {
			dataType: 'JSON',
			url: ajaxurl,
			type: 'POST',
			data: data,
			success: function ( response ) {
				// Return, if the response is not proper.
				if ( 0 === response ) {
					console.warn( 'easy-reservations: invalid ajax call' );
					return false;
				}

				// If the reservation is added.
				if ( 'reservation-added-to-cart' === response.data.code ) {
					// Unblock the element.
					unblock_element( this_button );

					alert( 'reservation has been added to the cart' );
				}
			},
		} );
	} );

	/**
	 * Open the contact owner modal.
	 */
	$( document ).on( 'click', '.ersrv-contact-owner-button', function() {
		$( '#ersrv-contact-owner-modal' ).show();
	} );

	/**
	 * Submit search request from single item page.
	 */
	$( document ).on( 'click', '.ersrv-submit-search-request', function() {
		var checkin_date    = $( '#ersrv-search-checkin' ).val();
		var checkout_date   = $( '#ersrv-search-checkout' ).val();
		var location        = $( '.ersrv-item-search-location' ).val();
		var price_min_range = $( '.ersrv-search-item-price-range' ).slider( 'values', 0 );
		var price_max_range = $( '.ersrv-search-item-price-range' ).slider( 'values', 1 );
		var boat_type       = parseInt( $( '#boat-types' ).val() );

		// Take an empty array.
		var query_params_array = {
			'checkin': checkin_date,
			'checkout': checkout_date,
			'location': location,
			'price_min': price_min_range,
			'price_max': price_max_range,
			'boat_type': boat_type,
		};

		// Iterate through the items in the object to create query parameters.
		var index = 0;
		$.each( query_params_array, function( key, value ) {
			if ( 0 === index ) {
				search_reservations_page_url += '?' + key + '=' + value;
			} else {
				search_reservations_page_url += '&' + key + '=' + value;
			}

			index++;
		} );

		// Redirect now.
		window.location.href = search_reservations_page_url;
	} );

	/**
	 * Submit contact owner request.
	 */
	$( document ).on( 'click', '.ersrv-submit-contact-owner-request', function() {
		var this_button        = $( this );
		var this_button_text   = this_button.text();
		var name               = $( '#contact-owner-customer-name' ).val();
		var email              = $( '#contact-owner-customer-email' ).val();
		var phone              = $( '#contact-owner-customer-phone' ).val();
		var subject            = $( '#contact-owner-customer-query-subject' ).val();
		var message            = $( '#contact-owner-customer-message' ).val();
		var submit_contact_req = true;

		// Vacate the errors.
		$( '.ersrv-reservation-error' ).text( '' );

		// Validate the first name.
		if ( -1 === is_valid_string( name ) ) {
			$( '.ersrv-reservation-error.contact-owner-customer-name' ).text( 'Name is required.' );
			submit_contact_req = false;
		}

		// Validate email.
		if ( -1 === is_valid_string( email ) ) {
			$( '.ersrv-reservation-error.contact-owner-customer-email' ).text( 'Email is required.' );
			submit_contact_req = false;
		} else if ( -1 === is_valid_email( email ) ) {
			$( '.ersrv-reservation-error.contact-owner-customer-email' ).text( 'Email is invalid.' );
			submit_contact_req = false;
		}

		// Validate the phone.
		if ( '' === phone ) {
			$( '.ersrv-reservation-error.contact-owner-customer-phone' ).text( 'Phone is required.' );
			submit_contact_req = false;
		}

		// Validate the subject.
		if ( '' === subject ) {
			$( '.ersrv-reservation-error.contact-owner-customer-query-subject' ).text( 'Subject is required.' );
			submit_contact_req = false;
		}

		// Validate the message.
		if ( '' === message ) {
			$( '.ersrv-reservation-error.contact-owner-customer-message' ).text( 'Message is required.' );
			submit_contact_req = false;
		}

		// Exit, if user registration is set to false.
		if ( false === submit_contact_req ) {
			return false;
		}

		// Block the button.
		block_element( this_button );

		// Activate loader.
		this_button.html( '<span class="ajax-request-in-process"><i class="fa fa-refresh fa-spin"></i></span> Please wait...' );

		// Send the AJAX now.
		var data = {
			action: 'submit_contact_owner_request',
			name: name,
			email: email,
			phone: phone,
			subject: subject,
			message: message,
		};

		$.ajax( {
			dataType: 'JSON',
			url: ajaxurl,
			type: 'POST',
			data: data,
			success: function ( response ) {
				// In case of invalid AJAX call.
				if ( 0 === response ) {
					console.warn( 'easy reservations: invalid AJAX call' );
					return false;
				}

				// If user already exists.
				if ( 'contact-owner-request-saved' === response.data.code ) {
					// Unblock the button.
					unblock_element( this_button );

					// Activate loader.
					this_button.html( this_button_text );

					// Vacate all the values in the modal.
					$( '#contact-owner-customer-name' ).val( '' );
					$( '#contact-owner-customer-email' ).val( '' );
					$( '#contact-owner-customer-phone' ).val( '' );
					$( '#contact-owner-customer-query-subject' ).val( '' );
					$( '#contact-owner-customer-message' ).val( '' );

					// Close the modal.
					setTimeout( function() {
						$( '#ersrv-contact-owner-modal' ).hide();
					}, 2000 );
				}
			}
		} );
	} );

	/**
	 * Check if a email is valid.
	 *
	 * @param {string} email
	 */
	function is_valid_email( email ) {
		var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

		return ( ! regex.test( email ) ) ? -1 : 1;
	}

	/**
	 * Get the dates that faal between 2 dates.
	 *
	 * @param {*} from 
	 * @param {*} to 
	 * @returns
	 */
	function ersrv_get_dates_between_2_dates( from, to ) {
		var dates = [];

		// Return, if either of the date is blank.
		if ( '' === from || '' === to ) {
			return dates;
		}

		// Get the date time javascript object.
		from = new Date( from );
		to   = new Date( to );

		// Iterate through the end date to get the array of between dates.
		while ( from <= to ) {
			dates.push( new Date( from ) );
			from.setDate( from.getDate() + 1 );
		}

		return dates;
	}

	/**
	 * Get the item subtotal.
	 *
	 * @returns number
	 */
	 function ersrv_get_reservation_item_subtotal() {
		var item_subtotal = $( 'tr.item-price-summary td span' ).text();
		item_subtotal     = parseFloat( item_subtotal.replace( /[^\d.]/g, '' ) );
		item_subtotal     = ( -1 === is_valid_number( item_subtotal ) ) ? 0 : item_subtotal;
		item_subtotal     = item_subtotal.toFixed( 2 );

		return item_subtotal;
	}

	/**
	 * Get the kids charge subtotal.
	 *
	 * @returns number
	 */
	function ersrv_get_reservation_kids_subtotal() {
		var kids_subtotal = $( 'tr.kids-charge-summary td span' ).text();
		kids_subtotal     = parseFloat( kids_subtotal.replace( /[^\d.]/g, '' ) );
		kids_subtotal     = ( -1 === is_valid_number( kids_subtotal ) ) ? 0 : kids_subtotal;
		kids_subtotal     = kids_subtotal.toFixed( 2 );

		return kids_subtotal;
	}

	/**
	 * Get the security amount subtotal.
	 *
	 * @returns number
	 */
	function ersrv_get_security_subtotal() {
		var security_subtotal = $( 'tr.security-amount-summary td span' ).text();
		security_subtotal     = parseFloat( security_subtotal.replace( /[^\d.]/g, '' ) );
		security_subtotal     = ( -1 === is_valid_number( security_subtotal ) ) ? 0 : security_subtotal;
		security_subtotal     = security_subtotal.toFixed( 2 );

		return security_subtotal;
	}

	/**
	 * Get the amenities charge subtotal.
	 *
	 * @returns number
	 */
	function ersrv_get_amenities_subtotal() {
		var amenities_subtotal = $( 'tr.amenities-summary td span' ).text();
		amenities_subtotal     = parseFloat( amenities_subtotal.replace( /[^\d.]/g, '' ) );
		amenities_subtotal     = ( -1 === is_valid_number( amenities_subtotal ) ) ? 0 : amenities_subtotal;
		amenities_subtotal     = amenities_subtotal.toFixed( 2 );

		return amenities_subtotal;
	}

	/**
	 * Calculate the reservation total cost.
	 */
	function ersrv_calculate_reservation_total_cost() {
		var item_subtotal      = parseFloat( ersrv_get_reservation_item_subtotal() );
		var kids_subtotal      = parseFloat( ersrv_get_reservation_kids_subtotal() );
		var security_subtotal  = parseFloat( ersrv_get_security_subtotal() );
		var amenities_subtotal = parseFloat( ersrv_get_amenities_subtotal() );

		// Addup to the total cost.
		var total_cost = item_subtotal + kids_subtotal + security_subtotal + amenities_subtotal;
		total_cost = total_cost.toFixed( 2 );

		// Paste the final total.
		$( 'tr.new-reservation-total-cost td span' ).html( woo_currency + total_cost );
	}

	/**
	 * Get the item total charge.
	 *
	 * @returns number
	 */
	function ersrv_get_item_total() {
		var item_total = $( 'tr.new-reservation-total-cost td span' ).text();
		item_total     = parseFloat( item_total.replace( /[^\d.]/g, '' ) );
		item_total     = ( -1 === is_valid_number( item_total ) ) ? 0 : item_total;
		item_total     = item_total.toFixed( 2 );

		return item_total;
	}

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
