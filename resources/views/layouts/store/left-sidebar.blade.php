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
                        
                        <div class="brand-logo">
                            <a href="{{ route('userHome') }}"><img class="img-fluid blur-up lazyload" alt="" src="{{$clientData->logo['image_fit'].'200/80'.$clientData->logo['image_path']}}" ></a>
                        </div>
                    </div>
                    <div class="menu-right pull-right">
                        <div>
                            <nav id="main-nav">
                                <div class="toggle-nav"><i class="fa fa-bars sidebar-bar"></i></div>
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
    <div class="search_warpper">
        <div class="container">
            <div class="row no-gutters" id="location_search_wrapper">
                @if( (Session::get('preferences')))
                @if(Session::get('preferences')->is_hyperlocal == 1)
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
                            <button class="btn btn-solid px-md-3 px-2"><i class="fa fa-search" aria-hidden="true"></i></button>
                        </form>
                    </div>
                @else
                    <div class="col-lg-12 col-md-12 col">
                        <form class="search_form d-flex align-items-center justify-content-between" action="">
                            <input class="form-control border-0" type="text" placeholder="Search">
                            <button class="btn btn-solid px-md-3 px-2"><i class="fa fa-search" aria-hidden="true"></i></button>
                        </form>
                    </div>
                @endif
                @endif
            </div>
        </div>
    </div>
</header>
<script type="text/template" id="nav_categories_template">
    <li>
        <div class="mobile-back text-end">Back<i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
    </li>
    <% _.each(nav_categories, function(category, key){ %>
        <li>
            <a href="{{route('categoryDetail')}}/<%= category.id %>"><%= category.name %></a>
            <% if(category.children) { %>
                <ul>
                <% _.each(category.children, function(childs, key1){ %>
                    <li>
                        <a href="{{route('categoryDetail')}}/<%= childs.id %>"><span class="new-tag"><%= childs.name %></span></a>
                        <% if(childs.children) { %>
                        <ul>
                            <% _.each(childs.children, function(chld, key2){ %>
                                <li><a href="{{route('categoryDetail')}}/<%= chld.id %>"><%= chld.name %></a></li>
                            <% }); %>
                        </ul>
                        <% } %>
                    </li>
                <% }); %>
                </ul>
            <% } %>
        </li>
    <% }); %>
</script>
<div class="modal fade edit_address" id="edit-address" tabindex="-1" aria-labelledby="edit-addressLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body p-0">
        <div id="address-map-container">
            <div id="address-map"></div>
        </div>
        <div class="delivery_address p-2 mb-2 position-relative">
            <button type="button" class="close edit-close position-absolute" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <div class="form-group">
                <label class="delivery-head mb-2">SELECT YOUR LOCATION</label>
                <div class="address-input-field d-flex align-items-center justify-content-between">
                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                    <input class="form-control border-0 map-input" type="text" name="address-input" id="address-input" value="{{session('deliveryAddress')}}">
                    <input type="hidden" name="address_latitude" id="address-latitude" value="{{session('latitude')}}" />
                    <input type="hidden" name="address_longitude" id="address-longitude" value="{{session('longitude')}}" />
                </div>
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
        <h6 class="m-0">Change in location will remove all your cart products. Do you really want to continue ?</h6>
      </div>
      <div class="modal-footer flex-nowrap justify-content-center align-items-center">
        <button type="button" class="btn btn-solid black-btn" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-solid" id="remove_cart_button" data-cart_id="">Remove</button>
      </div>
    </div>
  </div>
</div>