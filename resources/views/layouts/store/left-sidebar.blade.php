@php
$clientData = \App\Models\Client::select('id', 'logo')->where('id', '>', 0)->first();
$urlImg =  $clientData ? $clientData->logo['image_fit'].'200/80'.$clientData->logo['image_path'] : " ";
$languageList = \App\Models\ClientLanguage::with('language')->where('is_active', 1)->orderBy('is_primary', 'desc')->get();
$currencyList = \App\Models\ClientCurrency::with('currency')->orderBy('is_primary', 'desc')->get();
@endphp
<header class="site-header">
        <div class="container main-menu d-block">
            <div class="row align-items-center py-2">
                <div class="col-lg-1 col-2">
                    <a class="navbar-brand mr-0" href="{{ route('userHome') }}"><img class="img-fluid" alt="" src="{{$urlImg}}" ></a>
                </div>
                <div class="col-lg-6 main-menu d-block order-lg-1 order-2">
                    <div class="d-md-flex mr-auto">  
                        @if( (Session::get('preferences')))
                            @if( (isset(Session::get('preferences')->is_hyperlocal)) && (Session::get('preferences')->is_hyperlocal == 1) )
                                <div class="location-bar d-flex align-items-center justify-content-start ml-md-2 my-2 my-lg-0 dropdown-toggle order-1" href="#edit-address" data-toggle="modal">
                                    <div class="map-icon mr-1"><i class="fa fa-map-marker" aria-hidden="true"></i></div>
                                    <div class="homepage-address text-left">
                                        <h2><span data-placement="top" data-toggle="tooltip" title="{{session('selectedAddress')}}">{{session('selectedAddress')}}</span></h2>
                                    </div>
                                    <div class="down-icon">
                                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                                    </div>
                                </div>
                            @endif
                        @endif
                        @if($mod_count > 1)
                            <ul class="nav nav-tabs navigation-tab nav-material tab-icons mx-auto order-0 mt-3 mt-md-0 vendor_mods" id="top-tab" role="tablist">
                                @if($client_preference_detail->delivery_check == 1)
                                <li class="navigation-tab-item" role="presentation">
                                    <a class="nav-link {{$mod_count == 1 ? 'active' : 'active'}}" id="delivery_tab" data-toggle="tab" href="#delivery_tab" role="tab" aria-controls="profile" aria-selected="false">{{ __('Delivery') }}</a>
                                </li>
                                @endif
                                @if($client_preference_detail->dinein_check == 1)
                                <li class="navigation-tab-item" role="presentation">
                                    <a class="nav-link {{$client_preference_detail->dinein_check == 1 && $client_preference_detail->delivery_check != 1? 'active' : ''}}" id="dinein_tab" data-toggle="tab" href="#dinein_tab" role="tab" aria-controls="dinein_tab" aria-selected="false">{{ __('Dine-In') }}</a>
                                </li>
                                @endif
                                @if($client_preference_detail->takeaway_check == 1)
                                <li class="navigation-tab-item" role="presentation">
                                    <a class="nav-link {{$mod_count == 1 ? 'active' : ''}}" id="takeaway_tab" data-toggle="tab" href="#takeaway_tab" role="tab" aria-controls="takeaway_tab" aria-selected="false">{{ __('Takeaway') }}</a>
                                </li>                   
                                @endif    
                                <div class="navigation-tab-overlay"></div>
                            </ul>
                        @endif 
                    </div>
                </div>
                <div class="col-lg-5 col-10 order-lg-2 order-1">                
                    <div class="search_bar menu-right d-flex align-items-center justify-content-between w-100 ">
                        <div class="radius-bar">
                            <div class="search_form d-flex align-items-center justify-content-between">
                                <button class="btn"><i class="fa fa-search" aria-hidden="true"></i></button>
                                <input class="form-control border-0 typeahead" type="search" placeholder="{{__('Search')}}" id="main_search_box">
                            </div>
                            <div class="list-box style-4" style="display:none;" id="search_box_main_div">
                                
                            </div>
                        </div>
                            
                        
                        <script type="text/template" id="search_box_main_div_template">
                            <div class="row mx-0">
                                <% _.each(results, function(result, k){ %>
                                    <a class="col-md-4 text-center list-items mb-2" href="<%= result.redirect_url %>">
                                    <img src="<%= result.image_url%>" alt="">
                                    <span><%= result.name %></span>
                                    </a>
                                <% }); %>
                            </div>
                        </script>
                        <div class="icon-nav">
                            <form name="filterData" id="filterData" action="{{route('changePrimaryData')}}">
                                @csrf
                                <input type="hidden" id="cliLang" name="cliLang" value="{{session('customerLanguage')}}">
                                <input type="hidden" id="cliCur" name="cliCur" value="{{session('customerCurrency')}}">
                            </form>
                            <ul>
                                <li class="onhover-div pl-0">
                                    @if($client_preference_detail)
                                        @if($client_preference_detail->cart_enable == 1)
                                            <a class="btn btn-solid" href="{{route('showCart')}}">
                                                <i class="fa fa-shopping-cart mr-1 " aria-hidden="true"></i> <span>{{__('Cart')}} •</span> <span id="cart_qty_span"></span> 
                                            </a>
                                        @endif
                                    @endif
                                    <script type="text/template" id="header_cart_template">
                                        <% _.each(cart_details.products, function(product, key){%>
                                        <% _.each(product.vendor_products, function(vendor_product, vp){%>
                                            <li id="cart_product_<%= vendor_product.id %>" data-qty="<%= vendor_product.quantity %>">
                                                <a class='media' href='#'>
                                                    <% if(vendor_product.pvariant.media_one) { %>
                                                        <img class='mr-2' src="<%= vendor_product.pvariant.media_one.image.path.proxy_url %>200/200<%= vendor_product.pvariant.media_one.image.path.image_path %>">
                                                    <% } %>
                                                    <div class='media-body'>                                                                
                                                        <h4><%= vendor_product.product.translation_one ? vendor_product.product.translation_one.title :  vendor_product.product.sku %></h4>
                                                        <h4>
                                                            <span><%= vendor_product.quantity %> x <%= vendor_product.pvariant.price %></span>
                                                        </h4>
                                                    </div>
                                                </a>
                                                <div class='close-circle'>
                                                    <a href="javascript::void(0);" data-product="<%= vendor_product.id %>" class='remove-product'>
                                                        <i class='fa fa-times' aria-hidden='true'></i>
                                                    </a>
                                                </div>
                                            </li>
                                        <% }); %>
                                        <% }); %>
                                        <li><div class='total'><h5>{{__('Subtotal')}} : <span id='totalCart'><%= cart_details.gross_amount %></span></h5></div></li>
                                        <li><div class='buttons'><a href="<%= show_cart_url %>" class='view-cart'>{{__('View Cart')}}</a>
                                    </script>
                                    <ul class="show-div shopping-cart" id="header_cart_main_ul"></ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @if(count($navCategories) > 0)
        <div class="menu-navigation">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <ul id="main-menu" class="sm pixelstrap sm-horizontal">
                            <li>
                                <div class="mobile-back text-end">{{__('Back')}}<i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
                            </li>
                            @foreach($navCategories as $cate)
                                @if($cate['name'])
                                <li>
                                    <a href="{{route('categoryDetail', $cate['slug'])}}">{{$cate['name']}}</a>
                                    @if(!empty($cate['children']))
                                        
                                        <ul>
                                            @foreach($cate['children'] as $childs)
                                            <li>
                                                <a href="{{route('categoryDetail', $childs['slug'])}}"><span class="new-tag">{{$childs['name']}}</span></a>
                                                @if(!empty($childs['children']))
                                                <ul>
                                                    @foreach($childs['children'] as $chld)
                                                    <li><a href="{{route('categoryDetail', $chld['slug'])}}">{{$chld['name']}}</a></li>
                                                    @endforeach
                                                </ul>
                                                @endif
                                            </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif
</header>
<script type="text/template" id="nav_categories_template">
    <li>
        <div class="mobile-back text-end">Back<i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
    </li>
    <% _.each(nav_categories, function(category, key){ %>
        <li>
            <a href="{{route('categoryDetail')}}/<%= category.slug %>"><%= category.name %></a>
            <% if(category.children) { %>
                <ul>
                <% _.each(category.children, function(childs, key1){ %>
                    <li>
                        <a href="{{route('categoryDetail')}}/<%= childs.slug %>"><span class="new-tag"><%= childs.name %></span></a>
                        <% if(childs.children) { %>
                        <ul>
                            <% _.each(childs.children, function(chld, key2){ %>
                                <li><a href="{{route('categoryDetail')}}/<%= chld.slug %>"><%= chld.name %></a></li>
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
            <button type="button" class="close edit-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <div class="form-group">
                <label class="delivery-head mb-2">{{__('SELECT YOUR LOCATION')}}</label>
                <div class="address-input-field d-flex align-items-center justify-content-between">
                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                    <input class="form-control border-0 map-input" type="text" name="address-input" id="address-input" value="{{session('selectedAddress')}}">
                    <input type="hidden" name="address_latitude" id="address-latitude" value="{{session('latitude')}}" />
                    <input type="hidden" name="address_longitude" id="address-longitude" value="{{session('longitude')}}" />
                </div>
            </div>
            <div class="text-center">
                <button type="button" class="btn btn-solid ml-auto confirm_address_btn w-100">{{__('Confirm And Proceed')}}</button>
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
        <h5 class="modal-title" id="remove_cartLabel">{{__('Remove Cart')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <h6 class="m-0">{{__('Change in location will remove all your cart products. Do you really want to continue ?')}}</h6>
      </div>
      <div class="modal-footer flex-nowrap justify-content-center align-items-center">
        <button type="button" class="btn btn-solid black-btn" data-dismiss="modal">{{__('Cancel')}}</button>
        <button type="button" class="btn btn-solid" id="remove_cart_button" data-cart_id="">{{__('Remove')}}</button>
      </div>
    </div>
  </div>
</div>
