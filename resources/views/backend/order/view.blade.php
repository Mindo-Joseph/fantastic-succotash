@extends('layouts.vertical', ['title' => 'Order Detail'])
@section('content') 
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">UBold</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Ecommerce</a></li>
                            <li class="breadcrumb-item active">Order Detail</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Order Detail</h4>
                </div>
            </div>
        </div>     
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Track Order</h4>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-4">
                                    <h5 class="mt-0">Order ID:</h5>
                                    <p>#{{$order->order_number}}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-4">
                                    <h5 class="mt-0">Tracking ID:</h5>
                                    <p>894152012012</p>
                                </div>
                            </div>
                        </div>
                        <div class="row track-order-list">
                            <div class="col-lg-6">
                                <ul class="list-unstyled">
                                    <li class="completed">
                                        <h5 class="mt-0 mb-1">Placed</h5>
                                        <p class="text-muted">April 21 2019 <small class="text-muted">07:22 AM</small> </p>
                                    </li>
                                    <li class="completed">
                                        <h5 class="mt-0 mb-1">Accepted</h5>
                                        <p class="text-muted"><small class="text-muted">...</small></p>
                                    </li>
                                    <li class="completed">
                                        <span class="active-dot dot"></span>
                                        <h5 class="mt-0 mb-1">Processing</h5>
                                        <p class="text-muted"><small class="text-muted">...</small></p>
                                    </li>
                                    <li>
                                        <h5 class="mt-0 mb-1"> Out For Delivery</h5>
                                        <p class="text-muted">...</p>
                                    </li>
                                     <li>
                                        <h5 class="mt-0 mb-1">Delivered</h5>
                                        <p class="text-muted">....</p>
                                    </li>
                                </ul>
                                 <div class="text-center mt-2">
                                    <a href="#" class="btn btn-primary">Update Status</a>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <ul class="list-unstyled">
                                    <li class="completed">
                                        <h5 class="mt-0 mb-1">Created</h5>
                                        <p class="text-muted">April 21 2019 <small class="text-muted">07:22 AM</small> </p>
                                    </li>
                                    <li class="completed">
                                        <h5 class="mt-0 mb-1">Assigned</h5>
                                        <p class="text-muted">April 22 2019 <small class="text-muted">12:16 AM</small></p>
                                    </li>
                                    <li>
                                        <span class="active-dot dot"></span>
                                        <h5 class="mt-0 mb-1">Started</h5>
                                        <p class="text-muted">April 22 2019 <small class="text-muted">05:16 PM</small></p>
                                    </li>
                                    <li>
                                        <h5 class="mt-0 mb-1">Arrived</h5>
                                        <p class="text-muted">Estimated delivery within 3 days</p>
                                    </li>
                                     <li>
                                        <h5 class="mt-0 mb-1">Completed</h5>
                                        <p class="text-muted">....</p>
                                    </li>
                                </ul>
                                 <div class="text-center mt-2">
                                    <a href="#" class="btn btn-primary">Update Status</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Items from Order #{{$order->order_number}}</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered table-centered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product name</th>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                @foreach($order->vendors as $vendor)
                                <tbody>
                                    @php
                                        $sub_total = 0;
                                        $taxable_amount = 0;
                                    @endphp
                                    @foreach($vendor->products as $product)
                                    @php
                                        $taxable_amount += $product->taxable_amount;
                                        $sub_total += $product->quantity * $product->price;
                                    @endphp
                                    <tr>
                                        <th scope="row">{{$product->product_name}}</th>
                                        <td>
                                            <img src="{{$product->image['proxy_url'].'32/32'.$product->image['image_path']}}" alt="product-img" height="32">
                                        </td>
                                        <td>{{ $product->quantity }}</td>
                                        <td>$@money($product->price)</td>
                                        <td>$@money($product->quantity * $product->price)</td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <th scope="row" colspan="4" class="text-end">Sub Total :</th>
                                        <td><div class="fw-bold">$@money($sub_total)</div></td>
                                    </tr>
                                    <tr>
                                        <th scope="row" colspan="4" class="text-end">Total Discount :</th>
                                        <td>$@money($vendor->discount_amount)</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" colspan="4" class="text-end">Estimated Tax :</th>
                                        <td>$@money($taxable_amount)</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" colspan="4" class="text-end">Total :</th>
                                        <td><div class="fw-bold">$@money($vendor->payable_amount)</div></td>
                                    </tr>
                                </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Shipping Information</h4>
                        <h5 class="font-family-primary fw-semibold">{{$order->user->name}}</h5>
                        <p class="mb-2"><span class="fw-semibold me-2">Address:</span> {{ $order->address ? $order->address->address : ''}}</p>
                        <p class="mb-0"><span class="fw-semibold me-2">Mobile:</span> {{$order->user->phone_number}}</p>
                    </div>
                </div>
            </div> 
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Delivery Info</h4>
                        <div class="text-center">
                            <i class="mdi mdi-truck-fast h2 text-muted"></i>
                            <h5><b>UPS Delivery</b></h5>
                            <p class="mb-1"><span class="fw-semibold">Order ID :</span> #{{$order->order_number}}</p>
                            <p class="mb-0"><span class="fw-semibold">Payment Mode :</span> COD</p>
                        </div>
                        <div class="text-center mt-2">
                            <a href="javascript::void(0);" class="btn btn-primary" id="delivery_info_button">Delivery Info</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="delivery_info_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Delivery Info</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body" id="AddCardBox">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info waves-effect waves-light submitAddForm">Submit</button>
            </div>
        </div>
    </div>
</div>
@endsection