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
                            <th scope="col">image</th>
                            <th scope="col">product name</th>
                            <th scope="col">variants</th>
                            <th scope="col">price</th>
                            <th scope="col">quantity</th>
                            <th scope="col">action</th>
                            <th scope="col">total</th>
                        </tr>
                    </thead>
                    <tbody class="shopping-cart1">
                        <!-- <tr>
                            <td>
                                <a href="#"><img src="../assets/images/pro3/1.jpg" alt=""></a>
                            </td>
                            <td><a href="#">cotton shirt</a>
                                <div class="mobile-cart-content row">
                                    <div class="col-xs-3">
                                        <div class="qty-box">
                                            <div class="input-group">
                                                <input type="text" name="quantity" class="form-control input-number"
                                                    value="1">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-3">
                                        <h2 class="td-color">$63.00</h2>
                                    </div>
                                    <div class="col-xs-3">
                                        <h2 class="td-color"><a href="#" class="icon"><i class="ti-close"></i></a>
                                        </h2>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <h2>$63.00</h2>
                            </td>
                            <td>
                                <div class="qty-box">
                                    <div class="input-group">
                                        <input type="number" name="quantity" class="form-control input-number"
                                            value="1">
                                    </div>
                                </div>
                            </td>
                            <td><a href="#" class="icon"><i class="ti-close"></i></a></td>
                            <td>
                                <h2 class="td-color">$4539.00</h2>
                            </td>
                        </tr> -->
                    </tbody>
                   
                </table>
                <table class="table cart-table table-responsive-md shopping-cart-footer">
                    <!-- <tfoot>
                        <tr>
                            <td>total price :</td>
                            <td>
                                <h2>$6935.00</h2>
                            </td>
                        </tr>
                    </tfoot> -->
                </table>
            </div>
        </div>
        <div class="row cart-buttons">
            <div class="col-6"><a href="#" class="btn btn-solid">continue shopping</a></div>
            <div class="col-6"><a href="#" class="btn btn-solid">check out</a></div>
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
                if(data.res == "null"){
                    $(".shopping-cart1").html(data.html);
                }else {
                    var variants = JSON.parse(data.variants);
                    // console.log(variants[0].set);
                    var cp_id = JSON.parse(data.cart_products);
                    var products = JSON.parse(data.products);
                    var price = JSON.parse(data.price);
                    var images = JSON.parse(data.image);
                    var quantity = JSON.parse(data.quantity);
                   
                    for (i = 0; i < products.length; i++) {
                        var vari = "<ul>";
                        for(j = 0; j < variants.length; j++){
                            vari += "<li>"+variants[i].set[j].title+"</li>";
                            vari += "<br>";
                        }
                        vari += "</ul>";

                        console.log(vari);
                        total1 += parseInt(price[i]) * parseInt(quantity[i]);
                        $(".shopping-cart1").append("<tr id='tbody"+cp_id[i]+"'><td><a href='#'><img src=" + images[i]['0'].pimage.image.path.proxy_url + '200/200' + images[i]['0'].pimage.image.path.image_path + " alt=''></a></td><td><a href='#'>" + products[i] + "</a><div class='mobile-cart-content row'><div class='col-xs-3'><div class='qty-box'><div class='input-group'><input type='number' min='1' name='quantity' id='quant' class='form-control input-number quant' value=" + quantity[i] + "></div></div></div><div class='col-xs-3'><h2 class='td-color' id='price"+cp_id[i]+"'>$" + price[i] + "</h2></div><div class='col-xs-3'><h2 class='td-color'><a href='#' class='icon'><i class='ti-close'></i></a></h2></div></div></td><td>"+vari+"</td><td><h2>$" + price[i] + "</h2></td><td><div class='qty-box'><div class='input-group'><input type='number' min='1' name='quantity' id='quant' class='form-control input-number quant' data-id="+cp_id[i]+" value=" + quantity[i] + "></div></div></td><td><a class='icon closed' data-id="+cp_id[i]+"><i class='ti-close'></i></a></td><td><h2 class='td-color' data-id="+cp_id[i]+" id='h2"+cp_id[i]+"'>$" + parseInt(price[i]) * parseInt(quantity[i]) + "</h2></td></tr>");
                    }

                    $(".shopping-cart-footer").append("<tfoot><tr><td>total price :</td><td><h2 id='total'>$" + total1 + "</h2></td></tr></tfoot>");
                }
            },
            error: function(data) {
                console.log('Error Found : ' + data);
            }
        });
    });
    $(document).on('change', '.quant', function() {
        updateQuantity($(this).attr("data-id"),$(this).val() );
           
        });

      
    function updateQuantity(cp_id, quantity) {
        ajaxCall = $.ajax({
            type: "post",
            dataType: "json",
            url: "{{ route('updateQuantity') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "cartproduct_id": cp_id,
                "quantity": quantity,
            },
            success: function(response) {
                console.log(total1);
                cartHeader();
                var latest_price = parseInt($("#price"+cp_id).html().substring(1)) * parseInt(quantity);
                total1 = total1 - parseInt($("#h2"+cp_id).html().substring(1));
                total1 = total1 + latest_price;
                $("#h2"+cp_id).html("$"+latest_price);
                $("#total").html("$"+total1);
                $("#totalCart").html("$"+total1);
            },
            error: function(data) {

            },
        });
    }

    $(document).on('click', '.closed', function() {
        var total2 = total1 - parseInt($("#h2"+$(this).attr("data-id")).html().substring(1));
        console.log(total2);
        var idd = $(this).attr("data-id");
        ajaxCall = $.ajax({
            type: "post",
            dataType: "json",
            url: "{{ route('deleteCartProduct') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "cartproduct_id": $(this).attr("data-id"),
            },
            success: function(response) {
                console.log(response);
                total1 = total2;
                $("#total").html("$"+total1);
                $("#totalCart").html("$"+total1);
                $("#tbody"+idd).remove(); 
                cartHeader();
            },
            error: function(data) {

            },
        });
           
        });


</script>

@endsection