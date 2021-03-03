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
                <h4 class="page-title">Customers</h4>
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
                            <!--<button class="btn btn-blue waves-effect waves-light text-sm-right addVendor"><i class="mdi mdi-plus-circle mr-1"></i> Add </button> -->
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
                                    <th>Role</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td> {{$user->name}} </td>
                                    <td> {{$user->email}} </td>
                                    <td> {{$user->phone_number}} </td>
                                    <td> {{$user->country->name}} </td>
                                    <td>
                                        @if($user->status == 0)
                                            Pending
                                        @elseif($user->status == 1)
                                            Active
                                        @elseif($user->status == 2)
                                            Block
                                        @else
                                            Inactive
                                        @endif
                                    </td>
                                    <td> {{ $user->role->role }} </td>
                                    <td>
                                        @if($user->status == 0)
                                            <a class="btn btn-blue waves-effect waves-light text-sm-right"
                                             href="{{route('customer.account.action', ['user' => $user->id, 'action' => 1])}}" onclick="return confirm('Are you sure? You want to approve customer account.')"><i class="mdi mdi-lock-open-check mr-1"></i> Approve User
                                            </a>  
                                        @elseif($user->status == 1)
                                            <a class="btn btn-danger waves-effect waves-light text-sm-right"
                                             href="{{route('customer.account.action', ['user' => $user->id, 'action' => 2])}}" onclick="return confirm('Are you sure? You want to block customer account.')"><i class="mdi mdi-lock mr-1"></i> Block User
                                            </a>
                                        @elseif($user->status == 2)
                                            <a class="btn btn-blue waves-effect waves-light text-sm-right"
                                             href="{{route('customer.account.action', ['user' => $user->id, 'action' => 1])}}" onclick="return confirm('Are you sure? You want to activate customer account.')"><i class="mdi mdi-lock-open-variant mr-1"></i> Activate User
                                            </a>
                                        @endif
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