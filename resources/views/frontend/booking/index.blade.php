@extends('layouts.store', ['title' => 'Product'])
@section('content')
<style type="text/css">
.cab-location-details img {
    height: 30px;
}
.cabbooking-loader {
  width: 30px;
  height: 30px;
  animation: loading 1s infinite ease-out;
  margin: auto;
  border-radius: 50%;
  background-color: red;
}
@keyframes loading {
  0% {
    transform: scale(1);
  }
  100% {
    transform: scale(8);
    opacity: 0;
  }
}
</style>
<header>
    <div class="mobile-fix-option"></div>
    @include('layouts.store/left-sidebar')
</header>
<section class="cab-booking pt-0">
    <div id="booking-map" style="width: 100%; height: 100%;"></div>
    <input id="booking-latitude" type="hidden" value="-34">
    <input id="booking-longitude"  type="hidden" value="151">
    <div class="booking-experience ds bc">
        <div class="address-form">
            <div class="location-box">
                <ul class="location-inputs position-relative pl-2" id="location_input_main_div">
                    <li class="d-block mb-3 dots">
                        <input class="form-control pickup-text" type="text" name="pickup_location_name[]" placeholder="{{__('Add A Pick-Up Location')}}" id="pickup_location"/>
                        <input type="hidden" name="pickup_location_latitude[]" value="" id="pickup_location_latitude"/>
                        <input type="hidden" name="pickup_location_longitude[]" value="" id="pickup_location_longitude" />
                    </li>
                    <li class="d-block mb-3 dots">
                        <input class="form-control pickup-text" name="destination_location_name[]" type="text" placeholder="{{__('Add A Stop')}}" id="destination_location"/>
                        <input type="hidden" name="destination_location_latitude[]" value="" id="destination_location_latitude" />
                        <input type="hidden" name="destination_location_longitude[]" value="" id="destination_location_longitude" />
                        <i class="fa fa-times ml-1 apremove" aria-hidden="true" data-rel="{{Carbon\Carbon::now()->timestamp}}"></i>
                    </li>
                </ul>
                <a class="add-more-location position-relative pl-2" href="javascript:void(0)">{{__('Add Destination')}}</a>
            </div>
            <script type="text/template" id="destination_location_template">
                <li class="d-block mb-3 dots" id="dots_<%= random_id %>">
                    <input class="form-control pickup-text" type="text" name="destination_location_name[]" placeholder="{{__('Add A Stop')}}" id="destination_location_<%= random_id %>" data-rel="<%= random_id %>"/>
                    <input type="hidden" name="destination_location_latitude[]" value="" id="destination_location_latitude_<%= random_id %>" data-rel="<%= random_id %>"/>
                    <input type="hidden" name="destination_location_longitude[]" value="" id="destination_location_longitude_<%= random_id %>" data-rel="<%= random_id %>"/>
                    <i class="fa fa-times ml-1 apremove" aria-hidden="true" data-rel="<%= random_id %>"></i>
                </li>
            </script>
            <div class="location-list style-4">
                @forelse($user_addresses as $user_address)
                    <a class="search-location-result position-relative d-block" href="javascript:void(0);" data-address="{{$user_address->address}}" data-latitude="{{$user_address->latitude}}" data-longitude="{{$user_address->longitude}}">
                        <h4 class="mt-0 mb-1"><b>{{$user_address->address}}</b></h4>
                        <p class="ellips mb-0">{{$user_address->city}}, {{$user_address->state}}, {{$user_address->country}}</p>
                    </a>
                @empty
                @endforelse
            </div>
            <div class="table-responsive style-4">
                <div class="cab-button d-flex flex-nowrap align-items-center py-2" id="vendor_main_div"></div>
            </div>
            <div class="vehical-container style-4" style="height:calc(100vh - 397px !important" id="search_product_main_div"></div>
            <script type="text/template" id="vendors_template">
                <% _.each(results, function(result, key){%>
                    <a class="btn btn-solid ml-2 vendor-list" href="javascript:void(0);" data-vendor="<%= result.id %>"><%= result.name %></a>
                <% }); %>
            </script>
            <script type="text/template" id="products_template">
                <% _.each(results, function(result, key){%>
                    <a class="vehical-view-box row align-items-center no-gutters px-2 my-2" href="javascript:void(0)" data-product_id="<%= result.id %>">
                        <div class="col-3 vehicle-icon">
                            <img class='img-fluid' src='<%= result.image_url %>'>
                        </div>
                        <div class="col-9">
                            <div class="row no-gutters">
                                <div class="col-8 vehicle-details">
                                    <h4 class="m-0"><b><%= result.name %></b></h4>
                                </div>
                                <div class="col-4 ride-price pl-2 text-right">
                                    <p class="mb-0"><b>{{Session::get('currencySymbol')}}<%= result.tags_price%></b></p>
                                </div>
                            </div>
                        </div>
                    </a>
                    <hr class="m-0">
                <% }); %>
            </script>
        </div>
        <script type="text/template" id="cab_detail_box_template">
            <div class="cab-outer style-4">
                <div class="bg-white p-2">
                    <a class="close-cab-detail-box" href="javascript:void()">✕</a>
                    <div class="w-100 h-100">
                        <img src="<%= result.image_url %>">
                    </div>
                    <div class="cab-location-details">
                        <h4 class="d-flex align-items-center justify-content-between"><b><%= result.name %></b> <label><sub class="ling-throgh" id
                        ="discount_amount" style="display:none;"></sub> <b id="real_amount">{{Session::get('currencySymbol')}}<%= result.tags_price%></b></label></h4>
                        <p><%= result.description %></p>
                    </div>
                </div>
                <div class="cab-amount-details px-2">
                    <div class="row">
                        <div class="col-6 mb-2">{{__('Distance')}}</div>
                        <div class="col-6 mb-2 text-right" id="distance"></div>
                        <div class="col-6 mb-2">{{__('Duration')}}</div>
                        <div class="col-6 mb-2 text-right" id="duration"></div>
                        <% if(result.loyalty_amount_saved) { %>
                            <div class="col-6 mb-2">Loyalty</div>
                            <div class="col-6 mb-2 text-right">-{{Session::get('currencySymbol')}}<%= result.loyalty_amount_saved %></div>
                        <% } %>
                    </div>
                </div>
                <div class="coupon_box d-flex w-100 py-2 align-items-center justify-content-between">
                    <label class="mb-0 ml-1">   
                        <img src="{{asset('assets/images/discount_icon.svg')}}">
                        <span class="code-text">Select a promo code</span>
                    </label>
                    <a href="javascript:void(0)" class="ml-1" data-product_id="<%= result.id %>" data-vendor_id="<%= result.vendor_id %>" data-amount="<%= result.tags_price%>" id="promo_code_list_btn_cab_booking">Apply</a>
                    <a class="remove-coupon" href="javascript:void(0)" id="remove_promo_code_cab_booking_btn" data-product_id="<%= result.id %>" data-vendor_id="<%= result.vendor_id %>" data-amount="<%= result.tags_price%>" style="display:none;">Remove</a>
                </div>
            </div>
            <div class="payment-promo-container p-2">
                <h4 class="d-flex align-items-center justify-content-between mb-2">
                    <span>
                        <i class="fa fa-money" aria-hidden="true"></i> Cash
                    </span>
                    <i class="fa fa-angle-down" aria-hidden="true"></i>
                </h4>
                <button class="btn btn-solid w-100" id="cab_booking_place_order" data-product_id="<%= result.id %>" data-vendor_id="<%= result.vendor_id %>" data-amount="<%= result.tags_price%>" data-image="<%= result.image_url %>">Request <%= result.name %></button>
            </div>
        </script>
        <script type="text/template" id="cab_booking_promo_code_template">
            <% _.each(promo_codes, function(promo_code, key){%>
                <div class="col-12 mt-2">
                    <div class="coupon-code mt-0">
                        <div class="p-2">
                            <img src="<%= promo_code.image.image_fit %>100/35<%= promo_code.image.image_path %>" alt="">
                            <h6 class="mt-0"><%= promo_code.title %></h6>
                        </div>
                        <hr class="m-0">
                        <div class="code-outer p-2 text-uppercase d-flex align-items-center justify-content-between">
                            <label class="m-0"><%= promo_code.name %></label>
                            <a class="btn btn-solid cab_booking_apply_promo_code_btn" data-vendor_id="<%= vendor_id %>" data-coupon_id="<%= promo_code.id %>" data-product_id="<%= product_id %>" data-amount="<%= amount %>" style="cursor: pointer;">Apply</a>
                        </div>
                        <hr class="m-0">
                        <div class="offer-text p-2">
                            <p class="m-0"><%= promo_code.short_desc %></p>
                        </div>
                    </div>
                </div>
            <% }); %>
        </script>
        <script type="text/template" id="order_success_template">
            <div class="bg-white p-2">
                <div class="w-100 h-100">
                    <img src="<%= product_image %>" alt="">
                </div>
                <div class="cab-location-details">
                    <h4><b>Searching For Nearby Drivers</b></h4>
                    <img src="{{url('images/cabbooking-loader.gif')}}">
                </div>
                <div class="cab-location-details" id="driver_details" style="display:none;">
                   <div class="row align-items-center">
                       <div class="col-8" >
                            <h4 id="driver_name"><b><%= result.user_name %></b></h4>
                            <p class="mb-0" id="driver_phone_number"><%= result.phone_number %></p>
                       </div>
                       <div class="col-4">
                           <div class="taxi-img">
                               <img src="">
                           </div>
                       </div>
                   </div>
                </div>
            </div>
            <div class="cab-amount-details px-2">
                <div class="row">
                    <div class="col-6 mb-2">ETA</div>
                    <div class="col-6 mb-2 text-right" id="distance">--</div>
                    <div class="col-6 mb-2">Order ID</div>
                    <div class="col-6 mb-2 text-right" id=""><%= result.order_number %></div>
                    <div class="col-6 mb-2">Amount Paid</div>
                    <div class="col-6 mb-2 text-right">$<%= result.total_amount %></div>
                </div>
            </div>
        </script>
        <div class="cab-detail-box style-4 d-none" id="cab_detail_box"></div>
        <div class="promo-box style-4 d-none">
            <a class="d-block mt-2 close-promo-code-detail-box" href="javascript:void(0)">✕</a>
            <div class="row" id="cab_booking_promo_code_list_main_div">
                
            </div>    
        </div>
    </div>
</section>
<script>
var autocomplete_urls = "{{url('looking/vendor/list/14')}}";
var get_product_detail = "{{url('looking/product-detail')}}";
var promo_code_list_url = "{{route('verify.promocode.list')}}";
var get_vehicle_list = "{{url('looking/get-list-of-vehicles')}}";
var cab_booking_create_order = "{{url('looking/create-order')}}";
var live_location = "{{ URL::asset('/images/live_location.gif') }}";
var cab_booking_promo_code_remove_url = "{{url('looking/promo-code/remove')}}";
var apply_cab_booking_promocode_coupon_url = "{{ route('verify.cab.booking.promo-code') }}";
var no_coupon_available_message = "{{__('No Other Coupons Available.')}}";
</script>
@endsection