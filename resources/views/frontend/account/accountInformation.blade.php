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
        <div class="row mb-3">
            <div class="col-12">
                <a href="{{route('user.profile')}}"><i class="fa fa-arrow-left mr-2" aria-hidden="true"></i> Back to
                    Profile</a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 profile-sidebar">

                <div class="row">
                    <div class="col-12">
                        <!-- <div class='file file--upload'>
                            <label for='input-file'>
                                <span class="update_pic">
                                    <img src="{{asset('assets/images/products/product-1.png')}}" alt="" id="output">
                                </span>
                                <span class="plus_icon"><span class="img-txt">Change Picture <i class="fa fa-camera ml-1" aria-hidden="true"></i></span> </span>
                            </label>
                            <input id='input-file' type='file' name="profile_image" accept="image/*" onchange="loadFile(event)"/>

                            <div class="name_location">
                                <h5 class="mt-0 mb-1">CM Azad</h5>
                                <p class="m-0"><i class="fa fa-map-marker mr-1" aria-hidden="true"></i> Chandigarh</p>
                            </div>    
                        </div> -->
                    </div>
                </div>

                <div class="account-sidebar"><a class="popup-btn">my account</a></div>
                <div class="dashboard-left">
                    <div class="collection-mobile-back"><span class="filter-back"><i class="fa fa-angle-left"
                                aria-hidden="true"></i> back</span></div>
                    <div class="block-content">
                        <ul>
                            <li><a href="{{route('user.profile')}}">Account Info</a></li>
                            <li><a href="{{route('user.addressBook')}}">Address Book</a></li>
                            <li><a href="{{route('user.orders')}}">My Orders</a></li>
                            <li><a href="{{route('user.wishlists')}}">My Wishlist</a></li>
                            <li class="active"><a href="{{route('user.account')}}">My Wallet</a></li>
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
                            <h3 class="mt-0">My Wallet</h3>
                        </div>

                        <div class="box-account box-info">

                            <div class="card-box mb-0">
                                <div class="row align-items-center">
                                    <div class="col-sm-9 text-sm-left text-center mb-md-0 mb-4">
                                        <h5 class="text-17 mb-2">Available Balance</h5>
                                        <div class="text-36">$0</div>
                                    </div>
                                    <div class="col-sm-3 text-sm-right text-center">
                                        <a class="btn btn-solid" href="#" data-toggle="modal" data-target="#add-money">Payout</a>
                                    </div>
                                </div>
                            </div>

                            <h6>Transaction History</h6>

                            <div class="card-box">
                              <div class="table-responsive">
                                  <table class="table wallet-tarnsaction w-100">
                                      <tbody>
                                          <tr>
                                              <td>Aug 12, 2021   09:20 am</td>
                                              <td>Recieved from Shane colin</td>
                                              <td class="text-right"><b>+$50.00</b></td>
                                          </tr>
                                          <tr>
                                              <td>Aug 12, 2021   09:20 am</td>
                                              <td>Recieved from Shane colin</td>
                                              <td class="text-right"><b>+$50.00</b></td>
                                          </tr>
                                          <tr>
                                              <td>Aug 12, 2021   09:20 am</td>
                                              <td>Recieved from Shane colin</td>
                                              <td class="text-right"><b>+$50.00</b></td>
                                          </tr>
                                          <tr>
                                              <td>Aug 12, 2021   09:20 am</td>
                                              <td>Recieved from Shane colin</td>
                                              <td class="text-right"><b>+$50.00</b></td>
                                          </tr>
                                          
                                      </tbody>
                                  </table>
                              </div>
                          </div>
                            <!-- <div class="box-head">
                                <h2>Account Information</h2>
                            </div> -->

                            

                            <!-- <div class="row">
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
                                </div>
                                <div class="col-sm-6">
                                    <div class="box">
                                        <div class="box-title">
                                            <h3>Newsletters</h3><a href="#">Edit</a>
                                        </div>
                                        <div class="box-content">
                                            <p>You are currently not subscribed to any newsletter.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="box">
                                    <div class="box-title">
                                        <h3>Address Book</h3><a href="#">Manage Addresses</a>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <h6>Default Billing Address</h6>
                                            <address>You have not set a default billing address.<br><a href="#">Edit
                                                    Address</a></address>
                                        </div>
                                        <div class="col-sm-6">
                                            <h6>Default Shipping Address</h6>
                                            <address>You have not set a default shipping address.<br><a href="#">Edit Address</a></address>
                                        </div>
                                    </div>
                                </div>
                            </div>-->
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="col-lg-3">            
                <div class="page-title">
                    <h3 class="mt-0">Connected accounts</h3>
                </div>
                <div class="outer-box social-handler">
                    <form action="">
                        <div class="form-group">
                            <label for="">Facebook</label>
                            <a class="social-btn facebook-btn" href="#">
                                <i class="fa fa-facebook" aria-hidden="true"></i>
                                <span class="text-uppercase">Connected with facebook</span>
                            </a>
                        </div>
                        <div class="form-group">
                            <label for="">Twitter</label>
                            <a class="social-btn twitter-btn" href="#">
                                <i class="fa fa-twitter" aria-hidden="true"></i>
                                <span class="text-uppercase">Connected with twitter</span>
                            </a>
                        </div>
                    </form>
                </div>
            </div> -->
        </div>
    </div>
</section>

<!-- Modal -->
<div class="modal fade wallet_money" id="add-money" tabindex="-1" aria-labelledby="add-moneyLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-bottom">
        <h5 class="modal-title" id="add-moneyLabel">Pay-Out</h5>
        <button type="button" class="close top_right" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="">
            <div class="form-group">
                <label for="">Account Number</label>
                <input class="form-control" type="text" placeholder="Account Number">
            </div>
            <div class="form-group">
                <label for="">Account Name</label>
                <input class="form-control" type="text" placeholder="Account Name">
            </div>
            <div class="form-group">
                <label for="">Bank Name</label>
                <input class="form-control" type="text" placeholder="Bank Name">
            </div>
            <div class="form-group">
                <label for="">IFSC Code</label>
                <input class="form-control" type="text" placeholder="IFSC Code">
            </div>
            <button type="button" class="btn btn-solid w-100 mt-2" data-dismiss="modal">Close</button>
        </form>
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