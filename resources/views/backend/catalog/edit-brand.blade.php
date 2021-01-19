<div class="row">
    <div class="col-md-12 card-box">
        <div class="row mb-2">
            <div class="col-md-12">              
                <input type="file" data-plugins="dropify" name="image" class="dropify" data-default-file="{{ !empty($brand->image) ? env('IMG_URL').'storage/app/public/'.$brand->image : '' }}" />
                <p class="text-muted text-center mt-2 mb-0">Upload image</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('title', 'Select Category',['class' => 'control-label']) !!}
                    <select class="form-control selectize-select" id="cateSelectBox" name="cate_id">
                        @foreach($categories as $cate)
                            <option value="{{$cate->id}}" @if($brand->bc->category_id == $cate->id) selected @endif>{{$cate->slug}}</option>
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
                <table class="table table-borderless mb-0" id="edit_brand-datatable" >
                    <tr>
                        @foreach($languages as $langs)
                            <td>{{$langs->langName}}</td>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach($brand->translation as $trans)
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
    </div>
</div>