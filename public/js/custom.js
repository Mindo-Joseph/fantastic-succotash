$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    if($('#cart_main_page').length > 0){
        let address_checked = $("input:radio[name='address_id']").is(":checked");
        if(address_checked){
            $('#order_palced_btn').prop('disabled', false);
        }else{
            $('#order_palced_btn').prop('disabled', true);
        }
        $("form").submit(function(e){
            let address_id = $("input:radio[name='address_id']").is(":checked");
            if(!address_id){
                alert('Address field required.');
                return false;
            }
        });
    }
    var card = '';
    var stripe = '';
    
    $(".search_btn").click(function () {
        $(".search_warpper").slideToggle("slow");
    });

    $(".close_btn").click(function () {
        $(".search_warpper").slideUp("slow");
    });
    function settingData(type = '', v1 = '', v2 = '') {
        $.ajax({
            type: "post",
            dataType: "json",
            url: "{{ route('changePrimaryData') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "type": type,
                "value1": v1, 
                "value2": v2
            },
            success: function(response) {
                location.reload();
            },
            error: function (data) {
                location.reload();
            },
        });
    }
    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        console.log(charCode);
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)){
            return false;
        }
        return true;
    }
    $('.addWishList').click(function(){
        var sku = $(this).attr('proSku');
        $.ajax({
            type: "post",
            dataType: "json",
            url: "{{ route('addWishlist') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "sku": sku
            },
            success: function(response) {

            }
        });
    });
    $('.customerLang').click(function(){
        var changLang = $(this).attr('langId');
        settingData('language', changLang);
    });

    $('.customerCurr').click(function(){
        var changcurrId = $(this).attr('currId');
        var changSymbol = $(this).attr('currSymbol');
        settingData('currency', changcurrId, changSymbol);
    });
    function stripeInitialize(){
        stripe = Stripe('pk_test_51J0nVZSBx0AFwevbSTIDlYAaLjdsg4V4yoHpSo4BCZqGBzzGeU8Mnw1o0spfOYfMtyCXC11wEn6vBqbJeSNnAkw600U6jkzS3R');
        var elements = stripe.elements();
        var style = {
            base: {fontSize: '16px',color: '#32325d',borderColor: '#ced4da'},
        };
        card = elements.create('card', {hidePostalCode: true, style: style});
        card.mount('#stripe-card-element');
    }
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
    
    $(document).on("change","input:radio[name='address_id']",function() {
        if($(this).val()){
            $('#order_palced_btn').prop('disabled', false);
            cartHeader($(this).val());
        }
    });
    $(document).on("click","#order_palced_btn",function() {
        $('.alert-danger').html('');
        $.ajax({
            data: {},
            type: "POST",
            async: false,
            dataType: 'json',
            url: payment_option_list_url,
            success: function(response) {
                if (response.status == "Success") {
                    $('#v_pills_tab').html('');
                    $('#v_pills_tabContent').html('');
                    let payment_method_template = _.template($('#payment_method_template').html());
                    $("#v_pills_tab").append(payment_method_template({payment_options: response.data}));
                    let payment_method_tab_pane_template = _.template($('#payment_method_tab_pane_template').html());
                    $("#v_pills_tabContent").append(payment_method_tab_pane_template({payment_options: response.data}));
                    $('#proceed_to_pay_modal').modal('show');
                    $('#proceed_to_pay_modal #total_amt').html($('#cart_total_payable_amount').html());
                    stripeInitialize();
                }
            },error: function(error){
                var response = $.parseJSON(error.responseText);
                let error_messages = response.message;
                $.each(error_messages, function(key, error_message) {
                    $('#min_order_validation_error_'+error_message.vendor_id).html(error_message.message).show();
                });
            }
        });
    });
    function paymentViaStripe(stripe_token, address_id, payment_option_id){
        let cart_total_amount = $("input[name='cart_total_payable_amount']").val();
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: payment_stripe_url,
            data: {'stripe_token' : stripe_token ,'amount': cart_total_amount},
            success: function (resp) {
                if(resp.status == 'Success'){
                    placeOrder(address_id, payment_option_id, resp.data.id);
                }else{
                    success_error_alert('error', resp.message, "#stripe-payment-form .payment_response");
                    $("#order_palced_btn, .proceed_to_pay").removeAttr("disabled");
                }
            },
            error: function(error){
                var response = $.parseJSON(error.responseText);
                success_error_alert('error', response.message, "#stripe-payment-form .payment_response");
                $("#order_palced_btn, .proceed_to_pay").removeAttr("disabled");
            }
        });
    }
    function paymentViaPaypal(){
        let cart_total_amount = $("input[name='cart_total_payable_amount']").val();
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: payment_paypal_url,
            data: {'amount': cart_total_amount},
            success: function (response) {
                if(response.status == "Success"){
                    window.location.href = response.data;
                }else{
                    success_error_alert('error', response.message, "#paypal-payment-form .payment_response");
                    $("#order_palced_btn, .proceed_to_pay").removeAttr("disabled");
                }
            },
            error: function(error){
                var response = $.parseJSON(error.responseText);
                success_error_alert('error', response.message, "#paypal-payment-form .payment_response");
                $("#order_palced_btn, .proceed_to_pay").removeAttr("disabled");
            }
        });
    }

    let queryString = window.location.search;
    let urlParams = new URLSearchParams(queryString);
    if( (urlParams.has('PayerID')) && (urlParams.has('token')) ){
        paymentSuccessViaPaypal(urlParams.get('amount'), urlParams.get('token'), urlParams.get('PayerID'));
    }

    function paymentSuccessViaPaypal(amount, token, payer_id){
        $('#order_palced_btn').trigger('click');
        $('#v-pills-paypal-tab').trigger('click');
        $("#order_palced_btn, .proceed_to_pay").attr("disabled", true);
        let address_id = $("input:radio[name='address_id']:checked").val();
        $.ajax({
            type: "GET",
            dataType: 'json',
            url: payment_success_paypal_url,
            data: {'amount': amount, 'token': token, 'PayerID': payer_id},
            success: function (response) {
                if(response.status == "Success"){
                    placeOrder(address_id, 3, response.data);
                }else{
                    success_error_alert('error', response.message, "#paypal-payment-form .payment_response");
                    $("#order_palced_btn, .proceed_to_pay").removeAttr("disabled");
                }
            },
            error: function(error){
                var response = $.parseJSON(error.responseText);
                success_error_alert('error', response.message, "#paypal-payment-form .payment_response");
                $("#order_palced_btn, .proceed_to_pay").removeAttr("disabled");
            }
        });
    }
    function placeOrder(address_id, payment_option_id, transaction_id){
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: place_order_url,
            data: {address_id:address_id, payment_option_id:payment_option_id, transaction_id:transaction_id},
            success: function(response) {
                if (response.status == "Success") {
                    window.location.href = base_url+'/order/success/'+response.data.order.id;
                }else{
                    $("#order_palced_btn, .proceed_to_pay").attr("disabled", false);
                }
            },
            error: function(error){
                var response = $.parseJSON(error.responseText);
                success_error_alert('error', response.message, ".payment_response");
                $("#order_palced_btn, .proceed_to_pay").removeAttr("disabled");
            }
        });
    }
    $(document).on("click", ".proceed_to_pay", function() {
        $("#order_palced_btn, .proceed_to_pay").attr("disabled", true);
        let address_id = $("input:radio[name='address_id']:checked").val();
        let payment_option_id = $('#proceed_to_pay_modal #v_pills_tab').find('.active').data('payment_option_id');
        if(payment_option_id == 1){
            placeOrder(address_id, payment_option_id, '');
        }else if (payment_option_id == 4){
            stripe.createToken(card).then(function(result) {
                if (result.error) {
                    $('#stripe_card_error').html(result.error.message);
                    $("#order_palced_btn, .proceed_to_pay").attr("disabled", false);
                } else {
                    paymentViaStripe(result.token.id, address_id, payment_option_id);
                }
            });
        }else if(payment_option_id == 3){
            paymentViaPaypal(address_id, payment_option_id);
        }
    });
    $(document).on("click",".remove_promo_code_btn",function() {
        let cart_id = $(this).data('cart_id');
        let coupon_id = $(this).data('coupon_id');
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: promo_code_remove_url,
            data: {coupon_id:coupon_id, cart_id:cart_id},
            success: function(response) {
                if (response.status == "Success") {
                    cartHeader();
                }
            }
        });
    });
    $(document).on("click",".promo_code_list_btn",function() {
        let amount = $(this).data('amount');
        let cart_id = $(this).data('cart_id');
        let vendor_id = $(this).data('vendor_id');
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: promocode_list_url,
            data: {vendor_id:vendor_id, amount:amount, cart_id:cart_id},
            success: function(response) {
                $("#promo_code_list_main_div").html('');
                if (response.status == "Success") {
                    $('#refferal-modal').modal('show');
                    if(response.data.length != 0){
                        let promo_code_template = _.template($('#promo_code_template').html());
                        $("#promo_code_list_main_div").append(promo_code_template({promo_codes:response.data, vendor_id:vendor_id, cart_id:cart_id, amount:amount}));
                    }else{
                        let no_promo_code_template = _.template($('#no_promo_code_template').html());
                        $("#promo_code_list_main_div").append(no_promo_code_template());
                    }
                }
            }
        });
    });
    $(document).on("click",".apply_promo_code_btn",function() {
        let amount = $(this).data('amount');
        let cart_id = $(this).data('cart_id');
        let vendor_id = $(this).data('vendor_id');
        let coupon_id = $(this).data('coupon_id');
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: apply_promocode_coupon_url,
            data: {cart_id:cart_id, vendor_id:vendor_id, coupon_id:coupon_id, amount:amount},
            success: function(response) {
                if (response.status == "Success") {
                 $('#refferal-modal').modal('hide');
                 cartHeader();
                }
            },
            error: function (reject) {
                if( reject.status === 422 ) {
                    var message = $.parseJSON(reject.responseText);
                    alert(message.message);
                }
            }
        });
    });
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
    function capitalizeFirstLetter(string) {
      return string.charAt(0).toUpperCase() + string.slice(1);
    }
    function initialize() {
      var input = document.getElementById('address');
      var autocomplete = new google.maps.places.Autocomplete(input);
      google.maps.event.addListener(autocomplete, 'place_changed', function () {
        var place = autocomplete.getPlace();
        // console.log(place.name);
        document.getElementById('city').value = place.name;
        document.getElementById('longitude').value = place.geometry.location.lng()
        document.getElementById('latitude').value = place.geometry.location.lat()
        for(let i=1; i < place.address_components.length; i++){
            let mapAddress = place.address_components[i];
            if(mapAddress.long_name !=''){
                if(mapAddress.types[0] =="administrative_area_level_1"){
                    document.getElementById('state').value = mapAddress.long_name;
                }
                if(mapAddress.types[0] =="postal_code"){
                    document.getElementById('pincode').value = mapAddress.long_name;
                }
                if(mapAddress.types[0] == "country"){
                    var country = document.getElementById('country');
                    for (let i = 0; i < country.options.length; i++) {
                        if (country.options[i].text == mapAddress.long_name.toUpperCase()) {
                            country.value = i;
                            break;
                        }
                    }
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
    function cartHeader(address_id) {
        $(".shopping-cart").html("");
        $.ajax({
            data: {address_id:address_id},
            type: "get",
            dataType: 'json',
            url: cart_product_url,
            success: function(response) {
                if (response.status == "success") {
                    $("#cart_table").html('');
                    var cart_details = response.cart_details;
                    if(response.cart_details.length != 0){
                        if(response.cart_details.products.length != 0){
                            let header_cart_template = _.template($('#header_cart_template').html());
                            $("#header_cart_main_ul").append(header_cart_template({cart_details:cart_details, show_cart_url:show_cart_url}));
                            if($('#cart_main_page').length != 0){
                                let cart_template = _.template($('#cart_template').html());
                                $("#cart_table").append(cart_template({cart_details:cart_details}));
                            }
                            cartTotalProductCount();
                        }else{
                            if($('#cart_main_page').length != 0){
                                $('#cart_main_page').html('');
                                let empty_cart_template = _.template($('#empty_cart_template').html());
                                $("#cart_main_page").append(empty_cart_template());
                            }
                        }
                    }
                }
            }
        });
    }
    function updateQuantity(cartproduct_id, quantity, base_price) {
        ajaxCall = $.ajax({
            type: "post",
            dataType: "json",
            url: update_qty_url,
            data: {"quantity": quantity, "cartproduct_id": cartproduct_id},
            success: function(response) {
                var latest_price = parseInt(base_price) * parseInt(quantity);
                $('#product_total_amount_'+cartproduct_id).html('$'+latest_price);
            }
        });
    }
    $(document).on('click', '.qty-minus', function() {
        let base_price = $(this).data('base_price');
        let cartproduct_id = $(this).attr("data-id");
        let qty = $('#quantity_'+cartproduct_id).val();
        if (qty >= 1) {
            $('#quantity_'+cartproduct_id).val(--qty);
        }else{
            alert('remove this product');
        }
        updateQuantity(cartproduct_id, qty, base_price);
        cartHeader();
    });
    $(document).on('click', '.qty-plus', function() {
        let base_price = $(this).data('base_price');
        let cartproduct_id = $(this).attr("data-id");
        let qty = $('#quantity_'+cartproduct_id).val();
        $('#quantity_'+cartproduct_id).val(++qty);
        updateQuantity(cartproduct_id, qty, base_price);
        cartHeader();
    });
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
        let latitude = $('#add_new_address_form #latitude').val();
        let longitude = $('#add_new_address_form #longitude').val();
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
                "latitude": latitude,
                "longitude": longitude,
            },
            success: function(response) {
                $('#add_new_address_form').hide();
                let address_template = _.template($('#address_template').html());
                if(address.length > 0){
                    $('#order_palced_btn').attr('disabled', false);
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

    function success_error_alert(responseClass, message, element){
        $(element).find(".alert").html('');
        if(responseClass == 'success'){
            $(element).find(".alert").html("<div class='alert-success p-1'>"+message+"</div>").show();
        }else if(responseClass == 'error'){
            $(element).find(".alert").html("<div class='alert-danger p-1'>"+message+"</div>").show();
        }
        setTimeout(function(){
            $(element).find(".alert").hide();
        }, 5000);
    }

    $(document).on('click', '.prescription_btn', function(e) { 
        $("#product_id").val($(this).data("product"));
        $('#prescription_form').modal('show');
   });

    $(document).on('click', '.submitPrescriptionForm', function(e) { 
        e.preventDefault();
        var form =  document.getElementById('save_prescription_form');
        var formData = new FormData(form);
        var route_uri =  "add/product/prescription";

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
        $.ajax({
            type: "post",
            headers: {
                Accept: "application/json"
            },
            url: route_uri,
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function(){
                $(".loader_box").show();
            },
            success: function(response) {

                if (response.status == 'success') {
                    $(".modal .close").click();
                    location.reload(); 
                } else {
                    $(".show_all_error.invalid-feedback").show();
                    $(".show_all_error.invalid-feedback").text(response.message);
                }
                return response;
            },
            complete: function(){
                $('.loader_box').hide();
            }
        });

    });

});