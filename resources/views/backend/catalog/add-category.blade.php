<div class="row">
    <div class="col-md-12">

        <div class="row mb-2">
            <div class="col-md-2 col-sm-4 mb-sm-0 mb-3">
                <div class="round_img">
                    <input type="file" accept="image/*" data-plugins="dropify" name="icon" class="dropify" data-default-file="" />
                </div>
                <p class="text-muted text-center mt-2 mb-0">Upload Category Icon</p>
            </div> 
            
            <div class="col-md-10 col-sm-8"> <!--  Storage::disk('s3')->url($client->logo)  -->                 
                <div class="upload_box">
                    <input type="file" accept="image/*" data-plugins="dropify" name="image" class="dropify" data-default-file="" />
                </div>
                <p class="text-muted text-center mt-2 mb-0">Upload Category image</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group" id="slugInput">
                    {!! Form::label('title', 'Slug',['class' => 'control-label']) !!} 
                    {!! Form::text('slug', null, ['class'=>'form-control', 'required' => 'required']) !!}
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
                            <option value="{{$pc->id}}">{{$pc->slug}}</option>
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
                    <select class="selectize-select form-control type-select" for="add" id="typeSelectBox" name="type_id">
                        @foreach($typeArray as $type)
                        @if($is_vendor == 1 && ($type->title === 'vendor' || $type->title === 'Vendor'))
                        @else
                            <option value="{{$type->id}}">{{$type->title}}</option>
                        @endif
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
                        <input type="checkbox" data-plugin="switchery" name="is_visible" class="form-control switch2" data-color="#43bee1" checked='checked'>
                    </div>
                </div>
            </div>
            <div class="col-md-6 text-center" id="addProductHide">
                <div class="form-group">
                    {!! Form::label('title', 'Can Add Products',['class' => 'control-label']) !!} 
                    <div>
                        <input type="checkbox" data-plugin="switchery" name="can_add_products" class="form-control switch1" data-color="#43bee1" checked='checked'>
                    </div>
                </div>
            </div>
            <div class="col-md-6" id="addDispatcherHide" style="display: none;">
                <div class="form-group">
                    {!! Form::label('title', 'Dispatcher Tags',['class' => 'control-label']) !!}
                    {!! Form::hidden('tags', null, ['class'=>'form-control myTag1']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
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

        <div class="row">
            @foreach($languages as $langs)
                <div class="col-lg-6">
                <div class="outer_box px-3 py-2 mb-3">
                <div class="row rowYK">
                    <h4 class="col-md-12"> {{ $langs->langName.' Language' }} </h4>

                    <div class="col-md-6">
                        <div class="form-group" id="{{ ($langs->langId == 1) ? 'nameInput' : 'nameotherInput' }}">
                            {!! Form::label('title', 'Name',['class' => 'control-label']) !!}
                            @if($langs->is_primary == 1)
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
                </div>
                </div>
                
            @endforeach
        </div>
    </div>
</div>