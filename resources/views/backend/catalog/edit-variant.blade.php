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
                            <td>{{$langs->langName}}</td>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach($variant->translation as $trans)
                            @if(in_array($trans->language_id, $langIds))
                                @if($trans->language_id == 1)
                                    <td style="min-width: 200px;">
                                        {!! Form::hidden('language_id[]', $trans->language_id) !!}
                                        {!! Form::text('title[]', $trans->title, ['class' => 'form-control', 'required' => 'required']) !!}
                                    </td>
                                @else
                                    <td style="min-width: 200px;">
                                        {!! Form::hidden('language_id[]', $trans->language_id) !!}
                                        {!! Form::text('title[]', $trans->title, ['class' => 'form-control']) !!}
                                    </td>
                                @endif
                            @endif
                        @endforeach

                        @if(count($langIds) !=  count($existlangs))
                       
                            @foreach($languages as $language)
                                @if(!in_array($language->langId, $existlangs) && in_array($language->langId, $langIds))
                                    <td style="min-width: 200px;">
                                        {!! Form::hidden('language_id[]', $language->langId) !!}
                                        {!! Form::text('title[]', null, ['class' => 'form-control']) !!}
                                    </td>
                                @endif
                            @endforeach 
                        @endif
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
                <table class="table table-borderless mb-0 optionTableEdit" id="edit_banner-datatable">
                    <tr class="trForClone">
                        <td class="hexacodeClass-edit" style="@if($variant->type == 1) display: none @endif">Color Code</td>
                        @foreach($variant->option[0]->translation as $langu)
                            <td>{{$langu->name}}</td>
                        @endforeach
                        <td></td>
                    </tr>
                    
                   @foreach($variant->option as $first => $opt)
                   <tr>
                        <td style="min-width: 200px; @if($variant->type == 1) display: none @endif" class="hexacodeClass-edit">
                            <input type="text" name="hexacode[]" class="form-control hexa-colorpicker" value="{{$opt->hexacode}}" id="hexa-colorpicker-{{$opt->id}}">

                            {!! Form::hidden('option_id[]', $opt->id) !!}
                        </td>

                        @foreach($opt->translation as $key => $value)
                            @if(in_array($value->language_id, $langIds))
                            <td style="min-width: 200px;">
                                <input type="hidden" name="opt_id[{{$value->language_id}}][]" class="form-control" value="{{$value->variant_option_id}}" @if($langs->langId == 1) required @endif>
                                <input type="text" name="opt_color[{{$value->language_id}}][]" class="form-control" value="{{$value->title}}" @if($value->language_id == 1) required @endif>
                            </td>
                            @endif
                        @endforeach

                        @if(count($langIds) !=  count($existlangs))
                            @foreach($languages as $key => $language)
                                @if(in_array($trans->language_id, $langIds) && !in_array($language->langId, $existlangs))
                                    <td style="min-width: 200px;">
                                        {!! Form::hidden('opt_lang_new[]', $language->langId) !!}
                                        <input type="text" name="opt_color_new[{{$language->langId}}][]" class="form-control" value="" @if($language->langId == 1) required @endif>
                                    </td>
                                @endif
                            @endforeach 
                        @endif
                        
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
                <button type="button" class="btn btn-blue waves-effect waves-light addOptionRow-edit">Add Option</button>
            </div>
        </div>
    </div>
</div>