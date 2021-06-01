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
                            <h2>Wishlist</h2>
                        </div>
                        <!-- <div class="welcome-msg">
                            <h5>Here are all your wishlist products !</h5>
                        </div> -->
                        
                        <div class="box-account box-info mt-3">
                            <div class="row">
                                <div class="col-sm-12 table-responsive table-responsive-xs">
                                    <table class="table cart-table">
                                        <thead>
                                            <tr class="table-head">
                                                <th scope="col">
                                                    <div class="form-group mb-0">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="w-1">
                                                            <label class="custom-control-label" for="w-1"></label>
                                                        </div>
                                                    </div>
                                                </th>   
                                                <th scope="col">Image</th>
                                                <th scope="col">product name</th>
                                                <th scope="col">Unit price</th>
                                                <th scope="col">Date Added</th>
                                                <th scope="col">Stock Status</th>
                                                <th scope="col"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                       
                                           <tr>
                                                <td>
                                                    <div class="form-group mb-0">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="w-2">
                                                            <label class="custom-control-label" for="w-2"></label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="product-icon">
                                                        <img src="http://local.myorder.com/assets/images/products/product-1.png" alt="product-img" height="50">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="product-title pl-1">
                                                        <h4 class="m-0">T-Shirt</h4>
                                                    </div>
                                                </td>
                                                <td>
                                                    $20.00
                                                </td>
                                                <td>
                                                    May 28, 2021
                                                </td>
                                                <td>
                                                    <i class="fa fa-check-square-o mr-1" aria-hidden="true"></i>
                                                    <span>In Stock</span>
                                                </td>
                                                <td>
                                                    <a href="#"><i class="fa fa-times" aria-hidden="true"></i></a>
                                                </td>
                                           </tr>
                                           <tr>
                                                <td>
                                                    <div class="form-group mb-0">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="w-3">
                                                            <label class="custom-control-label" for="w-3"></label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="product-icon">
                                                        <img src="http://local.myorder.com/assets/images/products/product-1.png" alt="product-img" height="50">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="product-title pl-1">
                                                        <h4 class="m-0">T-Shirt</h4>
                                                    </div>
                                                </td>
                                                <td>
                                                    $20.00
                                                </td>
                                                <td>
                                                    May 28, 2021
                                                </td>
                                                <td>
                                                    <i class="fa fa-check-square-o mr-1" aria-hidden="true"></i>
                                                    <span>In Stock</span>
                                                </td>
                                                <td>
                                                    <a href="#"><i class="fa fa-times" aria-hidden="true"></i></a>
                                                </td>
                                           </tr>
                                           <tr>
                                                <td>
                                                    <div class="form-group mb-0">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="w-4">
                                                            <label class="custom-control-label" for="w-4"></label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="product-icon">
                                                        <img src="http://local.myorder.com/assets/images/products/product-1.png" alt="product-img" height="50">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="product-title pl-1">
                                                        <h4 class="m-0">T-Shirt</h4>
                                                    </div>
                                                </td>
                                                <td>
                                                    $20.00
                                                </td>
                                                <td>
                                                    May 28, 2021
                                                </td>
                                                <td>
                                                    <i class="fa fa-check-square-o mr-1" aria-hidden="true"></i>
                                                    <span>In Stock</span>
                                                </td>
                                                <td>
                                                    <a href="#"><i class="fa fa-times" aria-hidden="true"></i></a>
                                                </td>
                                           </tr>
                                        <!-- @foreach($wishList as $wish)
                                        
                                            <tr>
                                                <td>
                                                    <div class="form-group mb-0">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="w-1">
                                                            <label class="custom-control-label" for="w-1"></label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <ul class="d-inline-flex align-items-center px-2 py-1">
                                                        <li class="product-icon">
                                                            <img src="http://local.myorder.com/assets/images/products/product-1.png" alt="product-img" height="50">
                                                        </li>
                                                        <li class="product-title pl-1">
                                                            <h4 class="m-0">T-Shirt</h4>
                                                            <p class="m-0">Sector 21, Chandigarh</p>
                                                        </li>
                                                    </ul>
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
                                        @endforeach -->
                                        </tbody>
                                        <tfoot class="border-bottom">
                                            <tr>
                                                <td colspan="2">
                                                    <select class="form-control" name="" id="">
                                                        <option value="">Actions</option>
                                                        <option value="">Actions</option>
                                                        <option value="">Actions</option>
                                                        <option value="">Actions</option>
                                                        <option value="">Actions</option>
                                                        <option value="">Actions</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <a class="btn btn-solid" href="">Apply Actions  </a>
                                                </td>
                                                <td colspan="4" class="text-center">
                                                    <a class="btn btn-solid float-right" href="">Add All to Cart</a>
                                                    <a class="btn btn-solid float-right mr-2" href="">Add Selected to Cart</a>
                                                </td>
                                            </tr>
                                        </tfoot>
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