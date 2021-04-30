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
@if(Session::has('Not-Found'))
<div class="alert alert-success" role="alert">
    {{Session::get('Not-Found')}};
</div>
@endif
<div class="container">
    <h4>Searched Results </h4>
    <section class="section-b-space ratio_asos">
        <div class="collection-wrapper">
            <div class="container">
                <div class="row">
                    <!-- For products -->
                    <div class="Display-Products">
                        <div class="product-wrapper-grid">
                            <div class="row margin-res">
                                @foreach($products as $product)
                                <div class="col-xl-3 col-6 ">
                                    <div class="product-box">
                                        <?php $imagePath = '';
                                        foreach ($product['media'] as $k => $v) {
                                            $imagePath = $v['image']['path']['proxy_url'] . '200/200' . $v['image']['path']['image_path'];
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
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- For categories -->
                    <h3>Searched Categories</h3>
                    <div class="Display-Categories">
                        <div class="product-wrapper-grid">
                            <div class="row margin-res">
                                @foreach($categories as $category)
                                <div class="col-xl-3 col-6">
                                    <div class="category-box">
                                        <div class="img-wrapper">
                                            <a href="{{route('categoryDetail', $category['id'])}}">
                                                <div class="category-image"> <img src="{{$category['icon']['proxy_url']}}200/200{{$category['icon']['image_path']}}"></div>
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
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- <h3>Searched Vendors</h3> -->
                    <!-- <div class="Display-Vendors">
                        <div class="product-wrapper-grid">
                            <div class="row margin-res">
                                @foreach($vendors as $vendor)
                                <div class="col-md-3 col-6 col-grid-box">
                                    <div class="vendor-box">
                                        <div class="img-wrapper">
                                            <a href="{{route('vendorDetail', $vendor['id'])}}">
                                                <img src="{{$vendor['logo']['proxy_url']}}250/250{{$vendor['logo']['image_path']}}">
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
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </section>





    <!-- <h3>Searched Categories</h3>
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
    </section> -->

    <h3>Searched Vendors</h3>
    <section class="section-b-space p-t-0 ratio_asos">
        <div>
            <div class="row">
                <div class="col">
                    <div class="product-4 product-m no-arrow">
                        @foreach($vendors as $vendor)
                        <div class="product-box">
                            <div class="img-wrapper">
                                <a href="{{route('vendorDetail', $vendor['id'])}}">
                                    <img src="{{$vendor['logo']['proxy_url']}}200/200{{$vendor['logo']['image_path']}}">
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