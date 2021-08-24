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
	 * My account custom endpoint slug - favourite reservation items.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string $favourite_reservation_items_endpoint_slug Favourite reservations items endpoint slug.
	 */
	private $favourite_reservation_items_endpoint_slug;

	/**
	 * My account custom endpoint label - favourite reservation items.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string $favourite_reservation_items_endpoint_label Favourite reservations items endpoint label.
	 */
	private $favourite_reservation_items_endpoint_label;

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

		// Favourite reservation items endpoint slug - woocommerce my account page.
		$this->favourite_reservation_items_endpoint_slug = ersrv_get_account_endpoint_favourite_reservations();

		// Favourite reservation items endpoint label - woocommerce my account page.
		$this->favourite_reservation_items_endpoint_label = ersrv_get_account_endpoint_label_favourite_reservations();
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function ersrv_wp_enqueue_scripts_callback() {
		global $wp_registered_widgets, $post, $wp_query;
		// Active style file based on the active theme.
		$current_theme            = get_option( 'stylesheet' );
		$active_style             = ersrv_get_active_stylesheet( $current_theme );
		$active_style_url         = ( ! empty( $active_style['url'] ) ) ? $active_style['url'] : '';
		$active_style_path        = ( ! empty( $active_style['path'] ) ) ? $active_style['path'] : '';
		$is_search_page           = ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'ersrv_search_reservations' ) );
		$is_edit_reservation_page = ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'ersrv_edit_reservation' ) );
		$is_reservation_page      = ersrv_product_is_reservation( get_the_ID() );
		$enqueue_extra_css        = false;
		$is_fav_items_endpoint    = isset( $wp_query->query_vars[ $this->favourite_reservation_items_endpoint_slug ] );
		$is_view_order_endpoint   = isset( $wp_query->query_vars[ 'view-order' ] );

		// Conditions to enqueue the extra css file.
		if (
			is_cart() ||
			is_checkout() ||
			$is_fav_items_endpoint ||
			$is_view_order_endpoint
		) {
			$enqueue_extra_css = true;
		}

		/* ---------------------------------------STYLES--------------------------------------- */

		// If it's only the search page.
		if ( $is_search_page ) {
			// Enqueue the slick slider style.
			wp_enqueue_style(
				$this->plugin_name . '-slick-slider-style',
				ERSRV_PLUGIN_URL . 'public/css/slick/slick.min.css',
				array(),
				filemtime( ERSRV_PLUGIN_PATH . 'public/css/slick/slick.min.css' )
			);
		}

		// If it's the single reservation page or the search page.
		if ( $is_reservation_page || $is_search_page || $is_edit_reservation_page ) {
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
			wp_enqueue_style(
				$this->plugin_name . '-font-awesome-style',
				'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.13.1/css/all.css',
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

			// Enqueue the modal public style.
			wp_enqueue_style(
				$this->plugin_name . '-modal',
				ERSRV_PLUGIN_URL . 'public/css/core/easy-reservations-modal.css',
				array(),
				filemtime( ERSRV_PLUGIN_PATH . 'public/css/core/easy-reservations-modal.css' )
			);

			// Enqueue the common public style.
			wp_enqueue_style(
				$this->plugin_name . '-common',
				ERSRV_PLUGIN_URL . 'public/css/core/easy-reservations-common.css',
				array(),
				filemtime( ERSRV_PLUGIN_PATH . 'public/css/core/easy-reservations-common.css' )
			);
		}

		// Add the UI style only when the widget is active.
		if ( false !== $this->is_calendar_widget_active ) {
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

		// Check, if the extra css file is to be enqueued.
		if ( $enqueue_extra_css ) {
			// Enqueue the common public style.
			wp_enqueue_style(
				$this->plugin_name . '-extra',
				ERSRV_PLUGIN_URL . 'public/css/core/easy-reservations-extra.css',
				array(),
				filemtime( ERSRV_PLUGIN_PATH . 'public/css/core/easy-reservations-extra.css' )
			);
		}

		/* ---------------------------------------SCRIPTS--------------------------------------- */

		// If it's only the search page.
		if ( $is_search_page ) {
			// Enqueue the slick slider script.
			wp_enqueue_script(
				$this->plugin_name . '-slick-slider-script',
				ERSRV_PLUGIN_URL . 'public/js/slick/slick.min.js',
				array(),
				filemtime( ERSRV_PLUGIN_PATH . 'public/js/slick/slick.min.js' ),
				true
			);
		}

		// If it's the single reservation page or the search page.
		if ( $is_reservation_page || $is_search_page || $is_edit_reservation_page ) {
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
				filemtime( ERSRV_PLUGIN_PATH . 'public/js/ui/jquery-ui.min.js' ),
				true
			);

			// Custom public script.
			self::ersrv_enqueue_plugin_core_js( $this->plugin_name );
		} elseif ( is_checkout() ) {
			self::ersrv_enqueue_plugin_core_js( $this->plugin_name );
		} elseif ( $is_view_order_endpoint ) {
			self::ersrv_enqueue_plugin_core_js( $this->plugin_name );
		}

		// Add the datepicker and custom script only when the widget is active.
		if ( false !== $this->is_calendar_widget_active ) {
			// Enqueue the ui datepicker script if not already enqueued.
			if ( ! wp_script_is( $this->plugin_name . '-jquery-ui-script', 'enqueued' ) ) {
				wp_enqueue_script(
					$this->plugin_name . '-jquery-ui-script',
					ERSRV_PLUGIN_URL . 'public/js/ui/jquery-ui.min.js',
					array( 'jquery' ),
					filemtime( ERSRV_PLUGIN_PATH . 'public/js/ui/jquery-ui.min.js' ),
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
					'date_format'   => ersrv_get_plugin_settings( 'ersrv_datepicker_date_format' ),
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
		global $wp_registered_widgets, $post, $wp_query;
		$is_search_page         = ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'ersrv_search_reservations' ) );
		$is_reservation_page    = ersrv_product_is_reservation( get_the_ID() );
		$is_view_order_endpoint = isset( $wp_query->query_vars[ 'view-order' ] );

		$reservation_item_details = ( ersrv_product_is_reservation( get_the_ID() ) ) ? ersrv_get_item_details( get_the_ID() ) : array();
		$search_reservations_page = ersrv_get_page_id( 'search-reservations' );
		// Custom public script.
		wp_enqueue_script(
			$plugin_name,
			ERSRV_PLUGIN_URL . 'public/js/core/easy-reservations-public.js',
			array( 'jquery' ),
			filemtime( ERSRV_PLUGIN_PATH . 'public/js/core/easy-reservations-public.js' ),
			true
		);

		// Driving license file allowed extensions.
		$driving_license_allowed_extensions = array( 'jpeg', 'jpg', 'pdf', 'png' );

		// Localized variables.
		$localized_vars = array(
			'ajaxurl'                                      => admin_url( 'admin-ajax.php' ),
			'remove_sidebar'                               => ersrv_get_plugin_settings( 'ersrv_remove_reservation_pages_sidebar' ),
			'is_product'                                   => ( is_product() ) ? 'yes' : 'no',
			'is_checkout'                                  => ( is_checkout() ) ? 'yes' : 'no',
			'is_search_page'                               => ( $is_search_page ) ? 'yes' : 'no',
			'reservation_item_details'                     => $reservation_item_details,
			'woo_currency'                                 => get_woocommerce_currency_symbol(),
			'reservation_guests_err_msg'                   => __( 'Please provide the count of guests for the reservation.', 'easy-reservations' ),
			'reservation_only_kids_guests_err_msg'         => __( 'We cannot proceed with only the kids in the reservation.', 'easy-reservations' ),
			'reservation_guests_count_exceeded_err_msg'    => __( 'The guests count is more than the accomodation limit.', 'easy-reservations' ),
			'reservation_checkin_checkout_missing_err_msg' => __( 'Please provide checkin and checkout dates.', 'easy-reservations' ),
			'reservation_checkin_missing_err_msg'          => __( 'Please provide checkin dates.', 'easy-reservations' ),
			'reservation_checkout_missing_err_msg'         => __( 'Please provide checkout dates.', 'easy-reservations' ),
			'reservation_lesser_reservation_days_err_msg'  => __( 'The item can be reserved for a min. of XX days.', 'easy-reservations' ),
			'reservation_greater_reservation_days_err_msg' => __( 'The item can be reserved for a max. of XX days.', 'easy-reservations' ),
			'reservation_blocked_dates_err_msg'            => __( 'The dates you have selected for reservation contain the dates that are already reserved. Kindly check the availability on the left hand side and then proceed with the reservation.', 'easy-reservations' ),
			'search_reservations_page_url'                 => get_permalink( $search_reservations_page ),
			'date_format'                                  => ersrv_get_plugin_settings( 'ersrv_datepicker_date_format' ),
			'toast_success_heading'                        => __( 'Ohhoooo! Success..', 'easy-reservations' ),
			'toast_error_heading'                          => __( 'Ooops! Error..', 'easy-reservations' ),
			'toast_notice_heading'                         => __( 'Notice.', 'easy-reservations' ),
			'invalid_reservation_item_is_error_text'       => __( 'Invalid item ID.', 'easy-reservations' ),
			'reservation_add_to_cart_error_message'        => __( 'There are a few errors that need to be addressed.', 'easy-reservations' ),
			'reservation_item_contact_owner_error_message' => __( 'There is some issue contacting the owner. Please see the errors above and try again.', 'easy-reservations' ),
			'driving_license_allowed_extensions'           => $driving_license_allowed_extensions,
			'driving_license_invalid_file_error'           => sprintf( __( 'Invalid file selected. Allowed extensions are: %1$s', 'easy-reservations' ), implode( ', ', $driving_license_allowed_extensions ) ),
			'driving_license_empty_file_error'             => __( 'Please provide a driving license to upload.', 'easy-reservations' ),
			'cancel_reservation_confirmation_message'      => __( 'Click OK to confirm your cancellation. This action won\'t be undone.', 'easy-reservations' ),
		);

		/**
		 * This hook fires in public panel.
		 *
		 * This filter helps in modifying the script variables in public.
		 *
		 * @param array $localized_vars Script variables.
		 * @return array
		 * @since 1.0.0
		 */
		$localized_vars = apply_filters( 'ersrv_public_script_vars', $localized_vars );

		// Localize script.
		wp_localize_script( $plugin_name, 'ERSRV_Public_Script_Vars', $localized_vars );
	}

	/**
	 * Do the following when WordPress initiates.
	 * 1. Register custom product type in WooCommerce Products.
	 *
	 * @since    1.0.0
	 */
	public function ersrv_init_callback() {
		// Check if the action is required to download iCalendar invite.
		$action = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );
		// Include the product type custom class.
		require ERSRV_PLUGIN_PATH . 'includes/classes/class-wc-product-reservation.php';

		// Add the custom rewrite for my account endpoints.
		add_rewrite_endpoint( $this->favourite_reservation_items_endpoint_slug, EP_ROOT | EP_PAGES );
		$rewrite_fav_items_endpoint = get_option( 'ersrv_rewrite_fav_items_endpoint_permalink' );
        if ( 'yes' !== $rewrite_fav_items_endpoint ) {
            flush_rewrite_rules( false );
            update_option( 'ersrv_rewrite_fav_items_endpoint_permalink', 'yes', false );
        }

		// Register reservation item type taxonomy.
		ersrv_register_reservation_type_taxonomy();

		// If it's the download reservation receipt request.
		if ( ! is_null( $action ) && 'ersrv-download-reservation-receipt' === $action ) {
			$order_id = (int) filter_input( INPUT_GET, 'atts', FILTER_SANITIZE_NUMBER_INT );
			if ( ! is_null( $order_id ) || 0 !== $order_id ) {
				ersrv_download_reservation_receipt_callback( $order_id );
			}
		}
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
	 * Customizations on thank you page.
	 * Send the email to multiple administrators set in plugin settings.
	 *
	 * @param int $order_id WooCommerce Order ID.
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_thankyou_callback( $order_id ) {
		/**
		 * Generate the download reservation receipt button.
		 * Check if the order has reservation items.
		 */
		$wc_order             = wc_get_order( $order_id );
		$is_reservation_order = ersrv_order_is_reservation( $wc_order );

		// Return the actions if the order is not reservation order.
		if ( ! $is_reservation_order ) {
			return;
		}

		// Update order meta to be a reservation order.
		update_post_meta( $order_id, 'ersrv_reservation_order', 1 );

		// Block the dates after reservation is successfully filed by the customer.
		ersrv_block_dates_after_reservation_thankyou( $wc_order );
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
		$page      = (int) filter_input( INPUT_POST, 'page', FILTER_SANITIZE_NUMBER_INT );
		$page      = ( ! empty( $page ) ) ? $page : 1;

		// If the post type is available.
		if ( ! empty( $post_type ) ) {
			// Set the taxonomy args for woocommerce products.
			if ( 'product' === $post_type ) {
				$args['tax_query'][] = array(
					'taxonomy' => 'product_type',
					'field'    => 'slug',
					'terms'    => $this->custom_product_type,
				);
			} elseif ( 'shop_order' === $post_type ) {
				$args['meta_query'][] = array(
					'key'     => 'ersrv_reservation_order',
					'value'   => '1',
					'compare' => '=',
				);

				// Update the post status.
				$args['post_status'] = array(
					'wc-processing',
					'wc-pending',
				);
			}
		}

		// If the page is available.
		if ( ! empty( $page ) ) {
			$args['paged'] = $page;
		}

		return $args;
	}

	/**
	 * Get the unavailability dates of particular item.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_get_item_unavailable_dates_callback() {
		$action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );

		// Check if action mismatches.
		if ( empty( $action ) || 'get_item_unavailable_dates' !== $action ) {
			echo 0;
			wp_die();
		}

		// Posted data.
		$item_id = (int) filter_input( INPUT_POST, 'item_id', FILTER_SANITIZE_NUMBER_INT );

		// Now that we have item ID, get the unavailability dates.
		$reserved_dates = get_post_meta( $item_id, '_ersrv_reservation_blockout_dates', true );
		$reserved_dates = ( ! empty( $reserved_dates ) && is_array( $reserved_dates ) ) ? $reserved_dates : array();

		// Return the AJAX response.
		$response = array(
			'code'      => 'unavailability-dates-fetched',
			'dates'     => $reserved_dates,
			'item_link' => get_permalink( $item_id ),
		);
		wp_send_json_success( $response );
		wp_die();
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
	 * Setup the cron to send reservation reminder notifications to the customers.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_ersrv_reservation_reminder_email_notifications_callback() {
		// Send reminder emails to the customers.
		$this->ersrv_send_reminder_emails();
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
		// Return, if this is order received endpoint.
		if ( is_wc_endpoint_url( 'order-received' ) ) {
			return;
		}

		// Get the WooCommerce order ID.
		$order_id = $wc_order->get_id();

		// Should the calendar buttons be added.
		$is_reservation_order = ersrv_order_is_reservation( $wc_order );

		// Return, if the order is not reservation.
		if ( ! $is_reservation_order ) {
			return;
		}

		// Print the reservation custom actions now.
		?>
		<div class="ersrv-reservation-actions-container">
			<h2 class="woocommerce-column__title"><?php esc_html_e( 'Easy Reservations: Actions', 'easy-reservations' ); ?></h2>
			<div class="actions">
				<?php
				ersrv_print_calendar_buttons( $order_id, $wc_order ); // Print the calendar button.
				ersrv_print_receipt_button( $order_id, $wc_order ); // Print the receipt button.
				ersrv_print_edit_reservation_button( $order_id, $wc_order ); // Print the edit reservation button.
				?>
			</div>
		</div>
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
	public function ersrv_dokan_order_detail_after_order_items_callback( $wc_order ) {
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

	/**
	 * AJAX to get reservation items on search page.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_get_reservation_items_callback() {
		$action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );

		// Exit, if the action mismatches.
		if ( empty( $action ) || 'get_reservation_items' !== $action ) {
			echo 0;
			wp_die();
		}

		// Get the items now.
		$reservation_items_query = ersrv_get_posts( 'product' );
		$reservation_item_ids    = $reservation_items_query->posts;

		// Return the response if there are no items available.
		if ( empty( $reservation_item_ids ) || ! is_array( $reservation_item_ids ) ) {
			wp_send_json_success(
				array(
					'code' => 'no-items-found'
				)
			);
			wp_die();
		}

		// Iterate through the item IDs to generate the HTML.
		$html = '';
		foreach ( $reservation_item_ids as $item_id ) {
			$html .= ersrv_get_reservation_item_block_html( $item_id );
		}

		// Send the response.
		wp_send_json_success(
			array(
				'code' => 'items-found',
				'html' => $html,
			)
		);
		wp_die();
	}

	/**
	 * Add custom assets to footer section.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_wp_footer_callback() {
		global $post, $wp_query;
		$is_search_page         = ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'ersrv_search_reservations' ) );
		$is_reservation_page    = ersrv_product_is_reservation( $post->ID );
		$is_view_order_endpoint = isset( $wp_query->query_vars[ 'view-order' ] );

		// If it's the single reservation page or the search page.
		if ( $is_search_page ) {
			// Include the quick view modal.
			require_once ERSRV_PLUGIN_PATH . 'public/templates/modals/item-quick-view.php';
		}

		// If it's the reservation page.
		if ( $is_reservation_page && is_product() ) {
			// Include the quick view modal.
			require_once ERSRV_PLUGIN_PATH . 'public/templates/modals/contact-owner.php';
		}

		// If it's the view order page.
		if (
			( ! is_shop() ) &&
			(
				$is_search_page ||
				$is_view_order_endpoint ||
				$is_reservation_page ||
				is_checkout()
			)
		) {
			// Include the notification html.
			require_once ERSRV_PLUGIN_PATH . 'public/templates/notifications/notification.php';
		}
	}

	/**
	 * AJAX to mark the item as favourite.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_item_favourite_callback() {
		$action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );

		// Exit, if the action mismatches.
		if ( empty( $action ) || 'item_favourite' !== $action ) {
			echo 0;
			wp_die();
		}

		// Posted data.
		$item_id         = (int) filter_input( INPUT_POST, 'item_id', FILTER_SANITIZE_NUMBER_INT );
		$do_what         = filter_input( INPUT_POST, 'do', FILTER_SANITIZE_STRING );
		$user_id         = get_current_user_id();
		$favourite_items = get_user_meta( $user_id, 'ersrv_favourite_items', true );
		$favourite_items = ( empty( $favourite_items ) || ! is_array( $favourite_items ) ) ? array() : $favourite_items;

		if ( 'mark_fav' === $do_what ) {
			// Push in the item now.
			$favourite_items[] = $item_id;
			$toast_message     = __( 'Item has been marked favourite.', 'easy-reservations' );
		} elseif ( 'unmark_fav' === $do_what ) {
			// Remove the item from favourite list.
			$item_index = array_search( $item_id, $favourite_items, true );

			if ( false !== $item_index ) {
				unset( $favourite_items[ $item_index ] );
			}

			$toast_message = __( 'Item has been unmarked favourite.', 'easy-reservations' );
		}

		// Update the database.
		update_user_meta( $user_id, 'ersrv_favourite_items', $favourite_items );

		// Send the response.
		wp_send_json_success(
			array(
				'code'          => 'item-favourite-done',
				'toast_message' => $toast_message,
			)
		);
		wp_die();
	}

	/**
	 * Add custom endpoint on custmer's account page for managing favourite reservatin items.
	 *
	 * @param array $endpoints Endpoints array.
	 * @return array
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_account_menu_items_callback( $endpoints ) {
		// Check if the custom endpoint already exists.
		if ( array_key_exists( $this->favourite_reservation_items_endpoint_slug, $endpoints ) ) {
			return $endpoints;
		}

		// Add the custom endpoint now.
		$endpoints[ $this->favourite_reservation_items_endpoint_slug ] = $this->favourite_reservation_items_endpoint_label;

		return $endpoints;
	}

	/**
	 * Favourite reservation items content on my account.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_account_fav_items_endpoint_endpoint_callback() {
		// Include the file to manage the endpoint content.
		require ERSRV_PLUGIN_PATH . 'public/templates/woocommerce/favourite-reservation-items.php';
	}

	public function ersrv_query_vars_callback( $vars ) {
		$vars[] = $this->favourite_reservation_items_endpoint_slug;

		return $vars;
	}

	public function ersrv_the_title_callback( $title ) {
		global $wp_query;
		$is_endpoint = isset( $wp_query->query_vars[ $this->favourite_reservation_items_endpoint_slug ] );

		if ( $is_endpoint && ! is_admin() && is_main_query() && in_the_loop() && is_account_page() ) {
			$title = __( 'My Stuff', 'easy-reservations' );
		}

		return $title;
	}

	/**
	 * AJAX to load more reservation items on search page.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_loadmore_reservation_items_callback() {
		$action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );

		// Exit, if the action mismatches.
		if ( empty( $action ) || 'loadmore_reservation_items' !== $action ) {
			echo 0;
			wp_die();
		}

		// Get the items now.
		$reservation_items_query = ersrv_get_posts( 'product' );
		$reservation_item_ids    = $reservation_items_query->posts;

		// Return the response if there are no items available.
		if ( empty( $reservation_item_ids ) || ! is_array( $reservation_item_ids ) ) {
			wp_send_json_success(
				array(
					'code' => 'no-items-found',
				)
			);
			wp_die();
		}

		// Iterate through the item IDs to generate the HTML.
		$html = '';
		foreach ( $reservation_item_ids as $item_id ) {
			$html .= ersrv_get_reservation_item_block_html( $item_id );
		}

		// Send the response.
		wp_send_json_success(
			array(
				'code' => 'items-found',
				'html' => $html,
			)
		);
		wp_die();
	}

	/**
	 * AJAX to create new reservation.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_add_reservation_to_cart_callback() {
		$action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );

		// Exit, if the action mismatches.
		if ( empty( $action ) || 'add_reservation_to_cart' !== $action ) {
			echo 0;
			wp_die();
		}

		// Posted data.
		$item_id            = filter_input( INPUT_POST, 'item_id', FILTER_SANITIZE_NUMBER_INT );
		$checkin_date       = filter_input( INPUT_POST, 'checkin_date', FILTER_SANITIZE_STRING );
		$checkout_date      = filter_input( INPUT_POST, 'checkout_date', FILTER_SANITIZE_STRING );
		$adult_count        = filter_input( INPUT_POST, 'adult_count', FILTER_SANITIZE_NUMBER_INT );
		$kid_count          = filter_input( INPUT_POST, 'kid_count', FILTER_SANITIZE_NUMBER_INT );
		$posted_array       = filter_input_array( INPUT_POST );
		$amenities          = ( ! empty( $posted_array['amenities'] ) ) ? $posted_array['amenities'] : array();
		$item_subtotal      = (float) filter_input( INPUT_POST, 'item_subtotal', FILTER_SANITIZE_STRING );
		$kids_subtotal      = (float) filter_input( INPUT_POST, 'kids_subtotal', FILTER_SANITIZE_STRING );
		$security_subtotal  = (float) filter_input( INPUT_POST, 'security_subtotal', FILTER_SANITIZE_STRING );
		$amenities_subtotal = (float) filter_input( INPUT_POST, 'amenities_subtotal', FILTER_SANITIZE_STRING );
		$item_total         = (float) filter_input( INPUT_POST, 'item_total', FILTER_SANITIZE_STRING );

		/**
		 * This hook fires before the reservation item is added to the cart.
		 *
		 * This hook helps in adding actions before any reservation item is added to the cart.
		 */
		do_action( 'ersrv_add_reservation_to_cart_before' );

		// Prepare an array of all the posted data.
		$reservation_data = array(
			'item_id'         => $item_id,
			'checkin_date'    => $checkin_date,
			'checkout_date'   => $checkout_date,
			'adult_count'     => $adult_count,
			'adult_subtotal'  => $item_subtotal,
			'kid_count'       => $kid_count,
			'kid_subtotal'    => $kids_subtotal,
			'security_amount' => $security_subtotal,
			'item_total'      => $item_total,
		);

		// Iterate through the amenities array to add them to session.
		if ( ! empty( $amenities ) && is_array( $amenities ) ) {
			$reservation_data['amenities']          = $amenities;
			$reservation_data['amenities_subtotal'] = $amenities_subtotal;
		}

		// Add all the posted data in the session.
		WC()->session->set( 'reservation_data', $reservation_data );

		// Add the reservation item to the cart now.
		WC()->cart->add_to_cart( $item_id, 1 );

		/**
		 * This hook fires after the reservation item is added to the cart.
		 *
		 * This hook helps in adding actions after any reservation item is added to the cart.
		 */
		do_action( 'ersrv_add_reservation_to_cart_after' );

		// Prepare the response.
		$response = array(
			'code'          => 'reservation-added-to-cart',
			'toast_message' => sprintf( __( 'Reservation has been added to the cart. %1$sView Cart%2$s', 'easy-reservations' ), '<a title="' . __( 'View Cart', 'easy-reservations' ) . '" href="' . wc_get_cart_url() . '">', '</a>' ),
		);
		wp_send_json_success( $response );
		wp_die();
	}

	/**
	 * AJAX request to submit the request for contacting owner.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_submit_contact_owner_request_callback() {
		$action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );

		// Exit, if the action mismatches.
		if ( empty( $action ) || 'submit_contact_owner_request' !== $action ) {
			echo 0;
			wp_die();
		}

		// Posted data.
		$item_id = (int) filter_input( INPUT_POST, 'item_id', FILTER_SANITIZE_NUMBER_INT );

		// Get the item author.
		$item_author_id = (int) get_post_field( 'post_author', $item_id );

		// Get the author object.
		$item_author = get_userdata( $item_author_id );

		// Get the author email.
		$item_author_email = $item_author->data->user_email;

		/**
		 * This hook fires for sending email for reservation item contact requests.
		 *
		 * This hook helps in adding actions during any contact owner request is saved.
		 *
		 * @param string $item_author_email Author email address.
		 * @since 1.0.0
		 */
		do_action( 'ersrv_email_contact_owner_request', $item_author_email );

		// Prepare the response.
		$response = array(
			'code'          => 'contact-owner-request-saved',
			'toast_message' => __( 'Contact request is saved successfully. One of our teammates will get back to you soon.', 'easy-reservations' ),
		);
		wp_send_json_success( $response );
		wp_die();
	}

	/**
	 * Initiate the woocommerce customer session when the user is a non-loggedin user.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_init_callback() {
		// Return, if it's admin.
		if ( is_admin() ) {
			return;
		}

		// Set the session, if there is no session initiated.
		if ( isset( WC()->session ) && ! WC()->session->has_session() ) {
			WC()->session->set_customer_session_cookie( true );
		}
	}

	/**
	 * Add the reservation data to the woocommerce cart item data.
	 *
	 * @param array $cart_item_data WooCommerce cart item data.
	 * @param int   $product_id WooCommerce product ID.
	 * @return array
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_add_cart_item_data_callback( $cart_item_data, $product_id ) {
		$session_reservation_data = WC()->session->get( 'reservation_data' );
		$session_reservation_item = ( ! empty( $session_reservation_data['item_id'] ) ) ? (int) $session_reservation_data['item_id'] : '';

		// Return, if the session item ID is empty.
		if ( empty( $session_reservation_item ) ) {
			return $cart_item_data;
		}

		// Check if the item ID matches with the product ID that is being added to the cart.
		if ( $session_reservation_item === $product_id ) {
			$cart_item_data['reservation_data'] = $session_reservation_data;
		}

		return $cart_item_data;
	}

	/**
	 * Calculate the cart item subtotal for reservation items.
	 *
	 * @param array $cart_obj Holds the cart contents.
	 * @return void
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_before_calculate_totals_callback( $cart_obj ) {
		// Iterate through the cart items to set the price.
		foreach ( $cart_obj->get_cart() as $cart_item ) {
			$reservation_data = ( ! empty( $cart_item['reservation_data'] ) ) ? $cart_item['reservation_data'] : array();

			// Skip, if the cart item has this data.
			if ( empty( $reservation_data ) ) {
				continue;
			}

			// Item total cost.
			$reservation_item_total = ( ! empty( $reservation_data['item_total'] ) ) ? (float) $reservation_data['item_total'] : 0;
			$product_id             = $cart_item['product_id'];
			$reservation_data_item  = ( ! empty( $reservation_data['item_id'] ) ) ? (int) $reservation_data['item_id'] : 0;

			if ( ! empty( $reservation_data_item ) && $reservation_data_item === $product_id ) {
				$cart_item['data']->set_price( $reservation_data['item_total'] );
			}
		}
	}

	/**
	 * Add custom data to the cart item data.
	 *
	 * @param array $item_data Holds the item data.
	 * @param array $cart_item_data Holds the cart item data.
	 * @return array
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_get_item_data_callback( $item_data, $cart_item_data ) {
		// Return, if the reservation data is not set in the cart.
		if ( ! isset( $cart_item_data['reservation_data'] ) || empty( $cart_item_data['reservation_data'] ) ) {
			return $item_data;
		}

		// Add the checkin date to the cart item.
		$item_data[] = array(
			'key'   => __( 'Checkin Date', 'easy-reservations' ),
			'value' => $cart_item_data['reservation_data']['checkin_date'],
		);

		// Add the checkin date to the cart item.
		$item_data[] = array(
			'key'   => __( 'Checkout Date', 'easy-reservations' ),
			'value' => $cart_item_data['reservation_data']['checkout_date'],
		);

		// Add the adult count to the cart item.
		$item_data[] = array(
			'key'   => __( 'Adult Count', 'easy-reservations' ),
			'value' => $cart_item_data['reservation_data']['adult_count'],
		);

		// Add the adult subtotal to the cart item.
		$item_data[] = array(
			'key'   => __( 'Adult Subtotal', 'easy-reservations' ),
			'value' => wc_price( $cart_item_data['reservation_data']['adult_subtotal'] ),
		);

		// Add the kids count to the cart item.
		$item_data[] = array(
			'key'   => __( 'Kids Count', 'easy-reservations' ),
			'value' => $cart_item_data['reservation_data']['kid_count'],
		);

		// Add the adult subtotal to the cart item.
		$item_data[] = array(
			'key'   => __( 'Kids Subtotal', 'easy-reservations' ),
			'value' => wc_price( $cart_item_data['reservation_data']['kid_subtotal'] ),
		);

		// Add the security subtotal to the cart item.
		$item_data[] = array(
			'key'   => __( 'Security Amount', 'easy-reservations' ),
			'value' => wc_price( $cart_item_data['reservation_data']['security_amount'] ),
		);

		// Check if there are amenities.
		if ( ! empty( $cart_item_data['reservation_data']['amenities'] ) && is_array( $cart_item_data['reservation_data']['amenities'] ) ) {
			foreach ( $cart_item_data['reservation_data']['amenities'] as $amenity_data ) {
				$item_data[] = array(
					'key'   => __( 'Amenity', 'easy-reservations' ),
					'value' => $amenity_data['amenity'] . ' - ' . wc_price( $amenity_data['cost'] ),
				);
			}

			// Add the amenities subtotal to the cart item.
			$item_data[] = array(
				'key'   => __( 'Amenities Subtotal', 'easy-reservations' ),
				'value' => wc_price( $cart_item_data['reservation_data']['amenities_subtotal'] ),
			);
		}

		return apply_filters( 'ersrv_reservation_item_data', $item_data, $cart_item_data );
	}

	/**
	 * Save the reservation data from the cart item as order item meta data.
	 *
	 * @param object   $item WooCommerce order item.
	 * @param string   $cart_item_key WooCommerce cart item key.
	 * @param array    $cart_item_data WooCommerce cart item data.
	 * @param WC_Order $wc_order WooCommerce order
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_checkout_create_order_line_item_callback( $item, $cart_item_key, $cart_item_data, $wc_order ) {
		// Return, if the reservation data is not set in the cart.
		if ( ! isset( $cart_item_data['reservation_data'] ) || empty( $cart_item_data['reservation_data'] ) ) {
			return;
		}

		// Iterate through the amenities array to add them to session.
		if ( ! empty( $amenities ) && is_array( $amenities ) ) {
			$reservation_data['amenities']          = $amenities;
			$reservation_data['amenities_subtotal'] = $amenities_subtotal;
		}

		// Update the other reservation data to order item meta.
		$item->update_meta_data( 'Checkin Date', $cart_item_data['reservation_data']['checkin_date'] ); // Update the checkin date.
		$item->update_meta_data( 'Checkout Date', $cart_item_data['reservation_data']['checkout_date'] ); // Update the checkout date.
		$item->update_meta_data( 'Adult Count', $cart_item_data['reservation_data']['adult_count'] ); // Update the adult count.
		$item->update_meta_data( 'Adult Subtotal', $cart_item_data['reservation_data']['adult_subtotal'] ); // Update the adult subtotal.
		$item->update_meta_data( 'Kids Count', $cart_item_data['reservation_data']['kid_count'] ); // Update the kids count.
		$item->update_meta_data( 'Kids Subtotal', $cart_item_data['reservation_data']['kid_subtotal'] ); // Update the kids subtotal.
		$item->update_meta_data( 'Security Amount', $cart_item_data['reservation_data']['security_amount'] ); // Update the security subtotal.

		// Check if there are amenities.
		if ( ! empty( $cart_item_data['reservation_data']['amenities'] ) && is_array( $cart_item_data['reservation_data']['amenities'] ) ) {
			foreach ( $cart_item_data['reservation_data']['amenities'] as $amenity_data ) {
				$item->update_meta_data( 'Amenity: ' . $amenity_data['amenity'], $amenity_data['cost'] ); // Update the amenity data.
			}

			// Add the amenities subtotal to the item meta.
			$item->update_meta_data( 'Amenities Subtotal', $cart_item_data['reservation_data']['amenities_subtotal'] ); // Update the amenities subtotal.
		}
	}

	/**
	 * Send reminder emails to the customer's about their reservation.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_send_reminder_emails() {
		// Get the woocommerce orders.
		$wc_orders_query                 = ersrv_get_posts( 'shop_order', 1, -1 );
		$wc_order_ids                    = $wc_orders_query->posts;
		$reminder_to_be_sent_before_days = ersrv_get_plugin_settings( 'ersrv_reminder_email_send_before_days' );

		// Return, if the setting is not saved.
		if ( 0 === $reminder_to_be_sent_before_days ) {
			return;
		}

		// Return back, if there are no orders available.
		if ( empty( $wc_order_ids ) || ! is_array( $wc_order_ids ) ) {
			return;
		}

		/**
		 * This filter is fired by the cron.
		 *
		 * This filter helps in managing the array of order ids that are considered for sending reservation reminders.
		 *
		 * @param array $wc_order_ids Array of WooCommerce order IDs.
		 * @return array
		 * @since 1.0.0
		 */
		$wc_order_ids = apply_filters( 'ersrv_reservation_reminder_email_order_ids', $wc_order_ids );

		// Iterate through the orders to send the customers the remonder about their reservation.
		foreach ( $wc_order_ids as $order_id ) {
			$wc_order   = wc_get_order( $order_id );
			$line_items = $wc_order->get_items();

			// Skip, if there are no items.
			if ( empty( $line_items ) || ! is_array( $line_items ) ) {
				continue;
			}

			// Iterate through the items to check if the reminder email can be sent to the customers.
			foreach ( $line_items as $line_item ) {
				$product_id = $line_item->get_product_id();
				$item_id    = $line_item->get_id();

				// Skip, if this is not a reservation item.
				if ( ! ersrv_product_is_reservation( $product_id ) ) {
					continue;
				}

				// Get the checkin date.
				$checkin_date = wc_get_order_item_meta( $item_id, 'Checkin Date', true );
				$date_today   = gmdate( ersrv_get_php_date_format() );

				// Get the days yet to the reservation.
				$dates_range = ersrv_get_dates_within_2_dates( $date_today, $checkin_date );
				$dates       = array();

				// Iterate through the dates.
				if ( ! empty( $dates_range ) ) {
					foreach ( $dates_range as $date ) {
						$dates[] = $date->format( ersrv_get_php_date_format() );
					}
				}
				$days_difference_count = count( $dates );

				// Skip, if this doesn't match with the admin settings.
				if ( $reminder_to_be_sent_before_days !== $days_difference_count ) {
					continue;
				}

				/**
				 * Send the email now.
				 * This action is fired by the cron.
				 *
				 * This action helps in sending the reminder emails to the customers about their reservation.
				 *
				 * @param object $line_item WooCommerce line item object.
				 * @param int    $order_id WooCommerce order ID.
				 */
				do_action( 'ersrv_send_reservation_reminder_email', $line_item, $order_id );
			}
		}
	}

	/**
	 * AJAX request to fetch the quick view modal content.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_quick_view_item_data_callback() {
		$action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );

		// Exit, if the action mismatches.
		if ( empty( $action ) || 'quick_view_item_data' !== $action ) {
			echo 0;
			wp_die();
		}

		// Posted data.
		$item_id                = filter_input( INPUT_POST, 'item_id', FILTER_SANITIZE_NUMBER_INT );
		$item                   = wc_get_product( $item_id );
		$featured_image_id      = $item->get_image_id();
		$featured_image_src     = ersrv_get_attachment_url_from_attachment_id( $featured_image_id );
		$gallery_image_ids      = $item->get_gallery_image_ids();
		$gallery_image_ids      = ( ! empty( $gallery_image_ids ) ) ? array_merge( array( $featured_image_id ), $gallery_image_ids ) : array( $featured_image_id );
		$item_permalink         = get_permalink( $item_id );
		$item_details           = ersrv_get_item_details( $item_id );
		$adult_charge           = ( ! empty( $item_details['adult_charge'] ) ) ? $item_details['adult_charge'] : 0;
		$kid_charge             = ( ! empty( $item_details['kid_charge'] ) ) ? $item_details['kid_charge'] : 0;
		$amenities              = ( ! empty( $item_details['amenities'] ) ) ? $item_details['amenities'] : array();
		$security_amount        = ( ! empty( $item_details['security_amount'] ) ) ? $item_details['security_amount'] : 0;
		$accomodation_limit     = ( ! empty( $item_details['accomodation_limit'] ) ) ? $item_details['accomodation_limit'] : '';
		$min_reservation_period = ( ! empty( $item_details['min_reservation_period'] ) ) ? $item_details['min_reservation_period'] : '';
		$max_reservation_period = ( ! empty( $item_details['max_reservation_period'] ) ) ? $item_details['max_reservation_period'] : '';
		$reserved_dates         = ( ! empty( $item_details['reserved_dates'] ) ) ? $item_details['reserved_dates'] : '';
		$php_date_format        = ersrv_get_php_date_format();
		$curr_date              = ersrv_get_current_date( $php_date_format );
		$next_date              = gmdate( $php_date_format, ( strtotime( 'now' ) + 86400 ) );

		// Prepare the HTML.
		?>
		<div class="quick-row align-items-center">
			<div class="col-12 col-md-6  col-preview">
				<div class="product-preview">
					<div class="product-preview-main">
						<img src="<?php echo esc_url( $featured_image_src ); ?>" alt="featured-image" class="product-preview-image">
					</div>
					<!-- GALLERY IMAGES -->
					<div id="preview-list" class="product-preview-menu">
						<?php if ( ! empty( $gallery_image_ids ) && is_array( $gallery_image_ids ) ) { ?>
							<?php foreach ( $gallery_image_ids as $image_id ) {
								$image_src = ersrv_get_attachment_url_from_attachment_id( $image_id );
								?>
								<div class="product-preview-thumb">
									<img src="<?php echo esc_url( $image_src ); ?>" alt="gallery-image" class="product-preview-thumb-image">
								</div>
							<?php } ?>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="col-12 col-md-6  col-product">
				<div class="product-details">
					<h2 class="product-title font-weight-semibold font-size-30"><?php echo wp_kses_post( $item->get_title() ); ?></h2>
					<div class="product-price-meta mb-1">
						<h4 class="font-size-30 price">
							<?php
							echo wp_kses(
								wc_price( $adult_charge ),
								array(
									'span' => array(
										'class' => array(),
									),
								)
							);
							?>
							<span class="font-size-20 price-text"><?php esc_html_e( 'Per Night', 'easy-reservations' ); ?></span>
						</h4>
					</div>
					<div class="product-details-values mb-2">
						<div class="check-in-out-values d-flex flex-column mb-3">
							<div class="values">
								<div class="row form-row input-daterange">
									<div class="col-6">
										<h4 class="font-weight-semibold font-size-20"><?php esc_html_e( 'Check In', 'easy-reservations' ); ?></h4>
										<div><input type="text" id="ersrv-quick-view-item-checkin-date" class="form-control date-control text-left rounded-lg" placeholder="<?php echo esc_html( $curr_date ); ?>"></div>
									</div>
									<div class="col-6">
										<h4 class="font-weight-semibold font-size-20"><?php esc_html_e( 'Check Out', 'easy-reservations' ); ?></h4>
										<div><input type="text" id="ersrv-quick-view-item-checkout-date" class="form-control date-control text-left rounded-lg" placeholder="<?php echo esc_html( $next_date ); ?>"></div>
									</div>
									<label class="ersrv-reservation-error checkin-checkout-dates-error"></label>
								</div>
							</div>
						</div>
						<div class="accomodation-values d-flex flex-column mb-3">
							<h4 class="font-size-20 font-weight-semibold"><?php esc_html_e( 'Accomodation', 'easy-reservations' ); ?><small class="font-size-10 ml-1">(<?php echo sprintf( __( 'Limit: %1$d', 'easy-reservations' ), $accomodation_limit ); ?>)</small></h4>
							<div class="values">
								<div class="row form-row">
									<div class="col-6">
										<input type="number" id="quick-view-adult-accomodation-count" class="form-contol" placeholder="<?php esc_html_e( 'No. of Adults', 'easy-reservations' ); ?>" />
									</div>
									<div class="col-6">
										<input type="number" id="quick-view-kid-accomodation-count" class="form-contol" placeholder="<?php esc_html_e( 'No. of Kids', 'easy-reservations' ); ?>" />
									</div>
									<label class="ersrv-reservation-error accomodation-error"></label>
								</div>
							</div>
						</div>
						<div class="amenities-values d-flex flex-column mb-3">
							<h4 class="font-size-20 font-weight-semibold"><?php esc_html_e( 'Amenities', 'easy-reservations' ); ?></h4>
							<div class="values ersrv-item-amenities-wrapper non-clickable">
								<div class="row form-row">
									<?php if ( ! empty( $amenities ) && is_array( $amenities ) ) { ?>
										<?php foreach ( $amenities as $amenity_data ) {
											$amenity_title     = ( ! empty( $amenity_data['title'] ) ) ? $amenity_data['title'] : '';
											$amenity_cost      = ( ! empty( $amenity_data['cost'] ) ) ? $amenity_data['cost'] : 0.00;
											$amenity_slug      = ( ! empty( $amenity_title ) ) ? sanitize_title( $amenity_title ) : '';
											$amenity_cost_type = ( ! empty( $amenity_data['cost_type'] ) ) ? $amenity_data['cost_type'] : 'one_time';
											?>
											<div class="col-6">
												<div class="custom-control custom-switch ersrv-single-amenity-block" data-cost_type="<?php echo esc_attr( $amenity_cost_type ); ?>" data-cost="<?php echo esc_attr( $amenity_cost ); ?>" data-amenity="<?php echo esc_attr( $amenity_title ); ?>">
													<input type="checkbox" class="ersrv-quick-view-reservation-single-amenity custom-control-input" id="amenity-<?php echo esc_html( $amenity_slug ); ?>">
													<label class="custom-control-label" for="amenity-<?php echo esc_html( $amenity_slug ); ?>"><?php echo esc_html( $amenity_title ); ?> - <span class="font-lato font-weight-bold color-accent"><?php echo wc_price( $amenity_cost ); ?></span></label>
												</div>
											</div>
										<?php } ?>
									<?php } ?>
								</div>
							</div>
						</div>
						<div class="summary d-flex flex-column">
							<input type="hidden" id="quick-view-adult-subtotal" value="" />
							<input type="hidden" id="quick-view-kid-subtotal" value="" />
							<input type="hidden" id="quick-view-amenities-subtotal" value="" />
							<input type="hidden" id="quick-view-security-subtotal" value="<?php echo esc_html( $security_amount ); ?>" />
							<h4 class="font-size-20 font-weight-semibold"><?php esc_html_e( 'Subtotal', 'easy-reservations' ); ?></h4>
							<label class="font-size-16"><?php echo sprintf( __( 'This will add %1$s%3$s%2$s to the cart.', 'easy-reservations' ), '<span class="font-lato font-weight-bold color-accent ersrv-quick-view-item-subtotal">', '</span>', wc_price( $security_amount ) ); ?></label>
						</div>
					</div>
					<div class="product-action-link">
						<input type="hidden" id="quick-view-accomodation-limit" value="<?php echo esc_html( $accomodation_limit ); ?>" />
						<input type="hidden" id="quick-view-min-reservation-period" value="<?php echo esc_html( $min_reservation_period ); ?>" />
						<input type="hidden" id="quick-view-max-reservation-period" value="<?php echo esc_html( $max_reservation_period ); ?>" />
						<input type="hidden" id="quick-view-adult-charge" value="<?php echo esc_html( $adult_charge ); ?>" />
						<input type="hidden" id="quick-view-kid-charge" value="<?php echo esc_html( $kid_charge ); ?>" />
						<input type="hidden" id="quick-view-security-amount" value="<?php echo esc_html( $security_amount ); ?>" />
						<input type="hidden" id="quick-view-item-id" value="<?php echo esc_html( $item_id ) ?>" />
						<button type="button" class="ersrv-add-quick-view-reservation-to-cart product-button add-to-cart btn-block"><?php esc_html_e( 'Procced to checkout', 'easy-reservations' ); ?></button>
						<a href="<?php echo esc_url( $item_permalink ); ?>" class="readmore-link btn btn-link"><?php esc_html_e( 'View full details', 'easy-reservations' ); ?></a>
					</div>
				</div>
			</div>
		</div>
		<?php
		$html = ob_get_clean();

		/**
		 * This hook fires after the contact owner request is saved.
		 *
		 * This hook helps in adding actions after any contact owner request is saved.
		 */
		do_action( 'ersrv_save_contact_owner_request_after' );

		// Prepare the response.
		$response = array(
			'code'           => 'quick-view-modal-fetched',
			'html'           => $html,
			'reserved_dates' => $reserved_dates,
			'message'        => __( 'Contact request is saved successfully. One of our teammates will get back to you soon.', 'easy-reservations' ),
		);
		wp_send_json_success( $response );
		wp_die();
	}

	/**
	 * Remove the cart item quantity input for all the reservation products in cart.
	 *
	 * @param string $quantity_html Holds the cart item quantity input html.
	 * @param string $cart_item_key Holds the cart item key.
	 * @param string $cart_item Holds the cart item.
	 * @return int
	 */
	public function ersrv_woocommerce_cart_item_quantity_callback( $quantity_html, $cart_item_key, $cart_item ) {
		// Return, if the cart item is unavailable.
		if ( ! $cart_item ) {
			return $quantity_html;
		}

		$is_reservation = ( false !== $cart_item && ! empty( $cart_item['reservation_data'] ) ) ? true : false;

		if ( $is_reservation ) {
			// Remove the link to remove cart item for free product.
			$quantity_html = $cart_item['quantity'];
		}

		return $quantity_html;
	}

	/**
	 * Add the driving license upload code on the checkout page.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_after_order_notes_callback() {
		// Get the plugin setting.
		$allowed_to_upload_license = ersrv_get_plugin_settings( 'ersrv_driving_license_validation' );

		// Return, if it's not allowed to upload driving license.
		if ( empty( $allowed_to_upload_license ) || 'no' === $allowed_to_upload_license ) {
			return;
		}

		// Check if there are reservation items in the cart.
		$is_reservation_in_cart = ersrv_is_reservation_in_cart();

		// Return, if there are no reservation items in cart.
		if ( ! $is_reservation_in_cart ) {
			return;
		}

		// Get the attachment ID, if already uploaded.
		$attachment_id  = WC()->session->get( 'reservation_driving_license_attachment_id' );
		$attachment_url = ersrv_get_attachment_url_from_attachment_id( $attachment_id );

		ob_start();
		?>
		<div class="woocommerce-additional-fields__field-wrapper">
			<p class="form-row ersrv-driving-license" id="ersrv_driving_license_field">
				<label for="reservation-driving-license" class=""><?php esc_html_e( 'Driving License', 'easy-reservations' ); ?></label>
				<span class="woocommerce-input-wrapper">
					<input type="file" name="reservation-driving-license" id="reservation-driving-license" />
				</span>
				<button type="button" class="button"><?php esc_html_e( 'Upload the license', 'easy-reservations' ); ?></button>
			</p>
		</div>
		<?php

		echo ob_get_clean();
	}

	/**
	 * AJAX to upload the driving license file on checkout.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_upload_driving_license_callback() {
		$action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );

		// Exit, if the action mismatches.
		if ( empty( $action ) || 'upload_driving_license' !== $action ) {
			echo 0;
			wp_die();
		}

		// Upload the file now.
		$driving_license_file_name = $_FILES['driving_license_file']['name'];
		$driving_license_file_temp = $_FILES['driving_license_file']['tmp_name'];
		$file_data                 = file_get_contents( $driving_license_file_temp );
		$filename                  = basename( $driving_license_file_name );
		$upload_dir                = wp_upload_dir();
		$file_path                 = ( ! empty( $upload_dir['path'] ) ) ? $upload_dir['path'] . $filename : $upload_dir['basedir'] . $filename;
		file_put_contents( $file_path, $file_data );

		// Upload it as WP attachment.
		$wp_filetype = wp_check_filetype( $filename, null );
		$attachment  = array(
			'post_mime_type' => $wp_filetype['type'],
			'post_title'     => sanitize_file_name( $filename ),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);
		$attach_id   = wp_insert_attachment( $attachment, $file_path );

		// Update the attachment ID in woocommerce session.
		WC()->session->set( 'reservation_driving_license_attachment_id', $attach_id );

		// Prepare the response.
		$response = array(
			'code'          => 'driving-license-uploaded',
			'toast_message' => __( 'Driving license is uploaded successfully. Place order to get this attached with your order.', 'easy-reservations' ),
		);
		wp_send_json_success( $response );
		wp_die();
	}

	/**
	 * Throw checkout error in the case driving license is not uploaded.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_checkout_process_callback() {
		// Get the plugin setting.
		$allowed_to_upload_license = ersrv_get_plugin_settings( 'ersrv_driving_license_validation' );

		// Check if there are reservation items in the cart.
		$is_reservation_in_cart = ersrv_is_reservation_in_cart();

		// Return error if driving license is not uploaded.
		if ( ! empty( $allowed_to_upload_license ) && 'yes' === $allowed_to_upload_license ) {
			// If the reservation item is in the cart.
			if ( true === $is_reservation_in_cart ) {
				// Get the attachment ID, if already uploaded.
				$attachment_id = WC()->session->get( 'reservation_driving_license_attachment_id' );

				// Throw the error if the attachment is either null or empty.
				if ( empty( $attachment_id ) || is_null( $attachment_id ) ) {
					$error_message = sprintf( __( 'Since you\'re doing a reservation, we require you to upload a valid driving license. Click %1$shere%2$s to upload.', 'easy-reservations' ), '<a class="scroll-to-driving-license" href="#">', '</a>' );
					/**
					 * This filter fires on checkout page.
					 *
					 * This filter helps in modifying the checkout error that is thrown in case the driving license file is not provided.
					 *
					 * @param string $error_message Error message.
					 * @return string
					 * @since 1.0.0
					 */
					$error_message = apply_filters( 'ersrv_driving_license_validation_checkout_error', $error_message );

					// Shoot the error now.
					wc_add_notice( $error_message, 'error' );
				}
			}
		}
	}

	/**
	 * Update the driving license attachment ID to order meta.
	 *
	 * @param int $order_id WooCommerce order ID.
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_checkout_update_order_meta_callback( $order_id ) {
		// Get the attachment ID.
		$attachment_id = WC()->session->get( 'reservation_driving_license_attachment_id' );

		// Update the order meta if the attachment ID is available.
		if ( ! empty( $attachment_id ) ) {
			update_post_meta( $order_id, 'reservation_driving_license_attachment_id', sanitize_text_field( $attachment_id ) );

			// Unset the session.
			WC()->session->__unset( 'reservation_driving_license_attachment_id' );
		}
	}

	/**
	 * Add a cancellation button after every reservation item in order.
	 *
	 * @param int      $item_id WooCommerce order item ID.
	 * @param object   $item WooCommerce order item.
	 * @param WC_Order $wc_order WooCommerce order.
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_order_item_meta_end_callback( $item_id, $item, $wc_order ) {
		$cancellation_enabled = ersrv_get_plugin_settings( 'ersrv_enable_reservation_cancellation' );

		// Return, if the cancellation is not enabled.
		if ( ! empty( $cancellation_enabled ) && 'no' === $cancellation_enabled ) {
			return;
		}

		// Get the product ID.
		$product_id = $item->get_product_id();

		// If this product is a reservation product.
		$is_reservation_product = ersrv_product_is_reservation( $product_id );

		// Return, if the item is not a reservation product.
		if ( ! $is_reservation_product ) {
			return;
		}

		// Check if the reservation can be cancelled.
		$can_cancel = ersrv_reservation_eligible_for_cancellation( $item_id );

		// Return the actions if the order cannot be cancelled.
		if ( false === $can_cancel ) {
			return;
		}

		// Print the reservation order cancellation button.
		ersrv_print_reservation_cancel_button( $item_id, $wc_order->get_id() );
	}

	/**
	 * AJAX to raise cancellation request for the reservation.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_request_reservation_cancel_callback() {
		$action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );

		// Exit, if the action mismatches.
		if ( empty( $action ) || 'request_reservation_cancel' !== $action ) {
			echo 0;
			wp_die();
		}

		// Posted data.
		$item_id  = (int) filter_input( INPUT_POST, 'item_id', FILTER_SANITIZE_NUMBER_INT );
		$order_id = (int) filter_input( INPUT_POST, 'order_id', FILTER_SANITIZE_NUMBER_INT );

		// Update item meta data.
		wc_update_order_item_meta( $item_id, 'ersrv_cancellation_request', 1 );
		wc_update_order_item_meta( $item_id, 'ersrv_cancellation_request_time', time() );
		wc_update_order_item_meta( $item_id, 'ersrv_cancellation_request_status', 'pending' );

		/**
		 * This hook fires on the my account view order page.
		 *
		 * This hooks helps in firing the email to the administrator when there is any cancellation request from any customer.
		 *
		 * @param int $item_id WooCommerce order item ID.
		 * @param int $order_id WooCommerce order ID.
		 * @since 1.0.0
		 */
		do_action( 'ersrv_email_after_reservation_cancellation_request', $item_id, $order_id );

		// Prepare the response.
		$response = array(
			'code'          => 'cancellation-request-saved',
			'toast_message' => __( 'Cancellation request for this reservation has been saved successfully. You will receive an email when admin accepts/rejects the cancellation.', 'easy-reservations' ),
		);
		wp_send_json_success( $response );
		wp_die();
	}

	/**
	 * Edit reservation callback.
	 *
	 * @param array $args Holds the shortcode arguments.
	 * @return string
	 * @since 1.0.0
	 */
	public function ersrv_ersrv_edit_reservation_callback( $args = array() ) {
		// Return, if it's admin panel.
		if ( is_admin() ) {
			return;
		}

		ob_start();
		require_once ERSRV_PLUGIN_PATH . 'public/templates/shortcodes/edit-reservation.php';
		return ob_get_clean();
	}

	/**
	 * Add custom field to billing fields on checkout page.
	 *
	 * @param array $fields Billing fields.
	 * @return array
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_billing_fields_callback( $fields ) {
		$timezones = array( '' => __( 'Select timezone', 'easy-reservations' ) );

		// Iterate through the timezone identifiers list.
		foreach ( timezone_identifiers_list() as $timezone_label ) {
			$timezones[ $timezone_label ] = $timezone_label;
		}

		// Add a custom field for customer timezone.
		$fields['billing_timezone'] = array(
			'type'     => 'select',
			'label'    => __( 'Timezone', 'easy-reservations' ),
			'class'    => array( 'billing-timezone' ),
			'options'  => $timezones,
			'required' => true,
			'priority' => 85,
		);

		return $fields;
	}

	/**
	 * Add custom class to body tag on edit reservation page.
	 *
	 * @param array $classes Classes array.
	 * @return array
	 */
	public function ersrv_body_class_callback( $classes ) {
		global $post;

		$is_edit_reservation_page = ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'ersrv_edit_reservation' ) );

		// If it's the edit reservation page.
		if ( $is_edit_reservation_page ) {
			return array_merge( $classes, array( 'ersrv-edit-reservation-template' ) );
		}

		return $classes;
	}
}
