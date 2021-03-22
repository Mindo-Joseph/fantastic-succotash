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
    .productVariants .firstChild{
        min-width: 150px;
        text-align: left !important;
        border-radius: 0% !important;
        margin-right: 10px;
        cursor: default;
        border: none !important;
    }
    .product-right .color-variant li, .productVariants .otherChild{
        height: 35px;
        width: 35px;
        border-radius: 50%;
        margin-right: 10px;
        cursor: pointer;
        border: 1px solid #f7f7f7;
        text-align: center;
    }
    .productVariants .otherSize{
        height: auto !important;
        width: auto !important;
        border: none !important;
        border-radius: 0%;
    }
    .product-right .size-box ul li.active {
        background-color: inherit;
        }
</style>
<div class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <div class="page-title">
                    <h2>product</h2>
                </div>
            </div>
            <div class="col-sm-6">
                <nav aria-label="breadcrumb" class="theme-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">product</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<section>
    <div class="collection-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-5">
                    <div class="product-slick">
                        @if(!empty($product->media))
                            @foreach($product->media as $k => $img)
                                <div><img class="img-fluid blur-up lazyload image_zoom_cls-{{$k}}" alt="" src="{{$img->image->path['image_fit'].'600/800'.$img->image->path['image_path']}}"></div>
                            @endforeach
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-12 p-0">
                            <div class="slider-nav">
                                @if(!empty($product->media))
                                    @foreach($product->media as $img)
                                        <div><img alt="" src="{{$img->image->path['image_fit']. '300/300' .$img->image->path['image_path']}}" class="img-fluid blur-up lazyload"></div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7 rtl-text">
                    <div class="product-right">
                        <h2>{{$product->translation[0]->title}}</h2>
                        <!--<h4><del>$459.00</del><span>55% off</span></h4> -->
                        <h3>${{$product->variant[0]->price}}</h3>
                        <!--<ul class="color-variant">
                            <li class="bg-light0"></li>
                            <li class="bg-light1"></li>
                            <li class="bg-light2"></li>
                        </ul>-->
                        
                         @if(!empty($product->variantSet))

                            @foreach($product->variantSet as $key => $variant)
                             @if($variant->type == 1)
                                <div class="size-box">
                                    <ul class="productVariants">
                                        <li class="firstChild">{{$variant->title}}</li>
                                        @foreach($variant->options as $k => $optn)
                                            <li class="otherSize" >

                                                <?php $checked = ($k == 0) ? 'checked' : '';
                                                    $value = $optn->variant_id.'-'.$optn->id;
                                                    $name = 'variant_'.$key;
                                                ?>

                                                <div class="radio radio-info form-check-inline">
                                                    <input id="inlineRadio-{{$value}}" value="{{$value}}" name="{{$name}}" varId="{{$optn->variant_id}}" varOptId="{{$optn->id}}" type="radio" {{$checked}} class="dataVar{{$name}}">
                                                    <label for="inlineRadio-{{$value}}">{{$optn->title}}</label>
                                                </div>
                                            <!--<a href="#">{{$optn->title}}</a> -->
                                         </li>
                                        @endforeach
                                    </ul>
                                </div>

                             @else
                                <ul class="color-variant productVariants">
                                    <li class="firstChild">{{$variant->title}} </li>
                                    @foreach($variant->options as $k => $option)
                                        <li  class="otherChild bg-light1 {{($k == 0) ? 'active' : ''}}" style="background-color:{{$option->hexacode}} !important;"></li>
                                    @endforeach
                                </ul>
                             @endif
                            @endforeach
                        @endif 

                        <div class="product-description border-product">
                            <h6 class="product-title size-text">select size <!--<span><a href="" data-toggle="modal"
                                        data-target="#sizemodal">size chart</a></span> --></h6>
                            <!--<div class="modal fade" id="sizemodal" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Sheer Straight Kurta</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        </div>
                                        <div class="modal-body"><img src="{{asset('front-assets/images/size-chart.jpg')}}" alt=""
                                                class="img-fluid blur-up lazyload"></div>
                                    </div>
                                </div>
                            </div> -->
                            <!--<div class="size-box">
                                <ul>
                                    <li class="active"><a href="#">s</a></li>
                                    <li><a href="#">m</a></li>
                                    <li><a href="#">l</a></li>
                                    <li><a href="#">xl</a></li>
                                </ul>
                            </div> -->
                            <h6 class="product-title">quantity</h6>
                            <div class="qty-box">
                                <div class="input-group"><span class="input-group-prepend"><button type="button"
                                            class="btn quantity-left-minus" data-type="minus" data-field=""><i
                                                class="ti-angle-left"></i></button> </span>
                                    <input type="text" name="quantity" class="form-control input-number" value="1">
                                    <span class="input-group-prepend"><button type="button"
                                            class="btn quantity-right-plus" data-type="plus" data-field=""><i
                                                class="ti-angle-right"></i></button></span></div>
                            </div>
                        </div>

                        @if(!empty($product->addOn))
                        <div class="border-product">
                            <h6 class="product-title">Addon List</h6>
                            
                            <table class="table table-centered table-nowrap table-striped" id="banner-datatable">
                                <tbody>
                                    @foreach($product->addOn as $row => $addon)
                                    <tr>
                                        <td><h4 addon_id="{{$addon->addon_id}}" class="header-title productAddon">{{$addon->title}}</h4></td>
                                        <td>
                                             @foreach($addon->setoptions as $k => $option)
                                                <div class="checkbox checkbox-success form-check-inline">
                                                    <input type="checkbox" id="inlineCheckbox{{$k}}" name="addonData[$row][]" addonId="{{$addon->addon_id}}" addonOptId="{{$option->id}}">
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


                        <div class="product-buttons"><a href="#" data-toggle="modal" data-target="#addtocart"
                                class="btn btn-solid">add to cart</a> <a href="#" class="btn btn-solid">buy now</a>
                        </div>
                        <div class="border-product">
                            <h6 class="product-title">product details</h6>
                            <p>{{$product->translation[0]->body_html}}</p>
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
                                            class="padding-l">:</span> <span class="timer-cal">Hrs</span>
                                    </span><span>13 <span class="padding-l">:</span> <span
                                            class="timer-cal">Min</span> </span><span>57 <span
                                            class="timer-cal">Sec</span></span>
                                </p>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- product-tab starts -->
<section class="tab-product m-0">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-lg-12">
                <ul class="nav nav-tabs nav-material" id="top-tab" role="tablist">
                    <li class="nav-item"><a class="nav-link active" id="top-home-tab" data-toggle="tab"
                            href="#top-home" role="tab" aria-selected="true">Details</a>
                        <div class="material-border"></div>
                    </li>
                    <!--<li class="nav-item"><a class="nav-link" id="profile-top-tab" data-toggle="tab"
                            href="#top-profile" role="tab" aria-selected="false">Details</a>
                        <div class="material-border"></div>
                    </li> -->
                    <li class="nav-item"><a class="nav-link" id="contact-top-tab" data-toggle="tab"
                            href="#top-contact" role="tab" aria-selected="false">Video</a>
                        <div class="material-border"></div>
                    </li>
                    <li class="nav-item"><a class="nav-link" id="review-top-tab" data-toggle="tab"
                            href="#top-review" role="tab" aria-selected="false">Write Review</a>
                        <div class="material-border"></div>
                    </li>
                </ul>
                <div class="tab-content nav-material" id="top-tabContent">
                    <div class="tab-pane fade show active" id="top-home" role="tabpanel"
                        aria-labelledby="top-home-tab">
                          <p>{{$product->translation[0]->body_html}}</p> 
                    </div> 
                    <!--<div class="tab-pane fade" id="top-profile" role="tabpanel" aria-labelledby="profile-top-tab">
                        
                         @if(!empty($product->addOn))
                            <table class="table table-centered table-nowrap table-striped" id="banner-datatable">
                                <tbody>
                                    @foreach($product->addOn as $row => $addon)
                                    <tr>
                                        <td><h4 addon_id="{{$addon->addon_id}}" class="header-title productAddon">{{$addon->title}}</h4></td>
                                        <td>
                                             @foreach($addon->setoptions as $k => $option)
                                                <div class="checkbox checkbox-success form-check-inline">
                                                    <input type="checkbox" id="inlineCheckbox{{$k}}" name="addonData[$row][]" addonId="{{$addon->addon_id}}" addonOptId="{{$option->id}}">
                                                    <label for="inlineCheckbox{{$k}}"> {{$option->title .' ($'.$option->price.')' }}</label>
                                                </div>
                                             @endforeach
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @endif
                    </div> -->
                    <div class="tab-pane fade" id="top-contact" role="tabpanel" aria-labelledby="contact-top-tab">
                        <div class="mt-4 text-center">
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/BUWzX78Ye_8"
                                allow="autoplay; encrypted-media" allowfullscreen></iframe>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="top-review" role="tabpanel" aria-labelledby="review-top-tab">
                        <form class="theme-form">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="media">
                                        <label>Rating</label>
                                        <div class="media-body ml-3">
                                            <div class="rating three-star"><i class="fa fa-star"></i> <i
                                                    class="fa fa-star"></i> <i class="fa fa-star"></i> <i
                                                    class="fa fa-star"></i> <i class="fa fa-star"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" placeholder="Enter Your name"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label for="email">Email</label>
                                    <input type="text" class="form-control" id="email" placeholder="Email" required>
                                </div>
                                <div class="col-md-12">
                                    <label for="review">Review Title</label>
                                    <input type="text" class="form-control" id="review"
                                        placeholder="Enter your Review Subjects" required>
                                </div>
                                <div class="col-md-12">
                                    <label for="review">Review Title</label>
                                    <textarea class="form-control" placeholder="Wrire Your Testimonial Here"
                                        id="exampleFormControlTextarea1" rows="6"></textarea>
                                </div>
                                <div class="col-md-12">
                                    <button class="btn btn-solid" type="submit">Submit YOur Review</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- product-tab ends -->


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
                            <a href="#"><img src="{{asset('front-assets/images/pro3/33.jpg')}}"
                                    class="img-fluid blur-up lazyload bg-img" alt=""></a>
                        </div>
                        <div class="back">
                            <a href="#"><img src="{{asset('front-assets/images/pro3/34.jpg')}}"
                                    class="img-fluid blur-up lazyload bg-img" alt=""></a>
                        </div>
                        <div class="cart-info cart-wrap">
                            <button data-toggle="modal" data-target="#addtocart" title="Add to cart"><i
                                    class="ti-shopping-cart"></i></button> <a href="javascript:void(0)"
                                title="Add to Wishlist"><i class="ti-heart" aria-hidden="true"></i></a> <a href="#"
                                data-toggle="modal" data-target="#quick-view" title="Quick View"><i
                                    class="ti-search" aria-hidden="true"></i></a> <a href="compare.html"
                                title="Compare"><i class="ti-reload" aria-hidden="true"></i></a></div>
                    </div>
                    <div class="product-detail">
                        <div class="rating"><i class="fa fa-star"></i> <i class="fa fa-star"></i> <i
                                class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i></div>
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
                            <a href="#"><img src="{{asset('front-assets/images/pro3/1.jpg')}}"
                                    class="img-fluid blur-up lazyload bg-img" alt=""></a>
                        </div>
                        <div class="back">
                            <a href="#"><img src="{{asset('front-assets/images/pro3/2.jpg')}}"
                                    class="img-fluid blur-up lazyload bg-img" alt=""></a>
                        </div>
                        <div class="cart-info cart-wrap">
                            <button data-toggle="modal" data-target="#addtocart" title="Add to cart"><i
                                    class="ti-shopping-cart"></i></button> <a href="javascript:void(0)"
                                title="Add to Wishlist"><i class="ti-heart" aria-hidden="true"></i></a> <a href="#"
                                data-toggle="modal" data-target="#quick-view" title="Quick View"><i
                                    class="ti-search" aria-hidden="true"></i></a> <a href="compare.html"
                                title="Compare"><i class="ti-reload" aria-hidden="true"></i></a></div>
                    </div>
                    <div class="product-detail">
                        <div class="rating"><i class="fa fa-star"></i> <i class="fa fa-star"></i> <i
                                class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i></div>
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
                            <a href="#"><img src="{{asset('front-assets/images/pro3/27.jpg')}}"
                                    class="img-fluid blur-up lazyload bg-img" alt=""></a>
                        </div>
                        <div class="back">
                            <a href="#"><img src="{{asset('front-assets/images/pro3/28.jpg')}}"
                                    class="img-fluid blur-up lazyload bg-img" alt=""></a>
                        </div>
                        <div class="cart-info cart-wrap">
                            <button data-toggle="modal" data-target="#addtocart" title="Add to cart"><i
                                    class="ti-shopping-cart"></i></button> <a href="javascript:void(0)"
                                title="Add to Wishlist"><i class="ti-heart" aria-hidden="true"></i></a> <a href="#"
                                data-toggle="modal" data-target="#quick-view" title="Quick View"><i
                                    class="ti-search" aria-hidden="true"></i></a> <a href="compare.html"
                                title="Compare"><i class="ti-reload" aria-hidden="true"></i></a></div>
                    </div>
                    <div class="product-detail">
                        <div class="rating"><i class="fa fa-star"></i> <i class="fa fa-star"></i> <i
                                class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i></div>
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
                            <a href="#"><img src="{{asset('front-assets/images/pro3/35.jpg')}}"
                                    class="img-fluid blur-up lazyload bg-img" alt=""></a>
                        </div>
                        <div class="back">
                            <a href="#"><img src="{{asset('front-assets/images/pro3/36.jpg')}}"
                                    class="img-fluid blur-up lazyload bg-img" alt=""></a>
                        </div>
                        <div class="cart-info cart-wrap">
                            <button data-toggle="modal" data-target="#addtocart" title="Add to cart"><i
                                    class="ti-shopping-cart"></i></button> <a href="javascript:void(0)"
                                title="Add to Wishlist"><i class="ti-heart" aria-hidden="true"></i></a> <a href="#"
                                data-toggle="modal" data-target="#quick-view" title="Quick View"><i
                                    class="ti-search" aria-hidden="true"></i></a> <a href="compare.html"
                                title="Compare"><i class="ti-reload" aria-hidden="true"></i></a></div>
                    </div>
                    <div class="product-detail">
                        <div class="rating"><i class="fa fa-star"></i> <i class="fa fa-star"></i> <i
                                class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i></div>
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
                            <a href="#"><img src="{{asset('front-assets/images/pro3/2.jpg')}}"
                                    class="img-fluid blur-up lazyload bg-img" alt=""></a>
                        </div>
                        <div class="back">
                            <a href="#"><img src="{{asset('front-assets/images/pro3/1.jpg')}}"
                                    class="img-fluid blur-up lazyload bg-img" alt=""></a>
                        </div>
                        <div class="cart-info cart-wrap">
                            <button data-toggle="modal" data-target="#addtocart" title="Add to cart"><i
                                    class="ti-shopping-cart"></i></button> <a href="javascript:void(0)"
                                title="Add to Wishlist"><i class="ti-heart" aria-hidden="true"></i></a> <a href="#"
                                data-toggle="modal" data-target="#quick-view" title="Quick View"><i
                                    class="ti-search" aria-hidden="true"></i></a> <a href="compare.html"
                                title="Compare"><i class="ti-reload" aria-hidden="true"></i></a></div>
                    </div>
                    <div class="product-detail">
                        <div class="rating"><i class="fa fa-star"></i> <i class="fa fa-star"></i> <i
                                class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i></div>
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
                            <a href="#"><img src="{{asset('front-assets/images/pro3/28.jpg')}}"
                                    class="img-fluid blur-up lazyload bg-img" alt=""></a>
                        </div>
                        <div class="back">
                            <a href="#"><img src="{{asset('front-assets/images/pro3/27.jpg')}}"
                                    class="img-fluid blur-up lazyload bg-img" alt=""></a>
                        </div>
                        <div class="cart-info cart-wrap">
                            <button data-toggle="modal" data-target="#addtocart" title="Add to cart"><i
                                    class="ti-shopping-cart"></i></button> <a href="javascript:void(0)"
                                title="Add to Wishlist"><i class="ti-heart" aria-hidden="true"></i></a> <a href="#"
                                data-toggle="modal" data-target="#quick-view" title="Quick View"><i
                                    class="ti-search" aria-hidden="true"></i></a> <a href="compare.html"
                                title="Compare"><i class="ti-reload" aria-hidden="true"></i></a></div>
                    </div>
                    <div class="product-detail">
                        <div class="rating"><i class="fa fa-star"></i> <i class="fa fa-star"></i> <i
                                class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i></div>
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

@endsection
