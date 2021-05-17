@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Celebrity'])

@section('css')
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/mohithg-switchery/mohithg-switchery.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/multiselect/multiselect.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/selectize/selectize.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/bootstrap-selectroyoorders/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/nestable2/nestable2.min.css')}}" rel="stylesheet" type="text/css" />
<style>.error{color: red;}</style>
@endsection

@section('content')

<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Celebrity</h4>
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
                            <button class="btn btn-info waves-effect waves-light text-sm-right"
                              data-toggle="modal" data-target=".addModal"><i class="mdi mdi-plus-circle mr-1"></i> Add
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <form name="saveOrder" id="saveOrder"> @csrf </form>
                        <table class="table table-centered table-nowrap table-striped" id="celeb-datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Country</th>
                                    <!-- <th>Brands</th> -->
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="post_list">
                                @foreach($celebrities as $celeb)

                                <tr data-row-id="{{$celeb->id}}">
                                    <!-- <td class="draggableTd"><span class="dragula-handle"></span></td> -->
                                    <td> 
                                        <img class="img-fluid rounded-circle img-thumbnail" src="{{$celeb->avatar['proxy_url'].'60/60'.$celeb->avatar['image_path']}}" alt="{{$celeb->id}}" >
                                    </td>
                                    <td> {{ $celeb->name }} </td>
                                    <td> {{ $celeb->description }} </td>
                                    <td> {{ (!empty($celeb->country)) ? ucwords(strtolower($celeb->country->name)) : '' }} </td>
                                    <!-- <td> 
                                        @if(!empty($celeb->brands))
                                            @foreach($celeb->brands as $kb => $brand)
                                                    <span class="badge bg-soft-warning text-warning">{{$brand->title}}</span>
                                            @endforeach
                                        @else
                                            N/A
                                        @endif
                                    </td> -->
                                    <td> 
                                        <input type="checkbox" bid="{{$celeb->id}}" id="activeCheck" data-plugin="switchery" name="validity_index" class="chk_box" data-color="#43bee1" {{($celeb->status == '1') ? 'checked' : ''}} >
                                     </td>
                                    <td> 
                                        <div class="form-ul" style="width: 60px;">
                                            <div class="inner-div" style="float: left;">
                                                <a class="action-icon openEditModal" loyaltyID="{{$celeb->id}}" href="#"><h3> <i class="mdi mdi-square-edit-outline"></i></h3></a> 
                                            </div>
                                            <div class="inner-div">
                                                <form method="POST" action="{{ route('celebrity.destroy', $celeb->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="form-group">
                                                       <button type="submit" onclick="return confirm('Are you sure? You want to delete the loyalty card.')" class="btn btn-primary-outline action-icon"><h3><i class="mdi mdi-delete"></i></h3></button> 

                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                               @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination pagination-rounded justify-content-end mb-0">
                        {{-- $celebrities->links() --}}
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
</div>

@include('backend.celebrity.modals')
@endsection

@section('script')

@include('backend.celebrity.pagescript')

@endsection