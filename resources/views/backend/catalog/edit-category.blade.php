
<div class="row ">
    <div class="col-md-12">
        <div class="row mb-6">
            <div class="col-sm-2">
                <input type="file" accept="image/*" data-plugins="dropify" name="icon" class="dropify" data-default-file="{{$category->icon['proxy_url'].'400/400'.$category->icon['image_path']}}" />
                <p class="text-muted text-center mt-2 mb-0">Upload Category Icon </p>
            </div> 
            <div class="col-sm-4">                
                <input type="file" accept="image/*" data-plugins="dropify" name="image" class="dropify" data-default-file="{{$category->image['proxy_url'].'400/400'.$category->image['image_path']}}" />
                <p class="text-muted text-center mt-2 mb-0">Upload Category image</p>
            </div>
            <div class="col-md-6">
                 <div class="row">
                    <div class="col-md-6">
                         <div class="form-group" id="slugInputEdit">
                            {!! Form::label('title', 'Slug',['class' => 'control-label']) !!} 
                            {!! Form::text('slug', $category->slug, ['class'=>'form-control','id' => 'slug', 'onkeypress' => "return alphaNumeric(event)"]) !!}
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
                            <select class="selectize-select1 form-control parent-category" id="cateSelectBox" name="parent_cate">
                                @foreach($parCategory as $pc)
                                    <option value="{{$pc->id}}" {{ ($pc->id == $category->parent_id) ? 'selected' : '' }} > {{ucfirst($pc->translation_one['name'])}}</option>
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
                                @if($category->is_visible == '1')
                                    <input type="checkbox" data-plugin="switchery" name="is_visible" class="form-control edit-switch_menu" data-color="#43bee1" checked='checked'>
                                @else
                                    <input type="checkbox" data-plugin="switchery" name="is_visible" class="form-control edit-switch_menu" data-color="#43bee1">
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">                
                        <div class="form-group">
                            {!! Form::label('title', 'Show Wishlist',['class' => 'control-label']) !!} 
                            <div>
                                @if($category->show_wishlist == '1')
                                    <input type="checkbox" data-plugin="switchery" name="show_wishlist" class="form-control edit-wishlist_switch" data-color="#43bee1" checked='checked'>
                                @else
                                    <input type="checkbox" data-plugin="switchery" name="show_wishlist" class="form-control edit-wishlist_switch" data-color="#43bee1">
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3" style="{{($category->type_id != 1) ? 'display:none;' : ''}}" id="editProductHide">
                        <div class="form-group">
                            {!! Form::label('title', 'Can Add Products',['class' => 'control-label']) !!} 
                            <div>
                                @if($category->can_add_products == '1')
                                    <input type="checkbox" data-plugin="switchery" class="form-control edit-add_product_switch" data-color="#43bee1" name="can_add_products" checked='checked'>
                                @else
                                    <input type="checkbox" data-plugin="switchery" class="form-control edit-add_product_switch" data-color="#43bee1" name="can_add_products">
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6" style="{{ ($category->type_id != 2) ? 'display:none;' : '' }}" id="editDispatcherHide">
                        <div class="form-group mb-0">
                          
                        </div>
                    </div>
                </div>
            </div>
        </div> 
        <div class="row mt-3 edit-category catalog-cols">
            @foreach($typeArray as $type)
                @if($type->title == 'Celebrity' && $preference->celebrity_check == 0)
                    @continue
                @endif
                @if($type->title == 'Pickup/Delivery' && $preference->takeaway_check == 0)
                    @continue
                @endif
               <div class="col-sm-3 col-md-4">
                  <div class="card p-0 text-center select-category" id="tooltip-container">
                     <input class="form-check-input type-select" for="edit" type="radio" id="type_id_{{$type->id}}" name="type_id" @if($category->type_id == $type->id) checked @endif value="{{$type->id}}">
                     <label for="type_id_{{$type->id}}" class="card-body p-0 mb-0">
                        <div class="category-img">
                            <img src="{{url('images/'.$type->image)}}" alt="">
                        </div>
                        <div class="form-check form-check-info p-2">
                            <h6 class="mt-0" for="customradio5">{{$type->title}}</h6>
                        </div>
                     </label>
                  </div>
               </div>
           @endforeach
        </div>
        <input type="hidden" id="cateId" url="{{route('category.update', $category->id)}}">
        <div class="row">
            <div class="col-md-4" id="template_type_main_div" style="display:none;">
                <div class="form-group">
                    {!! Form::label('title', 'Warning Page',['class' => 'control-label']) !!}
                    <div class="row">
                        @foreach($dispatcher_warning_page_options as $dwpo => $dispatcher_warning_page_option)                       
                            <div class="col-lg-6 custom-radio radio_new mt-2">
                                @if($category->warning_page_id)
                                    <input type="radio" 
                                    value="{{$dispatcher_warning_page_option->id}}" id="dispatcher_warning_page_option_{{$dispatcher_warning_page_option->id}}" 
                                    name="warning_page_id" 
                                    class="custom-control-input tab_bar_options" {{ ($category->warning_page_id == $dispatcher_warning_page_option->id) ? 'checked' : '' }}>
                                @else
                                    <input {{$dwpo == 0 ? 'checked' : '' }} type="radio" 
                                    value="{{$dispatcher_warning_page_option->id}}" 
                                    id="dispatcher_warning_page_option_{{$dispatcher_warning_page_option->id}}" 
                                    name="warning_page_id" 
                                    class="custom-control-input tab_bar_options">
                                @endif
                                <label class="custom-control-label" for="dispatcher_warning_page_option_{{$dispatcher_warning_page_option->id}}">
                                    <img class="card-img-top img-fluid" src="{{asset('images/'.$dispatcher_warning_page_option->image_path)}}" alt="Card image cap">
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-4" id="warning_page_main_div" style="display:none;">
                <div class="form-group">
                    {!! Form::label('title', 'Template Type',['class' => 'control-label']) !!}
                    <div class="row">
                        @foreach($dispatcher_template_type_options as $dtto => $dispatcher_template_type_option)
                            <div class="col-lg-6 custom-radio radio_new mt-2">
                                @if($category->template_type_id)
                                    <input type="radio" value="{{$dispatcher_template_type_option->id}}" id="dispatcher_template_type_option_{{$dispatcher_template_type_option->id}}" name="template_type_id" class="custom-control-input tab_bar_options" {{ ($category->template_type_id == $dispatcher_template_type_option->id) ? 'checked' : '' }}>
                                @else
                                    <input type="radio" value="{{$dispatcher_template_type_option->id}}" id="dispatcher_template_type_option_{{$dispatcher_template_type_option->id}}" name="template_type_id" {{ ($dtto == 0) ? 'checked' : '' }} class="custom-control-input tab_bar_options">
                                @endif
                                <label class="custom-control-label" for="dispatcher_template_type_option_{{$dispatcher_template_type_option->id}}">
                                    <img class="card-img-top img-fluid" src="{{asset('images/'.$dispatcher_template_type_option->image_path)}}" alt="Card image cap">
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
        </div>
        <div class="row">
            @foreach($category->translation as $trans)
                <div class="col-lg-6">
                    <div class="outer_box px-3 py-2 mb-3">
                        <div class="row rowYK">
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
                    </div>
                </div>                
            @endforeach
            @if(count($langIds) !=  count($existlangs))
                @foreach($languages as $langs)
                @if(!in_array($langs->langId, $existlangs) && in_array($langs->langId, $langIds))
                <div class="col-lg-6">
                    <div class="outer_box px-3 py-2 mb-3">
                        <div class="row rowYK">
                            <h4 class="col-md-12"> {{ $langs->langName.' Language' }} </h4>
                            <div class="col-md-6">
                                <div class="form-group" id="{{ ($langs->is_primary == 1) ? 'nameInputEdit' : 'nameotherInput' }}">
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
                @endif
                @endforeach   
            @endif
        </div>    
    </div>
</div>