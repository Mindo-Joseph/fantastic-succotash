@extends('layouts.vertical', ['title' => 'Orders'])

@section('content')    
    <!-- Start Content-->
    <div class="container-fluid">
        
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                           <!--  <li class="breadcrumb-item"><a href="javascript: void(0);">UBold</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">eCommerce</a></li> 
                            <li class="breadcrumb-item active">Orders</li> -->
                        </ol>
                    </div>
                    <h4 class="page-title">Orders</h4>
                </div>
            </div>
        </div>     
        <!-- end page title --> 

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-lg-8">
                                <form class="form-inline">
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
                                </form>                            
                            </div>
                            <div class="col-lg-4">
                                <div class="text-lg-right">
                                    <!-- <button type="button" class="btn btn-danger waves-effect waves-light mb-2 mr-2"><i class="mdi mdi-basket mr-1"></i> Add New Order</button>
                                    <button type="button" class="btn btn-light waves-effect mb-2">Export</button> -->
                                </div>
                            </div><!-- end col-->
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
                                @foreach($orders as $or)
                                    <tr>
                                        <td>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="customCheck2">
                                                <label class="custom-control-label" for="customCheck2">&nbsp;</label>
                                            </div>
                                        </td>
                                        <td><a href="{{route('second', ['ecommerce', 'order-detail'])}}" class="text-body font-weight-bold">#{{$or['id']}}</a> </td>
                                        
                                        <td>
                                        {{$or['created_at']}}
                                            <!-- August 05 2018 <small class="text-muted">10:29 PM</small> -->
                                        </td>
                                        <td>
                                            <h5><span class="badge bg-soft-success text-success"><i class="mdi mdi-coin"></i> Paid</span></h5>
                                        </td>
                                        <td>
                                        {{$or['payable_amount']}}
                                        </td>
                                        <td>
                                        @if($or['payment_method'] == 1)
                                            Credit card
                                        @elseif($or['payment_method'] == 2)
                                        Cash On Delivery
                                        @elseif($or['payment_method'] == 3)
                                        Paypal
                                        @elseif($or['payment_method'] == 4)
                                        Wallet
                                        @endif
                                        </td>
                                        <td>
                                        @if($or['status'] == 1)
                                        <h5><span class="badge badge-success">Confirmed</span></h5>
                                        @elseif($or['status'] == 2)
                                        <h5><span class="badge badge-info">Shipped</span></h5>
                                        @elseif($or['status'] == 3)
                                        <h5><span class="badge badge-success">Delivered</span></h5>
                                        @elseif($or['status'] == 0)
                                        <h5><span class="badge badge-warning">Processing</span></h5>
                                        @elseif($or['status'] == 4)
                                        <h5><span class="badge badge-danger">Cancelled</span></h5>
                                        @endif
                                        </td>
                                        <td>
                                            <a href="javascript:void(0);" class="action-icon"> <i class="mdi mdi-eye"></i></a>
                                            <a href="javascript:void(0);" class="action-icon"> <i class="mdi mdi-square-edit-outline"></i></a>
                                            <a href="javascript:void(0);" class="action-icon"> <i class="mdi mdi-delete"></i></a>
                                        </td>
                                    </tr>
                                @endforeach 
                                    
                                    
                                </tbody>
                            </table>
                        </div>

                        <!-- <ul class="pagination pagination-rounded justify-content-end my-2">
                            <li class="page-item">
                                <a class="page-link" href="javascript: void(0);" aria-label="Previous">
                                    <span aria-hidden="true">«</span>
                                    <span class="sr-only">Previous</span>
                                </a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="javascript: void(0);">1</a></li>
                            <li class="page-item"><a class="page-link" href="javascript: void(0);">2</a></li>
                            <li class="page-item"><a class="page-link" href="javascript: void(0);">3</a></li>
                            <li class="page-item"><a class="page-link" href="javascript: void(0);">4</a></li>
                            <li class="page-item"><a class="page-link" href="javascript: void(0);">5</a></li>
                            <li class="page-item">
                                <a class="page-link" href="javascript: void(0);" aria-label="Next">
                                    <span aria-hidden="true">»</span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </li>
                        </ul> -->
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>
        <!-- end row -->
        
</div> <!-- container -->
@endsection
