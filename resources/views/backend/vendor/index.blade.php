@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Vendor'])
@section('css')
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
<style type="text/css">
    .pac-container, .pac-container .pac-item { z-index: 99999 !important; }
</style>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="page-title-box">
                <h4 class="page-title">Vendors</h4>
            </div>
        </div>
        <div class="col-sm-6 text-sm-right">
            <button class="btn btn-info waves-effect waves-light text-sm-right openImportModal"
                    userId="0"><i class="mdi mdi-plus-circle mr-1"></i> Import
            </button>
            <button class="btn btn-info waves-effect waves-light text-sm-right openAddModal"
                userId="0"><i class="mdi mdi-plus-circle mr-1"></i> Add
            </button>
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
                                    <i class="mdi mdi-storefront text-primary mdi-24px"></i>
                                    <span data-plugin="counterup" id="total_earnings_by_vendors">{{$total_vendor_count}}</span>
                                </h3>
                                <p class="text-muted font-15 mb-0">Total Vendors</p>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3 mb-3 mb-md-0">
                            <div class="text-center">
                                <h3>
                                    <i class="mdi mdi-store-24-hour text-primary mdi-24px"></i>
                                    <span data-plugin="counterup" id="total_order_count">{{$available_vendors_count}}</span>
                                </h3>
                                <p class="text-muted font-15 mb-0">Open Vendors</p>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3 mb-3 mb-md-0">
                            <div class="text-center">
                                <h3>
                                    <i class="mdi mdi-currency-usd text-primary mdi-24px"></i>
                                    <span data-plugin="counterup" id="total_cash_to_collected">{{$vendors_product_count}}</span>
                                </h3>
                                <p class="text-muted font-15 mb-0">Total Products</p>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3 mb-3 mb-md-0">
                            <div class="text-center">
                                <h3>
                                    <i class="mdi mdi-currency-usd text-primary mdi-24px"></i>
                                    <span data-plugin="counterup" id="total_delivery_fees">{{$vendors_active_order_count}}</span>
                                </h3>
                                <p class="text-muted font-15 mb-0">Total Active Orders</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-lg-12 tab-product pt-0">
            <ul class="nav nav-tabs nav-material" id="top-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="active-vendor" data-toggle="tab" href="#active_vendor" role="tab" aria-selected="false" data-rel="vendor_active_datatable" data-status="1">
                        <i class="icofont icofont-man-in-glasses"></i>Active Vendors
                    </a>
                    <div class="material-border"></div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="awaiting-vendor" data-toggle="tab" href="#awaiting_vendor" role="tab" aria-selected="true" data-rel="awaiting__Approval_vendor_datatble" data-status="0">
                        <i class="icofont icofont-ui-home"></i>Awaiting Approval Vendors
                    </a>
                    <div class="material-border"></div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="block-vendor" data-toggle="tab" href="#block_vendor" role="tab" aria-selected="false" data-rel="blocked_vendor_datatble" data-rel="blocked_vendor_datatble" data-status="2">
                        <i class="icofont icofont-man-in-glasses"></i>Blocked Vendors
                    </a>
                    <div class="material-border"></div>
                </li>
            </ul>
            <div class="tab-content nav-material pt-0   " id="top-tabContent">
                <div class="tab-pane fade past-order show active" id="active_vendor" role="tabpanel"
                    aria-labelledby="active-vendor">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <form name="saveOrder" id="saveOrder"> @csrf</form>
                                        <table class="table table-centered table-nowrap table-striped" id="vendor_active_datatable" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Icon</th>
                                                    <th>Name</th>
                                                    <th>Status</th>
                                                    <th>Address</th>
                                                    <th>Offers</th>
                                                    <th class="text-center">Can Add <br> Category</th>
                                                    <th class="text-center">Commission <br> Percentage</th>
                                                    <th class="text-center">Products</th>
                                                    <th class="text-center">Orders</th>
                                                    <th class="text-center">Active <br> Orders</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="post_list"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="row address" id="def" style="display: none;">
                            <input type="text" id="def-address" name="test" class="autocomplete form-control def_address">
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="awaiting_vendor" role="tabpanel"
                    aria-labelledby="awaiting-vendor">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <form name="saveOrder" id="saveOrder"> @csrf</form>
                                        <table class="table table-centered table-nowrap table-striped" id="awaiting__Approval_vendor_datatble" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Icon</th>
                                                    <th>Name</th>
                                                    <th>Status</th>
                                                    <th>Address</th>
                                                    <th>Offers</th>
                                                    <th class="text-center">Can Add <br> Category</th>
                                                    <th class="text-center">Commission <br> Percentage</th>
                                                    <th class="text-center">Products</th>
                                                    <th class="text-center">Orders</th>
                                                    <th class="text-center">Active <br> Orders</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="post_list"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="row address" id="def" style="display: none;">
                            <input type="text" id="def-address" name="test" class="autocomplete form-control def_address">
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade past-order" id="block_vendor" role="tabpanel" aria-labelledby="block-vendor">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-centered table-nowrap table-striped" id="blocked_vendor_datatble" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Icon</th>
                                                    <th>Name</th>
                                                    <th>Status</th>
                                                    <th>Address</th>
                                                    <th>Offers</th>
                                                    <th class="text-center">Can Add <br> Category</th>
                                                    <th class="text-center">Commission <br> Percentage</th>
                                                    <th class="text-center">Products</th>
                                                    <th class="text-center">Orders</th>
                                                    <th class="text-center">Active <br> Orders</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="post_list"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="row address" id="def" style="display: none;">
                            <input type="text" id="def-address" name="test" class="autocomplete form-control def_address">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</div>
@include('backend.vendor.modals')
<script type="text/javascript">
    $(document).ready(function() {
        var table;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
        setTimeout(function(){$('#active-vendor').trigger('click');}, 200);
        
        $(document).on("click",".nav-link",function() {
            let rel= $(this).data('rel');
            let status= $(this).data('status');
            initDataTable(rel, status);
        });
        $(document).on("click",".delete-vendor",function() {
            var destroy_url = $(this).data('destroy_url');
            var id = $(this).data('rel');
            if (confirm('Are you sure?')) {
              $.ajax({
                type: "POST",
                dataType: 'json',
                url: destroy_url,
                data:{'_method':'DELETE'},
                success: function(response) {
                    if (response.status == "Success") {
                        $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                        window.location.reload();
                    }
                }
            });
            }
        });
        function initDataTable(table, status) {
            $('#'+table).DataTable({
                "dom": '<"toolbar">Bfrtip',
                "destroy": true,
                "scrollX": true,
                "processing": true,
                "serverSide": true,
                "iDisplayLength": 20,
                language: {
                    search: "",
                    paginate: { previous: "<i class='mdi mdi-chevron-left'>", next: "<i class='mdi mdi-chevron-right'>" },
                    searchPlaceholder: "Search By Vendor Name"
                },
                drawCallback: function () {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
                },
                buttons: [],
                ajax: {
                  url: "{{route('vendor.filterdata')}}",
                  data: function (d) {
                    d.status = status;
                    d.search = $('input[type="search"]').val();
                    d.date_filter = $('#range-datepicker').val();
                    d.payment_option = $('#payment_option_select_box option:selected').val();
                    d.tax_type_filter = $('#tax_type_select_box option:selected').val();
                  }
                },
                columns: [
                    {data: 'order_number', name: 'order_number', orderable: false, searchable: false,"mRender": function ( data, type, full ) {
                        return "<a class='round_img_box' href='"+full.show_url+"'><img class='rounded-circle' src='"+full.logo.proxy_url+'90/90'+full.logo.image_path+"' alt='"+full.id+"'></a>";
                    }},
                    {data: 'name', name: 'name', orderable: false, searchable: false, "mRender": function ( data, type, full ) {
                        return "<a href='"+full.show_url+"'>"+full.name+"</a> ";
                    }},
                    {data: 'show_slot', name: 'show_slot', orderable: false, searchable: false, "mRender":function(data, type, full){
                        return "<span class='badge bg-soft-"+full.show_slot_label+" text-"+full.show_slot_label+"'>"+full.show_slot_option+"</span>";
                    }},
                    {data: 'address', name: 'address', class:'address_txt',orderable: false, searchable: false, "mRender":function(data, type, full){
                        return "<p class='ellips_txt' data-toggle='tooltip' data-placement='top' title='"+full.address+"'>"+full.address+"</p>";
                    }},
                    {data: 'offers', name: 'offers', class:'text-center', orderable: false, searchable: false, "mRender":function(data, type, full){
                        var markup = '';
                        for (var i = full.offers.length - 1; i >= 0; i--) {
                            if(full.offers[i]){
                                markup+="<span class='badge bg-soft-warning text-warning'>"+full.offers[i]+"</span>";
                            }
                        }
                        return markup;
                    }},
                    {data: 'add_category_option', class:'text-center', name: 'add_category_option', orderable: false, searchable: false},
                    {data: 'commission_percent', class:'text-center', name: 'commission_percent', orderable: false, searchable: false},
                    {data: 'products_count', class:'text-center', class:'text-center', name: 'products_count', orderable: false, searchable: false},
                    {data: 'orders_count', class:'text-center', name: 'orders_count', orderable: false, searchable: false},
                    {data: 'active_orders_count', class:'text-center', name: 'active_orders_count', orderable: false, searchable: false},
                    {data: 'edit_action', class:'text-center', name: 'edit_action', orderable: false, searchable: false, "mRender":function(data, type, full){
                        return "<div class='form-ul'><div class='inner-div d-inline-block'><a class='action-icon' userId='"+full.id+"' href='"+full.show_url+"'><i class='mdi mdi-eye'></i></a></div><div class='inner-div d-inline-block'><form method='POST' action='"+full.destroy_url+"'><div class='form-group action-icon mb-0'><button type='button' class='btn btn-primary-outline action-icon delete-vendor' data-destroy_url='"+full.destroy_url+"' data-rel='"+full.id+"'><i class='mdi mdi-delete'></i></button></div></form></div></div>"
                    }},
                ]
            });
        }
    });
</script>
@endsection
@section('script')
@include('backend.vendor.pagescript')
@endsection