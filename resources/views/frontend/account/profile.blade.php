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
                    <div class="collection-mobile-back"><span class="filter-back"><i class="fa fa-angle-left"
                                aria-hidden="true"></i> back</span></div>
                    <div class="block-content">
                        <ul>
                            <li class="active"><a href="{{route('user.profile')}}">Account Info</a></li>
                            <li><a href="{{route('user.addressBook')}}">Address Book</a></li>
                            <li><a href="{{route('user.orders')}}">My Orders</a></li>
                            <li><a href="{{route('user.wishlists')}}">My Wishlist</a></li>
                            <li><a href="{{route('user.account')}}">My Wallet</a></li>
                            <li><a href="{{route('user.changePassword')}}">Change Password</a></li>
                            <li class="last"><a href="{{route('user.logout')}}">Log Out</a></li>
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
                                    <div class="file file--upload">
                                        <label>
                                            <span class="update_pic">
                                                <img src="{{asset('assets/images/products/product-1.png')}}" alt="">
                                            </span>
                                        </label>
                                    </div>
                                    <div class="name_location">
                                        <h5 class="mt-0 mb-1"> Test</h5>
                                        <p class="m-0"><i class="fa fa-map-marker mr-1" aria-hidden="true"></i>
                                            Chandigarh</p>
                                    </div>
                                </div>
                                <div class="col-sm-6 text-center text-md-right mt-3 mt-md-0">
                                    <a class="btn btn-solid" data-toggle="modal" data-target="#refferal-modal"
                                        href="javascript:void(0)">Edit Profile</a>
                                </div>
                            </div>
                        </div>
                        <hr class="mt-2">
                        <div class="welcome-msg">
                            <h4 class="d-flex align-items-center justify-content-between m-0">
                                <span>Your Refferal Code:
                                    {{(isset($userRefferal['refferal_code'])) ? $userRefferal['refferal_code'] : ''}}</span>
                                <a href="{{route('user.sendRefferal')}}">Send Refferal</a>
                            </h4>
                        </div>

                        <div class="row mt-3 profile-page">
                            <div class="col-lg-4">

                                <div class="card-box">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h6 class="mt-0">Contact Details</h6>
                                                <a class="profile-edit" href="contact_details_popup.html">Edit</a>
                                            </div>

                                            <div class="info-text mb-2">
                                                <label class="m-0">Name</label>
                                                <p>Chander Mohan</p>
                                            </div>

                                            <div class="info-text mb-2">
                                                <label class="m-0">Email</label>
                                                <p>Chander123@yopmail.com</p>
                                            </div>

                                            <div class="info-text mb-2">
                                                <label class="m-0">Phone Number</label>
                                                <p>8521354681</p>
                                            </div>

                                            <div class="info-text mb-2">
                                                <label class="mb-1">Time Zone</label>
                                                {!! $timezone_list !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="card-box py-4 px-0 mb-3">
                                    
                                    <div class="d-flex align-items-center justify-content-between px-4 mb-2">
                                        <h6 class="m-0">Shipping Details</h6>
                                    </div>

                                    <div class="row px-4 align-items-center pb-1">
                                        <div class="col-md-8">
                                            <div class="address_txt">
                                                <p class="m-0 text-16">Chander</p>
                                            </div>
                                            <div class="address_box mt-1 p-0">
                                                <p>3065 Kirlin Prairie Suit 200, Dubai, UAE, 160036 </p>
                                                <p>832-050-8020</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 d-flex justify-content-end mt-md-0 mt-3">
                                            <div class="address_btn">
                                                <a href="#">Edit</a>
                                                <a href="#">Remove</a>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="line_divider my-2">

                                    <div class="row px-4 align-items-center">
                                        <div class="col-md-8">
                                            <div class="address_txt">
                                                <p class="m-0 text-16">Mohan</p>
                                            </div>
                                            <div class="address_box mt-1 p-0">
                                                <p>47 Lehner Mount Suite 045, Dubai, UAE, 160001 </p>
                                                <p>997-593-5277</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 d-flex justify-content-end mt-md-0 mt-3">
                                            <div class="address_btn">
                                                <a href="#">Edit</a>
                                                <a href="#">Remove</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-box p-4 mb-3">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <h6 class="m-0">About Me</h6>
                                        <a class="profile-edit" href="about_me_popup.html">Edit</a>
                                    </div>
                                    <div class="text-16">
                                        <p class="m-0">Women to share their wardrobes securely and in seconds. We’re on a
                                        mission to democratise luxury and make fashion circular.</p>
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
<div class="modal fade refferal_modal" id="refferal-modal" tabindex="-1" aria-labelledby="refferal-modalLabel"
    aria-hidden="true">
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
                                <div
                                    class="code-outer p-2 text-uppercase d-flex align-items-center justify-content-between">
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
                                <div
                                    class="code-outer p-2 text-uppercase d-flex align-items-center justify-content-between">
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
                                <div
                                    class="code-outer p-2 text-uppercase d-flex align-items-center justify-content-between">
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
            error: function(data) {},
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