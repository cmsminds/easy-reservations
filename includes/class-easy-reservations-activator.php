<?php
/**
 * Fired during plugin activation
 *
 * @link       https://www.cmsminds.com/
 * @since      1.0.0
 *
 * @package    Easy_Reservations
 * @subpackage Easy_Reservations/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Easy_Reservations
 * @subpackage Easy_Reservations/includes
 * @author     cmsMinds <info@cmsminds.com>
 */
class Easy_Reservations_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		/**
		 * Setup the cron to delete the PDF files generated while emailing the reservation PDF receipts to the customers.
		 *
		 * Setup the daily cron.
		 */
		if ( ! wp_next_scheduled( 'ersrv_delete_reservation_pdf_receipts' ) ) {
			wp_schedule_event( time(), 'daily', 'ersrv_delete_reservation_pdf_receipts' );
		}
	}
}
