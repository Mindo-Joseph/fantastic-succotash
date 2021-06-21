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
                <form method="post" enctype="multipart/form-data" id="save_regular_fonts">
                    @csrf
                    <h4 class="header-title">Font Styles</h4>
                    <p class="sub-header">Examples of Spectrum Fonts.</p>
                    <div>
                        <label class="form-label">Selecting regular font</label>
                        <select class="form-control" name="fonts" onchange="submitRegularFontForm()">
                            @foreach($regular_font_options as $regular_font)
                            @if($regular_font->is_selected == 1)
                            <option value="{{$regular_font->id}}" selected>{{$regular_font->name}}</option>
                            @else
                            <option value="{{$regular_font->id}}">{{$regular_font->name}}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <form method="post" enctype="multipart/form-data" id="save_medium_fonts">
                    @csrf
                    <div>
                        <label class="form-label">Selecting medium font</label>
                        <select class="form-control" name="fonts" onchange="submitMediumFontForm()">
                            @foreach($medium_font_options as $medium_font)
                            @if($medium_font->is_selected == 1)
                            <option value="{{$medium_font->id}}" selected>{{$medium_font->name}}</option>
                            @else
                            <option value="{{$medium_font->id}}">{{$medium_font->name}}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <form method="post" enctype="multipart/form-data" id="save_bold_fonts">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Selecting bold font</label>
                        <select class="form-control" name="fonts" onchange="submitBoldFontForm()">
                            @foreach($bold_font_options as $bold_font)
                            @if($bold_font->is_selected == 1)
                            <option value="{{$bold_font->id}}" selected>{{$bold_font->name}}</option>
                            @else
                            <option value="{{$bold_font->id}}">{{$bold_font->name}}</option>
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
                            <label for="primary_color">Primary Color</label>
                            <input type="text" id="primary_color" onchange="submitPrimaryColorForm()" name="primary_color" class="form-control" value="{{ old('primary_color', $primary_color_options->name ?? 'cccccc')}}">
                        </div>
                    </form>
                </div>
                <div class="mb-3">
                    <form method="post" enctype="multipart/form-data" id="save_colors">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="secondary_color">Secondary Color</label>
                            <input type="text" id="secondary_color" onchange="submitSecondaryColorForm()" name="secondary_color" class="form-control" value="{{ old('secondary_color', $secondary_color_options->name ?? 'cccccc')}}">
                        </div>
                    </form>
                </div>
                <div class="mb-3">
                    <form method="post" enctype="multipart/form-data" id="save_colors">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="tertiary_color">Tertiary Color</label>
                            <input type="text" id="tertiary_color" onchange="submitTertiaryColorForm()" name="tertiary_color" class="form-control" value="{{ old('tertiary_color', $tertiary_color_options->name ?? 'cccccc')}}">
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
        var color1 = new jscolor('#primary_color', options);
    });
    $(document).ready(function() {
        var color2 = new jscolor('#secondary_color', options);
    });
    $(document).ready(function() {
        var color3 = new jscolor('#tertiary_color', options);
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


    function submitRegularFontForm() {
        //console.log("fg4rg");
        // e.preventDefault();
        var form = document.getElementById('save_regular_fonts');
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

    function submitMediumFontForm() {
        //console.log("fg4rg");
        // e.preventDefault();
        var form = document.getElementById('save_medium_fonts');
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

    function submitBoldFontForm() {
        //console.log("fg4rg");
        // e.preventDefault();
        var form = document.getElementById('save_bold_fonts');
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
    function submitPrimaryColorForm() {
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