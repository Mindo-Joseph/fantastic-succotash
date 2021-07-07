@extends('layouts.store', ['title' => 'My Orders'])

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
@php
$timezone = Auth::user()->timezone;
@endphp
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

<section class="section-b-space order-page">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="account-sidebar"><a class="popup-btn">My account</a></div>
                <div class="dashboard-left">
                    <div class="collection-mobile-back"><span class="filter-back"><i class="fa fa-angle-left" aria-hidden="true"></i> back</span></div>
                    @include('layouts.store/profile-sidebar')
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
                        <div class="row" id="orders_wrapper">
                            <div class="col-sm-12 col-lg-12 tab-product pt-3">
                                <ul class="nav nav-tabs nav-material" id="top-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link {{ ((Request::query('pageType') === null) || (Request::query('pageType') == 'activeOrders')) ? 'active show' : '' }}" id="active-orders-tab" data-toggle="tab" href="#active-orders" role="tab" aria-selected="true"><i class="icofont icofont-ui-home"></i>Active Orders</a>
                                        <div class="material-border"></div>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ (Request::query('pageType') == 'pastOrders') ? 'active show' : '' }}" id="past_order-tab" data-toggle="tab" href="#past_order" role="tab" aria-selected="false"><i class="icofont icofont-man-in-glasses"></i>Past Orders</a>
                                        <div class="material-border"></div>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ (Request::query('pageType') == 'returnOrders') ? 'active show' : '' }}" id="return_order-tab" data-toggle="tab" href="#return_order" role="tab" aria-selected="false"><i class="icofont icofont-man-in-glasses"></i>Return Orders</a>
                                        <div class="material-border"></div>
                                    </li>
                                </ul>
                                <div class="tab-content nav-material" id="top-tabContent">
                                    <div class="tab-pane fade {{ ((Request::query('pageType') === null) || (Request::query('pageType') == 'activeOrders')) ? 'active show' : '' }}" id="active-orders" role="tabpanel"
                                        aria-labelledby="active-orders-tab">
                                        <div class="row">
                                            @if($activeOrders->isNotEmpty())
                                            @foreach($activeOrders as $key => $order)
                                            <div class="col-12">
                                                <div class="row no-gutters order_head">
                                                    <div class="col-md-3"><h4>Order Number</h4></div>
                                                    <div class="col-md-3"><h4>Date & Time</h4></div>
                                                    <div class="col-md-3"><h4>Customer Name</h4></div>
                                                    <div class="col-md-3"><h4>Address</h4></div>
                                                </div>
                                                <div class="row no-gutters order_data">
                                                    <div class="col-md-3">#{{$order->order_number}}</div>
                                                    <div class="col-md-3">{{convertDateTimeInTimeZone($order->created_at, $timezone, 'l, F d, Y, H:i A')}}</div>
                                                    <div class="col-md-3">
                                                        <a href="#">{{$order->user->name}}</a>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <span class="ellipsis" data-toggle="tooltip" data-placement="top" title="">
                                                            @if($order->address)
                                                                {{$order->address->address}}, {{$order->address->street}}, {{$order->address->city}}, {{$order->address->state}}, {{$order->address->country}} {{$order->address->pincode}}
                                                            @else
                                                                NA
                                                            @endif
                                                        </span>
                                                    </div>                    
                                                </div>
                                                <div class="row mt-2"> 
                                                    <div class="col-md-9 mb-3">
                                                        @php
                                                            $subtotal_order_price = $total_order_price = $total_tax_order_price = 0;
                                                        @endphp
                                                        @foreach($order->vendors as $key => $vendor)
                                                            @php
                                                                $product_total_count = $product_subtotal_amount = $product_taxable_amount = 0;
                                                            @endphp
                                                            @if($vendor->order_status != 'delivered')
                                                            <div class="order_detail order_detail_data align-items-top pb-3 card-box no-gutters mb-0">
                                                                <span class="left_arrow pulse"></span>
                                                                <div class="row">
                                                                    <div class="col-5 col-sm-3">
                                                                        <h5 class="m-0">Order Status</h5>
                                                                        <ul class="status_box mt-3 pl-0">
                                                                        @if(!empty($vendor->order_status))
                                                                            <li>
                                                                                @if($vendor->order_status == 'placed')
                                                                                    <img src="{{ asset('assets/images/order-icon.svg') }}" alt="">
                                                                                @elseif($vendor->order_status == 'accepted')
                                                                                    <li><img src="{{ asset('assets/images/payment_icon.svg') }}" alt="">
                                                                                @elseif($vendor->order_status == 'processing')
                                                                                    <img src="{{ asset('assets/images/customize_icon.svg') }}" alt="">
                                                                                @elseif($vendor->order_status == 'out for delivery')
                                                                                    <img src="{{ asset('assets/images/driver_icon.svg') }}" alt="">
                                                                                @endif
                                                                                <label class="m-0 in-progress">{{ ucfirst($vendor->order_status) }}</label>
                                                                            </li>
                                                                        @endif
                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-7 col-sm-4">
                                                                        <ul class="product_list d-flex align-items-center p-0 flex-wrap m-0">
                                                                            @foreach($vendor->products as $product)
                                                                                @if($vendor->vendor_id == $product->vendor_id)
                                                                                    <li class="text-center">
                                                                                        <img src="{{ $product->image['proxy_url'].'74/100'.$product->image['image_path'] }}" alt="">
                                                                                        <span class="item_no position-absolute">x{{$product->quantity}}</span>
                                                                                        <?php /* ?><label class="items_name">{{$product->product_name}}</label><?php */ ?>
                                                                                        <label class="items_price">${{$product->price}}</label>
                                                                                    </li>
                                                                                    @php
                                                                                        $product_total_count += $product->quantity * $product->price;
                                                                                        $product_taxable_amount += $product->taxable_amount;
                                                                                        $total_tax_order_price += $product->taxable_amount;
                                                                                    @endphp
                                                                                @endif
                                                                            @endforeach
                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-md-5 mt-md-0 mt-sm-2">
                                                                        <ul class="price_box_bottom m-0 p-0">
                                                                            <li class="d-flex align-items-center justify-content-between">
                                                                                <label class="m-0">Product Total</label>
                                                                                <span>$@money($product_total_count)</span>
                                                                            </li>
                                                                            <li class="d-flex align-items-center justify-content-between">
                                                                                <label class="m-0">Coupon Discount</label>
                                                                                <span>$@money($vendor->discount_amount)</span>
                                                                            </li>
                                                                            <li class="d-flex align-items-center justify-content-between">
                                                                                <label class="m-0">Delivery Fee</label>
                                                                                <span>$@money($vendor->delivery_fee)</span>
                                                                            </li>
                                                                            <li class="grand_total d-flex align-items-center justify-content-between">
                                                                                <label class="m-0">Amount</label>
                                                                                @php
                                                                                    $product_subtotal_amount = $product_total_count - $vendor->discount_amount + $vendor->delivery_fee;
                                                                                    $subtotal_order_price += $product_subtotal_amount;
                                                                                @endphp
                                                                                <span>$@money($product_subtotal_amount)</span>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                    <div class="col-md-3 mb-3 pl-lg-0">
                                                        <div class="card-box p-2 mb-0 h-100">
                                                            <ul class="price_box_bottom m-0 pl-0 pt-1">
                                                                <li class="d-flex align-items-center justify-content-between">
                                                                    <label class="m-0">Sub Total</label>
                                                                    <span>$@money($subtotal_order_price)</span>
                                                                </li>
                                                                <li class="d-flex align-items-center justify-content-between">
                                                                    <label class="m-0">Wallet</label>
                                                                    <span>--</span>
                                                                </li>
                                                                <li class="d-flex align-items-center justify-content-between">
                                                                    <label class="m-0">Loyalty</label>
                                                                    <span>{{$order->loyality_points_used ? $order->loyality_points_used : 0}}</span>
                                                                </li>
                                                                <li class="d-flex align-items-center justify-content-between">
                                                                    <label class="m-0">Tax</label>
                                                                    <span>$@money($total_tax_order_price)</span>
                                                                </li>
                                                                <li class="grand_total d-flex align-items-center justify-content-between">
                                                                    <label class="m-0">Total Payable</label>
                                                                    <span>$@money($total_order_price + $subtotal_order_price + $total_tax_order_price)</span>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                            @else
                                                <div class="col-12">
                                                    <div class="no-gutters order_head">
                                                        <h4 class="text-center">No Active Order Found</h4>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        {{ $activeOrders->appends(['pageType' => 'activeOrders'])->links() }}
                                    </div>
                                    <div class="tab-pane fade past-order {{ (Request::query('pageType') == 'pastOrders') ? 'active show' : '' }}" id="past_order" role="tabpanel"
                                        aria-labelledby="past_order-tab">
                                        <div class="row">
                                            @if($pastOrders->isNotEmpty())
                                            @foreach($pastOrders as $key => $order)
                                            <div class="col-12">
                                                <div class="row no-gutters order_head">
                                                    <div class="col-md-3"><h4>Order Number</h4></div>
                                                    <div class="col-md-3"><h4>Date & Time</h4></div>
                                                    <div class="col-md-3"><h4>Customer Name</h4></div>
                                                    <div class="col-md-3"><h4>Address</h4></div>
                                                </div>
                                                <div class="row no-gutters order_data">
                                                    <div class="col-md-3">#{{$order->order_number}}</div>
                                                    <div class="col-md-3">{{$order->created_at->format('D M d, Y h:m A')}}</div>
                                                    <div class="col-md-3">
                                                        <a href="#">{{$order->user->name}}</a>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <span class="ellipsis" data-toggle="tooltip" data-placement="top" title="">
                                                            @if($order->address)
                                                                {{$order->address->address}}, {{$order->address->street}}, {{$order->address->city}}, {{$order->address->state}}, {{$order->address->country}} {{$order->address->pincode}}
                                                            @else
                                                                NA
                                                            @endif
                                                        </span>
                                                    </div>                    
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-md-9 mb-3">
                                                        @php
                                                            $subtotal_order_price = $total_order_price = $total_tax_order_price = 0;
                                                        @endphp
                                                        @foreach($order->vendors as $key => $vendor)
                                                            @php
                                                                $product_total_count = $product_subtotal_amount = $product_taxable_amount = 0;
                                                            @endphp
                                                            @if($vendor->order_status == 'delivered')
                                                            <div class="order_detail order_detail_data align-items-top pb-3 card-box no-gutters mb-0">
                                                                <span class="left_arrow pulse"></span>
                                                                <div class="row">
                                                                    <div class="col-5 col-sm-3">
                                                                        <h5 class="m-0">Order Status</h5>
                                                                        <ul class="status_box mt-3 pl-0">
                                                                        @if(!empty($vendor->order_status))
                                                                            <li>
                                                                                <img src="{{ asset('assets/images/driver_icon.svg') }}" alt="">
                                                                                <label class="m-0 in-progress">{{ ucfirst($vendor->order_status) }}</label>
                                                                            </li>
                                                                        @endif
                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-7 col-sm-4">
                                                                        <ul class="product_list d-flex align-items-center p-0 flex-wrap m-0">
                                                                            @foreach($vendor->products as $product)
                                                                                @if($vendor->vendor_id == $product->vendor_id)
                                                                                @php
                                                                                $pro_rating = $product->productRating->rating??0;
                                                                            @endphp
                                                                            <li class="text-center">
                                                                                <img src="{{ $product->image['proxy_url'].'74/100'.$product->image['image_path'] }}" alt="">
                                                                                <span class="item_no position-absolute">x{{$product->quantity}}</span>
                                                                                <label class="items_price">${{$product->price}}</label>
                                                                                <label class="rating-star add_edit_review" data-id="{{$product->productRating->id??0}}"  data-order_vendor_product_id="{{$product->id??0}}">
                                                                                    <i class="fa fa-star{{ $pro_rating >= 1 ? '' : '-o' }}" ></i>
                                                                                    <i class="fa fa-star{{ $pro_rating >= 2 ? '' : '-o' }}" ></i>
                                                                                    <i class="fa fa-star{{ $pro_rating >= 3 ? '' : '-o' }}" ></i>
                                                                                    <i class="fa fa-star{{ $pro_rating >= 4 ? '' : '-o' }}" ></i>
                                                                                    <i class="fa fa-star{{ $pro_rating >= 5 ? '' : '-o' }}" ></i>
                                                                                </label>
                                                                              
                                                                                    @php
                                                                                        $product_total_count += $product->quantity * $product->price;
                                                                                        $product_taxable_amount += $product->taxable_amount;
                                                                                        $total_tax_order_price += $product->taxable_amount;
                                                                                    @endphp
                                                                                @endif
                                                                            @endforeach
                                                                                
                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-md-5 mt-md-0 mt-sm-2">
                                                                        <ul class="price_box_bottom m-0 p-0">
                                                                            <li class="d-flex align-items-center justify-content-between">
                                                                                <label class="m-0">Product Total</label>
                                                                                <span>$@money($product_total_count)</span>
                                                                            </li>
                                                                            <li class="d-flex align-items-center justify-content-between">
                                                                                <label class="m-0">Coupon Discount</label>
                                                                                <span>$@money($vendor->discount_amount)</span>
                                                                            </li>
                                                                            <li class="d-flex align-items-center justify-content-between">
                                                                                <label class="m-0">Delivery Fee</label>
                                                                                <span>$@money($vendor->delivery_fee)</span>
                                                                            </li>
                                                                            <li class="grand_total d-flex align-items-center justify-content-between">
                                                                                <label class="m-0">Amount</label>
                                                                                @php
                                                                                    $product_subtotal_amount = $product_total_count - $vendor->discount_amount + $vendor->delivery_fee;
                                                                                    $subtotal_order_price += $product_subtotal_amount;
                                                                                @endphp
                                                                                <span>$@money($product_subtotal_amount)</span>
                                                                            </li>
                                                                            <button class="return-order-product btn btn-solid" data-id="{{$order->id??0}}"  data-vendor_id="{{$vendor->vendor_id??0}}"><td class="text-center" colspan="3">Return</button>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                    <div class="col-md-3 mb-3 pl-lg-0">
                                                        <div class="card-box p-2 mb-0 h-100">
                                                            <ul class="price_box_bottom m-0 pl-0 pt-1">
                                                                <li class="d-flex align-items-center justify-content-between">
                                                                    <label class="m-0">Sub Total</label>
                                                                    <span>$@money($subtotal_order_price)</span>
                                                                </li>
                                                                <li class="d-flex align-items-center justify-content-between">
                                                                    <label class="m-0">Wallet</label>
                                                                    <span>--</span>
                                                                </li>
                                                                <li class="d-flex align-items-center justify-content-between">
                                                                    <label class="m-0">Loyalty</label>
                                                                    <span>{{$order->loyality_points_used ? $order->loyality_points_used : 0}}</span>
                                                                </li>
                                                                <li class="d-flex align-items-center justify-content-between">
                                                                    <label class="m-0">Tax</label>
                                                                    <span>$@money($total_tax_order_price)</span>
                                                                </li>
                                                                <li class="grand_total d-flex align-items-center justify-content-between">
                                                                    <label class="m-0">Total Payable</label>
                                                                    <span>$@money($total_order_price + $subtotal_order_price + $total_tax_order_price)</span>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                            @else
                                                <div class="col-12">
                                                    <div class="no-gutters order_head">
                                                        <h4 class="text-center">No Past Order Found</h4>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                        </div>
                                        {{ $pastOrders->appends(['pageType' => 'pastOrders'])->links() }}
                                    </div>

                                    <div class="tab-pane fade return-order {{ (Request::query('pageType') == 'returnOrders') ? 'active show' : '' }}" id="return_order" role="tabpanel"
                                    aria-labelledby="return_order-tab">
                                    <div class="row">
                                        @if($returnOrders->isNotEmpty())
                                        @foreach($returnOrders as $key => $order)
                                        @if($order->orderStatusVendor->isNotEmpty())
                                        <div class="col-12">
                                            <div class="row no-gutters order_head">
                                                <div class="col-md-3"><h4>Order Number</h4></div>
                                                <div class="col-md-3"><h4>Date & Time</h4></div>
                                                <div class="col-md-3"><h4>Customer Name</h4></div>
                                                <div class="col-md-3"><h4>Address</h4></div>
                                            </div>
                                            <div class="row no-gutters order_data">
                                                <div class="col-md-3">#{{$order->order_number}}</div>
                                                <div class="col-md-3">{{$order->created_at->format('D M d, Y h:m A')}}</div>
                                                <div class="col-md-3">
                                                    <a href="#">{{$order->user->name}}</a>
                                                </div>
                                                <div class="col-md-3">
                                                    <span class="ellipsis" data-toggle="tooltip" data-placement="top" title="">
                                                        @if($order->address)
                                                            {{$order->address->address}}, {{$order->address->street}}, {{$order->address->city}}, {{$order->address->state}}, {{$order->address->country}} {{$order->address->pincode}}
                                                        @else
                                                            NA
                                                        @endif
                                                    </span>
                                                </div>                    
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-9 mb-3">
                                                    @php
                                                        $subtotal_order_price = $total_order_price = $total_tax_order_price = 0;
                                                    @endphp
                                                    @foreach($order->vendors as $key => $vendor)
                                                        @php
                                                            $product_total_count = $product_subtotal_amount = $product_taxable_amount = 0;
                                                        @endphp
                                                       
                                                                <div class="order_detail order_detail_data align-items-top pb-3 card-box no-gutters mb-0">
                                                                    <span class="left_arrow pulse"></span>
                                                                    <div class="row">
                                                                        <div class="col-5 col-sm-3">
                                                                            <h5 class="m-0"></h5>
                                                                           
                                                                        </div>
                                                                        <div class="col-7 col-sm-4">
                                                                            <ul class="product_list d-flex align-items-center p-0 flex-wrap m-0">
                                                                                @foreach($vendor->products as $product)
                                                                                    @if($vendor->vendor_id == $product->vendor_id)
                                                                                        @php
                                                                                            $pro_rating = $product->productRating->rating??0;
                                                                                        @endphp
                                                                                        <li class="text-center">
                                                                                            <img src="{{ $product->image['proxy_url'].'74/100'.$product->image['image_path'] }}" alt="">
                                                                                            <span class="item_no position-absolute">x{{$product->quantity}}</span>
                                                                                            <?php /* ?><label class="items_name">{{$product->product_name}}</label><?php */ ?>
                                                                                            <label class="items_price">${{$product->price}}</label>
                                                                                            <label class="rating-star add_edit_review" data-id="{{$product->productRating->id??0}}"  data-order_vendor_product_id="{{$product->id??0}}">
                                                                                                <i class="fa fa-star{{ $pro_rating >= 1 ? '' : '-o' }}" ></i>
                                                                                                <i class="fa fa-star{{ $pro_rating >= 2 ? '' : '-o' }}" ></i>
                                                                                                <i class="fa fa-star{{ $pro_rating >= 3 ? '' : '-o' }}" ></i>
                                                                                                <i class="fa fa-star{{ $pro_rating >= 4 ? '' : '-o' }}" ></i>
                                                                                                <i class="fa fa-star{{ $pro_rating >= 5 ? '' : '-o' }}" ></i>
                                                                                            </label>
                                                                                           {{ __($product->productReturn->status??'') }}
                                                                    
                                                                                        </li>
                                                                                        @php
                                                                                            $product_total_count += $product->quantity * $product->price;
                                                                                            $product_taxable_amount += $product->taxable_amount;
                                                                                            $total_tax_order_price += $product->taxable_amount;
                                                                                        @endphp
                                                                                    @endif
                                                                                @endforeach
                                                                            </ul>
                                                                        </div>
                                                                        <div class="col-md-5 mt-md-0 mt-sm-2">
                                                                            <ul class="price_box_bottom m-0 p-0">
                                                                                <li class="d-flex align-items-center justify-content-between">
                                                                                    <label class="m-0">Product Total</label>
                                                                                    <span>$@money($product_total_count)</span>
                                                                                </li>
                                                                                <li class="d-flex align-items-center justify-content-between">
                                                                                    <label class="m-0">Coupon Discount</label>
                                                                                    <span>$@money($vendor->discount_amount)</span>
                                                                                </li>
                                                                                <li class="d-flex align-items-center justify-content-between">
                                                                                    <label class="m-0">Delivery Fee</label>
                                                                                    <span>$@money($vendor->delivery_fee)</span>
                                                                                </li>
                                                                                <li class="grand_total d-flex align-items-center justify-content-between">
                                                                                    <label class="m-0">Amount</label>
                                                                                    @php
                                                                                        $product_subtotal_amount = $product_total_count - $vendor->discount_amount + $vendor->delivery_fee;
                                                                                        $subtotal_order_price += $product_subtotal_amount;
                                                                                        $total_order_price += $product_subtotal_amount + $total_tax_order_price;
                                                                                    @endphp
                                                                                    <span>$@money($product_subtotal_amount)</span>
                                                                                </li>

                                                                               
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            
                                                    @endforeach
                                                </div>
                                                <div class="col-md-3 mb-3 pl-lg-0">
                                                    <div class="card-box p-2 mb-0 h-100">
                                                        <ul class="price_box_bottom m-0 pl-0 pt-1">
                                                            <li class="d-flex align-items-center justify-content-between">
                                                                <label class="m-0">Sub Total</label>
                                                                <span>$@money($subtotal_order_price)</span>
                                                            </li>
                                                            <li class="d-flex align-items-center justify-content-between">
                                                                <label class="m-0">Wallet</label>
                                                                <span>--</span>
                                                            </li>
                                                            <li class="d-flex align-items-center justify-content-between">
                                                                <label class="m-0">Loyalty</label>
                                                                <span>{{$order->loyality_points_used ? $order->loyality_points_used : 0}}</span>
                                                            </li>
                                                            <li class="d-flex align-items-center justify-content-between">
                                                                <label class="m-0">Tax</label>
                                                                <span>$@money($total_tax_order_price)</span>
                                                            </li>
                                                            <li class="grand_total d-flex align-items-center justify-content-between">
                                                                <label class="m-0">Total Payable</label>
                                                                <span>$@money($total_order_price)</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @endforeach
                                        @else
                                            <div class="col-12">
                                                <div class="no-gutters order_head">
                                                    <h4 class="text-center">No Return Order</h4>
                                                </div>
                                            </div>
                                        @endif
                                        
                                    </div>
                                    {{ $returnOrders->appends(['pageType' => 'returnOrders'])->links() }}
                                </div>
                                </div>
                            </div>  
                        </div>


                        <div class="box-account box-info">
                          
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
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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

<!-- product rating Modal -->
<div class="modal fade product-rating" id="product_rating" tabindex="-1" aria-labelledby="product_ratingLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <div id="review-rating-form-modal">
        </div>
      </div>
     
    </div>
  </div>
</div>

<!-- product return modal -->
<div class="modal fade return-order" id="return_order_model" tabindex="-1" aria-labelledby="return_orderLabel"  aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    </button>
                <div id="return-order-form-modal">

                </div>    
               

            </div>
        </div>
    </div>
</div>
<!-- end product return modal -->

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
//// ************  rating review order  *****************  //
    $('body').on('click', '.add_edit_review', function (event) {
        event.preventDefault();
        var id = $(this).data('id');
        var order_vendor_product_id = $(this).data('order_vendor_product_id');
        $.get('/rating/get-product-rating?id=' + id +'&order_vendor_product_id=' + order_vendor_product_id, function(markup)
        {
            $('#product_rating').modal('show'); 
            $('#review-rating-form-modal').html(markup);
        });
    });
//// ************  return product + Order   *****************  //
$('body').on('click', '.return-order-product', function (event) {
        event.preventDefault();
        var id = $(this).data('id');
        var vendor_id = $(this).data('vendor_id');
        $.get('/return-order/get-order-data-in-model?id=' + id +'&vendor_id=' + vendor_id, function(markup)
                {   
                    $('#return_order_model').modal('show'); 
                    $('#return-order-form-modal').html(markup);
                });
    });        
   
//// ************  end return product + Order   *****************  //
    $(document).delegate("#orders_wrapper .nav-tabs .nav-link", "click", function(){
        let id = $(this).attr('id');
        const params = window.location.search;
        if(params != ''){
            if(id == 'active-orders-tab'){
                window.location.href = window.location.pathname + '?pageType=activeOrders';
            }
            else if(id == 'past_order-tab'){
                window.location.href = window.location.pathname + '?pageType=pastOrders';
            }
        }
    });

 
</script>

@endsection
