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
                    <div class="address">
                        <h2 _ngcontent-saas-website-c153="" style="color: rgb(0, 0, 0);"><span _ngcontent-saas-website-c153="" data-placement="top" data-toggle="tooltip" title="Hyderabad,, Janapriya Nagar, Hafeezpet, Hyderabad, Telangana 500049, India" style="color: rgb(0, 0, 0);">Hyderabad,, Janapriya Nagar, Hafeezpet, Hyderabad, Telangana 500049, India </span></h2>
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
                        </div>
                        <div class="product-detail">
                            <div class="rating"><i class="fa fa-star"></i> <i class="fa fa-star"></i> <i
                                    class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i>
                            </div>
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
           
            <div id="step_one">
                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d13720.904154980397!2d76.81441854999998!3d30.71204525!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin!4v1622198188924!5m2!1sen!2sin" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            
                <div class="delivery_address p-3 position-relative">
                    <div class="modal-title">Set your delivery location</div>
                    <button type="button" class="close edit-close position-absolute" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <div class="form-group">
                        <label class="delivery-head">DELIVERY AREA</label>
                        <div class="select_address border d-flex align-items-center justify-content-between ">
                            <div class="location-area">
                                <i class="fa fa-check-circle-o" aria-hidden="true"></i>
                                <span>22A, Sector 22</span>
                            </div>    
                            <label class="m-0 text-uppercase">Change</label>
                        </div>
                    </div>
                    <div class="text-right">
                        <a class="btn btn-solid ml-auto next-step" href="javascript:void(0)">Confirm And Proceed</a>
                    </div>
                </div>
            </div>

            <div id="step-two">
                 <div class="delivery_address p-3 position-relative">
                    <div class="modal-title">Set your delivery location</div>
                    <button type="button" class="close edit-close hide-address position-absolute"><span aria-hidden="true">&times;</span></button>
                    <div class="form-group">
                        <label class="delivery-head">DELIVERY AREA</label>
                        <div class="address-input-field d-flex align-items-center">
                            <i class="fa fa-map-marker" aria-hidden="true"></i>
                            <input class="form-control border-0" type="text" name="" id="">
                        </div>
                    </div>
                    <div class="address_list"></div>
                    <div class="text-right d-none">
                        <a class="btn btn-solid ml-auto" href="#">Confirm and Proceed</a>
                    </div>
                </div>
            </div>
           
            <div id="step-three">
                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d13720.904154980397!2d76.81441854999998!3d30.71204525!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin!4v1622198188924!5m2!1sen!2sin" width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                <div class="delivery_address p-3 position-relative">
                    <div class="modal-title">Set your delivery location 3   </div>
                    <button type="button" class="close edit-close go-back position-absolute"><span aria-hidden="true">&times;</span></button>
                    <div class="form-group">
                        <label class="delivery-head">DELIVERY AREA</label>
                        <div class="select_address border d-flex align-items-center justify-content-between ">
                            <div class="location-area">
                                <i class="fa fa-check-circle-o" aria-hidden="true"></i>
                                <span>22A, Sector 22</span>
                            </div>    
                            <label class="m-0 text-uppercase">Change</label>
                        </div>
                        <div class="edit-area">
                            <input class="form-control" type="text" placeholder="Complete Address *" name="" id="">
                            <input class="form-control" type="text" placeholder="Floor (Optional)" name="" id="">
                            <input class="form-control" type="text" placeholder="How to reach (Optional)" name="" id="">
                        </div>
                        <div class="mt-2 mb-2">
                            <div class="address_type">
                                <label class="radio d-inline-block m-0">Home
                                    <input type="radio" name="is_company">
                                    <span class="checkround"></span>
                                </label>
                                <label class="radio d-inline-block m-0">Office
                                    <input type="radio" name="is_company">
                                    <span class="checkround"></span>
                                </label>
                                <label class="radio other_address d-inline-block m-0">Other
                                    <input type="radio" name="is_company">
                                    <span class="checkround"></span>
                                </label>   
                            </div>

                            <div class="other-address-input">
                                <label class="radio other_address d-inline-block m-0">Other
                                    <input type="radio" checked="checked" name="is_company">
                                    <span class="checkround"></span>
                                </label>   
                                <div class="address-input-field">
                                    <input class="form-control border-0" type="text" name="" id="">
                                    <label class="hide-other m-0 text-uppercase">Changes</label>
                                </div>                      
                            </div>                      
                        </div>
                       
                    </div>
                    <div class="text-right">
                        <a class="btn btn-solid ml-auto" href="#">Save and Proceed</a>
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
    jQuery("#step-two").hide();
    jQuery("#step-three").hide();
    jQuery(".other-address-input").hide();
    jQuery(document).ready(function () {
        jQuery(".select_address").click(function () {
            jQuery("#step-two").show();
            jQuery("#step_one").hide();
        });
        jQuery(".hide-address").click(function () {
            jQuery("#step-two").hide();
            jQuery("#step_one").show();
        });
        jQuery(".next-step").click(function(){
            jQuery("#step-three").show();
            jQuery("#step_one").hide();
        });
        jQuery(".go-back").click(function(){
            jQuery("#step-three").hide();
            jQuery("#step_one").show();
        });
        jQuery(".other_address").click(function(){
            jQuery(".other-address-input").show();
            jQuery(".address_type").hide();
        });
        jQuery(".hide-other").click(function(){
            jQuery(".other-address-input").hide();
            jQuery(".address_type").show();
        });
        jQuery(".select_address").click(function () {
            jQuery("#step-three").hide();
        });
        jQuery(".hide-address").click(function () {
            jQuery("#step-three").show();
            jQuery("#step_one").hide();
        });
    });
</script>

@endsection