@extends('layouts.store', ['title' => 'Verify Account'])
@section('content')
<header>
    <div class="mobile-fix-option"></div>
    @include('layouts.store/left-sidebar')
</header>
<section class="wrapper-main mb-5 py-lg-5">
    <div class="container">
        <div class="row">
            @if($preference->verify_email == 1)
            <div class="col-lg-6 mb-lg-0 mb-3 text-center border-right pb-4">
                <img src="{{asset('front-assets/images/email_icon.svg')}}" alt="">
                <h3 class="mb-2">Verify Email Address</h3>
                <p>Enter the code we just sent you on your email address</p>
                <div class="row mt-3">
                    <div class="offset-xl-3 col-xl-6 text-left">
                        <div class="verify_id input-group mb-3">
                            <input type="email" id="email" class="form-control" value="{{Auth::user()->email}}" disabled="">
                            <div class="input-group-append">
                                <span class="input-group-text" id="edit_email">Edit</span>
                            </div>
                            <span class="valid-feedback d-block" role="alert">
                                <strong class="invalid-feedback2"></strong>
                            </span>
                        </div>
                        <div method="get" class="digit-group otp_inputs d-flex justify-content-between" data-group-name="digits" data-autosubmit="false" autocomplete="off">
                            <input class="form-control" type="text" id="digit-1" name="digit-1" data-next="digit-2" />
                            <input class="form-control" type="text" id="digit-2" name="digit-2" data-next="digit-3" data-previous="digit-1" />
                            <input class="form-control" type="text" id="digit-3" name="digit-3" data-next="digit-4" data-previous="digit-2" />
                            <input class="form-control" type="text" id="digit-4" name="digit-4" data-next="digit-5" data-previous="digit-3" />
                            <input class="form-control" type="text" id="digit-5" name="digit-5" data-next="digit-6" data-previous="digit-4" />
                            <input class="form-control" type="text" id="digit-6" name="digit-6" data-next="digit-7" data-previous="digit-5" />
                        </div>
                        <div class="row text-center mt-2">
                            <div class="col-12 resend_txt">
                                <p class="mb-1">If you didn’t receive a code?</p>
                                <a class="verifyEmail"><u>RESEND</u></a>
                            </div>
                            <div class="col-md-12 mt-3">
                                <button type="submit" class="btn btn-solid submitLogin">Verify</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @if($preference->verify_phone == 1)
            <div class="col-lg-6 text-center">
                <img src="{{asset('front-assets/images/phone-otp.svg')}}" alt="">
                <h3 class="mb-2">Verify Phone</h3>
                <p>Enter the code we just sent you on your email address</p>
                <div class="row mt-3">
                    <div class="offset-xl-3 col-xl-6 text-left">
                        <div class="verify_id input-group mb-3">
                            <input type="text" class="form-control" id="phone_number" value="{{Auth::user()->phone_number}}" disabled="">
                            <div class="input-group-append">
                                <span class="input-group-text" id="edit_phone">Edit</span>
                            </div>
                        </div>
                        <div method="get" class="digit-group otp_inputs d-flex justify-content-between" data-group-name="digits" data-autosubmit="false" autocomplete="off">
                            <input class="form-control" type="text" id="digit-1" name="digit-1" data-next="digit-1" />
                            <input class="form-control" type="text" id="digit-2" name="digit-2" data-next="digit-2" data-previous="digit-1" />
                            <input class="form-control" type="text" id="digit-3" name="digit-3" data-next="digit-3" data-previous="digit-2" />
                            <input class="form-control" type="text" id="digit-4" name="digit-4" data-next="digit-4" data-previous="digit-3" />
                            <input class="form-control" type="text" id="digit-5" name="digit-5" data-next="digit-5" data-previous="digit-4" />
                            <input class="form-control" type="text" id="digit-6" name="digit-6" data-next="digit-6" data-previous="digit-5" />
                        </div>
                        <div class="row text-center mt-2">
                            <div class="col-12 resend_txt">
                                <p class="mb-1">If you didn’t receive a code?</p>
                                <a class="verifyPhone"><u>RESEND</u></a>
                            </div>
                            <div class="col-md-12 mt-3">
                                <button type="submit" class="btn btn-solid submitLogin">Verify</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</section> 
@endsection
@section('script')
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
    var ajaxCall = 'ToCancelPrevReq';
    $('#edit_email').click(function() {
        $('#email').prop('disabled', function(i, v) { return !v; });
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
        ajaxCall = $.ajax({
            type: "post",
            dataType: "json",
            url: "{{ route('email.send', Auth::user()->id) }}",
            data: {"_token": "{{ csrf_token() }}",type: $type,},
            success: function(response) {
                $('.valid-feedback').html(response.message);
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
    $("#verifyToken").submit(function(event) {
        event.preventDefault();  
        var formData = new FormData(form);
        var form = document.getElementById('verifyToken');
        $.ajax({
            type: "post",
            data: formData,
            dataType: "json",
            contentType: false,
            processData: false,
            url: "{{ route('user.verifyToken') }}",
            success: function(response) {
                console.log(response);
                window.location.href = "{{route('user.verify')}}";
            },
            error: function(data) {
                $(".invalid-feedback2").html(data.responseJSON.error);
                console.log(data.responseJSON.error);
            },
        });
    });
</script>
@endsection