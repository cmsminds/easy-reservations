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
$location = ( ! empty( $item_details['location'] ) ) ? $item_details['location'] : '';

// Google maps API key.
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

// Social share URLs.
$social_share_urls = array(
	'facebook' => array(
		'icon'  => 'fab fa-facebook-f',
		'link'  => 'https://facebook.com/sharer.php?u=' . get_permalink( $item_post->ID ),
		'class' => 'icon facebook',
	),
	'twitter'  => array(
		'icon'  => 'fab fa-twitter',
		'link'  => 'https://twitter.com/intent/tweet?text=' . $item_post->post_title . '&url=' . get_permalink( $item_post->ID ),
		'class' => 'icon twitter',
	),
);
/**
 * This hook is fired on the reservation item single page.
 *
 * This filter help in managing the social platforms for sharing the reservation item.
 *
 * @param array $social_share_urls Array of social media platforms.
 * @return array
 * @since 1.0.0
 */
$social_share_urls = apply_filters( 'ersrv_reservation_item_socia_share_platforms', $social_share_urls );

// Banner image.
$banner_image_id  = get_post_meta( $item_post->ID, 'ersrv_banner_image_id', true );
$banner_image_url = ersrv_get_attachment_url_from_attachment_id( $banner_image_id );
$banner_image_url = ( ! empty( $banner_image_url ) ) ? $banner_image_url : ERSRV_PLUGIN_URL . 'public/images/banner-bg.jpg';
?>
<section class="wrapper single-reserve-page" id="wrapper" data-item="<?php echo esc_attr( $item_post->ID ); ?>">
	<div class="banner text-center" style="background-image: url( '<?php echo $banner_image_url; ?>' );">
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
							<div class="dropdown-divider"></div>
							<div class="masonry-grid gallery-images">
								<!--Item -->
								<img src="https://source.unsplash.com/random?var-0" alt="" class="masonry-grid__item gallery-image-item" />
								<img src="https://source.unsplash.com/random?var-1" alt="" class="masonry-grid__item gallery-image-item" />
								<img src="https://source.unsplash.com/random?var-2" alt="" class="masonry-grid__item gallery-image-item" />
								<img src="https://source.unsplash.com/random?var-3" alt="" class="masonry-grid__item gallery-image-item" />
								<img src="https://source.unsplash.com/random?var-4" alt="" class="masonry-grid__item gallery-image-item" />
								<img src="https://source.unsplash.com/random?ver-5" alt="" class="masonry-grid__item gallery-image-item" />
							</div>
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
							<?php if ( ! empty( $api_key ) ) { ?>
								<iframe width="100%" height="400px" src="https://www.google.com/maps/embed/v1/place?key=<?php echo esc_html( $api_key ); ?>&q=<?php echo esc_html( $location ); ?>" style="border:0" loading="lazy" allowfullscreen></iframe>
							<?php } else { ?>
								<p><?php echo wp_kses_post( $location ); ?></p>
							<?php } ?>
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
								<span class="price-info font-size-20 font-lato font-weight-medium color-white">(<?php esc_html_e( 'per day', 'easy-reservations' ); ?>)</span>
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
										<label for="book-items" class="font-Poppins font-size-16 color-black"><?php echo esc_html( sprintf( __( 'Guests (Limit: %1$d)', 'easy-reservations' ), $accomodation_limit ) ); ?><span class="required">*</span></label>
										<input id="adult-accomodation-count" placeholder="<?php esc_html_e( 'No. of adults', 'easy-reservations' ); ?>" type="number" class="form-control mb-3" />
										<input id="kid-accomodation-count" placeholder="<?php esc_html_e( 'No. of kids', 'easy-reservations' ); ?>" type="number" class="form-control" />
										<p class="ersrv-reservation-error accomodation-error"></p>
									</div>
									<?php if ( ! empty( $amenities ) && is_array( $amenities ) ) { ?>
										<div class="ersrv-item-amenities-wrapper checkbox-wrapper mb-4 pb-3">
											<label for="amenities" class="font-Poppins font-size-16 color-black"><?php esc_html_e( 'Amenities', 'easy-reservations' ); ?></label>
											<?php foreach ( $amenities as $amenity_data ) {
												$amenity_title     = ( ! empty( $amenity_data['title'] ) ) ? $amenity_data['title'] : '';
												$amenity_cost      = ( ! empty( $amenity_data['cost'] ) ) ? (float) $amenity_data['cost'] : 0.00;
												$amenity_slug      = ( ! empty( $amenity_title ) ) ? sanitize_title( $amenity_title ) : '';
												$amenity_cost_type = ( ! empty( $amenity_data['cost_type'] ) ) ? $amenity_data['cost_type'] : 'one_time';
												?>
												<div class="custom-control custom-switch ersrv-single-amenity-block" data-cost_type="<?php echo esc_attr( $amenity_cost_type ); ?>" data-cost="<?php echo esc_attr( $amenity_cost ); ?>" data-amenity="<?php echo esc_attr( $amenity_title ); ?>">
													<input type="checkbox" class="custom-control-input ersrv-new-reservation-single-amenity" id="amenity-<?php echo esc_html( $amenity_slug ); ?>">
													<label class="custom-control-label font-size-15" for="amenity-<?php echo esc_html($amenity_slug); ?>">
														<span class="d-block font-lato font-weight-bold color-black pb-2"><?php echo esc_html($amenity_title); ?> </span>
														<span><span class="font-lato font-weight-bold color-accent"><?php echo wc_price($amenity_cost); ?></span> | <span class="font-lato font-weight-normal color-black-500">Single Fee </span></span>
													</label>

												</div>
											<?php } ?>
										</div>
									<?php } ?>
									<div class="calc-wrapper mb-3">
										<h4 class="font-Poppins font-size-16 color-black font-weight-bold mb-0">
											<?php echo sprintf( __( 'Subtotal: %1$s', 'easy-reservations' ), '<a class="text-decoration-none ersrv-split-reservation-cost" href="javascript:void(0);"><span class="ersrv-reservation-item-subtotal ersrv-cost">--</span></a>' ); ?>
										</h4>
										<div class="ersrv-reservation-details-item-summary">
											<div class="ersrv-reservation-details-item-summary-wrapper p-3">
												<table class="table table-borderless">
													<tbody>
														<tr class="adults-subtotal">
															<th><?php esc_html_e( 'Adults:', 'easy-reservations' ); ?></th>
															<td><span data-cost="" class="ersrv-cost font-lato font-weight-bold color-accent">--</span></td>
														</tr>
														<tr class="kids-subtotal">
															<th><?php esc_html_e( 'Kids:', 'easy-reservations' ); ?></th>
															<td><span data-cost="" class="ersrv-cost font-lato font-weight-bold color-accent">--</span></td>
														</tr>
														<tr class="amenities-subtotal">
															<th><?php esc_html_e( 'Amenities:', 'easy-reservations' ); ?></th>
															<td><span data-cost="" class="ersrv-cost font-lato font-weight-bold color-accent">--</span></td>
														</tr>
														<tr class="security-subtotal">
															<th><?php esc_html_e( 'Security:', 'easy-reservations' ); ?></th>
															<td><span data-cost="" class="ersrv-cost font-lato font-weight-bold color-accent">--</span></td>
														</tr>
														<tr class="reservation-item-subtotal">
															<th><?php esc_html_e( 'Total:', 'easy-reservations' ); ?></th>
															<td><span data-cost="" class="ersrv-cost font-lato font-weight-bold color-accent">--</span></td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
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
										<div class="ersrv-powered-by-cmsminds" role="complementary">
											<a class="ersrv-cmsminds-logo" href="https://cmsminds.com" title="<?php esc_html_e( 'Powered by cmsMinds opens in a new window', 'easy-reservations' ); ?>" target="_blank"><?php esc_html_e( 'Powered by cmsMinds', 'easy-reservations' ); ?></a>
										</div>
									</div>
									<div class="dropdown-divider my-4 py-2"></div>
									<div class="contact-owner mb-3 pb-2">
										<button type="button" class="ersrv-contact-owner-button btn btn-outline-fill-primary btn-block btn-xl font-lato font-size-18 font-weight-bold">
											<span><?php esc_html_e( 'Contact Owner', 'easy-reservations' ); ?></span>
										</button>
									</div>
									<div class="social">
										<div class="d-flex align-items-center justify-content-center">
											<?php
											if ( ! empty( $social_share_urls ) && is_array( $social_share_urls ) ) {
												foreach ( $social_share_urls as $social_share_url ) {
													?>
													<a href="<?php echo esc_url( $social_share_url['link'] ); ?>" class="<?php echo esc_attr( $social_share_url['class'] ); ?>">
														<span><i class="<?php echo esc_attr( $social_share_url['icon'] ); ?>"></i></span>
													</a>
													<?php
												}
											}
											?>
										</div>
									</div>
								</form>
							</div>
						</div>
						<div class="advanced-search bgcolor-white rounded-xl text-center">
							<div class="title pb-2">
								<h3 class="font-Poppins font-size-24 font-weight-bold color-black"><?php esc_html_e( 'Advanced Search', 'easy-reservations' ); ?></h3>
							</div>
							<div class="details text-left">
								<form action="">
									<div class="mb-2">
										<input type="text" class="ersrv-item-search-location form-control text-left rounded-lg" placeholder="<?php esc_html_e( 'Desired location', 'easy-reservations' ); ?>">
									</div>
									<div class="mb-2">
										<input type="number" class="ersrv-item-search-accomodation form-control ship-icon-field text-left rounded-lg" placeholder="<?php esc_html_e( 'Accomodation', 'easy-reservations' ); ?>">
									</div>
									<div class="input-daterange d-flex flex-column flex-fill pb-2">
										<input id="ersrv-search-checkin" type="text" class="form-control date-control text-left rounded-lg mb-2" placeholder="Check in">
										<input id="ersrv-search-checkout" type="text" class="form-control date-control text-left rounded-lg" placeholder="Check out">
									</div>
									<div class="book-items-wrapper pb-2">
										<select class="selectpicker form-control Boat-Types" id="boat-types" data-size="5" data-style="btn-outline-secondary focus-none" title="<?php esc_html_e( 'Select Item Type', 'easy-reservations' ); ?>">
											<option value=""><?php esc_html_e( 'Select Item Type', 'easy-reservations' ); ?></option>
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
	<!-- light box HTML -->
<div class="lightbox">
    <div class="title"></div>
    <div class="filter"></div>
    <div class="arrowr"></div>
    <div class="arrowl"></div>
    <div class="close"></div>
</div>
<script>
    jQuery(window).load(function($) {

        jQuery(".masonry-grid img").click(function() {
            jQuery(".lightbox").fadeIn(300);
            jQuery(".lightbox").append("<img src='" + jQuery(this).attr("src") + "' alt='" + jQuery(this).attr("alt") + "' />");
            jQuery(".filter").css("background-image", "url(" + jQuery(this).attr("src") + ")");
            /*jQuery(".title").append("<h1>" + jQuery(this).attr("alt") + "</h1>");*/
            jQuery("html").css("overflow", "hidden");
            if (jQuery(this).is(":last-child")) {
                jQuery(".arrowr").css("display", "none");
                jQuery(".arrowl").css("display", "block");
            } else if (jQuery(this).is(":first-child")) {
                jQuery(".arrowr").css("display", "block");
                jQuery(".arrowl").css("display", "none");
            } else {
                jQuery(".arrowr").css("display", "block");
                jQuery(".arrowl").css("display", "block");
            }
        });

        jQuery(".close").click(function() {
            jQuery(".lightbox").fadeOut(300);
            jQuery("h1").remove();
            jQuery(".lightbox img").remove();
            jQuery("html").css("overflow", "auto");
        });

        jQuery(document).keyup(function(e) {
            if (e.keyCode == 27) {
                jQuery(".lightbox").fadeOut(300);
                jQuery(".lightbox img").remove();
                jQuery("html").css("overflow", "auto");
            }
        });

        jQuery(".arrowr").click(function() {
            var imgSrc = jQuery(".lightbox img").attr("src");
            var search = jQuery(".masonry-grid").find("img[src$='" + imgSrc + "']");
            var newImage = search.next().attr("src");
            /*jQuery(".lightbox img").attr("src", search.next());*/
            jQuery(".lightbox img").attr("src", newImage);
            jQuery(".filter").css("background-image", "url(" + newImage + ")");

            if (!search.next().is(":last-child")) {
                jQuery(".arrowl").css("display", "block");
            } else {
                jQuery(".arrowr").css("display", "none");
            }
        });

        jQuery(".arrowl").click(function() {
            var imgSrc = jQuery(".lightbox img").attr("src");
            var search = jQuery(".masonry-grid").find("img[src$='" + imgSrc + "']");
            var newImage = search.prev().attr("src");
            /*jQuery(".lightbox img").attr("src", search.next());*/
            jQuery(".lightbox img").attr("src", newImage);
            jQuery(".filter").css("background-image", "url(" + newImage + ")");

            if (!search.prev().is(":first-child")) {
                jQuery(".arrowr").css("display", "block");
            } else {
                jQuery(".arrowl").css("display", "none");
            }
        });

    });
</script>
</section>
<?php
get_footer();
