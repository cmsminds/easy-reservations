<?php
/**
 * This file is used for templating the export reservations modal.
 *
 * @since 1.0.0
 * @package Easy_Reservations
 * @subpackage Easy_Reservations/includes
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.
?>
<section class="wrapper search-page" id="wrapper">
    <div class="banner text-center">
        <div class="container">
            <div class="details mx-auto font-lato">
                <div class="page-title">
                    <h1 class="font-Poppins font-size-40 font-weight-semibold color-white">Search for a boat</h1>
                </div>
                <div class="form-wrapper">
                    <form action="#" class="form-inner">
                        <div class="form-row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control date-control ship-icon-field text-left rounded-lg" placeholder="June 20, 2021">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="input-daterange">
                                    <div class="form-row">
                                        <div class="col-12 col-md-6">
                                            <input type="text" class="form-control date-control text-left rounded-lg mb-3" placeholder="Check in">
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <input type="text" class="form-control date-control text-left rounded-lg" placeholder="Check out">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-12 col-md-6">
                                <div class="slider-wrapper">
                                    <div class="search-price-range-slider"></div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="input-daterange">
                                    <div class="form-row">
                                        <div class="col-12 col-md-6">
                                            <select class="selectpicker form-control Boat-Types" id="boat-types" data-size="5" data-style="btn-outline-light focus-none" title="Boat Type">
                                                <option>Single Boat</option>
                                                <option>Cruse</option>
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
                <h4 class="title font-Poppins">Advanced Search: <span class="result-no">2 results</span></h4>
                <div class="search-result-inner form-row">
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card">
                            <div class="media">
                                <a href="#">
                                    <img src="<?php echo esc_url (ERSRV_PLUGIN_URL . 'public/images/ship-image.jpg' ); ?>" alt="img" class="card-img" />
                                </a>
                            </div>
                            <div class="favorite">
                                <a href="#" class="favorite-link">
                                    <span class="sr-only">Favorite</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 4.248c-3.148-5.402-12-3.825-12 2.944 0 4.661 5.571 9.427 12 15.808 6.43-6.381 12-11.147 12-15.808 0-6.792-8.875-8.306-12-2.944z"/></svg>
                                </a>
                            </div>
                            <div class="price-info">
                                <div class="inner-wrapper color-black font-size-12 font-weight-semibold">
                                    <span class="color-accent font-size-18 font-Poppins">$500</span> - Per Night
                                </div>
                            </div>
                            <div class="card-body">
                                <h3 class="card-title">
                                    <a href="#">Galia 630 Soler</a>
                                </h3>
                                <div class="review-stars mb-3">
                                    <img src="<?php echo esc_url (ERSRV_PLUGIN_URL . 'public/images/stars.png' ); ?>" alt="stars">
                                </div>
                                <div class="amenities mb-3">
                                    <div class="location">
                                        <span class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 48 48" width="24px" height="24px"><path d="M 23.951172 4 A 1.50015 1.50015 0 0 0 23.072266 4.3222656 L 8.859375 15.519531 C 7.0554772 16.941163 6 19.113506 6 21.410156 L 6 40.5 C 6 41.863594 7.1364058 43 8.5 43 L 18.5 43 C 19.863594 43 21 41.863594 21 40.5 L 21 30.5 C 21 30.204955 21.204955 30 21.5 30 L 26.5 30 C 26.795045 30 27 30.204955 27 30.5 L 27 40.5 C 27 41.863594 28.136406 43 29.5 43 L 39.5 43 C 40.863594 43 42 41.863594 42 40.5 L 42 21.410156 C 42 19.113506 40.944523 16.941163 39.140625 15.519531 L 24.927734 4.3222656 A 1.50015 1.50015 0 0 0 23.951172 4 z M 24 7.4101562 L 37.285156 17.876953 C 38.369258 18.731322 39 20.030807 39 21.410156 L 39 40 L 30 40 L 30 30.5 C 30 28.585045 28.414955 27 26.5 27 L 21.5 27 C 19.585045 27 18 28.585045 18 30.5 L 18 40 L 9 40 L 9 21.410156 C 9 20.030807 9.6307412 18.731322 10.714844 17.876953 L 24 7.4101562 z"/></svg>
                                        </span>
                                        <span>MotorBoat / Whithout Captain</span>
                                    </div>
                                    <div class="d-flex align-items-center flex-wrap">
                                        <div class="map-loaction mr-3">
                                            <span class="icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                                                    <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                                                </svg>
                                            </span>
                                            <span>Cannes</span>
                                        </div>
                                        <div class="capacity mr-3">
                                            <span class="font-weight-bold mr-2">Capacity :</span>
                                            <span class="">1</span>
                                        </div>
                                        <div class="cabins mr-3">
                                            <span class="font-weight-bold mr-2">Cabins1 :</span>
                                            <span class="">1</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="btns-group">
                                    <a href="#" class="btn btn-accent mr-2">Book Now</a>
                                    <a href="#" class="btn btn-primary">Quick View</a>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card">
                            <div class="media">
                                <a href="#">
                                    <img src="<?php echo esc_url (ERSRV_PLUGIN_URL . 'public/images/ship-image.jpg' ); ?>" alt="img" class="card-img" />
                                </a>
                            </div>
                            <div class="favorite">
                                <a href="#" class="favorite-link">
                                    <span class="sr-only">Favorite</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 4.248c-3.148-5.402-12-3.825-12 2.944 0 4.661 5.571 9.427 12 15.808 6.43-6.381 12-11.147 12-15.808 0-6.792-8.875-8.306-12-2.944z"/></svg>
                                </a>
                            </div>
                            <div class="price-info">
                                <div class="inner-wrapper color-black font-size-12 font-weight-semibold">
                                    <span class="color-accent font-size-18 font-Poppins">$500</span> - Per Night
                                </div>
                            </div>
                            <div class="card-body">
                                <h3 class="card-title">
                                    <a href="#">Galia 630 Soler</a>
                                </h3>
                                <div class="review-stars mb-3">
                                    <img src="<?php echo esc_url (ERSRV_PLUGIN_URL . 'public/images/stars.png' ); ?>" alt="stars">
                                </div>
                                <div class="amenities mb-3">
                                    <div class="location">
                                        <span class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 48 48" width="24px" height="24px"><path d="M 23.951172 4 A 1.50015 1.50015 0 0 0 23.072266 4.3222656 L 8.859375 15.519531 C 7.0554772 16.941163 6 19.113506 6 21.410156 L 6 40.5 C 6 41.863594 7.1364058 43 8.5 43 L 18.5 43 C 19.863594 43 21 41.863594 21 40.5 L 21 30.5 C 21 30.204955 21.204955 30 21.5 30 L 26.5 30 C 26.795045 30 27 30.204955 27 30.5 L 27 40.5 C 27 41.863594 28.136406 43 29.5 43 L 39.5 43 C 40.863594 43 42 41.863594 42 40.5 L 42 21.410156 C 42 19.113506 40.944523 16.941163 39.140625 15.519531 L 24.927734 4.3222656 A 1.50015 1.50015 0 0 0 23.951172 4 z M 24 7.4101562 L 37.285156 17.876953 C 38.369258 18.731322 39 20.030807 39 21.410156 L 39 40 L 30 40 L 30 30.5 C 30 28.585045 28.414955 27 26.5 27 L 21.5 27 C 19.585045 27 18 28.585045 18 30.5 L 18 40 L 9 40 L 9 21.410156 C 9 20.030807 9.6307412 18.731322 10.714844 17.876953 L 24 7.4101562 z"/></svg>
                                        </span>
                                        <span>MotorBoat / Whithout Captain</span>
                                    </div>
                                    <div class="d-flex align-items-center flex-wrap">
                                        <div class="map-loaction mr-3">
                                            <span class="icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                                                    <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                                                </svg>
                                            </span>
                                            <span>Cannes</span>
                                        </div>
                                        <div class="capacity mr-3">
                                            <span class="font-weight-bold mr-2">Capacity :</span>
                                            <span class="">1</span>
                                        </div>
                                        <div class="cabins mr-3">
                                            <span class="font-weight-bold mr-2">Cabins1 :</span>
                                            <span class="">1</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="btns-group">
                                    <a href="#" class="btn btn-accent mr-2">Book Now</a>
                                    <a href="#" class="btn btn-primary">Quick View</a>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card">
                            <div class="media">
                                <a href="#">
                                    <img src="<?php echo esc_url (ERSRV_PLUGIN_URL . 'public/images/ship-image.jpg' ); ?>" alt="img" class="card-img" />
                                </a>
                            </div>
                            <div class="favorite">
                                <a href="#" class="favorite-link">
                                    <span class="sr-only">Favorite</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 4.248c-3.148-5.402-12-3.825-12 2.944 0 4.661 5.571 9.427 12 15.808 6.43-6.381 12-11.147 12-15.808 0-6.792-8.875-8.306-12-2.944z"/></svg>
                                </a>
                            </div>
                            <div class="price-info">
                                <div class="inner-wrapper color-black font-size-12 font-weight-semibold">
                                    <span class="color-accent font-size-18 font-Poppins">$500</span> - Per Night
                                </div>
                            </div>
                            <div class="card-body">
                                <h3 class="card-title">
                                    <a href="#">Galia 630 Soler</a>
                                </h3>
                                <div class="review-stars mb-3">
                                    <img src="<?php echo esc_url (ERSRV_PLUGIN_URL . 'public/images/stars.png' ); ?>" alt="stars">
                                </div>
                                <div class="amenities mb-3">
                                    <div class="location">
                                        <span class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 48 48" width="24px" height="24px"><path d="M 23.951172 4 A 1.50015 1.50015 0 0 0 23.072266 4.3222656 L 8.859375 15.519531 C 7.0554772 16.941163 6 19.113506 6 21.410156 L 6 40.5 C 6 41.863594 7.1364058 43 8.5 43 L 18.5 43 C 19.863594 43 21 41.863594 21 40.5 L 21 30.5 C 21 30.204955 21.204955 30 21.5 30 L 26.5 30 C 26.795045 30 27 30.204955 27 30.5 L 27 40.5 C 27 41.863594 28.136406 43 29.5 43 L 39.5 43 C 40.863594 43 42 41.863594 42 40.5 L 42 21.410156 C 42 19.113506 40.944523 16.941163 39.140625 15.519531 L 24.927734 4.3222656 A 1.50015 1.50015 0 0 0 23.951172 4 z M 24 7.4101562 L 37.285156 17.876953 C 38.369258 18.731322 39 20.030807 39 21.410156 L 39 40 L 30 40 L 30 30.5 C 30 28.585045 28.414955 27 26.5 27 L 21.5 27 C 19.585045 27 18 28.585045 18 30.5 L 18 40 L 9 40 L 9 21.410156 C 9 20.030807 9.6307412 18.731322 10.714844 17.876953 L 24 7.4101562 z"/></svg>
                                        </span>
                                        <span>MotorBoat / Whithout Captain</span>
                                    </div>
                                    <div class="d-flex align-items-center flex-wrap">
                                        <div class="map-loaction mr-3">
                                            <span class="icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                                                    <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                                                </svg>
                                            </span>
                                            <span>Cannes</span>
                                        </div>
                                        <div class="capacity mr-3">
                                            <span class="font-weight-bold mr-2">Capacity :</span>
                                            <span class="">1</span>
                                        </div>
                                        <div class="cabins mr-3">
                                            <span class="font-weight-bold mr-2">Cabins1 :</span>
                                            <span class="">1</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="btns-group">
                                    <a href="#" class="btn btn-accent mr-2">Book Now</a>
                                    <a href="#" class="btn btn-primary">Quick View</a>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card">
                            <div class="media">
                                <a href="#">
                                    <img src="<?php echo esc_url (ERSRV_PLUGIN_URL . 'public/images/ship-image.jpg' ); ?>" alt="img" class="card-img" />
                                </a>
                            </div>
                            <div class="favorite">
                                <a href="#" class="favorite-link">
                                    <span class="sr-only">Favorite</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 4.248c-3.148-5.402-12-3.825-12 2.944 0 4.661 5.571 9.427 12 15.808 6.43-6.381 12-11.147 12-15.808 0-6.792-8.875-8.306-12-2.944z"/></svg>
                                </a>
                            </div>
                            <div class="price-info">
                                <div class="inner-wrapper color-black font-size-12 font-weight-semibold">
                                    <span class="color-accent font-size-18 font-Poppins">$500</span> - Per Night
                                </div>
                            </div>
                            <div class="card-body">
                                <h3 class="card-title">
                                    <a href="#">Galia 630 Soler</a>
                                </h3>
                                <div class="review-stars mb-3">
                                    <img src="<?php echo esc_url (ERSRV_PLUGIN_URL . 'public/images/stars.png' ); ?>" alt="stars">
                                </div>
                                <div class="amenities mb-3">
                                    <div class="location">
                                        <span class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 48 48" width="24px" height="24px"><path d="M 23.951172 4 A 1.50015 1.50015 0 0 0 23.072266 4.3222656 L 8.859375 15.519531 C 7.0554772 16.941163 6 19.113506 6 21.410156 L 6 40.5 C 6 41.863594 7.1364058 43 8.5 43 L 18.5 43 C 19.863594 43 21 41.863594 21 40.5 L 21 30.5 C 21 30.204955 21.204955 30 21.5 30 L 26.5 30 C 26.795045 30 27 30.204955 27 30.5 L 27 40.5 C 27 41.863594 28.136406 43 29.5 43 L 39.5 43 C 40.863594 43 42 41.863594 42 40.5 L 42 21.410156 C 42 19.113506 40.944523 16.941163 39.140625 15.519531 L 24.927734 4.3222656 A 1.50015 1.50015 0 0 0 23.951172 4 z M 24 7.4101562 L 37.285156 17.876953 C 38.369258 18.731322 39 20.030807 39 21.410156 L 39 40 L 30 40 L 30 30.5 C 30 28.585045 28.414955 27 26.5 27 L 21.5 27 C 19.585045 27 18 28.585045 18 30.5 L 18 40 L 9 40 L 9 21.410156 C 9 20.030807 9.6307412 18.731322 10.714844 17.876953 L 24 7.4101562 z"/></svg>
                                        </span>
                                        <span>MotorBoat / Whithout Captain</span>
                                    </div>
                                    <div class="d-flex align-items-center flex-wrap">
                                        <div class="map-loaction mr-3">
                                            <span class="icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                                                    <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                                                </svg>
                                            </span>
                                            <span>Cannes</span>
                                        </div>
                                        <div class="capacity mr-3">
                                            <span class="font-weight-bold mr-2">Capacity :</span>
                                            <span class="">1</span>
                                        </div>
                                        <div class="cabins mr-3">
                                            <span class="font-weight-bold mr-2">Cabins1 :</span>
                                            <span class="">1</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="btns-group">
                                    <a href="#" class="btn btn-accent mr-2">Book Now</a>
                                    <a href="#" class="btn btn-primary">Quick View</a>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card">
                            <div class="media">
                                <a href="#">
                                    <img src="<?php echo esc_url (ERSRV_PLUGIN_URL . 'public/images/ship-image.jpg' ); ?>" alt="img" class="card-img" />
                                </a>
                            </div>
                            <div class="favorite">
                                <a href="#" class="favorite-link">
                                    <span class="sr-only">Favorite</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 4.248c-3.148-5.402-12-3.825-12 2.944 0 4.661 5.571 9.427 12 15.808 6.43-6.381 12-11.147 12-15.808 0-6.792-8.875-8.306-12-2.944z"/></svg>
                                </a>
                            </div>
                            <div class="price-info">
                                <div class="inner-wrapper color-black font-size-12 font-weight-semibold">
                                    <span class="color-accent font-size-18 font-Poppins">$500</span> - Per Night
                                </div>
                            </div>
                            <div class="card-body">
                                <h3 class="card-title">
                                    <a href="#">Galia 630 Soler</a>
                                </h3>
                                <div class="review-stars mb-3">
                                    <img src="<?php echo esc_url (ERSRV_PLUGIN_URL . 'public/images/stars.png' ); ?>" alt="stars">
                                </div>
                                <div class="amenities mb-3">
                                    <div class="location">
                                        <span class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 48 48" width="24px" height="24px"><path d="M 23.951172 4 A 1.50015 1.50015 0 0 0 23.072266 4.3222656 L 8.859375 15.519531 C 7.0554772 16.941163 6 19.113506 6 21.410156 L 6 40.5 C 6 41.863594 7.1364058 43 8.5 43 L 18.5 43 C 19.863594 43 21 41.863594 21 40.5 L 21 30.5 C 21 30.204955 21.204955 30 21.5 30 L 26.5 30 C 26.795045 30 27 30.204955 27 30.5 L 27 40.5 C 27 41.863594 28.136406 43 29.5 43 L 39.5 43 C 40.863594 43 42 41.863594 42 40.5 L 42 21.410156 C 42 19.113506 40.944523 16.941163 39.140625 15.519531 L 24.927734 4.3222656 A 1.50015 1.50015 0 0 0 23.951172 4 z M 24 7.4101562 L 37.285156 17.876953 C 38.369258 18.731322 39 20.030807 39 21.410156 L 39 40 L 30 40 L 30 30.5 C 30 28.585045 28.414955 27 26.5 27 L 21.5 27 C 19.585045 27 18 28.585045 18 30.5 L 18 40 L 9 40 L 9 21.410156 C 9 20.030807 9.6307412 18.731322 10.714844 17.876953 L 24 7.4101562 z"/></svg>
                                        </span>
                                        <span>MotorBoat / Whithout Captain</span>
                                    </div>
                                    <div class="d-flex align-items-center flex-wrap">
                                        <div class="map-loaction mr-3">
                                            <span class="icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                                                    <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                                                </svg>
                                            </span>
                                            <span>Cannes</span>
                                        </div>
                                        <div class="capacity mr-3">
                                            <span class="font-weight-bold mr-2">Capacity :</span>
                                            <span class="">1</span>
                                        </div>
                                        <div class="cabins mr-3">
                                            <span class="font-weight-bold mr-2">Cabins1 :</span>
                                            <span class="">1</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="btns-group">
                                    <a href="#" class="btn btn-accent mr-2">Book Now</a>
                                    <a href="#" class="btn btn-primary">Quick View</a>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card">
                            <div class="media">
                                <a href="#">
                                    <img src="<?php echo esc_url (ERSRV_PLUGIN_URL . 'public/images/ship-image.jpg' ); ?>" alt="img" class="card-img" />
                                </a>
                            </div>
                            <div class="favorite">
                                <a href="#" class="favorite-link">
                                    <span class="sr-only">Favorite</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 4.248c-3.148-5.402-12-3.825-12 2.944 0 4.661 5.571 9.427 12 15.808 6.43-6.381 12-11.147 12-15.808 0-6.792-8.875-8.306-12-2.944z"/></svg>
                                </a>
                            </div>
                            <div class="price-info">
                                <div class="inner-wrapper color-black font-size-12 font-weight-semibold">
                                    <span class="color-accent font-size-18 font-Poppins">$500</span> - Per Night
                                </div>
                            </div>
                            <div class="card-body">
                                <h3 class="card-title">
                                    <a href="#">Galia 630 Soler</a>
                                </h3>
                                <div class="review-stars mb-3">
                                    <img src="<?php echo esc_url (ERSRV_PLUGIN_URL . 'public/images/stars.png' ); ?>" alt="stars">
                                </div>
                                <div class="amenities mb-3">
                                    <div class="location">
                                        <span class="icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 48 48" width="24px" height="24px"><path d="M 23.951172 4 A 1.50015 1.50015 0 0 0 23.072266 4.3222656 L 8.859375 15.519531 C 7.0554772 16.941163 6 19.113506 6 21.410156 L 6 40.5 C 6 41.863594 7.1364058 43 8.5 43 L 18.5 43 C 19.863594 43 21 41.863594 21 40.5 L 21 30.5 C 21 30.204955 21.204955 30 21.5 30 L 26.5 30 C 26.795045 30 27 30.204955 27 30.5 L 27 40.5 C 27 41.863594 28.136406 43 29.5 43 L 39.5 43 C 40.863594 43 42 41.863594 42 40.5 L 42 21.410156 C 42 19.113506 40.944523 16.941163 39.140625 15.519531 L 24.927734 4.3222656 A 1.50015 1.50015 0 0 0 23.951172 4 z M 24 7.4101562 L 37.285156 17.876953 C 38.369258 18.731322 39 20.030807 39 21.410156 L 39 40 L 30 40 L 30 30.5 C 30 28.585045 28.414955 27 26.5 27 L 21.5 27 C 19.585045 27 18 28.585045 18 30.5 L 18 40 L 9 40 L 9 21.410156 C 9 20.030807 9.6307412 18.731322 10.714844 17.876953 L 24 7.4101562 z"/></svg>
                                        </span>
                                        <span>MotorBoat / Whithout Captain</span>
                                    </div>
                                    <div class="d-flex align-items-center flex-wrap">
                                        <div class="map-loaction mr-3">
                                            <span class="icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                                                    <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                                                </svg>
                                            </span>
                                            <span>Cannes</span>
                                        </div>
                                        <div class="capacity mr-3">
                                            <span class="font-weight-bold mr-2">Capacity :</span>
                                            <span class="">1</span>
                                        </div>
                                        <div class="cabins mr-3">
                                            <span class="font-weight-bold mr-2">Cabins1 :</span>
                                            <span class="">1</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="btns-group">
                                    <a href="#" class="btn btn-accent mr-2">Book Now</a>
                                    <a href="#" class="btn btn-primary">Quick View</a>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!--  -->
                    <div class="col-12">
                        <div class="loadmore text-center mt-5 pt-2">
                            <a href="#" class="btn btn-outline-primary btn-xl font-size-18 loadmore-btn mx-auto">Load  More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>