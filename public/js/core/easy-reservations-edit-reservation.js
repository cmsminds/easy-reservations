jQuery(document).ready(function ($) {
	'use strict';

	// Localized variables.
	var ajaxurl = ERSRV_Edit_Reservation_Script_Vars.ajaxurl;

	/**
	 * Update reservation.
	 */
	$( document ).on( 'click', '.ersrv-update-reservation button', function() {
		var this_button = $( this );

		// Check if the availability is confirmed for all the orders edited.
		$( '.confirmed-validation-of-item' ).each( function() {
			var this_input               = $( this );
			var is_availability_confimed = parseInt( this_input.val() );
		} );
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
	$( document ).on( 'change', '.ersrv-edit-reservation-item-value', function() {
		var this_input = $( this );
		var new_val = this_input.val().toString();
		var old_val = this_input.data( 'oldval' ).toString();

		if ( new_val !== old_val ) {
			
		}
	} );

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
} );
