@extends('layouts.vertical', ['title' => 'Configure'])

@section('css')
    
@endsection
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
    <!-- end page title -->

    <form method="POST" action="{{route('configure.update', Auth::user()->code)}}">
        @csrf
        <div class="row">
            <div class="col-xl-11 col-md-offset-1">
                <div class="card-box">
                    <h4 class="header-title">Map Configuration</h4>
                    <p class="sub-header">
                        View and update your Map type and it's API key.
                    </p>
                    <div class="row mb-2">
                        <div class="col-sm-8">
                            <div class="text-sm-left">
                                @if (\Session::has('success'))
                                <div class="alert alert-success">
                                    <span>{!! \Session::get('success') !!}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="currency">MAP PROVIDER</label>
                                <select class="form-control" id="map_provider" name="map_provider">
                                    <option value=""> Select Map</option>
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
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="map_key">API KEY</label>
                                <input type="text" name="map_key" id="map_key" placeholder="kjadsasd66asdas"
                                    class="form-control" value="{{ old('map_key', $preference->map_key ?? '')}}">
                                @if($errors->has('map_key'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('map_key') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    {{-- <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="map_key_1">API KEY</label>
                                <input type="text" name="map_key_1" id="map_key_1" placeholder="kjadsasd66asdas"
                                    class="form-control" value="{{ old('map_key_1', $preference->map_key_1 ?? '')}}">
                                @if($errors->has('map_key_1'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('map_key_1') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="map_secret">TRACKING LINK API KEY</label>
                                <input type="text" name="map_secret" id="map_secret" placeholder="No key added.."
                                    class="form-control" value="{{ old('map_secret', $preference->map_secret ?? '')}}">
                                @if($errors->has('map_secret'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('map_secret') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div> --}}
                    <div class="row mb-2">
                        <div class="col-md-2">
                            <div class="form-group mb-0 text-center">
                                <button class="btn btn-blue btn-block" type="submit"> Update </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <form method="POST" action="{{route('configure.update', Auth::user()->code)}}">
        @csrf
        <div class="row">
            <div class="col-xl-11 col-md-offset-1">
                <div class="card-box">
                    <h4 class="header-title">SMS</h4>
                    <p class="sub-header">
                        Choose between multiple SMS gateways available for ready use or else configure ROYO dispatcher
                        SMS
                        service here
                    </p>
                    <div class="row mb-0">

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="sms_provider">SMS PROVIDER</label>
                                <select class="form-control" id="sms_provider" name="sms_provider">
                                    <option value=""> Select Sms Service</option>
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
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="sms_from">SMS From</label>
                                <input type="text" name="sms_from" id="sms_from" placeholder="asdada324234fd32" class="form-control"
                                    value="{{ old('sms_from', $preference->sms_from ?? '')}}">
                                @if($errors->has('sms_from'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('sms_from') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    {{-- <div class="row mb-0">
                        <div class="col-md-6">
                            <p class="sub-header">
                                To Configure your Bumbl SMS Service, go to <a href="#">Bumble Dashboard</a>
                            </p>
                        </div>
                    </div> --}}
                    <div class="row mb-2">

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="sms_key">API KEY</label>
                                <input type="text" name="sms_key" id="sms_key" placeholder="asdada324234fd32" class="form-control"
                                    value="{{ old('sms_key', $preference->sms_key ?? '')}}">
                                @if($errors->has('sms_key'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('sms_key') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="sms_secret">API Secret</label>
                                <input type="text" name="sms_secret" id="sms_secret" placeholder="asdada324234fd32" class="form-control"
                                    value="{{ old('sms_secret', $preference->sms_secret ?? '')}}">
                                @if($errors->has('sms_secret'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('sms_secret') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-2">
                            <div class="form-group mb-0 text-center">
                                <button class="btn btn-blue btn-block" type="submit"> Update </button>
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
                    <h4 class="header-title">Email</h4>
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
                                            johndoe@gmail.com</p>

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
                                <button class="btn btn-blue btn-block" type="submit"> Update </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    </div>
    <form method="POST" action="{{route('configure.update', Auth::user()->code)}}">
        @csrf
        <div class="row">
            <div class="col-xl-11 col-md-offset-1">
                <div class="card-box">
                    <h4 class="header-title">Personal Access Token</h4>
                    <p class="sub-header">
                        View and Generate API keys.
                    </p>
                    <div class="row mb-2">

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="personal_access_token_v1">V1 API ACCESS TOKEN</label>
                                <input type="text" name="personal_access_token_v1" id="personal_access_token_v1"
                                    placeholder="kjadsasd66asdas" class="form-control"
                                    value="{{ old('personal_access_token_v1', $preference->personal_access_token_v1 ?? '')}}">
                                @if($errors->has('personal_access_token_v1'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('personal_access_token_v1') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="personal_access_token_v2" class="row">
                                    <span class="col-md-6">V2 API KEYS</span>
                                    <span class="text-right col-md-6"><a href="javascript: genrateKeyAndToken();">Generate Key</a></span>
                                </label>
                                <input type="text" name="personal_access_token_v2" id="personal_access_token_v2"
                                    placeholder="No API key found.." class="form-control"
                                    value="{{ old('personal_access_token_v2', $preference->personal_access_token_v2 ?? '')}}">
                                @if($errors->has('personal_access_token_v2'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('personal_access_token_v2') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-2">
                            <div class="form-group mb-0 text-center">
                                <button class="btn btn-blue btn-block" type="submit"> Update </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form method="POST" action="{{route('configure.update', Auth::user()->code)}}">
        @csrf
        <div class="row">
            <div class="col-xl-11 col-md-offset-1">
                <div class="card-box">                    
                    <div class="row mb-2">
                        <h4 class="header-title col-md-12">Faceboob Login Details</h4>
                        <div class="col-md-6">
                            <div class="form-group mb-3 switchery-demo">
                                <label for="fb_login" class="mr-3">Facebook Login</label>
                                <input type="checkbox" data-plugin="switchery" name="fb_login" id="fb_login" class="form-control" data-color="#039cfd" @if((isset($preference) && $preference->fb_login == '1'))  checked='checked' @endif>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="fb_client_id">Facebook Client Key</label>
                                <input type="text" name="fb_client_id" id="fb_client_id" placeholder="kjadsasd66asdas"
                                    class="form-control" value="{{ old('fb_client_id', $preference->fb_client_id ?? '')}}">
                                @if($errors->has('fb_client_id'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('fb_client_id') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="fb_client_secret">Facebook Client Secret</label>
                                <input type="text" name="fb_client_secret" id="fb_client_secret" placeholder="kjadsasd66asdas"
                                    class="form-control" value="{{ old('fb_client_secret', $preference->fb_client_secret ?? '')}}">
                                @if($errors->has('fb_client_secret'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('fb_client_secret') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="fb_client_url">Facebook Client Url</label>
                                <input type="text" name="fb_client_url" id="fb_client_url" placeholder="kjadsasd66asdas"
                                    class="form-control" value="{{ old('fb_client_url', $preference->fb_client_url ?? '')}}">
                                @if($errors->has('fb_client_url'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('fb_client_url') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <h4 class="header-title col-md-12">Twitter Login Details</h4>
                        <div class="col-md-6">
                            <div class="form-group mb-3 switchery-demo">
                                <label for="twitter_login" class="mr-3">Twitter Login</label>
                                <input type="checkbox" data-plugin="switchery" name="twitter_login" id="twitter_login" class="form-control" data-color="#039cfd" @if((isset($preference) && $preference->twitter_login == '1'))  checked='checked' @endif>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="twitter_client_id">Twitter Client Key</label>
                                <input type="text" name="twitter_client_id" id="twitter_client_id" placeholder="kjadsasd66asdas"
                                    class="form-control" value="{{ old('twitter_client_id', $preference->twitter_client_id ?? '')}}">
                                @if($errors->has('twitter_client_id'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('twitter_client_id') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="twitter_client_secret">Twitter Client Secret</label>
                                <input type="text" name="twitter_client_secret" id="twitter_client_secret" placeholder="kjadsasd66asdas"
                                    class="form-control" value="{{ old('twitter_client_secret', $preference->twitter_client_secret ?? '')}}">
                                @if($errors->has('twitter_client_secret'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('twitter_client_secret') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="twitter_client_url">Twitter Client Url</label>
                                <input type="text" name="twitter_client_url" id="twitter_client_url" placeholder="kjadsasd66asdas"
                                    class="form-control" value="{{ old('twitter_client_url', $preference->twitter_client_url ?? '')}}">
                                @if($errors->has('twitter_client_url'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('twitter_client_url') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <h4 class="header-title col-md-12">Google Login Details</h4>
                        <div class="col-md-6">
                            <div class="form-group mb-3 switchery-demo">
                                <label for="google_login" class="mr-3">Goodle Login</label>
                                <input type="checkbox" data-plugin="switchery" name="google_login" id="google_login" class="form-control" data-color="#039cfd" @if((isset($preference) && $preference->google_login == '1'))  checked='checked' @endif>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="google_client_id">Google Client Key</label>
                                <input type="text" name="google_client_id" id="google_client_id" placeholder="kjadsasd66asdas"
                                    class="form-control" value="{{ old('google_client_id', $preference->google_client_id ?? '')}}">
                                @if($errors->has('google_client_id'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('google_client_id') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="google_client_secret">Google Client Secret</label>
                                <input type="text" name="google_client_secret" id="google_client_secret" placeholder="kjadsasd66asdas"
                                    class="form-control" value="{{ old('google_client_secret', $preference->google_client_secret ?? '')}}">
                                @if($errors->has('google_client_secret'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('google_client_secret') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="google_client_url">Google Client Url</label>
                                <input type="text" name="google_client_url" id="google_client_url" placeholder="kjadsasd66asdas"
                                    class="form-control" value="{{ old('google_client_url', $preference->google_client_url ?? '')}}">
                                @if($errors->has('google_client_url'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('google_client_url') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <h4 class="header-title col-md-12">Apple Login Details</h4>
                        <div class="col-md-6">
                            <div class="form-group mb-3 switchery-demo">
                                <label for="apple_login" class="mr-3">Apple Login</label>
                                <input type="checkbox" data-plugin="switchery" name="apple_login" id="apple_login" class="form-control" data-color="#039cfd" @if((isset($preference) && $preference->apple_login == '1'))  checked='checked' @endif>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="apple_client_id">Apple Client Key</label>
                                <input type="text" name="apple_client_id" id="apple_client_id" placeholder="kjadsasd66asdas"
                                    class="form-control" value="{{ old('apple_client_id', $preference->apple_client_id ?? '')}}">
                                @if($errors->has('apple_client_id'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('apple_client_id') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="apple_client_secret">Apple Client Secret</label>
                                <input type="text" name="apple_client_secret" id="apple_client_secret" placeholder="kjadsasd66asdas"
                                    class="form-control" value="{{ old('apple_client_secret', $preference->apple_client_secret ?? '')}}">
                                @if($errors->has('apple_client_secret'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('apple_client_secret') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="apple_client_url">Apple Client Url</label>
                                <input type="text" name="apple_client_url" id="apple_client_url" placeholder="kjadsasd66asdas"
                                    class="form-control" value="{{ old('apple_client_url', $preference->apple_client_url ?? '')}}">
                                @if($errors->has('apple_client_url'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('apple_client_url') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                    </div>
                   
                    <div class="row mb-2">
                        <div class="col-md-2">
                            <input type="hidden" name="social_login" id="social_login" value="1">
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