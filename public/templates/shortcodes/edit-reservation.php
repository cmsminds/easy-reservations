<?php

/**
 * This file is used for templating the edit reservation by customers.
 *
 * @since 1.0.0
 * @package Easy_Reservations
 * @subpackage Easy_Reservations/includes
 */

defined('ABSPATH') || exit; // Exit if accessed directly.

// Requested query arguments.
$action      = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );
$order_id    = (int) filter_input( INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT );
$page_title  = get_the_title();
$valid_order = false;

if ( ! is_null( $order_id ) && ! is_null( $action ) ) {
	// WooCommerce order.
	$wc_order = wc_get_order( $order_id );
	
	// Check for the valid action and WooCommerce order.
	if ( 'edit-reservation' === $action && false !== $wc_order ) {
		$valid_order = true;
		$page_title .= ': #' . $order_id;

		/**
		 * This hook executed on the edit reservation page.
		 *
		 * This filter helps modifying the page's main title.
		 *
		 * @param string $page_title Page title.
		 * @param int    $order_id WooCommerce order ID.
		 * @return string
		 */
		$page_title = apply_filters('ersrv_edit_reservation_page_title', $page_title, $order_id);

		// View order link.
		$view_order_link = $wc_order->get_view_order_url();

		// Get the order total.
		$order_total = (float) $wc_order->get_total();

		// Get the items.
		$line_items = $wc_order->get_items();

		// Cancel update link.
		$cancel_reservation = "location.href = '{$view_order_link}'";
	}
}
?>
<div class="wrapper edit-order-wrapper pb-5">
	<div class="section-title"><?php echo wp_kses_post($page_title); ?></div>
	<?php
	if ( $valid_order ) {
		/**
		 * This hook executes on edit reservation page.
		 *
		 * This filter helps adding some content after the main title.
		 *
		 * @param int $order_id WooCommerce order ID.
		 */
		do_action( 'ersrv_edit_reservation_after_main_title', $order_id );
	}
	?>
	<div class="contents">
		<div class="container">
			<?php
			if ( $valid_order ) {
				foreach ( $line_items as $line_item ) {
					$item_id    = $line_item->get_id();
					$product_id = $line_item->get_product_id();

					// Skip, if this is not a reservation item.
					if (!ersrv_product_is_reservation($product_id)) {
						continue;
					}

					// Product image.
					$product_image_id  = get_post_thumbnail_id($product_id);
					$product_image_url = ersrv_get_attachment_url_from_attachment_id($product_image_id);

					// Get the other reservation details.
					$checkin_date       = wc_get_order_item_meta($item_id, 'Checkin Date', true);
					$checkout_date      = wc_get_order_item_meta($item_id, 'Checkout Date', true);
					$adult_count        = wc_get_order_item_meta($item_id, 'Adult Count', true);
					$adult_subtotal     = wc_get_order_item_meta($item_id, 'Adult Subtotal', true);
					$kid_count          = wc_get_order_item_meta($item_id, 'Kids Count', true);
					$kid_subtotal       = wc_get_order_item_meta($item_id, 'Kids Subtotal', true);
					$security_amount    = wc_get_order_item_meta($item_id, 'Security Amount', true);
					$amenities_subtotal = wc_get_order_item_meta($item_id, 'Amenities Subtotal', true);

					// Item total.
					$item_total = $line_item->get_total();

					// Get the reservation item details.
					$item_details           = ersrv_get_item_details($product_id);
					$adult_charge           = (!empty($item_details['adult_charge'])) ? $item_details['adult_charge'] : 0;
					$kid_charge             = (!empty($item_details['kid_charge'])) ? $item_details['kid_charge'] : 0;
					$security_amount        = (!empty($item_details['security_amount'])) ? $item_details['security_amount'] : 0;
					$accomodation_limit     = (!empty($item_details['accomodation_limit'])) ? $item_details['accomodation_limit'] : '';
					$min_reservation_period = (!empty($item_details['min_reservation_period'])) ? $item_details['min_reservation_period'] : '';
					$max_reservation_period = (!empty($item_details['max_reservation_period'])) ? $item_details['max_reservation_period'] : '';
					?>
					<div class=" ersrv-edit-reservation-item-card card mb-3" data-productid="<?php echo esc_attr($product_id); ?>" data-itemid="<?php echo esc_attr($item_id); ?>">
						<div class="row no-gutters">
							<div class="col-12 col-lg-4">
								<a href="<?php echo esc_url(get_permalink($product_id)); ?>">
									<img src="<?php echo esc_url($product_image_url); ?>" alt="<?php echo wp_kses_post(get_the_title($product_id)); ?>" class="card-img">
								</a>
							</div>
							<div class="col-12 col-lg-8">
								<div class="card-body">
									<h3 class="card-title">
										<a href="<?php echo esc_url(get_permalink($product_id)); ?>"><?php echo wp_kses_post(get_the_title($product_id)); ?></a>
									</h3>
									<div class="details">
										<div class="form-wrapper">
											<div class="bookingDates mb-3">
												<div class="row form-row input-daterange">
													<div class="col-12 col-md-6">
														<label for="ersrv-edit-reservation-item-checkin-date-<?php echo esc_attr($item_id); ?>" class="font-Poppins font-size-16 color-black"><?php esc_html_e('Checkin', 'easy-reservations'); ?></label>
														<div>
															<input type="text" id="ersrv-edit-reservation-item-checkin-date-<?php echo esc_attr($item_id); ?>" value="<?php echo esc_html($checkin_date); ?>" data-oldval="<?php echo esc_html($checkin_date); ?>" class="ersrv-edit-reservation-item-value ersrv-edit-reservation-item-checkin-date form-control date-control text-left rounded-lg" placeholder="<?php echo esc_attr(ersrv_get_php_date_format()); ?>">
														</div>
													</div>
													<div class="col-12 col-md-6">
														<label for="ersrv-edit-reservation-item-checkout-date-<?php echo esc_attr($item_id); ?>" class="font-Poppins font-size-16 color-black"><?php esc_html_e('Checkout', 'easy-reservations'); ?></label>
														<div><input type="text" id="ersrv-edit-reservation-item-checkout-date-<?php echo esc_attr($item_id); ?>" value="<?php echo esc_html($checkout_date); ?>" data-oldval="<?php echo esc_html($checkout_date); ?>" class="ersrv-edit-reservation-item-value ersrv-edit-reservation-item-checkout-date form-control date-control text-left rounded-lg" placeholder="<?php echo esc_attr(ersrv_get_php_date_format()); ?>"></div>
													</div>
													<div class="col-12">
														<div class="d-flex flex-wrap ersrv-edit-reservation-dates-indicators mb-3 pt-2">
															<div class="ersrv-available-dates-notifier"><span><?php esc_html_e('Available Dates', 'easy-reservations'); ?></span></div>
															<div class="ersrv-order-reserved-dates-notifier ml-md-3"><span><?php esc_html_e('Order Reserved Dates', 'easy-reservations'); ?></span></div>
															<div class="ersrv-unavailable-dates-notifier ml-md-3"><span><?php esc_html_e('Unvailable Dates', 'easy-reservations'); ?></span></div>
														</div>
													</div>
													<p class="ersrv-reservation-error" id="checkin-checkout-dates-error-<?php echo esc_attr($item_id); ?>"></p>
												</div>
											</div>
										</div>
										<div class="form-wrapper">
											<div class="bookItems mb-3">
												<div class="row form-row input-daterange">
													<div class="col-12 col-md-6">
														<div class="position-relative">
															<label for="ersrv-edit-reservation-item-adult-count-<?php echo esc_attr($item_id); ?>" class="font-Poppins font-size-16 color-black"><?php esc_html_e( 'Adults', 'easy-reservations' ); ?></label>
															<div><input onkeypress="return /[0-9]/i.test(event.key)" id="ersrv-edit-reservation-item-adult-count-<?php echo esc_attr($item_id); ?>" placeholder="<?php esc_html_e( 'No. of adults', 'easy-reservations' ); ?>" type="text" min="<?php echo esc_html( $adult_count ); ?>" class="ersrv-edit-reservation-item-value ersrv-edit-reservation-item-adult-count form-control rounded-lg" value="<?php echo esc_html($adult_count); ?>" data-oldval="<?php echo esc_html($adult_count); ?>" /></div>
														</div>
													</div>
													<div class="col-12 col-md-6">
														<div class="position-relative">
															<label for="ersrv-edit-reservation-item-kid-count-<?php echo esc_attr($item_id); ?>" class="font-Poppins font-size-16 color-black"><?php esc_html_e( 'Kid(s)', 'easy-reservations' ); ?></label>
															<div><input onkeypress="return /[0-9]/i.test(event.key)" id="ersrv-edit-reservation-item-kid-count-<?php echo esc_attr($item_id); ?>" placeholder="<?php esc_html_e( 'No. of kids', 'easy-reservations' ); ?>" type="text" min="<?php echo esc_html($kid_count); ?>" class="ersrv-edit-reservation-item-value ersrv-edit-reservation-item-kid-count form-control rounded-lg" value="<?php echo esc_html($kid_count); ?>" data-oldval="<?php echo esc_html($kid_count); ?>" /></div>
														</div>
													</div>
													<p class="ersrv-reservation-error" id="guests-error-<?php echo esc_attr($item_id); ?>"></p>
												</div>
											</div>
										</div>
										<!-- <div class="form-wrapper">
											<div class="amenities mb-3">
												<label for="amenities" class="font-Poppins font-size-16 color-black"><?php // esc_html_e( 'Amenities', 'easy-reservations' ); 
																														?></label>
												<div class="d-flex flex-wrap align-items-center justify-content-start">
													<div class="custom-control custom-switch ersrv-single-amenity-block mr-3" data-cost_type="per_day" data-cost="500" data-amenity="Free Siteseeing">
														<input type="checkbox" class="custom-control-input ersrv-new-reservation-single-amenity" id="amenity-free-siteseeing" checked>
														<label class="custom-control-label font-size-15" for="amenity-free-siteseeing">
															<span class="d-block font-lato font-weight-bold color-black pb-2">Free Siteseeing - <span class="font-lato font-weight-bold color-accent"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>500</bdi></span></span></span>
														</label>
													</div>
													<div class="custom-control custom-switch ersrv-single-amenity-block mr-3" data-cost_type="per_day" data-cost="500" data-amenity="Traking">
														<input type="checkbox" class="custom-control-input ersrv-new-reservation-single-amenity" id="amenity-traking">
														<label class="custom-control-label font-size-15" for="amenity-traking">
															<span class="d-block font-lato font-weight-bold color-black pb-2">Traking - <span class="font-lato font-weight-bold color-accent"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>500</bdi></span></span></span>
														</label>
													</div>

												</div>
											</div>
										</div> -->
										<div class="form-wrapper">
											<div class="CTA">
												<div class="row form-row align-items-center">
													<div class="col-12 col-md-5 mb-4 mb-md-0">
														<h4 class="font-Poppins font-size-16 color-black font-weight-bold mb-0">
															<?php echo sprintf(__('Subtotal: %2$s%1$s%3$s', 'easy-reservations'), wc_price($item_total), '<span id="ersrv-edit-reservation-item-subtotal-' . $item_id . '">', '</span>'); ?>
															<a class="ersrv-split-reservation-cost text-theme-primary" href="javascript:void(0);" data-toggle="modal" data-target="#summaryModal"><?php esc_html_e('Know More', 'easy-reservations'); ?></a>
														</h4>
													</div>
													<div class="col-12 col-md-7 mb-6 text-right">
														<button class="btn btn-accent non-clickable ersrv-edit-reservation-validate-item-changes"><?php esc_html_e('Validate Changes', 'easy-reservations'); ?></button>
														<input type="hidden" id="confirmed-validation-of-item-<?php echo esc_attr($item_id); ?>" value="1" />

														<!-- ITEM DETAILS -->
														<input type="hidden" id="accomodation-limit-<?php echo esc_attr($item_id); ?>" value="<?php echo esc_html($accomodation_limit); ?>" />
														<input type="hidden" id="min-reservation-period-<?php echo esc_attr($item_id); ?>" value="<?php echo esc_html($min_reservation_period); ?>" />
														<input type="hidden" id="max-reservation-period-<?php echo esc_attr($item_id); ?>" value="<?php echo esc_html($max_reservation_period); ?>" />
														<input type="hidden" id="adult-charge-<?php echo esc_attr($item_id); ?>" value="<?php echo esc_html($adult_charge); ?>" />
														<input type="hidden" id="kid-charge-<?php echo esc_attr($item_id); ?>" value="<?php echo esc_html($kid_charge); ?>" />
														<input type="hidden" id="security-amount-<?php echo esc_attr($item_id); ?>" value="<?php echo esc_html($security_amount); ?>" />
														<input type="hidden" id="datepicker-initiated-<?php echo esc_attr($item_id); ?>" value="-1" />
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>

				<!-- UPDATE RESERVATION -->
				<div class="group-update-btn d-flex align-items-center justify-content-center ersrv-update-reservation flex-wrap">
					<input type="hidden" class="ersrv-edit-reservation-order-total" value="<?php echo esc_html($order_total); ?>" />
					<input type="hidden" class="ersrv-order-id" value="<?php echo esc_attr($order_id); ?>" />
					<div>
						<p class="font-lato font-size-16 color-black mb-3 text-center"><?php echo sprintf(__('Cost difference: %2$s%1$s%3$s (to be paid by the customer on arrival)', 'easy-reservations'), wc_price(0), '<span class="ersrv-edit-reservation-cost-difference">', '</span>'); ?></p>
					</div>
					<div class="ml-auto mr-3">
						<button class="btn btn-secondary cancel" onclick="<?php echo esc_attr($cancel_reservation); ?>"><?php esc_html_e('Cancel', 'easy-reservations'); ?></button>
					</div>
					<div class="">
						<button class="btn btn-accent update non-clickable"><?php esc_html_e('Update Reservation', 'easy-reservations'); ?></button>
					</div>
				</div>
			<?php
			} else {
				$my_account = wc_get_page_permalink('myaccount');
				?>
				<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
					<a class="woocommerce-Button button" href="<?php echo esc_url($my_account); ?>"><?php esc_html_e('My Account', 'woocommerce'); ?></a>
					<?php esc_html_e('Invalid access.', 'easy-reservations'); ?>
				</div>
			<?php } ?>
		</div>
	</div>
	
	<!-- Modal for summery-->
	<div class="modal fade" id="summaryModal" tabindex="-1" aria-labelledby="summaryModal" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm">
			<div class="modal-content border-0">
				<!-- <div class="modal-header bg-transparent border-0">
					<h5 class="modal-title" id="summaryModal">Modal title</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div> -->
				<div class="modal-body">
					<div class="ersrv-edit-reservation-item-summary" id="ersrv-edit-reservation-item-summary-<?php echo esc_attr($item_id); ?>">
						<div class="ersrv-edit-reservation-item-summary-wrapper p-3">
							<table class="table table-borderless">
								<tbody>
									<tr class="item-price-summary" id="item-price-summary-<?php echo esc_attr($item_id); ?>">
										<th><?php esc_html_e('Adults:', 'easy-reservations'); ?></th>
										<td>
											<span class="ersrv-cost font-lato font-weight-bold color-accent">
												<?php
												echo wp_kses(
													wc_price($adult_subtotal),
													array(
														'span' => array(
															'class' => array(),
														),
													)
												);
												?>
											</span>
										</td>
									</tr>
									<tr class="kids-charge-summary" id="kids-charge-summary-<?php echo esc_attr($item_id); ?>">
										<th><?php esc_html_e('Kids:', 'easy-reservations'); ?></th>
										<td>
											<span class="ersrv-cost font-lato font-weight-bold color-accent">
												<?php
												echo wp_kses(
													wc_price($kid_subtotal),
													array(
														'span' => array(
															'class' => array(),
														),
													)
												);
												?>
											</span>
										</td>
									</tr>
									<tr class="amenities-summary" id="amenities-summary-<?php echo esc_attr($item_id); ?>">
										<th><?php esc_html_e('Amenities:', 'easy-reservations'); ?></th>
										<td>
											<span class="ersrv-cost font-lato font-weight-bold color-accent">
												<?php
												echo wp_kses(
													wc_price($amenities_subtotal),
													array(
														'span' => array(
															'class' => array(),
														),
													)
												);
												?>
											</span>
										</td>
									</tr>
									<tr class="security-summary" id="security-summary-<?php echo esc_attr($item_id); ?>">
										<th><?php esc_html_e('Security:', 'easy-reservations'); ?></th>
										<td>
											<span class="ersrv-cost font-lato font-weight-bold color-accent">
												<?php
												echo wp_kses(
													wc_price($security_amount),
													array(
														'span' => array(
															'class' => array(),
														),
													)
												);
												?>
											</span>
										</td>
									</tr>
									<tr class="edit-reservation-item-total-cost" id="edit-reservation-item-total-cost-<?php echo esc_attr($item_id); ?>">
										<th><?php esc_html_e('Total:', 'easy-reservations'); ?></th>
										<td>
											<span class="ersrv-cost font-lato font-weight-bold color-accent">
												<?php
												echo wp_kses(
													wc_price($item_total),
													array(
														'span' => array(
															'class' => array(),
														),
													)
												);
												?>
											</span>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>