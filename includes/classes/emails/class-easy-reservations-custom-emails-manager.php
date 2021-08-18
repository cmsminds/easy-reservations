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
		define( 'ERSRV_CUSTOM_EMAIL_TEMPLATE_PATH', ERSRV_PLUGIN_PATH . 'admin/templates/emails/' );
		add_action( 'ersrv_email_contact_owner_request', array( &$this, 'ersrv_ersrv_email_contact_owner_request_callback' ) );
		add_action( 'ersrv_send_reservation_reminder_email', array( &$this, 'ersrv_ersrv_send_reservation_reminder_email_callback' ), 10, 2 );
		add_action( 'ersrv_email_after_reservation_cancellation_request', array( &$this, 'ersrv_ersrv_email_after_reservation_cancellation_request_callback' ), 10, 2 );
		add_filter( 'woocommerce_email_classes', array( &$this, 'ersrv_woocommerce_email_classes_callback' ) );
	}

	/**
	 * Send notification as soon someone contact reservation item via contact form.
	 *
	 * @since 1.0.0
	 */
	public function ersrv_ersrv_email_contact_owner_request_callback( $item_author_email ) {
		new WC_Emails();
		/**
		 * This action fires when someone submits contact request on some reservation item.
		 *
		 * @param string $item_author_email Item author email.
		 * @since 1.0.0
		 */
		do_action( 'ersrv_send_reservation_item_contact_request_notification', $item_author_email );
	}

	/**
	 * Send notification for the reservation reminder to the customers.
	 *
	 * @param object $line_item WooCommerce line item object.
	 * @param int    $order_id WooCommerce order ID.
	 * @since 1.0.0
	 */
	public function ersrv_ersrv_send_reservation_reminder_email_callback( $line_item, $order_id ) {
		new WC_Emails();
		/**
		 * This hook fires on the reminder emails cron.
		 *
		 * This hook is helpful in managing actions while sending emails.
		 *
		 * @param object $line_item WooCommerce line item object.
		 * @param int $order_id WooCommerce order ID.
		 * @since 1.0.0 
		 */
		do_action( 'ersrv_send_reservation_reminder_notification', $line_item, $order_id );
	}

	/**
	 * Send notification for the reservation cancellation request to the site administrator.
	 *
	 * @param object $line_item_id WooCommerce line item id.
	 * @param int    $order_id WooCommerce order ID.
	 * @since 1.0.0
	 */
	public function ersrv_ersrv_email_after_reservation_cancellation_request_callback( $line_item_id, $order_id ) {
		new WC_Emails();
		/**
		 * This hook fires when there is new cancellation request for any reservation.
		 *
		 * This hook is helpful in managing actions while sending emails.
		 *
		 * @param object $line_item_id WooCommerce line item id.
		 * @param int    $order_id WooCommerce order ID.
		 * @since 1.0.0 
		 */
		do_action( 'ersrv_send_reservation_cancellation_request_notification', $line_item_id, $order_id );
	}

	/**
	 * Add custom class to send reservation emails.
	 *
	 * @param array $email_classes Email classes array.
	 * @return array
	 * @since 1.0.0
	 */
	public function ersrv_woocommerce_email_classes_callback( $email_classes ) {
		// Contact owner email.
		require_once 'class-reservation-contact-owner-email.php'; // Require the class file.
		$email_classes['Reservation_Contact_Owner_Email'] = new Reservation_Contact_Owner_Email(); // Put in the classes into existing classes.

		// Reservation remminder email.
		require_once 'class-reservation-reminder-email.php'; // Require the class file.
		$email_classes['Reservation_Reminder_Email'] = new Reservation_Reminder_Email(); // Put in the classes into existing classes.

		// Reservation cancellation request email.
		require_once 'class-reservation-cancellation-request-email.php'; // Require the class file.
		$email_classes['Reservation_Cancellation_Request_Email'] = new Reservation_Cancellation_Request_Email(); // Put in the classes into existing classes.

		return $email_classes;
	}
}

new Easy_Reservations_Custom_Email_Manager();
