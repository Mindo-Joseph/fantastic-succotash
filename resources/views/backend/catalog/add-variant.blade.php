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
                    <select class="form-control selectize-select dropDownType" name="type" dataFor="add">
                        <option value="1">DropDown</option>
                        <option value="2">Color</option>
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
                            <th>{{$langs->langName}}</th>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach($languages as $langs)
                            @if($langs->is_primary == 1)
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
                <!--<p>Note: Fill Color code if variant type is color(Default - #cccccc)</p> -->
            </div>
            <div class="col-md-12" style="overflow-x: auto;">
                <table class="table table-borderless mb-0 optionTableAdd" id="banner-datatable">
                    <tr class="trForClone">
                        <td class="hexacodeClass-add" style="display:none;">Color Code</td>
                        @foreach($languages as $langs)
                            <td>{{$langs->langName}}</td>
                        @endforeach
                        <td></td>
                    </tr>
                    <tr>
                        <td style="min-width: 200px; display:none;" class="hexacodeClass-add">
                            <input type="text" name="hexacode[]" class="form-control hexa-colorpicker" value="cccccc" id="hexa-colorpicker-1">
                        </td>
                       @foreach($languages as $key => $langs)
                        <td style="min-width: 200px;">
                            <input type="text" name="opt_color[{{$key}}][]" class="form-control" @if($langs->is_primary == 1) required @endif>
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