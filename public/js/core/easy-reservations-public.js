jQuery( document ).ready( function( $ ) {
	'use strict';

	// Localized variables.
	var ajaxurl        = ERSRV_Public_Script_Vars.ajaxurl;
	var remove_sidebar = ERSRV_Public_Script_Vars.remove_sidebar;

	// Custom variables defined.
	var reservation_item_id  = $( '.ersrv-reservation-container' ).data( 'item' );
	var reservation_calendar = document.getElementById( 'calendar' );

	// If sidebar is to be removed on reservation single page.
	if ( 'yes' === remove_sidebar ) {
		$( '.secondary' ).remove();
		$( '.primary' ).css( 'width', '100%' );
	}

	// Datepicker dates.
	$( '.datepicker' ).datepicker( {
		format: 'yyyy-mm-dd',
	} );

	/**
	 * Add the reservation to google calendar.
	 */
	$( document ).on( 'click', '.add-to-gcal', function() {
		var this_button = $( this );
		var order_id    = this_button.parent( '.ersrv-reservation-calendars-container' ).data( 'oid' );

		// Return false, if the order id is invalid.
		if ( -1 === is_valid_number( order_id ) ) {
			return false;
		}

		// Send the AJAX now.
		block_element( $( '.ersrv-reservation-calendars-container' ) );

		// Send the AJAX now.
		$.ajax( {
			dataType: 'JSON',
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'add_reservation_to_gcal',
				order_id: order_id,
			},
			success: function ( response ) {
				// Check for invalid ajax request.
				if ( 0 === response ) {
					console.log( 'easy reservations: invalid ajax request' );
					return false;
				}

				// Check for invalid order ID.
				if ( -1 === response ) {
					console.log( 'easy reservations: invalid order ID' );
					return false;
				}

				if ( 'reservation_added-to-gcal' !== response.data.code ) {
					return false;
				}

				// Unblock the element.
				unblock_element( $( '.ersrv-reservation-calendars-container' ) );
			},
		} );
	} );

	/**
	 * Add the reservation to google calendar.
	 */
	$( document ).on( 'click', '.add-to-ical', function() {
		var this_button = $( this );
		var order_id    = this_button.parent( '.ersrv-reservation-calendars-container' ).data( 'oid' );

		// Return false, if the order id is invalid.
		if ( -1 === is_valid_number( order_id ) ) {
			return false;
		}

		// Send the AJAX now.
		block_element( $( '.ersrv-reservation-calendars-container' ) );

		// Send the AJAX now.
		$.ajax( {
			dataType: 'JSON',
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'add_reservation_to_ical',
				order_id: order_id,
			},
			success: function ( response ) {
				// Check for invalid ajax request.
				if ( 0 === response ) {
					console.log( 'easy reservations: invalid ajax request' );
					return false;
				}

				// Check for invalid order ID.
				if ( -1 === response ) {
					console.log( 'easy reservations: invalid order ID' );
					return false;
				}

				if ( 'reservation_added-to-ical' !== response.data.code ) {
					return false;
				}

				// Unblock the element.
				unblock_element( $( '.ersrv-reservation-calendars-container' ) );
			},
		} );
	} );

	/**
	 * Proceed with reservation details and add the details to the cart.
	 */
	$( document ).on( 'click', '.ersrv-proceed-with-reservation-details', function() {
		// Gather the amenities, if selected.
		if ( $( '.card.amenities' ).length ) {
			$( '.card.amenities .amenity' ).each( function() {
				
			} );
		}
	} );

	/**
	 * Enable/disable amenity.
	 */
	$( document ).on( 'click', '.card.amenities .amenity button', function() {
		$( this ).toggleClass( 'amenity-selected' );
	} );

	var calendar = new FullCalendar.Calendar( reservation_calendar, {
		themeSystem: 'bootstrap',
		initialView: 'dayGridMonth',
		selectable: true,
		nowIndicator: true,
		headerToolbar: {
			left: 'prev,next',
			center: 'title',
			right: 'viewChangeDropdown',
		},
		buttonText: {
			prev: 'prev',
			next: 'next'
		},
		buttonIcons: {
			prev: 'fa-chevron-left',
			next: 'fa-chevron-right',
		},
		select: function (info) {
			// alert('selected ' + info.startStr + ' to ' + info.endStr);
		},
		eventSources: [
			{
				events: [
					{
						title: '$750',
						start: '2021-06-01',
						allDay: true,
						className: 'light-green-label',
					},
					{
						title: '1 Available',
						start: '2021-06-01',
						allDay: true,
						className: 'green-label',
					},
					{
						title: '$750',
						start: '2021-06-12',
						allDay: true,
						className: 'light-blue-label',
					},
					{
						title: '1 Available',
						start: '2021-06-12',
						allDay: true,
						className: 'blue-label',
					},
					{
						title: '$750',
						start: '2021-06-11',
						allDay: true,
						className: 'light-green-label',
					},
					{
						title: '1 Available',
						start: '2021-06-11',
						allDay: true,
						className: 'green-label',
					},
					{
						title: '$750',
						start: '2021-06-21',
						allDay: true,
						className: 'light-blue-label',
					},
					{
						title: '1 Available',
						start: '2021-06-21',
						allDay: true,
						className: 'blue-label',
					},
				]
			}

		],
		eventClick: function (info) {
			alert('Event: ' + info.event.title);
		},
		editable: true,
		dayMaxEvents: true,
	});
	calendar.render();

	// custome view change dropdown
	var viewChangeDropdown = '<div class="custom-selectbox">' +
		'<select name="viewchange" id="viewChange" class="selectpicker">' +
		'<option value="dayGridMonth">Month</option>' +
		'<option value="timeGridWeek">Week</option>' +
		'<option value="timeGridDay">Day</option>' +
		'</select>' +
		'</div>';
	$('.fc-header-toolbar > .fc-toolbar-chunk:nth-child(3)').append(viewChangeDropdown);

	$( document ).on( 'change', '#viewChange', function() {
		var selected_calendar_type = $( this ).val();
		calendar.changeView( selected_calendar_type );
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
		if ( '' === data || undefined === data || ! isNaN( data ) || 0 === data ) {
			return -1;
		}

		return 1;
	}
} );
