@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Payment'])

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
                <h4 class="page-title">Payment</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-8">
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
                        <div class="col-sm-4 text-right">
                            <!-- <a class="btn btn-info waves-effect waves-light text-sm-right"
                                href="{{route('map.create')}}"><i class="mdi mdi-plus-circle mr-1"></i> Add
                            </a> -->
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-striped" id="products-datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Code</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for($i = 1; $i <11; $i++)
                                <tr>
                                    <td> 1 </td>
                                    <td> code{{$i}} </td>
                                    <td> second{{$i}} </td>
                                    <td> third{{$i}} </td>
                                    <td> 
                                        <a href="javascript:void" class="btn btn-info waves-effect waves-light text-sm-right applyVendor"> Apply </a>
                                    </td>
                                </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination pagination-rounded justify-content-end mb-0">
                        {{-- $banners->links() --}}
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
</div>

<div id="applyVendorModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Apply Payment Option</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="save_tax_category" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body card-box">
                    <div class="row mb-2">
                        {!! Form::label('title', 'All',['class' => 'control-label col-sm-4']) !!}
                        <div class="col-sm-8">
                            <input type="checkbox" id="all_select" data-plugin="switchery" name="all" class="chk_box all_select" data-color="#43bee1">
                        </div>
                    </div>
                    <div class="row vendorRow">
                        <div class="col-6 mb-2">
                            {!! Form::label('title', 'Select Vendor',['class' => 'control-label']) !!}
                            <select class="form-control select2-multiple" name="addon_sets[]" data-toggle="select2" multiple="multiple" placeholder="Select addon...">
                                <option value="all">Select</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info waves-effect waves-light submitAddTaxCate">Apply</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script type="text/javascript">
        $('.applyVendor').click(function(){
            $('#applyVendorModal').modal({
                backdrop: 'static',
                keyboard: false
            });
        });

        $('.all_select').change(function(){
            //var that = $(this);

            $('.vendorRow').toggle();
        });
    </script>
@endsection