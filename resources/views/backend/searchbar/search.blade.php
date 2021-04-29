@extends('layouts.store', ['title' => 'Searched Items'])

@section('css')
<style type="text/css">
    .main-menu .brand-logo {
        display: inline-block;
        padding-top: 20px;
        padding-bottom: 20px;
    }

    #product {
        margin-left: 100px !important;
    }
</style>

@endsection

@section('content')

<header>
    <div class="mobile-fix-option"></div>
    @include('layouts.store/left-sidebar')
</header>

<div class="container">
    <h3> <b>Searched Results</b></h3>
    <section class="section-b-space p-t-0 ratio_asos">
        <div class="">
            <div class="row">
                <div class="col">
                    <div class="product-4 product-m no-arrow">
                        @foreach($products as $product)
                        <div class="product-box">
                            <?php $imagePath = '';
                            foreach ($product['media'] as $k => $v) {
                                $imagePath = $v['image']['path']['proxy_url'] . '320/320' . $v['image']['path']['image_path'];
                            } ?>
                            <div class="img-wrapper">
                                <a href="{{route('productDetail', $product->url_slug)}}"><img src="{{$imagePath}}" alt=""></a>
                            </div>
                            <div class="product-detail">
                                <div class="rating"><i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i>
                                </div>
                                <a href="#">
                                    <h6>{{$product->url_slug}}</h6>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- <h3>Searched Categories</h3> -->
    <section class="section-b-space p-t-0 ratio_asos">
        <div class="">
            <div class="row">
                <div class="col">
                    <div class="product-4 product-m no-arrow">
                        @foreach($categories as $category)
                        <div class="product-box">
                            <div class="img-wrapper">
                                <a href="{{route('categoryDetail', $category['id'])}}">
                                    <div class="category-image"> <img src="{{$category['icon']['proxy_url']}}320/320{{$category['icon']['image_path']}}"></div>
                                </a>
                            </div>
                            <div class="category-detail">
                                <div class="rating"><i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i>
                                </div>
                                <a href="#">
                                    <h6>{{$category['slug']}}</h6>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- <h3>Searched Vendors</h3> -->
    <section class="section-b-space p-t-0 ratio_asos">
        <div>
            <div class="row">
                <div class="col">
                    <div class="product-4 product-m no-arrow">
                        @foreach($vendors as $vendor)
                        <div class="product-box">
                            <div class="img-wrapper">
                                <a href="{{route('vendorDetail', $vendor['id'])}}">
                                    <img src="{{$vendor['logo']['proxy_url']}}320/320{{$vendor['logo']['image_path']}}">
                                </a>
                            </div>
                            <div class="vendor-detail">
                                <div class="rating"><i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i>
                                </div>
                                <a href="#">
                                    <h6>{{$vendor['name']}}</h6>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


@endsection

@section('script')

@endsection