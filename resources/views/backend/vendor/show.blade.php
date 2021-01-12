@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Vendor'])

@section('css')
<link href="{{asset('assets/css/calender_main.css')}}" rel="stylesheet" type="text/css" />

<style type="text/css">
    #map-canvas {
        height: 90%;
        margin: 0px;
        padding: 0px;
        position: unset;
    }

    .serch {
        width: 100%;
        margin: 0px;
    }

    .imageagent {
        border-radius: 50%;
        height: 40px;
        width: 40px;
        margin-right: 15px;
    }

    .cornar {
        border: 1px solid #ddd;
        padding-top: 10px;
        height: 240px;
        overflow-y: auto;
        padding: 10px;
    }

    .teamshow {
        margin-left: 58px;

    }

    .display {
        height: 35px;
        width: 67px;
    }

    .boxes {
        margin-bottom: 10px;
        height: 100px;
    }

    .new {
        vertical-align: initial !important;
        display: revert !important;
    }

    #new_show {
        display: none;
    }

    .agentcheck {}

    .search {
        background-color: #02f2cc;
    }
    .card-box {

        padding: 1.5rem;
        box-shadow: 0 0 8px 0 rgba(154, 161, 171, 0.15) !important;
        margin-bottom: 24px !important;
        border-radius: 0.25rem;
        /* margin-bottom: 10px; */
    }
    .select_all{
        position: relative;
        top: 8px;
    }
    #show_map-canvas{
        height: 600px;
    }
    .pac-container, .pac-container .pac-item { z-index: 99999 !important; }
</style>
@endsection

@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">{{ucfirst($vendor->name)}} profile</h4>
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
                    @if (\Session::has('error_delete'))
                    <div class="alert alert-danger">
                        <span>{!! \Session::get('error_delete') !!}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-lg-3 col-xl-3">
                @php $bgimage =  url('storage/'.$vendor->banner); @endphp
                <div class="card-box text-center" style="">
                    
                    <div class="background">
                        <img src="{{ url('storage/'.$vendor->logo)}}" class="rounded-circle avatar-lg img-thumbnail"
                            alt="profile-image">

                        <h4 class="mb-0">{{ucfirst($vendor->name)}}</h4>
                        <p class="text-muted">{{$vendor->address}}</p>

                        <button type="button" class="btn btn-success btn-sm waves-effect mb-2 waves-light openEditModal"> Edit </button>
                        <button type="button" class="btn btn-danger btn-sm waves-effect mb-2 waves-light"> Block </button>
                    </div>
                    <div class="text-left mt-3">
                        <h4 class="font-13 text-uppercase">Description :</h4>
                        <p class="text-muted font-13 mb-3">
                           {{$vendor->desc}}
                        </p>
                        <p class="text-muted mb-2 font-13"><strong>Latitude :</strong> <span class="ml-2">{{$vendor->latitude}}</span></p>

                        <p class="text-muted mb-2 font-13"><strong>Longitude :</strong><span class="ml-2">{{$vendor->longitude}}</span></p>

                        <p class="text-muted mb-1 font-13"><strong>Status :</strong> <span class="ml-2">
                            {{ ($vendor->status == 1) ? 'Active' : (($vendor->status == 2) ? 'Blocked' : 'Pending') }}
                        </span></p>
                    </div>

                    <ul class="social-list list-inline mt-3 mb-0">
                        <li class="list-inline-item">
                            <a href="javascript: void(0);" class="social-list-item border-primary text-primary"><i
                                    class="mdi mdi-facebook"></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a href="javascript: void(0);" class="social-list-item border-danger text-danger"><i
                                    class="mdi mdi-google"></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a href="javascript: void(0);" class="social-list-item border-info text-info"><i
                                    class="mdi mdi-twitter"></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a href="javascript: void(0);" class="social-list-item border-secondary text-secondary"><i
                                    class="mdi mdi-github"></i></a>
                        </li>
                    </ul>
                </div> <!-- end card-box -->

                <div class="card-box">
                    <h4 class="header-title mb-3">Inbox</h4>

                    <div class="inbox-widget" data-simplebar style="max-height: 350px;">
                        <div class="inbox-item">
                            <div class="inbox-item-img"><img src="{{asset('assets/images/users/user-2.jpg')}}" class="rounded-circle" alt=""></div>
                            <p class="inbox-item-author">Tomaslau</p>
                            <p class="inbox-item-text">I've finished it! See you so...</p>
                            <p class="inbox-item-date">
                                <a href="javascript:(0);" class="btn btn-sm btn-link text-info font-13"> Reply </a>
                            </p>
                        </div>
                        <div class="inbox-item">
                            <div class="inbox-item-img"><img src="{{asset('assets/images/users/user-3.jpg')}}" class="rounded-circle" alt=""></div>
                            <p class="inbox-item-author">Stillnotdavid</p>
                            <p class="inbox-item-text">This theme is awesome!</p>
                            <p class="inbox-item-date">
                                <a href="javascript:(0);" class="btn btn-sm btn-link text-info font-13"> Reply </a>
                            </p>
                        </div>
                        <div class="inbox-item">
                            <div class="inbox-item-img"><img src="{{asset('assets/images/users/user-4.jpg')}}" class="rounded-circle" alt=""></div>
                            <p class="inbox-item-author">Kurafire</p>
                            <p class="inbox-item-text">Nice to meet you</p>
                            <p class="inbox-item-date">
                                <a href="javascript:(0);" class="btn btn-sm btn-link text-info font-13"> Reply </a>
                            </p>
                        </div>

                        <div class="inbox-item">
                            <div class="inbox-item-img"><img src="{{asset('assets/images/users/user-5.jpg')}}" class="rounded-circle" alt=""></div>
                            <p class="inbox-item-author">Shahedk</p>
                            <p class="inbox-item-text">Hey! there I'm available...</p>
                            <p class="inbox-item-date">
                                <a href="javascript:(0);" class="btn btn-sm btn-link text-info font-13"> Reply </a>
                            </p>
                        </div>
                        <div class="inbox-item">
                            <div class="inbox-item-img"><img src="{{asset('assets/images/users/user-6.jpg')}}" class="rounded-circle" alt=""></div>
                            <p class="inbox-item-author">Adhamdannaway</p>
                            <p class="inbox-item-text">This theme is awesome!</p>
                            <p class="inbox-item-date">
                                <a href="javascript:(0);" class="btn btn-sm btn-link text-info font-13"> Reply </a>
                            </p>
                        </div>

                        <div class="inbox-item">
                            <div class="inbox-item-img"><img src="{{asset('assets/images/users/user-3.jpg')}}" class="rounded-circle" alt=""></div>
                            <p class="inbox-item-author">Stillnotdavid</p>
                            <p class="inbox-item-text">This theme is awesome!</p>
                            <p class="inbox-item-date">
                                <a href="javascript:(0);" class="btn btn-sm btn-link text-info font-13"> Reply </a>
                            </p>
                        </div>
                        <div class="inbox-item">
                            <div class="inbox-item-img"><img src="{{asset('assets/images/users/user-4.jpg')}}" class="rounded-circle" alt=""></div>
                            <p class="inbox-item-author">Kurafire</p>
                            <p class="inbox-item-text">Nice to meet you</p>
                            <p class="inbox-item-date">
                                <a href="javascript:(0);" class="btn btn-sm btn-link text-info font-13"> Reply </a>
                            </p>
                        </div>
                    </div> <!-- end inbox-widget -->

                </div> <!-- end card-box-->

            </div> <!-- end col-->

            <div class="col-lg-9 col-xl-9">
                <div class="">
                    <ul class="nav nav-pills navtab-bg nav-justified">
                        <li class="nav-item">
                            <a href="{{ route('vendor.show', $vendor->id) }}"  aria-expanded="false" class="nav-link {{($tab == 'configuration') ? 'active' : '' }}">
                                Configuration
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('vendor.categories', $vendor->id) }}"  aria-expanded="true" class="nav-link {{($tab == 'category') ? 'active' : '' }}">
                                Category
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('vendor.catalogs', $vendor->id) }}"  aria-expanded="false" class="nav-link {{($tab == 'catalog') ? 'active' : '' }}">
                                Catalog
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane {{($tab == 'configuration') ? 'active show' : '' }} " id="configuration">

                            <div class="row">

                                <div class="col-md-4">
                                    <form name="config-form" action="{{route('vendor.config.update', $vendor->id)}}" class="needs-validation" id="slot-configs" method="post">
                                        @csrf
                                        <div class="row mb-2 card-box">
                                            <h4 class="mb-4 text-uppercase"><i data-feather="tool"></i> Configuration</h4>
                                            <div class="col-md-12">
                                                <div class="form-group" id="order_pre_timeInput">
                                                    {!! Form::label('title', 'Order Prepare Time(In minutes)',['class' => 'control-label']) !!}
                                                    <input class="form-control" onkeypress="return isNumberKey(event)" name="order_pre_time" type="text" value="{{$vendor->order_pre_time}}">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group" id="auto_reject_timeInput">
                                                    {!! Form::label('title', 'Auto Reject Time(In minutes, 0 for no rejection)',['class' => 'control-label']) !!}
                                                    <input class="form-control" onkeypress="return isNumberKey(event)" name="auto_reject_time" type="text" value="{{$vendor->auto_reject_time}}">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group" id="order_min_amountInput">
                                                    {!! Form::label('title', 'Order Min Amount',['class' => 'control-label']) !!}
                                                    <input class="form-control" onkeypress="return isNumberKey(event)" name="order_min_amount" type="text" value="{{$vendor->order_min_amount}}">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <div class="form-group" id="commission_percentInput">
                                                    {!! Form::label('title', 'Commission Percent',['class' => 'control-label']) !!}
                                                    <input class="form-control" name="commission_percent" type="text" value="{{$vendor->commission_percent}}" onkeypress="return isNumberKey(event)">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group" id="commission_fixed_per_orderInput">
                                                    {!! Form::label('title', 'Commission Fixed Per Order',['class' => 'control-label']) !!} 
                                                    <input class="form-control" name="commission_fixed_per_order" type="text" value="{{$vendor->commission_fixed_per_order}}" onkeypress="return isNumberKey(event)">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group" id="commission_monthlyInput">
                                                    {!! Form::label('title', 'Commission Monthly',['class' => 'control-label']) !!}
                                                    <input class="form-control" onkeypress="return isNumberKey(event)" name="commission_monthly" type="text" value="{{$vendor->commission_monthly}}">
                                                </div>
                                            </div>

                                            <div class="col-md-12 text-right">
                                                <button class="btn btn-blue waves-effect waves-light">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-8">
                                    <div class="col-md-12">
                                        <div class="row card-box">
                                        
                                            <div class="col-md-4"> 
                                                <div class="table-responsive">
                                                    <table class="table table-centered table-nowrap table-striped" id="products-datatable">
                                                        <thead>
                                                            <tr>
                                                                <th>Name</th>
                                                                <th style="width: 85px;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($areas as $geo)
                                                            <tr>
                                                                <td class="table-user">
                                                                    <a href="javascript:void(0);" class="text-body font-weight-semibold">{{$geo->name}}</a>
                                                                </td>

                                                                <td>
                                                                    <button type="button" class="btn btn-primary-outline action-icon editAreaBtn" area_id="{{$geo->id}}"><h3><i class="mdi mdi-square-edit-outline"></i></h3></button> 
                                                                   
                                                                    <form action="{{route('vendor.serviceArea.delete', $vendor->id)}}" method="POST" class="action-icon">
                                                                        @csrf
                                                                        <input type="hidden" value="{{$geo->id}}" name="area_id">
                                                                        <button type="submit" onclick="return confirm('Are you sure? You want to delete the banner.')" class="btn btn-primary-outline action-icon"><h3><i class="mdi mdi-delete"></i></h3></button> 

                                                                    </form>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>

                                            <div class="col-md-8">
                                                <h4 class="mb-4 text-uppercase"><i data-feather="tool"></i> Service Area <span style=" float:right;"><button class="btn btn-info openServiceModal"> Add Service Area</button></span></h4>

                                                <div class="card-box p-0 m-0" style="height:600px;">
                                                    <div id="show_map-canvas"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row card-box">
                                <h4 class="mb-4 text-uppercase"><i class="mdi mdi-av-timer"></i> Weekly Slot</h4>
                                <div class="col-md-12">
                                    <div class="row mb-2">
                                        <div class="col-md-12">
                                            <div id='calendar'></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div> <!-- end tab-pane -->
                        <!-- end about me section content -->

                        <div class="tab-pane {{($tab == 'category') ? 'active show' : '' }}" id="category">

                        </div>
                        <!-- end timeline content-->

                        <div class="tab-pane {{($tab == 'catalog') ? 'active show' : '' }}" id="catalog">
                            
                        </div>
                    </div> <!-- end tab-content -->
                </div> <!-- end card-box-->

            </div> 
        </div>
    </div>
    <div class="row address" id="def" style="display: none;">
        <input type="text" id="def-address" name="test" class="autocomplete form-control def_address">
    </div>    

<div id="service-area-form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Service Area</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>

            <form id="geo_form" action="{{ route('vendor.serviceArea', $vendor->id) }}" method="POST">
                @csrf
                <div class="modal-body" id="editCardBox">
                    
                        <input type="hidden" name="latlongs" value="" id="latlongs" />
                        <input type="hidden" name="zoom_level" value="13" id="zoom_level" />
                        <div class="row">

                            <div class="col-lg-12 mb-2">
                                {!! Form::label('title', 'Area Name',['class' => 'control-label']) !!}
                                {!! Form::text('name', '',['class' => 'form-control',  'placeholder' => 'Area Name']) !!}
                                
                            </div>

                             <div class="col-lg-12 mb-2">
                                {!! Form::label('title', 'Area Description',['class' => 'control-label']) !!}
                                {!! Form::textarea('description', '',['class' => 'form-control', 'rows' => '3', 'placeholder' => 'Area Description']) !!}
                                
                            </div>

                            <div class="col-lg-12">
                                <div class="input-group mb-3">

                                  <input type="text" id="pac-input" class="form-control" placeholder="Search by name" aria-label="Recipient's username" aria-describedby="button-addon2" name="loc_name">
                                  <div class="input-group-append">
                                    <button class="btn btn-info" type="button" id="refresh">Edit Mode</button>
                                  </div>
                                  
                                </div>
                                <div class="" style="height:96%;">

                                    <div id="map-canvas" style="min-width: 300px; width:100%; height: 600px;"></div>
                                </div>
                            </div>
                        </div>
                        
                </div>

                <div class="modal-footer">
                    <div class="row mt-1">
                        <div class="col-md-6">
                            <button type="button"
                                class="btn btn-block btn-outline-blue waves-effect waves-light">Cancel</button>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-block btn-blue waves-effect waves-light">Save</button>
                        </div>
                    </div>
                </div>
                
            </form>
        </div>
    </div>
</div>

<form name="noPurpose" id="noPurpose"> @csrf </form>

@include('backend.vendor.profile-modals')
@endsection

@section('script')

@include('backend.vendor.pagescript')

<script src="{{asset('assets/libs/moment/moment.min.js')}}"></script>

<!-- Page js-->
<script src="{{asset('assets/js/calender_main.js')}}"></script>
<script src="{{ asset('assets/js/pages/jquery.cookie.js') }}"></script>

<script type="text/javascript">
    /* show geo service area */
    var areajson_json = {!! json_encode($all_coordinates) !!};


    function gm_authFailure() {

        $('.excetion_keys').append('<span><i class="mdi mdi-block-helper mr-2"></i> <strong>Google Map</strong> key is not valid</span><br/>');
        $('.displaySettingsError').show();
    }
    var map; // Global declaration of the map
    function initialize_show() {     

        var myLatlng = new google.maps.LatLng("{{ $center['lat'] }}","{{ $center['lng']  }}");
        
        var myOptions = {
            zoom: parseInt(11),
            center: myLatlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        map = new google.maps.Map(document.getElementById("show_map-canvas"), myOptions);   
        var length = areajson_json.length;

        //console.log(length);
        for (var i = 0; i < length; i++) {
            
            data = areajson_json[i];

            var infowindow = new google.maps.InfoWindow();
            var no_parking_geofences_json_geo_area = new google.maps.Polygon({
                paths: data.coordinates,
                strokeColor: '#FF0000',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: '#ff0000',
                fillOpacity: 0.35,
                geo_name: data.name,
                geo_pos: data.coordinates[i],

            });

            no_parking_geofences_json_geo_area.setMap(map);

        }
    }
    google.maps.event.addDomListener(window, 'load', initialize_show);
</script>



<script type="text/javascript">

    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)){

            return false;
        }
        return true;
    }

    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            headerToolbar: {
                left: 'prev,next',
                center: 'title',
                right: 'timeGridWeek,timeGridDay'
            },
            navLinks: true,
            selectable: true,
            selectMirror: true,
            height: 'auto',
            editable: false,
            nowIndicator: true,
            select: function(arg) {

                calendar.addEvent({
                    title: '',
                    start: arg.start,
                    end: arg.end,
                    allDay: arg.allDay
                  })
                $('#standard-modal').modal({
                    //backdrop: 'static',
                    keyboard: false
                });
                var day = arg.start.getDay() + 1;
                $('#day_' + day).prop('checked', true);

                if(arg.allDay == true){
                    document.getElementById('start_time').value = "00:00";
                    document.getElementById('end_time').value = "23:59";

                }else{
                    var startTime = ("0" + arg.start.getHours()).slice(-2) + ":" + ("0" + arg.start.getMinutes()).slice(-2);
                    var EndTime = ("0" + arg.end.getHours()).slice(-2) + ":" + ("0" + arg.end.getMinutes()).slice(-2);

                    document.getElementById('start_time').value = startTime;
                    document.getElementById('end_time').value = EndTime;

                }

                $('#slot_date').flatpickr({minDate: "today"});
            },
            events: {
                url: "{{route('vendor.calender.data', $vendor->id)}}"
            },
            eventResize: function(arg) {
                console.log(arg.event.extendedProps);

            },
             eventClick: function(ev) {

                $('#edit-slot-modal').modal({
                    //backdrop: 'static',
                    keyboard: false
                });
                var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']
                var day = ev.event.start.getDay();

                document.getElementById('edit_type').value = ev.event.extendedProps.type;
                document.getElementById('edit_day').value = day + 1;
                document.getElementById('edit_type_id').value = ev.event.extendedProps.type_id;

                /*      Delete Slot Form      */
                document.getElementById('deleteSlotDayid').value = ev.event.extendedProps.type_id;
                document.getElementById('deleteSlotId').value = ev.event.extendedProps.slot_id;
                document.getElementById('deleteSlotType').value = ev.event.extendedProps.type;

                $('#edit_slot_date').flatpickr({minDate: "today"});

                $('#edit-slot-modal #edit_slotlabel').text('Edit For All '+ days[day] + '   ');

                var startTime = ("0"+ev.event.start.getHours()).slice(-2) + ":" + ("0" + ev.event.start.getMinutes()).slice(-2);
                document.getElementById('edit_start_time').value = startTime;

                var EndTime = '';

                if(ev.event.end){
                    EndTime = ("0" + ev.event.end.getHours()).slice(-2) + ":" + ("0" + ev.event.end.getMinutes()).slice(-2);
                }
                document.getElementById('edit_end_time').value = EndTime;

            }
        });

        calendar.render();

    });
        
    $(document).on('change', '.slotTypeRadio', function(){
        var val = $(this).val();
        if(val == 'day'){
            $('.modal .weekDays').show();
            $('.modal .forDate').hide();
        }else if(val == 'date'){
            $('.modal .weekDays').hide();
            $('.modal .forDate').show();
        }
    });

    $(document).on('change', '#btn-save-slot', function(){
        var val = $(this).val();
        if(val == 'day'){
            $('.modal .weekDays').show();
            $('.modal .forDate').hide();
        }else if(val == 'date'){
            $('.modal .weekDays').hide();
            $('.modal .forDate').show();
        }
    });

    $(document).on('change', '.slotTypeEdit', function(){
        var val = $(this).val();
        if(val == 'day'){
            $('.modal .weekDaysEdit').show();
            $('.modal .forDateEdit').hide();
        }else if(val == 'date'){
            $('.modal .weekDaysEdit').hide();
            $('.modal .forDateEdit').show();
        }
    });

    $(document).on('click', '#deleteSlotBtn', function(){
        if(confirm("Are you sure? You want to delete this slot.")) {
            $('#deleteSlotForm').submit();
        }
        return false;
    });

    /*$(document).on('click', '#deleteAreaBtn', function(){
        if(confirm("Are you sure? You want to delete this slot.")) {
            $('#deleteAreaForm').submit();
        }
        return false;
    });*/


    $('.openServiceModal').click(function(){
        $('#service-area-form').modal({
            keyboard: false
        });
    });

    
        /*          SERVICE     AREA        */
    var map; // Global declaration of the map
    var iw = new google.maps.InfoWindow(); // Global declaration of the infowindow
    var lat_longs = new Array();
    var markers = new Array();
    var drawingManager;
    var no_parking_geofences_json = {!!  json_encode($all_coordinates) !!};
    var newlocation = '<?php echo json_encode($co_ordinates); ?>';
    var first_location = JSON.parse(newlocation);
    var lat = parseFloat(first_location.lat);
    var lng = parseFloat(first_location.lng);


    function deleteSelectedShape() {
        drawingManager.setMap(null);
    }
    // console.log(first_location);
    function initialize() {

        var myLatlng = new google.maps.LatLng(lat, lng);
        var myOptions = {
            zoom: 13,
            center: myLatlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        const input = document.getElementById("pac-input");
        const searchBox = new google.maps.places.SearchBox(input);
        //map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
        // Bias the SearchBox results towards current map's viewport.

        map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);


        drawingManager = new google.maps.drawing.DrawingManager({
            drawingMode: google.maps.drawing.OverlayType.POLYGON,
            drawingControl: true,
            drawingControlOptions: {
                position: google.maps.ControlPosition.TOP_CENTER,
                drawingModes: [google.maps.drawing.OverlayType.POLYGON]
            },
            
            polygonOptions: {

                editable: true,
                draggable: true,
                strokeColor: '#bb3733',
                fillColor: '#bb3733',
            }
        });

        drawingManager.setMap(map);

        google.maps.event.addListener(drawingManager, "overlaycomplete", function(event) {
            var newShape = event.overlay;
            newShape.type = event.type;

        });

        google.maps.event.addListener(drawingManager, "overlaycomplete", function(event) {
            overlayClickListener(event.overlay);
            var vertices_val = $('#latlongs').val();
            //var vertices_val = event.overlay.getPath().getArray();
            if (vertices_val == null || vertices_val === '') {
                $('#latlongs').val(event.overlay.getPath().getArray());
                console.log(map.getZoom());
                $('#zoom_level').val(map.getZoom());
            } else {
                alert('You can draw only one zone at a time');
                event.overlay.setMap(null);
            }
        });

        searchBox.addListener("places_changed", () => {
            const places = searchBox.getPlaces();

            if (places.length == 0) {
                return;
            }
            // Clear out the old markers.
            markers.forEach((marker) => {
                marker.setMap(null);
            });
            markers = [];
            // For each place, get the icon, name and location.
            const bounds = new google.maps.LatLngBounds();
            places.forEach((place) => {
                if (!place.geometry) {
                    console.log("Returned place contains no geometry");
                    return;
                }
                const icon = {
                    url: place.icon,
                    size: new google.maps.Size(71, 71),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(17, 34),
                    scaledSize: new google.maps.Size(25, 25),
                };
                // Create a marker for each place.
                markers.push(
                    new google.maps.Marker({
                        map,
                        icon,
                        title: place.name,
                        position: place.geometry.location,
                    })
                );

                if (place.geometry.viewport) {
                    // Only geocodes have viewport.
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
            });
            map.fitBounds(bounds);
        });
    }

    function overlayClickListener(overlay) {
        google.maps.event.addListener(overlay, "mouseup", function(event) {
            $('#latlongs').val(overlay.getPath().getArray());
        });
    }
    google.maps.event.addDomListener(window, 'load', initialize);

    google.maps.event.addDomListener(document.getElementById('refresh'), 'click', deleteSelectedShape);

    $(function() {
        $('#save').click(function() {
            //iterate polygon latlongs?
        });
    });

    $("#geo_form").on("submit", function(e) {
        var lat = $('#latlongs').val();
        var trainindIdArray = lat.replace("[", "").replace("]", "").split(',');
        var length = trainindIdArray.length;

        if (length < 6) {
            Swal.fire(
                'Select Location?',
                'Please Drow a Location On Map first',
                'question'
            )
            e.preventDefault();
        }
    });

    /*                  EDIT       AREA        MODAL           */
    var CSRF_TOKEN = $("input[name=_token]").val();
    $(document).on('click', '.editAreaBtn', function(){
        var aid = $(this).attr('area_id');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "post",
            dataType: "json",
            url: "{{route('vendor.serviceArea.edit', $vendor->id)}}",
            data: {
                _token: CSRF_TOKEN,
                data: aid
            },
            success: function(data) {

                document.getElementById("edit-area-form").action = "{{url('client/vendor/updateArea')}}" + '/' + aid;

                $('#edit-area-form #editAreaBox').html(data.html);

                initialize_edit(data.zoomLevel, data.coordinate);
                $('#edit-area-modal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
               // load_area_map(data.zoomLevel, data.coordinate);

               //google.maps.event.addDomListener(document.getElementById('refresh'), 'click', initialize_edit);

            //
            }
        });
    });

    google.maps.event.addDomListener(window, 'load', initialize_edit);

    var Editmap; // Global declaration of the map
    function initialize_edit(zoomLevel = 0, coordinates = '') {

        var zoomLevel = zoomLevel;
        var coordinate = coordinates;
        if(coordinate != ''){
            coordinate = coordinate.split('(');
            coordinate = coordinate.join('[');
            coordinate = coordinate.split(')');
            coordinate = coordinate.join(']');
            coordinate = "[" + coordinate;
            coordinate = coordinate + "]";
            coordinate = JSON.parse(coordinate);

            var triangleCoords = [];
            const lat1 = coordinate[0][0];
            const long1 = coordinate[0][1];

            var max_x = lat1;
            var min_x = lat1;
            var max_y = long1;
            var min_y = long1;

            $.each(coordinate, function(key, value) {

                if (value[0] > max_x) {
                    max_x = value[0];
                }
                if (value[0] < min_x) {
                    min_x = value[0];
                }
                if (value[1] > max_y) {
                    max_y = value[1];
                }
                if (value[1] < min_y) {
                    min_y = value[1];
                }

                triangleCoords.push(new google.maps.LatLng(value[0], value[1]));
            });

            var myLatlng = new google.maps.LatLng((min_x + ((max_x - min_x) / 2)), (min_y + ((max_y - min_y) / 2)));
            var myOptions = {
                zoom: parseInt(zoomLevel),
                center: myLatlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            }
            Editmap = new google.maps.Map(document.getElementById("edit_map-canvas"), myOptions);
            myPolygon = new google.maps.Polygon({
                paths: triangleCoords,
                draggable: true, // turn off if it gets annoying
                editable: true,
                strokeColor: '#bb3733',
                //strokeOpacity: 0.8,
                //strokeWeight: 2,
                fillColor: '#bb3733',
                //fillOpacity: 0.35
            });
            
            myPolygon.setMap(Editmap);

            google.maps.event.addListener(myPolygon, "mouseup", function(event) {
                
                document.getElementById("latlongs_edit").value = myPolygon.getPath().getArray();
            });
        }
        

    }
</script>

@endsection