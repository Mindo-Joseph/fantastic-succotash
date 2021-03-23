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

<section class="portfolio-section portfolio-padding grid-portfolio ratio2_3">
    <div class="container">
        <!--<div align="center" id="form1">
            <button class="filter-button project_button active" data-filter="all">All</button>
            <button class="filter-button project_button" data-filter="fashion">Fashion</button>
            <button class="filter-button project_button" data-filter="bags">Bags</button>
            <button class="filter-button project_button" data-filter="shoes">Shoes</button>
            <button class="filter-button project_button" data-filter="watch">Watch</button>
             0 => array:6 [▼
    "id" => 1
    "name" => "DeliveryZone"
    "logo" => array:2 [▼
      "proxy_url" => "https://imgproxy.royoorders.com/insecure/fill/"
      "image_path" => "/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/default/default_logo.png"
    ]
    "banner" => array:2 [▼
      "proxy_url" => "https://imgproxy.royoorders.com/insecure/fill/"
      "image_path" => "/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/default/default_image.png"
    ]
    "order_pre_time" => null
    "order_min_amount" => "0.00"
  ]
        </div> -->
        <div class="row  zoom-gallery">
            @if(!empty($listData))
                @foreach($listData as $key => $data)
                <div class="isotopeSelector filter fashion col-lg-3 col-sm-6">
                    <div class="overlay">
                        <div class="border-portfolio">
                            <a href="{{url('vendor/'.'?lang
curr')}}">
                                <div class="overlay-background">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                </div>
                                <img src="../assets/images/portfolio/grid/1.jpg"
                                    class="img-fluid blur-up lazyload bg-img">
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif


        </div>
    </div>
</section>
@endsection

@section('script')

@endsection
