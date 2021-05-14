@php
$clientData = \App\Models\Client::select('id', 'logo')->where('id', '>', 0)->first();
$languageList = \App\Models\ClientLanguage::with('language')->where('is_active', 1)->orderBy('is_primary', 'desc')->get();
$currencyList = \App\Models\ClientCurrency::with('currency')->orderBy('is_primary', 'desc')->get();
@endphp
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="main-menu">
                <div class="menu-left">
                    <div class="navbar">
                        <a href="javascript:void(0)" onclick="openNav()">
                            <div class="bar-style"><i class="fa fa-bars sidebar-bar" aria-hidden="true"></i>
                            </div>
                        </a>
                        <div id="mySidenav" class="sidenav">
                            <a href="javascript:void(0)" class="sidebar-overlay" onclick="closeNav()"></a>
                            <nav>
                                <div onclick="closeNav()">
                                    <div class="sidebar-back text-left"><i class="fa fa-angle-left pr-2" aria-hidden="true"></i> Back</div>
                                </div>
                                @if($navCategories && !empty($navCategories))
                                <ul id="sub-menu" class="sm pixelstrap sm-vertical">
                                    @foreach($navCategories as $cate)
                                    <li> <a href="{{route('categoryDetail', $cate['id'])}}">{{$cate['name']}}</a>

                                        @if(!empty($cate['children']))
                                        <ul class="mega-menu clothing-menu">
                                            <div class="row m-0">
                                                @foreach($cate['children'] as $childs)
                                                <li class="col-xl-4">

                                                    <div class="link-section">
                                                        <a href="{{route('categoryDetail', $childs['id'])}}">
                                                            <h5>{{$childs['name']}}</h5>
                                                        </a>
                                                        @if(!empty($childs['children']))
                                                        <ul>
                                                            @foreach($childs['children'] as $chld)
                                                            <li><a href="{{route('categoryDetail', $chld['id'])}}">{{$chld['name']}}</a></li>
                                                            @endforeach
                                                        </ul>
                                                        @endif
                                                    </div>
                                                </li>
                                                @endforeach
                                            </div>
                                        </ul>
                                        @endif
                                    </li>
                                    @endforeach
                                </ul>
                                @endif
                            </nav>
                        </div>
                    </div>
                    <div class="brand-logo">
                        <a href="{{ route('userHome') }}"><img src="{{$clientData->logo->proxy_url . '120/100' . $clientData->logo->image_path}}" class="img-fluid blur-up lazyload" alt=""></a>
                    </div>
                </div>
                <div class="menu-right pull-right">
                    <div>
                        <nav id="main-nav">
                            <div class="toggle-nav"><i class="fa fa-bars sidebar-bar"></i></div>
                            <ul id="main-menu" class="sm pixelstrap sm-horizontal">
                                <li>
                                    <div class="mobile-back text-right">Back<i class="fa fa-angle-right pl-2" aria-hidden="true"></i></div>
                                </li>
                                <li>
                                    <a href="{{route('userHome')}}">Home</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <div>
                        <div class="icon-nav">
                            <form name="filterData" id="filterData" action="{{route('changePrimaryData')}}">
                                @csrf
                                <input type="hidden" id="cliLang" name="cliLang" value="{{session('customerLanguage')}}">
                                <input type="hidden" id="cliCur" name="cliCur" value="{{session('customerCurrency')}}">
                            </form>
                            <ul>
                                <li class="onhover-div mobile-search">
                                    <div><img src="{{asset('front-assets/images/icon/search.png')}}" onclick="openSearch()" class="img-fluid blur-up lazyload" alt=""> <i class="ti-search" onclick="openSearch()"></i></div>
                                    <div id="search-overlay" class="search-overlay">
                                        <div> <span class="closebtn" onclick="closeSearch()" title="Close Overlay">×</span>
                                            <div class="overlay-content">
                                                <div class="container">
                                                    <div class="row">
                                                        <div class="col-xl-12">
                                                            <form action="/search" method="GET">
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" id="exampleInputPassword1" name="query" placeholder="Search a Product">
                                                                </div>
                                                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="onhover-div mobile-setting">
                                    <div><img src="{{asset('front-assets/images/icon/setting.png')}}" class="img-fluid blur-up lazyload" alt=""> <i class="ti-settings"></i></div>
                                    <div class="show-div setting">
                                        <h6>language</h6>
                                        <ul>
                                            @foreach($languageList as $key => $listl)
                                            <li><a href="javascript:void(0)" class="customerLang" langId="{{$listl->language_id}}">{{$listl->language->name}}</a></li>
                                            @endforeach
                                        </ul>
                                        <h6>currency</h6>
                                        <ul class="list-inline">
                                            @foreach($currencyList as $key => $listc)
                                            <li><a href="javascript:void(0)" currId="{{$listc->currency_id}}" class="customerCurr" currSymbol="{{$listc->currency->symbol}}">{{$listc->currency->iso_code}}</a></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </li>
                                <li class="onhover-div mobile-cart">
                                    <div><img src="{{asset('front-assets/images/icon/cart.png')}}" class="img-fluid blur-up lazyload" alt=""> <i class="ti-shopping-cart"></i></div>
                                    <ul class="show-div shopping-cart">
                                        <!-- Append Cart Products from Javascript -->
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var cart_product_url= "{{ route('getCartProducts') }}";
    var delete_cart_product_url= "{{ route('deleteCartProduct') }}";
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="_token"]').attr('content')
        }
    });
</script>