@extends('layouts.store', ['title' => 'My Subscriptions'])

@section('css')
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
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
    .invalid-feedback {
        display: block;
    }
    .price-card{
        position: relative;
        max-width: 300px;
        height: auto;
        background: linear-gradient(-5deg, transparent 0%, var(--theme-deafult) 50%, var(--theme-deafult) 100%);
        border-radius: 15px;
        margin: 0 auto;
        padding: 40px 20px;
        -webkit-box-shadow: 0 10px 15px rgba(0,0,0,.1) ;
        box-shadow: 0 10px 15px rgba(0,0,0,.1) ;
        -webkit-transition: .5s;
        transition: .5s;
        overflow: hidden;
    }
    .price-card:hover{
        -webkit-transform: scale(1.1);
        transform: scale(1.1);
    }
    /* .col-sm-4:nth-child(1) .price-card{
        background: linear-gradient(-45deg,#f403d1,#64b5f6);
    }
    .col-sm-4:nth-child(2) .price-card{
        background: linear-gradient(-45deg,#ffec61,#f321d7);
    } */
    .price-card::before{
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 40%;
        background: rgba(255, 255, 255, .1);
        z-index: 0;
        -webkit-transform: skewY(-5deg) scale(1.5);
        transform: skewY(-5deg) scale(1.5);
    }
    .title h2 {
        position: relative;
        margin: 20px  0 0;
        padding: 0;
        color: #fff;
        font-size: 20px;
        z-index: 2;
    }
    .price,.option{
        position: relative;
        z-index: 2;
    }
    .price h4 {
        margin: 0;
        padding: 20px 0 ;
        color: #fff;
        font-size: 40px;
    }
    .option ul {
        margin: 0;
        padding: 0;

    }
    .option ul li {
        margin: 0 0 10px;
        padding: 0;
        list-style: none;
        color: #fff;
        font-size: 16px;
    }
    .price-card a {
        position: relative;
        z-index: 2;
        background: #fff;
        color : black;
        width: 150px;
        height: 40px;
        line-height: 40px;
        border-radius: 40px;
        display: block;
        text-align: center;
        margin: 20px auto 0 ;
        font-size: 16px;
        cursor: pointer;
        -webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, .1);
        box-shadow: 0 5px 10px rgba(0, 0, 0, .1);
    }
    .price-card a:hover{
        text-decoration: none;
    }
</style>

<section class="section-b-space">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="text-sm-left">
                    @if (\Session::has('success'))
                        <div class="alert alert-success">
                            <span>{!! \Session::get('success') !!}</span>
                        </div>
                    @endif
                    @if ( ($errors) && (count($errors) > 0) )
                        <div class="alert alert-danger">
                            <ul class="m-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <div class="account-sidebar"><a class="popup-btn">my account</a></div>
                @include('layouts.store/profile-sidebar')
            </div>
            <div class="col-lg-9">
                <div class="dashboard-right">
                    <div class="dashboard">
                        <div class="page-title">
                            <h2>My Subscriptions</h2>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4 justify-content-center">
                    @if($subscriptions->isNotEmpty())
                        @foreach($subscriptions as $sub)
                        <div class="col-sm-4">
                            <div class="price-card text-center">
                                <div class="title">
                                    <img src="{{ $sub->image['proxy_url'].'100/100'.$sub->image['image_path'] }}">
                                    <h2>{{ $sub->title }}</h2>
                                </div>
                                <div class="price">
                                    <h4><sup>$</sup>{{ $sub->price }}</h4>
                                </div>
                                <div class="option">
                                    <ul>
                                        @foreach($sub->features as $feature)
                                        <li class="d-block"><i class="fa fa-check" aria-hidden="true"></i> {{ $feature }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-outline text-white" onclick="return window.open(&quot;{{ route('user.buySubscription', $sub->slug) }}&quot;)">Buy Now</button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <h5>No Subscription Plan Found</h5>
                    @endif
                    <!-- END Col -->
                </div>
                
            </div>
        </div>
    </div>
</section>

@endsection

@section('script')
<script src="{{asset('assets/libs/dropzone/dropzone.min.js')}}"></script>
<script src="{{asset('assets/libs/dropify/dropify.min.js')}}"></script>

<script type="text/javascript">
    
</script>

@endsection