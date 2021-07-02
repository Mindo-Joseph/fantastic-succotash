@extends('layouts.store', ['title' => 'My Wishlist'])

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
    .box-info table tr:first-child td {
        padding-top: .85rem;
    }
</style>


<section class="section-b-space">
    <div class="container">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
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
                            <li><a href="{{route('user.wallet')}}">My Wallet</a></li>
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
                                    <table class="table wishlist-table border">
                                        <thead>
                                            <tr class="table-head">
                                                <th scope="col">
                                                    <div class="form-group mb-0">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="w-all">
                                                            <label class="custom-control-label" for="w-all"></label>
                                                        </div>
                                                    </div>
                                                </th>   
                                                <th scope="col">Image</th>
                                                <th scope="col">Product Name</th>
                                                <th scope="col">Price</th>
                                                <th scope="col">Date Added</th>
                                                <th scope="col">Stock Status</th>
                                                <th scope="col"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!empty($wishList))
                                                @foreach($wishList as $key => $wish)
                                                    <tr class="wishlist-row">
                                                        <td>
                                                            <div class="form-group mb-0">
                                                                @if($wish['product']['variant'][0]['quantity'] > 0)
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="wp-{{$wish['product']['id']}}" data-variant="{{$wish['product']['variant'][0]['id']}}">
                                                                    <label class="custom-control-label" for="wp-{{$wish['product']['id']}}"></label>
                                                                </div>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="product-icon">
                                                                @foreach($wish['product']['media'] as $media)
                                                                    <img src="{{$media['image']['path']['proxy_url'].'200/200'.$media['image']['path']['image_path']}}" alt="Product Image" height="50">
                                                                    @break
                                                                @endforeach
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="product-title pl-1">
                                                                <h4 class="m-0">{{ ((isset($wish['product']['translation'][0])) && (!empty($wish['product']['translation'][0]))) ? $wish['product']['translation'][0]['title'] : '' }}</h4>
                                                            </div>
                                                        </td>
                                                        <td>${{$wish['product']['variant'][0]['price']}}</td>
                                                        <td>{{date('M d, Y', strtotime($wish['added_on']))}}</td>
                                                        <td>
                                                            @if($wish['product']['variant'][0]['quantity'] > 0)
                                                                <i class="fa fa-check-square-o mr-1" aria-hidden="true"></i>
                                                                <span>In Stock</span>
                                                            @else
                                                                <span>Not In Stock</span>
                                                            @endif
                                                        </td>
                                                        <td><a href="{{ route('removeWishlist', $wish['product']['sku']) }}" class="icon me-3"><i class="ti-close"></i> </a></td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr><td align="center" colspan="6">No Item Exists In Your Wishlist</td></tr>
                                            @endif
                                        </tbody>
                                        @if(!empty($wishList))
                                            <tfoot class="border-top border-bottom">
                                                <tr>
                                                    <td colspan="7" class="pt-2">
                                                        <button type="button" class="btn btn-solid mr-2 addWishlistToCart">Add to Cart</button>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        @endif
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
    var add_wishlist_to_cart_url = "{{ route('addWishlistToCart') }}";
    var ajaxCall = 'ToCancelPrevReq';
    $('.verifyEmail').click(function() {
        verifyUser('email');
    });

    $('.verifyPhone').click(function() {
        verifyUser('phone');
    });

    $("#w-all").click(function(){
        if($(this).is(":checked")){
            $(".wishlist-table").find(".custom-checkbox input[type='checkbox']").prop("checked", true);
        }else{
            $(".wishlist-table").find(".custom-checkbox input[type='checkbox']").prop("checked", false);
        }
    });

    $(document).ready(function(){
        $(document).on("click",".addWishlistToCart",function() {
            let wishlist_products = [];
            $(".wishlist-row .custom-control-input:checked").each(function(i, obj){
                var id = $(obj).attr('id');
                var product_id = id.replace('wp-', '');
                var product_variant_id = $(obj).attr('data-variant');
                wishlist_products.push({'product_id':product_id, 'variant_id':product_variant_id});
            });
            addWishlistToCart(wishlist_products);
        });
        function addWishlistToCart(wishlist_products) {
            $.ajax({
                type: "post",
                dataType: "json",
                url: add_wishlist_to_cart_url,
                data: {"wishlistProducts": wishlist_products},
                success: function(response) {
                    location.reload();
                },
                error: function(data) {
                    console.log(data);
                },
            });
        }
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