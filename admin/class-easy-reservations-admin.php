<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.cmsminds.com/
 * @since      1.0.0
 *
 * @package    Easy_Reservations
 * @subpackage Easy_Reservations/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Easy_Reservations
 * @subpackage Easy_Reservations/admin
 * @author     cmsMinds <info@cmsminds.com>
 */
class Easy_Reservations_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

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
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		// Custom product type.
		$this->custom_product_type = ersrv_get_custom_product_type_slug();
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function ersrv_admin_enqueue_scripts_callback() {
		$post_type           = filter_input( INPUT_GET, 'post_type', FILTER_SANITIZE_STRING );
		$product_id          = (int) filter_input( INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT );
		$page                = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );
		$include_modal_style = false;

		// Include the blocked out reservation dates modal only on orders page.
		if ( ! is_null( $product_id ) && 'product' === get_post_type( $product_id ) ) {
			$include_modal_style = true;
		} elseif ( ! is_null( $post_type ) && 'shop_order' === $post_type ) { // Include the modal style only on orders page.
			$include_modal_style = true;
		} elseif ( ! is_null( $page ) && 'new-reservation' === $page ) {
			$include_modal_style = true;
		}

		// Include modal style.
		if ( $include_modal_style ) {
			wp_enqueue_style(
				$this->plugin_name . '-modal',
				ERSRV_PLUGIN_URL . 'admin/css/easy-reservations-modal.css',
				array(),
				filemtime( ERSRV_PLUGIN_PATH . 'admin/css/easy-reservations-modal.css' )
			);
		}

		// Custom admin style.
		wp_enqueue_style(
			$this->plugin_name,
			ERSRV_PLUGIN_URL . 'admin/css/easy-reservations-admin.css',
			array(),
			filemtime( ERSRV_PLUGIN_PATH . 'admin/css/easy-reservations-admin.css' )
		);

		// Custom admin script.
		wp_enqueue_script(
			$this->plugin_name,
			ERSRV_PLUGIN_URL . 'admin/js/easy-reservations-admin.js',
			array( 'jquery' ),
			filemtime( ERSRV_PLUGIN_PATH . 'admin/js/easy-reservations-admin.js' ),
			true
		);

		// Localize script.
		wp_localize_script( $this->plugin_name, 'ERSRV_Admin_Script_Vars', ersrv_get_admin_script_vars() );
	}

	/**
	 * Register a new product type in WooCommerce Products.
	 *
	 * @param array $product_types Holds the list of registered product types.
	 * @return array
	 * @since 1.0.0
	 */
	public function ersrv_product_type_selector_callback( $product_types ) {
		$product_type_label = ersrv_get_custom_product_type_label();

		// Check if the reservation product type already exists. Return, if it already exists.
		if ( in_array( $this->custom_product_type, $product_types, true ) ) {
			return $product_types;
		}

		// Add the new product type.
		$product_types[ $this->custom_product_type ] = $product_type_label;

		return $product_types;
	}

	/**
	 * Register product setting tabs in WooCommerce Products.
	 *
	 * @param array $tabs Holds the list of registered product settings tabs.
	 * @return array
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_product_data_tabs_callback( $tabs ) {
		// Reservation details tab.
		$reservation_details_tab_title = __( 'Details', 'easy-reservations' );

		/**
		 * This hook fires in admin panel on the item settings page.
		 *
		 * This filter will help in modifying the product type tab title - easy reservations.
		 *
		 * @param string $reservation_details_tab_title Holds the product type tab title.
		 * @return string
		 */
		$reservation_details_tab_title = apply_filters( 'ersrv_product_type_tab_label', $reservation_details_tab_title );

		// Add the new tab - reservation details.
		$tabs['reservation_details'] = array(
			'label'    => $reservation_details_tab_title,
			'target'   => 'reservation_details_product_options',
			'class'    => array(
				"show_if_{$this->custom_product_type}",
				'hide_if_simple',
				'hide_if_grouped',
				'hide_if_external',
				'hide_if_variable',
			),
			'priority' => 65,
		);

		// Reservation blockout dates tab.
		$reservation_blockout_dates_tab_title = __( 'Blockout Dates', 'easy-reservations' );

		/**
		 * This hook fires in admin panel on the item settings page.
		 *
		 * This filter will help in modifying the product type tab title - blockout dates.
		 *
		 * @param string $reservation_blockout_dates_tab_title Holds the product type tab title.
		 * @return string
		 */
		$reservation_blockout_dates_tab_title = apply_filters( 'ersrv_product_type_tab_label', $reservation_blockout_dates_tab_title );

		// Add the new tab - reservation blockout dates.
		$tabs['reservation_blockout_dates'] = array(
			'label'    => $reservation_blockout_dates_tab_title,
			'target'   => 'reservation_blockout_dates_product_options',
			'class'    => array(
				"show_if_{$this->custom_product_type}",
				'hide_if_simple',
				'hide_if_grouped',
				'hide_if_external',
				'hide_if_variable',
			),
			'priority' => 68,
		);

		// Hide the general tab.
		if ( ! empty( $tabs['general'] ) ) {
			$tabs['general']['class'][] = "hide_if_{$this->custom_product_type}";
		}

		// Hide the inventory tab.
		if ( ! empty( $tabs['inventory'] ) ) {
			$tabs['inventory']['class'][] = "hide_if_{$this->custom_product_type}";
		}

		// Hide the shipping tab.
		if ( ! empty( $tabs['shipping'] ) ) {
			$tabs['shipping']['class'][] = "hide_if_{$this->custom_product_type}";
		}

		// Hide the linked products tab.
		if ( ! empty( $tabs['linked_product'] ) ) {
			$tabs['linked_product']['class'][] = "hide_if_{$this->custom_product_type}";
		}

		// Hide the attributes tab.
		if ( ! empty( $tabs['attribute'] ) ) {
			$tabs['attribute']['class'][] = "hide_if_{$this->custom_product_type}";
		}

		// Hide the variations tab.
		if ( ! empty( $tabs['variations'] ) ) {
			$tabs['variations']['class'][] = "hide_if_{$this->custom_product_type}";
		}

		return $tabs;
	}

	/**
	 * Create the settings template for the reservation type.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_product_data_panels_callback() {
		global $post;

		if ( empty( $post->ID ) ) {
			return;
		}

		// Reservation details.
		require_once ERSRV_PLUGIN_PATH . 'admin/templates/settings/reservation-details-settings.php';

		// Reservation blockout dates.
		require_once ERSRV_PLUGIN_PATH . 'admin/templates/settings/reservation-blockout-dates.php';
	}

	/**
	 * Update product custom meta details.
	 *
	 * @param int $post_id Holds the product ID.
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_process_product_meta_callback( $post_id ) {
		$security_amt              = (float) filter_input( INPUT_POST, 'security_amount', FILTER_SANITIZE_NUMBER_FLOAT );
		$accomodation_limit        = (int) filter_input( INPUT_POST, 'accomodation_limit', FILTER_SANITIZE_NUMBER_INT );
		$accomodation_adult_charge = (float) filter_input( INPUT_POST, 'accomodation_adult_charge', FILTER_SANITIZE_NUMBER_FLOAT );
		$accomodation_kid_charge   = (float) filter_input( INPUT_POST, 'accomodation_kid_charge', FILTER_SANITIZE_NUMBER_FLOAT );
		$reservation_min_period    = (int) filter_input( INPUT_POST, 'reservation_min_period', FILTER_SANITIZE_NUMBER_INT );
		$reservation_max_period    = (int) filter_input( INPUT_POST, 'reservation_max_period', FILTER_SANITIZE_NUMBER_INT );
		$promotion_text            = filter_input( INPUT_POST, 'promotion_text', FILTER_SANITIZE_STRING );
		$posted_array              = filter_input_array( INPUT_POST );
		$amenities_titles          = ( $posted_array['amenity_title'] ) ? $posted_array['amenity_title'] : array();
		$amenities_costs           = ( $posted_array['amenity_cost'] ) ? $posted_array['amenity_cost'] : array();
		$amenities                 = array();
		$blockout_dates            = ( $posted_array['blockout_date'] ) ? $posted_array['blockout_date'] : array();
		$blockout_dates_messages   = ( $posted_array['blockout_date_message'] ) ? $posted_array['blockout_date_message'] : array();

		// Prepare the amenities array.
		if ( ! empty( $amenities_titles ) && is_array( $amenities_titles ) ) {
			foreach ( $amenities_titles as $index => $amenity_title ) {
				$amenities[] = array(
					'title' => $amenity_title,
					'cost'  => $amenities_costs[ $index ],
				);
			}

			// Update the amenities to the database.
			update_post_meta( $post_id, '_ersrv_reservation_amenities', $amenities );
		} else {
			delete_post_meta( $post_id, '_ersrv_reservation_amenities' );
		}

		// Prepare the blockout calendar dates array.
		if ( ! empty( $blockout_dates ) && is_array( $blockout_dates ) ) {
			// Get the already blockedout dates.
			$blockedout_dates = ersrv_get_reservation_item_blockout_dates( $post_id );
			$blockedout_dates = ( false === $blockedout_dates || ! is_array( $blockedout_dates ) ) ? array() : $blockedout_dates;

			// Iterate through the blockout dates to add them to database.
			foreach ( $blockout_dates as $index => $blockout_date ) {
				$existing_dates = array_column( $blockedout_dates, 'date' );

				// Skip the existing date.
				if ( in_array( $blockout_date, $existing_dates, true ) ) {
					continue;
				}

				$blockedout_dates[] = array(
					'date'    => $blockout_date,
					'message' => $blockout_dates_messages[ $index ],
				);
			}

			// Update the blocked out dates to the database.
			update_post_meta( $post_id, '_ersrv_reservation_blockout_dates', $blockedout_dates );
		}

		// If security amount is available.
		if ( ! empty( $security_amt ) ) {
			update_post_meta( $post_id, '_ersrv_security_amt', $security_amt );
		} else {
			delete_post_meta( $post_id, '_ersrv_security_amt' );
		}

		// If accomodation limit is available.
		if ( ! empty( $accomodation_limit ) ) {
			update_post_meta( $post_id, '_ersrv_accomodation_limit', $accomodation_limit );
		} else {
			delete_post_meta( $post_id, '_ersrv_accomodation_limit' );
		}

		// If accomodation adult charge is available.
		if ( ! empty( $accomodation_adult_charge ) ) {
			update_post_meta( $post_id, '_ersrv_accomodation_adult_charge', $accomodation_adult_charge );
		} else {
			delete_post_meta( $post_id, '_ersrv_accomodation_adult_charge' );
		}

		// If accomodation kid's charge is available.
		if ( ! empty( $accomodation_kid_charge ) ) {
			update_post_meta( $post_id, '_ersrv_accomodation_kid_charge', $accomodation_kid_charge );
		} else {
			delete_post_meta( $post_id, '_ersrv_accomodation_kid_charge' );
		}

		// If accomodation minimum period is available.
		if ( ! empty( $reservation_min_period ) ) {
			update_post_meta( $post_id, '_ersrv_reservation_min_period', $reservation_min_period );
		} else {
			delete_post_meta( $post_id, '_ersrv_reservation_min_period' );
		}

		// If accomodation maximum period is available.
		if ( ! empty( $reservation_max_period ) ) {
			update_post_meta( $post_id, '_ersrv_reservation_max_period', $reservation_max_period );
		} else {
			delete_post_meta( $post_id, '_ersrv_reservation_max_period' );
		}

		// If promotion text is available.
		if ( ! empty( $promotion_text ) ) {
			update_post_meta( $post_id, '_ersrv_promotion_text', $promotion_text );
		} else {
			delete_post_meta( $post_id, '_ersrv_promotion_text' );
		}
	}

	/**
	 * Add custom assets to WordPress admin footer section.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_admin_footer_callback() {
		$page       = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );
		$post_type  = filter_input( INPUT_GET, 'post_type', FILTER_SANITIZE_STRING );
		$product_id = (int) filter_input( INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT );

		// Include the export reservations modal only on orders page.
		if ( ! is_null( $post_type ) && 'shop_order' === $post_type ) {
			require_once ERSRV_PLUGIN_PATH . 'admin/templates/modals/export-reservations.php';
		}

		// Include the blocked out reservation dates modal only on orders page.
		if ( ! is_null( $product_id ) && 'product' === get_post_type( $product_id ) ) {
			require_once ERSRV_PLUGIN_PATH . 'admin/templates/modals/block-out-reservation-calendar-dates.php';
		}

		// Include the new customer modal on new reservation page only on product page.
		if ( ! is_null( $page ) && 'new-reservation' === $page ) {
			require_once ERSRV_PLUGIN_PATH . 'admin/templates/modals/new-customer.php';
		}
	}

	/**
	 * AJAX to export reservations.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_export_reservations_callback() {
		$action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );

		// Exit, if the action mismatches.
		if ( empty( $action ) || 'export_reservations' !== $action ) {
			echo 0;
			wp_die();
		}

		// Get the reservations.
		$reservations_query = ersrv_get_posts( 'shop_order' );
		$reservations       = ( ! empty( $reservations_query->posts ) ) ? $reservations_query->posts : array();

		debug( $reservations );
		die;
	}

	/**
	 * Admin settings for managing reservations.
	 *
	 * @param array $settings Array of WC settings.
	 * @return array
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_get_settings_pages_callback( $settings ) {
		$settings[] = include ERSRV_PLUGIN_PATH . 'includes/classes/class-easy-reservations-settings.php';

		return $settings;
	}

	/**
	 * Add custom plugin row meta actions.
	 *
	 * @param array  $links Holds the links array.
	 * @param string $file Holds this plugin file.
	 * @return array
	 * @since 1.0.0
	 */
	public function ersrv_plugin_row_meta_callback( $links, $file ) {
		// Return the links, if the file doesn't match this plugin main file.
		if ( 'easy-reservations/easy-reservations.php' !== $file ) {
			return $links;
		}

		// New row meta - community support.
		$links[] = '<a href="javascript:void(0);" target="_blank" title="' . __( 'Community support', 'easy-reservations' ) . '">' . __( 'Community support', 'easy-reservations' ) . '</a>';

		// New row meta - developer docs.
		$links[] = '<a href="javascript:void(0);" target="_blank" title="' . __( 'Developer docs', 'easy-reservations' ) . '">' . __( 'Developer docs', 'easy-reservations' ) . '</a>';

		return $links;
	}

	/**
	 * AJAX to fetch the amenity HTML.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_get_amenity_html_callback() {
		$action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );

		// Exit, if the action mismatches.
		if ( empty( $action ) || 'get_amenity_html' !== $action ) {
			echo 0;
			wp_die();
		}

		// Return the amenity html response.
		$response = array(
			'code' => 'amenity-html-fetched',
			'html' => ersrv_get_amenity_html(),
		);
		wp_send_json_success( $response );
		wp_die();
	}

	/**
	 * Register a widget for showing the calendar.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_widgets_init_callback() {
		require_once ERSRV_PLUGIN_PATH . 'includes/classes/class-easy-reservations-calendar-widget.php';
		register_widget( 'Easy_Reservations_Calendar_Widget' );
	}

	/**
	 * AJAX to fetch the blockout date HTML.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_get_blockout_date_html_callback() {
		$action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );

		// Exit, if the action mismatches.
		if ( empty( $action ) || 'get_blockout_date_html' !== $action ) {
			echo 0;
			wp_die();
		}

		// Posted data.
		$from    = filter_input( INPUT_POST, 'date_from', FILTER_SANITIZE_STRING );
		$to      = filter_input( INPUT_POST, 'date_to', FILTER_SANITIZE_STRING );
		$message = filter_input( INPUT_POST, 'message', FILTER_SANITIZE_STRING );

		// Dates array.
		$dates = array();

		// Gather dates.
		if ( empty( $to ) && ! empty( $from ) ) { // If only start date is available.
			$dates[] = array(
				'date'    => $from,
				'message' => $message,
			);
		} elseif ( empty( $from ) && ! empty( $to ) ) { // If only end date is available.
			$dates[] = array(
				'date'    => $to,
				'message' => $message,
			);
		} else { // If start and end dates are available.
			// Get the dates between 2 dates.
			$dates_range = ersrv_get_dates_within_2_dates( $from, $to );

			if ( ! empty( $dates_range ) ) {
				foreach ( $dates_range as $date ) {
					$dates[] = array(
						'date'    => $date->format( 'Y-m-d' ),
						'message' => $message,
					);
				}
			}
		}

		// Prepare the HTML now.
		$html = '';
		if ( ! empty( $dates ) && is_array( $dates ) ) {
			foreach ( $dates as $date_data ) {
				$html .= ersrv_get_blockout_date_html( $date_data['date'], $date_data['message'] );
			}
		}

		// Return the blockout date html response.
		$response = array(
			'code' => 'blockout-date-html-fetched',
			'html' => $html,
		);
		wp_send_json_success( $response );
		wp_die();
	}

	/**
	 * Add custom admin pages.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_admin_menu_callback() {
		// Submenu to add reservation from admin panel.
		add_submenu_page(
			'woocommerce',
			__( 'New Reservation', 'easy-reservations' ),
			__( 'New Reservation', 'easy-reservations' ),
			'manage_options',
			'new-reservation',
			array( $this, 'ersrv_new_admin_reservation' ),
			15
		);
	}

	/**
	 * New reservation template from admin panel.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_new_admin_reservation() {
		require_once ERSRV_PLUGIN_PATH . 'admin/templates/pages/new-reservation.php';
	}

	/**
	 * AJAX to create new user.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_register_new_customer_callback() {
		$action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );

		// Exit, if the action mismatches.
		if ( empty( $action ) || 'register_new_customer' !== $action ) {
			echo 0;
			wp_die();
		}

		// Posted data.
		$first_name     = filter_input( INPUT_POST, 'first_name', FILTER_SANITIZE_STRING );
		$last_name      = filter_input( INPUT_POST, 'last_name', FILTER_SANITIZE_STRING );
		$email          = filter_input( INPUT_POST, 'email', FILTER_SANITIZE_STRING );
		$password       = filter_input( INPUT_POST, 'password', FILTER_SANITIZE_STRING );
		$email_exploded = explode( '@', $email );
		$username       = ( ! empty( $email_exploded[0] ) ) ? $email_exploded[0] : $email;

		// Return the error if the customer exists.
		if ( email_exists( $email ) || username_exists( $username ) ) {
			wp_send_json_error(
				array(
					'code'          => 'ersrv-user-exists',
					'error_message' => sprintf( __( 'User with the requested email, %1$s, already exists. Please try with a different email address.', 'easy-reservations' ), $email ),
				)
			);
			wp_die();
		}

		// Save the user in database.
		$user_id           = ersrv_create_new_user( $username, $email, $password, $first_name, $last_name );
		$user_data         = get_userdata( $user_id );
		$user_display_name = $user_data->data->display_name;
		$user_email        = $user_data->data->user_email;
		$user_option_text  = "#{$user_id} [{$user_email}] - {$user_display_name}";

		$response = array(
			'code'            => 'ersrv-user-registered',
			'user_id'         => $user_id,
			'success_message' => __( 'User created.', 'easy-reservations' ),
			'user_html'       => '<option value="' . $user_id . '">' . $user_option_text . '</option>',
		);
		wp_send_json_success( $response );
		wp_die();
	}

	/**
	 * AJAX to generate new password.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_generate_new_password_callback() {
		$action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );

		// Exit, if the action mismatches.
		if ( empty( $action ) || 'generate_new_password' !== $action ) {
			echo 0;
			wp_die();
		}

		$response = array(
			'code'     => 'password-generated',
			'password' => wp_generate_password( 12, true, true ),
		);
		wp_send_json_success( $response );
		wp_die();
	}

	/**
	 * AJAX to get the reservable item details.
	 *
	 * @param int $item_id Holds the item ID.
	 * @return array
	 * @since 1.0.0
	 */
	public function ersrv_get_reservable_item_details_callback( $item_id ) {
		// If doing AJAX.
		if ( DOING_AJAX ) {
			$action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );

			// Exit, if the action mismatches.
			if ( empty( $action ) || 'get_reservable_item_details' !== $action ) {
				echo 0;
				wp_die();
			}

			// Posted data.
			$item_id = filter_input( INPUT_POST, 'item_id', FILTER_SANITIZE_NUMBER_INT );
		}

		// Accomodation limit.
		$accomodation_limit = get_post_meta( $item_id, '_ersrv_accomodation_limit', true );

		// Put the details in an array.
		$item_details = array(
			'accomodation_limit' => $accomodation_limit,
		);

		// Send the AJAX response.
		if ( DOING_AJAX ) {
			$response = array(
				'code'    => 'item-details-fetched',
				'details' => $item_details,
			);
			wp_send_json_success( $response );
			wp_die();
		}

		return $item_details;
	}
}
