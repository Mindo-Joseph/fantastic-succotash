@php
$clientData = \App\Models\Client::select('id', 'logo')->where('id', '>', 0)->first();

$urlImg = $clientData->logo['proxy_url'].'200/80'.$clientData->logo['image_path'];
$languageList = \App\Models\ClientLanguage::with('language')->where('is_active', 1)->orderBy('is_primary', 'desc')->get();
$currencyList = \App\Models\ClientCurrency::with('currency')->orderBy('is_primary', 'desc')->get();
@endphp
<header class="header-2 header-6">
    <div class="mobile-fix-option"></div>
    
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="main-menu border-section border-top-0">
                    <div class="menu-left">
                        <!-- <div class="navbar">
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
                        </div> -->
                        <div class="brand-logo">
                            <a href="{{ route('userHome') }}"><img class="img-fluid blur-up lazyload" alt="" src="{{$clientData->logo['image_fit'].'200/80'.$clientData->logo['image_path']}}" ></a>
                        </div>
                    </div>
                    <div class="menu-right pull-right">
                        <div>
                            <nav id="main-nav">
                                <div class="toggle-nav"><i class="fa fa-bars sidebar-bar"></i></div>
                                <!-- 
                                    <li>
                                        <div class="mobile-back text-right">Back<i class="fa fa-angle-right pl-2" aria-hidden="true"></i></div>
                                    </li>
                                    <li>
                                        <a href="{{route('userHome')}}">Home</a>
                                    </li>
                                </ul> -->
                                <ul id="main-menu" class="sm pixelstrap sm-horizontal">
                                    <li>
                                        <div class="mobile-back text-end">Back<i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
                                    </li>
                                @foreach($navCategories as $cate)
                                    <li>
                                        <a href="{{route('categoryDetail', $cate['id'])}}">{{$cate['name']}}</a>
                                        @if(!empty($cate['children']))
                                            
                                            <ul>
                                                @foreach($cate['children'] as $childs)
                                                <li>
                                                    <a href="{{route('categoryDetail', $childs['id'])}}"><span class="new-tag">{{$childs['name']}}</span></a>
                                                    @if(!empty($childs['children']))
                                                    <ul>
                                                      @foreach($childs['children'] as $chld)
                                                        <li><a href="{{route('categoryDetail', $chld['id'])}}">{{$chld['name']}}</a></li>
                                                      @endforeach
                                                    </ul>
                                                    @endif
                                                </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
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
                                    <!-- <li class="onhover-div mobile-search">
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
                                    </li> -->
                                    <li>
                                        <i class="search_btn fa fa-search"></i>
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
                                        <div>
                                            <img src="{{asset('front-assets/images/icon/cart.png')}}" class="img-fluid blur-up lazyload" alt=""> 
                                            <i class="ti-shopping-cart"></i>
                                        </div>
                                        <span class="cart_qty_cls" style="display:none;" id="cart_qty_span"></span>
                                        <script type="text/template" id="header_cart_template">
                                             <% _.each(cart_details.products, function(product, key){%>
                                              <% _.each(product.vendor_products, function(vendor_product, vp){%>
                                                <li id="cart_product_<%= vendor_product.id %>" data-qty="<%= vendor_product.quantity %>">
                                                    <div class='media'>
                                                        <a href='#'>
                                                            <% if(vendor_product.pvariant.media_one) { %>
                                                                <img class='mr-3' src="<%= vendor_product.pvariant.media_one.image.path.proxy_url %>200/200<%= vendor_product.pvariant.media_one.image.path.image_path %>">
                                                            <% } %>
                                                        </a>
                                                        <div class='media-body'>
                                                            <a href='#'>
                                                                
                                                                <h4><%= vendor_product.product.translation_one ? vendor_product.product.translation_one.title :  vendor_product.product.sku %></h4>
                                                            </a>
                                                            <h4>
                                                                <span><%= vendor_product.quantity %> x <%= vendor_product.pvariant.price %></span>
                                                            </h4>
                                                        </div>
                                                    </div>
                                                    <div class='close-circle'>
                                                        <a href="javascript::void(0);" data-product="<%= vendor_product.id %>" class='remove-product'>
                                                            <i class='fa fa-times' aria-hidden='true'></i>
                                                        </a>
                                                    </div>
                                                </li>
                                              <% }); %>
                                            <% }); %>
                                            <li><div class='total'><h5>subtotal : <span id='totalCart'><%= cart_details.gross_amount %></span></h5></div></li>
                                            <li><div class='buttons'><a href="<%= show_cart_url %>" class='view-cart'>viewcart</a>
                                        </script>
                                        <ul class="show-div shopping-cart" id="header_cart_main_ul">

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
    
    @if($navCategories && !empty($navCategories))
    <!-- <div class="container">
        <div class="row ">
            <div class="col-lg-12">
                <div class="main-nav-center">
                    <nav id="main-nav">
                        <div class="toggle-nav">
                            <i class="fa fa-bars sidebar-bar"></i>
                        </div>
                        <ul id="main-menu" class="sm pixelstrap sm-horizontal">
                            <li>
                                <div class="mobile-back text-right">Back<i class="fa fa-angle-right pl-2" aria-hidden="true"></i></div>
                            </li>
                            @foreach($navCategories as $cate)
                            <li>
                                <a href="{{route('categoryDetail', $cate['id'])}}">{{$cate['name']}}</a>
                                @if(!empty($cate['children']))
                                    
                                    <ul>
                                        @foreach($cate['children'] as $childs)
                                        <li>
                                            <a href="{{route('categoryDetail', $childs['id'])}}"><span class="new-tag">{{$childs['name']}}</span></a>
                                            @if(!empty($childs['children']))
                                            <ul>
                                              @foreach($childs['children'] as $chld)
                                                <li><a href="{{route('categoryDetail', $chld['id'])}}">{{$chld['name']}}</a></li>
                                              @endforeach
                                            </ul>
                                            @endif
                                        </li>
                                        @endforeach
                                    </ul>
                                @endif
                            @endforeach
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div> -->
    @endif

    <div class="search_warpper">
        <div class="container">
            <div class="row no-gutters" id="location_search_wrapper">
                @if( (Session::has('preferences')) && (Session::get('preferences')->is_hyperlocal == 1) )
                    <div class="col-lg-3 col-md-4 col">
                        <div class="d-flex align-items-center justify-content-start px-3 dropdown-toggle" href="#edit-address" data-toggle="modal">
                            <div class="map-icon mr-1"><i class="fa fa-map-marker" aria-hidden="true"></i></div>
                            <div class="homepage-address text-left">
                                <h2><span data-placement="top" data-toggle="tooltip" title="{{session('deliveryAddress')}}">{{session('deliveryAddress')}}</span></h2>
                            </div>
                            <div class="down-icon">
                                <i class="fa fa-angle-down" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-8 col">
                        <form class="search_form d-flex align-items-center justify-content-between" action="">
                            <input class="form-control border-0" type="text" placeholder="Search">
                            <button class="btn btn-solid px-md-3 px-2"><i class="fa fa-search" aria-hidden="true"></i><!--span class="search-text">Search</span--></button>
                        </form>
                    </div>
                @else
                    <div class="col-lg-12 col-md-12 col">
                        <form class="search_form d-flex align-items-center justify-content-between" action="">
                            <input class="form-control border-0" type="text" placeholder="Search">
                            <button class="btn btn-solid px-md-3 px-2"><i class="fa fa-search" aria-hidden="true"></i><!--span class="search-text">Search</span--></button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</header>

<div class="modal fade edit_address" id="edit-address" tabindex="-1" aria-labelledby="edit-addressLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body p-0">
        <div id="address-map-container">
            <div id="address-map"></div>
        </div>
        <div class="delivery_address p-2 mb-2 position-relative">
            <?php /* ?><div class="modal-title">Select your location</div><?php */ ?>
            <button type="button" class="close edit-close position-absolute" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <div class="form-group">
                <label class="delivery-head mb-2">SELECT YOUR LOCATION</label>
                <?php /* ?><div class="select_address border d-flex align-items-center justify-content-between ">
                    <div class="location-area">
                        <i class="fa fa-check-circle-o" aria-hidden="true"></i>
                        <span>Sector 28 C, Chandigarh, India</span>
                    </div>   
                    <label class="m-0 text-uppercase">Change</label>
                </div><?php */ ?>
                <div class="address-input-field d-flex align-items-center justify-content-between">
                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                    <input class="form-control border-0 map-input" type="text" name="address-input" id="address-input" value="{{session('deliveryAddress')}}">
                    <input type="hidden" name="address_latitude" id="address-latitude" value="{{session('latitude')}}" />
                    <input type="hidden" name="address_longitude" id="address-longitude" value="{{session('longitude')}}" />
                </div>
                <?php /* ?><div class="edit-area">
                    <input class="form-control" type="text" placeholder="Complete Address *" name="complete_address" id="complete_address">
                    <input class="form-control" type="text" placeholder="Floor (Optional)" name="floor" id="floor">
                    <input class="form-control" type="text" placeholder="How to reach (Optional)" name="address_hint" id="address_hint">
                </div>
                <div class="mt-2 mb-2">
                    <div class="address_type">
                        <label class="radio d-inline-block m-0">Home
                            <input type="radio" name="address_type" checked="checked" value="home">
                            <span class="checkround"></span>
                        </label>
                        <label class="radio d-inline-block m-0">Office
                            <input type="radio" name="address_type" value="office">
                            <span class="checkround"></span>
                        </label>
                        <label class="radio other_address d-inline-block m-0">Other
                            <input type="radio" name="address_type" value="other">
                            <span class="checkround"></span>
                        </label>   
                    </div>
                    <div class="other-address-input d-none">
                        <label class="d-inline-block m-0">
                            <input type="text" name="other_address">
                        </label>
                    </div>                      
                </div><?php */ ?>
            </div>
            <div class="text-center">
                <button type="button" class="btn btn-solid ml-auto confirm_address_btn w-100">Confirm And Proceed</button>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade remove-cart-modal" id="remove_cart_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="remove_cartLabel" style="background-color: rgba(0,0,0,0.8);">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header pb-0">
        <h5 class="modal-title" id="remove_cartLabel">Remove Cart</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="vendor_id" value="1">
        <input type="hidden" id="cartproduct_id" value="52">
        <h6 class="m-0">Change in location will remove all your cart products. Do you really want to continue ?</h6>
      </div>
      <div class="modal-footer flex-nowrap justify-content-center align-items-center">
        <button type="button" class="btn btn-solid black-btn" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-solid" id="remove_cart_button" data-cart_id="">Remove</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
    var show_cart_url = "{{ route('showCart') }}";
    var cart_details_url = "{{ route('cartDetails') }}";
    var user_checkout_url= "{{ route('user.checkout') }}";
    var cart_product_url= "{{ route('getCartProducts') }}";
    var delete_cart_product_url= "{{ route('deleteCartProduct') }}";
    var delete_cart_url = "{{ route('emptyCartData') }}";
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="_token"]').attr('content')
        }
    });
</script>
<script>
    jQuery(document).ready(function(){
        jQuery(".search_btn").click(function () {
            jQuery(".search_warpper").slideToggle("slow");
        });

        jQuery(".close_btn").click(function () {
            jQuery(".search_warpper").slideUp("slow");
        });
    });
</script>