@extends('layouts.vertical', ['demo' => 'Loyalty', 'title' => 'Loyalty'])

@section('css')
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                    </div>
                    <h4 class="page-title">Loyalty</h4>
                </div>
            </div>
        </div>     
        <div class="row">
            <div class="col-12">
                <div class="card widget-inline">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6 col-md-3 mb-3 mb-md-0">
                                <div class="p-2 text-center">
                                    <i class="mdi mdi-cart-plus text-primary mdi-24px"></i>
                                    <h3><span data-plugin="counterup">8954</span></h3>
                                    <p class="text-muted font-15 mb-0">Type of Loyalty applied</p>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-3 mb-3 mb-md-0">
                                <div class="p-2 text-center">
                                    <i class="mdi mdi-currency-usd text-success mdi-24px"></i>
                                    <h3>$ <span data-plugin="counterup">7841</span></h3>
                                    <p class="text-muted font-15 mb-0">Total Loyalty earned</p>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-3 mb-3 mb-md-0">
                                <div class="p-2 text-center">
                                    <i class="mdi mdi-account-group text-danger mdi-24px"></i>
                                    <h3><span data-plugin="counterup">6521</span></h3>
                                    <p class="text-muted font-15 mb-0">Total Loyalty spent</p>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-3 mb-3 mb-md-0">
                                <div class="p-2 text-center">
                                    <i class="mdi mdi-eye-outline text-blue mdi-24px"></i>
                                    <h3><span data-plugin="counterup">0</span> k</h3>
                                    <p class="text-muted font-15 mb-0">Unique orders</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
    </div> 
</div>
@endsection