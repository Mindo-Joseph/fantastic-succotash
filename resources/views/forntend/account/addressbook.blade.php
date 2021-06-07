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
    .login-page .theme-card .theme-form input {
        margin-bottom: 5px;
    }
    .invalid-feedback{
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
                            <li><a href="{{route('user.profile')}}">Account Info</a></li>
                            <li class="active"><a href="{{route('user.addressBook')}}">Address Book</a></li>
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
                            <h2>Address Book</h2>
                        </div>
                        <!-- <div class="welcome-msg">
                            <h5>Hello, {{ucwords(Auth::user()->name)}} !</h5>
                            <p>Here are all your addresses</p>
                        </div> -->
                        <div class="box-account box-info order-address">

                            <div class="row">
                                <div class="col-xl-4 col-md-6 text-center mt-3">
                                    <a class="outer-box border-dashed d-flex align-items-center justify-content-center" href="javascript:void(0)" data-toggle="modal" data-target="#add-new-address">
                                        <i class="fa fa-plus-circle d-block mb-1" aria-hidden="true"></i>
                                        <h6 class="m-0">Add new Address</h6>
                                    </a>
                                </div>

                                <div class="col-xl-4 col-md-6 mt-3">
                                    <div class="outer-box d-flex align-items-center justify-content-between px-0">
                                        <div class="address-type w-100">
                                            <div class="default_address border-bottom mb-1 px-2">
                                                <label>Default :</label>
                                                <img src="{{asset('assets/images/products/product-1.png')}}" alt="product-img" height="30" />
                                            </div>
                                            <div class="px-2">
                                                <h6 class="mt-0 mb-2"><i class="fa fa-home mr-1" aria-hidden="true"></i> Home</h6>
                                                <p class="mb-1">#1541, phase-5, mohali, Phase 5, Mohali</p>
                                                <p class="mb-1">Colony</p>
                                                <p class="mb-1">Mohali, PUNJAB 160059</p>
                                                <p class="mb-1">India</p>
                                                <p class="mb-1">Phone number: ‪8219512331‬</p>
                                            </div>
                                        </div>
                                        <div class="address-btn d-flex align-items-center justify-content-end w-100 mt-4 px-2">
                                            <a class="btn btn-solid" data-toggle="modal" data-target="#edit-address" href="javascript:void(0)">Edit</a>
                                            <a class="btn btn-solid" href="#">Delete</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6 mt-3">
                                    <div class="outer-box d-flex align-items-center justify-content-between px-0 h-100">
                                        <div class="address-type w-100">
                                            <div class="px-2">
                                                <h6 class="mt-0 mb-2"><i class="fa fa-building mr-1" aria-hidden="true"></i> Office</h6>
                                                <p class="mb-1">#1541, phase-5, mohali, Phase 5, Mohali</p>
                                                <p class="mb-1">Colony</p>
                                                <p class="mb-1">Mohali, PUNJAB 160059</p>
                                                <p class="mb-1">India</p>
                                                <p class="mb-1">Phone number: ‪8219512331‬</p>
                                            </div>
                                        </div>
                                        <div class="address-btn d-flex align-items-center justify-content-end w-100 mt-4 px-2">
                                            <a class="btn btn-solid" href="#">Edit</a>
                                            <a class="btn btn-solid" href="#">Delete</a>
                                            <a class="btn btn-solid" href="#">Set as Default</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="box-head">
                                <h2></h2>
                                <a href="{{route('addNewAddress')}}">Add new Address</a>
                            </div> -->
                            <div class="row mb-3">
                            @foreach($useraddress as $add)
                                <div class="col-sm-6">
                                    <div class="box">
                                        <div class="box-title">
                                            <h3 style="float: left;">Address</h3>
                                            <span style="float: right;">
                                            @if($add->is_primary == 0)
                                            <a href="{{ route('setPrimaryAddress', $add->id) }}" class="mr-2">Set Primary</a> 
                                            @endif
                                            <a href="{{ route('deleteAddress', $add->id) }}" class="mr-2">Delete</a> 
                                            <a href="{{ route('editAddress', $add->id) }}" class="mr-2">Edit</a>
                                            </span>
                                        </div>
                                        <div class="box-content">
                                            <h6>Address: {{$add->address}}</h6>
                                            <h6>Street: {{$add->street}}</h6>
                                            <h6>City: {{$add->city}}</h6>
                                            <h6>State: {{$add->state}}</h6>
                                            <h6>Country: {{$add->country  ? $add->country->name : ''}}</h6>
                                            <h6>Pincode: {{$add->pincode}}</h6>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <!-- <div>
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
                                            <address>You have not set a default shipping address.<br><a
                                                    href="#">Edit Address</a></address>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Add New Address Modal Start From Here -->
<div class="modal fade add_new_address" id="add-new-address" tabindex="-1" aria-labelledby="add-new-addressLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="add-new-addressLabel">Add New Address</h5>
        <button type="button" class="close top_right" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="outer-box">
            <form action="" class="theme-form" method="post">@csrf
                    <div class="form-row mb-0">
                        <div class="col-md-6 mb-2">
                            <label for="address">Address</label>
                            <input type="text" class="form-control" value="{{old('address')}}" id="address" placeholder="Address" required="" name="address">
                            @if($errors->first('address'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('address') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="street">Street</label>
                            <input type="text" class="form-control" id="street" placeholder="Street" required="" name="street" value="{{old('street')}}">
                            @if($errors->first('street'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('street') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-row mb-0">
                        <div class="col-md-6 mb-2">
                            <label for="city">City</label>
                            <input type="city" class="form-control" id="email" placeholder="City" required="" name="city" value="{{old('city')}}">
                            @if($errors->first('city'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('city') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="state">State</label>
                            <input type="text" class="form-control" id="state" placeholder="State" required="" name="state" value="{{old('state')}}">
                            @if($errors->first('state'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('state') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="country">Country</label>
                            <select name="country" id="country" class="form-control">
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="pincode">Pincode</label>
                            <input type="text" class="form-control" id="pincode" placeholder="Pincode" required="" name="pincode" value="{{old('pincode')}}">
                            @if($errors->first('pincode'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('pincode') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="type">Address Type</label>
                            <select name="type" id="type" class="form-control">
                                <option value="1" selected>Home</option>
                                <option value="2">Office</option>
                                
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                        </div>
                        
                    </div>
                </form>
            </div>
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
        <div class="col-md-12 mb-2"><button type="submit" class="btn btn-solid mt-3 w-100">Update Address</button></div>
      </div>
    </div>
  </div>
</div>


<!-- Address Edit Modal Start Form Here -->
<div class="modal fade edit_address" id="edit-address" tabindex="-1" aria-labelledby="edit-addressLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body p-0">
           
            <div id="step_one">
                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d13720.904154980397!2d76.81441854999998!3d30.71204525!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin!4v1622198188924!5m2!1sen!2sin" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            
                <div class="delivery_address p-3 position-relative">
                    <div class="modal-title">Set your delivery location</div>
                    <button type="button" class="close edit-close position-absolute" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <div class="form-group">
                        <label class="delivery-head">DELIVERY AREA</label>
                        <div class="select_address border d-flex align-items-center justify-content-between ">
                            <div class="location-area">
                                <i class="fa fa-check-circle-o" aria-hidden="true"></i>
                                <span>22A, Sector 22</span>
                            </div>    
                            <label class="m-0 text-uppercase">Change</label>
                        </div>
                    </div>
                    <div class="text-right">
                        <a class="btn btn-solid ml-auto next-step" href="javascript:void(0)">Confirm And Proceed</a>
                    </div>
                </div>
            </div>

            <div id="step-two">
                 <div class="delivery_address p-3 position-relative">
                    <div class="modal-title">Set your delivery location</div>
                    <button type="button" class="close edit-close hide-address position-absolute"><span aria-hidden="true">&times;</span></button>
                    <div class="form-group">
                        <label class="delivery-head">DELIVERY AREA</label>
                        <div class="address-input-field d-flex align-items-center">
                            <i class="fa fa-map-marker" aria-hidden="true"></i>
                            <input class="form-control border-0" type="text" name="" id="">
                        </div>
                    </div>
                    <div class="address_list"></div>
                    <div class="text-right d-none">
                        <a class="btn btn-solid ml-auto" href="#">Confirm and Proceed</a>
                    </div>
                </div>
            </div>
           
            <div id="step-three">
                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d13720.904154980397!2d76.81441854999998!3d30.71204525!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin!4v1622198188924!5m2!1sen!2sin" width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                <div class="delivery_address p-3 position-relative">
                    <div class="modal-title">Set your delivery location 3   </div>
                    <button type="button" class="close edit-close go-back position-absolute"><span aria-hidden="true">&times;</span></button>
                    <div class="form-group">
                        <label class="delivery-head">DELIVERY AREA</label>
                        <div class="select_address border d-flex align-items-center justify-content-between ">
                            <div class="location-area">
                                <i class="fa fa-check-circle-o" aria-hidden="true"></i>
                                <span>22A, Sector 22</span>
                            </div>    
                            <label class="m-0 text-uppercase">Change</label>
                        </div>
                        <div class="edit-area">
                            <input class="form-control" type="text" placeholder="Complete Address *" name="" id="">
                            <input class="form-control" type="text" placeholder="Floor (Optional)" name="" id="">
                            <input class="form-control" type="text" placeholder="How to reach (Optional)" name="" id="">
                        </div>
                        <div class="mt-2 mb-2">
                            <div class="address_type">
                                <label class="radio d-inline-block m-0">Home
                                    <input type="radio" name="is_company">
                                    <span class="checkround"></span>
                                </label>
                                <label class="radio d-inline-block m-0">Office
                                    <input type="radio" name="is_company">
                                    <span class="checkround"></span>
                                </label>
                                <label class="radio other_address d-inline-block m-0">Other
                                    <input type="radio" name="is_company">
                                    <span class="checkround"></span>
                                </label>   
                            </div>

                            <div class="other-address-input">
                                <label class="radio other_address d-inline-block m-0">Other
                                    <input type="radio" checked="checked" name="is_company">
                                    <span class="checkround"></span>
                                </label>   
                                <div class="address-input-field">
                                    <input class="form-control border-0" type="text" name="" id="">
                                    <label class="hide-other m-0 text-uppercase">Changes</label>
                                </div>                      
                            </div>                      
                        </div>
                       
                    </div>
                    <div class="text-right">
                        <a class="btn btn-solid ml-auto" href="#">Save and Proceed</a>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
  </div>
</div>


@endsection

@section('script')

<script>
    jQuery("#step-two").hide();
    jQuery("#step-three").hide();
    jQuery(".other-address-input").hide();
    jQuery(document).ready(function () {
        jQuery(".select_address").click(function () {
            jQuery("#step-two").show();
            jQuery("#step_one").hide();
        });
        jQuery(".hide-address").click(function () {
            jQuery("#step-two").hide();
            jQuery("#step_one").show();
        });
        jQuery(".next-step").click(function(){
            jQuery("#step-three").show();
            jQuery("#step_one").hide();
        });
        jQuery(".go-back").click(function(){
            jQuery("#step-three").hide();
            jQuery("#step_one").show();
        });
        jQuery(".other_address").click(function(){
            jQuery(".other-address-input").show();
            jQuery(".address_type").hide();
        });
        jQuery(".hide-other").click(function(){
            jQuery(".other-address-input").hide();
            jQuery(".address_type").show();
        });
        jQuery(".select_address").click(function () {
            jQuery("#step-three").hide();
        });
        jQuery(".hide-address").click(function () {
            jQuery("#step-three").show();
            jQuery("#step_one").hide();
        });
    });
</script>

<script type="text/javascript">
    var ajaxCall = 'ToCancelPrevReq';
    $('.verifyEmail').click(function(){
        verifyUser('email');
    });

    $('.verifyPhone').click(function(){
       verifyUser('phone');
    });

    function verifyUser($type = 'email'){
        ajaxCall = $.ajax({
            type: "post",
            dataType: "json",
            url: "{{ route('verifyInformation', Auth::user()->id) }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "type": $type, 
            },
            beforeSend : function() {
                if(ajaxCall != 'ToCancelPrevReq' && ajaxCall.readyState < 4) {
                    ajaxCall.abort();
                }
            },
            success: function(response) {
                var res = response.result;
                
            },
            error: function (data) {
                
            },
        });
    }

</script>

@endsection
