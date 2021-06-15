@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'App Styling'])

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
            <h4 class="page-title">App-Styling</h4>
        </div>
    </div>
</div>
<!-- end page title -->
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <form method="post" enctype="multipart/form-data" id="save_fonts">
                    @csrf
                    <h4 class="header-title">Font Styles</h4>
                    <p class="sub-header">Examples of Spectrum Fonts.</p>
                    <div class="mb-3">
                        <label class="form-label">Selecting multiple dates</label>
                        <select class="form-control" name="fonts" onchange="submitFontForm()">
                            @foreach($font_options as $font)
                            @if($font->is_selected == 1)
                            <option value="{{$font->id}}" selected>{{$font->name}}</option>
                            @else
                            <option value="{{$font->id}}">{{$font->name}}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">Color Picker</h4>
                <p class="sub-header">Examples of Spectrum Colorpicker.</p>
                <div class="mb-3">
                    <form method="post" enctype="multipart/form-data" id="save_colors">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="secondary_color">Simple input field</label>
                            <input type="text" id="secondary_color" onchange="submitColorForm()" name="secondary_color" class="form-control" value="{{ old('secondary_color', $color_options->name ?? 'cccccc')}}">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">Tab Bar Style</h4>
                <p class="sub-header">Examples of Spectrum Colorpicker.</p>
                <form method="post" enctype="multipart/form-data" id="save_tabbar" onchange="submitTabBarForm()">
                    @csrf
                    <div class="row">
                        @foreach($tab_style_options as $tab_style)
                        <div class="col-lg-4">
                            <div class="card mb-0">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12 custom-control custom-radio radio_new p-0">
                                            @if($tab_style->is_selected == 1)
                                            <input type="radio" checked value="{{$tab_style->id}}" id="{{$tab_style->id}}" name="tab_bars" class="custom-control-input" }}>
                                            @else
                                            <input type="radio" value="{{$tab_style->id}}" id="{{$tab_style->id}}" name="tab_bars" class="custom-control-input" }}>
                                            @endif
                                            <label class="custom-control-label" for="{{$tab_style->id}}">
                                                <img class="card-img-top img-fluid" src="{{$tab_style->image}}" alt="Card image cap">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">Home Page Style</h4>
                <p class="sub-header">Examples of Spectrum Colorpicker.</p>
                <form method="post" enctype="multipart/form-data" id="save_homepage" onchange="submitHomePageForm()">
                    @csrf
                    <div class="row">
                        @foreach($homepage_style_options as $homepage_style)
                        <div class="col-lg-4">
                            <div class="card mb-0">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12 custom-control custom-radio radio_new p-0">
                                            @if($homepage_style->is_selected == 1)
                                            <input type="radio" checked value="{{$homepage_style->id}}" id="{{$homepage_style->id}}" name="home_styles" class="custom-control-input" }}>
                                            @else
                                            <input type="radio" value="{{$homepage_style->id}}" id="{{$homepage_style->id}}" name="home_styles" class="custom-control-input" }}>
                                            @endif
                                            <label class="custom-control-label" for="{{$homepage_style->id}}">
                                                <img class="card-img-top img-fluid" src="{{$homepage_style->image}}" alt="Card image cap">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="{{asset('assets/js/jscolor.js')}}"></script>
<script type="text/javascript">
    var options = {
        zIndex: 9999
    }
    $(document).ready(function() {
        var color2 = new jscolor('#secondary_color', options);
    });

    function submitHomePageForm() {
        var form = document.getElementById('save_homepage');
        var formData = new FormData(form);
        var data_uri = "{{route('styling.updateHomePage')}}";

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
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
                }
            }
        });
    }


    function submitTabBarForm() {
        var form = document.getElementById('save_tabbar');
        var formData = new FormData(form);
        var data_uri = "{{route('styling.updateTabBar')}}";

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
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
                }
            }
        });
    }


    function submitFontForm() {
        //console.log("fg4rg");
        // e.preventDefault();
        var form = document.getElementById('save_fonts');
        var formData = new FormData(form);
        var data_uri = "{{route('styling.updateFont')}}";

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
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
                }
            }
        });
    }

    function submitColorForm() {
        var form = document.getElementById('save_colors');
        var formData = new FormData(form);
        var data_uri = "{{route('styling.updateColor')}}";

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
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
                }
            }
        });
    }
</script>

@endsection