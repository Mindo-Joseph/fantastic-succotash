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

            <div class="row">
                <div class="col-md-6 col-xl">
                    <div class="widget-rounded-circle card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="text-end">
                                        <p class="text-muted mb-1 text-truncate">Total Revenue</p>
                                        <h3 class="text-dark mt-1">$<span data-plugin="counterup">{{$total_revenue}}</span></h3>
                                    </div>
                                </div>
                                <div class="col text-md-right">
                                    <div class="avatar-lg rounded-circle ml-auto">
                                        <i class="fe-heart font-22 avatar-title"></i>
                                    </div>
                                </div>
                            </div> <!-- end row-->
                        </div>
                    </div> <!-- end widget-rounded-circle-->
                </div> <!-- end col-->

                <div class="col-md-6 col-xl">
                    <div class="widget-rounded-circle card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <div class="text-end">
                                        <p class="text-muted mb-1 text-truncate">Today's Sales</p>
                                        <h3 class="text-dark mt-1">$<span data-plugin="counterup">{{$today_sales}}</span></h3>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="avatar-lg rounded-circle ml-auto">
                                        <i class="fe-shopping-cart font-22 avatar-title text-success"></i>
                                    </div>
                                </div>

                            </div> <!-- end row-->
                        </div>
                    </div> <!-- end widget-rounded-circle-->
                </div> <!-- end col-->

                <div class="col-md-6 col-xl">
                    <div class="widget-rounded-circle card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <div class="text-end">
                                        <p class="text-muted mb-1 text-truncate">Conversion</p>
                                        <h3 class="text-dark mt-1"><span data-plugin="counterup">0</span>%</h3>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="avatar-lg rounded-circle ml-auto">
                                        <i class="fe-bar-chart-line font-22 avatar-title"></i>
                                    </div>
                                </div>

                            </div> <!-- end row-->
                        </div>
                    </div> <!-- end widget-rounded-circle-->
                </div> <!-- end col-->

                <div class="col-md-6 col-xl">
                    <div class="widget-rounded-circle card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <div class="text-end">
                                        <p class="text-muted mb-1 text-truncate">Today's Visits</p>
                                        <h3 class="text-dark mt-1"><span data-plugin="counterup">0</span></h3>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="avatar-lg rounded-circle ml-auto">
                                        <i class="fe-eye font-22 avatar-title"></i>
                                    </div>
                                </div>

                            </div> <!-- end row-->
                        </div>
                    </div> <!-- end widget-rounded-circle-->
                </div> <!-- end col-->
                <div class="col-md-6 col-xl">
                    <div class="widget-rounded-circle card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <div class="text-end">
                                        <p class="text-muted mb-1 text-truncate">Today's Visits</p>
                                        <h3 class="text-dark mt-1"><span data-plugin="counterup">0</span></h3>
                                    </div>
                                </div>
                                <div class="col-6">
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
                    <div class="card mb-0 h-100">
                        <div class="card-body">
                            <div class="card-widgets">
                                <a href="javascript: void(0);" data-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                                <a data-bs-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false" aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                                <a href="javascript: void(0);" data-toggle="remove"><i class="mdi mdi-close"></i></a>
                            </div>
                            
                            <h4 class="header-title mb-3">Total Revenue</h4>

                            <div id="cardCollpase1" class="collapse pt-3 show widget-chart text-center" dir="ltr" style="position: relative;">

                                <div id="total-revenue" class="mt-0" data-colors="#f1556c" style="min-height: 220.7px;">
                                    <div id="apexchartsfwg700r2" class="apexcharts-canvas apexchartsfwg700r2 apexcharts-theme-light" style="width: 451px; height: 220.7px;">

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div> <!-- end card -->
                </div> <!-- end col-->

                <div class="col-lg-8 mb-3">
                    <div class="card mb-0 h-100">
                        <div class="card-body pb-2">
                            <div class="float-right d-none d-md-inline-block">
                                <div class="btn-group mb-2">
                                    <button type="button" class="btn btn-xs btn-light yearSales">Yearly</button>
                                    <button type="button" class="btn btn-xs btn-light weeklySales">Weekly</button>
                                    <button type="button" class="btn btn-xs btn-secondary monthlySales">Monthly</button>
                                </div>
                            </div>

                            <h4 class="header-title mb-3">Sales Analytics</h4>

                            <div dir="ltr" style="position: relative;">
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

                                        </tr>

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

<script>
    var monthlyInfo_url = "{{route('client.monthlySalesInfo')}}";
    var yearlyInfo_url = "{{route('client.yearlySalesInfo')}}";
    var weeklyInfo_url = "{{route('client.weeklySalesInfo')}}";
    var categoryInfo_url = "{{route('client.categoryInfo')}}";
</script>

<script src="{{asset('js/admin_dashboard.js')}}"></script>

<script src="{{asset('assets/libs/flatpickr/flatpickr.min.js')}}"></script>

<!-- Third Party js-->
<!-- <script src="{{asset('assets/js/vendor.min.js')}}"></script> -->

<!-- Plugins js-->
<script src="{{asset('assets/libs/admin-resources/admin-resources.min.js')}}"></script>

<!-- Page js-->
<!-- <script src="../assets/js/pages/dashboard-1.init.js"></script> -->
<!-- <script src="{{asset('assets/js/pages/ecommerce-dashboard.init.js')}}"></script> -->
<script src="{{asset('assets/libs/apexcharts/apexcharts.min.js')}}"></script>

@endsection