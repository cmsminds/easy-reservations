<?php
/**
 * This file is used for templating the export reservations modal.
 *
 * @since 1.0.0
 * @package Easy_Reservations
 * @subpackage Easy_Reservations/includes
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

// Total reservations page.
$total_reservation_posts_query = ersrv_get_posts( 'product', 1, -1 );
$total_reservation_posts       = $total_reservation_posts_query->posts;
$posts_per_page                = (int) get_option( 'posts_per_page' );

// Get the boat types.
$reservation_item_types = get_terms(
	array(
		'taxonomy' => 'reservation-item-type',
		'hide_empty' => true,
	)
);

// Query parameters.
$search_location  = filter_input( INPUT_GET, 'location', FILTER_SANITIZE_STRING );
$search_location  = ( ! is_null( $search_location ) ) ? $search_location : '';
$search_checkin   = filter_input( INPUT_GET, 'checkin', FILTER_SANITIZE_STRING );
$search_checkin   = ( ! is_null( $search_checkin ) ) ? $search_checkin : '';
$search_checkout  = filter_input( INPUT_GET, 'checkout', FILTER_SANITIZE_STRING );
$search_checkout  = ( ! is_null( $search_checkout ) ) ? $search_checkout : '';
$search_price_min = filter_input( INPUT_GET, 'price_min', FILTER_SANITIZE_STRING );
$search_price_min = ( ! is_null( $search_price_min ) ) ? $search_price_min : '';
$search_price_max = filter_input( INPUT_GET, 'price_max', FILTER_SANITIZE_STRING );
$search_price_max = ( ! is_null( $search_price_max ) ) ? $search_price_max : '';
$search_boat_type = (int) filter_input( INPUT_GET, 'boat_type', FILTER_SANITIZE_NUMBER_INT );
$search_boat_type = ( ! is_null( $search_boat_type ) ) ? $search_boat_type : 0;
?>
<section class="wrapper search-page" id="wrapper">
	<div class="banner text-center">
		<div class="container">
			<div class="details mx-auto font-lato">
				<div class="page-title">
					<h1 class="font-Poppins font-size-40 font-weight-semibold color-white"><?php esc_html_e( 'Search for a boat', 'easy-reservations' ); ?></h1>
				</div>
				<div class="form-wrapper">
					<form action="#" class="form-inner">
						<div class="form-row">
							<div class="col-12 col-md-5 col-lg-6">
								<div class="form-group">
									<input type="text" value="<?php echo esc_html( $search_location ); ?>" class="ersrv-item-search-location form-control date-control ship-icon-field text-left rounded-lg" placeholder="<?php esc_html_e( 'Desired location', 'easy-reservations' ); ?>">
								</div>
							</div>
							<div class="col-12 col-md-7 col-lg-6">
								<div class="input-daterange">
									<div class="form-row">
										<div class="col-12 col-md-6">
											<input id="ersrv-search-checkin" value="<?php echo esc_html( $search_checkin ); ?>" type="text" class="form-control date-control text-left rounded-lg mb-3" placeholder="Check in">
										</div>
										<div class="col-12 col-md-6">
											<input id="ersrv-search-checkout" value="<?php echo esc_html( $search_checkout ); ?>" type="text" class="form-control date-control text-left rounded-lg mb-3" placeholder="Check out">
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="form-row">
							<div class="col-12 col-md-5 col-lg-6">
								<div class="slider-wrapper">
									<div class="search-price-range-slider ersrv-search-item-price-range"></div>
								</div>
							</div>
							<div class="col-12 col-md-7 col-lg-6">
								<div class="input-daterange">
									<div class="form-row">
										<div class="col-12 col-md-6">
											<select class="selectpicker form-control Boat-Types mb-3" id="boat-types" data-size="5" data-style="btn-outline-light focus-none" title="Boat Type">
												<?php if ( ! empty( $reservation_item_types ) && is_array( $reservation_item_types ) ) { ?>
													<?php foreach ( $reservation_item_types as $item_type ) {
														$is_selected = ( 0 !== $search_boat_type && $search_boat_type === $item_type->term_id );
														?>
														<option <?php echo ( $is_selected ) ? 'selected' : ''; ?> value="<?php echo esc_attr( $item_type->term_id ); ?>"><?php echo esc_html( $item_type->name ); ?></option>
													<?php } ?>
												<?php } ?>
											</select>
										</div>
										<div class="col-12 col-md-6">
											<button class="btn btn-primary btn-block font-lato font-size-18 font-weight-bold">
												<span class="mr-3"><img src="<?php echo esc_url (ERSRV_PLUGIN_URL . 'public/images/Search.png' ); ?>" alt="Search"></span>
												<span>Search</span>
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="content-part">
		<div class="container">
			<div class="search-results-wrapper">
				<h4 class="title font-Poppins"><?php echo sprintf( __( 'Advanced Search: %1$s%3$s items%2$s', 'easy-reservations' ), '<span class="result-no">', '</span>', count( $total_reservation_posts ) ) ?></h4>
				<div class="search-result-inner ersrv-search-reservations-items-container form-row">
					<div class="jumbotron text-center w-100 bg-transparent">
						<h3 class="loading-title">Please wait while we load items...</h3>
						<div class="loading-icon">
							<i class="fa fa-circle-notch fa-spin fa-3x fa-fw"></i>
							<span class="sr-only">Loading...</span>
						</div>
					</div>
				</div>
				<div class="col-12 ersrv-load-more-reservation-items">
					<div class="loadmore text-center mt-5 pt-2 ersrv-loadmore-container">
						<?php if ( count( $total_reservation_posts ) > $posts_per_page ) { ?>
							<input id="ersrv-posts-page" value="1" type="hidden" />
							<a href="javascript:void(0);" class="btn btn-outline-primary btn-xl font-size-18 loadmore-btn mx-auto"><?php esc_html_e( 'Load More', 'easy-reservations' ); ?></a>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>