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
                <h2 class="mb-3">{{$page_detail->primary->title}}</h2>
                <p>{!!$page_detail->primary->description!!}</p>
            </div>
        </div>
        @if($page_detail->id == 3)
            <form class="vendor-signup" id="vendor_signup_form">
                <div class="alert alert-success" role="alert" id="success_msg" style="display:none;"></div>
                <div class="row">
                    <div class="col-lg-8 offset-lg-2">
                        <div class="row">
                            <div class="col-12">
                                <h2>Personal Details.</h2>
                            </div>    
                        </div>
                        <div class="needs-validation vendor-signup">
                            <div class="form-row">
                                <div class="col-md-6 mb-3" id="full_nameInput">
                                    <label for="fullname">Full name</label>
                                    <input type="text" class="form-control" name="full_name" value="">
                                    <div class="invalid-feedback" id="full_name_error"><strong></strong></div>
                                </div>
                                <div class="col-md-6 mb-3" id="phone_noInput">
                                    <label for="validationCustom02">Phone No.</label>
                                    <input type="text" class="form-control" name="phone_no" value="" id="phone">
                                    <div class="invalid-feedback" id="phone_no_error"><strong></strong></div>
                                    <input type="hidden" id="countryData" name="countryData" value="us">
                                    <input type="hidden" id="dialCode" name="dialCode" value="1">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-4 mb-3" id="emailInput">
                                    <label for="email">Email</label>
                                    <input type="text" class="form-control" name="email" value="" required="">
                                    <div class="invalid-feedback" id="email_error"><strong></strong></div>
                                </div>
                                <div class="col-md-4 mb-3" id="passwordInput">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" name="password" value="" required="">
                                    <div class="invalid-feedback" id="password_error"><strong></strong></div>
                                </div>
                                 <div class="col-md-4 mb-3" id="confirm_passwordInput">
                                    <label for="confirm_password">Confirm Password</label>
                                    <input type="password" class="form-control" name="confirm_password" value="" required="">
                                    <div class="invalid-feedback"><strong></strong></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <h2>Store Details.</h2>
                                </div>    
                            </div>
                            <div class="form-row">
                                <div class="col-md-4 mb-3">
                                    <label for="">Upload Logo</label>
                                    <div class="file file--upload">
                                        <label for="input_file_logo">
                                            <span class="update_pic">
                                                <img src="" id="upload_logo_preview">
                                            </span>
                                            <span class="plus_icon">
                                                <i class="fas fa-plus"></i>
                                            </span>
                                        </label>
                                        <input id="input_file_logo" type="file" name="upload_logo" accept="image/*">
                                    </div>
                                </div>      
                                <div class="col-md-8 mb-3">
                                    <label for="">Upload Banner</label>
                                    <div class="file file--upload">
                                        <label for="input_file_banner">
                                            <span class="update_pic">
                                                <img src="" id="upload_banner_preview">
                                            </span>
                                            <span class="plus_icon"><i class="fas fa-plus"></i></span>
                                        </label>
                                        <input id="input_file_banner" type="file" name="upload_banner" accept="image/*">
                                    </div>
                                </div>      
                            </div>
                            <div class="form-row">
                                <div class="col-md-12 mb-3" id="nameInput">
                                    <label for="validationCustom01">Vendor Name</label>
                                    <input type="text" class="form-control" name="name" value="">
                                    <div class="invalid-feedback" id="name_error"><strong></strong></div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="validationCustom02">Description</label>
                                    <textarea class="form-control" name="vendor_description" cols="30" rows="3"></textarea>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-6 mb-3" id="addressInput">
                                    <label for="validationCustom01">Address</label>
                                    <input type="text" class="form-control" name="address" value="" id="address">
                                    <input type="hidden" class="form-control" name="longitude" value="" id="longitude">
                                    <input type="hidden" class="form-control" name="latitude" value="" id="latitude">
                                    <input type="hidden" class="form-control" name="pincode" value="" id="pincode">
                                    <input type="hidden" class="form-control" name="city" value="" id="city">
                                    <input type="hidden" class="form-control" name="state" value="" id="state">
                                    <input type="hidden" class="form-control" name="country" value="" id="country">
                                    <div class="invalid-feedback" id="address_error"><strong></strong></div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="validationCustom02">Website</label>
                                    <input type="text" class="form-control" name="website" value="">
                                    <div class="valid-feedback"></div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-2 mb-3">
                                    <label for="">Dine In</label>
                                    <div class="toggle-icon">
                                        <input type="checkbox" id="dine-in" name="dine_in"><label for="dine-in">Toggle</label>
                                    </div>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label for="">Takeaway</label>
                                    <div class="toggle-icon">
                                        <input type="checkbox" id="takeaway" name="takeaway"><label for="takeaway">Toggle</label>
                                    </div>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label for="">Delivery</label>
                                    <div class="toggle-icon">
                                        <input type="checkbox" id="delivery" name="delivery"><label for="delivery">Toggle</label>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-solid mt-3 w-100" type="button" id="register_btn">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        @endif
    </div>
</section>
@endsection
@section('script')
<script src="{{asset('assets/js/intlTelInput.js')}}"></script>
<script src="{{asset('front-assets/js/jquery.exitintent.js')}}"></script>
<script src="{{asset('front-assets/js/fly-cart.js')}}"></script>
<script type="text/javascript">

    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        function readURL(input, previewId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $(previewId).attr('src',e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
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
            var form = document.getElementById('vendor_signup_form');
            var formData = new FormData(form);
            $.ajax({
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                url: "{{ route('vendor.register') }}",
                headers: {
                    Accept: "application/json"
                },
                success: function(data) {
                    if (data.status == 'success') {
                        $("#vendor_signup_form")[0].reset();
                        $('#success_msg').html(data.message).show();
                        setTimeout(function() {
                            $('#success_msg').html('').hide();
                        }, 3000);
                    }
                },
                error: function(response) {
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
@endsection