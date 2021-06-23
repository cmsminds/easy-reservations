<?php
/**
 * This file is used for templating the export reservations modal.
 *
 * @since 1.0.0
 * @package Easy_Reservations
 * @subpackage Easy_Reservations/includes
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

// Shortcode arguments.
$item_id             = ( ! empty( $args['id'] ) ) ? (int) $args['id'] : 0;
$reservation_heading = __( 'Start Your Reservation', 'easy-reservations' );
/**
 * This hook fires on the reservation section.
 *
 * This hook helps in modifying the heading text in the reservation template.
 *
 * @param string $reservation_heading Holds the reservation heading text.
 * @param int    $item_id Holds the item ID to be reserved.
 * @return string
 * @since 1.0.0
 */
$reservation_heading = apply_filters( 'ersrv_reservation_template_heading_text', $reservation_heading, $item_id );
?>
<div class="ersrv-reservation-container" data-item="<?php echo esc_attr( $item_id ); ?>">
	<div class="wrapper">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="calender-wrapper text-center">
						<h1 class="h1 mb-4 pb-3 mt-5"><?php echo wp_kses_post( $reservation_heading ); ?></h1>
						<div class="card">
							<div id="calendar"></div>
						</div>
					</div>
				</div>
				<div class="col-12 col-md-5">
					<div class="booking-date-details-wrapper">
						<div class="card">
							<h3><?php esc_html_e( 'Checkin/Checkout', 'easy-reservations' ); ?></h3>
							<div class="input-group mb-3">
								<input placeholder="<?php esc_html_e( 'Checkin date', 'easy-reservations' ); ?>" type="text" class="form-control date-input datepicker calendar-icon" aria-label="startday" />
							</div>
							<span class="to mb-3"><?php esc_html_e( 'to', 'easy-reservations' ); ?></span>
							<div class="input-group mb-3">
								<input placeholder="<?php esc_html_e( 'Checkout date', 'easy-reservations' ); ?>" type="text" class="form-control date-input datepicker calendar-icon" aria-label="endday" />
							</div>
						</div>
						<div class="card">
							<h3><?php esc_html_e( 'Accomodation', 'easy-reservations' ); ?></h3>
							<p>
								<label for="ersrv-accomodation-adult"><?php esc_html_e( 'Adults', 'easy-reservations' ); ?></label>
								<input placeholder="0" type="number" id="ersrv-accomodation-adult" class="form-control " aria-label="accomodation-adult" />
							</p>
							<p>
								<label for="ersrv-accomodation-kids"><?php esc_html_e( 'Kid(s)', 'easy-reservations' ); ?></label>
								<input placeholder="0" type="number" id="ersrv-accomodation-kids" class="form-control " aria-label="accomodation-kids" />
							</p>
						</div>
						<div class="card">
							<h3><?php esc_html_e( 'Amenities', 'easy-reservations' ); ?></h3>
						</div>
					</div>
				</div>
				<div class="col-12 col-md-7">
					<div class="reservation-wrapper">
						<div class="card">
							<h2 class="h4">Reservation</h2>
							<div class="dropdown-divider"></div>
							<table>
								<tr>
									<td>Check in</td>
									<td>June 07, 2021</td>
								</tr>
								<tr>
									<td>Check out</td>
									<td>June 14, 2021</td>
								</tr>
								<tr>
									<td>No book items</td>
									<td>1</td>
								</tr>
								<tr>
									<td>Price</td>
									<td>$6000</td>
								</tr>
								<tr>
									<td colspan="2">
										<div class="dropdown-divider"></div>
									</td>
								</tr>
								<tr class="tax">
									<td>Local and state tax</td>
									<td>
										+7.85%
										<span class="d-block text-green font-weight-semibold">Included in price </span>
									</td>
								</tr>
								<tr class="empty"> <!-- this row for spacing -->
									<td class="py-3" colspan="2">&nbsp;</td>
								</tr>
								<tr class="deposit-row bg-7a7a7a">
									<td class="text-white pl-3">Deposit</td>
									<td class="text-white pr-3">$1000</td>
								</tr>
								<tr class="empty"> <!-- this row for spacing -->
									<td class="small" colspan="2">&nbsp;</td>
								</tr>
								<tr class="total-row bg-464646">
									<td class="text-white pl-3">Toatal</td>
									<td class="text-white pr-3">$6000</td>
								</tr>
							</table>
						</div>
						<div class="card">
							<button class="ersrv-proceed-with-reservation-details reservation-button" type="button"><?php esc_html_e( 'Proceed with reservation details', 'easy-reservations' ); ?></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
