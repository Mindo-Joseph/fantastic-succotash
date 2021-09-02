@extends('layouts.store', ['title' => 'Loyalty'])
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
<link rel="stylesheet" href="{{asset('assets/css/intlTelInput.css')}}">
@endsection
@section('content')
<header>
    <div class="mobile-fix-option"></div>
    @include('layouts.store/left-sidebar')
</header>
<style type="text/css">
</style>

<section class="section-b-space">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="account-sidebar"><a class="popup-btn">{{ __('My Account') }}</a></div>
                @include('layouts.store/profile-sidebar')
            </div>
            <div class="col-lg-9">
                <div class="dashboard-right">
                    <div class="dashboard">
                        <div class="page-title">
                            <h2>{{ __('My Loyalty') }}</h2>
                        </div>
                        <div class="card-box">
                            <div class="row">
                                <div class="offset-md-3 col-md-6">
                                    <div class="card-box">
                                        <div class="row align-items-center">
                                            <div class="col-4">
                                                <div class="medal-img">
                                                    <img src="{{asset('front-assets/images/ic_bronze@2x.png')}}" alt="">
                                                </div>
                                            </div>
                                            <div class="col-8">
                                                <h3 class="mt-0"><b>You are not</b></h3>
                                                <div class="loalty-title">
                                                    Bronze
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 text-center">
                                            <div class="card-box earn-points p-2">
                                                <div class="points-title">
                                                    0
                                                </div>
                                                <div class="ponits-heading">
                                                    Total Earned Points
                                                </div>                                                    
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-center">
                                            <div class="card-box spend-points p-2">
                                                <div class="points-title">
                                                    0
                                                </div>
                                                <div class="ponits-heading">
                                                    Spendable Points
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                           <div class="row">
                                <div class="offset-lg-1 col-lg-10">
                                    <div class="row">
                                        <div class="col-12 mt-3">
                                            <h2>Upcoming</h2>
                                        </div>
                                        <div class="col-md-6 mt-3 text-center">
                                            <div class="card-box">
                                                <div class="point-img-box">
                                                    <img src="{{asset('front-assets/images/ic_silver@2x.png')}}" alt="">
                                                </div>
                                                <h3 class="mb-0 mt-3"><b>100.00 points to Silver</b></h3>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-3 text-center">
                                            <div class="card-box">
                                                <div class="point-img-box">
                                                    <img src="{{asset('front-assets/images/ic_gold@2x.png')}}" alt="">
                                                </div>
                                                <h3 class="mb-0 mt-3"><b>100.00 points to Silver</b></h3>
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
    </div>
</section>

@endsection
@section('script')

@endsection