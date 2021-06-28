@extends('layouts.vertical', ['title' => 'Ecommerce Dashboard'])

@section('css')
<!-- Plugins css -->
<link href="{{asset('assets/libs/admin-resources/admin-resources.min.css')}}" rel="stylesheet" type="text/css" />

<link href="{{asset('assets/libs/flatpickr/flatpickr.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />

@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">

    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <!-- <div class="page-title-right">
                            <form class="d-flex align-items-center mb-3">
                                <div class="input-group input-group-sm">
                                    <input type="hidden" class="form-control border flatpickr-input" id="dash-daterange" value="2021-06-22"><input class="form-control border form-control input" placeholder="" tabindex="0" type="text" readonly="readonly">
                                    <span class="input-group-text bg-blue border-blue text-white">
                                        <i class="mdi mdi-calendar-range"></i>
                                    </span>
                                </div>
                                <a href="javascript: void(0);" class="btn btn-blue ml-2">
                                    <i class="mdi mdi-autorenew"></i>
                                </a>
                                <a href="javascript: void(0);" class="btn btn-blue ml-2">
                                    <i class="mdi mdi-filter-variant"></i>
                                </a>
                            </form>
                        </div> -->
                        <h4 class="page-title">Dashboard</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-md-6 col-xl-3">
                    <div class="widget-rounded-circle card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="avatar-lg rounded-circle bg-soft-primary border-primary border">
                                        <i class="fe-heart font-22 avatar-title text-primary"></i>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-end">
                                        <h3 class="text-dark mt-1">$<span data-plugin="counterup">{{$total_revenue}}</span></h3>
                                        <p class="text-muted mb-1 text-truncate">Total Revenue</p>
                                    </div>
                                </div>
                            </div> <!-- end row-->
                        </div>
                    </div> <!-- end widget-rounded-circle-->
                </div> <!-- end col-->

                <div class="col-md-6 col-xl-3">
                    <div class="widget-rounded-circle card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="avatar-lg rounded-circle bg-soft-success border-success border">
                                        <i class="fe-shopping-cart font-22 avatar-title text-success"></i>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-end">
                                        <h3 class="text-dark mt-1"><span data-plugin="counterup">0</span></h3>
                                        <p class="text-muted mb-1 text-truncate">Today's Sales</p>
                                    </div>
                                </div>
                            </div> <!-- end row-->
                        </div>
                    </div> <!-- end widget-rounded-circle-->
                </div> <!-- end col-->

                <div class="col-md-6 col-xl-3">
                    <div class="widget-rounded-circle card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="avatar-lg rounded-circle bg-soft-info border-info border">
                                        <i class="fe-bar-chart-line- font-22 avatar-title text-info"></i>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-end">
                                        <h3 class="text-dark mt-1"><span data-plugin="counterup">0</span>%</h3>
                                        <p class="text-muted mb-1 text-truncate">Conversion</p>
                                    </div>
                                </div>
                            </div> <!-- end row-->
                        </div>
                    </div> <!-- end widget-rounded-circle-->
                </div> <!-- end col-->

                <div class="col-md-6 col-xl-3">
                    <div class="widget-rounded-circle card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="avatar-lg rounded-circle bg-soft-warning border-warning border">
                                        <i class="fe-eye font-22 avatar-title text-warning"></i>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-end">
                                        <h3 class="text-dark mt-1"><span data-plugin="counterup">0</span></h3>
                                        <p class="text-muted mb-1 text-truncate">Today's Visits</p>
                                    </div>
                                </div>
                            </div> <!-- end row-->
                        </div>
                    </div> <!-- end widget-rounded-circle-->
                </div> <!-- end col-->
            </div>
            <!-- end row-->

            <div class="row">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="dropdown float-right">
                                <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="mdi mdi-dots-vertical"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item">Sales Report</a>
                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item">Profit</a>
                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item">Action</a>
                                </div>
                            </div>

                            <h4 class="header-title mb-0">Total Revenue</h4>

                            <div class="widget-chart text-center" dir="ltr" style="position: relative;">

                                <div id="total-revenue" class="mt-0" data-colors="#f1556c" style="min-height: 220.7px;">
                                    <div id="apexchartsfwg700r2" class="apexcharts-canvas apexchartsfwg700r2 apexcharts-theme-light" style="width: 451px; height: 220.7px;"><svg id="SvgjsSvg1301" width="451" height="220.70000000000002" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;">
                                            <g id="SvgjsG1303" class="apexcharts-inner apexcharts-graphical" transform="translate(117.5, 0)">
                                                <defs id="SvgjsDefs1302">
                                                    <clipPath id="gridRectMaskfwg700r2">
                                                        <rect id="SvgjsRect1305" width="224" height="242" x="-3" y="-1" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect>
                                                    </clipPath>
                                                    <clipPath id="gridRectMarkerMaskfwg700r2">
                                                        <rect id="SvgjsRect1306" width="222" height="244" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect>
                                                    </clipPath>
                                                </defs>
                                                <g id="SvgjsG1307" class="apexcharts-radialbar">
                                                    <g id="SvgjsG1308">
                                                        <g id="SvgjsG1309" class="apexcharts-tracks">
                                                            <g id="SvgjsG1310" class="apexcharts-radialbar-track apexcharts-track" rel="1">
                                                                <path id="apexcharts-radialbarTrack-0" d="M 109 32.498170731707305 A 76.5018292682927 76.5018292682927 0 1 1 108.98664791202248 32.49817189689679" fill="none" fill-opacity="1" stroke="rgba(242,242,242,0.85)" stroke-opacity="1" stroke-linecap="butt" stroke-width="12.182963414634148" stroke-dasharray="0" class="apexcharts-radialbar-area" data:pathOrig="M 109 32.498170731707305 A 76.5018292682927 76.5018292682927 0 1 1 108.98664791202248 32.49817189689679"></path>
                                                            </g>
                                                        </g>
                                                        <g id="SvgjsG1312">
                                                            <g id="SvgjsG1317" class="apexcharts-series apexcharts-radial-series" seriesName="Revenue" rel="1" data:realIndex="0">
                                                                <path id="SvgjsPath1318" d="M 109 32.498170731707305 A 76.5018292682927 76.5018292682927 0 1 1 39.66579641159804 141.33107010534962" fill="none" fill-opacity="0.85" stroke="rgba(241,85,108,0)" stroke-opacity="1" stroke-linecap="butt" stroke-width="12.559756097560978" stroke-dasharray="0" class="apexcharts-radialbar-area apexcharts-radialbar-slice-0" data:angle="245" data:value="68" index="0" j="0" data:pathOrig="M 109 32.498170731707305 A 76.5018292682927 76.5018292682927 0 1 1 39.66579641159804 141.33107010534962"></path>
                                                            </g>
                                                            <circle id="SvgjsCircle1313" r="65.41034756097562" cx="109" cy="109" class="apexcharts-radialbar-hollow" fill="transparent"></circle>
                                                            <g id="SvgjsG1314" class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)" style="opacity: 1;"><text id="SvgjsText1315" font-family="Helvetica, Arial, sans-serif" x="109" y="109" text-anchor="middle" dominant-baseline="auto" font-size="16px" font-weight="400" fill="#f1556c" class="apexcharts-text apexcharts-datalabel-label" style="font-family: Helvetica, Arial, sans-serif;">Revenue</text><text id="SvgjsText1316" font-family="Helvetica, Arial, sans-serif" x="109" y="141" text-anchor="middle" dominant-baseline="auto" font-size="14px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-datalabel-value" style="font-family: Helvetica, Arial, sans-serif;">0%</text></g>
                                                        </g>
                                                    </g>
                                                </g>
                                                <line id="SvgjsLine1319" x1="0" y1="0" x2="218" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" class="apexcharts-ycrosshairs"></line>
                                                <line id="SvgjsLine1320" x1="0" y1="0" x2="218" y2="0" stroke-dasharray="0" stroke-width="0" class="apexcharts-ycrosshairs-hidden"></line>
                                            </g>
                                            <g id="SvgjsG1304" class="apexcharts-annotations"></g>
                                        </svg>
                                        <div class="apexcharts-legend"></div>
                                    </div>
                                </div>

                                <h5 class="text-muted mt-0">Total sales made today</h5>
                                <h2>$0</h2>

                                <p class="text-muted w-75 mx-auto sp-line-2">Traditional heading elements are designed to work best in the meat of your page content.</p>

                                <div class="row mt-3">
                                    <div class="col-4">
                                        <p class="text-muted font-15 mb-1 text-truncate">Target</p>
                                        <h4><i class="fe-arrow-down text-danger me-1"></i>$0</h4>
                                    </div>
                                    <div class="col-4">
                                        <p class="text-muted font-15 mb-1 text-truncate">Last week</p>
                                        <h4><i class="fe-arrow-up text-success me-1"></i>$0</h4>
                                    </div>
                                    <div class="col-4">
                                        <p class="text-muted font-15 mb-1 text-truncate">Last Month</p>
                                        <h4><i class="fe-arrow-down text-danger me-1"></i>$0</h4>
                                    </div>
                                </div>

                                <div class="resize-triggers">
                                    <div class="expand-trigger">
                                        <div style="width: 452px; height: 419px;"></div>
                                    </div>
                                    <div class="contract-trigger"></div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end card -->
                </div> <!-- end col-->

                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body pb-2">
                            <div class="float-right d-none d-md-inline-block">
                                <div class="btn-group mb-2">
                                    <button type="button" class="btn btn-xs btn-light">Today</button>
                                    <button type="button" class="btn btn-xs btn-light">Weekly</button>
                                    <button type="button" class="btn btn-xs btn-secondary">Monthly</button>
                                </div>
                            </div>

                            <h4 class="header-title mb-3">Sales Analytics</h4>

                            <div dir="ltr" style="position: relative;">
                                <div id="sales-analytics" class="mt-4" data-colors="#1abc9c,#4a81d4" style="min-height: 393px;">
                                    <div style="display:none" id="apexchartsvgr8h7xo" class="apexcharts-canvas apexchartsvgr8h7xo apexcharts-theme-light" style="width: 973px; height: 378px;"><svg id="SvgjsSvg1322" width="973" height="378" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" class="apexcharts-svg apexcharts-zoomable" xmlns:data="ApexChartsNS" transform="translate(0, 10)" style="background: transparent;">
                                            <foreignObject x="0" y="0" width="973" height="378">
                                                <div class="apexcharts-legend apexcharts-align-center position-bottom" xmlns="http://www.w3.org/1999/xhtml" style="inset: auto 0px -2px; position: absolute; max-height: 189px;">
                                                    <div class="apexcharts-legend-series" rel="1" seriesname="Revenue" data:collapsed="false" style="margin: 2px 5px;"><span class="apexcharts-legend-marker" rel="1" data:collapsed="false" style="background: rgb(26, 188, 156) !important; color: rgb(26, 188, 156); height: 12px; width: 12px; left: 0px; top: 0px; border-width: 0px; border-color: rgb(255, 255, 255); border-radius: 12px;"></span><span class="apexcharts-legend-text" rel="1" i="0" data:default-text="Revenue" data:collapsed="false" style="color: rgb(55, 61, 63); font-size: 12px; font-weight: 400; font-family: Helvetica, Arial, sans-serif;">Revenue</span></div>
                                                    <div class="apexcharts-legend-series" rel="2" seriesname="Sales" data:collapsed="false" style="margin: 2px 5px;"><span class="apexcharts-legend-marker" rel="2" data:collapsed="false" style="background: rgb(74, 129, 212) !important; color: rgb(74, 129, 212); height: 12px; width: 12px; left: 0px; top: 0px; border-width: 0px; border-color: rgb(255, 255, 255); border-radius: 12px;"></span><span class="apexcharts-legend-text" rel="2" i="1" data:default-text="Sales" data:collapsed="false" style="color: rgb(55, 61, 63); font-size: 12px; font-weight: 400; font-family: Helvetica, Arial, sans-serif;">Sales</span></div>
                                                </div>
                                                <style type="text/css">
                                                    .apexcharts-legend {
                                                        display: flex;
                                                        overflow: auto;
                                                        padding: 0 10px;
                                                    }

                                                    .apexcharts-legend.position-bottom,
                                                    .apexcharts-legend.position-top {
                                                        flex-wrap: wrap
                                                    }

                                                    .apexcharts-legend.position-right,
                                                    .apexcharts-legend.position-left {
                                                        flex-direction: column;
                                                        bottom: 0;
                                                    }

                                                    .apexcharts-legend.position-bottom.apexcharts-align-left,
                                                    .apexcharts-legend.position-top.apexcharts-align-left,
                                                    .apexcharts-legend.position-right,
                                                    .apexcharts-legend.position-left {
                                                        justify-content: flex-start;
                                                    }

                                                    .apexcharts-legend.position-bottom.apexcharts-align-center,
                                                    .apexcharts-legend.position-top.apexcharts-align-center {
                                                        justify-content: center;
                                                    }

                                                    .apexcharts-legend.position-bottom.apexcharts-align-right,
                                                    .apexcharts-legend.position-top.apexcharts-align-right {
                                                        justify-content: flex-end;
                                                    }

                                                    .apexcharts-legend-series {
                                                        cursor: pointer;
                                                        line-height: normal;
                                                    }

                                                    .apexcharts-legend.position-bottom .apexcharts-legend-series,
                                                    .apexcharts-legend.position-top .apexcharts-legend-series {
                                                        display: flex;
                                                        align-items: center;
                                                    }

                                                    .apexcharts-legend-text {
                                                        position: relative;
                                                        font-size: 14px;
                                                    }

                                                    .apexcharts-legend-text *,
                                                    .apexcharts-legend-marker * {
                                                        pointer-events: none;
                                                    }

                                                    .apexcharts-legend-marker {
                                                        position: relative;
                                                        display: inline-block;
                                                        cursor: pointer;
                                                        margin-right: 3px;
                                                        border-style: solid;
                                                    }

                                                    .apexcharts-legend.apexcharts-align-right .apexcharts-legend-series,
                                                    .apexcharts-legend.apexcharts-align-left .apexcharts-legend-series {
                                                        display: inline-block;
                                                    }

                                                    .apexcharts-legend-series.apexcharts-no-click {
                                                        cursor: auto;
                                                    }

                                                    .apexcharts-legend .apexcharts-hidden-zero-series,
                                                    .apexcharts-legend .apexcharts-hidden-null-series {
                                                        display: none !important;
                                                    }

                                                    .apexcharts-inactive-legend {
                                                        opacity: 0.45;
                                                    }
                                                </style>
                                            </foreignObject>
                                            <g id="SvgjsG1324" class="apexcharts-inner apexcharts-graphical" transform="translate(105.53787878787878, 30)">
                                                <defs id="SvgjsDefs1323">
                                                    <clipPath id="gridRectMaskvgr8h7xo">
                                                        <rect id="SvgjsRect1350" width="843.3906250000001" height="279.2" x="-33.84517045454545" y="-1.5" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect>
                                                    </clipPath>
                                                    <clipPath id="gridRectMarkerMaskvgr8h7xo">
                                                        <rect id="SvgjsRect1351" width="779.7002840909091" height="280.2" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect>
                                                    </clipPath>
                                                    <linearGradient id="SvgjsLinearGradient1355" x1="0" y1="1" x2="1" y2="1">
                                                        <stop id="SvgjsStop1356" stop-opacity="0.75" stop-color="rgba(83,205,181,0.75)" offset="0"></stop>
                                                        <stop id="SvgjsStop1357" stop-opacity="0.75" stop-color="rgba(26,188,156,0.75)" offset="0"></stop>
                                                        <stop id="SvgjsStop1358" stop-opacity="0.75" stop-color="rgba(26,188,156,0.75)" offset="0"></stop>
                                                    </linearGradient>
                                                    <linearGradient id="SvgjsLinearGradient1362" x1="0" y1="1" x2="1" y2="1">
                                                        <stop id="SvgjsStop1363" stop-opacity="0.75" stop-color="rgba(83,205,181,0.75)" offset="0"></stop>
                                                        <stop id="SvgjsStop1364" stop-opacity="0.75" stop-color="rgba(26,188,156,0.75)" offset="0"></stop>
                                                        <stop id="SvgjsStop1365" stop-opacity="0.75" stop-color="rgba(26,188,156,0.75)" offset="0"></stop>
                                                    </linearGradient>
                                                    <linearGradient id="SvgjsLinearGradient1369" x1="0" y1="1" x2="1" y2="1">
                                                        <stop id="SvgjsStop1370" stop-opacity="0.75" stop-color="rgba(83,205,181,0.75)" offset="0"></stop>
                                                        <stop id="SvgjsStop1371" stop-opacity="0.75" stop-color="rgba(26,188,156,0.75)" offset="0"></stop>
                                                        <stop id="SvgjsStop1372" stop-opacity="0.75" stop-color="rgba(26,188,156,0.75)" offset="0"></stop>
                                                    </linearGradient>
                                                    <linearGradient id="SvgjsLinearGradient1376" x1="0" y1="1" x2="1" y2="1">
                                                        <stop id="SvgjsStop1377" stop-opacity="0.75" stop-color="rgba(83,205,181,0.75)" offset="0"></stop>
                                                        <stop id="SvgjsStop1378" stop-opacity="0.75" stop-color="rgba(26,188,156,0.75)" offset="0"></stop>
                                                        <stop id="SvgjsStop1379" stop-opacity="0.75" stop-color="rgba(26,188,156,0.75)" offset="0"></stop>
                                                    </linearGradient>
                                                    <linearGradient id="SvgjsLinearGradient1383" x1="0" y1="1" x2="1" y2="1">
                                                        <stop id="SvgjsStop1384" stop-opacity="0.75" stop-color="rgba(83,205,181,0.75)" offset="0"></stop>
                                                        <stop id="SvgjsStop1385" stop-opacity="0.75" stop-color="rgba(26,188,156,0.75)" offset="0"></stop>
                                                        <stop id="SvgjsStop1386" stop-opacity="0.75" stop-color="rgba(26,188,156,0.75)" offset="0"></stop>
                                                    </linearGradient>
                                                    <linearGradient id="SvgjsLinearGradient1390" x1="0" y1="1" x2="1" y2="1">
                                                        <stop id="SvgjsStop1391" stop-opacity="0.75" stop-color="rgba(83,205,181,0.75)" offset="0"></stop>
                                                        <stop id="SvgjsStop1392" stop-opacity="0.75" stop-color="rgba(26,188,156,0.75)" offset="0"></stop>
                                                        <stop id="SvgjsStop1393" stop-opacity="0.75" stop-color="rgba(26,188,156,0.75)" offset="0"></stop>
                                                    </linearGradient>
                                                    <linearGradient id="SvgjsLinearGradient1397" x1="0" y1="1" x2="1" y2="1">
                                                        <stop id="SvgjsStop1398" stop-opacity="0.75" stop-color="rgba(83,205,181,0.75)" offset="0"></stop>
                                                        <stop id="SvgjsStop1399" stop-opacity="0.75" stop-color="rgba(26,188,156,0.75)" offset="0"></stop>
                                                        <stop id="SvgjsStop1400" stop-opacity="0.75" stop-color="rgba(26,188,156,0.75)" offset="0"></stop>
                                                    </linearGradient>
                                                    <linearGradient id="SvgjsLinearGradient1404" x1="0" y1="1" x2="1" y2="1">
                                                        <stop id="SvgjsStop1405" stop-opacity="0.75" stop-color="rgba(83,205,181,0.75)" offset="0"></stop>
                                                        <stop id="SvgjsStop1406" stop-opacity="0.75" stop-color="rgba(26,188,156,0.75)" offset="0"></stop>
                                                        <stop id="SvgjsStop1407" stop-opacity="0.75" stop-color="rgba(26,188,156,0.75)" offset="0"></stop>
                                                    </linearGradient>
                                                    <linearGradient id="SvgjsLinearGradient1411" x1="0" y1="1" x2="1" y2="1">
                                                        <stop id="SvgjsStop1412" stop-opacity="0.75" stop-color="rgba(83,205,181,0.75)" offset="0"></stop>
                                                        <stop id="SvgjsStop1413" stop-opacity="0.75" stop-color="rgba(26,188,156,0.75)" offset="0"></stop>
                                                        <stop id="SvgjsStop1414" stop-opacity="0.75" stop-color="rgba(26,188,156,0.75)" offset="0"></stop>
                                                    </linearGradient>
                                                    <linearGradient id="SvgjsLinearGradient1418" x1="0" y1="1" x2="1" y2="1">
                                                        <stop id="SvgjsStop1419" stop-opacity="0.75" stop-color="rgba(83,205,181,0.75)" offset="0"></stop>
                                                        <stop id="SvgjsStop1420" stop-opacity="0.75" stop-color="rgba(26,188,156,0.75)" offset="0"></stop>
                                                        <stop id="SvgjsStop1421" stop-opacity="0.75" stop-color="rgba(26,188,156,0.75)" offset="0"></stop>
                                                    </linearGradient>
                                                    <linearGradient id="SvgjsLinearGradient1425" x1="0" y1="1" x2="1" y2="1">
                                                        <stop id="SvgjsStop1426" stop-opacity="0.75" stop-color="rgba(83,205,181,0.75)" offset="0"></stop>
                                                        <stop id="SvgjsStop1427" stop-opacity="0.75" stop-color="rgba(26,188,156,0.75)" offset="0"></stop>
                                                        <stop id="SvgjsStop1428" stop-opacity="0.75" stop-color="rgba(26,188,156,0.75)" offset="0"></stop>
                                                    </linearGradient>
                                                    <linearGradient id="SvgjsLinearGradient1432" x1="0" y1="1" x2="1" y2="1">
                                                        <stop id="SvgjsStop1433" stop-opacity="0.75" stop-color="rgba(83,205,181,0.75)" offset="0"></stop>
                                                        <stop id="SvgjsStop1434" stop-opacity="0.75" stop-color="rgba(26,188,156,0.75)" offset="0"></stop>
                                                        <stop id="SvgjsStop1435" stop-opacity="0.75" stop-color="rgba(26,188,156,0.75)" offset="0"></stop>
                                                    </linearGradient>
                                                    <linearGradient id="SvgjsLinearGradient1478" x1="0" y1="1" x2="1" y2="1">
                                                        <stop id="SvgjsStop1479" stop-opacity="0.75" stop-color="rgba(119,161,223,0.75)" offset="0"></stop>
                                                        <stop id="SvgjsStop1480" stop-opacity="0.75" stop-color="rgba(74,129,212,0.75)" offset="0"></stop>
                                                        <stop id="SvgjsStop1481" stop-opacity="0.75" stop-color="rgba(74,129,212,0.75)" offset="0"></stop>
                                                    </linearGradient>
                                                </defs>
                                                <line id="SvgjsLine1333" x1="-1.5" y1="0" x2="-1.5" y2="276.2" stroke="#b6b6b6" stroke-dasharray="3" class="apexcharts-xcrosshairs" x="-1.5" y="0" width="1" height="276.2" fill="#b1b9c4" filter="none" fill-opacity="0.9" stroke-width="1"></line>
                                                <g id="SvgjsG1495" class="apexcharts-xaxis" transform="translate(0, 0)">
                                                    <g id="SvgjsG1496" class="apexcharts-xaxis-texts-g" transform="translate(0, -4)"><text id="SvgjsText1498" font-family="Helvetica, Arial, sans-serif" x="17.629551911157026" y="305.2" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="600" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan id="SvgjsTspan1499">Jan '01</tspan>
                                                            <title>Jan '01</title>
                                                        </text><text id="SvgjsText1501" font-family="Helvetica, Arial, sans-serif" x="88.14775955578513" y="305.2" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan id="SvgjsTspan1502">02 Jan</tspan>
                                                            <title>02 Jan</title>
                                                        </text><text id="SvgjsText1504" font-family="Helvetica, Arial, sans-serif" x="158.66596720041323" y="305.2" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan id="SvgjsTspan1505">03 Jan</tspan>
                                                            <title>03 Jan</title>
                                                        </text><text id="SvgjsText1507" font-family="Helvetica, Arial, sans-serif" x="229.18417484504135" y="305.2" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan id="SvgjsTspan1508">04 Jan</tspan>
                                                            <title>04 Jan</title>
                                                        </text><text id="SvgjsText1510" font-family="Helvetica, Arial, sans-serif" x="299.70238248966945" y="305.2" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan id="SvgjsTspan1511">05 Jan</tspan>
                                                            <title>05 Jan</title>
                                                        </text><text id="SvgjsText1513" font-family="Helvetica, Arial, sans-serif" x="370.22059013429754" y="305.2" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan id="SvgjsTspan1514">06 Jan</tspan>
                                                            <title>06 Jan</title>
                                                        </text><text id="SvgjsText1516" font-family="Helvetica, Arial, sans-serif" x="440.73879777892563" y="305.2" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan id="SvgjsTspan1517">07 Jan</tspan>
                                                            <title>07 Jan</title>
                                                        </text><text id="SvgjsText1519" font-family="Helvetica, Arial, sans-serif" x="511.2570054235537" y="305.2" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan id="SvgjsTspan1520">08 Jan</tspan>
                                                            <title>08 Jan</title>
                                                        </text><text id="SvgjsText1522" font-family="Helvetica, Arial, sans-serif" x="581.7752130681819" y="305.2" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan id="SvgjsTspan1523">09 Jan</tspan>
                                                            <title>09 Jan</title>
                                                        </text><text id="SvgjsText1525" font-family="Helvetica, Arial, sans-serif" x="652.29342071281" y="305.2" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan id="SvgjsTspan1526">10 Jan</tspan>
                                                            <title>10 Jan</title>
                                                        </text><text id="SvgjsText1528" font-family="Helvetica, Arial, sans-serif" x="722.811628357438" y="305.2" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan id="SvgjsTspan1529">11 Jan</tspan>
                                                            <title>11 Jan</title>
                                                        </text><text id="SvgjsText1531" font-family="Helvetica, Arial, sans-serif" x="793.3298360020661" y="305.2" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                            <tspan id="SvgjsTspan1532">12 Jan</tspan>
                                                            <title>12 Jan</title>
                                                        </text></g>
                                                    <line id="SvgjsLine1533" x1="-30.345170454545453" y1="277.2" x2="806.0454545454546" y2="277.2" stroke="#e0e0e0" stroke-dasharray="0" stroke-width="1"></line>
                                                </g>
                                                <g id="SvgjsG1564" class="apexcharts-grid">
                                                    <g id="SvgjsG1565" class="apexcharts-gridlines-horizontal">
                                                        <line id="SvgjsLine1578" x1="-30.345170454545453" y1="0" x2="806.0454545454546" y2="0" stroke="#e0e0e0" stroke-dasharray="0" class="apexcharts-gridline"></line>
                                                        <line id="SvgjsLine1579" x1="-30.345170454545453" y1="69.05" x2="806.0454545454546" y2="69.05" stroke="#e0e0e0" stroke-dasharray="0" class="apexcharts-gridline"></line>
                                                        <line id="SvgjsLine1580" x1="-30.345170454545453" y1="138.1" x2="806.0454545454546" y2="138.1" stroke="#e0e0e0" stroke-dasharray="0" class="apexcharts-gridline"></line>
                                                        <line id="SvgjsLine1581" x1="-30.345170454545453" y1="207.14999999999998" x2="806.0454545454546" y2="207.14999999999998" stroke="#e0e0e0" stroke-dasharray="0" class="apexcharts-gridline"></line>
                                                        <line id="SvgjsLine1582" x1="-30.345170454545453" y1="276.2" x2="806.0454545454546" y2="276.2" stroke="#e0e0e0" stroke-dasharray="0" class="apexcharts-gridline"></line>
                                                    </g>
                                                    <g id="SvgjsG1566" class="apexcharts-gridlines-vertical"></g>
                                                    <line id="SvgjsLine1567" x1="17.629551911157026" y1="277.2" x2="17.629551911157026" y2="283.2" stroke="#e0e0e0" stroke-dasharray="0" class="apexcharts-xaxis-tick"></line>
                                                    <line id="SvgjsLine1568" x1="88.14775955578513" y1="277.2" x2="88.14775955578513" y2="283.2" stroke="#e0e0e0" stroke-dasharray="0" class="apexcharts-xaxis-tick"></line>
                                                    <line id="SvgjsLine1569" x1="158.66596720041323" y1="277.2" x2="158.66596720041323" y2="283.2" stroke="#e0e0e0" stroke-dasharray="0" class="apexcharts-xaxis-tick"></line>
                                                    <line id="SvgjsLine1570" x1="229.18417484504135" y1="277.2" x2="229.18417484504135" y2="283.2" stroke="#e0e0e0" stroke-dasharray="0" class="apexcharts-xaxis-tick"></line>
                                                    <line id="SvgjsLine1571" x1="299.70238248966945" y1="277.2" x2="299.70238248966945" y2="283.2" stroke="#e0e0e0" stroke-dasharray="0" class="apexcharts-xaxis-tick"></line>
                                                    <line id="SvgjsLine1572" x1="370.22059013429754" y1="277.2" x2="370.22059013429754" y2="283.2" stroke="#e0e0e0" stroke-dasharray="0" class="apexcharts-xaxis-tick"></line>
                                                    <line id="SvgjsLine1573" x1="440.73879777892563" y1="277.2" x2="440.73879777892563" y2="283.2" stroke="#e0e0e0" stroke-dasharray="0" class="apexcharts-xaxis-tick"></line>
                                                    <line id="SvgjsLine1574" x1="511.2570054235537" y1="277.2" x2="511.2570054235537" y2="283.2" stroke="#e0e0e0" stroke-dasharray="0" class="apexcharts-xaxis-tick"></line>
                                                    <line id="SvgjsLine1575" x1="581.7752130681819" y1="277.2" x2="581.7752130681819" y2="283.2" stroke="#e0e0e0" stroke-dasharray="0" class="apexcharts-xaxis-tick"></line>
                                                    <line id="SvgjsLine1576" x1="652.29342071281" y1="277.2" x2="652.29342071281" y2="283.2" stroke="#e0e0e0" stroke-dasharray="0" class="apexcharts-xaxis-tick"></line>
                                                    <line id="SvgjsLine1577" x1="722.811628357438" y1="277.2" x2="722.811628357438" y2="283.2" stroke="#e0e0e0" stroke-dasharray="0" class="apexcharts-xaxis-tick"></line>
                                                    <line id="SvgjsLine1584" x1="0" y1="276.2" x2="775.7002840909091" y2="276.2" stroke="transparent" stroke-dasharray="0"></line>
                                                    <line id="SvgjsLine1583" x1="0" y1="1" x2="0" y2="276.2" stroke="transparent" stroke-dasharray="0"></line>
                                                </g>
                                                <g id="SvgjsG1352" class="apexcharts-bar-series apexcharts-plot-series">
                                                    <g id="SvgjsG1353" class="apexcharts-series" rel="1" seriesName="Revenue" data:realIndex="0">
                                                        <path id="SvgjsPath1359" d="M -17.629551911157023 276.2L -17.629551911157023 124.28999999999999Q -17.629551911157023 124.28999999999999 -17.629551911157023 124.28999999999999L 15.629551911157023 124.28999999999999Q 15.629551911157023 124.28999999999999 15.629551911157023 124.28999999999999L 15.629551911157023 124.28999999999999L 15.629551911157023 276.2L 15.629551911157023 276.2z" fill="url(#SvgjsLinearGradient1355)" fill-opacity="1" stroke="#1abc9c" stroke-opacity="1" stroke-linecap="butt" stroke-width="2" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskvgr8h7xo)" pathTo="M -17.629551911157023 276.2L -17.629551911157023 124.28999999999999Q -17.629551911157023 124.28999999999999 -17.629551911157023 124.28999999999999L 15.629551911157023 124.28999999999999Q 15.629551911157023 124.28999999999999 15.629551911157023 124.28999999999999L 15.629551911157023 124.28999999999999L 15.629551911157023 276.2L 15.629551911157023 276.2z" pathFrom="M -17.629551911157023 276.2L -17.629551911157023 276.2L 15.629551911157023 276.2L 15.629551911157023 276.2L 15.629551911157023 276.2L 15.629551911157023 276.2L 15.629551911157023 276.2L -17.629551911157023 276.2" cy="124.28999999999999" cx="16.629551911157023" j="0" val="440" barHeight="151.91" barWidth="35.259103822314046"></path>
                                                        <path id="SvgjsPath1366" d="M 52.88865573347107 276.2L 52.88865573347107 101.84875Q 52.88865573347107 101.84875 52.88865573347107 101.84875L 86.14775955578511 101.84875Q 86.14775955578511 101.84875 86.14775955578511 101.84875L 86.14775955578511 101.84875L 86.14775955578511 276.2L 86.14775955578511 276.2z" fill="url(#SvgjsLinearGradient1362)" fill-opacity="1" stroke="#1abc9c" stroke-opacity="1" stroke-linecap="butt" stroke-width="2" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskvgr8h7xo)" pathTo="M 52.88865573347107 276.2L 52.88865573347107 101.84875Q 52.88865573347107 101.84875 52.88865573347107 101.84875L 86.14775955578511 101.84875Q 86.14775955578511 101.84875 86.14775955578511 101.84875L 86.14775955578511 101.84875L 86.14775955578511 276.2L 86.14775955578511 276.2z" pathFrom="M 52.88865573347107 276.2L 52.88865573347107 276.2L 86.14775955578511 276.2L 86.14775955578511 276.2L 86.14775955578511 276.2L 86.14775955578511 276.2L 86.14775955578511 276.2L 52.88865573347107 276.2" cy="101.84875" cx="87.14775955578511" j="1" val="505" barHeight="174.35125" barWidth="35.259103822314046"></path>
                                                        <path id="SvgjsPath1373" d="M 123.40686337809916 276.2L 123.40686337809916 133.2665Q 123.40686337809916 133.2665 123.40686337809916 133.2665L 156.6659672004132 133.2665Q 156.6659672004132 133.2665 156.6659672004132 133.2665L 156.6659672004132 133.2665L 156.6659672004132 276.2L 156.6659672004132 276.2z" fill="url(#SvgjsLinearGradient1369)" fill-opacity="1" stroke="#1abc9c" stroke-opacity="1" stroke-linecap="butt" stroke-width="2" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskvgr8h7xo)" pathTo="M 123.40686337809916 276.2L 123.40686337809916 133.2665Q 123.40686337809916 133.2665 123.40686337809916 133.2665L 156.6659672004132 133.2665Q 156.6659672004132 133.2665 156.6659672004132 133.2665L 156.6659672004132 133.2665L 156.6659672004132 276.2L 156.6659672004132 276.2z" pathFrom="M 123.40686337809916 276.2L 123.40686337809916 276.2L 156.6659672004132 276.2L 156.6659672004132 276.2L 156.6659672004132 276.2L 156.6659672004132 276.2L 156.6659672004132 276.2L 123.40686337809916 276.2" cy="133.2665" cx="157.6659672004132" j="2" val="414" barHeight="142.93349999999998" barWidth="35.259103822314046"></path>
                                                        <path id="SvgjsPath1380" d="M 193.92507102272728 276.2L 193.92507102272728 44.53725Q 193.92507102272728 44.53725 193.92507102272728 44.53725L 227.18417484504133 44.53725Q 227.18417484504133 44.53725 227.18417484504133 44.53725L 227.18417484504133 44.53725L 227.18417484504133 276.2L 227.18417484504133 276.2z" fill="url(#SvgjsLinearGradient1376)" fill-opacity="1" stroke="#1abc9c" stroke-opacity="1" stroke-linecap="butt" stroke-width="2" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskvgr8h7xo)" pathTo="M 193.92507102272728 276.2L 193.92507102272728 44.53725Q 193.92507102272728 44.53725 193.92507102272728 44.53725L 227.18417484504133 44.53725Q 227.18417484504133 44.53725 227.18417484504133 44.53725L 227.18417484504133 44.53725L 227.18417484504133 276.2L 227.18417484504133 276.2z" pathFrom="M 193.92507102272728 276.2L 193.92507102272728 276.2L 227.18417484504133 276.2L 227.18417484504133 276.2L 227.18417484504133 276.2L 227.18417484504133 276.2L 227.18417484504133 276.2L 193.92507102272728 276.2" cy="44.53725" cx="228.18417484504133" j="3" val="671" barHeight="231.66275" barWidth="35.259103822314046"></path>
                                                        <path id="SvgjsPath1387" d="M 264.44327866735534 276.2L 264.44327866735534 197.82825Q 264.44327866735534 197.82825 264.44327866735534 197.82825L 297.7023824896694 197.82825Q 297.7023824896694 197.82825 297.7023824896694 197.82825L 297.7023824896694 197.82825L 297.7023824896694 276.2L 297.7023824896694 276.2z" fill="url(#SvgjsLinearGradient1383)" fill-opacity="1" stroke="#1abc9c" stroke-opacity="1" stroke-linecap="butt" stroke-width="2" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskvgr8h7xo)" pathTo="M 264.44327866735534 276.2L 264.44327866735534 197.82825Q 264.44327866735534 197.82825 264.44327866735534 197.82825L 297.7023824896694 197.82825Q 297.7023824896694 197.82825 297.7023824896694 197.82825L 297.7023824896694 197.82825L 297.7023824896694 276.2L 297.7023824896694 276.2z" pathFrom="M 264.44327866735534 276.2L 264.44327866735534 276.2L 297.7023824896694 276.2L 297.7023824896694 276.2L 297.7023824896694 276.2L 297.7023824896694 276.2L 297.7023824896694 276.2L 264.44327866735534 276.2" cy="197.82825" cx="298.7023824896694" j="4" val="227" barHeight="78.37174999999999" barWidth="35.259103822314046"></path>
                                                        <path id="SvgjsPath1394" d="M 334.9614863119835 276.2L 334.9614863119835 133.61175Q 334.9614863119835 133.61175 334.9614863119835 133.61175L 368.22059013429754 133.61175Q 368.22059013429754 133.61175 368.22059013429754 133.61175L 368.22059013429754 133.61175L 368.22059013429754 276.2L 368.22059013429754 276.2z" fill="url(#SvgjsLinearGradient1390)" fill-opacity="1" stroke="#1abc9c" stroke-opacity="1" stroke-linecap="butt" stroke-width="2" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskvgr8h7xo)" pathTo="M 334.9614863119835 276.2L 334.9614863119835 133.61175Q 334.9614863119835 133.61175 334.9614863119835 133.61175L 368.22059013429754 133.61175Q 368.22059013429754 133.61175 368.22059013429754 133.61175L 368.22059013429754 133.61175L 368.22059013429754 276.2L 368.22059013429754 276.2z" pathFrom="M 334.9614863119835 276.2L 334.9614863119835 276.2L 368.22059013429754 276.2L 368.22059013429754 276.2L 368.22059013429754 276.2L 368.22059013429754 276.2L 368.22059013429754 276.2L 334.9614863119835 276.2" cy="133.61175" cx="369.22059013429754" j="5" val="413" barHeight="142.58825" barWidth="35.259103822314046"></path>
                                                        <path id="SvgjsPath1401" d="M 405.4796939566116 276.2L 405.4796939566116 206.80475Q 405.4796939566116 206.80475 405.4796939566116 206.80475L 438.73879777892563 206.80475Q 438.73879777892563 206.80475 438.73879777892563 206.80475L 438.73879777892563 206.80475L 438.73879777892563 276.2L 438.73879777892563 276.2z" fill="url(#SvgjsLinearGradient1397)" fill-opacity="1" stroke="#1abc9c" stroke-opacity="1" stroke-linecap="butt" stroke-width="2" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskvgr8h7xo)" pathTo="M 405.4796939566116 276.2L 405.4796939566116 206.80475Q 405.4796939566116 206.80475 405.4796939566116 206.80475L 438.73879777892563 206.80475Q 438.73879777892563 206.80475 438.73879777892563 206.80475L 438.73879777892563 206.80475L 438.73879777892563 276.2L 438.73879777892563 276.2z" pathFrom="M 405.4796939566116 276.2L 405.4796939566116 276.2L 438.73879777892563 276.2L 438.73879777892563 276.2L 438.73879777892563 276.2L 438.73879777892563 276.2L 438.73879777892563 276.2L 405.4796939566116 276.2" cy="206.80475" cx="439.73879777892563" j="6" val="201" barHeight="69.39524999999999" barWidth="35.259103822314046"></path>
                                                        <path id="SvgjsPath1408" d="M 475.9979016012397 276.2L 475.9979016012397 154.672Q 475.9979016012397 154.672 475.9979016012397 154.672L 509.2570054235537 154.672Q 509.2570054235537 154.672 509.2570054235537 154.672L 509.2570054235537 154.672L 509.2570054235537 276.2L 509.2570054235537 276.2z" fill="url(#SvgjsLinearGradient1404)" fill-opacity="1" stroke="#1abc9c" stroke-opacity="1" stroke-linecap="butt" stroke-width="2" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskvgr8h7xo)" pathTo="M 475.9979016012397 276.2L 475.9979016012397 154.672Q 475.9979016012397 154.672 475.9979016012397 154.672L 509.2570054235537 154.672Q 509.2570054235537 154.672 509.2570054235537 154.672L 509.2570054235537 154.672L 509.2570054235537 276.2L 509.2570054235537 276.2z" pathFrom="M 475.9979016012397 276.2L 475.9979016012397 276.2L 509.2570054235537 276.2L 509.2570054235537 276.2L 509.2570054235537 276.2L 509.2570054235537 276.2L 509.2570054235537 276.2L 475.9979016012397 276.2" cy="154.672" cx="510.2570054235537" j="7" val="352" barHeight="121.52799999999999" barWidth="35.259103822314046"></path>
                                                        <path id="SvgjsPath1415" d="M 546.5161092458677 276.2L 546.5161092458677 16.572000000000003Q 546.5161092458677 16.572000000000003 546.5161092458677 16.572000000000003L 579.7752130681818 16.572000000000003Q 579.7752130681818 16.572000000000003 579.7752130681818 16.572000000000003L 579.7752130681818 16.572000000000003L 579.7752130681818 276.2L 579.7752130681818 276.2z" fill="url(#SvgjsLinearGradient1411)" fill-opacity="1" stroke="#1abc9c" stroke-opacity="1" stroke-linecap="butt" stroke-width="2" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskvgr8h7xo)" pathTo="M 546.5161092458677 276.2L 546.5161092458677 16.572000000000003Q 546.5161092458677 16.572000000000003 546.5161092458677 16.572000000000003L 579.7752130681818 16.572000000000003Q 579.7752130681818 16.572000000000003 579.7752130681818 16.572000000000003L 579.7752130681818 16.572000000000003L 579.7752130681818 276.2L 579.7752130681818 276.2z" pathFrom="M 546.5161092458677 276.2L 546.5161092458677 276.2L 579.7752130681818 276.2L 579.7752130681818 276.2L 579.7752130681818 276.2L 579.7752130681818 276.2L 579.7752130681818 276.2L 546.5161092458677 276.2" cy="16.572000000000003" cx="580.7752130681818" j="8" val="752" barHeight="259.628" barWidth="35.259103822314046"></path>
                                                        <path id="SvgjsPath1422" d="M 617.0343168904958 276.2L 617.0343168904958 165.72Q 617.0343168904958 165.72 617.0343168904958 165.72L 650.2934207128098 165.72Q 650.2934207128098 165.72 650.2934207128098 165.72L 650.2934207128098 165.72L 650.2934207128098 276.2L 650.2934207128098 276.2z" fill="url(#SvgjsLinearGradient1418)" fill-opacity="1" stroke="#1abc9c" stroke-opacity="1" stroke-linecap="butt" stroke-width="2" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskvgr8h7xo)" pathTo="M 617.0343168904958 276.2L 617.0343168904958 165.72Q 617.0343168904958 165.72 617.0343168904958 165.72L 650.2934207128098 165.72Q 650.2934207128098 165.72 650.2934207128098 165.72L 650.2934207128098 165.72L 650.2934207128098 276.2L 650.2934207128098 276.2z" pathFrom="M 617.0343168904958 276.2L 617.0343168904958 276.2L 650.2934207128098 276.2L 650.2934207128098 276.2L 650.2934207128098 276.2L 650.2934207128098 276.2L 650.2934207128098 276.2L 617.0343168904958 276.2" cy="165.72" cx="651.2934207128098" j="9" val="320" barHeight="110.47999999999999" barWidth="35.259103822314046"></path>
                                                        <path id="SvgjsPath1429" d="M 687.552524535124 276.2L 687.552524535124 187.47075Q 687.552524535124 187.47075 687.552524535124 187.47075L 720.811628357438 187.47075Q 720.811628357438 187.47075 720.811628357438 187.47075L 720.811628357438 187.47075L 720.811628357438 276.2L 720.811628357438 276.2z" fill="url(#SvgjsLinearGradient1425)" fill-opacity="1" stroke="#1abc9c" stroke-opacity="1" stroke-linecap="butt" stroke-width="2" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskvgr8h7xo)" pathTo="M 687.552524535124 276.2L 687.552524535124 187.47075Q 687.552524535124 187.47075 687.552524535124 187.47075L 720.811628357438 187.47075Q 720.811628357438 187.47075 720.811628357438 187.47075L 720.811628357438 187.47075L 720.811628357438 276.2L 720.811628357438 276.2z" pathFrom="M 687.552524535124 276.2L 687.552524535124 276.2L 720.811628357438 276.2L 720.811628357438 276.2L 720.811628357438 276.2L 720.811628357438 276.2L 720.811628357438 276.2L 687.552524535124 276.2" cy="187.47075" cx="721.811628357438" j="10" val="257" barHeight="88.72925" barWidth="35.259103822314046"></path>
                                                        <path id="SvgjsPath1436" d="M 758.0707321797521 276.2L 758.0707321797521 220.95999999999998Q 758.0707321797521 220.95999999999998 758.0707321797521 220.95999999999998L 791.3298360020661 220.95999999999998Q 791.3298360020661 220.95999999999998 791.3298360020661 220.95999999999998L 791.3298360020661 220.95999999999998L 791.3298360020661 276.2L 791.3298360020661 276.2z" fill="url(#SvgjsLinearGradient1432)" fill-opacity="1" stroke="#1abc9c" stroke-opacity="1" stroke-linecap="butt" stroke-width="2" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskvgr8h7xo)" pathTo="M 758.0707321797521 276.2L 758.0707321797521 220.95999999999998Q 758.0707321797521 220.95999999999998 758.0707321797521 220.95999999999998L 791.3298360020661 220.95999999999998Q 791.3298360020661 220.95999999999998 791.3298360020661 220.95999999999998L 791.3298360020661 220.95999999999998L 791.3298360020661 276.2L 791.3298360020661 276.2z" pathFrom="M 758.0707321797521 276.2L 758.0707321797521 276.2L 791.3298360020661 276.2L 791.3298360020661 276.2L 791.3298360020661 276.2L 791.3298360020661 276.2L 791.3298360020661 276.2L 758.0707321797521 276.2" cy="220.95999999999998" cx="792.3298360020661" j="11" val="160" barHeight="55.239999999999995" barWidth="35.259103822314046"></path>
                                                    </g>
                                                </g>
                                                <g id="SvgjsG1439" class="apexcharts-line-series apexcharts-plot-series">
                                                    <g id="SvgjsG1440" class="apexcharts-series" seriesName="Sales" data:longestSeries="true" rel="1" data:realIndex="1">
                                                        <path id="SvgjsPath1482" d="M 0 149.148L 70.51820764462809 44.19200000000001L 141.03641528925618 82.86000000000001L 211.5546229338843 127.05199999999999L 282.07283057851237 38.668000000000006L 352.5910382231405 154.672L 423.1092458677686 182.292L 493.6274535123967 104.95600000000002L 564.1456611570247 154.672L 634.6638688016528 154.672L 705.182076446281 209.91199999999998L 775.7002840909091 187.816" fill="none" fill-opacity="1" stroke="url(#SvgjsLinearGradient1478)" stroke-opacity="1" stroke-linecap="butt" stroke-width="3" stroke-dasharray="0" class="apexcharts-line" index="1" clip-path="url(#gridRectMaskvgr8h7xo)" pathTo="M 0 149.148L 70.51820764462809 44.19200000000001L 141.03641528925618 82.86000000000001L 211.5546229338843 127.05199999999999L 282.07283057851237 38.668000000000006L 352.5910382231405 154.672L 423.1092458677686 182.292L 493.6274535123967 104.95600000000002L 564.1456611570247 154.672L 634.6638688016528 154.672L 705.182076446281 209.91199999999998L 775.7002840909091 187.816" pathFrom="M -1 276.2L -1 276.2L 70.51820764462809 276.2L 141.03641528925618 276.2L 211.5546229338843 276.2L 282.07283057851237 276.2L 352.5910382231405 276.2L 423.1092458677686 276.2L 493.6274535123967 276.2L 564.1456611570247 276.2L 634.6638688016528 276.2L 705.182076446281 276.2L 775.7002840909091 276.2"></path>
                                                        <g id="SvgjsG1441" class="apexcharts-series-markers-wrap" data:realIndex="1">
                                                            <g class="apexcharts-series-markers">
                                                                <circle id="SvgjsCircle1590" r="0" cx="0" cy="149.148" class="apexcharts-marker w2rp3fvn0i" stroke="#ffffff" fill="#4a81d4" fill-opacity="1" stroke-width="2" stroke-opacity="0.9" default-marker-size="0"></circle>
                                                            </g>
                                                        </g>
                                                    </g>
                                                    <g id="SvgjsG1354" class="apexcharts-datalabels" data:realIndex="0">
                                                        <g id="SvgjsG1361" class="apexcharts-data-labels" transform="rotate(0)"></g>
                                                        <g id="SvgjsG1368" class="apexcharts-data-labels" transform="rotate(0)"></g>
                                                        <g id="SvgjsG1375" class="apexcharts-data-labels" transform="rotate(0)"></g>
                                                        <g id="SvgjsG1382" class="apexcharts-data-labels" transform="rotate(0)"></g>
                                                        <g id="SvgjsG1389" class="apexcharts-data-labels" transform="rotate(0)"></g>
                                                        <g id="SvgjsG1396" class="apexcharts-data-labels" transform="rotate(0)"></g>
                                                        <g id="SvgjsG1403" class="apexcharts-data-labels" transform="rotate(0)"></g>
                                                        <g id="SvgjsG1410" class="apexcharts-data-labels" transform="rotate(0)"></g>
                                                        <g id="SvgjsG1417" class="apexcharts-data-labels" transform="rotate(0)"></g>
                                                        <g id="SvgjsG1424" class="apexcharts-data-labels" transform="rotate(0)"></g>
                                                        <g id="SvgjsG1431" class="apexcharts-data-labels" transform="rotate(0)"></g>
                                                        <g id="SvgjsG1438" class="apexcharts-data-labels" transform="rotate(0)"></g>
                                                    </g>
                                                    <g id="SvgjsG1442" class="apexcharts-datalabels" data:realIndex="1">
                                                        <g id="SvgjsG1443" class="apexcharts-data-labels">
                                                            <rect id="SvgjsRect1483" width="21.6875" height="16.015625" x="-10.6875" y="139.140625" rx="2" ry="2" opacity="0.9" stroke="#ffffff" stroke-width="1" stroke-dasharray="0" fill="#4a81d4"></rect><text id="SvgjsText1445" font-family="Helvetica, Arial, sans-serif" x="0" y="151.148" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="600" fill="#fff" class="apexcharts-datalabel" cx="0" cy="151.148" style="font-family: Helvetica, Arial, sans-serif;">23</text>
                                                            <rect id="SvgjsRect1484" width="21.6875" height="16.015625" x="59.84375" y="34.1875" rx="2" ry="2" opacity="0.9" stroke="#ffffff" stroke-width="1" stroke-dasharray="0" fill="#4a81d4"></rect><text id="SvgjsText1447" font-family="Helvetica, Arial, sans-serif" x="70.51820764462809" y="46.19200000000001" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="600" fill="#fff" class="apexcharts-datalabel" cx="70.51820764462809" cy="46.19200000000001" style="font-family: Helvetica, Arial, sans-serif;">42</text>
                                                        </g>
                                                        <g id="SvgjsG1448" class="apexcharts-data-labels">
                                                            <rect id="SvgjsRect1485" width="21.6875" height="16.015625" x="130.359375" y="72.859375" rx="2" ry="2" opacity="0.9" stroke="#ffffff" stroke-width="1" stroke-dasharray="0" fill="#4a81d4"></rect><text id="SvgjsText1450" font-family="Helvetica, Arial, sans-serif" x="141.03641528925618" y="84.86000000000001" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="600" fill="#fff" class="apexcharts-datalabel" cx="141.03641528925618" cy="84.86000000000001" style="font-family: Helvetica, Arial, sans-serif;">35</text>
                                                        </g>
                                                        <g id="SvgjsG1451" class="apexcharts-data-labels">
                                                            <rect id="SvgjsRect1486" width="21.6875" height="16.015625" x="200.875" y="117.046875" rx="2" ry="2" opacity="0.9" stroke="#ffffff" stroke-width="1" stroke-dasharray="0" fill="#4a81d4"></rect><text id="SvgjsText1453" font-family="Helvetica, Arial, sans-serif" x="211.5546229338843" y="129.052" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="600" fill="#fff" class="apexcharts-datalabel" cx="211.5546229338843" cy="129.052" style="font-family: Helvetica, Arial, sans-serif;">27</text>
                                                        </g>
                                                        <g id="SvgjsG1454" class="apexcharts-data-labels">
                                                            <rect id="SvgjsRect1487" width="21.6875" height="16.015625" x="271.390625" y="28.65625" rx="2" ry="2" opacity="0.9" stroke="#ffffff" stroke-width="1" stroke-dasharray="0" fill="#4a81d4"></rect><text id="SvgjsText1456" font-family="Helvetica, Arial, sans-serif" x="282.07283057851237" y="40.668000000000006" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="600" fill="#fff" class="apexcharts-datalabel" cx="282.07283057851237" cy="40.668000000000006" style="font-family: Helvetica, Arial, sans-serif;">43</text>
                                                        </g>
                                                        <g id="SvgjsG1457" class="apexcharts-data-labels">
                                                            <rect id="SvgjsRect1488" width="21.6875" height="16.015625" x="341.90625" y="144.671875" rx="2" ry="2" opacity="0.9" stroke="#ffffff" stroke-width="1" stroke-dasharray="0" fill="#4a81d4"></rect><text id="SvgjsText1459" font-family="Helvetica, Arial, sans-serif" x="352.5910382231405" y="156.672" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="600" fill="#fff" class="apexcharts-datalabel" cx="352.5910382231405" cy="156.672" style="font-family: Helvetica, Arial, sans-serif;">22</text>
                                                        </g>
                                                        <g id="SvgjsG1460" class="apexcharts-data-labels">
                                                            <rect id="SvgjsRect1489" width="21.6875" height="16.015625" x="412.421875" y="172.28125" rx="2" ry="2" opacity="0.9" stroke="#ffffff" stroke-width="1" stroke-dasharray="0" fill="#4a81d4"></rect><text id="SvgjsText1462" font-family="Helvetica, Arial, sans-serif" x="423.1092458677686" y="184.292" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="600" fill="#fff" class="apexcharts-datalabel" cx="423.1092458677686" cy="184.292" style="font-family: Helvetica, Arial, sans-serif;">17</text>
                                                        </g>
                                                        <g id="SvgjsG1463" class="apexcharts-data-labels">
                                                            <rect id="SvgjsRect1490" width="21.6875" height="16.015625" x="482.953125" y="94.953125" rx="2" ry="2" opacity="0.9" stroke="#ffffff" stroke-width="1" stroke-dasharray="0" fill="#4a81d4"></rect><text id="SvgjsText1465" font-family="Helvetica, Arial, sans-serif" x="493.6274535123967" y="106.95600000000002" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="600" fill="#fff" class="apexcharts-datalabel" cx="493.6274535123967" cy="106.95600000000002" style="font-family: Helvetica, Arial, sans-serif;">31</text>
                                                        </g>
                                                        <g id="SvgjsG1466" class="apexcharts-data-labels">
                                                            <rect id="SvgjsRect1491" width="21.6875" height="16.015625" x="553.46875" y="144.671875" rx="2" ry="2" opacity="0.9" stroke="#ffffff" stroke-width="1" stroke-dasharray="0" fill="#4a81d4"></rect><text id="SvgjsText1468" font-family="Helvetica, Arial, sans-serif" x="564.1456611570247" y="156.672" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="600" fill="#fff" class="apexcharts-datalabel" cx="564.1456611570247" cy="156.672" style="font-family: Helvetica, Arial, sans-serif;">22</text>
                                                        </g>
                                                        <g id="SvgjsG1469" class="apexcharts-data-labels">
                                                            <rect id="SvgjsRect1492" width="21.6875" height="16.015625" x="623.984375" y="144.671875" rx="2" ry="2" opacity="0.9" stroke="#ffffff" stroke-width="1" stroke-dasharray="0" fill="#4a81d4"></rect><text id="SvgjsText1471" font-family="Helvetica, Arial, sans-serif" x="634.6638688016528" y="156.672" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="600" fill="#fff" class="apexcharts-datalabel" cx="634.6638688016528" cy="156.672" style="font-family: Helvetica, Arial, sans-serif;">22</text>
                                                        </g>
                                                        <g id="SvgjsG1472" class="apexcharts-data-labels">
                                                            <rect id="SvgjsRect1493" width="21.6875" height="16.015625" x="694.5" y="199.90625" rx="2" ry="2" opacity="0.9" stroke="#ffffff" stroke-width="1" stroke-dasharray="0" fill="#4a81d4"></rect><text id="SvgjsText1474" font-family="Helvetica, Arial, sans-serif" x="705.182076446281" y="211.91199999999998" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="600" fill="#fff" class="apexcharts-datalabel" cx="705.182076446281" cy="211.91199999999998" style="font-family: Helvetica, Arial, sans-serif;">12</text>
                                                        </g>
                                                        <g id="SvgjsG1475" class="apexcharts-data-labels">
                                                            <rect id="SvgjsRect1494" width="21.6875" height="16.015625" x="765.015625" y="177.8125" rx="2" ry="2" opacity="0.9" stroke="#ffffff" stroke-width="1" stroke-dasharray="0" fill="#4a81d4"></rect><text id="SvgjsText1477" font-family="Helvetica, Arial, sans-serif" x="775.7002840909091" y="189.816" text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="600" fill="#fff" class="apexcharts-datalabel" cx="775.7002840909091" cy="189.816" style="font-family: Helvetica, Arial, sans-serif;">16</text>
                                                        </g>
                                                    </g>
                                                </g>
                                                <line id="SvgjsLine1585" x1="-30.345170454545453" y1="0" x2="806.0454545454546" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" class="apexcharts-ycrosshairs"></line>
                                                <line id="SvgjsLine1586" x1="-30.345170454545453" y1="0" x2="806.0454545454546" y2="0" stroke-dasharray="0" stroke-width="0" class="apexcharts-ycrosshairs-hidden"></line>
                                                <g id="SvgjsG1587" class="apexcharts-yaxis-annotations"></g>
                                                <g id="SvgjsG1588" class="apexcharts-xaxis-annotations"></g>
                                                <g id="SvgjsG1589" class="apexcharts-point-annotations"></g>
                                                <rect id="SvgjsRect1591" width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe" class="apexcharts-zoom-rect"></rect>
                                                <rect id="SvgjsRect1592" width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe" class="apexcharts-selection-rect"></rect>
                                            </g>
                                            <rect id="SvgjsRect1332" width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe"></rect>
                                            <g id="SvgjsG1534" class="apexcharts-yaxis" rel="0" transform="translate(41.19270833333333, 0)">
                                                <g id="SvgjsG1535" class="apexcharts-yaxis-texts-g"><text id="SvgjsText1536" font-family="Helvetica, Arial, sans-serif" x="20" y="31.4" text-anchor="end" dominant-baseline="auto" font-size="11px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                        <tspan id="SvgjsTspan1537">800</tspan>
                                                    </text><text id="SvgjsText1538" font-family="Helvetica, Arial, sans-serif" x="20" y="100.45" text-anchor="end" dominant-baseline="auto" font-size="11px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                        <tspan id="SvgjsTspan1539">600</tspan>
                                                    </text><text id="SvgjsText1540" font-family="Helvetica, Arial, sans-serif" x="20" y="169.5" text-anchor="end" dominant-baseline="auto" font-size="11px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                        <tspan id="SvgjsTspan1541">400</tspan>
                                                    </text><text id="SvgjsText1542" font-family="Helvetica, Arial, sans-serif" x="20" y="238.54999999999998" text-anchor="end" dominant-baseline="auto" font-size="11px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                        <tspan id="SvgjsTspan1543">200</tspan>
                                                    </text><text id="SvgjsText1544" font-family="Helvetica, Arial, sans-serif" x="20" y="307.59999999999997" text-anchor="end" dominant-baseline="auto" font-size="11px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                        <tspan id="SvgjsTspan1545">0</tspan>
                                                    </text></g>
                                                <g id="SvgjsG1546" class="apexcharts-yaxis-title"><text id="SvgjsText1547" font-family="Helvetica, Arial, sans-serif" x="16.9140625" y="168.1" text-anchor="end" dominant-baseline="auto" font-size="11px" font-weight="900" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-title-text " style="font-family: Helvetica, Arial, sans-serif;" transform="rotate(-90 -15.1171875 164.6015625)">Net Revenue</text></g>
                                            </g>
                                            <g id="SvgjsG1548" class="apexcharts-yaxis" rel="1" transform="translate(938.5833333333334, 0)">
                                                <g id="SvgjsG1549" class="apexcharts-yaxis-texts-g"><text id="SvgjsText1550" font-family="Helvetica, Arial, sans-serif" x="-20" y="31.5" text-anchor="start" dominant-baseline="auto" font-size="11px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                        <tspan id="SvgjsTspan1551">50</tspan>
                                                    </text><text id="SvgjsText1552" font-family="Helvetica, Arial, sans-serif" x="-20" y="86.74" text-anchor="start" dominant-baseline="auto" font-size="11px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                        <tspan id="SvgjsTspan1553">40</tspan>
                                                    </text><text id="SvgjsText1554" font-family="Helvetica, Arial, sans-serif" x="-20" y="141.98" text-anchor="start" dominant-baseline="auto" font-size="11px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                        <tspan id="SvgjsTspan1555">30</tspan>
                                                    </text><text id="SvgjsText1556" font-family="Helvetica, Arial, sans-serif" x="-20" y="197.21999999999997" text-anchor="start" dominant-baseline="auto" font-size="11px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                        <tspan id="SvgjsTspan1557">20</tspan>
                                                    </text><text id="SvgjsText1558" font-family="Helvetica, Arial, sans-serif" x="-20" y="252.45999999999998" text-anchor="start" dominant-baseline="auto" font-size="11px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                        <tspan id="SvgjsTspan1559">10</tspan>
                                                    </text><text id="SvgjsText1560" font-family="Helvetica, Arial, sans-serif" x="-20" y="307.7" text-anchor="start" dominant-baseline="auto" font-size="11px" font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label " style="font-family: Helvetica, Arial, sans-serif;">
                                                        <tspan id="SvgjsTspan1561">0</tspan>
                                                    </text></g>
                                                <g id="SvgjsG1562" class="apexcharts-yaxis-title"><text id="SvgjsText1563" font-family="Helvetica, Arial, sans-serif" x="50.390625" y="168.1" text-anchor="end" dominant-baseline="auto" font-size="11px" font-weight="900" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-title-text " style="font-family: Helvetica, Arial, sans-serif;" transform="rotate(90 8.6171875 164.6015625)">Number of Sales</text></g>
                                            </g>
                                            <g id="SvgjsG1325" class="apexcharts-annotations"></g>
                                        </svg>
                                        <div class="apexcharts-tooltip apexcharts-theme-light" style="left: 110.538px; top: 47px;">
                                            <div class="apexcharts-tooltip-title" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">31 Dec</div>
                                            <div class="apexcharts-tooltip-series-group apexcharts-active" style="order: 1; display: flex;"><span class="apexcharts-tooltip-marker" style="background-color: rgb(26, 188, 156);"></span>
                                                <div class="apexcharts-tooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                    <div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-label">Revenue: </span><span class="apexcharts-tooltip-text-value">440</span></div>
                                                    <div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div>
                                                </div>
                                            </div>
                                            <div class="apexcharts-tooltip-series-group apexcharts-active" style="order: 2; display: flex;"><span class="apexcharts-tooltip-marker" style="background-color: rgb(74, 129, 212);"></span>
                                                <div class="apexcharts-tooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                    <div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-label">Sales: </span><span class="apexcharts-tooltip-text-value">23</span></div>
                                                    <div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="apexcharts-xaxistooltip apexcharts-xaxistooltip-bottom apexcharts-theme-light" style="left: 72.3582px; top: 308.2px;">
                                            <div class="apexcharts-xaxistooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px; min-width: 44.3594px;">31 Dec</div>
                                        </div>
                                        <div class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-light">
                                            <div class="apexcharts-yaxistooltip-text"></div>
                                        </div>
                                        <div class="apexcharts-yaxistooltip apexcharts-yaxistooltip-1 apexcharts-yaxistooltip-right apexcharts-theme-light">
                                            <div class="apexcharts-yaxistooltip-text"></div>
                                        </div>
                                        <div class="apexcharts-toolbar" style="top: 0px; right: 3px;">
                                            <div class="apexcharts-zoomin-icon" title="Zoom In"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                                    <path d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M13 7h-2v4H7v2h4v4h2v-4h4v-2h-4V7zm-1-5C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"></path>
                                                </svg>
                                            </div>
                                            <div class="apexcharts-zoomout-icon" title="Zoom Out"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                                    <path d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M7 11v2h10v-2H7zm5-9C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"></path>
                                                </svg>
                                            </div>
                                            <div class="apexcharts-zoom-icon apexcharts-selected" title="Selection Zoom"><svg xmlns="http://www.w3.org/2000/svg" fill="#000000" height="24" viewBox="0 0 24 24" width="24">
                                                    <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"></path>
                                                    <path d="M0 0h24v24H0V0z" fill="none"></path>
                                                    <path d="M12 10h-2v2H9v-2H7V9h2V7h1v2h2v1z"></path>
                                                </svg></div>
                                            <div class="apexcharts-pan-icon" title="Panning"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000" height="24" viewBox="0 0 24 24" width="24">
                                                    <defs>
                                                        <path d="M0 0h24v24H0z" id="a"></path>
                                                    </defs>
                                                    <clipPath id="b">
                                                        <use overflow="visible" xlink:href="#a"></use>
                                                    </clipPath>
                                                    <path clip-path="url(#b)" d="M23 5.5V20c0 2.2-1.8 4-4 4h-7.3c-1.08 0-2.1-.43-2.85-1.19L1 14.83s1.26-1.23 1.3-1.25c.22-.19.49-.29.79-.29.22 0 .42.06.6.16.04.01 4.31 2.46 4.31 2.46V4c0-.83.67-1.5 1.5-1.5S11 3.17 11 4v7h1V1.5c0-.83.67-1.5 1.5-1.5S15 .67 15 1.5V11h1V2.5c0-.83.67-1.5 1.5-1.5s1.5.67 1.5 1.5V11h1V5.5c0-.83.67-1.5 1.5-1.5s1.5.67 1.5 1.5z"></path>
                                                </svg></div>
                                            <div class="apexcharts-reset-icon" title="Reset Zoom"><svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"></path>
                                                    <path d="M0 0h24v24H0z" fill="none"></path>
                                                </svg></div>
                                            <div class="apexcharts-menu-icon" title="Menu"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                                    <path fill="none" d="M0 0h24v24H0V0z"></path>
                                                    <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"></path>
                                                </svg></div>
                                            <div class="apexcharts-menu">
                                                <div class="apexcharts-menu-item exportSVG" title="Download SVG">Download SVG</div>
                                                <div class="apexcharts-menu-item exportPNG" title="Download PNG">Download PNG</div>
                                                <div class="apexcharts-menu-item exportCSV" title="Download CSV">Download CSV</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="resize-triggers">
                                    <div class="expand-trigger">
                                        <div style="width: 974px; height: 394px;"></div>
                                    </div>
                                    <div class="contract-trigger"></div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end card -->
                </div> <!-- end col-->
            </div>
            <!-- end row -->

            <div class="row">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="dropdown float-right">
                                <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="mdi mdi-dots-vertical"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item">Edit Report</a>
                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item">Action</a>
                                </div>
                            </div>

                            <h4 class="header-title mb-3">Top 5 Users Balances</h4>

                            <div class="table-responsive">
                                <table class="table table-borderless table-hover table-nowrap table-centered m-0">

                                    <thead class="table-light">
                                        <tr>
                                            <th colspan="2">Profile</th>
                                            <th>Currency</th>
                                            <th>Balance</th>
                                            <th>Reserved in orders</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                            <td colspan="5" class="text-center">
                                                <h5 class="m-0 fw-normal">No data found</h5>
                                            </td>
                                        </tr>
                                        <!-- <tr>
                                            <td style="width: 36px;">
                                                <img src="../assets/images/users/user-2.jpg" alt="contact-img" title="contact-img" class="rounded-circle avatar-sm">
                                            </td>

                                            <td>
                                                <h5 class="m-0 fw-normal">Tomaslau</h5>
                                                <p class="mb-0 text-muted"><small>Member Since 2017</small></p>
                                            </td>

                                            <td>
                                                <i class="mdi mdi-currency-btc text-primary"></i> BTC
                                            </td>

                                            <td>
                                                0.00816117 BTC
                                            </td>

                                            <td>
                                                0.00097036 BTC
                                            </td>

                                            <td>
                                                <a href="javascript: void(0);" class="btn btn-xs btn-light"><i class="mdi mdi-plus"></i></a>
                                                <a href="javascript: void(0);" class="btn btn-xs btn-danger"><i class="mdi mdi-minus"></i></a>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="width: 36px;">
                                                <img src="../assets/images/users/user-3.jpg" alt="contact-img" title="contact-img" class="rounded-circle avatar-sm">
                                            </td>

                                            <td>
                                                <h5 class="m-0 fw-normal">Erwin E. Brown</h5>
                                                <p class="mb-0 text-muted"><small>Member Since 2017</small></p>
                                            </td>

                                            <td>
                                                <i class="mdi mdi-currency-eth text-primary"></i> ETH
                                            </td>

                                            <td>
                                                3.16117008 ETH
                                            </td>

                                            <td>
                                                1.70360009 ETH
                                            </td>

                                            <td>
                                                <a href="javascript: void(0);" class="btn btn-xs btn-light"><i class="mdi mdi-plus"></i></a>
                                                <a href="javascript: void(0);" class="btn btn-xs btn-danger"><i class="mdi mdi-minus"></i></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 36px;">
                                                <img src="../assets/images/users/user-4.jpg" alt="contact-img" title="contact-img" class="rounded-circle avatar-sm">
                                            </td>

                                            <td>
                                                <h5 class="m-0 fw-normal">Margeret V. Ligon</h5>
                                                <p class="mb-0 text-muted"><small>Member Since 2017</small></p>
                                            </td>

                                            <td>
                                                <i class="mdi mdi-currency-eur text-primary"></i> EUR
                                            </td>

                                            <td>
                                                25.08 EUR
                                            </td>

                                            <td>
                                                12.58 EUR
                                            </td>

                                            <td>
                                                <a href="javascript: void(0);" class="btn btn-xs btn-light"><i class="mdi mdi-plus"></i></a>
                                                <a href="javascript: void(0);" class="btn btn-xs btn-danger"><i class="mdi mdi-minus"></i></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 36px;">
                                                <img src="../assets/images/users/user-5.jpg" alt="contact-img" title="contact-img" class="rounded-circle avatar-sm">
                                            </td>

                                            <td>
                                                <h5 class="m-0 fw-normal">Jose D. Delacruz</h5>
                                                <p class="mb-0 text-muted"><small>Member Since 2017</small></p>
                                            </td>

                                            <td>
                                                <i class="mdi mdi-currency-cny text-primary"></i> CNY
                                            </td>

                                            <td>
                                                82.00 CNY
                                            </td>

                                            <td>
                                                30.83 CNY
                                            </td>

                                            <td>
                                                <a href="javascript: void(0);" class="btn btn-xs btn-light"><i class="mdi mdi-plus"></i></a>
                                                <a href="javascript: void(0);" class="btn btn-xs btn-danger"><i class="mdi mdi-minus"></i></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 36px;">
                                                <img src="../assets/images/users/user-6.jpg" alt="contact-img" title="contact-img" class="rounded-circle avatar-sm">
                                            </td>

                                            <td>
                                                <h5 class="m-0 fw-normal">Luke J. Sain</h5>
                                                <p class="mb-0 text-muted"><small>Member Since 2017</small></p>
                                            </td>

                                            <td>
                                                <i class="mdi mdi-currency-btc text-primary"></i> BTC
                                            </td>

                                            <td>
                                                2.00816117 BTC
                                            </td>

                                            <td>
                                                1.00097036 BTC
                                            </td>

                                            <td>
                                                <a href="javascript: void(0);" class="btn btn-xs btn-light"><i class="mdi mdi-plus"></i></a>
                                                <a href="javascript: void(0);" class="btn btn-xs btn-danger"><i class="mdi mdi-minus"></i></a>
                                            </td>
                                        </tr> -->

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> <!-- end col -->

                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="dropdown float-right">
                                <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="mdi mdi-dots-vertical"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item">Edit Report</a>
                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item">Action</a>
                                </div>
                            </div>

                            <h4 class="header-title mb-3">Revenue History</h4>

                            <div class="table-responsive">
                                <table class="table table-borderless table-nowrap table-hover table-centered m-0">

                                    <thead class="table-light">
                                        <tr>
                                            <th>Marketplaces</th>
                                            <th>Date</th>
                                            <th>Payouts</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                        <td colspan="5" class="text-center">
                                                <h5 class="m-0 fw-normal">No data found</h5>
                                            </td>
                                        </tr>
                                        <!-- <tr>
                                            <td>
                                                <h5 class="m-0 fw-normal">Themes Market</h5>
                                            </td>

                                            <td>
                                                Oct 15, 2018
                                            </td>

                                            <td>
                                                $5848.68
                                            </td>

                                            <td>
                                                <span class="badge bg-soft-warning text-warning">Upcoming</span>
                                            </td>

                                            <td>
                                                <a href="javascript: void(0);" class="btn btn-xs btn-light"><i class="mdi mdi-pencil"></i></a>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <h5 class="m-0 fw-normal">Freelance</h5>
                                            </td>

                                            <td>
                                                Oct 12, 2018
                                            </td>

                                            <td>
                                                $1247.25
                                            </td>

                                            <td>
                                                <span class="badge bg-soft-success text-success">Paid</span>
                                            </td>

                                            <td>
                                                <a href="javascript: void(0);" class="btn btn-xs btn-light"><i class="mdi mdi-pencil"></i></a>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <h5 class="m-0 fw-normal">Share Holding</h5>
                                            </td>

                                            <td>
                                                Oct 10, 2018
                                            </td>

                                            <td>
                                                $815.89
                                            </td>

                                            <td>
                                                <span class="badge bg-soft-success text-success">Paid</span>
                                            </td>

                                            <td>
                                                <a href="javascript: void(0);" class="btn btn-xs btn-light"><i class="mdi mdi-pencil"></i></a>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <h5 class="m-0 fw-normal">Envato's Affiliates</h5>
                                            </td>

                                            <td>
                                                Oct 03, 2018
                                            </td>

                                            <td>
                                                $248.75
                                            </td>

                                            <td>
                                                <span class="badge bg-soft-danger text-danger">Overdue</span>
                                            </td>

                                            <td>
                                                <a href="javascript: void(0);" class="btn btn-xs btn-light"><i class="mdi mdi-pencil"></i></a>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <h5 class="m-0 fw-normal">Marketing Revenue</h5>
                                            </td>

                                            <td>
                                                Sep 21, 2018
                                            </td>

                                            <td>
                                                $978.21
                                            </td>

                                            <td>
                                                <span class="badge bg-soft-warning text-warning">Upcoming</span>
                                            </td>

                                            <td>
                                                <a href="javascript: void(0);" class="btn btn-xs btn-light"><i class="mdi mdi-pencil"></i></a>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <h5 class="m-0 fw-normal">Advertise Revenue</h5>
                                            </td>

                                            <td>
                                                Sep 15, 2018
                                            </td>

                                            <td>
                                                $358.10
                                            </td>

                                            <td>
                                                <span class="badge bg-soft-success text-success">Paid</span>
                                            </td>

                                            <td>
                                                <a href="javascript: void(0);" class="btn btn-xs btn-light"><i class="mdi mdi-pencil"></i></a>
                                            </td>
                                        </tr> -->

                                    </tbody>
                                </table>
                            </div> <!-- end .table-responsive-->
                        </div>
                    </div> <!-- end card-->
                </div> <!-- end col -->
            </div>
            <!-- end row -->

        </div> <!-- container -->

    </div>

</div> <!-- container -->
@endsection

@section('script')



<script src="{{asset('assets/libs/flatpickr/flatpickr.min.js')}}"></script>

<!-- Third Party js-->
<!-- <script src="{{asset('assets/js/vendor.min.js')}}"></script> -->

<!-- Plugins js-->
<script src="{{asset('assets/libs/admin-resources/admin-resources.min.js')}}"></script>

<!-- Page js-->
<!-- <script src="../assets/js/pages/dashboard-1.init.js"></script> -->
<!-- <script src="{{asset('assets/js/pages/ecommerce-dashboard.init.js')}}"></script> -->
<!-- <script src="{{asset('assets/libs/apexcharts/apexcharts.min.js')}}"></script> -->

@endsection