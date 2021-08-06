@extends('layouts.store', ['title' => __('Home')])

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
<section class="p-0 small-slider">
    <div class="slide-1 home-slider"> 
        @foreach($banners as $banner)
        @php
        $url = '';
        if($banner->link == 'category'){
        if($banner->category != null){
        $url = route('categoryDetail', $banner->category->slug);
        }
        }
        else if($banner->link == 'vendor'){
        if($banner->vendor != null){
        $url = route('vendorDetail', $banner->vendor->slug);
        }
        }
        @endphp
        <div>
            @if($url)
            <a href="{{$url}}">
                @endif
                <div class="home text-center">
                    <img src="{{$banner->image['image_fit'] . '1500/600' . $banner->image['image_path']}}" class="bg-img blur-up lazyload">
                </div>
                @if($url)
            </a>
            @endif
        </div>
        @endforeach
    </div>
</section>
<script type="text/template" id="vendors_template">
    <% _.each(vendors, function(vendor, k){%>
        <div class="product-box scale-effect">
            <div class="img-wrapper">
                <div class="front">
                    <a href="{{route('vendorDetail')}}/<%= vendor.slug %>">
                        <img class="img-fluid blur-up lazyload m-auto bg-img" alt="" src="<%= vendor.logo.proxy_url %>200/200<%= vendor.logo['image_path'] %>">
                    </a>
                </div>
            </div>
            <div class="product-detail">
                <a href="{{route('vendorDetail')}}/<%= vendor.slug %>">
                    <h6><%= vendor.name %></h6>
                </a>
                @if($client_preference_detail)
                    @if($client_preference_detail->rating_check == 1)
                        <div class="custom_rating">
                            <% if(vendor.vendorRating > 0) { %>
                                <% _.each([1,2,3,4,5], function(value, k){ %>
                                    <i class="fa fa-star<%= (k+1 <= vendor.vendorRating) ? ' filled' : '' %>"></i>
                                <% }); %>
                            <% } %>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    <% }); %>
</script>
<script type="text/template" id="banner_template">
    <% _.each(brands, function(brand, k){%>
        <div>
            <div class="">
                <a href="<%= brand.redirect_url %>">
                    <img src="<%= brand.image.image_fit %>120/120<%= brand.image.image_path %>" alt="">
                </a>
            </div>
        </div>
    <% }); %>
</script>
<script type="text/template" id="products_template">
    <% _.each(products, function(product, k){ %>
        <div>
            <a class="card scale-effect text-center" href="{{route('productDetail')}}/<%= product.url_slug %>">
                <label class="product-tag"><%= type %></label>
                <div class="product-image">
                    <img src="<%= product.image_url %>" alt="">
                </div>    
                <div class="media-body align-self-center">
                    <div class="inner_spacing px-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <h3 class="m-0"><%= product.title %></h3>
                            <span class="rating">4.2</span>
                        </div>
                        <p><%= product.vendor_name %></p>
                        <h4>
                            <% if(product.inquiry_only == 0) { %>
                                <%= product.price %>
                            <% } %>
                        </h4>
                        @if($client_preference_detail)
                            @if($client_preference_detail->rating_check == 1)
                                <div class="custom_rating">
                                    <% if(product.averageRating > 0) { %>
                                        <% _.each([1,2,3,4,5], function(value, k){ %>
                                            <i class="fa fa-star<%= (k+1 <= product.averageRating) ? ' filled' : '' %>"></i>
                                        <% }); %>
                                    <% } %>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </a>
        </div>
    <% }); %>
</script>
<section class="section-b-space p-t-0 pt-5 ratio_asos d-none" id="our_vendor_main_div">
    <div class="vendors">
        @foreach($homePageLabels as $homePageLabel)
        <div class="container" id="{{$homePageLabel->slug.'1'}}">
            <div class="row">
                <div class="col-12 text-center d-flex align-items-center justify-content-between mb-4">
                    <div class="title1">
                        <!-- <h2 class="title-inner1 mb-0">{{ $homePageLabel->slug == 'vendors' ? getNomenclatureName('vendors', true) :  __($homePageLabel->title) }}</h2> -->
                    </div>
                    <!-- @if($homePageLabel->slug == 'vendors')
                    <a class="view_more_items" href="{{route('vendor.all')}}">{{__('View More')}}</a>
                    @endif -->
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    @if($homePageLabel->slug == 'vendors')
                    <div class="vendorproduct-4 product-m no-arrow" id="{{$homePageLabel->slug}}"></div>
                    @else
                    <div class="product-4 product-m no-arrow" id="{{$homePageLabel->slug}}"></div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
<div class="modal fade" id="age_restriction" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img src="{{asset('assets/images/18.png')}}" alt="">
                <p class="mb-0 mt-3">{{ $client_preference_detail ? $client_preference_detail->age_restriction_title : 'Are you 18 or older?' }}</p>
                <p class="mb-0">Are you sure you want to continue?</p>
            </div>
            <div class="modal-footer d-block">
                <div class="row no-gutters">
                    <div class="col-6 pr-1">
                        <button type="button" class="btn btn-solid w-100 age_restriction_yes" data-dismiss="modal">Yes</button>
                    </div>
                    <div class="col-6 pl-1">
                        <button type="button" class="btn btn-solid w-100 age_restriction_no" data-dismiss="modal">No</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{asset('front-assets/js/jquery.exitintent.js')}}"></script>
<script src="{{asset('front-assets/js/fly-cart.js')}}"></script>
@endsection