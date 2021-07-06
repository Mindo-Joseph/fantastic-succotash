@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Styling - App Styling'])

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
            <h4 class="page-title">App Styling</h4>
        </div>
    </div>
</div>
<!-- end page title -->
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">Font Styles</h4>
                <div class="mb-3">
                    <label class="form-label">Selecting regular font</label>
                    <select class="form-control" name="fonts" onchange="submitRegularFontForm()" id="save_regular_fonts">
                        @foreach($regular_font_options as $regular_font)
                        <option value="{{$regular_font->id}}" {{$regular_font->is_selected == 1 ? 'selected' : ''}}>{{$regular_font->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Selecting medium font</label>
                    <select class="form-control" name="fonts" onchange="submitMediumFontForm()" id="save_medium_fonts">
                        @foreach($medium_font_options as $medium_font)
                        <option value="{{$medium_font->id}}" {{$medium_font->is_selected == 1 ? 'selected' : ''}}>{{$medium_font->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Selecting bold font</label>
                    <select class="form-control" name="fonts" onchange="submitBoldFontForm()" id="save_bold_fonts">
                        @foreach($bold_font_options as $bold_font)
                        <option value="{{$bold_font->id}}" {{$bold_font->is_selected == 1 ? 'selected' : ''}}>{{$bold_font->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">Color Picker</h4>
                <div class="mb-3">
                    <div class="form-group mb-3">
                        <label for="primary_color">Primary Color</label>
                        <input type="text" id="primary_color_option" onchange="submitPrimaryColorForm()" name="primary_color" class="form-control" value="{{ old('primary_color', $primary_color_options->name ?? 'cccccc')}}">
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-group mb-3">
                        <label for="secondary_color">Secondary Color</label>
                        <input type="text" id="secondary_color_option" onchange="submitSecondaryColorForm()" name="secondary_color" class="form-control" value="{{ old('secondary_color', $secondary_color_options->name ?? 'cccccc')}}">
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-group mb-3">
                        <label for="tertiary_color">Tertiary Color</label>
                        <input type="text" id="tertiary_color_option" onchange="submitTertiaryColorForm()" name="tertiary_color" class="form-control" value="{{ old('tertiary_color', $tertiary_color_options->name ?? 'cccccc')}}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">Tab Bar Style</h4>
                <div class="row">
                    @foreach($tab_style_options as $tab_style)
                    <div class="col-lg-4">
                        <div class="card mb-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12 custom-control custom-radio radio_new p-0">
                                        <input type="radio" {{$tab_style->is_selected == 1 ? 'checked' : ''}} onchange="submitTabBarForm(this.id)" value="{{$tab_style->id}}" id="{{$tab_style->id}}" name="tab_bars" class="custom-control-input tab_bar_options" }}>
                                        <label class="custom-control-label" for="{{$tab_style->id}}">
                                            <img class="card-img-top img-fluid" src="{{url('images/'.$tab_style->image)}}" alt="Card image cap">
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">Home Page Style</h4>
                <div class="row">
                    @foreach($homepage_style_options as $homepage_style)
                    <div class="col-lg-4">
                        <div class="card mb-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12 custom-control custom-radio radio_new p-0">
                                        <input type="radio" {{$homepage_style->is_selected == 1 ? 'checked' : ''}} value="{{$homepage_style->id}}" onchange="submitHomePageForm(this.id)" id="{{$homepage_style->id}}" name="home_styles" class="custom-control-input " }}>
                                        <label class="custom-control-label" for="{{$homepage_style->id}}">
                                            <img class="card-img-top img-fluid" src="{{url('images/'.$homepage_style->image)}}" alt="Card image cap">
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
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
        var color1 = new jscolor('#primary_color_option', options);
        var color3 = new jscolor('#tertiary_color_option', options);
        var color2 = new jscolor('#secondary_color_option', options);
    });

    function submitHomePageForm(id) {
        var data_uri = "{{route('styling.updateHomePage')}}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
        let home_styles = id;
        $.ajax({
            type: "post",
            headers: {
                Accept: "application/json"
            },
            url: data_uri,
            data: {
                home_styles: home_styles
            },
            dataType: 'json',
            success: function(response) {
                if (response.status == 'success') {
                    console.log(response.message);
                    $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                }
            }
        });
    }

    function submitTabBarForm(id) {
        var data_uri = "{{route('styling.updateTabBar')}}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
        let tab_bars = id;
        $.ajax({
            type: "post",
            headers: {
                Accept: "application/json"
            },
            url: data_uri,
            data: {
                tab_bars: tab_bars
            },
            dataType: 'json',
            success: function(response) {
                if (response.status == 'success') {
                    console.log(response.message);
                    $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                }
            }
        });
    }

    function submitRegularFontForm() {
        var data_uri = "{{route('styling.updateFont')}}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
        let fonts = $('#save_regular_fonts').val();
        $.ajax({
            type: "post",
            headers: {
                Accept: "application/json"
            },
            url: data_uri,
            data: {
                fonts: fonts
            },
            dataType: 'json',
            success: function(response) {
                if (response.status == 'success') {
                    console.log(response.message);
                    $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                }
            }
        });
    }

    function submitMediumFontForm() {
        var data_uri = "{{route('styling.updateFont')}}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
        let fonts = $('#save_medium_fonts').val();
        $.ajax({
            type: "post",
            headers: {
                Accept: "application/json"
            },
            url: data_uri,
            data: {
                fonts: fonts
            },
            dataType: 'json',
            success: function(response) {
                if (response.status == 'success') {
                    console.log(response.message);
                    $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                }
            }
        });
    }

    function submitBoldFontForm() {
        var data_uri = "{{route('styling.updateFont')}}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
        let fonts = $('#save_bold_fonts').val();
        $.ajax({
            type: "post",
            headers: {
                Accept: "application/json"
            },
            url: data_uri,
            data: {
                fonts: fonts
            },
            dataType: 'json',
            success: function(response) {
                if (response.status == 'success') {
                    console.log(response.message);
                    $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                }
            }
        });
    }

    function submitPrimaryColorForm() {
        var data_uri = "{{route('styling.updateColor')}}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
        let primary_color = $('#primary_color_option').val();
        $.ajax({
            type: "post",
            headers: {
                Accept: "application/json"
            },
            url: data_uri,
            data: {
                primary_color: primary_color,
                color_type: 'Primary'
            },
            dataType: 'json',
            success: function(response) {
                if (response.status == 'success') {
                    console.log(response.message);
                    $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                }
            }
        });
    }

    function submitSecondaryColorForm() {
        var data_uri = "{{route('styling.updateColor')}}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
        let secondary_color = $('#secondary_color_option').val();
        $.ajax({
            type: "post",
            headers: {
                Accept: "application/json"
            },
            url: data_uri,
            data: {
                secondary_color: secondary_color,
                color_type: 'Secondary'
            },
            dataType: 'json',
            success: function(response) {
                if (response.status == 'success') {
                    console.log(response.message);
                    $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                }
            }
        });
    }

    function submitTertiaryColorForm() {
        var data_uri = "{{route('styling.updateColor')}}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
        let tertiary_color = $('#tertiary_color_option').val();
        $.ajax({
            type: "post",
            headers: {
                Accept: "application/json"
            },
            url: data_uri,
            data: {
                tertiary_color: tertiary_color,
                color_type: 'Tertiary'
            },
            dataType: 'json',
            success: function(response) {
                if (response.status == 'success') {
                    console.log(response.message);
                    $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                }
            }
        });
    }
</script>

@endsection