$(document).ready(function() {
    /*$(".select_address").click(function () {
        addressInputDisplay(".select_address", ".address-input-field", "#address-input");
    });

    $(document).delegate("#address-input", "focusout", function(){
        addressInputHide(".select_address", ".address-input-field", "#address-input");
    });*/

    $(document).on('click', '#location_search_wrapper .dropdown-menu', function (e) {
        e.stopPropagation();
    });

    $(document).delegate(".confirm_address_btn", "click", function(){
        let latitude = $("#address-latitude").val();
        let longitude = $("#address-longitude").val();
        let selected_address = $("#address-input").val();
        $("#location_search_wrapper .homepage-address span").text(selected_address).attr({"title": selected_address, "data-original-title": selected_address});
        $("#location_search_wrapper .dropdown-menu").toggleClass('show');
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: homepage_url,
            data: {"latitude": latitude, "longitude": longitude, 'selectedAddress': selected_address},
            success: function(response) {
                if(response.status == 'Success'){
                    $('.product-4').slick('destroy');
                    $(".product-4").html(response.data.vendorsHtml);
                    $('.product-4').slick({
                        infinite: true,
                        speed: 300,
                        slidesToShow: 4,
                        slidesToScroll: 4,
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
                        }]
                    });
                }
            }
        });
    });
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

function initialize() {
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

google.maps.event.addDomListener(window, 'load', initialize);