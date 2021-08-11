jQuery(window).scroll(function () {
    var scroll = jQuery(window).scrollTop();

    if (scroll <= 192) {

        jQuery(".category-btns").removeClass("category-active-btns");

    } else {

        jQuery(".category-btns").addClass("category-active-btns");

    }
});
$(document).ready(function () {

    if (window.location.pathname == '/') {
        $(document).ready(function () {
            $.ajax({
                url: client_preferences_url,
                type: "POST",
                success: function (response) {
                    if ($.cookie("age_restriction") != 1) {
                        if (response.age_restriction == 1) {
                            $('#age_restriction').modal({backdrop: 'static', keyboard: false});
                        }
                    }
                    if (response.delivery_check == 1) {
                        getHomePage("", "", "delivery");
                    }
                    else if (response.dinein_check == 1) {
                        getHomePage("", "", "dine_in");
                    }
                    else {
                        getHomePage("", "", "takeaway");
                    }
                },
            });
        });
    }

    $(".age_restriction_no").click(function () {
        window.location.replace("https://google.com");
    });

    $(".age_restriction_yes").click(function () {
        $.cookie('age_restriction', 1);
        $('#age_restriction').modal('hide');
    });

    function initializeSlider() {
        $(".slide-6").slick({
            dots: !1,
            infinite: !0,
            speed: 300,
            slidesToShow: 5,
            slidesToScroll: 6,
            responsive: [
                { breakpoint: 1367, settings: { slidesToShow: 5, slidesToScroll: 5, infinite: !0 } },
                { breakpoint: 1024, settings: { slidesToShow: 4, slidesToScroll: 4, infinite: !0 } },
                { breakpoint: 767, settings: { slidesToShow: 3, arrows: true, slidesToScroll: 3, infinite: !0 } },
                { breakpoint: 480, settings: { slidesToShow: 2, arrows: true, slidesToScroll: 2 } },
            ],
        });
        $(".product-4").slick({
            arrows: !0,
            dots: !1,
            infinite: !1,
            speed: 300,
            slidesToShow: 4,
            slidesToScroll: 3,
            responsive: [
                { breakpoint: 1200, settings: { slidesToShow: 3, slidesToScroll: 3 } },
                { breakpoint: 991, settings: { slidesToShow: 2, arrows: true, slidesToScroll: 2 } },
                { breakpoint: 420, settings: { slidesToShow: 1, arrows: true,slidesToScroll: 1 } },
            ],
        });
        $(".product-5").slick({
            arrows: !0,
            dots: !1,
            infinite: !1,
            speed: 300,
            slidesToShow: 6,
            slidesToScroll: 3,
            responsive: [
                { breakpoint: 1200, settings: { slidesToShow: 3, slidesToScroll: 3 } },
                { breakpoint: 991, settings: { slidesToShow: 2, arrows: true,slidesToScroll: 2 } },
                { breakpoint: 420, settings: { slidesToShow: 1, arrows: true,slidesToScroll: 1 } },
            ],
        });
        $('.vendor-product').slick({
            infinite: true,
            speed: 300,
            arrows: false,
            dots: false,
            slidesToShow: 4,
            slidesToScroll: 3,
            autoplay: true,
            autoplaySpeed: 5000,
            rtl: false,
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
                    slidesToScroll: 1,
                    arrows: true
                }
            }
            ]
        });       
    }

    $( document ).ready(function() {
        $('.date-items').slick({
            infinite: true,
            speed: 300,
            arrows: true,
            dots: false,
            slidesToShow: 7,
            slidesToScroll: 5,
            autoplay: false,
            autoplaySpeed: 5000,
            rtl: false,
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
                    slidesToScroll: 1,
                    arrows: true
                }
            }
            ]
        });
        
        $('.booking-time').slick({
            infinite: true,
            speed: 300,
            arrows: true,
            dots: false,
            slidesToShow: 4,
            slidesToScroll: 3,
            autoplay: false,
            autoplaySpeed: 5000,
            rtl: false,
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
                    slidesToScroll: 1,
                    arrows: true
                }
            }
            ]
        });

        $('.hours-slot').slick({
            infinite: true,
            speed: 300,
            arrows: true,
            dots: false,
            slidesToShow: 9,
            slidesToScroll: 3,
            autoplay: false,
            autoplaySpeed: 5000,
            rtl: false,
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
                    slidesToScroll: 1,
                    arrows: true
                }
            }
            ]
        });
        
        $('.materials-slide').slick({
            infinite: true,
            speed: 300,
            arrows: true,
            dots: false,
            slidesToShow: 4,
            slidesToScroll: 3,
            autoplay: false,
            autoplaySpeed: 5000,
            rtl: false,
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
                    slidesToScroll: 1,
                    arrows: true
                }
            }
            ]
        });

        
    });
       
    $(document ).ready(function() {
        $("#number").hide();
        $("#add_btn").click(function(){
            $("#number").show();
            $(this).hide();
        });
        
    });

   

    $("#dinein_tab").click(function () {
        var url = "dine_in";
        getHomePage("", "", url);
    });

    $("#delivery_tab").click(function () {
        getHomePage();
    });

    $("#takeaway_tab").click(function () {
        var url = "takeaway";
        getHomePage("", "", url);
    });

    function getHomePage(latitude, longitude, url = "delivery") {
        let selected_address = $("#address-input").val();
        $("#location_search_wrapper .homepage-address span").text(selected_address).attr({ "title": selected_address, "data-original-title": selected_address });
        $("#edit-address").modal('hide');
        let ajaxData = { type: url };
        if ((latitude) && (longitude) && (selected_address)) {
            ajaxData.latitude = latitude;
            ajaxData.longitude = longitude;
            ajaxData.selectedAddress = selected_address;
        }
        $.ajax({
            data: ajaxData,
            type: "POST",
            dataType: 'json',
            url: home_page_data_url,
            success: function (response) {
                if (response.status == "Success") {
                    $("#main-menu").html('');
                    let nav_categories_template = _.template($('#nav_categories_template').html());
                    $("#main-menu").append(nav_categories_template({ nav_categories: response.data.navCategories }));
                    // $("#main-menu").smartmenus({ subMenusSubOffsetX: 1, subMenusSubOffsetY: -8 }), $("#sub-menu").smartmenus({ subMenusSubOffsetX: 1, subMenusSubOffsetY: -8 });
                    var path = window.location.pathname;
                    if (path == '/') {
                        $(".slide-6").slick('destroy');
                        $(".product-4").slick('destroy');
                        $(".product-5").slick('destroy');
                        if ($('.vendor-product').html() != '') {
                            $('.vendor-product').slick('destroy');
                        }
                        $(".slide-6").html('');
                        $(".product-4").html('');
                        $(".product-5").html('');
                        $('.vendor-product').html('');
                        $("#new_products").html('');
                        $("#best_sellers").html('');
                        $("#featured_products").html('');
                        $("#on_sale").html('');
                        let vendors = response.data.vendors;
                        let banner_template = _.template($('#banner_template').html());
                        let vendors_template = _.template($('#vendors_template').html());
                        let products_template = _.template($('#products_template').html());
                        $("#brands").append(banner_template({ brands: response.data.brands, type: "Brands" }));
                        $("#vendors").append(vendors_template({ vendors: response.data.vendors , type: "Vendors" }));
                        $("#new_products").append(products_template({ products: response.data.new_products, type: "New Product" }));
                        $("#best_sellers").append(products_template({ products: response.data.new_products, type: "Best Seller" }));
                        $("#featured_products").append(products_template({ products: response.data.feature_products, type: "Featured Product" }));
                        $("#on_sale").append(products_template({ products: response.data.on_sale_products, type: "On Sale" }));
                        if (response.data.new_products.length > 0) {
                            $('#new_products1').removeClass('d-none');
                        } else {
                            $('#new_products1').addClass('d-none');
                        }
                        if (response.data.new_products.length > 0) {
                            $('#best_sellers1').removeClass('d-none');
                        } else {
                            $('#best_sellers1').addClass('d-none');
                        }
                        if (response.data.on_sale_products.length > 0) {
                            $('#on_sale1').removeClass('d-none');
                        } else {
                            $('#on_sale1').addClass('d-none');
                        }
                        if (response.data.feature_products.length > 0) {
                            $('#featured_products1').removeClass('d-none');
                        } else {
                            $('#featured_products1').addClass('d-none');
                        }
                        if (vendors.length > 0) {
                            $('#our_vendor_main_div').removeClass('d-none');
                        } else {
                            $('#our_vendor_main_div').addClass('d-none');
                        }
                        initializeSlider();
                    }
                    else {
                        if ((latitude) && (longitude) && (selected_address)) {
                            window.location.href = home_page_url;
                        }
                    }
                } else {
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
        displayLocation(lat, long);
    }

    if (is_hyperlocal) {
        if (!selected_address) {
            getLocation();
        }
        let lat = $("#address-latitude").val();
        let long = $("#address-longitude").val();
        displayLocation(lat, long);
    }

    // $(document).on('click', '#location_search_wrapper .dropdown-menu', function (e) {
    //     e.stopPropagation();
    // });

    $(document).delegate(".confirm_address_btn", "click", function () {
        let latitude = $("#address-latitude").val();
        let longitude = $("#address-longitude").val();

        $.ajax({
            type: "get",
            dataType: 'json',
            url: cart_details_url,
            success: function (response) {
                if (response.data != "") {
                    let cartProducts = response.data.products;
                    if (cartProducts != "") {
                        $("#remove_cart_modal").modal('show');
                        $("#remove_cart_modal #remove_cart_button").attr("data-cart_id", response.data.id);
                    } else {
                        getHomePage(latitude, longitude);
                    }
                } else {
                    getHomePage(latitude, longitude);
                }
            }
        });
    });

    $(document).delegate("#remove_cart_button", "click", function () {
        let cart_id = $(this).attr("data-cart_id");
        $("#remove_cart_modal").modal('hide');
        removeCartData(cart_id);
    });

    function removeCartData(cart_id) {
        $.ajax({
            type: "post",
            dataType: 'json',
            url: delete_cart_url,
            data: { 'cart_id': cart_id },
            success: function (response) {
                if (response.status == 'success') {
                    let latitude = $("#address-latitude").val();
                    let longitude = $("#address-longitude").val();
                    getHomePage(latitude, longitude);
                }
            }
        });
    }

    function displayLocation(latitude, longitude) {
        var geocoder;
        geocoder = new google.maps.Geocoder();
        var latlng = new google.maps.LatLng(latitude, longitude);

        const map = new google.maps.Map(document.getElementById('address-map'), {
            center: { lat: parseFloat(latitude), lng: parseFloat(longitude) },
            zoom: 13
        });

        const marker = new google.maps.Marker({
            map: map,
            position: { lat: parseFloat(latitude), lng: parseFloat(longitude) },
        });

        geocoder.geocode(
            { 'latLng': latlng },
            function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        var add = results[0].formatted_address;
                        var value = add.split(",");

                        count = value.length;
                        country = value[count - 1];
                        state = value[count - 2];
                        city = value[count - 3];
                        $("#address-input").val(add);
                        $("#location_search_wrapper .homepage-address span").text(value).attr({ "title": value, "data-original-title": value });
                        if (!selected_address) {
                            getHomePage(latitude, longitude);
                        }
                    }
                    else {
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

function addressInputDisplay(locationWrapper, inputWrapper, input) {
    $(inputWrapper).removeClass("d-none").addClass("d-flex");
    $(locationWrapper).removeClass("d-flex").addClass("d-none");
    var val = $(input).val();
    $(input).focus().val('').val(val);
}

function addressInputHide(locationWrapper, inputWrapper, input) {
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
            center: { lat: latitude, lng: longitude },
            zoom: 13
        });
        const marker = new google.maps.Marker({
            map: map,
            position: { lat: latitude, lng: longitude },
        });

        marker.setVisible(isEdit);

        const autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.key = fieldKey;
        autocompletes.push({ input: input, map: map, marker: marker, autocomplete: autocomplete });
    }

    for (let i = 0; i < autocompletes.length; i++) {
        const input = autocompletes[i].input;
        const autocomplete = autocompletes[i].autocomplete;
        const map = autocompletes[i].map;
        const marker = autocompletes[i].marker;

        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            marker.setVisible(false);
            const place = autocomplete.getPlace();

            geocoder.geocode({ 'placeId': place.place_id }, function (results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    const lat = results[0].geometry.location.lat();
                    const lng = results[0].geometry.location.lng();
                    $(".homepage-address span").text(place.formatted_address);
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
                map.setZoom(13);
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