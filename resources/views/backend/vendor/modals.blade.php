<div id="add-form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Vendor</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="save_banner_form" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body" >
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="header-title mb-3"></h4>

                            <div class="row mb-2">
                                <div class="col-md-1"></div>
                                <div class="col-md-3">
                                    <input type="file" accept="image/*" data-plugins="dropify" name="logo" class="dropify" data-default-file="" />
                                    <p class="text-muted text-center mt-2 mb-0">Upload Logo</p>
                                </div> 
                                <div class="col-md-1"></div>
                                <div class="col-md-6"> <!--  Storage::disk('s3')->url($client->logo)  -->                 
                                    <input type="file" accept="image/*" data-plugins="dropify" name="banner" class="dropify" data-default-file="" />
                                    <p class="text-muted text-center mt-2 mb-0">Upload banner image</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group" id="nameInput">
                                        {!! Form::label('title', 'Name',['class' => 'control-label']) !!} 
                                        {!! Form::text('name', null, ['class'=>'form-control']) !!}
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2" id="add">
                                <div class="col-md-4">
                                    <div class="form-group mb-3" id="addressInput">
                                        {!! Form::label('title', 'Address',['class' => 'control-label']) !!} 
                                        <div class="input-group">
                                            <input type="text" name="address" id="add-address" placeholder="Delhi, India" class="form-control">
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
                                        <input type="text" name="latitude" id="add_latitude" placeholder="24.9876755" class="form-control" value="">
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
                                        <input type="text" name="longitude" id="add_longitude" placeholder="11.9871371723" class="form-control" value="">
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
                                            <input type="checkbox" data-plugin="switchery" name="dine_in" class="form-control validity" data-color="#43bee1" checked='checked'>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('title', 'Takeaway',['class' => 'control-label']) !!} 
                                        <div>
                                            <input type="checkbox" data-plugin="switchery" name="takeaway" class="form-control validity" data-color="#43bee1" checked='checked'>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('title', 'Delivery',['class' => 'control-label']) !!} 
                                        <div>
                                            <input type="checkbox" data-plugin="switchery" name="delivery" class="form-control validity" data-color="#43bee1" checked='checked'>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group" id="descInput">
                                        {!! Form::label('title', 'Description',['class' => 'control-label']) !!} 
                                        {!! Form::textarea('desc', null, ['class' => 'form-control', 'rows' => '3']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info waves-effect waves-light submitAddForm">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="show-map-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-full-width">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">Select Location</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body p-4">
                
                <div class="row">
                    <form id="task_form" action="#" method="POST" style="width: 100%">
                        <div class="col-md-12">
                            <div id="googleMap" style="height: 500px; min-width: 500px; width:100%"></div>
                            <input type="hidden" name="lat_input" id="lat_map" value="0" />
                            <input type="hidden" name="lng_input" id="lng_map" value="0" />
                            <input type="hidden" name="for" id="map_for" value="" />
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-info waves-effect waves-light selectMapLocation">Ok</button>
                <!--<button type="Cancel" class="btn btn-info waves-effect waves-light cancelMapLocation">cancel</button>-->
            </div>
        </div>
    </div>
</div>