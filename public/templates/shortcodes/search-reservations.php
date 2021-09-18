<?php
/**
 * This file is used for templating the search reservations.
 *
 * @since 1.0.0
 * @package Easy_Reservations
 * @subpackage Easy_Reservations/includes
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$post_id = get_the_ID();

// Total reservations page.
$total_reservation_posts_query = ersrv_get_posts( 'product', 1, -1 );
$total_reservation_posts       = $total_reservation_posts_query->posts;
$posts_per_page                = (int) get_option( 'posts_per_page' );

// Get the boat types.
$reservation_item_types = get_terms(
	array(
		'taxonomy'   => 'reservation-item-type',
		'hide_empty' => true,
	)
);

// Query parameters.
$search_location      = filter_input( INPUT_GET, 'location', FILTER_SANITIZE_STRING );
$search_location      = ( ! is_null( $search_location ) ) ? $search_location : '';
$search_accomodation  = filter_input( INPUT_GET, 'accomodation', FILTER_SANITIZE_STRING );
$search_accomodation  = ( ! is_null( $search_accomodation ) ) ? $search_accomodation : '';
$search_checkin       = filter_input( INPUT_GET, 'checkin', FILTER_SANITIZE_STRING );
$search_checkin       = ( ! is_null( $search_checkin ) ) ? $search_checkin : '';
$search_checkout      = filter_input( INPUT_GET, 'checkout', FILTER_SANITIZE_STRING );
$search_checkout      = ( ! is_null( $search_checkout ) ) ? $search_checkout : '';
$search_boat_type     = (int) filter_input( INPUT_GET, 'boat_type', FILTER_SANITIZE_NUMBER_INT );
$search_boat_type     = ( ! is_null( $search_boat_type ) ) ? $search_boat_type : 0;
$banner_image_id      = get_post_meta( $post_id, 'ersrv_banner_image_id', true );
$banner_image_url     = ersrv_get_attachment_url_from_attachment_id( $banner_image_id );
$banner_image_url     = ( ! empty( $banner_image_url ) ) ? $banner_image_url : ERSRV_PLUGIN_URL . 'public/images/search-banner-image.jpg';
?>
<section class="wrapper search-page" id="wrapper">
	<div class="banner text-center" style="background-image: url( '<?php echo $banner_image_url; ?>' );">
		<div class="container">
			<div class="details mx-auto font-lato">
				<div class="page-title">
					<h1 class="font-Poppins font-size-40 font-weight-semibold color-white"><?php echo wp_kses_post( get_the_title() ); ?></h1>
				</div>
				<div class="form-wrapper ersrv-form-wrapper">
					<form action="#" class="form-inner">
						<div class="form-row">
							<div class="col-12 col-md-3 col-lg-3">
								<div class="form-group">
									<input type="text" value="<?php echo esc_html( $search_location ); ?>" class="ersrv-search-parameter ersrv-item-search-location form-control date-control ship-icon-field text-left rounded-lg" placeholder="<?php esc_html_e( 'Desired location', 'easy-reservations' ); ?>">
								</div>
							</div>
							<div class="col-12 col-md-3 col-lg-3">
								<select class="ersrv-search-parameter ersrv-reservation-item-type selectpicker form-control Boat-Types mb-3" id="boat-types" data-size="5" data-style="btn-outline-light focus-none" title="<?php esc_html_e( 'Item Type', 'easy-reservations' ); ?>">
									<option value=""><?php esc_html_e( 'Item Type', 'easy-reservations' ); ?></option>
									<?php if ( ! empty( $reservation_item_types ) && is_array( $reservation_item_types ) ) { ?>
										<?php foreach ( $reservation_item_types as $item_type ) {
											$is_selected = ( 0 !== $search_boat_type && $search_boat_type === $item_type->term_id );
											?>
											<option <?php echo ( $is_selected ) ? 'selected' : ''; ?> value="<?php echo esc_attr( $item_type->term_id ); ?>"><?php echo esc_html( $item_type->name ); ?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</div>
							<div class="col-12 col-md-6 col-lg-6">
								<div class="input-daterange">
									<div class="form-row">
										<div class="col-12 col-md-6">
											<input id="ersrv-search-checkin" value="<?php echo esc_html( $search_checkin ); ?>" type="text" class="ersrv-search-parameter form-control date-control text-left rounded-lg mb-3" placeholder="Check in">
										</div>
										<div class="col-12 col-md-6">
											<input id="ersrv-search-checkout" value="<?php echo esc_html( $search_checkout ); ?>" type="text" class="ersrv-search-parameter form-control date-control text-left rounded-lg mb-3" placeholder="Check out">
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="form-row">
							<div class="col-12 col-md-5 col-lg-6">
							</div>
							<div class="col-12 col-md-7 col-lg-6">
								<div class="input-daterange">
									<div class="form-row">
										<div class="col-12 col-md-6">
											<div class="form-group">
												<input type="number" value="<?php echo esc_html( $search_accomodation ); ?>" class="ersrv-search-parameter ersrv-item-search-accomodation form-control rounded-lg ship-icon-field" placeholder="<?php esc_html_e( 'Accomodation', 'easy-reservations' ); ?>">
											</div>
										</div>
										<div class="col-12 col-md-6">
											<button type="button" class="ersrv-submit-reservation-search btn btn-primary btn-block font-lato font-size-18 font-weight-bold">
												<span class="mr-3"><img src="<?php echo esc_url( ERSRV_PLUGIN_URL . 'public/images/Search.png' ); ?>" alt="Search"></span>
												<span><?php esc_html_e( 'Search', 'easy-reservations' ); ?></span>
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
				<h3 class="title font-Poppins"><?php echo sprintf( __( 'Advanced Search: %1$s%3$s Items%2$s', 'easy-reservations' ), '<span class="ersrv-reservation-items-count result-no">', '</span>', count( $total_reservation_posts ) ) ?></h3>
				<div class="search-result-inner ersrv-search-reservations-items-container">
					<div class="jumbotron text-center w-100 bg-transparent">
						<h3 class="loading-title"><?php esc_html_e( 'Please wait while we load items...', 'easy-reservations' ); ?></h3>
						<div class="loading-icon">
							<i class="fa fa-circle-notch fa-spin fa-3x fa-fw"></i>
							<span class="sr-only"><?php esc_html_e( 'Loading...', 'easy-reservations' ); ?></span>
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
