<div class="row">
    <div class="col-md-12 card-box">
        <div class="row rowYK">
            <div class="col-md-12">
                <h5>Addon Title</h5>
            </div>
            <div class="col-md-12" style="overflow-x: auto;">
                
                <table class="table table-borderless mb-0" id="edit_addon-datatable" >
                    <tr>
                        @foreach($languages as $langs)
                            <td>{{$langs->langName}}</td>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach($addon->translation as $trans)
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

        <div class="row mb-2 rowYK">
            <div class="col-md-12" style="overflow-x: auto;">
                <table class="table table-borderless mb-0 optionTableEdit" id="edit_addon-datatable">
                    <tr class="trForClone">
                        <td>Price($)</td>
                        @foreach($addon->option[0]->translation as $langu)
                            <td>{{$langu->name}}</td>
                        @endforeach
                        <td></td>
                    </tr>
                    
                   @foreach($addon->option as $first => $opt)
                   <tr>
                        <td style="min-width: 100px;">
                            {!! Form::text('price[]', $opt->price, ['class' => 'form-control', 'onkeypress' => 'return isNumberKey(event)', 'min' => '1', 'required' => 'required']) !!}
                            {!! Form::hidden('option_id[]', $opt->id) !!}
                        </td>

                        @foreach($opt->translation as $key => $value)
                            @if(in_array($value->language_id, $langIds))
                            <td style="min-width: 200px;">
                                <input type="hidden" name="opt_id[{{$value->language_id}}][]" class="form-control" value="{{$value->addon_opt_id}}">
                                <input type="text" name="opt_value[{{$value->language_id}}][]" class="form-control" value="{{$value->title}}" @if($value->language_id == 1) required @endif>
                            </td>
                            @endif
                        @endforeach

                        @if(count($langIds) !=  count($existlangs))
                            @foreach($languages as $key => $language)
                                @if(in_array($trans->language_id, $langIds) && !in_array($language->langId, $existlangs))
                                    <td style="min-width: 200px;">
                                        {!! Form::hidden('opt_lang_new[]', $language->langId) !!}
                                        <input type="text" name="opt_value_new[{{$language->langId}}][]" class="form-control" value="" @if($language->langId == 1) required @endif>
                                    </td>
                                @endif
                            @endforeach 
                        @endif
                        
                        <td class="lasttd">
                            @if($first > 0)
                            <a href="javascript:void(0);" class="action-icon deleteCurRow"> <h3> <i class="mdi mdi-delete"></i> </h3></a>
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

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('title', 'Min Select',['class' => 'control-label']) !!}
                    {!! Form::text('min_select', $addon->min_select, ['class' => 'form-control', 'onkeypress' => 'return isNumberKey(event)']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('title', 'Max Select',['class' => 'control-label']) !!}
                    {!! Form::text('max_select', $addon->max_select, ['class' => 'form-control', 'onkeypress' => 'return isNumberKey(event)']) !!}
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