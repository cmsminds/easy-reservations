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
	 * @return object
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
	 */
	function ersrv_get_active_stylesheet( $current_theme ) {
		switch( $current_theme ) {
			case 'twentysixteen':
				return array(
					'url'  => ERSRV_PLUGIN_URL . 'public/css/easy-reservations-twentysixteen.css',
					'path' => ERSRV_PLUGIN_PATH . 'public/css/easy-reservations-twentysixteen.css',
				);

			case 'twentyseventeen':
				return array(
					'url'  => ERSRV_PLUGIN_URL . 'public/css/easy-reservations-twentyseventeen.css',
					'path' => ERSRV_PLUGIN_PATH . 'public/css/easy-reservations-twentyseventeen.css',
				);

			case 'twentynineteen':
				return array(
					'url'  => ERSRV_PLUGIN_URL . 'public/css/easy-reservations-twentynineteen.css',
					'path' => ERSRV_PLUGIN_PATH . 'public/css/easy-reservations-twentynineteen.css',
				);

			case 'twentytwenty':
				return array(
					'url'  => ERSRV_PLUGIN_URL . 'public/css/easy-reservations-twentytwenty.css',
					'path' => ERSRV_PLUGIN_PATH . 'public/css/easy-reservations-twentytwenty.css',
				);

			case 'twentytwentyone':
				return array(
					'url'  => ERSRV_PLUGIN_URL . 'public/css/easy-reservations-twentytwentyone.css',
					'path' => ERSRV_PLUGIN_PATH . 'public/css/easy-reservations-twentytwentyone.css',
				);

			case 'storefront':
				return array(
					'url'  => ERSRV_PLUGIN_URL . 'public/css/easy-reservations-storefront.css',
					'path' => ERSRV_PLUGIN_PATH . 'public/css/easy-reservations-storefront.css',
				);

			default:
				return array(
					'url'  => ERSRV_PLUGIN_URL . 'public/css/easy-reservations-other.css',
					'path' => ERSRV_PLUGIN_PATH . 'public/css/easy-reservations-other.css',
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
	 */
	function ersrv_get_icalendar_formatted_date( $timestamp = '', $include_time = true ) {

		return gmdate( 'Ymd' . ( $include_time ? '\THis' : '' ), $timestamp ) . 'Z';
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
	 */
	function ersrv_order_is_reservation( $wc_order ) {
		// Get the order line items.
		$line_items = $wc_order->get_items();

		// Return, if there are no order items.
		if ( empty( $line_items ) || ! is_array( $line_items ) ) {
			return false;
		}

		$custom_product_type  = ersrv_get_custom_product_type_slug();

		// Iterate through the items to check if any reservation has been booked.
		foreach ( $line_items as $line_item ) {
			$line_item_product_id   = $line_item->get_product_id();

			// If the item ID is available.
			$line_item_type = get_the_terms( $line_item_product_id, 'product_type' );

			// Check if the product is of reservation type.
			if ( ! empty( $line_item_type[0]->slug ) && $custom_product_type === $line_item_type[0]->slug ) {
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
	 * @param string  $amenity_title Holds the amenity title.
	 * @param string  $amenity_cost Holds the amenity cost.
	 * @return string
	 */
	function ersrv_get_amenity_html( $amenity_title = '', $amenity_cost = '' ) {
		ob_start();
		?>
		<p class="form-field reservation_amenity_field amenities-row">
			<input type="text" value="<?php echo esc_html( $amenity_title ); ?>" required name="amenity_title[]" class="short addTitle-field" placeholder="<?php esc_html_e( 'Amenity Title', 'easy-reservations' ); ?>">
			<input type="number" value="<?php echo esc_html( $amenity_cost ); ?>" required name="amenity_cost[]" class="short addNumber-field" placeholder="0.0" step="0.01" min="0.01">
			<button type="button" class="button button-secondary btn-submit ersrv-remove-amenity-html">
				<?php esc_html_e( 'Remove', 'easy-reservations' ); ?>
			</button>
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
