@extends('layouts.vertical', ['title' => 'Category'])

@section('css')
    <link href="{{asset('assets/libs/nestable2/nestable2.min.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Catalog</h4>
            </div>
        </div>
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

    <div class="row catalog_box">
        <div class="col-xl-4 col-lg-6 mb-4">
            <div class="card-box h-100">
                <div class="row mb-2">
                    <div class="col-sm-8">
                        <h4 class="page-title">Category</h4>
                        <p class="sub-header">
                            Drag & drop Categories to make child parent relation
                        </p>
                    </div>
                    <div class="col-sm-4 text-right">
                        <button class="btn btn-info waves-effect waves-light text-sm-right openCategoryModal"
                         dataid="0" is_vendor="0"><i class="mdi mdi-plus-circle mr-1"></i> Add
                        </button>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12">                        
                        <form name="category_order" id="category_order" action="{{route('category.order')}}" method="post">
                            @csrf
                            <input type="hidden" name="orderDta" id="orderDta" value="" />
                        </form>
                        <div class="custom-dd-empty dd" id="nestable_list_3">
                            <?php print_r($html); ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 text-right btn_bottom">
                        <button class="btn btn-info waves-effect waves-light text-sm-right saveList">Save Order</button>
                    </div>
                </div> <!-- end row -->
            </div> <!-- end card-box -->
        </div> <!-- end col -->

        <div class="col-xl-4 col-lg-6 mb-4">
            <div class="card-box h-100">
                <div class="row mb-2">
                    <div class="col-sm-8">
                        <h4 class="page-title">Variant</h4>
                        <p class="sub-header">
                            Drag & drop Variant to change the position
                        </p>
                    </div>
                    <div class="col-sm-4 text-right">
                        <button class="btn btn-info waves-effect waves-light text-sm-right addVariantbtn"
                         dataid="0"><i class="mdi mdi-plus-circle mr-1"></i> Add
                        </button>
                    </div>

                    <div class="col-sm-8">
                        <h4 class="page-title"></h4>
                    </div>
                    
                </div>
                <div class="row variant-row">
                    <div class="col-md-12">
                        <form name="variant_order" id="variant_order" action="{{route('variant.order')}}" method="post">
                            @csrf
                            <input type="hidden" name="orderData" id="orderVariantData" value="" />
                        </form>
                        <div class="table-responsive">
                            <table class="table table-centered table-nowrap table-striped" id="varient-datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Options</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($variants as $key => $variant)
                                    <tr class="variantList" data-row-id="{{$variant->id}}">
                                        <td><span class="dragula-handle"></span></td>
                                        <td>{{$variant->title}}</td>
                                        <td>{{isset($variant->varcategory->cate->primary->name) ? $variant->varcategory->cate->primary->name : ''}}</td>
                                        <td>
                                            @foreach($variant->option as $key => $value)
                                            <label style="margin-bottom: 3px;">
                                                @if($variant->type == 2)
                                                <span style="padding:8px; float: left; border: 1px dotted #ccc; background:{{$value->hexacode}};"> </span>
                                                @endif
                                                &nbsp;&nbsp; {{$value->title}}</label> <br/>
                                            @endforeach
                                        </td>
                                        <td>
                                            <a class="action-icon editVariantBtn" dataid="{{$variant->id}}" href="javascript:void(0);" >  <i class="mdi mdi-square-edit-outline"></i> </a>

                                            <a class="action-icon deleteVariant" dataid="{{$variant->id}}" href="javascript:void(0);">  <i class="mdi mdi-delete"></i></a>
                                            <form action="{{route('variant.destroy', $variant->id)}}" method="POST"  style="display: none;" id="varDeleteForm{{$variant->id}}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-icon btn btn-primary-outline" dataid="{{$variant->id}}" onclick="return confirm('Are you sure? You want to delete the variant.')" > <i class="mdi mdi-delete"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-12 text-right btn_bottom">
                        <button class="btn btn-info waves-effect waves-light text-sm-right saveVariantOrder">Save Order</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-6 mb-4">
            <div class="card-box h-100">
                <div class="row mb-2">
                    <div class="col-sm-8">
                        <h4 class="page-title">Brand</h4>
                        <p class="sub-header">
                            
                        </p>
                    </div>
                    <div class="col-sm-4 text-right">
                        <button class="btn btn-info waves-effect waves-light text-sm-right addBrandbtn"
                         dataid="0"><i class="mdi mdi-plus-circle mr-1"></i> Add
                        </button>
                    </div>
                </div>
                <div class="row brand-row">
                    <div class="col-md-12">
                        <form name="brand_order" id="brand_order" action="{{route('brand.order')}}" method="post">
                            @csrf
                            <input type="hidden" name="orderData" id="orderBrandData" value="" />
                        </form>
                        <div class="table-responsive">
                            <table class="table table-centered table-nowrap table-striped" id="brand-datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Icon</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($brands as $key => $brand)
                                    <tr class="brandList" data-row-id="{{$brand->id}}">
                                        <td><span class="dragula-handle"></span></td>
                                        <td><img class="rounded-circle" src="{{$brand->image['proxy_url'].'30/30'.$brand->image['image_path']}}"></td>
                                        <td>{{$brand->title}}</td>
                                        <td>{{isset($brand->bc->cate->primary) ? $brand->bc->cate->primary->name : ''}}</td>
                                        <td>
                                            <a class="action-icon editBrandBtn" dataid="{{$brand->id}}" href="javascript:void(0);" > <i class="mdi mdi-square-edit-outline"></i> </a>

                                            <a class="action-icon deleteBrand" dataid="{{$brand->id}}" href="javascript:void(0);"> <i class="mdi mdi-delete"></i> </a>
                                            <form action="{{route('brand.destroy', $brand->id)}}" method="POST" style="display: none;" id="brandDeleteForm{{$brand->id}}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-icon btn btn-primary-outline" dataid="{{$brand->id}}"> <i class="mdi mdi-delete"></i> </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-12 text-right btn_bottom">
                        <button class="btn btn-info waves-effect waves-light text-sm-right saveBrandOrder">Save Order</button>
                    </div>
                </div>
            </div> <!-- end card-box -->
        </div>
    </div>
</div>
@include('backend.common.category-modals')
@include('backend.catalog.modals')
@endsection

@section('script')
    <script src="{{asset('assets/libs/nestable2/nestable2.min.js')}}"></script>
    <script src="{{asset('assets/js/pages/my-nestable.init.js')}}"></script>
    <script src="{{asset('assets/libs/dragula/dragula.min.js')}}"></script>
    <script src="{{asset('assets/js/pages/dragula.init.js')}}"></script>
    <script src="{{asset('assets/js/jscolor.js')}}"></script>
    <script src="{{ asset('assets/js/jquery.tagsinput-revisited.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.tagsinput-revisited.css') }}" />
    @include('backend.common.category-script')
    @include('backend.catalog.pagescript')

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

    $('.saveList').on('click', function(e) {
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token-z"]').attr('content')
            }
        });

        var data = $('.dd').nestable('serialize');
        document.getElementById('orderDta').value = JSON.stringify(data);
        $('#category_order').submit();
        
        /*var jsonString = ;
         console.log(jsonString);
        $.ajax({
            type: "post",
            headers: {
                Accept: "application/json"
            },
            url: "{{route('category.order')}}",
            data: {data : jsonString}, 
            dataType : 'json',
            success: function(response) {
                console.log(response);
                location.reload();
            }
        });*/

    });
    
</script>
    
@endsection