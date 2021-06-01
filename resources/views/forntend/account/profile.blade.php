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

<section class="section-b-space">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="account-sidebar"><a class="popup-btn">my account</a></div>
                <div class="dashboard-left">
                    <div class="collection-mobile-back"><span class="filter-back"><i class="fa fa-angle-left" aria-hidden="true"></i> back</span></div>
                    <div class="block-content">
                        <ul>
                            <li class="active"><a href="{{route('user.profile')}}">Account Info</a></li>
                            <li><a href="{{route('user.addressBook')}}">Address Book</a></li>
                            <li><a href="{{route('user.orders')}}">My Orders</a></li>
                            <li><a href="{{route('user.wishlists')}}">My Wishlist</a></li>
                            <li><a href="{{route('user.account')}}">My Account</a></li>
                            <li><a href="{{route('user.changePassword')}}">Change Password</a></li>
                            <li class="last"><a href="{{route('user.logout')}}" >Log Out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="dashboard-right">
                    <div class="dashboard">
                        <div class="page-title">
                            <h2>My Profile</h2>
                        </div>
                        <div class="card-box">
                            <div class="row align-items-center">
                                <div class="col-sm-6 d-flex align-items-center">
                                    <div class='file file--upload'>
                                        <label>
                                            <span class="update_pic">
                                                <img src="{{asset('assets/images/products/product-1.png')}}" alt="">
                                            </span>
                                        </label>
                        <div class="welcome-msg">
                            <h5>Hello, {{ucwords(Auth::user()->name)}} !</h5>

                            <h3>Your Refferal Code: {{(isset($userRefferal['refferal_code'])) ? $userRefferal['refferal_code'] : ''}}</h3>
                            <div class="box mb-2">
                               <a href="{{route('user.sendRefferal')}}">Send Refferal</a>
                            </div>

                            <p>From your My Account Dashboard you have the ability to view a snapshot of your recent
                                account activity and update your account information. Select a link below to view or
                                edit information.</p>
                        </div>
                        <div class="box-account box-info">
                            <div class="box-head">
                                <h2>Account Information</h2>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="box">
                                        <div class="box-title">
                                            <h3>Contact Information</h3><a href="#">Edit</a>
                                        </div>
                                        <div class="box-content">
                                            <h6>{{ucwords(Auth::user()->name)}}</h6>
                                            <h6>{{Auth::user()->email}}</h6>
                                            <h6>{{Auth::user()->phone_number}}</h6>
                                        </div>
                                    </div>
                                    <div class="name_location">
                                        <h5 class="mt-0 mb-1"> {{ucwords(Auth::user()->name)}}</h5>
                                        <p class="m-0"><i class="fa fa-map-marker mr-1" aria-hidden="true"></i> Chandigarh</p>
                                    </div>  
                                </div>
                                <div class="col-sm-6 text-center text-md-right mt-3 mt-md-0">
                                    <a class="btn btn-solid" data-toggle="modal" data-target="#refferal-modal" href="javascript:void(0)">Edit Profile</a>
                                </div>
                            </div>
                        </div>
                        <hr class="mt-2">
                        <div class="welcome-msg">
                            <h4 class="d-flex align-items-center justify-content-between m-0"><span>Your Refferal Code: {{$userRefferal['refferal_code']}}</span> <a href="{{route('user.sendRefferal')}}">Send Refferal</a></h4>                           
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-lg-7">
                                <div class="card-box">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-text mb-3">
                                                <label class="m-0">Name</label>
                                                <p>Chander Mohan</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-text mb-3">
                                                <label class="m-0">Email</label>
                                                <p>Chander123@yopmail.com</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-text mb-3">
                                                <label class="m-0">Phone Number</label>
                                                <p>8521354681</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-text mb-3">
                                                <label class="m-0">Time Zone</label>
                                                <p>Chander Mohan</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Refferal Code Popup -->
<div class="modal fade refferal_modal" id="refferal-modal" tabindex="-1" aria-labelledby="refferal-modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="refferal-modalLabel">Apply Coupon Code</h5>
        <button type="button" class="close top_right" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="code-input d-flex align-items-center">
            <input class="form-control" type="text">
            <button class="btn btn-solid">Apply</button>
        </div>
        <div class="coupon-box mt-4">
            <div class="row d-none">
                <div class="col-lg-6">
                    <div class="coupon-code">
                        <div class="p-2">
                            <img src="{{asset('assets/images/axis.png')}}" alt="">
                            <h6 class="mt-0">Get 50% off up to ₹100</h6>
                            <p class="m-0">Valid on order with items worth ₹159 or more.</p>
                        </div>
                        <hr class="m-0">
                        <div class="code-outer p-2 text-uppercase d-flex align-items-center justify-content-between">
                            <label class="m-0">Axisneo</label>
                            <a href="#">Apply</a>
                        </div>
                        <hr class="m-0">
                        <div class="offer-text p-2">
                            <p class="m-0">Add items worth ₹200 to apply this offer.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="coupon-code">
                        <div class="p-2">
                            <img src="{{asset('assets/images/ptm.png')}}" alt="">
                            <h6 class="mt-0">Get 50% off up to ₹100</h6>
                            <p class="m-0">Valid on order with items worth ₹159 or more.</p>
                        </div>
                        <hr class="m-0">
                        <div class="code-outer p-2 text-uppercase d-flex align-items-center justify-content-between">
                            <label class="m-0">Axisneo</label>
                            <a href="#">Apply</a>
                        </div>
                        <hr class="m-0">
                        <div class="offer-text p-2">
                            <p class="m-0">Add items worth ₹200 to apply this offer.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="coupon-code">
                        <div class="p-2">
                            <img src="{{asset('assets/images/pazapp.png')}}" alt="">
                            <h6 class="mt-0">Get 50% off up to ₹100</h6>
                            <p class="m-0">Valid on order with items worth ₹159 or more.</p>
                        </div>
                        <hr class="m-0">
                        <div class="code-outer p-2 text-uppercase d-flex align-items-center justify-content-between">
                            <label class="m-0">Axisneo</label>
                            <a href="#">Apply</a>
                        </div>
                        <hr class="m-0">
                        <div class="offer-text p-2">
                            <p class="m-0">Add items worth ₹200 to apply this offer.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 no-more-coupon text-center">
                    <p>No coupon available</p>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

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
            url: "{{ route('verifyInformation', Auth::user()->id) }}",
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
                var res = response.result;

            },
            error: function(data) {

            },
        });
    }
</script>
<script>
  var loadFile = function(event) {
    var output = document.getElementById('output');
    output.src = URL.createObjectURL(event.target.files[0]);
   };
</script>

@endsection