@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Styling - Web Styling'])
@section('css')
    <link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.1/spectrum.min.css">
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Web Styling</h4>
        </div>
    </div>
</div>
<form id="favicon-form" method="post" enctype="multipart/form-data">
<div class="row">
    <div class="col-lg-2">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">Favicon</h4>
                <div class="mb-0">
                    <label>Upload Favicon</label>
                    <input type="file" accept="image/*" data-default-file="{{$client_preferences->favicon ? $client_preferences->favicon['proxy_url'].'600/400'.$client_preferences->favicon['image_path'] : ''}}" data-plugins="dropify" name="favicon" class="dropify" id="image"/>
                    <label class="logo-size d-block text-right mt-1">Icon Size 32x32</label>                    
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-2">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">Color</h4>
                <div class="mb-0">
                    <div class="form-group mb-0">
                        <label for="primary_color">Primary Color</label>
                        <input type="text" id="primary_color_option" name="primary_color" class="form-control" value="{{ old('primary_color', $client_preferences->web_color ?? 'cccccc')}}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-xl-3">
        <div class="card">
            <div class="card-body">
               <div class="d-flex align-items-center justify-content-between mb-3">
                    <h4 class="header-title mb-0">Age Restriction</h4>
                    <div class="mb-0">
                        <input type="checkbox" id="age_restriction" data-plugin="switchery" name="age_restriction" class="chk_box1" data-color="#43bee1" {{$client_preferences->age_restriction == 1 ? 'checked' : ''}}>
                    </div>
               </div>
                <h5 class="header-title mb-2">Title</h5>
                <input type="text" class="form-control" id="age_restriction_title" name="age_restriction_title" value="{{ old('age_restriction_title', $client_preferences->age_restriction_title ?? '')}}">
            </div>
        </div>
    </div>
    <div class="col-md-4 col-xl-2">
        <div class="card">
            <div class="card-body">
                <ul class="pl-0 mb-0">
                    <li class="d-flex align-items-center justify-content-between">
                        <h4 class="header-title mb-2">Cart Toggle</h4>
                        <div class="mb-0">
                            <input type="checkbox" id="cart_enable" data-plugin="switchery" name="cart_enable" class="chk_box1" data-color="#43bee1" {{$client_preferences->cart_enable == 1 ? 'checked' : ''}}>
                        </div>
                    </li>
                    <li class="d-flex align-items-center justify-content-between mt-2">
                        <h4 class="header-title mb-2">Rating Toggle</h4>
                        <div class="mb-0">
                            <input type="checkbox" id="rating_enable" data-plugin="switchery" name="rating_enable" class="chk_box2" data-color="#43bee1" {{$client_preferences->rating_check == 1 ? 'checked' : ''}}>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
   
    
   
    <!-- <div class="col-md-4 col-xl-2">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-2">Cart Toggle</h4>
                <div class="mb-0">
                    <input type="checkbox" id="cart_enable" data-plugin="switchery" name="cart_enable" class="chk_box1" data-color="#43bee1" {{$client_preferences->cart_enable == 1 ? 'checked' : ''}}>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-xl-2">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-2">Rating Toggle</h4>
                <div class="mb-0">
                    <input type="checkbox" id="rating_enable" data-plugin="switchery" name="rating_enable" class="chk_box2" data-color="#43bee1" {{$client_preferences->rating_check == 1 ? 'checked' : ''}}>
                </div>
            </div>
        </div>
    </div>     -->
</div>
</form>

@endsection

@section('script')
<script src="{{asset('assets/js/jscolor.js')}}"></script>
<script type="text/javascript">
    var options = {
        zIndex: 9999
    }
    $(document).ready(function() {
        var color1 = new jscolor('#primary_color_option', options);
    });

    $("#primary_color_option").change(function() {
        submitData();
    });

    $("#cart_enable").change(function() {
        submitData();
    });
    $("#rating_enable").change(function() {
        submitData();
    });
    $("#age_restriction").change(function() {
        submitData();
    });
    $("#age_restriction_title").keyup(function() {
        submitData();
    });
    $("#image").change(function() {
        submitData();
    });
    function submitData(){
        var form = document.getElementById('favicon-form');
        var formData = new FormData(form);
        var data_uri = "{{route('styling.updateWebStyles')}}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
        $.ajax({
            type: "post",
            url: data_uri,
            data: formData,
            contentType: false,
            processData: false,
            headers: {Accept: "application/json"},
            success: function(response) {
                if (response.status == 'success') {
                    console.log(response.message);
                    $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                    var r = document.querySelector(':root');
                    r.style.setProperty('--theme-deafult', 'lightblue');
                }
            }
        });
    }
</script>
@endsection