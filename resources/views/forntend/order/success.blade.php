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
                    	<p>Transaction ID:267676GHERT105467</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
<section class="section-b-space">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="product-order">
                    <h3>your order details</h3>
                    @foreach($order->products as $product)
	                    <div class="row product-order-detail">
	                        <div class="col-3">
	                        	<img src="{{ $product->image['proxy_url'].'74/100'.$product->image['image_path']}}" alt="" class="img-fluid blur-up lazyloaded">
	                        </div>
	                        <div class="col-3 order_detail">
	                            <div>
	                                <h4>product name</h4>
	                                <h5>{{$product->product_name}}</h5>
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
	                                <h5>${{$product->price}}</h5>
	                            </div>
	                        </div>
	                    </div>
                    @endforeach
                    <div class="total-sec">
                        <ul>
                            <li>subtotal <span>${{$order->total_amount}}</span></li>
                            <!-- <li>shipping <span>$12.00</span></li> -->
                            <li>tax(GST) <span>${{$order->taxable_amount}}</span></li>
                        </ul>
                    </div>
                    <div class="final-total">
                        <h3>total <span>${{$order->payable_amount}}</span></h3>
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
                        <p>Pay on Delivery (Cash/Card). Cash on delivery (COD) available. Card/Net banking acceptance subject to device availability.</p>
                    </div>
                    <!-- <div class="col-md-12">
                        <div class="delivery-sec">
                            <h3>expected date of delivery</h3>
                            <h2>october 22, 2018</h2>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</section>
@endsection