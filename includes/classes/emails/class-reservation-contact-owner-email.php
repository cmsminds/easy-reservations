<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Reservation item - contact owner class
 *
 * @since 1.0.0
 * @extends \WC_Email
 */
class Reservation_Contact_Owner_Email extends WC_Email {
	/**
	 * Set email defaults.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Email slug we can use to filter other data.
		$this->id          = 'reservation_contact_owner_email';
		$this->title       = __( 'Contact Owner Requests to Item Owner', 'easy-reservations' );
		$this->description = __( 'An email sent to the item owner when the customer requests more info regarding the reservation item.', 'easy-reservations' );

		// For admin area to let the user know we are sending this email to administrators.
		$this->customer_email = false;
		$this->heading        = __( 'Reservation Item Contact Owner Request', 'easy-reservations' );

		// translators: placeholder is {blogname}, a variable that will be substituted when email is sent out.
		$this->subject = sprintf( _x( '[%s] Contact Owner Request', 'default email subject for contact requests emails sent to the item owner', 'easy-reservations' ), '{blogname}' );

		// Template paths.
		$this->template_html  = 'emails/reservation-item-contact-owner-html.php';
		$this->template_plain = 'emails/plain/reservation-item-contact-owner-plain.php';

		add_action( 'ersrv_send_reservation_item_contact_request_notification', array( $this, 'ersrv_ersrv_send_reservation_item_contact_request_notification_callback' ) );

		// Call parent constructor.
		parent::__construct();

		// Template base path.
		$this->template_base = ERSRV_CUSTOM_EMAIL_TEMPLATE_PATH;

		// Recipient.
		$this->recipient = $this->get_option( 'recipient', get_option( 'admin_email' ) );
	}

	public function ersrv_ersrv_send_reservation_item_contact_request_notification_callback() {
		$this->send(
			$this->get_recipient(),
			$this->get_subject(),
			$this->get_content(),
			$this->get_headers(),
			array()
		);
	}
} // end \Reservation_Contact_Owner_Email class
