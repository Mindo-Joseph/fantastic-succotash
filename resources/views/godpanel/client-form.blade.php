@extends('layouts.god-vertical', ['title' => 'Client'])

@section('css')
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Create Client</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <?php $disable = $style = ""; ?>
                    @if(isset($client))

                    <?php $disable = 'disabled';
                    $style = "cursor:not-allowed;"; ?>
                    <form id="UpdateClient" method="post" action="{{route('client.update', $client->id)}}"
                        enctype="multipart/form-data" autocomplete="off">
                        @method('PUT')
                    @else
                        <form id="StoreClient" method="post" action="{{route('client.store')}}" enctype="multipart/form-data" autocomplete="off">
                    @endif
                        @csrf
                        <div class="row mb-2">
                            <div class="col-md-4"> <!--  Storage::disk('s3')->url($client->logo)  url('storage/'.$client->logo)--> 
                                <input type="file" accept="image/*" data-plugins="dropify" name="logo" data-default-file="{{isset($client->logo) ? $client->logo['proxy_url'].'400/400'.$client->logo['image_path'] : ''}}" />
                                <p class="text-muted text-center mt-2 mb-0">Upload Logo</p>
                            </div>
                        </div>
                        <div class=" row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="control-label">NAME</label>
                                    <input type="text" class="form-control" name="name" id="name"
                                        value="{{ old('name', $client->name ?? '')}}" placeholder="John Doe">
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
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $client->email ?? '')}}" placeholder="Enter email address" autocomplete="false" autocomplete="off">
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
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">+91</span>
                                        </div>
                                        <input type="text" class="form-control" name="phone_number" id="phone_number" value="{{ old('phone_number', $client->phone_number ?? '')}}" placeholder="Enter mobile number" autocomplete="off">
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
                                    <label for="encpass" class="control-label"> {{ (!isset($client) || !isset($client->id)) ? 'PASSWORD' : 'PASSWORD(Remain blank if not want to change)' }}</label>
                                    <input type="password" class="form-control" id="encpass" name="encpass" value="" placeholder="Enter password" autocomplete="off">
                                    @if($errors->has('encpass'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('encpass') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="languages">Primary Language</label>
                                <select class="form-control" id="primary_language" name="primary_language">
                                    @foreach($languages as $lang)
                                        <option value="{{$lang->id}}"> {{$lang->name}} </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- <div class="col-md-6">
                                <div class="form-group">
                                    <label for="database_path" class="control-label">DATABASE PATH</label>
                                    <input type="text" class="form-control" name="database_path" id="database_path"
                                        value="{{ old('database_path', $client->database_path ?? '')}}"
                                        placeholder="Enter Path">
                                    @if($errors->has('database_path'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('database_path') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div> -->

                            <!-- <div class="col-md-6">
                                <div class="form-group">
                                    <label for="database_host" class="control-label">DATABASE HOST</label>
                                    @if(!isset($client) || !isset($client->id))
                                        <input type="text" class="form-control" name="database_host" id="database_host"
                                        value="{{ old('database_host', $client->database_host ?? '')}}"
                                        placeholder="Please Enter One String Example:-'127.0.0.1' ">
                                    @else
                                        <input type="text" class="form-control" name="database_host" id="database_host"
                                        value="{{ old('database_host', $client->database_host ?? '')}}"
                                        placeholder="DB HOST Example:127.0.0.1">

                                    @endif
                                    @if($errors->has('database_host'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('database_host') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="database_port" class="control-label">DATABASE PORT</label>
                                    @if(!isset($client) || !isset($client->id))
                                        <input type="text" class="form-control" name="database_port" id="database_port"
                                        value="{{ old('database_port', $client->database_port ?? '')}}"
                                        placeholder="3306">
                                    @else
                                        <input type="text" class="form-control" name="database_port" id="database_port"
                                        value="{{ old('database_port', $client->database_port ?? '')}}"
                                        placeholder="DB PORT Example:3306"  >

                                    @endif
                                    @if($errors->has('database_port'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('database_port') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div> -->

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="database_name" class="control-label">DATABASE NAME</label>
                                    @if(!isset($client) || !isset($client->id))
                                        <input type="text" class="form-control" name="database_name" id="database_name"
                                        value="{{ old('database_name', $client->database_name ?? '')}}"
                                        placeholder="Please Enter One String Example:-'mydatabase' ">
                                    @else
                                        <input type="text" class="form-control" name="database_name" id="database_name"
                                        value="{{ old('database_name', $client->database_name ?? '')}}"
                                        placeholder="Please Enter One String Example:-'mydatabase' " disabled="" style="cursor:not-allowed;">

                                    @endif
                                    @if($errors->has('database_name'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('database_name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>


                            <!-- <div class="col-md-6">
                                <div class="form-group">
                                    <label for="database_username" class="control-label">DATABASE USERNAME</label>
                                    <input type="text" class="form-control" name="database_username" id="database_username"
                                        value="{{ old('database_username', $client->database_username ?? '')}}" placeholder="Enter database username">
                                    @if($errors->has('database_username'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('database_username') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="database_password" class="control-label">DATABASE PASSWORD</label>
                                    <input type="text" class="form-control" name="database_password"
                                        id="database_password"
                                        value="{{ old('database_password', $client->database_password ?? '')}}"
                                        placeholder="Enter database password">
                                    @if($errors->has('database_password'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('database_password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div> -->

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company_name" class="control-label">COMPANY NAME</label>
                                    <input type="text" class="form-control" name="company_name" id="company_name"
                                        value="{{ old('company_name', $client->company_name ?? '')}}"
                                        placeholder="Enter company name">
                                    @if($errors->has('company_name'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('company_name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company_address" class="control-label">COMPANY ADDRESS</label>
                                    <input type="text" class="form-control" id="company_address"
                                        name="company_address"
                                        value="{{ old('company_address', $client->company_address ?? '')}}"
                                        placeholder="Enter company address">
                                    @if($errors->has('company_address'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('company_address') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sub_domain" class="control-label">SUB DOMAIN</label>
                                    <input type="text" class="form-control" name="sub_domain" id="sub_domain"
                                        value="{{ old('sub_domain', $client->sub_domain ?? '')}}"
                                        placeholder="Enter sub domain">
                                    @if($errors->has('sub_domain'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('sub_domain') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="custom_domain" class="control-label">CUSTOM DOMAIN</label>
                                    <input type="text" class="form-control" name="custom_domain" id="custom_domain"
                                        value="{{ old('custom_domain', $client->custom_domain ?? '')}}"
                                        placeholder="Enter custom domain">
                                    @if($errors->has('custom_domain'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('custom_domain') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-info waves-effect waves-light">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



</div>
@endsection

@section('script')
<script src="{{asset('assets/libs/dropzone/dropzone.min.js')}}"></script>
<script src="{{asset('assets/libs/dropify/dropify.min.js')}}"></script>
<!-- Page js-->
<script src="{{asset('assets/js/pages/form-fileuploads.init.js')}}"></script>


@endsection