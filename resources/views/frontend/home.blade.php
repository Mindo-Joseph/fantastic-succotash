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
        <div class="row no-gutters">
            <div class="col-lg-3 col-md-4 col">
                <div class="d-flex align-items-center justify-content-between px-3" data-toggle="modal" data-target="#edit-address" href="javascript:void(0)">
                    <div class="map-icon"><i class="fa fa-map-marker" aria-hidden="true"></i></div>
                    <div class="homepage-address">
                        <h2><span data-placement="top" data-toggle="tooltip" title="Sector 28C, Chandigarh, India">Sector 28C, Chandigarh, India</span></h2>
                    </div>
                    <div class="down-icon">
                        <i class="fa fa-angle-down" aria-hidden="true"></i>
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
<section class="section-b-space p-t-0 pb-0 pt-5 ratio_asos">
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
                                <a href="{{route('vendorDetail', $vendor->id)}}"><img
                                        class="img-fluid blur-up lazyload bg-img" alt=""
                                        src="{{$vendor->logo['proxy_url'] . '300/300' . $vendor->logo['image_path']}}"></a>
                            </div>
                            <div class="back">
                                <a href="{{route('vendorDetail', $vendor->id)}}"><img
                                        class="img-fluid blur-up lazyload bg-img" alt=""
                                        src="{{$vendor->logo['proxy_url'] . '300/300' . $vendor->logo['image_path']}}"></a>
                            </div>
                        </div>
                        <div class="product-detail">
                             <a href="#">
                                <h6>{{$vendor->name}}</h6>
                            </a>
                            <!-- <div class="rating"><i class="fa fa-star"></i> <i class="fa fa-star"></i> <i
                                    class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i>
                            </div> -->
                            
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
<section class="section-b-space">
    <div class="container">
        
        <div class="row">
            <div class="col-12 text-center">
                <div class="title1">
                    <h2 class="title-inner1">New Products</h2>
                </div>
            </div>
            <div class="col-12 theme-card">                
                <div class="vendor-product common_card">
                    <div>
                        <a class="card text-center" href="#">
                            <div class="product-image">
                                <img src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/NVtOSeR3oh8PW8JPOMCHj4uIQuHUR49M5xqSQMoU.jpg" alt="">
                            </div>    
                            <div class="media-body align-self-center px-3">
                                <div class="inner_spacing">
                                    <h3>Pizza</h3>
                                    <p>Interested in selling this item? List the sale price.</p>
                                    <h4>$100</h4>
                                    <div class="rating">
                                        @for($i = 1; $i < 6; $i++) <i class="fa fa-star"></i>
                                            @endfor
                                    </div>
                                </div>
                                <p class="btn btn-solid w-100">View Details</p>
                            </div>
                        </a>
                    </div>
                    <div>
                        <a class="card text-center" href="#">
                            <div class="product-image">
                                <img src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/NVtOSeR3oh8PW8JPOMCHj4uIQuHUR49M5xqSQMoU.jpg" alt="">
                            </div>    
                            <div class="media-body align-self-center px-3">
                                <div class="inner_spacing">
                                    <h3>Pizza</h3>
                                    <p>Interested in selling this item? List the sale price.</p>
                                    <h4>$100</h4>
                                    <div class="rating">
                                        @for($i = 1; $i < 6; $i++) <i class="fa fa-star"></i>
                                            @endfor
                                    </div>
                                </div>
                                <p class="btn btn-solid w-100">View Details</p>
                            </div>
                        </a>
                    </div>
                    <div>
                        <a class="card text-center" href="#">
                            <div class="product-image">
                                <img src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/NVtOSeR3oh8PW8JPOMCHj4uIQuHUR49M5xqSQMoU.jpg" alt="">
                            </div>    
                            <div class="media-body align-self-center px-3">
                                <div class="inner_spacing">
                                    <h3>Pizza</h3>
                                    <p>Interested in selling this item? List the sale price.</p>
                                    <h4>$100</h4>
                                    <div class="rating">
                                        @for($i = 1; $i < 6; $i++) <i class="fa fa-star"></i>
                                            @endfor
                                    </div>
                                </div>
                                <p class="btn btn-solid w-100">View Details</p>
                            </div>
                        </a>
                    </div>
                    <div>
                        <a class="card text-center" href="#">
                            <div class="product-image">
                                <img src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/NVtOSeR3oh8PW8JPOMCHj4uIQuHUR49M5xqSQMoU.jpg" alt="">
                            </div>    
                            <div class="media-body align-self-center px-3">
                                <div class="inner_spacing">
                                    <h3>Pizza</h3>
                                    <p>Interested in selling this item? List the sale price.</p>
                                    <h4>$100</h4>
                                    <div class="rating">
                                        @for($i = 1; $i < 6; $i++) <i class="fa fa-star"></i>
                                            @endfor
                                    </div>
                                </div>
                                <p class="btn btn-solid w-100">View Details</p>
                            </div>
                        </a>
                    </div>
                    <div>
                        <a class="card text-center" href="#">
                            <div class="product-image">
                                <img src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/NVtOSeR3oh8PW8JPOMCHj4uIQuHUR49M5xqSQMoU.jpg" alt="">
                            </div>    
                            <div class="media-body align-self-center px-3">
                                <div class="inner_spacing">
                                    <h3>Pizza</h3>
                                    <p>Interested in selling this item? List the sale price.</p>
                                    <h4>$100</h4>
                                    <div class="rating">
                                        @for($i = 1; $i < 6; $i++) <i class="fa fa-star"></i>
                                            @endfor
                                    </div>
                                </div>
                                <p class="btn btn-solid w-100">View Details</p>
                            </div>
                        </a>
                    </div>
                    <div>
                        <a class="card text-center" href="#">
                            <div class="product-image">
                                <img src="https://imgproxy.royoorders.com/insecure/fill/300/300/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/NVtOSeR3oh8PW8JPOMCHj4uIQuHUR49M5xqSQMoU.jpg" alt="">
                            </div>    
                            <div class="media-body align-self-center px-3">
                                <div class="inner_spacing">
                                    <h3>Pizza</h3>
                                    <p>Interested in selling this item? List the sale price.</p>
                                    <h4>$100</h4>
                                    <div class="rating">
                                        @for($i = 1; $i < 6; $i++) <i class="fa fa-star"></i>
                                            @endfor
                                    </div>
                                </div>
                                <p class="btn btn-solid w-100">View Details</p>
                            </div>
                        </a>
                    </div>
                    
                </div>
            </div>
        </div>

        <div class="row multiple-slider">

            <div class="col-lg-3 col-sm-6">
                <div class="theme-card">
                    <h5 class="title-border">New Products</h5>
                    <div class="offer-slider slide-1">
                        @foreach($newProducts as $newProduct)
                        <div>
                            @foreach($newProduct as $product)
                            <?php $imagePath = '';
                                    foreach ($product['media'] as $k => $v) {
                                        $imagePath = $v['image']['path']['proxy_url'].'300/300'.$v['image']['path']['image_path'];
                                    } ?>
                            <div class="media">
                                <a href="{{route('productDetail', $product['sku'])}}"><img style="max-width: 200px;"
                                        src="{{$imagePath}}" alt=""></a>
                                <div class="media-body align-self-center">
                                    <div class="rating">
                                        @for($i = 1; $i < 6; $i++) <i class="fa fa-star"></i>
                                            @endfor
                                    </div>
                                    <a href="{{route('productDetail', $product['sku'])}}">
                                        <h6>{{ !empty($product['translation']) ? $product['translation'][0]['title'] : $product['sku']}}
                                        </h6>
                                    </a>
                                    <h4>
                                        <?php $multiply = (empty($product['variant'][0]['multiplier'])) ? 1 : $product['variant'][0]['multiplier']; ?>
                                        {{ Session::get('currencySymbol').' '.($product['variant'][0]['price'] * $multiply)}}
                                    </h4>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="theme-card">
                    <h5 class="title-border">feature product</h5>
                    <div class="offer-slider slide-1">
                        @foreach($featuredProducts as $featuredProduct)
                        <div>
                            @foreach($featuredProduct as $product)
                            <?php $imagePath = '';
                                    foreach ($product['media'] as $k => $v) {
                                        $imagePath = $v['image']['path']['proxy_url'].'300/300'.$v['image']['path']['image_path'];
                                    } ?>
                            <div class="media">
                                <a href="{{route('productDetail', $product['sku'])}} "><img style="max-width: 200px;"
                                        src="{{$imagePath}}" alt=""></a>
                                <div class="media-body align-self-center">
                                    <div class="rating">
                                        @for($i = 1; $i < 6; $i++) <i class="fa fa-star"></i>
                                            @endfor
                                    </div>
                                    <a href="{{route('productDetail', $product['sku'])}}">
                                        <h6>{{ !empty($product['translation']) ? $product['translation'][0]['title'] : $product['sku']}}
                                        </h6>
                                    </a>
                                    <h4>
                                        <?php $multiply = (empty($product['variant'][0]['multiplier'])) ? 1 : $product['variant'][0]['multiplier']; ?>
                                        {{ Session::get('currencySymbol').' '.($product['variant'][0]['price'] * $multiply)}}
                                    </h4>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="theme-card">
                    <h5 class="title-border">best seller</h5>
                    <div class="offer-slider slide-1">
                        @foreach($newProducts as $newProduct)
                        <div>
                            @foreach($newProduct as $product)
                            <?php $imagePath = '';
                                    foreach ($product['media'] as $k => $v) {
                                        $imagePath = $v['image']['path']['proxy_url'].'300/300'.$v['image']['path']['image_path'];
                                    } ?>
                            <div class="media">
                                <a href="{{route('productDetail', $product['sku'])}} "><img style="max-width: 200px;"
                                        src="{{$imagePath}}" alt=""></a>
                                <div class="media-body align-self-center">
                                    <div class="rating">
                                        @for($i = 1; $i < 6; $i++) <i class="fa fa-star"></i>
                                            @endfor
                                    </div>
                                    <a href="{{route('productDetail', $product['sku'])}}">
                                        <h6>{{ !empty($product['translation']) ? $product['translation'][0]['title'] : $product['sku']}}
                                        </h6>
                                    </a>
                                    <h4>
                                        <?php $multiply = (empty($product['variant'][0]['multiplier'])) ? 1 : $product['variant'][0]['multiplier']; ?>
                                        {{ Session::get('currencySymbol').' '.($product['variant'][0]['price'] * $multiply)}}
                                    </h4>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="theme-card">
                    <h5 class="title-border">on sale</h5>
                    <div class="offer-slider slide-1">
                        @foreach($onSaleProducts as $onSaleProduct)
                        <div>
                            @foreach($onSaleProduct as $product)
                            <?php $imagePath = '';
                                    foreach ($product['media'] as $k => $v) {
                                        $imagePath = $v['image']['path']['proxy_url'].'300/300'.$v['image']['path']['image_path'];
                                    } ?>
                            <div class="media">
                                <a href="{{route('productDetail', $product['sku'])}} "><img style="max-width: 200px;"
                                        src="{{$imagePath}}" alt=""></a>
                                <div class="media-body align-self-center">
                                    <div class="rating">
                                        @for($i = 1; $i < 6; $i++) <i class="fa fa-star"></i>
                                            @endfor
                                    </div>
                                    <a href="{{route('productDetail', $product['sku'])}}">
                                        <h6>{{ !empty($product['translation']) ? $product['translation'][0]['title'] : $product['sku']}}
                                        </h6>
                                    </a>
                                    <h4>
                                        <?php $multiply = (empty($product['variant'][0]['multiplier'])) ? 1 : $product['variant'][0]['multiplier']; ?>
                                        {{ Session::get('currencySymbol').' '.($product['variant'][0]['price'] * $multiply)}}
                                    </h4>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!--  logo section -->
<section class="section-b-space">
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
                            <a href="{{route('brandDetail', $brand->id)}}"><img
                                    src="{{$brand->image['image_fit'] . '120/120' . $brand->image['image_path']}}"
                                    alt=""></a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Address Edit Modal Start Form Here -->
<div class="modal fade edit_address" id="edit-address" tabindex="-1" aria-labelledby="edit-addressLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body p-0">
            <div id="address-map-container" style="width:100%;height:400px; ">
                <div style="width: 100%; height: 100%" id="address-map"></div>
            </div>
            <div id="step_one">
                <div class="delivery_address p-3 position-relative">
                    <div class="modal-title">Set your delivery location</div>
                    <button type="button" class="close edit-close position-absolute" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <div class="form-group">
                        <label class="delivery-head">DELIVERY AREA</label>
                        <div class="select_address border d-flex align-items-center justify-content-between ">
                            <div class="location-area">
                                <i class="fa fa-check-circle-o" aria-hidden="true"></i>
                                <span>Sector 28 C, Chandigarh, India</span>
                            </div>   
                            <label class="m-0 text-uppercase">Change</label>
                        </div>
                        <div class="address-input-field d-none align-items-center justify-content-between">
                            <i class="fa fa-map-marker" aria-hidden="true"></i>
                            <input class="form-control border-0 map-input" type="text" name="" id="address-input">
                            <input type="hidden" name="address_latitude" id="address-latitude" value="0" />
                            <input type="hidden" name="address_longitude" id="address-longitude" value="0" />
                        </div>
                        <div class="edit-area">
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
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="button" class="btn btn-solid ml-auto confirm_address_btn">Confirm And Proceed</button>
                    </div>
                </div>
            </div>            
        </div>
    </div>
  </div>
</div>
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