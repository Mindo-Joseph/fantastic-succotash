@extends('layouts.store', ['title' => 'Checkout'])
@section('content')
<header>
    <div class="mobile-fix-option"></div>
    @include('layouts.store.left-sidebar')
</header>
<section class="section-b-space light-layout">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="success-text">
                	<i class="fa fa-check-circle" aria-hidden="true"></i>
                    <h2>thank you</h2>
                    <p>Payment is successfully processsed and your order is on the way</p>
                    @if($order->payment_method != 2)
                    	<p>Transaction ID: {{$order->payment ? $order->payment->transaction_id : ''}}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
<section class="section-b-space">
    <div class="container position-relative">
        <div class="error_msg">You have earned {{ (int)$order->loyalty_points_earned }} points with this order.</div>
        <div class="row">
            <div class="col-lg-6">
                <div class="product-order">
                    <h3>your order details</h3>
                    @foreach($order->products as $product)
                    @php
                        $image = $product->media ? $product->media->first()->image['path']['proxy_url'].'74/100'.$product->media->first()->image['path']['image_path']:$product->image['proxy_url'].'74/100'.$product->image['image_path'];
                    @endphp
	                    <div class="row product-order-detail">
	                        <div class="col-3">
	                        	<img src="{{ $image }}" class="img-fluid blur-up lazyloaded">
	                        </div>
	                        <div class="col-3 order_detail">
	                            <div>
	                                <h4>product name</h4>
	                                <h5>{{$product->product_name}}</h5>
                                    @foreach($product->pvariant->vset as $vset)
                                        <label><span>{{$vset->optionData->trans->title}}:</span>{{$vset->variantDetail->trans->title}}</label>
                                    @endforeach
	                            </div>
	                        </div>
	                        <div class="col-3 order_detail">
	                            <div>
	                                <h4>quantity</h4>
	                                <h5>{{$product->quantity}}</h5>
	                            </div>
	                        </div>
	                        <div class="col-3 order_detail">
	                            <div>
	                                <h4>price</h4>
	                                <h5>{{Session::get('currencySymbol')}}@money($product->price)</h5>
	                            </div>
	                        </div>
	                    </div>
                    @endforeach
                    <div class="total-sec">
                        <ul>
                            <li>subtotal <span>{{Session::get('currencySymbol')}}@money($order->total_amount)</span></li>
                            <li>tax(GST) <span>{{Session::get('currencySymbol')}}@money($order->taxable_amount)</span></li>
                        </ul>
                    </div>
                    <div class="final-total">
                        <h3>total <span>{{Session::get('currencySymbol')}}@money($order->payable_amount)</span></h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row order-success-sec">
                    <div class="col-sm-6">
                        <h4>summery</h4>
                        <ul class="order-detail">
                            <li>order ID: {{$order->order_number}}</li>
                            <li>Order Date: {{ date('F d, Y', strtotime($order->created_at)) }}</li>
                            <li>Order Total: ${{$order->payable_amount}}</li>
                        </ul>
                    </div>
                    <div class="col-sm-6">
                        <h4>shipping address</h4>
                        <ul class="order-detail">
                            <li>{{$order->address ? $order->address->address : ''}}</li>
                        </ul>
                    </div>
                    <div class="col-sm-12 payment-mode">
                        <h4>payment method</h4>
                        <p>{{$order->paymentOption->title}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection