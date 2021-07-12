@extends('layouts.store', ['title' => 'Register'])
@section('css')

<style type="text/css">
    .iti__flag-container li,
    .flag-container li {
        display: block;
    }
    .iti.iti--allow-dropdown,
    .allow-dropdown {
        position: relative;
        display: inline-block;
        width: 100%;
    }
    .iti.iti--allow-dropdown .phone,
    .flag-container .phone {
        padding: 17px 0 17px 100px !important;
    }
    .invalid-feedback {
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
<section class="wrapper-main mb-5 py-lg-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 mb-lg-0 mb-3 text-center">
                <h3 class="mb-2">New Customer</h3>
                @if(session('preferences'))
                    @if(session('preferences')->fb_login == 1 || session('preferences')->twitter_login == 1 || session('preferences')->google_login == 1 || session('preferences')->apple_login == 1)
                    <ul class="social-links d-flex align-items-center mx-auto mb-4 mt-3">
                        @if(session('preferences')->google_login == 1)
                            <li>
                                <a href="{{url('auth/google')}}">
                                    <img src="{{asset('front-assets/images/google.svg')}}" alt="">
                                </a>
                            </li>
                        @endif
                        @if(session('preferences')->fb_login == 1)
                            <li>
                                <a href="{{url('auth/facebook')}}">
                                    <img src="{{asset('front-assets/images/facebook.svg')}}" alt="">
                                </a>
                            </li>
                        @endif
                        @if(session('preferences')->twitter_login)
                            <li>
                                <a href="{{url('auth/twitter')}}">
                                    <img src="{{asset('front-assets/images/twitter.svg')}}" alt="">
                                </a>
                            </li>
                        @endif
                        @if(session('preferences')->apple_login == 1)
                            <li>
                                <a href="javascript::void(0);">
                                    <img src="{{asset('front-assets/images/apple.svg')}}">
                                </a>
                            </li>
                        @endif
                    </ul>
                    <div class="divider_line m-auto">
                        <span>OR</span>
                    </div>
                    @endif
                @endif
                <div class="row mt-3">
                    <div class="offset-xl-2 col-xl-8 text-left">
                         <form name="register" id="register" action="{{route('customer.register')}}" class="px-lg-4" method="post"> @csrf
                            <div class="row form-group mb-0">
                                <div class="col-md-6 mb-3">
                                    <label for="">Full Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Full Name" name="name" value="{{ old('name')}}">
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Phone No.</label>
                                    <input type="tel" class="form-control phone @error('phone_number') is-invalid @enderror" id="phone" placeholder="Phone Number" name="phone_number" value="{{ old('phone_number')}}">
                                    <input type="hidden" id="countryData" name="countryData" value="us">
                                    @error('phone_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>                                    
                            </div>
                            <div class="row form-group mb-0">
                                <div class="col-md-6 mb-3">
                                    <label for="">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" name="email" value="{{ old('email')}}">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="review" placeholder="Enter your password" name="password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @enderror
                                </div>                                    
                            </div>
                            <div class="row form-group mb-0 align-items-end">
                                <div class="col-md-6 mb-3">
                                    <label for="">Referral Code</label>
                                    <input type="text" class="form-control" id="refferal_code" placeholder="Refferal Code" name="refferal_code" value="{{ old('refferal_code', $code ?? '')}}">
                                    @if($errors->first('refferal_code'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('refferal_code') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-3">
                                    <input type="hidden" name="device_type" value="web">
                                    <input type="hidden" name="device_token" value="web">
                                    <button type="submit" class="btn btn-solid submitLogin w-100">Create An Account</button>
                                </div>                                    
                            </div>                               
                        </form>
                    </div>
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
    $(document).ready(function() {
        $("#phone").keypress(function(e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
            return true;
        });
    });
    $('.iti__country').click(function() {
        var code = $(this).attr('data-country-code');
        $('#countryData').val(code);
    });
</script>
@endsection