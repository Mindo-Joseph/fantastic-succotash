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

                <?php $action = '';
                if(isset($promo->id) && $promo->id > 0){
                    $action = 'Edit';
                }
                ?>

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
                    {!! Form::label('title', 'Promo Type',['class' => 'control-label']) !!}
                    <select class="selectize-select form-control promoTypeField" name="promo_type_id">
                        <option selected>select..</option>
                        @foreach($promoTypes as $key => $types)
                        <option value="{{$types->id}}" @if(isset($promo->id) && $promo->id > 0 && $types->id == $promo->promo_type_id) selected @endif >{{$types->title}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <?php 
                $pricevalue = (isset($promo->id) && $promo->id > 0 && $promo->promo_type_id == 1) ? (int)$promo->amount : $promo->amount; ?>
                <div class="form-group" id="amountInput">
                    {!! Form::label('title', 'Amount',['class' => 'control-label']) !!}
                    {!! Form::number('amount', $pricevalue, ['class' => 'form-control amountInputField', 'id' => 'amountInputField', 'placeholder'=>'Enter total amount', 'max' => "10000", 'min' => "1", "onKeyPress" => "return check(event,value)", "onInput" => "checkLength()"]) !!}
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
                        {!! Form::label('title', 'Expiry Date',['class' => 'control-label']) !!}
                        {!! Form::text('expiry_date', $promo->expiry_date, ['class' => 'form-control downside datetime-datepicker', 'id' => 'start-datepicker', 'min' => $minDate]) !!}

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
                        <input type="checkbox" data-plugin="switchery" name="allow_free_delivery" class="form-control switch1{{$action}}" data-color="#039cfd" @if(isset($promo->id) && $promo->id > 0 && $promo->allow_free_delivery == 1) checked @endif>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="form-group">
                    {!! Form::label('title', 'First Order Only',['class' => 'control-label']) !!}
                    <div>
                        <input type="checkbox" data-plugin="switchery" name="first_order_only" class="form-control switch2{{$action}}" data-color="#039cfd" @if(isset($promo->id) && $promo->id > 0 && $promo->first_order_only == 1) checked @endif>
                    </div>
                </div>
            </div>
            <input type="hidden" id="promocode_id" url="{{ (isset($promo->id) && $promo->id > 0) ? route('promocode.update', $promo->id) : route('promocode.store') }}">
            <div class="col-md-4 text-center">
                <div class="form-group">
                    {!! Form::label('title', 'Paid By',['class' => 'control-label']) !!}
                    <div>
                        <div class="radio radio-info form-check-inline">
                            <input type="radio" id="inlineRadio1" value="1" name="radioInline" checked>
                            <label for="inlineRadio1"> Admin</label>
                        </div>
                        <div class="radio form-check-inline">
                            <input type="radio" id="inlineRadio2" value="0" name="radioInline">
                            <label for="inlineRadio2"> Vendor</label>
                        </div>
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
                    {!! Form::text('limit_total', $promo->limit_total, ['class' => 'form-control']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('title', 'Apply Restriction On',['class' => 'control-label']) !!}
                    <select class="selectize-select form-control inlineRadioOptions" name="inlineRadioOptions" for="{{(isset($promo->id) && $promo->id > 0) ? 'edit' : 'add'}}">
                        <option value=''>select..</option>
                        <option value='0' @if($restrictionType == 0) selected @endif>Products</option>
                        <option value='1' @if($restrictionType == 1) selected @endif>Vendors</option>
                        <option value='2' @if($restrictionType == 2) selected @endif>Categories</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('title', 'Restriction Type',['class' => 'control-label']) !!}
                    <select class="selectize-select form-control" name="applied_type">
                        <option value='include' @if($include == 1) selected @endif>Include</option>
                        <option value='exclude' @if($exclude == 1) selected @endif>Exclude</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6" style="{{($restrictionType == 0) ? '' : 'display: none;'}}" id="productsList">
                <div class="form-group">
                    {!! Form::label('title', 'Products',['class' => 'control-label']) !!}
                    <select class="form-control select2-multiple" id="IncludeProduct" name="productList[]" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">

                        @foreach($products as $sk)
                        <option value="{{$sk->id}}" @if($restrictionType == 0 && in_array($sk->id, $dataIds)) selected @endif>{{$sk->sku}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6" style="{{($restrictionType == 1) ? '' : 'display: none;'}}" id="vendorsList">
                <div class="form-group">
                    {!! Form::label('title', 'Vendors',['class' => 'control-label']) !!}
                    <select class="form-control select2-multiple" id="IncludeVendor" name="vendorList[]" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">
                        @foreach($vendors as $nm)
                        <option value="{{$nm->id}}" @if($restrictionType == 1 && in_array($nm->id, $dataIds)) selected @endif>{{$nm->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6" style="{{($restrictionType == 2) ? '' : 'display: none;'}}" id="categoriesList">
                <div class="form-group">
                    {!! Form::label('title', 'Category',['class' => 'control-label']) !!}
                    <select class="form-control select2-multiple" id="IncludeCategory" name="categoryList[]" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">
                        @foreach($categories as $slu)
                        <option value="{{$slu->id}}" @if($restrictionType == 2 && in_array($slu->id, $dataIds)) selected @endif>{{$slu->slug}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>



        <!-- {!! Form::label('title', ' Types ',['class' => 'control-label']) !!}
        <div class="form-group">
            <div class="form-check form-check-inline">
                <input class="form-check-input" onclick="myfunction('categoriesList')" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="2">
                <label class="form-check-label" for="inlineRadio1">Categories</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" onclick="myfunction('vendorsList')" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="1">
                <label class="form-check-label" for="inlineRadio2">Vendors</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" onclick="myfunction('productsList')" type="radio" name="inlineRadioOptions" id="inlineRadio3" value="0">
                <label class="form-check-label" for="inlineRadio3">Products</label>
            </div>
        </div> -->



        <!-- <div class="row" style="display: none;" id="productsList" >
            <div class="col-md-6">
                <div class="form-group">
                    <label for="product"> Include Products</label>
                    <select class="form-control select2-multiple" id="IncludeProduct" name="includeProducts[]" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">
                        @foreach($products as $sk)
                        <option value="{{$sk->id}}">{{$sk->sku}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="product"> Exclude Products</label>
                    <select class="form-control select2-multiple" id="ExcludeProduct" name="excludeProducts[]" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">
                        @foreach($products as $sk)
                        <option value="{{$sk->id}}">{{$sk->sku}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row" style="display: none;" id="vendorsList">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="product">Include Vendors</label>
                    <select class="form-control select2-multiple" id="IncludeVendor" name="includeVendors[]" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">
                        @foreach($vendors as $nm)
                        <option value="{{$nm->id}}">{{$nm->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="product"> Exclude Vendors</label>
                    <select class="form-control select2-multiple" id="ExcludeVendor" name="excludeVendors[]" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">
                        @foreach($vendors as $nm)
                        <option value="{{$nm->id}}">{{$nm->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row" style="display: none;" id="categoriesList">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="product">Include Categories</label>
                    <select class="form-control select2-multiple" id="IncludeCategory" name="includeCategories[]" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">
                        @foreach($categories as $slu)
                        <option value="{{$slu->id}}">{{$slu->slug}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="product">Exclude Categories</label>
                    <select class="form-control select2-multiple" id="ExcludeCategory" name="excludeCategories[]" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">
                        @foreach($categories as $slu)
                        <option value="{{$slu->id}}">{{$slu->slug}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div> -->

    </div>
</div>