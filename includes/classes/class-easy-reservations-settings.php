<?php
/**
 * The admin-settings of the plugin.
 *
 * @link       https://www.cmsminds.com/
 * @since      1.0.0
 *
 * @package    Easy_Reservations
 * @subpackage Easy_Reservations/includes/classes
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if ( class_exists( 'Easy_Reservations_Settings', false ) ) {
	return new Easy_Reservations_Settings();
}

/**
 * Class to manage the admin settings for the reservations.
 */
class Easy_Reservations_Settings extends WC_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'easy-reservations';
		$this->label = __( 'Easy Reservations', 'easy-reservations' );

		parent::__construct();
	}

	/**
	 * Get sections.
	 *
	 * @return array
	 */
	public function get_sections() {
		$sections = array_merge(
			array( '' => __( 'General', 'easy-reservations' ) ),
			array( 'reservation_calendar' => __( 'Reservation Calendar', 'easy-reservations' ) ),
			array( 'quickbooks' => __( 'Quickbooks', 'easy-reservations' ) ),
			array( 'emails' => __( 'Emails', 'easy-reservations' ) )
		);

		return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
	}

	/**
	 * Output the settings.
	 */
	public function output() {
		global $current_section;

		$settings = $this->get_settings( $current_section );
		WC_Admin_Settings::output_fields( $settings );
	}

	/**
	 * Save settings.
	 */
	public function save() {
		global $current_section;

		$settings = $this->get_settings( $current_section );
		WC_Admin_Settings::save_fields( $settings );

		if ( $current_section ) {
			do_action( 'woocommerce_update_options_' . $this->id . '_' . $current_section );
		}
	}

	/**
	 * Get settings array.
	 *
	 * @param string $current_section Current section name.
	 * @return array
	 */
	public function get_settings( $current_section = '' ) {
		switch ( $current_section ) {
			case 'reservation_calendar':
				$settings = $this->ersrv_reservation_calendar_settings_fields();
				break;
			case 'quickbooks':
				$settings = $this->ersrv_quickbooks_settings_fields();
				break;
			case 'emails':
				$settings = $this->ersrv_emails_settings_fields();
				break;
			default:
				$settings = $this->ersrv_general_settings_fields(); // Fields for the general section.
		}

		return apply_filters( 'woocommerce_get_settings_' . $this->id, $settings, $current_section );
	}

	/**
	 * Return the fields for general settings.
	 *
	 * @return array
	 */
	public function ersrv_general_settings_fields() {
		$fields = array(
			array(
				'title' => __( 'General', 'easy-reservations' ),
				'type'  => 'title',
				'desc'  => __( 'This section includes the reservation general settings.', 'easy-reservations' ),
				'id'    => 'ersrv_reservation_general_settings',
			),
			array(
				'title' => __( 'Remove Sidebar from Reservation Single Page', 'easy-reservations' ),
				'desc'  => __( 'This sets whether the sidebar is to be displayed on reservation single page.', 'easy-reservations' ),
				'id'    => 'ersrv_remove_product_single_sidebar',
				'type'  => 'checkbox',
			),
			array(
				'title' => __( 'Driving License Validation', 'easy-reservations' ),
				'desc'  => __( 'This sets whether the driver needs to submit the driving license of the reservation or not.', 'easy-reservations' ),
				'id'    => 'ersrv_driving_license_validation',
				'type'  => 'checkbox',
			),
			array(
				'type' => 'sectionend',
				'id'   => 'ersrv_reservation_general_settings',
			),
			array(
				'title' => __( 'Archive Page', 'easy-reservations' ),
				'type'  => 'title',
				'desc'  => __( 'This section includes the settings related to woocommerce archive pages which include shop page, category and tags pages.', 'easy-reservations' ),
				'id'    => 'ersrv_wc_archive_page_settings',
			),
			array(
				'title'       => __( 'Add To Cart Button Text', 'easy-reservations' ),
				'desc'        => __( 'This holds the add to cart button text. Default: Reserve It', 'easy-reservations' ),
				'desc_tip'    => true,
				'id'          => 'ersrv_archive_page_add_to_cart_button_text',
				'placeholder' => __( 'E.g.: Reserve It', 'easy-reservations' ),
				'type'        => 'text',
			),
			array(
				'type' => 'sectionend',
				'id'   => 'ersrv_wc_archive_page_settings',
			),
			array(
				'title' => __( 'Product Single', 'easy-reservations' ),
				'type'  => 'title',
				'desc'  => __( 'This section includes the settings related to woocommerce product single pages.', 'easy-reservations' ),
				'id'    => 'ersrv_wc_product_single_page_settings',
			),
			array(
				'title'       => __( 'Add To Cart Button Text', 'easy-reservations' ),
				'desc'        => __( 'This holds the add to cart button text. Default: Reserve It', 'easy-reservations' ),
				'desc_tip'    => true,
				'id'          => 'ersrv_product_single_page_add_to_cart_button_text',
				'placeholder' => __( 'E.g.: Reserve It', 'easy-reservations' ),
				'type'        => 'text',
			),
			array(
				'type' => 'sectionend',
				'id'   => 'ersrv_wc_product_single_page_settings',
			),
		);

		/**
		 * This hook fires on the admin settings page - general section.
		 *
		 * This account help in managing general section plugin settings fields.
		 *
		 * @param array $fields Holds the fields array.
		 * @return array
		 */
		return apply_filters( 'ersrv_general_section_plugin_settings', $fields );
	}

	/**
	 * Return the fields for Quickbooks settings.
	 *
	 * @return array
	 */
	public function ersrv_quickbooks_settings_fields() {
		$fields = array(
			array(
				'title' => __( 'Quickbooks Settings', 'easy-reservations' ),
				'type'  => 'title',
				'desc'  => '',
				'id'    => 'ersrv_quickbooks_settings',
			),
			array(
				'title'       => __( 'API Key', 'easy-reservations' ),
				'desc'        => __( 'This holds the quickbooks account API key.', 'easy-reservations' ),
				'desc_tip'    => true,
				'id'          => 'ersrv_quickbooks_api_key',
				'placeholder' => __( 'XXX XXX XXX', 'easy-reservations' ),
				'type'        => 'text',
			),
			array(
				'type' => 'sectionend',
				'id'   => 'ersrv_quickbooks_settings',
			),
		);

		/**
		 * This hook fires on the admin settings page - general section.
		 *
		 * This account help in managing general section plugin settings fields.
		 *
		 * @param array $fields Holds the fields array.
		 * @return array
		 */
		return apply_filters( 'ersrv_general_section_plugin_settings', $fields );
	}

	/**
	 * Return the fields for Emails settings.
	 *
	 * @return array
	 */
	public function ersrv_emails_settings_fields() {
		$fields = array(
			array(
				'title' => __( 'Emails Settings', 'easy-reservations' ),
				'type'  => 'title',
				'desc'  => '',
				'id'    => 'ersrv_emails_settings',
			),
			array(
				'title'             => __( 'Multiple Administrators', 'easy-reservations' ),
				'desc'              => __( 'This holds the list of admin emails who\'ll will receive the reservation email.', 'easy-reservations' ),
				'desc_tip'          => true,
				'id'                => 'ersrv_reservation_multiple_admin_recipients',
				'placeholder'       => __( 'Provide comma separated emails.', 'easy-reservations' ),
				'type'              => 'textarea',
				'custom_attributes' => array(
					'rows' => 4,
				),
			),
			array(
				'type' => 'sectionend',
				'id'   => 'ersrv_emails_settings',
			),
		);

		/**
		 * This hook fires on the admin settings page - general section.
		 *
		 * This account help in managing general section plugin settings fields.
		 *
		 * @param array $fields Holds the fields array.
		 * @return array
		 */
		return apply_filters( 'ersrv_general_section_plugin_settings', $fields );
	}

	/**
	 * Return the fields for Reservation Calendar settings.
	 *
	 * @return array
	 */
	public function ersrv_reservation_calendar_settings_fields() {
		$fields = array(
			array(
				'title' => __( 'Calendar Colors', 'easy-reservations' ),
				'type'  => 'title',
				'desc'  => __( 'This section includes the settings related to calendar dates color indications.', 'easy-reservations' ),
				'id'    => 'ersrv_calendar_colors_settings',
			),
			array(
				'title'       => __( 'Item availability background color', 'easy-reservations' ),
				'desc'        => __( 'This holds the background color of the calendar date, which would reflect the item\'s availability.', 'easy-reservations' ),
				'desc_tip'    => true,
				'id'          => 'ersrv_item_availability_calendar_color',
				'placeholder' => 'E.g.: #b2b2b2',
				'type'        => 'color',
			),
			array(
				'type' => 'sectionend',
				'id'   => 'ersrv_calendar_colors_settings',
			),
		);

		/**
		 * This hook fires on the admin settings page - reservation calendar section.
		 *
		 * This account help in managing reservation calendar section plugin settings fields.
		 *
		 * @param array $fields Holds the fields array.
		 * @return array
		 */
		return apply_filters( 'ersrv_reservaton_calendar_section_plugin_settings', $fields );
	}
}

return new Easy_Reservations_Settings();
