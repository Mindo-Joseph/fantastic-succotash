@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Inquiries'])
@section('css')
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{asset('assets/css/intlTelInput.css')}}">
<style type="text/css">
    .iti__flag-container li,
    .flag-container li {
        display: block;
    }

    .iti.iti--allow-dropdown,
    .allow-dropdown {
        position: relative;
        display: inline-block;
        width: 100%;
    }

    .iti.iti--allow-dropdown .phone,
    .flag-container .phone {
        padding: 17px 0 17px 100px !important;
    }

    .mdi-icons {
        color: #43bee1;
        font-size: 26px;
        vertical-align: middle;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="page-title-box">
                <h4 class="page-title">Product Inquries</h4>
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
                                    <i class="mdi mdi-storefront text-primary mdi-24px"></i>
                                    <span data-plugin="counterup" id="total_vendor">{{$total_vendor}}</span>
                                </h3>
                                <p class="text-muted font-15 mb-0">Total unique vendor count</p>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 mb-3 mb-md-0">
                            <div class="text-center">
                                <h3>
                                    <i class="mdi mdi-dump-truck text-primary mdi-24px"></i>
                                    <span data-plugin="counterup" id="total_product">{{$total_product}}</span>
                                </h3>
                                <p class="text-muted font-15 mb-0">Total unique product count</p>
                            </div>
                        </div>
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
                    <form name="saveOrder" id="saveOrder"> @csrf </form>
                    <table class="table table-centered table-nowrap table-striped" id="inquiry-datatable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Company Name</th>
                                <th>Message</th>
                                <th>Product</th>
                            </tr>
                        </thead>
                        <tbody id="post_list">
                            @foreach($inquries as $inquiry)
                            <tr data-row-id="{{$inquiry->id}}">
                                <td>{{$inquiry->name}}</td>
                                <td>{{$inquiry->email}}</td>
                                <td>{{$inquiry->phone_number}}</td>
                                <td>{{$inquiry->company_name}}</td>
                                <td>{{$inquiry->message}}</td>
                                <td>{{$inquiry->product->sku}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pagination pagination-rounded justify-content-end mb-0">
                    <!-- {{ $inquries->links() }} -->
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
@section('script')
<script src="{{asset('assets/libs/datatables/datatables.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $('#inquiry-datatable').DataTable({
            // "scrollX": true,
            // "destroy": true,
            // "processing": true,
            // "serverSide": true,
            // "iDisplayLength": 50,
            language: {
                search: "",
                paginate: {
                    previous: "<i class='mdi mdi-chevron-left'>",
                    next: "<i class='mdi mdi-chevron-right'>"
                },
                searchPlaceholder: "Search By Product name, customer name"
            },
            drawCallback: function() {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
            },
        });
    });
</script>
@endsection