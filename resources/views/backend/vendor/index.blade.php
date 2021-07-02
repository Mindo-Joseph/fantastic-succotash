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
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="page-title-box">
                <h4 class="page-title">Vendors</h4>
            </div>
        </div>
        <div class="col-sm-6 text-sm-right">
            <button class="btn btn-info waves-effect waves-light text-sm-right openImportModal"
                    userId="0"><i class="mdi mdi-plus-circle mr-1"></i> Import
            </button>
            <button class="btn btn-info waves-effect waves-light text-sm-right openAddModal"
                userId="0"><i class="mdi mdi-plus-circle mr-1"></i> Add
            </button>
        </div>
    </div>
    <!-- end page title -->
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
                        <form name="saveOrder" id="saveOrder"> @csrf </form>
                        <table class="table table-centered table-nowrap table-striped" id="banner-datatable">
                            <thead>
                                <tr>
                                    <th>Icon</th>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Offers</th>
                                    <th class="text-center">Can Add <br> Category</th>
                                    <th class="text-center">Commission <br> Percentage</th>
                                    <!-- <th>Commission <br> Fixed per Order</th>
                                    <th>Commission <br> Monthly</th> -->
                                    <th class="text-center">Products</th>
                                    <th class="text-center">Orders</th>
                                    <th class="text-center">Active <br> Orders</th>
                                    <th class="text-center"></th>
                                    <!-- <th>Latitude</th>
                                    <th>Longitude</th> -->
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="post_list">
                                @foreach($vendors as $vendor)
                                <tr data-row-id="{{$vendor->id}}">
                                    <td>
                                        <a class="round_img_box" href="{{ route('vendor.show', $vendor->id) }}"><img class="rounded-circle" src="{{$vendor->logo['proxy_url'].'90/90'.$vendor->logo['image_path']}}" alt="{{$vendor->id}}"></a>
                                    </td>
                                    <td><a href="{{ route('vendor.show', $vendor->id) }}">{{ $vendor->name }}</a> </td>
                                    <td class="address_txt"> <p class="ellips_txt" data-toggle="tooltip" data-placement="top" title="{{ $vendor->address }}">{{ $vendor->address }}</p></td>
                                        <td>
                                        @if($client_preferences->dinein_check == 1)
                                            @if($vendor->dine_in == 1)
                                                <span class="badge bg-soft-warning text-warning">Dine In</span>
                                            @endif
                                        @endif
                                        @if($client_preferences->takeaway_check == 1)
                                            @if($vendor->takeaway == 1)
                                                <span class="badge bg-soft-warning text-warning">Take Away</span>
                                            @endif
                                        @endif
                                        @if($client_preferences->delivery_check == 1)
                                            @if($vendor->delivery == 1)
                                                <span class="badge bg-soft-warning text-warning">Delivery</span>
                                            @endif
                                        @endif
                                        </td>
                                        <td class="text-center">{{($vendor->add_category == 0) ? 'No' : 'Yes' }}</td>
                                        <td class="text-center">{{$vendor->commission_percent }}</td>
                                        <!-- <td>{{$vendor->commission_fixed_per_order}}</td>
                                        <td>{{$vendor->commission_monthly }}</td> -->
                                        <td class="text-center">{{$vendor->products_count}}</td>
                                        <td class="text-center">{{$vendor->orders_count}}</td>
                                        <td class="text-center">{{$vendor->active_orders_count}}</td>
                                        <td class="text-center"> </td>
                                        <!-- <td> {{ $vendor->latitude }} </td>
                                        <td> {{ $vendor->longitude }}</td> -->
                                        <td class="text-center"> 
                                            <div class="form-ul">
                                                <div class="inner-div d-inline-block">
                                                    <a class="action-icon" userId="{{$vendor->id}}" href="{{ route('vendor.show', $vendor->id) }}">
                                                        <i class="mdi mdi-eye"></i>
                                                    </a> 
                                                </div>
                                                <div class="inner-div d-inline-block">
                                                    <form method="POST" action="{{ route('vendor.destroy', $vendor->id) }}">
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