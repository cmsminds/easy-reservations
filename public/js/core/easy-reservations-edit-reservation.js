jQuery(document).ready(function ($) {
	'use strict';

	// Localized variables.
	var ajaxurl                                      = ERSRV_Edit_Reservation_Script_Vars.ajaxurl;
	var woo_currency                                 = ERSRV_Edit_Reservation_Script_Vars.woo_currency;
	var date_format                                  = ERSRV_Edit_Reservation_Script_Vars.date_format;
	var toast_success_heading                        = ERSRV_Edit_Reservation_Script_Vars.toast_success_heading;
	var toast_error_heading                          = ERSRV_Edit_Reservation_Script_Vars.toast_error_heading;
	var toast_notice_heading                         = ERSRV_Edit_Reservation_Script_Vars.toast_notice_heading;
	var reservation_guests_err_msg                   = ERSRV_Edit_Reservation_Script_Vars.reservation_guests_err_msg;
	var reservation_only_kids_guests_err_msg         = ERSRV_Edit_Reservation_Script_Vars.reservation_only_kids_guests_err_msg;
	var reservation_guests_count_exceeded_err_msg    = ERSRV_Edit_Reservation_Script_Vars.reservation_guests_count_exceeded_err_msg;
	var reservation_checkin_checkout_missing_err_msg = ERSRV_Edit_Reservation_Script_Vars.reservation_checkin_checkout_missing_err_msg;
	var reservation_checkin_missing_err_msg          = ERSRV_Edit_Reservation_Script_Vars.reservation_checkin_missing_err_msg;
	var reservation_checkout_missing_err_msg         = ERSRV_Edit_Reservation_Script_Vars.reservation_checkout_missing_err_msg;
	var reservation_lesser_reservation_days_err_msg  = ERSRV_Edit_Reservation_Script_Vars.reservation_lesser_reservation_days_err_msg;
	var reservation_greater_reservation_days_err_msg = ERSRV_Edit_Reservation_Script_Vars.reservation_greater_reservation_days_err_msg;
	var reservation_item_changes_invalidated         = ERSRV_Edit_Reservation_Script_Vars.reservation_item_changes_invalidated;
	var cannot_update_reservation_item_invalidated   = ERSRV_Edit_Reservation_Script_Vars.cannot_update_reservation_item_invalidated;

	// If sidebar is to be removed on reservation single page.
	$( '#secondary' ).remove();
	$( '#content-bottom-widgets' ).remove();
	$( '#primary' ).css( 'width', '100%' );

	/**
	 * Click on the checkin and checkout date to fetch the dates available while editing the reservation.
	 */
	$( document ).on( 'click', '.ersrv-edit-reservation-item-checkin-date, .ersrv-edit-reservation-item-checkout-date', function() {
		var this_input    = $( this );
		var this_input_id = this_input.attr( 'id' );
		var item_id       = this_input.parents( '.ersrv-edit-reservation-item-card' ).data( 'itemid' );
		var product_id    = this_input.parents( '.ersrv-edit-reservation-item-card' ).data( 'productid' );
		var checkin_date  = $( '#ersrv-edit-reservation-item-checkin-date-' + item_id ).val();
		var checkout_date = $( '#ersrv-edit-reservation-item-checkout-date-' + item_id ).val();

		// Check if the datepicker is already initiated.
		var is_datepicker_initiated = parseInt( $( '#datepicker-initiated-' + item_id ).val() );

		// Exit the initiation, if the datepicker has been initiated once.
		if ( 1 === is_valid_number( is_datepicker_initiated ) && 1 === is_datepicker_initiated ) {
			return false;
		}
		
		// Block the element.
		block_element( this_input.parents( '.input-daterange' ) );

		// Process the AJAX.
		$.ajax( {
			dataType: 'JSON',
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'edit_reservation_initiate_datepicker',
				product_id: product_id,
				checkin_date: checkin_date,
				checkout_date: checkout_date,
			},
			success: function ( response ) {
				// Return, if the response is not proper.
				if ( 0 === response ) {
					console.warn( 'easy-reservations: invalid ajax call' );
					return false;
				}

				// If the reservation is added.
				if ( 'datepicker-initiated' === response.data.code ) {
					// Unblock the button.
					unblock_element( this_input.parents( '.input-daterange' ) );

					// Reserved dates in response.
					var reserved_dates       = response.data.reserved_dates;
					var order_reserved_dates = response.data.order_reserved_dates;
					var today_formatted      = ersrv_get_formatted_date( new Date() );
					var blocked_dates        = [];

					// Prepare the blocked out dates in a separate array.
					if ( 0 < reserved_dates.length ) {
						for ( var i in reserved_dates ) {
							blocked_dates.push( reserved_dates[i].date );
						}
					}

					// Initiate the datepicker now.
					$( '#ersrv-edit-reservation-item-checkin-date-' + item_id + ', #ersrv-edit-reservation-item-checkout-date-' + item_id ).datepicker( {
						beforeShowDay: function( date ) {
							var loop_date_formatted = ersrv_get_formatted_date( date );
							var date_enabled        = true;
							var date_class          = '';
			
							// If not the past date.
							if ( today_formatted <= loop_date_formatted ) {
								// Add custom class to the active dates of the current month.
								var reserved_key = $.map( blocked_dates, function( val, i ) {
									if ( val === loop_date_formatted ) {
										return i;
									}
								} );

								// Add custom class to order reserved date.
								var order_reserved_key = $.map( order_reserved_dates, function( val, i ) {
									if ( val === loop_date_formatted ) {
										return i;
									}
								} );
			
								// If the loop date is a blocked date.
								if ( 0 < reserved_key.length ) {
									date_class = 'ui-datepicker-unselectable ui-state-disabled ersrv-date-disabled';
								} else if ( 0 < order_reserved_key.length ) {
									date_class = 'ersrv-order-reserved-date';
								} else {
									date_class = 'ersrv-date-active';
								}
							} else {
								date_class = 'ersrv-date-disabled';
							}
			
							// Return the datepicker day object.
							return [ date_enabled, date_class ];
						},
						onSelect: function ( selected_date, instance ) {
							if ( 'ersrv-edit-reservation-item-checkin-date-' + item_id === instance.id ) {
								// Min date for checkout should be on/after the checkin date.
								$( '#ersrv-edit-reservation-item-checkout-date-' + item_id ).datepicker( 'option', 'minDate', selected_date );
								setTimeout( function() {
									$( '#ersrv-edit-reservation-item-checkout-date-' + item_id ).datepicker( 'show' );
								}, 16 );
							}
						},
						dateFormat: date_format,
						minDate: 0,
					} ).on( 'change', function() {
						console.log( 'hello-its working' );
						$( '#confirmed-validation-of-item-' + item_id ).val( 1 );
						var confirm_validation_button = this_input.parents( '.details' ).find( '.ersrv-edit-reservation-validate-item-changes' );
						block_element( confirm_validation_button );
						block_element( $( '.ersrv-update-reservation button.update' ) );
						var this_input_new_val = this_input.val().toString();
						var this_input_old_val = this_input.data( 'oldval' ).toString();

						if ( this_input_new_val !== this_input_old_val ) {
							$( '#confirmed-validation-of-item-' + item_id ).val( -1 );
							unblock_element( confirm_validation_button );
						}
					} );

					// Show the datepicker.
					$( '#' + this_input_id ).datepicker( 'show' );

					// Set the hidden value to be 1.
					$( '#datepicker-initiated-' + item_id ).val( '1' );
				}
			},
		} );
	} );

	/**
	 * Update reservation.
	 */
	$( document ).on( 'click', '.ersrv-update-reservation button.update', function() {
		var this_button       = $( this );
		var invalidated_items = 0;

		// Check if the changes are validated.
		$( '.ersrv-edit-reservation-item-card' ).each( function() {
			var this_card    = $( this );
			var item_id      = parseInt( this_card.data( 'itemid' ) );
			var is_validated = parseInt( $( '#confirmed-validation-of-item-' + item_id ).val() );

			if ( -1 === is_valid_number( is_validated ) || -1 === is_validated ) {
				invalidated_items++;
			}
		} );

		// Throw the error, if there are any invalidated items.
		if ( 0 < invalidated_items ) {
			ersrv_show_notification( 'bg-danger', 'fa-skull-crossbones', toast_error_heading, cannot_update_reservation_item_invalidated );
			return false;
		}

		// Order ID.
		var order_id = parseInt( $( '.ersrv-order-id' ).val() );

		// Cost difference.
		var cost_difference = ersrv_get_reservation_cost_difference();

		// Order total.
		var order_total = 0.0;

		// Items data.
		var items_data = [];

		/**
		 * If you're here, it means that it is okay to update the reservation.
		 * Iterate through the items to collect the new data.
		 */
		$( '.ersrv-edit-reservation-item-card' ).each( function() {
			var this_card  = $( this );
			var item_id    = parseInt( this_card.data( 'itemid' ) );
			var item_total = parseFloat( ersrv_calculate_edit_reservation_item_total_cost( item_id ) );  

			// Add the item cost to the order total.
			order_total += item_total;

			// Collect all the data in an array.
			items_data.push( {
					item_id: item_id,
					adult_subtotal: parseFloat( ersrv_get_edit_reservation_item_subtotal( item_id ) ),
					kids_subtotal: parseFloat( ersrv_get_edit_reservation_kids_subtotal( item_id ) ),
					item_total: item_total,
					checkin: $( '#ersrv-edit-reservation-item-checkin-date-' + item_id ).val(),
					checkout: $( '#ersrv-edit-reservation-item-checkout-date-' + item_id ).val(),
					adult_count: parseInt( $( '#ersrv-edit-reservation-item-adult-count-' + item_id ).val() ),
					kids_count: parseInt( $( '#ersrv-edit-reservation-item-kid-count-' + item_id ).val() ),
			} );
		} );

		// Block the button.
		block_element( this_button );

		// Process the AJAX.
		$.ajax( {
			dataType: 'JSON',
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'update_reservation',
				order_id: order_id,
				cost_difference: cost_difference,
				items_data: items_data,
				order_total: order_total,
			},
			success: function ( response ) {
				// Return, if the response is not proper.
				if ( 0 === response ) {
					console.warn( 'easy-reservations: invalid ajax call' );
					return false;
				}

				// If the reservation is added.
				if ( 'reservation-updated' === response.data.code ) {
					// Unblock the button.
					unblock_element( this_button );

					// Show notification.
					ersrv_show_notification( 'bg-success', 'fa-check-circle', toast_success_heading, response.data.toast_message );

					// Redirecting to the order details now.
					setTimeout( function() {
						window.location.href = response.data.view_order_link;
					}, 2000 );
				}
			},
		} );
	} );

	/**
	 * Check if any value is changed, so the user should confirm availability.
	 */
	$( document ).on( 'keyup click change', '.ersrv-edit-reservation-item-value', function() {
		var this_input  = $( this );
		var item_id     = this_input.parents( '.ersrv-edit-reservation-item-card' ).data( 'itemid' );
		var input_boxes = this_input.parents( '.details' ).find( '.ersrv-edit-reservation-item-value' );

		// Initiatives to keep the validations intact.
		$( '#confirmed-validation-of-item-' + item_id ).val( 1 );
		var confirm_validation_button = this_input.parents( '.details' ).find( '.ersrv-edit-reservation-validate-item-changes' );
		block_element( confirm_validation_button );
		block_element( $( '.ersrv-update-reservation button.update' ) );

		// Loop through the input boxes to check if either value is changed.
		input_boxes.each( function() {
			var input_box = $( this );
			var new_val = input_box.val().toString();
			var old_val = input_box.data( 'oldval' ).toString();

			if ( new_val !== old_val ) {
				$( '#confirmed-validation-of-item-' + item_id ).val( -1 );
				unblock_element( confirm_validation_button );
			}
		} );

		// Enable the update button in case of any item value is changed.
		$( '.ersrv-edit-reservation-validate-item-changes' ).each( function() {
			var is_blocked = is_element_blocked( $( this ) );

			// Exit the loop if the button is not blocked.
			if ( false === is_blocked ) {
				unblock_element( $( '.ersrv-update-reservation button.update' ) );
				return false;
			}
		} );
	} );

	/**
	 * Edit reservation adult accomodation charge.
	 */
	$( document ).on( 'keyup click', '.ersrv-edit-reservation-item-adult-count', function() {
		var this_input  = $( this );
		var item_id     = this_input.parents( '.ersrv-edit-reservation-item-card' ).data( 'itemid' );
		var adult_count = parseInt( this_input.val() );
		adult_count     = ( -1 === is_valid_number( adult_count ) ) ? 0 : adult_count;

		// The adult count should be minimum what was previously selected.
		var min_adult_count = parseInt( this_input.attr( 'min' ) );
		adult_count         = ( adult_count < min_adult_count ) ? min_adult_count : adult_count;
		this_input.val( adult_count );

		// Manage the adult's cost.
		var per_adult_charge       = parseFloat( $( '#adult-charge-' + item_id ).val() );
		var total_charge           = adult_count * per_adult_charge;
		var formatted_total_charge = ersrv_get_formatted_price( total_charge );

		// Paste this final adult price in summary.
		$( 'tr#item-price-summary-' + item_id + ' td span.ersrv-cost' ).html( formatted_total_charge );

		// Calculate the total cost.
		ersrv_calculate_edit_reservation_item_total_cost( item_id );
	} );

	/**
	 * Edit reservation kid accomodation charge.
	 */
	$( document ).on( 'keyup click', '.ersrv-edit-reservation-item-kid-count', function() {
		var this_input = $( this );
		var item_id    = this_input.parents( '.ersrv-edit-reservation-item-card' ).data( 'itemid' );
		var kid_count  = parseInt( this_input.val() );
		kid_count      = ( -1 === is_valid_number( kid_count ) ) ? 0 : kid_count;

		// The adult count should be minimum what was previously selected.
		var min_kid_count = parseInt( this_input.attr( 'min' ) );
		kid_count         = ( kid_count < min_kid_count ) ? min_kid_count : kid_count;
		this_input.val( kid_count );

		// Manage the kids cost.
		var per_kid_charge         = parseFloat( $( '#kid-charge-' + item_id ).val() );
		var total_charge           = kid_count * per_kid_charge;
		var formatted_total_charge = ersrv_get_formatted_price( total_charge );

		// Paste this final kid price in summary.
		$( 'tr#kids-charge-summary-' + item_id + ' td span.ersrv-cost' ).html( formatted_total_charge );

		// Calculate the total cost.
		ersrv_calculate_edit_reservation_item_total_cost( item_id );
	} );

	/**
	 * Validate the item changes.
	 */
	$( document ).on( 'click', '.ersrv-edit-reservation-validate-item-changes', function() {
		var this_button = $( this );
		var item_id     = this_button.parents( '.ersrv-edit-reservation-item-card' ).data( 'itemid' );
		var product_id  = this_button.parents( '.ersrv-edit-reservation-item-card' ).data( 'productid' );

		// Get the item details.
		var checkin_date       = $( '#ersrv-edit-reservation-item-checkin-date-' + item_id ).val();
		var checkout_date      = $( '#ersrv-edit-reservation-item-checkout-date-' + item_id ).val();
		var old_checkin_date   = $( '#ersrv-edit-reservation-item-checkin-date-' + item_id ).data( 'oldval' );
		var old_checkout_date  = $( '#ersrv-edit-reservation-item-checkout-date-' + item_id ).data( 'oldval' );
		var adult_count        = parseInt( $( '#ersrv-edit-reservation-item-adult-count-' + item_id ).val() );
		adult_count            = ( -1 !== is_valid_number( adult_count ) ) ? adult_count : 0;
		var kid_count          = parseInt( $( '#ersrv-edit-reservation-item-kid-count-' + item_id ).val() );
		kid_count              = ( -1 !== is_valid_number( kid_count ) ) ? kid_count : 0;
		var guests             = adult_count + kid_count;
		var accomodation_limit = parseInt( $( '#accomodation-limit-' + item_id ).val() );
		var min_reservation    = $( '#min-reservation-period-' + item_id ).val();
		var max_reservation    = $( '#max-reservation-period-' + item_id ).val();

		// Item validated.
		var item_validated = true;

		// Vacate all the errors.
		$( '.ersrv-reservation-error' ).text();

		// Guests count.
		if ( -1 === is_valid_number( adult_count ) && -1 === is_valid_number( kid_count ) ) {
			item_validated = false;
			$( '.ersrv-reservation-error#guests-error-' + item_id ).text( reservation_guests_err_msg );
		} else if ( -1 === is_valid_number( adult_count ) && -1 !== is_valid_number( kid_count ) ) {
			item_validated = false;
			$( '.ersrv-reservation-error#guests-error-' + item_id ).text( reservation_only_kids_guests_err_msg );
		} else if ( accomodation_limit < guests ) {
			item_validated = false;
			$( '.ersrv-reservation-error#guests-error-' + item_id ).text( reservation_guests_count_exceeded_err_msg );
		}

		// If the checkin and checkout dates are not available.
		if ( '' === checkin_date && '' === checkout_date ) {
			item_validated = false;
			$( '.ersrv-reservation-error#checkin-checkout-dates-error-' + item_id ).text( reservation_checkin_checkout_missing_err_msg );
		} else if ( '' === checkin_date ) {
			item_validated = false;
			$( '.ersrv-reservation-error#checkin-checkout-dates-error-' + item_id ).text( reservation_checkin_missing_err_msg );
		} else if ( '' === checkout_date ) {
			item_validated = false;
			$( '.ersrv-reservation-error#checkin-checkout-dates-error-' + item_id ).text( reservation_checkout_missing_err_msg );
		} else {
			/**
			 * If the reservation period is more than allowed.
			 * Get the dates between checkin and checkout dates.
			 */
			var new_reservation_dates = ersrv_get_dates_between_2_dates( checkin_date, checkout_date );
			var new_reservation_days  = new_reservation_dates.length;
			if ( min_reservation > new_reservation_days ) {
				item_validated = false;
				$( '.ersrv-reservation-error#checkin-checkout-dates-error-' + item_id ).text( reservation_lesser_reservation_days_err_msg.replace( 'XX', min_reservation ) );
			} else if ( max_reservation < new_reservation_days ) {
				item_validated = false;
				$( '.ersrv-reservation-error#checkin-checkout-dates-error-' + item_id ).text( reservation_greater_reservation_days_err_msg.replace( 'XX', max_reservation ) );
			}
		}

		// Exit, if we cannot process the reservation.
		if ( false === item_validated ) {
			ersrv_show_notification( 'bg-danger', 'fa-skull-crossbones', toast_error_heading, reservation_item_changes_invalidated );
			return false;
		}

		// Block the button.
		block_element( this_button );

		// Process the AJAX.
		$.ajax( {
			dataType: 'JSON',
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'edit_reservation_validate_item_changes',
				product_id: product_id,
				checkin_date: checkin_date,
				checkout_date: checkout_date,
				old_checkin_date: old_checkin_date,
				old_checkout_date: old_checkout_date,
			},
			success: function ( response ) {
				// Return, if the response is not proper.
				if ( 0 === response ) {
					console.warn( 'easy-reservations: invalid ajax call' );
					return false;
				}

				// If the reservation is added.
				if ( 'item-changes-validated' === response.data.code ) {
					// Is success & toast message.
					var is_success    = response.data.is_success;
					var toast_message = response.data.toast_message;

					// Show error notification.
					if ( 'no' === is_success ) {
						ersrv_show_notification( 'bg-danger', 'fa-skull-crossbones', toast_error_heading, toast_message );

						// Unblock the button.
						unblock_element( this_button );
					} else if ( 'yes' === is_success ) {
						ersrv_show_notification( 'bg-success', 'fa-check-circle', toast_success_heading, toast_message );
						$( '#confirmed-validation-of-item-' + item_id ).val( 1 );
					}
				}
			},
		} );
	} );

	/**
	 * Get the item subtotal.
	 *
	 * @returns number
	 */
	function ersrv_get_edit_reservation_item_subtotal( item_id ) {
		var item_subtotal = $( 'tr#item-price-summary-' + item_id + ' td span span' ).text();
		item_subtotal     = parseFloat( item_subtotal.replace( /[^\d.]/g, '' ) );
		item_subtotal     = ( -1 === is_valid_number( item_subtotal ) ) ? 0 : item_subtotal;

		return item_subtotal;
	}

	/**
	 * Get the kids charge subtotal.
	 *
	 * @returns number
	 */
	function ersrv_get_edit_reservation_kids_subtotal( item_id ) {
		var kids_subtotal = $( 'tr#kids-charge-summary-' + item_id + ' td span span' ).text();
		kids_subtotal     = parseFloat( kids_subtotal.replace( /[^\d.]/g, '' ) );
		kids_subtotal     = ( -1 === is_valid_number( kids_subtotal ) ) ? 0 : kids_subtotal;

		return kids_subtotal;
	}

	/**
	 * Get the security amount subtotal.
	 *
	 * @returns number
	 */
	function ersrv_get_edit_security_subtotal( item_id ) {
		var security_subtotal = $( 'tr#security-summary-' + item_id + ' td span span' ).text();
		security_subtotal     = parseFloat( security_subtotal.replace( /[^\d.]/g, '' ) );
		security_subtotal     = ( -1 === is_valid_number( security_subtotal ) ) ? 0 : security_subtotal;

		return security_subtotal;
	}

	/**
	 * Get the amenities charge subtotal.
	 *
	 * @returns number
	 */
	function ersrv_get_edit_amenities_subtotal( item_id ) {
		var amenities_subtotal = $( 'tr#amenities-summary-' + item_id + ' td span span' ).text();
		amenities_subtotal     = parseFloat( amenities_subtotal.replace( /[^\d.]/g, '' ) );
		amenities_subtotal     = ( -1 === is_valid_number( amenities_subtotal ) ) ? 0 : amenities_subtotal;

		return amenities_subtotal;
	}

	/**
	 * Calculate the reservation total cost.
	 */
	function ersrv_calculate_edit_reservation_item_total_cost( item_id ) {
		var item_subtotal      = parseFloat( ersrv_get_edit_reservation_item_subtotal( item_id ) );
		var kids_subtotal      = parseFloat( ersrv_get_edit_reservation_kids_subtotal( item_id ) );
		var security_subtotal  = parseFloat( ersrv_get_edit_security_subtotal( item_id ) );
		var amenities_subtotal = parseFloat( ersrv_get_edit_amenities_subtotal( item_id ) );

		// Addup to the total cost.
		var total_cost           = item_subtotal + kids_subtotal + security_subtotal + amenities_subtotal;
		var total_cost_formatted = ersrv_get_formatted_price( total_cost );

		// Paste the final total.
		$( 'tr#edit-reservation-item-total-cost-' + item_id + ' td span.ersrv-cost' ).html( total_cost_formatted );
		$( 'span#ersrv-edit-reservation-item-subtotal-' + item_id ).html( total_cost_formatted );

		// Calculate the cost difference.
		ersrv_calculate_reservation_cost_difference();

		return total_cost;
	}

	/**
	 * Calculate the cost difference.
	 */
	function ersrv_calculate_reservation_cost_difference() {
		var item_new_total = 0.00;
		// Iterate through the items to get IDs.
		$( '.ersrv-edit-reservation-item-card' ).each( function() {
			var this_card   = $( this );
			var item_id     = this_card.data( 'itemid' );
			var subtotal    = $( '#ersrv-edit-reservation-item-subtotal-' + item_id ).text();
			subtotal        = parseFloat( subtotal.replace( /[^\d.]/g, '' ) );
			subtotal        = ( -1 === is_valid_number( subtotal ) ) ? 0 : subtotal;
			item_new_total += subtotal;
		} );

		// Calculate the cost difference now.
		var old_order_total = parseFloat( $( '.ersrv-edit-reservation-order-total' ).val() );
		var cost_difference = item_new_total - old_order_total;

		// Get the formatted cost difference.
		cost_difference = ersrv_get_formatted_price( cost_difference );

		// Paste the cost difference.
		$( '.ersrv-edit-reservation-cost-difference' ).html( cost_difference );
	}

	/**
	 * Return the cost difference.
	 */
	function ersrv_get_reservation_cost_difference() {
		var cost_difference = $( '.ersrv-edit-reservation-cost-difference span' ).text();
		cost_difference     = parseFloat( cost_difference.replace( /[^\d.]/g, '' ) );
		cost_difference     = ( -1 === is_valid_number( cost_difference ) ) ? 0 : cost_difference;

		return cost_difference;
	}

	/**
	 * Show the notification text.
	 *
	 * @param {string} bg_color Holds the toast background color.
	 * @param {string} icon Holds the toast icon.
	 * @param {string} heading Holds the toast heading.
	 * @param {string} message Holds the toast body message.
	 */
	function ersrv_show_notification( bg_color, icon, heading, message ) {
		$( '.ersrv-notification-wrapper .toast' ).removeClass( 'bg-success bg-warning bg-danger' );
		$( '.ersrv-notification-wrapper .toast' ).addClass( bg_color );
		$( '.ersrv-notification-wrapper .toast .ersrv-notification-icon' ).removeClass( 'fa-skull-crossbones fa-check-circle fa-exclamation-circle' );
		$( '.ersrv-notification-wrapper .toast .ersrv-notification-icon' ).addClass( icon );
		$( '.ersrv-notification-wrapper .toast .ersrv-notification-heading' ).text( heading );
		$( '.ersrv-notification-wrapper .toast .ersrv-notification-message' ).html( message );
		$( '.ersrv-notification-wrapper .toast' ).removeClass( 'hide' ).addClass( 'show' );

		setTimeout( function() {
			$( '.ersrv-notification-wrapper .toast' ).removeClass( 'show' ).addClass( 'hide' );
		}, 5000 );
	}

	/**
	 * Is the element blocked.
	 *
	 * @param {string} element
	 */
	function is_element_blocked( element ) {

		return element.hasClass( 'non-clickable' ) ? true : false;
	}

	/**
	 * Block element.
	 *
	 * @param {string} element
	 */
	 function block_element( element ) {
		element.addClass( 'non-clickable' );
	}

	/**
	 * Unblock element.
	 *
	 * @param {string} element
	 */
	function unblock_element( element ) {
		element.removeClass( 'non-clickable' );
	}

	/**
	 * Check if a number is valid.
	 * 
	 * @param {number} data 
	 */
	function is_valid_number( data ) {
		if ( '' === data || undefined === data || isNaN( data ) || 0 === data ) {
			return -1;
		}

		return 1;
	}

	/**
	 * Check if a string is valid.
	 * 
	 * @param {string} data 
	 */
	function is_valid_string( data ) {
		if ( '' === data || undefined === data || !isNaN( data ) || 0 === data ) {
			return -1;
		}

		return 1;
	}

	/**
	 * Return the formatted price.
	 *
	 * @param {*} cost 
	 * @returns 
	 */
	function ersrv_get_formatted_price( cost ) {
		// Upto 2 decimal places.
		cost = cost.toFixed( 2 );

		// Let's first comma format the price.
		var cost_parts = cost.toString().split( '.' );
		cost_parts[0]  = cost_parts[0].replace( /\B(?=(\d{3})+(?!\d))/g, ',' );
		cost           = cost_parts.join( '.' );
		
		// Prepare the cost html now.
		var cost_html  = '<span class="woocommerce-Price-amount amount">';
		cost_html     += '<span class="woocommerce-Price-currencySymbol">' + woo_currency + '</span>';
		cost_html     += cost;
		cost_html     += '</span>';

		return cost_html;
	}

	/**
	 * Return the formatted date based on the global date format.
	 *
	 * @param {*} date_obj 
	 * @returns 
	 */
	function ersrv_get_formatted_date( date_obj ) {
		var month = ( ( '0' + ( date_obj.getMonth() + 1 ) ).slice( -2 ) );
		var date  = ( ( '0' + ( date_obj.getDate() ) ).slice( -2 ) );
		var year  = date_obj.getFullYear();

		// Replace the variables now.
		var formatted_date = date_format.replace( 'dd', date );
		formatted_date = formatted_date.replace( 'mm', month );
		formatted_date = formatted_date.replace( 'yy', year );

		return formatted_date;
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
} );
