
<div id="addVariantmodal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Variant</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="addVariantForm" method="post" enctype="multipart/form-data" action="{{route('variant.store')}}">
                @csrf
                <div class="modal-body" id="AddVariantBox">
                    <div class="row">
                        <div class="col-md-12 card-box">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('title', 'Select Category',['class' => 'control-label']) !!}
                                        <select class="selectize-select form-control" id="cateSelectBox" name="cate_id">
                                            @foreach($categories as $cate)
                                                <option value="{{$cate->id}}">{{$cate->slug}}</option>
                                            @endforeach
                                        </select>
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('title', 'Type',['class' => 'control-label']) !!}
                                        <select class="form-control selectize-select" name="type">
                                            <option value="1">DropDown</option>
                                            <option value="2">Color</option>
                                        </select>
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>

                                <!--<div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('title', 'Variant Title(English)',['class' => 'control-label']) !!}
                                        {!! Form::hidden('language_id[]', 1) !!}
                                        {!! Form::text('title[]', null, ['class' => 'form-control']) !!}
                                    </div>
                                </div>-->

                            </div>                            
                            <div class="row rowYK">
                                <div class="col-md-12">
                                    <h5>Variant Title</h5>
                                </div>
                                <div class="col-md-12" style="overflow-x: auto;">
                                    <table class="table table-borderless mb-0" id="banner-datatable" >
                                        <tr>
                                            @foreach($languages as $langs)
                                                <td>{{$langs->langName}}</td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            @foreach($languages as $langs)
                                                @if($langs->langId == 1)
                                                    <td style="min-width: 200px;">
                                                        {!! Form::hidden('language_id[]', $langs->langId) !!}
                                                        {!! Form::text('title[]', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                                    </td>

                                                @else
                                                    <td style="min-width: 200px;">
                                                        {!! Form::hidden('language_id[]', $langs->langId) !!}
                                                        {!! Form::text('title[]', null, ['class' => 'form-control']) !!}
                                                    </td>
                                                @endif
                                            @endforeach 
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="row rowYK">
                                <div class="col-md-12">
                                    <h5>Variant Options</h5>
                                    <p>Note: Fill Color code if variant type is color(Default - #cccccc)</p>
                                </div>
                                <div class="col-md-12" style="overflow-x: auto;">
                                    <table class="table table-borderless mb-0 optionTableAdd" id="banner-datatable">
                                        <tr class="trForClone">
                                            <td>Color Code</td>
                                            @foreach($languages as $langs)
                                                <td>{{$langs->langName}}</td>
                                            @endforeach
                                            <td></td>
                                        </tr>
                                        <tr >
                                            <td style="min-width: 100px;">
                                                {!! Form::text('hexacode[]', null, ['class' => 'form-control', 'placeholder' => '#cccccc']) !!}
                                            </td>
                                           @foreach($languages as $key => $langs)
                                            <td style="min-width: 200px;">
                                                <input type="text" name="opt_color[{{$key}}][]" class="form-control" @if($langs->langId == 1) required @endif>
                                            </td>
                                            @endforeach
                                            <td class="lasttd"></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-blue waves-effect waves-light addOptionRow-Add">Add Option</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-blue waves-effect waves-light addVariantSubmit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="editVariantmodal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Variant</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="editVariantForm" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body" id="editVariantBox">
                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-blue waves-effect waves-light addVariantSubmit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--   Brand      modals   -->
<div id="addBrandmodal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Brand</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="addBrandForm" method="post" enctype="multipart/form-data" action="{{route('brand.store')}}">
                @csrf
                <div class="modal-body" id="AddVariantBox">
                    <div class="row">
                        <div class="col-md-12 card-box">
                            <div class="row mb-2">
                                <div class="col-md-12">              
                                    <input type="file" data-plugins="dropify" name="image" class="dropify" data-default-file="" />
                                    <p class="text-muted text-center mt-2 mb-0">Upload image</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('title', 'Select Category',['class' => 'control-label']) !!}
                                        <select class="form-control selectize-select" id="cateSelectBox" name="cate_id">
                                            @foreach($categories as $cate)
                                                <option value="{{$cate->id}}">{{$cate->slug}}</option>
                                            @endforeach
                                        </select>
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>

                            </div>                            
                            <div class="row rowYK">
                                <div class="col-md-12">
                                    <h5>Variant Title</h5>
                                </div>
                                <div class="col-md-12" style="overflow-x: auto;">
                                    <table class="table table-borderless mb-0" id="banner-datatable" >
                                        <tr>
                                            @foreach($languages as $langs)
                                                <td>{{$langs->langName}}</td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            @foreach($languages as $langs)
                                                @if($langs->langId == 1)
                                                    <td style="min-width: 200px;">
                                                        {!! Form::hidden('language_id[]', $langs->langId) !!}
                                                        {!! Form::text('title[]', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                                    </td>

                                                @else
                                                    <td style="min-width: 200px;">
                                                        {!! Form::hidden('language_id[]', $langs->langId) !!}
                                                        {!! Form::text('title[]', null, ['class' => 'form-control']) !!}
                                                    </td>
                                                @endif
                                            @endforeach 
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-blue waves-effect waves-light addbrandSubmit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="editBrandmodal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Brand</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="editBrandForm" method="post" enctype="multipart/form-data" action="">
                @csrf
                @method('PUT')
                <div class="modal-body" id="editBrandBox">
                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-blue waves-effect waves-light editbrandSubmit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>