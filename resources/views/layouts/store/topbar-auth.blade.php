<div class="top-header">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="header-contact">
                    <ul>
                        <li>Welcome to Our store {{session('client_config')->company_name}}</li>
                        <li><i class="fa fa-phone" aria-hidden="true"></i>Call Us: {{session('client_config')->phone_number}}</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 header-contact text-center">
                <!-- <ul>
                  <li class="text-center"><a href="#"><i class="fa fa-map-marker" aria-hidden="true"></i></a></li>
                </ul> -->
                @if( (session('deliveryAddress')) && (Route::currentRouteName() != 'userHome') )
                <div class="row no-gutters" id="location_search_wrapper">
                    <div class="col-lg-12 col-md-12 col">
                        <div class="d-flex align-items-center justify-content-start px-3 dropdown-toggle" id="dropdownLocationButton" data-toggle="dropdown" aria-haspopup="true" 
                        aria-expanded="false">
                            <div class="map-icon mr-1"><i class="fa fa-map-marker" aria-hidden="true"></i></div>
                            <div class="homepage-address text-left">
                                <h2><span data-placement="top" data-toggle="tooltip" title="{{session('deliveryAddress')}}">{{session('deliveryAddress')}}</span></h2>
                            </div>
                            <div class="down-icon">
                                <i class="fa fa-angle-down" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div class="dropdown-menu p-0" aria-labelledby="dropdownLocationButton" style="max-width:400px;width:100%">
                            <div id="address-map-container">
                                <div id="address-map"></div>
                            </div>
                            <div class="delivery_address p-2 position-relative">
                                <div class="modal-title">Set your delivery location</div>
                                <button type="button" class="close edit-close position-absolute" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <div class="form-group">
                                    <label class="delivery-head">DELIVERY AREA</label>
                                    <!--<div class="select_address border d-flex align-items-center justify-content-between ">
                                        <div class="location-area">
                                            <i class="fa fa-check-circle-o" aria-hidden="true"></i>
                                            <span>Sector 28 C, Chandigarh, India</span>
                                        </div>   
                                        <label class="m-0 text-uppercase">Change</label>
                                    </div>-->
                                    <div class="address-input-field d-flex align-items-center justify-content-between">
                                        <i class="fa fa-map-marker" aria-hidden="true"></i>
                                        <input class="form-control border-0 map-input" type="text" name="address-input" id="address-input" value="{{session('deliveryAddress')}}">
                                        <input type="hidden" name="address_latitude" id="address-latitude" value="{{session('latitude')}}" />
                                        <input type="hidden" name="address_longitude" id="address-longitude" value="{{session('longitude')}}" />
                                    </div>
                                    <!--<div class="edit-area">
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
                                    </div>-->
                                </div>
                                <div class="text-center">
                                    <button type="button" class="btn btn-solid ml-auto confirm_address_btn w-100">Confirm And Proceed</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="col-lg-4 text-right">
                <ul class="header-dropdown">
                    <li class="mobile-wishlist"><a href="#"><i class="fa fa-heart" aria-hidden="true"></i></a>
                    </li>
                    <li class="onhover-dropdown mobile-account"> <i class="fa fa-user" aria-hidden="true"></i>
                        My Account
                        <ul class="onhover-show-div">
                            @if(Auth::user()->is_superadmin == 1 || Auth::user()->is_admin == 1)
                            <li><a href="{{route('client.dashboard')}}" data-lng="en">Dashboard</a></li>
                            @endif
                            <li><a href="{{route('user.profile')}}" data-lng="en">Profile</a></li>
                            <li><a href="{{route('user.logout')}}" data-lng="es">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>