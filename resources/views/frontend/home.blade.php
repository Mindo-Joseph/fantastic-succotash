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
<div class="find_location">
    <div class="container px-0">
        <?php /* ?>
        <div class="row no-gutters" id="location_search_wrapper">
            <div class="col-lg-3 col-md-4 col">
                <div class="d-flex align-items-center justify-content-start px-3" href="#edit-address" data-toggle="modal">
                    <div class="map-icon mr-1"><i class="fa fa-map-marker" aria-hidden="true"></i></div>
                    <div class="homepage-address">
                        <h2><span data-placement="top" data-toggle="tooltip" title="{{$deliveryAddress}}">{{$deliveryAddress}}</span></h2>
                    </div>
                    <div class="down-icon">
                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                    </div>
                </div>
                
                <div class="d-flex align-items-center justify-content-start px-3 dropdown-toggle" id="dropdownLocationButton" data-toggle="dropdown" aria-haspopup="true" 
                  aria-expanded="false">
                    <div class="map-icon mr-1"><i class="fa fa-map-marker" aria-hidden="true"></i></div>
                    <div class="homepage-address">
                        <h2><span data-placement="top" data-toggle="tooltip" title="{{$deliveryAddress}}">{{$deliveryAddress}}</span></h2>
                    </div>
                    <div class="down-icon">
                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="dropdown-menu p-0" aria-labelledby="dropdownLocationButton" style="max-width:400px;width:100%">
                    <div id="address-map-container">
                        <div id="address-map"></div>
                    </div>
                    <div class="delivery_address p-2 position-relative">
                        <div class="modal-title">Set your delivery location</div>
                        <button type="button" class="close edit-close position-absolute" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <div class="form-group">
                            <label class="delivery-head">DELIVERY AREA</label>
                            <!--<div class="select_address border d-flex align-items-center justify-content-between ">
                                <div class="location-area">
                                    <i class="fa fa-check-circle-o" aria-hidden="true"></i>
                                    <span>Sector 28 C, Chandigarh, India</span>
                                </div>   
                                <label class="m-0 text-uppercase">Change</label>
                            </div>-->
                            <div class="address-input-field d-flex align-items-center justify-content-between">
                                <i class="fa fa-map-marker" aria-hidden="true"></i>
                                <input class="form-control border-0 map-input" type="text" name="address-input" id="address-input" value="{{$deliveryAddress}}">
                                <input type="hidden" name="address_latitude" id="address-latitude" value="{{$latitude}}" />
                                <input type="hidden" name="address_longitude" id="address-longitude" value="{{$longitude}}" />
                            </div>
                            <!--<div class="edit-area">
                                <input class="form-control" type="text" placeholder="Complete Address *" name="complete_address" id="complete_address">
                                <input class="form-control" type="text" placeholder="Floor (Optional)" name="floor" id="floor">
                                <input class="form-control" type="text" placeholder="How to reach (Optional)" name="address_hint" id="address_hint">
                            </div>
                            <div class="mt-2 mb-2">
                                <div class="address_type">
                                    <label class="radio d-inline-block m-0">Home
                                        <input type="radio" name="address_type" checked="checked" value="home">
                                        <span class="checkround"></span>
                                    </label>
                                    <label class="radio d-inline-block m-0">Office
                                        <input type="radio" name="address_type" value="office">
                                        <span class="checkround"></span>
                                    </label>
                                    <label class="radio other_address d-inline-block m-0">Other
                                        <input type="radio" name="address_type" value="other">
                                        <span class="checkround"></span>
                                    </label>   
                                </div>
                                <div class="other-address-input d-none">
                                    <label class="d-inline-block m-0">
                                        <input type="text" name="other_address">
                                    </label>
                                </div>                      
                            </div>-->
                        </div>
                        <div class="text-center">
                            <button type="button" class="btn btn-solid ml-auto confirm_address_btn w-100 w-100">Confirm And Proceed</button>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="col-lg-9 col-md-8 col">
                <form class="search_form d-flex align-items-center justify-content-between" action="">
                    <input class="form-control border-0" type="text" placeholder="Search">
                    <button class="btn btn-solid px-md-3 px-2"><i class="fa fa-search" aria-hidden="true"></i><!--span class="search-text">Search</span--></button>
                </form>
            </div>
        </div>
        <?php */ ?>
    </div>
</div>
<section class="p-0 small-slider">
    <div class="slide-1 home-slider">
        @foreach($banners as $banner)
        <div>
            <div class="home  text-center">
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
<script type="text/template" id="featured_products_template">
    <% _.each(featured_product_options, function(product, k){ %>
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
<script type="text/template" id="bestseller_products_template">
    <% _.each(bestseller_product_options, function(product, k){ %>
        <div>
            <a class="card text-center" href="{{route('productDetail')}}/<%= product.sku %>")}}">
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
<script type="text/template" id="onsale_products_template">
    <% _.each(onsale_product_options, function(product, k){ %>
        <div>
            <a class="card text-center" href="{{route('productDetail')}}/<%= product.sku %>")}}">
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
        @if(count($newProducts) > 0)
        <div class="row" id="new_products_wrapper">
            <div class="col-12 text-center">
                <div class="title1">
                    <h2 class="title-inner1">New Products</h2>
                </div>
            </div>
            <div class="col-12 theme-card">                
                <div class="vendor-product common_card">
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
                </div>
            </div>
        </div>
        @endif
        @if(count($featuredProducts) > 0)
        <div class="row" id="featured_products_wrapper">
            <div class="col-12 text-center">
                <div class="title1">
                    <h2 class="title-inner1">Feature Product</h2>
                </div>
            </div>
            <div class="col-12 theme-card">                
                <div class="vendor-product common_card">
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
                </div>
            </div>
        </div>
        @endif
        @if(count($newProducts) > 0)
        <div class="row" id="bestseller_products_wrapper">
            <div class="col-12 text-center">
                <div class="title1">
                    <h2 class="title-inner1">Best Seller</h2>
                </div>
            </div>
            <div class="col-12 theme-card">                
                <div class="vendor-product common_card">
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
                </div>
            </div>
        </div>
        @endif
        @if(count($onSaleProducts) > 0)
        <div class="row" id="onsale_products_wrapper">
            <div class="col-12 text-center">
                <div class="title1">
                    <h2 class="title-inner1">On Sale</h2>
                </div>
            </div>
            <div class="col-12 theme-card">                
                <div class="vendor-product common_card">
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
                </div>
            </div>
        </div>
        @endif
    </div>
</section>
<section class="section-b-space pt-0">
    <div class="container">
        <div class="title1">
            <h2 class="title-inner1">Brands</h2>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="slide-6 no-arrow">
                    @foreach($brands as $brand)
                    <div>
                        <div class="logo-block">
                            <a href="{{route('brandDetail', $brand->id)}}">
                                <img src="{{$brand->image['image_fit'] . '120/120' . $brand->image['image_path']}}" alt="">
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
<script src="{{asset('front-assets/js/jquery.exitintent.js')}}"></script>
<script src="{{asset('front-assets/js/fly-cart.js')}}"></script>
<script type="text/javascript">
    var homepage_url = "{{route('homepage')}}";
    jQuery('.home_slider').slick({
        dots: true,
        arrows: false,
        infinite: true,
        speed: 300,
        slidesToShow: 1,
        adaptiveHeight: true
    });
</script>

<script>
     $('.vendor-product').slick({
        infinite: true,
        speed: 300,
        arrows: false,
        slidesToShow: 5,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 5000,
        responsive: [{
                breakpoint: 1200,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });
</script>

@endsection