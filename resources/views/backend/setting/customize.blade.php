@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Customize'])

@section('css')
<link href="https://itsjavi.com/fontawesome-iconpicker/dist/css/fontawesome-iconpicker.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Customize</h4>

            </div>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-sm-12">
            <div class="text-sm-left">
                @if (\Session::has('success'))
                <div class="alert alert-success">
                    <span>{!! \Session::get('success') !!}</span>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="row col-spacing">
        <div class="col-lg-3 col-xl-3 mb-3">
            <form method="POST" class="h-100" action="{{route('configure.update', Auth::user()->code)}}">
                @csrf
                <div class="card-box mb-0 h-100 pb-0">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h4 class="header-title mb-0">Theme</h4>
                        <input type="hidden" name="send_to" id="send_to" value="customize">
                        <button class="btn btn-info d-block" type="submit"> Save </button>
                    </div>
                    <p class="sub-header">
                        Choose between light and dark theme, for the platform.
                    </p>
                    <div class="row">
                        <div class="col-sm-12 mb-2">
                            <label for="primary_color">Admin Panel Theme</label> <br />
                            <div class="radio radio-blue form-check-inline">
                                <input type="radio" id="light_theme" value="light" name="theme_admin" {{ (isset($preference) && $preference->theme_admin =="light")? "checked" : "" }}>
                                <label for="light_theme"> Light theme </label>
                            </div>
                            <div class="radio form-check-inline">
                                <input type="radio" id="dark_theme" value="dark" name="theme_admin" {{ (isset($preference) &&  $preference->theme_admin =="dark")? "checked" : "" }}>
                                <label for="dark_theme"> Dark theme </label>
                            </div>
                            @if($errors->has('theme'))
                            <span class="text-danger" role="alert">
                                <strong>{{ $errors->first('theme') }}</strong>
                            </span>
                            @endif
                        </div>

                    </div>
                </div>
            </form>
        </div>
        <div class="col-lg-4 col-xl-3 mb-3">
            <form method="POST" class="h-100" action="{{route('configure.update', Auth::user()->code)}}">
                @csrf
                <div class="card-box mb-0 h-100 pb-0">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h4 class="header-title mb-0">Date & Time</h4>
                        <input type="hidden" name="send_to" id="send_to" value="customize">
                        <button class="btn btn-info d-block" type="submit"> Save </button>
                    </div>
                    <p class="sub-header">
                        View and update the date & time format.
                    </p>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="form-group mb-2">
                                <label for="date_format">Date Format</label>
                                <select class="form-control" id="date_format" name="date_format">
                                    <option value="DD-MM-YYYY" {{ ($preference && $preference->date_format =="DD-MM-YYYY")? "selected" : "" }}>
                                        DD-MM-YYYY</option>
                                    <option value="DD/MM/YYYY" {{ ($preference && $preference->date_format =="DD/MM/YYYY")? "selected" : "" }}>
                                        DD/MM/YYYY</option>
                                    <option value="YYYY-MM-DD" {{ ($preference && $preference->date_format =="YYYY-MM-DD")? "selected" : "" }}>
                                        YYYY-MM-DD</option>
                                </select>
                                @if($errors->has('date_format'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('date_format') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-2">
                                <label for="time_format">Time Format</label>
                                <select class="form-control" id="time_format" name="time_format">
                                    <option value="12" {{ ($preference && $preference->time_format =="12")? "selected" : "" }}>12 hours
                                    </option>
                                    <option value="24" {{ ($preference && $preference->time_format =="24")? "selected" : "" }}>24 hours
                                    </option>
                                </select>
                                @if($errors->has('time_format'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('time_format') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-lg-5 col-xl-6 mb-3">
            <form method="POST" class="h-100" action="{{route('configure.update', Auth::user()->code)}}">
                @csrf
                <div class="card-box mb-0 h-100 pb-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="header-title mb-0">Nomenclature</h4>
                        <input type="hidden" name="send_to" id="send_to" value="customize">
                        <button class="btn btn-info d-block" type="submit"> Save </button>
                    </div>
                    <p class="sub-header">
                        Define and update the nomenclature
                    </p>
                    <div class="row col-spacing">
                        <div class="col-sm-4 mb-2">
                            <label for="languages">Primary Language</label>
                            <select class="form-control" id="primary_language" name="primary_language">
                                @foreach($languages as $lang)
                                <option {{(isset($preference) && ($lang->id == $preference->primarylang->language_id))? "selected" : "" }} value="{{$lang->id}}"> {{$lang->name}} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-8 mb-2">
                            <label for="languages">Additional Languages</label>
                            <select class="form-control select2-multiple" id="languages" name="languages[]" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">
                                @foreach($languages as $lang)
                                @if($lang->id != $preference->primarylang->language_id)
                                <option value="{{$lang->id}}" {{ (isset($preference) && in_array($lang->id, $cli_langs))? "selected" : "" }}> {{$lang->name}} </option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-4 mb-2">
                            <label for="primary_currency">Primary Currency</label>

                            <select class="form-control" id="primary_currency" name="primary_currency">
                                @foreach($currencies as $currency)
                                <option iso="{{$currency->iso_code.' '.$currency->symbol}}" {{ (isset($preference) && $preference->primary->currency->id == $currency->id) ? "selected" : ""}} value="{{$currency->id}}"> {{$currency->iso_code.' '.$currency->symbol}} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-8">
                            <label for="currency">Additional Currency</label>
                            <select class="form-control select2-multiple" id="currency" name="currency_data[]" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">
                                @foreach($currencies as $currency)
                                @if($preference->primary->currency->id != $currency->id)
                                <option value="{{$currency->id}}" iso="{{$currency->iso_code}}" {{ (isset($preference) && in_array($currency->id, $cli_currs))? "selected" : "" }}> {{$currency->iso_code}} {{!empty($currency->symbol) ? $currency->symbol : ''}} </option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 mt-2">
                            <div class="row multiplierData">
                                @if($preference->currency)
                                @foreach($preference->currency as $ac)
                                <div class="col-sm-10 offset-sm-4 col-lg-12 offset-lg-0 col-xl-8 offset-xl-4 mb-2" id="addCur-{{$ac->currency->id}}">
                                    <label class="primaryCurText">1 {{$preference->primary->currency->iso_code}} {{!empty($preference->primary->currency->symbol) ? $preference->primary->currency->symbol : ''}} = </label>
                                    <input class="form-control w-50 d-inline-block" type="number" value="{{$ac->doller_compare}}" step=".01" name="multiply_by[]" min="0.01"> {{$ac->currency->iso_code}} {{!empty($ac->currency->symbol) ? $ac->currency->symbol : ''}}
                                    <input type="hidden" name="cuid[]" class="curr_id" value="{{ $ac->currency->id }}">
                                </div>
                                @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-lg-3 col-lg-3 mb-3">
            <form method="POST" class="h-100" action="{{route('client.updateDomain', Auth::user()->code)}}">
                @csrf
                <div class="card-box mb-0 h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="header-title mb-0">Custom Domain</h4>
                        <input type="hidden" name="send_to" id="send_to" value="customize">
                        <button class="btn btn-info d-block" type="submit"> Save </button>
                    </div>
                    <p class="sub-header">
                        Update custom domain here.
                    </p>
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <div class="form-group mb-3">
                                <label for="custom_domain">Custom Domain</label>
                                <input type="text" name="custom_domain" id="custom_domain" placeholder="xyz" class="form-control" value="{{ old('custom_domain', $preference->domain->custom_domain ?? '')}}">
                                @if($errors->has('custom_domain'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('custom_domain') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-lg-3 col-lg-3 mb-3">
            <form method="POST" class="h-100" action="{{route('referandearn.update', Auth::user()->code)}}">
                @csrf
                <div class="card-box mb-0 h-100 pb-1">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h4 class="header-title mb-0">Refer and Earn</h4>
                        <button class="btn btn-info d-block" type="submit"> Save </button>
                    </div>
                    <div class="col-sm-10 offset-sm-4 col-lg-12 offset-lg-0 mb-2 mt-4" id="addCur-160">
                        <label class="primaryCurText">Referred To Amount = </label>
                        <input class="form-control" type="number" id="reffered_to_amount" name="reffered_to_amount" value="{{ old('reffered_to_amount', $reffer_to ?? '')}}" min="0">
                    </div>
                    <div class="col-sm-10 offset-sm-4 col-lg-12 offset-lg-0 mb-2 mt-3" id="addCur-160">
                        <label class="primaryCurText">Referred By Amount = </label>
                        <input class="form-control" type="number" name="reffered_by_amount" id="reffered_by_amount" value="{{ old('reffered_by_amount', $reffer_by ?? '')}}" min="0">
                    </div>
                </div>
            </form>
        </div>

        <div class="col-lg-6 col-lg-3 mb-3">
            <div class="card-box mb-0 h-100 pb-1">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h4 class="header-title mb-0">Social Media</h4>
                    <button class="btn btn-info d-block" id="add_social_media_modal"> <i class="mdi mdi-plus-circle mr-1"></i>Add </button>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-centered table-nowrap table-striped" id="promo-datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Icon</th>
                                        <th>Url</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="post_list">
                                    <tr data-row-id="">
                                        <td></td>
                                        <td><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></td>
                                        <td style="width:100px">
                                            <p class="ellips">Second</p>
                                        </td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div> <!-- container -->

<div id="standard_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Social Media</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form id="save_social_media" action="">
                    @csrf
                    <div class="form-group">
                        <label for="">Icon</label>
                        <input class="form-control icp icp-auto" name="icon" value="fas fa-anchor" type="text" />
                    </div>
                    <div class="form-group">
                        <label for="">Url</label>
                        <input class="form-control" name="url" type="text">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary submitSaveSocialForm">Save</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="{{asset('assets/js/jscolor.js')}}"></script>
<script src="https://itsjavi.com/fontawesome-iconpicker/dist/js/fontawesome-iconpicker.js"></script>
<script type="text/javascript">
    var options_iconpicker = {
        title: false,
        selected: false,
        trigger: 'hover',
        defaultValue: true,
        placement: 'bottom',
        collision: 'none',
        trigger : 'hover',
        animation: false,
        hideOnSelect: false,
        showFooter: false,
        searchInFooter: false,
        mustAccept: false,
        selectedCustomClass: 'bg-primary',
        input: 'input,.icp-auto',
        inputSearch: false,
        container: false,
        templates: {
            popover: '<div class="iconpicker-popover popover"><div class="arrow"></div>' +
                '<div class="popover-title"></div><div class="popover-content"></div></div>',
            footer: '<div class="popover-footer"></div>',
            buttons: '<button class="iconpicker-btn iconpicker-btn-cancel btn btn-default btn-sm">Cancel</button>' +
                ' <button class="iconpicker-btn iconpicker-btn-accept btn btn-primary btn-sm">Accept</button>',
            search: '<input type="search" class="form-control iconpicker-search" placeholder="Type to filter" />',
            iconpicker: '<div class="iconpicker"><div class="iconpicker-items"></div></div>',
            iconpickerItem: '<a role="button" href="#" class="iconpicker-item"><i></i></a>',
        }
    };
    $(document).ready(function() {
        $(document).on("click", "#add_social_media_modal", function() {
            $('.icp-auto').iconpicker(options_iconpicker);
            $('#standard_modal').modal('show');
        });
    });

    $(document).on('click', '.submitSaveSocialForm', function(e) {
        e.preventDefault();
        var form = document.getElementById('save_social_media');
        var formData = new FormData(form);
        // var url = "{{route('socialMedia.store')}}";

        // saveData(formData, 'edit', url);

    });
</script>
<script type="text/javascript">
    var options = {
        zIndex: 9999
    }
    $(document).ready(function() {
        var color1 = new jscolor('#primary_color', options);
        var color2 = new jscolor('#secondary_color', options);
    });

    function generateRandomString(length) {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

        for (var i = 0; i < length; i++)
            text += possible.charAt(Math.floor(Math.random() * possible.length));

        return text;
    }

    function genrateKeyAndToken() {
        var key = generateRandomString(30);
        var token = generateRandomString(60);

        $('#personal_access_token_v1').val(key);
        $('#personal_access_token_v2').val(token);
    }

    var existCid = [];

    $('#primary_currency').change(function() {
        var pri_curr = $('#primary_currency option:selected').text();
        console.log(pri_curr);
        $(document).find('.primaryCurText').html('1 ' + pri_curr + '  = ');
    });

    $('#currency').change(function() {
        var pri_curr = $('#primary_currency option:selected').text();

        var cidText = $('#currency').select2('data');

        var activeCur = [];

        for (i = 0; i < cidText.length; i++) {
            activeCur.push(cidText[i].id);
        }

        $(".curr_id").each(function() {
            var cv = $(this).val();

            if (existCid.indexOf(cv) === -1) {
                existCid.push(cv);
            }
        });

        for (i = 0; i < existCid.length; i++) {
            if (activeCur.indexOf(existCid[i]) === -1) {
                $('#addCur-' + existCid[i]).remove();
            }
        }

        for (i = 0; i < cidText.length; i++) {

            if (existCid.indexOf(cidText[i].id) === -1) {
                var text = '<div class="col-sm-10 offset-sm-4 col-lg-12 offset-lg-0 col-xl-8 offset-xl-4 mb-2" id="addCur-' + cidText[i].id + '"><label class="primaryCurText">1 ' + pri_curr + '  = </label> <input type="number" name="multiply_by[]" min="0.01" value="0" step=".01">' + cidText[i].text + '<input type="hidden" name="cuid[]" class="curr_id" value="' + cidText[i].id + '"></div>';
                $('.multiplierData').append(text);
            }

        }
    });
</script>

@endsection