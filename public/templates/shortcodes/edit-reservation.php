<?php

/**
 * This file is used for templating the edit reservation by customers.
 *
 * @since 1.0.0
 * @package Easy_Reservations
 * @subpackage Easy_Reservations/includes
 */

defined('ABSPATH') || exit; // Exit if accessed directly.
?>
<div class="wrapper edit-order-wrapper">
    <div class="section-title">Edit Orders</div>
    <div class="contents">
        <div class="container">
            <div class="card mb-3">
                <div class="row no-gutters">
                    <div class="col-12 col-lg-4">
                        <a href="http://localhost/easy-reservation/product/3-days-and-4-night-at-shimla/">
                            <img src="http://localhost/easy-reservation/wp-content/uploads/2021/08/image-4.jpg" alt="img" class="card-img">
                        </a>
                    </div>
                    <div class="col-12 col-lg-8">
                        <div class="card-body">
                            <h3 class="card-title">
                                <a href="#" class="mr-2"> #12 - </a>
                                <a href="http://localhost/easy-reservation/product/3-days-and-4-night-at-shimla/">3 Days and 4 Night At Shimla</a>
                            </h3>
                            <form action="#" method="post" class="mb-0">
                                <div class="form-wrapper">
                                    <div class="bookingDates mb-3">
                                        <div class="row form-row input-daterange">
                                            <div class="col-6">
                                                <label for="" class="font-Poppins font-size-16 color-black">Check In</label>
                                                <div><input type="text" id="ersrv-quick-view-item-checkin-date" class="form-control date-control text-left rounded-lg hasDatepicker" placeholder="08/23/2021"></div>
                                            </div>
                                            <div class="col-6">
                                                <div class="d-flex justify-content-between">
                                                    <label for="" class="font-Poppins font-size-16 color-black">Check Out</label>
                                                    <a href="#" class="btn-link text-theme-primary">Check Avaibility</a>
                                                </div>
                                                <div><input type="text" id="ersrv-quick-view-item-checkout-date" class="form-control date-control text-left rounded-lg hasDatepicker" placeholder="08/24/2021"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-wrapper">
                                    <div class="bookItems mb-3">
                                        <div class="row form-row input-daterange">
                                            <div class="col-6">
                                                <label for="" class="font-Poppins font-size-16 color-black">Adults</label>
                                                <div><input placeholder="No. of adults" type="number" class="form-control rounded-lg" value="2" /></div>
                                            </div>
                                            <div class="col-6">
                                                <label for="" class="font-Poppins font-size-16 color-black">Kids</label>
                                                <div><input placeholder="No. of kids" type="number" class="form-control rounded-lg" value="2" /></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-wrapper">
                                    <div class="amenities mb-3">
                                        <label for="amenities" class="font-Poppins font-size-16 color-black">Amenities</label>
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
                                </div>
                                <div class="form-wrapper">
                                    <div class="CTA">
                                        <div class="row form-row align-items-center justify-content-end">
                                            <div class="col-12 col-md-6 mb-4 mb-md-0">
                                                <h4 class="font-Poppins font-size-16 color-black font-weight-bold mb-0">
                                                    Total Price : <span class="price">$800</span>
                                                </h4>
                                            </div>
                                            <div class="col-12 col-md-6 text-md-right">
                                                <button class="btn btn-accent">Update Order</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>