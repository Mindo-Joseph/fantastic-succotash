$(document).ready(function() {
    /*$(".select_address").click(function () {
        addressInputDisplay(".select_address", ".address-input-field", "#address-input");
    });

    $(document).delegate("#address-input", "focusout", function(){
        addressInputHide(".select_address", ".address-input-field", "#address-input");
    });*/

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
        if(!delivery_address){
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
        setDeliveryAddress(latitude, longitude);
    });

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
                    var path = window.location.pathname;
                    if(path == '/'){
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

                        var newProducts = response.data.newProducts;
                        $('#new_products_wrapper .vendor-product').slick('destroy');
                        $("#new_products_wrapper .vendor-product").html('');
                        $('#bestseller_products_wrapper .vendor-product').slick('destroy');
                        $("#bestseller_products_wrapper .vendor-product").html('');
                        var newProductsArray = [];
                        newProducts.forEach(function(new_product, i, arr1){
                            new_product.forEach(function(product, j, arr2){
                                product.title = product['translation'] ? product['translation'][0]['title'] : product['sku'];
                                product.title = product.title.slice(0, 18)+'..';
                                product.body_html = product['translation'] ? product['translation'][0]['body_html'] : '';
                                product.description = product.body_html.replace(/(<([^>]+)>)/ig, '');
                                product.description = product.description.slice(0, 25)+'..';
                                product.price = product['variant'][0]['price'];
                                product.multiply = product['variant'][0]['multiplier'] ? 1 : product['variant'][0]['multiplier'];
                                product.imagePath = '';
                                product['media'].forEach(function(item, index, arr3){
                                    product.imagePath = item['image']['path']['proxy_url']+'300/300'+item['image']['path']['image_path'];
                                });
                                newProductsArray.push(product);
                            });
                        });
                        let new_products_template = _.template($('#new_products_template').html());
                        $("#new_products_wrapper .vendor-product").append(new_products_template({new_product_options: newProductsArray}));
                        let bestseller_products_template = _.template($('#bestseller_products_template').html());
                        $("#bestseller_products_wrapper .vendor-product").append(bestseller_products_template({bestseller_product_options: newProductsArray}));
                        $('#new_products_wrapper .vendor-product').slick(slickOptions);
                        $('#bestseller_products_wrapper .vendor-product').slick(slickOptions);

                        var featuredProducts = response.data.featuredProducts;
                        $('#featured_products_wrapper .vendor-product').slick('destroy');
                        $("#featured_products_wrapper .vendor-product").html('');
                        var featuredProductsArray = [];
                        featuredProducts.forEach(function(featured_product, i, arr1){
                            featured_product.forEach(function(product, j, arr2){
                                product.title = product['translation'] ? product['translation'][0]['title'] : product['sku'];
                                product.title = product.title.slice(0, 18)+'..';
                                product.body_html = product['translation'] ? product['translation'][0]['body_html'] : '';
                                product.description = product.body_html.replace(/(<([^>]+)>)/ig, '');
                                product.description = product.description.slice(0, 25)+'..';
                                product.price = product['variant'][0]['price'];
                                product.multiply = product['variant'][0]['multiplier'] ? 1 : product['variant'][0]['multiplier'];
                                product.imagePath = '';
                                product['media'].forEach(function(item, index, arr3){
                                    product.imagePath = item['image']['path']['proxy_url']+'300/300'+item['image']['path']['image_path'];
                                });
                                featuredProductsArray.push(product);
                            });
                        });
                        let featured_products_template = _.template($('#featured_products_template').html());
                        $("#featured_products_wrapper .vendor-product").append(featured_products_template({featured_product_options: featuredProductsArray}));
                        $('#featured_products_wrapper .vendor-product').slick(slickOptions);

                        var onSaleProducts = response.data.onSaleProducts;
                        $('#onsale_products_wrapper .vendor-product').slick('destroy');
                        $("#onsale_products_wrapper .vendor-product").html('');
                        var onSaleProductsArray = [];
                        onSaleProducts.forEach(function(onsale_product, i, arr1){
                            onsale_product.forEach(function(product, j, arr2){
                                product.title = product['translation'] ? product['translation'][0]['title'] : product['sku'];
                                product.title = product.title.slice(0, 18)+'..';
                                product.body_html = product['translation'] ? product['translation'][0]['body_html'] : '';
                                product.description = product.body_html.replace(/(<([^>]+)>)/ig, '');
                                product.description = product.description.slice(0, 25)+'..';
                                product.price = product['variant'][0]['price'];
                                product.multiply = product['variant'][0]['multiplier'] ? 1 : product['variant'][0]['multiplier'];
                                product.imagePath = '';
                                product['media'].forEach(function(item, index, arr3){
                                    product.imagePath = item['image']['path']['proxy_url']+'300/300'+item['image']['path']['image_path'];
                                });
                                onSaleProductsArray.push(product);
                            });
                        });
                        let onsale_products_template = _.template($('#onsale_products_template').html());
                        $("#onsale_products_wrapper .vendor-product").append(onsale_products_template({onsale_product_options: onSaleProductsArray}));
                        $('#onsale_products_wrapper .vendor-product').slick(slickOptions);
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
            center: {lat: latitude, lng: longitude},
            zoom: 13
        });
    
        const marker = new google.maps.Marker({
            map: map,
            position: {lat: latitude, lng: longitude},
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
                        if(!delivery_address){
                            setDeliveryAddress(latitude, longitude);
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