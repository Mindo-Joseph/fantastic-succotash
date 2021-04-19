<div class="row">
    <div class="col-md-12 card-box">
        <h4 class="header-title mb-3"></h4>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group" id="nameInput">
                    {!! Form::label('title', 'PromoCode',['class' => 'control-label']) !!}
                    {!! Form::text('code', $promo->code, ['class' => 'form-control']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group" id="nameInput">
                    {!! Form::label('title', 'PromoCode',['class' => 'control-label']) !!}
                    {!! Form::text('name', $promo->name, ['class' => 'form-control']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('title', 'Promo Type',['class' => 'control-label']) !!}
                    <select class="selectize-select form-control" name="vendor_id">
                        <option value="">Select</option>
                        @foreach($promoTypes as $key => $types)
                            <option value="{{$types->id}}">{{$types->title}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group" id="amountInput">
                    {!! Form::label('title', 'Amount',['class' => 'control-label']) !!}
                    {!! Form::number('amount', $promo->amount, ['class' => 'form-control', 'placeholder'=>'Enter total amount']) !!}
                    <input class="form-control" id="example-number" type="number" name="" >
                    @error('amount')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group" id="expiry_dateInput">
                    {!! Form::label('title', 'Expiry Date',['class' => 'control-label']) !!}
                    {!! Form::text('expiry_date', $promo->expiry_date, ['class' => 'form-control']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="form-group">
                    {!! Form::label('title', 'Allow Free Delivery',['class' => 'control-label']) !!} 
                    <div>
                        <input type="checkbox" data-plugin="switchery" name="free_delivery" class="form-control switch1" data-color="#039cfd" checked='checked'>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="form-group">
                    {!! Form::label('title', 'First Order Only',['class' => 'control-label']) !!} 
                    <div>
                        <input type="checkbox" data-plugin="switchery" name="first_order" class="form-control switch1" data-color="#039cfd" checked='checked'>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="form-group">
                    {!! Form::label('title', 'Paid By',['class' => 'control-label']) !!} 
                    <div class="radio radio-info form-check-inline">
                        <input type="radio" id="inlineRadio1" value="admin" name="radioInline" checked>
                        <label for="inlineRadio1"> Admin</label>
                    </div>
                    <div class="radio form-check-inline">
                        <input type="radio" id="inlineRadio2" value="vendor" name="radioInline">
                        <label for="inlineRadio2"> Vendor</label>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group" id="minimum_amountInput">
                    {!! Form::label('title', 'Minimum Amount',['class' => 'control-label']) !!}
                    {!! Form::text('minimum_amount', $promo->minimum_amount, ['class' => 'form-control']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group" id="maximum_amountInput">
                    {!! Form::label('title', 'Maximum Amount',['class' => 'control-label']) !!}
                    {!! Form::text('maximum_amount', $promo->maximum_amount, ['class' => 'form-control']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group" id="limit_per_userInput">
                    {!! Form::label('title', 'Limit Per User',['class' => 'control-label']) !!}
                    {!! Form::text('limit_per_user', $promo->limit_per_user, ['class' => 'form-control']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group" id="total_limitInput">
                    {!! Form::label('title', 'Total Limit',['class' => 'control-label']) !!}
                    {!! Form::text('total_limit', $promo->total_limit, ['class' => 'form-control']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>

            







    </div>
</div>





<!-- 

<div class="form-row">
    <div class="form-group col-md-6">
        <label for="RoleName"></label>
        <input type="text" class="form-control" name="name" id="inputRoleName" placeholder="Enter promocode">
        @error('name')
            <span class="text-danger">{{$message}}</span>
        @enderror
    </div>
    

    <div class="form-group mb-3 col-md-6">
        
    </div>
    <div class="form-group mb-3 col-md-6">
        <label></label>
        <input type="text" id="humanfd-datepicker" name="expiry_date" class="form-control" placeholder="October 9, 2018">
        @error('expiry_date')
        <span class="text-danger">{{$message}}</span>
        @enderror
    </div>

    <div class="form-group mb-3 col-md-6">
        <label for="">Allow Free Delivery</label> <br>
        <input type="checkbox" checked data-plugin="switchery" name="free_delivery" data-color="#039cfd" />
        @error('free_delivery')
        <span class="text-danger">{{$message}}</span>
        @enderror
    </div>

    <div class="form-group mb-3 col-md-6">
        <label for="">First Order Only</label> <br>
        <input type="checkbox" checked data-plugin="switchery" name="first_order" data-color="#039cfd" />
        @error('first_order')
        <span class="text-danger">{{$message}}</span>
        @enderror
    </div>


    <div class="form-group mb-6 col-md-6">
        <label for="example-number">Minimum Amount</label>
        <input class="form-control" id="example-number" type="number" name="minimum_amount" placeholder="Enter Minimum Amount">
        @error('minimum_amount')
        <span class="text-danger">{{$message}}</span>
        @enderror
    </div>


    <div class="form-group mb-6 col-md-6">
        <label for="example-number">Maximum Amount</label>
        <input class="form-control" id="example-number1" type="number" name="maximum_amount" placeholder="Enter Maximum Amount">
        @error('maximum_amount')
        <span class="text-danger">{{$message}}</span>
        @enderror
    </div>
    <div class="form-group mb-3 col-md-6">
        <label>Limit Per Users</label>
        <input class="form-control" id="example-number" type="number" name="limit_per_user" placeholder="Enter limit per users">
        @error('limit_per_user')
        <span class="text-danger">{{$message}}</span>
        @enderror
    </div>

    <div class="form-group  col-md-6">
        <label>Total Limit</label>
        <input class="form-control" id="example-number" type="number" name="total_limit" placeholder="Enter total limits">
        @error('total_limit')
        <span class="text-danger">{{$message}}</span>
        @enderror
    </div>

    
    <div class="form-group col-md-6">
        <p class="font-weight-bold text-muted">Restriction Types</p>
        <select class="form-control" name="restriction_types" data-toggle="select2">
            <option>Select</option>
            <option value="0">Product</option>
            <option value="1">Vendor</option>
            <option value="2">Category</option>
        </select>
        @error('restriction_types')
        <span class="text-danger">{{$message}}</span>
        @enderror
    </div>
</div> -->