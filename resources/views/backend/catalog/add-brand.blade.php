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
                            <th>{{$langs->langName}}</th>
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