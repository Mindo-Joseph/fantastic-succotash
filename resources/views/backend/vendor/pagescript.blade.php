<script>
    $('.openAddModal').click(function(){
        $('#add-form').modal({
            //backdrop: 'static',
            keyboard: false
        });
        //runPicker();
        $('.dropify').dropify();
        $('.selectize-select').selectize();
        autocompletesWraps.push('add');
        loadMap(autocompletesWraps);
    });

    function runPicker(){
        $('.datetime-datepicker').flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i"
        });

        $('.selectpicker').selectpicker();
    }

    var autocomplete = {};
        var autocompletesWraps = [];
        var count = 1; editCount = 0;
    $(document).ready(function(){
        autocompletesWraps.push('def');
        loadMap(autocompletesWraps);
    });

    function loadMap(autocompletesWraps){

        console.log(autocompletesWraps);
        $.each(autocompletesWraps, function(index, name) {
            const geocoder = new google.maps.Geocoder;

            if($('#'+name).length == 0) {
                return;
            }
            //autocomplete[name] = new google.maps.places.Autocomplete(('.form-control')[0], { types: ['geocode'] }); console.log('hello');
            autocomplete[name] = new google.maps.places.Autocomplete(document.getElementById(name+"-address"), { types: ['geocode'] });
            google.maps.event.addListener(autocomplete[name], 'place_changed', function() {
                
                var place = autocomplete[name].getPlace();

                geocoder.geocode({'placeId': place.place_id}, function (results, status) {
                    
                    if (status === google.maps.GeocoderStatus.OK) {
                        const lat = results[0].geometry.location.lat();
                        const lng = results[0].geometry.location.lng();
                        document.getElementById(name+'_latitude').value = lat;
                        document.getElementById(name+'_longitude').value = lng;
                    }
                });
            });
        });

    }
    $('#show-map-modal').on('hide.bs.modal', function () {
         $('#add-customer-modal').removeClass('fadeIn');

    });

    $(document).on('click', '.showMap', function(){
        var no = $(this).attr('num');
        console.log(no);

        var lats = document.getElementById(no+'_latitude').value;
        var lngs = document.getElementById(no+'_longitude').value;
        console.log(lats + '--' + lngs);

        document.getElementById('map_for').value = no;

        if(lats == null || lats == '0'){
            lats = 30.53899440;
        }
        if(lngs == null || lngs == '0'){
            lngs = 75.95503290;
        }

        var myLatlng = new google.maps.LatLng(lats, lngs);
            var mapProp = {
                center:myLatlng,
                zoom:13,
                mapTypeId:google.maps.MapTypeId.ROADMAP
              
            };
            var map=new google.maps.Map(document.getElementById("googleMap"), mapProp);
                var marker = new google.maps.Marker({
                  position: myLatlng,
                  map: map,
                  title: 'Hello World!',
                  draggable:true  
              });
            document.getElementById('lat_map').value= lats;
            document.getElementById('lng_map').value= lngs ; 
            // marker drag event
            google.maps.event.addListener(marker,'drag',function(event) {
                document.getElementById('lat_map').value = event.latLng.lat();
                document.getElementById('lng_map').value = event.latLng.lng();
            });

            //marker drag event end
            google.maps.event.addListener(marker,'dragend',function(event) {
                var zx =JSON.stringify(event);
                console.log(zx);


                document.getElementById('lat_map').value = event.latLng.lat();
                document.getElementById('lng_map').value = event.latLng.lng();
                //alert("lat=>"+event.latLng.lat());
                //alert("long=>"+event.latLng.lng());
            });
            $('#add-customer-modal').addClass('fadeIn');
        $('#show-map-modal').modal({
            //backdrop: 'static',
            keyboard: false
        });

    });

    $(document).on('click', '.selectMapLocation', function () {

        var mapLat = document.getElementById('lat_map').value;
        var mapLlng = document.getElementById('lng_map').value;
        var mapFor = document.getElementById('map_for').value;

        document.getElementById(mapFor+'_latitude').value = mapLat;
        document.getElementById(mapFor+'_longitude').value = mapLlng;

        $('#show-map-modal').modal('hide');
    });

    $(".openEditModal").click(function (e) {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();

        var uri =  "{{ isset($vendor) ? route('vendor.edit', $vendor->id) : '' }}";

        console.log(uri);
        $.ajax({
            type: "get",
            url: uri,
            data: '',
            dataType: 'json',
            success: function (data) {
                $('#edit-form #editCardBox').html(data.html);

                $('#edit-form').modal({
                    backdrop: 'static',
                    keyboard: false
                });

                $('.dropify').dropify();
                $('.selectize-select').selectize();
                dine = document.getElementsByClassName('dine_in');
                var switchery = new Switchery(dine[0]);

                take = document.getElementsByClassName('takeaway');
                var switchery = new Switchery(take[0]);

                deli = document.getElementsByClassName('delivery');
                var switchery = new Switchery(deli[0]);
                
                autocompletesWraps.push('edit');
                loadMap(autocompletesWraps);              
            },
            error: function (data) {
                console.log('data2');
            },
            beforeSend: function(){
                $(".loader_box").show();
            },
            complete: function(){
                $(".loader_box").hide();
            }
        });
    });

    $(document).on('click', '.submitAddForm', function(e) { 
        e.preventDefault();
        var form =  document.getElementById('save_banner_form');
        var formData = new FormData(form);
        var url = "{{route('vendor.store')}}";
        saveData(formData, 'add', url );

    });

    $(document).on('click', '.submitEditForm', function(e) { 
        e.preventDefault();
        var form =  document.getElementById('save_edit_banner_form');
        var formData = new FormData(form);
        var url =  "{{ isset($vendor) ? route('vendor.update', $vendor->id) : ''}}";

        saveData(formData, 'edit', url);

    });

    function saveData(formData, type, data_uri){
        console.log(data_uri);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "post",
            headers: {
                Accept: "application/json"
            },
            url: data_uri,
            data: formData,
            contentType: false,
            processData: false,
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
            beforeSend: function(){
                $(".loader_box").show();
            },
            complete: function(){
                $(".loader_box").hide();
            },
            error: function(response) {
                if (response.status === 422) {
                    let errors = response.responseJSON.errors;
                    Object.keys(errors).forEach(function(key) {
                        $("#" + key + "Input input").addClass("is-invalid");
                        $("#" + key + "Input span.invalid-feedback").children("strong").text(errors[key][0]);
                        $("#" + key + "Input span.invalid-feedback").show();
                    });
                } else {
                    $(".show_all_error.invalid-feedback").show();
                    $(".show_all_error.invalid-feedback").text('Something went wrong, Please try Again.');
                }
                return response;
            }
        });
    }



   /* 

    $(document).on('change', '.assignToSelect', function(){
        var val = $(this).val();
        if(val == 'category'){
            $('.modal .category_vendor').show();
            $('.modal .category_list').show();
            $('.modal .vendor_list').hide();
        }else if(val == 'vendor'){
            $('.modal .category_vendor').show();
            $('.modal .category_list').hide();
            $('.modal .vendor_list').show();
        }else{
            $('.modal .category_vendor').hide();
            $('.modal .category_list').hide();
            $('.modal .vendor_list').hide();
        }
    });

    $("#banner-datatable tbody").sortable({
        placeholder : "ui-state-highlight",
        update  : function(event, ui)
        {
            var post_order_ids = new Array();
            $('#post_list tr').each(function(){
                post_order_ids.push($(this).data("row-id"));
            });
            console.log(post_order_ids);
            saveOrder(post_order_ids);
        }
    });
    var CSRF_TOKEN = $("input[name=_token]").val();
    function saveOrder(orderVal){

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "post",
            dataType: "json",
            url: "{{ url('client/banner/saveOrder') }}",
            data: {
                _token: CSRF_TOKEN,
                order: orderVal
            },
            success: function(response) {

                if (response.status == 'success') {
                }
                return response;
            }
        });
    }

    */
/*          Add on  set   script          */
    
$(".openAddonModal").click(function (e) {
    $('#addAddonmodal').modal({
        backdrop: 'static',
        keyboard: false
    });
    
});

$(document).on('click', '.addOptionRow-Add',function (e) {
    var $tr = $('.optionTableAdd tbody>tr:first').next('tr');
    console.log('asasd');
    var $clone = $tr.clone();
    $clone.find(':text').val('');
    $clone.find('.lasttd').html('<a href="javascript:void(0);" class="action-icon deleteCurRow"> <i class="mdi mdi-delete"></i></a>');
    $('.optionTableAdd').append($clone);
});

$(document).on('click', '.addOptionRow-edit',function (e) {
    var $tr = $('.optionTableEdit tbody>tr:first').next('tr');
    var $clone = $tr.clone();
    $clone.find(':text').val('');
    $clone.find(':hidden').val('');
    $clone.find('.lasttd').html('<a href="javascript:void(0);" class="action-icon deleteCurRow"> <i class="mdi mdi-delete"></i></a>');
    $('.optionTableEdit').append($clone);
});
$("#addAddonmodal").on('click', '.deleteCurRow', function () {
    $(this).closest('tr').remove();
});

$("#editdAddonmodal").on('click', '.deleteCurRow', function () {
    $(this).closest('tr').remove();
});

$(document).on('click', '.deleteAddon', function(){
       
    var did = $(this).attr('dataid');
    if(confirm("Are you sure? You want to delete this addon set.")) {
        $('#addonDeleteForm'+did).submit();
    }
    return false;
});

$('.editAddonBtn').on('click', function(e) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
    e.preventDefault();

    var did = $(this).attr('dataid');
    $.ajax({
        type: "get",
        url: "<?php echo url('client/addon'); ?>" + '/' + did + '/edit',
        data: '',
        dataType: 'json',
        success: function (data) {

            $('#editdAddonmodal').modal({
                backdrop: 'static',
                keyboard: false
            });
            $('#editAddonForm #editAddonBox').html(data.html);

            document.getElementById('editAddonForm').action = data.submitUrl;
        },
        beforeSend: function(){
                $(".loader_box").show();
        },
        complete: function(){
            $(".loader_box").hide();
        },
        error: function (data) {
            console.log('data2');
        }
    });
});
</script>