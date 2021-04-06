@extends('layouts.store', ['title' => 'Login'])

@section('css')
<style type="text/css">
    .main-menu .brand-logo {
        display: inline-block;
        padding-top: 20px;
        padding-bottom: 20px;
    }
</style>
    
@endsection

@section('content')

 <header>
    <div class="mobile-fix-option"></div>
    @include('layouts.store/left-sidebar')
</header>
<style type="text/css">
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
</style>

<section class="login-page section-b-space">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <h3>Login</h3>
                <div class="theme-card">
                    @if(session('preferences')->fb_login == 1 || session('preferences')->twitter_login == 1 || session('preferences')->google_login == 1 || session('preferences')->apple_login == 1)
                        <div class="form-row mb-5">
                            <h3>Social Login</h3>
                            <div class="col-md-12">
                                <div class="social-logins">
                                    @if(session('preferences')->fb_login == 1)
                                        <a href="{{url('auth/facebook')}}"><img src="{{asset('assets/images/social-fb-login.png')}}"></a>
                                    @endif
                                    @if(session('preferences')->twitter_login == 1)
                                        <a href="{{url('auth/twitter')}}"><img src="{{asset('assets/images/twitter-login.png')}}"></a>
                                    @endif
                                    @if(session('preferences')->google_login == 1)
                                        <a href="{{url('auth/google')}}"><img src="{{asset('assets/images/google-login.png')}}"> </a>
                                    @endif
                                    @if(session('preferences')->apple_login == 1)
                                        <img src="{{asset('assets/images/apple-login.png')}}">
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                    <form name="customerLogin" id="customerLogin" action="{{route('customer.login')}}" class="theme-form" type="post" method="post"> @csrf
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" class="form-control" id="email" placeholder="Email" required="">
                        </div>
                        <div class="form-group">
                            <label for="review">Password</label>
                            <input type="password" class="form-control" id="review"
                                placeholder="Enter your password" required="">
                        </div>
                        <input type="hidden" name="device_type" value="web">
                        <input type="hidden" name="device_token" value="web">
                        <button type="submit" class="btn btn-solid">Login</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-6 right-login">
                <h3>New Customer</h3>
                <div class="theme-card authentication-right">
                    <h6 class="title-font">Create A Account</h6>
                    <p>Sign up for a free account at our store. Registration is quick and easy. It allows you to be
                        able to order from our shop. To start shopping click register.</p><a href="#"
                        class="btn btn-solid">Create an Account</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')

@endsection
