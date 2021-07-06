@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Configurations'])
@section('css')
@endsection
@section('content')

<div class="container-fluid">
   <div class="row">
      <div class="col-12">
         <div class="page-title-box">
            <h4 class="page-title text-uppercase">Configurations</h4>
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
         <div class="col-lg-4 col-md-6 mb-3">
            <div class="row h-100">
               <div class="col-12">
                  <div class="card-box h-100">
                     <div class="d-flex align-items-center justify-content-between mb-2">
                        <h4 class="header-title mb-0">Hyperlocal</h4>
                        <button class="btn btn-info d-block" type="submit"> Save </button>
                     </div>
                     <p class="sub-header">Enable location based visibility of Vendors and set the Default Location.</p>
                     <input type="hidden" name="hyperlocals" id="hyperlocals" value="1">

                     <div class="row">
                        <div class="col-12">
                           <div class="form-group mb-0">
                              <label for="is_hyperlocal" class="mr-3">Enable</label>
                              <input type="checkbox" data-plugin="switchery" name="is_hyperlocal" id="is_hyperlocal" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->is_hyperlocal == '1')) checked @endif>
                           </div>
                           <div class="row">
                              <div class="col-12 mt-3 disableHyperLocal" style="{{((isset($preference) && $preference->is_hyperlocal == '1')) ? '' : 'display:none;'}}">
                                 <div class="row">
                                    <div class="col-12">
                                       <div class="form-group mb-0">
                                          <label for="Default_location_name">Default Location</label>
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
                                          <label for="Default_latitude">Latitude</label>
                                          <input type="text" name="Default_latitude" id="Default_latitude" placeholder="24.9876755" class="form-control"
                                             value="{{ old('Default_latitude', $preference->Default_latitude ?? '')}}">
                                          @if($errors->has('Default_latitude'))
                                          <span class="text-danger" role="alert">
                                          <strong>{{ $errors->first('Default_latitude') }}</strong>
                                          </span>
                                          @endif
                                       </div>
                                       <div class="form-group mt-3 mb-0">
                                          <label for="Default_longitude">Longitude</label>
                                          <input type="text" name="Default_longitude" id="Default_longitude" placeholder="11.9871371723" class="form-control"
                                             value="{{ old('Default_longitude', $preference->Default_longitude ?? '')}}">
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
         <div class="col-lg-4 col-md-6 mb-3">
            <div class="card-box h-100">
               <div class="d-flex align-items-center justify-content-between mb-2">
                  <h4 class="header-title mb-0">Last Mile Delivery</h4>
                  <button class="btn btn-info d-block" type="submit"> Save </button>
               </div>
               <p class="sub-header">Offer Last Mile Delivery with Royo Dispatcher.</p>
               <div class="row">
                  <div class="col-12">
                     <div class="form-group mb-0">
                        <div class="form-group mb-0 switchery-demo">
                           <label for="need_delivery_service" class="mr-3">Enable</label>
                           <input data-plugin="switchery" name="need_delivery_service" id="need_delivery_service" class="form-control" data-color="#43bee1" type="checkbox" @if((isset($preference) && $preference->need_delivery_service == '1')) checked @endif >
                        </div>
                     </div>

                     <div class="form-group mt-3 mb-0 deliveryServiceFields" style="{{((isset($preference) && $preference->need_delivery_service == '1')) ? '' : 'display:none;'}}">
                        <label for="delivery_service_key_url">Royo Dispatcher Url</label>
                        <input type="text" name="delivery_service_key_url" id="delivery_service_key_url" placeholder="" class="form-control" value="{{ old('delivery_service_key_url', $preference->delivery_service_key_url ?? '')}}">
                        @if($errors->has('delivery_service_key_url'))
                        <span class="text-danger" role="alert">
                        <strong>{{ $errors->first('delivery_service_key_url') }}</strong>
                        </span>
                        @endif
                     </div>

                     <div class="form-group mt-3 mb-0 deliveryServiceFields" style="{{((isset($preference) && $preference->need_delivery_service == '1')) ? '' : 'display:none;'}}">
                        <label for="delivery_service_key_code">Royo Dispatcher Short code</label>
                        <input type="text" name="delivery_service_key_code" id="delivery_service_key_code" placeholder="" class="form-control" value="{{ old('delivery_service_key_code', $preference->delivery_service_key_code ?? '')}}">
                        @if($errors->has('delivery_service_key_code'))
                        <span class="text-danger" role="alert">
                        <strong>{{ $errors->first('delivery_service_key_code') }}</strong>
                        </span>
                        @endif
                     </div>

                     <div class="form-group mt-3 mb-0 deliveryServiceFields" style="{{((isset($preference) && $preference->need_delivery_service == '1')) ? '' : 'display:none;'}}">
                        <label for="delivery_service_key">Royo Dispatcher API key</label>
                        <input type="text" name="delivery_service_key" id="delivery_service_key" placeholder="" class="form-control" value="{{ old('delivery_service_key', $preference->delivery_service_key ?? '')}}">
                        @if($errors->has('delivery_service_key'))
                        <span class="text-danger" role="alert">
                        <strong>{{ $errors->first('delivery_service_key') }}</strong>
                        </span>
                        @endif
                     </div>
                     
                  </div>
               </div>
            </div>
         </div>
         <div class="col-lg-4 col-md-6 mb-3">
            <div class="card-box h-100">
               <div class="d-flex align-items-center justify-content-between mb-2">
                  <h4 class="header-title mb-0">Pickup & Delivery</h4>
                  <button class="btn btn-info d-block" type="submit"> Save </button>
               </div>
               <p class="sub-header">Offer Pickup & Delivery with Royo Dispatcher.</p>
               <div class="row">
                  <div class="col-12">
                     <div class="form-group mb-0">
                        <div class="form-group mb-0 switchery-demo">
                           <label for="need_dispacher_ride" class="mr-3">Enable</label>
                           <input type="checkbox" data-plugin="switchery" name="need_dispacher_ride" id="need_dispacher_ride" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->need_dispacher_ride == '1'))  checked='checked' @endif>
                        </div>
                     </div>

                     <div class="form-group mt-3 mb-0 dispatcherFields" style="{{((isset($preference) && $preference->need_dispacher_ride == '1')) ? '' : 'display:none;'}}">
                        <label for="pickup_delivery_service_key_url">Royo Dispatcher Url</label>
                        <input type="text" name="pickup_delivery_service_key_url" id="pickup_delivery_service_key_url" placeholder="" class="form-control" value="{{ old('pickup_delivery_service_key_url', $preference->pickup_delivery_service_key_url ?? '')}}">
                        @if($errors->has('pickup_delivery_service_key_url'))
                        <span class="text-danger" role="alert">
                        <strong>{{ $errors->first('pickup_delivery_service_key_url') }}</strong>
                        </span>
                        @endif
                     </div>

                     <div class="form-group mt-3 mb-0 dispatcherFields" style="{{((isset($preference) && $preference->need_dispacher_ride == '1')) ? '' : 'display:none;'}}">
                        <label for="delivery_service_key_code">Royo Dispatcher Short code</label>
                        <input type="text" name="pickup_delivery_service_key_code" id="pickup_delivery_service_key_code" placeholder="" class="form-control" value="{{ old('pickup_delivery_service_key_code', $preference->pickup_delivery_service_key_code ?? '')}}">
                        @if($errors->has('pickup_delivery_service_key_code'))
                        <span class="text-danger" role="alert">
                        <strong>{{ $errors->first('pickup_delivery_service_key_code') }}</strong>
                        </span>
                        @endif
                     </div>

                     <div class="form-group mt-3 mb-0 dispatcherFields" style="{{((isset($preference) && $preference->need_dispacher_ride == '1')) ? '' : 'display:none;'}}">
                        <label for="pickup_delivery_service_key">Royo Dispatcher API key</label>
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
      </div>
   </form>
   <div class="row">
      <div class="col-12">
         <div class="page-title-box">
            <h4 class="page-title text-uppercase">Social Logins</h4>
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
                           <h5 class="social_head"><i class="fab fa-facebook-f"></i> <span>Facebook</span></h5>
                           <button class="btn btn-info btn-block save_btn" type="submit"> Save </button>
                        </label>
                        <label for="" class="mr-3">Enable</label>
                        <input type="checkbox" data-plugin="switchery" name="fb_login" id="fb_login" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->fb_login == '1'))  checked='checked' @endif>
                     </div>
                  </div>
               </div>
               <div class="row fb_row" style="{{((isset($preference) && $preference->fb_login == '1')) ? '' : 'display:none;'}}">
                  <div class="col-12">
                     <div class="form-group mb-2 mt-2">
                        <label for="fb_client_id">Facebook Client Key</label>
                        <input type="text" name="fb_client_id" id="fb_client_id" placeholder=""
                           class="form-control" value="{{ old('fb_client_id', $preference->fb_client_id ?? '')}}">
                        @if($errors->has('fb_client_id'))
                        <span class="text-danger" role="alert">
                        <strong>{{ $errors->first('fb_client_id') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-12">
                     <div class="form-group mb-2">
                        <label for="fb_client_secret">Facebook Client Secret</label>
                        <input type="text" name="fb_client_secret" id="fb_client_secret" placeholder=""
                           class="form-control" value="{{ old('fb_client_secret', $preference->fb_client_secret ?? '')}}">
                        @if($errors->has('fb_client_secret'))
                        <span class="text-danger" role="alert">
                        <strong>{{ $errors->first('fb_client_secret') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-12">
                     <div class="form-group mb-0">
                        <label for="fb_client_url">Facebook Redirect Url</label>
                        <input type="text" name="fb_client_url" id="fb_client_url" placeholder=""
                           class="form-control" value="{{ old('fb_client_url', $preference->fb_client_url ?? '')}}">
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
                           <button class="btn btn-info btn-block save_btn" type="submit"> Save </button>
                        </label>
                        <label for="" class="mr-3">Enable</label>
                        <input type="checkbox" data-plugin="switchery" name="twitter_login" id="twitter_login" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->twitter_login == '1'))  checked='checked' @endif>
                     </div>
                  </div>
               </div>
               <div class="row  twitter_row" style="{{((isset($preference) && $preference->twitter_login == '1')) ? '' : 'display:none;'}}">
                  <div class="col-12">
                     <div class="form-group mb-2 mt-2">
                        <label for="twitter_client_id">Twitter Client Key</label>
                        <label for="" class="mr-3">Enable</label>
                        <input type="text" name="twitter_client_id" id="twitter_client_id" placeholder=""
                           class="form-control" value="{{ old('twitter_client_id', $preference->twitter_client_id ?? '')}}">
                        @if($errors->has('twitter_client_id'))
                        <span class="text-danger" role="alert">
                        <strong>{{ $errors->first('twitter_client_id') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-12">
                     <div class="form-group mb-2">
                        <label for="twitter_client_secret">Twitter Client Secret</label>
                        <input type="text" name="twitter_client_secret" id="twitter_client_secret" placeholder=""
                           class="form-control" value="{{ old('twitter_client_secret', $preference->twitter_client_secret ?? '')}}">
                        @if($errors->has('twitter_client_secret'))
                        <span class="text-danger" role="alert">
                        <strong>{{ $errors->first('twitter_client_secret') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-12">
                     <div class="form-group mb-0">
                        <label for="twitter_client_url">Twitter Redirect Url</label>
                        <input type="text" name="twitter_client_url" id="twitter_client_url" placeholder=""
                           class="form-control" value="{{ old('twitter_client_url', $preference->twitter_client_url ?? '')}}">
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
                           <button class="btn btn-info btn-block save_btn" type="submit"> Save </button>
                        </label>
                        <label for="" class="mr-3">Enable</label>
                        <input type="checkbox" data-plugin="switchery" name="google_login" id="google_login" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->google_login == '1'))  checked='checked' @endif>
                     </div>
                  </div>
               </div>
               <div class="row google_row" style="{{((isset($preference) && $preference->google_login == '1')) ? '' : 'display:none;'}}">
                  <div class="col-md-12">
                     <div class="form-group mb-2 mt-2">
                        <label for="google_client_id">Google Client Key</label>
                        <input type="text" name="google_client_id" id="google_client_id" placeholder=""
                           class="form-control" value="{{ old('google_client_id', $preference->google_client_id ?? '')}}">
                        @if($errors->has('google_client_id'))
                        <span class="text-danger" role="alert">
                        <strong>{{ $errors->first('google_client_id') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="form-group mb-2">
                        <label for="google_client_secret">Google Client Secret</label>
                        <input type="text" name="google_client_secret" id="google_client_secret" placeholder=""
                           class="form-control" value="{{ old('google_client_secret', $preference->google_client_secret ?? '')}}">
                        @if($errors->has('google_client_secret'))
                        <span class="text-danger" role="alert">
                        <strong>{{ $errors->first('google_client_secret') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="form-group mb-0">
                        <label for="google_client_url">Google Redirect Url</label>
                        <input type="text" name="google_client_url" id="google_client_url" placeholder=""
                           class="form-control" value="{{ old('google_client_url', $preference->google_client_url ?? '')}}">
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
                           <button class="btn btn-info btn-block save_btn" type="submit"> Save </button>
                        </label>
                        <label for="" class="mr-3">Enable</label>
                        <input type="checkbox" data-plugin="switchery" name="apple_login" id="apple_login" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->apple_login == '1'))  checked='checked' @endif>
                     </div>
                  </div>
               </div>
               <div class="row apple_row" style="{{((isset($preference) && $preference->apple_login == '1')) ? '' : 'display:none;'}}">
                  <div class="col-12">
                     <div class="form-group mb-2 mt-2">
                        <label for="apple_client_id">Apple Client Key</label>
                        <input type="text" name="apple_client_id" id="apple_client_id" placeholder=""
                           class="form-control" value="{{ old('apple_client_id', $preference->apple_client_id ?? '')}}">
                        @if($errors->has('apple_client_id'))
                        <span class="text-danger" role="alert">
                        <strong>{{ $errors->first('apple_client_id') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-12">
                     <div class="form-group mb-2">
                        <label for="apple_client_secret">Apple Client Secret</label>
                        <input type="text" name="apple_client_secret" id="apple_client_secret" placeholder=""
                           class="form-control" value="{{ old('apple_client_secret', $preference->apple_client_secret ?? '')}}">
                        @if($errors->has('apple_client_secret'))
                        <span class="text-danger" role="alert">
                        <strong>{{ $errors->first('apple_client_secret') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-12">
                     <div class="form-group mb-0">
                        <label for="apple_client_url"> Apple Redirect Url</label>
                        <input type="text" name="apple_client_url" id="apple_client_url" placeholder=""
                           class="form-control" value="{{ old('apple_client_url', $preference->apple_client_url ?? '')}}">
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
            <h4 class="page-title text-uppercase">Map Sms Emails</h4>
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-xl-3 mb-3">
         <form class="h-100" method="POST" action="{{route('configure.update', Auth::user()->code)}}">
            @csrf
            <div class="card-box h-100 h-100">
               <h4 class="header-title text-uppercase text-uppercase">Map Configuration</h4>
               <p class="sub-header">
                  View and update your Map type and it's API key.
               </p>
               <div class="row">
                  <div class="col-12">
                     <div class="form-group mb-3">
                        <label for="currency">MAP PROVIDER</label>
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
                     <div class="form-group mb-3">
                        <label for="map_key">API KEY</label>
                        <input type="text" name="map_key" id="map_key" placeholder=""
                           class="form-control" value="{{ old('map_key', $preference->map_key ?? '')}}">
                        @if($errors->has('map_key'))
                        <span class="text-danger" role="alert">
                        <strong>{{ $errors->first('map_key') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group mb-0 text-center">
                        <button class="btn btn-info btn-block" type="submit"> Save </button>
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
               <h4 class="header-title text-uppercase">SMS Configuration</h4>
               <p class="sub-header">View and update your SMS Gateway and it's API keys.</p>
               <div class="row mb-0">
                  <div class="col-12">
                     <div class="form-group mb-3">
                        <label for="sms_provider">SMS PROVIDER</label>
                        <select class="form-control" id="sms_provider" name="sms_provider">
                        @foreach($smsTypes as $sms)
                        <option value="{{$sms->id}}" {{ (isset($preference) && $preference->sms_provider == $sms->id)? "selected" : "" }}> {{$sms->provider}} </option>
                        @endforeach
                        </select>
                        @if($errors->has('sms_provider'))
                        <span class="text-danger" role="alert">
                        <strong>{{ $errors->first('sms_provider') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-12">
                     <div class="form-group mb-3">
                        <label for="sms_from">SMS From</label>
                        <input type="text" name="sms_from" id="sms_from" placeholder="" class="form-control"
                           value="{{ old('sms_from', $preference->sms_from ?? '')}}">
                        @if($errors->has('sms_from'))
                        <span class="text-danger" role="alert">
                        <strong>{{ $errors->first('sms_from') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-12">
                     <div class="form-group mb-3">
                        <label for="sms_key">API KEY</label>
                        <input type="text" name="sms_key" id="sms_key" placeholder="" class="form-control"
                           value="{{ old('sms_key', $preference->sms_key ?? '')}}">
                        @if($errors->has('sms_key'))
                        <span class="text-danger" role="alert">
                        <strong>{{ $errors->first('sms_key') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-12">
                     <div class="form-group mb-3">
                        <label for="sms_secret">API Secret</label>
                        <input type="text" name="sms_secret" id="sms_secret" placeholder="" class="form-control"
                           value="{{ old('sms_secret', $preference->sms_secret ?? '')}}">
                        @if($errors->has('sms_secret'))
                        <span class="text-danger" role="alert">
                        <strong>{{ $errors->first('sms_secret') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group mb-0 text-center">
                        <button class="btn btn-info btn-block" type="submit"> Save </button>
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
               <h4 class="header-title text-uppercase">Mail Configuration</h4>
               <p class="sub-header"> View and update your SMTP credentials.</p>
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group mb-3">
                        <label for="mail_type">Mail Type</label>
                        <input type="text" name="mail_type" id="mail_type" placeholder="SMTP"
                           class="form-control" value="{{ old('mail_type', $preference->mail_type ?? '')}}">
                        @if($errors->has('mail_type'))
                        <span class="text-danger" role="alert">
                        <strong>{{ $errors->first('mail_type') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group mb-3">
                        <label for="mail_driver">Mail Driver</label>
                        <input type="text" name="mail_driver" id="mail_driver" placeholder=""
                           class="form-control" value="{{ old('mail_driver', $preference->mail_driver ?? '')}}">
                        @if($errors->has('mail_driver'))
                        <span class="text-danger" role="alert">
                        <strong>{{ $errors->first('mail_driver') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group mb-3">
                        <label for="mail_host">Mail Host</label>
                        <input type="text" name="mail_host" id="mail_host" placeholder="SMTP"
                           class="form-control" value="{{ old('mail_host', $preference->mail_host ?? '')}}">
                        @if($errors->has('mail_host'))
                        <span class="text-danger" role="alert">
                        <strong>{{ $errors->first('mail_host') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group mb-3">
                        <label for="mail_port">Mail Port</label>
                        <input type="text" name="mail_port" id="mail_port" placeholder=""
                           class="form-control" value="{{ old('mail_port', $preference->mail_port ?? '')}}">
                        @if($errors->has('mail_port'))
                        <span class="text-danger" role="alert">
                        <strong>{{ $errors->first('mail_port') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group mb-3">
                        <label for="mail_username">Mail Username</label>
                        <input type="text" name="mail_username" id="mail_username" placeholder="username"
                           class="form-control" value="{{ old('mail_username', $preference->mail_username ?? '')}}">
                        @if($errors->has('mail_username'))
                        <span class="text-danger" role="alert">
                        <strong>{{ $errors->first('mail_username') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group mb-3">
                        <label for="mail_password">Mail Password</label>
                        <input type="text" name="mail_password" id="mail_password" placeholder=""
                           class="form-control" value="{{ old('mail_password', $preference->mail_password ?? '')}}">
                        @if($errors->has('mail_password'))
                        <span class="text-danger" role="alert">
                        <strong>{{ $errors->first('mail_password') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group mb-3">
                        <label for="mail_encryption">Mail Encryption</label>
                        <input type="text" name="mail_encryption" id="mail_encryption" placeholder="username"
                           class="form-control" value="{{ old('mail_encryption', $preference->mail_encryption ?? '')}}">
                        @if($errors->has('mail_encryption'))
                        <span class="text-danger" role="alert">
                        <strong>{{ $errors->first('mail_encryption') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group mb-3">
                        <label for="mail_from">Mail From</label>
                        <input type="text" name="mail_from" id="mail_from" placeholder="service@xyz.com"
                           class="form-control" value="{{ old('mail_from', $preference->mail_from ?? '')}}">
                        @if($errors->has('mail_from'))
                        <span class="text-danger" role="alert">
                        <strong>{{ $errors->first('mail_from') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
               </div>
               <div class="row mb-2">
                  <div class="col-md-2">
                     <div class="form-group mb-0 text-center">
                        <button class="btn btn-info d-block" type="submit"> Save </button>
                     </div>
                  </div>
               </div>
            </div>
         </form>
      </div>
   </div>
   <div class="row">
      <div class="col-12">
         <div class="page-title-box">
            <h4 class="page-title text-uppercase">User Authentication</h4>
         </div>
      </div>
   </div>
   <form method="POST" action="{{route('configure.update', Auth::user()->code)}}">
      <input type="hidden" name="verify_config" id="verify_config" value="1">
      @csrf
      <div class="row">
         <div class="col-md-6 col-lx-4">
            <div class="card-box">
               <div class="row align-items-center">
                  <div class="col-sm-5">
                     <div class="form-group mb-0 switchery-demo">
                        <label for="verify_email" class="mr-3 mb-0">Verify Email</label>
                        <input type="checkbox" data-plugin="switchery" name="verify_email" id="verify_email" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->verify_email == '1'))  checked='checked' @endif>
                     </div>
                  </div>
                  <div class="col-sm-5">
                     <div class="form-group mb-0">
                        <label for="verify_phone" class="mr-3 mb-0">Verify Phone</label>
                        <input type="checkbox" data-plugin="switchery" name="verify_phone" id="verify_phone" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->verify_phone == '1'))  checked='checked' @endif>
                     </div>
                  </div>
                  <div class="col-sm-2">
                     <div class="form-group mb-0 text-md-right">
                        <button class="btn btn-info d-block ml-auto" type="submit"> Save </button>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-md-6 col-lx-4">
            <div class="card-box">
               <div class="row align-items-center">
                  <div class="col-md-8">
                     <div class="form-group mb-0">
                        <label for="celebrity_check" class="mr-3 mb-0">Influencer Mod</label>
                        <input type="checkbox" data-plugin="switchery" name="celebrity_check" id="celebrity_check" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->celebrity_check == '1'))  checked='checked' @endif>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group mb-0 text-md-right">
                        <button class="btn btn-info d-block ml-md-auto" type="submit"> Save </button>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-md-6 col-lx-4">
            <div class="card-box">
               <div class="row align-items-center">
                  <div class="col-md-8">
                     <div class="form-group mb-0">
                        <label for="pharmacy_check" class="mr-3 mb-0">Pharmacy Mod</label>
                        <input type="checkbox" data-plugin="switchery" name="pharmacy_check" id="pharmacy_check" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->pharmacy_check == '1'))  checked='checked' @endif>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group mb-0 text-md-right">
                        <button class="btn btn-info d-block ml-md-auto" type="submit"> Save </button>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-md-6 col-lx-4">
            <div class="card-box">
               <div class="row align-items-center">
                  <div class="col-md-3">
                     <div class="form-group mb-0">
                        <label for="dinein_check" class="mr-3 mb-0">Dine In</label>
                        <input type="checkbox" data-plugin="switchery" name="dinein_check" id="dinein_check" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->dinein_check == '1'))  checked='checked' @endif>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group mb-0">
                        <label for="delivery_check" class="mr-3 mb-0">Delivery</label>
                        <input type="checkbox" data-plugin="switchery" name="delivery_check" id="delivery_check" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->delivery_check == '1'))  checked='checked' @endif>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group mb-0">
                        <label for="takeaway_check" class="mr-3 mb-0">Takeaway</label>
                        <input type="checkbox" data-plugin="switchery" name="takeaway_check" id="takeaway_check" class="form-control" data-color="#43bee1" @if((isset($preference) && $preference->takeaway_check == '1'))  checked='checked' @endif>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group mb-0 text-md-right">
                        <button class="btn btn-info d-block ml-md-auto" type="submit"> Save </button>
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
                  <h4 class="header-title text-uppercase">Email</h4>
                  <p class="sub-header">
                     Choose Email paid plan to whitelable "From email address" and "Sender Name" in the Email sent
                     out
                     from your account.
                  </p>
                  <div class="row mb-0">
                     <div class="col-md-6">
                        <div class="form-group mb-3">
                           <label for="email_plan">CURRENT SELECTION</label>
                           <select class="form-control" id="email_plan" name="email_plan">
                              <option>Select Plan</option>
                              <option value="free"
                              {{ (isset($preference) && $preference->email_plan =="free")? "selected" : "" }}>
                              Free</option>
                              <option value="paid"
                              {{ (isset($preference) && $preference->email_plan =="paid")? "selected" : "" }}>
                              Paid</option>
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
                           <label for="sms_service_api_key">PREVIEW</label>
                           <div class="card">
                              <div class="card-body">
                                 <p class="mb-2"><span class="font-weight-semibold mr-2">From:</span>
                                    johndoe<span>
                                    << /span>contact@royodispatcher.com<span>></span>
                                 </p>
                                 <p class="mb-2"><span class="font-weight-semibold mr-2">Reply To:</span>
                                    johndoe@gmail.com
                                 </p>
                                 <p class="mt-3 text-center">
                                    Your message hore here..
                                 </p>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="row mb-2">
                     <div class="col-md-2">
                        <div class="form-group mb-0 text-center">
                           <button class="btn btn-info btn-block" type="submit"> Save </button>
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
         <div class="modal-header">
            <h4 class="modal-title">Select Location</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
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
            <button type="submit" class="btn btn-info waves-effect waves-light selectMapLocation">Ok</button>
         </div>
      </div>
   </div>
</div>
@endsection
@section('script')
<script type="text/javascript">
   
   function generateRandomString(length) {
     var text = "";
     var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
      
     for (var i = 0; i < length; i++)
       text += possible.charAt(Math.floor(Math.random() * possible.length));
      
     return text;
   }
   
   function genrateKeyAndToken(){
       var key = generateRandomString(30);
       var token = generateRandomString(60);
   
       $('#personal_access_token_v1').val(key);
       $('#personal_access_token_v2').val(token);
   }
   
   var autocomplete = {};
       var autocompletesWraps = [];
       var count = 1; editCount = 0;
   $(document).ready(function(){
       autocompletesWraps.push('Default_location_name');
       loadMap(autocompletesWraps);
   });
   
   function loadMap(autocompletesWraps){
   
       $.each(autocompletesWraps, function(index, name) {
           const geocoder = new google.maps.Geocoder;
       
           if($('#'+name).length == 0) {
               return;
           }
           autocomplete[name] = new google.maps.places.Autocomplete(document.getElementById(name), { types: ['geocode'] });
               
           google.maps.event.addListener(autocomplete[name], 'place_changed', function() {
               
               var place = autocomplete[name].getPlace();
   
               geocoder.geocode({'placeId': place.place_id}, function (results, status) {
                   
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
   $('#show-map-modal').on('hide.bs.modal', function () {
        $('#add-customer-modal').removeClass('fadeIn');
   
   });
   
   $(document).on('click', '.showMap', function(){
       var no = $(this).attr('num');
       var lats = document.getElementById('Default_latitude').value;
       var lngs = document.getElementById('Default_longitude').value;
   
       document.getElementById('map_for').value = no;
   
       if(lats == null || lats == '0'){
           lats = 30.53899440;
       }
       if(lngs == null || lngs == '0'){
           lngs = 75.95503290;
       }
   
       var myLatlng = new google.maps.LatLng(lats, lngs);
           var mapProp = {
               center:myLatlng,
               zoom:13,
               mapTypeId:google.maps.MapTypeId.ROADMAP
             
           };
           var map=new google.maps.Map(document.getElementById("googleMap"), mapProp);
               var marker = new google.maps.Marker({
                 position: myLatlng,
                 map: map,
                 title: 'Hello World!',
                 draggable:true  
             });
           document.getElementById('lat_map').value= lats;
           document.getElementById('lng_map').value= lngs ; 
           google.maps.event.addListener(marker,'drag',function(event) {
               document.getElementById('lat_map').value = event.latLng.lat();
               document.getElementById('lng_map').value = event.latLng.lng();
           });
   
           google.maps.event.addListener(marker,'dragend',function(event) {
               var zx =JSON.stringify(event);
               console.log(zx);
   
   
               document.getElementById('lat_map').value = event.latLng.lat();
               document.getElementById('lng_map').value = event.latLng.lng();
           });
           $('#add-customer-modal').addClass('fadeIn');
       $('#show-map-modal').modal({
           keyboard: false
       });
   
   });
   
   $(document).on('click', '.selectMapLocation', function () {
   
       var mapLat = document.getElementById('lat_map').value;
       var mapLlng = document.getElementById('lng_map').value;
       var mapFor = document.getElementById('map_for').value;
   
       document.getElementById('Default_latitude').value = mapLat;
       document.getElementById('Default_longitude').value = mapLlng;
   
       $('#show-map-modal').modal('hide');
   });
   
   
   var hyprlocal = $('#is_hyperlocal');
   
   hyprlocal[0].onchange = function() {
   
       if($('#is_hyperlocal:checked').length != 1){
           $('.disableHyperLocal').hide();
       }else{
           $('.disableHyperLocal').show();
       }
   }
   
   var delivery_service = $('#need_delivery_service');
   var dispatcherDiv = $('#need_dispacher_ride');
   
   delivery_service[0].onchange = function() {
   
       if($('#need_delivery_service:checked').length != 1){
           $('.deliveryServiceFields').hide();
       }else{
           $('.deliveryServiceFields').show();
       }
   }
   
   dispatcherDiv[0].onchange = function() {
   
       if($('#need_dispacher_ride:checked').length != 1){
           $('.dispatcherFields').hide();
       }else{
           $('.dispatcherFields').show();
       }
   }
   
   
   var hyprlocal = $('#fb_login');
   
   hyprlocal[0].onchange = function() {
       if($('#fb_login:checked').length != 1){
           $('.fb_row').hide();
       }else{
           $('.fb_row').show();
       }
   }
   
   var hyprlocal = $('#twitter_login');
   
   hyprlocal[0].onchange = function() {
       if($('#twitter_login:checked').length != 1){
           $('.twitter_row').hide();
       }else{
           $('.twitter_row').show();
       }
   }
   
   var hyprlocal = $('#google_login');
   
   hyprlocal[0].onchange = function() {
       if($('#google_login:checked').length != 1){
           $('.google_row').hide();
       }else{
           $('.google_row').show();
       }
   }
   
   var hyprlocal = $('#apple_login');
   
   hyprlocal[0].onchange = function() {
   
       if($('#apple_login:checked').length != 1){
           $('.apple_row').hide();
       }else{
           $('.apple_row').show();
       }
   }

   var dinein_option = $('#dinein_check');
   dinein_option[0].onchange = function() {
      optionsChecked("dinein_check");
   }
   var takeaway_option = $('#takeaway_check');
   takeaway_option[0].onchange = function() {
      optionsChecked("takeaway_check");
   }
   var delivery_option = $('#delivery_check');
   delivery_option[0].onchange = function() {
      optionsChecked("delivery_check");
   }

   function optionsChecked(id){
      var delivery_checked = $("#delivery_check").is(":checked");
      var takeaway_checked = $("#takeaway_check").is(":checked");
      var dinein_checked = $("#dinein_check").is(":checked");
      if(dinein_checked == false && takeaway_checked == false && delivery_checked == false){
         alert("One option must be enables");
         $("#"+id).trigger('click');
      }
   }
</script>
@endsection