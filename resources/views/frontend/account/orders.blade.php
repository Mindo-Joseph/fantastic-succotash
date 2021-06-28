@extends('layouts.store', ['title' => 'Profile'])

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
                            <li><a href="{{route('user.account')}}">My Wallet</a></li>
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
                                @foreach($orders as $key => $order)
                                <div class="col-12">        
                                    <div class="row">
                                        <div class="col-md-9 mb-3">
                                            <div class="order_detail order_detail_data align-items-top pb-3 card-box no-gutters mb-0 h-100">
                                                <div class="row no-gutters order_head">
                                                    <div class="col-md-3"><h4>Order Number</h4></div>
                                                    <div class="col-md-3"><h4>Date & Time</h4></div>
                                                    <div class="col-md-3"><h4>Customer Name</h4></div>
                                                    <div class="col-md-3"><h4>Address</h4></div>
                                                </div>
                                                <div class="row no-gutters order_data mb-4">
                                                    <div class="col-md-3">#{{$order->order_number}}</div>
                                                    <div class="col-md-3">{{$order->created_at->format('D M d, Y h:m A')}}</div>
                                                    <div class="col-md-3">
                                                        <a href="#">{{$order->user->name}}</a>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <p class="ellipsis" data-toggle="tooltip" data-placement="top" title="">
                                                            @if($order->address)
                                                                {{$order->address->address}}, {{$order->address->street}}, {{$order->address->city}}, {{$order->address->state}}, {{$order->address->country}} {{$order->address->pincode}}
                                                            @else
                                                            NA
                                                            @endif
                                                        </p>
                                                    </div>                    
                                                </div>
                                                <span class="left_arrow pulse"></span>
                                                <div class="row">
                                                    <div class="col-5 col-sm-3">
                                                        <h5 class="m-0">Order Status</h5>
                                                        <ul class="status_box mt-3 pl-0">
                                                            @foreach($order->orderStatusVendor as $key => $status)
                                                                @if($status->order_status_option_id == 1)
                                                                    <li><img src="{{ asset('assets/images/order-icon.svg') }}" alt=""><label class="m-0 in-progress">Placed</label></li>
                                                                @endif
                                                                @if($status->order_status_option_id == 2)
                                                                    <li><img src="{{ asset('assets/images/payment_icon.svg') }}" alt=""><label class="m-0 in-progress">Accepted</label></li>
                                                                @endif
                                                                @if($status->order_status_option_id == 3)
                                                                    <li><img src="{{ asset('assets/images/customize_icon.svg') }}" alt=""><label class="m-0 in-progress">Processing</label></li>
                                                                @endif
                                                                @if($status->order_status_option_id == 4)
                                                                    <li><img src="{{ asset('assets/images/driver_icon.svg') }}" alt=""><label class="m-0 in-progress">Out For Delivery</label></li>
                                                                @endif
                                                                @if($status->order_status_option_id == 5)
                                                                    <li><img src="{{ asset('assets/images/driver_icon.svg') }}" alt=""><label class="m-0 in-progress">Delivered</label></li>
                                                                @endif
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                    <div class="col-7 col-sm-4">
                                                        <ul class="product_list d-flex align-items-center p-0 flex-wrap m-0">
                                                            @foreach($order->products as $key => $product)
                                                                <li class="text-center">
                                                                    <img src="{{ asset('assets/images/order-icon.svg') }}" alt="">
                                                                    <span class="item_no position-absolute">x{{$product->quantity}}</span>
                                                                    <label class="items_name">{{$product->product_name}}</label>
                                                                    <label class="items_price">${{$product->price}}</label>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                    <div class="col-md-5 mt-md-0 mt-sm-2">
                                                        <ul class="price_box_bottom m-0 p-0">
                                                            <li class="d-flex align-items-center justify-content-between">
                                                                <label class="m-0">Product Total</label>
                                                                <span>${{number_format($order->total_amount, 2)}}</span>
                                                            </li>
                                                            <li class="d-flex align-items-center justify-content-between">
                                                                <label class="m-0">Coupon (10%)</label>
                                                                <span>${{$order->total_discount ? number_format($order->total_discount, 2) : 0.00}}</span>
                                                            </li>
                                                            <li class="d-flex align-items-center justify-content-between">
                                                                <label class="m-0">Delivery Fee</label>
                                                                <span>${{$order->total_delivery_fee ? number_format($order->total_delivery_fee, 2) : 0.00}}</span>
                                                            </li>
                                                            <li class="grand_total d-flex align-items-center justify-content-between">
                                                                <label class="m-0">Amount</label>
                                                                <span>${{number_format($order->total_amount, 2)}}</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>    
                                        <div class="col-md-3 mb-3">
                                            <div class="card-box p-2 mb-0 h-100">
                                                <ul class="price_box_bottom m-0 pl-0 pt-1">
                                                    <li class="d-flex align-items-center justify-content-between">
                                                        <label class="m-0">Sub Total</label>
                                                        <span>${{number_format($order->total_amount, 2)}}</span>
                                                    </li>
                                                    <li class="d-flex align-items-center justify-content-between">
                                                        <label class="m-0">Wallet</label>
                                                        <span>$0.00</span>
                                                    </li>
                                                    <li class="d-flex align-items-center justify-content-between">
                                                        <label class="m-0">Loyalty</label>
                                                        <span>${{$order->loyality_points_used ? $order->loyality_points_used : 0}}</span>
                                                    </li>
                                                    <li class="d-flex align-items-center justify-content-between">
                                                        <label class="m-0">Tax</label>
                                                        <span>${{number_format($order->taxable_amount, 2)}}</span>
                                                    </li>
                                                    <li class="grand_total d-flex align-items-center justify-content-between">
                                                        <label class="m-0">Total Payable</label>
                                                        <span>${{number_format($order->payable_amount, 2)}}</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <?php /* ?><div class="row">
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
                                    </div><?php /*/ ?>
                                </div>
                                @endforeach
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