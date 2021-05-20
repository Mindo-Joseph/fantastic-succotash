@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Vendor'])

@section('css')
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />

<style type="text/css">
    .pac-container, .pac-container .pac-item { z-index: 99999 !important; }
</style>
@endsection

@section('content')

<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Vendors</h4>
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
                            <button class="btn btn-info waves-effect waves-light text-sm-right openAddModal"
                             userId="0"><i class="mdi mdi-plus-circle mr-1"></i> Add
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <form name="saveOrder" id="saveOrder"> @csrf </form>
                        <table class="table table-centered table-nowrap table-striped" id="banner-datatable">
                            <thead>
                                <tr>
                                    <th>Icon</th>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Offers</th>
                                    <th>Can Add Category</th>
                                    <th>Commission Percentage</th>
                                    <th>Commission Fixed per Order</th>
                                    <th>Commission Monthly</th>
                                    <!-- <th>Products</th>
                                    <th>Orders</th>
                                    <th>Active Orders</th> -->
                                    <th></th>
                                    <!-- <th>Latitude</th>
                                    <th>Longitude</th> -->
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="post_list">
                                @foreach($vendors as $ven)
                                <tr data-row-id="{{$ven->id}}">
                                    <td>
                                        <a href="{{ route('vendor.show', $ven->id) }}"><img class="rounded-circle" src="{{$ven->logo['proxy_url'].'90/90'.$ven->logo['image_path']}}" alt="{{$ven->id}}"></a>
                                    </td>
                                    <td><a href="{{ route('vendor.show', $ven->id) }}">{{ $ven->name }}</a> </td>
                                    <td> {{ $ven->address }}</td>

                                        <td>
                                            @if($ven->dine_in == 1)
                                                <span class="badge bg-soft-warning text-warning">Dine In</span>
                                            @endif
                                            @if($ven->takeaway == 1)
                                                <span class="badge bg-soft-warning text-warning">Take Away</span>
                                            @endif
                                            @if($ven->delivery == 1)
                                                <span class="badge bg-soft-warning text-warning">Delivery</span>
                                            @endif
                                        </td>
                                        <td>{{ ($ven->add_category == 0) ? 'No' : 'Yes' }}</td>
                                        <td>{{ $ven->commission_percent }}</td>
                                        <td>{{ $ven->commission_fixed_per_order}}</td>
                                        <td>{{ $ven->commission_monthly }}</td>
                                        <td> </td>
                                        <!-- <td> {{ $ven->latitude }} </td>
                                        <td> {{ $ven->longitude }}</td> -->
                                        <td> 
                                            <div class="form-ul" style="width: 60px;">
                                                <div class="inner-div" style="float: left;">
                                                    <a class="action-icon" userId="{{$ven->id}}" href="{{ route('vendor.show', $ven->id) }}">
                                                        <i class="mdi mdi-eye"></i>
                                                    </a> 
                                                </div>
                                                <div class="inner-div">
                                                    <form method="POST" action="{{ route('vendor.destroy', $ven->id) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="form-group action-icon mb-0">
                                                            <button type="submit" onclick="return confirm('Are you sure? You want to delete the vendor.')" class="btn btn-primary-outline action-icon">
                                                                <i class="mdi mdi-delete"></i>
                                                            </button> 
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
                        {{-- $banners->links() --}}
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> 

        <div class="row address" id="def" style="display: none;">
            <input type="text" id="def-address" name="test" class="autocomplete form-control def_address">
        </div><!-- end col -->
    </div>
</div>
@include('backend.vendor.modals')
@endsection

@section('script')

@include('backend.vendor.pagescript')

@endsection