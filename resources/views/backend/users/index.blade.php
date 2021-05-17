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
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Email Token</th>
                                    <th>Phone Token</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="post_list">
                                @foreach($users as $user)
                                <tr data-row-id="{{$user->id}}">
                                    <td> 
                                        <img class="rounded-circle" src="{{$user->image['proxy_url'].'60/60'.$user->image['image_path']}}" alt="{{$user->id}}" >
                                    </td>
                                    <td>{{ $user->name}}</td>
                                    <td>{{ (!empty($user->system_id)) ? 'Guest User' : $user->email }}</td>
                                    <td>{{ $user->phone_number }}</td>
                                    
                                    <td>{{(!empty($user->email_token)) ? $user->email_token : 'N/A'}}</td>
                                    <td>{{(!empty($user->phone_token)) ? $user->phone_token : 'N/A'}}</td>
                                    <td>{{($user->is_email_verified == 1) ? 'Verified' : 'Not Verified'}}</td>
                                    <td> 
                                        <div class="form-ul" style="width: 60px;">
                                            <!-- <div class="inner-div" style="float: left;">
                                                <a class="action-icon openBannerModal" userId="{{$user->id}}" href="#"><h3> <i class="mdi mdi-square-edit-outline"></i></h3></a> 
                                            </div> -->
                                            <div class="inner-div" style="float: left;">
                                                <a class="action-icon" userId="{{$user->id}}" href="{{route('customer.show', $user->id)}}"><h3> <i class="mdi mdi-eye"></i></h3></a> 
                                            </div>
                                            <?php 
                                                $status = 2; $icon = 'mdi-lock';
                                                $title = 'Block user account';
                                            if($user->status == 2){
                                                $status = 1; $icon = 'mdi-lock-open-variant'; 
                                                $title = 'Activate user account';
                                            } ?>
                                            <div class="inner-div" style="float: left;">
                                                <a class="action-icon" userId="{{$user->id}}" href="{{route('customer.account.action', [$user->id, $status])}}"><h3> <i class="mdi {{$icon}}" title="{{$title}}"></i></h3></a> 
                                            </div>
                                            <div class="inner-div" >
                                                <a href="{{route('customer.account.action', [$user->id, 3])}}" onclick="return confirm('Are you sure? You want to delete the user.')" class="action-icon"> <h3> <i class="mdi mdi-delete" title="Delete user"></i></h3></a>
                                            </div>
                                        </div>
                                    </td> 

                               
                                </tr>
                               @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination pagination-rounded justify-content-end mb-0">
                        {{-- $users->links() --}}
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
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
        //allowDropdown: true,
        //autoHideDialCode: true,
      // autoPlaceholder: "off",
      // dropdownContainer: document.body,
      // excludeCountries: ["us"],
      // formatOnDisplay: false,
      // geoIpLookup: function(callback) {
      //   $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
      //     var countryCode = (resp && resp.country) ? resp.country : "";
      //     callback(countryCode);
      //   });
      // },
        hiddenInput: "contact",
        //initialCountry: "auto",
      // localizedCountries: { 'de': 'Deutschland' },
        //nationalMode: false,
      // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
        //placeholderNumberType: "MOBILE",
      // preferredCountries: ['cn', 'jp'],
        //separateDialCode: true,
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