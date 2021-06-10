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
            <div class="home  text-center">
                <img src="{{$banner->image['proxy_url'] . '1500/600' . $banner->image['image_path']}}"
                    class="bg-img blur-up lazyload" alt="">
                <!--<div class="container">
                        <div class="row">
                            <div class="col">
                                <div class="slider-contain">
                                    <div>
                                        <h4>welcome to fashion</h4>
                                        <h1>men fashion</h1>
                                        <a href="#" class="btn btn-solid">shop now</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
            </div>
        </div>
        @endforeach
    </div>
</section>
<!-- Home slider end -->
<!-- category -->
<!-- <section class="section-b-space border-section border-top-0">
        <div class="row">
            <div class="col">
                <div class="slide-6 no-arrow">
                    @foreach($navCategories as $cate)
                    <div class="category-block">
                        <a href="{{route('categoryDetail', $cate['id'])}}">
                            <div class="category-image"><img src="{{$cate['icon']['proxy_url'].'40/30'.$cate['icon']['image_path']}}" alt=""></div>
                        </a>
                        <div class="category-details">
                            <a href="{{route('categoryDetail', $cate['id'])}}">
                                <h5>{{$cate['name']}}</h5>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section> -->

<!-- Paragraph-->
<!-- <div class="title1 section-t-space">
        <h2 class="title-inner1">Vendors</h2>
    </div> -->
<!-- Home Banner Start From Here -->
<!-- <section class="home-banner">
    <div class="home_slider">
        <div>
            <div class="main-img" style="background: url({{asset('assets/images/food.webp')}});">
                <div class="container-fluid">
                    <div class="row">
                        <div class="offset-lg-6"></div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div class="main-img" style="background: url({{asset('assets/images/food.webp')}});">
                <div class="container-fluid">
                    <div class="row">
                        <div class="offset-lg-6"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> -->

<section class="section-b-space p-t-0 pt-5 ratio_asos">
    <div class="container">
        <div class="row">
            <div class="col">
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
                            <!-- <div class="cart-info cart-wrap">
                                    <button data-toggle="modal" data-target="#addtocart" title="Add to cart">
                                        <i class="ti-shopping-cart"></i>
                                    </button>
                                    <a href1="javascript:void(0)" title="Add to Wishlist">
                                        <i class="ti-heart" aria-hidden="true"></i>
                                    </a>
                                    <a href1="#" data-toggle="modal" data-target="#quick-view" title="Quick View">
                                        <i class="ti-search" aria-hidden="true"></i>
                                    </a>
                                    <a href1="compare.html" title="Compare">
                                        <i class="ti-reload" aria-hidden="true"></i>
                                    </a>
                                </div> -->
                        </div>

                        <div class="product-detail">
                            <div class="rating"><i class="fa fa-star"></i> <i class="fa fa-star"></i> <i
                                    class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i>
                            </div>
                            <a href="#">
                                <h6>{{$vendor->name}}</h6>
                            </a>
                            <!-- <h4>{{$vendor->name}}</h4>
                                <ul class="color-variant">
                                    <li class="bg-light0"></li>
                                    <li class="bg-light1"></li>
                                    <li class="bg-light2"></li>
                                </ul> -->
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</section>
<!-- Product slider end -->

<section class="section-b-space">
    <div class="container">
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

                    <!--<div>
                            <div class="logo-block">
                                <a href="#"><img src="{{asset('front-assets/images/logos/2.png')}}" alt=""></a>
                            </div>
                        </div>
                        <div>
                            <div class="logo-block">
                                <a href="#"><img src="{{asset('front-assets/images/logos/3.png')}}" alt=""></a>
                            </div>
                        </div>
                        <div>
                            <div class="logo-block">
                                <a href="#"><img src="{{asset('front-assets/images/logos/4.png')}}" alt=""></a>
                            </div>
                        </div>
                        <div>
                            <div class="logo-block">
                                <a href="#"><img src="{{asset('front-assets/images/logos/5.png')}}" alt=""></a>
                            </div>
                        </div>
                        <div>
                            <div class="logo-block">
                                <a href="#"><img src="{{asset('front-assets/images/logos/6.png')}}" alt=""></a>
                            </div>
                        </div>
                        <div>
                            <div class="logo-block">
                                <a href="#"><img src="{{asset('front-assets/images/logos/7.png')}}" alt=""></a>
                            </div>
                        </div>
                        <div>
                            <div class="logo-block">
                                <a href="#"><img src="{{asset('front-assets/images/logos/8.png')}}" alt=""></a>
                            </div>
                        </div> -->
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
    jQuery('.home_slider').slick({
        dots: true,
        arrows: false,
        infinite: true,
        speed: 300,
        slidesToShow: 1,
        adaptiveHeight: true
    });  
</script>

@endsection