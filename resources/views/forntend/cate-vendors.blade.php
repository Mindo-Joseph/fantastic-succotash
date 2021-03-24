@extends('layouts.store', ['title' => 'Vendor'])

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

<section class="section-b-space border-section border-top-0">
    <div class="row">
        <div class="col-12">
            @if(!empty($category->childs))
            <div class="slide-6 no-arrow">
                @foreach($category->childs->toArray() as $cate)
                <div class="category-block">
                    <a href="{{route('categoryDetail', $cate['id'])}}">
                        <div class="category-image"><img alt="" src="{{$cate['icon']['image_fit'] . '40/30' . $cate['icon']['image_path']}}" ></div>
                    </a>
                    <div class="category-details">
                        <a href="{{route('categoryDetail', $cate['id'])}}">
                            <h5>{{$cate['translation'][0]['name']}}</h5>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</section>

<section class="ratio_asos game-product section-b-space">
    <div class="container">

        <div class="row">
            <div class="col">
                <div class="product-5 product-m no-arrow">
                    @if(!empty($listData))
                        @foreach($listData as $key => $data)
                        <div class="product-box">
                            <div class="img-wrapper">
                                <div class="front">
                                    <a href="{{route('vendorDetail', $data->id)}}"><img class="img-fluid blur-up lazyload" alt="" src="{{$data->banner['image_fit'] .'300/300'. $data->banner['image_path']}}" ></a>
                                </div>                                
                            </div>
                            <div class="product-detail">
                                <div class="rating">
                                    @for($i = 1; $i < 6; $i++)
                                        <i class="fa fa-star"></i>
                                    @endfor
                                </div>
                                <a href="{{route('vendorDetail', $data->id)}}">
                                    <h6>{{$data->name}}</h6>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('script')

@endsection
