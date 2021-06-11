@extends('layouts.store', ['title' => 'Product'])
@section('content')
<style type="text/css">
.swal2-title {
  margin: 0px;
  font-size: 26px;
  font-weight: 400;
  margin-bottom: 28px;
}
</style>
<header>
    <div class="mobile-fix-option"></div>
    @include('layouts.store.left-sidebar')
</header>
<script type="text/template" id="address_template">
    <div class="col-md-12">
        <div class="delivery_box">
            <label class="radio m-0"><%= address.address %> <%= address.city %><%= address.state %> <%= address.pincode %>
                <input type="radio" checked="checked" name="address_id" value="<%= address.id %>">
                <span class="checkround"></span>
            </label>
        </div>
    </div>
</script>
<script type="text/template" id="empty_cart_template">
    <div class="row mt-2 mb-4 mb-lg-5">
        <div class="col-12 text-center">
            <div class="cart_img_outer">
                <img src="{{asset('front-assets/images/empty_cart.png')}}">
            </div>
            <h3>Your cart is empty!</h3>
            <p>Add items to it now.</p>
            <a class="btn btn-solid" href="{{url('/')}}">Shop Now</a>
        </div>
    </div>
</script>
<h1></h1>
<script type="text/template" id="cart_template">
    <% _.each(cart_details.products, function(product, key){%>
        <tbody id="tbody_<%= product.vendor.id %>">
            <tr>
                <td colspan="6"><%= product.vendor.name %></td>
            </tr>
            <% _.each(product.vendor_products, function(vendor_product, vp){%>
                <tr class="padding-bottom vendor_products_tr" id="tr_vendor_products_<%= vendor_product.id %>">
                    <td style="width:100px" <%= vendor_product.length > 0 ? 'rowspan=2' : '' %>>
                        <div class="product-img pb-2">
                           <% if(vendor_product.pvariant.media_one) { %>
                                <img src="<%= vendor_product.pvariant.media_one.image.path.proxy_url %>100/70<%= vendor_product.pvariant.media_one.image.path.image_path %>" alt="">
                            <% } %>
                        </div>
                    </td>
                    <td class="items-details text-left">
                        <h4><%= vendor_product.product.translation_one ? vendor_product.product.translation_one.title :  vendor_product.product.sku %></h4>
                        <% _.each(vendor_product.pvariant.vset, function(vset, vs){%>
                            <% if(vset.variant_detail.trans) { %>
                                <label><span><%= vset.variant_detail.trans.title %>:</span> <%= vset.option_data.trans.title %></label>
                            <% } %>
                        <% }); %>
                    </td>
                    <td>
                        <div class="items-price mb-3">$<%= vendor_product.pvariant.price %></div>
                    </td>
                    <td>
                        <div class="number">
                            <span class="minus qty-minus" data-id="<%= vendor_product.id %>" data-base_price=" <%= vendor_product.pvariant.price %>">
                                <i class="fa fa-minus" aria-hidden="true"></i>
                            </span>
                            <input style="text-align:center;width: 40px;margin:auto;height: 24px;padding-bottom: 3px;" placeholder="1" type="number" value="<%= vendor_product.quantity %>" class="input-number" step="0.01" id="quantity_<%= vendor_product.id %>">
                            <span class="plus qty-plus" data-id="<%= vendor_product.id %>" data-base_price=" <%= vendor_product.pvariant.price %>">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </span>
                        </div>
                    </td>
                    <td class="text-right">
                        <a  class="action-icon d-block mb-3 remove_product_via_cart" data-product="<%= vendor_product.id %>" data-vendor_id="<%= vendor_product.vendor_id %>">
                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                        </a>
                    </td>
                    <td class="text-right pl-lg-2">
                        <div class="items-price mb-3">$<%= vendor_product.pvariant.quantity_price %></div>
                    </td>
                </tr>
                <% if(vendor_product.addon.length != 0) { %>
                    <tr>
                         <td colspan="6" class="border_0 p-0 border-0">
                           <h6 class="m-0 pl-0"><b>Add Ons</b></h6>
                        </td>
                    </tr>
                    <% _.each(vendor_product.addon, function(addon, ad){%>
                    <tr>
                         
                            <td></td>
                            <td class="items-details text-left">
                                <p class="m-0"><%= addon.set.title %></p>
                            </td>
                            <td>
                                <div class="extra-items-price">$<%= addon.option.price_in_cart %></div>
                            </td>
                            <td></td>
                            <td></td>
                            <td class="text-right pl-lg-2">
                                <div class="extra-items-price">$<%= addon.option.quantity_price %></div>
                            </td>
                    </tr> 

                        <% }); %>
                <% } %>
            <% }); %>
            <tr>
                <td colspan="2">
                    <div class="d-flex w-100 ">
                    <div class="coupon_box d-flex w-50 align-items-center">
                        <img src="{{ asset('assets/images/discount_icon.svg') }}">
                        <label class="mb-0 ml-2"><%= product.coupon ? product.coupon.promo.name : '' %></label>
                        
                    </div>
                    <% if(!product.coupon) { %>
                            <a class="btn btn-outline-info promo_code_list_btn" data-vendor_id="<%= product.vendor.id %>" data-cart_id="<%= cart_details.id %>" data-amount="<%= product.product_total_amount %>">Apply</a>
                        <% }else{ %>
                        <i class="fa fa-times ml-4 remove_promo_code_btn" data-coupon_id="<%= product.coupon ? product.coupon.promo.id : '' %>" data-cart_id="<%= cart_details.id %>"></i>
                        <% } %>
                        </div>
                </td> 
                <td colspan="3"></td>
                <td class="text-right pl-lg-2">
                    <p class="total_amt m-0">$ <%= product.product_total_amount %></p>
                </td>
            </tr>
        </tbody>
    <% }); %>
    <tfoot>
        <tr>
            <td colspan="3"></td>
            <td class="pr-0">
               <p class="mb-1"></p> Sub Total  
               <!-- <p class="mb-1"></p> Wallet  -->
               <!-- <p class="mb-1"></p> Loyalty (500 pts)  -->
               <hr class="my-2">
               <!-- <p class="total_amt m-0">Total Amount</p> -->
            </td>
            <td class="text-right pl-0" colspan="3">
               <p class="mb-1"></p> $<%= cart_details.gross_amount %>
               <!-- <p class="mb-1"></p> -$60.00 -->
               <!-- <p class="mb-1"></p> -$10.00 -->
               <hr class="my-2">
               <!-- <p class="total_amt m-0">$<%= cart_details.gross_amount %></p> -->
            </td>
        </tr>
        <tr class="border_0">
            <td colspan="3"></td>
            <td>Tax</td>
            <td class="text-right" colspan="2">
                <p class="m-1"><span class="pl-4">$<%= cart_details.total_taxable_amount %></span></p>
            </td>
        </tr>
        <tr class="border_0">
            <td colspan="3"></td>
            <td colspan="2" class="pt-0 pr-0">
                <hr class="mt-0 mb-2">
                <p class="total_amt m-0">Amount Payable</p>
            </td>
            <td colspan="2" class="pt-0 pl-0 text-right">
                <hr class="mt-0 mb-2">
                <p class="total_amt m-0">$<%= cart_details.total_payable_amount %></p>
            </td>
        </tr>
    </tfoot>
</script>
<script type="text/template" id="promo_code_template">
    <% _.each(promo_codes, function(promo_code, key){%>
        <div class="col-lg-6">
            <div class="coupon-code mt-0">
                <div class="p-2">
                    <img src="<%= promo_code.image.proxy_url %>100/35<%= promo_code.image.image_path %>" alt="">
                    <h6 class="mt-0"><%= promo_code.title %></h6>
                </div>
                <hr class="m-0">
                <div class="code-outer p-2 text-uppercase d-flex align-items-center justify-content-between">
                    <label class="m-0"><%= promo_code.name %></label>
                    <a class="btn btn-solid apply_promo_code_btn" data-vendor_id="<%= vendor_id %>" data-cart_id="<%= cart_id %>" data-coupon_id="<%= promo_code.id %>" data-amount="<%= amount %>" style="cursor: pointer;">Apply</a>
                </div>
                <hr class="m-0">
                <div class="offer-text p-2">
                    <p class="m-0"><%= promo_code.short_desc %></p>
                </div>
            </div>
        </div>
    <% }); %>
</script>

<script type="text/template" id="no_promo_code_template">
    <div class="col-12 no-more-coupon text-center">
        <p>No other coupons available.</p>
    </div>
</script>
<div class="container" id="cart_main_page">
    @if($cartData)
        @if($cartData->products)
            <form method="post" action="{{route('user.placeorder')}}" id="placeorder_form">
                @csrf
                <div class="card-box">
                    <div class="row">
                        <div class="col-4 left_box">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <h4 class="page-title">Delivery Address</h4>
                                    <span class="text-danger hide" id="address_error"></span>
                                </div>
                            </div>
                            <div class="row mb-4" id="address_template_main_div">
                                @forelse($addresses as $k => $address)
                                    <div class="col-md-12">
                                        <div class="delivery_box">
                                            <label class="radio m-0">{{$address->address}}, {{$address->state}} {{$address->pincode}} 
                                                @if($address->is_primary)
                                                    <input type="radio" name="address_id" value="{{$address->id}}"  checked="checked">
                                                @else
                                                    <input type="radio" name="address_id" value="{{$address->id}}"  {{$k == 0? 'checked="checked""' : '' }} >
                                                @endif
                                                <span class="checkround"></span>
                                            </label>
                                        </div>
                                    </div>
                                @empty
                                    <div class="address-no-found">
                                        <p>Address not available.</p>
                                    </div>
                                @endforelse
                            </div>
                            <div class="row">
                                <div class="col-12 mt-4 text-center" id="add_new_address_btn">
                                    <a class="btn btn-solid w-100 m-auto" >
                                        <i class="fa fa-plus mr-1" aria-hidden="true"></i> Add New Address
                                    </a>
                                </div>
                                <div class="col-md-12" id="add_new_address_form" style="display:none;">
                                    <div class="theme-card w-100">
                                        <div class="form-row no-gutters">
                                            <div class="col-12">
                                                <label for="type">Address Type</label>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="delivery_box pt-0 pl-0  pb-3">
                                                    <label class="radio m-0">Home 
                                                        <input type="radio" checked="checked" name="address_type" value="1">
                                                        <span class="checkround"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                            <div class="delivery_box pt-0 pl-0  pb-3">
                                                <label class="radio m-0">Office 
                                                    <input type="radio" name="address_type" value="2">
                                                    <span class="checkround"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="delivery_box pt-0 pl-0  pb-3">
                                                <label class="radio m-0">Others
                                                    <input type="radio" name="address_type" value="3">
                                                    <span class="checkround"></span>
                                                </label>
                                            </div>
                                        </div>
                                        </div>
                                        <input type="hidden" id="latitude">
                                        <input type="hidden" id="longitude">
                                        <div class="form-row">
                                            <div class="col-md-12 mb-3">
                                                <label for="address">Address</label>
                                                <div class="input-group">
                                                  <input type="text" class="form-control" id="address" placeholder="Address" aria-label="Recipient's Address" aria-describedby="button-addon2">
                                                  <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary" type="button" id="button-addon2">
                                                        <i class="fa fa-map-marker" aria-hidden="true"></i>
                                                    </button>
                                                  </div>
                                                </div>
                                                <span class="text-danger" id="address_error"></span>
                                            </div>
                                        </div>
                                        <div class="form-row mb-3">
                                            <div class="col-md-6 mb-3">
                                                <label for="city">City</label>
                                                <input type="text" class="form-control" id="city" placeholder="City" value="">
                                                <span class="text-danger" id="city_error"></span>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="state">State</label>
                                                <input type="text" class="form-control" id="state" placeholder="State" value="">
                                                <span class="text-danger" id="state_error"></span>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="country">Country</label>
                                                <select name="country" id="country" class="form-control">
                                                    @foreach($countries as $co)
                                                        <option value="{{$co->id}}">{{$co->name}}</option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger" id="country_error"></span>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="pincode">Pincode</label>
                                                <input type="text" class="form-control" id="pincode" placeholder="Pincode" value="">
                                                <span class="text-danger" id="pincode_error"></span>
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                <button type="button" class="btn btn-solid" id="save_address">Save Address</button>
                                                <button type="button" class="btn btn-solid black-btn" id="cancel_save_address_btn">Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="table-responsive">
                                <table class="table table-centered table-nowrap table-striped" id="cart_table"></table>
                            </div>
                        </div>
                    </div>
                     <div class="row mb-4">
                        <div class="col-lg-3 col-md-4">
                            <a class="btn btn-solid" href="{{ url('/') }}">Continue Shopping</a>
                        </div>
                        <div class="offset-lg-6 offset-md-4 col-lg-3 col-md-4 text-md-right">
                            <button id="order_palced_btn" class="btn btn-solid" type="button" {{$addresses->count() == 0 ? 'disabled': ''}} >Place Order</button>
                        </div>
                    </div>
                </div>
               
            </form>
        @endif
    @else
        <div class="row mt-2 mb-4 mb-lg-5">
            <div class="col-12 text-center">
                <div class="cart_img_outer">
                    <img src="{{asset('front-assets/images/empty_cart.png')}}">
                </div>
                <h3>Your cart is empty!</h3>
                <p>Add items to it now.</p>
                <a class="btn btn-solid" href="{{url('/')}}">Shop Now</a>
            </div>
        </div>
    @endif
</div>
<div class="modal fade refferal_modal" id="refferal-modal" tabindex="-1" aria-labelledby="refferal-modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="refferal-modalLabel">Apply Coupon Code</h5>
        <button type="button" class="close top_right" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body mt-0 pt-0">
        <div class="coupon-box">
            <div class="row" id="promo_code_list_main_div">
                
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade remove-item-modal" id="remove_item_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="remove_itemLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header pb-0">
        <h5 class="modal-title" id="remove_itemLabel">Remove Item</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="vendor_id" value="">
        <input type="hidden" id="cartproduct_id" value="">
        <h6 class="m-0">Are you sure you want to remove this item ?</h6>
      </div>
      <div class="modal-footer flex-nowrap justify-content-center align-items-center">
        <button type="button" class="btn btn-solid black-btn" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-solid" id="remove_product_button">Remove</button>
      </div>
    </div>
  </div>
</div>
<script type="text/template" id="payment_method_template">
    <% _.each(payment_options, function(payment_option, k){%>
        <a class="nav-link <%= k == 0 ? 'show': ''%>" id="v-pills-<%= payment_option.slug %>-tab" data-toggle="pill" href="#v-pills-<%= payment_option.slug %>" role="tab" aria-controls="v-pills-wallet" aria-selected="true"><%= payment_option.title %></a>
    <% }); %>
</script>
<script type="text/template" id="payment_method_tab_pane_template">
    <% _.each(payment_options, function(payment_option, k){%>
        <div class="tab-pane fade <%= k == 0 ? 'active show': ''%>" id="v-pills-<%= payment_option.slug %>" role="tabpanel" aria-labelledby="v-pills-<%= payment_option.slug %>-tab">
            <form method="POST" action="" id="stripe-payment-form">
                @csrf
                @method('POST')
                <div class="payment_resp" role="alert"></div>
                <div class="form_fields">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row form-group">
                                <div class="col-sm-8">
                                    <label for="">Amount</label>
                                    <input class="form-control" name="amount" type="text" value="0.5">
                                </div>                                                    
                            </div>
                            <% if(payment_option.slug == 'stripe') { %>
                                <div id="stripe-card-element"></div>
                            <% } %>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-md-12 text-md-right">
                            <button type="button" class="btn btn-solid" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-solid ml-1 payment_btn">Continue to Pay</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    <% }); %>
</script>
<div class="modal fade" id="proceed_to_pay_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="pay-billLabel">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-body p-0">
            <div class="row no-gutters pr-3">
               <div class="col-4">
                  <div class="nav flex-column nav-pills" id="v_pills_tab" role="tablist" aria-orientation="vertical"></div>
               </div>
               <div class="col-8">
                  <div class="tab-content-box pl-3">
                     <h5 class="modal-title pt-4" id="pay-billLabel">Total Amount</h5>
                     <button type="button" class="close top_right" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">×</span>
                     </button>
                     <div class="tab-content h-100" id="v_pills_tabContent">
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<script src="https://js.stripe.com/v3/"></script>
<script type="text/javascript">
    var user_store_address_url = "{{route('address.store')}}";
    var promo_code_remove_url = "{{ route('remove.promocode') }}";
    var update_qty_url = "{{ url('product/updateCartQuantity') }}";
    var promocode_list_url = "{{ route('verify.promocode.list') }}";
    var payment_option_list_url = "{{route('payment.option.list')}}";
    var apply_promocode_coupon_url = "{{ route('verify.promocode') }}";
    $( document ).ready(function() {
        let address_checked = $("input:radio[name='address_id']").is(":checked");
        if(address_checked){
            $('#order_palced_btn').prop('disabled', false);
        }else{
            $('#order_palced_btn').prop('disabled', true);
        }
        $("input:radio[name='address_id']").change(function() {
            if($(this).val()){
                $('#order_palced_btn').prop('disabled', false);
            }
        });
        $("form").submit(function(e){
            let address_id = $("input:radio[name='address_id']").is(":checked");
            if(!address_id){
                alert('Address field required.');
                return false;
            }
        });
    });
</script>
@endsection