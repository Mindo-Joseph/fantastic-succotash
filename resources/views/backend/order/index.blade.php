@extends('layouts.vertical', ['title' => 'Orders'])
@section('content') 
@php
$timezone = Auth::user()->timezone;
@endphp   
<style type="text/css">
    .ellipsis{
        white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    body{
        font-size: 0.75rem;
    }
    .order_data > div,.order_head h4 {
        padding: 0 !important;
    }
    .order-page .card-box {
        padding: 20px 20px 5px !important;
    }
</style>
    <div class="container-fluid order-page">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="page-title">Orders</h4>
                    <a class="return-btn" href="#"><b>Return Request <span>(5)</span> <i class="fa fa-arrow-circle-right ml-1" aria-hidden="true"></i></b></a>
                </div>
            </div>
        </div>    
        
        <!-- Order Tabbar COntent -->
        <div class="row">
            <div class="col-sm-12 col-lg-12 tab-product pt-0">
                <ul class="nav nav-tabs nav-material" id="top-tab" role="tablist">
                    <li class="nav-item"><a class="nav-link active" id="active-orders-tab" data-toggle="tab"
                            href="#active-orders" role="tab" aria-selected="true"><i
                                class="icofont icofont-ui-home"></i>Active Orders</a>
                        <div class="material-border"></div>
                    </li>
                    <li class="nav-item"><a class="nav-link" id="pending_order-tab" data-toggle="tab"
                            href="#pending_order" role="tab" aria-selected="false"><i
                                class="icofont icofont-man-in-glasses"></i>Pending Orders</a>
                        <div class="material-border"></div>
                    </li>
                    <li class="nav-item"><a class="nav-link" id="order_history-tab" data-toggle="tab"
                            href="#order_history" role="tab" aria-selected="false"><i
                                class="icofont icofont-man-in-glasses"></i>Orders History</a>
                        <div class="material-border"></div>
                    </li>
                </ul>
                <div class="tab-content nav-material" id="top-tabContent">
                    <div class="tab-pane fade show active" id="active-orders" role="tabpanel"
                        aria-labelledby="active-orders-tab">
                        <div class="row">    
                            @foreach($orders as $order)
                                <div class="col-xl-6 mb-3">
                                    <div class="row no-gutters order_head">
                                        <div class="col-md-3"><h4>Order Id</h4></div>
                                        <div class="col-md-3"><h4>Date & Time</h4></div>
                                        <div class="col-md-3"><h4>Customer</h4></div>
                                        <div class="col-md-3"><h4>Address</h4></div>
                                    </div>
                                    <div class="row no-gutters order_data mb-lg-0">
                                        <div class="col-md-3">#{{$order->order_number}}</div>
                                        <div class="col-md-3">{{ convertDateTimeInTimeZone($order->created_at, $timezone, 'd-m-Y, H:i A')}}</div>
                                        <div class="col-md-3">
                                        <a href="#">{{$order->user->name}}</a>
                                        </div>
                                        <div class="col-md-3">
                                            <p class="ellipsis" data-toggle="tooltip" data-placement="top" title="{{$order->address ? $order->address['address'] : ''}}">
                                                {{$order->address ? $order->address['address'] : ''}}
                                            </p>
                                        </div>                    
                                    </div>
                                    <div class="row">
                                        <div class="col-md-9">
                                            @php
                                                $total_order_price = 0;
                                                $total_tax_order_price = 0;
                                            @endphp
                                            @foreach($order->vendors as $k => $vendor)
                                            @php
                                                $product_total_count = 0;
                                                $product_taxable_amount = 0;
                                            @endphp
                                                <div class="row  {{$k ==0 ? 'mt-0' : 'mt-3'}}">
                                                    <div class="col-12">
                                                        <a href="{{route('order.show.detail', [$order->id, $vendor->vendor_id])}}" class="row order_detail order_detail_data align-items-top pb-3 card-box no-gutters h-100">
                                                            <span class="left_arrow pulse">
                                                            </span>
                                                            <div class="col-5 col-sm-3">
                                                                <h4 class="m-0">{{ $vendor->name }}</h4>
                                                                <ul class="status_box mt-3 pl-0">
                                                                    <li>
                                                                        <img src="{{ asset('assets/images/order-icon.svg') }}" alt="">
                                                                        <label class="m-0 in-progress">{{$vendor->order_status}}</label>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="col-7 col-sm-4">
                                                                <ul class="product_list d-flex align-items-center p-0 flex-wrap m-0">
                                                                    @foreach($vendor->products as $product)
                                                                        @if($vendor->vendor_id == $product->vendor_id)
                                                                            <li class="text-center">
                                                                                <img src="{{ $product->image_path['proxy_url'].'74/100'.$product->image_path['image_path']}}">
                                                                                <span class="item_no position-absolute">x{{$product->quantity}}</span>
                                                                                <label class="items_price">$@money($product->price)</label>
                                                                            </li>
                                                                            @php
                                                                                $product_total_count += $product->quantity * $product->price;
                                                                                $product_taxable_amount += $product->taxable_amount;
                                                                                $total_tax_order_price += $product->taxable_amount;
                                                                                $total_order_price += ($product->quantity * $product->price);
                                                                            @endphp
                                                                        @endif
                                                                    @endforeach                                    
                                                                </ul>
                                                            </div>
                                                            <div class="col-md-5 mt-md-0 mt-sm-2">
                                                                <ul class="price_box_bottom m-0 p-0">
                                                                    <li class="d-flex align-items-center justify-content-between">
                                                                        <label class="m-0">Total</label>
                                                                        <span>$@money($product_total_count)</span>
                                                                    </li>
                                                                    <li class="d-flex align-items-center justify-content-between">
                                                                        <label class="m-0">Promocode</label>
                                                                        <span>{{$vendor->discount_amount}}</span>
                                                                    </li>
                                                                    <li class="d-flex align-items-center justify-content-between">
                                                                        <label class="m-0">Delivery</label>
                                                                        <span>--</span>
                                                                    </li>
                                                                    <li class="grand_total d-flex align-items-center justify-content-between">
                                                                        <label class="m-0">Amount</label>
                                                                        <span>$@money($product_total_count + $product_taxable_amount)</span>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>   
                                        <div class="col-md-3 pl-0">
                                            <div class="card-box p-2">
                                                <ul class="price_box_bottom m-0 pl-0 pt-1">
                                                    <li class="d-flex align-items-center justify-content-between">
                                                        <label class="m-0">Total</label>
                                                        <span>$@money($total_order_price)</span>
                                                    </li>
                                                    <li class="d-flex align-items-center justify-content-between">
                                                        <label class="m-0">Loyalty</label>
                                                        <span>{{$order->loyalty_points_earned}}</span>
                                                    </li>
                                                    <li class="d-flex align-items-center justify-content-between">
                                                        <label class="m-0">Tax</label>
                                                        <span>$@money($order->taxable_amount)</span>
                                                    </li>
                                                    <li class="grand_total d-flex align-items-center justify-content-between">
                                                        <label class="m-0">Payable </label>
                                                        <span>$@money($order->payable_amount)</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                            @endforeach
                            <hr>
                        </div>
                    </div>
                    <div class="tab-pane fade past-order" id="pending_order" role="tabpanel"
                        aria-labelledby="pending_order-tab">
                    </div>
                    <div class="tab-pane fade past-order" id="order_history" role="tabpanel"
                        aria-labelledby="order_history-tab">
                        order_history
                    </div>
                </div>
            </div>
        </div>   
        
        
        <!-- Return Page Tabbar start from here -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="page-title">Return Requests</h4>                    
                </div>
            </div>
        </div>   
        <div class="row mb-lg-5">
            <div class="col-sm-12 col-lg-12 tab-product pt-0">
                <ul class="nav nav-tabs nav-material" id="top-tab" role="tablist">
                    <li class="nav-item"><a class="nav-link active" id="awaiting_review" data-toggle="tab"
                            href="#awaiting-review" role="tab" aria-selected="true"><i
                                class="icofont icofont-ui-home"></i>Active Orders</a>
                        <div class="material-border"></div>
                    </li>
                    <li class="nav-item"><a class="nav-link" id="processed-tab" data-toggle="tab"
                            href="#processed" role="tab" aria-selected="false"><i
                                class="icofont icofont-man-in-glasses"></i>Process Order</a>
                        <div class="material-border"></div>
                    </li>
                    <li class="nav-item"><a class="nav-link" id="rejected-tab" data-toggle="tab"
                            href="#rejected" role="tab" aria-selected="false"><i
                                class="icofont icofont-man-in-glasses"></i>Rejected Order</a>
                        <div class="material-border"></div>
                    </li>
                </ul>
                <div class="tab-content nav-material" id="top-tabContent">
                    <div class="tab-pane fade show active" id="awaiting-review" role="tabpanel"
                        aria-labelledby="awaiting_review">
                        <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="w-100 common-table">
                                    <thead>
                                        <tr>
                                            <th>Order id</th>
                                            <th>Customer Name</th>
                                            <th>Product Name</th>
                                            <th>Product Price</th>
                                            <th>Date & Time</th>
                                            <th>Request Date & Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                #71326615
                                            </td>
                                            <td>
                                                <a href="#">Rahul</a>
                                            </td>
                                            <td class="product-name">
                                                <h2 class="m-0">
                                                    Pizza
                                                </h2>
                                            </td>
                                            <td class="">
                                                <b class="text-black">$315.00</b>
                                            </td>
                                            <td>11-06-2021, 13:38 PM</td>
                                            <td>11-06-2021, 13:38 PM</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                #71326615
                                            </td>
                                            <td>
                                                <a href="#">Rahul</a>
                                            </td>
                                            <td class="product-name">
                                                <h2 class="m-0">
                                                    Pizza
                                                </h2>
                                            </td>
                                            <td class="">
                                                <b class="text-black">$315.00</b>
                                            </td>
                                            <td>11-06-2021, 13:38 PM</td>
                                            <td>11-06-2021, 13:38 PM</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                #71326615
                                            </td>
                                            <td>
                                                <a href="#">Rahul</a>
                                            </td>
                                            <td class="product-name">
                                                <h2 class="m-0">
                                                    Pizza
                                                </h2>
                                            </td>
                                            <td class="">
                                                <b class="text-black">$315.00</b>
                                            </td>
                                            <td>11-06-2021, 13:38 PM</td>
                                            <td>11-06-2021, 13:38 PM</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="processed" role="tabpanel"
                        aria-labelledby="processed-tab">
                        processed
                    </div>
                    <div class="tab-pane fade" id="rejected" role="tabpanel"
                        aria-labelledby="rejected-tab">
                        rejected
                    </div>
                </div>
            </div>
        </div>   
       
    </div> 
@endsection
