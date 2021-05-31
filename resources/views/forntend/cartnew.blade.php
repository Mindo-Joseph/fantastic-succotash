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
    @include('layouts.store/left-sidebar')
</header>
<script type="text/template" id="address_template">
    <div class="col-md-12">
        <div class="delivery_box">
            <label class="radio m-0"><%= address.address %> <%= address.city %> <%= address.state %> <%= address.pincode %>
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
<script type="text/template" id="cart_template">
    <% _.each(cart_details.products, function(product, key){%>
        <tbody id="tbody_|<%= product.vendor.id %>">
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
                        <h4><%= vendor_product.product.sku %></h4>
                        <label><span>Size:</span> Regular</label>
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
                    <td class="text-center">
                        <div class="items-price mb-3">$<%= vendor_product.pvariant.quantity_price %></div>
                    </td>
                    <td class="text-right">
                        <a  class="action-icon d-block mb-3 remove_product_via_cart" data-product="<%= vendor_product.id %>" data-vendor_id="<%= vendor_product.vendor_id %>">
                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                        </a>
                    </td>
                </tr>
                <!-- <tr>
                    <td colspan="5" class="border_0 p-0 border-0">
                        <table class="add_on_items w-100">
                            <thead>
                                <tr>
                                    <h6 class="m-0 pl-3"><b>Add Ons</b></h6>
                                </tr>
                            </thead>    
                            <tbody>
                                <tr class="border_0 padding-top">
                                    <td class="items-details text-left">
                                        <p class="m-0">Spicy Dip</p>
                                    </td>
                                    <td>
                                        <div class="extra-items-price">$5.00</div>
                                    </td>
                                    <td>
                                    </td>
                                    
                                    <td class="text-center">
                                        <div class="extra-items-price">$5.00</div>
                                    </td>
                                    <td class="text-right">
                                        <a href="#" class="action-icon d-block">
                                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr> -->
            <% }); %>
            <tr>
                <td colspan="2">
                    <div class="coupon_box d-flex align-items-center">
                        <img src="{{ asset('assets/images/discount_icon.svg') }}">
                        <input class="form-control" type="text" placeholder="Enter Coupon Code">
                        <button class="btn btn-outline-info">Apply</button>
                    </div>
                </td> 
                <!-- <td>
                    <label class="d-block txt-13">Delivery Fee</label>
                    <p class="total_amt m-0">Amount</p>
                </td> -->
                <td colspan="2"></td>
                <td class="text-center">
                    <!-- <label class="d-block  txt-13">$5.00</label> -->
                    <p class="total_amt m-0">$ <%= product.payable_amount %></p>
                </td>
                <td></td>
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
                <p class="m-1"><label class="m-0">CGST 7.5%</label><span class="pl-4">$10.00</span></p>
                <p class="m-0"><label class="m-0">CGST 7.5%</label><span class="pl-4">$10.00</span></p>
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
<div class="container" id="cart_main_page">
    @if($cartData)
        @if($cartData->products)
        <form method="post" action="{{route('user.placeorder')}}">
            @csrf
            <div class="row card-box">
                <div class="col-4 left_box">
                    <div class="row">
                    <div class="col-12 mb-2">
                        <h4 class="page-title">Delivery Address</h4>
                    </div>
                    <span class="text-danger" id="address_error"></span>
                </div>
                <div class="row mb-4" id="address_template_main_div">
                    @forelse($addresses as $address)
                        <div class="col-md-12">
                            <div class="delivery_box">
                                <label class="radio m-0">{{$address->address}}, {{$address->state}} {{$address->pincode}} 
                                    <input type="radio" checked="checked" name="address_id" value="{{$address->id}}">
                                    <span class="checkround"></span>
                                </label>
                            </div>
                        </div>
                    @empty

                    @endforelse
                </div>
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
                                        <option value="{{$co->id}}" selected>{{$co->name}}</option>
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
                
                <div class="col-8">
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-striped" id="cart_table">
                            
                        </table>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-lg-3 col-md-4">
                    <a class="btn btn-solid" href="{{url('/')}}">Continue Shopping</a>
                </div>
                <div class="offset-lg-6 offset-md-4 col-lg-3 col-md-4 text-md-right">
                    <button class="btn btn-solid" type="submit">Place Order</button>
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
<script type="text/javascript">
    var user_store_address_url = "{{url('user/store')}}";
    var update_qty_url = "{{ url('product/updateCartQuantity') }}";
</script>
@endsection