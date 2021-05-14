@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Options'])

@section('css')
<style type="text/css">
    .modal.fadeIn {
      opacity:.4;
    }
    .pac-container, .pac-container .pac-item { z-index: 99999 !important; }

    .modal-header{
        padding: 12px 1rem 0px !important;
    }
    .modal-body{
        padding: padding:10px 1rem !important;
    }
    .modal-body .card-box{
        padding: 0px 1rem !important;
    }
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
                            </div>
                        </div>
                        <div class="col-sm-4 text-right">
                            <button class="btn btn-info waves-effect waves-light text-sm-right addVendor"><i class="mdi mdi-plus-circle mr-1"></i> Add </button>
                        </div>

                    </div>

                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-striped" id="products-datatable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Country</th>
                                    <th>Status</th>
                                    <th>Verified Email</th>
                                    <th>Verified Phone</th>
                                    <th style="width: 85px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td class="table-user">
                                        <a href="javascript:void(0);" class="text-body font-weight-semibold">{{$user->name}}</a>
                                    </td>
                                    <td> {{$user->email}} </td>
                                    <td> {{$user->country->name}} </td>
                                    <td> {{ $status = ($user->status == 0) ? 'pending' : ($user->status == 1) ? 'active' : ($user->status == 2) ? 'blocked' : 'inactive' }} </td>
                                    <td> {{ ($user->verified_email == 0) ? 'NO' : 'YES' }} </td>
                                    <td> {{ ($user->verified_phone == 0) ? 'NO' : 'YES' }} </td>
                                    <!-- <td>
                                        <span class="badge bg-soft-success text-success">Active</span>
                                    </td> -->

                                    <td>
                                        <a href="{{route('user.edit', $user->id)}}" class="action-icon"> <i class="mdi mdi-square-edit-outline"></i></a>
                                        <form method="POST" action="{{route('user.destroy', $user->id)}}">
                                            @csrf
                                            @method('DELETE')
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary-outline action-icon"> <i
                                                        class="mdi mdi-delete"></i></button>

                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination pagination-rounded justify-content-end mb-0">
                        {{ $users->links() }}
                    </div>

                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>


</div>
@include('backend.users.modals')
@endsection

@section('script')

@include('backend.users.pagescript')  

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