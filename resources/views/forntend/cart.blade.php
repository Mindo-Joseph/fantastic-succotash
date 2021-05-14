@extends('layouts.store', ['title' => 'Product'])

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
    <div class="collection-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-sm-3 collection-filter">
                    <h2>Cart Products</h2>
                </div>
                <div class="col-lg-9 col-sm-12 col-xs-12">
                    <div class="container-fluid">
                        <div class="row">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="cart-section section-b-space">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <table class="table cart-table table-responsive-xs ">
                    <thead>
                        <tr class="table-head">
                            <th scope="col">vendor</th>
                            <th scope="col">image</th>
                            <th scope="col">product name</th>
                            <th scope="col">price</th>
                            <th scope="col">quantity</th>
                            <th scope="col">action</th>
                            <th scope="col">product total</th>
                            <th scope="col">tax</th>
                            <th scope="col">payable amount</th>
                        </tr>
                    </thead>
                    <tbody class="shopping-cart1">
                        @php
                            $total_cart_amt = 0;
                        @endphp
                        @foreach($cartData->products as $product)
                            @foreach($product['vendor_products'] as $vendor_product)
                            @php
                                $total_cart_amt = $product['payable_amount'];
                            @endphp
                            <tr>
                                <td>
                                    <h4 class="td-color">{{$product['vendor']['name']}}</h4>
                                </td>
                                <td>
                                    <a href="#"><img src="{{asset('assets/images/products/product-1.png')}}" alt=""></a>
                                </td>
                                <td>
                                    <a href="#">{{$vendor_product['product']['sku']}}</a>
                                </td>
                                <td>
                                    <h2>${{$product['product_total_amount'] / $vendor_product['quantity']}}</h2>
                                </td>
                                <td>
                                    <div class="qty-box">
                                        <div class="input-group">
                                            <input type="number" name="quantity" class="form-control input-number" value="{{$vendor_product['quantity']}}">
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="#" class="icon">
                                        <i class="ti-close"></i>
                                    </a>
                                </td>
                                <td>
                                    <h2 class="td-color">${{$product['product_total_amount']}}</h2>
                                </td>
                                <td>
                                    <h2 class="td-color">${{$product['taxable_amount']}}</h2>
                                </td>
                                <td>
                                    <h2 class="td-color">${{$product['payable_amount']}}</h2>
                                </td>
                            </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
                <table class="table cart-table table-responsive-md shopping-cart-footer">
                    <tfoot>
                        <tr>
                            <td>Total :</td>
                            <td>
                                <h2>${{ $total_cart_amt }}</h2>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="row cart-buttons">
            <div class="col-6"><a href="{{url('/')}}" class="btn btn-solid">continue shopping</a></div>
            <div class="col-6"><a class="btn btn-solid checkout" style="color: white;" onMouseOver="this.style.color='black'" onMouseOut="this.style.color='white'" href="{{url('user/checkout')}}">check out</a></div>
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
                if (data.res == "null") {
                    $(".shopping-cart1").html(data.html);
                } else {
                    console.log(data);
                    var products = data.products;
                    for (i = 0; i < products.length; i++) {
                        var vendor_products = products[i].vendor_products;
                        for (j = 0; j < vendor_products.length; j=j+2) {
                            console.log(products[i].vendor.name);
                            console.log(vendor_products[j].pvariant.media[0].image.path.proxy_url + '200/200' + vendor_products[j].pvariant.media[0].image.path.image_path);
                            console.log(vendor_products[j].product.sku);
                            console.log(vendor_products[j].pvariant.quantity_price);
                            console.log(vendor_products[j].quantity);
                            
                        }
                    }
                }
            },
            error: function(data) {
                console.log('Error Found : ' + data);
            }
        });
    });
    // $(document).on('change', '.quant', function() {
    //     updateQuantity($(this).attr("data-id"), $(this).val());
    // });


    // function updateQuantity(cp_id, quantity) {
    //     ajaxCall = $.ajax({
    //         type: "post",
    //         dataType: "json",
    //         url: "{{ route('updateQuantity') }}",
    //         data: {
    //             "_token": "{{ csrf_token() }}",
    //             "cartproduct_id": cp_id,
    //             "quantity": quantity,
    //         },
    //         success: function(response) {
    //             console.log(total1);
    //             cartHeader();
    //             var latest_price = parseInt($("#price" + cp_id).html().substring(1)) * parseInt(quantity);
    //             total1 = total1 - parseInt($("#h2" + cp_id).html().substring(1));
    //             total1 = total1 + latest_price;
    //             $("#h2" + cp_id).html("$" + latest_price);
    //             $("#total").html("$" + total1);
    //             $("#totalCart").html("$" + total1);
    //         },
    //         error: function(data) {

    //         },
    //     });
    // }

    // $(document).on('click', '.closed', function() {
    //     var total2 = total1 - parseInt($("#h2" + $(this).attr("data-id")).html().substring(1));
    //     console.log(total2);
    //     var idd = $(this).attr("data-id");
    //     ajaxCall = $.ajax({
    //         type: "post",
    //         dataType: "json",
    //         url: "{{ route('deleteCartProduct') }}",
    //         data: {
    //             "_token": "{{ csrf_token() }}",
    //             "cartproduct_id": $(this).attr("data-id"),
    //         },
    //         success: function(response) {
    //             console.log(response);
    //             total1 = total2;
    //             $("#total").html("$" + total1);
    //             $("#totalCart").html("$" + total1);
    //             $("#tbody" + idd).remove();
    //             cartHeader();
    //         },
    //         error: function(data) {

    //         },
    //     });

    // });

    // $(document).on('click', '.checkout', function() {
    //     console.log("checkout");
    //     ajaxCall = $.ajax({
    //         type: "get",
    //         dataType: "json",
    //         url: "{{ route('checkUserLogin') }}",
    //         success: function(response) {
    //             if(response == 'no'){
    //                 window.location.href = "{{ route('customer.login') }}";
    //             }
    //             else if(response == 'yes'){
    //                 window.location.href = "{{ route('user.checkout') }}";
    //             }
    //         },
    //         error: function(data) {
    //         },
    //     });
    // });
</script>
@endsection