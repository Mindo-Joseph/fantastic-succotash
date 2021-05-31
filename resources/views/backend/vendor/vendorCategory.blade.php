@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Vendor'])

@section('css')
<link href="{{asset('assets/libs/nestable2/nestable2.min.css')}}" rel="stylesheet" type="text/css" />

<style type="text/css">
    .modal-lg {
        max-width: 70%;
    }
    span.inner-div{
        float: right;
        display: block;
        position: absolute;
        top: -5px;
        right: 16px;
    }
    .dd{
        max-width: 100%;
    }
    .fc-v-event{
        border-color: #43bee1;
        background-color: #43bee1;
    }
    .dd-list .dd3-content{
        position: relative;
    }
    span.inner-div {
        top: 50%;
        -webkit-transform: translateY(-50%);
        -moz-transform: translateY(-50%);
        transform: translateY(-50%);
    }
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
        <div class="row mb-1">
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
        <!--<div class="row mb-1">
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
        </div> -->

        <div class="row">
            <div class="col-lg-3 col-xl-3">
                @include('backend.vendor.show-md-3')
            </div>

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
                        <div class="tab-pane {{($tab == 'configuration') ? 'active show' : '' }} card-body" id="configuration">

                        </div> <!-- end tab-pane -->
                        <!-- end about me section content -->
                        <div class="tab-pane {{($tab == 'category') ? 'active show' : '' }}" id="category">
                            <div class="row">
                                
                                <div class="col-4">
                                    <div class="card-box" style="">
                                        <div class="row" style="max-height: 600px; overflow-x: auto">
                                            <div class="col-sm-6">
                                                <h4 class="mb-4"> Categories</h4>
                                            </div>
                                            <div class="col-sm-6 text-right">
                                                @if($vendor->add_category == 1)
                                                <button class="btn btn-info waves-effect waves-light text-sm-right openCategoryModal" dataid="0" is_vendor="1"><i class="mdi mdi-plus-circle mr-1"></i> Add
                                                </button>
                                                @endif
                                            </div> 
                                            <div class="col-md-12">
                                                <div class="row mb-2">
                                                    <div class="col-md-12">

                                                        <div class="custom-dd-empty dd" id="nestable_list_3">
                                                            <?php print_r($html); ?>
                                                        </div>
                                                    </div>
                                                    
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-8">
                                    <div class="row card-box" style="max-height: 600px; overflow-x: auto">
                                        <div class="col-sm-8">
                                            <h4 class="mb-4"> Addon Set</h4>
                                        </div>
                                        <div class="col-sm-4 text-right">
                                            <button class="btn btn-info waves-effect waves-light text-sm-right openAddonModal" dataid="0"><i class="mdi mdi-plus-circle mr-1"></i> Add 
                                            </button>
                                        </div> 
                                        <div class="col-md-12">
                                            <div class="row addon-row">
                                                <div class="col-md-12">
                                                    <form name="addon_order" id="addon_order" action="" method="post">
                                                        @csrf
                                                        <input type="hidden" name="orderData" id="orderVariantData" value="" />
                                                    </form>
                                                    <table class="table table-centered table-nowrap table-striped" id="varient-datatable">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Title</th>
                                                                <th>Select(Min - Max)</th>
                                                                <th>Options</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($addon_sets as $set)
                                                            <tr>
                                                                <td>{{$set->id}}</td>
                                                                <td>{{$set->title}}</td>
                                                                <td>{{$set->min_select}} - {{$set->max_select}}</td>
                                                                <td>
                                                                    @foreach($set->option as $opt)
                                                                        <span>{{$opt->title}} - ${{$opt->price}}</span><br/>
                                                                        <span></span>
                                                                    @endforeach
                                                                </td>
                                                                <td>
                                                                    <a class="action-icon editAddonBtn" dataid="{{$set->id}}" href="javascript:void(0);" > <h3> <i class="mdi mdi-square-edit-outline"></i> </h3></a>

                                                                    <a class="action-icon deleteAddon" dataid="{{$set->id}}" href="javascript:void(0);"> <i class="mdi mdi-delete"></i></a>
                                                                    <form action="{{route('addon.destroy', $set->id)}}" method="POST" style="display: none;" id="addonDeleteForm{{$set->id}}">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        
                                                                    </form>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        
                        <div class="tab-pane {{($tab == 'catalog') ? 'active show' : '' }}" id="catalog">
                            
                        </div>
                    </div> <!-- end tab-content -->
                </div> <!-- end card-box-->

            </div> 
        </div>
    </div>
<!--   Add On    modals   -->
<div id="addAddonmodal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create AddOn Set</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="addAddonForm" method="post" enctype="multipart/form-data" action="{{route('addon.store')}}">
                @csrf
                {!! Form::hidden('vendor_id', $vendor->id) !!}
                <div class="modal-body" id="AddAddonBox">
                    <div class="row">
                        <div class="col-md-12">

                            <div class="row rowYK">
                                <div class="col-md-12">
                                    <h5>Addon Title</h5>
                                </div>
                                <div class="col-md-12" style="overflow-x: auto;">
                                    <table class="table table-borderless mb-0" id="banner-datatable" >
                                        <tr>
                                            @foreach($languages as $langs)
                                                <th>{{$langs->language->name}}</th>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            @foreach($languages as $langs)
                                                <td>
                                                    {!! Form::hidden('language_id[]', $langs->language_id) !!}
                                                    <input type="text" name="title[]" value="" class="form-control" @if($langs->is_primary == 1) required @endif>
                                                </td>
                                            @endforeach
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="row rowYK mb-2">
                                <div class="col-md-12">
                                    <h5>Addon Options</h5>
                                </div>
                                <div class="col-md-12" style="overflow-x: auto;">
                                    <table class="table table-borderless mb-0 optionTableAdd" id="banner-datatable">
                                        <tr class="trForClone">
                                            <th>Price($)</th>
                                            @foreach($languages as $langs)
                                                <th>{{$langs->language->name}}</th>
                                            @endforeach
                                            <th></th>
                                        </tr>
                                        <tr>
                                            <td>{!! Form::text('price[]', null, ['class' => 'form-control', 'onkeypress' => 'return isNumberKey(event)', 'min' => '1', 'required' => 'required']) !!}</td>

                                            @foreach($languages as $k => $langs)
                                                <td><input type="text" name="opt_value[{{$k}}][]" class="form-control" @if($langs->is_primary == 1) required @endif>
                                                </td>
                                            @endforeach
                                            <td class="lasttd"></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-info waves-effect waves-light addOptionRow-Add">Add Option</button>
                                </div>
                            </div>

                            <div class="row">
                                <!-- <div class="col-12">
                                    <div class="form-group">
                                        <label for="formControlRange">Example Range input</label>
                                        <input type="range" class="form-control-range" id="formControlRange">
                                    </div>
                                </div> -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('title', 'Min Select',['class' => 'control-label']) !!}
                                        {!! Form::text('min_select', 1, ['class' => 'form-control', 'onkeypress' => 'return isNumberKey(event)']) !!}
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('title', 'Max Select',['class' => 'control-label']) !!}
                                        {!! Form::text('max_select', 1, ['class' => 'form-control', 'onkeypress' => 'return isNumberKey(event)']) !!}
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <p>If max select is greater than total option than max will be total option</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info waves-effect waves-light addAddonSubmit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="editdAddonmodal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create AddOn Set</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="editAddonForm" method="post" enctype="multipart/form-data" action="">
                @csrf
                @method('PUT')
                <div class="modal-body" id="editAddonBox">
                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info waves-effect waves-light editAddonSubmit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="add-category-form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Category</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="addCategoryForm" method="post" enctype="multipart/form-data">
                @csrf
                {!! Form::hidden('vendor_id', $vendor->id) !!}
                <div class="modal-body" id="AddCategoryBox">
                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info waves-effect waves-light addCategorySubmit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="edit-category-form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Category</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>

            <form id="editCategoryForm" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                {!! Form::hidden('vendor_id', $vendor->id) !!}
                <div class="modal-body" id="editCategoryBox">
                    
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-info waves-effect waves-light editCategorySubmit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('backend.vendor.modals')

@endsection

@section('script')
    <script src="{{asset('assets/libs/nestable2/nestable2.min.js')}}"></script>
    <script src="{{asset('assets/js/pages/my-nestable.init.js')}}"></script>
    <script src="{{asset('assets/js/jscolor.js')}}"></script>
    <script src="{{ asset('assets/js/jquery.tagsinput-revisited.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.tagsinput-revisited.css') }}" />

@include('backend.vendor.pagescript')
@include('backend.common.category-script')

<script type="text/javascript">
    var tagList = "";
    tagList = tagList.split(',');
    console.log(tagList);
    function makeTag(tagList = ''){
        $('.myTag1').tagsInput({
            'autocomplete': {
                source: tagList
            } 
        });
    }
</script>

@endsection