@extends('layouts.store', ['title' => 'Home'])
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
<section class="p-0 small-slider">
    <div class="slide-1 home-slider">
        @foreach($banners as $banner)
            <div>
                <div class="home text-center">
                    <img src="{{$banner->image['proxy_url'] . '1500/600' . $banner->image['image_path']}}" class="bg-img blur-up lazyload">
                </div>
            </div>
        @endforeach
    </div>
</section>
<script type="text/template" id="vendors_template">
    <% _.each(vendors, function(vendor, k){%>
        <div class="product-box scale-effect">
            <div class="img-wrapper">
                <div class="front">
                    <a href="{{route('vendorDetail')}}/<%= vendor.slug %>">
                        <img class="img-fluid blur-up lazyload bg-img" alt="" src="<%= vendor.logo.proxy_url %>200/200<%= vendor.logo['image_path'] %>">
                    </a>
                </div>
            </div>
            <div class="product-detail">
            @if($client_preference_detail)
                @if($client_preference_detail->rating_check == 1)
                    <div class="rating">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i> 
                        <i class="fa fa-star"></i> 
                        <i class="fa fa-star"></i> 
                        <i class="fa fa-star"></i>
                    </div>
                @endif
            @endif
                <a href="{{route('vendorDetail')}}/<%= vendor.slug %>">
                    <h6><%= vendor.name %></h6>
                </a>
            </div>
        </div>
    <% }); %>
</script>
<script type="text/template" id="banner_template">
    <% _.each(brands, function(brand, k){%>
        <div>
            <div class="logo-block">
                <a href="<%= brand.redirect_url %>">
                    <img src="<%= brand.image.image_fit %>120/120<%= brand.image.image_path %>" alt="">
                </a>
            </div>
        </div>
    <% }); %>
</script>
<script type="text/template" id="products_template">
    <% _.each(products, function(product, k){ %>
        <div>
            <a class="card scale-effect text-center" href="{{route('productDetail')}}/<%= product.url_slug %>">
                <div class="product-image">
                    <img src="<%= product.image_url %>" alt="">
                </div>    
                <div class="media-body align-self-center px-3">
                    <div class="inner_spacing">
                        <h3><%= product.title %></h3>
                        <p><%= product.vendor_name %></p>
                        <h4>
                            <% if(product.inquiry_only == 0) { %>
                                <%= product.price %>
                            <% } %>
                        </h4>
                        @if($client_preference_detail)
                            @if($client_preference_detail->rating_check == 1)
                                <div class="rating">
                                    <% _.each([1,2,3,4,5], function(value, k){ %>
                                        <i class="fa fa-star"></i>
                                    <% }); %>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </a>
        </div>
    <% }); %>
</script>

<!-- <div class="tab-content" id="myTabContent">
    <div class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">.. 1 ..</div>
    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">.. 2 ..</div>
    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">.. 3 ..</div>
</div> -->

@if($count > 1)
<section class="home-tabbar">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-lg-12 tab-product pt-0">
                <ul class="nav nav-tabs nav-material tab-icons" id="top-tab" role="tablist">
                    @if($clientPreferences->delivery_check == 1)
                    <li class="nav-item">
                        <a class="nav-link {{$count == 1 ? 'active' : 'active'}}" id="delivery_tab" data-toggle="tab" href="#delivery_tab" role="tab" aria-selected="false" data-rel="delivery_tab">
                            <i><span class="icon-shipped"></span></i>
                            <span>{{ __('Delivery') }}</span>
                        </a>
                        <div class="material-border"></div>
                    </li>
                    @endif
                    @if($clientPreferences->dinein_check == 1)
                    <li class="nav-item">
                        <a class="nav-link {{$clientPreferences->dinein_check == 1 && $clientPreferences->delivery_check != 1? 'active' : ''}}" id="dinein_tab" data-toggle="tab" href="#dinein_tab" role="tab" aria-selected="true" data-rel="dinein_tab">
                            <i><span class="icon-dine-in"></span></i>
                            <span>{{ __('Dine-In') }}</span>
                        </a>
                        <div class="material-border"></div>
                    </li>
                    @endif
                    @if($clientPreferences->takeaway_check == 1)
                    <li class="nav-item">
                        <a class="nav-link {{$count == 1 ? 'active' : ''}}" id="takeaway_tab" data-toggle="tab" href="#takeaway_tab" role="tab" aria-selected="false" data-rel="takeaway_tab">
                            <i><span class="icon-take-away"></span></i> 
                            <span>{{ __('Takeaway') }}</span>
                        </a>
                        <div class="material-border"></div>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
</section>
@endif
<section class="section-b-space p-t-0 pt-5 ratio_asos pb-0 d-none" id="our_vendor_main_div">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center d-flex align-items-center justify-content-between mb-4">
                <div class="title1">
                    <h2 class="title-inner1 mb-0">{{getNomenclatureName('vendors', true)}}</h2>
                </div>
                <!-- <a class="view_more_items" href="#">View More</a> -->
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="product-4 product-m no-arrow" id="vendor_main_div"></div>
            </div>
        </div>
    </div>
</section>
<section class="section-b-space">
    <div class="container">
        <div class="row d-none" id="new_products_wrapper">
            <div class="col-12 text-center d-flex align-items-center justify-content-between mb-4">
                <div class="title1">
                    <h2 class="title-inner1 mb-0">{{ __('New Products') }}</h2>
                </div>
            </div>
            <div class="col-12 theme-card">
                <div class="vendor-product common_card" id="new_product_main_div"></div>
            </div>
        </div>
        <div class="row d-none mt-4" id="featured_products_wrapper">
            <div class="col-12 text-center d-flex align-items-center justify-content-between mb-4">
                <div class="title1">
                    <h2 class="title-inner1 mb-0">{{ __('Feature Product') }}</h2>
                </div>
            </div>
            <div class="col-12 theme-card">
                <div class="vendor-product common_card" id="feature_product_main_div"></div>
            </div>
        </div>

        <div class="row d-none mt-md-5 mt-4" id="bestseller_products_wrapper">
            <div class="col-12 text-center d-flex align-items-center justify-content-between mb-4">
                <div class="title1">
                    <h2 class="title-inner1 mb-0">{{ __('Best Seller') }}</h2>
                </div>
            </div>
            <div class="col-12 theme-card">
                <div class="vendor-product common_card" id="best_seller_main_div">

                </div>
            </div>
        </div>
        <div class="row d-none mt-4" id="onsale_products_wrapper">
            <div class="col-12 text-center d-flex align-items-center justify-content-between mb-4">
                <div class="title1">
                    <h2 class="title-inner1 mb-0">{{ __('On Sale') }}</h2>
                </div>
            </div>
            <div class="col-12 theme-card">
                <div class="vendor-product common_card" id="on_sale_product_main_div"></div>
            </div>
        </div>
    </div>
</section>
<section class="section-b-space pt-0">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center d-flex align-items-center justify-content-between mb-4">
                <div class="title1">
                    <h2 class="title-inner1 mb-0">{{ __('Brands') }}</h2>
                </div>
            </div>
            <div class="col-md-12">
                <div class="slide-6 no-arrow" id="brand_main_div"></div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
<script src="{{asset('front-assets/js/jquery.exitintent.js')}}"></script>
<script src="{{asset('front-assets/js/fly-cart.js')}}"></script>
@endsection