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
                            <li><a href="{{route('user.profile')}}">Account Info</a></li>
                            <li><a href="{{route('user.addressBook')}}">Address Book</a></li>
                            <li class="active"><a href="{{route('user.orders')}}">My Orders</a></li>
                            <li><a href="{{route('user.wishlists')}}">My Wishlist</a></li>
                            <li><a href="#">My Account</a></li>
                            <li><a href="#">Change Password</a></li>
                            <li class="last"><a href="#">Log Out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="dashboard-right">
                    <div class="dashboard">
                        <div class="page-title">
                            <h2>Orders</h2>
                        </div>
                        <div class="welcome-msg">
                            <h5>Here are all your previous orders !</h5>

                        </div>
                        <div class="box-account box-info">
                            <!-- <div class="box-head">
                                <h2>Account Information</h2>
                            </div> -->
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="box">
                                        <div class="box-title">
                                            <h3>Order Number #2</h3><a data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                                                View Details
                                            </a>
                                        </div>
                                        <div class="box-content">
                                            <h6>Total Items: 3</h6>
                                            <h6>Order Status: Created</h6>
                                            <h6>Recepient Name: Puneet</h6>
                                            <h6>Recepient Email: gargpuneet217@gmail.com</h6>
                                            <h6>Recepient Phone number: 9996778910</h6>
                                            <h6>Payment Method: Credit Card</h6>
                                            <h6>Payment Status: Paid</h6>

                                            <div class="collapse" id="collapseExample">
                                                <h5>Products</h5>
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <h6>
                                                            <span>
                                                                <a href="{{route('second', ['ecommerce', 'product-detail'])}}"><img src="{{asset('assets/images/products/product-1.png')}}" alt="product-img" height="50" /></a>
                                                            </span>
                                                            Product 1
                                                        </h6>
                                                        <h6>Variants</h6>
                                                        <ul class="ml-4">
                                                            <li style="display:list-item;">cheese</li>
                                                            <li style="display:list-item;">small</li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <h6>
                                                            <span>
                                                                <a href="{{route('second', ['ecommerce', 'product-detail'])}}"><img src="{{asset('assets/images/products/product-1.png')}}" alt="product-img" height="50" /></a>
                                                            </span>
                                                            Product 2
                                                        </h6>
                                                        <h6>Variants</h6>
                                                        <ul class="ml-4">
                                                            <li style="display:list-item;">cheese</li>
                                                            <li style="display:list-item;">small</li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <h6>
                                                            <span>
                                                                <a href="{{route('second', ['ecommerce', 'product-detail'])}}"><img src="{{asset('assets/images/products/product-1.png')}}" alt="product-img" height="50" /></a>
                                                            </span>
                                                            Product 2
                                                        </h6>
                                                        <h6>Variants</h6>
                                                        <ul class="ml-4">
                                                            <li style="display:list-item;">cheese</li>
                                                            <li style="display:list-item;">small</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="box">
                                        <div class="box-title">
                                            <h3>Order Number #3</h3><a data-toggle="collapse" href="#collapseExample2" role="button" aria-expanded="false" aria-controls="collapseExample">
                                                View Details
                                            </a>
                                        </div>
                                        <div class="box-content">
                                            <h6>Total Items: 3</h6>
                                            <h6>Order Status: Created</h6>
                                            <h6>Recepient Name: Puneet</h6>
                                            <h6>Recepient Email: gargpuneet217@gmail.com</h6>
                                            <h6>Recepient Phone number: 9996778910</h6>
                                            <h6>Payment Method: Credit Card</h6>
                                            <h6>Payment Status: Paid</h6>

                                            <div class="collapse" id="collapseExample2">
                                                <h5>Products</h5>
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <h6>
                                                            <span>
                                                                <a href="{{route('second', ['ecommerce', 'product-detail'])}}"><img src="{{asset('assets/images/products/product-1.png')}}" alt="product-img" height="50" /></a>
                                                            </span>
                                                            Product 1
                                                        </h6>
                                                        <h6>Variants</h6>
                                                        <ul class="ml-4">
                                                            <li style="display:list-item;">cheese</li>
                                                            <li style="display:list-item;">small</li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <h6>
                                                            <span>
                                                                <a href="{{route('second', ['ecommerce', 'product-detail'])}}"><img src="{{asset('assets/images/products/product-1.png')}}" alt="product-img" height="50" /></a>
                                                            </span>
                                                            Product 2
                                                        </h6>
                                                        <h6>Variants</h6>
                                                        <ul class="ml-4">
                                                            <li style="display:list-item;">cheese</li>
                                                            <li style="display:list-item;">small</li>
                                                        </ul>
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
        </div>
    </div>
</section>


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

@endsection