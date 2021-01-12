<div class="row">
    <div class="col-md-12 card-box">
        <h4 class="header-title mb-3"></h4>

        <div class="row mb-2">
            <div class="col-md-1"></div>
            <div class="col-md-3">
                <input type="file" data-plugins="dropify" name="logo" class="dropify" data-default-file="{{ url('storage/'.$vendor->logo) }}" />
                <p class="text-muted text-center mt-2 mb-0">Upload Logo</p>
            </div> 
            <div class="col-md-1"></div>
            <div class="col-md-6">                
                <input type="file" data-plugins="dropify" data-default-file="{{ url('storage/'.$vendor->banner) }}" name="banner" class="dropify" />
                <p class="text-muted text-center mt-2 mb-0">Upload banner image</p>
            </div>
        </div>

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
         
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('title', 'Dine In',['class' => 'control-label']) !!} 
                    <div>
                        <input type="checkbox" data-plugin="switchery" name="dine_in" class="form-control dine_in" data-color="#039cfd" @if($vendor->dine_in == 1) 'checked' @endif>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('title', 'Takeaway',['class' => 'control-label']) !!} 
                    <div>
                        <input type="checkbox" data-plugin="switchery" name="takeaway" class="form-control takeaway" data-color="#039cfd" checked='checked'>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('title', 'Delivery',['class' => 'control-label']) !!} 
                    <div>
                        <input type="checkbox" data-plugin="switchery" name="delivery" class="form-control delivery" data-color="#039cfd" checked='checked'>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group" id="descInput">
                    {!! Form::label('title', 'Description',['class' => 'control-label']) !!} 
                    {!! Form::textarea('desc', $vendor->desc, ['class' => 'form-control', 'rows' => '3']) !!}
                </div>
            </div>
        </div>
    </div>
</div>