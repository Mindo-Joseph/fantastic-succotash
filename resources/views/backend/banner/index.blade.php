@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Banner'])

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
                <h4 class="page-title">Banner</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-8">
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
                        <div class="col-sm-4 text-right">
                            <button class="btn btn-blue waves-effect waves-light text-sm-right openBannerModal"
                             userId="0"><i class="mdi mdi-plus-circle mr-1"></i> Add
                            </button>
                        </div>
                    </div>


                    <div class="table-responsive">
                        <form name="saveOrder" id="saveOrder"> @csrf </form>
                        <table class="table table-centered table-nowrap table-striped" id="banner-datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Duration</th>
                                    <th>Redirect To</th>
                                    <th></th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="post_list">
                                @foreach($banners as $ban)
                                <tr data-row-id="{{$ban->id}}">
                                    <td> 
                                    @php 
                                    
                                    @endphp
    
                                        <img src="{{ url($ban->image)}}" alt="{{$ban->id}}" >
                                    </td>

                                    <td> {{ $ban->name }} </td>
                                    <td> {{ $ban->start_date_time }} <br/> to <br/> {{$ban->end_date_time}}</td>
                                    <td> {{ $ban->redirect_category_id }} </td>
                                    <td> 
                                        <input type="checkbox" bid="{{$ban->id}}" id="cur_{{$ban->id}}" data-plugin="switchery" name="validity_index" class="chk_box" data-color="#039cfd" {{($ban->validity_on == '1') ? 'checked' : ''}} >
                                     </td>
                                    <td> 
                                        <div class="form-ul" style="width: 60px;">
                                            <div class="inner-div" style="float: left;">
                                                <a class="action-icon openBannerModal" userId="{{$ban->id}}" href="#"><h3> <i class="mdi mdi-square-edit-outline"></i></h3></a> 
                                            </div>
                                            <div class="inner-div">
                                                <form method="POST" action="{{ route('banner.destroy', $ban->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="form-group">
                                                       <button type="submit" onclick="return confirm('Are you sure? You want to delete the banner.')" class="btn btn-primary-outline action-icon"><h3><i class="mdi mdi-delete"></i></h3></button> 

                                                    </div>
                                                </form>
                                            </div>
                                         </h3>
                                    </td>
                                </tr>
                               @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination pagination-rounded justify-content-end mb-0">
                        {{-- $banners->links() --}}
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
</div>
@include('backend.banner.modals')
@endsection

@section('script')

@include('backend.banner.pagescript')

@endsection