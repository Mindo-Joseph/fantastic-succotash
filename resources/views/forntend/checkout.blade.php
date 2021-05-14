@extends('layouts.store', ['title' => 'Checkout'])
@section('css')
<style type="text/css">
    .main-menu .brand-logo {
        display: inline-block;
        padding-top: 20px;
        padding-bottom: 20px;
    }
</style>
@endsection
@section('content')
<header>
    <div class="mobile-fix-option"></div>
    @include('layouts.store/left-sidebar')
</header>
<style type="text/css">
    .productVariants .firstChild {
        min-width: 150px;
        text-align: left !important;
        border-radius: 0% !important;
        margin-right: 10px;
        cursor: default;
        border: none !important;
    }

    .product-right .color-variant li,
    .productVariants .otherChild {
        height: 35px;
        width: 35px;
        border-radius: 50%;
        margin-right: 10px;
        cursor: pointer;
        border: 1px solid #f7f7f7;
        text-align: center;
    }

    .productVariants .otherSize {
        height: auto !important;
        width: auto !important;
        border: none !important;
        border-radius: 0%;
    }

    .product-right .size-box ul li.active {
        background-color: inherit;
    }
</style>

<section class="section-b-space">
    <div class="container">
        <div class="checkout-page">
            <div class="checkout-form">
                <form method="post" action="{{route('user.placeorder')}}"> @csrf
                    <div class="row">
                        <div class="col-lg-6 col-sm-12 col-xs-12">
                            <div class="checkout-title">
                                <h3>Billing Details</h3>
                            </div>
                            <div class="row check-out">
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <div class="field-label">First Name</div>
                                    <input type="text" name="first_name" id="first_name" value="" placeholder="">
                                </div>
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <div class="field-label">Last Name</div>
                                    <input type="text" name="last_name" id="last_name" value="" placeholder="">
                                </div>
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <div class="field-label">Phone</div>
                                    <input type="text" name="phone" id="phone" value="" placeholder="">
                                </div>
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <div class="field-label">Email Address</div>
                                    <input type="text" name="email_address" id="email_address" value="" placeholder="">
                                </div>
                                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                    <div class="field-label">Country</div>
                                    <select class="countries">
                                        
                                    </select>
                                </div>
                                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                    <div class="field-label">Address</div>
                                    <input type="hidden" name="address_id" id="address_id" value="" >
                                    <input type="text" name="address" id="address" value="" placeholder="Street address">
                                </div>
                                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                    <div class="field-label">Town/City</div>
                                    <input type="text" name="city" id="city" value="" placeholder="">
                                </div>
                                <div class="form-group col-md-12 col-sm-6 col-xs-12">
                                    <div class="field-label">State / County</div>
                                    <input type="text" name="state" id="state" value="" placeholder="">
                                </div>
                                <div class="form-group col-md-12 col-sm-6 col-xs-12">
                                    <div class="field-label">Postal Code</div>
                                    <input type="text" name="pincode" id="pincode" value="" placeholder="">
                                </div>
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="checkbox" name="shipping-option" id="account-option"> &ensp;
                                    <label for="account-option">Create An Account?</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12 col-xs-12">
                            <div class="checkout-details">
                                <div class="order-box">
                                    <div class="title-box">
                                        <div>Product <span>Total</span></div>
                                    </div>
                                    <script type="text/template" id="checkout_products_template">
                                        <% _.each(products, function(product, key){%>
                                            <li>Pink Slim Shirt × 1 <span>$25.10</span></li>
                                        <% }); %>
                                    </script>
                                    <ul class="qty checkout-products" id="checkout_products_main_div">
                                        
                                    </ul>
                                    <ul class="sub-total">
                                        <li>Subtotal <span class="count checkout-total"></span></li>
                                        <li>Shipping
                                            <div class="shipping">
                                                <div class="shopping-option">
                                                    <input type="checkbox" name="free-shipping" id="free-shipping">
                                                    <label for="free-shipping">Free Shipping</label>
                                                </div>
                                                <div class="shopping-option">
                                                    <input type="checkbox" name="local-pickup" id="local-pickup">
                                                    <label for="local-pickup">Local Pickup</label>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                    <ul class="total">
                                        <li>Total <span class="count">$620.00</span></li>
                                        <input type="hidden" name="total_amount"  value="620" placeholder="">

                                    </ul>
                                </div>
                                <div class="payment-box">
                                    <div class="upper-box">
                                        <div class="payment-options">
                                            <ul>
                                                <li>
                                                    <div class="radio-option">
                                                        <input type="radio" name="payment-group" value="1" id="payment-1" checked="checked">
                                                        <label for="payment-1">Check Payments<span class="small-text">Please send a check to Store
                                                                Name, Store Street, Store Town, Store State /
                                                                County, Store Postcode.</span></label>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="radio-option">
                                                        <input type="radio" name="payment-group" value="2" id="payment-2">
                                                        <label for="payment-2">Cash On Delivery<span class="small-text">Please send a check to Store
                                                                Name, Store Street, Store Town, Store State /
                                                                County, Store Postcode.</span></label>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="radio-option paypal">
                                                        <input type="radio" name="payment-group" value="3" id="payment-3">
                                                        <label for="payment-3">PayPal<span class="image"><img src="{{asset('front-assets/images/paypal.png')}}" alt=""></span></label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="text-right"><button type="submit" class="btn-solid btn">Place Order</button></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>


@endsection

@section('script')
<script>
    var total1 = 0;
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "get",
            url: "{{ route('getCartProducts') }}",
            data: '',
            dataType: 'json',
            success: function(data) {
                console.log(data);
                if (data.res == "null") {
                    $(".checkout-products").html(data.html);
                } else {
                    console.log(data.products);
                    // var price = JSON.parse(data.price);
                    // var images = JSON.parse(data.image);
                    // var variants = JSON.parse(data.variants);
                    var products = data.products;
                    // var quantity = JSON.parse(data.quantity);
                    // var cp_id = JSON.parse(data.cart_products);
                    let checkout_products_template = _.template($('#checkout_products_template').html());
                    if(products.length > 0){
                        $("#checkout_products_main_div").html(checkout_products_template({products:products}));
                    }
                    // for (i = 0; i < products.length; i++) {
                    //     total1 += parseInt(price[i]) * parseInt(quantity[i]);
                    //     $(".checkout-products").append("<li>" + products[i] + " × " + quantity[i] + " <span>$" + parseInt(price[i]) * parseInt(quantity[i]) + "</span></li>");
                    // }
                    // $(".checkout-total").append("$" + total1);
                }
            },
            error: function(data) {
                console.log('Error Found : ' + data);
            }
        });
    });

    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "get",
            url: "{{ route('getUserAddress') }}",
            data: '',
            dataType: 'json',
            success: function(data) {
                $("#first_name").val("{{Auth::user()->name}}");
                $("#phone").val("{{Auth::user()->phone_number}}");
                $("#email_address").val("{{Auth::user()->email}}");
                // $("#address").val(data.address.address);
                // $("#address_id").val(data.address != null? data.address.id : 0);
                // $("#city").val(data.address.city);
                // $("#state").val(data.address.state);
                // $("#pincode").val(data.address.pincode);
                $(".countries").append("<option selected value='"+data.country.id+"'>"+data.country.name+"</option>");
            },
            error: function(data) {
                console.log('Error Found : ' + data);
            }
        });
    });

</script>

@endsection