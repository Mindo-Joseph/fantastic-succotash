<div class="modal-header border-bottom">
    <h4 class="modal-title">Edit Plan</h4>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>
<form id="user_subscription_form" method="post" enctype="multipart/form-data" action="{{ route('subscription.plan.save.user', $plan->slug) }}">
    @csrf
    <div class="modal-body" >
        <div class="row">
            <div class="col-md-12">
                <div class="row mb-2">
                    <div class="col-md-12">
                        <label>Upload Image </label>
                        <input type="file" accept="image/*" data-plugins="dropify" name="image" class="dropify" data-default-file="{{ $plan->image['proxy_url'].'100/100'.$plan->image['image_path'] }}" />
                    </div> 
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('title', 'Enable',['class' => 'control-label']) !!} 
                            <div class="mt-md-1">
                                <input type="checkbox" data-plugin="switchery" name="status" class="form-control status" data-color="#43bee1" {{($plan->status == 1) ? 'checked' : ''}}>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group" id="nameInput">
                            {!! Form::label('title', 'Title',['class' => 'control-label']) !!} 
                            {!! Form::text('title', $plan->title, ['class'=>'form-control', 'required'=>'required']) !!}
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Features</label>
                            <select class="form-control select2-multiple" name="features[]" data-toggle="select2" multiple="multiple" data-placeholder="Choose ..." required="required">
                                @foreach($features as $feature)
                                    <option value="{{$feature->id}}" {{ (in_array($feature->id, $subPlanFeatures)) ? "selected" : "" }}> {{$feature->title}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Price</label>
                            <input class="form-control" type="number" name="price" min="0" value="{{ $plan->price }}" required="required">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <?php /* ?><div class="col-md-6">
                        <div class="form-group">
                            <label for="">Validity (In days)</label>
                            <input class="form-control" type="number" name="period" min="0" value="{{ $plan->period }}" required="required">
                        </div>
                    </div><?php */ ?>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Frequency</label>
                            <select class="form-control" name="frequency" value="{{ $plan->frequency }}" required="required">
                                <option value="">Choose...</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Sort Order</label>
                            <input class="form-control" type="number" name="sort_order" min="1" value="{{ $plan->sort_order }}" required="required">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('title', 'Description',['class' => 'control-label']) !!} 
                            {!! Form::textarea('description', $plan->Description, ['class' => 'form-control', 'rows' => '3']) !!}
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