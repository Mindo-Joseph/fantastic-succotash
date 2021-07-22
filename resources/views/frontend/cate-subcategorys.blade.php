@extends('layouts.store', ['title' => (!empty($category->translation) && isset($category->translation[0])) ? $category->translation[0]->name : $category->slug])
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
                    <!-- side-bar banner start start -->
                    <div class="theme-card">
                        <h5 class="title-border">new product</h5>
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
                                                @if($client_preference_detail)
                                                    @if($client_preference_detail->rating_check == 1)  
                                                    <div class="rating">
                                                        @for($i = 1; $i < 6; $i++)
                                                            <i class="fa fa-star"></i>
                                                        @endfor
                                                    </div>
                                                    @endif
                                                @endif
                                                <a href="{{route('productDetail', $new['url_slug'])}}">
                                                    <h6>{{(!empty($new['translation']) && isset($new['translation'][0])) ? $new['translation'][0]['title'] : $new['sku']}}</h6>
                                                </a>
                                                @if($new['inquiry_only'] == 0)
                                                <h4> <?php $multiply = (empty($new['variant'][0]['multiplier'])) ? 1 : $new['variant'][0]['multiplier']; ?>
                                                    {{ Session::get('currencySymbol').' '.(number_format($new['variant'][0]['price'] * $multiply,2))}} </h4>
                                                @endif
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
                                <div class="top-banner-wrapper text-center">
                                    @if(!empty($category->image))
                                    <div class="common-banner"><img alt="" src="{{$category->image['proxy_url'] . '1000/200' . $category->image['image_path']}}" class="img-fluid blur-up lazyload"></div>
                                    @endif
                                    <div class="top-banner-content small-section">
                                        <h4>{{ (!empty($category->translation) && isset($category->translation[0])) ? $category->translation[0]->name : $category->slug }}</h4>
                                        {{--@if(!empty($category->childs) && count($category->childs) > 0)
                                            <div class="row">
                                                <div class="col-12">
                                                    
                                                    <div class="slide-6 no-arrow">
                                                        @foreach($category->childs->toArray() as $cate)
                                                        <div class="category-block">
                                                            <a href="{{route('categoryDetail', $cate['slug'])}}">
                                                                <div class="category-image"><img alt="" src="{{$cate['icon']['proxy_url'] . '100/80' . $cate['icon']['image_path']}}" ></div>
                                                            </a>
                                                            <div class="category-details">
                                                                <a href="{{route('categoryDetail', $cate['slug'])}}">
                                                                    <h5>{{$cate['translation'][0]['name']}}</h5>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endif--}}
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
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="displayProducts">
                                        <div class="product-wrapper-grid">
                                            <div class="row margin-res">
                                                @if(!empty($category->childs) && count($category->childs) > 0)
                                                    @foreach($category->childs->toArray() as $cate)
                                                    <div class="col-xl-2 col-lg-3 col-md-3 col-sm-4 col-xs-6">
                                                        <div class="product-box">
                                                            <a href="{{route('categoryDetail', $cate['slug'])}}">
                                                                <div class="category-image text-center"><img width="100%" alt="" src="{{$cate['icon']['proxy_url'] . '150/150' . $cate['icon']['image_path']}}" ></div>
                                                            </a>
                                                            <div class="category-details">
                                                                <a href="{{route('categoryDetail', $cate['slug'])}}">
                                                                    <h5 class="text-center">{{$cate['translation'][0]['name']}}</h5>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                @else
                                                    <div class="col-xl-12 col-12 mt-4"><h5 class="text-center">Details Not Available</h5></div>
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
