@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Customers'])
@section('css')
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{asset('assets/css/intlTelInput.css')}}">
<style type="text/css">
    .iti__flag-container li, .flag-container li{
        display: block;
    }
    .iti.iti--allow-dropdown, .allow-dropdown {
        position: relative;
        display: inline-block;
        width: 100%;
    }
    .iti.iti--allow-dropdown .phone, .flag-container .phone {
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
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Customers</h4>
            </div>
        </div>
    </div>
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
                            <button class="btn btn-info waves-effect waves-light text-sm-right addUserModal"
                             userId="0" style=""><i class="mdi mdi-plus-circle mr-1"></i> Add
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <form name="saveOrder" id="saveOrder"> @csrf </form>
                        <table class="table table-centered table-nowrap table-striped" id="banner-datatable">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Login Type</th>
                                    <th>Email/Auth-id</th>
                                    <th>Phone</th>
                                    <th>Email Otp</th>
                                    <th>Phone Otp</th>
                                    <th>Orders</th>
                                    <th>Active Orders</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="post_list">
                                @foreach($users as $user)
                                <?php 
                                    $loginType = 'Email'; 
                                    $loginTypeValue = $user->email;
                                    if(!empty($user->facebook_auth_id)){
                                        $loginType = 'Facebook';
                                        $loginTypeValue = $user->facebook_auth_id;

                                    }elseif(!empty($user->twitter_auth_id)){
                                        $loginType = 'Twitter';
                                        $loginTypeValue = $user->twitter_auth_id;

                                    }elseif(!empty($user->google_auth_id)){
                                        $loginType = 'Google';
                                        $loginTypeValue = $user->google_auth_id;

                                    }elseif(!empty($user->apple_auth_id)){
                                        $loginType = 'Apple';
                                        $loginTypeValue = $user->apple_auth_id;
                                    } 
                                ?>
                                <tr data-row-id="{{$user->id}}">
                                    <td> 
                                        <img src="{{$user->image['proxy_url'].'40/40'.$user->image['image_path']}}" class="rounded-circle" alt="{{$user->id}}" >
                                    </td>
                                    <td>{{$user->name}}</td>
                                    <td>{{$loginType}}</td>
                                    <td> 
                                        @if($user->is_email_verified == 1)
                                          <i class="mdi mdi-email-check mr-1 mdi-icons"></i>
                                        @endif
                                        {{$loginTypeValue}}
                                    </td>
                                    <td>
                                        @if($user->is_phone_verified == 1)
                                          <i class="mdi mdi-phone-check mr-1 mdi-icons"></i>
                                        @endif
                                        {{ $user->phone_number }} 
                                    </td>
                                    <td>{{$user->email_token}}</td>
                                    <td>{{$user->phone_token}}</td>
                                    <td>{{$user->orders_count}}</td>
                                    <td>{{$user->active_orders_count}}</td>
                                    <td>
                                        <input type="checkbox" data-id="{{$user->id}}" id="cur_{{$user->id}}" data-plugin="switchery" name="userAccount" class="chk_box" data-color="#43bee1" {{($user->status == 1) ? 'checked' : ''}} >
                                    </td> 
                                    <td> 
                                        <div class="form-ul" style="width: 60px;">
                                            <div class="inner-div" >
                                                @if(Auth::user()->is_superadmin == 1)<a href="{{route('customer.new.edit', $user->id)}}"  class="action-icon editIconBtn"> 
                                                <i class="mdi mdi-square-edit-outline"></i></a>

                                                <a href="{{route('customer.account.action', [$user->id, 3])}}" onclick="return confirm('Are you sure? You want to delete the user.')" class="action-icon"> <i class="mdi mdi-delete" title="Delete user"></i></a>
                                                @endif    
                                            </div>
                                        </div>
                                    </td> 
                                </tr>
                               @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination pagination-rounded justify-content-end mb-0">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('backend.users.modals')
@endsection
@section('script')
<script src="{{asset('assets/js/intlTelInput.js')}}"></script>
<script type="text/javascript">
    var input = document.querySelector("#phone");
    window.intlTelInput(input, {
        separateDialCode: true,
        hiddenInput: "contact",
        utilsScript: "{{asset('assets/js/utils.js')}}",
    });
    $(document).ready(function () {
        $("#phone").keypress(function (e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
            return true;
        });
    });
    $('.iti__country').click(function(){
        var code = $(this).attr('data-country-code');
        document.getElementById('addCountryData').value = code;
    })
</script>
@include('backend.users.pagescript')
@endsection