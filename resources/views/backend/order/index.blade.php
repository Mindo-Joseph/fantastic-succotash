@extends('layouts.vertical', ['title' => 'Orders'])
@section('content') 
<style type="text/css">
    .ellipsis{
        white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    body{
        font-size: 0.75rem;
    }
    .order_data > div,.order_head h4 {
        padding: 0 !important;
    }
    .order-page .card-box {
        padding: 20px 20px 5px !important;
    }
</style>

<script type="text/template" id="order_page_template">
    <div class="row">
        <% _.each(orders, function(order, k){%>
            <% if(order.vendors.length !== 0) { %>
                <div class="col-xl-6 mb-3"  id="full-order-div<%= k %>">
                    <div class="row no-gutters order_head">
                        <div class="col-md-3"><h4>Order Id</h4></div>
                        <div class="col-md-3"><h4>Date & Time</h4></div>
                        <div class="col-md-3"><h4>Customer</h4></div>
                        <div class="col-md-3"><h4>Address</h4></div>
                    </div>
                    <div class="row no-gutters order_data mb-lg-0">
                        <div class="col-md-3"><h6>#<%= order.order_number %></h6></div>
                        <div class="col-md-3"><%= order.created_date %></div>
                        <div class="col-md-3">
                            <a class="text-capitalize" href="#"><%= order.user.name %></a>
                        </div>
                        

                        <% if(order.address !== null) { %>
                        <div class="col-md-3">
                            <p class="ellipsis" data-toggle="tooltip" data-placement="top" title="<%= order.address.address %>">
                                <%= order.address.address %>
                            </p>
                        </div>  
                        <% } %>                  
                    </div>
                    <div class="row">
                        <div class="col-md-9">
                            <% _.each(order.vendors, function(vendor, ve){%>
                                <div class="row  <%= ve ==0 ? 'mt-0' : 'mt-3'%>" id="single-order-div<%= k %><%= ve %>">
                                    <div class="col-12">
                                        <a href="<%= vendor.vendor_detail_url %>" class="row order_detail order_detail_data align-items-top pb-3 card-box no-gutters h-100">
                                            <span class="left_arrow pulse">
                                            </span>
                                            <div class="col-5 col-sm-3">
                                                <h4 class="m-0"><%= vendor.vendor_name %></h4>
                                                <ul class="status_box mt-3 pl-0">
                                                    <li>
                                                        <img src="{{ asset('assets/images/order-icon.svg') }}" alt="">
                                                        <label class="m-0 in-progress"><%= vendor.order_status %></label>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-7 col-sm-4">
                                                <ul class="product_list d-flex align-items-center p-0 flex-wrap m-0">
                                                    <% _.each(vendor.products, function(product, pr){%>
                                                            <li class="text-center">
                                                                <img src="<%= product.image_path.proxy_url %>74/100<%= product.image_path.image_path %>">
                                                                <span class="item_no position-absolute">x<%= product.quantity %></span>
                                                                <label class="items_price">$<%= product.price %></label>
                                                            </li>
                                                    <% }); %>                                    
                                                </ul>
                                            </div>
                                            <div class="col-md-5 mt-md-0 mt-sm-2">
                                                <ul class="price_box_bottom m-0 p-0">
                                                    <li class="d-flex align-items-center justify-content-between">
                                                        <label class="m-0">Total</label>
                                                        <span>$<%= vendor.subtotal_amount %></span>
                                                    </li>
                                                    <li class="d-flex align-items-center justify-content-between">
                                                        <label class="m-0">Promocode</label>
                                                        <span>$<%= vendor.discount_amount %></span>
                                                    </li>
                                                    <li class="d-flex align-items-center justify-content-between">
                                                        <label class="m-0">Delivery</label>
                                                        <% if(vendor.delivery_fee !== null) { %>
                                                        <span>$<%= vendor.delivery_fee %></span>
                                                        <% }else { %>
                                                            <span>$ 0.00</span>
                                                        <% } %> 
                                                    </li>
                                                    <li class="grand_total d-flex align-items-center justify-content-between">
                                                        <label class="m-0">Amount</label>
                                                        <span>$<%= vendor.payable_amount %></span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </a>
                                        
                                        <div class="row">
                                            <div class="col-5" id="update-single-status">
                                                <% if(vendor.order_status_option_id == 1) { %>
                                                    <button class="update-status btn-info" data-full_div="#full-order-div<%= k %>"  data-single_div="#single-order-div<%= k %><%= ve %>" data-count="<%= ve %>" data-order_id="<%= order.id %>"  data-vendor_id="<%= vendor.vendor_id %>"  data-status_option_id="2" data-order_vendor_id="<%= vendor.order_vendor_id %>">Accept</button>
                                                    <button class="update-status btn-danger" data-full_div="#full-order-div<%= k %>"  data-single_div="#single-order-div<%= k %><%= ve %>"  data-count="<%= ve %>"   data-order_id="<%= order.id %>"  data-vendor_id="<%= vendor.vendor_id %>" data-status_option_id="3" data-order_vendor_id="<%= vendor.order_vendor_id %>">Reject</button>
                                                    <% } else if(vendor.order_status_option_id == 2) { %>
                                                        <button class="update-status btn-warning" data-full_div="#full-order-div<%= k %>"  data-single_div="#single-order-div<%= k %><%= ve %>"  data-count="<%= ve %>"  data-order_id="<%= order.id %>"  data-vendor_id="<%= vendor.vendor_id %>"  data-status_option_id="4" data-order_vendor_id="<%= vendor.order_vendor_id %>">Processing</button>
                                                    <% } else if(vendor.order_status_option_id == 4) { %>
                                                            <button class="update-status btn-success" data-full_div="#full-order-div<%= k %>"  data-single_div="#single-order-div<%= k %><%= ve %>"  data-count="<%= ve %>"  data-order_id="<%= order.id %>"  data-vendor_id="<%= vendor.vendor_id %>"  data-status_option_id="5" data-order_vendor_id="<%= vendor.order_vendor_id %>">Out For Delivery</button>
                                                    <% } else if(vendor.order_status_option_id == 5) { %>
                                                        <button class="update-status btn-info" data-full_div="#full-order-div<%= k %>"  data-single_div="#single-order-div<%= k %><%= ve %>"  data-count="<%= ve %>"  data-order_id="<%= order.id %>"  data-vendor_id="<%= vendor.vendor_id %>"  data-status_option_id="6" data-order_vendor_id="<%= vendor.order_vendor_id %>">Delivered</button>
                                                    <% } else { %>
                                                        
                                                <% } %> 
                                            </div>
                                        </div>
                                      
                                    </div>
                                </div>
                            <% }); %>
                        </div>   
                        <div class="col-md-3 pl-0">
                            <div class="card-box p-2">
                                <ul class="price_box_bottom m-0 pl-0 pt-1">
                                    <li class="d-flex align-items-center justify-content-between">
                                        <label class="m-0">Total</label>
                                        <span>$<%= order.total_amount %></span>
                                    </li>
                                    <li class="d-flex align-items-center justify-content-between">
                                        <label class="m-0">Loyalty Used</label>
                                        <span>$<%= order.loyalty_amount_saved %></span>
                                    </li>
                                    <li class="d-flex align-items-center justify-content-between">
                                        <label class="m-0">Tax</label>
                                        <span>$<%= order.taxable_amount %></span>
                                    </li>
                                    <li class="d-flex align-items-center justify-content-between">
                                        <label class="m-0">Delivery Fee</label>
                                        <span>$<%= order.total_delivery_fee %></span>
                                    </li>
                                    <li class="grand_total d-flex align-items-center justify-content-between">
                                        <label class="m-0">Payable </label>
                                        <span>$<%= order.payable_amount %></span>
                                    </li>
                                </ul>
                            </div>
                        </div> 
                    </div>
                </div>
            <% } %>
        <% }); %>
    </div>
    <% if(next_page_url) { %>
        <div class="row mt-4">
            <div class="col-md-4 offset-md-4 text-center">
                <button class="ladda-button btn btn-primary load-more-btn" dir="ltr" data-style="expand-left" data-url="<%= next_page_url%>" data-rel="<%= filter_order_status %>">
                    <span class="ladda-label">Load More</span>
                    <span class="ladda-spinner"></span>
                    <div class="ladda-progress" style="width: 0px;"></div>
                </button>
            </div>
        </div>
    <% } %>
</script>
<div class="container-fluid order-page">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="page-title">Orders</h4>
                <a class="return-btn" href="{{route('backend.order.returns',['Pending'])}}">
                    <b>Return Request  <sup class="total-items">({{$return_requests}})</sup>
                        <i class="fa fa-arrow-circle-right ml-1" aria-hidden="true"></i>
                    </b>
                </a>
            </div>
        </div>
    </div>    
    </div>
    <script type="text/template" id="no_order_template">
        <div class="error-msg"><p>You have not any order yet now.</p></div>
    </script>
    <div class="loader" id="order_list_order">
        <div class="spinner-border avatar-lg text-primary m-2" role="status"></div>
    </div>  
    <div class="row">
        <div class="col-sm-12 col-lg-12 tab-product pt-0">
            <ul class="nav nav-tabs nav-material" id="top-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pending_order-tab" data-toggle="tab" href="#pending_orders" role="tab" aria-selected="false" data-rel="pending_orders">
                        <i class="icofont icofont-man-in-glasses"></i>Pending Orders <sup class="total-items" id="pending-orders">({{$pending_order_count}})</sup>
                    </a>
                    <div class="material-border"></div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="active_orders_tab" data-toggle="tab"
                        href="#active_orders" role="tab" aria-selected="true" data-rel="active_orders">
                        <i class="icofont icofont-ui-home"></i>Active Orders <sup class="total-items" id="active-orders">({{$active_order_count}})</sup>
                    </a>
                    <div class="material-border"></div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="orders_history_tab" data-toggle="tab" href="#orders_history" role="tab" aria-selected="false" data-rel="orders_history">
                        <i class="icofont icofont-man-in-glasses"></i>Orders History <sup class="total-items"  id="history-orders">({{$past_order_count}})</sup>
                    </a>
                    <div class="material-border"></div>
                </li>
            </ul>
            <div class="tab-content nav-material" id="top-tabContent">
                <div class="tab-pane fade past-order show active" id="pending_orders" role="tabpanel"
                    aria-labelledby="pending_order-tab"></div>
                <div class="tab-pane fade" id="active_orders" role="tabpanel"
                    aria-labelledby="active_orders_tab"></div>
                <div class="tab-pane fade past-order" id="orders_history" role="tabpanel"
                    aria-labelledby="orders_history_tab"><div class="error-msg"><p>You have not any order yet now.</p></div></div>
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
        setTimeout(function(){ $("#pending_order-tab").trigger('click'); }, 500);
        $(document).on("click", ".load-more-btn",function() {
            $('#order_list_order').show();
            var url = $(this).data('url');
            var rel = $(this).data('rel');
            init(rel, url);
            $(this).remove();
        });
        $(".nav-link").click(function(){
            $('#order_list_order').show();
            var rel = $(this).data('rel');
            var url = "{{ route('orders.filter') }}";
            $(".tab-pane").html('');
            init(rel, url);
        });
        function init(filter_order_status, url){
            $.ajax({
                url: url,
                type: "POST",
                dataType: "JSON",
                data: {filter_order_status:filter_order_status},
                success: function(response) {
                    $('#order_list_order').hide();
                    if(response.status == 'Success'){
                        if(response.data.orders.data.length != 0){
                            let order_page_template = _.template($('#order_page_template').html());
                            $("#"+filter_order_status).append(order_page_template({orders: response.data.orders.data, next_page_url:response.data.orders.next_page_url , filter_order_status:filter_order_status}));
                        }else{
                            let no_order_template = _.template($('#no_order_template').html());
                            $("#"+filter_order_status).html(no_order_template({}));
                        }
                        $("#active-orders").html("("+response.data.active_orders+")");
                        $("#pending-orders").html("("+response.data.pending_orders+")");
                        $("#history-orders").html("("+response.data.orders_history+")");
                    }
                },
                error: function (data) {

                },
            });
        }


        // update status 
        $(document).on("click", ".update-status",function() {
         if(confirm("Are you Sure?")){
            let that = $(this);
            var count = that.data("count");
            var full_div = that.data("full_div");
            var single_div = that.data("single_div");
            var status_option_id = that.data("status_option_id");
            var status_option_id_next = status_option_id + 1;
            var order_vendor_id = that.data("order_vendor_id");
            var order_id = that.data("order_id");
            var vendor_id = that.data("vendor_id");
            var count = that.data("count");
    
            $.ajax({
                url: "{{ route('order.changeStatus') }}",
                type: "POST",
                data: {
                    order_id: order_id,
                    vendor_id: vendor_id,
                    "_token": "{{ csrf_token() }}",
                    status_option_id: status_option_id,
                    order_vendor_id: order_vendor_id,
                },
                success: function(response) {
                      
                    if(status_option_id == 4 || status_option_id == 5){
                        if(status_option_id == 4)
                        var next_status = 'Out For Delivery';
                        else
                        var next_status = 'Delivered';
                        that.replaceWith("<button class='update-status btn-warning' data-full_div='"+ full_div +"' data-single_div='"+ single_div +"'  data-count='"+ count +"'  data-order_id='"+ order_id +"'  data-vendor_id='"+ vendor_id +"'  data-status_option_id='"+ status_option_id_next +"' data-order_vendor_id="+ order_vendor_id +">"+ next_status +"</button>");
                        return false;
                    }else{
                            if(count == 0){
                            $(full_div).slideUp(1000, function() {
                            $(this).remove();
                            });
                    
                            }else{
                            $(single_div).slideUp(1000, function() {
                            $(this).remove();
                            });
                    
                            }
                    }

                    

                    if(status_option_id == 2)
                    $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                   // location.reload();
                },
            });
        }
        });
    });
</script>


@endsection
