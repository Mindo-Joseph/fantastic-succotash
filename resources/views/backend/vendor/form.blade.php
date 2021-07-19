<div class="row">
    <div class="col-md-12">
        <div class="row mb-2">
            <div class="col-md-3">
                <label>Upload Logo</label>
                <input type="file" accept="image/*" data-plugins="dropify" name="logo" class="dropify" data-default-file="{{ $vendor->logo['proxy_url'].'90/90'.$vendor->logo['image_path'] }}" />
            </div> 
            <div class="col-md-6">     
                <label>Upload banner image</label>           
                <input type="file" accept="image/*" data-plugins="dropify" data-default-file="{{$vendor->banner['proxy_url'] . '90/90' . $vendor->banner['image_path']}}" name="banner" class="dropify" />
            </div>
            <div class="col-md-3">
                <label>Uploaded Document</label>
                <div class="d-flex align-items-center justify-content-between">
                    <label>Document One</label><a class="d-block mb-1 document-btn" target="_blank" href="#"><i class="fa fa-eye float-right"></i> </a>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <label>Document Two</label><a class="d-block mb-1 document-btn" target="_blank" href="#"><i class="fa fa-eye float-right"></i> </a>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <label>Document Three</label><a class="d-block mb-1 document-btn" target="_blank" href="#"><i class="fa fa-eye float-right"></i> </a>
                </div>
                
            
            </div>
        </div>
        {!! Form::hidden('vendor_id', $vendor->id, ['class'=>'form-control']) !!}
        <div class="row">
            <div class="col-md-12">
                <div class="form-group" id="nameInput">
                    {!! Form::label('title', 'Name',['class' => 'control-label']) !!} 
                    {!! Form::text('name', $vendor->name, ['class'=>'form-control']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group" id="descInput">
                    {!! Form::label('title', 'Description',['class' => 'control-label']) !!} 
                    {!! Form::textarea('desc', $vendor->desc, ['class' => 'form-control', 'rows' => '3']) !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group" id="emailInput">
                    <label for="">Email</label>
                    {!! Form::text('email', $vendor->email, ['class'=>'form-control']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group" id="phone_noInput">
                    <label for="">Phone Number</label>
                    {!! Form::text('phone_no', $vendor->phone_no, ['class'=>'form-control']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
        </div>
        <div class="row mb-2" id="edit">
            <div class="col-md-4">
                <div class="form-group mb-3" id="addressInput">
                    {!! Form::label('title', 'Address',['class' => 'control-label']) !!} 
                    <div class="input-group">
                        <input type="text" name="address" id="edit-address" placeholder="Delhi, India" class="form-control" value="{{$vendor->address}}">
                        <div class="input-group-append">
                            <button class="btn btn-xs btn-dark waves-effect waves-light showMap" type="button" num="edit"> <i class="mdi mdi-map-marker-radius"></i></button>
                        </div>
                    </div>
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3" id="latitudeInput">
                    {!! Form::label('title', 'latitude',['class' => 'control-label']) !!} 
                    <input type="text" name="latitude" id="edit_latitude" placeholder="24.9876755" class="form-control" value="{{$vendor->latitude}}">
                    @if($errors->has('latitude'))
                    <span class="text-danger" role="alert">
                        <strong>{{ $errors->first('latitude') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3" id="longitudeInput">
                    {!! Form::label('title', 'longitude',['class' => 'control-label']) !!} 
                    <input type="text" name="longitude" id="edit_longitude" placeholder="11.9871371723" class="form-control" value="{{$vendor->longitude}}">
                    @if($errors->has('longitude'))
                    <span class="text-danger" role="alert">
                        <strong>{{ $errors->first('longitude') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Website</label>
                    {!! Form::text('website', $vendor->website, ['class'=>'form-control']) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group" @if($client_preferences->dinein_check == 0) style="display: none;" @endif >
                            {!! Form::label('title', 'Dine In',['class' => 'control-label']) !!} 
                            <div>
                                <input type="checkbox" data-plugin="switchery" name="dine_in" class="form-control dine_in" data-color="#43bee1" @if($vendor->dine_in == 1) checked @endif>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" @if($client_preferences->takeaway_check == 0) style="display: none;" @endif >
                            {!! Form::label('title', 'Takeaway',['class' => 'control-label']) !!} 
                            <div>
                                <input type="checkbox" data-plugin="switchery" name="takeaway" class="form-control takeaway" data-color="#43bee1" @if($vendor->takeaway == 1) checked @endif>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" @if($client_preferences->delivery_check == 0) style="display: none;" @endif >
                            {!! Form::label('title', 'Delivery',['class' => 'control-label']) !!} 
                            <div>
                                <input type="checkbox" data-plugin="switchery" name="delivery" class="form-control delivery" data-color="#43bee1" @if($vendor->delivery == 1) checked @endif>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>