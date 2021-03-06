@extends('layouts.vertical', ['demo' => 'Orders', 'title' => 'Accounting - Orders'])
@section('css')
{{-- <link href="{{asset('assets/libs/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css" /> --}}
<style>
.dataTables_filter,.toolbar,.dt-buttons.btn-group.flex-wrap {position: absolute;height:40px;}.dataTables_filter{right:0;top: -50px;}
.dataTables_filter label{margin:0;height:40px;}.dataTables_filter label input{margin:0;height:40px;}.dt-buttons.btn-group.flex-wrap{right: 170px;top: -50px;}
.table-responsive{position: relative;overflow:visible;margin-top:10px;}table.dataTable{margin-top:0 !important;}
</style>
@endsection
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">{{ __('Orders') }}</h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card widget-inline">
                    <div class="card-body p-2">
                        <div class="row">
                            <div class="col-6 col-md-3 mb-3 mb-md-0">
                                <div class="text-center">
                                    <h3>
                                        <i class="mdi mdi-currency-usd text-primary mdi-24px"></i>
                                        <span data-plugin="counterup" id="total_earnings_by_vendors">{{$total_earnings_by_vendors}}</span>
                                    </h3>
                                    <p class="text-muted font-15 mb-0">{{ __('Total Order Value') }}</p>
                                </div>
                            </div>
                            <div class="col-6 col-md-3 mb-3 mb-md-0">
                                <div class="text-center">
                                    <h3>
                                        <i class="mdi mdi-cart-arrow-up text-primary mdi-24px"></i>
                                        <span data-plugin="counterup" id="total_order_count">{{$total_order_count}}</span>
                                    </h3>
                                    <p class="text-muted font-15 mb-0">{{ __('Total Orders') }}</p>
                                </div>
                            </div>
                            <div class="col-6 col-md-3 mb-3 mb-md-0">
                                <div class="text-center">
                                    <h3>
                                        <i class="fas fa-money-check-alt text-primary"></i>
                                        <span data-plugin="counterup" id="total_cash_to_collected">{{$total_cash_to_collected}}</span>
                                    </h3>
                                    <p class="text-muted font-15 mb-0">{{ __('Total Cash To Be Collected') }}</p>
                                </div>
                            </div>
                            <div class="col-6 col-md-3 mb-3 mb-md-0">
                                <div class="text-center">
                                    <h3>
                                        <i class="fas fa-money-check-alt text-primary"></i>
                                        <span data-plugin="counterup" id="total_delivery_fees">{{$total_delivery_fees}}</span>
                                    </h3>
                                    <p class="text-muted font-15 mb-0">{{ __('Total Delivery Fees') }}</p>
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
                <div class="card-body position-relative al">
                    <div class="top-input position-absoluteal">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col">
                                        <input type="text" id="range-datepicker" class="form-control al_box_height flatpickr-input" placeholder="2018-10-03 to 2018-10-10" readonly="readonly">
                                    </div>
                                    <div class="col">
                                        <select class="form-control al_box_height " id="vendor_select_box">
                                            <option value="">{{ __('Select Vendor') }}</option>
                                            @forelse($vendors as $vendor)
                                                <option value="{{$vendor->id}}">{{$vendor->name}}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </div>
                                    <div class="col">
                                        <select class="form-control al_box_height" name="" id="order_status_option_select_box">
                                            <option value="">{{ __('Select Order Status') }}</option>
                                            @forelse($order_status_options as $order_status_option)
                                                <option value="{{$order_status_option->title}}">{{$order_status_option->title}}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </div>
                                    <div class="col">
                                        <button type="button" class="btn btn-danger al_box_height waves-effect waves-light" id="clear_filter_btn_icon">
                                            <i class="mdi mdi-close"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                   </div>
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-striped" id="accounting_vendor_datatable" width="100%">
                            <thead>
                                <tr>
                                    <th>{{ __('Order ID') }}</th>
                                    <th>{{ __('Date & Time') }}</th>
                                    <th>{{ __('Customer Name') }}</th>
                                    <th>{{ __('Vendor') }}</th>
                                    <th>{{ __('Subtotal Amount') }}</th>
                                    <th>{{ __('Promo Code Discount') }}</th>
                                    <th>{{ __('Admin Commission') }} [{{ __("Fixed") }}]</th>
                                    <th>{{ __('Admin Commission') }} [%{{ __("Age") }}]</th>
                                    <th>{{ __('Final Amount') }}</th>
                                    <th>{{ __('Payment Method') }}</th>
                                    <th>{{ __('Order Status') }}</th>
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
        function getPercentageAmount(percent,amouny){
            var totalPercent = (amouny/percent);

            return numberWithCommas(parseFloat(totalPercent).toFixed(2));
        }


        function numberWithCommas(x) {
        // x=x.toFixed(2)
            if(x > 0){
                return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }else{
                return 0;
            }
        }
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
                            info:'{{__("Showing _START_ to _END_  of _TOTAL_ entries")}}',
                            paginate: { previous: "<i class='mdi mdi-chevron-left'>", next: "<i class='mdi mdi-chevron-right'>" },
                            searchPlaceholder: '{{__("Search By Order No.,Vendor,Customer Name")}}'
                        },
                        drawCallback: function () {
                            $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
                        },
                        buttons: [{
                                className:'btn btn-success waves-effect waves-light',
                                text: '<span class="btn-label"><i class="mdi mdi-export-variant"></i></span>{{__("Export CSV")}}',
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
                            {data: 'subtotal_amount', name: 'action', orderable: false, searchable: false,
                            "mRender": function(data, type, full) {
                                return numberWithCommas(data);
                            }},
                            {data: 'discount_amount', name: 'action', orderable: false, searchable: false,
                            "mRender": function(data, type, full) {
                                return numberWithCommas(data);
                            }},
                            {data: 'admin_commission_fixed_amount', name: 'action', orderable: false, searchable: false,
                            "mRender": function(data, type, full) {
                                return numberWithCommas(data);
                            }},
                            {data: 'admin_commission_percentage_amount', name: 'action', orderable: false, searchable: false,
                            "mRender": function(data, type, full) {
                                return numberWithCommas(data)+" ("+getPercentageAmount(data,full.subtotal_amount)+"%)";
                            }},
                            {data: 'payable_amount', name: 'action', orderable: false, searchable: false,
                            "mRender": function(data, type, full) {
                                return numberWithCommas(data);
                            }},
                            {data: 'order_detail.payment_option.title', name: 'action', orderable: false, searchable: false,
                            "mRender": function(data, type, full) {
                                return full.payment_option_title;
                            }
                        },
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
