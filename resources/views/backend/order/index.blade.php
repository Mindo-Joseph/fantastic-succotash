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
</style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                        </ol>
                    </div>
                    <h4 class="page-title">Orders</h4>
                </div>
            </div>
        </div>     
        <div class="row">    
            @foreach($orders as $order)
                <div class="col-xl-6">
                    <div class="row no-gutters order_head">
                        <div class="col-md-3"><h4>Order Id</h4></div>
                        <div class="col-md-3"><h4>Date & Time</h4></div>
                        <div class="col-md-3"><h4>Customer Name</h4></div>
                        <div class="col-md-3"><h4>Address</h4></div>
                    </div>
                    <div class="row no-gutters order_data mb-lg-3">
                        <div class="col-md-3">#{{$order->order_number}}</div>
                        <div class="col-md-3">{{ convertDateTimeInTimeZone($order->created_at, $timezone, 'l, F d, Y, H:i A')}}</div>
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
                                <div class="row  {{$k ==0 ? 'mt-3' : ''}}">
                                    <div class="col-12">
                                        <a href="{{route('order.show.detail', [$order->id, $vendor->vendor_id])}}" class="row order_detail order_detail_data align-items-top pb-3 card-box no-gutters">
                                            <span class="left_arrow pulse"></span>
                                            <div class="col-5 col-sm-3">
                                                <h4 class="m-0">{{ $vendor->name }}</h4>
                                                <ul class="status_box mt-3 pl-0">
                                                    <li><img src="{{ asset('assets/images/order-icon.svg') }}" alt=""><label class="m-0 in-progress">Accepted</label></li>
                                                    <li><img src="{{ asset('assets/images/driver_icon.svg') }}" alt=""><label class="m-0 in-progress">Assigned</label></li>
                                                </ul>
                                            </div>
                                            <div class="col-7 col-sm-4">
                                                <ul class="product_list d-flex align-items-center p-0 flex-wrap m-0">
                                                    {{$vendor->products->count()}}
                                                    @foreach($vendor->products as $product)
                                                        <li class="text-center">
                                                            <img src="{{ $product->image['proxy_url'].'74/100'.$product->image['image_path']}}" alt="">
                                                            <span class="item_no position-absolute">x{{$product->quantity}}</span>
                                                            <label class="items_price">$@money($product->price)</label>
                                                        </li>
                                                        @php
                                                            $product_total_count += $product->quantity * $product->price;
                                                            $product_taxable_amount += $product->taxable_amount;
                                                            $total_tax_order_price += $product->taxable_amount;
                                                            $total_order_price += ($product->quantity * $product->price);
                                                        @endphp
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
                                                        <label class="m-0">Discount Amount</label>
                                                        <span>{{$vendor->discount_amount}}</span>
                                                    </li>
                                                    <li class="d-flex align-items-center justify-content-between">
                                                        <label class="m-0">Delivery Fee</label>
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
                        <div class="col-md-3">
                            <div class="card-box p-2">
                                <ul class="price_box_bottom m-0 pl-0 pt-1">
                                    <li class="d-flex align-items-center justify-content-between">
                                        <label class="m-0">Sub Total</label>
                                        <span>$@money($total_order_price)</span>
                                    </li>
                                   <!--  <li class="d-flex align-items-center justify-content-between">
                                        <label class="m-0">Wallet</label>
                                        <span>$0.00</span>
                                    </li> -->
                                    <li class="d-flex align-items-center justify-content-between">
                                        <label class="m-0">Loyalty</label>
                                        <span>{{$order->loyalty_points_earned}}</span>
                                    </li>
                                    <li class="d-flex align-items-center justify-content-between">
                                        <label class="m-0">Tax</label>
                                        <span>$@money($order->taxable_amount)</span>
                                    </li>
                                    <li class="grand_total d-flex align-items-center justify-content-between">
                                        <label class="m-0">Total Payable </label>
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
@endsection
