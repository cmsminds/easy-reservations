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
		var password         = $( '#ersrv-customer-password' ).val();
		var register_user    = true;

		// Vacate the errors.
		$( '.ersrv-form-field-error, .ersrv-form-error' ).text( '' );

		// Validate email.
		if ( -1 === is_valid_string( email ) ) {
			$( '.ersrv-form-field-error.email-error' ).text( email_address_required );
			register_user = false;
		} else if ( -1 === is_valid_email( email ) ) {
			$( '.ersrv-form-field-error.email-error' ).text( email_address_invalid );
			register_user = false;
		}

		// Validate password.
		if ( -1 === is_valid_string( password ) ) {
			$( '.ersrv-form-field-error.password-error' ).text( password_required );
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
			password: password,
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

		// Change the accomodation limit text.
		$( 'label[for="accomodation"]' ).next( 'small' ).text( accomodation_limit_text );

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

					// Item details.
					var item_details = response.data.details;

					var accomodation_limit = ( -1 !== is_valid_number( item_details.accomodation_limit ) ) ? parseInt( item_details.accomodation_limit ) : '';
					$( '#accomodation-limit' ).val( accomodation_limit );
					$( 'label[for="accomodation"]' ).next( 'small' ).text( accomodation_limit_text.replace( '--', accomodation_limit ) );

					var blocked_dates          = item_details.reserved_dates;
					var datepicker_date_format = 'yyyy-mm-dd';
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
					$( '#checkin-checkout-date' ).datepicker( {
						beforeShowDay: function( date ) {
							var loop_month          = ( ( '0' + ( date.getMonth() + 1 ) ).slice( -2 ) );
							var loop_date_formatted = date.getFullYear() + '-' + loop_month + '-' + date.getDate();
							var date_enabled        = false;
							var date_classes        = '';
							var date_tooltip        = '';

							// If not the past date.
							if ( today_formatted <= loop_date_formatted ) {
								// Add custom class to the active dates of the current month.
								var key = $.map( reserved_dates, function( val, i ) {
									if ( val.date === loop_date_formatted ) {
										return i;
									}
								} );
				
								// If the loop date is a blocked date.
								if ( 0 < key.length ) {
									key = key[0];
									date_tooltip = reserved_dates[key].message;
								} else if ( 0 === key.length ) {
									date_enabled = true;
									date_classes = 'ersrv-date-active';
								}
							}

							// Return the datepicker day object.
							return {
								'enabled': date_enabled,
								'classes': date_classes,
								'tooltip': date_tooltip,
							};
						},
						format: datepicker_date_format,
						startDate: current_date,
						weekStart: start_of_week,
					} );
				}
			}
		} );
	} );

	/**
	 * Add reservation from admin panel.
	 */
	$( document ).on( 'click', '.ersrv-add-new-reservation', function() {
		var item_id            = $( '#item-id' ).val();
		var customer_id        = $( '#customer-id' ).val();
		var accomodation_limit = $( '#accomodation-limit' ).val();
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
