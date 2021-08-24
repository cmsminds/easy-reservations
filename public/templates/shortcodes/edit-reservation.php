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

if ( ! empty( $order_id ) && ! empty( $action ) && 'edit-reservation' === $action ) {
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
	$page_title = apply_filters( 'ersrv_edit_reservation_page_title', $page_title, $order_id );

	// WooCommerce order.
	$wc_order = wc_get_order( $order_id );

	// Get the items.
	$line_items = $wc_order->get_items();
}
?>
<div class="wrapper edit-order-wrapper pb-5">
	<div class="section-title"><?php echo wp_kses_post( $page_title ); ?></div>
	<div class="contents">
		<div class="container">
			<?php
			if ( ! empty( $order_id ) && ! empty( $action ) && 'edit-reservation' === $action ) {
				foreach ( $line_items as $line_item ) {
					$item_id       = $line_item->get_id();
					$product_id    = $line_item->get_product_id();
		
					// Skip, if this is not a reservation item.
					if ( ! ersrv_product_is_reservation( $product_id ) ) {
						continue;
					}

					// Product image.
					$product_image_id  = get_post_thumbnail_id( $product_id );
					$product_image_url = ersrv_get_attachment_url_from_attachment_id( $product_image_id );

					// Get the other reservation details.
					$checkin_date  = wc_get_order_item_meta( $item_id, 'Checkin Date', true );
					$checkout_date = wc_get_order_item_meta( $item_id, 'Checkout Date', true );
					$adult_count   = (int) wc_get_order_item_meta( $item_id, 'Adult Count', true );
					$kid_count     = (int) wc_get_order_item_meta( $item_id, 'Kids Count', true );

					// Item total.
					$item_total = $line_item->get_total();
					?>
					<div class="card mb-3">
						<div class="row no-gutters">
							<div class="col-12 col-lg-4">
								<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>">
									<img src="<?php echo esc_url( $product_image_url ); ?>" alt="<?php echo wp_kses_post( get_the_title( $product_id ) ); ?>" class="card-img">
								</a>
							</div>
							<div class="col-12 col-lg-8">
								<div class="card-body">
									<h3 class="card-title">
										<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>"><?php echo wp_kses_post( get_the_title( $product_id ) ); ?></a>
									</h3>
									<div class="details">
										<div class="form-wrapper">
											<div class="bookingDates mb-3">
												<div class="row form-row input-daterange">
													<div class="col-6">
														<label for="" class="font-Poppins font-size-16 color-black"><?php esc_html_e( 'Checkin', 'easy-reservations' ); ?></label>
														<div><input type="text" value="<?php echo esc_html( $checkin_date ); ?>" class="ersrv-quick-view-item-checkin-date form-control date-control text-left rounded-lg hasDatepicker" placeholder="08/23/2021"></div>
													</div>
													<div class="col-6">
														<div class="d-flex justify-content-between">
															<label for="" class="font-Poppins font-size-16 color-black"><?php esc_html_e( 'Checkout', 'easy-reservations' ); ?></label>
															<a href="javascript:void(0);" class="btn-link text-theme-primary"><?php esc_html_e( 'Confirm Availability', 'easy-reservations' ); ?></a>
															<input type="hidden" class="confirmed-availability-of-items" value="-1" />
														</div>
														<div><input type="text" value="<?php echo esc_html( $checkout_date ); ?>" class="ersrv-quick-view-item-checkout-date form-control date-control text-left rounded-lg hasDatepicker" placeholder="08/24/2021"></div>
													</div>
												</div>
											</div>
										</div>
										<div class="form-wrapper">
											<div class="bookItems mb-3">
												<div class="row form-row input-daterange">
													<div class="col-6">
														<label for="" class="font-Poppins font-size-16 color-black"><?php esc_html_e( 'Adults', 'easy-reservations' ); ?></label>
														<div><input placeholder="No. of adults" type="number" class="form-control rounded-lg" value="<?php echo esc_html( $adult_count ); ?>" /></div>
													</div>
													<div class="col-6">
														<label for="" class="font-Poppins font-size-16 color-black"><?php esc_html_e( 'Kid(s)', 'easy-reservations' ); ?></label>
														<div><input placeholder="No. of kids" type="number" class="form-control rounded-lg" value="<?php echo esc_html( $kid_count ); ?>" /></div>
													</div>
												</div>
											</div>
										</div>
										<!-- <div class="form-wrapper">
											<div class="amenities mb-3">
												<label for="amenities" class="font-Poppins font-size-16 color-black"><?php // esc_html_e( 'Amenities', 'easy-reservations' ); ?></label>
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
												<div class="row form-row align-items-center justify-content-end">
													<div class="col-12 col-md-6 mb-4 mb-md-0">
														<h4 class="font-Poppins font-size-16 color-black font-weight-bold mb-0">
															<?php echo sprintf( __( 'Subtotal: %1$s', 'easy-reservations' ), wc_price( $item_total ) ); ?>
														</h4>
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
				<div class="group-update-btn d-flex align-items-center justify-content-center ersrv-update-reservation">
					<button class="btn btn-accent"><?php esc_html_e( 'Update Reservation', 'easy-reservations' ); ?></button>
				</div>
			<?php } else {
				$my_account = wc_get_page_permalink( 'myaccount' );
				?>
				<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
					<a class="woocommerce-Button button" href="<?php echo esc_url( $my_account ); ?>"><?php esc_html_e( 'My Account', 'woocommerce' ); ?></a>
					<?php esc_html_e( 'Invalid access.', 'easy-reservations' ); ?>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
