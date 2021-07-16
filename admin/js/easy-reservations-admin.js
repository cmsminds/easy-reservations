jQuery( document ).ready( function( $ ) {
	'use strict';

	// Localized variables.
	var ajaxurl                 = ERSRV_Admin_Script_Vars.ajaxurl;
	var same_as_adult           = ERSRV_Admin_Script_Vars.same_as_adult;
	var export_reservations     = ERSRV_Admin_Script_Vars.export_reservations;
	var email_address_required  = ERSRV_Admin_Script_Vars.email_address_required;
	var email_address_invalid   = ERSRV_Admin_Script_Vars.email_address_invalid;
	var password_required       = ERSRV_Admin_Script_Vars.password_required;
	var accomodation_limit_text = ERSRV_Admin_Script_Vars.accomodation_limit_text;
	var start_of_week           = parseInt( ERSRV_Admin_Script_Vars.start_of_week );

	// Add HTML after the kid charge number field.
	$( '<a class="ersrv-copy-adult-charge" href="javascript:void(0);">' + same_as_adult + '</a>' ).insertAfter( '#accomodation_kid_charge' );

	// Add the dropdown on the order's page.
	var export_reservations_button = '<a class="page-title-action ersrv-export-reservations" href="javascript:void(0);">' + export_reservations + '</a>';
	$( export_reservations_button ).insertAfter( 'body.woocommerce-page.post-type-shop_order .wrap h1.wp-heading-inline' );

	/**
	 * Copy the adult charge to the kid's charge.
	 */
	$( document ).on( 'click', '.ersrv-copy-adult-charge', function() {
		$( '#accomodation_kid_charge' ).val( $( '#accomodation_adult_charge' ).val() );
	} );

	/**
	 * Open the modal to allow date range selection.
	 */
	 $( document ).on( 'click', '.ersrv-export-reservations', function() {
		$( '#ersrv-export-reservations-modal' ).fadeIn( 'slow' );
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
		if ( 'ersrv-export-reservations-modal' === evt.target.id ) {
			$( '.ersrv-modal' ).fadeOut( 'slow' );
		}
	} );

	/**
	 * Export reservations.
	 */
	$( document ).on( 'click', '.export-reservations', function() {
		var this_button = $( this );
		var from_date   = $( '#ersrv-date-from' ).val();
		var to_date     = $( '#ersrv-date-to' ).val();
		var format      = $( '#ersrv-export-format' ).val();

		// Block the element.
		block_element( this_button );

		// Send the AJAX for clearing the log.
		$.ajax( {
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'export_reservations',
				from_date: from_date,
				to_date: to_date,
				format: format,
			},
			success: function ( response ) {
				// Return, if the response is not proper.
				if ( 'mods-fetched' !== response.data.code ) {
					return false;
				}

				// Unblock the element.
				unblock_element( $( '#cf-export-rooms-by-mods-modal .select-mods' ) );

				// Change the button text.
				$( '#cf-export-rooms-by-mods-modal .select-mods #cf-mods-select' ).html( response.data.html );
			},
		} );
	} );

	/**
	 * Add amenity HTML block.
	 */
	$( document ).on( 'click', '.ersrv-add-amenity-html', function() {
		// Block the element.
		block_element( $( '.reservations-amenities' ) );

		// Send the AJAX for adding amenity html.
		$.ajax( {
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'get_amenity_html',
			},
			success: function ( response ) {
				// Return, if the response is not proper.
				if ( 'amenity-html-fetched' !== response.data.code ) {
					return false;
				}

				// Unblock the element.
				unblock_element( $( '.reservations-amenities' ) );

				// Apend the amenity html block.
				$( '.amenities-list' ).append( response.data.html );
			},
		} );
	} );

	/**
	 * Remove amenity HTML block.
	 */
	 $( document ).on( 'click', '.ersrv-remove-amenity-html', function() {
		$( this ).parent( '.reservation_amenity_field' ).remove();
	} );

	/**
	 * Open calendar for blockingout dates to reservation calendar.
	 */
	$( document ).on( 'click', '.ersrv-add-blockedout-date-html', function() {
		$( '#ersrv-blockout-reservation-calendar-dates-modal' ).fadeIn( 'slow' );
	} );

	/**
	 * Add blockout dates HTML.
	 */
	$( document ).on( 'click', '.submit-blockout-calendar-dates', function() {
		var this_button = $( this );
		var date_from   = $( '#ersrv-blockout-date-from' ).val();
		var date_to     = $( '#ersrv-blockout-date-to' ).val();
		var message     = $( '.ersrv-blockout-dates-message textarea' ).val();

		// Block the element.
		block_element( this_button );

		// Send the AJAX for adding blockout date html.
		$.ajax( {
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'get_blockout_date_html',
				date_from: date_from,
				date_to: date_to,
				message: message,
			},
			success: function ( response ) {
				// Return, if the response is not proper.
				if ( 'blockout-date-html-fetched' !== response.data.code ) {
					return false;
				}

				// Unblock the element.
				unblock_element( this_button );

				// Apend the blockout date html block.
				$( '.blockout-dates-list' ).append( response.data.html );

				// Vacate the modal fields.
				$( '#ersrv-blockout-date-from, #ersrv-blockout-date-to, .ersrv-blockout-dates-message textarea' ).val( '' );

				// Hide the modal.
				$( '#ersrv-blockout-reservation-calendar-dates-modal' ).fadeOut( 'slow' );
			},
		} );
	} );

	/**
	 * Remove blockout date HTML block.
	 */
	$( document ).on( 'click', '.ersrv-remove-blockout-date-html', function() {
		$( this ).parent( '.reservation_blockout_date_field' ).remove();
	} );

	/**
	 * Open the new customer modal.
	 */
	$( document ).on( 'click', '.ersrv-create-new-customer-link', function() {
		$( '#ersrv-new-customer-modal' ).fadeIn( 'slow' );
	} );

	/**
	 * Submit new customer details.
	 */
	$( document ).on( 'click', '.submit-customer button', function() {
		var this_button      = $( this );
		var this_button_text = this_button.text();
		var first_name       = $( '#ersrv-customer-first-name' ).val();
		var last_name        = $( '#ersrv-customer-last-name' ).val();
		var email            = $( '#ersrv-customer-email' ).val();
		var phone            = $( '#ersrv-customer-phone' ).val();
		var password         = $( '#ersrv-customer-password' ).val();
		var address_line     = $( '#ersrv-customer-address-line' ).val();
		var address_line_2   = $( '#ersrv-customer-address-line-2' ).val();
		var country          = $( '#ersrv-customer-country' ).val();
		var state            = $( '#ersrv-customer-state' ).val();
		state                = ( null === state ) ? '' : state;
		var city             = $( '#ersrv-customer-city' ).val();
		var postcode         = $( '#ersrv-customer-postcode' ).val();
		var register_user    = true;

		// Vacate the errors.
		$( '.ersrv-form-field-error, .ersrv-form-error' ).text( '' );

		// Validate the first name.
		if ( -1 === is_valid_string( first_name ) ) {
			$( '.ersrv-form-field-error.first-name-error' ).text( 'First name is required.' );
			register_user = false;
		}

		// Validate the last name.
		if ( -1 === is_valid_string( last_name ) ) {
			$( '.ersrv-form-field-error.last-name-error' ).text( 'Last name is required.' );
			register_user = false;
		}

		// Validate email.
		if ( -1 === is_valid_string( email ) ) {
			$( '.ersrv-form-field-error.email-error' ).text( email_address_required );
			register_user = false;
		} else if ( -1 === is_valid_email( email ) ) {
			$( '.ersrv-form-field-error.email-error' ).text( email_address_invalid );
			register_user = false;
		}

		// Validate the phone.
		if ( '' === phone ) {
			$( '.ersrv-form-field-error.phone-error' ).text( 'Phone number is required.' );
			register_user = false;
		}

		// Validate password.
		if ( -1 === is_valid_string( password ) ) {
			$( '.ersrv-form-field-error.password-error' ).text( password_required );
			register_user = false;
		}

		// Validate the address line.
		if ( -1 === is_valid_string( address_line ) ) {
			$( '.ersrv-form-field-error.address-line-error' ).text( 'Address line is required.' );
			register_user = false;
		}

		// Validate the country.
		if ( -1 === is_valid_string( country ) ) {
			$( '.ersrv-form-field-error.country-error' ).text( 'Country is required.' );
			register_user = false;
		}

		// Validate the city.
		if ( -1 === is_valid_string( city ) ) {
			$( '.ersrv-form-field-error.city-error' ).text( 'City is required.' );
			register_user = false;
		}

		// Validate the postcode.
		if ( -1 === is_valid_string( postcode ) ) {
			$( '.ersrv-form-field-error.postcode-error' ).text( 'Postcode is required.' );
			register_user = false;
		}

		// Exit, if user registration is set to false.
		if ( false === register_user ) {
			return false;
		}

		// Block the button.
		block_element( this_button );

		// Activate loader.
		this_button.html( '<span class="webinar-registration-in-process"><i class="fa fa-refresh fa-spin"></i></span> Please wait...' );

		// Send the AJAX now.
		var data = {
			action: 'register_new_customer',
			first_name: first_name,
			last_name: last_name,
			email: email,
			phone: phone,
			password: password,
			address_line: address_line,
			address_line_2: address_line_2,
			country: country,
			state: state,
			city: city,
			postcode: postcode,
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
				if ( 'ersrv-user-exists' === response.data.code ) {
					// Unblock the button.
					unblock_element( this_button );

					// Activate loader.
					this_button.html( this_button_text );

					// Paste the error message.
					$( '.ersrv-form-error' ).text( response.data.error_message );

					return false;
				}

				// If user is created.
				if ( 'ersrv-user-registered' === response.data.code ) {
					// Unblock the button.
					unblock_element( this_button );

					// Activate loader.
					this_button.html( this_button_text );

					// Vacate all the form values.
					$( '#ersrv-customer-first-name, #ersrv-customer-last-name, #ersrv-customer-email, #ersrv-customer-password' ).val( '' );

					// Hide the modal.
					$( '.ersrv-close-modal' ).click();

					// Add the user as an option in the select box, and select the created user.
					$( '#customer-id' ).append( response.data.user_html ).val( response.data.user_id );
				}
			}
		} );
	} );

	/**
	 * Generate new password.
	 */
	$( document ).on( 'click', '.ersrv-generate-password', function() {
		// Block the element.
		block_element( $( '#ersrv-customer-password' ) );

		// Send AJAX.
		$.ajax( {
			dataType: 'JSON',
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'generate_new_password',
			},
			success: function ( response ) {
				// In case of invalid AJAX call.
				if ( 0 === response ) {
					console.warn( 'easy reservations: invalid AJAX call' );
					return false;
				}

				// If the password is generated.
				if ( 'password-generated' === response.data.code ) {
					// Unblock the element.
					unblock_element( $( '#ersrv-customer-password' ) );

					// Paste the password.
					$( '#ersrv-customer-password' ).val( response.data.password );
				}
			}
		} );
	} );

	/**
	 * Fetch the reservation item details for creating new reservation from admin.
	 */
	$( document ).on( 'change', '#item-id', function() {
		var this_select = $( this );
		var item_id     = this_select.val();

		// Disable everything if item is invalid.
		if ( -1 === is_valid_number( item_id ) ) {
			// Block the items.
			block_element( $( 'tr.ersrv-new-reservation-customer-row' ) );
			block_element( $( 'tr.ersrv-new-reservation-accomodation-row' ) );
			block_element( $( 'tr.ersrv-new-reservation-checkin-checkout-row' ) );
			block_element( $( 'tr.ersrv-new-reservation-amenities-row' ) );
			block_element( $( '.ersrv-add-new-reservation' ) );
			return false;
		}

		// Change the accomodation limit text.
		$( '.ersrv-new-reservation-limit-text' ).text( accomodation_limit_text );

		// Block the element.
		block_element( this_select );

		// Send AJAX.
		$.ajax( {
			dataType: 'JSON',
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'get_reservable_item_details',
				item_id: item_id,
			},
			success: function ( response ) {
				// In case of invalid AJAX call.
				if ( 0 === response ) {
					console.warn( 'easy reservations: invalid AJAX call' );
					return false;
				}

				// If the password is generated.
				if ( 'item-details-fetched' === response.data.code ) {
					// Unblock the element.
					unblock_element( this_select );

					// Unblock the items.
					unblock_element( $( 'tr.ersrv-new-reservation-customer-row' ) );
					unblock_element( $( 'tr.ersrv-new-reservation-accomodation-row' ) );
					unblock_element( $( 'tr.ersrv-new-reservation-checkin-checkout-row' ) );
					unblock_element( $( 'tr.ersrv-new-reservation-amenities-row' ) );
					unblock_element( $( '.ersrv-add-new-reservation' ) );

					// Item details.
					var item_details       = response.data.details;
					var accomodation_limit = ( -1 !== is_valid_number( item_details.accomodation_limit ) ) ? parseInt( item_details.accomodation_limit ) : '';
					$( '#accomodation-limit' ).val( accomodation_limit );
					$( '.ersrv-new-reservation-limit-text' ).text( accomodation_limit_text.replace( '--', accomodation_limit ) );

					var blocked_dates          = item_details.reserved_dates;
					var current_date           = new Date();
					var current_month          = ( ( '0' + ( current_date.getMonth() + 1 ) ).slice( -2 ) );
					var today_formatted        = current_date.getFullYear() + '-' + current_month + '-' + current_date.getDate();
					var reserved_dates         = [];

					// Prepare the blocked out dates in a separate array.
					if ( 0 < blocked_dates.length ) {
						for ( var i in blocked_dates ) {
							reserved_dates.push( blocked_dates[i].date );
						}
					}

					// Set the calendar on checkin and checkout dates.
					$( '#ersrv-checkin-date, #ersrv-checkout-date' ).datepicker( {
						beforeShowDay: function( date ) {
							var loop_month          = ( ( '0' + ( date.getMonth() + 1 ) ).slice( -2 ) );
							var loop_date_formatted = date.getFullYear() + '-' + loop_month + '-' + date.getDate();
							var date_enabled        = true;

							// If not the past date.
							if ( today_formatted <= loop_date_formatted ) {
								// Add custom class to the active dates of the current month.
								var key = $.map( reserved_dates, function( val, i ) {
									if ( val === loop_date_formatted ) {
										return i;
									}
								} );

								// If the loop date is a blocked date.
								if ( 0 < key.length ) {
									date_enabled = false;
								}
							} else {
								date_enabled = false;
							}

							// Return the datepicker day object.
							return [ date_enabled ];
						},
						onSelect: function ( selected_date, instance ) {
							if ( 'ersrv-checkin-date' === instance.id ) {
								// Min date for checkout should be on/after the checkin date.
								$( '#ersrv-checkout-date' ).datepicker( 'option', 'minDate', selected_date );
								setTimeout( function() {
									$( '#ersrv-checkout-date' ).datepicker( 'show' );
								}, 16 );
							}
						},
						dateFormat: 'yy-mm-dd',
						minDate: 0,
						weekStart: start_of_week,
						changeMonth: true,
					} );

					// Min and max reservation periods.
					var min_reservation_period = ( -1 !== is_valid_number( item_details.min_reservation_period ) ) ? parseInt( item_details.min_reservation_period ) : -1;
					var max_reservation_period = ( -1 !== is_valid_number( item_details.max_reservation_period ) ) ? parseInt( item_details.max_reservation_period ) : -1;
					$( '#min-reservation-period' ).val( min_reservation_period );
					$( '#max-reservation-period' ).val( max_reservation_period );

					// Put the amenities html.
					$( 'tr.ersrv-new-reservation-amenities-row td' ).html( item_details.amenity_html );

					// Adult & kid's charge.
					var adult_charge = ( -1 !== is_valid_number( item_details.adult_charge ) ) ? parseInt( item_details.adult_charge ) : 0;
					var kid_charge   = ( -1 !== is_valid_number( item_details.kid_charge ) ) ? parseInt( item_details.kid_charge ) : 0;
					$( '#adult-charge' ).val( adult_charge );
					$( '#kid-charge' ).val( kid_charge );

					// Security amount.
					var security_amount   = ( -1 !== is_valid_number( item_details.security_amount ) ) ? parseInt( item_details.security_amount ) : 0;
					$( '#security-amount' ).val( security_amount );
				}
			}
		} );
	} );

	/**
	 * Add reservation from admin panel.
	 */
	$( document ).on( 'click', '.ersrv-add-new-reservation', function() {
		var this_button         = $( this );
		var item_id             = $( '#item-id' ).val();
		var customer_id         = $( '#customer-id' ).val();
		customer_id             = ( -1 !== is_valid_number( customer_id ) ) ? customer_id : 0;
		var accomodation_limit  = parseInt( $( '#accomodation-limit' ).val() );
		var checkin_date        = $( '#ersrv-checkin-date' ).val();
		var checkout_date       = $( '#ersrv-checkout-date' ).val();
		var adult_count         = parseInt( $( '#adult-accomodation-count' ).val() );
		adult_count             = ( -1 !== is_valid_number( adult_count ) ) ? adult_count : 0;
		var kid_count           = parseInt( $( '#kid-accomodation-count' ).val() );
		kid_count               = ( -1 !== is_valid_number( kid_count ) ) ? kid_count : 0;
		var guests              = adult_count + kid_count;
		var process_reservation = true;

		// Vacate the error message.
		$( '.ersrv-reservation-error' ).text( '' );

		// If the customer is not selected.
		if ( -1 === is_valid_number( customer_id ) ) {
			process_reservation = false;
			$( '.ersrv-reservation-error.customer-error' ).text( 'Please select a customer for this reservation.' );
		}

		// Guests count.
		if ( -1 === is_valid_number( adult_count ) && -1 === is_valid_number( kid_count ) ) {
			process_reservation = false;
			$( '.ersrv-reservation-error.accomodation-error' ).text( 'Please provide the count of guests for the reservation.' );
		} else if ( -1 === is_valid_number( adult_count ) && -1 !== is_valid_number( kid_count ) ) {
			process_reservation = false;
			$( '.ersrv-reservation-error.accomodation-error' ).text( 'We cannot proceed with only the kids in the reservation.' );
		} else if ( accomodation_limit < guests ) {
			process_reservation = false;
			$( '.ersrv-reservation-error.accomodation-error' ).text( 'The guests count is more than the accomodation limit.' );
		}

		// If the checkin and checkout dates are not available.
		if ( '' === checkin_date && '' === checkout_date ) {
			process_reservation = false;
			$( '.ersrv-reservation-error.checkin-checkout-dates-error' ).text( 'Please provide checkin and checkout dates.' );
		} else if ( '' === checkin_date ) {
			process_reservation = false;
			$( '.ersrv-reservation-error.checkin-checkout-dates-error' ).text( 'Please provide checkin date.' );
		} else if ( '' === checkout_date ) {
			process_reservation = false;
			$( '.ersrv-reservation-error.checkin-checkout-dates-error' ).text( 'Please provide checkout date.' );
		}

		// Exit, if we cannot process the reservation.
		if ( false === process_reservation ) {
			return false;
		}

		// Block element.
		block_element( this_button );

		// Send AJAX creating a reservation.
		var data = {
			action: 'create_reservation',
			item_id: item_id,
			customer_id: customer_id,
			checkin_date: checkin_date,
			checkout_date: checkout_date,
			adult_count: adult_count,
			kid_count: kid_count,
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
				if ( 'reservation-created' === response.data.code ) {
					// Unblock the element.
					unblock_element( this_button );

					console.log( 'response', response );
				}
			},
		} );
	} );

	/**
	 * Fetch states based on country code.
	 */
	$( document ).on( 'change', '#ersrv-customer-country', function() {
		var this_select  = $( this );
		var country_code = this_select.val();

		// Exit, if the country code is invalid.
		if ( -1 === is_valid_string( country_code ) ) {
			return false;
		}

		// Block the element now.
		block_element( this_select );

		// Send the AJAX now.
		$.ajax( {
			dataType: 'JSON',
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'get_states',
				country_code: country_code,
			},
			success: function ( response ) {
				// Return, if the response is not proper.
				if ( 0 === response ) {
					console.log( 'easy-reservations: invalid ajax call' );
					return false;
				}

				// If the reservation is added.
				if ( 'states-fetched' === response.data.code ) {
					// Unblock the element.
					unblock_element( this_select );

					var states = response.data.states;
					if ( 0 !== states.length ) {
						// Prepare the html for states.
						var html = '<option value="">Select state</option>';
						for ( var i in states ) {
							html += '<option value="' + i + '">' + states[i] + '</option>';
						}

						// Paste the html.
						$( '#ersrv-customer-state' ).html( html );

						// Show the states select box.
						$( '.ersrv-customer-field.state' ).show();
					} else {
						// Hide the states select box.
						$( '.ersrv-customer-field.state' ).hide();
					}
				}
			},
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
	 * Check if a string is valid.
	 *
	 * @param {string} $data
	 */
	 function is_valid_string( data ) {
		if ( '' === data || undefined === data || ! isNaN( data ) || 0 === data ) {
			return -1;
		} else {
			return 1;
		}
	}

	/**
	 * Check if a number is valid.
	 *
	 * @param {number} $data
	 */
	function is_valid_number( data ) {
		if ( '' === data || undefined === data || isNaN( data ) || 0 === data ) {
			return -1;
		} else {
			return 1;
		}
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
} );
