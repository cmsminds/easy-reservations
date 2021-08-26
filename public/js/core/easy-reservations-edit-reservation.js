jQuery(document).ready(function ($) {
	'use strict';

	// Localized variables.
	var ajaxurl               = ERSRV_Edit_Reservation_Script_Vars.ajaxurl;
	var woo_currency          = ERSRV_Edit_Reservation_Script_Vars.woo_currency;
	var date_format           = ERSRV_Edit_Reservation_Script_Vars.date_format;
	var toast_success_heading = ERSRV_Edit_Reservation_Script_Vars.toast_success_heading;
	var toast_error_heading   = ERSRV_Edit_Reservation_Script_Vars.toast_error_heading;
	var toast_notice_heading  = ERSRV_Edit_Reservation_Script_Vars.toast_notice_heading;

	/**
	 * Click on the checkin and checkout date to fetch the dates available while editing the reservation.
	 */
	$( document ).on( 'click', '.ersrv-edit-reservation-item-checkin-date, .ersrv-edit-reservation-item-checkout-date', function() {
		var this_input = $( this );
		var item_id    = this_input.parents( '.ersrv-edit-reservation-item-card' ).data( 'itemid' );
		var product_id = this_input.parents( '.ersrv-edit-reservation-item-card' ).data( 'productid' );

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

					// Initiate the datepicker now.
					$( '.ersrv-edit-reservation-item-checkin-date, .ersrv-edit-reservation-item-checkout-date' ).datepicker( {
						dateFormat: date_format,
						minDate: 0,
					} );

					// Set the hidden value to be 1.
					$( '#datepicker-initiated-' + item_id ).val( '1' );
				}
			},
		} );
	} );

	/**
	 * Update reservation.
	 */
	$( document ).on( 'click', '.ersrv-update-reservation button', function() {
		var this_button = $( this );
	} );

	/**
	 * Open the cost splitter box.
	 */
	$( document ).on( 'click', '.ersrv-split-reservation-cost', function() {
		var this_button = $( this );
		var item_id = this_button.parents( '.ersrv-edit-reservation-item-card' ).data( 'itemid' );
		$( '#ersrv-edit-reservation-item-summary-' + item_id ).toggleClass( 'show' );
	} );

	/**
	 * Check if any value is changed, so the user should confirm availability.
	 */
	$( document ).on( 'keyup click change', '.ersrv-edit-reservation-item-value', function() {
		var this_input  = $( this );
		var input_boxes = this_input.parents( '.details' ).find( '.ersrv-edit-reservation-item-value' );

		// Initiatives to keep the validations intact.
		this_input.parents( '.details' ).find( '.confirmed-validation-of-item' ).val( 1 );
		var confirm_validation_button = this_input.parents( '.details' ).find( '.ersrv-edit-reservation-validate-item-changes' );
		block_element( confirm_validation_button );
		block_element( $( '.ersrv-update-reservation button' ) );

		// Loop through the input boxes to check if either value is changed.
		input_boxes.each( function() {
			var input_box = $( this );
			var new_val = input_box.val().toString();
			var old_val = input_box.data( 'oldval' ).toString();

			if ( new_val !== old_val ) {
				this_input.parents( '.details' ).find( '.confirmed-validation-of-item' ).val( -1 );
				unblock_element( confirm_validation_button );
			}
		} );

		// Enable the update button in case of any item value is changed.
		$( '.ersrv-edit-reservation-validate-item-changes' ).each( function() {
			var is_blocked = is_element_blocked( $( this ) );

			// Exit the loop if the button is not blocked.
			if ( false === is_blocked ) {
				unblock_element( $( '.ersrv-update-reservation button' ) );
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
		var adult_count        = $( '#ersrv-edit-reservation-item-adult-count-' + item_id ).val();
		var kid_count          = $( '#ersrv-edit-reservation-item-kid-count-' + item_id ).val();
		var accomodation_limit = $( '#accomodation-limit-' + item_id ).val();

		// Block the button.
		block_element( this_button );

		// Process the AJAX.
		$.ajax( {
			dataType: 'JSON',
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'edit_reservation_validate_item_changes',
				item_id: item_id,
				product_id: product_id,
				checkin_date: checkin_date,
				checkout_date: checkout_date,
				adult_count: adult_count,
				kid_count: kid_count,
				accomodation_limit: accomodation_limit,
			},
			success: function ( response ) {
				// Return, if the response is not proper.
				if ( 0 === response ) {
					console.warn( 'easy-reservations: invalid ajax call' );
					return false;
				}

				// If the reservation is added.
				if ( 'item-changes-validated' === response.data.code ) {
					// Unblock the button.
					unblock_element( this_button );

					// Is success.
					var is_success = response.data.is_success;

					// Show the error message.
					if ( 'no' === is_success ) {
						// Show error notification.
						ersrv_show_notification( 'bg-danger', 'fa-skull-crossbones', toast_error_heading, response.data.toast_message );
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
		var total_cost = item_subtotal + kids_subtotal + security_subtotal + amenities_subtotal;
		total_cost     = ersrv_get_formatted_price( total_cost );

		// Paste the final total.
		$( 'tr#edit-reservation-item-total-cost-' + item_id + ' td span.ersrv-cost' ).html( total_cost );
		$( 'span#ersrv-edit-reservation-item-subtotal-' + item_id ).html( total_cost );

		// Calculate the cost difference.
		ersrv_calculate_reservation_cost_difference();
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
} );
