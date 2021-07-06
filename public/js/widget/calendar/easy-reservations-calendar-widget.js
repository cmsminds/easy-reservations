jQuery( document ).ready( function( $ ) {
	'use strict';

	// Localized variables.
	var ajaxurl       = ERSRV_Calendar_Widget_Script_Vars.ajaxurl;
	var start_of_week = parseInt( ERSRV_Calendar_Widget_Script_Vars.start_of_week );

	/**
	 * Display the calendar widget when the reservable item is selected.
	 */
	$( document ).on( 'change', '#ersrv-widget-reservable-items', function() {
		// Item ID.
		var item_id = parseInt( $( this ).val() );

		// Hide the calendar.
		$( '.ersrv-widget-calendar, .ersrv-book-item-from-widget' ).hide();

		// If no item id is selected.
		if ( -1 === item_id ) {
			return false;
		}

		// Send the AJAX to fetch the unavailability dates.
		block_element( $( '.ersrv-reservation-widget-container' ) );

		// Send the AJAX now.
		$.ajax( {
			dataType: 'JSON',
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'get_item_unavailable_dates',
				item_id: item_id,
			},
			success: function ( response ) {
				// Check for invalid ajax request.
				if ( 0 === response ) {
					console.log( 'easy reservations: invalid ajax request' );
					return false;
				}

				if ( 'unavailability-dates-fetched' !== response.data.code ) {
					return false;
				}

				// Unblock the element.
				unblock_element( $( '.ersrv-reservation-widget-container' ) );

				// Set the reserve button link.
				$( '.ersrv-book-item-from-widget' ).show();
				$( '.ersrv-book-item-from-widget a' ).attr( 'href', response.data.item_link );

				// Dates to disable. These are actually unavailability dates.
				var blocked_dates          = response.data.dates;
				var datepicker_date_format = 'yyyy-mm-dd';
				var current_date           = new Date();
				var current_month          = ( ( '0' + ( current_date.getMonth() + 1 ) ).slice( -2 ) );
				var today_formatted        = current_date.getFullYear() + '-' + current_month + '-' + current_date.getDate();

				// Display the calendar.
				$( '.ersrv-widget-calendar' ).show().datepicker( {
					beforeShowDay: function( date ) {
						var loop_month          = ( ( '0' + ( date.getMonth() + 1 ) ).slice( -2 ) );
						var loop_date_formatted = date.getFullYear() + '-' + loop_month + '-' + date.getDate();
						var date_enabled        = false;
						var date_classes        = '';
						var date_tooltip        = '';

						// If not the past date.
						if ( today_formatted <= loop_date_formatted ) {
							// Add custom class to the active dates of the current month.
							var key = $.map( blocked_dates, function( val, i ) {
								if ( val.date === loop_date_formatted ) {
									return i;
								}
							} );
			
							// If the loop date is a blocked date.
							if ( 0 < key.length ) {
								key = key[0];
								date_tooltip = blocked_dates[key].message;
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
			},
		} );
	} );

	// Select the first reservable item on load.
	$( '#ersrv-widget-reservable-items option:eq(1)' ).prop( 'selected', true );
	$( '#ersrv-widget-reservable-items' ).trigger( 'change' );

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
