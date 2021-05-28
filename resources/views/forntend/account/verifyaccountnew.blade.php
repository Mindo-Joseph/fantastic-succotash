@extends('layouts.store', ['title' => 'Verify Account'])
@section('content')
<header>
    <div class="mobile-fix-option"></div>
    @include('layouts.store/left-sidebar')
</header>
<section class="wrapper-main mb-5 py-lg-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mb-lg-0 mb-3 text-center border-right pb-4">
                <img src="{{asset('front-assets/images/email_icon.svg')}}" alt="">
                <h3 class="mb-2">Verify Email Address</h3>
                <p>Enter the code we just sent you on your email address</p>
                <div class="row mt-3">
                    <div class="offset-xl-3 col-xl-6 text-left">
                        <div class="verify_id input-group mb-3">
                            <input type="text" class="form-control" value="natish.designer@gmail.com" placeholder="natish.designer@gmail.com" aria-label="" aria-describedby="">
                            <div class="input-group-append">
                                <span class="input-group-text" id="">Edit</span>
                            </div>
                        </div>
                        <div method="get" class="digit-group otp_inputs d-flex justify-content-around" data-group-name="digits" data-autosubmit="false" autocomplete="off">
                            <input class="form-control" type="text" id="digit-1" name="digit-1" data-next="digit-2" />
                            <input class="form-control" type="text" id="digit-2" name="digit-2" data-next="digit-3" data-previous="digit-1" />
                            <input class="form-control" type="text" id="digit-3" name="digit-3" data-next="digit-4" data-previous="digit-2" />
                            <input class="form-control" type="text" id="digit-4" name="digit-4" data-next="digit-5" data-previous="digit-3" />
                        </div>
                        <div class="row text-center mt-2">
                            <div class="col-12 resend_txt">
                                <p class="mb-1">If you didn’t receive a code?</p>
                                <a href="#"><u>RESEND</u></a>
                            </div>
                            <div class="col-md-12 mt-3">
                                <button type="submit" class="btn btn-solid submitLogin">Verify</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <img src="{{asset('front-assets/images/phone-otp.svg')}}" alt="">
                <h3 class="mb-2">Verify Phone</h3>
                <p>Enter the code we just sent you on your email address</p>
                <div class="row mt-3">
                    <div class="offset-xl-3 col-xl-6 text-left">
                        <div class="verify_id input-group mb-3">
                            <input type="text" class="form-control" placeholder="+91 8054433291" value="+91 8054433291" aria-label="" aria-describedby="">
                            <div class="input-group-append">
                                <span class="input-group-text" id="">Edit</span>
                            </div>
                        </div>
                        <div method="get" class="digit-group otp_inputs d-flex justify-content-around" data-group-name="digits" data-autosubmit="false" autocomplete="off">
                            <input class="form-control" type="text" id="digit-1" name="digit-1" data-next="digit-1" />
                            <input class="form-control" type="text" id="digit-2" name="digit-2" data-next="digit-2" data-previous="digit-1" />
                            <input class="form-control" type="text" id="digit-3" name="digit-3" data-next="digit-3" data-previous="digit-2" />
                            <input class="form-control" type="text" id="digit-4" name="digit-4" data-next="digit-4" data-previous="digit-3" />
                        </div>
                        <div class="row text-center mt-2">
                            <div class="col-12 resend_txt">
                                <p class="mb-1">If you didn’t receive a code?</p>
                                <a href="#"><u>RESEND</u></a>
                            </div>
                            <div class="col-md-12 mt-3">
                                <button type="submit" class="btn btn-solid submitLogin">Verify</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> 
@endsection
@section('script')
<script type="text/javascript">
    var ajaxCall = 'ToCancelPrevReq';
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
            data: {
                "_token": "{{ csrf_token() }}",
                "type": $type,
            },
            beforeSend: function() {
                if (ajaxCall != 'ToCancelPrevReq' && ajaxCall.readyState < 4) {
                    ajaxCall.abort();
                }
            },
            success: function(response) {
                console.log(response);
                $("#exampleModalCenter").modal("show")
            },
            error: function(data) {

            },
        });
    }

    $("#verifyToken").submit(function(event) {
        event.preventDefault();  
       console.log("fregwr");
       var form = document.getElementById('verifyToken');
        var formData = new FormData(form);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        
        $.ajax({
            type: "post",
            dataType: "json",
            url: "{{ route('user.verifyToken') }}",
            data: formData,
            contentType: false,
            processData: false,
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