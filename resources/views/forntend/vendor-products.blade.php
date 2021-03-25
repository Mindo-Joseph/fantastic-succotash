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

<section class="ratio_asos game-product section-b-space">
    <div class="container">

        <div class="row">
            <div class="col">
                <div class="product-5 product-m no-arrow">
                    @if(!empty($vendor->products))
                        @foreach($vendor->products as $key => $prod)

                        
                        <?php $imagePath = '';
                        foreach ($prod->media as $k => $v) {
                            $imagePath = $v->image->path['proxy_url'].'300/300'.$v->image->path['image_path'];
                        } ?>
                        <div class="product-box">
                            <div class="img-wrapper">
                                <div class="front">
                                    <a href="{{route('productDetail', $prod->sku)}}"><img class="img-fluid blur-up lazyload" src="{{$imagePath}}" alt=""></a>
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
                                    <h6>{{ (!empty($prod->translation) && isset($prod->translation[0])) ? $prod->translation[0]->title : ''}}</h6>
                                </a>
                                <h4>{{Session::get('currencySymbol').($prod->variant[0]->price * $prod->variant[0]->multiplier)}}</h4>
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
