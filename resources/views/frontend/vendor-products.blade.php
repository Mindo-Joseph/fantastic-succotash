@extends('layouts.store', ['title' => 'Vendor'])

@section('css')
<style type="text/css">
    .main-menu .brand-logo {
        display: inline-block;
        padding-top: 20px;
        padding-bottom: 20px;
    }
</style>
<link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/price-range.css')}}">
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
<!-- section start -->
<section class="section-b-space ratio_asos">
    <div class="collection-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-sm-3 collection-filter">
                    <!-- side-bar colleps block stat -->
                    <div class="collection-filter-block">
                        <!-- brand filter start -->
                        <div class="collection-mobile-back"><span class="filter-back"><i class="fa fa-angle-left" aria-hidden="true"></i> back</span></div>
                        <div class="collection-collapse-block open">
                            @if(!empty($brands) && count($brands) > 0)
                            <h3 class="collapse-block-title">brand</h3>
                            <div class="collection-collapse-block-content">
                                <div class="collection-brand-filter">
                                    @foreach($brands as $key => $val)
                                    <div class="custom-control custom-checkbox collection-filter-checkbox">
                                        <input type="checkbox" class="custom-control-input productFilter" fid="{{$val->brand_id}}" used="brands" id="brd{{$val->brand_id}}">
                                        @foreach($val->brand->translation as $k => $v)
                                        <label class="custom-control-label" for="brd{{$val->brand_id}}">{{$v->title}}</label>
                                        @endforeach
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>

                        @if(!empty($variantSets) && count($variantSets) > 0)
                        @foreach($variantSets as $key => $sets)
                        <div class="collection-collapse-block border-0 open">
                            <h3 class="collapse-block-title">{{$sets->title}}</h3>
                            <div class="collection-collapse-block-content">
                                <div class="collection-brand-filter">

                                    @if($sets->type == 2)
                                        @foreach($sets->options as $ok => $opt)
                                        <div class="chiller_cb small_label d-inline-block color-selector">
                                            <?php $checkMark = ($key == 0) ? 'checked' : ''; ?>
                                            <input class="custom-control-input productFilter" type="checkbox" {{$checkMark}} id="Opt{{$key.'-'.$opt->id}}" fid="{{$sets->variant_type_id}}" used="variants" optid="{{$opt->id}}">
                                            <label for="Opt{{$key.'-'.$opt->id}}"></label>
                                            @if(strtoupper($opt->hexacode) == '#FFF' || strtoupper($opt->hexacode) == '#FFFFFF')
                                            <span style="background: #FFFFFF; border-color:#000;" class="check_icon white_check"></span>
                                            @else
                                            <span class="check_icon" style="background:{{$opt->hexacode}}; border-color: {{$opt->hexacode}};"></span>
                                            @endif
                                        </div>
                                        @endforeach

                                    @else
                                        @foreach($sets->options as $ok => $opt)
                                        <div class="custom-control custom-checkbox collection-filter-checkbox">
                                            <input type="checkbox" class="custom-control-input productFilter" id="Opt{{$key.'-'.$opt->id}}" fid="{{$sets->variant_type_id}}" type="variants" optid="{{$opt->id}}">
                                            <label class="custom-control-label" for="Opt{{$key.'-'.$opt->id}}">{{$opt->title}}</label>
                                        </div>
                                        @endforeach
                                    @endif

                                </div>
                            </div>
                        </div>

                        @endforeach
                        @endif
                        <div class="collection-collapse-block border-0 open">
                            <h3 class="collapse-block-title">price</h3>
                            <div class="collection-collapse-block-content">
                                <div class="wrapper mt-3">
                                    <div class="range-slider">
                                        <input type="text" class="js-range-slider rangeSliderPrice" value="" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- side-bar single product slider start -->
                    <div class="theme-card">
                        <h5 class="title-border">new product</h5>
                        <div class="offer-slider slide-1">
                            @if(!empty($newProducts) && count($newProducts) > 0)
                            @foreach($newProducts as $newProds)
                            <div>
                                @foreach($newProds as $new)
                                <?php $imagePath = '';
                                foreach ($new['media'] as $k => $v) {
                                    $imagePath = $v['image']['path']['proxy_url'] . '300/300' . $v['image']['path']['image_path'];
                                } ?>
                                <div class="media">
                                    <a href="{{route('productDetail', $new['url_slug'])}} "><img class="img-fluid blur-up lazyload" style="max-width: 200px;" src="{{$imagePath}}" alt=""></a>
                                    <div class="media-body align-self-center">
                                        <div class="rating">
                                            @for($i = 1; $i < 6; $i++) <i class="fa fa-star"></i>
                                                @endfor
                                        </div>
                                        <a href="{{route('productDetail', $new['url_slug'])}}">
                                            <h6>{{(!empty($new['translation']) && isset($new['translation'][0])) ? $new['translation'][0]['title'] : $new['sku']}}</h6>
                                        </a>
                                        <h4> <?php $multiply = (empty($new['variant'][0]['multiplier'])) ? 1 : $new['variant'][0]['multiplier']; ?>
                                            {{ Session::get('currencySymbol').' '.($new['variant'][0]['price'] * $multiply)}}
                                        </h4>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endforeach
                            @endif
                        </div>
                    </div>
                    <!-- side-bar banner end here -->
                </div>
                <div class="collection-content col">
                    <div class="page-main-content">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="top-banner-wrapper text-center">

                                    @if(!empty($vendor->banner))
                                    <div class="common-banner"><img alt="" src="{{$vendor->banner['proxy_url'] . '1000/200' . $vendor->banner['image_path']}}" class="img-fluid blur-up lazyload"></div>
                                    @endif


                                    <div class="top-banner-content small-section">
                                        <h4>{{ $vendor->name }}</h4>
                                    </div>
                                </div>
                                <div class="collection-product-wrapper">
                                    <div class="product-top-filter">
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <div class="filter-main-btn"><span class="filter-btn btn btn-theme"><i class="fa fa-filter" aria-hidden="true"></i> Filter</span></div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="product-filter-content">
                                                    <!-- <div class="search-count">
                                                        <h5>Showing Products 1-24 of 10 Result</h5>
                                                    </div> -->
                                                    <div class="collection-view">
                                                        <ul>
                                                            <li><i class="fa fa-th grid-layout-view"></i></li>
                                                            <li><i class="fa fa-list-ul list-layout-view"></i></li>
                                                        </ul>
                                                    </div>
                                                    <div class="collection-grid-view">
                                                        <ul>
                                                            <li><img src="{{asset('front-assets/images/icon/2.png')}}" alt="" class="product-2-layout-view"></li>
                                                            <li><img src="{{asset('front-assets/images/icon/3.png')}}" alt="" class="product-3-layout-view"></li>
                                                            <li><img src="{{asset('front-assets/images/icon/4.png')}}" alt="" class="product-4-layout-view"></li>
                                                            <li><img src="{{asset('front-assets/images/icon/6.png')}}" alt="" class="product-6-layout-view"></li>
                                                        </ul>
                                                    </div>
                                                    <div class="product-page-per-view">
                                                        <?php $pnum = (Session::has('cus_paginate')) ? Session::get('cus_paginate') : 8; ?>
                                                        <select class="customerPaginate">

                                                            <option value="8" @if($pnum==8) selected @endif>Show 8
                                                            </option>
                                                            <option value="12" @if($pnum==12) selected @endif>Show 12 </option>
                                                            <option value="24" @if($pnum==24) selected @endif>Show 24
                                                            </option>
                                                            <option value="48" @if($pnum==48) selected @endif>Show 48
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <!-- <div class="product-page-filter">
                                                        <select>
                                                            <option value="High to low">Sorting items</option>
                                                            <option value="Low to High">50 Products</option>
                                                            <option value="Low to High">100 Products</option>
                                                        </select>
                                                    </div> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="displayProducts">
                                        <div class="product-wrapper-grid">
                                            <div class="row margin-res">

                                                @if($listData->isNotEmpty())
                                                    @foreach($listData as $key => $data)

                                                    <?php $imagePath = $imagePath2 = '';
                                                    $mediaCount = count($data->media);
                                                    for ($i = 0; $i < $mediaCount && $i < 2; $i++) {
                                                        if ($i == 0) {
                                                            $imagePath = $data->media[$i]->image->path['proxy_url'] . '300/300' . $data->media[$i]->image->path['image_path'];
                                                        }
                                                        $imagePath2 = $data->media[$i]->image->path['proxy_url'] . '300/300' . $data->media[$i]->image->path['image_path'];
                                                    } ?>
                                                    <div class="col-xl-3 col-6 col-grid-box">
                                                        <div class="product-box">
                                                            <div class="img-wrapper">
                                                                <div class="front">
                                                                    <a href="{{route('productDetail', $data->url_slug)}}"><img class="img-fluid blur-up lazyload" src="{{$imagePath}}" alt=""></a>
                                                                </div>
                                                                <div class="back">
                                                                    <a href="{{route('productDetail', $data->url_slug)}}"><img class="img-fluid blur-up lazyload" src="{{$imagePath2}}" alt=""></a>
                                                                </div>
                                                                <div class="cart-info cart-wrap">
                                                                    <button data-toggle="modal" data-target="#addtocart" title="Add to cart"><i class="ti-shopping-cart"></i></button>
                                                                    <a href="javascript:void(0)" title="Add to Wishlist" class="addWishList" proSku="{{$data->sku}}"><i class="ti-heart" aria-hidden="true"></i></a>
                                                                    <!-- <a data-toggle="modal" href="#" data-target="#quick-view" title="Quick View"><i class="ti-search" aria-hidden="true"></i></a>
                                                                    <a href="compare.html" title="Compare"><i class="ti-reload" aria-hidden="true"></i></a> -->
                                                                </div>
                                                            </div>
                                                            <div class="product-detail">
                                                                <div>
                                                                    <div class="rating">
                                                                        @for($i = 1; $i < 6; $i++) <i class="fa fa-star"></i>
                                                                            @endfor
                                                                    </div>
                                                                    <a href="{{route('productDetail', $data->url_slug)}}">
                                                                        <h6>{{(!empty($data->translation) && isset($data->translation[0])) ? $data->translation[0]->title : ''}}</h6>
                                                                    </a>
                                                                    <h4>{{Session::get('currencySymbol').($data->variant[0]->price * $data->variant[0]->multiplier)}}</h4>
                                                                    <!-- <ul class="color-variant">
                                                                    <li class="bg-light0"></li>
                                                                    <li class="bg-light1"></li>
                                                                    <li class="bg-light2"></li>
                                                                </ul> -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                @else
                                                    <div class="col-xl-12 col-12 mt-4"><h5 class="text-center">No Product Found</h5></div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="pagination pagination-rounded justify-content-end mb-0">
                                            {{ $listData->links() }}
                                        </div>
                                    </div>
                                    <!-- <div class="product-pagination">
                                        <div class="theme-paggination-block">
                                            <div class="row">
                                                <div class="col-xl-6 col-md-6 col-sm-12">
                                                    <nav aria-label="Page navigation">
                                                        <ul class="pagination">
                                                            <li class="page-item"><a class="page-link" href="#" aria-label="Previous"><span aria-hidden="true"><i class="fa fa-chevron-left" aria-hidden="true"></i></span> <span class="sr-only">Previous</span></a></li>
                                                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                                            <li class="page-item"><a class="page-link" href="#" aria-label="Next"><span aria-hidden="true"><i
                                                                            class="fa fa-chevron-right"
                                                                            aria-hidden="true"></i></span> <span
                                                                        class="sr-only">Next</span></a></li>
                                                        </ul>
                                                    </nav>
                                                </div>
                                                <div class="col-xl-6 col-md-6 col-sm-12">
                                                    <div class="product-search-count-bottom">
                                                        <h5>Showing Products 1-24 of 10 Result</h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('script')

<script src="{{asset('front-assets/js/rangeSlider.min.js')}}"></script>
<script src="{{asset('front-assets/js/my-sliders.js')}}"></script>
<script>
    $('.js-range-slider').ionRangeSlider({
        type: 'double',
        grid: false,
        min: 0,
        max: 50000,
        from: 0,
        to: 50000,
        prefix: ""
    });

    var ajaxCall = 'ToCancelPrevReq';
    $('.js-range-slider').change(function() {
        filterProducts();
    });

    $('.productFilter').click(function() {
        filterProducts();
    });

    function filterProducts() {
        var brands = [];
        var variants = [];
        var options = [];
        $('.productFilter').each(function() {
            var that = this;
            if (this.checked == true) {
                var forCheck = $(that).attr('used');
                if (forCheck == 'brands') {
                    brands.push($(that).attr('fid'));
                } else {
                    variants.push($(that).attr('fid'));
                    options.push($(that).attr('optid'));
                }
            }
        });
        var range = $('.rangeSliderPrice').val();

        ajaxCall = $.ajax({
            type: "post",
            dataType: "json",
            url: "{{ route('vendorProductFilters', $vendor->id) }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "brands": brands,
                "variants": variants,
                "options": options,
                "range": range
            },
            beforeSend: function() {
                if (ajaxCall != 'ToCancelPrevReq' && ajaxCall.readyState < 4) {
                    ajaxCall.abort();
                }
            },
            success: function(response) {
                $('.displayProducts').html(response.html);
            },
            error: function(data) {
                //location.reload();
            },
        });
    }
</script>

@endsection