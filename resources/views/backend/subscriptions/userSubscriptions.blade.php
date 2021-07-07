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

            <div class="row">
                <div class="col-12">
                    <div class="card widget-inline">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6 col-md-6 mb-3 mb-md-0">
                                    <div class="text-center">
                                        <h3>
                                            <i class="mdi mdi-account-multiple-plus text-primary mdi-24px"></i>
                                            <span data-plugin="counterup" id="total_subscribed_users_count">0</span>
                                        </h3>
                                        <p class="text-muted font-15 mb-0">Total Subscribed Users</p>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6 mb-3 mb-md-0">
                                    <div class="text-center">
                                        <h3>
                                            <i class="mdi mdi-account-multiple-plus text-primary mdi-24px"></i>
                                            <span data-plugin="counterup" id="total_subscribed_users_percentage">0</span>
                                        </h3>
                                        <p class="text-muted font-15 mb-0">Total Subscribed Users (%)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-sm-12">
                                    <div class="text-sm-left">
                                        @if (\Session::has('success'))
                                        <div class="alert alert-success">
                                            <span>{!! \Session::get('success') !!}</span>
                                        </div>
                                        @endif
                                        @if (\Session::has('error_delete'))
                                        <div class="alert alert-danger">
                                            <span>{!! \Session::get('error_delete') !!}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <div class="table-responsive">
                                    <form name="saveOrder" id="saveOrder"> @csrf </form>
                                    <table class="table table-centered table-nowrap table-striped" id="banner-datatable">
                                        <thead>
                                            <tr>
                                                <th>Image</th>
                                                <th>Title</th>
                                                <th>Description</th>
                                                <th>Price</th>
                                                <th>Validity</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="post_list">
                                            @foreach($user_subscriptions as $sub)
                                            <?php 
                                            ?>
                                            <tr data-row-id="{{$sub->id}}">
                                                <td> 
                                                    <img src="{{$sub->image['proxy_url'].'40/40'.$sub->image['image_path']}}" class="rounded-circle" alt="{{$sub->id}}" >
                                                </td>
                                                <td><a href="{{route('customer.new.edit', $sub->id)}}"  class=""> {{$sub->title}}</a></td>
                                                <td>{{$sub->description}}</td>
                                                <td>{{$sub->price}}</td>
                                                <td>{{$sub->validity_id}}</td>
                                                <td>
                                                    <input type="checkbox" data-id="{{$sub->id}}" id="cur_{{$sub->id}}" data-plugin="switchery" name="userSubscription" class="chk_box" data-color="#43bee1" {{($sub->status == 1) ? 'checked' : ''}} >
                                                </td> 
                                                <td> 
                                                    <div class="form-ul" style="width: 60px;">
                                                        <div class="inner-div" >
                                                            @if(Auth::user()->is_superadmin == 1)<a href="{{route('subscriptions.editUserSubscription', $sub->slug)}}"  class="action-icon editIconBtn"> 
                                                            <i class="mdi mdi-square-edit-outline"></i></a>

                                                            <a href="{{route('subscriptions.deleteUserSubscription', [$sub->id, 3])}}" onclick="return confirm('Are you sure? You want to delete the user.')" class="action-icon"> <i class="mdi mdi-delete" title="Delete user"></i></a>
                                                            @endif    
                                                        </div>
                                                    </div>
                                                </td> 
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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