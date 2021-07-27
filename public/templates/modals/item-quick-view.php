<?php

/**
 * This file is used for templating the reservable item quick view modal.
 *
 * @since 1.0.0
 * @package Easy_Reservations
 * @subpackage Easy_Reservations/public/templates/modals
 */

defined('ABSPATH') || exit; // Exit if accessed directly.
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
<div id="ersrv-item-quick-view-modal" class="ersrv-modal quick-view-modal">
	<div class="ersrv-modal-content modal-content modal-lg m-auto">
		<!-- <span class="ersrv-close-modal">&times;</span> -->
		<!-- <h3><?php esc_html_e('Quick View', 'easy-reservations'); ?></h3> -->
		<div class="modal-body">
			<span class="ersrv-close-modal quick-close close">×</span>
			<div class="quickbuymodal">
				<div class="quick-row align-items-center">
					<div class="col-12 col-md-6  col-preview">
						<div class="product-preview">
							<div class="product-preview-main">
								<img src="https://welovepets.care/wp-content/uploads/2020/07/XGC-calming-cat-.png" alt="featured-image" class="product-preview-image">
							</div>
							<!-- GALLERY IMAGES -->
							<div id="preview-list" class="product-preview-menu">
								<div class="product-preview-thumb">
									<img src="https://welovepets.care/wp-content/uploads/2020/07/XGC-calming-cat-.png" alt="gallery-image" class="product-preview-thumb-image">
								</div>
								<div class="product-preview-thumb">
									<img src="https://welovepets.care/wp-content/uploads/2020/07/XGC-calming-cat-.png" alt="gallery-image" class="product-preview-thumb-image">
								</div>
								<div class="product-preview-thumb">
									<img src="https://welovepets.care/wp-content/uploads/2020/07/XGC-calming-cat-.png" alt="gallery-image" class="product-preview-thumb-image">
								</div>
								<div class="product-preview-thumb">
									<img src="https://welovepets.care/wp-content/uploads/2020/07/XGC-calming-cat-.png" alt="gallery-image" class="product-preview-thumb-image">
								</div>
								<div class="product-preview-thumb">
									<img src="https://welovepets.care/wp-content/uploads/2020/07/XGC-calming-cat-.png" alt="gallery-image" class="product-preview-thumb-image">
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-md-6  col-product">
						<div class="product-details">
							<form action="" method="post" class="form-inner">
								<h2 class="product-title font-weight-semibold font-size-30">Calming Functional Treats</h2>
								<div class="product-price-meta mb-5">
									<h4 class="font-size-30 price">$ 50 <span class="font-size-20 price-text">Per Night</span></h4>
								</div>
								<div class="product-details-values mb-5">
									<div class="check-in-out-values d-flex flex-column mb-3">
										<h4 class="font-size-20 font-weight-semibold">Checkin/checkout Date</h4>
										<div class="values">
											<div class="row form-row input-daterange">
												<div class="col-6">
													<div>
														<label class="font-size-16">Check In</label>
													</div>
													<div>
														<input type="text" class="form-control date-control text-left rounded-lg" value="2012-04-05">
													</div>
												</div>
												<div class="col-6">
													<div>
														<label class="font-size-16">Check Out</label>
													</div>
													<div>
													<input type="text" class="form-control date-control text-left rounded-lg" value="2012-04-19"> 
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="accomodation-values d-flex flex-column mb-3">
										<h4 class="font-size-20 font-weight-semibold">Accomodation</h4>
										<div class="values">
											<div class="row form-row">
												<div class="col-6">
													<label class="font-size-16">No. of Adults</label>
													<input type="number" name="" id="" class="form-contol" placeholder="2" />
												</div>
												<div class="col-6">
													<label class="font-size-16">No. of Kids</label>
													<input type="number" name="" id="" class="form-contol" placeholder="2" />
												</div>
											</div>
										</div>
									</div>
									<div class="amenities-values d-flex flex-column">
										<h4 class="font-size-20 font-weight-semibold">Amenities</h4>
										<div class="values">
											<div class="row form-row">
												<div class="col-6">
													<div class="custom-control custom-switch">
														<input type="checkbox" class="custom-control-input" id="free-water">
														<label class="custom-control-label" for="free-water">Free Water</label>
													</div>
												</div>
												<div class="col-6">
													<div class="custom-control custom-switch">
														<input type="checkbox" class="custom-control-input" id="free-tour">
														<label class="custom-control-label" for="free-tour">Free Tour</label>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="product-action-link">
									<button type="button" class="product-button add-to-cart btn-block">Procced to checkout</button>
									<a href="#" class="readmore-link btn btn-link">View full details</a>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>