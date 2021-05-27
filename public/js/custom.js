$(document).ready(function() {
    function productRemove(cartproduct_id, vendor_id){
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: delete_cart_product_url,
            data: {cartproduct_id:cartproduct_id},
            success: function(data) {
                if(data.status == 'success'){
                    $('#cart_product_'+cartproduct_id).remove();
                    $('#shopping_cart1_'+cartproduct_id).remove();
                    $('#tr_vendor_products_'+cartproduct_id).remove();
                    cartTotalProductCount();
                    if($("#tbody_" + vendor_id + " > tr.vendor_products_tr").length == 0){
                        $('#tbody_'+vendor_id).remove();
                    }
                    if($("[id^=tr_vendor_products_]").length == 0){
                        if($("#cart_main_page").length){
                            $("#cart_main_page").html('');
                            $('#tbody_'+vendor_id).remove()
                            let empty_cart_template = _.template($('#empty_cart_template').html());
                            $("#cart_main_page").append(empty_cart_template());
                        }
                    }
                    if($("[id^=cart_product_]").length == 0){
                        $(".shopping-cart").html('');
                    }
                }
            }
        });
    }
    $(document).on("click",".remove-product",function() {
        let vendor_id = $(this).data('vendor_id');
        let cartproduct_id = $(this).data('product');
        productRemove(cartproduct_id, vendor_id);
    });
    $(document).on("click",".remove_product_via_cart",function() {
        $('#remove_item_modal').modal('show');
        let vendor_id = $(this).data('vendor_id');
        let cartproduct_id = $(this).data('product');
        $('#remove_item_modal #vendor_id').val(vendor_id);
        $('#remove_item_modal #cartproduct_id').val(cartproduct_id);
    });
    $(document).on("click","#remove_product_button",function() {
        let vendor_id = $('#remove_item_modal #vendor_id').val();
        let cartproduct_id = $('#remove_item_modal #cartproduct_id').val();
        $('#remove_item_modal').modal('hide');
        productRemove(cartproduct_id, vendor_id);
    });
    function initialize() {
      var input = document.getElementById('address');
      var autocomplete = new google.maps.places.Autocomplete(input);
      google.maps.event.addListener(autocomplete, 'place_changed', function () {
        var place = autocomplete.getPlace();
        document.getElementById('city').value = place.name;
        // document.getElementById('latitude').value = place.geometry.location.lat();
        // document.getElementById('longit').value = place.geometry.location.lng();
        for(let i=1; i < place.address_components.length; i++){
            let mapAddress = place.address_components[i];
            if(mapAddress.long_name !=''){
                if(mapAddress.types[0] =="administrative_area_level_1"){
                    document.getElementById('state').value = mapAddress.long_name;
                }
                if(mapAddress.types[0] =="postal_code"){
                    document.getElementById('pincode').value = mapAddress.long_name;
                }
            }
        }
      });
    }
    google.maps.event.addDomListener(window, 'load', initialize);
    function cartTotalProductCount(){
        let cart_qty_total = 0;
        $(".shopping-cart li" ).each(function( index ) {
            if($(this).data('qty')){
                cart_qty_total += $(this).data('qty');
            }
        });
        if(cart_qty_total > 0){
            $('#cart_qty_span').html(cart_qty_total).show();
        }else{
            $('#cart_qty_span').html(cart_qty_total).hide();
        }
    }
    function cartHeader() {
        $(".shopping-cart").html(" ");
        $.ajax({
            data: '',
            type: "get",
            dataType: 'json',
            url: cart_product_url,
            success: function(data) {
                if (data.res == "null") {
                    $(".shopping-cart").html(data.html);
                } else {
                    var products = data.products;
                    if(typeof products != 'undefined'){
                        for (i = 0; i < products.length; i++) {
                            var vendor_products = products[i].vendor_products;
                            for (j = 0; j < vendor_products.length; j=j+2) {
                                $(".shopping-cart").append("<li id='cart_product_"+vendor_products[j].id+"' data-qty="+vendor_products[j].quantity+"><div class='media'><a href='#'><img alt='' class='mr-3' src='" + vendor_products[j].pvariant.media[0].image.path.proxy_url + '200/200' + vendor_products[j].pvariant.media[0].image.path.image_path + "'></a><div class='media-body'><a href='#'><h4>" + vendor_products[j].product.sku + "</h4></a><h4><span>" + vendor_products[j].quantity + " x $" + products[i].payable_amount + "</span></h4></div></div><div class='close-circle'><a href='#' data-product="+vendor_products[j].id+" class='remove-product'><i class='fa fa-times' aria-hidden='true'></i></a></div></li>");
                            }
                        }
                        $(".shopping-cart").append("<li><div class='total'><h5>subtotal : <span id='totalCart'>" + data.total_payable_amount + "</span></h5></div></li>");
                        $(".shopping-cart").append("<li><div class='buttons'><a href='"+show_cart_url+"' class='view-cart'>viewcart</a>");
                        cartTotalProductCount();
                    }
                }
            }
        });
    }
    cartHeader();
    $(document).on("click","#cancel_save_address_btn",function() {
        $('#add_new_address').show();
        $('#add_new_address_btn').show();
        $('#add_new_address_form').hide();
    });
    $(document).on("click","#add_new_address_btn",function() {
        $(this).hide();
        $('#add_new_address_form').show();
    });
    $(document).on("click","#save_address",function() {
        let city = $('#add_new_address_form #city').val();
        let state = $('#add_new_address_form #state').val();
        let street = $('#add_new_address_form #street').val();
        let address = $('#add_new_address_form #address').val();
        let country = $('#add_new_address_form #country').val();
        let pincode = $('#add_new_address_form #pincode').val();
        let type = $("input[name='address_type']:checked").val();
        $.ajax({
            type: "post",
            dataType: "json",
            url: user_store_address_url,
            data: {
                "city": city,
                "type" : type,
                "state" : state,
                "address": address,
                "country": country,
                "pincode": pincode,
            },
            success: function(response) {
                $('#add_new_address_form').hide();
                let address_template = _.template($('#address_template').html());
                if(address.length > 0){
                    $("#address_template_main_div").append(address_template({address:response.address}));
                }
            },
            error: function (reject) {
                if( reject.status === 422 ) {
                    var message = $.parseJSON(reject.responseText);
                    $.each(message.errors, function (key, val) {
                        $("#" + key + "_error").text(val[0]);
                    });
                }
            }
        });
    });
    $(document).on("click",".addToCart",function() {
        addToCart();
    });
    function addToCart() {
        $.ajax({
            type: "post",
            dataType: "json",
            url: add_to_cart_url,
            data: {
                "addonID" : addonids,
                "vendor_id": vendor_id,
                "product_id": product_id,
                "addonoptID" : addonoptids,
                "quantity": $('.quantity_count').val(),
                "variant_id": $('#prod_variant_id').val(),
            },
            success: function(response) {
                cartHeader();
            },
            error: function(data) {
                console.log(data);
            },
        });
    }
    
    $(document).on('click', '.quantity-right-plus', function() {
        var quan = parseInt($('.quantity_count').val());
        var str = $('#instock').html();
        var res = parseInt(str.substring(10, str.length - 1));
        if (quan > res) {
            alert("Quantity is not available in stock");
            $('.quantity_count').val(res)
        }
    });
    $(document).on('change', '.quantity_count', function() {
        var quan = $(this).val();
        var str = $('#instock').html();
        var res = parseInt(str.substring(10, str.length - 1));
        if (quan > res) {
            alert("Quantity is not available in stock");
            $('.quantity_count').val(res)
        }
    });
});