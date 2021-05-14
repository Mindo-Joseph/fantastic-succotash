@extends('layouts.god-vertical', ['title' => 'Clients'])

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
                <h4 class="page-title">Clients</h4>
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
                            <a class="btn btn-info waves-effect waves-light text-sm-right" href="{{route('client.create')}}"><i class="mdi mdi-plus-circle mr-1"></i> Add </a>
                        </div>

                    </div>

                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-striped" id="products-datatable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Password</th>
                                    <th>Phone</th>
                                    <th>DB Name</th>
                                    <!-- <th>DB User</th>
                                    <th>DB Password</th> -->
                                    <th>Client Code</th>
                                    <th style="width: 85px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($clients as $client)
                                <tr>
                                    <td class="table-user">
                                        <a href="javascript:void(0);" class="text-body font-weight-semibold">{{$client->name}}</a>
                                    </td>
                                    <td> {{$client->email}} </td>
                                    <td style="width:100px;max-width:100px;"> {{$client->encpass}} </td>
                                    <td> {{$client->phone_number}} </td>
                                    <td> {{$client->database_name}} </td>
                                    <!-- <td> {{$client->database_username}} </td>
                                    <td> {{$client->database_password}} </td> -->
                                    <td> {{$client->code}} </td>
                                    <td>

                                        <a href="{{route('client.edit', $client->id)}}" class="btn btn-primary-outlineaction-icon"> <h3><i class="mdi mdi-square-edit-outline"></i></h3></a>
                                        
                                        <!-- <button class="btn btn-primary-outline blockClient action-icon" cli_id="{{$client->id}}" status="{{$client->status}}"> <h3><i class="mdi {{ ($client->status == 2) ? 'mdi-lock-open-variant-outline' : 'mdi-lock-outline'}}"></i></h3></button> 
                                        <button class="btn btn-primary-outline deleteClient action-icon" cli_id="{{$client->id}}"> <h3><i class="mdi mdi-delete"></i></h3></button>-->

                                        <a href="{{URL::to('godpanel/delete/client/'.$client->id)}}" class="btn btn-primary-outlineaction-icon"> <h3><i class="mdi mdi-delete"></i></h3></a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination pagination-rounded justify-content-end mb-0">
                        {{ $clients->links() }}
                    </div>

                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>


</div>
@endsection

@section('script')


<script type="text/javascript">

    $('.deleteClient').click(function(){
        alert('deleteClient');
        var id = $(this).attr('cli_id');

        if(confirm('Are you sure? You want to delete this client.')) {
            //document.getElementById('client_'+id).value = action;
            $('#formClient_'+id).submit();
        }
        return false;
    });

    // $('.blockClient').click(function(){
    //     var status = $(this).attr('status');
    //     var id = $(this).attr('cli_id');

    //     var msg = 'Are you sure? You want to block this client.';
    //     var action = 2;
    //     if(status == 2) {
    //         msg = 'Are you sure? You want to activate this client.';
    //         action = 1;
    //     }

    //     if(confirm(msg)) {
    //         document.getElementById('client_'+id).value = action;
    //         $('#formClient_'+id).submit();
    //     }
    //     return false;
    // });

    // $('.deleteClient').click(function(){
    //     var status = $(this).attr('status');
    //     var id = $(this).attr('cli_id');

    //     var msg = 'Are you sure? You want to delete this client.';
    //     var action = 3;

    //     if(confirm(msg)) {
    //         document.getElementById('client_'+id).value = action;
    //         $('#formClient_'+id).submit();
    //     }
    //     return false;
    // });
    
</script>

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