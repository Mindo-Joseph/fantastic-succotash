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
                        <input class="form-control pickup-text" type="text" placeholder="Add a pick-up location" id="pickup_location"/>
                        <input type="hidden" name="latitude[]" value="" id="pickup_location_latitude" />
                        <input type="hidden" name="longitude[]" value="" id="pickup_location_longitude" />
                        <i class="fa fa-times ml-1" aria-hidden="true"></i>
                    </li>
                    <li class="d-block mb-3 dots">
                        <input class="form-control pickup-text" type="text" placeholder="Enter Your Destination" id="destination_location" />
                        <input type="hidden" name="latitude[]" value="" id="destination_location_latitude" />
                        <input type="hidden" name="longitude[]" value="" id="destination_location_longitude" />
                        <i class="fa fa-times ml-1" aria-hidden="true"></i>
                    </li>
                </ul>
                <a class="add-more-location position-relative pl-2" href="javascript:void(0)">Add Destination</a>
            </div>
            <div class="location-list style-4 d-none">
                @forelse($user_addresses as $user_address)
                    <a class="search-location-result position-relative d-block" href="javascript:void(0);" data-address="{{$user_address->address}}" data-latitude="{{$user_address->latitude}}" data-longitude="{{$user_address->longitude}}">
                        <h4 class="mt-0 mb-1"><b>{{$user_address->address}}</b></h4>
                        <p class="ellips mb-0">{{$user_address->city}}, {{$user_address->state}}, {{$user_address->country}}</p>
                    </a>
                @empty
                @endforelse
            </div>
            <div class="table-responsive style-4">
                <div class="cab-button d-flex flex-nowrap align-items-center py-2">
                    <a class="btn btn-solid ml-2" href="#">uber</a>
                    <a class="btn btn-solid ml-2" href="#">ola</a>
                    <a class="btn btn-solid ml-2" href="#">uber</a>
                    <a class="btn btn-solid ml-2" href="#">ola</a>
                    <a class="btn btn-solid ml-2" href="#">uber</a>
                    <a class="btn btn-solid ml-2" href="#">ola</a>
                    <a class="btn btn-solid ml-2" href="#">uber</a>
                    <a class="btn btn-solid ml-2" href="#">ola</a>
                    <a class="btn btn-solid ml-2" href="#">uber</a>
                    <a class="btn btn-solid ml-2" href="#">ola</a>
                    <a class="btn btn-solid ml-2" href="#">uber</a>
                    <a class="btn btn-solid ml-2" href="#">ola</a>
                    <a class="btn btn-solid ml-2" href="#">uber</a>
                    <a class="btn btn-solid ml-2" href="#">ola</a>

                </div>
            </div>
            <div class="vehical-container style-4" style="height:calc(100vh - 397px !important">
                <a class="vehical-view-box row align-items-center no-gutters px-2" href="#">
                    <div class="col-3 vehicle-icon">
                        <img class="img-fluid" src="https://d1a3f4spazzrp4.cloudfront.net/car-types/haloProductImages/v1.1/Select_v1.png">
                    </div>
                    <div class="col-9">
                        <div class="row no-gutters">
                            <div class="col-8 vehicle-details">
                                <h4 class="m-0"><b>Go Intercity</b></h4>
                                <p class="station-rides ellips">Affordable outstation rides</p>
                                <p class="waiting-time m-0"><span class="mr-1">In 2 mins.</span><span>03:04 pm</span></p>
                            </div>
                            <div class="col-4 ride-price pl-2">
                                <p class="mb-0"><b>₹2,634.37</b></p>
                            </div>
                        </div>
                    </div>
                </a>
                <hr class="m-0">
            </div>
        </div>
    </div>
</section>
<script type="text/template">
    
</script>
<script>
var live_location = "{{ URL::asset('/images/live_location.gif') }}";
var autocomplete_urls = "{{url('api/v1/pickup-delivery/get-list-of-vehicles/18?page=1&limit=12')}}";
</script>
@endsection