<div class="row">
    <div class="col-md-12 card-box">
        <h4 class="header-title mb-3"></h4>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group" id="nameInput">
                    {!! Form::label('title', 'Promocode ',['class' => 'control-label']) !!}
                    {!! Form::text('name', $promo->name, ['class' => 'form-control', 'placeholder'=>'Enter promocode']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>

                <!-- <div class="form-group">
                    <label>PromoCode</label>
                    <input type="text" class="form-control" name="name" id="inputRoleName" placeholder="Enter promocode">
                    @error('name')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div> -->
                <!-- <div class="form-group" id="nameInput">
                    {!! Form::label('title', 'PromoCode',['class' => 'control-label']) !!}
                    {!! Form::text('code', $promo->code, ['class' => 'form-control']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div> -->
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <!-- {!! Form::label('title', 'Promo Type',['class' => 'control-label']) !!} -->
                    <p class="font-weight-bold">Promo Types</p>
                    <select class="selectize-select form-control" name="promo_type_id">
                        <option selected>select..</option>
                        @foreach($promoTypes as $key => $types)
                        <option value="{{$types->id}}">{{$types->title}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group" id="amountInput">
                    {!! Form::label('title', 'Amount',['class' => 'control-label']) !!}
                    <!-- {!! Form::number('amount', $promo->amount, ['class' => 'form-control', 'placeholder'=>'Enter total amount']) !!} -->
                    <input class="form-control" id="example-number" type="number" name="amount">
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="">
                    <div class="form-group" id="start_date_timeInput">
                        @php
                        $minDate = Date('Y-m-d');
                        @endphp
                        {!! Form::label('title', 'Start Date',['class' => 'control-label']) !!}
                        {!! Form::text('expity_date', $promo->expity_date, ['class' => 'form-control downside datetime-datepicker', 'id' => 'start-datepicker', 'min' => $minDate]) !!}

                        <span class="invalid-feedback" role="alert">
                            <input type="hidden" name="promo_id" value="{{isset($promo->id) ? $promo->id : ''}}">
                            <strong></strong>
                        </span>
                    </div>
                </div>

            </div>

        </div>
        <div class="row">
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
                        <input type="checkbox" data-plugin="switchery" name="first_order" class="form-control switch2" data-color="#039cfd" checked='checked'>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="form-group">
                    {!! Form::label('title', 'Paid By',['class' => 'control-label']) !!} <br><br>
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
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group" id="minimum_amountInput">
                    {!! Form::label('title', 'Minimum Amount',['class' => 'control-label']) !!}
                    {!! Form::text('minimum_spend', $promo->minimum_spend, ['class' => 'form-control']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group" id="maximum_amountInput">
                    {!! Form::label('title', 'Maximum Amount',['class' => 'control-label']) !!}
                    {!! Form::text('maximum_spend', $promo->maximum_spend, ['class' => 'form-control']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
        </div>
        <div class="row">
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
</div>
</div>