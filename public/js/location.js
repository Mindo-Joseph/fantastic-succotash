$(document).ready(function() {
    /*$(".select_address").click(function () {
        addressInputDisplay(".select_address", ".address-input-field", "#address-input");
    });

    $(document).delegate("#address-input", "focusout", function(){
        addressInputHide(".select_address", ".address-input-field", "#address-input");
    });*/

    getHomePage();
    function initializeSlider(){
        $(".slide-6").slick({
            dots: !1,
            infinite: !0,
            speed: 300,
            slidesToShow: 6,
            slidesToScroll: 6,
            responsive: [
                { breakpoint: 1367, settings: { slidesToShow: 5, slidesToScroll: 5, infinite: !0 } },
                { breakpoint: 1024, settings: { slidesToShow: 4, slidesToScroll: 4, infinite: !0 } },
                { breakpoint: 767, settings: { slidesToShow: 3, slidesToScroll: 3, infinite: !0 } },
                { breakpoint: 480, settings: { slidesToShow: 2, slidesToScroll: 2 } },
            ],
        });
        $(".product-4").slick({
        arrows: !0,
        dots: !1,
        infinite: !1,
        speed: 300,
        slidesToShow: 6,
        slidesToScroll: 1,
        responsive: [
            { breakpoint: 1200, settings: { slidesToShow: 3, slidesToScroll: 3 } },
            { breakpoint: 991, settings: { slidesToShow: 2, slidesToScroll: 2 } },
            { breakpoint: 420, settings: { slidesToShow: 1, slidesToScroll: 1 } },
        ],
        });
        $('.vendor-product').slick({
            infinite: true,
            speed: 300,
            arrows: false,
            dots: false,
            slidesToShow: 6,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 5000,
            responsive: [{
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
    }
    function getHomePage(latitude, longitude){
        let selected_address = $("#address-input").val();
        $("#location_search_wrapper .homepage-address span").text(selected_address).attr({"title": selected_address, "data-original-title": selected_address});
        $("#edit-address").modal('hide');
        let ajaxData = {};
        if( (latitude) && (longitude) && (selected_address) ){
            ajaxData = {"latitude": latitude, "longitude": longitude, 'selectedAddress': selected_address};
        }
        $.ajax({
            data: ajaxData,
            type: "POST",
            dataType: 'json',
            url: home_page_data_url,
            success: function (response) {
                if(response.status == "Success"){
                    $("#main-menu").html('');
                    let nav_categories_template = _.template($('#nav_categories_template').html());
                    $("#main-menu").append(nav_categories_template({nav_categories: response.data.navCategories}));
                    $("#main-menu").smartmenus({ subMenusSubOffsetX: 1, subMenusSubOffsetY: -8 }), $("#sub-menu").smartmenus({ subMenusSubOffsetX: 1, subMenusSubOffsetY: -8 });
                    var path = window.location.pathname;
                    if(path == '/'){
                        $(".slide-6").slick('destroy');
                        $(".product-4").slick('destroy');
                        if($('.vendor-product').html() != ''){
                            $('.vendor-product').slick('destroy');
                        }
                        $(".slide-6").html('');
                        $(".product-4").html('');
                        $('.vendor-product').html('');
                        $("#new_product_main_div").html('');
                        $("#best_seller_main_div").html('');
                        $("#feature_product_main_div").html('');
                        $("#on_sale_product_main_div").html('');
                        let vendors = response.data.vendors;
                        let banner_template = _.template($('#banner_template').html());
                        let vendors_template = _.template($('#vendors_template').html());
                        let products_template = _.template($('#products_template').html());
                        $("#brand_main_div").append(banner_template({brands: response.data.brands}));
                        $("#vendor_main_div").append(vendors_template({vendors: response.data.vendors}));
                        $("#new_product_main_div").append(products_template({products: response.data.new_products}));
                        $("#best_seller_main_div").append(products_template({products: response.data.new_products}));
                        $("#feature_product_main_div").append(products_template({products: response.data.feature_products}));
                        $("#on_sale_product_main_div").append(products_template({products: response.data.on_sale_products}));
                        if(response.data.new_products.length > 0){
                            $('#new_products_wrapper, #bestseller_products_wrapper').removeClass('d-none');
                        }else{
                            $('#new_products_wrapper, #bestseller_products_wrapper').addClass('d-none');
                        }
                        if(response.data.on_sale_products.length > 0){
                            $('#onsale_products_wrapper').removeClass('d-none');
                        }else{
                            $('#onsale_products_wrapper').addClass('d-none');
                        }
                        if(response.data.feature_products.length > 0){
                            $('#featured_products_wrapper').removeClass('d-none');
                        }else{
                            $('#featured_products_wrapper').addClass('d-none');
                        }
                        if(vendors.length > 0){
                            $('#our_vendor_main_div').removeClass('d-none');
                        }else{
                            $('#our_vendor_main_div').addClass('d-none');
                        }
                        initializeSlider();
                    }
                    else{
                        if( (latitude) && (longitude) && (selected_address) ){
                            window.location.href = home_page_url;
                        }
                    }
                }else{
                }
            }
        });
    }

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, null);
        } else { 
            alert("Geolocation is not supported by this browser.");
        }
    }
    
    function showPosition(position) {
        let lat = position.coords.latitude;
        let long = position.coords.longitude;
        displayLocation(lat,long);
    }

    if(is_hyperlocal){
        if(!selected_address){
            getLocation();
        }else{
            let lat = $("#address-latitude").val();
            let long = $("#address-longitude").val();
            displayLocation(lat,long);
        }
    }

    // $(document).on('click', '#location_search_wrapper .dropdown-menu', function (e) {
    //     e.stopPropagation();
    // });

    $(document).delegate(".confirm_address_btn", "click", function(){
        let latitude = $("#address-latitude").val();
        let longitude = $("#address-longitude").val();

        $.ajax({
            type: "get",
            dataType: 'json',
            url: cart_details_url,
            success: function(response) {
                if(response.data != ""){
                    let cartProducts = response.data.products;
                    if(cartProducts != ""){
                        $("#remove_cart_modal").modal('show');
                        $("#remove_cart_modal #remove_cart_button").attr("data-cart_id", response.data.id);
                    }else{
                        getHomePage(latitude, longitude);
                    }
                }else{
                    getHomePage(latitude, longitude);
                }
            }
        });

        // setDeliveryAddress(latitude, longitude);
    });

    $(document).delegate("#remove_cart_button", "click", function(){
        let cart_id = $(this).attr("data-cart_id");
        $("#remove_cart_modal").modal('hide');
        removeCartData(cart_id);
    });

    function removeCartData(cart_id){
        $.ajax({
            type: "post",
            dataType: 'json',
            url: delete_cart_url,
            data: {'cart_id': cart_id},
            success: function(response) {
                if(response.status == 'success'){
                    let latitude = $("#address-latitude").val();
                    let longitude = $("#address-longitude").val();
                    getHomePage(latitude, longitude);
                }
            }
        });
    }

    function setDeliveryAddress(latitude, longitude){
        let selected_address = $("#address-input").val();
        $("#location_search_wrapper .homepage-address span").text(selected_address).attr({"title": selected_address, "data-original-title": selected_address});
        // $("#location_search_wrapper .dropdown-menu").removeClass('show');
        $("#edit-address").modal('hide');
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: '/homepage',
            data: {"latitude": latitude, "longitude": longitude, 'selectedAddress': selected_address},
            success: function(response) {
                if(response.status == 'Success'){
                    $("#main-nav #main-menu").html('');
                    let nav_categories_template = _.template($('#nav_categories_template').html());
                    $("#main-nav #main-menu").append(nav_categories_template({nav_categories: response.data.navCategories}));
                    $("#main-menu").smartmenus({ subMenusSubOffsetX: 1, subMenusSubOffsetY: -8 }), $("#sub-menu").smartmenus({ subMenusSubOffsetX: 1, subMenusSubOffsetY: -8 });
                    var path = window.location.pathname;
                    if(path == '/'){
                        var slickOptions = {
                            infinite: true,
                            speed: 300,
                            arrows: false,
                            slidesToShow: 5,
                            slidesToScroll: 1,
                            autoplay: true,
                            autoplaySpeed: 5000,
                            responsive: [{
                                    breakpoint: 1200,
                                    settings: {
                                        slidesToShow: 2,
                                        slidesToScroll: 2
                                    }
                                },
                                {
                                    breakpoint: 767,
                                    settings: {
                                        slidesToShow: 1,
                                        slidesToScroll: 1
                                    }
                                }
                            ]
                        };

                        if(response.data.vendors == ''){
                            $(".product-4").html('<h4 class="text-center">No vendor exists nearby your location</h4>');
                        }
                        else{
                            $('.product-4').slick('destroy');
                            $(".product-4").html('');
                            let vendors_template = _.template($('#vendors_template').html());
                            $(".product-4").append(vendors_template({vendor_options: response.data.vendors}));
                            $('.product-4').slick({
                                infinite: true,
                                speed: 300,
                                slidesToShow: 5,
                                slidesToScroll: 1,
                                autoplay: true,
                                autoplaySpeed: 3000,
                                responsive: [{
                                        breakpoint: 1200,
                                        settings: {
                                            slidesToShow: 3,
                                            slidesToScroll: 3
                                        }
                                    },
                                    {
                                        breakpoint: 991,
                                        settings: {
                                            slidesToShow: 2,
                                            slidesToScroll: 2
                                        }
                                    }
                                ]
                            });
                        }
                        
                        var newProducts = response.data.newProducts;
                        $('#new_products_wrapper .vendor-product').slick('destroy');
                        $("#new_products_wrapper .vendor-product").html('');
                        $('#bestseller_products_wrapper .vendor-product').slick('destroy');
                        $("#bestseller_products_wrapper .vendor-product").html('');
                        var newProductsArray = [];
                        newProducts.forEach(function(new_product, i, arr1){
                            new_product.forEach(function(product, j, arr2){
                                product.title = product.description = product.imagePath = '';
                                product.price = product.multiply = 0;
                                product['translation'].forEach(function(item, index, arr3){
                                    product.title = (item['title'] != null) ? item['title'] : item['sku'];
                                    product.title = product.title.slice(0, 18)+'..';
                                    product.description = (item['body_html'] != null) ? item['body_html'] : '';
                                    product.description = product.description.replace(/(<([^>]+)>)/ig, '');
                                    product.description = product.description.slice(0, 25)+'..';
                                });
                                product['variant'].forEach(function(item, index, arr3){
                                    product.price = item['price'];
                                    product.multiply = (item['multiplier'] != null) ? 1 : item['multiplier'];
                                });
                                product['media'].forEach(function(item, index, arr3){
                                    product.imagePath = item['image']['path']['proxy_url']+'300/300'+item['image']['path']['image_path'];
                                });
                                newProductsArray.push(product);
                            });
                        });
                        if(newProductsArray == ''){
                            $("#new_products_wrapper, #bestseller_products_wrapper").addClass("d-none");
                            $("#new_products_wrapper .vendor-product, #bestseller_products_wrapper .vendor-product").html('');
                        }
                        else{
                            $("#new_products_wrapper, #featured_products_wrapper").removeClass("d-none");
                            let new_products_template = _.template($('#products_template').html());
                            $("#new_products_wrapper .vendor-product").append(new_products_template({product_options: newProductsArray}));
                            let bestseller_products_template = _.template($('#products_template').html());
                            $("#bestseller_products_wrapper .vendor-product").append(bestseller_products_template({product_options: newProductsArray}));
                            $('#new_products_wrapper .vendor-product').slick(slickOptions);
                            $('#bestseller_products_wrapper .vendor-product').slick(slickOptions);
                        } 

                        var featuredProducts = response.data.featuredProducts;
                        $('#featured_products_wrapper .vendor-product').slick('destroy');
                        $("#featured_products_wrapper .vendor-product").html('');
                        var featuredProductsArray = [];
                        featuredProducts.forEach(function(featured_product, i, arr1){
                            featured_product.forEach(function(product, j, arr2){
                                product.title = product.description = product.imagePath = '';
                                product.price = product.multiply = 0;
                                product['translation'].forEach(function(item, index, arr3){
                                    product.title = (item['title'] != null) ? item['title'] : item['sku'];
                                    product.title = product.title.slice(0, 18)+'..';
                                    product.description = (item['body_html'] != null) ? item['body_html'] : '';
                                    product.description = product.description.replace(/(<([^>]+)>)/ig, '');
                                    product.description = product.description.slice(0, 25)+'..';
                                });
                                product['variant'].forEach(function(item, index, arr3){
                                    product.price = item['price'];
                                    product.multiply = (item['multiplier'] != null) ? 1 : item['multiplier'];
                                });
                                product['media'].forEach(function(item, index, arr3){
                                    product.imagePath = item['image']['path']['proxy_url']+'300/300'+item['image']['path']['image_path'];
                                });
                                featuredProductsArray.push(product);
                            });
                        });
                        if(featuredProductsArray == ''){
                            $("#featured_products_wrapper").addClass("d-none");
                            $("#featured_products_wrapper .vendor-product").html('');
                        }
                        else{
                            $("#featured_products_wrapper").removeClass("d-none");
                            let featured_products_template = _.template($('#products_template').html());
                            $("#featured_products_wrapper .vendor-product").append(featured_products_template({product_options: featuredProductsArray}));
                            $('#featured_products_wrapper .vendor-product').slick(slickOptions);
                        }

                        var onSaleProducts = response.data.onSaleProducts;
                        $('#onsale_products_wrapper .vendor-product').slick('destroy');
                        $("#onsale_products_wrapper .vendor-product").html('');
                        var onSaleProductsArray = [];
                        onSaleProducts.forEach(function(onsale_product, i, arr1){
                            onsale_product.forEach(function(product, j, arr2){
                                product.title = product.description = product.imagePath = '';
                                product.price = product.multiply = 0;
                                product['translation'].forEach(function(item, index, arr3){
                                    product.title = (item['title'] != null) ? item['title'] : item['sku'];
                                    product.title = product.title.slice(0, 18)+'..';
                                    product.description = (item['body_html'] != null) ? item['body_html'] : '';
                                    product.description = product.description.replace(/(<([^>]+)>)/ig, '');
                                    product.description = product.description.slice(0, 25)+'..';
                                });
                                product['variant'].forEach(function(item, index, arr3){
                                    product.price = item['price'];
                                    product.multiply = (item['multiplier'] != null) ? 1 : item['multiplier'];
                                });
                                product['media'].forEach(function(item, index, arr3){
                                    product.imagePath = item['image']['path']['proxy_url']+'300/300'+item['image']['path']['image_path'];
                                });
                                onSaleProductsArray.push(product);
                            });
                        });
                        if(onSaleProductsArray == ''){
                            $("#onsale_products_wrapper").addClass("d-none");
                            $("#onsale_products_wrapper .vendor-product").html('');
                        }
                        else{
                            $("#onsale_products_wrapper").removeClass("d-none");
                            let onsale_products_template = _.template($('#products_template').html());
                            $("#onsale_products_wrapper .vendor-product").append(onsale_products_template({product_options: onSaleProductsArray}));
                            $('#onsale_products_wrapper .vendor-product').slick(slickOptions);
                        }
                    }
                    else{
                        window.location.reload();
                    }
                }
            }
        });
    }

    function displayLocation(latitude,longitude){
        var geocoder;
        geocoder = new google.maps.Geocoder();
        var latlng = new google.maps.LatLng(latitude, longitude);
    
        const map = new google.maps.Map(document.getElementById('address-map'), {
            center: {lat: parseFloat(latitude), lng: parseFloat(longitude)},
            zoom: 13
        });
    
        const marker = new google.maps.Marker({
            map: map,
            position: {lat: parseFloat(latitude), lng: parseFloat(longitude)},
        });
    
        geocoder.geocode(
            {'latLng': latlng}, 
            function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        var add= results[0].formatted_address ;
                        var  value=add.split(",");
    
                        count=value.length;
                        country=value[count-1];
                        state=value[count-2];
                        city=value[count-3];
                        $("#address-input").val(value);
                        $("#location_search_wrapper .homepage-address span").text(value).attr({"title": value, "data-original-title": value});
                        if(!selected_address){
                            getHomePage(latitude, longitude);
                        }
                    }
                    else  {
                        // $("#address-input").val("address not found");
                    }
                }
                else {
                    $("#address-input").val("Geocoder failed due to: " + status);
                }
            }
        );
    }
});

function addressInputDisplay(locationWrapper, inputWrapper, input){
    $(inputWrapper).removeClass("d-none").addClass("d-flex");
    $(locationWrapper).removeClass("d-flex").addClass("d-none");
    var val = $(input).val();
    $(input).focus().val('').val(val);
}

function addressInputHide(locationWrapper, inputWrapper, input){
    $(inputWrapper).addClass("d-none").removeClass("d-flex");
    $(locationWrapper).addClass("d-flex").removeClass("d-none");
}

function initMap() {
    const locationInputs = document.getElementsByClassName("map-input");

    const autocompletes = [];
    const geocoder = new google.maps.Geocoder;
    for (let i = 0; i < locationInputs.length; i++) {

        const input = locationInputs[i];
        const fieldKey = input.id.replace("-input", "");
        const isEdit = document.getElementById(fieldKey + "-latitude").value != '' && document.getElementById(fieldKey + "-longitude").value != '';

        const latitude = parseFloat(document.getElementById(fieldKey + "-latitude").value) || -33.8688;
        const longitude = parseFloat(document.getElementById(fieldKey + "-longitude").value) || 151.2195;

        const map = new google.maps.Map(document.getElementById(fieldKey + '-map'), {
            center: {lat: latitude, lng: longitude},
            zoom: 13
        });
        const marker = new google.maps.Marker({
            map: map,
            position: {lat: latitude, lng: longitude},
        });

        marker.setVisible(isEdit);

        const autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.key = fieldKey;
        autocompletes.push({input: input, map: map, marker: marker, autocomplete: autocomplete});
    }

    for (let i = 0; i < autocompletes.length; i++) {
        const input = autocompletes[i].input;
        const autocomplete = autocompletes[i].autocomplete;
        const map = autocompletes[i].map;
        const marker = autocompletes[i].marker;

        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            marker.setVisible(false);
            const place = autocomplete.getPlace();

            geocoder.geocode({'placeId': place.place_id}, function (results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    const lat = results[0].geometry.location.lat();
                    const lng = results[0].geometry.location.lng();
                    $(".location-area span").text(place.formatted_address);
                    // addressInputHide(".select_address", ".address-input-field", "#address-input");
                    setLocationCoordinates(autocomplete.key, lat, lng);
                }
            });

            if (!place.geometry) {
                window.alert("No details available for input: '" + place.name + "'");
                input.value = "";
                return;
            }

            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);
            }
            marker.setPosition(place.geometry.location);
            marker.setVisible(true);

        });
    }
}

function setLocationCoordinates(key, lat, lng) {
    const latitudeField = document.getElementById(key + "-" + "latitude");
    const longitudeField = document.getElementById(key + "-" + "longitude");
    latitudeField.value = lat;
    longitudeField.value = lng;
}

google.maps.event.addDomListener(window, 'load', initMap);