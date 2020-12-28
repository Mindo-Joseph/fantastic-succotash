@extends('layouts.god-vertical', ['title' => 'Options'])

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
                <h4 class="page-title">Currencies</h4>
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
                            <a class="btn btn-blue waves-effect waves-light text-sm-right"
                                href="{{route('map.create')}}"><i class="mdi mdi-plus-circle mr-1"></i> Add
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-striped" id="products-datatable">
                            <thead>
                                <tr>
                                    <th>Provider Name</th>
                                    <th>Keyword</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($maps as $m)
                                <tr>
                                    <td> {{ $m->provider }} </td>
                                    <td> {{ $m->keyword }} </td>
                                    <td> {{ ($m->status == 1) ? 'Available' : 'Not Available' }} </td>
                                    <td> 
                                        <a class="btn btn-sm btn-danger" onclick="return confirm('Are you sure? You want to delete the map provider.')" href="{{URL::to('godpanel/map/destroy/'.$m->id)}}"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination pagination-rounded justify-content-end mb-0">
                        {{ $maps->links() }}
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
</div>
@endsection