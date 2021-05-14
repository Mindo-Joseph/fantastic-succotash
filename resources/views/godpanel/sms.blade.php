@extends('layouts.god-vertical', ['title' => 'SMS'])

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
                            <a class="btn btn-info waves-effect waves-light text-sm-right"
                                href="{{route('sms.create')}}"><i class="mdi mdi-plus-circle mr-1"></i> Add
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
                                @foreach($sms as $s)
                                <tr>
                                    <td> {{ $s->provider }} </td>
                                    <td> {{ $s->keyword }} </td>
                                    <td> {{ ($s->status == 1) ? 'Available' : 'Not Available' }} </td>
                                    <td>
                                        <a onclick="return confirm('Are you sure? You want to delete the sms provider.')" href="{{URL::to('godpanel/sms/destroy/'.$s->id)}}" class="btn btn-primary-outline action-icon"> <h3><i class="mdi mdi-delete"></i></h3></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination pagination-rounded justify-content-end mb-0">
                        {{ $sms->links() }}
                    </div>

                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>


</div>
@endsection

@section('script')
<script src="{{asset('assets/libs/dropzone/dropzone.min.js')}}"></script>
<script src="{{asset('assets/libs/dropify/dropify.min.js')}}"></script>
<!-- Page js-->
<script src="{{asset('assets/js/pages/form-fileuploads.init.js')}}"></script>

<!-- @parent

@if(count($errors->add) > 0)
<script>
$(function() {
    $('#add-client-modal').modal({
        show: true
    });
});
</script>
@elseif(count($errors->update) > 0)
<script>
$(function() {
    $('#update-client-modal').modal({
        show: true
    });
});
</script>
@endif
@if(\Session::has('getClient'))
<script>
$(function() {
    $('#update-client-modal').modal({
        show: true
    });
});
</script>
@endif -->
@endsection