<div class="row">
    <div class="col-md-12 card-box">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('title', 'Select Category',['class' => 'control-label']) !!}
                    <select class="form-control selectize-select" id="edit_cateSelectBox" name="cate_id">
                        @foreach($categories as $cate)
                            <option value="{{$cate->id}}" @if($variant->varcategory->category_id == $cate->id) selected @endif>{{$cate->slug}}</option>
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
                    <select class="form-control selectize-select dropDownType" name="type" dataFor="edit">
                        <option value="1" @if($variant->type == 1) selected @endif>DropDown</option>
                        <option value="2" @if($variant->type == 2) selected @endif>Color</option>
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
                <input type="hidden" name="submitHide" id="submitEditHidden" value="{{route('variant.update', $variant->id)}}">
                <table class="table table-borderless mb-0" id="edit_banner-datatable" >
                    <tr>
                        @foreach($languages as $langs)
                            <td>{{$langs->language->name}}</td>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach($languages as $langs)

                            <?php $valueData = ''; ?>

                            @foreach($variant->translation as $trans)

                                @if($trans->language_id == $langs->language_id)

                                    <?php $valueData = $trans->title; ?>

                                @endif
                            @endforeach

                            <td style="min-width: 200px;">
                                {!! Form::hidden('language_id[]', $langs->language_id) !!}
                                <input type="text" name="title[]" class="form-control" value="{{$valueData}}" @if($langs->is_primary == 1) required @endif>
                            </td>
                        @endforeach 

                    </tr>
                    
                </table>
            </div>
        </div>

        <div class="row rowYK">
            <div class="col-md-12">
                <h5>Variant Options</h5>
            </div>
            <div class="col-md-12" style="overflow-x: auto;">
                <table class="table table-borderless mb-0 optionTableEdit" id="edit_variant-datatable">
                    <tr class="trForClone">
                        <td class="hexacodeClass-edit" style="@if($variant->type == 1) display: none @endif">Color Code</td>
                        @foreach($languages as $langs)
                            <td>{{$langs->language->name}}</td>
                        @endforeach
                        <td></td>
                    </tr>

                   @foreach($variant->option as $first => $opt)
                   <tr>
                        <td style="min-width: 200px; @if($variant->type == 1) display: none @endif" class="hexacodeClass-edit">
                            <input type="text" name="hexacode[]" class="form-control hexa-colorpicker" value="{{$opt->hexacode}}" id="hexa-colorpicker-{{$opt->id}}">

                            {!! Form::hidden('option_id[]', $opt->id) !!}
                        </td>

                        @foreach($languages as $langs)

                            @php $optData = $optDataId = ''; @endphp
                            @foreach($opt->translation as $opt_trans)

                                @if($opt_trans->language_id == $langs->language_id)
                                    @php
                                        $optData = $opt_trans->title;
                                        $optDataId = $opt_trans->variant_option_id;
                                    @endphp

                                @endif
                            @endforeach

                            <td style="min-width: 200px;">
                                <input type="hidden" name="opt_id[{{$langs->language_id}}][]" class="form-control" value="{{$optDataId}}" @if($langs->is_primary == 1) required @endif>
                                <input type="text" name="opt_title[{{$langs->language_id}}][]" class="form-control" value="{{$optData}}" @if($langs->is_primary == 1) required @endif>
                            </td>
                        @endforeach 


                        <td class="lasttd">
                            @if($first > 0)
                            <a href="#" class="action-icon deleteCurRow"> <h3> <i class="mdi mdi-delete"></i> </h3></a>
                            @endif
                        </td>
                    </tr>
                        
                    @endforeach
                    
                </table>
            </div>
            <div class="col-md-12">
                <button type="button" class="btn btn-info waves-effect waves-light addOptionRow-edit">Add Option</button>
            </div>
        </div>
    </div>
</div>