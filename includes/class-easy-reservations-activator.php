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

		/**
		 * Setup the cron to send reservation reminder emails.
		 *
		 * Setup the daily cron.
		 */
		if ( ! wp_next_scheduled( 'ersrv_reservation_reminder_email_notifications' ) ) {
			wp_schedule_event( time(), 'daily', 'ersrv_reservation_reminder_email_notifications' );
		}

		// Create pages useful for ths is plugin.
		$pages = array(
			array(
				'post_title'   => __( 'Search Reservations', 'easy-reservations' ),
				'post_content' => '[ersrv_search_reservations]',
				'post_status'  => 'publish',
				'post_author'  => 1,
				'post_type'    => 'page'
			),
			array(
				'post_title'   => __( 'Edit Reservation', 'easy-reservations' ),
				'post_content' => '[ersrv_edit_reservation]',
				'post_status'  => 'publish',
				'post_author'  => 1,
				'post_type'    => 'page'
			)
		);

		if ( ! empty( $pages ) && is_array( $pages ) ) {
			foreach ( $pages as $page_data ) {
				$page_exists = get_page_by_title( $page_data['post_title'] );

				// Skip the page creation, if that already exists by title.
				if ( ! is_null( $page_exists ) ) {
					continue;
				}

				$page_id = wp_insert_post( $page_data );
				$page    = get_post( $page_id );
				update_option( 'ersrv_' . $page->post_name . '_page_id', $page_id, false );
			}
		}
	}
}
