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
<div class="added-notification">
    <img src="../assets/images/fashion/pro/1.jpg" class="img-fluid" alt="">
    <h3>added to cart</h3>
</div>
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
                <div class="col-sm-12">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="filter-main-btn mb-2"><span class="filter-btn"><i class="fa fa-filter"
                                            aria-hidden="true"></i> filter</span></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="product-slick">
                                    @if(!empty($product->media))
                                    @foreach($product->media as $k => $img)
                                    <div class="image_mask"><img
                                            class="img-fluid blur-up lazyload image_zoom_cls-{{$k}}" alt=""
                                            src="{{$img->image->path['proxy_url'].'600/800'.$img->image->path['image_path']}}">
                                    </div>
                                    @endforeach
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="col-12 p-0">
                                        <div class="slider-nav">
                                            @if(!empty($product->media))
                                            @foreach($product->media as $k => $img)
                                            <div><img class="img-fluid blur-up lazyload" alt=""
                                                    src="{{$img->image->path['proxy_url'].'300/300'.$img->image->path['image_path']}}">
                                            </div>
                                            @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 rtl-text">
                                <div class="product-right">
                                    <h2 class="mb-0">
                                        {{ (!empty($product->translation) && isset($product->translation[0])) ? $product->translation[0]->title : ''}}
                                    </h2>
                                    <div class="rating">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <a href="#"></a>
                                    </div>
                                    <div class="description_txt my-3">
                                        <p>It is a long established fact that a reader will be distracted by the
                                            readable content of a page when looking at its layout.</p>
                                    </div>

                                    <input type="hidden" name="variant_id" id="prod_variant_id"
                                        value="{{$product->variant[0]->id}}">
                                    <!--<h4><del>$459.00</del><span>55% off</span></h4> -->
                                    <h3 id="productPriceValue" class="mb-md-3">
                                        @if($product->variant[0]->compare_at_price > 0 )
                                        <span
                                            class="org_price">{{Session::get('currencySymbol').($product->variant[0]->compare_at_price * $product->variant[0]->multiplier)}}</span>
                                        @endif
                                        <b>{{Session::get('currencySymbol').($product->variant[0]->price * $product->variant[0]->multiplier)}}</b>
                                    </h3>

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
                                                <label class="radio d-inline-block txt-14 mr-2">{{$optn->title}}
                                                    <input id="lineRadio-{{$opt_id}}" name="{{'var_'.$var_id}}"
                                                        vid="{{$var_id}}" optid="{{$opt_id}}" value="{{$opt_id}}"
                                                        type="radio" {{$checked}}
                                                        class="changeVariant dataVar{{$var_id}}">
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
                                        <h6 class="product-title mt-0">quantity: <span id="instock"
                                                style="color: green;">In Stock
                                                ({{$product->variant[0]->quantity}})</span></h6>
                                        <div class="qty-box">
                                            <div class="input-group">
                                                <span class="input-group-prepend"><button type="button"
                                                        class="btn quantity-left-minus" data-type="minus"
                                                        data-field=""><i class="ti-angle-left"></i></button> </span>
                                                <input type="text" name="quantity" id="quantity"
                                                    class="form-control input-number quantity_count" value="1">
                                                <span class="input-group-prepend quant-plus"><button type="button"
                                                        class="btn quantity-right-plus " data-type="plus"
                                                        data-field=""><i class="ti-angle-right"></i></button></span>
                                            </div>
                                        </div>
                                    </div>

                                    @if(!empty($product->addOn))
                                    <div class="border-product">
                                        <h6 class="product-title">Addon List</h6>

                                        <table class="table table-centered table-nowrap table-striped"
                                            id="banner-datatable">
                                            <tbody>
                                                @foreach($product->addOn as $row => $addon)
                                                <tr>
                                                    <td>
                                                        <h4 addon_id="{{$addon->addon_id}}"
                                                            class="header-title productAddon">{{$addon->title}}</h4>
                                                    </td>
                                                    <td>
                                                        @foreach($addon->setoptions as $k => $option)
                                                        <div class="checkbox checkbox-success form-check-inline">
                                                            <input type="checkbox" id="inlineCheckbox{{$k}}"
                                                                class="chkPassport" name="addonData[$row][]"
                                                                addonId="{{$addon->addon_id}}"
                                                                addonOptId="{{$option->id}}">
                                                            <label class="pl-2" for="inlineCheckbox{{$k}}">
                                                                {{$option->title .' ($'.$option->price.')' }}</label>
                                                        </div>
                                                        @endforeach
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @endif

                                    <div class="product-buttons"><a href="#" data-toggle="modal"
                                            data-target="#addtocart" class="btn btn-solid addToCart">add to cart</a> <a
                                            href="#" class="btn btn-solid">buy now</a></div>
                                    <div class="border-product">
                                        <h6 class="product-title">product details</h6>
                                        <p>{!!(!empty($product->translation) && isset($product->translation[0])) ?
                                            $product->translation[0]->body_html : ''!!}</p>
                                    </div>

                                    <div class="border-product">
                                        <h6 class="product-title">share it</h6>
                                        <div class="product-icon w-100">
                                            <ul class="product-social">
                                                <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                                <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                                                <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                                <li><a href="#"><i class="fa fa-instagram"></i></a></li>
                                            </ul>
                                        </div>
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
                                    <li class="nav-item"><a class="nav-link active" id="top-home-tab" data-toggle="tab"
                                            href="#top-home" role="tab" aria-selected="true"><i
                                                class="icofont icofont-ui-home"></i>Description</a>
                                        <div class="material-border"></div>
                                    </li>
                                    <li class="nav-item"><a class="nav-link" id="profile-top-tab" data-toggle="tab"
                                            href="#top-profile" role="tab" aria-selected="false"><i
                                                class="icofont icofont-man-in-glasses"></i>Details</a>
                                        <div class="material-border"></div>
                                    </li>
                                    <!--<li class="nav-item"><a class="nav-link" id="contact-top-tab" data-toggle="tab"
                                            href="#top-contact" role="tab" aria-selected="false"><i
                                                class="icofont icofont-contacts"></i>Video</a>
                                        <div class="material-border"></div>
                                    </li> -->
                                    @if($order_deliver == 1)
                                    <li class="nav-item"><a class="nav-link" id="review-top-tab" data-toggle="tab"
                                            href="#top-review" role="tab" aria-selected="false"><i
                                                class="icofont icofont-contacts"></i>Write Review</a>
                                        <div class="material-border"></div>
                                    </li>
                                    @endif
                                </ul>
                                <div class="tab-content nav-material" id="top-tabContent">
                                    <div class="tab-pane fade show active" id="top-home" role="tabpanel"
                                        aria-labelledby="top-home-tab">
                                        <p>{!! (!empty($product->translation) && isset($product->translation[0])) ?
                                            $product->translation[0]->body_html : ''!!}</p>
                                    </div>
                                    <div class="tab-pane fade" id="top-profile" role="tabpanel"
                                        aria-labelledby="profile-top-tab">
                                        <p>{!! (!empty($product->translation) && isset($product->translation[0])) ?
                                            $product->translation[0]->body_html : ''!!}</p>
                                    </div>
                                    <!-- <div class="tab-pane fade" id="top-contact" role="tabpanel"
                                        aria-labelledby="contact-top-tab">
                                        <div class="mt-3 text-center">
                                            <iframe width="560" height="315"
                                                src="https://www.youtube.com/embed/BUWzX78Ye_8"
                                                allow="autoplay; encrypted-media" allowfullscreen></iframe>
                                        </div>
                                    </div> -->
                                    <div class="tab-pane fade" id="top-review" role="tabpanel"
                                        aria-labelledby="review-top-tab">
                                       
                                <form id="file-upload-form" class="theme-form" action="{{ route('update.order.rating')}}" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                                                @csrf
                                            <div class="rating-form">
                                                <fieldset class="form-group">
                                                    <legend class="form-legend">Rating:</legend>
                                                    <div class="form-item">
                                                        <input id="rating-5" name="rating" type="radio" value="5" />
                                                        <label for="rating-5" data-value="5">
                                                            <span class="rating-star">
                                                                <i class="fa fa-star-o"></i>
                                                                <i class="fa fa-star"></i>
                                                            </span>
                                                            <span class="ir">5</span>
                                                        </label>
                                                        <input id="rating-4" name="rating" type="radio" value="4" />
                                                        <label for="rating-4" data-value="4">
                                                            <span class="rating-star">
                                                                <i class="fa fa-star-o"></i>
                                                                <i class="fa fa-star"></i>
                                                            </span>
                                                            <span class="ir">4</span>
                                                        </label>
                                                        <input id="rating-3" name="rating" type="radio" value="3" />
                                                        <label for="rating-3" data-value="3">
                                                            <span class="rating-star">
                                                                <i class="fa fa-star-o"></i>
                                                                <i class="fa fa-star"></i>
                                                            </span>
                                                            <span class="ir">3</span>
                                                        </label>
                                                        <input id="rating-2" name="rating" type="radio" value="2" />
                                                        <label for="rating-2" data-value="2">
                                                            <span class="rating-star">
                                                                <i class="fa fa-star-o"></i>
                                                                <i class="fa fa-star"></i>
                                                            </span>
                                                            <span class="ir">2</span>
                                                        </label>
                                                        <input id="rating-1" name="rating" type="radio" value="1" />
                                                        <label for="rating-1" data-value="1">
                                                            <span class="rating-star">
                                                                <i class="fa fa-star-o"></i>
                                                                <i class="fa fa-star"></i>
                                                            </span>
                                                            <span class="ir">1</span>
                                                        </label>

                                                        <!-- <div class="form-action">
                                                            <input class="btn-reset" type="reset" value="Reset" />
                                                        </div> -->

                                                        <div class="form-output">
                                                            ? / 5
                                                        </div>

                                                    </div>
                                                </fieldset>
                                            </div>

                                            <div class="row rating_files">
                                                <div class="col-12">
                                                    <h4>Upload Images</h4>
                                                </div>
                                                <div class="col-6 col-md-3 col-lg-2">
                                                    <div class="file file--upload">
                                                        <label for="input-file">
                                                            <span class="plus_icon"><i class="fa fa-plus" aria-hidden="true"></i></span>
                                                        </label>
                                                        <input id="input-file" type="file" name="files" accept="image/*"  multiple >
                                                    </div>
                                                </div>

                                                <div class="col-10">
                                                    <span class="row" id="thumb-output">
                                                        
                                                    </span>
                                                </div>
                                                
                                            </div>

                                            <div class="form-row">
                                                <!-- <div class="col-md-12">
                                                    <div class="media">
                                                        <label>Rating</label>
                                                        <div class="media-body ml-3">
                                                            <div class="rating three-star">
                                                                @for($i = 1; $i < 6; $i++) <i class="fa fa-star"></i>
                                                                    @endfor
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> -->
                                                <!-- <div class="col-md-6">
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
                                                </div> -->
                                                <div class="col-md-12 mb-3">
                                                    <label for="review">Review Title</label>
                                                    <textarea class="form-control"
                                                        placeholder="Wrire Your Testimonial Here"
                                                        id="exampleFormControlTextarea1" rows="8"></textarea>
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

<section class="">
    <div class="container">
        <div class="row">
            <div class="col-12 ">
                <h2>Related products</h2>

                <!-- <div id="starrate" class="starrate mt-3 d-flex align-items-center" data-val="2.5" data-max="5">
                    <span class="ctrl"></span>
                    <span class="cont m-1">
                        <i class="fa fa-star-o" aria-hidden="true"></i>
                        <i class="fa fa-star-o" aria-hidden="true"></i>
                        <i class="fa fa-star-o" aria-hidden="true"></i>
                        <i class="fa fa-star-o" aria-hidden="true"></i>
                        <i class="fa fa-star-o" aria-hidden="true"></i>
                    </span>
                    <div id="test" class="col-3 mr-auto display-4">2.5</div>                    
                </div> -->

            </div>
        </div>
    </div>
</section>

<!-- product section start -->
<section class="section-b-space ratio_asos">
    <div class="container">
        <div class="row">

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
                                data-toggle="modal" data-target="#quick-view" title="Quick View"><i class="ti-search"
                                    aria-hidden="true"></i></a> <a href="compare.html" title="Compare"><i
                                    class="ti-reload" aria-hidden="true"></i></a>
                        </div>
                    </div>
                    <div class="product-detail">
                        <div class="rating">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i> <i class="fa fa-star"></i></div>

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


<script>
    var valueHover = 0;

    function calcSliderPos(e, maxV) {
        return (e.offsetX / e.target.clientWidth) * parseInt(maxV, 10);
    }

    $(".starrate").on("click", function () {
        $(this).data('val', valueHover);
        $(this).addClass('saved')
    });

    $(".starrate").on("mouseout", function () {
        upStars($(this).data('val'));
    });


    $(".starrate span.ctrl").on("mousemove", function (e) {
        var maxV = parseInt($(this).parent("div").data('max'))
        valueHover = Math.ceil(calcSliderPos(e, maxV) * 2) / 2;
        upStars(valueHover);
    });


    function upStars(val) {

        var val = parseFloat(val);
        $("#test").html(val.toFixed(1));

        var full = Number.isInteger(val);
        val = parseInt(val);
        var stars = $("#starrate i");

        stars.slice(0, val).attr("class", "fa fa-star");
        if (!full) {
            stars.slice(val, val + 1).attr("class", "fa fa-star-half-o");
            val++
        }
        stars.slice(val, 5).attr("class", "fa fa-star-o");




    }


    $(document).ready(function () {
        $(".starrate span.ctrl").width($(".starrate span.cont").width());
        $(".starrate span.ctrl").height($(".starrate span.cont").height());
    });
</script>

<script type="text/javascript">
    var ajaxCall = 'ToCancelPrevReq';
    var vendor_id = "{{ $product->vendor_id }}";
    var product_id = "{{ $product->id }}";
    var add_to_cart_url = "{{ route('addToCart') }}";
    $('.changeVariant').click(function () {
        var variants = [];
        var options = [];
        $('.changeVariant').each(function () {
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
            beforeSend: function () {
                if (ajaxCall != 'ToCancelPrevReq' && ajaxCall.readyState < 4) {
                    ajaxCall.abort();
                }
            },
            success: function (response) {
                var res = response.result;
                console.log(res.id);
                $('#prod_variant_id').val(res.id);
                $('#productPriceValue').html(res.productPrice);
                $('#instock').html("In Stock (" + res.quantity + ")");
            },
            error: function (data) {

            },
        });
    });
</script>
<script>
    var addonids = [];
    var addonoptids = [];
    $(function () {
        0
        $(".chkPassport").click(function () {
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

<!-----  rating product if delivered -->
<script>
  
 $(document).ready(function(){
  $('#input-file').on('change', function(){ //on file input change
     if (window.File && window.FileReader && window.FileList && window.Blob) //check File API supported browser
     {
          
         var data = $(this)[0].files; //this file data
          
         $.each(data, function(index, file){ //loop though each file
             if(/(\.|\/)(gif|jpe?g|png)$/i.test(file.type)){ //check supported file type
                 var fRead = new FileReader(); //new filereader
                 fRead.onload = (function(file){ //trigger function on successful read
                 return function(e) {
                     var img = $('<img/>').addClass('col-6 col-md-3 col-lg-2 update_pic').attr('src', e.target.result); //create image element 
                     $('#thumb-output').append(img); //append image to output element
                 };
                 })(file);
                 fRead.readAsDataURL(file); //URL representing the file's data.
             }
         });
          
     }else{
         alert("Your browser doesn't support File API!"); //if File API is absent
     }
  });
 });
  
 </script>

@endsection