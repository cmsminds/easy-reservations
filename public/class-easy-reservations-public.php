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
	 * @since 1.0.0
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
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
		$is_search_page      = ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'ersrv_search_reservations' ) );
		$is_reservation_page = ersrv_product_is_reservation( get_the_ID() );

		/* ---------------------------------------STYLES--------------------------------------- */

		// If it's the single reservation page or the search page.
		if ( $is_reservation_page || $is_search_page ) {
			// Enqueue the bootstrap style.
			wp_enqueue_style(
				$this->plugin_name . '-bootstrap-style',
				ERSRV_PLUGIN_URL . 'public/css/bootstrap/bootstrap.min.css',
				array(),
				filemtime( ERSRV_PLUGIN_PATH . 'public/css/bootstrap/bootstrap.min.css' )
			);

			// Enqueue the ui style.
			wp_enqueue_style(
				$this->plugin_name . '-jquery-ui-style',
				ERSRV_PLUGIN_URL . 'public/css/ui/jquery-ui.min.css',
				array(),
				filemtime( ERSRV_PLUGIN_PATH . 'public/css/ui/jquery-ui.min.css' )
			);

			// Enqueue the bootstrap select style.
			wp_enqueue_style(
				$this->plugin_name . '-bootstrap-select-style',
				ERSRV_PLUGIN_URL . 'public/css/bootstrap/bootstrap-select.min.css',
				array(),
				filemtime( ERSRV_PLUGIN_PATH . 'public/css/bootstrap/bootstrap-select.min.css' )
			);

			// Enqueue the free font-awesome style.
			// wp_enqueue_style(
			// 	$this->plugin_name . '-font-awesome-style',
			// 	'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.13.1/css/all.css',
			// 	array(),
			// 	'5.13.1'
			// );

			// Enqueue the public style only when the style url and path are available.
			if ( ! empty( $active_style_url ) && ! empty( $active_style_path ) ) {
				wp_enqueue_style(
					$this->plugin_name,
					$active_style_url,
					array(),
					filemtime( $active_style_path ),
				);
			}

			// Enqueue the public style only when the style url and path are available.
			wp_enqueue_style(
				$this->plugin_name . '-common',
				ERSRV_PLUGIN_URL . 'public/css/core/easy-reservations-common.css',
				array(),
				filemtime( ERSRV_PLUGIN_PATH . 'public/css/core/easy-reservations-common.css' )
			);
		}

		// Add the UI style only when the widget is active.
		if ( false !== $this->is_calendar_widget_active ) {
			// Enqueue the bootstrap style if not already enqueued.
			if ( ! wp_style_is( $this->plugin_name . '-bootstrap-style', 'enqueued' ) ) {
				wp_enqueue_style(
					$this->plugin_name . '-bootstrap-style',
					ERSRV_PLUGIN_URL . 'public/css/bootstrap/bootstrap.min.css',
					array(),
					filemtime( ERSRV_PLUGIN_PATH . 'public/css/bootstrap/bootstrap.min.css' )
				);
			}

			// Enqueue the ui datepicker style if not already enqueued.
			if ( ! wp_style_is( $this->plugin_name . '-jquery-ui-style', 'enqueued' ) ) {
				wp_enqueue_style(
					$this->plugin_name . '-jquery-ui-style',
					ERSRV_PLUGIN_URL . 'public/css/ui/jquery-ui.min.css',
					array(),
					filemtime( ERSRV_PLUGIN_PATH . 'public/css/ui/jquery-ui.min.css' )
				);
			}

			wp_enqueue_style(
				$this->plugin_name,
				ERSRV_PLUGIN_URL . 'public/css/widget/calendar/easy-reservations-calendar-widget.css',
				array(),
				filemtime( ERSRV_PLUGIN_PATH . 'public/css/widget/calendar/easy-reservations-calendar-widget.css' ),
			);
		}

		/* ---------------------------------------SCRIPTS--------------------------------------- */

		// If it's the single reservation page or the search page.
		if ( $is_reservation_page || $is_search_page ) {
			// Bootstrap bundle script.
			wp_enqueue_script(
				$this->plugin_name . '-bootstrap-bundle-script',
				ERSRV_PLUGIN_URL . 'public/js/bootstrap/bootstrap.bundle.min.js',
				array(),
				filemtime( ERSRV_PLUGIN_PATH . 'public/js/bootstrap/bootstrap.bundle.min.js' ),
				true
			);

			// Bootstrap select script.
			wp_enqueue_script(
				$this->plugin_name . '-bootstrap-select-script',
				ERSRV_PLUGIN_URL . 'public/js/bootstrap/bootstrap-select.min.js',
				array(),
				filemtime( ERSRV_PLUGIN_PATH . 'public/js/bootstrap/bootstrap-select.min.js' ),
				true
			);

			// Enqueue the ui script.
			wp_enqueue_script(
				$this->plugin_name . '-jquery-ui-script',
				ERSRV_PLUGIN_URL . 'public/js/ui/jquery-ui.min.js',
				array( 'jquery' ),
				filemtime( ERSRV_PLUGIN_PATH . 'public/js/ui/jquery-ui.min.js' )
			);

			// Custom public script.
			self::ersrv_enqueue_plugin_core_js( $this->plugin_name );
		} elseif ( is_checkout() ) {
			self::ersrv_enqueue_plugin_core_js( $this->plugin_name );
		}

		// Add the datepicker and custom script only when the widget is active.
		if ( false !== $this->is_calendar_widget_active ) {
			// Enqueue the ui datepicker script if not already enqueued.
			if ( ! wp_script_is( $this->plugin_name . '-jquery-ui-script', 'enqueued' ) ) {
				wp_enqueue_style(
					$this->plugin_name . '-jquery-ui-script',
					ERSRV_PLUGIN_URL . 'public/js/ui/jquery-ui.min.js',
					array(),
					filemtime( ERSRV_PLUGIN_PATH . 'public/js/ui/jquery-ui.min.js' )
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
	 * Enqueue the plugin core JS file.
	 *
	 * @param string $plugin_name Plugin folder name.
	 * @since 1.0.0
	 */
	public static function ersrv_enqueue_plugin_core_js( $plugin_name ) {
		// Custom public script.
		wp_enqueue_script(
			$plugin_name,
			ERSRV_PLUGIN_URL . 'public/js/core/easy-reservations-public.js',
			array( 'jquery' ),
			filemtime( ERSRV_PLUGIN_PATH . 'public/js/core/easy-reservations-public.js' ),
			true
		);

		// Localize script.
		wp_localize_script(
			$plugin_name,
			'ERSRV_Public_Script_Vars',
			array(
				'ajaxurl'        => admin_url( 'admin-ajax.php' ),
				'remove_sidebar' => ersrv_get_plugin_settings( 'ersrv_remove_product_single_sidebar' ),
				'is_product'     => ( is_product() ) ? 'yes' : 'no',
				'is_checkout'    => ( is_checkout() ) ? 'yes' : 'no',
			)
		);
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

		// Check if the action is required to download iCalendar invite.
		$action = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );

		// If it's the download reservation receipt request.
		if ( ! is_null( $action ) && 'ersrv-download-reservation-receipt' === $action ) {
			$order_id = filter_input( INPUT_GET, 'atts', FILTER_SANITIZE_NUMBER_INT );
			ersrv_download_reservation_receipt_callback( $order_id );
		} elseif ( ! is_null( $action ) && 'download_ical_invite' === $action ) {
			$order_id = filter_input( INPUT_GET, 'order_id', FILTER_SANITIZE_NUMBER_INT );
			// Include the ical library file.
			require_once ERSRV_PLUGIN_PATH . 'includes/lib/ICS.php';

			$nvite_file_name = "ersrv-reservation-#{$order_id}.ics";
			/**
			 * This hook fires when the request to download ical file is processed.
			 *
			 * This filter helps to modify the ical file name.
			 *
			 * @param string $nvite_file_name iCal file name.
			 * @param string $order_id WooCommerce Order ID.
			 * @return string
			 * @since 1.0.0
			 */
			$nvite_file_name = apply_filters( 'ersrv_icalendar_invitation_filename', $nvite_file_name, $order_id );

			header( 'Content-Type: text/calendar; charset=utf-8' );
			header( 'Content-Disposition: attachment; filename=' . $nvite_file_name );

			$invitation_details = array(
				'location'    => '123 Fake St, New York, NY', // reservation item location.
				'description' => 'This is my description', // reservation description.
				'dtstart'     => '2017-1-16 9:00AM', // reservation start date time.
				'dtend'       => '2017-1-16 10:00AM', // reservation end date time.
				'summary'     => 'This is my summary', // invitation summary.
				'url'         => 'https://example.com' // view order url.
			);
			/**
			 * This hook fires when the request to download ical file is processed.
			 *
			 * This filter helps to modify the ical invitation details.
			 *
			 * @param array  $invitation_details iCal details.
			 * @param string $order_id WooCommerce Order ID.
			 * @return array
			 * @since 1.0.0
			 */
			$invitation_details = apply_filters( 'ersrv_icalendar_invitation_details', $invitation_details, $order_id );

			// Generate the invitation now.
			$ics = new ICS( $invitation_details );
			
			/**
			 * This hook fires before icalendar invitation is downloaded.
			 *
			 * This hook helps in executing anything before the reservation icalendar invite is downloaded.
			 *
			 * @param int $order_id Holds the WooCommerce order ID.
			 */
			do_action( 'ersrv_add_reservation_to_ical_before', $order_id );

			// Download the invitation.
			echo $ics->to_string();
			exit;
		}
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
		$order_id             = $wc_order->get_id();

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

		// Download ical invite link.
		$download_ical_invite_link = home_url( "/?action=download_ical_invite&order_id={$order_id}" );
		ob_start();
		?>
		<div class="ersrv-reservation-calendars-container" data-oid="<?php echo esc_attr( $order_id ); ?>">
			<button type="button" class="add-to-gcal"><?php echo wp_kses_post( $google_calendar_button_text ); ?></button>
			<button type="button" class="add-to-ical" data-goto="<?php echo $download_ical_invite_link; ?>"><?php echo wp_kses_post( $icalendar_button_text ); ?></button>
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
		echo wp_kses(
			apply_filters(
				'ersrv_reservations_calendar_container_html',
				$reservations_calendar_container,
				$google_calendar_button_text,
				$icalendar_button_text,
				$wc_order
			),
			array(
				'div'    => array(
					'class'    => array(),
					'data-oid' => array(),
				),
				'button' => array(
					'type'      => array(),
					'class'     => array(),
					'data-goto' => array(),
				),
			)
		);
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

		/**
		 * Generate the download reservation receipt button.
		 * Check if the order has reservation items.
		 */
		$wc_order              = wc_get_order( $order_id );
		$is_reservation_order  = ersrv_order_is_reservation( $wc_order );

		// Return the actions if the order is not reservation order.
		if ( ! $is_reservation_order ) {
			return;
		}

		// Check if the order status is allowed for receipts.
		$display_order_receipt = ersrv_should_display_receipt_button( $order_id );

		// Return the actions if the receipt button should not be displayed.
		if ( false === $display_order_receipt ) {
			return;
		}

		$button_text  = ersrv_get_plugin_settings( 'ersrv_easy_reservations_receipt_button_text' );
		$button_url   = ersrv_download_reservation_receipt_url( $order_id );
		$button_title = ersrv_download_reservation_receipt_button_title( $order_id );

		// Show the button now.
		ob_start();
		?>
		<a href="<?php echo esc_url( $button_url ); ?>" class="button" title="<?php echo esc_html( $button_title ); ?>"><?php echo esc_html( $button_text ); ?></a>
		<?php
		echo wp_kses_post( ob_get_clean() );
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
		$blocked_dates = ersrv_get_reservation_item_blockout_dates( $item_id );

		// If doing AJAX.
		if ( DOING_AJAX ) {
			$response = array(
				'code'      => 'unavailability-dates-fetched',
				'dates'     => $blocked_dates,
				'item_link' => get_permalink( $item_id ),
			);
			wp_send_json_success( $response );
			wp_die();
		}

		// Return the dates, otherewise.
		return $blocked_dates;
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
		$item_to_exclude_key = array_search( $post_id, $related_post_ids, true );
		if ( false !== $item_to_exclude_key ) {
			unset( $related_post_ids[ $item_to_exclude_key ] );
		}

		return $related_post_ids;
	}

	/**
	 * Override the WooCommerce single product page.
	 *
	 * @param string $template Holds the template path.
	 * @return string
	 * @since 1.0.0
	 */
	public function ersrv_template_include_callback( $template ) {
		// Override the product single page.
		if ( ersrv_product_is_reservation( get_the_ID() ) ) {
			$template = ERSRV_PLUGIN_PATH . 'public/templates/woocommerce/single-product.php';
		}

		return $template;
	}

	/**
	 * Search reservations callback.
	 *
	 * @param array $args Holds the shortcode arguments.
	 * @return string
	 * @since 1.0.0
	 */
	public function ersrv_ersrv_search_reservations_callback( $args = array() ) {
		// Return, if it's admin panel.
		if ( is_admin() ) {
			return;
		}

		ob_start();
		require_once ERSRV_PLUGIN_PATH . 'public/templates/shortcodes/search-reservations.php';
		return ob_get_clean();
	}

	/**
	 * Setup the cron to delete the pdf files from the uploads folder.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_ersrv_delete_reservation_pdf_receipts_callback() {
		$wp_upload_dir = wp_upload_dir();
		$attach_path   = $wp_upload_dir['basedir'] . '/wc-logs/';
		$pdfs          = glob( $wp_upload_dir['basedir'] . '/wc-logs/ersrv-reservation-receipt-*.pdf' );

		// Return, if there are no PDFs generated.
		if ( empty( $pdfs ) ) {
			return;
		}

		// Loop in through the files to unlink each of them.
		foreach ( $pdfs as $pdf ) {
			unlink( $pdf );
		}
	}

	/**
	 * Hook the receipt option in order listing page on customer's my account.
	 *
	 * @param array    $actions Holds the array of order actions.
	 * @param WC_Order $wc_order Holds the WooCommerce order object.
	 * @return array
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_my_account_my_orders_actions_callback( $actions, $wc_order ) {
		$order_id = $wc_order->get_id();
		
		// Check if the order has reservation items.
		$is_reservation_order  = ersrv_order_is_reservation( $wc_order );

		// Return the actions if the order is not reservation order.
		if ( ! $is_reservation_order ) {
			return $actions;
		}

		// Check if the order status is allowed for receipts.
		$display_order_receipt = ersrv_should_display_receipt_button( $order_id );

		// Return the actions if the receipt button should not be displayed.
		if ( false === $display_order_receipt ) {
			return $actions;
		}

		// Add the action.
		$actions['ersrv-reservation-receipt'] = array(
			'url'  => ersrv_download_reservation_receipt_url( $order_id ),
			'name' => ersrv_get_plugin_settings( 'ersrv_easy_reservations_receipt_button_text' ),
		);

		return $actions;
	}

	/**
	 * Add custom action after the order details table.
	 *
	 * @param object $order Holds the WC order object.
	 * @return void
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_order_details_after_order_table_callback( $wc_order ) {
		$order_id = $wc_order->get_id();
		
		// Check if the order has reservation items.
		$is_reservation_order  = ersrv_order_is_reservation( $wc_order );

		// Return the actions if the order is not reservation order.
		if ( ! $is_reservation_order ) {
			return;
		}

		// Check if the order status is allowed for receipts.
		$display_order_receipt = ersrv_should_display_receipt_button( $order_id );

		// Return the actions if the receipt button should not be displayed.
		if ( false === $display_order_receipt ) {
			return;
		}

		$button_text  = ersrv_get_plugin_settings( 'ersrv_easy_reservations_receipt_button_text' );
		$button_url   = ersrv_download_reservation_receipt_url( $order_id );
		$button_title = ersrv_download_reservation_receipt_button_title( $order_id );
		?>
		<p class="ersrv-reservation-receipt-container">
			<a href="<?php echo esc_url( $button_url ); ?>" class="button" title="<?php echo esc_html( $button_title ); ?>"><?php echo esc_html( $button_text ); ?></a>
		</p>
		<?php
	}

	/**
	 * Modify the billing address to add email and phone number to the billing address.
	 *
	 * @param string $address Holds the billing address.
	 * @param array  $raw_address Holds the billing address in an array.
	 * @return string
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_order_get_formatted_billing_address_callback( $address, $raw_address ) {
		$address .= ( ! empty( $raw_address['email'] ) ) ? '<br />' . $raw_address['email'] : '';
		$address .= ( ! empty( $raw_address['phone'] ) ) ? '<br />' . $raw_address['phone'] : '';

		return $address;
	}

	/**
	 * Add metabox on the dokan view order page so get the receipt.
	 *
	 * @param WC_Order $wc_order Holds the WooCommerce order object.
	 */
	public function wpir_dokan_order_detail_after_order_items_callback( $wc_order ) {
		$order_id = $wc_order->get_id();
		
		// Check if the order has reservation items.
		$is_reservation_order  = ersrv_order_is_reservation( $wc_order );

		// Return the actions if the order is not reservation order.
		if ( ! $is_reservation_order ) {
			return;
		}

		// Check if the order status is allowed for receipts.
		$display_order_receipt = ersrv_should_display_receipt_button( $order_id );

		// Return the actions if the receipt button should not be displayed.
		if ( false === $display_order_receipt ) {
			return;
		}

		$button_text  = ersrv_get_plugin_settings( 'ersrv_easy_reservations_receipt_button_text' );
		$button_url   = ersrv_download_reservation_receipt_url( $order_id );
		$button_title = ersrv_download_reservation_receipt_button_title( $order_id );
		?>
		<div class="dokan-order-receipt">
			<div class="dokan-panel dokan-panel-default">
				<div class="dokan-panel-heading"><strong><?php esc_html_e( 'Receipt', 'easy-reservations' ); ?></strong></div>
				<div class="dokan-panel-body">
				<a href="<?php echo esc_url( $button_url ); ?>" class="button dokan-btn" title="<?php echo esc_html( $button_title ); ?>"><?php echo esc_html( $button_text ); ?></a>
				</div>
			</div>
		</div>
		<?php
	}
}
