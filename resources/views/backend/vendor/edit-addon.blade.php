<div class="row">
    <div class="col-md-12">
        <div class="row rowYK">
            <div class="col-md-12">
                <h5>Addon Title</h5>
            </div>
            <div class="col-md-12" style="overflow-x: auto;">
                
                <table class="table table-borderless mb-0" id="edit_addon-datatable" >
                    <tr>
                        @foreach($languages as $langs)
                            <th>{{$langs->language->name}}</th>
                        @endforeach
                    </tr>
                    <tr>

                        @foreach($languages as $langs)

                            <?php $valueData = ''; ?>

                            @foreach($addon->translation as $trans)

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

        <div class="row mb-2 rowYK">
            <div class="col-md-12" style="overflow-x: auto;">
                <table class="table table-borderless mb-0 optionTableEdit" id="edit_addon-datatable">
                    <tr class="trForClone">
                        <th>Price($)</th>
                        @foreach($languages as $langs)
                            <th>{{$langs->language->name}}</th>
                        @endforeach
                        <th></th>
                    </tr>
                    
                   @foreach($addon->option as $first => $opt)
                   <tr>
                        <td style="min-width: 100px;">
                            {!! Form::text('price[]', $opt->price, ['class' => 'form-control', 'onkeypress' => 'return isNumberKey(event)', 'min' => '1', 'required' => 'required']) !!}
                            {!! Form::hidden('option_id[]', $opt->id) !!}
                        </td>
                        

                        @foreach($languages as $langs)

                            @php $optData = $optDataId = ''; @endphp
                            @foreach($opt->translation as $opt_trans)

                                @if($opt_trans->language_id == $langs->language_id)
                                    @php
                                        $optData = $opt_trans->title;
                                        $optDataId = $opt_trans->addon_opt_id;
                                    @endphp

                                @endif
                            @endforeach

                            <td style="min-width: 200px;">
                            <input type="hidden" name="opt_id[{{$langs->language_id}}][]" class="form-control" value="{{$optDataId}}">
                                <input type="text" name="opt_value[{{$langs->language_id}}][]" class="form-control" value="{{$optData}}" @if($langs->is_primary == 1) required @endif>
                            </td>
                        @endforeach

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
                <button type="button" class="btn btn-info waves-effect waves-light addOptionRow-edit">Add Option</button>
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