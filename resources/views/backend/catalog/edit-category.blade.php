<div class="row">
    <div class="col-md-12 card-box">
        <h4 class="header-title mb-3"></h4>

        <div class="row mb-2">
            <div class="col-md-3">
                <input type="file" data-plugins="dropify" name="icon" class="dropify" data-default-file="{{$category->icon['proxy_url'].'400/400'.$category->icon['image_path']}}" />
                <p class="text-muted text-center mt-2 mb-0">Upload Category Icon</p>
            </div> <div class="col-md-2"></div>
            <div class="col-md-6"> <!--  Storage::disk('s3')->url($client->logo)  -->                 
                <input type="file" data-plugins="dropify" name="image" class="dropify" data-default-file="{{$category->image['proxy_url'].'400/400'.$category->image['image_path']}}" />
                <p class="text-muted text-center mt-2 mb-0">Upload Category image</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group" id="slugInputEdit">
                    {!! Form::label('title', 'Slug',['class' => 'control-label']) !!} 
                    {!! Form::text('slug', $category->slug, ['class'=>'form-control']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                    {!! Form::hidden('login_user_type', session('preferences.login_user_type'), ['class'=>'form-control']) !!}
                    {!! Form::hidden('login_user_id', auth()->user()->id, ['class'=>'form-control']) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('title', 'Select Parent Category',['class' => 'control-label']) !!}
                    <select class="selectize-select1 form-control" id="cateSelectBox" name="parent_cate">
                        <option value="">Select</option>
                        @foreach($parCategory as $pc)
                            <option value="{{$pc->id}}" {{ ($pc->id == $category->parent_id) ? 'selected' : '' }} > {{$pc->name}}</option>
                        @endforeach
                    </select>
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('title', 'Type',['class' => 'control-label']) !!}
                    <select class="selectize-select form-control type-select" for="edit" id="typeSelectBox" name="type_id">
                        @foreach($typeArray as $type)
                        <option value="{{$type->id}}" @if($category->type_id == $type->id) selected @endif>{{$type->title}}</option>
                        @endforeach

                    </select>
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                    <input type="hidden" id="cateId" url="{{route('category.update', $category->id)}}">
                </div>
            </div>
            <div class="col-md-3 text-center">
                <div class="form-group">
                    {!! Form::label('title', 'Visible In Menus',['class' => 'control-label']) !!} 
                    <div>
                        @if($category->is_visible == '1')
                            <input type="checkbox" data-plugin="switchery" name="is_visible" class="form-control switch2Edit" data-color="#039cfd" checked='checked'>
                        @else
                            <input type="checkbox" data-plugin="switchery" name="is_visible" class="form-control switch2Edit" data-color="#039cfd">
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6 text-center" style="{{($category->type_id != 1) ? 'display:none;' : ''}}" id="editProductHide">
                <div class="form-group">
                    {!! Form::label('title', 'Can Add Products',['class' => 'control-label']) !!} 
                    <div>
                        @if($category->can_add_products == '1')
                            <input type="checkbox" data-plugin="switchery" class="form-control switch1Edit" data-color="#039cfd" name="can_add_products" checked='checked'>
                        @else
                            <input type="checkbox" data-plugin="switchery" class="form-control switch1Edit" data-color="#039cfd" name="can_add_products">
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6" style="{{($category->type_id != 2) ? 'display:none;' : ''}}" id="editDispatcherHide">
                <div class="form-group">
                    {!! Form::label('title', 'Dispatcher Tags',['class' => 'control-label']) !!}
                    {!! Form::hidden('tags', implode(',', $tagList), ['class'=>'form-control myTag1']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>

            <!--<div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('title', 'Display Mode',['class' => 'control-label']) !!}
                    <select class="selectize-select form-control" name="display_mode">
                        <option value="1" @if($category->display_mode == '1') selected @endif >Show Product only</option>
                        <option value="2" @if($category->display_mode == '2') selected @endif >Show Product With Description</option>
                    </select>
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div> 
        -->
        </div>
        @foreach($category->translation as $trans)
            <div class="row rowYK" style="border: 2px dashed #d2d0cd;">
                <h4 class="col-md-12"> {{ $trans->langName.' Language' }} </h4>
                <div class="col-md-6">
                    <div class="form-group" id="{{ ($trans->is_primary == 1) ? 'nameInputEdit' : 'nameotherInput' }}">
                        {!! Form::label('title', 'Name',['class' => 'control-label']) !!}
                        @if($trans->is_primary == 1)
                            {!! Form::text('name[]', $trans->name, ['class' => 'form-control', 'required' => 'required']) !!}
                        @else
                            {!! Form::text('name[]', $trans->name, ['class' => 'form-control']) !!}
                        @endif
                        
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    </div>
                </div>
                {!! Form::hidden('language_id[]', $trans->langId) !!}
                {!! Form::hidden('trans_id[]', $trans->id) !!}
                <div class="col-md-6">
                    <div class="form-group" id="meta_titleInput">
                        {!! Form::label('title', 'Meta Title',['class' => 'control-label']) !!} 
                        {!! Form::text('meta_title[]', $trans->meta_title, ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('title', 'Meta Description',['class' => 'control-label']) !!} 
                        {!! Form::textarea('meta_description[]', $trans->meta_description, ['class'=>'form-control', 'rows' => '3']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('title', 'Meta Keywords',['class' => 'control-label']) !!} 
                        {!! Form::textarea('meta_keywords[]', $trans->meta_keywords, ['class' => 'form-control', 'rows' => '3']) !!}
                    </div>
                </div>
            </div>
        @endforeach

        @if(count($langIds) !=  count($existlangs))
            @foreach($languages as $langs)
              @if(!in_array($langs->langId, $existlangs) && in_array($langs->langId, $langIds))
                <div class="row rowYK" style="border: 2px dashed #d2d0cd;">
                    <h4 class="col-md-12"> {{ $langs->langName.' Language' }} </h4>
                    <div class="col-md-6">
                        <div class="form-group" id="{{ ($langs->is_primary == 1) ? 'nameInputEdit' : 'nameotherInput' }}">
                            {!! Form::label('title', 'Name',['class' => 'control-label']) !!}
                            @if($trans->is_primary == 1)
                                {!! Form::text('name[]', null, ['class' => 'form-control', 'required' => 'required']) !!}
                            @else
                                {!! Form::text('name[]', null, ['class' => 'form-control']) !!}
                            @endif
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                    {!! Form::hidden('language_id[]', $langs->langId) !!}
                    <div class="col-md-6">
                        <div class="form-group" id="meta_titleInput">
                            {!! Form::label('title', 'Meta Title',['class' => 'control-label']) !!} 
                            {!! Form::text('meta_title[]', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('title', 'Meta Description',['class' => 'control-label']) !!} 
                            {!! Form::textarea('meta_description[]', null, ['class'=>'form-control', 'rows' => '3']) !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('title', 'Meta Keywords',['class' => 'control-label']) !!} 
                            {!! Form::textarea('meta_keywords[]', null, ['class' => 'form-control', 'rows' => '3']) !!}
                        </div>
                    </div>
                </div>
              @endif
            @endforeach
        @endif
    </div>
</div>