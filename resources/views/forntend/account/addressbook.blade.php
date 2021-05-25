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
                            <li><a href="#">My Account</a></li>
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
                        <div class="welcome-msg">
                            <h5>Hello, {{ucwords(Auth::user()->name)}} !</h5>
                            <p>Here are all your addresses</p>
                        </div>
                        <div class="box-account box-info">
                            <div class="box-head">
                                <h2></h2>
                                <a href="{{route('addNewAddress')}}">Add new Address</a>
                            </div>
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
                                            <h6>Country: {{$add->country->name}}</h6>
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


@endsection

@section('script')

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
