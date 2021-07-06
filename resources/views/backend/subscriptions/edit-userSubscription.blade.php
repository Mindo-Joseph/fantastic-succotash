<div class="modal-header">
    <h4 class="modal-title">Edit Plan</h4>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>
<form id="user_subscription_form" method="post" enctype="multipart/form-data" action="{{ route('subscriptions.updateUserSubscription') }}">
    @csrf
    <div class="modal-body" >
        <div class="row">
            <div class="col-md-12">
                <div class="row mb-2">
                    <div class="col-md-12">
                        <input type="file" accept="image/*" data-plugins="dropify" name="image" class="dropify" data-default-file="" />
                        <p class="text-muted text-center mt-2 mb-0">Upload Image</p>
                    </div> 
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('title', 'Enable',['class' => 'control-label']) !!} 
                            <div class="mt-md-1">
                                <input type="checkbox" data-plugin="switchery" name="status" class="form-control validity" data-color="#43bee1" checked='checked'>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" id="nameInput">
                            {!! Form::label('title', 'Title',['class' => 'control-label']) !!} 
                            {!! Form::text('title', null, ['class'=>'form-control']) !!}
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Features</label>
                            <select class="form-control select2-multiple" name="features[]" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">
                                @foreach($features as $feature)
                                    <option value="{{$feature->id}}" {{ (isset($sub->feature_id) && ($feature->id == $sub->feature_id)) ? "selected" : "" }}> {{$feature->title}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Price</label>
                            <input class="form-control" type="number" name="price" min="0">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Validity</label>
                            <select class="form-control" name="validity" data-placeholder="Choose ...">
                                @foreach($validities as $val)
                                    <option value="{{$val->id}}" {{ (isset($sub->validity_id) && ($val->id == $sub->validity_id)) ? "selected" : "" }}> {{$val->name}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group" id="descInput">
                            {!! Form::label('title', 'Description',['class' => 'control-label']) !!} 
                            {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => '3']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-info waves-effect waves-light submitAddSubscriptionForm">Submit</button>
    </div>
</form>