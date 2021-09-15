@extends('layouts.store', ['title' => 'Home'])
@section('css')
<link rel="stylesheet" href="{{asset('assets/css/intlTelInput.css')}}">
@endsection
@section('content')
<header>
    <div class="mobile-fix-option"></div>
    @include('layouts.store/left-sidebar')
</header>
<section class="section-b-space new-pages pb-250">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-3">Driver Registration</h2>
                <p>We provide Visitors (as defined below) with access to the Website and Registered Members (as defined below) with access to the Platform subject to the following Terms of Use. By browsing the public areas of the Website, you acknowledge that you have read, understood, and agree to be legally bound by these Terms of Use and our Privacy Policy, which is hereby incorporated by reference (collectively, this “Agreement”). If you do not agree to any of these terms, then please do not use the Website, the App, and/or the Platform. We may change the terms and conditions of these Terms of Use from time to time with or without notice to you.</p>
            </div>
        </div>
        <form class="vendor-signup" id="vendor_signup_form" method="POST" action="{{route('page.driverSignup')}}" enctype="multipart/form-data">
            @csrf
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="alert alert-success" role="alert" id="success_msg" style="display:none;"></div>
                    <div class="row">
                        <div class="col-12">
                            <h2>{{__('Personal Details.')}}</h2>
                        </div>
                    </div>
                    <div class="needs-validation vendor-signup">
                        <div class="form-group">
                            <label for="">{{__('UPLOAD PROFILE PHOTO')}}</label>
                            <div class="file file--upload">
                                <label for="input_file_logo">
                                    <span class="update_pic">
                                        <img src="" id="upload_logo_preview">
                                    </span>
                                    <span class="plus_icon">
                                        <i class="fa fa-plus"></i>
                                    </span>
                                </label>
                                <input id="input_file_logo" type="file" name="upload_photo" accept="image/*">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-3" id="phone_numberInput">
                                <div class="form-group" id="nameInputEdit">
                                    <label for="name" class="control-label">NAME</label>
                                    <input type="text" class="form-control" id="name" placeholder="John Doe" name="name" value="">
                                    <span class="invalid-feedback" role="alert">
                                        <strong></strong>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3" id="phone_numberInput">
                                <label for="validationCustom02">{{__('CONTACT NUMBER.')}}</label>
                                <input type="tel" class="form-control" name="phone_number" id="phone">
                                <div class="invalid-feedback" id="phone_number_error"><strong></strong></div>
                                <input type="hidden" id="countryData" name="countryData" value="us">
                                <input type="hidden" id="dialCode" name="dialCode" value="">
                            </div>
                            <div class="col-md-4 mb-3" id="full_nameInput">
                                <div class="form-group" id="typeInputEdit">
                                    <label for="type" class="control-label">TYPE</label>
                                    <select class="form-control" data-style="btn-light" name="type" id="type">
                                        <option value="Employee">Employee</option>
                                        <option value="Freelancer">Freelancer</option>
                                    </select>
                                    <span class="invalid-feedback" role="alert">
                                        <strong></strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-md-12">
                                <div class="form-group" id="vehicle_type_idInput">
                                    <p class="text-muted mt-3 mb-2">TRANSPORT TYPE</p>
                                    <div class="radio radio-blue form-check-inline click cursors">
                                        <input type="radio" id="onfoot" value="1" name="vehicle_type_id" act="add" checked>
                                        <img id="foot_add" src="{{asset('assets/icons/walk.png')}}" style="float:right;padding-right:40px;">
                                    </div>
                                    <div class="radio radio-primery form-check-inline click cursors">
                                        <input type="radio" id="bycycle" value="2" name="vehicle_type_id" act="add">
                                        <img id="cycle_add" src="{{asset('assets/icons/cycle.png')}}" style="float:right;padding-right:40px;">
                                    </div>
                                    <div class="radio radio-info form-check-inline click cursors">
                                        <input type="radio" id="motorbike" value="3" name="vehicle_type_id" act="add">
                                        <img id="bike_add" src="{{asset('assets/icons/bike.png')}}" style="float:right;padding-right:40px;">
                                    </div>
                                    <div class="radio radio-danger form-check-inline click cursors">
                                        <input type="radio" id="car" value="4" name="vehicle_type_id" act="add">
                                        <img id="cars_add" src="{{asset('assets/icons/car.png')}}" style="float:right;padding-right:40px;">
                                    </div>
                                    <div class="radio radio-warning form-check-inline click cursors">
                                        <input type="radio" id="truck" value="5" name="vehicle_type_id" act="add">
                                        <img id="trucks_add" src="{{asset('assets/icons/truck.png')}}" style="float:right;padding-right:40px;">
                                    </div>
                                    <span class="invalid-feedback" role="alert">
                                        <strong></strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row ">
                        <div class="col-md-6">
                            <div class="form-group" id="make_modelInputEdit">
                                <label for="make_model" class="control-label">TRANSPORT DETAILS</label>
                                <input type="text" class="form-control" id="make_model" placeholder="Year, Make, Model" name="make_model" value="">
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" id="make_modelInput1">
                                <label for="make_model" class="control-label">UID</label>
                                <input type="text" class="form-control" id="uid" placeholder="897abd" name="uid" value="">
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row ">
                        <div class="col-md-6">
                            <div class="form-group" id="plate_numberInputEdit">
                                <label for="plate_number" class="control-label">LICENCE PLATE</label>
                                <input type="text" class="form-control" id="plate_number" name="plate_number" placeholder="508.KLV" value="">
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" id="colorInputEdit">
                                <label for="color" class="control-label">COLOR</label>
                                <input type="text" class="form-control" id="color" name="color" placeholder="Color" value="">
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        @foreach($driver_registration_documents as $driver_registration_document)
                        <div class="col-md-6 mb-3" id="{{$driver_registration_document->primary->slug}}Input">
                            <label for="">{{$driver_registration_document->primary ? $driver_registration_document->primary->name : ''}}</label>
                            @if(strtolower($driver_registration_document->file_type) == 'text')
                            <div class="form-group">
                                <input type="text" class="form-control" id="input_file_logo_{{$driver_registration_document->id}}" name="{{$driver_registration_document->primary->slug}}" placeholder="Enter Text" value="">
                            </div>
                            @else
                            <div class="file file--upload">
                                <label for="input_file_logo_{{$driver_registration_document->id}}">
                                    <span class="update_pic pdf-icon">
                                        <img src="" id="upload_logo_preview_{{$driver_registration_document->id}}">
                                    </span>
                                    <span class="plus_icon" id="plus_icon_{{$driver_registration_document->id}}">
                                        <i class="fa fa-plus"></i>
                                    </span>
                                </label>
                                @if(strtolower($driver_registration_document->file_type) == 'image')
                                <input id="input_file_logo_{{$driver_registration_document->id}}" type="file" name="{{$driver_registration_document->primary->slug}}" v accept="image/*" data-rel="{{$driver_registration_document->id}}">
                                @elseif(strtolower($driver_registration_document->file_type) == 'pdf')
                                <input id="input_file_logo_{{$driver_registration_document->id}}" type="file" name="{{$driver_registration_document->primary->slug}}" accept=".pdf" data-rel="{{$driver_registration_document->id}}">
                                @endif
                                <div class="invalid-feedback" id="{{$driver_registration_document->primary->slug}}_error"><strong></strong></div>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    <button class="btn btn-solid mt-3 w-100" dir="ltr" data-style="expand-right" id="register_btn" type="button">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" id="register_btn_loader" style="display:none !important;"></span>
                        <span class="ladda-label">{{__('Submit')}}</span>
                    </button>
                </div>
            </div>
    </div>
    </form>
    </div>
</section>
@endsection
@section('script')
<script src="{{ asset('assets/js/jquery.tagsinput-revisited.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/css/jquery.tagsinput-revisited.css') }}" />
<script src="{{asset('assets/js/intlTelInput.js')}}"></script>
<script src="{{asset('front-assets/js/jquery.exitintent.js')}}"></script>
<script src="{{asset('front-assets/js/fly-cart.js')}}"></script>
<script type="text/javascript">
    var text_image = "{{url('images/104647.png')}}";

    $(document).ready(function() {
        makeTag();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        function getExtension(filename) {
            return filename.split('.').pop().toLowerCase();
        }
        $("#phone").keypress(function(e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
            return true;
        });

        function readURL(input, previewId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                var extension = getExtension(input.files[0].name);
                reader.onload = function(e) {
                    if (extension == 'pdf') {
                        $(previewId).attr('src', 'https://image.flaticon.com/icons/svg/179/179483.svg');
                    } else if (extension == 'csv') {
                        $(previewId).attr('src', text_image);
                    } else if (extension == 'txt') {
                        $(previewId).attr('src', text_image);
                    } else if (extension == 'xls') {
                        $(previewId).attr('src', text_image);
                    } else if (extension == 'xlsx') {
                        $(previewId).attr('src', text_image);
                    } else {
                        $(previewId).attr('src', e.target.result);
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $(document).on('change', '[id^=input_file_logo_]', function(event) {
            var rel = $(this).data('rel');
            // $('#plus_icon_'+rel).hide();
            readURL(this, '#upload_logo_preview_' + rel);
        });
        $("#input_file_logo").change(function() {
            readURL(this, '#upload_logo_preview');
        });
        $("#input_file_banner").change(function() {
            readURL(this, '#upload_banner_preview');
        });
        var input = document.querySelector("#phone");
        window.intlTelInput(input, {
            separateDialCode: true,
            hiddenInput: "full_number",
            utilsScript: "{{asset('assets/js/utils.js')}}",
            initialCountry: "{{ Session::get('default_country_code','US') }}",
        });

        function initialize() {
            var input = document.getElementById('address');
            var autocomplete = new google.maps.places.Autocomplete(input);
            google.maps.event.addListener(autocomplete, 'place_changed', function() {
                var place = autocomplete.getPlace();
                document.getElementById('longitude').value = place.geometry.location.lng();
                document.getElementById('latitude').value = place.geometry.location.lat();
                for (let i = 1; i < place.address_components.length; i++) {
                    let mapAddress = place.address_components[i];
                    if (mapAddress.long_name != '') {
                        let streetAddress = '';
                        if (mapAddress.types[0] == "street_number") {
                            streetAddress += mapAddress.long_name;
                        }
                        if (mapAddress.types[0] == "route") {
                            streetAddress += mapAddress.short_name;
                        }
                        if ($('#street').length > 0) {
                            document.getElementById('street').value = streetAddress;
                        }
                        if (mapAddress.types[0] == "locality") {
                            document.getElementById('city').value = mapAddress.long_name;
                        }
                        if (mapAddress.types[0] == "administrative_area_level_1") {
                            document.getElementById('state').value = mapAddress.long_name;
                        }
                        if (mapAddress.types[0] == "postal_code") {
                            document.getElementById('pincode').value = mapAddress.long_name;
                        } else {
                            document.getElementById('pincode').value = '';
                        }
                        if (mapAddress.types[0] == "country") {
                            var country = document.getElementById('country');
                            for (let i = 0; i < country.options.length; i++) {
                                if (country.options[i].text.toUpperCase() == mapAddress.long_name.toUpperCase()) {
                                    country.value = country.options[i].value;
                                    break;
                                }
                            }
                        }
                    }
                }
            });
        }
        $('.iti__country').click(function() {
            var code = $(this).attr('data-country-code');
            $('#countryData').val(code);
            var dial_code = $(this).attr('data-dial-code');
            $('#dialCode').val(dial_code);
        });
        $('#register_btn').click(function() {
            var that = $(this);
            $(this).attr('disabled', true);
            $('#register_btn_loader').show();
            $('.form-control').removeClass("is-invalid");
            $('.invalid-feedback').children("strong").html('');
            var form = document.getElementById('vendor_signup_form');
            var formData = new FormData(form);
            $.ajax({
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                url: "{{ route('page.driverSignup') }}",
                headers: {
                    Accept: "application/json"
                },
                success: function(data) {
                    $('#register_btn_loader').hide();
                    that.attr('disabled', false);
                    if (data.status == 'success') {
                        $('input[type=file]').val('');
                        $("#vendor_signup_form")[0].reset();
                        $('#vendor_signup_form img').attr('src', '');
                        $('html,body').animate({
                            scrollTop: '0px'
                        }, 1000);
                        $('#success_msg').html(data.message).show();
                        setTimeout(function() {
                            $('#success_msg').html('').hide();
                        }, 3000);
                    }
                },
                error: function(response) {
                    that.attr('disabled', false);
                    $('html,body').animate({
                        scrollTop: '0px'
                    }, 1000);
                    $('#register_btn_loader').hide();
                    if (response.status === 422) {
                        let errors = response.responseJSON.errors;
                        Object.keys(errors).forEach(function(key) {
                            $("#" + key + "Input input").addClass("is-invalid");
                            $("#" + key + "_error").children("strong").text(errors[key][0]).show();
                            $("#" + key + "Input div.invalid-feedback").show();
                        });
                    } else {
                        $(".show_all_error.invalid-feedback").show();
                        $(".show_all_error.invalid-feedback").text('Something went wrong, Please try Again.');
                    }
                }
            });
        });
    });
</script>
<script type="text/javascript">
    var tagList = "{{$showTag}}";
    tagList = tagList.split(',');

    function makeTag() {
        $('.myTag1').tagsInput({
            'autocomplete': {
                source: tagList,
            }
        });
    }
    var mobile_number = '';
    // $('#add-agent-modal .xyz').val(mobile_number.getSelectedCountryData().dialCode); 
    $('#add-agent-modal .xyz').change(function() {
        var phonevalue = $('.xyz').val();
        $("#countryCode").val(mobile_number.getSelectedCountryData().dialCode);
    });
    function phoneInput() {
        console.log('phone working');
        var input = document.querySelector(".xyz");

        var mobile_number_input = document.querySelector(".xyz");
        mobile_number = window.intlTelInput(mobile_number_input, {
            separateDialCode: true,
            hiddenInput: "full_number",
            utilsScript: "{{ asset('telinput/js/utils.js') }}",
        });
    }
    $(document).ready(function() {
        jQuery('#onfoot').click();
    });
    $(document).on('click', '.click', function() { //alert('a');
        var radi = $(this).find('input[type="radio"]');
        radi.prop('checked', true);
        var check = radi.val();
        var act = radi.attr('act');
        switch (check) {
            case "1":
                $("#foot_" + act).attr("src", "{{ asset('assets/icons/walk_blue.png') }}");
                $("#cycle_" + act).attr("src", "{{ asset('assets/icons/cycle.png') }}");
                $("#bike_" + act).attr("src", "{{ asset('assets/icons/bike.png') }}");
                $("#cars_" + act).attr("src", "{{ asset('assets/icons/car.png') }}");
                $("#trucks_" + act).attr("src", "{{ asset('assets/icons/truck.png') }}");
                break;
            case "2":
                $("#foot_" + act).attr("src", "{{ asset('assets/icons/walk.png') }}");
                $("#cycle_" + act).attr("src", "{{ asset('assets/icons/cycle_blue.png') }}");
                $("#bike_" + act).attr("src", "{{ asset('assets/icons/bike.png') }}");
                $("#cars_" + act).attr("src", "{{ asset('assets/icons/car.png') }}");
                $("#trucks_" + act).attr("src", "{{ asset('assets/icons/truck.png') }}");
                break;
            case "3":
                $("#foot_" + act).attr("src", "{{ asset('assets/icons/walk.png') }}");
                $("#cycle_" + act).attr("src", "{{ asset('assets/icons/cycle.png') }}");
                $("#bike_" + act).attr("src", "{{ asset('assets/icons/bike_blue.png') }}");
                $("#cars_" + act).attr("src", "{{ asset('assets/icons/car.png') }}");
                $("#trucks_" + act).attr("src", "{{ asset('assets/icons/truck.png') }}");
                break;
            case "4":
                $("#foot_" + act).attr("src", "{{ asset('assets/icons/walk.png') }}");
                $("#cycle_" + act).attr("src", "{{ asset('assets/icons/cycle.png') }}");
                $("#bike_" + act).attr("src", "{{ asset('assets/icons/bike.png') }}");
                $("#cars_" + act).attr("src", "{{ asset('assets/icons/car_blue.png') }}");
                $("#trucks_" + act).attr("src", "{{ asset('assets/icons/truck.png') }}");
                break;
            case "5":
                $("#foot_" + act).attr("src", "{{ asset('assets/icons/walk.png') }}");
                $("#cycle_" + act).attr("src", "{{ asset('assets/icons/cycle.png') }}");
                $("#bike_" + act).attr("src", "{{ asset('assets/icons/bike.png') }}");
                $("#cars_" + act).attr("src", "{{ asset('assets/icons/car.png') }}");
                $("#trucks_" + act).attr("src", "{{ asset('assets/icons/truck_blue.png') }}");
                break;
        }
    });
    /* Get agent by ajax */
    $(".editIcon").click(function(e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();
        var uid = $(this).attr('agentId');
        $.ajax({
            type: "get",
            url: "<?php echo url('agent'); ?>" + '/' + uid + '/edit',
            data: '',
            dataType: 'json',
            success: function(data) {
                $('#edit-agent-modal #editCardBox').html(data.html);
                $('#edit-agent-modal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                makeTag();
                phoneInput();
                //$('.dropify').dropify();
                var imgs = $('#profilePic').attr('showImg');
                $('#profilePic').attr("data-default-file", imgs);
                $('#profilePic').dropify();
                $('').dropify();
            },
            error: function(data) {
                console.log('data2');
            }
        });
    });
    /* add Team using ajax*/
    // $("#add-agent-modal #submitAgent").submit(function(e) {

    // });
    $("#submitAgent").submit(function(e) {
        e.preventDefault();
        // $(document).on('click', '.submitAgentForm', function() { 
        var form = document.getElementById('submitAgent');
        var formData = new FormData(form);
        var urls = "{{URL::route('page.driverSignup')}}";
        saveTeam(urls, formData, inp = '', modal = 'add-agent-modal');
    });
    /* edit Team using ajax*/
    $("#edit-agent-modal #UpdateAgent").submit(function(e) {
        e.preventDefault();
    });
    $(document).on('click', '.submitEditForm', function() {
        var form = document.getElementById('UpdateAgent');
        var formData = new FormData(form);
        var urls = document.getElementById('agent_id').getAttribute('url');
        saveTeam(urls, formData, inp = 'Edit', modal = 'edit-agent-modal');
        console.log(urls);
    });
    function saveTeam(urls, formData, inp = '', modal = '') {
        $.ajax({
            method: 'post',
            headers: {
                Accept: "application/json"
            },
            url: urls,
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.status == 'success') {
                    $("#" + modal + " .close").click();
                    location.reload();
                } else {
                    $(".show_all_error.invalid-feedback").show();
                    $(".show_all_error.invalid-feedback").text(response.message);
                }
                return response;
            },
            error: function(response) {
                if (response.status === 422) {
                    let errors = response.responseJSON.errors;
                    Object.keys(errors).forEach(function(key) {
                        $("#" + key + "Input" + inp + " input").addClass("is-invalid");
                        $("#" + key + "Input" + inp + " span.invalid-feedback").children("strong").text(errors[key][0]);
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
    /* Get agent by ajax */
    $(".submitpayreceive").click(function(e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();
        $.ajax({
            type: "post",
            url: "",
            data: '',
            dataType: 'json',
            success: function(data) {
                $('#edit-agent-modal #editCardBox').html(data.html);
                $('#edit-agent-modal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                makeTag();
                //$('.dropify').dropify();
                var imgs = $('#profilePic').attr('showImg');
                $('#profilePic').attr("data-default-file", imgs);
                $('#profilePic').dropify();
                $('').dropify();
            },
            error: function(data) {
                console.log('data2');
            }
        });
    });
</script>
@endsection