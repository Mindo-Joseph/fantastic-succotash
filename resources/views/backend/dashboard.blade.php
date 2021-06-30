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

    <div class="content dashboard-boxes">

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

            <div class="row custom-cols">
                <div class="col col-md-4 col-lg-3 col-xl">
                    <div class="widget-rounded-circle card">
                        <div class="card-body p-2">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <div class="text-end">
                                        <p class="text-muted mb-1 text-truncate">Pending Orders</p>
                                        <h3 class="text-dark mt-1 mb-0"><span data-plugin="counterup">{{$total_pending_order}}</span></h3>
                                    </div>
                                </div>
                                <div class="col-4 text-md-right">
                                    <div class="avatar-lg rounded-circle ml-auto">
                                        <i class="fe-heart font-22 avatar-title"></i>
                                    </div>
                                </div>
                            </div> <!-- end row-->
                        </div>
                    </div> <!-- end widget-rounded-circle-->
                </div> <!-- end col-->

                <div class="col">
                    <div class="widget-rounded-circle card">
                        <div class="card-body p-2">
                            <div class="row align-items-center">
                            <div class="col-8">
                                    <div class="text-end">
                                        <p class="text-muted mb-1 text-truncate">Active Orders</p>
                                        <h3 class="text-dark mt-1 mb-0"><span data-plugin="counterup">{{$total_active_order}}</span></h3>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="avatar-lg rounded-circle ml-auto">
                                        <i class="fe-shopping-cart font-22 avatar-title"></i>

                                    </div>
                                </div>

                            </div> <!-- end row-->
                        </div>
                    </div> <!-- end widget-rounded-circle-->
                </div> <!-- end col-->

                <div class="col">
                    <div class="widget-rounded-circle card">
                        <div class="card-body p-2">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <div class="text-end">
                                        <p class="text-muted mb-1 text-truncate">Delivered Orders</p>
                                        <h3 class="text-dark mt-1 mb-0"><span data-plugin="counterup">{{$total_delivered_order}}</span></h3>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="avatar-lg rounded-circle ml-auto">
                                        <i class="fe-bar-chart-line font-22 avatar-title"></i>
                                    </div>
                                </div>

                            </div> <!-- end row-->
                        </div>
                    </div> <!-- end widget-rounded-circle-->
                </div> <!-- end col-->

                <div class="col">
                    <div class="widget-rounded-circle card">
                        <div class="card-body p-2">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <div class="text-end">
                                        <p class="text-muted mb-1 text-truncate">Cancelled Orders</p>
                                        <h3 class="text-dark mt-1 mb-0"><span data-plugin="counterup">{{$total_rejected_order}}</span></h3>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="avatar-lg rounded-circle ml-auto">
                                        <i class="fe-eye font-22 avatar-title"></i>
                                    </div>
                                </div>

                            </div> <!-- end row-->
                        </div>
                    </div> <!-- end widget-rounded-circle-->
                </div> <!-- end col-->
                <div class="col">
                    <div class="widget-rounded-circle card">
                        <div class="card-body p-2">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <div class="text-end">
                                        <p class="text-muted mb-1 text-truncate">Vendor</p>
                                        <h3 class="text-dark mt-1 mb-0"><span data-plugin="counterup">{{$total_vendor}}</span></h3>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="avatar-lg rounded-circle ml-auto">
                                        <i class="fe-eye font-22 avatar-title"></i>
                                    </div>
                                </div>
                               
                            </div> <!-- end row-->
                        </div>
                    </div> <!-- end widget-rounded-circle-->
                </div> <!-- end col-->
                <br>
                <div class="col">
                    <div class="widget-rounded-circle card">
                        <div class="card-body p-2">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <div class="text-end">
                                        <p class="text-muted mb-1 text-truncate">Categories</p>
                                        <h3 class="text-dark mt-1 mb-0"><span data-plugin="counterup">{{$total_categories}}</span></h3>
                                    </div>
                                </div>
                                <div class="col-4 text-md-right">
                                    <div class="avatar-lg rounded-circle ml-auto">
                                        <i class="fa fa-list-alt font-22 avatar-title" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div> <!-- end row-->
                        </div>
                    </div> <!-- end widget-rounded-circle-->
                </div> <!-- end col-->

                <div class="col">
                    <div class="widget-rounded-circle card">
                        <div class="card-body p-2">
                            <div class="row align-items-center">
                            <div class="col-8">
                                    <div class="text-end">
                                        <p class="text-muted mb-1 text-truncate">Products</p>
                                        <h3 class="text-dark mt-1 mb-0"><span data-plugin="counterup">{{$total_products}}</span></h3>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="avatar-lg rounded-circle ml-auto">
                                        <i class="fe-shopping-cart font-22 avatar-title"></i>
                                    </div>
                                </div>
                               
                            </div> <!-- end row-->
                        </div>
                    </div> <!-- end widget-rounded-circle-->
                </div> <!-- end col-->

                <div class="col">
                    <div class="widget-rounded-circle card">
                        <div class="card-body p-2">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <div class="text-end">
                                        <p class="text-muted mb-1 text-truncate">Banner Promotions</p>
                                        <h3 class="text-dark mt-1 mb-0"><span data-plugin="counterup">{{$total_banners}}</span></h3>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="avatar-lg rounded-circle ml-auto">
                                        <i class="fa fa-bullhorn  font-22 avatar-title" aria-hidden="true"></i>
                                    </div>
                                </div>
                                
                            </div> <!-- end row-->
                        </div>
                    </div> <!-- end widget-rounded-circle-->
                </div> <!-- end col-->

                <div class="col">
                    <div class="widget-rounded-circle card">
                        <div class="card-body p-2">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <div class="text-end">
                                        <p class="text-muted mb-1 text-truncate">Brands</p>
                                        <h3 class="text-dark mt-1"><span data-plugin="counterup">{{$total_brands}}</span></h3>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="avatar-lg rounded-circle ml-auto">
                                    <i class="fa fa-shopping-cart font-22 avatar-title" aria-hidden="true"></i>
                                    </div>
                                </div>
                               
                            </div> <!-- end row-->
                        </div>
                    </div> <!-- end widget-rounded-circle-->
                </div> <!-- end col-->
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

                            </div> <!-- end row-->
                        </div>
                    </div> <!-- end widget-rounded-circle-->
                </div> <!-- end col-->
            </div>
            <!-- end row-->

            <div class="row">
                <div class="col-lg-4 mb-3">
                    <div class="card mb-0">
                        <div class="card-body p-2">
                            <div class="card-widgets">
                                <a href="javascript: void(0);" data-toggle="reload"><i class="mdi mdi-refresh refresh_cataegoryinfo"></i></a>
                                <a data-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false" aria-controls="cardCollpase1"><i class="mdi mdi-plus"></i></a>
                            </div>
                            <h4 class="header-title mb-0">Orders (Top Categories)</h4>
                            <div id="cardCollpase1" class="collapse mt-3 show widget-chart" dir="ltr" style="position: relative;">

                                <div id="total-revenue" class="mt-0" data-colors="#f1556c" style="min-height: 220.7px;">
                                    <div id="apexchartsfwg700r2" class="apexcharts-canvas apexchartsfwg700r2 apexcharts-theme-light" style="width: 451px; height: 220.7px;">

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div> <!-- end card -->
                </div> <!-- end col-->

                <div class="col-lg-8 mb-3">
                    <div class="card mb-0">
                        <div class="card-body p-2">
                            <div class="card-widgets">
                                <div class="btn-group mb-0 mr-2">
                                    <button type="button" class="btn btn-xs btn-light yearSales">Yearly</button>
                                    <button type="button" class="btn btn-xs btn-light weeklySales">Weekly</button>
                                    <button type="button" class="btn btn-xs btn-secondary monthlySales">Monthly</button>
                                </div>
                                <a href="javascript: void(0);" data-toggle="reload"><i class="mdi mdi-refresh align-middle refresh_salesChart"></i></a>
                                <a class="align-middle" data-toggle="collapse" href="#cardCollpase2" role="button" aria-expanded="false" aria-controls="cardCollpase2"><i class="mdi mdi-plus"></i></a>

                            </div>
                           

                            <h4 class="header-title mb-0">Sales Analytics</h4>

                            <div id="cardCollpase2" class="collapse hide mt-3" dir="ltr" style="position: relative;">
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
                    </div> <!-- end card -->
                </div> <!-- end col-->
            </div>
            <!-- end row -->

            <div class="row">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body p-2">
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

                            <h4 class="header-title mb-3">Top 5 Users</h4>

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
                                                <h5 class="m-0 mt-3 fw-normal">No data found</h5>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> <!-- end col -->

                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body p-2">
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

                            <h4 class="header-title mb-3">Top 5 Vendors</h4>

                            <div class="table-responsive">
                                <table class="table table-borderless table-nowrap table-hover table-centered m-0">

                                    <thead class="table-light">
                                        <tr>
                                            <th>Name</th>
                                            <th>Last Order Date</th>
                                            <th>Payouts</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                <h5 class="m-0 mt-3 fw-normal">No data found</h5>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
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

<script>
    var monthlyInfo_url = "{{route('client.monthlySalesInfo')}}";
    var yearlyInfo_url = "{{route('client.yearlySalesInfo')}}";
    var weeklyInfo_url = "{{route('client.weeklySalesInfo')}}";
    var categoryInfo_url = "{{route('client.categoryInfo')}}";
</script>
<script src="{{asset('js/admin_dashboard.js')}}"></script>
<script src="{{asset('assets/libs/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{asset('assets/libs/admin-resources/admin-resources.min.js')}}"></script>
<script src="{{asset('assets/libs/apexcharts/apexcharts.min.js')}}"></script>

@endsection