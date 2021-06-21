jQuery( document ).ready( function( $ ) {
	'use strict';

	// Localized variables.
	var same_as_adult       = ERSRV_Admin_Script_Vars.same_as_adult;
	var export_reservations = ERSRV_Admin_Script_Vars.export_reservations;

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
		$( '#ersrv-export-reservations-modal' ).show();
	} );

	/**
	 * Close modal.
	 */
	$( document ).on( 'click', '.ersrv-close-modal', function() {
		$( '.ersrv-modal' ).hide();
	} );

	/**
	 * Close the modal when clicked outside the window.
	 */
	$( 'body' ).click( function( evt ) {
		if ( 'ersrv-export-reservations-modal' === evt.target.id ) {
			$( '.ersrv-modal' ).hide();
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
		var this_button   = $( this );

		// Block the element.
		block_element( $( '.reservations-amenities' ) );

		// Send the AJAX for clearing the log.
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
		var this_button = $( this );
		this_button.parent( '.reservation_amenity_field' ).remove();
	} );

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
