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

		case 'ersrv_remove_product_single_sidebar':
			$data = get_option( $setting );
			$data = ( ! empty( $data ) && ! is_bool( $data ) ) ? $data : 'no';
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
		$vars = array(
			'ajaxurl'             => admin_url( 'admin-ajax.php' ),
			'same_as_adult'       => __( 'Same as Adult!', 'easy-reservations' ),
			'export_reservations' => __( 'Export Reservations', 'easy-reservations' ),
		);

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
