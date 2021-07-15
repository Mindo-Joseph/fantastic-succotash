@extends('layouts.vertical', ['title' => 'Dashboard'])
@section('css')
<link href="{{asset('assets/libs/admin-resources/admin-resources.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/flatpickr/flatpickr.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="container-fluid">
    <div class="content dashboard-boxes">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <div class="d-flex align-items-center mb-3">
                                <input type="text" id="range-datepicker" class="form-control flatpickr-input active" placeholder="2018-10-03 to 2018-10-10" readonly="">
                                <a href="javascript: void(0);" class="btn btn-blue ml-2">
                                    <i class="mdi mdi-autorenew"></i>
                                </a>
                                <a href="javascript: void(0);" class="btn btn-blue ml-2">
                                    <i class="mdi mdi-filter-variant"></i>
                                </a>
                            </div>
                        </div>
                        <h4 class="page-title">Dashboard</h4>
                    </div>
                </div>
            </div>
            <div class="row custom-cols">
                <div class="col col-md-4 col-lg-3 col-xl">
                    <div class="widget-rounded-circle card">
                        <div class="card-body p-2">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <div class="text-end">
                                        <p class="text-muted mb-1 text-truncate">Pending Orders</p>
                                        <h3 class="text-dark mt-1 mb-0"><span data-plugin="counterup" id="total_pending_order"></span></h3>
                                    </div>
                                </div>
                                <div class="col-4 text-md-right">
                                    <div class="avatar-lg rounded-circle ml-auto">
                                        <i class="fe-heart font-22 avatar-title"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="widget-rounded-circle card">
                        <div class="card-body p-2">
                            <div class="row align-items-center">
                            <div class="col-8">
                                    <div class="text-end">
                                        <p class="text-muted mb-1 text-truncate">Active Orders</p>
                                        <h3 class="text-dark mt-1 mb-0"><span data-plugin="counterup" id="total_active_order"></span></h3>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="avatar-lg rounded-circle ml-auto">
                                        <i class="fe-shopping-cart font-22 avatar-title"></i>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="widget-rounded-circle card">
                        <div class="card-body p-2">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <div class="text-end">
                                        <p class="text-muted mb-1 text-truncate">Delivered Orders</p>
                                        <h3 class="text-dark mt-1 mb-0"><span data-plugin="counterup" id="total_delivered_order"></span></h3>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="avatar-lg rounded-circle ml-auto">
                                        <i class="fe-bar-chart-line font-22 avatar-title"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="widget-rounded-circle card">
                        <div class="card-body p-2">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <div class="text-end">
                                        <p class="text-muted mb-1 text-truncate">Cancelled Orders</p>
                                        <h3 class="text-dark mt-1 mb-0"><span data-plugin="counterup" id="total_rejected_order"></span></h3>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="avatar-lg rounded-circle ml-auto">
                                        <i class="fe-eye font-22 avatar-title"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="widget-rounded-circle card">
                        <div class="card-body p-2">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <div class="text-end">
                                        <p class="text-muted mb-1 text-truncate">Vendor</p>
                                        <h3 class="text-dark mt-1 mb-0"><span data-plugin="counterup" id="total_vendor"></span></h3>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="avatar-lg rounded-circle ml-auto">
                                        <i class="fe-eye font-22 avatar-title"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="col">
                    <div class="widget-rounded-circle card">
                        <div class="card-body p-2">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <div class="text-end">
                                        <p class="text-muted mb-1 text-truncate">Categories</p>
                                        <h3 class="text-dark mt-1 mb-0"><span data-plugin="counterup" id="total_categories"></span></h3>
                                    </div>
                                </div>
                                <div class="col-4 text-md-right">
                                    <div class="avatar-lg rounded-circle ml-auto">
                                        <i class="fa fa-list-alt font-22 avatar-title" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="widget-rounded-circle card">
                        <div class="card-body p-2">
                            <div class="row align-items-center">
                            <div class="col-8">
                                    <div class="text-end">
                                        <p class="text-muted mb-1 text-truncate">Products</p>
                                        <h3 class="text-dark mt-1 mb-0"><span data-plugin="counterup" id="total_products"></span></h3>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="avatar-lg rounded-circle ml-auto">
                                        <i class="fe-shopping-cart font-22 avatar-title"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="widget-rounded-circle card">
                        <div class="card-body p-2">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <div class="text-end">
                                        <p class="text-muted mb-1 text-truncate">Banner Promotions</p>
                                        <h3 class="text-dark mt-1 mb-0"><span data-plugin="counterup" id="total_banners"></span></h3>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="avatar-lg rounded-circle ml-auto">
                                        <i class="fa fa-bullhorn  font-22 avatar-title" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="widget-rounded-circle card">
                        <div class="card-body p-2">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <div class="text-end">
                                        <p class="text-muted mb-1 text-truncate">Brands</p>
                                        <h3 class="text-dark mt-1"><span data-plugin="counterup" id="total_brands"></span></h3>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="avatar-lg rounded-circle ml-auto">
                                    <i class="fa fa-shopping-cart font-22 avatar-title" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="widget-rounded-circle card">
                        <div class="card-body p-2">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <div class="text-end">
                                        <p class="text-muted mb-1 text-truncate">Drive Active Orders</p>
                                        <h3 class="text-dark mt-1 mb-0"><span data-plugin="counterup">0</span></h3>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="avatar-lg rounded-circle ml-auto">
                                        <i class="fe-eye font-22 avatar-title"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 mb-3">
                    <div class="card mb-0">
                        <div class="card-body p-2">
                            <div class="card-widgets d-flex align-items-center select-date">
                                <div class="btn-group mb-0 mx-2">
                                    <button type="button" class="btn btn-xs btn-light yearSales">Yearly</button>
                                    <button type="button" class="btn btn-xs btn-light weeklySales">Weekly</button>
                                    <button type="button" class="btn btn-xs btn-secondary monthlySales">Monthly</button>
                                </div>
                                <a href="javascript: void(0);" data-toggle="reload"><i class="mdi mdi-refresh align-middle refresh_salesChart"></i></a>
                                <a class="align-middle" data-toggle="collapse" href="#cardCollpase2" role="button" aria-expanded="false" aria-controls="cardCollpase2"><i class="mdi mdi-minus"></i></a>
                            </div>
                            <h4 class="header-title mb-0">Sales Analytics</h4>
                            <div id="cardCollpase2" class="collapse show mt-3" dir="ltr" style="position: relative;">
                                <div id="sales-analytics" class="mt-4" data-colors="#1abc9c,#4a81d4" style="min-height: 393px;">
                                </div>
                                <div class="resize-triggers">
                                    <div class="expand-trigger">
                                        <div style="width: 974px; height: 394px;"></div>
                                    </div>
                                    <div class="contract-trigger"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 mb-3">
                    <div class="card mb-0">
                        <div class="card-body p-2">
                            <div class="card-widgets">
                                <a href="javascript: void(0);" data-toggle="reload"><i class="mdi mdi-refresh refresh_cataegoryinfo"></i></a>
                                <a data-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false" aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                            </div>
                            <h4 class="header-title mb-0">Orders (Top Categories)</h4>
                            <div id="cardCollpase1" class="collapse mt-3 show widget-chart" dir="ltr" style="position: relative;">
                                <div id="total-revenue" class="mt-0" data-colors="#f1556c" style="min-height: 220.7px;">
                                    <div id="apexchartsfwg700r2" class="apexcharts-canvas apexchartsfwg700r2 apexcharts-theme-light" style="width: 451px; height: 220.7px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
@section('script')
@endsection
    var categoryInfo_url = "{{route('client.categoryInfo')}}";
    var yearlyInfo_url = "{{route('client.yearlySalesInfo')}}";
    var weeklyInfo_url = "{{route('client.weeklySalesInfo')}}";
    var monthlyInfo_url = "{{route('client.monthlySalesInfo')}}";
    var dashboard_filter_url = "{{ route('client.dashboard.filter') }}";
</script>
<script src="{{asset('js/admin_dashboard.js')}}"></script>
<script src="{{asset('assets/libs/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{asset('assets/libs/apexcharts/apexcharts.min.js')}}"></script>
<script src="{{asset('assets/libs/admin-resources/admin-resources.min.js')}}"></script>
@endsection