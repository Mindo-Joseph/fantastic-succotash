@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Profile'])

@section('css')
<style>
    // workaround
    .intl-tel-input {
        display: table-cell;
    }

    .intl-tel-input .selected-flag {
        z-index: 4;
    }

    .intl-tel-input .country-list {
        z-index: 5;
    }

    .input-group .intl-tel-input .form-control {
        border-top-left-radius: 4px;
        border-top-right-radius: 0;
        border-bottom-left-radius: 4px;
        border-bottom-right-radius: 0;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Settings</h4>
            </div>
        </div>
    </div>

    <div class="text-sm-left">
        @if (\Session::has('success'))
        <div class="alert alert-success">
            <span>{!! \Session::get('success') !!}</span>
        </div>
        @endif
    </div>
    <div class="text-sm-left">
        @if (\Session::has('error'))
        <div class="alert alert-danger">
            <span>{!! \Session::get('error') !!}</span>
        </div>
        @endif
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Organization details</h4>
                    <p class="sub-header">
                        View and edit your organization's profile details.
                    </p>
                    <form id="UpdateClient" method="post" action="{{route('client.profile.update',Auth::user()->code)}}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf

                        <div class="row mb-2 d-flex align-items-center">

                            <div class="col-md-4">
                                <input type="file" accept="image/*" data-plugins="dropify" name="logo" data-default-file="{{Auth::user()->logo['proxy_url'] . '400/400' . Auth::user()->logo['image_path']}}" />
                                <p class="text-muted text-center mt-2 mb-0">Upload Logo</p>
                            </div>

                            <div class="col-md-8 text-center">
                                <div class="form-group">
                                    <h1>{{Auth::user()->code}}</h1>
                                </div>                               
                            </div>
                        </div>

                        <div class=" row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="control-label">NAME</label>
                                    <input type="text" class="form-control" name="name" id="name" value="{{ old('name', Auth::user()->name ?? '')}}" placeholder="John Doe">
                                    @if($errors->has('name'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="control-label">EMAIL</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', Auth::user()->email ?? '')}}" placeholder="Enter email address" disabled="" style="cursor:not-allowed;">
                                    @if($errors->has('email'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone_number" class="control-label">CONTACT NUMBER</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="phone_number" id="phone_number" value="{{ old('phone_number', Auth::user()->phone_number ?? '')}}">
                                    </div>
                                    @if($errors->has('phone_number'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('phone_number') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company_address" class="control-label">COMPANY ADDRESS</label>
                                    <input type="text" class="form-control" id="company_address" name="company_address" value="{{ old('company_address', Auth::user()->company_address ?? '')}}" placeholder="Enter company address">
                                    @if($errors->has('company_address'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('company_address') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company_name" class="control-label">COMPANY NAME</label>
                                    <input type="text" class="form-control" name="company_name" id="company_name" value="{{ old('company_name', Auth::user()->company_name ?? '')}}" placeholder="Enter company name">
                                    @if($errors->has('company_name'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('company_name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3" id="countryInput">
                                    <label for="country">COUNTRY</label>
                                    @if($errors->has('country'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('country') }}</strong>
                                    </span>
                                    @endif
                                    <select class="form-control" id="country" name="country_id" value="{{ old('country', $client->id ?? '')}}" placeholder="Country">
                                        @foreach($countries as $code=>$country)
                                        <option value="{{ $country->id }}" @if(Auth::user()->country == $country->name) selected @endif>{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback" role="alert">
                                        <strong></strong>
                                    </span>
                                </div>
                            </div>

                
                            <div class="col-md-6">
                                <div class="form-group mb-3" id="timezoneInput">
                                    <label for="timezone">TIMEZONE</label>
                                    @if($errors->has('timezone'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('timezone') }}</strong>
                                    </span>
                                    @endif
                                    <select class="form-control" id="timezone" name="timezone" value="{{ old('timezone', $client->timezone ?? '')}}" placeholder="Timezone">
                                        @foreach($tzlist as $tz)
                                        <option value="{{ $tz }}" @if(Auth::user()->timezone == $tz) selected @endif>{{ $tz }}</option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback" role="alert">
                                        <strong></strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-blue waves-effect waves-light">Update</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <form method="post" action="{{route('client.password.update')}}">
        @csrf

        <div class="row">
            <div class="col-md-12">
                <div class="card-box">
                    <h4 class="header-title">Change Password</h4>
                    <p class="sub-header">
                        {{-- <code>Organization details</code>/Change Password. --}}
                    </p>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="old_password">Old Password</label>
                                <div class="input-group input-group-merge ">
                                    <input class="form-control " name="old_password" type="password" required="" id="old_password" placeholder="Enter your old password">
                                    <div class="input-group-append" data-password="false">
                                        <div class="input-group-text">
                                            <span class="password-eye"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if($errors->has('old_password'))
                            <span class="text-danger" role="alert">
                                <strong>{{ $errors->first('old_password') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="password">New Password</label>
                                <div class="input-group input-group-merge ">
                                    <input class="form-control " name="password" type="password" required="" id="password" placeholder="Enter your password">
                                    <div class="input-group-append" data-password="false">
                                        <div class="input-group-text">
                                            <span class="password-eye"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if($errors->has('password'))
                            <span class="text-danger" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                            @endif
                        </div>

                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="confirm_password">Confirm Password</label>
                                <div class="input-group input-group-merge ">
                                    <input class="form-control " name="password_confirmation" type="password" required="" id="confirm_password" placeholder="Enter your confirm password">
                                    <div class="input-group-append" data-password="false">
                                        <div class="input-group-text">
                                            <span class="password-eye"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if($errors->has('password_confirmation'))
                            <span class="text-danger" role="alert">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                            @endif
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

</div> <!-- container -->
@endsection

@section('script')
<script src="{{asset('assets/libs/dropzone/dropzone.min.js')}}"></script>
<script src="{{asset('assets/libs/dropify/dropify.min.js')}}"></script>
<!-- Page js-->
<script src="{{asset('assets/js/pages/form-fileuploads.init.js')}}"></script>
<script src="{{asset('assets/js/storeClients.js')}}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/8.4.7/js/intlTelInput.js"></script>

<script>
    $("#phone_number").intlTelInput({
        nationalMode: false,
        formatOnDisplay: true,
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/8.4.6/js/utils.js"
    });
    $('.intl-tel-input').css('width', '100%');

    // var regEx = /\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/;
    // $("#UpdateClient").bind("submit", function() {
    //     var val = $("#phone_number").val();
    //     if (!val.match(regEx)) {
    //         $('#phone_number').css('color', 'red');
    //         return false;
    //     }
    // });

    $(function() {
        $('#phone_number').focus(function() {
            $('#phone_number').css('color', '#6c757d');
        });
    });
</script>
@endsection
    

