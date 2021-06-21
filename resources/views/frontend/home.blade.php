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
                    <img src="{{$banner->image['proxy_url'] . '1500/600' . $banner->image['image_path']}}" class="bg-img blur-up lazyload" alt="">
                </div>
            </div>
        @endforeach
    </div>
</section>
<script type="text/template" id="vendors_template">
    <% _.each(vendors, function(vendor, k){%>
        <div class="product-box">
            <div class="img-wrapper">
                <div class="front">
                    <a href="{{route('vendorDetail')}}/<%= vendor.id %>">
                        <img class="img-fluid blur-up lazyload bg-img" alt="" src="<%= vendor.logo.proxy_url %>200/200<%= vendor.logo['image_path'] %>">
                    </a>
                </div>
                <div class="back">
                    <a href="{{route('vendorDetail')}}/<%= vendor.id %>">
                        <img class="img-fluid blur-up lazyload bg-img" alt="" src="<%= vendor.logo.proxy_url %>200/200<%= vendor.logo.image_path %>">
                    </a>
                </div>
            </div>
            <div class="product-detail">
                <div class="rating">
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i> 
                    <i class="fa fa-star"></i> 
                    <i class="fa fa-star"></i> 
                    <i class="fa fa-star"></i>
                </div>
                <a href="#">
                    <h6>
                    <%= vendor.name %>
                </h6></a>
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
            <a class="card text-center" href="{{route('productDetail')}}/<%= product.sku %>">
                <div class="product-image">
                    <img src="<%= product.image_url %>" alt="">
                </div>    
                <div class="media-body align-self-center px-3">
                    <div class="inner_spacing">
                        <h3><%= product.title %></h3>
                        <p><%= product.vendor_name %></p>
                        <h4><%= product.price %></h4>
                        <div class="rating">
                            @for($i = 1; $i < 6; $i++) 
                                <i class="fa fa-star"></i>
                            @endfor
                        </div>
                    </div>
                </div>
            </a>
        </div>
    <% }); %>
</script>
<section class="section-b-space p-t-0 pt-5 ratio_asos pb-0 d-none" id="our_vendor_main_div">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center d-flex align-items-center justify-content-between mb-4">
                <div class="title1">
                    <h2 class="title-inner1 mb-0">Our Vendors</h2>
                </div>
                <a class="view_more_items" href="#">View More</a>
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
                    <h2 class="title-inner1 mb-0">New Products</h2>
                </div>
                <a class="view_more_items" href="#">View More</a>
            </div>
            <div class="col-12 theme-card">                
                <div class="vendor-product common_card" id="new_product_main_div"></div>
            </div>
        </div>
        <div class="row d-none mt-4" id="featured_products_wrapper">
            <div class="col-12 text-center d-flex align-items-center justify-content-between mb-4">
                <div class="title1">
                    <h2 class="title-inner1 mb-0">Feature Product</h2>
                </div>
                <a class="view_more_items" href="#">View More</a>
            </div>
            <div class="col-12 theme-card">                
                <div class="vendor-product common_card" id="feature_product_main_div"></div>
            </div>
        </div>
        
        <div class="row d-none mt-md-5 mt-4" id="bestseller_products_wrapper">
            <div class="col-12 text-center d-flex align-items-center justify-content-between mb-4">
                <div class="title1">
                    <h2 class="title-inner1 mb-0">Best Seller</h2>
                </div>
                <a class="view_more_items" href="#">View More</a>
            </div>
            <div class="col-12 theme-card">                
                <div class="vendor-product common_card" id="best_seller_main_div">
                
                </div>
            </div>
        </div>
        
        <div class="row d-none mt-4" id="onsale_products_wrapper">
            <div class="col-12 text-center d-flex align-items-center justify-content-between mb-4">
                <div class="title1">
                    <h2 class="title-inner1 mb-0">On Sale</h2>
                </div>
                <a class="view_more_items" href="#">View More</a>
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
                    <h2 class="title-inner1 mb-0">Brands</h2>
                </div>
                <!-- <a class="view_more_items" href="#">View More</a> -->
            </div>
            <div class="col-md-12">
                <div class="slide-6 no-arrow" id="brand_main_div">
                    
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
<script src="{{asset('front-assets/js/jquery.exitintent.js')}}"></script>
<script src="{{asset('front-assets/js/fly-cart.js')}}"></script>
@endsection