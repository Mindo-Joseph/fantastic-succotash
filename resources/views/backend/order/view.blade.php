@extends('layouts.vertical', ['title' => 'Order Detail'])
@section('content')
@php
$timezone = Auth::user()->timezone;
@endphp
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
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
                                    <p>----</p>
                                </div>
                            </div>
                        </div>
                        <div class="row track-order-list">
                            <div class="col-lg-6">
                                <button type="button" class="btn btn-danger waves-effect waves-light">
                                    <i class="mdi mdi-close"></i>
                                 </button>
                                <ul class="list-unstyled" id="order_statuses">
                                    @foreach($order_status_options as $order_status_option)
                                    @php
                                        $class = in_array($order_status_option->id, $vendor_order_status_option_ids) ? 'completed disabled': '';
                                        $date = isset($vendor_order_status_created_dates[$order_status_option->id]) ? $vendor_order_status_created_dates[$order_status_option->id] : '';
                                    @endphp
                                        <li class="{{$class}}" data-status_option_id="{{$order_status_option->id}}">
                                            <h5 class="mt-0 mb-1">{{$order_status_option->title}}</h5>
                                            <p class="text-muted" id="text_muted_{{$order_status_option->id}}">
                                                @if($date)
                                                    <small class="text-muted">{{convertDateTimeInTimeZone($date, $timezone, 'l, F d, Y, H:i A')}}</small>
                                                @endif
                                            </p>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="col-lg-6">
                                <ul class="list-unstyled">
                                    @foreach($dispatcher_status_options as $dispatcher_status_option)
                                        <li>
                                            <h5 class="mt-0 mb-1">{{$dispatcher_status_option->title}}</h5>
                                            <p class="text-muted">
                                                <small class="text-muted">...</small>
                                            </p>
                                        </li>
                                    @endforeach
                                </ul>
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
                                            <img src="{{$product->image_path['proxy_url'].'32/32'.$product->image_path['image_path']}}" alt="product-img" height="32">
                                        </td>
                                        <td>{{ $product->quantity }}</td>
                                        <td>$@money($product->price)</td>
                                        <td>$@money($product->quantity * $product->price)</td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <th scope="row" colspan="4" class="text-end">Sub Total :</th>
                                        <td>
                                            <div class="fw-bold">$@money($sub_total)</div>
                                        </td>
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
                                        <td>
                                            <div class="fw-bold">$@money($vendor->payable_amount+$taxable_amount)</div>
                                        </td>
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
                            @if($order->payment_method == 1)
                            <p class="mb-0"><span class="fw-semibold">Payment Mode :</span> Credit Card</p>
                            @elseif($order->payment_method == 2)
                            <p class="mb-0"><span class="fw-semibold">Payment Mode :</span> Cash On Delivery</p>
                            @elseif($order->payment_method == 3)
                            <p class="mb-0"><span class="fw-semibold">Payment Mode :</span> Paypal </p>
                            @elseif($order->payment_method == 4)
                            <p class="mb-0"><span class="fw-semibold">Payment Mode :</span> Wallet</p>
                            @endif
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
@section('script')
<script>
    $("#order_statuses li").click(function() {
        if(confirm("Are you Sure?")){
            let that = $(this);
        var status_option_id = that.data("status_option_id");
        $.ajax({
            url: "{{ route('order.changeStatus') }}",
            type: "POST",
            data: {
                order_id: "{{$order->id}}",
                vendor_id: "{{$vendor_id}}",
                "_token": "{{ csrf_token() }}",
                status_option_id: status_option_id,
            },
            success: function(response) {
                that.addClass("completed");
                $('#text_muted_'+status_option_id).html('<small class="text-muted">'+response.created_date+'</small>');
                $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
            },
        });
        }
    });
</script>
@endsection