@extends('layouts.store', ['title' => 'Verify Account'])
@section('content')
<style type="text/css">
    a.disabled {
      pointer-events: none;
      cursor: default;
    }
    input[type="email"]:disabled {
      background: #dddddd;
    }
    input[type="text"]:disabled {
      background: #dddddd;
    }
</style>
<header>
    <div class="mobile-fix-option"></div>
    @include('layouts.store/left-sidebar')
</header>
<section class="wrapper-main mb-5 py-lg-5">
    <div class="container">
        <script type="text/template" id="email_verified_template">
            <img src="{{asset('front-assets/images/verified.svg')}}" alt="">
            <h3 class="mb-2">Email Address Verified!</h3>
            <p>You have successfully verified the <br> email account.</p>
        </script>
        <script type="text/template" id="phone_verified_template">
            <img src="{{asset('front-assets/images/verified.svg')}}" alt="">
            <h3 class="mb-2">Phone Verified!</h3>
            <p>You have successfully verified the <br> Phone.</p>
        </script>
        <div class="row">
            @if($preference->verify_email == 1)
                <div class="col-lg-6 mb-lg-0 mb-3 text-center  pb-4 {{$user->is_phone_verified == 0 && $preference->verify_phone == 1 ? 'border-right' : 'offset-lg-3'}}" id="verify_email_main_div">
                    @if($user->is_email_verified == 0)
                        <img class="h-45" src="{{asset('front-assets/images/email_icon.svg')}}" alt="">
                        <h3 class="mb-2">Verify Email Address</h3>
                        <p>Enter the code we just sent you on your email address</p>
                        <div class="row mt-3">
                            <div class="offset-xl-3 col-xl-6 text-left">
                                <div class="verify_id input-group mb-3">
                                    <input type="email" id="email" class="form-control" value="{{Auth::user()->email}}" disabled="">
                                    <div class="input-group-append">
                                        <a class="input-group-text" id="edit_email" href="javascript:void(0)">Edit</a>
                                    </div>
                                    <span class="valid-feedback d-block text-center" role="alert">
                                        <strong class="edit_email_feedback"></strong>
                                    </span>
                                </div>
                                <div method="get" class="digit-group otp_inputs d-flex justify-content-between" data-group-name="digits" data-autosubmit="false" autocomplete="off">
                                    <input class="form-control" type="text" id="digit-1" name="digit-1" data-next="digit-2" onkeypress="return isNumberKey(event)"/>
                                    <input class="form-control" type="text" id="digit-2" name="digit-2" data-next="digit-3" data-previous="digit-1" onkeypress="return isNumberKey(event)"/>
                                    <input class="form-control" type="text" id="digit-3" name="digit-3" data-next="digit-4" data-previous="digit-2" onkeypress="return isNumberKey(event)"/>
                                    <input class="form-control" type="text" id="digit-4" name="digit-4" data-next="digit-5" data-previous="digit-3" onkeypress="return isNumberKey(event)"/>
                                    <input class="form-control" type="text" id="digit-5" name="digit-5" data-next="digit-6" data-previous="digit-4" onkeypress="return isNumberKey(event)"/>
                                    <input class="form-control" type="text" id="digit-6" name="digit-6" data-next="digit-7" data-previous="digit-5" onkeypress="return isNumberKey(event)"/>
                                </div>
                                <strong class="invalid-feedback2 invalid_email_otp_error"></strong>
                                <div class="row text-center mt-2">
                                    <div class="col-12 resend_txt">
                                        <p class="mb-1">If you didn’t receive a code?</p>
                                        <a class="verifyEmail" href="javascript:void(0)"><u>RESEND</u></a>
                                    </div>
                                    <div class="col-md-12 mt-3">
                                        <button type="button" class="btn btn-solid" id="verify_email_token">Verify</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <img src="{{asset('front-assets/images/verified.svg')}}" alt="">
                        <h3 class="mb-2">Email Address Verified!</h3>
                        <p>You have successfully verified the <br> email account.</p>
                    @endif
                </div>
            @endif
            @if($preference->verify_phone == 1 && $user->is_phone_verified == 0)
                <div class="col-lg-6 text-center {{$user->verify_phone == 1 && $preference->is_phone_verified == 0 ? '' : 'offset-lg-0'}}" id="verify_phone_main_div">
                    @if($user->is_phone_verified == 0)
                    <img class="h-45" src="{{asset('front-assets/images/phone-otp.svg')}}">
                    <h3 class="mb-2">Verify Phone</h3>
                    <p>Enter the code we just sent you on your email address</p>
                    <div class="row mt-3">
                        <div class="offset-xl-3 col-xl-6 text-left">
                            <div class="verify_id input-group mb-3">
                                <input type="text" class="form-control" id="phone_number" value="{{Auth::user()->phone_number}}" disabled="">
                                <div class="input-group-append">
                                    <a class="input-group-text" id="edit_phone" href="javascript:void(0)">Edit</a>
                                </div>
                                <span class="valid-feedback d-block text-center" role="alert">
                                    <strong class="edit_phone_feedback"></strong>
                                </span>
                            </div>
                            <div method="get" class="digit-group otp_inputs d-flex justify-content-between" data-group-name="digits" data-autosubmit="false" autocomplete="off">
                                <input class="form-control" type="text" id="digit-1" name="digit-1" data-next="digit-2" onkeypress="return isNumberKey(event)"/>
                                <input class="form-control" type="text" id="digit-2" name="digit-2" data-next="digit-3" data-previous="digit-1" onkeypress="return isNumberKey(event)"/>
                                <input class="form-control" type="text" id="digit-3" name="digit-3" data-next="digit-4" data-previous="digit-2" onkeypress="return isNumberKey(event)"/>
                                <input class="form-control" type="text" id="digit-4" name="digit-4" data-next="digit-5" data-previous="digit-3" onkeypress="return isNumberKey(event)"/>
                                <input class="form-control" type="text" id="digit-5" name="digit-5" data-next="digit-6" data-previous="digit-4" onkeypress="return isNumberKey(event)"/>
                                <input class="form-control" type="text" id="digit-6" name="digit-6" data-next="digit-7" data-previous="digit-5" onkeypress="return isNumberKey(event)"/>
                            </div>
                            <strong class="invalid-feedback2 invalid_phone_otp_error text-center"></strong>
                            <div class="row text-center mt-2">
                                <div class="col-12 resend_txt">
                                    <p class="mb-1">If you didn’t receive a code?</p>
                                    <a class="verifyPhone"><u>RESEND</u></a>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <button type="button" class="btn btn-solid" id="verify_phone_token">Verify</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <img src="{{asset('front-assets/images/verified.svg')}}" alt="">
                    <h3 class="mb-2">Phone Verified!</h3>
                    <p>You have successfully verified the <br> Phone.</p>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</section> 
@endsection
@section('script')
<script type="text/javascript">
    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : evt.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
    var ajaxCall = 'ToCancelPrevReq';
    $('#edit_email').click(function() {
        if ($(this).text() == "Edit")
            $(this).text("Save")
        else
           $(this).text("Edit");
            verifyUser('email');
            $('#email').prop('disabled', function(i, v) { return !v; });
            $('#email').focus();
    });
    $('#edit_phone').click(function() {
        $('#phone_number').prop('disabled', function(i, v) { return !v; });
    });
    $('.verifyEmail').click(function() {
        verifyUser('email');
    });
    $('.verifyPhone').click(function() {
        verifyUser('phone');
    });
    function verifyUser($type = 'email') {
        if($type == 'email'){
            $('.verifyEmail').addClass('disabled').html('SENDING...');
        }else if ($type == 'phone') {
            $('.verifyPhone').addClass('disabled').html('SENDING...');
        }
        ajaxCall = $.ajax({
            type: "post",
            dataType: "json",
            url: "{{ route('email.send', Auth::user()->id) }}",
            data: {"_token": "{{ csrf_token() }}",type: $type,},
            success: function(response) {
                if($type == 'email'){
                    $('.verifyEmail').removeClass('disabled').html('RESEND');
                }else{
                    $('.verifyPhone').removeClass('disabled').html('RESEND');
                }
                if($type == 'email'){
                 $('.edit_email_feedback').html(response.message);
                }else{
                 $('.edit_phone_feedback').html(response.message);
                }
            }
        });
    }
    $('.digit-group').find('input').each(function() {
        $(this).attr('maxlength', 1);
        $(this).on('keyup', function(e) {
            var parent = $($(this).parent());
            if(e.keyCode === 8 || e.keyCode === 37) {
                var prev = parent.find('input#' + $(this).data('previous'));
                if(prev.length) {
                    $(prev).select();
                }
            } else if((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 65 && e.keyCode <= 90) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode === 39) {
                var next = parent.find('input#' + $(this).data('next'));
                if(next.length) {
                    $(next).select();
                } else {
                    if(parent.data('autosubmit')) {
                        parent.submit();
                    }
                }
            }
        });
    });
    $("#verify_phone_token").click(function(event) {
        var verifyToken = '';
        $('.digit-group').find('input').each(function() {
            if($(this).val()){
               verifyToken +=  $(this).val();
            }
        });
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "{{ route('user.verifyToken') }}",
            data: {'verifyToken':verifyToken, 'type': 'phone'},
            success: function(response) {
                $("#verify_phone_main_div").html('');
                let phone_verified_template = _.template($('#phone_verified_template').html());
                $("#verify_phone_main_div").append(phone_verified_template());
            },
            error: function(data) {
                $(".invalid_phone_otp_error").html(data.responseJSON.error);
            },
        });
    });
    $("#verify_email_token").click(function(event) {
        var verifyToken = '';
        $('.digit-group').find('input').each(function() {
            if($(this).val()){
               verifyToken +=  $(this).val();
            }
        });
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "{{ route('user.verifyToken') }}",
            data: {'verifyToken':verifyToken, 'type': 'email'},
            success: function(response) {
                $("#verify_email_main_div").html('');
                let email_verified_template = _.template($('#email_verified_template').html());
                $("#verify_email_main_div").append(email_verified_template());
            },
            error: function(data) {
                $(".invalid_email_otp_error").html(data.responseJSON.error);
            },
        });
    });
</script>
@endsection