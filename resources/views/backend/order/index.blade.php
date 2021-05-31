@extends('layouts.vertical', ['title' => 'Orders'])
@section('content')    
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
        <!-- <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-lg-12">
                                <div class="form-inline">
                                    <div class="form-group mb-2">
                                        <label for="inputPassword2" class="sr-only">Search</label>
                                        <input type="search" class="form-control" id="inputPassword2" placeholder="Search...">
                                    </div>
                                    <div class="form-group mx-sm-3 mb-2">
                                        <label for="status-select" class="mr-2">Status</label>
                                        <select class="custom-select" id="status-select">
                                            <option selected>Choose...</option>
                                            <option value="1">Paid</option>
                                            <option value="2">Awaiting Authorization</option>
                                            <option value="3">Payment failed</option>
                                            <option value="4">Cash On Delivery</option>
                                            <option value="5">Fulfilled</option>
                                            <option value="6">Unfulfilled</option>
                                        </select>
                                    </div>
                                </div>                            
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-centered table-nowrap mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width: 20px;">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                <label class="custom-control-label" for="customCheck1">&nbsp;</label>
                                            </div>
                                        </th>
                                        <th>Order ID</th>
                                        <th>Products</th>
                                        <th>Date</th>
                                        <th>Payment Status</th>
                                        <th>Total</th>
                                        <th>Payment Method</th>
                                        <th>Order Status</th>
                                        <th style="width: 125px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="customCheck2">
                                                <label class="custom-control-label" for="customCheck2">&nbsp;</label>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{route('second', ['ecommerce', 'order-detail'])}}" class="text-body font-weight-bold">#{{$order->order_number}}</a>
                                        </td>
                                        <td>
                                            @foreach($order->products as $product)
                                                <a href="ecommerce-product-detail.html">
                                                    <img src="{{$product->image['proxy_url'].'32/32'.$product->image['image_path']}}" alt="product-img" height="32">
                                                </a>
                                            @endforeach
                                        </td>
                                        <td>{{$order->created_at}}</td>
                                        <td>
                                            <h5><span class="badge bg-soft-success text-success"><i class="mdi mdi-coin"></i> Paid</span></h5>
                                        </td>
                                        <td>
                                        {{$order->payable_amount}}
                                        </td>
                                        <td>
                                        @if($order['payment_method'] == 1)
                                            <h5><span class="badge bg-soft-success text-success"><i class="mdi mdi-coin"></i> Paid</span></h5>
                                        @elseif($order['payment_method'] == 2)
                                            <h5><span class="badge bg-soft-info text-info"><i class="mdi mdi-cash"></i> Cash on Delivery</span></h5>
                                        @endif
                                        </td>
                                        <td>
                                        @if($order['status'] == 1)
                                            <h5><span class="badge badge-success">Confirmed</span></h5>
                                        @elseif($order['status'] == 2)
                                            <h5><span class="badge badge-info">Shipped</span></h5>
                                        @elseif($order['status'] == 3)
                                            <h5><span class="badge badge-success">Delivered</span></h5>
                                        @elseif($order['status'] == 0)
                                            <h5><span class="badge badge-warning">Processing</span></h5>
                                        @elseif($order['status'] == 4)
                                            <h5><span class="badge badge-danger">Cancelled</span></h5>
                                        @endif
                                        </td>
                                        <td>
                                            <a  class="action-icon">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach 
                                </tbody>
                            </table>
                        </div>
                        <div class="pagination pagination-rounded justify-content-end mb-0">
                        {{ $orders->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div> -->


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
                    <div class="col-md-3">{{ \Carbon\Carbon::parse($order->created_at)->format('l, F d, Y, H:i A') }}</div>
                    <div class="col-md-3">
                       <a href="#">{{$order->user->name}}</a>
                    </div>
                    <div class="col-md-3">
                        <p class="ellipsis" data-toggle="tooltip" data-placement="top" title="{{$order->address['address']}}">
                            {{$order->address ?? $order->address['address']}}
                        </p>
                    </div>                    
                </div>
                @php
                    $product_total_count = 0;
                @endphp
                @foreach($order->products->groupBy('vendor_id') as $k => $products)
                <div class="row {{$k ==0 ? 'mt-3' : ''}}">
                    <div class="col-md-9">
                        <a href="{{route('order.show', $order->id)}}" class="row order_detail order_detail_data align-items-top pb-3 card-box no-gutters">
                            <span class="left_arrow pulse"></span>
                            <div class="col-5 col-sm-3">
                                <h4 class="m-0">{{ App\Models\Vendor::getNameById($k) }}</h4>
                                <ul class="status_box mt-3 pl-0">
                                    <li><img src="{{ asset('assets/images/order-icon.svg') }}" alt=""><label class="m-0 in-progress">Accepted</label></li>
                                    <li><img src="{{ asset('assets/images/driver_icon.svg') }}" alt=""><label class="m-0 in-progress">Assigned</label></li>
                                </ul>
                            </div>
                            <div class="col-7 col-sm-4">
                                <ul class="product_list d-flex align-items-center p-0 flex-wrap m-0">
                                    @foreach($products as $product)
                                        <li class="text-center">
                                            <img src="{{ $product->image['proxy_url'].'74/100'.$product->image['image_path']}}" alt="">
                                            <span class="item_no position-absolute">x{{$product->quantity}}</span>
                                            <label class="items_price">${{$product->price}}</label>
                                        </li>
                                        @php
                                            $product_total_count += $product->quantity * $product->price;
                                        @endphp
                                    @endforeach                                    
                                </ul>
                            </div>
                            <div class="col-md-5 mt-md-0 mt-sm-2">
                                <ul class="price_box_bottom m-0 p-0">
                                    <li class="d-flex align-items-center justify-content-between">
                                        <label class="m-0">Product Total</label>
                                        <span>${{$product_total_count}}</span>
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
                    <div class="col-md-3">
                        <div class="card-box p-2">
                            <ul class="price_box_bottom m-0 pl-0 pt-1">
                                <li class="d-flex align-items-center justify-content-between">
                                    <label class="m-0">Sub Total</label>
                                    <span>${{$product_total_count}}</span>
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
                @endforeach
            </div>
                @endforeach
            
                <hr>
        </div>


        <!-- <div class="order_listing border pb-3">
            <div class="row no-gutters order_head">
                <div class="col-md-2"><h4>Order Id</h4></div>
                <div class="col-md-2"><h4>Date & Time</h4></div>
                <div class="col-md-2"><h4>Customer Name</h4></div>
                <div class="col-md-2"><h4>Address</h4></div>
                <div class="col-md-4">
                    <div class="row align-items-center">
                        <div class="col text-center"><h4 class="m-0">Sub Total</h4></div>
                        <div class="col text-center"><h4 class="m-0">Wallet</h4></div>
                        <div class="col text-center"><h4 class="m-0">Loyalty</h4></div>
                        <div class="col text-center"><h4 class="m-0">Tax</h4></div>
                        <div class="col text-center"><h4 class="m-0">Total Payable</h4></div>
                    </div>
                </div>
            </div>
            <hr class="mt-0 mb-2">
            @foreach($orders as $order)
                <div class="row no-gutters order_data mb-4">
                    <div class="col-md-2">#{{$order->order_number}}</div>
                    <div class="col-md-2">{{ \Carbon\Carbon::parse($order->created_at)->format('l, F d, Y, H:i A') }}</div>
                    <div class="col-md-2">
                       <a href="#">{{$order->user->name}}</a>
                    </div>
                    <div class="col-md-2">{{$order->address['address']}}</div>
                    <div class="col-md-4">
                        <div class="row no-gutters no-wrap price_box">
                            <div class="col text-center">${{$order->total_amount}}</div>
                            <div class="col text-center">$0.00</div>
                            <div class="col text-center">$0.00</div>
                            <div class="col text-center">${{$order->taxable_amount}}</div>
                            <div class="col text-center">${{$order->payable_amount}}</div>
                        </div>
                    </div>
                </div>
                @php
                    $product_total_count = 0;
                @endphp
                @foreach($order->products->groupBy('vendor_id') as $k => $products)
                <div class="row {{$k ==0 ? 'mt-3' : ''}}">
                    <div class="offset-md-2 col-md-8">
                        <a href="{{route('order.show', $order->id)}}" class="row order_detail order_detail_data align-items-top pb-3 card-box no-gutters">
                            <span class="left_arrow pulse"></span>
                            <div class="col-md-2">
                                <h4 class="m-0">{{ App\Models\Vendor::getNameById($k) }}</h4>
                                <ul class="status_box mt-3 pl-0">
                                    <li><img src="{{ asset('assets/images/order-icon.svg') }}" alt=""><label class="m-0 in-progress">Accepted</label></li>
                                    <li><img src="{{ asset('assets/images/driver_icon.svg') }}" alt=""><label class="m-0 in-progress">Assigned</label></li>
                                </ul>
                            </div>
                            <div class="col-md-10">
                                <div class="row align-items-start">
                                    <div class="col-md-8">
                                        <ul class="product_list d-flex align-items-center p-0 flex-wrap m-0">
                                            @foreach($products as $product)
                                                <li class="text-center">
                                                    <img src="{{ $product->image['proxy_url'].'74/100'.$product->image['image_path']}}" alt="">
                                                    <span class="item_no position-absolute">x{{$product->quantity}}</span>
                                                    <label class="items_price">${{$product->price}}</label>
                                                </li>
                                                @php
                                                    $product_total_count += $product->quantity * $product->price;
                                                @endphp
                                            @endforeach                                    
                                        </ul>
                                    </div>
                                    <div class="col-md-4">
                                        <ul class="price_box_bottom m-0 p-0">
                                            <li class="d-flex align-items-center justify-content-between">
                                                <label class="m-0">Product Total</label>
                                                <span>${{$product_total_count}}</span>
                                            </li>
                                            <li class="d-flex align-items-center justify-content-between">
                                                <label class="m-0">Discount Coupon (10%)</label>
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
                                </div>  
                            </div>
                        </a>
                    </div>     
                </div>
                @endforeach
            @endforeach
        </div> -->
</div> 
@endsection
