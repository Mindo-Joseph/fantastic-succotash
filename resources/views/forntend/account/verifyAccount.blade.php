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
    .productVariants .firstChild {
        min-width: 150px;
        text-align: left !important;
        border-radius: 0% !important;
        margin-right: 10px;
        cursor: default;
        border: none !important;
    }

    .product-right .color-variant li,
    .productVariants .otherChild {
        height: 35px;
        width: 35px;
        border-radius: 50%;
        margin-right: 10px;
        cursor: pointer;
        border: 1px solid #f7f7f7;
        text-align: center;
    }

    .productVariants .otherSize {
        height: auto !important;
        width: auto !important;
        border: none !important;
        border-radius: 0%;
    }

    .product-right .size-box ul li.active {
        background-color: inherit;
    }

    .login-page .theme-card .theme-form input {
        margin-bottom: 5px;
    }

    .invalid-feedback {
        display: block;
    }
</style>

<section class="login-page section-b-space">
    <div class="container">
        <div class="row">
            <h3>Verify Account</h3>
            <div class="col-lg-12">
                <div class="dashboard-right">
                    <div class="dashboard">
                        <div class="welcome-msg">
                            <h5>Hello, {{ucwords(Auth::user()->name)}} !</h5>
                            <p>To enjoy shopping from our website, Please verify below information. So you will not face any interruption in future.</p>
                        </div>
                        <div class="box-account box-info">
                            <div class="box-head">
                                <h2>Verify Information</h2>
                            </div>
                            <div class="row">
                                @if($preference->verify_email == 1)
                                <div class="col-sm-6">
                                    <div class="box">
                                        <div class="box-title">
                                            <h3>Email
                                                @if(Auth::user()->is_email_verified == 1)
                                                <a href="javascript:void" class="btn btn-sm"> <i class="fa fa-check"></i></a>
                                                @else
                                                <a href="javascript:void" class="btn btn-sm"> <i class="fa fa-times"></i></a>
                                                @endif
                                            </h3>
                                            <a class="verifyEmail">Verify Now</a>
                                        </div>
                                        <div class="box-content">
                                            <p>{{Auth::user()->email}}</p>
                                        </div>
                                        @if(\Session::has('err_user'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{!! \Session::get('err_user') !!}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                @endif
                                @if($preference->verify_phone == 1)
                                <div class="col-sm-6">
                                    <div class="box">
                                        <div class="box-title">
                                            <h3>Phone Number
                                                @if(Auth::user()->is_phone_verified == 1)
                                                <a href="javascript:void" class="btn btn-sm"> <i class="fa fa-check"></i></a>
                                                @else
                                                <a href="javascript:void" class="btn btn-sm"> <i class="fa fa-times"></i></a>
                                                @endif
                                            </h3>
                                            <a href="javascript:void" class="verifyPhone">Verify Now</a>
                                        </div>
                                        <div class="box-content">
                                            <p>{{Auth::user()->phone_number}}</p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="login-page section-b-space otp-section" style="display: none;">
    <div class="container">
        <div class="row">
            <div class="col-lg-5">
                <div class="dashboard-right">
                    <div class="dashboard">
                        <div class="box-account box-info">
                            <div class="box-head">
                                <h2>Enter OTP</h2>
                            </div>
                            <div class="row">
                                <form name="register" id="register" action="{{route('user.verifyToken')}}" class="theme-form" method="post"> @csrf

                                    <div class="modal-body">
                                        <div class="form-row mb-3">
                                            <div class="col-md-6">
                                                <label for="otp">OTP</label>
                                                <input type="hidden" class="form-control" id="name" value="email" required="" name="type">
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
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- <div class="modal otp_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enter OTP</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
        </div>
    </div>
</div> -->
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
                $(".otp-section").attr("style", "display:block")
            },
            error: function(data) {

            },
        });
    }
</script>

@endsection