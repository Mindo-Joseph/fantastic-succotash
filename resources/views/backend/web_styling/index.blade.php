@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Styling - Web Styling'])

@section('css')
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.1/spectrum.min.css">

@endsection

@section('content')

<!-- start page title -->
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
        <div class="card h-100">
            <div class="card-body">
                <h4 class="header-title mb-3">Favicon</h4>
                <div class="mb-0">
                    <input type="file" accept="image/*" data-default-file="{{$client_preferences->favicon ? $client_preferences->favicon['proxy_url'].'600/400'.$client_preferences->favicon['image_path'] : ''}}" data-plugins="dropify" name="favicon" class="dropify" id="image"/>
                    <p class="text-muted text-center mt-2 mb-0">Upload Favicon</p>
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card h-100">
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
            headers: {
                Accept: "application/json"
            },
            url: data_uri,
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.status == 'success') {
                    console.log(response.message);
                    $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                    var r = document.querySelector(':root');
                    r.style.setProperty('--theme-deafult', 'lightblue');
                    console.log("Fergw");
                }
            }
        });
    }
</script>
@endsection