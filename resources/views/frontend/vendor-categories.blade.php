@extends('layouts.store', ['title' => $vendor->name])
@section('css')
<style type="text/css">
    .main-menu .brand-logo {
        display: inline-block;
        padding-top: 20px;
        padding-bottom: 20px;
    }
    .slick-track{
        margin-left: 0px;
    }
</style>
<link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/price-range.css')}}">
@endsection
@section('content')
<header>
    <div class="mobile-fix-option"></div>
    @include('layouts.store/left-sidebar')
</header>
<section class="section-b-space ratio_asos">
    <div class="collection-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-sm-3 collection-filter">
                    <div class="theme-card">
                        <h5 class="title-border">{{__('New Product')}}</h5>
                        <div class="offer-slider slide-1">
                            @if(!empty($newProducts) && count($newProducts) > 0)
                                @foreach($newProducts as $newProds)
                                    <div>
                                    @foreach($newProds as $new)
                                        <?php $imagePath = '';
                                        foreach ($new['media'] as $k => $v) {
                                            $imagePath = $v['image']['path']['proxy_url'].'300/300'.$v['image']['path']['image_path'];
                                        } ?>
                                        <div class="media">
                                            <a href="{{route('productDetail', $new['url_slug'])}} "><img class="img-fluid blur-up lazyload" style="max-width: 200px;" src="{{$imagePath}}" alt="" ></a>
                                            <div class="media-body align-self-center">
                                                <div class="inner_spacing">
                                                    <a href="{{route('productDetail', $new['url_slug'])}}">
                                                        <h3>{{ $new['translation_title'] }}</h3>
                                                        <h6><b>{{$new['vendor']['name']}}</b></h6>
                                                        @if($new['inquiry_only'] == 0)
                                                            <h4 class="mt-1">
                                                                <?php $multiply = $new['variant_multiplier']; ?>
                                                                {{ Session::get('currencySymbol').' '.(number_format($new['variant_price'] * $multiply,2))}}
                                                            </h4>
                                                        @endif
                                                        @if($client_preference_detail)
                                                            @if($client_preference_detail->rating_check == 1)
                                                                @if($new['averageRating'] > 0)
                                                                    <span class="rating">{{ $new['averageRating'] }} <i class="fa fa-star text-white p-0"></i></span>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                <div class="collection-content col">
                    <div class="page-main-content">
                        <div class="row">
                            <div class="col-sm-12">
                            <div class="top-banner-wrapper mb-4">
                                    @if(!empty($vendor->banner))
                                        <div class="common-banner text-center"><img alt="" src="{{$vendor->banner['proxy_url'] . '1000/200' . $vendor->banner['image_path']}}" class="img-fluid blur-up lazyload"></div>
                                    @endif
                                    <div class="row mt-n4">
                                        <div class="col-12">
                                            <form action="">
                                                <div class="row">
                                                    <div class="col-sm-12 text-center">
                                                        <div class="file file--upload">
                                                            <label>
                                                                <span class="update_pic border-0">
                                                                <img src="{{$vendor->logo['proxy_url'] . '1000/200' . $vendor->logo['image_path']}}" alt="">
                                                                </span>
                                                            </label>
                                                        </div>
                                                        <div class="name_location d-block py-0">
                                                            <h4 class="mt-0 mb-1"><b>{{$vendor->name}}</b></h4>
                                                        </div>
                                                        @if($vendor->is_show_vendor_details == 1)
                                                            <div class="">
                                                                @if($vendor->email)
                                                                    <a href="{{$vendor->email}}" target="_blank" data-toggle="tooltip" data-placement="bottom" title="{{$vendor->email}}"><i class="fa fa-envelope"></i></a>
                                                                @endif
                                                                <a href="javascript:void(0)" data-toggle="tooltip" data-placement="bottom" title="{{$vendor->address}}"><i class="fa fa-address-card mx-1"></i></a>
                                                                @if($vendor->website)
                                                                    <a href="{{http_check($vendor->website) }}" target="_blank" data-toggle="tooltip" data-placement="bottom" title="{{$vendor->website}}"><i class="fa fa-home"></i></a>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                    @if($vendor->desc)
                                                        <div class="col-md-12 text-center">
                                                            <p>{{$vendor->desc}}</p>
                                                        </div>                                                  
                                                    @endif
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="collection-product-wrapper">
                                    <div class="product-top-filter">
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <div class="filter-main-btn">
                                                    <span class="filter-btn btn btn-theme">
                                                        <i class="fa fa-filter" aria-hidden="true"></i>{{__('Filter')}}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="product-filter-content">
                                                    <div class="collection-view">
                                                        <ul>
                                                            <li><i class="fa fa-th grid-layout-view"></i></li>
                                                            <li><i class="fa fa-list-ul list-layout-view"></i></li>
                                                        </ul>
                                                    </div>
                                                    {{-- <div class="collection-grid-view">
                                                        <ul>
                                                            <li><img src="{{asset('front-assets/images/icon/2.png')}}" alt="" class="product-2-layout-view"></li>
                                                            <li><img src="{{asset('front-assets/images/icon/3.png')}}" alt="" class="product-3-layout-view"></li>
                                                            <li><img src="{{asset('front-assets/images/icon/4.png')}}" alt="" class="product-4-layout-view"></li>
                                                            <li><img src="{{asset('front-assets/images/icon/6.png')}}" alt="" class="product-6-layout-view"></li>
                                                        </ul>
                                                    </div> --}}
                                                    {{-- <div class="product-page-per-view">
                                                        <?php $pagiNate = (Session::has('cus_paginate')) ? Session::get('cus_paginate') : 8; ?>
                                                        <select class="customerPaginate">
                                                            <option value="8" @if($pagiNate == 8) selected @endif>Show 8 
                                                            </option>
                                                            <option value="12" @if($pagiNate == 12) selected @endif>Show 12 
                                                            </option>
                                                            <option value="24" @if($pagiNate == 24) selected @endif>Show 24
                                                            </option>
                                                            <option value="48" @if($pagiNate == 48) selected @endif>Show 48
                                                            </option>
                                                        </select>
                                                    </div> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="displayCategories">
                                        <div class="categories-wrapper-grid">
                                            <div class="row margin-res">
                                            @if($listData->isNotEmpty())
                                                @foreach($listData as $key => $cate)
                                                <div class="col-xl-3 col-6 col-grid-box">
                                                    <div class="product-box">
                                                        <div class="img-wrapper">
                                                            <a href="{{ route('vendorCategoryProducts', [$vendor->slug, $cate['slug']]) }}">
                                                                <div class="category-image "><img alt="" src="{{$cate['icon']['proxy_url'] . '300/300' . $cate['icon']['image_path']}}" ></div>
                                                            </a>
                                                        </div>
                                                        <div class="product-detail">
                                                            <a href="{{ route('vendorCategoryProducts', [$vendor->slug, $cate['slug']]) }}">
                                                                <h5>{{$cate['translation_name']}}</h5>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            @else
                                                <div class="col-xl-12 col-12 mt-4"><h5 class="text-center">{{__('Details Not Available')}}</h5></div>
                                            @endif
                                            </div>
                                        </div>
                                        <div class="pagination pagination-rounded justify-content-end mb-0">
                                            {{ $listData->links() }}
                                        </div>
                                    </div>
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
@endsection
