@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Add Product'])

@section('css')
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-8">
            <div class="page-title-box">
                <h4 class="page-title">Add Product</h4>
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
    <form action="" method="post" class="" id="product_form" action="{{route('product.store')}}">
        <div class="row">
            {!! Form::hidden('vendor_id', $vendor_id) !!}
            <div class="col-lg-7">

                <div class="card-box">
                    <h5 class="text-uppercase bg-light p-2 mt-0 mb-3">General</h5>
                    <div class="row mb-2">
                        <div class="col-6 mb-2">
                            {!! Form::label('title', 'Product Type',['class' => 'control-label']) !!}
                            <select class="form-control selectizeInput" id="typeSelectBox" name="type_id">
                                @foreach($typeArray as $type)
                                    <option value="{{$type->id}}">{{$type->title}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-6 mb-2">
                            {!! Form::label('title', 'SKU (Allowed Keys -> a-z,A-Z,0-9,-,_)',['class' => 'control-label']) !!}
                            <span class="text-danger">*</span>
                            {!! Form::text('sku', null, ['class'=>'form-control','id' => 'sku', 'onkeypress' => 'return alplaNumeric(event)', 'placeholder' => 'Apple-iMac']) !!}
                        </div>

                        <div class="col-6" style="cursor: not-allowed;">
                            {!! Form::label('title', 'Product Url',['class' => 'control-label']) !!}
                            <span class="text-danger">*</span>
                            {!! Form::text('product_url', null, ['class'=>'form-control', 'id' => 'product_url', 'placeholder' => 'Apple iMac', 'style' => 'pointer-events:none;']) !!}
                        </div>

                        <div class="col-6">
                            {!! Form::label('title', 'Category',['class' => 'control-label']) !!}
                            <select class="form-control selectizeInput" id="category" name="category">
                                <option value="">Select Category...</option>
                                @foreach($categories as $cate)
                                    <option value="{{$cate->id}}">{{$cate->english->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card-box">
                    <h5 class="text-uppercase bg-light p-2 mt-0 mb-3">Product Information</h5>
                    <div class="row mb-2">
                        <div class="col-12 mb-2">
                            {!! Form::label('title', 'Language',['class' => 'control-label']) !!}
                            <select class="selectize-select form-control" id="product_type" name="type">
                                <option value="product">English</option>
                                <option value="Service">Italian</option>
                                <option value="ride">French</option>
                            </select>
                        </div>

                        <div class="col-12 mb-2">
                            {!! Form::label('title', 'Product Name',['class' => 'control-label']) !!}
                            <span class="text-danger">*</span>
                            {!! Form::text('product_name', null, ['class'=>'form-control', 'id' => 'product_name', 'placeholder' => 'Apple iMac']) !!}
                        </div>

                        <div class="col-12 mb-2">
                            {!! Form::label('title', 'Product Desription',['class' => 'control-label']) !!}
                            {!! Form::textarea('product_desription', null, ['class'=>'form-control', 'id' => 'product_desription', 'placeholder' => 'Description', 'rows' => '3']) !!}
                        </div>

                        <div class="col-12 mb-2">
                            {!! Form::label('title', 'Meta Title',['class' => 'control-label']) !!}
                            {!! Form::text('meta_title', null, ['class'=>'form-control', 'id' => 'meta_title', 'placeholder' => 'Meta Title']) !!}
                        </div>

                        <div class="col-12 mb-2">
                            {!! Form::label('title', 'Meta Keyword',['class' => 'control-label']) !!}
                            {!! Form::textarea('meta_keyword', null, ['class'=>'form-control', 'id' => 'meta_keyword', 'placeholder' => 'Meta Keyword', 'rows' => '3']) !!}
                        </div>

                        <div class="col-12 mb-2">
                            {!! Form::label('title', 'Meta Desription',['class' => 'control-label']) !!}
                            {!! Form::textarea('meta_desription', null, ['class'=>'form-control', 'id' => 'meta_desription', 'placeholder' => 'Meta Desription', 'rows' => '3']) !!}
                        </div>
                    </div>
                </div>

                <div class="card-box">

                    <div class="row mb-2">
                        
                    </div>

                    <div class="form-group mb-3">
                        <label for="product-summary">Product Summary</label>
                        <textarea class="form-control" id="product-summary" rows="3" placeholder="Please enter summary"></textarea>
                    </div>


                    <div class="form-group mb-3">
                        <label for="product-price">Price <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="product-price" placeholder="Enter amount">
                    </div>

                    <div class="form-group mb-3">
                        <label class="mb-2">Status <span class="text-danger">*</span></label>
                        <br/>
                        <div class="radio form-check-inline">
                            <input type="radio" id="inlineRadio1" value="option1" name="radioInline" checked="">
                            <label for="inlineRadio1"> Online </label>
                        </div>
                        <div class="radio form-check-inline">
                            <input type="radio" id="inlineRadio2" value="option2" name="radioInline">
                            <label for="inlineRadio2"> Offline </label>
                        </div>
                        <div class="radio form-check-inline">
                            <input type="radio" id="inlineRadio3" value="option3" name="radioInline">
                            <label for="inlineRadio3"> Draft </label>
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <label>Comment</label>
                        <textarea class="form-control" rows="3" placeholder="Please enter comment"></textarea>
                    </div>
                </div>

                
            </div> <!-- end col -->

            <div class="col-lg-5">
                
                 <div class="card-box">
                    <h5 class="text-uppercase mt-0 mb-3 bg-light p-2">Product Images</h5>
                        <div class="fallback">
                            <input name="file" type="file" multiple />
                        </div>

                        <div class="dz-message needsclick">
                            <i class="h1 text-muted dripicons-cloud-upload"></i>
                            <h3>Drop files here or click to upload.</h3>
                            <span class="text-muted font-13">(This is just a demo dropzone. Selected files are
                                <strong>not</strong> actually uploaded.)</span>
                        </div>
                    <div class="dropzone-previews mt-3" id="file-previews"></div>
                </div>

                <div class="card-box">
                    <h5 class="text-uppercase mt-0 mb-3 bg-light p-2">Other Information</h5>



                    <div class="row mb-2">
                        {!! Form::label('title', 'New',['class' => 'control-label col-sm-4']) !!}
                        <div class="col-sm-8">
                            <input type="checkbox" bid="" id="is_new" data-plugin="switchery" name="is_new" class="chk_box" data-color="#039cfd">
                        </div>
                    </div>
                    <div class="row mb-2">
                        {!! Form::label('title', 'Featured',['class' => 'control-label col-sm-4']) !!}
                        <div class="col-sm-8">
                            <input type="checkbox" bid="" id="is_featured" data-plugin="switchery" name="is_featured" class="chk_box" data-color="#039cfd">
                        </div>
                    </div>
                    <div class="row mb-2">
                        {!! Form::label('title', 'Live',['class' => 'control-label col-sm-4']) !!}
                        <div class="col-sm-8">
                            <input type="checkbox" bid="" id="is_live" data-plugin="switchery" name="is_live" class="chk_box" data-color="#039cfd">
                        </div>
                    </div>
                    <div class="row mb-2">
                        {!! Form::label('title', 'Physical',['class' => 'control-label col-sm-4']) !!}
                        <div class="col-sm-8">
                            <input type="checkbox" bid="" id="is_physical" data-plugin="switchery" name="is_physical" class="chk_box" data-color="#039cfd">
                        </div>
                    </div>
                    <div class="row mb-2 physicalDiv" style="display: none;">
                        <div class="col-sm-6">
                            {!! Form::label('title', 'Weight',['class' => 'control-label']) !!}
                            {!! Form::text('weight', null,['class' => 'form-control']) !!}
                        </div>
                        <div class="col-sm-6">
                            {!! Form::label('title', 'Weight Unit',['class' => 'control-label']) !!}
                            {!! Form::text('weight_unit', null,['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="row mb-2">
                        {!! Form::label('title', 'Required Shipping',['class' => 'control-label col-sm-4']) !!}
                        <div class="col-sm-8">
                            <input type="checkbox" id="requiredShipping" data-plugin="switchery" name="requires_shipping" class="chk_box" data-color="#039cfd">
                        </div>
                    </div>
                    <div class="row mb-2 shippingDiv"  style="display: none;">
                        <div class="col-sm-6">
                            {!! Form::label('title', 'Country Origin',['class' => 'control-label']) !!}
                            {!! Form::text('country_origin', null,['class' => 'form-control']) !!}
                        </div>
                        <div class="col-sm-6">
                            {!! Form::label('title', 'Weight Unit',['class' => 'control-label']) !!}
                            {!! Form::text('weight_unit', null,['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="row mb-2">
                        {!! Form::label('title', 'Required Last Mile',['class' => 'control-label col-sm-4']) !!}
                        <div class="col-sm-8">
                            <input type="checkbox" id="Requires_last_mile" data-plugin="switchery" name="Requires_last_mile" class="chk_box" data-color="#039cfd">
                        </div>
                    </div>
                    
                </div> <!-- end card-box -->
                <div class="card-box">
                    <h5 class="text-uppercase mt-0 mb-3 bg-light p-2">Relate with other products</h5>
                    <div class="row mb-2">
                        <div class="col-12">
                            {!! Form::label('title', 'Up Cell Products',['class' => 'control-label']) !!}
                            <select class="form-control selectizeInput" name="up_cell" multiple placeholder="Select gear...">
                                <option value="">Select gear...</option>
                                <optgroup label="Climbing">
                                    <option value="pitons">Pitons</option>
                                    <option value="cams">Cams</option>
                                    <option value="nuts">Nuts</option>
                                    <option value="bolts">Bolts</option>
                                    <option value="stoppers">Stoppers</option>
                                    <option value="sling">Sling</option>
                                </optgroup>
                                <optgroup label="Skiing">
                                    <option value="skis">Skis</option>
                                    <option value="skins">Skins</option>
                                    <option value="poles">Poles</option>
                                </optgroup>
                            </select>
                        </div>

                        <div class="col-12">
                            {!! Form::label('title', 'Cross Cell Products',['class' => 'control-label']) !!}
                            <select class="form-control selectizeInput" name="cross_cell" multiple placeholder="Select gear...">
                                <option value="">Select gear...</option>
                                <optgroup label="Climbing">
                                    <option value="pitons">Pitons</option>
                                    <option value="cams">Cams</option>
                                    <option value="nuts">Nuts</option>
                                    <option value="bolts">Bolts</option>
                                    <option value="stoppers">Stoppers</option>
                                    <option value="sling">Sling</option>
                                </optgroup>
                                <optgroup label="Skiing">
                                    <option value="skis">Skis</option>
                                    <option value="skins">Skins</option>
                                    <option value="poles">Poles</option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="col-12">
                            {!! Form::label('title', 'Related Products',['class' => 'control-label']) !!}
                            <select class="form-control selectizeInput" name="releted_product" multiple placeholder="Select gear...">
                                <option value="">Select gear...</option>
                                <optgroup label="Climbing">
                                    <option value="pitons">Pitons</option>
                                    <option value="cams">Cams</option>
                                    <option value="nuts">Nuts</option>
                                    <option value="bolts">Bolts</option>
                                    <option value="stoppers">Stoppers</option>
                                    <option value="sling">Sling</option>
                                </optgroup>
                                <optgroup label="Skiing">
                                    <option value="skis">Skis</option>
                                    <option value="skins">Skins</option>
                                    <option value="poles">Poles</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection

@section('script')
<script type="text/javascript">
    $('#requiredShipping').change(function(){
        var val = $(this).prop('checked');
        if(val == true){
            $('.shippingDiv').show();
        }else{
            $('.shippingDiv').hide();
        }
    });

    $('#is_physical').change(function(){
        var val = $(this).prop('checked');
        if(val == true){
            $('.physicalDiv').show();
        }else{
            $('.physicalDiv').hide();
        }
    });

    var regexp = /^[a-zA-Z0-9-_]+$/;

    function alplaNumeric(evt){
        var charCode = String.fromCharCode(event.which || event.keyCode);

        if (!regexp.test(charCode)){
            return false;
        }
        var n1 = document.getElementById('sku');
        var n2 = document.getElementById('product_url');
        n2.value = n1.value+charCode;
        return true;
    }

    $('.saveProduct').click(function(){
        $('#product_form').submit();
    });

    

</script>

@endsection