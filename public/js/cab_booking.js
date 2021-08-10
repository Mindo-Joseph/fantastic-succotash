$(document).ready(function () {
    var selected_address = '';
    const styles = [
    {
        "stylers": [
            {
                "visibility": "on"
            },
            {
                "saturation": -100
            },
            {
                "gamma": 0.54
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "labels.icon",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "water",
        "stylers": [
            {
                "color": "#4d4946"
            }
        ]
    },
    {
        "featureType": "poi",
        "elementType": "labels.icon",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "poi",
        "elementType": "labels.text",
        "stylers": [
            {
                "visibility": "simplified"
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#ffffff"
            }
        ]
    },
    {
        "featureType": "road.local",
        "elementType": "labels.text",
        "stylers": [
            {
                "visibility": "simplified"
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "labels.text.fill",
        "stylers": [
            {
                "color": "#ffffff"
            }
        ]
    },
    {
        "featureType": "transit.line",
        "elementType": "geometry",
        "stylers": [
            {
                "gamma": 0.48
            }
        ]
    },
    {
        "featureType": "transit.station",
        "elementType": "labels.icon",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "geometry.stroke",
        "stylers": [
            {
                "gamma": 7.18
            }
        ]
    }
];
    $(document).on("click","#show_dir",function() {
        initMap2();
    });
    $(document).on("click",".search-location-result",function() {
        $('#pickup_location').val($(this).data('address'));
        var latitude = $(this).data('latitude');
        var longitude = $(this).data('longitude');
        displayLocation(latitude, longitude);
    });
    getVendorList();
    function getVendorList(){
        $.ajax({
            data: {},
            type: "POST",
            dataType: 'json',
            url: autocomplete_urls,
            success: function(response) {
                if(response.status == 'Success'){
                    $('#vendor_main_div').html('');
                    if(response.data.length != 0){
                        let vendors_template = _.template($('#vendors_template').html());
                        $("#vendor_main_div").append(vendors_template({results: response.data})).show();
                    }else{
                        $("#vendor_main_div").html('<p class="text-center my-3">No result found. Please try a new search</p>').show();
                    }
                }
            }
        });
    }
    $(document).on("click",".vendor-list",function() {
        let vendor_id = $(this).data('vendor');
        $.ajax({
            data: {},
            type: "POST",
            dataType: 'json',
            url: get_vehicle_list+'/'+vendor_id,
            success: function(response) {
                if(response.status == 'Success'){
                    $('#search_product_main_div').html('');
                    if(response.data.length != 0){
                        let products_template = _.template($('#products_template').html());
                        $("#search_product_main_div").append(products_template({results: response.data})).show();
                    }else{
                        $("#search_product_main_div").html('<p class="text-center my-3">No result found. Please try a new search</p>').show();
                    }
                }
            }
        });
    });
    function initMap2() {
        let pickup_location_latitude = $('#pickup_location_latitude').val();
        let pickup_location_longitude = $('#pickup_location_longitude').val();
        let destination_location_latitude = $('#destination_location_latitude').val();
        let destination_location_longitude = $('#destination_location_longitude').val();
        if(pickup_location_latitude && pickup_location_longitude && destination_location_latitude && destination_location_longitude){
            var pointA = new google.maps.LatLng(pickup_location_latitude, pickup_location_longitude);
            pointB = new google.maps.LatLng(destination_location_latitude, destination_location_longitude);
            if(pointA && pointB){
                myOptions = {zoom: 7,center: pointA};
                map = new google.maps.Map(document.getElementById('booking-map'), myOptions),
                map.setOptions({ styles:  styles}),
                // Instantiate a directions service.
                directionsService = new google.maps.DirectionsService,
                directionsDisplay = new google.maps.DirectionsRenderer({
                  map: map
                }),
                markerA = new google.maps.Marker({
                  position: pointA,
                  title: "point A",
                  label: "A",
                  map: map
                }),
                markerB = new google.maps.Marker({
                  position: pointB,
                  title: "point B",
                  label: "B",
                  map: map
                });
                calculateAndDisplayRoute(directionsService, directionsDisplay, pointA, pointB);
            }
        }
    }
    function calculateAndDisplayRoute(directionsService, directionsDisplay, pointA, pointB) {
          directionsService.route({
            origin: pointA,
            destination: pointB,
            travelMode: google.maps.TravelMode.DRIVING
          }, function(response, status) {
            if (status == google.maps.DirectionsStatus.OK) {
              directionsDisplay.setDirections(response);
            } else {
              window.alert('Directions request failed due to ' + status);
            }
          });
    }
    initialize();
    function initialize() {
      var input = document.getElementById('pickup_location');
      var input2 = document.getElementById('destination_location');
      if(input){
        var autocomplete = new google.maps.places.Autocomplete(input);
        var autocomplete2 = new google.maps.places.Autocomplete(input2);
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var place = autocomplete.getPlace();
           $('#pickup_location_latitude').val(place.geometry.location.lat());
           $('#pickup_location_longitude').val(place.geometry.location.lng());
           initMap2();
        });
        google.maps.event.addListener(autocomplete2, 'place_changed', function () {
            var place2 = autocomplete2.getPlace();
            $('#destination_location_latitude').val(place2.geometry.location.lat());
            $('#destination_location_longitude').val(place2.geometry.location.lng());
            initMap2();
        });
      }
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
        $('#addHeader1-latitude').val(lat);
        $('#addHeader1-longitude').val(long);
        displayLocation(lat, long);
    }
    if (!selected_address) {
        getLocation();
    }
    let lat = $("#booking-latitude").val();
    let long = $("#booking-longitude").val();
    displayLocation(lat, long);
    function displayLocation(latitude, longitude) {
        var geocoder;
        geocoder = new google.maps.Geocoder();
        var latlng = new google.maps.LatLng(latitude, longitude);
        const map = new google.maps.Map(document.getElementById('booking-map'), {
            center: { lat: parseFloat(latitude), lng: parseFloat(longitude) },
            zoom: 13
        });
        map.setOptions({ styles:  styles});
        var icon_set = {
            url: live_location, // url
            scaledSize: new google.maps.Size(30, 30), // scaled size
            origin: new google.maps.Point(0,0), // origin
            anchor: new google.maps.Point(0, 0) // anchor
        };
        const marker = new google.maps.Marker({
            map: map,
            position: { lat: parseFloat(latitude), lng: parseFloat(longitude) },
            icon : icon_set,
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
                        $("#addHeader1-input").val(add);
                        $("#location_search_wrapper .homepage-address span").text(value).attr({ "title": value, "data-original-title": value });
                    }else {
                    }
                }else {
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
    const autocompletes = [];
    const locationInputs = document.getElementsByClassName("map-input");
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
            position: { lat: latitude, lng: longitude }
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