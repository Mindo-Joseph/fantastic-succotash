@extends('layouts.vertical', ['demo' => 'Order list', 'title' => 'Vendors'])
@section('css')
<link href="{{asset('assets/libs/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                    </div>
                    <h4 class="page-title">Vendors</h4>
                </div>
            </div>
        </div>     
        <div class="row">
            <div class="col-12">
                <div class="card widget-inline">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6 col-md-4 mb-3 mb-md-0">
                                <div class="p-2 text-center">
                                    <h3><i class="mdi mdi-currency-usd text-success mdi-24px"></i><span data-plugin="counterup" id="total_order_value"></span></h3>
                                    <p class="text-muted font-15 mb-0">Total Order Value</p>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 mb-3 mb-md-0">
                                <div class="p-2 text-center">
                                    <h3><i class="mdi mdi-currency-usd text-success mdi-24px"></i><span data-plugin="counterup" id="total_delivery_fees"></span></h3>
                                    <p class="text-muted font-15 mb-0">Total Delivery Fees</p>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 mb-3 mb-md-0">
                                <div class="p-2 text-center">
                                    <h3><i class="mdi mdi-currency-usd text-success mdi-24px"></i><span data-plugin="counterup" id="total_admin_commissions"></span></h3>
                                    <p class="text-muted font-15 mb-0">Total Admin Commissions</p>
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
    <% _.each(vendors, function(vendor, key){%>
        <tr data-row-id="1">
            <td><a href="<%= vendor.url %>" target="_blank"><%= vendor.name%></a></td>
            <td><%= vendor.order_value > 0 ? vendor.order_value : '-' %></td>
            <td><%= vendor.delivery_fee > 0 ? vendor.delivery_fee : '-' %></td>
            <td><%= vendor.commission_percent %></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td> 
            <td></td> 
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
                                    <th>Vendor Name</th>
                                    <th>Order Value</th>
                                    <th>Delivery Fees</th>
                                    <th>Admin Commissions</th>
                                    <th>Promo [Vendor]</th>
                                    <th>Promo [Admin]</th>
                                    <th>Cash Collected</th>
                                    <th>Payment Gateway</th>
                                    <th>Vendor Earning</th>
                                    <th>Total Paid</th>
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
        initDataTable();
        $(document).on("change","#month_picker_filter",function() {
            initDataTable();
        });
        function initDataTable() {
            $('#accounting_vendor_datatable').DataTable({
                "dom": '<"toolbar">Bfrtip',
                "scrollX": true,
                "processing": true,
                "serverSide": true,
                "iDisplayLength": 50,
                destroy: true,
                language: {
                    search: "",
                    searchPlaceholder: "Search By Order No.,Vendor,Customer Name"
                },
                ajax: {
                  url: "{{route('account.vendor.filter')}}",
                  data: function (d) {
                    d.search = $('input[type="search"]').val();
                    d.date_filter = $('#range-datepicker').val();
                  }
                },
                columns: [
                    {data: 'vendor.name', name: 'vendor_name', orderable: false, searchable: false},
                    {data: 'vendor.order_value', name: 'order_value', orderable: false, searchable: false},
                ]
            });
        }
    });
</script>
@endsection
@section('script')
<script src="{{asset('assets/libs/datatables/datatables.min.js')}}"></script>
@endsection