@extends('layouts.vertical', ['demo' => 'Orders', 'title' => 'Orders Accounting'])
@section('css')
<link href="{{asset('assets/libs/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Orders</h4>
                </div>
            </div>
        </div>     
        <div class="row">
            <div class="col-12">
                <div class="card widget-inline">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6 col-md-3 mb-3 mb-md-0">
                                <div class="text-center">
                                    <h3>
                                        <i class="mdi mdi-currency-usd text-primary mdi-24px"></i>
                                        <span data-plugin="counterup" id="total_earnings_by_vendors">{{$total_earnings_by_vendors}}</span>
                                    </h3>
                                    <p class="text-muted font-15 mb-0">Total Earnings By Vendors</p>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3 mb-3 mb-md-0">
                                <div class="text-center">
                                    <h3>
                                        <i class="mdi mdi-cart-arrow-up text-primary mdi-24px"></i>
                                        <span data-plugin="counterup" id="total_order_count">{{$total_order_count}}</span>
                                    </h3>
                                    <p class="text-muted font-15 mb-0">Total Orders</p>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3 mb-3 mb-md-0">
                                <div class="text-center">
                                    <h3>
                                        <i class="mdi mdi-currency-usd text-primary mdi-24px"></i>
                                        <span data-plugin="counterup" id="total_cash_to_collected">{{$total_cash_to_collected}}</span>
                                    </h3>
                                    <p class="text-muted font-15 mb-0">Total Cash To Be Collected</p>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3 mb-3 mb-md-0">
                                <div class="text-center">
                                    <h3>
                                        <i class="mdi mdi-currency-usd text-primary mdi-24px"></i>
                                        <span data-plugin="counterup" id="total_delivery_fees">{{$total_delivery_fees}}</span>
                                    </h3>
                                    <p class="text-muted font-15 mb-0">Total Delivery Fees</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
    </div> 
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body position-relative">
                    <div class="top-input position-absolute">
                        <div class="row">                            
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col">
                                        <input type="text" id="range-datepicker" class="form-control flatpickr-input" placeholder="2018-10-03 to 2018-10-10" readonly="readonly">
                                    </div>
                                    <div class="col">
                                        <select class="form-control" id="vendor_select_box">
                                            <option value="">Select Vendor</option>
                                            @forelse($vendors as $vendor)
                                                <option value="{{$vendor->id}}">{{$vendor->name}}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </div>
                                    <div class="col">
                                        <select class="form-control" name="" id="order_status_option_select_box">
                                            <option value="">Select Order Status</option>
                                            @forelse($order_status_options as $order_status_option)
                                                <option value="{{$order_status_option->title}}">{{$order_status_option->title}}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </div>
                                    <div class="col">
                                        <button type="button" class="btn btn-danger waves-effect waves-light" id="clear_filter_btn_icon">
                                            <i class="mdi mdi-close"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                   </div>
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-striped" id="accounting_vendor_datatable">
                            <thead>
                                <tr>
                                    <th>Order Id</th>
                                    <th>Date & Time</th>
                                    <th>Customer Name</th>
                                    <th>Vendor</th>
                                    <th>Subtotal Amount</th>
                                    <th>Promo Code Discount</th>
                                    <th>Admin Commission [Fixed]</th>
                                    <th>Admin Commission [%Age]</th>
                                    <th>Final Amount</th>
                                    <th>Payment Method</th>
                                    <th>Order Status</th>
                                </tr>
                            </thead>
                            <tbody id="accounting_vendor_tbody_list">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        var table;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
        getOrderList();
        function getOrderList() {
            $(document).ready(function() {
                initDataTable();
                $("#range-datepicker").flatpickr({ 
                    mode: "range",
                    onClose: function(selectedDates, dateStr, instance) {
                        initDataTable();
                    }
                });
                $("#clear_filter_btn_icon").click(function() {
                    $('#range-datepicker').val('');
                    $('#vendor_select_box').val('');
                    $('#order_status_option_select_box').val('');
                    initDataTable();
                });
                $("#vendor_select_box, #order_status_option_select_box").change(function() {
                    initDataTable();
                });
                function initDataTable() {
                    $('#accounting_vendor_datatable').DataTable({
                        "dom": '<"toolbar">Bfrtip',
                        "scrollX": true,
                        "destroy": true,
                        "processing": true,
                        "serverSide": true,
                        "iDisplayLength": 50,
                        language: {
                            search: "",
                            paginate: { previous: "<i class='mdi mdi-chevron-left'>", next: "<i class='mdi mdi-chevron-right'>" },
                            searchPlaceholder: "Search By Order No.,Vendor,Customer Name"
                        },
                        drawCallback: function () {
                            $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
                        },
                        buttons: [{   
                                className:'btn btn-success waves-effect waves-light',
                                text: '<span class="btn-label"><i class="mdi mdi-export-variant"></i></span>Export CSV',
                                action: function ( e, dt, node, config ) {
                                    window.location.href = "{{ route('account.order.export') }}";
                                }
                        }],
                        ajax: {
                          url: "{{route('account.order.filter')}}",
                          data: function (d) {
                            d.search = $('input[type="search"]').val();
                            d.date_filter = $('#range-datepicker').val();
                            d.vendor_id = $('#vendor_select_box option:selected').val();
                            d.status_filter = $('#order_status_option_select_box option:selected').val();
                          }
                        },
                        columns: [
                            {data: 'order_detail.order_number', name: 'order_number', orderable: false, searchable: false,"mRender": function ( data, type, full ) {
                              return "<a href='" + full.view_url + "' target='_blank'>"+full.order_detail.order_number+"</a>";
                            }},
                            {data: 'created_date', name: 'name',orderable: false, searchable: false},
                            {data: 'user_name', name: 'Customer Name',orderable: false, searchable: false},
                            {data: 'vendor.name', name: 'vendor_name', orderable: false, searchable: false},
                            {data: 'subtotal_amount', name: 'action', orderable: false, searchable: false},
                            {data: 'discount_amount', name: 'action', orderable: false, searchable: false},
                            {data: 'admin_commission_fixed_amount', name: 'action', orderable: false, searchable: false},
                            {data: 'admin_commission_percentage_amount', name: 'action', orderable: false, searchable: false},
                            {data: 'payable_amount', name: 'action', orderable: false, searchable: false},
                            {data: 'order_detail.payment_option.title', name: 'action', orderable: false, searchable: false},
                            {data: 'order_status', name: 'order_status', orderable: false, searchable: false, "mRender": function ( data, type, full ) {
                              return "<h5><span class='badge bg-success'>"+full.order_status+"</span></h5>";
                            }},
                        ]
                    });
                }
                
            });
        }
    });
</script>
@endsection
@section('script')
<script src="{{asset('assets/libs/datatables/datatables.min.js')}}"></script>
@endsection