@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Configurations'])
@section('css')
@endsection
@section('content')


@php
$sms_crendential = json_decode($preference->sms_credentials);
@endphp

<div class="container-fluid custom-toggle">
   <div class="row">
      <div class="col-12">
         <div class="page-title-box">
            <h4 class="page-title text-uppercase">{{ __('Configurations') }}</h4>
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-12">
         <div class="text-sm-left">
            @if (\Session::has('success'))
            <div class="alert alert-success">
               <span>{!! \Session::get('success') !!}</span>
            </div>
            @elseif(\Session::has('error'))
            <div class="alert alert-danger">
               <span>{!! \Session::get('error') !!}</span>
            </div>
            @endif
         </div>
      </div>
   </div>
   <form method="POST" action="{{route('configure.update', Auth::user()->code)}}">
      @csrf
      <div class="row">
         @if($client_preference_detail->business_type != 'taxi')
         <div class="col-lg-3 col-md-6 mb-3">
            <div class="row h-100">
               <div class="col-12">
                  <div class="card-box h-100">
                     <div class="d-flex align-items-center justify-content-between mb-2">
                        <h4 class="header-title mb-0">{{ __("Hyperlocal") }}</h4>
                        <button class="btn btn-info d-block" type="submit"> {{ __("Save") }} </button>
                     </div>
                     <p class="sub-header">{{ __("Enable location based visibility of Vendors and set the Default Location.") }}</p>
                     <input type="hidden" name="hyperlocals" id="hyperlocals" value="1">

                     <div class="row">
                        <div class="col-12">
                           <div class="form-group mb-0">
                              <label for="is_hyperlocal" class="mr-3">{{ __("Enable") }}</label>
                              <input type="checkbox" data-plugin="switchery" name="is_hyperlocal" id="is_hyperlocal" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->is_hyperlocal == '1')) checked @endif>
                           </div>
                           <div class="row">
                              <div class="col-12 mt-3 disableHyperLocal" style="{{((isset($preference) && $preference->is_hyperlocal == '1')) ? '' : 'display:none;'}}">
                                 <div class="row">
                                    <div class="col-12">
                                       <div class="form-group mb-0">
                                          <label for="Default_location_name">{{ __("Default Location") }}</label>
                                          <div class="input-group">
                                             <input type="text" name="Default_location_name" id="Default_location_name" placeholder="Delhi, India" class="form-control" value="{{ old('Default_location_name', $preference->Default_location_name ?? '')}}">
                                             <div class="input-group-append">
                                                <button class="btn btn-xs btn-dark waves-effect waves-light showMap" type="button" num="add1"> <i class="mdi mdi-map-marker-radius"></i></button>
                                             </div>
                                          </div>
                                          @if($errors->has('Default_location_name'))
                                          <span class="text-danger" role="alert">
                                             <strong>{{ $errors->first('Default_location_name') }}</strong>
                                          </span>
                                          @endif
                                       </div>
                                       <div class="form-group mt-3 mb-0">
                                          <label for="Default_latitude">{{ __("Latitude") }}</label>
                                          <input type="text" name="Default_latitude" id="Default_latitude" placeholder="24.9876755" class="form-control" value="{{ old('Default_latitude', $preference->Default_latitude ?? '')}}">
                                          @if($errors->has('Default_latitude'))
                                          <span class="text-danger" role="alert">
                                             <strong>{{ $errors->first('Default_latitude') }}</strong>
                                          </span>
                                          @endif
                                       </div>
                                       <div class="form-group mt-3 mb-0">
                                          <label for="Default_longitude">{{ __("Longitude") }}</label>
                                          <input type="text" name="Default_longitude" id="Default_longitude" placeholder="11.9871371723" class="form-control" value="{{ old('Default_longitude', $preference->Default_longitude ?? '')}}">
                                          @if($errors->has('Default_longitude'))
                                          <span class="text-danger" role="alert">
                                             <strong>{{ $errors->first('Default_longitude') }}</strong>
                                          </span>
                                          @endif
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
         @endif

         @if($client_preference_detail->business_type != 'taxi' && $client_preference_detail->business_type != 'laundry')
         <div class="col-lg-3 col-md-6 mb-3">
            <div class="card-box h-100">
               <div class="d-flex align-items-center justify-content-between mb-2">
                  <h4 class="header-title mb-0">{{ __("Last Mile Delivery") }}</h4>
                  <button class="btn btn-info d-block" type="submit" name="last_mile_submit_btn" value ="1"> {{ __("Save") }} </button>
               </div>
               <p class="sub-header">{{ __("Offer Last Mile Delivery with Dispatcher.") }}</p>
               <div class="row">
                  <div class="col-12">
                     <div class="form-group mb-0">
                        <div class="form-group mb-0 switchery-demo">
                           <label for="need_delivery_service" class="mr-3">{{ __("Enable") }}</label>
                           <input data-plugin="switchery" name="need_delivery_service" id="need_delivery_service" class="form-control" data-color="#43bee1" type="checkbox" @if((isset($preference) && $preference->need_delivery_service == '1')) checked @endif >
                        </div>
                     </div>

                     <div class="form-group mt-3 mb-0 deliveryServiceFields" style="{{((isset($preference) && $preference->need_delivery_service == '1')) ? '' : 'display:none;'}}">
                        <label for="delivery_service_key_url">{{ __("Dispatcher URL") }} * ( https://www.abc.com )</label>
                        <input type="text" name="delivery_service_key_url" id="delivery_service_key_url" placeholder="https://www.abc.com" class="form-control" value="{{ old('delivery_service_key_url', $preference->delivery_service_key_url ?? '')}}">
                        @if($errors->has('delivery_service_key_url'))
                        <span class="text-danger" role="alert">
                           <strong>{{ $errors->first('delivery_service_key_url') }}</strong>
                        </span>
                        @endif
                     </div>
                     <div class="form-group mt-3 mb-0 deliveryServiceFields" style="{{((isset($preference) && $preference->need_delivery_service == '1')) ? '' : 'display:none;'}}">
                        <label for="delivery_service_key_code">{{ __("Dispatcher Short code") }}</label>
                        <input type="text" name="delivery_service_key_code" id="delivery_service_key_code" placeholder="" class="form-control" value="{{ old('delivery_service_key_code', $preference->delivery_service_key_code ?? '')}}">
                        @if($errors->has('delivery_service_key_code'))
                        <span class="text-danger" role="alert">
                           <strong>{{ $errors->first('delivery_service_key_code') }}</strong>
                        </span>
                        @endif
                     </div>
                     <div class="form-group mt-3 mb-0 deliveryServiceFields" style="{{((isset($preference) && $preference->need_delivery_service == '1')) ? '' : 'display:none;'}}">
                        <label for="delivery_service_key">{{ __("Dispatcher API key") }}</label>
                        <input type="text" name="delivery_service_key" id="delivery_service_key" placeholder="" class="form-control" value="{{ old('delivery_service_key', $preference->delivery_service_key ?? '')}}">
                        @if($errors->has('delivery_service_key'))
                        <span class="text-danger" role="alert">
                           <strong>{{ $errors->first('delivery_service_key') }}</strong>
                        </span>
                        @endif
                     </div>

                     @if($last_mile_teams != null && count($last_mile_teams))
                     <div class="form-group mt-3 mb-0" style="{{(isset($preference) && $preference->need_delivery_service == '1') ? '' : 'display: none;'}}" id="lastMileTeamListDiv">
                        <div class="form-group">
                            {!! Form::label('title', __('Team Tag For Last Mile'),['class' => 'control-label']) !!}
                            <select class="form-control" id="lastMileTeamList" name="last_mile_team" data-toggle="select2" >
                              <option value="0">{{__('Select Team Tag')}}</option>
                              @foreach($last_mile_teams as $nm)
                                 <option value="{{$nm['name']}}" @if($preference->last_mile_team == $nm['name']) selected="selected" @endif>{{$nm['name']}}</option>
                              @endforeach

                            </select>
                        </div>
                     </div>
                     @endif


                  </div>
               </div>
            </div>
         </div>
         @endif

         @if($client_preference_detail->business_type != 'taxi' && $client_preference_detail->business_type != 'food_grocery_ecommerce' && $client_preference_detail->business_type != 'laundry')
         <div class="col-lg-3 col-md-6 mb-3">
            <div class="card-box h-100">
               <div class="d-flex align-items-center justify-content-between mb-2">
                  <h4 class="header-title mb-0">{{ __('On Demand Services') }}</h4>
                  <button class="btn btn-info d-block" type="submit"  name="need_dispacher_home_other_service_submit_btn" value ="1"> {{ __("Save") }} </button>
               </div>
               <p class="sub-header">{{ __('Offer On Demand Services with Dispatcher.') }}</p>
               <div class="row">
                  <div class="col-12">
                     <div class="form-group mb-0">
                        <div class="form-group mb-0 switchery-demo">
                           <label for="need_dispacher_home_other_service" class="mr-3">{{ __('Enable') }}</label>
                           <input type="checkbox" data-plugin="switchery" name="need_dispacher_home_other_service" id="need_dispacher_home_other_service" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->need_dispacher_home_other_service == '1')) checked='checked' @endif>
                        </div>
                     </div>

                     <div class="form-group mt-3 mb-0 home_other_dispatcherFields" style="{{((isset($preference) && $preference->need_dispacher_home_other_service == '1')) ? '' : 'display:none;'}}">
                        <label for="dispacher_home_other_service_key_url">{{ __('Dispatcher URL') }} *(https://www.abc.com)</label>
                        <input type="text" name="dispacher_home_other_service_key_url" id="dispacher_home_other_service_key_url" placeholder="https://www.abc.com" class="form-control" value="{{ old('dispacher_home_other_service_key_url', $preference->dispacher_home_other_service_key_url ?? '')}}">
                        @if($errors->has('dispacher_home_other_service_key_url'))
                        <span class="text-danger" role="alert">
                           <strong>{{ $errors->first('dispacher_home_other_service_key_url') }}</strong>
                        </span>
                        @endif
                     </div>

                     <div class="form-group mt-3 mb-0 home_other_dispatcherFields" style="{{((isset($preference) && $preference->need_dispacher_home_other_service == '1')) ? '' : 'display:none;'}}">
                        <label for="dispacher_home_other_service_key_code">{{ __('Dispatcher Short code') }}</label>
                        <input type="text" name="dispacher_home_other_service_key_code" id="dispacher_home_other_service_key_code" placeholder="" class="form-control" value="{{ old('dispacher_home_other_service_key_code', $preference->dispacher_home_other_service_key_code ?? '')}}">
                        @if($errors->has('dispacher_home_other_service_key_code'))
                        <span class="text-danger" role="alert">
                           <strong>{{ $errors->first('dispacher_home_other_service_key_code') }}</strong>
                        </span>
                        @endif
                     </div>

                     <div class="form-group mt-3 mb-0 home_other_dispatcherFields" style="{{((isset($preference) && $preference->need_dispacher_home_other_service == '1')) ? '' : 'display:none;'}}">
                        <label for="dispacher_home_other_service_key">{{ __('Dispatcher API key') }}</label>
                        <input type="text" name="dispacher_home_other_service_key" id="dispacher_home_other_service_key" placeholder="" class="form-control" value="{{ old('dispacher_home_other_service_key', $preference->dispacher_home_other_service_key ?? '')}}">
                        @if($errors->has('dispacher_home_other_service_key'))
                        <span class="text-danger" role="alert">
                           <strong>{{ $errors->first('dispacher_home_other_service_key') }}</strong>
                        </span>
                        @endif
                     </div>

                  </div>
               </div>
            </div>
         </div>
         @endif

         @if($client_preference_detail->business_type == 'taxi' || $client_preference_detail->business_type == '' || $client_preference_detail->business_type == 'super_app' )
         <div class="col-lg-3 col-md-6 mb-3">
            <div class="card-box h-100">
               <div class="d-flex align-items-center justify-content-between mb-2">
                  <h4 class="header-title mb-0">{{ __("Pickup & Delivery") }}</h4>
                  <button class="btn btn-info d-block" type="submit"  name="need_dispacher_ride_submit_btn" value ="1"> {{ __("Save") }} </button>
               </div>
               <p class="sub-header">{{ __("Offer Pickup & Delivery with Dispatcher.") }}</p>
               <div class="row">
                  <div class="col-12">
                     <div class="form-group mb-0">
                        <div class="form-group mb-0 switchery-demo">
                           <label for="need_dispacher_ride" class="mr-3">{{ __("Enable") }}</label>
                           <input type="checkbox" data-plugin="switchery" name="need_dispacher_ride" id="need_dispacher_ride" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->need_dispacher_ride == '1')) checked='checked' @endif>
                        </div>
                     </div>

                     <div class="form-group mt-3 mb-0 dispatcherFields" style="{{((isset($preference) && $preference->need_dispacher_ride == '1')) ? '' : 'display:none;'}}">
                        <label for="pickup_delivery_service_key_url">{{ __("Dispatcher URL") }} * ( https://www.abc.com )</label>
                        <input type="text" name="pickup_delivery_service_key_url" id="pickup_delivery_service_key_url" placeholder="https://www.abc.com" class="form-control" value="{{ old('pickup_delivery_service_key_url', $preference->pickup_delivery_service_key_url ?? '')}}">
                        @if($errors->has('pickup_delivery_service_key_url'))
                        <span class="text-danger" role="alert">
                           <strong>{{ $errors->first('pickup_delivery_service_key_url') }}</strong>
                        </span>
                        @endif
                     </div>

                     <div class="form-group mt-3 mb-0 dispatcherFields" style="{{((isset($preference) && $preference->need_dispacher_ride == '1')) ? '' : 'display:none;'}}">
                        <label for="delivery_service_key_code">{{ __("Dispatcher Short code") }}</label>
                        <input type="text" name="pickup_delivery_service_key_code" id="pickup_delivery_service_key_code" placeholder="" class="form-control" value="{{ old('pickup_delivery_service_key_code', $preference->pickup_delivery_service_key_code ?? '')}}">
                        @if($errors->has('pickup_delivery_service_key_code'))
                        <span class="text-danger" role="alert">
                           <strong>{{ $errors->first('pickup_delivery_service_key_code') }}</strong>
                        </span>
                        @endif
                     </div>

                     <div class="form-group mt-3 mb-0 dispatcherFields" style="{{((isset($preference) && $preference->need_dispacher_ride == '1')) ? '' : 'display:none;'}}">
                        <label for="pickup_delivery_service_key">{{ __("Dispatcher API key") }}</label>
                        <input type="text" name="pickup_delivery_service_key" id="pickup_delivery_service_key" placeholder="" class="form-control" value="{{ old('pickup_delivery_service_key', $preference->pickup_delivery_service_key ?? '')}}">
                        @if($errors->has('pickup_delivery_service_key'))
                        <span class="text-danger" role="alert">
                           <strong>{{ $errors->first('pickup_delivery_service_key') }}</strong>
                        </span>
                        @endif
                     </div>

                  </div>
               </div>
            </div>
         </div>
         @endif

         @if($client_preference_detail->business_type == 'laundry')
         <div class="col-lg-3 col-md-6 mb-3">
            <div class="card-box h-100">
               <div class="d-flex align-items-center justify-content-between mb-2">
                  <h4 class="header-title mb-0">{{ __("Laundry") }}</h4>
                  <button class="btn btn-info d-block" type="submit" name="laundry_submit_btn" value ="1"> {{ __("Save") }} </button>
               </div>
               <p class="sub-header">{{ __("Offer laundry with Dispatcher.") }}</p>
               <div class="row">
                  <div class="col-12">
                     <div class="form-group mb-0">
                        <div class="form-group mb-0 switchery-demo">
                           <label for="need_laundry_service" class="mr-3">{{ __("Enable") }}</label>
                           <input data-plugin="switchery" name="need_laundry_service" id="need_laundry_service" class="form-control" data-color="#43bee1" type="checkbox" @if((isset($preference) && $preference->need_laundry_service == '1')) checked @endif >
                        </div>
                     </div>

                     <div class="form-group mt-3 mb-0 laundryServiceFields" style="{{((isset($preference) && $preference->need_laundry_service == '1')) ? '' : 'display:none;'}}">
                        <label for="laundry_service_key_url">{{ __("Dispatcher URL") }} * ( https://www.abc.com )</label>
                        <input type="text" name="laundry_service_key_url" id="laundry_service_key_url" placeholder="https://www.abc.com" class="form-control" value="{{ old('laundry_service_key_url', $preference->laundry_service_key_url ?? '')}}">
                        @if($errors->has('laundry_service_key_url'))
                        <span class="text-danger" role="alert">
                           <strong>{{ $errors->first('laundry_service_key_url') }}</strong>
                        </span>
                        @endif
                     </div>
                     <div class="form-group mt-3 mb-0 laundryServiceFields" style="{{((isset($preference) && $preference->need_laundry_service == '1')) ? '' : 'display:none;'}}">
                        <label for="laundry_service_key_code">{{ __("Dispatcher Short code") }}</label>
                        <input type="text" name="laundry_service_key_code" id="laundry_service_key_code" placeholder="" class="form-control" value="{{ old('laundry_service_key_code', $preference->laundry_service_key_code ?? '')}}">
                        @if($errors->has('laundry_service_key_code'))
                        <span class="text-danger" role="alert">
                           <strong>{{ $errors->first('laundry_service_key_code') }}</strong>
                        </span>
                        @endif
                     </div>
                     <div class="form-group mt-3 mb-0 laundryServiceFields" style="{{((isset($preference) && $preference->need_laundry_service == '1')) ? '' : 'display:none;'}}">
                        <label for="laundry_service_key">{{ __("Dispatcher API key") }}</label>
                        <input type="text" name="laundry_service_key" id="laundry_service_key" placeholder="" class="form-control" value="{{ old('laundry_service_key', $preference->laundry_service_key ?? '')}}">
                        @if($errors->has('laundry_service_key'))
                        <span class="text-danger" role="alert">
                           <strong>{{ $errors->first('laundry_service_key') }}</strong>
                        </span>
                        @endif
                     </div>

                     @if($laundry_teams != null && count($laundry_teams))
                     <div class="form-group mt-3 mb-0 laundryServiceFields" style="{{(isset($preference) && $preference->need_laundry_service == '1') ? '' : 'display: none;'}}" id="laundryPickupTeamListDiv">
                        <div class="form-group">
                            {!! Form::label('title', __('Team Tag For Laundry Pickup'),['class' => 'control-label']) !!}
                            <select class="form-control" id="laundryPickupTeamList" name="laundry_pickup_team" data-toggle="select2" >
                              <option value="0">{{__('Select Team Tag')}}</option>
                              @foreach($laundry_teams as $nm)
                                 <option value="{{$nm['name']}}" @if($preference->laundry_pickup_team == $nm['name']) selected="selected" @endif>{{$nm['name']}}</option>
                              @endforeach

                            </select>
                        </div>
                     </div>

                     <div class="form-group mt-3 mb-0 laundryServiceFields" style="{{(isset($preference) && $preference->need_laundry_service == '1') ? '' : 'display: none;'}}" id="laundryDropoffTeamListDiv">
                        <div class="form-group">
                            {!! Form::label('title', __('Team Tag For Laundry Dropoff'),['class' => 'control-label']) !!}
                            <select class="form-control" id="laundryDropoffTeamList" name="laundry_dropoff_team" data-toggle="select2" >
                              <option value="0">{{__('Select Team Tag')}}</option>
                              @foreach($laundry_teams as $nm)
                                 <option value="{{$nm['name']}}" @if($preference->laundry_dropoff_team == $nm['name']) selected="selected" @endif>{{$nm['name']}}</option>
                              @endforeach

                            </select>
                        </div>
                     </div>
                     @endif


                  </div>
               </div>
            </div>
         </div>
         @endif

         <div class="col-lg-3 col-md-6 mb-3">
            <div class="row h-100">
               <div class="col-12">
                  <div class="card-box h-100">
                     <div class="d-flex align-items-center justify-content-between mb-2">
                        <h4 class="header-title mb-0">{{ __("Customer Support") }}</h4>
                        <button class="btn btn-info d-block" type="submit"> {{ __("Save") }} </button>
                     </div>
                     <p class="sub-header">{{ __("View and update your Customer Support, it's API key and Application ID") }}</p>
                     <div class="row">
                        <div class="col-12">
                           <div class="form-group mb-0">
                              <label for="customer_support">{{ __("Customer Support") }}</label>
                              <select class="form-control" id="customer_support" name="customer_support">
                                 <option value="zen_desk" {{ isset($preference) && $preference->customer_support == 'zen_desk' ? 'selected' : '' }}>
                                    {{__('Zen Desk')}}
                                </option>
                              </select>
                              @if($errors->has('customer_support'))
                              <span class="text-danger" role="alert">
                                 <strong>{{ $errors->first('customer_support') }}</strong>
                              </span>
                              @endif
                           </div>

                           <div class="form-group mt-3 mb-0">
                              <label for="customer_support_key">{{ __("API Key") }}</label>
                              <input type="text" name="customer_support_key" id="customer_support_key" placeholder="Please enter key" class="form-control" value="{{ old('customer_support_key', $preference->customer_support_key ?? '')}}">
                              @if($errors->has('customer_support_key'))
                              <span class="text-danger" role="alert">
                                 <strong>{{ $errors->first('customer_support_key') }}</strong>
                              </span>
                              @endif
                           </div>

                           <div class="form-group mt-3 mb-0">
                              <label for="customer_support_application_id">{{ __("Application ID") }}</label>
                              <input type="text" name="customer_support_application_id" id="customer_support_application_id" placeholder="Please enter application ID" class="form-control" value="{{ old('customer_support_application_id', $preference->customer_support_application_id ?? '')}}">
                              @if($errors->has('customer_support_application_id'))
                              <span class="text-danger" role="alert">
                                 <strong>{{ $errors->first('customer_support_application_id') }}</strong>
                              </span>
                              @endif
                           </div>

                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </form>
   <div class="row">
      <div class="col-12">
         <div class="page-title-box">
            <h4 class="page-title text-uppercase">{{ __("Social Logins") }}</h4>
         </div>
      </div>
   </div>
   <form method="POST" action="{{route('configure.update', Auth::user()->code)}}">
      <input type="hidden" name="social_login" id="social_login" value="1">
      @csrf
      <div class="row">
         <div class="col-xl-3 col-lg-6 mb-xl-0 mb-3">
            <div class="card-box h-100">
               <div class="row">
                  <div class="col-12">
                     <div class="form-group mb-0 switchery-demo">
                        <label for="fb_login" class="d-flex align-items-center justify-content-between">
                           <h5 class="social_head"><i class="fab fa-facebook-f"></i> <span>{{ __("Facebook") }}</span></h5>
                           <button class="btn btn-info btn-block save_btn" type="submit"> {{ __("Save") }} </button>
                        </label>
                        <label for="" class="mr-3">{{ __("Enable") }}</label>
                        <input type="checkbox" data-plugin="switchery" name="fb_login" id="fb_login" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->fb_login == '1')) checked='checked' @endif>
                     </div>
                  </div>
               </div>
               <div class="row fb_row" style="{{((isset($preference) && $preference->fb_login == '1')) ? '' : 'display:none;'}}">
                  <div class="col-12">
                     <div class="form-group mb-2 mt-2">
                        <label for="fb_client_id">{{ __("Facebook Client Key") }}</label>
                        <input type="text" name="fb_client_id" id="fb_client_id" placeholder="" class="form-control" value="{{ old('fb_client_id', $preference->fb_client_id ?? '')}}">
                        @if($errors->has('fb_client_id'))
                        <span class="text-danger" role="alert">
                           <strong>{{ $errors->first('fb_client_id') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-12">
                     <div class="form-group mb-2">
                        <label for="fb_client_secret">{{ __("Facebook Client Secret") }}</label>
                        <input type="password" name="fb_client_secret" id="fb_client_secret" placeholder="" class="form-control" value="{{ old('fb_client_secret', $preference->fb_client_secret ?? '')}}">
                        @if($errors->has('fb_client_secret'))
                        <span class="text-danger" role="alert">
                           <strong>{{ $errors->first('fb_client_secret') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-12">
                     <div class="form-group mb-0">
                        <label for="fb_client_url">{{ __("Facebook Redirect URL") }}</label>
                        <input type="text" name="fb_client_url" id="fb_client_url" placeholder="" class="form-control" value="{{ old('fb_client_url', $preference->fb_client_url ?? '')}}">
                        @if($errors->has('fb_client_url'))
                        <span class="text-danger" role="alert">
                           <strong>{{ $errors->first('fb_client_url') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-xl-3 col-lg-6 mb-xl-0 mb-3">
            <div class="card-box h-100">
               <div class="row">
                  <div class="col-12">
                     <div class="form-group mb-0 switchery-demo">
                        <label for="twitter_login" class="d-flex align-items-center justify-content-between">
                           <h5 class="social_head"><i class="fab fa-twitter"></i> <span>Twitter</span></h5>
                           <button class="btn btn-info btn-block save_btn" type="submit"> {{ __("Save") }} </button>
                        </label>
                        <label for="" class="mr-3">{{ __("Enable") }}</label>
                        <input type="checkbox" data-plugin="switchery" name="twitter_login" id="twitter_login" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->twitter_login == '1')) checked='checked' @endif>
                     </div>
                  </div>
               </div>
               <div class="row  twitter_row" style="{{((isset($preference) && $preference->twitter_login == '1')) ? '' : 'display:none;'}}">
                  <div class="col-12">
                     <div class="form-group mb-2 mt-2">
                        <label for="twitter_client_id"></label>{{ __("Twitter Client Key") }}</label>
                        <label for="" class="mr-3">{{ __("Enable") }}</label>
                        <input type="text" name="twitter_client_id" id="twitter_client_id" placeholder="" class="form-control" value="{{ old('twitter_client_id', $preference->twitter_client_id ?? '')}}">
                        @if($errors->has('twitter_client_id'))
                        <span class="text-danger" role="alert">
                           <strong>{{ $errors->first('twitter_client_id') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-12">
                     <div class="form-group mb-2">
                        <label for="twitter_client_secret">{{ __("Twitter Client Secret") }}</label>
                        <input type="password" name="twitter_client_secret" id="twitter_client_secret" placeholder="" class="form-control" value="{{ old('twitter_client_secret', $preference->twitter_client_secret ?? '')}}">
                        @if($errors->has('twitter_client_secret'))
                        <span class="text-danger" role="alert">
                           <strong>{{ $errors->first('twitter_client_secret') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-12">
                     <div class="form-group mb-0">
                        <label for="twitter_client_url">{{ __("Twitter Redirect URL") }}</label>
                        <input type="text" name="twitter_client_url" id="twitter_client_url" placeholder="" class="form-control" value="{{ old('twitter_client_url', $preference->twitter_client_url ?? '')}}">
                        @if($errors->has('twitter_client_url'))
                        <span class="text-danger" role="alert">
                           <strong>{{ $errors->first('twitter_client_url') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-xl-3 col-lg-6 mb-xl-0 mb-3">
            <div class="card-box h-100">
               <div class="row">
                  <div class="col-12">
                     <div class="form-group mb-0 switchery-demo">
                        <label for="google_login" class="d-flex align-items-center justify-content-between">
                           <h5 class="social_head"><i class="fab fa-google"></i> <span>Google</span></h5>
                           <button class="btn btn-info btn-block save_btn" type="submit"> {{ __("Save") }} </button>
                        </label>
                        <label for="" class="mr-3">{{ __("Enable") }}</label>
                        <input type="checkbox" data-plugin="switchery" name="google_login" id="google_login" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->google_login == '1')) checked='checked' @endif>
                     </div>
                  </div>
               </div>
               <div class="row google_row" style="{{((isset($preference) && $preference->google_login == '1')) ? '' : 'display:none;'}}">
                  <div class="col-md-12">
                     <div class="form-group mb-2 mt-2">
                        <label for="google_client_id">Google {{ __("Client Key") }}</label>
                        <input type="text" name="google_client_id" id="google_client_id" placeholder="" class="form-control" value="{{ old('google_client_id', $preference->google_client_id ?? '')}}">
                        @if($errors->has('google_client_id'))
                        <span class="text-danger" role="alert">
                           <strong>{{ $errors->first('google_client_id') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="form-group mb-2">
                        <label for="google_client_secret">Google {{ __("Client Secret") }}</label>
                        <input type="password" name="google_client_secret" id="google_client_secret" placeholder="" class="form-control" value="{{ old('google_client_secret', $preference->google_client_secret ?? '')}}">
                        @if($errors->has('google_client_secret'))
                        <span class="text-danger" role="alert">
                           <strong>{{ $errors->first('google_client_secret') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="form-group mb-0">
                        <label for="google_client_url">Google {{ __("Redirect URL") }}</label>
                        <input type="text" name="google_client_url" id="google_client_url" placeholder="" class="form-control" value="{{ old('google_client_url', $preference->google_client_url ?? '')}}">
                        @if($errors->has('google_client_url'))
                        <span class="text-danger" role="alert">
                           <strong>{{ $errors->first('google_client_url') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-xl-3 col-lg-6 mb-xl-0 mb-3">
            <div class="card-box h-100">
               <div class="row">
                  <div class="col-12">
                     <div class="form-group mb-0 switchery-demo">
                        <label for="apple_login" class="d-flex align-items-center justify-content-between">
                           <h5 class="social_head"><i class="fab fa-apple"></i> <span>Apple</span></h5>
                           <button class="btn btn-info btn-block save_btn" type="submit"> {{ __("Save") }} </button>
                        </label>
                        <label for="" class="mr-3">{{ __("Enable") }}</label>
                        <input type="checkbox" data-plugin="switchery" name="apple_login" id="apple_login" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->apple_login == '1')) checked='checked' @endif>
                     </div>
                  </div>
               </div>
               <div class="row apple_row" style="{{((isset($preference) && $preference->apple_login == '1')) ? '' : 'display:none;'}}">
                  <div class="col-12">
                     <div class="form-group mb-2 mt-2">
                        <label for="apple_client_id">Apple {{ __("Client Key") }}</label>
                        <input type="text" name="apple_client_id" id="apple_client_id" placeholder="" class="form-control" value="{{ old('apple_client_id', $preference->apple_client_id ?? '')}}">
                        @if($errors->has('apple_client_id'))
                        <span class="text-danger" role="alert">
                           <strong>{{ $errors->first('apple_client_id') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-12">
                     <div class="form-group mb-2">
                        <label for="apple_client_secret">Apple {{ __("Client Secret") }}</label>
                        <input type="password" name="apple_client_secret" id="apple_client_secret" placeholder="" class="form-control" value="{{ old('apple_client_secret', $preference->apple_client_secret ?? '')}}">
                        @if($errors->has('apple_client_secret'))
                        <span class="text-danger" role="alert">
                           <strong>{{ $errors->first('apple_client_secret') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-12">
                     <div class="form-group mb-0">
                        <label for="apple_client_url"> Apple {{ __("Redirect URL") }}</label>
                        <input type="text" name="apple_client_url" id="apple_client_url" placeholder="" class="form-control" value="{{ old('apple_client_url', $preference->apple_client_url ?? '')}}">
                        @if($errors->has('apple_client_url'))
                        <span class="text-danger" role="alert">
                           <strong>{{ $errors->first('apple_client_url') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </form>
   <div class="row">
      <div class="col-12">
         <div class="page-title-box">
            <h4 class="page-title text-uppercase">{{ __("Map Sms Emails") }}</h4>
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-xl-3 mb-3">
         <form class="h-100" method="POST" action="{{route('configure.update', Auth::user()->code)}}">
            @csrf
            <div class="card-box h-100 h-100">
               <h4 class="header-title text-uppercase text-uppercase">{{ __("Map Configuration") }}</h4>
               <p class="sub-header">
                  {{ __("View and update your Map type and it's API key.") }}
               </p>
               <div class="row">
                  <div class="col-12">
                     <div class="form-group mb-2">
                        <label for="currency">{{ __("MAP PROVIDER") }}</label>
                        <select class="form-control" id="map_provider" name="map_provider">
                           @foreach($mapTypes as $map)
                           <option value="{{$map->id}}" {{ (isset($preference) && $preference->map_provider == $map->id)? "selected" : "" }}> {{$map->provider}} </option>
                           @endforeach
                        </select>
                        @if($errors->has('map_provider'))
                        <span class="text-danger" role="alert">
                           <strong>{{ $errors->first('map_provider') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-12">
                     <div class="form-group mb-2">
                        <label for="map_key">{{ __("API KEY") }}</label>
                        <input type="password" name="map_key" id="map_key" placeholder="" class="form-control" value="{{ old('map_key', $preference->map_key ?? '')}}">
                        @if($errors->has('map_key'))
                        <span class="text-danger" role="alert">
                           <strong>{{ $errors->first('map_key') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-md-2 mt-3">
                     <div class="form-group mb-0 text-center">
                        <button class="btn btn-info btn-block" type="submit"> {{ __("Save") }} </button>
                     </div>
                  </div>
               </div>
            </div>
         </form>
      </div>
      <div class="col-xl-3 mb-3">
         <form class="h-100" method="POST" action="{{route('configure.update', Auth::user()->code)}}">
            @csrf
            <div class="card-box h-100 h-100">
               <h4 class="header-title text-uppercase">{{ __("SMS Configuration") }}</h4>
               <p class="sub-header">{{ __("View and update your SMS Gateway and it's API keys.") }}</p>
               <div class="row mb-0">
                  <div class="col-12">
                     <div class="form-group mb-2">
                        <label for="sms_provider">{{ __("SMS PROVIDER") }}</label>
                        <select class="form-control" id="sms_provider" name="sms_provider" onchange="toggle_smsFields(this)">
                           @foreach($smsTypes as $sms)
                           <option data-id="{{$sms->keyword}}_fields" value="{{$sms->id}}" {{ (isset($preference) && $preference->sms_provider == $sms->id)? "selected" : "" }} > {{$sms->provider}} </option>
                           @endforeach
                        </select>
                        @if($errors->has('sms_provider'))
                        <span class="text-danger" role="alert">
                           <strong>{{ $errors->first('sms_provider') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>

                  <!-- For twillio -->
                  <div class="sms_fields row mx-0" id="twilio_fields" style="display : {{$preference->sms_provider == 1 ? 'flex' : 'none'}};">
                     <div class="col-12">
                        <div class="form-group mb-2">
                           <label for="sms_from">{{ __("SMS From") }}</label>
                           <input type="text" name="sms_from" id="sms_from" placeholder="" class="form-control" value="{{ old('sms_from', $preference->sms_from ?? '')}}">
                           @if($errors->has('sms_from'))
                           <span class="text-danger" role="alert">
                              <strong>{{ $errors->first('sms_from') }}</strong>
                           </span>
                           @endif
                        </div>
                     </div>
                     <div class="col-12">
                        <div class="form-group mb-2">
                           <label for="sms_key">{{ __("API KEY") }}</label>
                           <input type="text" name="sms_key" id="sms_key" placeholder="" class="form-control" value="{{ old('sms_key', $preference->sms_key ?? '')}}">
                           @if($errors->has('sms_key'))
                           <span class="text-danger" role="alert">
                              <strong>{{ $errors->first('sms_key') }}</strong>
                           </span>
                           @endif
                        </div>
                     </div>
                     <div class="col-12">
                        <div class="form-group mb-2">
                           <label for="sms_secret">{{ __("API Secret") }}</label>
                           <input type="password" name="sms_secret" id="sms_secret" placeholder="" class="form-control" value="{{ old('sms_secret', $preference->sms_secret ?? '')}}">
                           @if($errors->has('sms_secret'))
                           <span class="text-danger" role="alert">
                              <strong>{{ $errors->first('sms_secret') }}</strong>
                           </span>
                           @endif
                        </div>
                     </div>
                  </div>


                  <!-- For mTalkz -->
                  <div class="row sms_fields mx-0" id="mTalkz_fields" style="display : {{$preference->sms_provider == 2 ? 'flex' : 'none'}};">
                     <div class="col-12">
                        <div class="form-group mb-2">
                           <label for="mtalkz_api_key">{{ __("API Key") }}</label>
                           <input type="text" name="mtalkz_api_key" id="mtalkz_api_key" placeholder="" class="form-control" value="{{ old('mtalkz_api_key', $sms_crendential->api_key ?? '')}}">
                           @if($errors->has('mtalkz_api_key'))
                           <span class="text-danger" role="alert">
                              <strong>{{ $errors->first('mtalkz_api_key') }}</strong>
                           </span>
                           @endif
                        </div>
                     </div>
                     <div class="col-12">
                        <div class="form-group mb-2">
                           <label for="mtalkz_sender_id">{{ __("Sender ID") }}</label>
                           <input type="text" name="mtalkz_sender_id" id="mtalkz_sender_id" placeholder="" class="form-control" value="{{ old('mtalkz_sender_id', $sms_crendential->sender_id ?? '')}}">
                           @if($errors->has('mtalkz_sender_id'))
                           <span class="text-danger" role="alert">
                              <strong>{{ $errors->first('mtalkz_sender_id') }}</strong>
                           </span>
                           @endif
                        </div>
                     </div>
                  </div>

                  <!-- For mTalkz -->
                  <div class="row sms_fields mx-0" id="mazinhost_fields" style="display : {{$preference->sms_provider == 3 ? 'flex' : 'none'}};">
                     <div class="col-12">
                        <div class="form-group mb-2">
                           <label for="mazinhost_api_key">{{ __("API Key") }}</label>
                           <input type="text" name="mazinhost_api_key" id="mazinhost_api_key" placeholder="" class="form-control" value="{{ old('mazinhost_api_key', $sms_crendential->api_key ?? '')}}">
                           @if($errors->has('mazinhost_api_key'))
                           <span class="text-danger" role="alert">
                              <strong>{{ $errors->first('mazinhost_api_key') }}</strong>
                           </span>
                           @endif
                        </div>
                     </div>
                     <div class="col-12">
                        <div class="form-group mb-2">
                           <label for="mazinhost_sender_id">{{ __("Sender ID") }}</label>
                           <input type="text" name="mazinhost_sender_id" id="mazinhost_sender_id" placeholder="" class="form-control" value="{{ old('mazinhost_sender_id', $sms_crendential->sender_id ?? '')}}">
                           @if($errors->has('mazinhost_sender_id'))
                           <span class="text-danger" role="alert">
                              <strong>{{ $errors->first('mazinhost_sender_id') }}</strong>
                           </span>
                           @endif
                        </div>
                     </div>
                  </div>

                  <!-- For unifonic_fields -->
                  <div class="row sms_fields mx-0" id="unifonic_fields" style="display : {{$preference->sms_provider == 4 ? 'flex' : 'none'}};">
                     <div class="col-12">
                        <div class="form-group mb-2">
                           <label for="unifonic_app_id">{{ __("App Id") }}</label>
                           <input type="text" name="unifonic_app_id" id="unifonic_app_id" placeholder="" class="form-control" value="{{ old('unifonic_app_id', $sms_crendential->unifonic_app_id ?? '')}}">
                           @if($errors->has('unifonic_app_id'))
                           <span class="text-danger" role="alert">
                              <strong>{{ $errors->first('unifonic_app_id') }}</strong>
                           </span>
                           @endif
                        </div>
                     </div>
                     <div class="col-12">
                        <div class="form-group mb-2">
                           <label for="unifonic_account_email">{{ __("Unifonic Account Email") }}</label>
                           <input type="text" name="unifonic_account_email" id="unifonic_account_email" placeholder="" class="form-control" value="{{ old('unifonic_account_email', $sms_crendential->unifonic_account_email ?? '')}}">
                           @if($errors->has('unifonic_account_email'))
                           <span class="text-danger" role="alert">
                              <strong>{{ $errors->first('unifonic_account_email') }}</strong>
                           </span>
                           @endif
                        </div>
                     </div>
                      <div class="col-12">
                        <div class="form-group mb-2">
                           <label for="unifonic_account_password">{{ __("Unifonic Account Password") }}</label>
                           <input type="text" name="unifonic_account_password" id="unifonic_account_password" placeholder="" class="form-control" value="{{ old('unifonic_account_password', $sms_crendential->unifonic_account_password ?? '')}}">
                           @if($errors->has('unifonic_account_password'))
                           <span class="text-danger" role="alert">
                              <strong>{{ $errors->first('unifonic_account_password') }}</strong>
                           </span>
                           @endif
                        </div>
                     </div>
                  </div>

                  <div class="col-md-2 mt-3">
                     <div class="form-group mb-0 text-center">
                        <button class="btn btn-info btn-block" type="submit"> {{ __("Save") }} </button>
                     </div>
                  </div>
               </div>
            </div>
         </form>
      </div>
      <div class="col-xl-6 mb-3">
         <form method="POST" action="{{route('configure.update', Auth::user()->code)}}" class="h-100">
            @csrf
            <div class="card-box h-100 h-100">
               <h4 class="header-title text-uppercase">{{ __("Mail Configuration") }}</h4>
               <p class="sub-header"> {{ __("View and update your SMTP credentials.") }}</p>
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group mb-2">
                        <label for="mail_type">{{ __("Mail Type") }}</label>
                        <input type="text" name="mail_type" id="mail_type" placeholder="SMTP" class="form-control" value="{{ old('mail_type', $preference->mail_type ?? '')}}">
                        @if($errors->has('mail_type'))
                        <span class="text-danger" role="alert">
                           <strong>{{ $errors->first('mail_type') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group mb-2">
                        <label for="mail_driver">{{ __("Mail Driver") }}</label>
                        <input type="text" name="mail_driver" id="mail_driver" placeholder="" class="form-control" value="{{ old('mail_driver', $preference->mail_driver ?? '')}}">
                        @if($errors->has('mail_driver'))
                        <span class="text-danger" role="alert">
                           <strong>{{ $errors->first('mail_driver') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group mb-2">
                        <label for="mail_host">{{ __("Mail Host") }}</label>
                        <input type="text" name="mail_host" id="mail_host" placeholder="SMTP" class="form-control" value="{{ old('mail_host', $preference->mail_host ?? '')}}">
                        @if($errors->has('mail_host'))
                        <span class="text-danger" role="alert">
                           <strong>{{ $errors->first('mail_host') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group mb-2">
                        <label for="mail_port">{{ __("Mail Port") }}</label>
                        <input type="text" name="mail_port" id="mail_port" placeholder="" class="form-control" value="{{ old('mail_port', $preference->mail_port ?? '')}}">
                        @if($errors->has('mail_port'))
                        <span class="text-danger" role="alert">
                           <strong>{{ $errors->first('mail_port') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group mb-2">
                        <label for="mail_username">{{ __("Mail Username") }}</label>
                        <input type="text" name="mail_username" id="mail_username" placeholder="username" class="form-control" value="{{ old('mail_username', $preference->mail_username ?? '')}}">
                        @if($errors->has('mail_username'))
                        <span class="text-danger" role="alert">
                           <strong>{{ $errors->first('mail_username') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group mb-2">
                        <label for="mail_password">{{ __("Mail Password") }}</label>
                        <input type="password" name="mail_password" id="mail_password" placeholder="" class="form-control" value="{{ old('mail_password', $preference->mail_password ?? '')}}">
                        @if($errors->has('mail_password'))
                        <span class="text-danger" role="alert">
                           <strong>{{ $errors->first('mail_password') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group mb-2">
                        <label for="mail_encryption">{{ __("Mail Encryption") }}</label>
                        <input type="text" name="mail_encryption" id="mail_encryption" placeholder="username" class="form-control" value="{{ old('mail_encryption', $preference->mail_encryption ?? '')}}">
                        @if($errors->has('mail_encryption'))
                        <span class="text-danger" role="alert">
                           <strong>{{ $errors->first('mail_encryption') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group mb-2">
                        <label for="mail_from">{{ __("Mail From") }}</label>
                        <input type="text" name="mail_from" id="mail_from" placeholder="service@xyz.com" class="form-control" value="{{ old('mail_from', $preference->mail_from ?? '')}}">
                        @if($errors->has('mail_from'))
                        <span class="text-danger" role="alert">
                           <strong>{{ $errors->first('mail_from') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
               </div>
               <div class="row mb-2 mt-3">
                  <div class="col-md-2">
                     <div class="form-group mb-0 text-center">
                        <button class="btn btn-info d-block" type="submit"> {{ __("Save") }} </button>
                     </div>
                  </div>
               </div>
            </div>
         </form>
      </div>
      <div class="col-xl-6 mb-3">
         <form method="POST" action="{{route('configure.update', Auth::user()->code)}}" class="h-100">
            @csrf
            <div class="card-box h-100 h-100">
               <h4 class="header-title text-uppercase">{{__('Firebase Notification Configuration')}}</h4>
               <p class="sub-header">{{__('View and update your Firebase Keys')}}</p>
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group mb-2">
                        <label for="fcm_server_key">{{__('Server Key')}}</label>
                        <input type="text" name="fcm_server_key" id="fcm_server_key" placeholder="" class="form-control" value="{{ old('fcm_server_key', $preference->fcm_server_key ?? '')}}" required>
                        @if($errors->has('fcm_server_key'))
                        <span class="text-danger" role="alert">
                           <strong>{{ $errors->first('fcm_server_key') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group mb-2">
                        <label for="fcm_api_key">{{__('API Key')}}</label>
                        <input type="text" name="fcm_api_key" id="fcm_api_key" placeholder="" class="form-control" value="{{ old('fcm_api_key', $preference->fcm_api_key ?? '')}}" required>
                        @if($errors->has('fcm_api_key'))
                        <span class="text-danger" role="alert">
                           <strong>{{ $errors->first('fcm_api_key') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group mb-2">
                        <label for="fcm_auth_domain">{{__('Auth Domain')}}</label>
                        <input type="text" name="fcm_auth_domain" id="fcm_auth_domain" placeholder="" class="form-control" value="{{ old('fcm_auth_domain', $preference->fcm_auth_domain ?? '')}}" required>
                        @if($errors->has('fcm_auth_domain'))
                        <span class="text-danger" role="alert">
                           <strong>{{ $errors->first('fcm_auth_domain') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group mb-2">
                        <label for="fcm_project_id">{{__('Project ID')}}</label>
                        <input type="text" name="fcm_project_id" id="fcm_project_id" placeholder="" class="form-control" value="{{ old('fcm_project_id', $preference->fcm_project_id ?? '')}}" required>
                        @if($errors->has('fcm_project_id'))
                        <span class="text-danger" role="alert">
                           <strong>{{ $errors->first('fcm_project_id') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group mb-2">
                        <label for="fcm_storage_bucket">{{__('Storage Bucket')}}</label>
                        <input type="text" name="fcm_storage_bucket" id="fcm_storage_bucket" placeholder="" class="form-control" value="{{ old('fcm_storage_bucket', $preference->fcm_storage_bucket ?? '')}}" required>
                        @if($errors->has('fcm_storage_bucket'))
                        <span class="text-danger" role="alert">
                           <strong>{{ $errors->first('fcm_storage_bucket') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group mb-2">
                        <label for="fcm_messaging_sender_id">{{__('Messaging Sender ID')}}</label>
                        <input type="text" name="fcm_messaging_sender_id" id="fcm_messaging_sender_id" placeholder="" class="form-control" value="{{ old('fcm_messaging_sender_id', $preference->fcm_messaging_sender_id ?? '')}}" required>
                        @if($errors->has('fcm_messaging_sender_id'))
                        <span class="text-danger" role="alert">
                           <strong>{{ $errors->first('fcm_messaging_sender_id') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group mb-2">
                        <label for="fcm_app_id">{{__('App ID')}}</label>
                        <input type="text" name="fcm_app_id" id="fcm_app_id" placeholder="" class="form-control" value="{{ old('fcm_app_id', $preference->fcm_app_id ?? '')}}" required>
                        @if($errors->has('fcm_app_id'))
                        <span class="text-danger" role="alert">
                           <strong>{{ $errors->first('fcm_app_id') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group mb-2">
                        <label for="fcm_measurement_id">{{__('Measurement ID')}}</label>
                        <input type="text" name="fcm_measurement_id" id="fcm_measurement_id" placeholder="" class="form-control" value="{{ old('fcm_measurement_id', $preference->fcm_measurement_id ?? '')}}">
                        @if($errors->has('fcm_measurement_id'))
                        <span class="text-danger" role="alert">
                           <strong>{{ $errors->first('fcm_measurement_id') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-2">
                     <div class="form-group mb-0 text-center">
                        <button class="btn btn-info d-block" type="submit"> {{ __("Save") }} </button>
                     </div>
                  </div>
               </div>
            </div>
         </form>
      </div>
      <div class="col-xl-6 mb-3">
         <form method="POST" action="{{route('configure.update', Auth::user()->code)}}">
            <input type="hidden" name="verify_config" id="verify_config" value="1">
            @csrf
               <div class="row">
                  <div class="col-md-6 mb-3">
                     <div class="card-box h-100 mb-0">
                        <h4 class="header-title text-uppercase">{{ __("User Authentication") }}</h4>
                        <div class="row align-items-center">
                           <div class="col-sm-12">
                              <div class="form-group switchery-demo">
                                 <label for="verify_email" class="mr-3 mb-0">{{ __("Verify Email") }}</label>
                                 <input type="checkbox" data-plugin="switchery" name="verify_email" id="verify_email" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->verify_email == '1')) checked='checked' @endif>
                              </div>
                           </div>
                           <div class="col-sm-12">
                              <div class="form-group">
                                 <label for="verify_phone" class="mr-3 mb-0">{{ __("Verify Phone") }}</label>
                                 <input type="checkbox" data-plugin="switchery" name="verify_phone" id="verify_phone" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->verify_phone == '1')) checked='checked' @endif>
                              </div>
                           </div>
                           <div class="col-sm-12 mt-4">
                              <div class="form-group mb-0">
                                 <button class="btn btn-info d-block" type="submit"> {{ __("Save") }} </button>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  @if($client_preference_detail->business_type != 'taxi' && $client_preference_detail->business_type != 'laundry' )
                  <div class="col-md-6 mb-3">
                     <div class="card-box h-100 mb-0">
                        <h4 class="header-title text-uppercase">{{ __("Vendor") }}</h4>
                        <div class="row align-items-center">
                           <div class="col-md-12">
                              <div class="form-group">
                                 <label for="dinein_check" class="mr-3 mb-0">{{getDynamicTypeName('Dine-In')}}</label>
                                 <input type="checkbox" data-plugin="switchery" name="dinein_check" id="dinein_check" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->dinein_check == '1')) checked='checked' @endif>
                              </div>
                           </div>
                           <div class="col-md-12">
                              <div class="form-group">
                                 <label for="delivery_check" class="mr-3 mb-0">{{getDynamicTypeName('Delivery')}}</label>
                                 <input type="checkbox" data-plugin="switchery" name="delivery_check" id="delivery_check" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->delivery_check == '1')) checked='checked' @endif>
                              </div>
                           </div>
                           <div class="col-md-12">
                              <div class="form-group">
                                 <label for="takeaway_check" class="mr-3 mb-0">{{getDynamicTypeName('Takeaway')}}</label>
                                 <input type="checkbox" data-plugin="switchery" name="takeaway_check" id="takeaway_check" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->takeaway_check == '1')) checked='checked' @endif>
                              </div>
                           </div>
                           <div class="col-md-12">
                              <div class="form-group mb-0">
                                 <button class="btn btn-info d-block" type="submit"> {{ __("Save") }} </button>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               @endif
            </div>
         </form>

         <div class="card-box">
            <h4 class="header-title text-uppercase mb-2">{{ __("Custom Mods") }}</h4>
            <form method="POST" action="{{route('configure.update', Auth::user()->code)}}">
               <input type="hidden" name="custom_mods_config" id="custom_mods_config" value="1">
               @csrf
               <div class="row align-items-center">
                  @if($client_preference_detail->business_type != 'taxi')

                     @if($client_preference_detail->business_type != 'laundry')
                     <div class="col-md-4">
                        <div class="form-group mb-3">
                           <label for="celebrity_check" class="mr-2 mb-0"> {{ __("Celebrity Mod") }}</label>
                           <input type="checkbox" data-plugin="switchery" name="celebrity_check" id="celebrity_check" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->celebrity_check == '1')) checked='checked' @endif>
                        </div>
                     </div>
                     <div class="col-md-4">
                        <div class="form-group mb-3">
                           <label for="pharmacy_check" class="mr-2 mb-0">{{ __('Pharmacy Mod') }}</label>
                           <input type="checkbox" data-plugin="switchery" name="pharmacy_check" id="pharmacy_check" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->pharmacy_check == '1')) checked='checked' @endif>
                        </div>
                     </div>
                     <div class="col-md-4">
                        <div class="form-group mb-3">
                           <label for="enquire_mode" class="mr-2 mb-0">{{ __("Inquiry Mod") }}</label>
                           <input type="checkbox" data-plugin="switchery" name="enquire_mode" id="	enquire_mode" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->enquire_mode == '1')) checked='checked' @endif>
                        </div>
                     </div>
                    @endif

                  <div class="col-md-4">
                     <div class="form-group mb-3">
                        <label for="off_scheduling_at_cart" class="mr-2 mb-0">{{__('Off Scheduling  Order')}}</label>
                        <input type="checkbox" data-plugin="switchery" name="off_scheduling_at_cart" id="off_scheduling_at_cart" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->off_scheduling_at_cart == '1')) checked='checked' @endif>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group mb-3">
                        <label for="isolate_single_vendor_order" class="mr-2 mb-0">{{__('Isolate Single Vendor Order')}}</label>
                        <input type="checkbox" data-plugin="switchery" name="isolate_single_vendor_order" id="isolate_single_vendor_order" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->isolate_single_vendor_order == '1')) checked='checked' @endif>
                     </div>
                  </div>

                  <div class="col-md-4">
                     <div class="form-group mb-3">
                        <label for="subscription_mode" class="mr-2 mb-0">{{ __("Subscription Mod") }}</label>
                        <input type="checkbox" data-plugin="switchery" name="subscription_mode" id="subscription_mode" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->subscription_mode == '1')) checked='checked' @endif>
                     </div>
                  </div>
                  @endif

                  <div class="col-md-4">
                     <div class="form-group mb-3">
                        <label for="tip_before_order" class="mr-2 mb-0">{{__('Tip Before Order')}}</label>
                        <input type="checkbox" data-plugin="switchery" name="tip_before_order" id="tip_before_order" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->tip_before_order == '1')) checked='checked' @endif>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group mb-3">
                        <label for="tip_after_order" class="mr-2 mb-0">{{__('Tip After Order')}}</label>
                        <input type="checkbox" data-plugin="switchery" name="tip_after_order" id="tip_after_order" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->tip_after_order == '1')) checked='checked' @endif>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group mb-3">
                        <label for="delay_order" class="mr-2 mb-0">{{__('Delay Order')}}</label>
                        <input type="checkbox" data-plugin="switchery" name="delay_order" id="delay_order" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->delay_order == '1')) checked='checked' @endif>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group mb-3">
                        <label for="product_order_form" class="mr-2 mb-0">{{__('Product Order Form')}}</label>
                        <input type="checkbox" data-plugin="switchery" name="product_order_form" id="product_order_form" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->product_order_form == '1')) checked='checked' @endif>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group mb-3">
                        <label for="gifting" class="mr-2 mb-0">{{__('Gifting')}}</label>
                        <input type="checkbox" data-plugin="switchery" name="gifting" id="gifting" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->gifting == '1')) checked='checked' @endif>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group mb-3">
                        <label for="gifting" class="mr-2 mb-0">{{__('Pickup Delivery Service Area')}}</label>
                        <input type="checkbox" data-plugin="switchery" name="pickup_delivery_service_area" id="pickup_delivery_service_area" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->pickup_delivery_service_area == '1')) checked='checked' @endif>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group mb-3">
                        <label for="gifting" class="mr-2 mb-0">{{__('Minumum Order/Batch')}}</label>
                        <input type="checkbox" data-plugin="switchery" name="minimum_order_batch" id="minimum_order_batch" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->minimum_order_batch == '1')) checked='checked' @endif>
                     </div>
                  </div>

                  <div class="col-md-4">
                     <div class="form-group mb-3">
                        <label for="gifting" class="mr-2 mb-0">{{__('Static Delivery fee')}}</label>
                        <input type="checkbox" data-plugin="switchery" name="static_delivey_fee" id="static_delivey_fee" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->static_delivey_fee == '1')) checked='checked' @endif>
                     </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group mb-3">
                       <label for="page_header" class="mr-2 mb-0">{{__('Quick Link in Header')}}</label>
                       <input type="checkbox" data-plugin="switchery" name="header_quick_link" id="page_header" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->header_quick_link == '1')) checked='checked' @endif>
                    </div>
                 </div>

                 <div class="col-md-4">
                  <div class="form-group mb-3">
                     <label for="page_header" class="mr-2 mb-0">{{__('Get Estimations')}}</label>
                     <input type="checkbox" data-plugin="switchery" name="get_estimations" id="get_estimations" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->get_estimations == '1')) checked='checked' @endif>
                  </div>
                 </div>
                 <div class="col-md-4">
                  <div class="form-group mb-3">
                     <label for="page_header" class="mr-2 mb-0">{{__('Tools')}}</label>
                     <input type="checkbox" data-plugin="switchery" name="tools_mode" id="tools_mode" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->tools_mode == '1')) checked='checked' @endif>
                  </div>
                 </div>

                  <div class="col-md-12">
                     <div class="form-group mb-0 text-md-left">
                        <button class="btn btn-info d-block" type="submit">{{ __("Save") }}</button>
                     </div>
                  </div>
               </div>
            </form>
         </div>

         <div class="card-box">
            <h4 class="header-title text-uppercase mb-2">{{ __("Edit Order By") }}</h4>
            <form method="POST" action="{{route('configure.update', Auth::user()->code)}}">
               <input type="hidden" name="edit_order_modes" id="edit_order_modes" value="1">
               @csrf

               @php
                   $vendormenu = getNomenclatureName('Vendors', true);
                   $vendormenulabel = ($vendormenu=="Vendors")?__('Vendors'):$vendormenu;

               @endphp

               <div class="row align-items-center">
                   <div class="col-md-4">
                        <div class="form-group mb-3">
                           <label for="is_edit_order_admin" class="mr-2 mb-0"> {{ __("Admin") }}</label>
                           <input type="checkbox" data-plugin="switchery" name="is_edit_order_admin" id="is_edit_order_admin" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->is_edit_order_admin == '1')) checked='checked' @endif>
                        </div>
                     </div>
                     <div class="col-md-4">
                        <div class="form-group mb-3">
                           <label for="is_edit_order_vendor" class="mr-2 mb-0">{{ $vendormenulabel }}</label>
                           <input type="checkbox" data-plugin="switchery" name="is_edit_order_vendor" id="is_edit_order_vendor" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->is_edit_order_vendor == '1')) checked='checked' @endif>
                        </div>
                     </div>
                     <div class="col-md-4">
                        <div class="form-group mb-3">
                           <label for="is_edit_order_driver" class="mr-2 mb-0">{{ __("Driver") }}</label>
                           <input type="checkbox" data-plugin="switchery" name="is_edit_order_driver" id="is_edit_order_driver" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->is_edit_order_driver == '1')) checked='checked' @endif>
                        </div>
                     </div>


                  <div class="col-md-12">
                     <div class="form-group mb-0 text-md-left">
                        <button class="btn btn-info d-block" type="submit">{{ __("Save") }}</button>
                     </div>
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>

   <div class="row">
      <div class="col-xl-6">
         <div class="card-box pb-2">
            <div class="d-flex align-items-center justify-content-between">
               <h4 class="header-title text-uppercase m-0">{{ __("Vendor Registration Documents") }}</h4>
               <a class="btn btn-info d-block" id="add_vendor_registration_document_modal_btn">
                  <i class="mdi mdi-plus-circle mr-1"></i>{{ __("Add") }}
               </a>
            </div>
            <div class="table-responsive mt-3 mb-1">
               <table class="table table-centered table-nowrap table-striped" id="promo-datatable">
                  <thead>
                     <tr>
                        <th>{{ __("Name") }}</th>
                        <th>{{ __("Type") }}</th>
                        <th>{{ __("Is Required?") }}</th>
                        <th>{{ __("Action") }}</th>
                     </tr>
                  </thead>
                  <tbody id="post_list">
                     @forelse($vendor_registration_documents as $vendor_registration_document)
                     <tr>
                        <td>
                           <a class="edit_vendor_registration_document_btn" data-vendor_registration_document_id="{{$vendor_registration_document->id}}" href="javascript:void(0)">
                              {{$vendor_registration_document->primary ? $vendor_registration_document->primary->name : ''}}
                           </a>
                        </td>
                        <td>{{$vendor_registration_document->file_type}}</td>
                        <td>{{ ($vendor_registration_document->is_required == 1)?__('Yes'):__('No') }}</td>
                        <td>
                           <div>
                              <div class="inner-div" style="float: left;">
                                 <a class="action-icon edit_vendor_registration_document_btn" data-vendor_registration_document_id="{{$vendor_registration_document->id}}" href="javascript:void(0)">
                                    <i class="mdi mdi-square-edit-outline"></i>
                                 </a>
                              </div>
                              <div class="inner-div">
                                 <button type="button" class="btn btn-primary-outline action-icon delete_vendor_registration_document_btn" data-vendor_registration_document_id="{{$vendor_registration_document->id}}">
                                    <i class="mdi mdi-delete"></i>
                                 </button>
                              </div>
                           </div>
                        </td>
                     </tr>
                     @empty
                     <tr align="center">
                        <td colspan="4" style="padding: 20px 0">{{ __("Result not found.") }}</td>
                     </tr>
                     @endforelse
                  </tbody>
               </table>
            </div>
         </div>
         <div class="row">
            <div class="col-lg-6">
               <div class="card-box pb-2">
                  <div class="table-responsive mb-1">
                     <form method="POST" class="h-100" action="{{route('configure.update', Auth::user()->code)}}">
                        <input type="hidden" name="distance_to_time_calc_config" id="distance_to_time_calc_config" value="1">
                        @csrf
                        <div class="card-box mb-0 py-0 px-2">
                           <div class="d-flex align-items-center justify-content-between">
                              <h4 class="header-title mb-0">{{ __("Android/IOS Link") }}</h4>
                              <button class="btn btn-info d-block" type="submit"> {{ __("Save") }} </button>
                           </div>
                           <div class="row mt-2">
                              <div class="col-12 mb-2">
                                 <label class="primaryCurText">{{__('Android App Link')}}</label>
                                 <input class="form-control" type="text" id="android_app_link" name="android_app_link" value="{{ old('android_app_link', $preference->android_app_link  ?? '')}}">
                              </div>
                              <div class="col-12">
                                 <label class="primaryCurText">{{__('IOS App Link')}}</label>
                                 <input class="form-control" type="text" id="ios_link" name="ios_link" value="{{ old('ios_link', $preference->ios_link  ?? '')}}" >
                              </div>
                           </div>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
            <div class="col-lg-6">

               <form method="POST" class="h-100" action="{{route('configure.update', Auth::user()->code)}}">
                  <input type="hidden" name="distance_to_time_calc_config" id="distance_to_time_calc_config" value="1">
                  @csrf
                  <div class="card-box mb-2">
                     <div class="d-flex align-items-center justify-content-between">
                        <h4 class="header-title mb-0">{{__('Distance to Time Calculator')}}</h4>
                        <button class="btn btn-info d-block" type="submit"> {{ __("Save") }} </button>
                     </div>
                     <div class="row mt-2">
                        <div class="col-12 mb-2">
                           <label class="primaryCurText">{{__('Distance Unit')}}</label>
                           <select class="form-control" id="distance_unit_for_time" name="distance_unit_for_time">
                              <option value="">{{__('Select unit')}}</option>
                              <option value="kilometer" @if((isset($preference) && $preference->distance_unit_for_time == 'kilometer')) selected @endif>{{__('Kilometer')}}</option>
                              <option value="mile" @if((isset($preference) && $preference->distance_unit_for_time == 'mile')) selected @endif>{{__('Mile')}}</option>
                           </select>
                        </div>
                        <div class="col-12">
                           <label class="primaryCurText">{{__('Distance to Time Multiplier (Per 1 distance unit)')}}</label>
                           <input class="form-control" type="number" id="distance_to_time_multiplier" name="distance_to_time_multiplier" value="{{ old('distance_to_time_multiplier', $preference->distance_to_time_multiplier  ?? '')}}" min="0">
                        </div>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>

      <div class="col-xl-6">
         <div class="row">

            <div class="col-xl-6 mb-3">

               <form method="POST" class="h-100" action="{{route('referandearn.update', Auth::user()->code)}}">
                  @csrf
                  <div class="card-box mb-0 pb-1">
                     <div class="d-flex align-items-center justify-content-between">
                        <h4 class="header-title text-uppercase">Refer and Earn</h4>
                        <button class="btn btn-info d-block" type="submit"> {{ __("Save") }} </button>
                     </div>
                     <div class="col-xl-12 my-2" id="addCur-160">
                        <label class="primaryCurText">{{ __("Referred To Amount") }} = </label>
                        <input class="form-control" type="number" id="reffered_to_amount" name="reffered_to_amount" value="{{ old('reffered_to_amount', $reffer_to ?? '')}}" min="0">
                     </div>
                     <div class="col-xl-12 mb-2 mt-3" id="addCur-160">
                        <label class="primaryCurText">{{ __("Referred By Amount") }} = </label>
                        <input class="form-control" type="number" name="reffered_by_amount" id="reffered_by_amount" value="{{ old('reffered_by_amount', $reffer_by ?? '')}}" min="0">
                     </div>
                  </div>
               </form>
            </div>

            <div class="col-xl-6 mb-3">
               <form method="POST" class="h-100" action="{{route('configure.update', Auth::user()->code)}}">
                  @csrf
                  <div class="card-box mb-0 pb-1">
                     <div class="d-flex align-items-center justify-content-between">
                        <h4 class="header-title text-uppercase">{{ __('Order Email Notification') }}</h4>
                        <button class="btn btn-info d-block" type="submit"> {{ __("Save") }} </button>
                     </div>
                     <div class="col-xl-12 my-2" id="addCur-160">
                        <label class="primaryCurText">{{ __('Email') }}</label>
                        <input class="form-control" type="email" id="admin_email" name="admin_email" value="{{ old('admin_email', $preference->admin_email)}}">
                     </div>
                  </div>
               </form>
            </div>

            <div class="col-xl-6 mb-3">

               <form method="POST" class="h-100" action="{{route('referandearn.update', Auth::user()->code)}}">
                  @csrf
                  <div class="card-box product-tags mb-0 pb-1">
                     <div class="d-flex align-items-center justify-content-between">
                        <h4 class="header-title text-uppercase">{{ __('Tags for Product')}}</h4>
                        <a class="btn btn-info d-block" id="add_product_tag_modal_btn">
                           <i class="mdi mdi-plus-circle mr-1"></i>{{ __("Add") }}
                        </a>
                     </div>
                     <div class="table-responsive mt-3 mb-1">
                        <table class="table table-centered table-nowrap table-striped" id="promo-datatable">
                           <thead>
                              <tr>
                                 <th>{{ __("Icon") }}</th>
                                 <th>{{ __("Name") }}</th>
                                 <th>{{ __("Action") }}</th>
                              </tr>
                           </thead>
                           <tbody id="post_list">
                              @forelse($tags as $tag)
                                 <tr>
                                    <td>
                                       @if(isset($tag->icon) && !empty($tag->icon)) <img src="{{ $tag->icon['proxy_url'].'100/100'.$tag->icon['image_path'] }}">@endif
                                    </td>
                                    <td>
                                       <a class="edit_product_tag_btn" data-tag_id="{{$tag->id}}" href="javascript:void(0)">
                                          {{$tag->primary ? $tag->primary->name : ''}}
                                       </a>
                                    </td>
                                    <td>
                                       <div>
                                          <div class="inner-div" style="float: left;">
                                             <a class="action-icon edit_product_tag_btn" data-tag_id="{{$tag->id}}" href="javascript:void(0)">
                                                <i class="mdi mdi-square-edit-outline"></i>
                                             </a>
                                          </div>
                                          <div class="inner-div">
                                             <button type="button" class="btn btn-primary-outline action-icon delete_product_tag_btn" data-tag_id="{{$tag->id}}">
                                                <i class="mdi mdi-delete"></i>
                                             </button>
                                          </div>
                                       </div>
                                    </td>
                                 </tr>
                              @empty
                                 <tr align="center">
                                    <td colspan="4" style="padding: 20px 0">{{ __("Tags not found.") }}</td>
                                 </tr>
                              @endforelse
                           </tbody>
                        </table>
                     </div>
                  </div>
               </form>
            </div>


         </div>
      </div>
   </div>

   <div class="row">
      {{--<div class="col-lg-6">
         <div class="page-title-box">
            <h4 class="page-title text-uppercase">{{ __("Driver") }}</h4>
         </div>
         <div class="card-box pb-2">
         <h4 class="header-title text-uppercase">{{ __("Driver Registration Documents") }}</h4>
            <div class="d-flex align-items-center justify-content-end mt-2">
               <a class="btn btn-info d-block" id="add_driver_registration_document_modal_btn">
                  <i class="mdi mdi-plus-circle mr-1"></i>{{ __("Add") }}
               </a>
            </div>
            <div class="table-responsive mt-3 mb-1">
               <table class="table table-centered table-nowrap table-striped" id="promo-datatable">
                  <thead>
                     <tr>
                        <th>{{ __("Name") }}</th>
                        <th>{{ __("Type") }}</th>
                        <th>{{ __("Action") }}</th>
                     </tr>
                  </thead>
                  <tbody id="post_list">
                     @forelse($driver_registration_documents as $driver_registration_document)
                     <tr>
                        <td>
                           <a class="edit_driver_registration_document_btn" data-driver_registration_document_id="{{$driver_registration_document->id}}" href="javascript:void(0)">
                              {{$driver_registration_document->primary ? $driver_registration_document->primary->name : ''}}
                           </a>
                        </td>
                        <td>{{$driver_registration_document->file_type}}</td>
                        <td>
                           <div>
                              <div class="inner-div" style="float: left;">
                                 <a class="action-icon edit_driver_registration_document_btn" data-driver_registration_document_id="{{$driver_registration_document->id}}" href="javascript:void(0)">
                                    <i class="mdi mdi-square-edit-outline"></i>
                                 </a>
                              </div>
                              <div class="inner-div">
                                 <button type="button" class="btn btn-primary-outline action-icon delete_driver_registration_document_btn" data-driver_registration_document_id="{{$driver_registration_document->id}}">
                                    <i class="mdi mdi-delete"></i>
                                 </button>
                              </div>
                           </div>
                        </td>
                     </tr>
                     @empty
                     <tr align="center">
                        <td colspan="4" style="padding: 20px 0">{{ __("Result not found.") }}</td>
                     </tr>
                     @endforelse
                  </tbody>
               </table>
            </div>
         </div>
      </div> --}}

   </div>

   <div style="display:none;">
      <form method="POST" action="{{route('configure.update', Auth::user()->code)}}">
         @csrf
         <div class="row">
            <div class="col-xl-11 col-md-offset-1">
               <div class="card-box">
                  <h4 class="header-title text-uppercase">{{ __("Email") }}</h4>
                  <p class="sub-header">
                     {{ __("Choose Email paid plan to whitelable 'From email address' and 'Sender Name' in the Email sent out from your account.") }}
                  </p>
                  <div class="row mb-0">
                     <div class="col-md-6">
                        <div class="form-group mb-3">
                           <label for="email_plan">{{ __("CURRENT SELECTION") }}</label>
                           <select class="form-control" id="email_plan" name="email_plan">
                              <option>{{ __("Select Plan") }}</option>
                              <option value="free" {{ (isset($preference) && $preference->email_plan =="free")? "selected" : "" }}>
                                 {{ __("Free") }}</option>
                              <option value="paid" {{ (isset($preference) && $preference->email_plan =="paid")? "selected" : "" }}>
                                 {{ __("Paid") }}</option>
                           </select>
                           @if($errors->has('email_plan'))
                           <span class="text-danger" role="alert">
                              <strong>{{ $errors->first('email_plan') }}</strong>
                           </span>
                           @endif
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </form>
      <div style="display:none;">
         <form method="POST" action="{{route('configure.update', Auth::user()->code)}}">
            @csrf
            <div class="row">
               <div class="col-xl-11 col-md-offset-1">
                  <div class="card-box">
                     <h4 class="header-title text-uppercase">{{ __("Email") }}</h4>
                     <p class="sub-header">
                        {{ __("Choose Email paid plan to whitelable 'From email address' and 'Sender Name' in the Email sent out from your account.") }}
                     </p>
                     <div class="row mb-0">
                        <div class="col-md-6">
                           <div class="form-group mb-3">
                              <label for="email_plan">{{ __("CURRENT SELECTION") }}</label>
                              <select class="form-control" id="email_plan" name="email_plan">
                                 <option>{{ __("Select Plan") }}</option>
                                 <option value="free" {{ (isset($preference) && $preference->email_plan =="free")? "selected" : "" }}>
                                    {{ __("Free") }}</option>
                                 <option value="paid" {{ (isset($preference) && $preference->email_plan =="paid")? "selected" : "" }}>
                                    {{ __("Paid") }}</option>
                              </select>
                              @if($errors->has('email_plan'))
                              <span class="text-danger" role="alert">
                                 <strong>{{ $errors->first('email_plan') }}</strong>
                              </span>
                              @endif
                           </div>
                        </div>
                     </div>
                     <div class="row mb-2">
                        <div class="col-md-12">
                           <div class="form-group mb-3">
                              <label for="sms_service_api_key">{{ __("PREVIEW") }}</label>
                              <div class="card">
                                 <div class="card-body">
                                    <p class="mb-2"><span class="font-weight-semibold mr-2">{{ __("From") }}:</span>
                                       johndoe<span>
                                          << /span>contact@royodispatcher.com<span>></span>
                                    </p>
                                    <p class="mb-2"><span class="font-weight-semibold mr-2">{{ __("Reply To") }}:</span>
                                       johndoe@gmail.com
                                    </p>
                                    <p class="mt-3 text-center">
                                      {{ __("Your message note here..") }}
                                    </p>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="row mb-2">
                        <div class="col-md-2">
                           <div class="form-group mb-0 text-center">
                              <button class="btn btn-info btn-block" type="submit"> {{ __("Save") }} </button>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </form>
      </div>
   </div>
   <div id="show-map-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
      <div class="modal-dialog modal-full-width">
         <div class="modal-content">
            <div class="modal-header border-bottom">
               <h4 class="modal-title">{{ __("Select Location") }}</h4>
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">??</button>
            </div>
            <div class="modal-body p-4">
               <div class="row">
                  <form id="task_form" action="#" method="POST" style="width: 100%">
                     <div class="col-md-12">
                        <div id="googleMap" style="height: 500px; min-width: 500px; width:100%"></div>
                        <input type="hidden" name="lat_input" id="lat_map" value="0" />
                        <input type="hidden" name="lng_input" id="lng_map" value="0" />
                        <input type="hidden" name="for" id="map_for" value="" />
                     </div>
                  </form>
               </div>
            </div>
            <div class="modal-footer">
               <button type="submit" class="btn btn-info waves-effect waves-light selectMapLocation">{{ __("Ok") }}</button>
            </div>
         </div>
      </div>
   </div>
   <div id="add_vendor_registration_document_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"  standard-modalLabel aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
         <div class="modal-content">
            <div class="modal-header border-bottom">
               <h4 class="modal-title" id="standard-modalLabel">{{ __("Add Vendor Registration Document") }}</h4>
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">??</button>
            </div>
            <div class="modal-body">
               <form id="vendorRegistrationDocumentForm" method="POST" action="javascript:void(0)">
                  @csrf
                  <div id="save_social_media">
                     <input type="hidden" name="vendor_registration_document_id" value="">
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group position-relative">
                              <label for="">Type</label>
                              <div class="input-group mb-2">
                                 <select class="form-control" name="file_type" id="file_type_select">
                                    <option value="Text">Text</option>
                                    <option value="Image">Image</option>
                                    <option value="Pdf">PDF</option>
                                    <option value="selector">Selector</option>
                                 </select>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group position-relative">
                              <label for="">Is Required?</label>
                              <div class="input-group mb-2">
                                 <select class="form-control" name="is_required">
                                    <option value="1">{{__('Yes')}}</option>
                                    <option value="0">{{__('No')}}</option>
                                 </select>
                              </div>
                           </div>
                        </div>
                        @forelse($client_languages as $k => $client_language)
                        <div class="col-md-6 mb-2">
                           <div class="row">
                              <div class="col-12">
                                 <div class="form-group position-relative">
                                    <label for="">{{ __("Name") }} ({{$client_language->langName}})</label>
                                    <input class="form-control" name="language_id[{{$k}}]" type="hidden" value="{{$client_language->langId}}">
                                    <input class="form-control" name="name[{{$k}}]" type="text" id="vendor_registration_document_name_{{$client_language->langId}}">
                                 </div>
                                 @if($k == 0)
                                    <span class="text-danger error-text social_media_url_err"></span>
                                 @endif
                              </div>
                           </div>
                        </div>
                        @empty
                        @endforelse
                        <div id="selector_div" class="col-md-12 d-none">
                            <div class="card">
                            <div class="card-box mb-0 ">
                                <div class="d-flex align-items-center justify-content-between">
                                   <h4 class="header-title text-uppercase">{{__('Options')}}</h4>
                                   {{-- <div class="col-md-4 col-xl-4 mb-2 ">
                                        <div class="form-group mb-0">
                                            <select class="form-control" name="option_language_id" id="option_client_language">
                                            @foreach($client_languages as $client_language)
                                                <option value="{{$client_language->langId}}">{{$client_language->langName}}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                    </div> --}}
                                </div>
                                <div id="option_div">

                                        <div class="selector-option table-responsive">
                                            <table class="table table-borderless mb-0 optionTableAdd" id="selector-datatable">
                                                <tr class="trForClone">

                                                    @foreach($client_languages as $langs)
                                                        <th>{{$langs->langName}}</th>
                                                    @endforeach
                                                    <th></th>
                                                </tr>
                                                <tbody id="table_body">
                                                    {{-- <tr>
                                                    @foreach($client_languages as $key => $langs)
                                                        <td>
                                                            {{-- <input type="hidden" name="option_language_id[]"  value="{{$langs->langId}}" class="form-control">
                                                            <input type="hidden" name="option_id[{{$key}}][]"   class="form-control" >
                                                            <input type="text" name="option_names[{{$key}}][]" class="form-control" @if($langs->is_primary == 1) required @endif>
                                                        </td>
                                                        @endforeach
                                                        <td class="lasttd"></td>
                                                    </tr> --}}
                                                </tbody>
                                            </table>
                                        </div>


                                </div>
                            </div>
                            </div>
                        </div>
                     </div>
                  </div>
               </form>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-primary submitSaveVendorRegistrationDocument">{{ __("Save") }}</button>
            </div>
         </div>
      </div>
   </div>


   <div id="add_driver_registration_document_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
            <div class="modal-header border-bottom">
               <h4 class="modal-title" id="standard-modalLabel">{{ __("Add Driver Registration Document") }}</h4>
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">??</button>
            </div>
            <div class="modal-body">
               <form id="driverRegistrationDocumentForm" method="POST" action="javascript:void(0)">
                  @csrf
                  <div id="save_social_media">
                     <input type="hidden" name="driver_registration_document_id" value="">
                     <div class="row">
                        <div class="col-md-12">
                           <div class="form-group position-relative">
                              <label for="">{{ __('Type') }}</label>
                              <div class="input-group mb-2">
                                 <select class="form-control" name="file_type">
                                    @forelse($file_types_driver as $k => $file_type)
                                    <option value="{{$file_type}}">{{$file_type}}</option>
                                    @empty
                                    @endforelse
                                 </select>
                              </div>
                           </div>
                        </div>
                        @forelse($client_languages as $k => $client_language)
                        <div class="col-md-6 mb-2">
                           <div class="row">
                              <div class="col-12">
                                 <div class="form-group position-relative">
                                    <label for="">{{ __("Name") }} ({{$client_language->langName}})</label>
                                    <input class="form-control" name="language_id[{{$k}}]" type="hidden" value="{{$client_language->langId}}">
                                    <input class="form-control" name="name[{{$k}}]" type="text" id="driver_registration_document_name_{{$client_language->langId}}">
                                 </div>
                                 @if($k == 0)
                                    <span class="text-danger error-text social_media_url_err"></span>
                                 @endif
                              </div>
                           </div>
                        </div>
                        @empty
                        @endforelse
                     </div>
                  </div>
               </form>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-primary submitSaveDriverRegistrationDocument">{{ __("Save") }}</button>
            </div>
         </div>
      </div>
   </div>

   <!-- modal for product tags -->
   <div id="add_product_tag_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
            <div class="modal-header border-bottom">
               <h4 class="modal-title" id="standard-modalLabel">{{ __("Add Product Tag") }}</h4>
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">??</button>
            </div>
            <div class="modal-body">
               <form id="productTagForm" method="POST" action="javascript:void(0)" enctype="multipart/form-data">
                  @csrf
                  <div id="save_product_tag">
                     <input type="hidden" name="tag_id" value="">
                     <div class="row">
                        <div class="col-md-3">
                           <label>{{ __('Upload Icon') }}</label>
                           <input type="file" accept="image/*" data-plugins="dropify" name="icon" class="dropify"  />
                           <label class="logo-size text-right w-100">{{ __("Icon Size") }} 100X100</label>
                       </div>

                        @forelse($client_languages as $k => $client_language)
                        <div class="col-md-6 mb-2">
                           <div class="row">
                              <div class="col-12">
                                 <div class="form-group position-relative">
                                    <label for="">{{ __("Name") }} ({{$client_language->langName}})</label>
                                    <input class="form-control" name="language_id[{{$k}}]" type="hidden" value="{{$client_language->langId}}">
                                    <input class="form-control" name="name[{{$k}}]" type="text" id="product_tag_name_{{$client_language->langId}}">
                                 </div>
                                 @if($k == 0)
                                    <span class="text-danger error-text product_tag_err"></span>
                                 @endif
                              </div>
                           </div>
                        </div>
                        @empty
                        @endforelse
                     </div>
                  </div>
               </form>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-primary submitSaveProductTag">{{ __("Save") }}</button>
            </div>
         </div>
      </div>
   </div>
   <!-- end product tags -->
   <script type="text/template" id="vendorSelectorTemp">
        <tr class ="option_section" id ="option_section_<%= id %>" data-section_number="<%= id %>">
        <input type="hidden" name="option_id[<%= id-1 %>][]"  id="option_id<%= id %>" data-id ="<%= id %>" value ="<%= data?data.id:'' %>">
        @foreach($client_languages as $key => $langs)
        <td>
            <div class="form-group mb-0">
                <input type="hidden" name="option_lang_id[<%= id-1 %>][]"   value ="{{$langs->langId}}">
                <input type="text" name="option_name[<%= id-1 %>][]" class="form-control" @if($langs->is_primary == 1) required @endif   id="option_name_<%= id-1 %>_{{$langs->langId}}" placeholder="" data-id ="<%= id %>" value ="<%= data?(data.translations?data.translations.name:''):'' %>">
            </div>
        </td>

        @endforeach
        <td class="lasttd d-flex align-items-center justify-content-center">
            <% if(id > 1) { %>
                <a href="javascript:void(0)" class="action-icon remove_more_button"  id ="remove_button_<%= id %>" data-id ="<%= id %>"> <i class="mdi mdi-delete"></i></a>
            <% } %>
            <a href="javascript:void(0)" class="add_more_button" id ="add_button_<%= id %>" data-id ="<%= id %>"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>

        </td>

    </tr>


</script>


   <!-- modal for slots -->
   <div id="add_slot_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
            <div class="modal-header border-bottom">
               <h4 class="modal-title" id="standard-modalLabel">{{ __("Add Slot") }}</h4>
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">??</button>
            </div>
            <div class="modal-body">
               <form id="slotForm" method="POST" action="javascript:void(0)">
                  @csrf
                  <div id="save_product_tag">
                     <input type="hidden" name="slot_id" value="">
                     <div class="row">


                        <div class="col-md-12 mb-2">
                           <div class="row">
                              <div class="col-md-4">
                                 <div class="form-group position-relative">
                                    <label for="">{{ __("Name") }}</label>
                                    <input class="form-control" name="name" type="text" value="">
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group position-relative">
                                    <label for="">{{ __("Start Time") }}</label>
                                    <input class="form-control" name="start_time" type="time" value="">
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group position-relative">
                                    <label for="">{{ __("End Time") }}</label>
                                    <input class="form-control" name="end_time" type="time" value="">
                                 </div>
                              </div>
                           </div>
                        </div>

                     </div>
                  </div>
               </form>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-primary submitSaveSlot">{{ __("Save") }}</button>
            </div>
         </div>
      </div>
   </div>
   <!-- end product Slot -->

   @endsection
   @section('script')
   <script type="text/javascript">
      $('#add_vendor_registration_document_modal_btn').click(function(e) {
         document.getElementById("vendorRegistrationDocumentForm").reset();
         $('#add_vendor_registration_document_modal input[name=vendor_registration_document_id]').val("");
         $('#add_vendor_registration_document_modal').modal('show');
         $('#add_vendor_registration_document_modal #standard-modalLabel').html('Add Vendor Registration Document');
      });

      $('#add_product_tag_modal_btn').click(function(e) {
         document.getElementById("productTagForm").reset();
         $('#add_product_tag_modal input[name=tag_id]').val("");
         $('#add_product_tag_modal').modal('show');
         $('#add_product_tag__modal #standard-modalLabel').html('Add Tag');
      });

    $(document).on("change", "#file_type_select", function() {
        var file_type = $(this).val();
        if(file_type == 'selector'){
            $("#selector_div").removeClass("d-none");
            var classoption_section = $('#option_div').find('.option_section');
            if(classoption_section.length==0){
                addoptionTemplate(0);
            }
        }
        else{
            $("#selector_div").addClass("d-none");
        }
    });
    $(document).on('click','.add_more_button',function(){
        var main_id = $(this).data('id');
        addoptionTemplate(main_id);
        console.log($('.add_more_button').length);
    });
    $(document).on('click','.remove_more_button',function(){
        var main_id =$(this).data('id');
        removeSeletOptionSectionTemplate(main_id);
        $('.add_more_button').each(function(key,value){
            if(key == ($('.add_more_button').length-1)){
                $('#add_button_'+$(this).data('id')).show();
            }
        });
    });
    $(document).on("change","#option_client_language",function() {
        let vendor_registration_document_id = $('input[name="vendor_registration_document_id"]').val();
        editVendorRegistrationForm(vendor_registration_document_id);
    });
    function removeSeletOptionSectionTemplate(div_id){
        $('#option_section_'+div_id).remove();
    }
    $(document).on('click', '.addOptionRow-Add', function(e) {
        var d = new Date();
        var n = d.getTime();
        var $tr = $('.optionTableAdd tbody>tr:first').next('tr');
        var $clone = $tr.clone();
        $clone.find(':text').val('');
        $clone.find('.hexa-colorpicker').attr("id", "hexa-colorpicker-" + n);
        $clone.find('.lasttd').html('<a href="javascript:void(0);" class="action-icon deleteCurRow"> <i class="mdi mdi-delete"></i></a>');
        $('.optionTableAdd').append($clone);

    });

    function addoptionTemplate(section_id){
        section_id                = parseInt(section_id);
        section_id                = section_id +1;
        var data                  = '';

        var price_section_temp    = $('#vendorSelectorTemp').html();
        var modified_temp         = _.template(price_section_temp);
        var result_html           = modified_temp({id:section_id,data:data});
        $("#table_body").append(result_html);
        $('.add_more_button').hide();
        $('#add_button_'+section_id).show();
    }
      $('#add_slot_modal_btn').click(function(e) {
         document.getElementById("slotForm").reset();
         $('#add_slot_modal input[name=slot_id]').val("");
         $('#add_slot_modal').modal('show');
         $('#add_slot__modal #standard-modalLabel').html('Add Slot');
      });

      $(document).on("click", ".delete_vendor_registration_document_btn", function() {
         var vendor_registration_document_id = $(this).data('vendor_registration_document_id');
         Swal.fire({
            title: "{{__('Are you Sure?')}}",
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Ok',
         }).then((result) => {
            if (result.value) {
               $.ajax({
                  type: "POST",
                  dataType: 'json',
                  url: "{{ route('vendor.registration.document.delete') }}",
                  data: {
                     _token: "{{ csrf_token() }}",
                     vendor_registration_document_id: vendor_registration_document_id
                  },
                  success: function(response) {
                     if (response.status == "Success") {
                        $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                        setTimeout(function() {
                           location.reload()
                        }, 2000);
                     }
                  }
               });
            }
         });
      });
      $(document).on('click', '.submitSaveVendorRegistrationDocument', function(e) {
         var vendor_registration_document_id = $("#add_vendor_registration_document_modal input[name=vendor_registration_document_id]").val();
         if (vendor_registration_document_id) {
            var post_url = "{{ route('vendor.registration.document.update') }}";
         } else {
            var post_url = "{{ route('vendor.registration.document.create') }}";
         }
         var form_data = new FormData(document.getElementById("vendorRegistrationDocumentForm"));


         $.ajax({
            url: post_url,
            method: 'POST',
            data: form_data,
            contentType: false,
            processData: false,
            success: function(response) {
               if (response.status == 'Success') {
                  $('#add_or_edit_social_media_modal').modal('hide');
                  $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                  setTimeout(function() {
                     location.reload()
                  }, 2000);
               } else {
                  $.NotificationApp.send("Error", response.message, "top-right", "#ab0535", "error");
               }
            },
            error: function(response) {
               $('#add_vendor_registration_document_modal .social_media_url_err').html('The default language name field is required.');
            }
         });
      });
      $(document).on("click", ".edit_vendor_registration_document_btn", function() {
         let vendor_registration_document_id = $(this).data('vendor_registration_document_id');
         editVendorRegistrationForm(vendor_registration_document_id);
      });

      function editVendorRegistrationForm(vendor_registration_document_id){
        let language_id = $('#option_client_language').val();
         $('#add_vendor_registration_document_modal input[name=vendor_registration_document_id]').val(vendor_registration_document_id);
         $.ajax({
            method: 'GET',
            data: {
               vendor_registration_document_id: vendor_registration_document_id,
               language_id:language_id
            },
            url: "{{ route('vendor.registration.document.edit') }}",
            success: function(response) {
               if (response.status = 'Success') {
                    if(response.data.file_type=="selector"){
                        $("#selector_div").removeClass("d-none");
                        $('.option_section').remove();
                        var options = response.data.options;
                        var section_id =0
                        var row =0
                        var option_section_temp    = $('#vendorSelectorTemp').html();
                        var modified_temp         = _.template(option_section_temp);
                        $(options).each(function(index, value) {
                            section_id                = parseInt(section_id);
                            row                       = parseInt(section_id)
                            section_id                = section_id +1;
                            $('#table_body').append(modified_temp({ id:section_id,data:value}));
                            var options_trans = value.translations;
                            $(options_trans).each(function(trans_index, trans_value) {
                                var input_id = '#option_name_'+row+'_'+trans_value.language_id;
                                $(input_id).val(trans_value.name);
                            });
                            $('.add_more_button').hide();
                            $('#add_button_'+section_id).show();
                        });
                    }else{
                        $('.option_section').remove();
                        $("#selector_div").addClass("d-none");
                    }
                  $(document).find("#add_vendor_registration_document_modal select[name=file_type]").val(response.data.file_type).change();

                  $("#add_vendor_registration_document_modal input[name=vendor_registration_document_id]").val(response.data.id);
                  $(document).find("#add_vendor_registration_document_modal select[name=is_required]").val(response.data.is_required).change();
                  $('#add_vendor_registration_document_modal #standard-modalLabel').html('Update Vendor Registration Document');
                  $('#add_vendor_registration_document_modal').modal('show');
                  $.each(response.data.translations, function( index, value ) {
                    $('#add_vendor_registration_document_modal #vendor_registration_document_name_'+value.language_id).val(value.name);
                  });
               }
            },
            error: function() {

            }
         });

      }



      ///   product tag ////
      $(document).on("click", ".delete_product_tag_btn", function() {
         var tag_id = $(this).data('tag_id');
         Swal.fire({
            title: "{{__('Are you Sure?')}}",
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Ok',
          }).then((result) => {
            if(result.value)
            {
               $.ajax({
                  type: "POST",
                  dataType: 'json',
                  url: "{{ route('tag.delete') }}",
                  data: {
                     _token: "{{ csrf_token() }}",
                     tag_id: tag_id
                  },
                  success: function(response) {
                     if (response.status == "Success") {
                        $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                        setTimeout(function() {
                           location.reload()
                        }, 2000);
                     }
                  }
               });
            }
         });
      });
      $(document).on('click', '.submitSaveProductTag', function(e) {
         var tag_id = $("#add_product_tag_modal input[name=tag_id]").val();
         if (tag_id) {
            var post_url = "{{ route('tag.update') }}";
         } else {
            var post_url = "{{ route('tag.create') }}";
         }
         var form_data = new FormData(document.getElementById("productTagForm"));
         $.ajax({
            url: post_url,
            method: 'POST',
            data: form_data,
            contentType: false,
            processData: false,
            success: function(response) {
               if (response.status == 'Success') {
                  $('#add_or_edit_social_media_modal').modal('hide');
                  $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                  setTimeout(function() {
                     location.reload()
                  }, 2000);
               } else {
                  $.NotificationApp.send("Error", response.message, "top-right", "#ab0535", "error");
               }
            },
            error: function(response) {
               $('#add_product_tag_modal .product_tag_err').html('The default language name field is required.');
            }
         });
      });
      $(document).on("click", ".edit_product_tag_btn", function() {
         let tag_id = $(this).data('tag_id');
         $('#add_product_tag_modal input[name=tag_id]').val(tag_id);
         $.ajax({
            method: 'GET',
            data: {
               tag_id: tag_id
            },
            url: "{{ route('tag.edit') }}",
            success: function(response) {
               if (response.status = 'Success') {
                  $("#add_product_tag_modal input[name=tag_id]").val(response.data.id);
                  $('#add_product_tag_modal #standard-modalLabel').html('Update Product Tag');
                  $('#add_product_tag_modal').modal('show');
                  $.each(response.data.translations, function( index, value ) {
                    $('#add_product_tag_modal #product_tag_name_'+value.language_id).val(value.name);
                  });
               }
            },
            error: function() {

            }
         });
      });
      // end product tag ////

      // Start Slot ////

      $(document).on("click", ".delete_slot_btn", function() {
         var tag_id = $(this).data('slot_id');
         if (confirm('Are you sure?')) {
            $.ajax({
               type: "POST",
               dataType: 'json',
               url: "{{ route('slot.delete') }}",
               data: {
                  _token: "{{ csrf_token() }}",
                  slot_id: tag_id
               },
               success: function(response) {
                  if (response.status == "Success") {
                     $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                     setTimeout(function() {
                        location.reload()
                     }, 2000);
                  }
               }
            });
         }
      });

      $(document).on('click', '.submitSaveSlot', function(e) {
         var slot_id = $("#add_slot_modal input[name=slot_id]").val();
         if (slot_id) {
            var post_url = "{{ route('slot.update') }}";
         } else {
            var post_url = "{{ route('slot.create') }}";
         }
         $.ajax({
            url: post_url,
            method: 'POST',
            data: $('#slotForm').serialize(),
            success: function(response) {
               if (response.status == 'Success') {
                  $('#add_or_edit_social_media_modal').modal('hide');
                  $.NotificationApp.send("Success", 'Slot Added Successfuly', "top-right", "#5ba035", "success");
                  setTimeout(function() {
                     location.reload()
                  }, 2000);
               } else {
                  $.NotificationApp.send("Error", 'Something went wrong', "top-right", "#ab0535", "error");
               }
            }
         });
      });
      $(document).on("click", ".edit_slot_btn", function() {
         let slot_id = $(this).data('slot_id');
         $('#add_slot_modal input[name=slot_id]').val(slot_id);
         $.ajax({
            method: 'GET',
            data: {
               slot_id: slot_id
            },
            url: "{{ route('slot.edit') }}",
            success: function(response) {
               if (response.status = 'Success') {
                  $("#add_slot_modal input[name=slot_id]").val(response.data.id);
                  $("#add_slot_modal input[name=name]").val(response.data.name);
                  $("#add_slot_modal input[name=start_time]").val(response.data.start_time);
                  $("#add_slot_modal input[name=end_time]").val(response.data.end_time);
                  $('#add_slot_modal #standard-modalLabel').html('Update Slot');
                  $('#add_slot_modal').modal('show');
               }
            },
            error: function() {

            }
         });
      });
      // end Slot ////


      $('#add_driver_registration_document_modal_btn').click(function(e) {
         $('#add_driver_registration_document_modal').modal('show');
         $('#add_driver_registration_document_modal #standard-modalLabel').html('Add Driver Registration Document');
      });
      $(document).on("click", ".delete_driver_registration_document_btn", function() {
         var driver_registration_document_id = $(this).data('driver_registration_document_id');
         Swal.fire({
            title: "{{__('Are you Sure?')}}",
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Ok',
         }).then((result) => {
            if(result.value)
            {
               $.ajax({
                  type: "POST",
                  dataType: 'json',
                  url: "{{ route('driver.registration.document.delete') }}",
                  data: {
                     _token: "{{ csrf_token() }}",
                     driver_registration_document_id: driver_registration_document_id
                  },
                  success: function(response) {
                     if (response.status == "Success") {
                        $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                        setTimeout(function() {
                           location.reload()
                        }, 2000);
                     }
                  }
               });
            }
         });
      });
      $(document).on('click', '.submitSaveDriverRegistrationDocument', function(e) {
         var driver_registration_document_id = $("#add_driver_registration_document_modal input[name=driver_registration_document_id]").val();
         if (driver_registration_document_id) {
            var post_url = "{{ route('driver.registration.document.update') }}";
         } else {
            var post_url = "{{ route('driver.registration.document.create') }}";
         }
         var form_data = new FormData(document.getElementById("driverRegistrationDocumentForm"));
         $.ajax({
            url: post_url,
            method: 'POST',
            data: form_data,
            contentType: false,
            processData: false,
            success: function(response) {
               if (response.status == 'Success') {
                  $('#add_or_edit_social_media_modal').modal('hide');
                  $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                  setTimeout(function() {
                     location.reload()
                  }, 2000);
               } else {
                  $.NotificationApp.send("Error", response.message, "top-right", "#ab0535", "error");
               }
            },
            error: function(response) {
               $('#add_driver_registration_document_modal .social_media_url_err').html('The default language name field is required.');
            }
         });
      });
      $(document).on("click", ".edit_driver_registration_document_btn", function() {
         let driver_registration_document_id = $(this).data('driver_registration_document_id');
         $('#add_driver_registration_document_modal input[name=driver_registration_document_id]').val(driver_registration_document_id);
         $.ajax({
            method: 'GET',
            data: {
               driver_registration_document_id: driver_registration_document_id
            },
            url: "{{ route('driver.registration.document.edit') }}",
            success: function(response) {
               if (response.status = 'Success') {
                  $('#add_driver_registration_document_modal').modal('show');
                  $("#add_driver_registration_document_modal input[name=file_type]").val(response.data.file_type).change();
                  $("#add_driver_registration_document_modal input[name=driver_registration_document_id]").val(response.data.id);
                  $('#add_driver_registration_document_modal #standard-modalLabel').html('Update Driver Registration Document');
                  $.each(response.data.translations, function( index, value ) {
                    $('#add_driver_registration_document_modal #driver_registration_document_name_'+value.language_id).val(value.name);
                  });
               }
            },
            error: function() {

            }
         });
      });
      $('.cleanSoftDeleted').click(function(e) {
         Swal.fire({
            title: "{{__('Are you Sure?')}}",
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Ok',
         }).then((result) => {
            if(result.value)
            {
               e.preventDefault();
               $.ajax({
                  url: "{{ route('config.cleanSoftDeleted') }}",
                  type: "POST",
                  data: {
                     "_token": "{{ csrf_token() }}"
                  },
                  success: function(response) {
                     $.NotificationApp.send("Success", "Deleted Successfully", "top-right", "#5ba035", "success");
                  },
               });
            }
         });
      });

      $('.importDemoContent').click(function(e) {
         Swal.fire({
            title: "{{__('Are you Sure you want to hard delete?')}}",
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Ok',
         }).then((result) => {
            if(result.value)
            {
               e.preventDefault();
               $.ajax({
                  url: "{{ route('config.importDemoContent') }}",
                  type: "POST",
                  data: {
                     "_token": "{{ csrf_token() }}"
                  },
                  success: function(response) {
                     $.NotificationApp.send("Success", "Deleted Successfully", "top-right", "#5ba035", "success");
                  },
               });
            }
         });
      });

      $('.hardDeleteEverything').click(function(e) {
         Swal.fire({
            title: "{{__('Are you Sure you want to proceed?')}}",
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Ok',
         }).then((result) => {
            if(result.value)
            {
               e.preventDefault();
               $.ajax({
                  url: "{{ route('config.hardDeleteEverything') }}",
                  type: "POST",
                  data: {
                     "_token": "{{ csrf_token() }}"
                  },
                  success: function(response) {
                     $.NotificationApp.send("Success", "Deleted Successfully", "top-right", "#5ba035", "success");
                  },
               });
            }
         });
      });

      function generateRandomString(length) {
         var text = "";
         var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
         for (var i = 0; i < length; i++)
            text += possible.charAt(Math.floor(Math.random() * possible.length));
         return text;
      }

      function genrateKeyAndToken() {
         var key = generateRandomString(30);
         var token = generateRandomString(60);
         $('#personal_access_token_v1').val(key);
         $('#personal_access_token_v2').val(token);
      }
      var autocomplete = {};
      var autocompletesWraps = [];
      var count = 1;
      editCount = 0;
      $(document).ready(function() {
         autocompletesWraps.push('Default_location_name');
         loadMap(autocompletesWraps);
      });

      function loadMap(autocompletesWraps) {
         $.each(autocompletesWraps, function(index, name) {
            const geocoder = new google.maps.Geocoder;
            if ($('#' + name).length == 0) {
               return;
            }
            autocomplete[name] = new google.maps.places.Autocomplete(document.getElementById(name), {
               types: ['geocode']
            });
            google.maps.event.addListener(autocomplete[name], 'place_changed', function() {
               var place = autocomplete[name].getPlace();
               geocoder.geocode({
                  'placeId': place.place_id
               }, function(results, status) {
                  if (status === google.maps.GeocoderStatus.OK) {
                     const lat = results[0].geometry.location.lat();
                     const lng = results[0].geometry.location.lng();
                     document.getElementById('Default_latitude').value = lat;
                     document.getElementById('Default_longitude').value = lng;
                  }
               });
            });
         });

      }
      $('#show-map-modal').on('hide.bs.modal', function() {
         $('#add-customer-modal').removeClass('fadeIn');

      });

      $(document).on('click', '.showMap', function() {
         var no = $(this).attr('num');
         var lats = document.getElementById('Default_latitude').value;
         var lngs = document.getElementById('Default_longitude').value;

         document.getElementById('map_for').value = no;

         if (lats == null || lats == '0') {
            lats = 30.53899440;
         }
         if (lngs == null || lngs == '0') {
            lngs = 75.95503290;
         }

         var myLatlng = new google.maps.LatLng(lats, lngs);
         var mapProp = {
            center: myLatlng,
            zoom: 13,
            mapTypeId: google.maps.MapTypeId.ROADMAP

         };
         var map = new google.maps.Map(document.getElementById("googleMap"), mapProp);
         var marker = new google.maps.Marker({
            position: myLatlng,
            map: map,
            title: 'Hello World!',
            draggable: true
         });
         document.getElementById('lat_map').value = lats;
         document.getElementById('lng_map').value = lngs;
         google.maps.event.addListener(marker, 'drag', function(event) {
            document.getElementById('lat_map').value = event.latLng.lat();
            document.getElementById('lng_map').value = event.latLng.lng();
         });

         google.maps.event.addListener(marker, 'dragend', function(event) {
            var zx = JSON.stringify(event);
            console.log(zx);


            document.getElementById('lat_map').value = event.latLng.lat();
            document.getElementById('lng_map').value = event.latLng.lng();
         });
         $('#add-customer-modal').addClass('fadeIn');
         $('#show-map-modal').modal({
            keyboard: false
         });

      });

      $(document).on('click', '.selectMapLocation', function() {

         var mapLat = document.getElementById('lat_map').value;
         var mapLlng = document.getElementById('lng_map').value;
         var mapFor = document.getElementById('map_for').value;

         document.getElementById('Default_latitude').value = mapLat;
         document.getElementById('Default_longitude').value = mapLlng;

         $('#show-map-modal').modal('hide');
      });


      var hyprlocal = $('#is_hyperlocal');
      if(hyprlocal.length > 0){
         hyprlocal[0].onchange = function() {

         if ($('#is_hyperlocal:checked').length != 1) {
            $('.disableHyperLocal').hide();
         } else {
            $('.disableHyperLocal').show();
         }
         }
      }

      var delivery_service = $('#need_delivery_service');
      var dispatcherDiv = $('#need_dispacher_ride');
      var need_dispacher_home_other_service = $('#need_dispacher_home_other_service');
      var laundry_service = $('#need_laundry_service');

      if(delivery_service.length > 0){
         delivery_service[0].onchange = function() {

            if ($('#need_delivery_service:checked').length != 1) {
               $('.deliveryServiceFields').hide();
            } else {
               $('.deliveryServiceFields').show();
            }
         }
      }

      if(laundry_service.length > 0){
         laundry_service[0].onchange = function() {

            if ($('#need_laundry_service:checked').length != 1) {
               $('.laundryServiceFields').hide();
            } else {
               $('.laundryServiceFields').show();
            }
         }
      }

      if(dispatcherDiv.length > 0){
         dispatcherDiv[0].onchange = function() {
            console.log('ok');
            if ($('#need_dispacher_ride:checked').length != 1) {
               $('.dispatcherFields').hide();
            } else {
               $('.dispatcherFields').show();
            }
         }
      }

      if(need_dispacher_home_other_service.length > 0){
         need_dispacher_home_other_service[0].onchange = function() {

         if ($('#need_dispacher_home_other_service:checked').length != 1) {
            $('.home_other_dispatcherFields').hide();
         } else {
            $('.home_other_dispatcherFields').show();
         }
         }
      }


      var fb_login = $('#fb_login');

      fb_login[0].onchange = function() {
         if ($('#fb_login:checked').length != 1) {
            $('.fb_row').hide();
         } else {
            $('.fb_row').show();
         }
      }

      var twitter_login = $('#twitter_login');

      twitter_login[0].onchange = function() {
         if ($('#twitter_login:checked').length != 1) {
            $('.twitter_row').hide();
         } else {
            $('.twitter_row').show();
         }
      }

      var google_login = $('#google_login');

      google_login[0].onchange = function() {
         if ($('#google_login:checked').length != 1) {
            $('.google_row').hide();
         } else {
            $('.google_row').show();
         }
      }

      var apple_login = $('#apple_login');

      apple_login[0].onchange = function() {

         if ($('#apple_login:checked').length != 1) {
            $('.apple_row').hide();
         } else {
            $('.apple_row').show();
         }
      }

      var dinein_option = $('#dinein_check');
      if(dinein_option.length > 0){
         dinein_option[0].onchange = function() {
         optionsChecked("dinein_check");
         }
      }

      var takeaway_option = $('#takeaway_check');
      if(takeaway_option.length > 0){
         takeaway_option[0].onchange = function() {
         optionsChecked("takeaway_check");
      }
      }

      var delivery_option = $('#delivery_check');
      if(delivery_option > 0){
         delivery_option[0].onchange = function() {
         optionsChecked("delivery_check");
         }
      }


      function optionsChecked(id) {
         var delivery_checked = $("#delivery_check").is(":checked");
         var takeaway_checked = $("#takeaway_check").is(":checked");
         var dinein_checked = $("#dinein_check").is(":checked");
         if (dinein_checked == false && takeaway_checked == false && delivery_checked == false) {
            Swal.fire({
               title: "Warning!",
               text: "One option must be enables",
               icon: "warning",
               button: "OK",
            });
            $("#" + id).trigger('click');
         }
      }
      $(document).ready(function() {
         smsChange();
      });
      function toggle_smsFields(obj)
      {
         smsChange();
         // var id = $(obj).find(':selected').attr('data-id');
         // $('.sms_fields').css('display','none');
         // $('#'+id).css('display','flex');
         // console.log(id);
      }
      function smsChange(){
         var id = $("#sms_provider").find(':selected').attr('data-id');
         $('.sms_fields').css('display','none');
         $('#'+id).css('display','flex');
         console.log(id);
      }
   </script>
   @endsection
