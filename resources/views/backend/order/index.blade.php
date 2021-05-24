@extends('layouts.vertical', ['title' => 'Orders'])
@section('content')    
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
                                            <a href="{{route('order.show', $order->id)}}" class="action-icon">
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
        </div>
</div> 
@endsection
