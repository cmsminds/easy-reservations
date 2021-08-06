<?php
/**
 * This file is used for templating the single reservation item.
 *
 * @since 1.0.0
 * @package Easy_Reservations
 * @subpackage Easy_Reservations/public/templates/woocommerce
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

// Header.
get_header();

$item_post    = get_post( get_the_ID() );
$item_details = ersrv_get_item_details( $item_post->ID );

// Cost details.
$adult_charge = ( ! empty( $item_details['adult_charge'] ) ) ? $item_details['adult_charge'] : 0;
$kid_charge   = ( ! empty( $item_details['kid_charge'] ) ) ? $item_details['kid_charge'] : 0;

// Amenities.
$amenities = ( ! empty( $item_details['amenities'] ) ) ? $item_details['amenities'] : array();

// Location.
$api_key = ersrv_get_plugin_settings( 'ersrv_google_maps_api_key' );

// Security amount.
$security_amount = ( ! empty( $item_details['security_amount'] ) ) ? $item_details['security_amount'] : 0;

// Accomodation Limit.
$accomodation_limit = ( ! empty( $item_details['accomodation_limit'] ) ) ? $item_details['accomodation_limit'] : '';

// Reservation Limits.
$min_reservation_period = ( ! empty( $item_details['min_reservation_period'] ) ) ? $item_details['min_reservation_period'] : '';
$max_reservation_period = ( ! empty( $item_details['max_reservation_period'] ) ) ? $item_details['max_reservation_period'] : '';

// Reservation Item Type.
$types    = wp_get_object_terms( $item_post->ID, 'reservation-item-type' );
$type_str = '';

if ( ! empty( $types ) && is_array( $types ) ) {
	foreach ( $types as $type_obj ) {
		$type_links[] = '<a href="' . get_category_link( $type_obj->term_id ) . '">' . $type_obj->name . '</a>';
	}

	$type_str = implode( ', ', $type_links );
}

// Current date.
$php_date_format = ersrv_get_php_date_format();
$curr_date       = ersrv_get_current_date( $php_date_format );
$next_date       = gmdate( $php_date_format, ( strtotime( 'now' ) + 86400 ) );

// Reservation item types.
$reservation_item_types = get_terms(
	array(
		'taxonomy' => 'reservation-item-type',
		'hide_empty' => true,
	)
);

// WooCommerce currency.
$woo_currency = get_woocommerce_currency_symbol();
?>
<section class="wrapper single-reserve-page" id="wrapper" data-item="<?php echo esc_attr( $item_post->ID ); ?>">
	<div class="banner text-center">
		<div class="container">
			<div class="details mx-auto font-lato">
				<div class="page-title mb-3">
					<h1 class="font-Poppins font-size-40 font-weight-semibold color-white"><?php echo wp_kses_post( $item_post->post_title ); ?></h1>
				</div>
				<div class="review d-flex justify-content-center align-items-center color-white mb-3 font-size-17 font-weight-normal">
					<img src="<?php echo esc_url (ERSRV_PLUGIN_URL . 'public/images/stars.png' ); ?>" alt="stars">
					<span class="ml-2">(1 review)</span>
				</div>
				<div class="boat-options d-flex justify-content-center align-items-center color-white font-size-16 flex-column flex-md-row">
					<?php if ( ! empty( $type_str ) ) { ?>
						<div class="d-flex align-items-center first mb-2 mb-md-0 mr-3 pr-1">
							<img src="<?php echo esc_url (ERSRV_PLUGIN_URL . 'public/images/Ship-icon.png' ); ?>" alt="">
							<span class="ml-2 font-weight-medium"><?php echo wp_kses_post( $type_str ); ?></span>
						</div>
					<?php } ?>
					<div class="d-flex align-items-center second mb-2 mb-md-0 mr-3 pr-1">
						<img src="<?php echo esc_url (ERSRV_PLUGIN_URL . 'public/images/user-icon.png' ); ?>" alt="">
						<span class="ml-2 font-weight-medium">With Capitanicon</span>
					</div>
					<div class="d-flex align-items-center third mb-2 mb-md-0">
						<img src="<?php echo esc_url (ERSRV_PLUGIN_URL . 'public/images/3d-box.png' ); ?>" alt="">
						<span class="ml-2 font-weight-medium">4</span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="content-part">
		<div class="container">
			<div class="row">
				<div class="col-12 col-lg-7 col-xl-8">
					<div class="ship-features info-box">
						<h3 class="section-title font-Poppins font-size-24 font-weight-bold d-block color-black text-decoration-none show-on-load">
							<span class=""><?php esc_html_e( 'Ship Features', 'easy-reservations' ); ?></span>
						</h3>
						<div class="ship-inner-features show show-on-load" id="ship-features-collapse">
							<div class="dropdown-divider"></div>
							<div class="card">
								<div class="datepicker datepicker-inline ersrv-item-availability-calendar"></div>
								<div class="d-flex flex-wrap flex-column">
									<div class="ersrv-available-dates-notifier"><span><?php esc_html_e( 'Available Dates', 'easy-reservations' ); ?></span></div>
									<div class="ersrv-unavailable-dates-notifier"><span><?php esc_html_e( 'Unvailable Dates', 'easy-reservations' ); ?></span></div>
								</div>
							</div>
						</div>
					</div>
					<div class="ship-description info-box">
						<a class="section-title font-Poppins font-size-24 font-weight-bold d-block color-black text-decoration-none" data-toggle="collapse" href="#ship-description-collapse" role="button" aria-expanded="true" aria-controls="ship-description-collapse">
							<span class=""><?php esc_html_e( 'Ship Description', 'easy-reservations' ); ?></span>
						</a>
						<div class="collapse show" id="ship-description-collapse">
							<div class="dropdown-divider"></div>
							<?php echo wp_kses_post( $item_post->post_content ); ?>
						</div>
					</div>
					<div class="price-details info-box">
						<a class="section-title font-Poppins font-size-24 font-weight-bold d-block color-black text-decoration-none" data-toggle="collapse" href="#price-details-collapse" role="button" aria-expanded="false" aria-controls="price-details-collapse">
							<span class=""><?php esc_html_e( 'Price Details', 'easy-reservations' ); ?></span>
						</a>
						<div class="collapse" id="price-details-collapse">
							<div class="dropdown-divider"></div>
							<?php echo sprintf( __( '%1$sPer adult: %3$s%2$s', 'easy-reservations' ), '<p>', '</p>', wc_price( $adult_charge ) ); ?>
							<?php echo sprintf( __( '%1$sPer kid: %3$s%2$s', 'easy-reservations' ), '<p>', '</p>', wc_price( $kid_charge ) ); ?>
						</div>
					</div>
					<div class="ship-location info-box">
						<a class="section-title font-Poppins font-size-24 font-weight-bold d-block color-black text-decoration-none" data-toggle="collapse" href="#ship-location-collapse" role="button" aria-expanded="false" aria-controls="ship-location-collapse">
							<span class=""><?php esc_html_e( 'Ship Location', 'easy-reservations' ); ?></span>
						</a>
						<div class="collapse" id="ship-location-collapse">
							<div class="dropdown-divider"></div>
							<iframe width="100%" height="400px" src="https://www.google.com/maps/embed/v1/place?key=<?php echo $api_key; ?>&q=Space+Needle,Seattle+WA" style="border:0" loading="lazy" allowfullscreen></iframe>
						</div>
					</div>
					<div class="ship-details info-box">
						<a class="section-title font-Poppins font-size-24 font-weight-bold d-block color-black text-decoration-none" data-toggle="collapse" href="#ship-details-collapse" role="button" aria-expanded="false" aria-controls="ship-details-collapse">
							<span class=""><?php esc_html_e( 'Ship Details', 'easy-reservations' ); ?></span>
						</a>
						<div class="collapse" id="ship-details-collapse">
							<div class="dropdown-divider"></div>
							<div class="security-amount">
								<h4><?php esc_html_e( 'Security', 'easy-reservations' ); ?></h4>
								<p><?php echo wp_kses(
									wc_price( $security_amount ),
									array(
										'span' => array(
											'class' => array(),
										),
									)
								); ?></p>
							</div>
							<div class="accomodation-limits">
								<h4><?php esc_html_e( 'Accomodation Limit', 'easy-reservations' ); ?></h4>
								<p><?php echo esc_html( $accomodation_limit ); ?></p>
							</div>
							<div class="reservation-limits">
								<h4><?php esc_html_e( 'Reservation Limits', 'easy-reservations' ); ?></h4>
								<?php if ( ! empty( $min_reservation_period ) ) { ?>
									<p><?php echo sprintf( __( 'Minimum: %1$d days', 'easy-reservations' ), $min_reservation_period ); ?></p>
								<?php } ?>

								<?php if ( ! empty( $max_reservation_period ) ) { ?>
									<p><?php echo sprintf( __( 'Maximum: %1$d days', 'easy-reservations' ), $max_reservation_period ); ?></p>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12 col-lg-5 col-xl-4">
					<div class="sidebar-wrapper">
						<div class="price-box bgcolor-accent rounded-xl py-2">
							<div class="d-flex align-items-center justify-content-center py-1">
								<?php
								echo wp_kses(
									wc_price( $adult_charge ),
									array(
										'span' => array(
											'class' => array(),
										),
									)
								);
								?>
								<span class="price-info font-size-20 font-lato font-weight-medium color-white">(<?php esc_html_e( 'Per Night', 'easy-reservations' ); ?>)</span>
							</div>
						</div>
						<div class="book-tour bgcolor-white rounded-xl text-center">
							<div class="title mb-4">
								<h3 class="font-Poppins font-size-24 font-weight-bold color-black"><?php esc_html_e( 'Book The Tour', 'easy-reservations' ); ?></h3>
							</div>
							<div class="details text-left">
								<form action="">
									<div class="input-daterange d-flex flex-column flex-fill mb-3 pb-2">
										<input placeholder="E.g.: <?php echo esc_html( $curr_date ); ?>" type="text" id="ersrv-single-reservation-checkin-datepicker" class="form-control date-control text-left rounded-lg">
										<div class="input-group-addon font-Poppins font-size-18 font-weight-light color-black-400 py-2 my-1 text-center"><?php esc_html_e( 'to', 'easy-reservations' ); ?></div>
										<input placeholder="E.g.: <?php echo esc_html( $next_date ); ?>" type="text" id="ersrv-single-reservation-checkout-datepicker" class="form-control date-control text-left rounded-lg">
										<p class="ersrv-reservation-error checkin-checkout-dates-error"></p>
									</div>
									<div class="book-items-wrapper mb-4 pb-3">
										<label for="book-items" class="font-Poppins font-size-16 color-black"><?php esc_html_e( 'No book items', 'easy-reservations' ); ?></label>
										<input id="adult-accomodation-count" placeholder="<?php esc_html_e( 'No. of adults', 'easy-reservations' ); ?>" type="number" class="form-control mb-3" />
										<input id="kid-accomodation-count" placeholder="<?php esc_html_e( 'No. of kids', 'easy-reservations' ); ?>" type="number" class="form-control" />
										<p class="ersrv-reservation-error accomodation-error"></p>
									</div>
									<?php if ( ! empty( $amenities ) && is_array( $amenities ) ) { ?>
										<div class="ersrv-item-amenities-wrapper non-clickable checkbox-wrapper mb-4 pb-3">
											<label for="amenities" class="font-Poppins font-size-16 color-black"><?php esc_html_e( 'Amenities', 'easy-reservations' ); ?></label>
											<?php foreach ( $amenities as $amenity_data ) {
												$amenity_title     = $amenity_data['title'];
												$amenity_slug      = sanitize_title( $amenity_title );
												$amenity_cost      = $amenity_data['cost'];
												$amenity_cost_type = $amenity_data['cost_type'];
												?>
												<div class="custom-control custom-switch ersrv-single-amenity-block" data-cost_type="<?php echo esc_attr( $amenity_cost_type ); ?>" data-cost="<?php echo esc_attr( $amenity_cost ); ?>" data-amenity="<?php echo esc_attr( $amenity_title ); ?>">
													<input type="checkbox" class="custom-control-input ersrv-new-reservation-single-amenity" id="amenity-<?php echo esc_html( $amenity_slug ); ?>">
													<label class="custom-control-label font-size-15" for="amenity-<?php echo esc_html( $amenity_slug ); ?>">
														<span class="d-block font-lato font-weight-bold color-black pb-2"><?php echo esc_html( $amenity_title ); ?> - <span class="font-lato font-weight-bold color-accent"><?php echo wc_price( $amenity_cost ); ?></span></span>
													</label>
												</div>
											<?php } ?>
										</div>
									<?php } ?>
									<div class="calc-wrapper mb-3">
										<label class="font-Poppins font-size-16 color-black"><?php esc_html_e( 'Summary', 'easy-reservations' ); ?></label>
										<table class="table table-borderless">
											<tbody>
												<tr class="item-price-summary">
													<th><?php esc_html_e( 'Adults:', 'easy-reservations' ); ?></th>
													<td><span class="font-lato font-weight-bold color-accent">--</span></td>
												</tr>
												<tr class="kids-charge-summary">
													<th><?php esc_html_e( 'Kids:', 'easy-reservations' ); ?></th>
													<td><span class="font-lato font-weight-bold color-accent">--</span></td>
												</tr>
												<tr class="security-amount-summary">
													<th><?php esc_html_e( 'Security:', 'easy-reservations' ); ?></th>
													<td>
														<span class="font-lato font-weight-bold color-accent">
															<?php
															echo wp_kses(
																wc_price( $security_amount ),
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
												<tr class="amenities-summary">
													<th><?php esc_html_e( 'Amenities:', 'easy-reservations' ); ?></th>
													<td><span class="font-lato font-weight-bold color-accent">--</span></td>
												</tr>
												<tr class="new-reservation-total-cost">
													<th><?php esc_html_e( 'Total:', 'easy-reservations' ); ?></th>
													<td>
														<span class="font-lato font-weight-bold color-accent">
															<?php
															echo wp_kses(
																wc_price( $security_amount ),
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
										<input type="hidden" id="accomodation-limit" value="<?php echo esc_html( $accomodation_limit ); ?>" />
										<input type="hidden" id="min-reservation-period" value="<?php echo esc_html( $min_reservation_period ); ?>" />
										<input type="hidden" id="max-reservation-period" value="<?php echo esc_html( $max_reservation_period ); ?>" />
										<input type="hidden" id="adult-charge" value="<?php echo esc_html( $adult_charge ); ?>" />
										<input type="hidden" id="kid-charge" value="<?php echo esc_html( $kid_charge ); ?>" />
										<input type="hidden" id="security-amount" value="<?php echo esc_html( $security_amount ); ?>" />
									</div>
									<div class="instant-booking">
										<button type="button" class="ersrv-proceed-to-checkout-single-reservation-item btn btn-primary btn-block btn-xl font-lato font-size-18 font-weight-bold">
											<span class="mr-3"><img src="<?php echo esc_url( ERSRV_PLUGIN_URL . 'public/images/Instant-booking.png' ); ?>" alt="instant-booking"></span>
											<span><?php esc_html_e( 'Instant Booking', 'easy-reservations' ); ?></span>
										</button>
									</div>
									<div class="dropdown-divider my-4 py-2"></div>
									<div class="contact-owner mb-3 pb-2">
										<button type="button" class="ersrv-contact-owner-button btn btn-outline-fill-primary btn-block btn-xl font-lato font-size-18 font-weight-bold">
											<span><?php esc_html_e( 'Contact Owner', 'easy-reservations' ); ?></span>
										</button>
									</div>
									<div class="social">
										<div class="d-flex align-items-center justify-content-center">
											<a href="#" class="icon facebook">
												<span>
													<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
														<path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z" />
													</svg>
												</span>
											</a>
											<a href="#" class="icon twitter">
												<span>
													<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
														<path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
													</svg>
												</span>
											</a>
											<a href="#" class="icon email">
												<span>
													<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
														<path d="M0 3v18h24v-18h-24zm21.518 2l-9.518 7.713-9.518-7.713h19.036zm-19.518 14v-11.817l10 8.104 10-8.104v11.817h-20z" />
													</svg>
												</span>
											</a>
											<a href="#" class="icon pinterest">
												<span>
													<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
														<path d="M 16.09375 4 C 11.01675 4 6 7.3833281 6 12.861328 C 6 16.344328 7.9584844 18.324219 9.1464844 18.324219 C 9.6364844 18.324219 9.9199219 16.958266 9.9199219 16.572266 C 9.9199219 16.112266 8.7460938 15.131797 8.7460938 13.216797 C 8.7460938 9.2387969 11.774359 6.4199219 15.693359 6.4199219 C 19.063359 6.4199219 21.556641 8.3335625 21.556641 11.851562 C 21.556641 14.478563 20.501891 19.40625 17.087891 19.40625 C 15.855891 19.40625 14.802734 18.516234 14.802734 17.240234 C 14.802734 15.370234 16 13.558906 16 11.628906 C 16 8.3529063 11.462891 8.94725 11.462891 12.90625 C 11.462891 13.73725 11.5665 14.657063 11.9375 15.414062 C 11.2555 18.353063 10 23.037406 10 26.066406 C 10 27.001406 10.133656 27.921422 10.222656 28.857422 C 10.390656 29.045422 10.307453 29.025641 10.564453 28.931641 C 13.058453 25.517641 12.827078 24.544172 13.955078 20.076172 C 14.564078 21.234172 16.137766 21.857422 17.384766 21.857422 C 22.639766 21.857422 25 16.736141 25 12.119141 C 25 7.2061406 20.75475 4 16.09375 4 z" />
													</svg>
												</span>
											</a>
										</div>
									</div>
								</form>
							</div>
						</div>
						<div class="advanced-search bgcolor-white rounded-xl text-center">
							<div class="title mb-4 pb-2">
								<h3 class="font-Poppins font-size-24 font-weight-bold color-black"><?php esc_html_e( 'Advanced Search', 'easy-reservations' ); ?></h3>
							</div>
							<div class="details text-left">
								<form action="">
									<div class="form-group">
										<input type="text" class="ersrv-item-search-location form-control ship-icon-field text-left rounded-lg" placeholder="<?php esc_html_e( 'Desired location', 'easy-reservations' ); ?>">
									</div>
									<div class="input-daterange d-flex flex-column flex-fill mb-4 pb-3">
										<input id="ersrv-search-checkin" type="text" class="form-control date-control text-left rounded-lg mb-3" placeholder="Check in">
										<input id="ersrv-search-checkout" type="text" class="form-control date-control text-left rounded-lg" placeholder="Check out">
									</div>
									<div class="range-slider-wrapper mb-4 pb-2">
										<h4 class="font-lato font-size-14 font-weight-normal color-black text-center mb-2"><?php esc_html_e( 'Price Range', 'easy-reservations' ); ?></h4>
										<h4 class="font-lato font-size-20 font-weight-bolder color-black text-center mb-0 price-value"><?php echo esc_html( $woo_currency );?>0 to <?php echo esc_html( $woo_currency );?>10,000</h4>
										<div class="slider-wrapper mt-3">
											<div class="ersrv-search-item-price-range" id="slider-range"></div>
										</div>
									</div>
									<div class="book-items-wrapper mb-4 pb-3">
										<select class="selectpicker form-control Boat-Types" id="boat-types" data-size="5" data-style="btn-outline-secondary focus-none" title="<?php esc_html_e( 'Select Item Type', 'easy-reservations' ); ?>">
											<?php if ( ! empty( $reservation_item_types ) && is_array( $reservation_item_types ) ) { ?>
												<?php foreach ( $reservation_item_types as $item_type ) { ?>
													<option value="<?php echo esc_attr( $item_type->term_id ); ?>"><?php echo esc_html( $item_type->name ); ?></option>
												<?php } ?>
											<?php } ?>
										</select>
									</div>
									<div class="search-box">
										<button type="button" class="ersrv-submit-search-request btn btn-primary btn-block btn-xl font-lato font-size-18 font-weight-bold">
											<span class="mr-3"><img src="<?php echo esc_url( ERSRV_PLUGIN_URL . 'public/images/Search.png' ); ?>" alt="Search"></span>
											<span><?php esc_html_e( 'Search', 'easy-reservations' ); ?></span>
										</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php
get_footer();
