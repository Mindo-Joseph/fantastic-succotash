<input type="hidden" name="lc_id" id="lc_id" url="{{route('celebrity.update', $lc->id)}}">
<div class="row mb-3">
    <div class="col-md-3"></div>
    <div class="col-md-6" id="imageInput">
        <input type="file" accept="image/*" data-plugins="dropify" name="image" class="dropify" data-default-file="{{$lc->avatar['proxy_url'].'600/400'.$lc->avatar['image_path']}}" />
        <p class="text-muted text-center mt-2 mb-0">Upload banner image</p>
        <span class="invalid-feedback" role="alert">
            <strong></strong>
        </span>
    </div>
</div>
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
        <div class="form-group" id="emailInput">
            {!! Form::label('title', 'Email *',['class' => 'control-label']) !!}
            {!! Form::text('email', $lc->email, ['class' => 'form-control']) !!}
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group" id="phone_numberInput">
            {!! Form::label('title', 'Phone number',['class' => 'control-label']) !!}
            {!! Form::text('phone_number', $lc->phone_number, ['class' => 'form-control', 'placeholder' => 'Phone Number']) !!}
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group" id="addressInput">
            {!! Form::label('title', 'Address *',['class' => 'control-label']) !!}
            {!! Form::text('address', $lc->address, ['class' => 'form-control']) !!}
            <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
        </div>
    </div>

    <div class="col-md-6" id="product_list">
        <div class="form-group">
            {!! Form::label('title', 'Products',['class' => 'control-label']) !!}
            <select class="form-control select2-multiple" id="products" name="products[]" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">
                @foreach($products as $nm)
                @if(in_array($nm->id, $pros)) 
                <option value="{{$nm->id}}" selected>{{$nm->sku}}</option>
                @else
                <option value="{{$nm->id}}">{{$nm->sku}}</option>
                @endif
                @endforeach
            </select>
        </div>
    </div>

</div>