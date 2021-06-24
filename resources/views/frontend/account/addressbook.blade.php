@extends('layouts.store', ['title' => 'Profile'])

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
    .outer-box{
        min-height: 280px;
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
                                    <a class="outer-box border-dashed d-flex align-items-center justify-content-center" href="javascript:void(0)" data-toggle="modal" data-target="#add_edit_address">
                                        <i class="fa fa-plus-circle d-block mb-1" aria-hidden="true"></i>
                                        <h6 class="m-0">Add new Address</h6>
                                    </a>
                                </div>
                                @foreach($useraddress as $add)
                                    <div class="col-xl-4 col-md-6 mt-3">
                                        <div class="outer-box d-flex align-items-center justify-content-between px-0">
                                            <div class="address-type w-100">
                                                <div class="default_address border-bottom mb-1 px-2">
                                                    <h6 class="mt-0 mb-2"><i class="fa fa-{{ ($add->type == 1) ? 'home' : 'building' }} mr-1" aria-hidden="true"></i> {{ ($add->type == 1) ? 'Home' : 'Office' }}</h6>
                                                </div>
                                                <div class="px-2">
                                                    <p class="mb-1">{{$add->address}}</p>
                                                    <p class="mb-1">{{$add->street}}</p>
                                                    <p class="mb-1">{{$add->city}}, {{$add->state}} {{$add->pincode}}</p>
                                                    <p class="mb-1">{{$add->country  ? $add->country : ''}}</p>
                                                    <p class="mb-1">Phone number: ‪8219512331‬</p>
                                                </div>
                                            </div>
                                            <div class="address-btn d-flex align-items-center justify-content-end w-100 mt-4 px-2">
                                                @if($add->is_primary == 1)
                                                    <a class="btn btn-solid disabled" href="#">Primary</a>
                                                @else
                                                    <a class="btn btn-solid" href="{{ route('setPrimaryAddress', $add->id) }}" class="mr-2">Set as Primary</a>
                                                @endif
                                                <a class="btn btn-solid" href="javascript:void(0)" data-toggle="modal" data-target="#add_edit_address">Edit</a>
                                                <a class="btn btn-solid delete_address_btn" href="javascript:void(0)" data-toggle="modal" data-target="#removeAddressConfirmation" data-id="{{$add->id}}">Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- <div class="box-head">
                                <h2></h2>
                                <a href="{{route('addNewAddress')}}">Add new Address</a>
                            </div> -->
                            <?php /* ?><div class="row mb-3">
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
                                            <h6>Country: {{$add->country  ? $add->country : ''}}</h6>
                                            <h6>Pincode: {{$add->pincode}}</h6>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div><?php */ ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Add New Address Modal Start From Here -->
<?php /* ?><div class="modal fade add_new_address" id="add-new-address" tabindex="-1" aria-labelledby="add-new-addressLabel" aria-hidden="true">
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
</div><?php */ ?>


<div class="modal fade" id="removeAddressConfirmation" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="remove_addressLabel">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header pb-0">
        <h5 class="modal-title" id="remove_addressLabel">Delete Address</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <h6 class="m-0">Do you really want to delete this address ?</h6>
      </div>
      <div class="modal-footer flex-nowrap justify-content-center align-items-center">
        <button type="button" class="btn btn-solid black-btn" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-solid" id="remove_address_confirm_btn" data-id="">Delete</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="add_edit_address" tabindex="-1" aria-labelledby="addedit-addressLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addedit-addressLabel">Edit Address</h5>
        <button type="button" class="close top_right" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="outer-box">
            <div class="row">
                <div class="col-md-12" id="add_new_address_form">
                    <div class="theme-card w-100">
                        <div class="form-row no-gutters">
                            <div class="col-12">
                                <label for="type">Address Type</label>
                            </div>
                            <div class="col-md-3">
                                <div class="delivery_box pt-0 pl-0  pb-3">
                                    <label class="radio m-0">Home 
                                        <input type="radio" checked="checked" name="address_type" value="1">
                                        <span class="checkround"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                            <div class="delivery_box pt-0 pl-0  pb-3">
                                <label class="radio m-0">Office 
                                    <input type="radio" name="address_type" value="2">
                                    <span class="checkround"></span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="delivery_box pt-0 pl-0  pb-3">
                                <label class="radio m-0">Others
                                    <input type="radio" name="address_type" value="3">
                                    <span class="checkround"></span>
                                </label>
                            </div>
                        </div>
                        </div>
                        <input type="hidden" id="latitude">
                        <input type="hidden" id="longitude">
                        <div class="form-row">
                            <div class="col-md-12 mb-2">
                                <label for="address">Address</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="address" placeholder="Address" aria-label="Recipient's Address" aria-describedby="button-addon2">
                                    <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="button-addon2">
                                        <i class="fa fa-map-marker" aria-hidden="true"></i>
                                    </button>
                                    </div>
                                </div>
                                <span class="text-danger" id="address_error"></span>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6 mb-2">
                                <label for="street">Street</label>
                                <input type="text" class="form-control" id="street" placeholder="Street" name="street" value="">
                                <span class="text-danger" id="street_error"></span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="city">City</label>
                                <input type="text" class="form-control" id="city" name="city" placeholder="City" value="">
                                <span class="text-danger" id="city_error"></span>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6 mb-2">
                                <label for="state">State</label>
                                <input type="text" class="form-control" id="state" name="state" placeholder="State" value="">
                                <span class="text-danger" id="state_error"></span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="country">Country</label>
                                <select name="country" id="country" class="form-control">
                                    @foreach($countries as $co)
                                        <option value="{{$co->id}}">{{$co->name}}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger" id="country_error"></span>
                            </div>
                        </div>
                        <div class="form-row mb-3">
                            <div class="col-md-6 mb-2">
                                <label for="pincode">Pincode</label>
                                <input type="text" class="form-control" id="pincode" name="pincode" placeholder="Pincode" value="">
                                <span class="text-danger" id="pincode_error"></span>
                            </div>
                            <div class="col-md-12 mt-3">
                                <button type="button" class="btn btn-solid" id="save_address">Save Address</button>
                                <button type="button" class="btn btn-solid black-btn" id="cancel_save_address_btn">Cancel</button>
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

@endsection

@section('script')
<script type="text/javascript">
    var user_store_address_url = "{{ route('address.store') }}";
    var delete_address_url = "{{ route('deleteAddress', ':id') }}";
    var verify_information_url = "{{ route('verifyInformation', Auth::user()->id) }}";
    
    var ajaxCall = 'ToCancelPrevReq';
    $('.verifyEmail').click(function(){
        verifyUser('email');
    });

    $('.verifyPhone').click(function(){
       verifyUser('phone');
    });

    $(document).delegate(".delete_address_btn", "click", function(){
        var addressID = $(this).attr("data-id");
        $("#remove_address_confirm_btn").attr("data-id", addressID);
    });

    $(document).delegate("#remove_address_confirm_btn", "click", function(){
        var addressID = $(this).attr("data-id");
        var url = delete_address_url.replace(':id', addressID);
        location.href = url;
    });

    function verifyUser($type = 'email'){
        ajaxCall = $.ajax({
            type: "post",
            dataType: "json",
            url: verify_information_url,
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
