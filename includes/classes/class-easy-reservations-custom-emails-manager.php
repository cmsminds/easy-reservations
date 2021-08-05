<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Custom email templates manager class.
 *
 * @link       https://www.cmsminds.com/
 * @since      1.0.0
 *
 * @package    Easy_Reservations
 * @subpackage Easy_Reservations/includes/classes
 */

/**
 * Custom email templates manager class.
 *
 * Defines the custom email templates and notifications.
 *
 * @package    Easy_Reservations
 * @subpackage Easy_Reservations/includes/classes
 * @author     cmsMinds <info@cmsminds.com>
 */
class Easy_Reservations_Custom_Email_Manager {
	/**
	 * Constructor to help define actions.
	 */
	public function __construct() {
		// Template path.
		define( 'ERSRV_CUSTOM_EMAIL_TEMPLATE_PATH', ERSRV_PLUGIN_PATH . 'public/templates/emails/' );
		// Hook when the email should be fired.
		add_action( 'ersrv_save_contact_owner_request_before', array( &$this, 'ersrv_ersrv_save_contact_owner_request_before_callback' ) );
		// Include all the email files.
		add_filter( 'woocommerce_email_classes', array( &$this, 'ersrv_woocommerce_email_classes_callback' ) );
	}

	public function ersrv_ersrv_save_contact_owner_request_before_callback() {
		new WC_Emails();
		do_action( 'ersrv_send_notification_to_item_owner' );
	}

	/**
	 * Add custom class to send reservation emails.
	 *
	 * @param array $email_classes Email classes array.
	 * @return array
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_email_classes_callback( $email_classes ) {
		// Return, if the class already exists.
		if ( class_exists( 'Reservation_Contact_Owner_Email' ) ) {
			return $email_classes;
		}

		// Require the class file.
		require_once ERSRV_PLUGIN_PATH . 'includes/classes/class-reservation-contact-owner-email.php';

		// Put in the classes into existing classes.
		$email_classes['Reservation_Contact_Owner_Email'] = new Reservation_Contact_Owner_Email();

		return $email_classes;
	}
}

new Easy_Reservations_Custom_Email_Manager();
