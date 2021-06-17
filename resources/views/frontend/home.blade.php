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
    <% _.each(vendor_options, function(vendor, k){%>
        <div class="product-box">
            <div class="img-wrapper">
                <div class="front">
                    <a href="{{route('vendorDetail')}}/<%= vendor.id %>"><img class="img-fluid blur-up lazyload bg-img" alt="" src="<%= vendor.logo['proxy_url'] %>300/300<%= vendor.logo['image_path'] %>"></a>
                </div>
                <div class="back">
                    <a href="{{route('vendorDetail')}}/<%= vendor.id %>"><img class="img-fluid blur-up lazyload bg-img" alt="" src="<%= vendor.logo['proxy_url'] %>300/300<%= vendor.logo['image_path'] %>"></a>
                </div>
            </div>
            <div class="product-detail">
                <div class="rating"><i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i></div>
                <a href="#"><h6><%= vendor.name %></h6></a>
            </div>
        </div>
    <% }); %>
</script>
<section class="section-b-space p-t-0 pt-5 ratio_asos pb-0">
    <div class="container">
        <div class="row">
        <div class="col-12 text-center mb-4">
                <div class="title1">
                    <h2 class="title-inner1">Our Vendors</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="product-4 product-m no-arrow">
                    @if(count($vendors) > 0)
                        @foreach($vendors as $vendor)
                        <div class="product-box">
                            <div class="img-wrapper">
                                <div class="front">
                                    <a href="{{route('vendorDetail', $vendor->id)}}">
                                        <img class="img-fluid blur-up lazyload bg-img" src="{{$vendor->logo['proxy_url'] . '1000/1000' . $vendor->logo['image_path']}}">
                                    </a>
                                </div>
                                <div class="back">
                                    <a href="{{route('vendorDetail', $vendor->id)}}">
                                        <img class="img-fluid blur-up lazyload bg-img" alt="" src="{{$vendor->logo['proxy_url'] . '1000/1000' . $vendor->logo['image_path']}}">
                                    </a>
                                </div>
                            </div>
                            <div class="product-detail">
                                 <a href="#">
                                    <h6>{{$vendor->name}}</h6>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <h4 class="text-center">No vendor exists nearby your location</h4>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/template" id="new_products_template">
    <% _.each(new_product_options, function(product, k){ %>
        <div>
            <a class="card text-center" href="{{route('productDetail')}}/<%= product.sku %>">
                <div class="product-image">
                    <img src="<%= product.imagePath %>" alt="">
                </div>    
                <div class="media-body align-self-center px-3">
                    <div class="inner_spacing">
                        <h3><%= product.title %></h3>
                        <p><%= product.description %></p>
                        <h4>{{ Session::get('currencySymbol') }} <%= (product.price * product.multiply) %></h4>
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
<section class="section-b-space">
    <div class="container">
        <div class="row @if (count($onSaleProducts) < 1) d-none @endif" id="new_products_wrapper">
            <div class="col-12 text-center">
                <div class="title1">
                    <h2 class="title-inner1">New Products</h2>
                </div>
            </div>
            <div class="col-12 theme-card">                
                <div class="vendor-product common_card">
                @if(count($newProducts) > 0)
                    @foreach($newProducts as $newProduct)
                    @foreach($newProduct as $product)
                    @php
                        $title = !empty($product['translation']) ? $product['translation'][0]['title'] : $product['sku'];
                        $body_html = !empty($product['translation']) ? $product['translation'][0]['body_html'] : '';
                        $description = strip_tags($body_html);
                        $multiply = (empty($product['variant'][0]['multiplier'])) ? 1 : $product['variant'][0]['multiplier'];
                        $imagePath = '';
                        foreach ($product['media'] as $k => $v) {
                            $imagePath = $v['image']['path']['proxy_url'].'300/300'.$v['image']['path']['image_path'];
                        }
                    @endphp
                    <div>
                        <a class="card text-center" href="{{route('productDetail', $product['sku'])}}">
                            <div class="product-image">
                                <img src="{{$imagePath}}" alt="">
                            </div>    
                            <div class="media-body align-self-center px-3">
                                <div class="inner_spacing">
                                    <h3>{{ Str::limit($title, 18, '..')}}</h3>
                                    <p>{!! Str::limit($description, 25, '..') !!}</p>
                                    <h4>{{ Session::get('currencySymbol').' '.($product['variant'][0]['price'] * $multiply)}}</h4>
                                    <div class="rating">
                                        @for($i = 1; $i < 6; $i++) 
                                            <i class="fa fa-star"></i>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                    @endforeach
                @endif
                </div>
            </div>
        </div>
        
        <div class="row @if (count($featuredProducts) < 1) d-none @endif" id="featured_products_wrapper">
            <div class="col-12 text-center">
                <div class="title1">
                    <h2 class="title-inner1">Feature Product</h2>
                </div>
            </div>
            <div class="col-12 theme-card">                
                <div class="vendor-product common_card">
                @if(count($featuredProducts) > 0)
                    @foreach($featuredProducts as $featuredProduct)
                    @foreach($featuredProduct as $product)
                    @php
                        $title = !empty($product['translation']) ? $product['translation'][0]['title'] : $product['sku'];
                        $body_html = !empty($product['translation']) ? $product['translation'][0]['body_html'] : '';
                        $description = strip_tags($body_html);
                        $multiply = (empty($product['variant'][0]['multiplier'])) ? 1 : $product['variant'][0]['multiplier'];
                        $imagePath = '';
                        foreach ($product['media'] as $k => $v) {
                            $imagePath = $v['image']['path']['proxy_url'].'300/300'.$v['image']['path']['image_path'];
                        }
                    @endphp
                    <div>
                        <a class="card text-center" href="{{route('productDetail', $product['sku'])}}">
                            <div class="product-image">
                                <img src="{{$imagePath}}" alt="">
                            </div>    
                            <div class="media-body align-self-center px-3">
                                <div class="inner_spacing">
                                    <h3>{{ Str::limit($title, 18, '..')}}</h3>
                                    <p>{!! Str::limit($description, 25, '..') !!}</p>
                                    <h4>{{ Session::get('currencySymbol').' '.($product['variant'][0]['price'] * $multiply)}}</h4>
                                    <div class="rating">
                                        @for($i = 1; $i < 6; $i++) 
                                            <i class="fa fa-star"></i>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                    @endforeach
                @endif
                </div>
            </div>
        </div>
        
        <div class="row @if (count($newProducts) < 1) d-none @endif" id="bestseller_products_wrapper">
            <div class="col-12 text-center">
                <div class="title1">
                    <h2 class="title-inner1">Best Seller</h2>
                </div>
            </div>
            <div class="col-12 theme-card">                
                <div class="vendor-product common_card">
                @if(count($newProducts) > 0)
                    @foreach($newProducts as $newProduct)
                    @foreach($newProduct as $new_product)
                    @php
                        $title = !empty($new_product['translation']) ? $new_product['translation'][0]['title'] : $product['sku'];
                        $body_html = !empty($new_product['translation']) ? $new_product['translation'][0]['body_html'] : '';
                        $description = strip_tags($body_html);
                        $imagePath = '';
                        $multiply = (empty($new_product['variant'][0]['multiplier'])) ? 1 : $new_product['variant'][0]['multiplier'];
                        foreach ($new_product['media'] as $k => $v) {
                            $imagePath = $v['image']['path']['proxy_url'].'300/300'.$v['image']['path']['image_path'];
                        }
                    @endphp
                    <div>
                        <a class="card text-center" href="{{route('productDetail', $product['sku'])}}">
                            <div class="product-image">
                                <img src="{{$imagePath}}" alt="">
                            </div>    
                            <div class="media-body align-self-center px-3">
                                <div class="inner_spacing">
                                    <h3>{{ Str::limit($title, 18, '..')}}</h3>
                                    <p>{!! Str::limit($description, 25, '..') !!}</p>
                                    <h4>{{ Session::get('currencySymbol').' '.($new_product['variant'][0]['price'] * $multiply)}}</h4>
                                    <div class="rating">
                                        @for($i = 1; $i < 6; $i++) 
                                            <i class="fa fa-star"></i>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                    @endforeach
                @endif
                </div>
            </div>
        </div>
        <div class="row @if (count($onSaleProducts) < 1) d-none @endif" id="onsale_products_wrapper">
            <div class="col-12 text-center">
                <div class="title1">
                    <h2 class="title-inner1">On Sale</h2>
                </div>
            </div>
            <div class="col-12 theme-card">                
                <div class="vendor-product common_card">
                @if(count($onSaleProducts) > 0)
                    @foreach($onSaleProducts as $onSaleProduct)
                    @foreach($onSaleProduct as $product)
                    @php
                        $body_html = !empty($product['translation']) ? $product['translation'][0]['body_html'] : '';
                        $title = !empty($product['translation']) ? $product['translation'][0]['title'] : $product['sku'];
                        $description = strip_tags($body_html);
                        $multiply = (empty($product['variant'][0]['multiplier'])) ? 1 : $product['variant'][0]['multiplier'];
                        $imagePath = '';
                        foreach ($product['media'] as $k => $v) {
                            $imagePath = $v['image']['path']['proxy_url'].'300/300'.$v['image']['path']['image_path'];
                        }
                    @endphp
                    <div>
                        <a class="card text-center" href="{{route('productDetail', $product['sku'])}}">
                            <div class="product-image">
                                <img src="{{$imagePath}}" alt="">
                            </div>    
                            <div class="media-body align-self-center px-3">
                                <div class="inner_spacing">
                                    <h3>{{ Str::limit($title, 18, '..')}}</h3>
                                    <p>{!! Str::limit($description, 25, '..') !!}</p>
                                    <h4>{{ Session::get('currencySymbol').' '.($product['variant'][0]['price'] * $multiply)}}</h4>
                                    <div class="rating">
                                        @for($i = 1; $i < 6; $i++) 
                                            <i class="fa fa-star"></i>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                    @endforeach
                @endif
                </div>
            </div>
        </div>
    </div>
</section>
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
<section class="section-b-space pt-0">
    <div class="container">
        <div class="title1">
            <h2 class="title-inner1">Brands</h2>
        </div>
        <div class="row">
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
@if( count($vendors) < 1 )
    <script>$(".product-4").slick('destroy');</script>
@endif
@endsection