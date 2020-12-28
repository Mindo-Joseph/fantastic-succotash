@extends('layouts.vertical', ['title' => 'Configure'])

@section('content')

<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Settings</h4>
            </div>
        </div>
    </div>

    <form method="POST" action="{{route('configure.update', Auth::user()->code)}}">
        @csrf
        <div class="row">
            <div class="col-xl-11 col-md-offset-1">
                <div class="card-box">
                    <h4 class="header-title">Basic Website Customization</h4>
                    <p class="sub-header"></p>
                    <div class="row mb-0">

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="sms_provider">Country</label>
                                <select class="form-control" id="sms_provider" name="sms_provider">
                                    <option value="">Select Country</option>
                                    @foreach($currencies as $curr)
                                         <option value="{{$curr->id}}" {{ (isset($preference) && $preference->currency_id == $curr->id)? "selected" : "" }}> {{$curr->name}} </option>
                                    @endforeach
                                </select>
                                @if($errors->has('sms_provider'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('sms_provider') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="sms_provider">Language</label>
                                <select class="form-control" id="sms_provider" name="sms_provider">
                                    <option value="">Select Language</option>
                                    @foreach($languages as $lang)
                                         <option value="{{$lang->id}}" {{ (isset($preference) && $preference->language_id == $lang->id)? "selected" : "" }}> {{$lang->name}} </option>
                                    @endforeach
                                </select>
                                @if($errors->has('sms_provider'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('sms_provider') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="theme_admin">Theme Admin</label>
                                <select class="form-control" id="theme_admin" name="theme_admin">
                                    <option value="dark" {{(isset($preference) && $preference->theme_admin == 'dark')? "selected" : "" }}> Dark</option>
                                    <option value="light" {{(isset($preference) && $preference->theme_admin == 'light')? "selected" : "" }}> Light</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="distance_unit">Distance Unit</label>
                                <select class="form-control" id="distance_unit" name="distance_unit">
                                    <option value="KM" {{(isset($preference) && $preference->distance_unit == 'KM')? "selected" : "" }}>Kilometer</option>
                                    <option value="MILES" {{(isset($preference) && $preference->distance_unit == 'MILES')? "selected" : "" }}>Miles</option>
                                    <option value="METER" {{(isset($preference) && $preference->distance_unit == 'METER')? "selected" : "" }}>Meter</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="date_format">Date Format</label>
                                <select class="form-control" id="date_format" name="date_format">
                                    <option value="Y-m-d" {{(isset($preference) && $preference->date_format == 'Y-m-d')? "selected" : "" }}>yyyy-mm-dd</option>
                                    <option value="d-m-Y" {{(isset($preference) && $preference->date_format == 'd-m-Y')? "selected" : "" }}>dd-mm-yyyy</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="time_format">Time Format</label>
                                <select class="form-control" id="time_format" name="time_format">
                                    <option value="H:i" {{(isset($preference) && $preference->time_format == 'H:i')? "selected" : "" }}>24 Hour Format</option>
                                    <option value="h:i" {{(isset($preference) && $preference->time_format == 'h:i')? "selected" : "" }}>12 Hour Fromat</option>
                                    <option value="h:i,a" {{(isset($preference) && $preference->time_format == 'h:i,a')? "selected" : "" }}>12 Hour Fromat with AM/PM</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3 switchery-demo">
                                <label for="verify_email" class="mr-3">Apple Login</label>
                                <input type="checkbox" data-plugin="switchery" name="verify_email" id="verify_email" class="form-control" data-color="#039cfd" @if((isset($preference) && $preference->verify_email == '1'))  checked='checked' @endif>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3 ">
                                <label for="verify_phone" class="mr-3">Verify Phone</label>
                                <input type="checkbox" data-plugin="switchery" name="verify_phone" id="verify_phone" class="form-control" data-color="#039cfd" @if((isset($preference) && $preference->verify_phone == '1'))  checked='checked' @endif>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="Default_location_name">Default location name</label>
                                <input type="text" name="Default_location_name" id="Default_location_name" placeholder="Delhi, India" class="form-control"
                                    value="{{ old('Default_location_name', $preference->Default_location_name ?? '')}}">
                                @if($errors->has('Default_location_name'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('Default_location_name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="Default_latitude">Default latitude</label>
                                <input type="text" name="Default_latitude" id="Default_latitude" placeholder="24.9876755" class="form-control"
                                    value="{{ old('Default_latitude', $preference->Default_latitude ?? '')}}">
                                @if($errors->has('Default_latitude'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('Default_latitude') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="Default_longitude">Default longitude</label>
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
                    <div class="row mb-2">
                        <div class="col-md-2">
                            <input type="hidden" name="send_to" id="send_to" value="customize">
                            <div class="form-group mb-0 text-center">
                                <button class="btn btn-blue btn-block" type="submit"> Update </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div> <!-- container -->
@endsection

@section('script')

<script type="text/javascript">
/*function toggleDisplayCustomDomain(){
    $("#custom_domain_name").toggle( 'fast', function(){ 

    });
}*/

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

</script>

@endsection