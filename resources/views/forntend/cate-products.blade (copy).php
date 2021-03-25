@extends('layouts.store', ['title' => 'Category'])

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
@if(!empty($category->childs))
<section class="section-b-space border-section border-top-0">
    <div class="row">
        <div class="col-12">
            
            <div class="slide-6 no-arrow">
                @foreach($category->childs->toArray() as $cate)
                <div class="category-block">
                    <a href="{{route('categoryDetail', $cate['id'])}}">
                        <div class="category-image"><img alt="" src="{{$cate['icon']['proxy_url'] . '40/30' . $cate['icon']['image_path']}}" ></div>
                    </a>
                    <div class="category-details">
                        <a href="{{route('categoryDetail', $cate['id'])}}">
                            <h5>{{$cate['translation'][0]['name']}}</h5>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif
<section class="ratio_asos game-product section-b-space">
    <div class="container">

        <div class="row">
            <div class="col">
                <div class="product-5 product-m no-arrow">
                    @if(!empty($listData))
                        @foreach($listData as $key => $data)

                        <?php $imagePath = '';
                        foreach ($data->media as $k => $v) {
                            $imagePath = $v->image->path['proxy_url'].'300/300'.$v->image->path['image_path'];
                        } ?>
                        <div class="product-box">
                            <div class="img-wrapper">
                                <div class="front">
                                    <a href="{{route('productDetail', $data->sku)}}"><img class="img-fluid blur-up lazyload" src="{{$imagePath}}" alt=""></a>
                                </div>
                                <div class="cart-info cart-wrap">
                                    <a href="javascript:void(0)" title="Add to Wishlist"><i class="ti-heart"
                                            aria-hidden="true"></i></a>
                                    <!--<a href="#" data-toggle="modal" data-target="#quick-view" title="Quick View"><i
                                            class="ti-search" aria-hidden="true"></i></a>
                                    <a href="compare.html" title="Compare"><i class="ti-reload"
                                            aria-hidden="true"></i></a> -->
                                </div>
                                <div class="add-button" data-toggle="modal" data-target="#addtocart">add to cart</div>
                            </div>
                            <div class="product-detail">
                                <div class="rating">
                                    @for($i = 1; $i < 6; $i++)
                                        <i class="fa fa-star"></i>
                                    @endfor
                                </div>
                                <a href="{{route('productDetail', $data->sku)}}">
                                    <h6>{{(!empty($data->translation) && isset($data->translation[0])) ? $data->translation[0]->title : ''}}</h6>
                                </a>
                                <h4>{{Session::get('currencySymbol').($data->variant[0]->price * $data->variant[0]->multiplier)}}</h4>
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
