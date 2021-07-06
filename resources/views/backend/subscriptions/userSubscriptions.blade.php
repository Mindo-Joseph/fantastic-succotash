@extends('layouts.vertical', ['title' => 'Dashboard'])

@section('css')
<!-- Plugins css -->
<link href="{{asset('assets/libs/admin-resources/admin-resources.min.css')}}" rel="stylesheet" type="text/css" />

<link href="{{asset('assets/libs/flatpickr/flatpickr.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />

@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">

    <div class="content dashboard-boxes">

        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <div class="page-title-box">
                        <h4 class="page-title">User Subscriptions</h4>
                    </div>
                </div>
                <div class="col-sm-6 text-sm-right">
                    <button class="btn btn-info waves-effect waves-light text-sm-right" data-toggle="modal" data-target="#add-user-subscription">
                        <i class="mdi mdi-plus-circle mr-1"></i> Add Plan
                    </button>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-sm-12">
                    <div class="text-sm-left">
                        @if (\Session::has('success'))
                            <div class="alert alert-success">
                                <span>{!! \Session::get('success') !!}</span>
                            </div>
                        @endif
                        @if ( ($errors) && (count($errors) > 0) )
                            <div class="alert alert-danger">
                                <ul class="m-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row custom-cols">
                <div class="col col-md-4 col-lg-3 col-xl">
                    <div class="widget-rounded-circle card">
                        <div class="card-body p-2">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <div class="text-end">
                                        <p class="text-muted mb-1 text-truncate">Subscribed Users</p>
                                        <h3 class="text-dark mt-1 mb-0"><span data-plugin="counterup">{{0}}</span></h3>
                                    </div>
                                </div>
                                <div class="col-4 text-md-right">
                                    <div class="avatar-lg rounded-circle ml-auto">
                                        <i class="fe-heart font-22 avatar-title"></i>
                                    </div>
                                </div>
                            </div> <!-- end row-->
                        </div>
                    </div> <!-- end widget-rounded-circle-->
                </div> <!-- end col-->

                <div class="col">
                    <div class="widget-rounded-circle card">
                        <div class="card-body p-2">
                            <div class="row align-items-center">
                            <div class="col-8">
                                    <div class="text-end">
                                        <p class="text-muted mb-1 text-truncate">Subscribed Users (%)</p>
                                        <h3 class="text-dark mt-1 mb-0"><span data-plugin="counterup">{{0}}</span></h3>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="avatar-lg rounded-circle ml-auto">
                                        <i class="fe-heart font-22 avatar-title"></i>

                                    </div>
                                </div>

                            </div> <!-- end row-->
                        </div>
                    </div> <!-- end widget-rounded-circle-->
                </div> <!-- end col-->
                <br>
            </div>
            <!-- end row-->

        </div> <!-- container -->

    </div>

</div> <!-- container -->

<div id="add-user-subscription" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addUserSubscription_Label" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Plan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="user_subscription_form" method="post" enctype="multipart/form-data" action="{{ route('subscriptions.saveUserSubscription') }}">
                @csrf
                <div class="modal-body" >
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <input type="file" accept="image/*" data-plugins="dropify" name="image" class="dropify" data-default-file="" />
                                    <p class="text-muted text-center mt-2 mb-0">Upload Image</p>
                                </div> 
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('title', 'Enable',['class' => 'control-label']) !!} 
                                        <div class="mt-md-1">
                                            <input type="checkbox" data-plugin="switchery" name="status" class="form-control validity" data-color="#43bee1" checked='checked'>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group" id="nameInput">
                                        {!! Form::label('title', 'Title',['class' => 'control-label']) !!} 
                                        {!! Form::text('title', null, ['class'=>'form-control']) !!}
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Features</label>
                                        <select class="form-control select2-multiple" name="features[]" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">
                                            @foreach($features as $feature)
                                                <option value="{{$feature->id}}" {{ (isset($sub->feature_id) && ($feature->id == $sub->feature_id)) ? "selected" : "" }}> {{$feature->title}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Price</label>
                                        <input class="form-control" type="number" name="price" min="0">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Validity</label>
                                        <select class="form-control" name="validity" placeholder="Choose ...">
                                            @foreach($validities as $val)
                                                <option value="{{$val->id}}" {{ (isset($sub->validity_id) && ($val->id == $sub->validity_id)) ? "selected" : "" }}> {{$val->name}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group" id="descInput">
                                        {!! Form::label('title', 'Description',['class' => 'control-label']) !!} 
                                        {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => '3']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info waves-effect waves-light submitAddSubscriptionForm">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="edit-user-subscription" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editUserSubscription_Label" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        </div>
    </div>
</div>

@endsection

@section('script')

<script>
    var monthlyInfo_url = "{{route('client.monthlySalesInfo')}}";
    var yearlyInfo_url = "{{route('client.yearlySalesInfo')}}";
    var weeklyInfo_url = "{{route('client.weeklySalesInfo')}}";
    var categoryInfo_url = "{{route('client.categoryInfo')}}";
</script>

@endsection