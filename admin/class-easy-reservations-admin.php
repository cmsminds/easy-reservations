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
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function ersrv_admin_enqueue_scripts_callback() {
		$post_type = filter_input( INPUT_GET, 'post_type', FILTER_SANITIZE_STRING );

		// Include the modal style only on orders page.
		if ( ! is_null( $post_type ) && 'shop_order' === $post_type ) {
			// Custom admin style.
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
		wp_localize_script(
			$this->plugin_name,
			'ERSRV_Admin_Script_Vars',
			array(
				'same_as_adult'       => __( 'Same as Adult!', 'easy-reservations' ),
				'export_reservations' => __( 'Export Reservations', 'easy-reservations' ),
			)
		);
	}

	/**
	 * Register a new product type in WooCommerce Products.
	 *
	 * @param array $product_types Holds the list of registered product types.
	 * @return array
	 * @since 1.0.0
	 */
	public function ersrv_product_type_selector_callback( $product_types ) {
		$product_type_slug  = ersrv_get_custom_product_type_slug();
		$product_type_label = ersrv_get_custom_product_type_label();

		// Check if the reservation product type already exists. Return, if it already exists.
		if ( in_array( $product_type_slug, $product_types, true ) ) {
			return $product_types;
		}

		// Add the new product type.
		$product_types[ $product_type_slug ] = $product_type_label;

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
		$product_type_slug = ersrv_get_custom_product_type_slug();

		// Check if the tab for reservation product type already exists. Return, if it already exists.
		if ( in_array( $product_type_slug, $tabs, true ) ) {
			return $tabs;
		}

		$tab_title = __( 'Reservation Details', 'easy-reservations' );

		/**
		 * This hook fires in admin panel on the item settings page.
		 *
		 * This filter will help in modifying the product type tab title.
		 *
		 * @param string $tab_title Holds the product type tab title.
		 * @return string
		 */
		$tab_title = apply_filters( 'ersrv_product_type_tab_label', $tab_title );

		// Add the new tab.
		$tabs[ $product_type_slug ] = array(
			'label'    => $tab_title,
			'target'   => "{$product_type_slug}_product_options",
			'class'    => array(
				"show_if_{$product_type_slug}",
				'hide_if_simple',
				'hide_if_grouped',
				'hide_if_external',
				'hide_if_variable',
			),
			'priority' => 65,
		);

		// Hide the general tab.
		if ( ! empty( $tabs['general'] ) ) {
			$tabs['general']['class'][] = "hide_if_{$product_type_slug}";
		}

		// Hide the inventory tab.
		if ( ! empty( $tabs['inventory'] ) ) {
			$tabs['inventory']['class'][] = "hide_if_{$product_type_slug}";
		}

		// Hide the shipping tab.
		if ( ! empty( $tabs['shipping'] ) ) {
			$tabs['shipping']['class'][] = "hide_if_{$product_type_slug}";
		}

		// Hide the linked products tab.
		if ( ! empty( $tabs['linked_product'] ) ) {
			$tabs['linked_product']['class'][] = "hide_if_{$product_type_slug}";
		}

		// Hide the attributes tab.
		if ( ! empty( $tabs['attribute'] ) ) {
			$tabs['attribute']['class'][] = "hide_if_{$product_type_slug}";
		}

		// Hide the variations tab.
		if ( ! empty( $tabs['variations'] ) ) {
			$tabs['variations']['class'][] = "hide_if_{$product_type_slug}";
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

		require_once ERSRV_PLUGIN_PATH . 'admin/templates/settings/product-settings.php';
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
		$post_type = filter_input( INPUT_GET, 'post_type', FILTER_SANITIZE_STRING );

		// Include the export reservations modal only on orders page.
		if ( ! is_null( $post_type ) && 'shop_order' === $post_type ) {
			require_once ERSRV_PLUGIN_PATH . 'admin/templates/modals/export-reservations.php';
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
}
