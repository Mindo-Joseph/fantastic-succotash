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
        </div> -->
        <div class="order_listing border pb-3">
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
            <div class="row no-gutters order_data mb-4">
                <div class="col-md-2">#111-851254-2121</div>
                <div class="col-md-2">Monday, May 24, 2021, 11:05 AM</div>                
                <div class="col-md-2">
                   <a href="#">Santiago</a>
                </div>
                <div class="col-md-2">
                    Plot no 5, CH Devi Lal Centre of Learning, Building, Sector, 28B, Sector 28, Chandigarh, 160019
                </div>
                <div class="col-md-4">
                    <div class="row no-gutters no-wrap price_box">
                        <div class="col text-center">$120</div>
                        <div class="col text-center">$50</div>
                        <div class="col text-center">$150</div>
                        <div class="col text-center">$200</div>
                        <div class="col text-center">$400</div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="offset-md-2 col-md-8">
                    <a href="#" class="row order_detail order_detail_data align-items-top pb-3 card-box no-gutters">
                        <span class="left_arrow"></span>
                        <div class="col-md-2">
                            <h4 class="m-0">Mcdonald's</h4>
                            <ul class="status_box mt-3 pl-0">
                                <li><i class="fas fa-shopping-cart mr-1"></i><label class="m-0 in-progress">Accepted</label></li>
                                <li><i class="fas fa-truck mr-1"></i><label class="m-0 in-progress">Assigned</label></li>
                            </ul>
                        </div>
                       
                        <div class="col-md-10">
                            <div class="row align-items-start">
                                <div class="col-md-8">
                                    <ul class="product_list d-flex align-items-center p-0 flex-wrap m-0">
                                        <li class="text-center">
                                            <img src="{{ asset('assets/images/burger.jpg') }}" alt="">
                                            <span class="item_no position-absolute">3</span>
                                            <label class="items_price">$200</label>
                                        </li>
                                        <li class="text-center">
                                            <img src="{{ asset('assets/images/burger.jpg') }}" alt="">
                                            <span class="item_no position-absolute">3</span>
                                            <label class="items_price">$20</label>
                                        </li>
                                        <li class="text-center">
                                            <img src="{{ asset('assets/images/burger.jpg') }}" alt="">
                                            <span class="item_no position-absolute">3</span>
                                            <label class="items_price">$20</label>
                                        </li>
                                        <li class="text-center">
                                            <img src="{{ asset('assets/images/burger.jpg') }}" alt="">
                                            <span class="item_no position-absolute">3</span>
                                            <label class="items_price">$20</label>
                                        </li>
                                        <li class="text-center">
                                            <img src="{{ asset('assets/images/burger.jpg') }}" alt="">
                                            <span class="item_no position-absolute">3</span>
                                            <label class="items_price">$20</label>
                                        </li>
                                        <li class="text-center">
                                            <img src="{{ asset('assets/images/burger.jpg') }}" alt="">
                                            <span class="item_no position-absolute">3</span>
                                            <label class="items_price">$20</label>
                                        </li>
                                        <li class="text-center">
                                            <img src="{{ asset('assets/images/burger.jpg') }}" alt="">
                                            <span class="item_no position-absolute">3</span>
                                            <label class="items_price">$20</label>
                                        </li>
                                        <li class="text-center">
                                            <img src="{{ asset('assets/images/burger.jpg') }}" alt="">
                                            <span class="item_no position-absolute">3</span>
                                            <label class="items_price">$20</label>
                                        </li>
                                        <li class="text-center">
                                            <img src="{{ asset('assets/images/burger.jpg') }}" alt="">
                                            <span class="item_no position-absolute">3</span>
                                            <label class="items_price">$20</label>
                                        </li>
                                        <li class="text-center">
                                            <img src="{{ asset('assets/images/burger.jpg') }}" alt="">
                                            <span class="item_no position-absolute">3</span>
                                            <label class="items_price">$20</label>
                                        </li>                                        
                                    </ul>
                                </div>
                                <div class="col-md-4">
                                    
                                    <ul class="price_box_bottom m-0 p-0">
                                        <li class="d-flex align-items-center justify-content-between">
                                            <label class="m-0">Product Total</label>
                                            <span>$300.00</span>
                                        </li>
                                        <li class="d-flex align-items-center justify-content-between">
                                            <label class="m-0">Discount Coupon (10%)</label>
                                            <span>$30.00</span>
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
            <div class="row">
                <div class="offset-md-2 col-md-8">
                    <a href="#" class="row order_detail order_detail_data align-items-top pb-3 card-box no-gutters">
                        <span class="left_arrow pulse"></span>
                        <div class="col-md-2">
                            <h4 class="m-0">Mcdonald's</h4>
                            <ul class="status_box mt-3 pl-0">
                                <li><i class="fas fa-shopping-cart mr-1"></i><label class="m-0 in-progress">Accepted</label></li>
                                <li><i class="fas fa-truck mr-1"></i><label class="m-0 in-progress">Assigned</label></li>
                            </ul>
                        </div>
                       
                        <div class="col-md-10">
                            <div class="row align-items-start">
                                <div class="col-md-8">
                                    <ul class="product_list d-flex align-items-center p-0 flex-wrap m-0">
                                        <li class="text-center">
                                            <img src="{{ asset('assets/images/burger.jpg') }}" alt="">
                                            <span class="item_no position-absolute">3</span>
                                            <label class="items_price">$200</label>
                                        </li>
                                        <li class="text-center">
                                            <img src="{{ asset('assets/images/burger.jpg') }}" alt="">
                                            <span class="item_no position-absolute">3</span>
                                            <label class="items_price">$20</label>
                                        </li>
                                        <li class="text-center">
                                            <img src="{{ asset('assets/images/burger.jpg') }}" alt="">
                                            <span class="item_no position-absolute">3</span>
                                            <label class="items_price">$20</label>
                                        </li>
                                        <li class="text-center">
                                            <img src="{{ asset('assets/images/burger.jpg') }}" alt="">
                                            <span class="item_no position-absolute">3</span>
                                            <label class="items_price">$20</label>
                                        </li>
                                        <li class="text-center">
                                            <img src="{{ asset('assets/images/burger.jpg') }}" alt="">
                                            <span class="item_no position-absolute">3</span>
                                            <label class="items_price">$20</label>
                                        </li>
                                        <li class="text-center">
                                            <img src="{{ asset('assets/images/burger.jpg') }}" alt="">
                                            <span class="item_no position-absolute">3</span>
                                            <label class="items_price">$20</label>
                                        </li>
                                        <li class="text-center">
                                            <img src="{{ asset('assets/images/burger.jpg') }}" alt="">
                                            <span class="item_no position-absolute">3</span>
                                            <label class="items_price">$20</label>
                                        </li>
                                        <li class="text-center">
                                            <img src="{{ asset('assets/images/burger.jpg') }}" alt="">
                                            <span class="item_no position-absolute">3</span>
                                            <label class="items_price">$20</label>
                                        </li>
                                        <li class="text-center">
                                            <img src="{{ asset('assets/images/burger.jpg') }}" alt="">
                                            <span class="item_no position-absolute">3</span>
                                            <label class="items_price">$20</label>
                                        </li>
                                        <li class="text-center">
                                            <img src="{{ asset('assets/images/burger.jpg') }}" alt="">
                                            <span class="item_no position-absolute">3</span>
                                            <label class="items_price">$20</label>
                                        </li>                                        
                                    </ul>
                                </div>
                                <div class="col-md-4">
                                    
                                    <ul class="price_box_bottom m-0 p-0">
                                        <li class="d-flex align-items-center justify-content-between">
                                            <label class="m-0">Product Total</label>
                                            <span>$300.00</span>
                                        </li>
                                        <li class="d-flex align-items-center justify-content-between">
                                            <label class="m-0">Discount Coupon (10%)</label>
                                            <span>$30.00</span>
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
        </div>
</div> 
@endsection
