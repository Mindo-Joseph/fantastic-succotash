<div class="row">
    <div class="col-md-12 card-box">

        <div class="row mb-2">
            <div class="col-md-3">
                <input type="file" data-plugins="dropify" name="icon" class="dropify" data-default-file="" />
                <p class="text-muted text-center mt-2 mb-0">Upload Category Icon</p>
            </div> <div class="col-md-2"></div>
            <div class="col-md-6"> <!--  Storage::disk('s3')->url($client->logo)  -->                 
                <input type="file" data-plugins="dropify" name="image" class="dropify" data-default-file="" />
                <p class="text-muted text-center mt-2 mb-0">Upload Category image</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group" id="slugInput">
                    {!! Form::label('title', 'Slug',['class' => 'control-label']) !!} 
                    {!! Form::text('slug', null, ['class'=>'form-control']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                    {!! Form::hidden('login_user_type', session('login_user_type'), ['class'=>'form-control']) !!}
                    {!! Form::hidden('login_user_id', auth()->user()->id, ['class'=>'form-control']) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('title', 'Select Parent Category',['class' => 'control-label']) !!}
                    <select class="selectize-select form-control" id="cateSelectBox" name="parent_cate">
                        <option value="">Select</option>
                        @foreach($parCategory as $pc)
                            <option value="{{$pc->id}}">{{$pc->name}}</option>
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
                    <select class="selectize-select form-control type-select" for="add" id="typeSelectBox" name="type_id">
                        @foreach($typeArray as $type)
                        <option value="{{$type->id}}">{{$type->title}}</option>
                        @endforeach
                    </select>
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="col-md-3 text-center">
                <div class="form-group">
                    {!! Form::label('title', 'Visible In Menus',['class' => 'control-label']) !!} 
                    <div>
                        <input type="checkbox" data-plugin="switchery" name="is_visible" class="form-control switch2" data-color="#039cfd" checked='checked'>
                    </div>
                </div>
            </div>
            <div class="col-md-3 text-center" id="addProductHide">
                <div class="form-group">
                    {!! Form::label('title', 'Can Add Products',['class' => 'control-label']) !!} 
                    <div>
                        <input type="checkbox" data-plugin="switchery" name="can_add_products" class="form-control switch1" data-color="#039cfd" checked='checked'>
                    </div>
                </div>
            </div>
            <!--<div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('title', 'Display Mode',['class' => 'control-label']) !!}
                    <select class="selectize-select form-control" name="display_mode">
                        <option value="1">Show Product only</option>
                        <option value="2">Show Product With Description</option>
                    </select>
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div> -->
        </div>
        @foreach($languages as $langs)
            <div class="row rowYK" style="border: 2px dashed #d2d0cd;">
                <h4 class="col-md-12"> {{ ($langs->langId == 1) ? 'Default Language(English)' : $langs->langName.' Language' }} </h4>

                <div class="col-md-6">
                    <div class="form-group" id="{{ ($langs->langId == 1) ? 'nameInput' : 'nameotherInput' }}">
                        {!! Form::label('title', 'Name',['class' => 'control-label']) !!}
                        {!! Form::text('name[]', null, ['class' => 'form-control']) !!}
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
        @endforeach

    </div>
</div>