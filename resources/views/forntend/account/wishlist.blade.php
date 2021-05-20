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
                            <li><a href="{{route('user.orders')}}">My Orders</a></li>
                            <li class="active"><a href="{{route('user.wishlists')}}">My Wishlist</a></li>
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
                            <h2>Wishlist</h2>
                        </div>
                        <div class="welcome-msg">
                            <h5>Here are all your wishlist products !</h5>
                        </div>
                        <div class="box-account box-info">
                            <div class="row">
                            <div class="col-sm-12 table-responsive-xs">
                    <table class="table cart-table">
                        <thead>
                            <tr class="table-head">
                                <th scope="col">image</th>
                                <th scope="col">product name</th>
                                <th scope="col">price</th>
                                <th scope="col">availability</th>
                                <th scope="col">action</th>
                            </tr>
                        </thead>
                        @foreach($wishList as $wish)
                        <tbody>
                            <tr>
                                <td>
                                    <a href="#"><img src="{{$wish['product']['media'][0]['image']['path']['proxy_url'].'200/200'.$wish['product']['media'][0]['image']['path']['image_path']}}" alt=""></a>
                                </td>
                                <td><a href="#">{{$wish['product']['sku']}}</a>
                                </td>
                                <td>
                                    <h2>${{$wish['product']['variant'][0]['price']}}</h2>
                                </td>
                                <td>
                                @if($wish['product']['variant'][0]['quantity'] > 0)
                                    <p>In stock</p>
                                @else
                                    <p>Not in stock</p>
                                @endif
                                </td>
                                <td><a href="{{ route('removeWishlist', $wish['product']['sku']) }}" class="icon me-3"><i class="ti-close"></i> </a></td>
                            </tr>
                        </tbody>
                        @endforeach
                    </table>
                </div>
                            <!-- @foreach($wishList as $wish)
                                <div class="col-sm-4">
                                    <div class="box">
                                        <div class="box-title">
                                            <h3>{{$wish['product']['sku']}}</h3><a href="{{ route('removeWishlist', $wish['product']['sku']) }}">Remove</a>
                                        </div>
                                        <div class="box-content">
                                            <a href="{{route('second', ['ecommerce', 'product-detail'])}}"><img src="{{$wish['product']['media'][0]['image']['path']['proxy_url'].'200/200'.$wish['product']['media'][0]['image']['path']['image_path']}}" alt="product-img" height="100" /></a>
                                            <h6>Price: {{$wish['product']['variant'][0]['price']}}</h6>
                                        </div>
                                    </div>
                                </div>
                                @endforeach -->
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