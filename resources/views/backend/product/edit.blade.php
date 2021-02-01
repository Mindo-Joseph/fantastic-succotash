@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Edit Product'])

@section('css')
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
<style type="text/css">
    .image-upload>input {
        display: none;
    }
</style>
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
        <div class="col-4 text-right" style="margin: auto;">
            <button type="button" class="btn btn-blue waves-effect waves-light text-sm-right saveProduct"> Submit</button>
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

    <form action="{{route('product.update', $product->id)}}" enctype="multipart/form-data" method="post" class="product_form">
        <div class="row">
            <div class="col-lg-7">
                @csrf
                @method('PUT')
                <div class="card-box">
                    <h5 class="text-uppercase bg-light p-2 mt-0 mb-3">General</h5>
                    <div class="row mb-2">
                        <div class="col-6 mb-2">
                            {!! Form::label('title', 'Product Type',['class' => 'control-label']) !!}
                            <select class="form-control selectizeInput" id="typeSelectBox" name="type_id">
                                @foreach($typeArray as $type)
                                    <option value="{{$type->id}}" @if($type->id == $product->type_id) selected @endif >{{$type->title}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-6 mb-2" style="cursor: not-allowed;">
                            {!! Form::label('title', 'SKU (Allowed Keys -> a-z,A-Z,0-9,-,_)',['class' => 'control-label']) !!}
                            <span class="text-danger">*</span>
                            {!! Form::text('sku', $product->sku, ['class'=>'form-control','id' => 'sku', 'style' => 'pointer-events:none;']) !!}

                            @if($errors->has('sku'))
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('sku') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="col-6" style="cursor: not-allowed;">
                            {!! Form::label('title', 'Url Slug',['class' => 'control-label']) !!}
                            {!! Form::text('url_slug', $product->url_slug, ['class'=>'form-control', 'id' => 'url_slug', 'style' => 'pointer-events:none;']) !!}
                        </div>

                        <div class="col-6" style="cursor: not-allowed;">
                            {!! Form::label('title', 'Category',['class' => 'control-label']) !!}
                            {!! Form::text('url_slug', $product->category->cat->slug, ['class'=>'form-control', 'id' => 'url_slug', 'style' => 'pointer-events:none;']) !!}
                        </div>
                    </div>
                </div>

                <div class="card-box ">
                    <div class="row mb-2 bg-light">
                        <div class="col-8" style="margin:auto; padding: 8px !important;">
                            <h5 class="text-uppercase  mt-0 mb-0">Product Information</h5>
                        </div>
                        <div class="col-4 p-2 mt-0" style="margin:auto; padding: 8px !important;">
                            <select class="selectize-select form-control" id="language_id" name="language_id">
                                @foreach($languages as $lang)
                                <option value="{{$lang->langId}}">{{$lang->langName}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-12 mb-2">
                            {!! Form::label('title', 'Product Name',['class' => 'control-label']) !!}
                            {!! Form::text('product_name', $product->english->title, ['class'=>'form-control', 'id' => 'product_name', 'placeholder' => 'Apple iMac']) !!}
                        </div>

                        <div class="col-12 mb-2">
                            {!! Form::label('title', 'Product Desription',['class' => 'control-label']) !!}
                            {!! Form::textarea('body_html', $product->english->body_html, ['class'=>'form-control', 'id' => 'body_html', 'placeholder' => 'Description', 'rows' => '3']) !!}
                        </div>

                        <div class="col-12 mb-2">
                            {!! Form::label('title', 'Meta Title',['class' => 'control-label']) !!}
                            {!! Form::text('meta_title', $product->english->meta_title, ['class'=>'form-control', 'id' => 'meta_title', 'placeholder' => 'Meta Title']) !!}
                        </div>

                        <div class="col-12 mb-2">
                            {!! Form::label('title', 'Meta Keyword',['class' => 'control-label']) !!}
                            {!! Form::textarea('meta_keyword', $product->english->meta_keyword, ['class'=>'form-control', 'id' => 'meta_keyword', 'placeholder' => 'Meta Keyword', 'rows' => '3']) !!}
                        </div>

                        <div class="col-12 mb-2">
                            {!! Form::label('title', 'Meta Desription',['class' => 'control-label']) !!}
                            {!! Form::textarea('meta_description', $product->english->meta_description, ['class'=>'form-control', 'id' => 'meta_description', 'placeholder' => 'Meta Desription', 'rows' => '3']) !!}
                        </div>
                    </div>
                </div>
                @if(empty($product->variantSet))
                <div class="card-box">
                    <h5 class="text-uppercase mt-0 mb-3 bg-light p-2">Pricing Information</h5>
                    <div class="row mb-2">
                        <div class="col-6 mb-2">
                            {!! Form::label('title', 'Price', ['class' => 'control-label']) !!}
                            {!! Form::text('price', null, ['class'=>'form-control', 'id' => 'price', 'placeholder' => '200', 'onkeypress' => 'return isNumberKey(event)']) !!}
                        </div>
                        <div class="col-6 mb-2">
                            {!! Form::label('title', 'Compare at price', ['class' => 'control-label']) !!}
                            {!! Form::text('compare_at_price', null, ['class'=>'form-control', 'id' => 'compare_at_price', 'placeholder' => '200', 'onkeypress' => 'return isNumberKey(event)']) !!}
                        </div>
                        <div class="col-6 mb-2">
                            {!! Form::label('title', 'Cost Price', ['class' => 'control-label']) !!}
                            {!! Form::text('cost_price', null, ['class'=>'form-control', 'id' => 'cost_price', 'placeholder' => '200', 'onkeypress' => 'return isNumberKey(event)']) !!}
                        </div>
                        
                    </div>
                    <div class="row mb-2">
                        {!! Form::label('title', 'Track Inventory',['class' => 'control-label col-sm-4']) !!}
                        <div class="col-sm-4">
                            <input type="checkbox" bid="" id="has_inventory" data-plugin="switchery" name="has_inventory" class="chk_box" data-color="#039cfd" checked>
                        </div>
                    </div>
                    <div class="row mb-2 check_inventory">
                        <div class="col-sm-6">
                            {!! Form::label('title', 'Quantity',['class' => 'control-label']) !!}
                            {!! Form::number('quantity', 0, ['class'=>'form-control', 'id' => 'quantity', 'placeholder' => '0', 'min' => '0', 'onkeypress' => 'return isNumberKey(event)']) !!}
                        </div>
                        <div class="col-sm-2"></div>
                        <div class="col-sm-4">
                            {!! Form::label('title', 'Sell When Out Of Stock',['class' => 'control-label']) !!} <br/>
                            <input type="checkbox" bid="" id="sell_stock_out" data-plugin="switchery" name="sell_stock_out" class="chk_box" data-color="#039cfd">
                        </div>

                    </div>
                </div>
                @endif

                <div class="card-box">
                    
                    <div class="row mb-2 bg-light">
                        <div class="col-8" style="margin:auto;">
                            <h5 class="text-uppercase mt-0 bg-light p-2">Variant Information</h5>
                        </div>
                        <div class="col-4 p-2 mt-0 text-right" style="margin:auto; ">
                            <button type="button" class="btn btn-blue makeVariantRow"> Create Variants</button>
                        </div>
                    </div>
                    <p>Select or change category to get variants</p>
                    <div class="row" style="width:100%; overflow-x: scroll;">
                        <div id="variantAjaxDiv" class="col-12 mb-2" >
                            <h5 class="">Variant List</h5>
                            <div class="row mb-2">
                                
                                @foreach($productVariants as $vk => $var)
                                    <div class="col-sm-3"> 
                                        <label class="control-label">{{$var->title}}</label>
                                    </div> 
                                    <div class="col-sm-9">
                                    @foreach($var->option as $key => $opt)
                                        <div class="checkbox checkbox-success form-check-inline pr-3">
                                            <input type="checkbox" name="variant{{$var->id}}" class="intpCheck" opt="{{$opt->id.';'.$opt->title}}" varId="{{$var->id.';'.$var->title}}" id="opt_vid_{{$opt->id}}" @if(in_array($opt->id, $existOptions)) checked @endif> 
                                            <label  for="opt_vid_{{$opt->id}}">{{$opt->title}}</label>
                                        </div>
                                    @endforeach
                                    </div>
                                @endforeach
                            </div>

                        </div>
                        <div class="col-12" id="exist_variant_div">
                            <h5 class="">Applied Variants Set</h5>
                            <table class="table table-centered table-nowrap table-striped">
                                <thead>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Variants</th>
                                    <th>Price</th>
                                    <th>Compare at price</th>
                                    <th>Cost Price</th>
                                    <th>Quantity</th>
                                    <th> </th>
                                </thead>
                                @foreach($product->variant as $varnt)
                                    @php 
                                        $existSet = array();
                                        $mediaPath = (empty($varnt->set[0]->path)) ? asset("assets/images/default_image.png") : url('storage/'.$varnt->set[0]->path);
                                        $existSet = explode('-', $varnt->sku);
                                        $vsets = '';

                                    @endphp
                                    @foreach($varnt->set as $vs)
                                         @php 
                                            $vsets .= $vs->title.', ';
                                         @endphp
                                    @endforeach
                                    <tr>
                                        <td>
                                            <div class="image-upload">
                                              <label class="file-input" for="file-input_{{$varnt->id}}">
                                                <img src="{{$mediaPath}}" width="30" height="30" class="vimg_{{$varnt->id}}"/>
                                              </label>

                                              <input id="file-input_{{$varnt->id}}" type="file" name="variantImage-{{$varnt->id}}" class="vimage" for="{{$varnt->id}}"/>
                                            </div>
                                        </td>
                                        <td> 
                                            <input type="hidden" name="exist_variant[]" value="{{$varnt->id}}">
                                            <input type="hidden" class="exist_sets" value="{{$existSet[(count($existSet) - 2)]}}">
                                            <input type="text" name="exist_variant_titles[]" value="{{$varnt->title}}">
                                        </td>
                                        <td>{{rtrim($vsets, ', ')}}</td>
                                        <td>
                                            <input type="text" style="width: 70px;" name="exist_variant_price[]" value="{{$varnt->price}}" onkeypress="return isNumberKey(event)"> </td>
                                        <td>
                                            <input type="text" style="width: 100px;" name="exist_variant_compare_price[]" value="{{$varnt->compare_at_price}}" onkeypress="return isNumberKey(event)">
                                        </td>
                                        <td>
                                            <input type="text" style="width: 70px;" name="exist_variant_cost_price[]" value="{{$varnt->cost_price}}" onkeypress="return isNumberKey(event)">
                                        </td>
                                        <td>
                                            <input type="text" style="width: 70px;" name="exist_variant_quantity[]" value="{{$varnt->quantity}}" onkeypress="return isNumberKey(event)">
                                        </td>
                                        <td>
                                            <a href="javascript:void(0);" varId="{{$varnt->id}}" class="action-icon deleteExistRow"> <h3> <i class="mdi mdi-delete"></i> </h3></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>

                        <div id="variantRowDiv" class="col-12"></div>
                        
                    </div>
                </div>                

            </div> <!-- end col -->

            <div class="col-lg-5">

                <div class="card-box">
                    <h5 class="text-uppercase mt-0 mb-3 bg-light p-2">Other Information</h5>

                    <div class="row mb-2">
                        {!! Form::label('title', 'New',['class' => 'control-label col-sm-2']) !!}
                        <div class="col-sm-4">
                            <input type="checkbox" id="is_new" data-plugin="switchery" name="is_new" class="chk_box" data-color="#039cfd" @if($product->is_new == 1) checked @endif>
                        </div>
                        {!! Form::label('title', 'Featured',['class' => 'control-label col-sm-2']) !!}
                        <div class="col-sm-4">
                            <input type="checkbox" id="is_featured" data-plugin="switchery" name="is_featured" class="chk_box" data-color="#039cfd" @if($product->is_new == 1) checked @endif>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-6">
                            {!! Form::label('title', 'Live',['class' => 'control-label']) !!}
                            <select class="selectize-select form-control" id="is_live" name="is_live">
                                <option value="0" @if($product->is_live == 0) selected @endif>Draft</option>
                                <option value="1" @if($product->is_live == 1) selected @endif>Published</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            {!! Form::label('title', 'Tax Category',['class' => 'control-label']) !!}
                            <select class="form-control selectizeInput" id="typeSelectBox" name="tax_category">
                                @foreach($taxCate as $cate)
                                    <option value="{{$cate->id}}" @if($product->variant[0]->tax_category_id == $cate->id) selected @endif>{{$cate->title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-2">
                        {!! Form::label('title', 'Physical',['class' => 'control-label col-sm-2']) !!}
                        <div class="col-sm-4">
                            <input type="checkbox" bid="" id="is_physical" data-plugin="switchery" name="is_physical" class="chk_box" data-color="#039cfd" @if($product->is_physical == 1) checked @endif>
                        </div>

                        {!! Form::label('title', 'Required Last Mile',['class' => 'control-label col-sm-2']) !!}
                        <div class="col-sm-4">
                            <input type="checkbox" id="last_mile" data-plugin="switchery" name="last_mile" class="chk_box" data-color="#039cfd" @if($product->Requires_last_mile == 1) checked @endif>
                        </div>
                    </div>
                    <div class="row mb-2 physicalDiv" style="{{ ($product->is_physical == 1) ? '' : 'display: none;' }}">
                        <div class="col-sm-6">
                            {!! Form::label('title', 'Weight',['class' => 'control-label']) !!}
                            {!! Form::text('weight', $product->weight,['class' => 'form-control', 'onkeypress' => 'return isNumberKey(event)']) !!}
                        </div>
                        <div class="col-sm-6">
                            {!! Form::label('title', 'Weight Unit',['class' => 'control-label']) !!}
                            {!! Form::text('weight_unit', $product->weight_unit,['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="row mb-2 physicalDiv" style="{{ ($product->is_physical == 1) ? '' : 'display: none;' }}">
                        {!! Form::label('title', 'Required Shipping',['class' => 'control-label col-sm-2 mb-2']) !!}
                        <div class="col-sm-4 mb-2">
                            <input type="checkbox" id="requiredShipping" data-plugin="switchery" name="require_ship" class="chk_box" data-color="#039cfd" @if($product->requires_shipping == 1) checked @endif>
                        </div>
                        <div class="col-sm-6"></div>
                        <div class="col-sm-6 shippingDiv" style="{{($product->requires_shipping == 1) ? '' : 'display:none;' }}">
                            {!! Form::label('title', 'Country Origin',['class' => 'control-label']) !!}
                            <select class="form-control selectizeInput" id="country_origin_id" name="country_origin_id">
                                @foreach($countries as $coun)
                                    <option value="{{$coun->id}}" @if($product->country_origin_id == $coun->id) selected @endif>{{$coun->name}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                </div>

                <div class="card-box">
                    <h5 class="text-uppercase mt-0 mb-3 bg-light p-2">Product Images</h5>
                    <div class="row mb-2">
                        @foreach($product->media as $media)
                        <div class="col-4" style="overflow: hidden;">
                            @php 
                                $mediaPath = (empty($media->path)) ? asset("assets/images/default_image.png") : url($media->path);
                            @endphp
                            <img src="{{$mediaPath}}" style="width:100%;" class="vimg_{{$media->id}}"/>
                        </div>
                        @endforeach
                    </div>
                    <div class="dropzone dropzone-previews" id="my-awesome-dropzone"></div>

                    <div class="imageDivHidden" ></div>

                </div>

                <div class="card-box">
                    <h5 class="text-uppercase mt-0 mb-3 bg-light p-2">Relate with other products</h5>
                    <div class="row mb-2">
                        <div class="col-12">
                            {!! Form::label('title', 'Select Addon Set',['class' => 'control-label']) !!}
                            <select class="form-control selectizeInput" name="addon_sets[]" multiple placeholder="Select gear...">
                                <option value="">Select gear...</option>
                                @foreach($addons as $set)
                                <option value="{{$set->id}}" @if(in_array($set->id, $addOn_ids)) selected @endif>{{$set->title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> 
                    <div class="row mb-2">
                        <div class="col-12">
                            {!! Form::label('title', 'Up Cell Products',['class' => 'control-label']) !!}
                            <select class="form-control selectizeInput" name="up_cell[]" multiple placeholder="Select gear...">
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
                            <select class="form-control selectizeInput" name="cross_cell[]" multiple placeholder="Select gear...">
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
                            <select class="form-control selectizeInput" name="releted_product[]" multiple placeholder="Select gear...">
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

<link href="{{asset('assets/css/dropzone.css')}}" rel="stylesheet" />
<script src="{{asset('assets/js/dropzone.js')}}"></script>

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

    $('#has_inventory').change(function(){
        var val = $(this).prop('checked');
        if(val == true){
            $('.check_inventory').show();
        }else{
            $('.check_inventory').hide();
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
        $('.product_form').submit();
    });

    var uploadedDocumentMap = {};

    Dropzone.autoDiscover = false;
    jQuery(document).ready(function() {

        $("div#my-awesome-dropzone").dropzone({
            addRemoveLinks: true,
            url: "{{route('product.images')}}",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            success: function (file, res) {

                $('.imageDivHidden').append('<input type="hidden" name="fileIds[]" value="' + res.imageId + '">')
                uploadedDocumentMap[file.name] = res.imageId;

            },
            removedfile: function (file) {
                file.previewElement.remove();
                console.log(file);
                var name = ''
                if (typeof file.file_name !== 'undefined') {
                    name = file.file_name
                } else {
                    name = uploadedDocumentMap[file.name]
                }
                $('form').find('input[name="fileIds[]"][value="' + name +  '"]').remove();
            },
        });

    });

    $('#category_list').change(function(){

        var cid = $(this).val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        var uri = "{{url('client/variant/cate')}}" + '/' + cid;

        $.ajax({
            type: "get",
            url: uri,
            data: '',
            dataType: 'json',
            success: function (data) {
                $('#variantAjaxDiv').html(data.resp);
            },
            error: function (data) {
                console.log('data2');
            }
        });
    });

    $('.makeVariantRow').click(function(){
        var psku = $('#sku').val();
        if(psku.trim() == ''){
            alert('Enter Product sku.');
            return false;
        }
        var vids = [];
        var optids = [];
        var exist = [];
        $("#variantAjaxDiv .intpCheck").each(function(){
            var $this = $(this);
            if($this.is(":checked") ){
                optids.push($this.attr('opt'));
                vids.push($this.attr('varid'));
            }
        });

        $("#exist_variant_div .exist_sets").each(function(){
            exist.push($(this).val());
        });

        $.ajax({
            type: "post",
            url: "{{route('product.makeRows')}}",
            data: {"_token": "{{ csrf_token() }}", 'variantIds' : vids, 'optionIds' : optids, 'sku': psku, 'existing' : exist},
            dataType: 'json',
            success: function (resp) {
                if(resp.success == 'false'){
                    alert(resp.msg);
                    $('#variantRowDiv').html('');
                }else{
                    $('#variantRowDiv').html(resp.html);
                }
                
            },
            error: function (resp) {
                console.log('data2');
            }
        });
    });

    $(document).on('click', '.deleteCurRow', function () {
        $(this).closest('tr').remove();
    });

    $(document).on('click', '.deleteExistRow', function () {
        var prod_id = '{{$product->id}}';
        var prod_var_id = $(this).attr('varid');
        $this = $(this);

        if(confirm("Are you sure? You want to delete this brand.")) {
            $.ajax({
                type: "post",
                url: "{{route('product.deleteVariant')}}",
                data: {"_token": "{{ csrf_token() }}", 'prod_id' : prod_id, 'prod_var_id' : prod_var_id},
                dataType: 'json',
                success: function (resp) {
                    alert(resp.msg);
                    $this.closest('tr').remove();
                }
            });
        }
        return false;
    });

    $(document).on('change', '.vimage', function () {
        var forv = $(this).attr('for');
        readURL(this, forv);
    });

    function readURL(input, forv) {
        
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('.vimg_'+forv).attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

</script>

@endsection