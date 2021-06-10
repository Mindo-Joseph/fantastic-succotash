@extends('layouts.store', ['title' => 'Login'])

@section('css')
<style type="text/css">
    .main-menu .brand-logo {
        display: inline-block;
        padding-top: 20px;
        padding-bottom: 20px;
    }
</style>

@endsection

@section('content')

<header>
    <div class="mobile-fix-option"></div>
    @include('layouts.store/left-sidebar')
</header>
<style type="text/css">
    .productVariants .firstChild {
        min-width: 150px;
        text-align: left !important;
        border-radius: 0% !important;
        margin-right: 10px;
        cursor: default;
        border: none !important;
    }

    .product-right .color-variant li,
    .productVariants .otherChild {
        height: 35px;
        width: 35px;
        border-radius: 50%;
        margin-right: 10px;
        cursor: pointer;
        border: 1px solid #f7f7f7;
        text-align: center;
    }

    .productVariants .otherSize {
        height: auto !important;
        width: auto !important;
        border: none !important;
        border-radius: 0%;
    }

    .product-right .size-box ul li.active {
        background-color: inherit;
    }

    .login-page .theme-card .theme-form input {
        margin-bottom: 5px;
    }

    .invalid-feedback {
        display: block;
    }
</style>

<section class="section-b-space">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="account-sidebar"><a class="popup-btn">my account</a></div>
                <div class="dashboard-left">
                    <div class="collection-mobile-back"><span class="filter-back"><i class="fa fa-angle-left" aria-hidden="true"></i> back</span></div>
                    <div class="block-content">
                        <ul>
                            <li><a href="{{route('user.profile')}}">Account Info</a></li>
                            <li><a href="{{route('user.addressBook')}}">Address Book</a></li>
                            <li class="active"><a href="{{route('user.orders')}}">My Orders</a></li>
                            <li><a href="{{route('user.wishlists')}}">My Wishlist</a></li>
                            <li><a href="{{route('user.account')}}">My Account</a></li>
                            <li><a href="{{route('user.changePassword')}}">Change Password</a></li>
                            <li class="last"><a href="{{route('user.logout')}}" >Log Out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="dashboard-right">
                    <div class="dashboard">
                        <div class="page-title">
                            <h2>Orders</h2>
                        </div>
                        <div class="welcome-msg">
                            <h5>Here are all your previous orders !</h5>
                        </div>
                        <div class="box-account box-info">
                            <!-- <div class="box-head">
                                <h2>Account Information</h2>
                            </div> -->
                            <div class="row">

                                <div class="col-lg-4 col-sm-6 mt-3">
                                    <div class="outer-box p-0">
                                       <ul class="d-inline-flex align-items-center px-2 py-1">
                                            <li class="product-icon">
                                                <img src="{{asset('assets/images/products/product-1.png')}}" alt="product-img" height="50" />
                                            </li>
                                            <li class="product-title pl-1">
                                                <h4 class="m-0">T-Shirt</h4>
                                                <p class="m-0">Sector 21, Chandigarh</p>
                                            </li>
                                       </ul>
                                       <p class="m-0 py-2 pr-2 float-right">Delivered</p>
                                       <hr class="m-0">
                                       <div class="order_details p-2">
                                            <div class="order-no mb-2">
                                                <p class="order_head">Order Number</p>
                                                <p class="order_sub_title">1312370737</p>
                                           </div> 
                                           <div class="total-amt mb-2">
                                                <p class="order_head">TOTAL AMOUNT</p>
                                                <p class="order_sub_title">$73.00</p>
                                           </div> 
                                           <div class="total-items mb-2">
                                                <p class="order_head">ITEMS</p>
                                                <p class="order_sub_title">1 T-Shirt</p>
                                           </div> 
                                           <div class="ordered-on mb-2">
                                                <p class="order_head">ORDERED ON</p>
                                                <p class="order_sub_title">March 29, 2019 at 09:01 PMs</p>
                                           </div> 

                                           <div class="">
                                                <fieldset class="rate">
                                                    <input type="radio" id="rating10" name="rating" value="10" /><label for="rating10" title="5 stars"></label>
                                                    <input type="radio" id="rating9" name="rating" value="9" /><label class="half" for="rating9" title="4 1/2 stars"></label>
                                                    <input type="radio" id="rating8" name="rating" value="8" /><label for="rating8" title="4 stars"></label>
                                                    <input type="radio" id="rating7" name="rating" value="7" /><label class="half" for="rating7" title="3 1/2 stars"></label>
                                                    <input type="radio" id="rating6" name="rating" value="6" /><label for="rating6" title="3 stars"></label>
                                                    <input type="radio" id="rating5" name="rating" value="5" /><label class="half" for="rating5" title="2 1/2 stars"></label>
                                                    <input type="radio" id="rating4" name="rating" value="4" /><label for="rating4" title="2 stars"></label>
                                                    <input type="radio" id="rating3" name="rating" value="3" /><label class="half" for="rating3" title="1 1/2 stars"></label>
                                                    <input type="radio" id="rating2" name="rating" value="2" /><label for="rating2" title="1 star"></label>
                                                    <input type="radio" id="rating1" name="rating" value="1" /><label class="half" for="rating1" title="1/2 star"></label>
                                                    <span>Rated:</span>
                                                </fieldset>
                                           </div>
                                           
                                           <div class="text-right mt-3">
                                                <a class="btn btn-solid ml-auto" data-toggle="modal" data-target="#order-details">
                                                    View Details
                                                </a>
                                            </div>
                                       </div>
                                    </div>                                    
                                </div>
                                <div class="col-lg-4 col-sm-6 mt-3">
                                    <div class="outer-box p-0">
                                       <ul class="d-inline-flex align-items-center px-2 py-1">
                                            <li class="product-icon">
                                                <img src="{{asset('assets/images/products/product-1.png')}}" alt="product-img" height="50" />
                                            </li>
                                            <li class="product-title pl-1">
                                                <h4 class="m-0">T-Shirt</h4>
                                                <p class="m-0">Sector 21, Chandigarh</p>
                                            </li>
                                       </ul>
                                       <p class="m-0 py-2 pr-2 float-right">Delivered</p>
                                       <hr class="m-0">
                                       <div class="order_details p-2">
                                            <div class="order-no mb-2">
                                                <p class="order_head">Order Number</p>
                                                <p class="order_sub_title">1312370737</p>
                                           </div> 
                                           <div class="total-amt mb-2">
                                                <p class="order_head">TOTAL AMOUNT</p>
                                                <p class="order_sub_title">$73.00</p>
                                           </div> 
                                           <div class="total-items mb-2">
                                                <p class="order_head">ITEMS</p>
                                                <p class="order_sub_title">1 T-Shirt</p>
                                           </div> 
                                           <div class="ordered-on mb-2">
                                                <p class="order_head">ORDERED ON</p>
                                                <p class="order_sub_title">March 29, 2019 at 09:01 PMs</p>
                                           </div> 

                                           <div class="">
                                                <fieldset class="rate">
                                                    <input type="radio" id="rating10" name="rating" value="10" /><label for="rating10" title="5 stars"></label>
                                                    <input type="radio" id="rating9" name="rating" value="9" /><label class="half" for="rating9" title="4 1/2 stars"></label>
                                                    <input type="radio" id="rating8" name="rating" value="8" /><label for="rating8" title="4 stars"></label>
                                                    <input type="radio" id="rating7" name="rating" value="7" /><label class="half" for="rating7" title="3 1/2 stars"></label>
                                                    <input type="radio" id="rating6" name="rating" value="6" /><label for="rating6" title="3 stars"></label>
                                                    <input type="radio" id="rating5" name="rating" value="5" /><label class="half" for="rating5" title="2 1/2 stars"></label>
                                                    <input type="radio" id="rating4" name="rating" value="4" /><label for="rating4" title="2 stars"></label>
                                                    <input type="radio" id="rating3" name="rating" value="3" /><label class="half" for="rating3" title="1 1/2 stars"></label>
                                                    <input type="radio" id="rating2" name="rating" value="2" /><label for="rating2" title="1 star"></label>
                                                    <input type="radio" id="rating1" name="rating" value="1" /><label class="half" for="rating1" title="1/2 star"></label>
                                                    <span>Rated:</span>
                                                </fieldset>
                                           </div>
                                           
                                           <div class="text-right mt-3">
                                                <a class="btn btn-solid ml-auto">
                                                    View Details
                                                </a>
                                            </div>
                                       </div>
                                    </div>                                    
                                </div>
                                <div class="col-lg-4 col-sm-6 mt-3">
                                    <div class="outer-box p-0">
                                       <ul class="d-inline-flex align-items-center px-2 py-1">
                                            <li class="product-icon">
                                                <img src="{{asset('assets/images/products/product-1.png')}}" alt="product-img" height="50" />
                                            </li>
                                            <li class="product-title pl-1">
                                                <h4 class="m-0">T-Shirt</h4>
                                                <p class="m-0">Sector 21, Chandigarh</p>
                                            </li>
                                       </ul>
                                       <p class="m-0 py-2 pr-2 float-right">Delivered</p>
                                       <hr class="m-0">
                                       <div class="order_details p-2">
                                            <div class="order-no mb-2">
                                                <p class="order_head">Order Number</p>
                                                <p class="order_sub_title">1312370737</p>
                                           </div> 
                                           <div class="total-amt mb-2">
                                                <p class="order_head">TOTAL AMOUNT</p>
                                                <p class="order_sub_title">$73.00</p>
                                           </div> 
                                           <div class="total-items mb-2">
                                                <p class="order_head">ITEMS</p>
                                                <p class="order_sub_title">1 T-Shirt</p>
                                           </div> 
                                           <div class="ordered-on mb-2">
                                                <p class="order_head">ORDERED ON</p>
                                                <p class="order_sub_title">March 29, 2019 at 09:01 PMs</p>
                                           </div> 

                                           <div class="">
                                                <fieldset class="rate">
                                                    <input type="radio" id="rating10" name="rating" value="10" /><label for="rating10" title="5 stars"></label>
                                                    <input type="radio" id="rating9" name="rating" value="9" /><label class="half" for="rating9" title="4 1/2 stars"></label>
                                                    <input type="radio" id="rating8" name="rating" value="8" /><label for="rating8" title="4 stars"></label>
                                                    <input type="radio" id="rating7" name="rating" value="7" /><label class="half" for="rating7" title="3 1/2 stars"></label>
                                                    <input type="radio" id="rating6" name="rating" value="6" /><label for="rating6" title="3 stars"></label>
                                                    <input type="radio" id="rating5" name="rating" value="5" /><label class="half" for="rating5" title="2 1/2 stars"></label>
                                                    <input type="radio" id="rating4" name="rating" value="4" /><label for="rating4" title="2 stars"></label>
                                                    <input type="radio" id="rating3" name="rating" value="3" /><label class="half" for="rating3" title="1 1/2 stars"></label>
                                                    <input type="radio" id="rating2" name="rating" value="2" /><label for="rating2" title="1 star"></label>
                                                    <input type="radio" id="rating1" name="rating" value="1" /><label class="half" for="rating1" title="1/2 star"></label>
                                                    <span>Rated:</span>
                                                </fieldset>
                                           </div>
                                           
                                           <div class="text-right mt-3">
                                                <a class="btn btn-solid ml-auto">
                                                    View Details
                                                </a>
                                            </div>
                                       </div>
                                    </div>                                    
                                </div>

                                <div class="col-12">        
                                    <div class="row no-gutters order_head">
                                        <div class="col-md-3"><h4>Order Number</h4></div>
                                        <div class="col-md-3"><h4>Date & Time</h4></div>
                                        <div class="col-md-3"><h4>Customer Name</h4></div>
                                        <div class="col-md-3"><h4>Address</h4></div>
                                    </div>
                                    <div class="row no-gutters order_data mb-lg-3">
                                        <div class="col-md-3">#3215412</div>
                                        <div class="col-md-3">Monday, May 24, 2021, 13:22 PM</div>
                                        <div class="col-md-3">
                                        <a href="#">Chander</a>
                                        </div>
                                        <div class="col-md-3">
                                            <p class="ellipsis" data-toggle="tooltip" data-placement="top" title="RTC Cross Road, P & T Colony, Jawahar Nagar, Himayatnagar, Hyderabad, Telangana, India">
                                            RTC Cross Road, P & T Colony, Jawahar Nagar, Himayatnagar, Hyderabad, Telangana, India
                                            </p>
                                        </div>                    
                                    </div>
                                    <div class="row">
                                        <div class="col-md-9 mb-3">
                                            <a href="#" class="row order_detail order_detail_data align-items-top pb-3 card-box no-gutters mb-0 h-100">
                                                <span class="left_arrow pulse"></span>
                                                <div class="col-5 col-sm-3">
                                                    <h4 class="m-0">test</h4>
                                                    <ul class="status_box mt-3 pl-0">
                                                        <li><img src="{{ asset('assets/images/order-icon.svg') }}" alt=""><label class="m-0 in-progress">Accepted</label></li>
                                                        <li><img src="{{ asset('assets/images/driver_icon.svg') }}" alt=""><label class="m-0 in-progress">Assigned</label></li>
                                                    </ul>
                                                </div>
                                                <div class="col-7 col-sm-4">
                                                    <ul class="product_list d-flex align-items-center p-0 flex-wrap m-0">
                                                            <li class="text-center">
                                                                <img src="{{ asset('assets/images/order-icon.svg') }}" alt="">
                                                                <span class="item_no position-absolute">x2</span>
                                                                <label class="items_price">$20.00</label>
                                                            </li>                                   
                                                    </ul>
                                                </div>
                                                <div class="col-md-5 mt-md-0 mt-sm-2">
                                                    <ul class="price_box_bottom m-0 p-0">
                                                        <li class="d-flex align-items-center justify-content-between">
                                                            <label class="m-0">Product Total</label>
                                                            <span>$20.00</span>
                                                        </li>
                                                        <li class="d-flex align-items-center justify-content-between">
                                                            <label class="m-0">Coupon (10%)</label>
                                                            <span>$0.00</span>
                                                        </li>
                                                        <li class="d-flex align-items-center justify-content-between">
                                                            <label class="m-0">Delivery Fee</label>
                                                            <span>$20.00</span>
                                                        </li>
                                                        <li class="grand_total d-flex align-items-center justify-content-between">
                                                            <label class="m-0">Amount</label>
                                                            <span>$320.00</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </a>
                                        </div>    
                                        <div class="col-md-3 mb-3">
                                            <div class="card-box p-2 mb-0 h-100">
                                                <ul class="price_box_bottom m-0 pl-0 pt-1">
                                                    <li class="d-flex align-items-center justify-content-between">
                                                        <label class="m-0">Sub Total</label>
                                                        <span>$20.00</span>
                                                    </li>
                                                    <li class="d-flex align-items-center justify-content-between">
                                                        <label class="m-0">Wallet</label>
                                                        <span>$0.00</span>
                                                    </li>
                                                    <li class="d-flex align-items-center justify-content-between">
                                                        <label class="m-0">Loyalty</label>
                                                        <span>$20.00</span>
                                                    </li>
                                                    <li class="d-flex align-items-center justify-content-between">
                                                        <label class="m-0">Tax</label>
                                                        <span>$320.00</span>
                                                    </li>
                                                    <li class="grand_total d-flex align-items-center justify-content-between">
                                                        <label class="m-0">Total Payable</label>
                                                        <span>$320.00</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-9 mb-3">
                                            <a href="#" class="row order_detail order_detail_data align-items-top pb-3 card-box no-gutters mb-0 h-100">
                                                <span class="left_arrow pulse"></span>
                                                <div class="col-5 col-sm-3">
                                                    <h4 class="m-0">test</h4>
                                                    <ul class="status_box mt-3 pl-0">
                                                        <li><img src="{{ asset('assets/images/order-icon.svg') }}" alt=""><label class="m-0 in-progress">Accepted</label></li>
                                                        <li><img src="{{ asset('assets/images/driver_icon.svg') }}" alt=""><label class="m-0 in-progress">Assigned</label></li>
                                                    </ul>
                                                </div>
                                                <div class="col-7 col-sm-4">
                                                    <ul class="product_list d-flex align-items-center p-0 flex-wrap m-0">
                                                            <li class="text-center">
                                                                <img src="{{ asset('assets/images/order-icon.svg') }}" alt="">
                                                                <span class="item_no position-absolute">x2</span>
                                                                <label class="items_price">$20.00</label>
                                                            </li>                                   
                                                    </ul>
                                                </div>
                                                <div class="col-md-5 mt-md-0 mt-sm-2">
                                                    <ul class="price_box_bottom m-0 p-0">
                                                        <li class="d-flex align-items-center justify-content-between">
                                                            <label class="m-0">Product Total</label>
                                                            <span>$20.00</span>
                                                        </li>
                                                        <li class="d-flex align-items-center justify-content-between">
                                                            <label class="m-0">Coupon (10%)</label>
                                                            <span>$0.00</span>
                                                        </li>
                                                        <li class="d-flex align-items-center justify-content-between">
                                                            <label class="m-0">Delivery Fee</label>
                                                            <span>$20.00</span>
                                                        </li>
                                                        <li class="grand_total d-flex align-items-center justify-content-between">
                                                            <label class="m-0">Amount</label>
                                                            <span>$320.00</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </a>
                                        </div>    
                                        <div class="col-md-3 mb-3">
                                            <div class="card-box p-2 mb-0 h-100">
                                                <ul class="price_box_bottom m-0 pl-0 pt-1">
                                                    <li class="d-flex align-items-center justify-content-between">
                                                        <label class="m-0">Sub Total</label>
                                                        <span>$20.00</span>
                                                    </li>
                                                    <li class="d-flex align-items-center justify-content-between">
                                                        <label class="m-0">Wallet</label>
                                                        <span>$0.00</span>
                                                    </li>
                                                    <li class="d-flex align-items-center justify-content-between">
                                                        <label class="m-0">Loyalty</label>
                                                        <span>$20.00</span>
                                                    </li>
                                                    <li class="d-flex align-items-center justify-content-between">
                                                        <label class="m-0">Tax</label>
                                                        <span>$320.00</span>
                                                    </li>
                                                    <li class="grand_total d-flex align-items-center justify-content-between">
                                                        <label class="m-0">Total Payable</label>
                                                        <span>$320.00</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="row">

                                <div class="col-sm-6">
                                    <div class="box">
                                        <div class="box-title">
                                            <h3>Order Number #2</h3>

                                            <a data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                                                View Details
                                            </a>
                                        </div>
                                        <div class="box-content">
                                            <h6>Total Items: 3</h6>
                                            <h6>Order Status: Created</h6>
                                            <h6>Recepient Name: Puneet</h6>
                                            <h6>Recepient Email: gargpuneet217@gmail.com</h6>
                                            <h6>Recepient Phone number: 9996778910</h6>
                                            <h6>Payment Method: Credit Card</h6>
                                            <h6>Payment Status: Paid</h6>

                                            <div class="collapse" id="collapseExample">
                                                <h5>Products</h5>
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <h6>
                                                            <span>
                                                                <a href="{{route('second', ['ecommerce', 'product-detail'])}}"><img src="{{asset('assets/images/products/product-1.png')}}" alt="product-img" height="50" /></a>
                                                            </span>
                                                            Product 1
                                                        </h6>
                                                        <h6>Variants</h6>
                                                        <ul class="ml-4">
                                                            <li style="display:list-item;">cheese</li>
                                                            <li style="display:list-item;">small</li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <h6>
                                                            <span>
                                                                <a href="{{route('second', ['ecommerce', 'product-detail'])}}"><img src="{{asset('assets/images/products/product-1.png')}}" alt="product-img" height="50" /></a>
                                                            </span>
                                                            Product 2
                                                        </h6>
                                                        <h6>Variants</h6>
                                                        <ul class="ml-4">
                                                            <li style="display:list-item;">cheese</li>
                                                            <li style="display:list-item;">small</li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <h6>
                                                            <span>
                                                                <a href="{{route('second', ['ecommerce', 'product-detail'])}}"><img src="{{asset('assets/images/products/product-1.png')}}" alt="product-img" height="50" /></a>
                                                            </span>
                                                            Product 2
                                                        </h6>
                                                        <h6>Variants</h6>
                                                        <ul class="ml-4">
                                                            <li style="display:list-item;">cheese</li>
                                                            <li style="display:list-item;">small</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="box">
                                        <div class="box-title">
                                            <h3>Order Number #3</h3><a data-toggle="collapse" href="#collapseExample2" role="button" aria-expanded="false" aria-controls="collapseExample">
                                                View Details
                                            </a>
                                        </div>
                                        <div class="box-content">
                                            <h6>Total Items: 3</h6>
                                            <h6>Order Status: Created</h6>
                                            <h6>Recepient Name: Puneet</h6>
                                            <h6>Recepient Email: gargpuneet217@gmail.com</h6>
                                            <h6>Recepient Phone number: 9996778910</h6>
                                            <h6>Payment Method: Credit Card</h6>
                                            <h6>Payment Status: Paid</h6>

                                            <div class="collapse" id="collapseExample2">
                                                <h5>Products</h5>
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <h6>
                                                            <span>
                                                                <a href="{{route('second', ['ecommerce', 'product-detail'])}}"><img src="{{asset('assets/images/products/product-1.png')}}" alt="product-img" height="50" /></a>
                                                            </span>
                                                            Product 1
                                                        </h6>
                                                        <h6>Variants</h6>
                                                        <ul class="ml-4">
                                                            <li style="display:list-item;">cheese</li>
                                                            <li style="display:list-item;">small</li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <h6>
                                                            <span>
                                                                <a href="{{route('second', ['ecommerce', 'product-detail'])}}"><img src="{{asset('assets/images/products/product-1.png')}}" alt="product-img" height="50" /></a>
                                                            </span>
                                                            Product 2
                                                        </h6>
                                                        <h6>Variants</h6>
                                                        <ul class="ml-4">
                                                            <li style="display:list-item;">cheese</li>
                                                            <li style="display:list-item;">small</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- View Order details Modal -->
<div class="modal fade order_detail" id="order-details" tabindex="-1" aria-labelledby="order-detailsLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header pb-2">
        <h5 class="modal-title" id="order-detailsLabel">Order Details</h5>
        <button type="button" class="close top_right" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body pt-0">
        <ul class="d-inline-flex align-items-center p-0">
            <li class="product-icon">
                <img src="http://local.myorder.com/assets/images/products/product-1.png" alt="product-img" height="50">
            </li>
            <li class="product-title pl-1">
                <h4 class="m-0">T-Shirt</h4>
                <span class="rating vertical-middle my-1">
                    <i class="fa fa-star-o" aria-hidden="true"></i>
                    <i class="fa fa-star-o" aria-hidden="true"></i>
                    <i class="fa fa-star-o" aria-hidden="true"></i>
                    <i class="fa fa-star-o" aria-hidden="true"></i>
                    <i class="fa fa-star-o" aria-hidden="true"></i>
                    <span class="review_txt"> 3 Review</span>
                </span>
                <p class="m-0">Sector 21, Chandigarh</p>
            </li>
        </ul>
        <hr class="mb-2">
        <h5 class="modal-title mt-0 mb-2">Your Order</h5>
        <h6 class="product-title mt-0">T-Shirt</h6>
        <p class="d-flex align-items-center justify-content-between"><label>1 X ₹89</label><span>₹89</span></p>
        <div class="table-responsive">
            <table class="table detail-table mb-0">
                <thead>
                    <tr>
                        <th>Item Total</th>
                        <th class="text-right">₹89</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Promo - (ZOMATO20)</td>
                        <td class="text-right">you save ₹15.84</td>
                    </tr>
                    <tr>
                        <td>Restaurant Promo</td>
                        <td class="text-right">you save ₹9.79</td>
                    </tr>
                    <tr>
                        <td>Delivery Charge</td>
                        <td class="text-right">₹10.00</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <p class="saving-txt d-flex align-items-center justify-content-between m-0"><span>Your total savings</span><span>₹25.63</span></p>  
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td>Grand Total</td>
                        <td class="text-right">₹73.37</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <h5 class="modal-title mt-3 mb-2">Order Details</h5>
        <div class="order_details p-0">
            <div class="order-no mb-2">
                <p class="order_head">Order Id</p>
                <p class="order_sub_title">1312370737</p>
            </div> 
            <div class="total-amt mb-2">
                <p class="order_head">PAYMENT</p>
                <p class="order_sub_title">Paid: Using Card</p>
            </div> 
            <div class="total-items mb-2">
                <p class="order_head">DATE</p>
                <p class="order_sub_title">March 29, 2019 at 09:01 PM</p>
            </div> 
            <div class="ordered-on mb-2">
                <p class="order_head">PHONE NUMBER</p>
                <p class="order_sub_title">854622123</p>
            </div> 
            <div class="ordered-on mb-2">
                <p class="order_head">DELIVER TO</p>
                <p class="order_sub_title">#1541, phase-5, mohali, Phase 5, Mohali</p>
            </div> 
        </div>
      </div>
      <div class="modal-footer">
       
      </div>
    </div>
  </div>
</div>

@endsection

@section('script')

<script type="text/javascript">
    var ajaxCall = 'ToCancelPrevReq';
    $('.verifyEmail').click(function() {
        verifyUser('email');
    });

    $('.verifyPhone').click(function() {
        verifyUser('phone');
    });

    function verifyUser($type = 'email') {
        ajaxCall = $.ajax({
            type: "post",
            dataType: "json",
            url: "{{ route('verifyInformation', Auth::user()->id) }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "type": $type,
            },
            beforeSend: function() {
                if (ajaxCall != 'ToCancelPrevReq' && ajaxCall.readyState < 4) {
                    ajaxCall.abort();
                }
            },
            success: function(response) {
                var res = response.result;

            },
            error: function(data) {

            },
        });
    }
</script>

@endsection