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

<div class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <div class="page-title">
                    <h2>cart</h2>
                </div>
            </div>
            <div class="col-sm-6">
                <nav aria-label="breadcrumb" class="theme-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                        <li class="breadcrumb-item active">cart</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<section class="cart-section section-b-space">
    <div class="container">
        @if($cartData)
            <div class="row">
                <div class="col-sm-12 table-responsive-xs">
                    <table class="table cart-table">
                        <thead>
                            <tr class="table-head">
                                <th scope="col">Vendor</th>
                                <th scope="col">Image</th>
                                <th scope="col">Name</th>
                                <th scope="col">Price</th>
                                <th scope="col">quantity</th>
                                <!-- <th scope="col">Tax Amt.</th> -->
                                <th scope="col">Total</th>
                                <th scope="col">action</th>
                            </tr>
                        </thead>
                        <tbody class="shopping-cart1">
                            @php
                                $total_cart_amt = 0;
                            @endphp
                            @foreach($cartData->products as $product)
                                @foreach($product['vendor_products'] as $vendor_product)
                                @php
                                    $total_cart_amt += $product['payable_amount'];
                                @endphp
                                <tr id="shopping_cart1_{{$vendor_product['id']}}">
                                    <td>
                                        <h4 class="td-color">{{$product['vendor']['name']}}</h4>
                                    </td>
                                    <td>
                                        @if($vendor_product['pvariant']['media'])
                                        <a href="#">
                                            <img src="{{$vendor_product['pvariant']['media'][0]['image']['path']['proxy_url'].'66/90'.$vendor_product['pvariant']['media'][0]['image']['path']['image_path']}}" alt="">
                                        </a>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="#">{{$vendor_product['product']['sku']}}</a>
                                    </td>
                                    <td>
                                        <h2>${{$vendor_product['pvariant']['price']}}</h2>
                                    </td>
                                    <td>
                                        <div class="qty-box">
                                            <div class="input-group">
                                                <input type="number" name="quantity" class="form-control input-number" value="{{$vendor_product['quantity']}}" data-id="{{$vendor_product['id']}}" data-base_price="{{$vendor_product['pvariant']['price']}}" data-tax="">
                                            </div>
                                        </div>
                                    </td>
                                    <!-- <td>
                                        @foreach($vendor_product['taxdata'] as $tax)
                                            <h2 class="td-color" id="product_total_amount_{{$vendor_product['id']}}">${{$tax['product_tax']}}</h2>
                                        @endforeach
                                    </td> -->
                                    <td>
                                        <h2 class="td-color" id="product_total_amount_{{$vendor_product['id']}}">${{$product['product_total_amount']}}</h2>
                                    </td>
                                    <td>
                                        <a class="icon remove_product_via_cart" data-product="{{$vendor_product['id']}}">
                                            <i class="ti-close"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            @endforeach
                            
                        </tbody>
                    </table>
                    <div class="table-responsive-md">
                        <table class="table cart-table">
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
            </div>
            <div class="row cart-buttons">
                <div class="col-6">
                    <a href="{{url('/')}}" class="btn btn-solid">continue shopping</a>
                </div>
                <div class="col-6">
                    <a class="btn btn-solid checkout" style="color: white;" onMouseOver="this.style.color='black'" onMouseOut="this.style.color='white'" href="{{url('user/checkout')}}">check out</a>
                </div>
            </div>
        @else
            <div class="alert alert-info" role="alert">
              Oops! Your cart is empty
            </div>
        @endif
    </div>
</section>
@endsection
@section('script')
<script>
$(document).ready(function() {
    $(document).on('change', '.input-number', function() {
        updateQuantity($(this).attr("data-id"), $(this).val(), $(this).data('base_price'));
    });
    function updateQuantity(cartproduct_id, quantity, base_price) {
        ajaxCall = $.ajax({
            type: "post",
            dataType: "json",
            url: "{{ route('updateQuantity') }}",
            data: {"quantity": quantity, "cartproduct_id": cartproduct_id},
            success: function(response) {
                var latest_price = parseInt(base_price) * parseInt(quantity);
                $('#product_total_amount_'+cartproduct_id).html('$'+latest_price);
            }
        });
    }
});
</script>
@endsection