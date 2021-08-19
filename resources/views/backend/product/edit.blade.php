@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Edit Product'])

@section('css')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
<style type="text/css">
    .image-upload>input {
        display: none;
    }

    .product-img-box {
        width: 100%;
        height: 150px;
        border: 1px solid #ccc;
    }

    .product-img-box img {
        height: 100%;
        width: 100%;
        object-fit: cover;
        object-position: center;
    }

    .upload-btn-wrapper {
        position: relative;
        overflow: hidden;
        display: inline-block;
    }



    .upload-btn-wrapper input[type=file] {
        font-size: 100px;
        position: absolute;
        left: 0;
        top: 0;
        opacity: 0;
    }

    .product-img-box input[type="checkbox"] {
        position: absolute;
        top: 0;
        right: 11px;
    }

    .product-img-box label {
        width: 100%;
        height: 100%;
        display: block;
    }

    .product-img-box .form-group {
        height: 100%;
    }

    .product-img-box label:before {
        right: -1px;
        left: auto;
        top: -1px;
    }

    .product-img-box .checkbox-success input[type="checkbox"]:checked+label::after {
        left: auto;
        right: 6px;
        top: 4px;
    }

    .product-box.editPage .product-action {
        padding: 0.5rem 1rem 0 0.5rem;
    }

    .product-box.editPage .product-action .btn {
        padding: 0px 2px;
    }
</style>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-8">
            <div class="page-title-box">
                <h4 class="page-title">Edit Product</h4>
            </div>
        </div>
        <div class="col-4 text-right" style="margin: auto;">
            <button type="button" class="btn btn-info waves-effect waves-light text-sm-right saveProduct"> Submit</button>
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
                    <div class="row mb-2 row-spacing">
                        <div class="col-md-5 mb-2" style="cursor: not-allowed;">
                            {!! Form::label('title', 'SKU (a-z, A-Z, 0-9, -,_)',['class' => 'control-label']) !!}
                            <span class="text-danger">*</span>
                            {!! Form::text('sku', $product->sku, ['class'=>'form-control','id' => 'sku', 'onkeypress' => "return alplaNumeric(event)",'name' => 'sku']) !!}

                            @if($errors->has('sku'))
                            <span class="text-danger" role="alert">
                                <strong>{{ $errors->first('sku') }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="col-md-4" style="cursor: not-allowed;">
                            {!! Form::label('title', 'URL Slug',['class' => 'control-label']) !!}
                            {!! Form::text('url_slug', $product->url_slug, ['class'=>'form-control', 'id' => 'url_slug','onkeypress' => "return alplaNumericSlug(event)"]) !!}
                        </div>

                        <div class="col-md-3" style="cursor: not-allowed;">
                            {!! Form::label('title', 'Category',['class' => 'control-label']) !!}
                            {!! Form::text('category', $product->category ? $product->category->cat->name : '', ['class'=>'form-control', 'id' => 'url_slug', 'style' => 'pointer-events:none;']) !!}
                            <input type="hidden" name="category_id" value="{{$product->category ? $product->category->cat->category_id : $product->category_id}}">
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
                                <option value="{{$lang->langId}}" {{ ($lang->is_primary == 1) ? 'selected' : ''}}>{{$lang->langName}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-12 mb-2">
                            {!! Form::label('title', 'Product Name',['class' => 'control-label']) !!}
                            {!! Form::text('product_name', $product->primary ? $product->primary->title : '', ['class'=>'form-control', 'id' => 'product_name', 'placeholder' => 'Apple iMac']) !!}
                        </div>
                        <div class="col-12 mb-2">
                            {!! Form::label('title', 'Product Description',['class' => 'control-label']) !!}
                            {!! Form::textarea('body_html', $product->primary ? $product->primary->body_html : '', ['class'=>'form-control', 'id' => 'body_html', 'placeholder' => 'Description', 'rows' => '5']) !!}
                        </div>
                        <div class="col-12 mb-2">
                            {!! Form::label('title', 'Meta Title',['class' => 'control-label']) !!}
                            {!! Form::text('meta_title', $product->primary ? $product->primary->meta_title : '', ['class'=>'form-control', 'id' => 'meta_title', 'placeholder' => 'Meta Title']) !!}
                        </div>
                        <div class="col-12 mb-2">
                            {!! Form::label('title', 'Meta Keyword',['class' => 'control-label']) !!}
                            {!! Form::textarea('meta_keyword', $product->primary ? $product->primary->meta_keyword : '', ['class'=>'form-control', 'id' => 'meta_keyword', 'placeholder' => 'Meta Keyword', 'rows' => '3']) !!}
                        </div>
                        <div class="col-12 mb-2">
                            {!! Form::label('title', 'Meta Description',['class' => 'control-label']) !!}
                            {!! Form::textarea('meta_description', $product->primary ? $product->primary->meta_description : '', ['class'=>'form-control', 'id' => 'meta_description', 'placeholder' => 'Meta Description', 'rows' => '3']) !!}
                        </div>
                    </div>
                </div>
                @if($product->category->categoryDetail->type_id != 7)
                <div class="card-box">

                    <h5 class="text-uppercase mt-0 mb-3 bg-light p-2">Pricing Information</h5>
                    @if($product->has_variant == 0)
                    <div class="row mb-2">
                        <div class="col-4 mb-2">
                            {!! Form::label('title', 'Price', ['class' => 'control-label']) !!}
                            {!! Form::text('price', $product->variant[0]->price, ['class'=>'form-control', 'id' => 'price', 'placeholder' => '200', 'onkeypress' => 'return isNumberKey(event)']) !!}
                        </div>
                        <div class="col-4 mb-2">
                            {!! Form::label('title', 'Compare at price (Optional)', ['class' => 'control-label']) !!}
                            {!! Form::text('compare_at_price', $product->variant[0]->compare_at_price, ['class'=>'form-control', 'id' => 'compare_at_price', 'placeholder' => '200', 'onkeypress' => 'return isNumberKey(event)']) !!}
                        </div>
                        {{-- <div class="col-4 mb-2">
                            {!! Form::label('title', 'Cost Price (Optional)', ['class' => 'control-label']) !!}
                            {!! Form::text('cost_price', $product->variant[0]->cost_price, ['class'=>'form-control', 'id' => 'cost_price', 'placeholder' => '200', 'onkeypress' => 'return isNumberKey(event)']) !!}
                        </div> --}}
                    </div>
                    @endif
                    <div class="row mb-2">
                        @if($product->category->categoryDetail->type_id != 8)
                        <div class="col-sm-4">
                            {!! Form::label('title', 'Track Inventory') !!} <br>
                            <input type="checkbox" bid="" id="has_inventory" data-plugin="switchery" name="has_inventory" class="chk_box" data-color="#43bee1" {{$product->has_inventory == 1 ? 'checked' : ''}}>
                        </div>
                        @endif

                        <div class="col-sm-8 check_inventory {{$product->has_inventory == 0
Key
Value
Description
 ? 'd-none' : ''}}">
                            <div class="row">
                                @if($product->category->categoryDetail->type_id != 8)
                                @if($product->has_variant == 0)
                                <div class="col-sm-4">
                                    {!! Form::label('title', 'Quantity',['class' => 'control-label']) !!}
                                    {!! Form::number('quantity', $product->variant[0]->quantity, ['class'=>'form-control', 'id' => 'quantity', 'placeholder' => '0', 'min' => '0', 'onkeypress' => 'return isNumberKey(event)']) !!}
                                </div>
                                @endif
                                <div class="col-sm-4">
                                    {!! Form::label('title', 'Sell When Out Of Stock',['class' => 'control-label']) !!} <br />
                                    <input type="checkbox" bid="" id="sell_stock_out" data-plugin="switchery" name="sell_stock_out" class="chk_box" data-color="#43bee1" @if($product->sell_when_out_of_stock == 1) checked @endif>
                                </div>
                                @endif
                                @if($configData->need_dispacher_home_other_service == 1 && $product->category->categoryDetail->type_id == 8)
                                {{-- <div class="col-sm-4">
                                    {!! Form::label('title', 'Need Price From Dispatcher',['class' => 'control-label']) !!} <br />
                                    <input type="checkbox" bid="" id="need_price_from_dispatcher" data-plugin="switchery" name="need_price_from_dispatcher" class="chk_box" data-color="#43bee1" @if($product->need_price_from_dispatcher == 1) checked @endif>
                                </div> --}}
                                @endif
                                
                            </div>
                        </div>
                    </div>

                </div>
                @endif

                @if($productVariants->count() > 0)
                <div class="card-box">
                    <div class="row mb-2 bg-light">
                        <div class="col-8" style="margin:auto;">
                            <h5 class="text-uppercase mt-0 bg-light p-2">Variant Information</h5>
                        </div>
                        @if(!empty($productVariants))
                        <div class="col-4 p-2 mt-0 text-right" style="margin:auto; ">
                            <button type="button" class="btn btn-info makeVariantRow"> Make Variant Sets</button>
                        </div>
                        @endif
                    </div>
                    <p>Select or change category to get variants</p>
                    
                    <div class="row" style="width:100%; overflow-x: scroll;">
                        <div id="variantAjaxDiv" class="col-12 mb-2">
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
                                        <label for="opt_vid_{{$opt->id}}">{{$opt->title}}</label>
                                    </div>
                                    @endforeach
                                </div>
                                @endforeach
                            </div>
                        </div>

                        @if($product->has_variant == 1)
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
                                    <th class="check_inventory">Quantity</th>
                                    <th>Action</th>
                                </thead>
                                <tbody id="product_tbody_{{$product->id}}">
                                    @foreach($product->variant as $varnt)
                                    <?php
                                    $existSet = array();

                                    $mediaPath = Storage::disk('s3')->url('default/default_image.png');

                                    if (!empty($varnt->vimage) && isset($varnt->vimage->pimage->image)) {
                                        $mediaPath = $varnt->vimage->pimage->image->path['proxy_url'] . '100/100' . $varnt->vimage->pimage->image->path['image_path'];
                                    }
                                    $existSet = explode('-', $varnt->sku);
                                    $vsets = '';

                                    foreach ($varnt->set as $vs) {
                                        $vsets .= $vs->title . ', ';
                                    }
                                    ?>
                                    <tr id="tr_{{$varnt->id}}">
                                        <td>
                                            <div class="image-upload">
                                                <label class="file-input uploadImages" for="{{$varnt->id}}">
                                                    <img src="{{$mediaPath}}" width="30" height="30" for="{{$varnt->id}}" />
                                                </label>
                                            </div>
                                            <div class="imageCountDiv{{$varnt->id}}"></div>
                                        </td>
                                        <td>
                                            <input type="hidden" name="variant_ids[]" value="{{$varnt->id}}">
                                            <input type="hidden" class="exist_sets" value="{{$existSet[(count($existSet) - 1)]}}">
                                            <input type="text" name="variant_titles[]" value="{{$varnt->title}}">
                                        </td>
                                        <td>{{rtrim($vsets, ', ')}}</td>
                                        <td>
                                            <input type="text" style="width: 70px;" name="variant_price[]" value="{{$varnt->price}}" onkeypress="return isNumberKey(event)">
                                        </td>
                                        <td>
                                            <input type="text" style="width: 100px;" name="variant_compare_price[]" value="{{$varnt->compare_at_price}}" onkeypress="return isNumberKey(event)">
                                        </td>
                                        <td>
                                            <input type="text" style="width: 70px;" name="variant_cost_price[]" value="{{$varnt->cost_price}}" onkeypress="return isNumberKey(event)">
                                        </td>
                                        <td class="check_inventory">
                                            <input type="text" style="width: 70px;" name="variant_quantity[]" value="{{$varnt->quantity}}" onkeypress="return isNumberKey(event)">
                                        </td>
                                        <td>
                                            <a href="javascript:void(0);" data-varient_id="{{$varnt->id}}" class="action-icon deleteExistRow">
                                                <i class="mdi mdi-delete"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                        <div id="variantRowDiv" class="col-12"></div>
                    </div>
                </div>
                @endif
            </div>
            <div class="col-lg-5">
                <div class="card-box">
                    <h5 class="text-uppercase mt-0 mb-3 bg-light p-2">Other Information</h5>
                    <div class="row mb-2">
                        <div class="col-md-6 d-flex justify-content-between mb-2">
                            {!! Form::label('title', 'New',['class' => 'control-label']) !!}
                            <input type="checkbox" id="is_new" data-plugin="switchery" name="is_new" class="chk_box" data-color="#43bee1" @if($product->is_new == 1) checked @endif>
                        </div>
                        <div class="col-md-6 d-flex justify-content-between mb-2">
                            {!! Form::label('title', 'Featured',['class' => 'control-label']) !!}
                            <input type="checkbox" id="is_featured" data-plugin="switchery" name="is_featured" class="chk_box" data-color="#43bee1" @if($product->is_new == 1) checked @endif>
                        </div>
                        @if($configData->need_delivery_service == 1 && $product->category->categoryDetail->type_id != 7)
                        <div class="col-md-6 d-flex justify-content-between mb-2">
                            {!! Form::label('title', 'Requires Last Mile Delivery',['class' => 'control-label']) !!}
                            <input type="checkbox" id="last_mile" data-plugin="switchery" name="last_mile" class="chk_box" data-color="#43bee1" @if($product->Requires_last_mile == 1) checked @endif>
                        </div>
                        @endif
                        @if($configData->pharmacy_check == 1)
                        <div class="col-md-6 d-flex justify-content-between mb-2">
                            {!! Form::label('title', 'Requires Prescription',['class' => 'control-label']) !!}
                            <input type="checkbox" bid="" id="pharmacy_check" data-plugin="switchery" name="pharmacy_check" class="chk_box" data-color="#43bee1" @if($product->pharmacy_check == 1) checked @endif>
                        </div>
                        @endif
                        @if($configData->enquire_mode == 1)
                        <div class="col-md-6 d-flex justify-content-between mb-2">
                            {!! Form::label('title', 'Inquiry Only',['class' => 'control-label']) !!}
                            <input type="checkbox" bid="" id="inquiry_only" data-plugin="switchery" name="inquiry_only" class="chk_box" data-color="#43bee1" @if($product->inquiry_only == 1) checked @endif>
                        </div>
                        @endif


                        @if($configData->need_dispacher_ride == 1 && $product->category->categoryDetail->type_id == 7)
                            <div class="col-md-6 d-flex justify-content-between mb-2">
                                {!! Form::label('title', 'Dispatcher Tags',['class' => 'control-label']) !!}
                                <select class="selectize-select1 form-control"  name="tags" required>
                                    @if($agent_dispatcher_tags != null && count($agent_dispatcher_tags))
                                    @foreach($agent_dispatcher_tags as $key => $tags)
                                            <option value="{{ $tags['name'] }}" @if($product->tags == $tags['name']) selected="selected" @endif>{{ ucfirst($tags['name']) }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        @endif
                            
                        @if($configData->need_dispacher_home_other_service == 1 && $product->category->categoryDetail->type_id == 8)
                            <div class="col-md-6 d-flex justify-content-between mb-2">
                                {!! Form::label('title', 'Dispatcher Tags',['class' => 'control-label']) !!}
                                <select class="selectize-select1 form-control"  name="tags" required>
                                    @if($agent_dispatcher_on_demand_tags != null && count($agent_dispatcher_on_demand_tags))
                                    @foreach($agent_dispatcher_on_demand_tags as $key => $tags)
                                            <option value="{{ $tags['name'] }}" @if($product->tags == $tags['name']) selected="selected" @endif>{{ ucfirst($tags['name']) }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        @endif



                    </div>
                    <div class="row">
                        <div class="col-sm-12 mb-2">
                            {!! Form::label('title', 'Live',['class' => 'control-label']) !!}
                            <select class="selectizeInput form-control" id="is_live" name="is_live">
                                <option value="0" @if($product->is_live == 0) selected @endif>Draft</option>
                                <option value="1" @if($product->is_live == 1) selected @endif>Published</option>
                            </select>
                        </div>

                        @if($product->category->categoryDetail->type_id != 8 && $product->category->categoryDetail->type_id != 7)
                        <div class="col-md-6 mb-2">
                            {!! Form::label('title', 'Brand',['class' => 'control-label']) !!}
                            <select class="form-control " id="brand_idBox" name="brand_id">
                                <option value="">Select</option>
                                @foreach($brands as $brand)
                                <option value="{{$brand->id}}" @if(!empty($product->brand) && $product->brand->id == $brand->id) selected @endif>{{$brand->title}}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div class="col-md-6 mb-2">
                            {!! Form::label('title', 'Tax Category',['class' => 'control-label']) !!}
                            <select class="form-control " id="typeSelectBox" name="tax_category">
                                <option value="">Select</option>
                                @foreach($taxCate as $cate)
                                <option value="{{$cate->id}}" @if($product->variant[0]->tax_category_id == $cate->id) selected @endif>{{$cate->title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- <div class="row mb-2">
                        {!! Form::label('title', 'Physical',['class' => 'control-label col-sm-2']) !!}
                        <div class="col-sm-4">
                            <input type="checkbox" bid="" id="is_physical" data-plugin="switchery" name="is_physical" class="chk_box" data-color="#43bee1" @if($product->is_physical == 1) checked @endif>
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
                    <div class="row mb-2 physicalDiv" style="{{ ($product->is_physical==1) ? '' : 'display:none;' }}">
                        {!! Form::label('title', 'Required Shipping',['class' => 'control-label col-sm-3 mb-2']) !!}
                        <div class="col-sm-3 mb-2">
                            <input type="checkbox" id="requiredShipping" data-plugin="switchery" name="require_ship" class="chk_box" data-color="#43bee1" @if($product->requires_shipping == 1) checked @endif>
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
                    </div> -->

                </div>

                <div class="card-box">
                    <h5 class="text-uppercase mt-0 mb-3 bg-light p-2">Product Images</h5>
                    <div class="row mb-2">
                        @if(isset($product->media) && !empty($product->media))
                        @foreach($product->media as $media)
                        <div class="col-4 product-box editPage" style="overflow: hidden;">
                            <?php
                            $mediaPath = Storage::disk('s3')->url('default/default_image.png');
                            if (isset($media->image) && is_array($media->image->path)) {
                                $mediaPath = $media->image->path['proxy_url'] . '300/300' . $media->image->path['image_path'];
                            }
                            ?>
                            <div class="product-action">
                                <a href="{{route('product.deleteImg',[$product->id, $media->image->id])}}" class="btn btn-danger btn-xs waves-effect waves-light" onclick="return confirm('Are you sure? You want to delete the image.')"><i class="mdi mdi-close" {{$media->image}}></i></a>
                            </div>
                            <div class="bg-light">
                                <img src="{{$mediaPath}}" style="width:100%;" class="vimg_{{$media->id}}" />
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                    <div class="dropzone dropzone-previews" id="my-awesome-dropzone"></div>
                    <label class="logo-size d-block text-right mt-1">Image Size 540x715</label>
                    <div class="imageDivHidden"></div>
                </div>

                <div class="card-box">
                    <h5 class="text-uppercase mt-0 mb-3 bg-light p-2">Relate with other products</h5>
                    <div class="row">
                        @if($configData->celebrity_check == 1)
                        <div class="col-md-6 mb-2">
                            {!! Form::label('title', 'Select Celebrity',['class' => 'control-label']) !!}
                            <select class="form-control select2-multiple" name="celebrities[]" data-toggle="select2" multiple="multiple" placeholder="Select celebrity...">
                                @foreach($celebrities as $cel)
                                <option value="{{$cel->id}}" @if(in_array($cel->id, $celeb_ids)) selected @endif> {{$cel->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <div class="col-md-6 mb-2">
                            {!! Form::label('title', 'Select Addon Set',['class' => 'control-label']) !!}
                            <select class="form-control select2-multiple" name="addon_sets[]" data-toggle="select2" multiple="multiple" placeholder="Select addon...">
                                @foreach($addons as $set)
                                <option value="{{$set->id}}" @if(in_array($set->id, $addOn_ids)) selected @endif>{{$set->title}}</option>
                                @endforeach
                            </select>
                        </div>
                        @if($product->category->categoryDetail->type_id != 8)
                        <div class="col-md-6 mb-2">
                            {!! Form::label('title', 'Up Cell Products',['class' => 'control-label']) !!}
                            <select class="form-control select2-multiple" name="up_cell[]" data-toggle="select2" multiple="multiple" placeholder="Select gear...">
                                @foreach($otherProducts as $otherProduct)
                                <option value="{{$otherProduct->id}}" @if(in_array($otherProduct->id, $upSell_ids)) selected @endif>{{$otherProduct->sku}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-2">
                            {!! Form::label('title', 'Cross Cell Products',['class' => 'control-label']) !!}
                            <select class="form-control select2-multiple" name="cross_cell[]" data-toggle="select2" multiple="multiple" placeholder="Select gear...">
                                @foreach($otherProducts as $otherProduct)
                                <option value="{{$otherProduct->id}}" @if(in_array($otherProduct->id, $crossSell_ids)) selected @endif>{{$otherProduct->sku}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            {!! Form::label('title', 'Related Products',['class' => 'control-label']) !!}
                            <select class="form-control select2-multiple" name="releted_product[]" data-toggle="select2" multiple="multiple" placeholder="Select gear...">
                                @foreach($otherProducts as $otherProduct)
                                <option value="{{$otherProduct->id}}" @if(in_array($otherProduct->id, $related_ids)) selected @endif>{{$otherProduct->sku}}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<div id="upload-media" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">Add Product Image</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body" id="AddCardBox">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info waves-effect waves-light selectVaiantImages">Select</button>
            </div>
        </div>
    </div>
</div>

<style>

</style>

@endsection

@section('script')

<link href="{{asset('assets/css/dropzone.css')}}" rel="stylesheet" />
<script src="{{asset('assets/js/dropzone.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

<script type="text/javascript">
    $('#requiredShipping').change(function() {
        var val = $(this).prop('checked');
        if (val == true) {
            $('.shippingDiv').show();
        } else {
            $('.shippingDiv').hide();
        }
    });
    $('#is_physical').change(function() {
        var val = $(this).prop('checked');
        if (val == true) {
            $('.physicalDiv').show();
        } else {
            $('.physicalDiv').hide();
        }
    });

    $('#has_inventory').change(function() {
        var val = $(this).prop('checked');
        if (val == true) {
            $('.check_inventory').show();
        } else {
            $('.check_inventory').hide();
        }
    });

    var regexp = /^[a-zA-Z0-9-_]+$/;

    function removeVariant(product_id, product_variant_id, is_product_delete) {
        var redirect_url = "{{url('client/vendor/catalogs/'.$product->vendor_id)}}";
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: "{{route('product.deleteVariant')}}",
            data: {
                "_token": "{{ csrf_token() }}",
                product_id: product_id,
                product_variant_id: product_variant_id,
                is_product_delete: is_product_delete
            },
            success: function(response) {
                $('#tr_' + product_variant_id).remove();
                if (is_product_delete) {
                    window.location.href = redirect_url;
                }
            }
        });
    }
    $(document).on('click', '.deleteExistRow', function() {
        var that = $(this);
        var product_id = "{{$product->id}}";
        var product_variant_id = $(this).data('varient_id');
        var rowCount = $('#product_tbody_' + product_id + ' tr').length;
        if (rowCount == 1) {
            var is_product_delete = true;
            if (confirm("Are you sure? You want to delete this variant.")) {
                removeVariant(product_id, product_variant_id, is_product_delete);
            }
        } else {
            var is_product_delete = false;
            if (confirm("Are you sure? You want to delete this brand.")) {
                removeVariant(product_id, product_variant_id, is_product_delete);
            }
        }
    });

    function alplaNumeric(evt) {
        var charCode = String.fromCharCode(event.which || event.keyCode);
        if (!regexp.test(charCode)) {
            return false;
        }
        var n1 = document.getElementById('sku');
        var n2 = document.getElementById('url_slug');
        n2.value = n1.value + charCode;
        return true;
    }

    function alplaNumericSlug(evt) {
        var charCode = String.fromCharCode(event.which || event.keyCode);
        if (!regexp.test(charCode)) {
            return false;
        }
        var n2 = document.getElementById('url_slug');
        n2.value = n2.value + charCode;
        return true;
    }
    $('.saveProduct').click(function() {
        $('.product_form').submit();
    });

    var uploadedDocumentMap = {};
    Dropzone.autoDiscover = false;
    $(document).ready(function() {
        $('#body_html').summernote({
            placeholder: 'Description',
            tabsize: 2,
            height: 120,
            toolbar: [
                ['style', ['style']],
                // ['color', ['color']],
                ['table', ['table']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['font', ['bold', 'underline', 'clear']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
        $("div#my-awesome-dropzone").dropzone({
            acceptedFiles: ".jpeg,.jpg,.png",
            addRemoveLinks: true,
            url: "{{route('product.images')}}",
            params: {
                prodId: "{{$product->id}}"
            },
            parameter: "{{route('product.images')}}",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            success: function(file, res) {

                $('.imageDivHidden').append('<input type="hidden" name="fileIds[]" value="' + res.imageId + '">')
                uploadedDocumentMap[file.name] = res.imageId;

            },
            removedfile: function(file) {
                file.previewElement.remove();
                console.log(file);
                var name = ''
                if (typeof file.file_name !== 'undefined') {
                    name = file.file_name
                } else {
                    name = uploadedDocumentMap[file.name]
                }
                $('form').find('input[name="fileIds[]"][value="' + name + '"]').remove();
            },
        });

    });

    $('#category_list').change(function() {

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
            success: function(data) {
                $('#variantAjaxDiv').html(data.resp);
            },
            beforeSend: function() {
                $(".loader_box").show();
            },
            complete: function() {
                $(".loader_box").hide();
            },
            error: function(data) {
                console.log('data2');
            }
        });
    });

    $('.makeVariantRow').click(function() {
        var psku = $('#sku').val();
        var pid = "{{$product->id}}";
        if (psku.trim() == '') {
            alert('Enter Product sku.');
            return false;
        }
        var vids = [];
        var optids = [];
        var exist = [];
        $("#variantAjaxDiv .intpCheck").each(function() {
            var $this = $(this);
            if ($this.is(":checked")) {
                optids.push($this.attr('opt'));
                vids.push($this.attr('varid'));
            }
        });
        $("#exist_variant_div .exist_sets").each(function() {
            exist.push($(this).val());
        });
        $.ajax({
            type: "post",
            url: "{{route('product.makeRows')}}",
            data: {
                "_token": "{{ csrf_token() }}",
                'variantIds': vids,
                'optionIds': optids,
                'sku': psku,
                'existing': exist,
                'pid': pid
            },
            dataType: 'json',
            success: function(resp) {
                if (resp.success == 'false') {
                    alert(resp.msg);
                    $('#variantRowDiv').html('');
                } else {
                    $('#variantRowDiv').html(resp.html);
                }

            },
            beforeSend: function() {
                $(".loader_box").show();
            },
            complete: function() {
                $(".loader_box").hide();
            },
            error: function(resp) {
                console.log('data2');
            }
        });
    });

    $(document).on('click', '.deleteCurRow', function() {
        $(this).closest('tr').remove();
    });



    $(document).on('change', '.vimageNew', function() {

        var file = this.files[0];
        var fileType = file['type'];
        var validImageTypes = ['image/gif', 'image/jpeg', 'image/png'];
        if (!validImageTypes.includes(fileType)) {
            alert('select only images');
        } else {

            var form = document.getElementById('modalImageForm');
            var formData = new FormData(form);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "post",
                headers: {
                    Accept: "application/json"
                },
                url: "{{route('product.images')}}",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    $('#upload-media .lastDiv').before(data.htmlData);

                },
                beforeSend: function() {
                    $(".loader_box").show();
                },
                complete: function() {
                    $(".loader_box").hide();
                },
                error: function(data) {
                    console.log("error");
                    console.log(data);
                }
            });

        }
    });

    function readURL(input, forv) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('.vimg_' + forv).attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $(document).on('change', '#language_id', function() {
        var forv = $(this).val();
        var pid = "{{$product->id}}";
        $.ajax({
            type: "post",
            url: "{{route('product.translation')}}",
            data: {
                "_token": "{{ csrf_token() }}",
                'prod_id': pid,
                'lang_id': forv
            },
            dataType: 'json',
            success: function(resp) {
                if (resp) {
                    $('#product_name').val(resp.data.title);
                    $('#body_html').val(resp.data.body_html);
                    $('#meta_title').val(resp.data.meta_title);
                    $('#meta_keyword').val(resp.data.meta_keyword);
                    $('#meta_description').val(resp.data.meta_description);
                }
            },
            beforeSend: function() {
                $(".loader_box").show();
            },
            complete: function() {
                $(".loader_box").hide();
            }
        });
    });

    $(document).on('click', '.uploadImages', function() {

        var vari_id = $(this).attr('for');
        var pid = "{{$product->id}}";
        var vendor = "{{$product->vendor_id}}";
        $.ajax({
            type: "post",
            url: "{{route('productImage.get')}}",
            data: {
                "_token": "{{ csrf_token() }}",
                'prod_id': pid,
                'variant_id': vari_id,
                'vendor_id': vendor
            },
            dataType: 'json',
            success: function(data) {
                $('#upload-media #AddCardBox').html(data.htmlData);
                $('#upload-media').modal({
                    keyboard: false
                });
            },
            beforeSend: function() {
                $(".loader_box").show();
            },
            complete: function() {
                $(".loader_box").hide();
            },
        });
    });

    $(document).on('click', '.selectVaiantImages', function() {
        var variantId = $('#upload-media #modalVariantId').val();
        var productId = "{{$product->id}}";
        var imageId = [];
        $("#upload-media .imgChecks").each(function() {
            var $this = $(this);
            if ($this.is(":checked")) {
                imageId.push($this.attr('imgId'));
            }
        });
        $.ajax({
            type: "post",
            url: "{{route('product.variant.update')}}",
            data: {
                "_token": "{{ csrf_token() }}",
                'prod_id': productId,
                'variant_id': variantId,
                'image_id': imageId
            },
            dataType: 'json',
            success: function(resp) {
                if (resp.success != 'false') {
                    $('#upload-media').modal('hide');
                } else {
                    alert(resp.msg);
                    $('#upload-media').modal('hide');
                }
            },
            beforeSend: function() {
                $(".loader_box").show();
            },
            complete: function() {
                $(".loader_box").hide();
            }
        });
    });
</script>

@endsection