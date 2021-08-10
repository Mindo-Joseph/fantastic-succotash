@extends('layouts.store', ['title' => 'Product'])
@section('content')
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
                <ul class="location-inputs position-relative pl-2">
                    <li class="d-block mb-3 dots">
                        <input class="form-control pickup-text" type="text" placeholder="{{__('Add A Pick-Up Location')}}" id="pickup_location"/>
                        <input type="hidden" name="latitude[]" value="" id="pickup_location_latitude" />
                        <input type="hidden" name="longitude[]" value="" id="pickup_location_longitude" />
                        <i class="fa fa-times ml-1" aria-hidden="true"></i>
                    </li>
                    <li class="d-block mb-3 dots">
                        <input class="form-control pickup-text" type="text" placeholder="{{__('Enter Your Destination')}}" id="destination_location" />
                        <input type="hidden" name="latitude[]" value="" id="destination_location_latitude" />
                        <input type="hidden" name="longitude[]" value="" id="destination_location_longitude" />
                        <i class="fa fa-times ml-1" aria-hidden="true"></i>
                    </li>
                </ul>
                <a class="add-more-location position-relative pl-2" href="javascript:void(0)">{{__('Add Destination')}}</a>
            </div>
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
                <div class="cab-button d-flex flex-nowrap align-items-center py-2" id="vendor_main_div">
                    
                </div>
            </div>
            <div class="vehical-container style-4" style="height:calc(100vh - 397px !important" id="search_product_main_div">
                
            </div>
            <script type="text/template" id="vendors_template">
                <% _.each(results, function(result, key){%>
                    <a class="btn btn-solid ml-2 vendor-list" href="javascript:void(0);" data-vendor="<%= result.id %>"><%= result.name %></a>
                <% }); %>
            </script>
            <script type="text/template" id="products_template">
                <% _.each(results, function(result, key){%>
                    <a class="vehical-view-box row align-items-center no-gutters px-2" href="javascript:void(0)" data-product_id="<%= result.id %>">
                        <div class="col-3 vehicle-icon">
                            <img class='img-fluid' src='<%= result.image_url %>'>
                        </div>
                        <div class="col-9">
                            <div class="row no-gutters">
                                <div class="col-8 vehicle-details">
                                    <h4 class="m-0"><b><%= result.name %></b></h4>
                                    <p class="station-rides ellips"><%= result.description %></p>
                                    <p class="waiting-time m-0"><span class="mr-1">In 2 mins.</span><span>03:04 pm</span></p>
                                </div>
                                <div class="col-4 ride-price pl-2">
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
                        <img src="<%= result.image_url %>" alt="">
                    </div>
                    <div class="cab-location-details">
                        <h4 class="d-flex align-items-center justify-content-between"><b><%= result.name %></b> <b>{{Session::get('currencySymbol')}}<%= result.tags_price%></b></h4>
                        <p class="mb-0">In 3 mins.</p>
                        <p><%= result.description %></p>
                    </div>
                </div>
                <div class="cab-amount-details px-2">
                    <div class="row">
                        <div class="col-6 mb-2">Distance</div>
                        <div class="col-6 mb-2 text-right">20.25 kms</div>
                        <div class="col-6 mb-2">Duration</div>
                        <div class="col-6 mb-2 text-right">10.25 mins</div>
                        <div class="col-6 mb-2">Delivery fee</div>
                        <div class="col-6 mb-2 text-right">$114.02</div>
                        <% if(result.loyalty_amount_saved) { %>
                            <div class="col-6 mb-2">Loyalty</div>
                            <div class="col-6 mb-2 text-right">-{{Session::get('currencySymbol')}}<%= result.loyalty_amount_saved %></div>
                        <% } %>
                    </div>
                </div>
            </div>
            <div class="payment-promo-container p-2">
                <h4 class="d-flex align-items-center justify-content-between mb-2">
                    <span>
                        <i class="fa fa-money" aria-hidden="true"></i> Cash
                    </span>
                    <i class="fa fa-angle-down" aria-hidden="true"></i>
                </h4>
                <button class="btn btn-solid w-100">Request <%= result.name %></button>
            </div>
        </script>
        <div class="cab-detail-box style-4 d-none" id="cab_detail_box">
                            
        </div> 
    </div>
</section>
<script>
var live_location = "{{ URL::asset('/images/live_location.gif') }}";
var autocomplete_urls = "{{url('looking/vendor/list/14')}}";
var get_vehicle_list = "{{url('looking/get-list-of-vehicles')}}";
var get_product_detail = "{{url('looking/product-detail')}}";
</script>
@endsection