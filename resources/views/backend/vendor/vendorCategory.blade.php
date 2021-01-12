@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Vendor'])

@section('css')
<link href="{{asset('assets/libs/nestable2/nestable2.min.css')}}" rel="stylesheet" type="text/css" />

<style type="text/css">
    .modal-lg {
        max-width: 70%;
    }
    span.inner-div{
        float: right;
        display: block;
        position: absolute;
        top: -5px;
        right: 16px;
    }
</style>
@endsection

@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">{{ucfirst($vendor->name)}} profile</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-lg-4 col-xl-4">
                @php $bgimage =  url('storage/'.$vendor->banner); @endphp
                <div class="card-box text-center" style="">
                    
                <div class="background">
                    <img src="{{ url('storage/'.$vendor->logo)}}" class="rounded-circle avatar-lg img-thumbnail"
                        alt="profile-image">

                    <h4 class="mb-0">{{ucfirst($vendor->name)}}</h4>
                    <p class="text-muted">{{$vendor->address}}</p>

                    <button type="button" class="btn btn-success btn-sm waves-effect mb-2 waves-light openEditModal"> Edit </button>
                    <button type="button" class="btn btn-danger btn-sm waves-effect mb-2 waves-light"> Block </button>
                </div>
                    <div class="text-left mt-3">
                        <h4 class="font-13 text-uppercase">Description :</h4>
                        <p class="text-muted font-13 mb-3">
                           {{$vendor->desc}}
                        </p>
                        <p class="text-muted mb-2 font-13"><strong>Latitude :</strong> <span class="ml-2">{{$vendor->latitude}}</span></p>

                        <p class="text-muted mb-2 font-13"><strong>Longitude :</strong><span class="ml-2">{{$vendor->longitude}}</span></p>

                        <p class="text-muted mb-1 font-13"><strong>Status :</strong> <span class="ml-2">
                            {{ ($vendor->status == 1) ? 'Active' : (($vendor->status == 2) ? 'Blocked' : 'Pending') }}
                        </span></p>
                    </div>

                    <ul class="social-list list-inline mt-3 mb-0">
                        <li class="list-inline-item">
                            <a href="javascript: void(0);" class="social-list-item border-primary text-primary"><i
                                    class="mdi mdi-facebook"></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a href="javascript: void(0);" class="social-list-item border-danger text-danger"><i
                                    class="mdi mdi-google"></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a href="javascript: void(0);" class="social-list-item border-info text-info"><i
                                    class="mdi mdi-twitter"></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a href="javascript: void(0);" class="social-list-item border-secondary text-secondary"><i
                                    class="mdi mdi-github"></i></a>
                        </li>
                    </ul>
                </div> <!-- end card-box -->

                <div class="card-box">
                    <h4 class="header-title mb-3">Inbox</h4>

                    <div class="inbox-widget" data-simplebar style="max-height: 350px;">
                        <div class="inbox-item">
                            <div class="inbox-item-img"><img src="{{asset('assets/images/users/user-2.jpg')}}" class="rounded-circle" alt=""></div>
                            <p class="inbox-item-author">Tomaslau</p>
                            <p class="inbox-item-text">I've finished it! See you so...</p>
                            <p class="inbox-item-date">
                                <a href="javascript:(0);" class="btn btn-sm btn-link text-info font-13"> Reply </a>
                            </p>
                        </div>
                        <div class="inbox-item">
                            <div class="inbox-item-img"><img src="{{asset('assets/images/users/user-3.jpg')}}" class="rounded-circle" alt=""></div>
                            <p class="inbox-item-author">Stillnotdavid</p>
                            <p class="inbox-item-text">This theme is awesome!</p>
                            <p class="inbox-item-date">
                                <a href="javascript:(0);" class="btn btn-sm btn-link text-info font-13"> Reply </a>
                            </p>
                        </div>
                        <div class="inbox-item">
                            <div class="inbox-item-img"><img src="{{asset('assets/images/users/user-4.jpg')}}" class="rounded-circle" alt=""></div>
                            <p class="inbox-item-author">Kurafire</p>
                            <p class="inbox-item-text">Nice to meet you</p>
                            <p class="inbox-item-date">
                                <a href="javascript:(0);" class="btn btn-sm btn-link text-info font-13"> Reply </a>
                            </p>
                        </div>

                        <div class="inbox-item">
                            <div class="inbox-item-img"><img src="{{asset('assets/images/users/user-5.jpg')}}" class="rounded-circle" alt=""></div>
                            <p class="inbox-item-author">Shahedk</p>
                            <p class="inbox-item-text">Hey! there I'm available...</p>
                            <p class="inbox-item-date">
                                <a href="javascript:(0);" class="btn btn-sm btn-link text-info font-13"> Reply </a>
                            </p>
                        </div>
                        <div class="inbox-item">
                            <div class="inbox-item-img"><img src="{{asset('assets/images/users/user-6.jpg')}}" class="rounded-circle" alt=""></div>
                            <p class="inbox-item-author">Adhamdannaway</p>
                            <p class="inbox-item-text">This theme is awesome!</p>
                            <p class="inbox-item-date">
                                <a href="javascript:(0);" class="btn btn-sm btn-link text-info font-13"> Reply </a>
                            </p>
                        </div>

                        <div class="inbox-item">
                            <div class="inbox-item-img"><img src="{{asset('assets/images/users/user-3.jpg')}}" class="rounded-circle" alt=""></div>
                            <p class="inbox-item-author">Stillnotdavid</p>
                            <p class="inbox-item-text">This theme is awesome!</p>
                            <p class="inbox-item-date">
                                <a href="javascript:(0);" class="btn btn-sm btn-link text-info font-13"> Reply </a>
                            </p>
                        </div>
                        <div class="inbox-item">
                            <div class="inbox-item-img"><img src="{{asset('assets/images/users/user-4.jpg')}}" class="rounded-circle" alt=""></div>
                            <p class="inbox-item-author">Kurafire</p>
                            <p class="inbox-item-text">Nice to meet you</p>
                            <p class="inbox-item-date">
                                <a href="javascript:(0);" class="btn btn-sm btn-link text-info font-13"> Reply </a>
                            </p>
                        </div>
                    </div> <!-- end inbox-widget -->

                </div> <!-- end card-box-->

            </div> <!-- end col-->

            <div class="col-lg-8 col-xl-8">
                <div class="">
                    <ul class="nav nav-pills navtab-bg nav-justified">
                        <li class="nav-item">
                            <a href="{{ route('vendor.show', $vendor->id) }}"  aria-expanded="false" class="nav-link {{($tab == 'configuration') ? 'active' : '' }}">
                                Configuration
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('vendor.categories', $vendor->id) }}"  aria-expanded="true" class="nav-link {{($tab == 'category') ? 'active' : '' }}">
                                Category
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('vendor.catalogs', $vendor->id) }}"  aria-expanded="false" class="nav-link {{($tab == 'catalog') ? 'active' : '' }}">
                                Catalog
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane {{($tab == 'configuration') ? 'active show' : '' }} card-body" id="configuration">

                        </div> <!-- end tab-pane -->
                        <!-- end about me section content -->

                        <div class="tab-pane {{($tab == 'category') ? 'active show' : '' }}" id="category">

                            <div class="row card-box">
                                <h4 class="mb-4 text-uppercase"><i data-feather="credit-card"></i> Categories</h4>
                                <div class="col-md-12">
                                    <div class="row mb-2">
                                        
                                        <div class="col-md-12">
                                            
                                            <!--<p class="sub-header">
                                                Drag & drop Categories to make child parent relation
                                            </p> -->
                                            <!--<form name="category_order" id="category_order" action="{{route('category.order')}}" method="post">
                                                @csrf
                                                <input type="hidden" name="orderDta" id="orderDta" value="" />
                                            </form> -->
                                            <div class="custom-dd-empty dd" id="nestable_list_3">
                                                
                                                <?php print_r($html); ?>

                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <!-- end timeline content-->

                        <div class="tab-pane {{($tab == 'catalog') ? 'active show' : '' }}" id="catalog">
                            
                        </div>
                    </div> <!-- end tab-content -->
                </div> <!-- end card-box-->

            </div> 
        </div>
    </div>


@include('backend.vendor.modals')
@endsection

@section('script')
    <script src="{{asset('assets/libs/nestable2/nestable2.min.js')}}"></script>
    <script src="{{asset('assets/js/pages/my-nestable.init.js')}}"></script>

@include('backend.vendor.pagescript')

@endsection