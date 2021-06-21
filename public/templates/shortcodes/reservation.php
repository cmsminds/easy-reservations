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
<div class="ersrv-reservation-container">
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
							<form action="#">
								<div class="input-group mb-3">
									<input type="text" class="form-control date-input datepicker calendar-icon" aria-label="startday" />
								</div>
								<span class="to mb-3">to</span>
								<div class="input-group mb-3">
									<input type="text" class="form-control date-input datepicker calendar-icon" aria-label="endday" />
								</div>
								<label for="">No book items</label>
								<div class="custom-selectbox">
									<select name="viewchange" id="bookItems" class="selectpicker">
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="2">3</option>
									</select>
								</div>
							</form>
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
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
