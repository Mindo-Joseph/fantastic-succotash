$(document).ready(function () {
    var selected_address = '';
    const styles = [{"stylers":[{"visibility":"on"},{"saturation":-100},{"gamma":0.54}]},{"featureType":"road","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"water","stylers":[{"color":"#4d4946"}]},{"featureType":"poi","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"labels.text","stylers":[{"visibility":"simplified"}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"road.local","elementType":"labels.text","stylers":[{"visibility":"simplified"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"gamma":0.48}]},{"featureType":"transit.station","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"gamma":7.18}]}];
    $(document).on("click","#show_dir",function() {
        initMap2();
    });
    $(document).on("click", ".add-more-location",function() {
        let random_id = Date.now();
        let destination_location_template = _.template($('#destination_location_template').html());
        $("#location_input_main_div").append(destination_location_template({random_id:random_id})).show();
        initializeNew(random_id);
    });
    $(document).on("click", ".location-inputs .apremove",function() {
        var rel = $(this).data('rel');
        $('#dots_'+rel).remove();
    });
    function initializeNew(random_id) {
      var input2 = document.getElementById('destination_location_'+random_id);
      if(input2){
        var autocomplete = new google.maps.places.Autocomplete(input2);
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var place2 = autocomplete.getPlace();
            $('#destination_location_latitude_'+random_id).val(place2.geometry.location.lat());
            $('#destination_location_longitude_'+random_id).val(place2.geometry.location.lng());
        });
      }
    }
    $(document).on("click",".search-location-result",function() {
        $('#pickup_location').val($(this).data('address'));
        var latitude = $(this).data('latitude');
        var longitude = $(this).data('longitude');
        displayLocation(latitude, longitude);
    });
    function getVendorList(){
        $('.location-list').hide();
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
                        if(response.data.length == 1){
                            $('.vendor-list').trigger('click');
                            $('.table-responsive').remove();
                        }
                    }else{
                        $("#vendor_main_div").html('<p class="text-center my-3">No result found. Please try a new search</p>').show();
                    }
                }
            }
        });
    }
    $(document).on("click",".vendor-list",function() {
        var locations = [];
        let vendor_id = $(this).data('vendor');
        var latitudes = $('input[name="latitude[]"]').map(function(){
           return this.value;
        }).get();
        var longitudes = $('input[name="longitude[]"]').map(function(){
           return this.value;
        }).get();
        $(latitudes).each(function(index, latitude) {
            var data = {};
            data.latitude = latitude;
            data.longitude = longitudes[index];
            locations.push(data);
        });
        var post_data = JSON.stringify(locations);
        $.ajax({
            type: "POST",
            dataType: 'json',
            data: {locations:post_data},
            url: get_vehicle_list+'/'+vendor_id,
            success: function(response) {
                if(response.status == 'Success'){
                    $('#search_product_main_div').html('');
                    if(response.data.length != 0){
                        let products_template = _.template($('#products_template').html());
                        $("#search_product_main_div").append(products_template({results: response.data.products})).show();
                    }else{
                        $("#search_product_main_div ").html('<p class="text-center my-3">No result found. Please try a new search</p>').show();
                    }
                }
            }
        });
    });
    $(document).on("click",".promo_code_list_btn_cab_booking",function() {
        let amount = $(this).data('amount');
        let vendor_id = $(this).data('vendor_id');
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: promo_code_list_url,
            data: {amount:amount, vendor_id:vendor_id},
            success: function(response) {
                if(response.status == 'Success'){
                    $('#cab_booking_promo_code_list_main_div').html('');
                    if(response.data.length != 0){
                        $('.promo-box').removeClass('d-none');
                        $('.cab-detail-box').addClass('d-none');
                        let cab_booking_promo_code_template = _.template($('#cab_booking_promo_code_template').html());
                        $("#cab_booking_promo_code_list_main_div").append(cab_booking_promo_code_template({promo_codes: response.data})).show();
                    }else{
                        $("#cab_booking_promo_code_list_main_div").html(no_coupon_available_message).show();
                    }
                }
            }
        });
        
    });
    $(document).on("click",".close-promo-code-detail-box",function() {
        $('.promo-box').addClass('d-none');
        $('.cab-detail-box').removeClass('d-none');
    });
    $(document).on("click",".close-cab-detail-box",function() {
        $('.cab-detail-box').addClass('d-none');
        $('.address-form').removeClass('d-none');
    });
    $(document).on("click",".vehical-view-box",function() {
        let product_id = $(this).data('product_id');
        $.ajax({
            data: {},
            type: "POST",
            dataType: 'json',
            url: get_product_detail+'/'+product_id,
            success: function(response) {
                if(response.status == 'Success'){
                    $('#cab_detail_box').html('');
                    if(response.data.length != 0){
                        $('.address-form').addClass('d-none');
                        $('.cab-detail-box').removeClass('d-none');
                        let cab_detail_box_template = _.template($('#cab_detail_box_template').html());
                        $("#cab_detail_box").append(cab_detail_box_template({result: response.data})).show();
                        getDistance();
                    }else{
                        $("#cab_detail_box ").html('<p class="text-center my-3">No result found. Please try a new search</p>').show();
                    }
                }
            }
        });
    });
    function initMap2() {
        var locations = [];
        let pickup_location_latitude = $('#pickup_location_latitude').val();
        let pickup_location_longitude = $('#pickup_location_longitude').val();
        var latitudes = $('input[name="destination_location_latitude[]"]').map(function(){
           return this.value;
        }).get();
        var longitudes = $('input[name="destination_location_latitude[]"]').map(function(){
           return this.value;
        }).get();
        $(latitudes).each(function(index, latitude) {
            var data = {};
            data.latitude = latitude;
            data.longitude = longitudes[index];
            locations.push(data);
        });
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
              var point = response.routes[0].legs[0];
              console.log(point.duration.text);
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
            getVendorList();
        });
      }
    }
    function getDistance(){
     //Find the distance
     var distanceService = new google.maps.DistanceMatrixService();
     distanceService.getDistanceMatrix({
        origins: [$("#pickup_location").val()],
        destinations: [$("#destination_location").val()],
        travelMode: google.maps.TravelMode.WALKING,
        unitSystem: google.maps.UnitSystem.METRIC,
        durationInTraffic: true,
        avoidHighways: false,
        avoidTolls: false
    },
    function (response, status) {
        if (status !== google.maps.DistanceMatrixStatus.OK) {
            console.log('Error:', status);
        } else {
            console.log('distance is'+response.rows[0].elements[0].distance.text);
            console.log('duration is'+response.rows[0].elements[0].duration.text);
            $("#distance").text(response.rows[0].elements[0].distance.text).show();
            $("#duration").text(response.rows[0].elements[0].duration.text).show();
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