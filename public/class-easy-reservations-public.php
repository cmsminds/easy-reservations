<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.cmsminds.com/
 * @since      1.0.0
 *
 * @package    Easy_Reservations
 * @subpackage Easy_Reservations/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Easy_Reservations
 * @subpackage Easy_Reservations/public
 * @author     cmsMinds <info@cmsminds.com>
 */
class Easy_Reservations_Public {
	/**
	 * The ID of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Whether the calendar widget is active or not.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    boolean $is_calendar_widget_active Whether the calendar widget is active or not.
	 */
	private $is_calendar_widget_active;

	/**
	 * Reservation - Custom product type.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string $custom_product_type Reservation - Custom product type.
	 */
	private $custom_product_type;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		// Check, if the calendar widget is active or not.
		$calendar_widget_base_id         = ersrv_get_calendar_widget_base_id(); // Calendar widget base id.
		$this->is_calendar_widget_active = is_active_widget( false, false, $calendar_widget_base_id );

		// Custom product type.
		$this->custom_product_type = ersrv_get_custom_product_type_slug();
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function ersrv_wp_enqueue_scripts_callback() {
		global $wp_registered_widgets, $post;
		// Active style file based on the active theme.
		$current_theme       = get_option( 'stylesheet' );
		$active_style        = ersrv_get_active_stylesheet( $current_theme );
		$active_style_url    = ( ! empty( $active_style['url'] ) ) ? $active_style['url'] : '';
		$active_style_path   = ( ! empty( $active_style['path'] ) ) ? $active_style['path'] : '';

		/************************************************ STYLES ************************************************/

		// Include few styles on reservation product type page.
		if ( is_product() ) {
			$wc_product = wc_get_product( get_the_ID() );
			// Check for reservation product type.
			if ( $this->custom_product_type === $wc_product->get_type() ) {
				// Enqueue the bootstrap style.
				wp_enqueue_style(
					$this->plugin_name . '-bootstrap-style',
					ERSRV_PLUGIN_URL . 'public/css/bootstrapv4.6.0.min.css'
				);

				// Enqueue the bootstrap select style.
				wp_enqueue_style(
					$this->plugin_name . '-bootstrap-select-style',
					ERSRV_PLUGIN_URL . 'public/css/bootstrap-select.1.13.14.min.css'
				);

				// Enqueue the bootstrap datepicker style.
				wp_enqueue_style(
					$this->plugin_name . '-bootstrap-datepicker-style',
					ERSRV_PLUGIN_URL . 'public/css/bootstrap-datepicker-v1.9.0.min.css'
				);

				// Enqueue the free font-awesome style.
				wp_enqueue_style(
					$this->plugin_name . '-font-awesome-style',
					'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.13.1/css/all.css'
				);

				// Enqueue the calendar core style.
				wp_enqueue_style(
					$this->plugin_name . '-fullcalendar-core-style',
					ERSRV_PLUGIN_URL . 'public/css/fullcalendar/main.min.css'
				);

				// Enqueue the public style only when the style url and path are available.
				if ( ! empty( $active_style_url ) && ! empty( $active_style_path ) ) {
					wp_enqueue_style(
						$this->plugin_name,
						$active_style_url,
						array(),
						filemtime( $active_style_path ),
					);
				}
			}
		}

		// Add the UI style only when the widget is active.
		if ( false !== $this->is_calendar_widget_active ) {
			// Enqueue the bootstrap style if not already enqueued.
			if ( ! wp_style_is( $this->plugin_name . '-bootstrap-style', 'enqueued' ) ) {
				wp_enqueue_style(
					$this->plugin_name . '-bootstrap-style',
					ERSRV_PLUGIN_URL . 'public/css/bootstrapv4.6.0.min.css'
				);
			}

			// Enqueue the bootstrap datepicker style if not already enqueued.
			if ( ! wp_style_is( $this->plugin_name . '-bootstrap-datepicker-style', 'enqueued' ) ) {
				wp_enqueue_style(
					$this->plugin_name . '-bootstrap-datepicker-style',
					ERSRV_PLUGIN_URL . 'public/css/bootstrap-datepicker-v1.9.0.min.css'
				);
			}

			wp_enqueue_style(
				$this->plugin_name,
				ERSRV_PLUGIN_URL . 'public/css/widget/calendar/easy-reservations-calendar-widget.css',
				array(),
				filemtime( ERSRV_PLUGIN_PATH . 'public/css/widget/calendar/easy-reservations-calendar-widget.css' ),
			);
		}

		/************************************************ SCRIPTS ************************************************/

		if ( is_product() ) {
			$wc_product = wc_get_product( get_the_ID() );
			// Check for reservation product type.
			if ( $this->custom_product_type === $wc_product->get_type() ) {
				// Bootstrap bundle script.
				wp_enqueue_script(
					$this->plugin_name . '-bootstrap-bundle-script',
					ERSRV_PLUGIN_URL . 'public/js/bootstrap-bundle-v4.6.0.min.js',
					array(),
					filemtime( ERSRV_PLUGIN_PATH . 'public/js/bootstrap-bundle-v4.6.0.min.js' ),
					true
				);

				// Bootstrap select script.
				wp_enqueue_script(
					$this->plugin_name . '-bootstrap-select-script',
					ERSRV_PLUGIN_URL . 'public/js/bootstrap-select-v.1.13.14.min.js',
					array(),
					filemtime( ERSRV_PLUGIN_PATH . 'public/js/bootstrap-select-v.1.13.14.min.js' ),
					true
				);

				// Bootstrap datepicker script.
				wp_enqueue_script(
					$this->plugin_name . '-bootstrap-datepicker-script',
					ERSRV_PLUGIN_URL . 'public/js/bootstrap-datepicker-v.1.9.0.min.js',
					array(),
					filemtime( ERSRV_PLUGIN_PATH . 'public/js/bootstrap-datepicker-v.1.9.0.min.js' ),
					true
				);

				// Moment script.
				wp_enqueue_script(
					$this->plugin_name . '-moment-script',
					ERSRV_PLUGIN_URL . 'public/js/moment-v2.29.1.min.js',
					array(),
					filemtime( ERSRV_PLUGIN_PATH . 'public/js/moment-v2.29.1.min.js' ),
					true
				);

				// Full calendar script.
				wp_enqueue_script(
					$this->plugin_name . '-full-calendar-script',
					ERSRV_PLUGIN_URL . 'public/js/fullcalendar/main.min.js',
					array(),
					filemtime( ERSRV_PLUGIN_PATH . 'public/js/fullcalendar/main.min.js' ),
					true
				);

				// Custom public script.
				wp_enqueue_script(
					$this->plugin_name,
					ERSRV_PLUGIN_URL . 'public/js/easy-reservations-public.js',
					array( 'jquery' ),
					filemtime( ERSRV_PLUGIN_PATH . 'public/js/easy-reservations-public.js' ),
					true
				);

				// Localize script.
				wp_localize_script(
					$this->plugin_name,
					'ERSRV_Public_Script_Vars',
					array(
						'ajaxurl'        => admin_url( 'admin-ajax.php' ),
						'remove_sidebar' => ersrv_get_plugin_settings( 'ersrv_remove_product_single_sidebar' ), 
					)
				);
			}
		}

		// Add the datepicker and custom script only when the widget is active.
		if ( false !== $this->is_calendar_widget_active ) {
			// Enqueue the bootstrap datepicker script if not already enqueued.
			if ( ! wp_script_is( $this->plugin_name . '-bootstrap-datepicker-script', 'enqueued' ) ) {
				wp_enqueue_script(
					$this->plugin_name . '-bootstrap-datepicker-script',
					ERSRV_PLUGIN_URL . 'public/js/bootstrap-datepicker-v.1.9.0.min.js',
					array(),
					filemtime( ERSRV_PLUGIN_PATH . 'public/js/bootstrap-datepicker-v.1.9.0.min.js' ),
					true
				);
			}

			// Calendar widget public script.
			wp_enqueue_script(
				$this->plugin_name . '-calendar-widget',
				ERSRV_PLUGIN_URL . 'public/js/widget/calendar/easy-reservations-calendar-widget.js',
				array( 'jquery' ),
				filemtime( ERSRV_PLUGIN_PATH . 'public/js/widget/calendar/easy-reservations-calendar-widget.js' ),
				true
			);

			// Localize script.
			wp_localize_script(
				$this->plugin_name . '-calendar-widget',
				'ERSRV_Calendar_Widget_Script_Vars',
				array(
					'ajaxurl'       => admin_url( 'admin-ajax.php' ),
					'start_of_week' => get_option( 'start_of_week' ),
				)
			);
		}
	}

	/**
	 * Do the following when WordPress initiates.
	 * 1. Register custom product type in WooCommerce Products.
	 *
	 * @since    1.0.0
	 */
	public function ersrv_init_callback() {
		// Include the product type custom class.
		require ERSRV_PLUGIN_PATH . 'includes/classes/class-wc-product-reservation.php';
	}

	/**
	 * Reservation item callback.
	 *
	 * @param array $args Holds the shortcode arguments.
	 * @return string
	 * @since 1.0.0
	 */
	public function ersrv_ersrv_reserve_item_callback( $args = array() ) {
		// Return, if it's admin panel.
		if ( is_admin() ) {
			return;
		}

		ob_start();
		require_once ERSRV_PLUGIN_PATH . 'public/templates/shortcodes/reservation.php';
		return ob_get_clean();
	}

	/**
	 * Add the reservation shortcode to the woocommerce product page.
	 */
	public function ersrv_woocommerce_after_single_product_summary_callback() {
		global $product;
		$product_id   = $product->get_id();
		$product_type = $product->get_type();

		// Return, if the current product type doesn't match with the expected product type.
		if ( $this->custom_product_type !== $product_type ) {
			return;
		}

		// Integrate the shortcode, instead.
		echo do_shortcode( '[ersrv_reserve_item id="' . $product_id . '"]' );
	}

	/**
	 * Add buttons to save reservations to google calendar and icalendar on the thank you page.
	 *
	 * @param WC_Order $wc_order WooCommerce Order data.
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_after_order_details_callback( $wc_order ) {
		$add_calendar_buttons = ersrv_order_is_reservation( $wc_order );
		$add_calendar_buttons = true;
		
		// Return, if there is no need to add calendar buttons.
		if ( ! $add_calendar_buttons ) {
			return;
		}

		$google_calendar_button_text = __( 'Add to my Google Calendar', 'easy-reservations' );
		/**
		 * This hook fires on checkout page to add reservation to calendar.
		 *
		 * This filter helps to modify the google calendar button text, which adds the reservation to google calendar.
		 *
		 * @param string   $google_calendar_button_text Google calendar button text. 
		 * @param WC_Order $wc_order WooCommerce Order data.
		 * @return string
		 * @since 1.0.0
		 */
		$google_calendar_button_text = apply_filters( 'ersrv_add_reservation_to_google_calendar_button_text', $google_calendar_button_text, $wc_order );

		$icalendar_button_text = __( 'Add to my iCalendar', 'easy-reservations' );
		/**
		 * This hook fires on checkout page to add reservation to calendar.
		 *
		 * This filter helps to modify the icalendar button text, which adds the reservation to icalendar.
		 *
		 * @param string   $icalendar_button_text Google calendar button text. 
		 * @param WC_Order $wc_order WooCommerce Order data.
		 * @return string
		 * @since 1.0.0
		 */
		$icalendar_button_text = apply_filters( 'ersrv_add_reservation_to_icalendar_button_text', $icalendar_button_text, $wc_order );

		ob_start();
		?>
		<div class="ersrv-reservation-calendars-container" data-oid="<?php echo esc_attr( $wc_order->get_id() ); ?>">
			<button type="button" class="add-to-gcal"><?php echo wp_kses_post( $google_calendar_button_text ); ?></button>
			<button type="button" class="add-to-ical"><?php echo wp_kses_post( $icalendar_button_text ); ?></button>
		</div>
		<?php
		$reservations_calendar_container = ob_get_clean();

		/**
		 * This hook fires on checkout page.
		 *
		 * This hook helps to modify the reservations calendar html container.
		 *
		 * @param string   $reservations_calendar_container Holds the reservations calendar html.
		 * @param string   $google_calendar_button_text Holds the google calendar button text.
		 * @param string   $icalendar_button_text Holds the icalendar button text.
		 * @param WC_Order $wc_order WooCommerce Order data.
		 * @return string
		 * @since 1.0.0
		 */
		echo wp_kses_post( apply_filters( 'ersrv_reservations_calendar_container_html', $reservations_calendar_container, $google_calendar_button_text, $icalendar_button_text, $wc_order ) );
	}

	/**
	 * AJAX to add reservation to customer's google calendar.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_add_reservation_to_gcal_callback() {
		$action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );

		// Exit, if the action mismatches.
		if ( empty( $action ) || 'add_reservation_to_gcal' !== $action ) {
			echo 0;
			wp_die();
		}

		// Posted data.
		$order_id = (int) filter_input( INPUT_POST, 'order_id', FILTER_SANITIZE_NUMBER_INT );

		// Exit, if this order ID is invalid.
		$wc_order_post = get_post( $order_id );

		if ( is_null( $wc_order_post ) ) {
			echo -1;
			wp_die();
		}

		/**
		 * This hook fires before adding reservation to the calendar.
		 *
		 * This hook helps in executing anything before the reservation is added to google calendar.
		 *
		 * @param int $order_id Holds the WooCommerce order ID.
		 */
		do_action( 'ersrv_add_reservation_to_gcal_before', $order_id );

		// Add the reservation to the calendar now.

		/**
		 * This hook fires after adding reservation to the calendar.
		 *
		 * This hook helps in executing anything after the reservation is added to google calendar.
		 *
		 * @param int $order_id Holds the WooCommerce order ID.
		 */
		do_action( 'ersrv_add_reservation_to_gcal_after', $order_id );
	}

	/**
	 * AJAX to add reservation to customer's icalendar.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_add_reservation_to_ical_callback() {
		$action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );

		// Exit, if the action mismatches.
		if ( empty( $action ) || 'add_reservation_to_ical' !== $action ) {
			echo 0;
			wp_die();
		}

		// Posted data.
		$order_id = (int) filter_input( INPUT_POST, 'order_id', FILTER_SANITIZE_NUMBER_INT );

		// Exit, if this order ID is invalid.
		$wc_order_post = get_post( $order_id );

		if ( is_null( $wc_order_post ) ) {
			echo -1;
			wp_die();
		}

		/**
		 * This hook fires before adding reservation to the calendar.
		 *
		 * This hook helps in executing anything before the reservation is added to icalendar.
		 *
		 * @param int $order_id Holds the WooCommerce order ID.
		 */
		do_action( 'ersrv_add_reservation_to_ical_before', $order_id );

		// Add the reservation to the icalendar now.
		$reservation_start_date = '2021-06-29 13:30:00';
		$reservation_end_date   = '2021-06-31 14:30:00';
		$event                  = array(
			'id'          => $order_id,
			'title'       => sprintf( __( 'Reservation booked. ID: #%1$d', 'easy-reservations' ), $order_id ),
			'description' => sprintf( __( 'Example reservation booked on %1$s', 'easy-reservations' ), get_bloginfo( 'name' ) ),
			'datestart'   => strtotime( $reservation_start_date ),
			'dateend'     => strtotime( $reservation_end_date ),
			'address'     => '123 Fake St, MyCity, State 12345',
			'order_url'   => home_url( "/my-account/view-order/{$order_id}/" ),
		);

		// Build the ics file.
		$ical = 'BEGIN:VCALENDAR
		VERSION:2.0
		PRODID:-//hacksw/handcal//NONSGML v1.0//EN
		CALSCALE:GREGORIAN
		BEGIN:VEVENT
		DTEND:' . ersrv_get_icalendar_formatted_date( $event['dateend'] ) . '
		UID:' . md5( $event['title'] ) . '
		DTSTAMP:' . time() . '
		LOCATION:' . addslashes( $event['address'] ) . '
		DESCRIPTION:' . addslashes($event['description']) . '
		URL;VALUE=URI: ' . $event['order_url'] . '
		SUMMARY:' . addslashes( $event['title'] ) . '
		DTSTART:' . ersrv_get_icalendar_formatted_date( $event['datestart'] ) . '
		END:VEVENT
		END:VCALENDAR';

		header( 'Content-type: text/calendar; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=your-reservation.ics' );
		echo $ical;

		die;

		/**
		 * This hook fires after adding reservation to the calendar.
		 *
		 * This hook helps in executing anything after the reservation is added to icalendar.
		 *
		 * @param int $order_id Holds the WooCommerce order ID.
		 */
		do_action( 'ersrv_add_reservation_to_ical_after', $order_id );
	}

	/**
	 * Customizations on thank you page.
	 * Send the email to multiple administrators set in plugin settings.
	 *
	 * @param int $order_id WooCommerce Order ID.
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_thankyou_callback( $order_id ) {
		$recipients = array(
			'adarshvermaofficial3@gmail.com',
			'jyoti.adarsh.verma@gmail.com',
		);
		wp_mail( $recipients, 'Test Subject', 'Test Content' );
	}

	/**
	 * Modify the post query arguments.
	 *
	 * @param array $args Holds the post arguments.
	 * @return array
	 * @since 1.0.0
	 */
	public function ersrv_ersrv_posts_args_callback( $args = array() ) {
		// If the product posts are requested, modify the query to set product type of 'reservation'.
		$post_type = ( ! empty( $args['post_type'] ) ) ? $args['post_type'] : '';

		// If the post type is available.
		if ( ! empty( $post_type ) ) {
			// Set the taxonomy args.
			$args['tax_query'][] = array(
				'taxonomy' => 'product_type',
				'field'    => 'slug',
				'terms'    => $this->custom_product_type,
			);
		}

		return $args;
	}

	/**
	 * Get the unavailability dates of particular item.
	 *
	 * @param int $item_id Holds the reservable item ID.
	 * @return array
	 * @since 1.0.0
	 */
	public function ersrv_get_item_unavailable_dates_callback( $item_id ) {
		// If doing AJAX.
		if ( DOING_AJAX ) {
			$action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );

			// Check if action mismatches.
			if ( empty( $action ) || 'get_item_unavailable_dates' !== $action ) {
				echo 0;
				wp_die();
			}

			// Posted data.
			$item_id = (int) filter_input( INPUT_POST, 'item_id', FILTER_SANITIZE_NUMBER_INT );
		}

		// Now that we have item ID, get the unavailability dates.
		$unavailability_dates = get_post_meta( $item_id, 'ersrv_unavailability_dates' );
		$unavailability_dates = array( '2021-06-25', '2021-06-28' );

		// If doing AJAX.
		if ( DOING_AJAX ) {
			$response = array(
				'code'      => 'unavailability-dates-fetched',
				'dates'     => $unavailability_dates,
				'item_link' => get_permalink( $item_id ),
			);
			wp_send_json_success( $response );
			wp_die();
		}

		// Return the dates, otherewise.
		return $unavailability_dates;
	}

	/**
	 * Add custom assets in header.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_wp_head_callback() {
		// Add the style to active calendar dates only when the widget is active.
		if ( false !== $this->is_calendar_widget_active ) {
			$calendar_widget_base_id = ersrv_get_calendar_widget_base_id(); // Calendar widget base id.
			$widget_settings         = ersrv_get_widget_settings( $calendar_widget_base_id );
			$active_date_bg_color    = ( ! empty( $widget_settings['available_dates_bg_color'] ) ) ? $widget_settings['available_dates_bg_color'] : '#000';
			?>
			<style>
				.ersrv-date-active {
					background-color: <?php echo esc_attr( $active_date_bg_color ); ?>;
					color: #fff;
				}
			</style>
			<?php
		}
	}

	/**
	 * Change the add to cart button text for reservations on shop page.
	 *
	 * @param string     $button_text Holds the button text.
	 * @param WC_Product $wc_product Holds the product object.
	 * @return string
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_product_add_to_cart_text_callback( $button_text, $wc_product ) {
		// Return the button text if the product is not of reservation type.
		if ( $this->custom_product_type !== $wc_product->get_type() ) {
			return $button_text;
		}

		return ersrv_get_plugin_settings( 'ersrv_archive_page_add_to_cart_button_text' );
	}

	/**
	 * Alter the related products on reservation product type.
	 *
	 * @param array $related_post_ids Holds the related posts IDs.
	 * @param int   $post_id Holds the current post ID.
	 * @return array
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_related_products_callback( $related_post_ids, $post_id ) {
		global $product;

		// Return, if it's not product single page.
		if ( ! is_product() ) {
			return $related_post_ids;
		}

		// Return, if the current product is not of reservation type.
		if ( $this->custom_product_type !== $product->get_type() ) {
			return $related_post_ids;
		}

		// Get the reservation type products now.
		$reservation_posts_query = ersrv_get_posts( 'product', 1, -1 );
		$related_post_ids        = $reservation_posts_query->posts;

		// Unset the current reservation item.
		$item_to_exclude_key = array_search( $post_id, $related_post_ids );
		if ( false !== $item_to_exclude_key ) {
			unset( $related_post_ids[ $item_to_exclude_key ] );
		}

		return $related_post_ids;
	}
}
