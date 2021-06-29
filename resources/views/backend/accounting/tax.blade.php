@extends('layouts.vertical', ['demo' => 'Taxes', 'title' => 'Taxes'])
@section('css')
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                    </div>
                    <h4 class="page-title">Taxes</h4>
                </div>
            </div>
        </div>     
        <div class="row">
            <div class="col-12">
                <div class="card widget-inline">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <div class="p-2 text-center">
                                    <h3>
                                        <i class="mdi mdi-cart-plus text-primary mdi-24px"></i>
                                        <span data-plugin="counterup" id="">8954</span>
                                    </h3>
                                    <p class="text-muted font-15 mb-0">Type Of Taxes Applied</p>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <div class="p-2 text-center">
                                    <h3><i class="mdi mdi-currency-usd text-success mdi-24px"></i><span data-plugin="counterup">7841</span></h3>
                                    <p class="text-muted font-15 mb-0">Total Tax Collected</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
    </div> 
</div>
<script type="text/template" id="accounting_vendor_template">
    <% _.each(vendor_orders, function(vendor_order, key){%>
        <tr>
            <td><%= vendor_order.order_detail.order_number%></td>
            <td><%= vendor_order.created_at %></td>
            <td><%= vendor_order.user.name %></td>
            <td><%= vendor_order.payable_amount %></td>
            <td><%= vendor_order.discount_amount %></td>
            <td><%= vendor_order.admin_commission_fixed_amount %></td>
            <td><%= vendor_order.order_detail.payment_option.title %></td> 
        </tr>
    <% }); %>
</script>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body position-relative">
                    <div class="top-input position-absolute">
                        <div class="row">
                            <div class="col-md-6">
                                 <input type="text" class="form-control" data-provide="datepicker" data-date-format="MM yyyy" data-date-min-view-mode="1" id="month_picker_filter" style="display:none;">
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
                                    <th>Final Amount</th>
                                    <th>Tax Amount</th>
                                    <th>Tax Types</th>
                                    <th>Payment Method</th>
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
            $.ajax({
                data: {},
                type: "POST",
                dataType: "json",
                url: "{{route('account.order.filter')}}",
                success: function(response) {
                    if(response.status == 'Success'){
                        // $('#month_picker_filter').show();
                        $('#accounting_vendor_tbody_list').html('');
                        let accounting_vendor_template = _.template($('#accounting_vendor_template').html());
                        $("#accounting_vendor_tbody_list").append(accounting_vendor_template({vendor_orders: response.data.vendor_orders}));
                        $('#total_order_count').html(response.data.vendor_orders.length);
                        $('#total_delivery_fees').html(response.data.total_delivery_fees);
                        $('#total_cash_to_collected').html(response.data.total_cash_to_collected);
                        $('#total_earnings_by_vendors').html(response.data.total_earnings_by_vendors);
                        $('#total_order_count').counterUp({
                          delay: 10,
                          time: 2000
                        });
                        $('#total_delivery_fees').counterUp({
                          delay: 10,
                          time: 2000
                        });
                        $('#total_cash_to_collected').counterUp({
                          delay: 10,
                          time: 2000
                        });
                        $('#total_earnings_by_vendors').counterUp({
                          delay: 10,
                          time: 2000
                        });
                        if($.fn.DataTable.isDataTable('#accounting_vendor_datatable')){
                            table.destroy();
                            $('#accounting_vendor_datatable tbody').empty();
                        }
                        table = $("#accounting_vendor_datatable").DataTable({
                            "dom": '<"toolbar">frtip',
                            "scrollX": true,
                            drawCallback: function () {
                                $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
                            },
                        }).fnClearTable();
                    }
                }
            });
        }
    });
</script>
@endsection
@section('script')
<script src="{{asset('assets/libs/datatables/datatables.min.js')}}"></script>
@endsection