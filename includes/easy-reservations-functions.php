<?php
/**
 * This file is used for writing all the re-usable custom functions.
 *
 * @since 1.0.0
 * @package Easy_Reservations
 * @subpackage Easy_Reservations/includes
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * Get plugin setting by setting index.
 *
 * @param string $setting Holds the setting index.
 * @return boolean|string|array|int
 * @since 1.0.0
 */
function ersrv_get_plugin_settings( $setting ) {
	switch ( $setting ) {
		case 'ersrv_item_availability_calendar_color':
			$data = get_option( $setting );
			$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? $data : 'green';
			break;

		case 'ersrv_archive_page_add_to_cart_button_text':
			$data = get_option( $setting );
			$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? $data : __( 'Reserve It', 'easy-reservations' );
			break;

		case 'ersrv_product_single_page_add_to_cart_button_text':
			$data = get_option( $setting );
			$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? $data : __( 'Reserve It', 'easy-reservations' );
			break;

		case 'ersrv_remove_reservation_pages_sidebar':
			$data = get_option( $setting );
			$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? $data : 'no';
			break;

		case 'ersrv_reservation_receipt_store_name':
			$data = get_option( $setting );
			$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? $data : '';
			break;

		case 'ersrv_reservation_receipt_store_contact_number':
			$data = get_option( $setting );
			$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? $data : '';
			break;

		case 'ersrv_reservation_receipt_store_logo_media_id':
			$data = get_option( $setting );
			$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? $data : '';
			break;

		case 'ersrv_easy_reservations_receipt_for_order_statuses':
			$data = get_option( $setting );
			$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? $data : array();
			break;

		case 'ersrv_easy_reservations_receipt_button_text':
			$data = get_option( $setting );
			$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? $data : __( 'Download Reservation Receipt', 'easy-reservations' );
			break;

		case 'ersrv_easy_reservations_reservation_thanks_note':
			$data = get_option( $setting );
			$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? $data : '';
			break;

		case 'ersrv_easy_reservations_receipt_footer_text':
			$data = get_option( $setting );
			$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? $data : '';
			break;

		default:
			$data = -1;
	}

	return $data;
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_custom_product_type_slug' ) ) {
	/**
	 * Get the custom product type slug.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	function ersrv_get_custom_product_type_slug() {

		return 'reservation';
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_custom_product_type_label' ) ) {
	/**
	 * Get the custom product type label.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	function ersrv_get_custom_product_type_label() {
		$product_type_label = __( 'Reservation', 'easy-reservations' );

		/**
		 * This hook fires in admin panel on the item settings page.
		 *
		 * This filter will help in modifying the product type label.
		 *
		 * @param string $product_type_label Holds the product type label.
		 * @return string
		 */
		return apply_filters( 'ersrv_product_type_label', $product_type_label );
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_posts' ) ) {
	/**
	 * Get the posts.
	 *
	 * @param string $post_type Post type.
	 * @param int    $paged Paged value.
	 * @param int    $posts_per_page Posts per page.
	 * @return object
	 * @since 1.0.0
	 */
	function ersrv_get_posts( $post_type = 'post', $paged = 1, $posts_per_page = -1 ) {
		// Prepare the arguments array.
		$args = array(
			'post_type'      => $post_type,
			'paged'          => $paged,
			'posts_per_page' => $posts_per_page,
			'post_status'    => 'publish',
			'fields'         => 'ids',
			'orderby'        => 'date',
			'order'          => 'DESC',
		);

		/**
		 * Posts/custom posts listing arguments filter.
		 *
		 * This filter helps to modify the arguments for retreiving posts of default/custom post types.
		 *
		 * @param array $args Holds the post arguments.
		 * @return array
		 */
		$args = apply_filters( 'ersrv_posts_args', $args );

		return new WP_Query( $args );
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_active_stylesheet' ) ) {
	/**
	 * Get the active stysheet URL.
	 *
	 * @param string $current_theme Holds the current theme slug.
	 * @return string
	 * @since 1.0.0
	 */
	function ersrv_get_active_stylesheet( $current_theme ) {
		switch ( $current_theme ) {
			case 'twentysixteen':
				return array(
					'url'  => ERSRV_PLUGIN_URL . 'public/css/core/easy-reservations-twentysixteen.css',
					'path' => ERSRV_PLUGIN_PATH . 'public/css/core/easy-reservations-twentysixteen.css',
				);

			case 'twentyseventeen':
				return array(
					'url'  => ERSRV_PLUGIN_URL . 'public/css/core/easy-reservations-twentyseventeen.css',
					'path' => ERSRV_PLUGIN_PATH . 'public/css/core/easy-reservations-twentyseventeen.css',
				);

			case 'twentynineteen':
				return array(
					'url'  => ERSRV_PLUGIN_URL . 'public/css/core/easy-reservations-twentynineteen.css',
					'path' => ERSRV_PLUGIN_PATH . 'public/css/core/easy-reservations-twentynineteen.css',
				);

			case 'twentytwenty':
				return array(
					'url'  => ERSRV_PLUGIN_URL . 'public/css/core/easy-reservations-twentytwenty.css',
					'path' => ERSRV_PLUGIN_PATH . 'public/css/core/easy-reservations-twentytwenty.css',
				);

			case 'twentytwentyone':
				return array(
					'url'  => ERSRV_PLUGIN_URL . 'public/css/core/easy-reservations-twentytwentyone.css',
					'path' => ERSRV_PLUGIN_PATH . 'public/css/core/easy-reservations-twentytwentyone.css',
				);

			case 'storefront':
				return array(
					'url'  => ERSRV_PLUGIN_URL . 'public/css/core/easy-reservations-storefront.css',
					'path' => ERSRV_PLUGIN_PATH . 'public/css/core/easy-reservations-storefront.css',
				);

			default:
				return array(
					'url'  => ERSRV_PLUGIN_URL . 'public/css/core/easy-reservations-other.css',
					'path' => ERSRV_PLUGIN_PATH . 'public/css/core/easy-reservations-other.css',
				);
		}
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_icalendar_formatted_date' ) ) {
	/**
	 * Get the iCal formatted datetime from timestamp.
	 *
	 * @param string  $timestamp Holds the linux timestamp.
	 * @param boolean $include_time Whether to include time in the formatted datetime.
	 * @return string
	 * @since 1.0.0
	 */
	function ersrv_get_icalendar_formatted_date( $timestamp = '', $include_time = true ) {

		return gmdate( 'Ymd' . ( $include_time ? '\THis' : '' ), $timestamp ) . 'Z';
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_wc_product_type' ) ) {
	/**
	 * Get the product type from ID.
	 *
	 * @param int $product_id Holds the WooCommerce product type.
	 * @return boolean|string
	 * @since 1.0.0
	 */
	function ersrv_get_wc_product_type( $product_id = 0 ) {
		// Return false, if the item ID is 0.
		if ( 0 === $product_id || ! is_int( $product_id ) ) {
			return false;
		}

		$wc_product = wc_get_product( $product_id );

		return ( false === $wc_product ) ? false : $wc_product->get_type();
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_order_is_reservation' ) ) {
	/**
	 * Check if the order is reservation.
	 *
	 * @param WC_Order $wc_order Order data.
	 * @return boolean
	 * @since 1.0.0
	 */
	function ersrv_order_is_reservation( $wc_order ) {
		// Get the order line items.
		$line_items = $wc_order->get_items();

		// Return, if there are no order items.
		if ( empty( $line_items ) || ! is_array( $line_items ) ) {
			return false;
		}

		$custom_product_type = ersrv_get_custom_product_type_slug();

		// Iterate through the items to check if any reservation has been booked.
		foreach ( $line_items as $line_item ) {
			$line_item_product_id = $line_item->get_product_id();

			// If the item ID is available.
			$line_item_type = ersrv_get_wc_product_type( $line_item_product_id );

			// Check if the product is of reservation type.
			if ( false !== $line_item_type && $custom_product_type === $line_item_type ) {
				return true;
			}
		}

		return false;
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_amenity_html' ) ) {
	/**
	 * Get the amenity HTML.
	 *
	 * @param string $amenity_title Holds the amenity title.
	 * @param string $amenity_cost Holds the amenity cost.
	 * @return string
	 * @since 1.0.0
	 */
	function ersrv_get_amenity_html( $amenity_title = '', $amenity_cost = '' ) {
		ob_start();
		?>
		<p class="form-field reservation_amenity_field amenities-row">
			<input type="text" value="<?php echo esc_html( $amenity_title ); ?>" required name="amenity_title[]" class="short addTitle-field" placeholder="<?php esc_html_e( 'Amenity Title', 'easy-reservations' ); ?>">
			<input type="number" value="<?php echo esc_html( $amenity_cost ); ?>" required name="amenity_cost[]" class="short addNumber-field" placeholder="0.0" step="0.01" min="0.01">
			<button type="button" class="button button-secondary btn-submit ersrv-remove-amenity-html"></button>
		</p>
		<?php
		return ob_get_clean();
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_calendar_widget_base_id' ) ) {
	/**
	 * Get the base ID of calendar widget.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	function ersrv_get_calendar_widget_base_id() {

		return 'easy-reservations-calendar-widget';
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_widget_settings' ) ) {
	/**
	 * Get the widget settings based on widget base ID.
	 *
	 * @param string $widget_base_id Holds the eidget base ID.
	 * @return array
	 * @since 1.0.0
	 */
	function ersrv_get_widget_settings( $widget_base_id ) {
		global $wp_registered_widgets;
		$widget_settings = array();

		// Check if there are registered widgets.
		if ( empty( $wp_registered_widgets ) || ! is_array( $wp_registered_widgets ) ) {
			return $widget_settings;
		}

		// Iterate through the registered widgets to get the settings of the requested widget ID.
		foreach ( $wp_registered_widgets as $base_id => $wp_widget ) {
			if ( false === stripos( $base_id, $widget_base_id ) ) {
				continue;
			}

			// Get the instance ID.
			$instance_id = ( ! empty( $wp_widget['callback'][0]->number ) ) ? (int) $wp_widget['callback'][0]->number : false;

			// Get the widget settings now.
			$settings        = get_option( "widget_{$widget_base_id}" );
			$widget_settings = ( ! empty( $settings[ $instance_id ] ) ) ? $settings[ $instance_id ] : array();

			break; // Terminate the loop, because the requested settings have been achieved.
		}

		return $widget_settings;
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_reservation_item_blockout_dates' ) ) {
	/**
	 * Get the blockedout dates for the reservable item.
	 *
	 * @param int $item_id Holds the reservation item ID.
	 * @return boolean|array
	 * @since 1.0.0
	 */
	function ersrv_get_reservation_item_blockout_dates( $item_id ) {
		// Return, if the item ID is not integer.
		if ( ! is_int( $item_id ) ) {
			return false;
		}

		// Get the item type.
		$item_type           = ersrv_get_wc_product_type( $item_id );
		$custom_product_type = ersrv_get_custom_product_type_slug();

		// If it's not reservation, return false.
		if ( false === $item_type || $custom_product_type !== $item_type ) {
			return false;
		}

		$blockout_dates = get_post_meta( $item_id, '_ersrv_reservation_blockout_dates', true );

		return ( empty( $blockout_dates ) ) ? array() : $blockout_dates;
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_blockout_date_html' ) ) {
	/**
	 * Get the amenity HTML.
	 *
	 * @param string $amenity_title Holds the amenity title.
	 * @param string $amenity_cost Holds the amenity cost.
	 * @return string
	 * @since 1.0.0
	 */
	function ersrv_get_blockout_date_html( $date = '', $message = '' ) {
		ob_start();
		?>
		<p class="form-field reservation_blockout_date_field blockout-dates-row">
			<input type="text" value="<?php echo esc_html( $date ); ?>" required name="blockout_date[]" class="short addTitle-field" placeholder="YYYY-MM-DD">
			<input type="text" value="<?php echo esc_html( $message ); ?>" required name="blockout_date_message[]" class="short addTitle-field">
			<button type="button" class="button button-secondary btn-submit ersrv-remove-blockout-date-html"></button>
		</p>
		<?php
		return ob_get_clean();
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_dates_within_2_dates' ) ) {
	/**
	 * Get dates that fall between 2 dates.
	 *
	 * @param string $from Start date.
	 * @param string $to End date.
	 * @return boolean|DatePeriod
	 * @since 1.0.0
	 */
	function ersrv_get_dates_within_2_dates( $from, $to ) {
		// Return if either of the date is not provided.
		if ( empty( $from ) || empty( $to ) ) {
			return false;
		}

		// Get the dates array.
		$from     = new DateTime( $from );
		$to       = new DateTime( $to );
		$to       = $to->modify( '+1 day' );
		$interval = new DateInterval( 'P1D' );

		return new DatePeriod( $from, $interval, $to );
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_product_is_reservation' ) ) {
	/**
	 * Check if the page is reservation single page.
	 *
	 * @param int $product_id Holds the product ID.
	 * @return boolean
	 * @since 1.0.0
	 */
	function ersrv_product_is_reservation( $product_id ) {
		// Get woocommerce product.
		$wc_product = wc_get_product( $product_id );

		// Return false, if it's not the valid WC product.
		if ( false === $wc_product ) {
			return false;
		}

		// Return false, if the product type is not reservation.
		if ( ersrv_get_custom_product_type_slug() !== $wc_product->get_type() ) {
			return false;
		}

		return true;
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_admin_script_vars' ) ) {
	/**
	 * Return the array of script variables.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	function ersrv_get_admin_script_vars() {
		$page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );
		$post = (int) filter_input( INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT );
		$vars = array(
			'ajaxurl'             => admin_url( 'admin-ajax.php' ),
			'same_as_adult'       => __( 'Same as Adult!', 'easy-reservations' ),
			'export_reservations' => __( 'Export Reservations', 'easy-reservations' ),
		);

		// Add some script variables on product edit page.
		if ( ! is_null( $post ) && 'product' === get_post_type( $post ) ) {
			$vars['ersrv_product_type'] = ersrv_get_custom_product_type_slug();
		}

		// Add the error message to the array on new reservation page.
		if ( ! is_null( $page ) && 'new-reservation' === $page ) {
			$vars['accomodation_limit_text']                      = __( 'Limit: --', 'easy-reservations' );
			$vars['start_of_week']                                = get_option( 'start_of_week' );
			$vars['woo_currency']                                 = get_woocommerce_currency_symbol();
			$vars['reservation_customer_err_msg']                 = __( 'Please select a customer for this reservation.', 'easy-reservations' );
			$vars['reservation_guests_err_msg']                   = __( 'Please provide the count of guests for the reservation.', 'easy-reservations' );
			$vars['reservation_only_kids_guests_err_msg']         = __( 'We cannot proceed with only the kids in the reservation.', 'easy-reservations' );
			$vars['reservation_guests_count_exceeded_err_msg']    = __( 'The guests count is more than the accomodation limit.', 'easy-reservations' );
			$vars['reservation_checkin_checkout_missing_err_msg'] = __( 'Please provide checkin and checkout dates.', 'easy-reservations' );
			$vars['reservation_checkin_missing_err_msg']          = __( 'Please provide checkin dates.', 'easy-reservations' );
			$vars['reservation_checkout_missing_err_msg']         = __( 'Please provide checkout dates.', 'easy-reservations' );
			$vars['reservation_lesser_reservation_days_err_msg']  = __( 'The item can be reserved for a min. of XX days.', 'easy-reservations' );
			$vars['reservation_greater_reservation_days_err_msg'] = __( 'The item can be reserved for a max. of XX days.', 'easy-reservations' );
			$vars['reservation_customer_first_name_err_msg']      = __( 'First name is required.', 'easy-reservations' );
			$vars['reservation_customer_last_name_err_msg']       = __( 'Last name is required.', 'easy-reservations' );
			$vars['reservation_customer_email_err_msg']           = __( 'Email address is required.', 'easy-reservations' );
			$vars['reservation_customer_email_invalid_err_msg']   = __( 'Email address is invalid.', 'easy-reservations' );
			$vars['reservation_customer_password_err_msg']        = __( 'Password is required.', 'easy-reservations' );
			$vars['reservation_customer_phone_err_msg']           = __( 'Phone number is required.', 'easy-reservations' );
			$vars['reservation_customer_address_err_msg']         = __( 'Address line is required.', 'easy-reservations' );
			$vars['reservation_customer_country_err_msg']         = __( 'Country is required.', 'easy-reservations' );
			$vars['reservation_customer_city_err_msg']            = __( 'City is required.', 'easy-reservations' );
			$vars['reservation_customer_postcode_err_msg']        = __( 'Postcode is required.', 'easy-reservations' );
		}

		/**
		 * This hook fires in admin panel.
		 *
		 * This filter helps in modifying the script variables in admin.
		 *
		 * @param array $vars Script variables.
		 * @return array
		 * @since 1.0.0
		 */
		$vars = apply_filters( 'ersrv_admin_script_vars', $vars );

		return $vars;
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_create_new_user' ) ) {
	/**
	 * Create the new wp user.
	 *
	 * @param string $username Holds the username.
	 * @param string $email Holds the email.
	 * @param string $password Holds the password.
	 * @param string $first_name Holds the first name.
	 * @param string $last_name Holds the last name.
	 * @return int
	 * @since 1.0.0
	 */
	function ersrv_create_new_user( $username, $email, $password, $first_name, $last_name ) {
		$user_id  = wp_create_user( $username, $password, $email ); // Create the user.

		// Update the first name.
		if ( ! empty( $first_name ) ) {
			update_user_meta( $user_id, 'first_name', $first_name );
		}

		// Update the last name.
		if ( ! empty( $last_name ) ) {
			update_user_meta( $last_name, 'last_name', $last_name );
		}

		return $user_id;
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_should_display_receipt_button' ) ) {
	/**
	 * Check if the receipt button text is generatable for the receiving order ID.
	 *
	 * @param int $order_id Holds the order ID.
	 * @return boolean
	 * @since 1.0.0
	 */
	function ersrv_should_display_receipt_button( $order_id ) {
		// Check if order ID is valid.
		if ( empty( $order_id ) || ! is_int( $order_id ) ) {
			return false;
		}

		// Get order.
		$wc_order = wc_get_order( $order_id );

		// Return if the order is not available.
		if ( false === $wc_order ) {
			return false;
		}

		$order_statuses        = ersrv_get_plugin_settings( 'ersrv_easy_reservations_receipt_for_order_statuses' );
		$order_status          = 'wc-' . $wc_order->get_status();
		$display_order_receipt = ( in_array( $order_status, $order_statuses, true ) ) ? true : false;

		/**
		 * Display receipt button filter.
		 *
		 * This filter help modifying the condition under which the receipt button should be displayed or not.
		 *
		 * @param boolean $display_order_receipt Holds the boolean value to display the button.
		 * @param int     $order_id Holds the order ID.
		 * @return boolean
		 * @since 1.0.0
		 */
		$display_order_receipt = apply_filters( 'ersrv_display_receipt_button', $display_order_receipt, $order_id );

		return $display_order_receipt;
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_is_dokan_active' ) ) {
	/**
	 * Check if dokan plugin is active.
	 *
	 * @return boolean
	 * @since 1.0.0
	 */
	function ersrv_is_dokan_active() {

		return ( false === in_array( 'dokan-lite/dokan.php', get_option( 'active_plugins' ), true ) ) ? false : true;
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_download_reservation_receipt_url' ) ) {
	/**
	 * Download reservation receipt URL.
	 *
	 * @param int $order_id WooCommerce order ID.
	 * @return boolean
	 * @since 1.0.0
	 */
	function ersrv_download_reservation_receipt_url( $order_id ) {

		return home_url( "/?action=ersrv-download-reservation-receipt&atts={$order_id}" );
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_download_reservation_button_title' ) ) {
	/**
	 * Download reservation button title.
	 *
	 * @param int $order_id WooCommerce order ID.
	 * @return boolean
	 * @since 1.0.0
	 */
	function ersrv_download_reservation_receipt_button_title( $order_id ) {
		/* translators: 1: %d: order ID. */
		$button_title = sprintf( __( 'Download reservation receipt for order #%1$d', 'easy-reservations' ), $order_id );

		/**
		 * This filter fires on the download receipt button.
		 *
		 * This filter helps in modifying the download receipt button title tag.
		 *
		 * @param string $button_title Download reservation receipt button title.
		 * @param int    $order_id WooCommerce order ID.
		 * @return string
		 * @since 1.0.0
		 */
		$button_title = apply_filters( 'ersrv_download_reservation_receipt_button_title_attr', $button_title, $order_id );

		return $button_title;
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_email_reservation_receipt_order_status_change' ) ) {
	/**
	 * Email the reservation receipt on order status change.
	 *
	 * @param int $order_id WooCommerce order ID.
	 * @since 1.0.0
	 */
	function ersrv_email_reservation_receipt_order_status_change( $order_id ) {
		// Check if the order has reservation items.
		$wc_order              = wc_get_order( $order_id );
		$is_reservation_order  = ersrv_order_is_reservation( $wc_order );

		// Return, if the receipt should not be emailed.
		if ( ! $is_reservation_order ) {
			return;
		}

		// Check if the order status is allowed for receipts.
		$email_reservation_receipt = ersrv_should_display_receipt_button( $order_id );

		// Return, if the receipt should not be emailed.
		if ( false === $email_reservation_receipt ) {
			return;
		}

		// Email the order receipt.
		ersrv_email_reservation_receipt( $order_id );
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_email_reservation_receipt_order_status_change' ) ) {
	/**
	 * Function to send reservation receipt as email attachment.
	 *
	 * @param int $order_id Holds the order ID.
	 */
	function ersrv_email_reservation_receipt( $order_id ) {
		ersrv_download_reservation_receipt_callback( $order_id, 'email' );
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_download_reservation_receipt_callback' ) ) {
	/**
	 * Function hooked to generate the Receipt PDF from order id.
	 *
	 * @param int    $order_id Holds the order ID.
	 * @param string $action Holds the receipt action.
	 */
	function ersrv_download_reservation_receipt_callback( $order_id, $action = '' ) {
		return;
		// Include the main TCF classes.
		include_once ERSRV_PLUGIN_PATH . 'includes/lib/tcpdf/tcpdf.php';
		include_once ERSRV_PLUGIN_PATH . 'public/class-wpir-receipts-tcpdf.php';

		// PDF title.
		/* translators: 1: %s: site title, 2: %d: order ID */
		$pdf_title = sprintf( __( '%1$s - Order Receipt #%2$d', 'wc-print-invoice-receipts' ), get_bloginfo( 'title' ), $order_id );

		// Start PDF generation.
		$pdf = new WPIR_Receipts_TCPDF( PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false );
		$pdf->SetCreator( PDF_CREATOR );
		$pdf->SetAuthor( 'Nicola Asuni' );
		$pdf->SetTitle( $pdf_title );
		$pdf->SetSubject( 'Order Receipt' );
		$pdf->SetKeywords( 'TCPDF, PDF, example, test, guide' );
		$pdf->setHeaderFont( array( PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN ) );
		$pdf->setFooterFont( array( PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA ) );
		$pdf->SetDefaultMonospacedFont( PDF_FONT_MONOSPACED );
		$pdf->SetMargins( 6, 37, 6 );
		$pdf->SetHeaderMargin( 6 );
		$pdf->SetFooterMargin( PDF_MARGIN_FOOTER );
		$pdf->SetAutoPageBreak( true, 23 );
		$pdf->setImageScale( PDF_IMAGE_SCALE_RATIO );
		if ( file_exists( dirname( __FILE__ ) . '/lang/eng.php' ) ) {
			require_once dirname( __FILE__ ) . '/lang/eng.php';
			$pdf->setLanguageArray( $l );
		}
		$pdf->setFontSubsetting( true );
		$pdf->SetFont( 'robotocondensed', '', 12, '', true );
		$pdf->AddPage();

		// Order details.
		$wc_order                = wc_get_order( $order_id );
		$billing_address         = $wc_order->get_formatted_billing_address();
		$shipping_address        = $wc_order->get_formatted_shipping_address();
		$raw_billing_address     = $wc_order->get_address( 'billing' );
		$raw_shipping_address    = $wc_order->get_address( 'shipping' );
		$order_status            = $wc_order->get_status();
		$line_items              = $wc_order->get_items();
		$shipping_data           = wpir_get_order_shipping_data( $wc_order );
		$shipping_method         = ( ! empty( $shipping_data['title'] ) ) ? $shipping_data['title'] : '';
		$shipping_cost           = ( ! empty( $shipping_data['amount'] ) ) ? $shipping_data['amount'] : '';
		$shipping_cost_formatted = ( ! empty( $shipping_data['formatted_amount'] ) ) ? $shipping_data['formatted_amount'] : '';

		// Store info.
		$store_address          = wpir_get_store_formatted_address();
		$date_created           = $wc_order->get_date_created();
		$date_created_formatted = gmdate( 'F j, Y, g:i A', strtotime( $date_created ) );

		// Plugin settings.
		$show_shipping_method  = wpir_get_plugin_setting( 'show-shipping-method' );
		$show_customer_details = wpir_get_plugin_setting( 'show-customer-details' );
		$show_customer_note    = wpir_get_plugin_setting( 'show-customer-note' );
		$store_thanks_note     = wpir_get_plugin_setting( 'store-thanks-note' );

		// Differences in billing and shipping addresses.
		$billing_shipping_addresses_difference = array_diff( $raw_billing_address, $raw_shipping_address );

		// Remove the billing email and phone number from the billing differences.
		if ( ! empty( $billing_shipping_addresses_difference['email'] ) ) {
			unset( $billing_shipping_addresses_difference['email'] );
		}

		if ( ! empty( $billing_shipping_addresses_difference['phone'] ) ) {
			unset( $billing_shipping_addresses_difference['phone'] );
		}

		// Payment details.
		$payment_method       = $wc_order->payment_method;
		$payment_method_title = $wc_order->payment_method_title;

		// Watermark.
		$watermarks = wpir_receipt_watermarks();
		$watermark  = $watermarks[ $order_status ]['text'];

		// Coupons usage.
		$used_coupons = $wc_order->get_used_coupons();
		$coupon_data  = ( ! empty( $used_coupons ) ) ? wpir_get_order_coupon_data( $wc_order ) : array();
		$coupon_str   = ( ! empty( $coupon_data ) ) ? wpir_get_coupon_string( $coupon_data ) : '';

		// Shipment tracking details.
		$date_shipped_formatted    = __( 'Yet to ship!', 'wc-print-invoice-receipts' );
		$tracking_number           = '--';
		$tracking_id               = '--';
		$shipment_tracking_details = get_post_meta( $order_id, '_wc_shipment_tracking_items', true );

		if ( ! empty( $shipment_tracking_details[0] ) ) {
			$shipment_tracking_details = $shipment_tracking_details[0];
			$tracking_number           = $shipment_tracking_details['tracking_number'];
			$tracking_id               = $shipment_tracking_details['tracking_id'];
			$date_shipped              = $shipment_tracking_details['date_shipped'];
			$date_shipped_formatted    = gmdate( 'F j, Y', $date_shipped );
		}

		$order_totals = 0.00;
		ob_start();
		?>
		<table cellspacing="0" cellpadding="0" width="100%" border="0">
			<tr width="100%">
				<td colspan="2">
					<table cellspacing="0" cellpadding="0" width="100%" border="0">
						<tr width="100%">
							<?php
							if ( empty( $billing_shipping_addresses_difference ) ) {
								?>
									<td style="width:35%">
										<table cellspacing="0" cellpadding="0" width="100%" border="0">
											<tr width="100%"><td style="line-height:28px;font-size:14px;"><b><?php esc_html_e( 'BILL & SHIP ADDRESS:', 'wc-print-invoice-receipts' ); ?></b></td></tr>
											<tr width="100%"><td style="line-height:14px;font-size:12px;"><?php echo wp_kses_post( $billing_address ); ?></td></tr>
											<tr><td style="height:3px"></td></tr>
										</table>
									</td>
								<?php
							} else {
								?>
								<td style="<?php echo ( true === $show_shipping_method ) ? 'width:17.5%' : 'width:35%'; ?>">
									<table cellspacing="0" cellpadding="0" width="100%" border="0">
										<tr width="100%">
											<td style="line-height:28px;font-size:14px;">
												<b><?php esc_html_e( 'BILL ADDRESS:', 'wc-print-invoice-receipts' ); ?></b>
											</td>
										</tr>
										<tr width="100%">
											<td style="line-height:14px;font-size:12px;"><?php echo wp_kses_post( $billing_address ); ?></td>
										</tr>
									</table>
								</td>
								<?php if ( true === $show_shipping_method ) { ?>
									<td style="width:17.5%">
										<table cellspacing="0" cellpadding="0" width="100%" border="0">
											<tr width="100%">
												<td style="line-height:28px;font-size:14px;">
													<b><?php esc_html_e( 'SHIP ADDRESS:', 'wc-print-invoice-receipts' ); ?></b>
												</td>
											</tr>
											<tr width="100%">
												<?php if ( $show_shipping_method ) { ?>
													<td style="line-height:14px;font-size:12px;">
														<?php echo wp_kses_post( $shipping_address ); ?>
													</td>
												<?php } ?>
											</tr>
										</table>
									</td>
								<?php } ?>
							<?php } ?>
							<td style="width:35%">
								<table cellspacing="0" cellpadding="0" width="100%" border="0">
									<tr width="100%">
										<td style="line-height:28px;font-size:14px;vertical-align:middle;" colspan="2">
											<b><?php esc_html_e( 'ORDER:', 'wc-print-invoice-receipts' ); ?></b>
										</td>
									</tr>
									<tr width="100%">
										<td style="line-height:14px;font-size:12px;"><?php echo esc_html( "#{$order_id}" ); ?></td>
									</tr>
									<tr><td style="height:3px"></td></tr>
									<tr width="100%">
										<td style="line-height:28px;font-size:14px;vertical-align:middle;" colspan="2">
											<b><?php esc_html_e( 'DATE:', 'wc-print-invoice-receipts' ); ?></b>
										</td>
									</tr>
									<tr width="100%">
										<td style="line-height:14px;font-size:12px;"><?php echo esc_html( $date_created_formatted ); ?></td>
									</tr>
									<tr><td style="height:3px"></td></tr>
								</table>
								<?php if ( ! empty( $store_thanks_note ) ) { ?>
									<table cellspacing="0" cellpadding="0" width="95%" border="0">
										<tr width="100%">
											<td style="line-height:16px;font-size:12px;" colspan="2"><?php echo esc_html( $store_thanks_note ); ?></td>
										</tr>
										<tr><td style="height:20px"></td></tr>
									</table>
								<?php } ?>
							</td>
							<td style="width:30%;">
								<table cellspacing="0" cellpadding="0" width="100%" border="0">
									<?php
									if ( 'bacs' === $payment_method ) {
										$bacs = get_option( 'woocommerce_bacs_settings' );
										if ( ! empty( $bacs['enabled'] ) && 'yes' === $bacs['enabled'] ) {
											$accounts = get_option( 'woocommerce_bacs_accounts' );
											if ( ! empty( $accounts[0] ) ) {
												$account = $accounts[0];
												?>
												<tr>
													<td style="line-height:16px;font-size:12px;">
														<b style="text-transform:uppercase;"><?php esc_html_e( 'Store address', 'wc-print-invoice-receipts' ); ?></b>
														<br/><?php echo wp_kses_post( $store_address ); ?>
													</td>
												</tr>
												<tr><td style="height:5px"></td>
												</tr>

												<tr>
													<td style="line-height:16px;font-size:12px;">
														<b style="text-transform:uppercase;line-height:28px;font-size:14px;">
															<?php echo esc_html( $payment_method_title ); ?></b>
														<br/>
														<span>
															<?php
															/* translators 1: %s: br tag, 2: %s: bank name, 3: %s: account name, 4: %s: sort code, 5: %s: account number, 6: %s: bic */
															echo wp_kses_post( sprintf( __( 'Bank Name: %2$s%1$sAccount Name: %3$s%1$sRouting Name: %4$s%1$sAccount: %5$s%1$sBIC: %6$s%1$s', 'wc-print-invoice-receipts' ), '<br />', esc_html( $account['bank_name'] ), esc_html( $account['account_name'] ), esc_html( $account['sort_code'] ), esc_html( $account['account_number'] ), esc_html( $account['bic'] ) ) );
															?>
														</span>
													</td>
												</tr>
												<?php
											}
										}
									} elseif ( 'cheque' === $payment_method ) {
										?>
										<tr>
											<td style="line-height:16px;font-size:12px;">
												<b style="text-transform:uppercase;"><?php esc_html_e( 'Pay by cheque:', 'wc-print-invoice-receipts' ); ?></b>
												<br/>
												<?php
												/* translators: 1: %s: br tag, 2: %s: store address */
												echo wp_kses_post( sprintf( __( 'Mail your cheque to:%1$s%2$s', 'wc-print-invoice-receipts' ), '<br />', $store_address ) );
												?>
											</td>
										</tr>
										<tr><td style="height:5px"></td></tr>
										<?php
									} else {
										?>
										<tr>
											<td style="line-height:16px;font-size:12px;">
												<b style="text-transform:uppercase;"><?php esc_html_e( 'Store address', 'wc-print-invoice-receipts' ); ?></b>
												<br/><?php echo wp_kses_post( $store_address ); ?>
											</td>
										</tr>
										<tr><td style="height:5px"></td></tr>
										<tr><td style="line-height:16px;font-size:12px;"><b style="text-transform:uppercase;"><?php esc_html_e( 'Payment mode:', 'wc-print-invoice-receipt' ); ?></b><br/><?php echo esc_html( $payment_method_title ); ?></td></tr>
										<tr><td style="height:5px"></td></tr>
									<?php } ?>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr width="100%">
				<td colspan="2">
					<table cellspacing="0px" cellpadding="2px" width="100%" border="0">
						<tr style="background-color:#ccc">
							<td style="line-height:24px;font-size:12px;padding:5px" width="10%"></td>
							<td style="line-height:24px;font-size:12px;padding:5px"
								width="25%"><?php esc_html_e( 'ITEM', 'wc-print-invoice-receipts' ); ?></td>
							<td style="line-height:24px;font-size:12px;padding:5px"
								width="35%"><?php esc_html_e( 'DESCRIPTION', 'wc-print-invoice-receipts' ); ?></td>
							<td style="line-height:24px;font-size:12px;padding:5px"
								width="5%"><?php esc_html_e( 'QTY', 'wc-print-invoice-receipts' ); ?></td>
							<td style="line-height:24px;font-size:12px;text-align:right;padding:5px"
								width="10%"><?php esc_html_e( 'COST', 'wc-print-invoice-receipts' ); ?></td>
							<td style="line-height:24px;font-size:12px;text-align:right;padding:5px"
								width="15%"><?php esc_html_e( 'TOTAL', 'wc-print-invoice-receipts' ); ?></td>
						</tr>
						<?php
						if ( ! empty( $line_items ) && is_array( $line_items ) ) {
							foreach ( $line_items as $item ) {
								$quantity          = $item->get_quantity();
								$prod_id           = wpir_product_id( $item->get_product_id(), $item->get_variation_id() );
								$wc_product        = $item->get_product();
								$sku               = $wc_product->get_sku();
								$item_subtotal     = (float) $item->get_subtotal();
								$item_total        = (float) $item->get_total();
								$item_cost         = $item_total / $quantity;
								$order_totals     += $item_total;
								$product_image_id  = get_post_thumbnail_id( $prod_id );
								$product_image_url = wpir_get_image_url( $product_image_id );

								// Prepare the item total cost with the cost difference, in case coupon is applied.
								$item_discount = $item_subtotal - $item_total;

								// Add the discount to the item total.
								if ( 0 < $item_discount ) {
									$item_discount_formatted = ( 0 !== $item_discount ) ? wc_price( $item_discount ) : '';
									/* translators: 1: %s: br tag, 2: %s: discount amount */
									$item_total = wc_price( $item_total ) . sprintf( __( '%1$s%2$s discount', 'wc-print-invoice-receipts' ), '<br />', $item_discount_formatted );
								}

								?>
								<tr valign="middle">
									<td style="padding:5px" width="10%">
										<img src="<?php echo esc_url( $product_image_url ); ?>" height="50px" width="50px"/>
									</td>
									<td style="line-height:16px;font-size:12px;padding:15px" width="25%"><?php echo esc_html( $sku ); ?></td>
									<td style="line-height:16px;font-size:12px;padding:15px" width="35%"><?php echo esc_html( $wc_product->get_title() ); ?></td>
									<td style="line-height:16px;font-size:12px;padding:15px" width="5%"><?php echo esc_html( $quantity ); ?></td>
									<td style="line-height:16px;font-size:12px;text-align:right;padding:15px" width="10%"><?php echo wp_kses_post( wc_price( $item_cost ) ); ?></td>
									<td style="line-height:16px;font-size:12px;text-align:right;padding:15px" width="15%"><?php echo wp_kses_post( $item_total ); ?></td>
								</tr>
								<?php
							}
							$subtotal = wc_price( $order_totals );
							?>
							<tr>
								<td style="line-height:16px;font-size:12px;" width="20%"></td>
								<td style="line-height:16px;font-size:12px;" width="50%"></td>
								<td style="line-height:16px;font-size:12px;" width="5%"></td>
								<td style="line-height:16px;font-size:12px;text-align:right" width="10%"></td>
								<td style="line-height:16px;font-size:12px;text-align:right" width="15%"></td>
							</tr>
							<tr>
								<td style="line-height:16px;font-size:12px;" width="20%"></td>
								<td style="line-height:16px;font-size:12px;" width="50%"></td>
								<td style="line-height:16px;font-size:12px;" width="5%"></td>
								<td style="line-height:16px;font-size:12px;text-align:right" width="10%"><?php esc_html_e( 'SUBTOTAL:', 'wc-print-invoice-receipts' ); ?></td>
								<td style="line-height:16px;font-size:12px;text-align:right" width="15%"><?php echo wp_kses_post( $subtotal ); ?></td>
							</tr>
							<?php
							if ( ! empty( $coupon_str ) ) {
								?>
								<tr>
									<td style="line-height:20px;font-size:12px;" width="20%"></td>
									<td style="line-height:20px;font-size:12px;"
										width="50%"></td>
									<td style="line-height:20px;font-size:12px;" width="5%"></td>
									<td style="line-height:20px;font-size:12px;text-align:right"
										width="10%"><?php esc_html_e( 'COUPON:', 'wc-print-invoice-receipts' ); ?></td>
									<td style="line-height:20px;font-size:12px;text-align:right"
										width="15%"><?php echo wp_kses_post( $coupon_str ); ?></td>
								</tr>
								<?php
							}
							// Updating the order totals for shipping costs.
							$order_totals += $shipping_cost;
							?>
							<tr>
								<?php /* translators: 1: %s: shipping method name */ ?>
								<td colspan="5" style="line-height:16px;font-size:12px;text-align:right;" width="85%"><span style="text-transform:uppercase;"><?php echo esc_html( sprintf( __( 'Shipping: %1$s', 'wc-print-invoice-receipts' ), $shipping_method ) ); ?></span></td>
								<td style="line-height:16px;font-size:12px;text-align:right" width="15%"><?php echo wp_kses_post( $shipping_cost_formatted ); ?></td>
							</tr>

							<!-- TAXES -->
							<?php
							$taxes = $wc_order->get_tax_totals();
							if ( ! empty( $taxes ) ) {
								$tax_label         = '';
								$tax_amt_formatted = '';
								$tax_amount        = 0.00;
								foreach ( $taxes as $tax ) {
									$tax_label         = $tax->label;
									$tax_amt_formatted = $tax->formatted_amount;
									$tax_amount        = (float) $tax->amount;
									break;
								}
								$order_totals += $tax_amount;
								$tax_label     = empty( $tax_label ) ? __( 'TAX', 'wc-print-invoice-receipts' ) : $tax_label;

								?>
								<tr>
									<td style="line-height:16px;font-size:12px;" width="20%"></td>
									<td style="line-height:16px;font-size:12px;" width="50%"></td>
									<td style="line-height:16px;font-size:12px;" width="5%"></td>
									<td style="line-height:16px;font-size:12px;text-align:right" width="10%"><?php echo esc_html( "{$tax_label}:" ); ?></td>
									<td style="line-height:16px;font-size:12px;text-align:right" width="15%"><?php echo wp_kses_post( $tax_amt_formatted ); ?></td>
								</tr>
								<?php
							}
							?>
							<?php
						} else {
							?>
							<tr>
								<td colspan="5" style="line-height:16px;font-size:12px;"
									width="20%"><?php esc_html_e( 'No items found !!', 'wc-print-invoice-receipts' ); ?></td>
							</tr>
							<?php
						}
						?>
					</table>
				</td>
			</tr>
			<tr width="100%">
				<td colspan="2" style="border-bottom:1px dashed #919191;" height="0px">
				</td>
			</tr>
			<tr width="100%">
				<td colspan="2">
					<table cellspacing="0" cellpadding="0" width="100%" border="0">
						<tr width="100%">
							<td style="width:70%"></td>
							<td style="width:10%; line-height:28px;font-size:14px;text-align:right;"><?php esc_html_e( 'TOTAL', 'wc-print-invoice-receipts' ); ?></td>
							<td style="width:20%; line-height:28px;font-size:14px;text-align:right;font-weight:bold"><?php echo wp_kses_post( wc_price( $order_totals ) ); ?></td>
						</tr>
					</table>
				</td>
			</tr>

			<!-- REFUNDS -->
			<?php
			$order_refunds = $wc_order->get_refunds();
			if ( 'refunded' === $order_status || ! empty( $order_refunds ) ) {
				?>
				<tr width="100%">
					<td colspan="2">
						<table width="100%" cellpadding="2px">
							<tr width="100%">
								<td colspan="3">
									<h5 style="text-transform:uppercase;"><?php esc_html_e( 'Refunds', 'wc-print-invoice-receipts' ); ?></h5>
								</td>
							</tr>
							<tr width="100%" style="background-color:#ccc;">
								<td style="width:10%;font-size:12px;line-height:24px;padding:5px"><?php esc_html_e( 'SR.NO.', 'wc-print-invoice-receipts' ); ?></td>
								<td style="width:80%;font-size:12px;line-height:24px;padding:5px"><?php esc_html_e( 'REASON', 'wc-print-invoice-receipts' ); ?></td>
								<td style="width:10%;font-size:12px;line-height:24px;text-align:right;padding:5px"><?php esc_html_e( 'AMOUNT', 'wc-print-invoice-receipts' ); ?></td>
							</tr>
							<?php
							foreach ( $order_refunds as $key => $order_refund ) {
								$refund_amount = (float) $order_refund->amount;
								$order_totals -= $refund_amount;
								$refund_reason = $order_refund->refund_reason;
								if ( ! empty( $refund_amount ) ) {
									$index = $key + 1;
									?>
									<tr width="100%">
										<td style="width:10%;font-size:12px;line-height:28px;"><?php echo esc_html( "{$index}." ); ?></td>
										<td style="width:80%;font-size:12px;line-height:28px;"><?php echo esc_html( ( empty( $refund_reason ) ) ? __( 'N/A', 'wc-print-invoice-receipts' ) : $refund_reason ); ?></td>
										<td style="width:10%;color:red;font-size:12px;line-height:28px;text-align:right;"><?php echo '- ' . wp_kses_post( wc_price( $refund_amount ) ); ?></td>
									</tr>
									<?php
								}
							}
							?>
						</table>
					</td>
				</tr>
				<tr width="100%">
					<td colspan="2" style="border-bottom:1px dashed #919191;" height="0px"></td>
				</tr>
				<tr width="100%">
					<td colspan="2">
						<table cellspacing="0" cellpadding="0" width="100%" border="0">
							<tr width="100%">
								<td style="width:70%"></td>
								<td style="width:10%; line-height:28px;font-size:14px;text-align:right;"><?php esc_html_e( 'TOTAL', 'wc-print-invoice-receipts' ); ?></td>
								<td style="width:20%; line-height:28px;font-size:16px;text-align:right;font-weight:bold"><?php echo wp_kses_post( wc_price( $order_totals ) ); ?></td>
							</tr>
						</table>
					</td>
				</tr>

			<?php } ?>

			<!-- CUSTOMER NOTES -->
			<?php
			$customer_note = $wc_order->customer_message;
			if ( ! empty( $customer_note ) && true === $show_customer_note ) {
				?>
				<tr width="100%">
					<td colspan="2">
						<table cellspacing="0" cellpadding="0" width="100%" border="0">
							<tr width="100%">
								<td width="100%" style="line-height:16px;font-size:12px;">
									<span style="text-transform:uppercase;"><?php esc_html_e( 'Customer Note:', 'wc-print-invoice-receipts' ); ?></span><br/>
									<span><?php echo esc_html( $customer_note ); ?></span>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<?php
			}
			?>
		</table>
		<?php
		$pdf->StartTransform();
		$pdf->Rotate( 35 );
		$pdf->SetXY( 98, 177 );
		$paid_invoice_watermark = '<p stroke="0.4" fill="true" strokecolor="#b0f5b0" color="#fff" style="font-family:helvetica;font-weight:bold;font-size:36pt;z-index:9999">' . $watermark . '</p>';
		$pdf->writeHTML( $paid_invoice_watermark, true, false, false, false, '' );
		$pdf->StopTransform();
		$html = ob_get_clean();

		$search = array(
			'/\>[^\S ]+/s',     // strip whitespaces after tags, except space.
			'/[^\S ]+\</s',     // strip whitespaces before tags, except space.
			'/(\s)+/s',         // shorten multiple whitespace sequences.
			'/<!--(.|\s)*?-->/', // Remove HTML comments.
		);

		$replace = array(
			'>',
			'<',
			'\\1',
			'',
		);

		$html = preg_replace( $search, $replace, $html );
		$pdf->SetXY( 3, 37 );
		$pdf->writeHTMLCell( 0, 0, '', '', $html, 0, 1, 0, true, '', true );
		$timestamp      = time();
		$pdf_file_title = "order-receipt-{$order_id}-{$timestamp}.pdf";

		// Check the action requested.
		if ( '' !== $action ) {
			$customer_email = $wc_order->get_billing_email();
			$wp_upload_dir  = wp_upload_dir();
			$attach_path    = $wp_upload_dir['basedir'] . '/wc-logs/' . $pdf_file_title;
			$pdf->Output( $attach_path, 'F' );
			$admin_email = get_option( 'admin_email' );
			$site_title  = get_option( 'blogname' );
			$subject     = "Receipt Email - Order #{$order_id}";
			$headers     = 'From:' . $site_title . '<' . $admin_email . "> \r\n";
			$headers    .= 'Reply-To:' . $admin_email . "\r\n";
			$headers    .= "X-Priority: 1\r\n";
			$headers    .= 'MIME-Version: 1.0' . "\n";
			$headers    .= 'Content-type: text/html; charset=utf-8' . "\r\n";
			$attachments = array( $attach_path );
			$body        = 'Hello, please find the attached receipt.';
			wp_mail( $customer_email, $subject, $body, $headers, $attachments );
		} else {
			// The PDF is either to be viewed or downloaded.
			$view_receipt = wpir_get_plugin_setting( 'view-order-receipt' );
			$pdf_action   = ( true === $view_receipt ) ? 'I' : 'D';
			$pdf->Output( $pdf_file_title, $pdf_action );
		}
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_reservation_item_block_html' ) ) {
	/**
	 * Get the block HTML for reservation item.
	 *
	 * @param int $item_id Reservation item ID.
	 */
	function ersrv_get_reservation_item_block_html( $item_id ) {
		$user_id             = get_current_user_id();
		$featured_image_id   = get_post_thumbnail_id( $item_id );
		$item_featured_image = ersrv_get_attachment_url_from_attachment_id( $featured_image_id );
		$item_link           = get_permalink( $item_id );
		$item_details        = ersrv_get_item_details( $item_id );
		$adult_charge        = ( ! empty( $item_details['adult_charge'] ) ) ? $item_details['adult_charge'] : 0;
		$location            = ( ! empty( $item_details['location'] ) ) ? $item_details['location'] : '';
		$capacity            = ( ! empty( $item_details['accomodation_limit'] ) ) ? $item_details['accomodation_limit'] : '';
		$security_amt        = ( ! empty( $item_details['security_amount'] ) ) ? $item_details['security_amount'] : '';
		$min_reservation     = ( ! empty( $item_details['min_reservation_period'] ) ) ? $item_details['min_reservation_period'] : '';
		$max_reservation     = ( ! empty( $item_details['max_reservation_period'] ) ) ? $item_details['max_reservation_period'] : '';
		$is_favourite        = ersrv_is_favourite_item( $user_id, $item_id );
		$item_class          = ( $is_favourite ) ? 'selected' : '';
		$reservation_period  = '';

		// Generate the booking period restrictions.
		if ( ! empty( $min_reservation ) && ! empty( $max_reservation ) ) {
			$reservation_period = sprintf( __( 'Booking for min. %1$s to %2$s days.', 'easy-reservations' ), $min_reservation, $max_reservation );
		} elseif ( ! empty( $min_reservation ) ) {
			$reservation_period = sprintf( __( 'Booking for min. %1$s days.', 'easy-reservations' ), $min_reservation );
		}

		ob_start();
		?>
		<div class="col-12 col-md-6 col-lg-4 ersrv-reservation-item-block" data-item="<?php echo esc_attr( $item_id ); ?>">
			<div class="card">
				<div class="media">
					<a href="<?php echo esc_url( $item_link ); ?>">
						<img src="<?php echo esc_url( $item_featured_image ); ?>" alt="img" class="card-img" />
					</a>
				</div>
				<?php if ( is_user_logged_in() ) { ?>
					<div class="favorite">
						<a href="javascript:void(0);" class="favorite-link ersrv-mark-reservation-favourite <?php echo esc_attr( $item_class ); ?>">
							<span class="sr-only"><?php esc_html_e( 'Favorite', 'easy-reservations' ); ?></span>
							<span class="icon-heart">&nbsp;</span>
						</a>
					</div>
				<?php } ?>
				<div class="price-info">
					<div class="inner-wrapper color-black font-size-12 font-weight-semibold">
						<span class="color-accent font-size-18 font-Poppins">
							<?php echo wp_kses(
								wc_price( $adult_charge ),
								array(
									'span' => array(
										'class' => array(),
									),
								)
							); ?>
						</span><?php esc_html_e( ' - Per Night', 'easy-reservations' ); ?>
					</div>
				</div>
				<div class="card-body">
					<h3 class="card-title">
						<a href="<?php echo esc_url( $item_link ); ?>"><?php echo wp_kses_post( get_the_title( $item_id ) ); ?></a>
					</h3>
					<div class="review-stars mb-3">
						<img src="<?php echo esc_url ( ERSRV_PLUGIN_URL . 'public/images/stars.png' ); ?>" alt="stars">
					</div>
					<div class="amenities mb-3">
						<?php if ( $location ) {?>
							<div class="location">
								<span class="icon"><i class="fas fa-location-arrow"></i></span>
								<span><?php echo esc_html( $location ); ?></span>
							</div>
						<?php } ?>
						<div class="d-flex align-items-center flex-wrap">
							<div class="map-loaction mr-3">
								<span class="icon"><i class="fas fa-calendar-alt"></i></span>
								<span class=""><?php echo esc_html( $reservation_period ); ?></span>
							</div>
							<?php if ( $capacity ) { ?>
							<div class="capacity mr-3">
								<span class="font-weight-bold mr-2"><?php esc_html_e( 'Capacity:', 'easy-reservations' ); ?></span>
								<span class=""><?php echo esc_html( $capacity ); ?></span>
							</div>
							<?php } ?>
							<div class="cabins mr-3">
								<span class="icon"><i class="fas fa-money-bill-alt"></i></span>
								<span class="font-weight-bold mr-2"><?php esc_html_e( 'Security Amt:', 'easy-reservations' ); ?></span>
								<span class="">
									<?php echo wp_kses(
										wc_price( $security_amt ),
										array(
											'span' => array(
												'class' => array(),
											),
										)
									); ?>
								</span>
							</div>
						</div>
					</div>
					<div class="btns-group">
						<a href="<?php echo esc_url( $item_link ); ?>" class="btn btn-accent mr-2">Book Now</a>
						<a href="javascript:void(0);" class="btn btn-primary ersrv-quick-view-item">Quick View</a>
					</div>
				</div>
			</div>
		</div>
		<?php

		return ob_get_clean();
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_is_favourite_item' ) ) {
	/**
	 * Returns the image URL by attachment ID.
	 *
	 * @param int $image_id Holds the attachment ID.
	 * @return string
	 */
	function ersrv_is_favourite_item( $user_id, $item_id ) {
		$favourite_items = get_user_meta( $user_id, 'ersrv_favourite_items', true );

		return ( ! empty( $favourite_items ) && in_array( $item_id, $favourite_items, true ) ) ? true : false;
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_attachment_url_from_attachment_id' ) ) {
	/**
	 * Returns the image URL by attachment ID.
	 *
	 * @param int $image_id Holds the attachment ID.
	 * @return string
	 */
	function ersrv_get_attachment_url_from_attachment_id( $image_id ) {

		return ( empty( $image_id ) ) ? wc_placeholder_img_src() : wp_get_attachment_url( $image_id );
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_item_details' ) ) {
	/**
	 * Return all the details about the reservable item.
	 *
	 * @param int $item_id Holds the item ID.
	 * @return array
	 */
	function ersrv_get_item_details( $item_id ) {
		// Accomodation limit.
		$accomodation_limit = get_post_meta( $item_id, '_ersrv_accomodation_limit', true );

		// Reserved dates.
		$reserved_dates = ersrv_get_reservation_item_blockout_dates( $item_id );

		// Amenities.
		$amenities = get_post_meta( $item_id, '_ersrv_reservation_amenities', true );
		// Prepare the amenities HTML.
		ob_start();
		if ( ! empty( $amenities ) && is_array( $amenities ) ) {
			foreach ( $amenities as $index => $amenity ) {
				$title = ( ! empty( $amenity['title'] ) ) ? $amenity['title'] : '';
				$cost  = ( ! empty( $amenity['cost'] ) ) ? $amenity['cost'] : '';

				// Skip the HTML is either the title or the cost is missing.
				if ( empty( $title ) || empty( $cost ) ) {
					continue;
				}

				// WooCommerce currency symbol.
				$currency = get_woocommerce_currency_symbol();
				?>
				<div data-amenity="<?php echo esc_attr( $title ); ?>" data-cost="<?php echo esc_attr( $cost ); ?>" class="ersrv-new-reservation-single-amenity <?php echo ( 2 < $index ) ? 'mtop' : ''; ?>">
					<label class="ersrv-switch">
						<input type="checkbox" class="ersrv-switch-input">
						<span class="slider ersrv-switch-slider"></span>
					</label>
					<span><?php echo "{$title} [{$currency}{$cost}]"; ?></span>
				</div>
				<?php
			}
		}
		$amenity_html = ob_get_clean();

		// Put the details in an array.
		$item_details = array(
			'accomodation_limit'     => $accomodation_limit,
			'reserved_dates'         => $reserved_dates,
			'min_reservation_period' => get_post_meta( $item_id, '_ersrv_reservation_min_period', true ),
			'max_reservation_period' => get_post_meta( $item_id, '_ersrv_reservation_max_period', true ),
			'amenity_html'           => $amenity_html,
			'adult_charge'           => get_post_meta( $item_id, '_ersrv_accomodation_adult_charge', true ),
			'kid_charge'             => get_post_meta( $item_id, '_ersrv_accomodation_kid_charge', true ),
			'security_amount'        => get_post_meta( $item_id, '_ersrv_security_amt', true ),
			'location'               => get_post_meta( $item_id, '_ersrv_item_location', true ),
			'currency'               => get_woocommerce_currency_symbol(),
		);

		return $item_details;
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_account_endpoint_favourite_reservations' ) ) {
	/**
	 * Get the endpoint slug for customer account - favourite reservable items.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	function ersrv_get_account_endpoint_favourite_reservations() {
		$endpoint = 'favourite-reservable-items';
		/**
		 * This hook fires on customer's account page.
		 *
		 * This filter will help in modifying the favourite reservable items endpoint slug.
		 *
		 * @param string $endpoint Custom account endpoint slug.
		 * @return string
		 */
		return apply_filters( 'ersrv_account_endpoint_favourite_reservations_slug', $endpoint );
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_account_endpoint_label_favourite_reservations' ) ) {
	/**
	 * Get the endpoint label for customer account - favourite reservable items.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	function ersrv_get_account_endpoint_label_favourite_reservations() {
		$endpoint_label = __( 'Favourite Reservable Items', 'easy-reservations' );
		/**
		 * This hook fires on customer's account page.
		 *
		 * This filter will help in modifying the favourite reservable items endpoint label.
		 *
		 * @param string $endpoint_label Custom account endpoint label.
		 * @return string
		 */
		return apply_filters( 'ersrv_account_endpoint_favourite_reservations_label', $endpoint_label );
	}
}

/**
 * Check if the function exists.
 */
if ( ! function_exists( 'ersrv_get_page_id' ) ) {
	/**
	 * Get the page ID.
	 *
	 * @param string $page_slug Holds the page slug.
	 * @return int
	 * @since 1.0.0
	 */
	function ersrv_get_page_id( $page_slug ) {
		$page = apply_filters( 'ersrv_get_' . $page_slug . '_page_id', get_option( 'ersrv_' . $page_slug . '_page_id' ) );

		return $page ? absint( $page ) : -1;
	}
}
