<div class="row mb-2">
    <div class="col-md-6">
        <div class="form-group" id="nameInput">
            {!! Form::label('title', 'Name',['class' => 'control-label']) !!}
            {!! Form::text('name', $lc->name, ['class' => 'form-control', 'placeholder' => 'Name']) !!}
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group" id="minimum_pointsInput">
            {!! Form::label('title', 'Minimum Points to reach this level *',['class' => 'control-label']) !!}
            {!! Form::text('minimum_points', $lc->minimum_points, ['class' => 'form-control']) !!}
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group" id="descriptionInput">
            {!! Form::label('title', 'Description *',['class' => 'control-label']) !!}
            {!! Form::textarea('description', $lc->description, ['class' => 'form-control', 'rows' => '3']) !!}
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
    </div>
</div>
<br>
<h5>Earnings</h5>
<br>
<input type="hidden" name="lc_id" id="lc_id" url="{{route('loyalty.update', $lc->id)}}">
<div class="form-group" id="per_order_pointsInput">
    {!! Form::label('title', 'Earnings Per Order*',['class' => 'control-label']) !!}
    {!! Form::text('per_order_points', $lc->per_order_points, ['class' => 'form-control']) !!}
    <span class="invalid-feedback" role="alert">
        <strong></strong>
    </span>
</div>
<br>
<label for="purchase">Order Amount to earn 1 {{getNomenclatureName('Loyalty Cards', false)}} point (as per primary currency)</label>
<div class="row mb-2">
    <div class="col-md-6">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">1 {{getNomenclatureName('Loyalty Cards', false)}} Point </span>
                </div>
                <input type="text" onkeypress="return isNumberKey(event);" class="form-control"  value="{{$lc->amount_per_loyalty_point}}" name="amount_per_loyalty_point" id="amount_per_loyalty_point" placeholder="Value" aria-label="Username" aria-describedby="basic-addon1">
            </div>
        </div>
    </div>
</div>

