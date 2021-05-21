@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Tax Category'])

@section('css')
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Tax</h4>
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
    <!-- end page title -->
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-8">
                            <h4 class="page-title">Tax Category</h4>
                        </div>
                        <div class="col-sm-4 text-right">
                            <button class="btn btn-info waves-effect waves-light text-sm-right addTaxCateModal"
                             userId="0"><i class="mdi mdi-plus-circle mr-1"></i> Add
                            </button>
                        </div>
                    </div>


                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-striped" id="banner-datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="post_list">
                                @foreach($taxCates as $cat)

                                <tr data-row-id="{{$cat->id}}">
                                    <td> {{ $cat->id }} </td>
                                    <td> {{ $cat->title }} </td>
                                    <td> {{ $cat->code }} </td>
                                    <td> {{ $cat->description }} </td>
                                    
                                    <td> 
                                        <div class="form-ul" style="width: 60px;">
                                            <div class="inner-div" style="float: left;">
                                                <a class="action-icon editTaxCateModal" userId="{{$cat->id}}" href="javascript:void(0);"><i class="mdi mdi-square-edit-outline"></i></a> 
                                            </div>
                                            <div class="inner-div">
                                                <form method="POST" action="{{ route('tax.destroy', $cat->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="form-group">
                                                       <button type="submit" onclick="return confirm('Are you sure? You want to delete the tax category.')" class="btn btn-primary-outline action-icon"><i class="mdi mdi-delete"></i></button> 

                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                               @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="pagination pagination-rounded justify-content-end mb-0"></div>
                </div>
            </div> 
        </div> 
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-8">
                            <h4 class="page-title">Tax Rate</h4>
                        </div>
                        <div class="col-sm-4 text-right">
                            <button class="btn btn-info waves-effect waves-light text-sm-right addTaxRateModal"
                             userId="0"><i class="mdi mdi-plus-circle mr-1"></i> Add
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-striped" id="Rate-datatable">
                            <thead>
                                <tr>
                                    <th>Identifier</th>
                                    <th>Tax Categories</th>
                                    <th>Postal Code(s)</th>
                                    <th>Tax Rate</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="post_list">
                                @foreach($taxRates as $rat)

                                <tr data-row-id="{{$cat->id}}">
                                    <td> {{ $rat->identifier }} </td>
                                    <td> 
                                        @foreach($rat->category as $cats)
                                            <span>{{$cats->title}}</span><br/>
                                        @endforeach
                                    </td>
                                    <td> 
                                        @if( $rat->is_zip == 1)
                                            {{ $rat->zip_code }}
                                        @elseif( $rat->is_zip == 2)
                                            {{ $rat->zip_from }} - {{ $rat->zip_to }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td> {{ $rat->tax_rate }} </td>
                                    <td> 
                                        <div class="form-ul" style="width: 60px;">
                                            <div class="inner-div" style="float: left;">
                                                <a class="action-icon editTaxRateModal" userId="{{$rat->id}}" href="javascript:void(0);"><i class="mdi mdi-square-edit-outline"></i></a> 
                                            </div>
                                            <div class="inner-div">
                                                <form method="POST" action="{{ route('taxRate.destroy', $rat->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="form-group">
                                                       <button type="submit" onclick="return confirm('Are you sure? You want to delete the tax rate.')" class="btn btn-primary-outline action-icon"><i class="mdi mdi-delete"></i></button> 
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                               @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination pagination-rounded justify-content-end mb-0">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Card Page Start From Here -->
    <div class="row card-box">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-centered table-nowrap table-striped" id="order_table">
                    <thead>
                        <tr>
                            <th colspan="6">
                                McDonalds
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="width:100px">
                                <div class="product-img">
                                    <img src="{{ asset('assets/images/burger.jpg') }}" alt="">
                                </div>
                            </td>
                            <td class="items-details text-left">
                                <h4>Ultimate Saving Bucket</h4>
                                <label><span>Size:</span> Regular</label>
                                <h6>Add Ons</h6>
                                <p>Spicy Dip</p>
                            </td>
                            <td>
                                <div class="items-price mb-3">$40.00</div>
                                <div class="extra-items-price">$5.00</div>
                            </td>
                            <td>
                                <input style="text-align:center;width:50px;margin:auto;" placeholder="1" type="text">
                            </td>
                            <td class="text-center">
                                <a href="#" class="action-icon d-block mb-3">
                                    <i class="mdi mdi-delete"></i>
                                </a>
                                <a href="#" class="action-icon d-block">
                                    <i class="mdi mdi-delete"></i>
                                </a>
                            </td>
                            <td class="text-right">
                                <div class="items-price mb-3">$40.00</div>
                                <div class="extra-items-price">$5.00</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:100px">
                                <div class="product-img">
                                    <img src="{{ asset('assets/images/burger.jpg') }}" alt="">
                                </div>
                            </td>
                            <td class="items-details text-left">
                                <h4>Ultimate Saving Bucket</h4>
                                <label><span>Size:</span> Regular</label>
                                <h6>Add Ons</h6>
                                <p>Spicy Dip</p>
                            </td>
                            <td>
                                <div class="items-price mb-3">$40.00</div>
                                <div class="extra-items-price">$5.00</div>
                            </td>
                            <td>
                                <input style="text-align:center;width:50px;margin:auto;" placeholder="1" type="text">
                            </td>
                            <td class="text-center">
                                <a href="#" class="action-icon d-block mb-3">
                                    <i class="mdi mdi-delete"></i>
                                </a>
                                <a href="#" class="action-icon d-block">
                                    <i class="mdi mdi-delete"></i>
                                </a>
                            </td>
                            <td class="text-right">
                            <div class="items-price mb-3">$40.00</div>
                                <div class="extra-items-price">$5.00</div>
                            </td>
                        </tr>
                        <tr>
                           <td colspan="2">
                               <div class="coupon_box d-flex align-items-center">
                                   <img src="{{ asset('assets/images/discount_icon.svg') }}" alt="">
                                   <input class="form-control" type="text">
                                   <button>Apply</button>
                               </div>
                           </td> 
                           <td>
                               <label class="d-block txt-13">Delivery Fee</label>
                               <p class="total_amt m-0">Amount</p>
                           </td>
                           <td colspan="3" class="text-right">
                               <label class="d-block  txt-13">$5.00</label>
                               <p class="total_amt m-0">$90.00</p>
                           </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row card-box">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-centered table-nowrap table-striped" id="order_table">
                    <thead>
                        <tr>
                            <th colspan="6">
                                McDonalds
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="width:100px">
                                <div class="product-img">
                                    <img src="{{ asset('assets/images/burger.jpg') }}" alt="">
                                </div>
                            </td>
                            <td class="items-details text-left">
                                <h4>Ultimate Saving Bucket</h4>
                                <label><span>Size:</span> Regular</label>
                                <h6>Add Ons</h6>
                                <p>Spicy Dip</p>
                            </td>
                            <td>
                                <div class="items-price mb-3">$40.00</div>
                                <div class="extra-items-price">$5.00</div>
                            </td>
                            <td>
                                <input style="text-align:center;width:50px;margin:auto;" placeholder="1" type="text">
                            </td>
                            <td class="text-center">
                                <a href="#" class="action-icon d-block mb-3">
                                    <i class="mdi mdi-delete"></i>
                                </a>
                                <a href="#" class="action-icon d-block">
                                    <i class="mdi mdi-delete"></i>
                                </a>
                            </td>
                            <td class="text-right">
                                <div class="items-price mb-3">$40.00</div>
                                <div class="extra-items-price">$5.00</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:100px">
                                <div class="product-img">
                                    <img src="{{ asset('assets/images/burger.jpg') }}" alt="">
                                </div>
                            </td>
                            <td class="items-details text-left">
                                <h4>Ultimate Saving Bucket</h4>
                                <label><span>Size:</span> Regular</label>
                                <h6>Add Ons</h6>
                                <p>Spicy Dip</p>
                            </td>
                            <td>
                                <div class="items-price mb-3">$40.00</div>
                                <div class="extra-items-price">$5.00</div>
                            </td>
                            <td>
                                <input style="text-align:center;width:50px;margin:auto;" placeholder="1" type="text">
                            </td>
                            <td class="text-center">
                                <a href="#" class="action-icon d-block mb-3">
                                    <i class="mdi mdi-delete"></i>
                                </a>
                                <a href="#" class="action-icon d-block">
                                    <i class="mdi mdi-delete"></i>
                                </a>
                            </td>
                            <td class="text-right">
                                <div class="items-price mb-3">$40.00</div>
                                <div class="extra-items-price">$5.00</div>
                            </td>
                        <tr>
                        <td colspan="2">
                            <div class="coupon_box d-flex align-items-center">
                                <img src="{{ asset('assets/images/discount_icon.svg') }}" alt="">
                                <input class="form-control" type="text">
                                <button>Apply</button>
                            </div>
                        </td> 
                        <td>
                            <label class="d-block txt-13">Delivery Fee</label>
                            <p class="total_amt m-0">Amount</p>
                        </td>
                        <td colspan="3" class="text-right">
                            <label class="d-block  txt-13">$5.00</label>
                            <p class="total_amt m-0">$90.00</p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        <td class="pr-0">
                           <p class="mb-1"></p> Sub Total  
                           <p class="mb-1"></p> Wallet 
                           <p class="mb-1"></p> Loyalty (500 pts) 
                           <hr class="my-2">
                           <p class="total_amt m-0">Total Amount</p>
                        </td>
                        <td class="text-right pl-0" colspan="3">
                           <p class="mb-1"></p> $40.00
                           <p class="mb-1"></p> -$60.00
                           <p class="mb-1"></p> -$10.00
                           <hr class="my-2">
                           <p class="total_amt m-0">$100.00</p>
                        </td>
                    </tr>
                    <tr class="border_0">
                        <td colspan="3"></td>
                        <td>Tax</td>
                        <td class="text-right" colspan="2">
                            <p class="m-0"><label class="m-0">CGST 7.5%</label><span class="pl-4">$10.00</span></p>
                            <p class="m-0"><label class="m-0">CGST 7.5%</label><span class="pl-4">$10.00</span></p>
                        </td>
                    </tr>
                    <tr class="border_0">
                        <td colspan="2"></td>
                        <td colspan="2" class="pt-0 pr-0">
                            <hr class="mt-0 mb-2">
                            <p class="total_amt m-0">Amount Payable</p>
                        </td>
                        <td colspan="2" class="pt-0 pl-0 text-right">
                            <hr class="mt-0 mb-2">
                            <p class="total_amt m-0">$100.00</p>
                        </td>
                    </tr>
                    
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-2">
            <h4 class="page-title">Delivery Address</h4>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="delivery_box">
                <label class="radio m-0">1663  Railroad Street, Marquette, Michigan, CA  PIN: 49855 
                    <input type="radio" checked="checked" name="is_company">
                    <span class="checkround"></span>
                </label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="delivery_box">
                <label class="radio m-0">Rosewood Court, Luverne, Marquette, Michigan, CA  PIN: 49855
                    <input type="radio" name="is_company">
                    <span class="checkround"></span>
                </label>
            </div>
        </div>
        <div class="col-12 mt-4">
            <a class="add-btn" href="#">Add New Address</a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-3 col-md-4">
            <a class="btn btn-info waves-effect waves-light w-100" href="#">Continue Shopping   </a>
        </div>
        <div class="offset-lg-6 offset-md-4 col-lg-3 col-md-4">
            <a class="btn btn-info waves-effect waves-light w-100" href="#">Check Out</a>
        </div>
    </div>
</div>

@include('backend.tax.modals')
@endsection

@section('script')

@include('backend.tax.pagescript')

@endsection