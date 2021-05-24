@extends('layouts.store', ['title' => 'Product'])

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
</style>
<section class="section-b-space">
    <div class="collection-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-sm-3 collection-filter">
                    <div class="collection-filter-block">
                        <div class="collection-mobile-back">
                            <span class="filter-back">
                                <i class="fa fa-angle-left" aria-hidden="true"></i>
                                back
                            </span>
                        </div>
                        <div class="collection-collapse-block border-0 open">
                            <h3 class="collapse-block-title">brand</h3>
                            <div class="collection-collapse-block-content">
                                <div class="collection-brand-filter">
                                    <ul class="category-list">
                                        <li><a href="#">clothing</a></li>
                                        <li><a href="#">bags</a></li>
                                        <li><a href="#">footwear</a></li>
                                        <li><a href="#">watches</a></li>
                                        <li><a href="#">accessories</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="collection-filter-block">
                        <div class="product-service">
                            <div class="media">
                                <div class="media-body">
                                    <h4>free shipping</h4>
                                    <p>free shipping world wide</p>
                                </div>
                            </div>
                            <div class="media">

                                <div class="media-body">
                                    <h4>24 X 7 service</h4>
                                    <p>online service for new customer</p>
                                </div>
                            </div>
                            <div class="media">

                                <div class="media-body">
                                    <h4>festival offer</h4>
                                    <p>new online special festival offer</p>
                                </div>
                            </div>
                            <div class="media border-0 m-0">

                                <div class="media-body">
                                    <h4>online payment</h4>
                                    <p>Contrary to popular belief.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- side-bar single product slider start -->
                    <div class="theme-card">
                        <h5 class="title-border">new product</h5>
                        <div class="offer-slider slide-1">
                            @foreach($newProducts as $newProds)
                            <div>
                                @foreach($newProds as $new)
                                <?php $imagePath = '';
                                foreach ($new['media'] as $k => $v) {
                                    $imagePath = $v['image']['path']['proxy_url'] . '300/300' . $v['image']['path']['image_path'];
                                } ?>
                                <div class="media">
                                    <a href="{{route('productDetail', $new['sku'])}} "><img class="img-fluid blur-up lazyload" style="max-width: 200px;" src="{{$imagePath}}" alt=""></a>
                                    <div class="media-body align-self-center">
                                        <div class="rating">
                                            @for($i = 1; $i < 6; $i++) <i class="fa fa-star"></i>
                                                @endfor
                                        </div>
                                        <a href="{{route('productDetail', $new['sku'])}}">
                                            <h6>{{(!empty($new['translation']) && isset($new['translation'][0])) ? $new['translation'][0]['title'] : $new['sku']}}</h6>
                                        </a>
                                        <h4>
                                            <?php $multiply = (empty($new['variant'][0]['multiplier'])) ? 1 : $new['variant'][0]['multiplier']; ?>
                                            {{ Session::get('currencySymbol').' '.($new['variant'][0]['price'] * $multiply)}}
                                        </h4>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 col-sm-12 col-xs-12">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="filter-main-btn mb-2"><span class="filter-btn"><i class="fa fa-filter" aria-hidden="true"></i> filter</span></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="product-slick">
                                    @if(!empty($product->media))
                                    @foreach($product->media as $k => $img)
                                    <div><img class="img-fluid blur-up lazyload image_zoom_cls-{{$k}}" alt="" src="{{$img->image->path['proxy_url'].'600/800'.$img->image->path['image_path']}}"></div>
                                    @endforeach
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="col-12 p-0">
                                        <div class="slider-nav">
                                            @if(!empty($product->media))
                                            @foreach($product->media as $k => $img)
                                            <div><img class="img-fluid blur-up lazyload" alt="" src="{{$img->image->path['proxy_url'].'300/300'.$img->image->path['image_path']}}"></div>
                                            @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 rtl-text">
                                <div class="product-right">
                                    <h2>{{ (!empty($product->translation) && isset($product->translation[0])) ? $product->translation[0]->title : ''}}</h2>
                                    <input type="hidden" name="variant_id" id="prod_variant_id" value="{{$product->variant[0]->id}}">
                                    <!--<h4><del>$459.00</del><span>55% off</span></h4> -->
                                    <h3 id="productPriceValue">{{Session::get('currencySymbol').($product->variant[0]->price * $product->variant[0]->multiplier)}}</h3>
                                    
                                    @if(!empty($product->variantSet))
                                    @php 
                                        $selectedVariant = isset($product->variant[0]) ? $product->variant[0]->id : 0;
                                    @endphp

                                    @foreach($product->variantSet as $key => $variant)
                                    @if($variant->type == 1 || $variant->type == 2)

                                    <div class="size-box">
                                        <ul class="productVariants">
                                            <li class="firstChild">{{$variant->title}}</li>
                                            <li class="otherSize">
                                                @foreach($variant->option2 as $k => $optn)

                                                <?php $var_id = $variant->variant_type_id;
                                                $opt_id = $optn->variant_option_id;
                                                $checked = ($selectedVariant == $optn->product_variant_id) ? 'checked' : '';
                                                ?>
                                                <label class="radio d-inline-block txt-14">{{$optn->title}}
                                                    <input id="lineRadio-{{$opt_id}}" name="{{'var_'.$var_id}}" vid="{{$var_id}}" optid="{{$opt_id}}" value="{{$opt_id}}" type="radio" {{$checked}} class="changeVariant dataVar{{$var_id}}">
                                                    <span class="checkround"></span>
                                                </label>

                                                @endforeach
                                            </li>
                                        </ul>
                                    </div>

                                    @else
                                    <!-- <div class="size-box">
                                                <ul class="productVariants">
                                                    <li class="firstChild">{{$variant->title}}</li>
                                                    @foreach($variant->option2 as $k => $optn)
                                                    <li class="otherSize">
                                                        <?php /*$var_id = $variant->variant_type_id;
                                                            $opt_id = $optn->variant_option_id;
                                                            $checked = ($product->variant[0]->set[$key]->variant_option_id == $optn->variant_option_id) ? 'checked' : '';*/
                                                        ?>
                                                        <div class="radio radio-info form-check-inline">
                                                            <input id="lineRadio-{{$opt_id}}" name="{{'var_'.$var_id}}" vid="{{$var_id}}" optid="{{$opt_id}}" value="{{$opt_id}}" type="radio" {{$checked}} class="changeVariant dataVar{{$var_id}}">
                                                            <label for="lineRadio-{{$opt_id}}">{{$optn->title}}</label>
                                                        </div>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </div> -->
                                    @endif
                                    @endforeach
                                    @endif

                                    <div class="product-description border-product">
                                        <h6 class="product-title">quantity: <span id="instock" style="color: green;">In Stock ({{$product->variant[0]->quantity}})</span></h6>
                                        <div class="qty-box">
                                            <div class="input-group">
                                                <span class="input-group-prepend"><button type="button" class="btn quantity-left-minus" data-type="minus" data-field=""><i class="ti-angle-left"></i></button> </span>
                                                <input type="text" name="quantity" id="quantity" class="form-control input-number quantity_count" value="1">
                                                <span class="input-group-prepend quant-plus"><button type="button" class="btn quantity-right-plus " data-type="plus" data-field=""><i class="ti-angle-right"></i></button></span>
                                            </div>
                                        </div>
                                    </div>

                                    @if(!empty($product->addOn))
                                    <div class="border-product">
                                        <h6 class="product-title">Addon List</h6>

                                        <table class="table table-centered table-nowrap table-striped" id="banner-datatable">
                                            <tbody>
                                                @foreach($product->addOn as $row => $addon)
                                                <tr>
                                                    <td>
                                                        <h4 addon_id="{{$addon->addon_id}}" class="header-title productAddon">{{$addon->title}}</h4>
                                                    </td>
                                                    <td>
                                                        @foreach($addon->setoptions as $k => $option)
                                                        <div class="checkbox checkbox-success form-check-inline">
                                                            <input type="checkbox" id="inlineCheckbox{{$k}}" class="chkPassport" name="addonData[$row][]" addonId="{{$addon->addon_id}}" addonOptId="{{$option->id}}">
                                                            <label class="pl-2" for="inlineCheckbox{{$k}}"> {{$option->title .' ($'.$option->price.')' }}</label>
                                                        </div>
                                                        @endforeach
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @endif

                                    <div class="product-buttons"><a href="#" data-toggle="modal" data-target="#addtocart" class="btn btn-solid addToCart">add to cart</a> <a href="#" class="btn btn-solid">buy now</a></div>
                                    <div class="border-product">
                                        <h6 class="product-title">product details</h6>
                                        <p>{{ (!empty($product->translation) && isset($product->translation[0])) ? $product->translation[0]->body_html : ''}}</p>
                                    </div>
                                    <!--<div class="border-product">
                                        <h6 class="product-title">share it</h6>
                                        <div class="product-icon">
                                            <ul class="product-social">
                                                <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                                <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                                                <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                                <li><a href="#"><i class="fa fa-instagram"></i></a></li>
                                                <li><a href="#"><i class="fa fa-rss"></i></a></li>
                                            </ul>
                                            <form class="d-inline-block">
                                                <button class="wishlist-btn"><i class="fa fa-heart"></i><span
                                                        class="title-font">Add To WishList</span></button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="border-product">
                                        <h6 class="product-title">Time Reminder</h6>
                                        <div class="timer">
                                            <p id="demo"><span>25 <span class="padding-l">:</span> <span
                                                        class="timer-cal">Days</span> </span><span>22 <span
                                                        class="padding-l">:</span> <span
                                                        class="timer-cal">Hrs</span> </span><span>13 <span
                                                        class="padding-l">:</span> <span
                                                        class="timer-cal">Min</span> </span><span>57 <span
                                                        class="timer-cal">Sec</span></span>
                                            </p>
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <section class="tab-product m-0">
                        <div class="row">
                            <div class="col-sm-12 col-lg-12">
                                <ul class="nav nav-tabs nav-material" id="top-tab" role="tablist">
                                    <li class="nav-item"><a class="nav-link active" id="top-home-tab" data-toggle="tab" href="#top-home" role="tab" aria-selected="true"><i class="icofont icofont-ui-home"></i>Description</a>
                                        <div class="material-border"></div>
                                    </li>
                                    <li class="nav-item"><a class="nav-link" id="profile-top-tab" data-toggle="tab" href="#top-profile" role="tab" aria-selected="false"><i class="icofont icofont-man-in-glasses"></i>Details</a>
                                        <div class="material-border"></div>
                                    </li>
                                    <!--<li class="nav-item"><a class="nav-link" id="contact-top-tab" data-toggle="tab"
                                            href="#top-contact" role="tab" aria-selected="false"><i
                                                class="icofont icofont-contacts"></i>Video</a>
                                        <div class="material-border"></div>
                                    </li> -->
                                    <li class="nav-item"><a class="nav-link" id="review-top-tab" data-toggle="tab" href="#top-review" role="tab" aria-selected="false"><i class="icofont icofont-contacts"></i>Write Review</a>
                                        <div class="material-border"></div>
                                    </li>
                                </ul>
                                <div class="tab-content nav-material" id="top-tabContent">
                                    <div class="tab-pane fade show active" id="top-home" role="tabpanel" aria-labelledby="top-home-tab">
                                        <p>{{ (!empty($product->translation) && isset($product->translation[0])) ? $product->translation[0]->body_html : ''}}</p>
                                    </div>
                                    <div class="tab-pane fade" id="top-profile" role="tabpanel" aria-labelledby="profile-top-tab">
                                        <p>{{ (!empty($product->translation) && isset($product->translation[0])) ? $product->translation[0]->body_html : ''}}</p>
                                    </div>
                                    <!-- <div class="tab-pane fade" id="top-contact" role="tabpanel"
                                        aria-labelledby="contact-top-tab">
                                        <div class="mt-3 text-center">
                                            <iframe width="560" height="315"
                                                src="https://www.youtube.com/embed/BUWzX78Ye_8"
                                                allow="autoplay; encrypted-media" allowfullscreen></iframe>
                                        </div>
                                    </div> -->
                                    <div class="tab-pane fade" id="top-review" role="tabpanel" aria-labelledby="review-top-tab">
                                        <form class="theme-form">
                                            <div class="form-row">
                                                <div class="col-md-12">
                                                    <div class="media">
                                                        <label>Rating</label>
                                                        <div class="media-body ml-3">
                                                            <div class="rating three-star">
                                                                @for($i = 1; $i < 6; $i++) <i class="fa fa-star"></i>
                                                                    @endfor
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="name">Name</label>
                                                    <input type="text" class="form-control" id="name" placeholder="Enter Your name" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="email">Email</label>
                                                    <input type="text" class="form-control" id="email" placeholder="Email" required>
                                                </div>
                                                <div class="col-md-12">
                                                    <label for="review">Review Title</label>
                                                    <input type="text" class="form-control" id="review" placeholder="Enter your Review Subjects" required>
                                                </div>
                                                <div class="col-md-12">
                                                    <label for="review">Review Title</label>
                                                    <textarea class="form-control" placeholder="Wrire Your Testimonial Here" id="exampleFormControlTextarea1" rows="6"></textarea>
                                                </div>
                                                <div class="col-md-12">
                                                    <button class="btn btn-solid" type="submit">Submit YOur
                                                        Review</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- product section start -->
<section class="section-b-space ratio_asos">
    <div class="container">
        <div class="row">
            <div class="col-12 product-related">
                <h2>Related products</h2>
            </div>
        </div>
        <div class="row search-product">
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="product-box">
                    <div class="img-wrapper">
                        <div class="front">
                            <a href="#"><img src="{{asset('front-assets/images/pro3/33.jpg')}}" class="img-fluid blur-up lazyload bg-img" alt=""></a>
                        </div>
                        <div class="back">
                            <a href="#"><img src="{{asset('front-assets/images/pro3/34.jpg')}}" class="img-fluid blur-up lazyload bg-img" alt=""></a>
                        </div>
                        <div class="cart-info cart-wrap">
                            <button data-toggle="modal" data-target="#addtocart" title="Add to cart"><i class="ti-shopping-cart"></i></button> <a href="javascript:void(0)" title="Add to Wishlist"><i class="ti-heart" aria-hidden="true"></i></a> <a href="#" data-toggle="modal" data-target="#quick-view" title="Quick View"><i class="ti-search" aria-hidden="true"></i></a> <a href="compare.html" title="Compare"><i class="ti-reload" aria-hidden="true"></i></a>
                        </div>
                    </div>
                    <div class="product-detail">
                        <div class="rating"><i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i></div>
                        <a href="product-page(no-sidebar).html">
                            <h6>Slim Fit Cotton Shirt</h6>
                        </a>
                        <h4>$500.00</h4>
                        <ul class="color-variant">
                            <li class="bg-light0"></li>
                            <li class="bg-light1"></li>
                            <li class="bg-light2"></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="product-box">
                    <div class="img-wrapper">
                        <div class="front">
                            <a href="#"><img src="{{asset('front-assets/images/pro3/1.jpg')}}" class="img-fluid blur-up lazyload bg-img" alt=""></a>
                        </div>
                        <div class="back">
                            <a href="#"><img src="{{asset('front-assets/images/pro3/2.jpg')}}" class="img-fluid blur-up lazyload bg-img" alt=""></a>
                        </div>
                        <div class="cart-info cart-wrap">
                            <button data-toggle="modal" data-target="#addtocart" title="Add to cart"><i class="ti-shopping-cart"></i></button> <a href="javascript:void(0)" title="Add to Wishlist"><i class="ti-heart" aria-hidden="true"></i></a> <a href="#" data-toggle="modal" data-target="#quick-view" title="Quick View"><i class="ti-search" aria-hidden="true"></i></a> <a href="compare.html" title="Compare"><i class="ti-reload" aria-hidden="true"></i></a>
                        </div>
                    </div>
                    <div class="product-detail">
                        <div class="rating"><i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i></div>
                        <a href="product-page(no-sidebar).html">
                            <h6>Slim Fit Cotton Shirt</h6>
                        </a>
                        <h4>$500.00</h4>
                        <ul class="color-variant">
                            <li class="bg-light0"></li>
                            <li class="bg-light1"></li>
                            <li class="bg-light2"></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="product-box">
                    <div class="img-wrapper">
                        <div class="front">
                            <a href="#"><img src="{{asset('front-assets/images/pro3/27.jpg')}}" class="img-fluid blur-up lazyload bg-img" alt=""></a>
                        </div>
                        <div class="back">
                            <a href="#"><img src="{{asset('front-assets/images/pro3/28.jpg')}}" class="img-fluid blur-up lazyload bg-img" alt=""></a>
                        </div>
                        <div class="cart-info cart-wrap">
                            <button data-toggle="modal" data-target="#addtocart" title="Add to cart"><i class="ti-shopping-cart"></i></button> <a href="javascript:void(0)" title="Add to Wishlist"><i class="ti-heart" aria-hidden="true"></i></a> <a href="#" data-toggle="modal" data-target="#quick-view" title="Quick View"><i class="ti-search" aria-hidden="true"></i></a> <a href="compare.html" title="Compare"><i class="ti-reload" aria-hidden="true"></i></a>
                        </div>
                    </div>
                    <div class="product-detail">
                        <div class="rating"><i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i></div>
                        <a href="product-page(no-sidebar).html">
                            <h6>Slim Fit Cotton Shirt</h6>
                        </a>
                        <h4>$500.00</h4>
                        <ul class="color-variant">
                            <li class="bg-light0"></li>
                            <li class="bg-light1"></li>
                            <li class="bg-light2"></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="product-box">
                    <div class="img-wrapper">
                        <div class="front">
                            <a href="#"><img src="{{asset('front-assets/images/pro3/35.jpg')}}" class="img-fluid blur-up lazyload bg-img" alt=""></a>
                        </div>
                        <div class="back">
                            <a href="#"><img src="{{asset('front-assets/images/pro3/36.jpg')}}" class="img-fluid blur-up lazyload bg-img" alt=""></a>
                        </div>
                        <div class="cart-info cart-wrap">
                            <button data-toggle="modal" data-target="#addtocart" title="Add to cart"><i class="ti-shopping-cart"></i></button> <a href="javascript:void(0)" title="Add to Wishlist"><i class="ti-heart" aria-hidden="true"></i></a> <a href="#" data-toggle="modal" data-target="#quick-view" title="Quick View"><i class="ti-search" aria-hidden="true"></i></a> <a href="compare.html" title="Compare"><i class="ti-reload" aria-hidden="true"></i></a>
                        </div>
                    </div>
                    <div class="product-detail">
                        <div class="rating"><i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i></div>
                        <a href="product-page(no-sidebar).html">
                            <h6>Slim Fit Cotton Shirt</h6>
                        </a>
                        <h4>$500.00</h4>
                        <ul class="color-variant">
                            <li class="bg-light0"></li>
                            <li class="bg-light1"></li>
                            <li class="bg-light2"></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="product-box">
                    <div class="img-wrapper">
                        <div class="front">
                            <a href="#"><img src="{{asset('front-assets/images/pro3/2.jpg')}}" class="img-fluid blur-up lazyload bg-img" alt=""></a>
                        </div>
                        <div class="back">
                            <a href="#"><img src="{{asset('front-assets/images/pro3/1.jpg')}}" class="img-fluid blur-up lazyload bg-img" alt=""></a>
                        </div>
                        <div class="cart-info cart-wrap">
                            <button data-toggle="modal" data-target="#addtocart" title="Add to cart"><i class="ti-shopping-cart"></i></button> <a href="javascript:void(0)" title="Add to Wishlist"><i class="ti-heart" aria-hidden="true"></i></a> <a href="#" data-toggle="modal" data-target="#quick-view" title="Quick View"><i class="ti-search" aria-hidden="true"></i></a> <a href="compare.html" title="Compare"><i class="ti-reload" aria-hidden="true"></i></a>
                        </div>
                    </div>
                    <div class="product-detail">
                        <div class="rating"><i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i></div>
                        <a href="product-page(no-sidebar).html">
                            <h6>Slim Fit Cotton Shirt</h6>
                        </a>
                        <h4>$500.00</h4>
                        <ul class="color-variant">
                            <li class="bg-light0"></li>
                            <li class="bg-light1"></li>
                            <li class="bg-light2"></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="product-box">
                    <div class="img-wrapper">
                        <div class="front">
                            <a href="#"><img src="{{asset('front-assets/images/pro3/28.jpg')}}" class="img-fluid blur-up lazyload bg-img" alt=""></a>
                        </div>
                        <div class="back">
                            <a href="#"><img src="{{asset('front-assets/images/pro3/27.jpg')}}" class="img-fluid blur-up lazyload bg-img" alt=""></a>
                        </div>
                        <div class="cart-info cart-wrap">
                            <button data-toggle="modal" data-target="#addtocart" title="Add to cart"><i class="ti-shopping-cart"></i></button> <a href="javascript:void(0)" title="Add to Wishlist"><i class="ti-heart" aria-hidden="true"></i></a> <a href="#" data-toggle="modal" data-target="#quick-view" title="Quick View"><i class="ti-search" aria-hidden="true"></i></a> <a href="compare.html" title="Compare"><i class="ti-reload" aria-hidden="true"></i></a>
                        </div>
                    </div>
                    <div class="product-detail">
                        <div class="rating"><i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i></div>
                        <a href="product-page(no-sidebar).html">
                            <h6>Slim Fit Cotton Shirt</h6>
                        </a>
                        <h4>$500.00</h4>
                        <ul class="color-variant">
                            <li class="bg-light0"></li>
                            <li class="bg-light1"></li>
                            <li class="bg-light2"></li>
                        </ul>
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
    var vendor_id = "{{ $product->vendor_id }}";
    var product_id = "{{ $product->id }}";
    var add_to_cart_url = "{{ route('addToCart') }}";
    $('.changeVariant').click(function() {
        var variants = [];
        var options = [];
        $('.changeVariant').each(function() {
            var that = this;
            if (this.checked == true) {
                variants.push($(that).attr('vid'));
                options.push($(that).attr('optid'));
            }
        });
        ajaxCall = $.ajax({
            type: "post",
            dataType: "json",
            url: "{{ route('productVariant', $product->sku) }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "variants": variants,
                "options": options,
            },
            beforeSend: function() {
                if (ajaxCall != 'ToCancelPrevReq' && ajaxCall.readyState < 4) {
                    ajaxCall.abort();
                }
            },
            success: function(response) {
                var res = response.result;
                console.log(res.id);
                $('#prod_variant_id').val(res.id);
                $('#productPriceValue').html(res.productPrice);
                $('#instock').html("In Stock (" + res.quantity + ")");
            },
            error: function(data) {

            },
        });
    });
</script>
<script>
var addonids = [];
var addonoptids = [];
    $(function() {0
        $(".chkPassport").click(function() {
            var addonId = $(this).attr("addonId");
            var addonOptId = $(this).attr("addonOptId");
            if ($(this).is(":checked")) {
                addonids.push(addonId); 
                addonoptids.push(addonOptId);
            } else {
                addonids.splice(addonids.indexOf(addonId), 1);
                addonoptids.splice(addonoptids.indexOf(addonOptId), 1);
            }
        });
    });
</script>
@endsection