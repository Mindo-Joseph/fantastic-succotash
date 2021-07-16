<div class="modal-header border-bottom">
    <h4 class="modal-title">Edit Plan</h4>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>
<form id="vendor_subscription_form" method="post" enctype="multipart/form-data" action="{{ route('subscriptions.saveVendorSubscription', $subscription->slug) }}">
    @csrf
    <div class="modal-body" >
        <div class="row">
            <div class="col-md-12">
                <div class="row mb-2">
                    <div class="col-md-12">
                        <label>Upload Image</label>
                        <input type="file" accept="image/*" data-plugins="dropify" name="image" class="dropify" data-default-file="{{ $subscription->image['proxy_url'].'100/100'.$subscription->image['image_path'] }}" />
                    </div> 
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('title', 'Enable',['class' => 'control-label']) !!} 
                            <div class="mt-md-1">
                                <input type="checkbox" data-plugin="switchery" name="status" class="form-control status" data-color="#43bee1" {{($subscription->status == 1) ? 'checked' : ''}}>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('title', 'On Request',['class' => 'control-label']) !!} 
                            <div class="mt-md-1">
                                <input type="checkbox" data-plugin="switchery" name="on_request" class="form-control on_request" data-color="#43bee1" {{($subscription->on_request == 1) ? 'checked' : ''}}>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group" id="nameInput">
                            {!! Form::label('title', 'Title',['class' => 'control-label']) !!} 
                            {!! Form::text('title', $subscription->title, ['class'=>'form-control']) !!}
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="">Features</label>
                            <select class="form-control select2-multiple" name="features[]" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">
                                @foreach($features as $feature)
                                    <option value="{{$feature->id}}" {{ (in_array($feature->id, $subFeatures)) ? "selected" : "" }}> {{$feature->title}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Price</label>
                            <input class="form-control" type="number" name="price" min="0" value="{{ $subscription->price }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Validity</label>
                            <select class="form-control" name="validity" data-placeholder="Choose ...">
                                @foreach($validities as $val)
                                    <option value="{{$val->id}}" {{ (isset($subscription->validity_id) && ($val->id == $subscription->validity_id)) ? "selected" : "" }}> {{$val->name}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('title', 'Description',['class' => 'control-label']) !!} 
                            {!! Form::textarea('description', $subscription->Description, ['class' => 'form-control', 'rows' => '3']) !!}
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