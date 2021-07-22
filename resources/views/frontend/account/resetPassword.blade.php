@extends('layouts.store', ['title' => 'Register'])
@section('css')
<style type="text/css">
    .main-menu .brand-logo {
        display: inline-block;
        padding-top: 20px;
        padding-bottom: 20px;
    }
    .productVariants .firstChild{
        min-width: 150px;
        text-align: left !important;
        border-radius: 0% !important;
        margin-right: 10px;
        cursor: default;
        border: none !important;
    }
    .product-right .color-variant li, .productVariants .otherChild{
        height: 35px;
        width: 35px;
        border-radius: 50%;
        margin-right: 10px;
        cursor: pointer;
        border: 1px solid #f7f7f7;
        text-align: center;
    }
    .productVariants .otherSize{
        height: auto !important;
        width: auto !important;
        border: none !important;
        border-radius: 0%;
    }
    .product-right .size-box ul li.active {
        background-color: inherit;
    }
    .iti__flag-container li, .flag-container li{
        display: block;
    }
    .iti.iti--allow-dropdown, .allow-dropdown {
        position: relative;
        display: inline-block;
        width: 100%;
    }
    .iti.iti--allow-dropdown .phone, .flag-container .phone {
        padding: 17px 0 17px 100px !important;
    }
    .social-logins{
        text-align: center;
    }
    .social-logins img{
        width: 100px;
        height: 100px;
        border-radius: 100%;
        margin-right: 20px;
    }
    .register-page .theme-card .theme-form input {
        margin-bottom: 5px;
    }
    .invalid-feedback{
        display: block;
    }
</style>
<link rel="stylesheet" href="{{asset('assets/css/intlTelInput.css')}}">
@endsection
@section('content')
<header>
    <div class="mobile-fix-option"></div>
    @include('layouts.store/left-sidebar')
</header>
<section class="register-page section-b-space">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h3>Reset Password</h3>
                  <div class="theme-card"> 
                    <form name="register" id="register" action="{{route('customer.resetPass')}}" class="theme-form" method="post"> @csrf
                        <div class="form-row mb-3">
                            <div class="col-md-6">
                                <label for="otp">OTP</label>
                                <input type="text" class="form-control" id="name" placeholder="OTP" required="" name="otp">
                                @if($errors->first('otp'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('otp') }}</strong>
                                    </span>
                                @endif 
                                @if(\Session::has('err_otp'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{!! \Session::get('err_otp') !!}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" placeholder="Email" required="" name="email">
                                @if($errors->first('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                                @if(\Session::has('err_email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{!! \Session::get('err_email') !!}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-row mb-3">
                            <div class="col-md-6">
                                <label for="review">Password</label>
                                <input type="password" class="form-control" id="review" placeholder="Enter your password" required="" name="new_password">
                                @if($errors->first('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                                @if(\Session::has('err_pass'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{!! \Session::get('err_pass') !!}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="review">Confirm Password</label>
                                <input type="password" class="form-control" id="review" placeholder="Confirm password" required="" name="confirm_password">
                                @if($errors->first('confirm_password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('confirm_password') }}</strong>
                                    </span>
                                @endif
                                @if(\Session::has('err_cf'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{!! \Session::get('err_cf') !!}</strong>
                                    </span>
                                @endif
                            </div>
                            <input type="hidden" name="device_type" value="web">
                            <input type="hidden" name="device_token" value="web">
                            <input type="hidden" id="countryData" name="countryData" value="us">
                            <button type="submit" class="btn btn-solid mt-3 submitRegister">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
<script src="{{asset('assets/js/intlTelInput.js')}}"></script>
<script>
    var input = document.querySelector("#phone");
    window.intlTelInput(input, {
        separateDialCode: true,
        hiddenInput: "full_number",
        utilsScript: "{{asset('assets/js/utils.js')}}",
    });
    $(document).ready(function () {
        $("#phone").keypress(function (e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
            return true;
        });
    });
    $('.iti__country').click(function(){
        var code = $(this).attr('data-country-code');
        $('#countryData').val(code);
    })
</script>
@endsection