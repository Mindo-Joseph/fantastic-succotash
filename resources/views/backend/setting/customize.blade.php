@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Configure'])

@section('content')

<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Customize</h4>
            </div>
        </div>
    </div>

    <form method="POST" action="{{route('configure.update', Auth::user()->code)}}">
        @csrf
        <div class="row">
            <div class="col-xl-12">
                <div class="card-box">
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

                    <h4 class="header-title">Theme</h4>
                    <p class="sub-header">
                        Choose between light and dark theme, for the platform.
                    </p>
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <div class="radio radio-blue form-check-inline">
                                <input type="radio" id="light_theme" value="light" name="theme_admin" {{ (isset($preference) && $preference->theme_admin =="light")? "checked" : "" }}>
                                <label for="light_theme"> Light theme </label>
                            </div>
                            <div class="radio form-check-inline">
                                <input type="radio" id="dark_theme" value="dark" name="theme_admin" {{ (isset($preference) &&  $preference->theme_admin =="dark")? "checked" : "" }}>
                                <label for="dark_theme"> Dark theme </label>
                            </div>
                            @if($errors->has('theme'))
                            <span class="text-danger" role="alert">
                                <strong>{{ $errors->first('theme') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-2">
                            <div class="form-group mb-0 text-center">
                                <input type="hidden" name="send_to" id="send_to" value="customize">
                                <button class="btn btn-blue btn-block" type="submit"> Update </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="row">
        <div class="col-6">
            <form method="POST" action="{{route('configure.update', Auth::user()->code)}}">
                @csrf
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card-box">
                            <h4 class="header-title"> Web Template</h4>
                            <p class="sub-header">
                                Select web templete
                            </p>
                            <div class="row mb-2">
                                @foreach($webTemplates as $webt)
                                <div class="col-lg-4 col-xl-3">
                                    <div class="card">
                                        <img class="card-img-top img-fluid" src="{{asset('assets/images/small/img-1.jpg')}}" alt="Card image cap">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-12 custom-control custom-radio">
                                                    <input type="radio" value="{{$webt->id}}" id="webTemplate{{$webt->id}}" name="web_template_id" class="custom-control-input" {{ ($preference && $preference->web_template_id == $webt->id)? "checked" : "" }}>
                                                    <label class="custom-control-label" for="webTemplate{{$webt->id}}">{{$webt->name}}</label>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                                <!--<div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="webTemplates">Web Template</label>
                                        <select class="form-control" >
                                            @foreach($webTemplates as $webt)
                                            <option value="{{ $webt->id }}" {{ ($preference && $preference->web_template_id == $webt->id)? "selected" : "" }}>{{ $webt->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('web_template_id'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('web_template_id') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div> -->
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-2">
                                    <div class="form-group mb-0 text-center">
                                        <input type="hidden" name="send_to" id="send_to" value="customize">
                                        <button class="btn btn-blue btn-block" type="submit"> Update </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-6">
            <form method="POST" action="{{route('configure.update', Auth::user()->code)}}">
                @csrf
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card-box">
                            <h4 class="header-title">App Template</h4>
                            <p class="sub-header">
                                Select app templete
                            </p>
                            <div class="row mb-2">
                                @foreach($appTemplates as $webt)
                                <div class="col-lg-4 col-xl-3">
                                    <div class="card" style="max-height: 445px; overflow: hidden;">
                                        <img class="card-img-top img-fluid" src="{{asset('assets/images/small/img-1.jpg')}}" alt="Card image cap">
                                        <div class="card-body">
                                            <div class="row">
                                                 <div class="col-sm-4 custom-control custom-radio">
                                                    <input type="radio" value="{{$webt->id}}" id="appTemplate{{$webt->id}}" name="app_template_id" class="custom-control-input" {{ ($preference && $preference->app_template_id == $webt->id)? "checked" : "" }}>
                                                <label class="custom-control-label" for="appTemplate{{$webt->id}}">{{$webt->name}}</label>
                                                 </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                <!--<div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="appTemplates">App Template</label>
                                        <select class="form-control" id="appTemplates" name="app_template_id">
                                            @foreach($appTemplates as $webt)
                                            <option value="{{ $webt->id }}" {{ ($preference && $preference->app_template_id == $webt->id)? "selected" : "" }}>{{ $webt->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('app_template_id'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('app_template_id') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>-->
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-2">
                                    <div class="form-group mb-0 text-center">
                                        <input type="hidden" name="send_to" id="send_to" value="customize">
                                        <button class="btn btn-blue btn-block" type="submit"> Update </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
                <form method="POST" action="{{route('configure.update', Auth::user()->code)}}">
                @csrf
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card-box">
                            <h4 class="header-title">Date & Time</h4>
                            <p class="sub-header">
                                View and update the date & time format.
                            </p>
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="date_format">DATE FORMAT</label>
                                        <select class="form-control" id="date_format" name="date_format">
                                            <option value="DD-MM-YYYY" {{ ($preference && $preference->date_format =="DD-MM-YYYY")? "selected" : "" }}>
                                                DD-MM-YYYY</option>
                                            <option value="DD/MM/YYYY" {{ ($preference && $preference->date_format =="DD/MM/YYYY")? "selected" : "" }}>
                                                DD/MM/YYYY</option>
                                            <option value="YYYY-MM-DD" {{ ($preference && $preference->date_format =="YYYY-MM-DD")? "selected" : "" }}>
                                                YYYY-MM-DD</option>
                                        </select>
                                        @if($errors->has('date_format'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('date_format') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="time_format">TIME FORMAT</label>
                                        <select class="form-control" id="time_format" name="time_format">
                                            <option value="12" {{ ($preference && $preference->time_format =="12")? "selected" : "" }}>12 hours
                                            </option>
                                            <option value="24" {{ ($preference && $preference->time_format =="24")? "selected" : "" }}>24 hours
                                            </option>
                                        </select>
                                        @if($errors->has('time_format'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('time_format') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-2">
                                    <div class="form-group mb-0 text-center">
                                        <input type="hidden" name="send_to" id="send_to" value="customize">
                                        <button class="btn btn-blue btn-block" type="submit"> Update </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-6">
            <form method="POST" action="{{route('client.updateDomain', Auth::user()->code)}}">
                @csrf
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card-box">
                            <h4 class="header-title">Custom Domain</h4>
                            <p class="sub-header">
                                Update custom domain here.
                            </p>
                            <div class="row mb-2">
                                <div class="col-sm-12">
                                    <div class="form-group mb-3">
                                        <label for="custom_domain">Custom Domain</label>
                                        <input type="text" name="custom_domain" id="custom_domain" placeholder="xyz"
                                            class="form-control" value="{{ old('custom_domain', $preference->domain->custom_domain ?? '')}}">
                                        @if($errors->has('custom_domain'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('custom_domain') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-2">
                                    <div class="form-group mb-0 text-center">
                                        <input type="hidden" name="send_to" id="send_to" value="customize">
                                        <button class="btn btn-blue btn-block" type="submit"> Update </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <form method="POST" action="{{route('configure.update', Auth::user()->code)}}">
        @csrf
        <div class="row">
            <div class="col-xl-12">
                <div class="card-box">
                    <h4 class="header-title">Nomenclature</h4>
                    <p class="sub-header">
                        Define and update the nomenclature
                    </p>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="languages">Primary Language</label>
                            <select class="form-control" id="primary_language" name="primary_language">
                                @foreach($languages as $lang)
                                    <option {{(isset($preference) && ($lang->id == $preference->primarylang->language_id))? "selected" : "" }} value="{{$lang->id}}"> {{$lang->name}} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="languages">Additional Languages</label>
                            <select class="form-control select2-multiple" id="languages" name="languages[]" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">
                                @foreach($languages as $lang)
                                    @if($lang->id != $preference->primarylang->language_id)
                                        <option value="{{$lang->id}}" {{ (isset($preference) && in_array($lang->id, $cli_langs))? "selected" : "" }}> {{$lang->name}} </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label for="languages">Primary Currency</label>
                            <input type="text" class="form-control" value="{{ $preference->primary->currency->iso_code }} - {{ $preference->primary->currency->symbol }}" disabled="" style="cursor:not-allowed;">
                        </div>
                        <div class="col-md-6">
                            <label for="languages">Additional Currency</label>
                            <select class="form-control select2-multiple" id="currency" name="currency_data[]" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">
                                @foreach($currencies as $currency)
                                    @if($currency->id != 147)
                                        <option value="{{$currency->id}}" {{ (isset($preference) && in_array($currency->id, $cli_currs))? "selected" : "" }}> {{$currency->iso_code}} {{$currency->symbol}} </option>
                                    @endif
                                @endforeach
                            </select>
                        
                            @if(!empty($curtableData))
                            <!--<table class="table table-centered table-nowrap table-striped" id="banner-datatable">
                                <tr>
                                    <th>#</th>
                                    <th>Currency</th>
                                    <th>Multiplier</th>
                                    <th>Active</th>
                                    <th>#</th>
                                    <th>Currency</th>
                                    <th>Multiplier</th>
                                    <th>Active</th>
                                </tr>
                            
                            @foreach($curtableData as $currency)
                                <tr>
                                    @foreach($currency as $cur)
                                    <td>{{$cur['id']}}</td>
                                    <td>{{$cur['iso_code']}}</td>
                                    <td><input type="number" name="multiplier" min="0" value="0" step=".01"></td>
                                    <td></td>
                                    @endforeach
                                </tr>

                            @endforeach
                            </table> -->
                            @endif
                        </div>
                        
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-2">
                            <div class="form-group mb-0 text-center">
                                <input type="hidden" name="send_to" id="send_to" value="customize">
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
<script src="{{asset('assets/js/jscolor.js')}}"></script>
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