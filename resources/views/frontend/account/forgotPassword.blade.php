@extends('layouts.store', ['title' => 'Forgot Password'])
@section('css')
<style type="text/css">
    .main-menu .brand-logo {
        display: inline-block;
        padding-top: 20px;
        padding-bottom: 20px;
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
                <h3>Enter Email Address</h3>
                <div class="theme-card">
                    <form name="register" id="register" action="{{route('customer.forgotPass')}}" class="theme-form" method="post"> @csrf
                        <div class="form-row mb-3">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="email" class="form-control" id="email" placeholder="Enter Email" required="" name="email" value="">
                                    <button class="btn input-group-text btn-dark waves-effect waves-light" type="button">Send Password Reset Link</button>
                                </div>
                                @if($errors->first('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection