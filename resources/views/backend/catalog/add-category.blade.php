<style type="text/css">
.add-category input[type="radio"] {
    display: none;
}
.add-category label{
    cursor: pointer;
}
.add-category input:checked ~ label {
    box-shadow: 0 0px 8px rgb(67 190 225 / 55%);
}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="row mb-6">
            <div class="col-sm-2">
                <input type="file" accept="image/*" data-plugins="dropify" name="icon" class="dropify" data-default-file="" />
                <p class="text-muted text-center mt-2 mb-0">Upload Category Icon</p>
            </div>
            <div class="col-sm-4">              
                <input type="file" accept="image/*" data-plugins="dropify" name="image" class="dropify" data-default-file="" />
                <p class="text-muted text-center mt-2 mb-0">Upload Category image</p>
            </div>
            <div class="col-sm-6">
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
                            <select class="selectize-select form-control parent-category" id="cateSelectBox" name="parent_cate">
                                <option value="">Select</option>
                                @foreach($parCategory as $pc)
                                    <option value="{{$pc->id}}">{{ucfirst($pc->slug)}}</option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('title', 'Visible In Menus',['class' => 'control-label']) !!} 
                            <div>
                                <input type="checkbox" data-plugin="switchery" name="is_visible" class="form-control switch_menu" data-color="#43bee1" checked='checked'>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('title', 'Wishlist',['class' => 'control-label']) !!} 
                            <div>
                                <input type="checkbox" data-plugin="switchery" name="show_wishlist" class="form-control wishlist_switch" data-color="#43bee1" checked='checked'>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3" id="addProductHide">
                        <div class="form-group">
                            {!! Form::label('title', 'Can Add Products',['class' => 'control-label']) !!} 
                            <div>
                                <input type="checkbox" data-plugin="switchery" name="can_add_products" class="form-control add_product_switch" data-color="#43bee1" checked='checked'>
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
                </div>
            </div>
        </div>
        <div class="row mt-3 add-category">
            @foreach($typeArray as $k => $type)
            <div class="col">
                  <div class="card p-0 text-center" id="tooltip-container">
                     <input class="form-check-input type-select" for="add" type="radio" id="type_id_{{$type->id}}" name="type_id" @if($category->type_id == $type->id) checked @endif value="{{$type->id}}">
                     <label for="type_id_{{$type->id}}" class="card-body p-0">
                        <div class="category-img">
                            <img src="https://www.w3schools.com/tags/img_girl.jpg" alt="">
                        </div>
                        <div class="form-check form-check-info pl-0">
                            <span for="customradio5">{{$type->title}}</span>
                        </div>
                     </label>
                  </div>
               </div>
           @endforeach
        </div>
        <div class="row">
            <div class="col-md-3" style="display:none;" id="template_type_main_div">
                <div class="form-group">
                    {!! Form::label('title', 'Template Type',['class' => 'control-label']) !!}
                    <select class="selectize-select form-control" id="templateTypeSelectBox" name="template_type_id">
                            <option value="">Select</option>
                        @foreach($dispatcher_warning_page_options as $dispatcher_warning_page_option)
                            <option value="{{$dispatcher_warning_page_option->id}}">{{$dispatcher_warning_page_option->title}}</option>
                        @endforeach
                    </select>
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="col-md-3" style="display:none;" id="warning_page_main_div">
                <div class="form-group">
                    {!! Form::label('title', 'Warning Page',['class' => 'control-label']) !!}
                    <select class="selectize-select form-control" id="warningPageSelectBox" name="warning_page_id">
                            <option value="">Select</option>
                        @foreach($dispatcher_template_type_options as $dispatcher_template_type_option)
                            <option value="{{$dispatcher_template_type_option->id}}">{{$dispatcher_template_type_option->title}}</option>
                        @endforeach
                    </select>
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="col-md-6" id="warning_page_design_main_div" style="display:none;">
                {!! Form::label('title', 'Warning Page Design',['class' => 'control-label']) !!}
                {!! Form::textarea('warning_page_design', '', ['class'=>'form-control', 'id' => 'warning_page_design', 'placeholder' => 'Description', 'rows' => '5', 'name' => 'warning_page_design']) !!}
            </div>
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