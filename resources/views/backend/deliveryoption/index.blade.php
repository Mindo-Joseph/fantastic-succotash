@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Delivery Options'])

@section('content')

<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <!-- <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Payment Options</h4>
            </div>
        </div> -->
        <div class="col-12">
            <div class="text-sm-left">
                @if (\Session::has('success'))
                <div class="alert mt-2 mb-0 alert-success">
                    <span>{!! \Session::get('success') !!}</span>
                </div>
                @endif
                @if ( ($errors) && (count($errors) > 0) )
                <div class="alert mt-2 mb-0 alert-danger">
                    <ul class="m-0">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>
    </div>

    <form method="POST" id="payment_option_form" action="{{route('deliveryoption.store')}}">
        @csrf
        @method('POST')
        <div class="row align-items-center">
            <div class="col-sm-8">
                <div class="text-sm-left">
                    <div class="page-title-box">
                        <h4 class="page-title">{{ __("Delivery Options") }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 text-right">
                <button class="btn btn-info waves-effect waves-light save_btn" type="submit"> {{ __("Save") }}</button>
            </div>
        </div>
        
        <div class="row">
            @if($delOption)
            <div class="col-md-4 mb-3">

                <input type="hidden" name="method_id" id="{{$delOption->id}}" value="{{base64_encode($delOption->id)}}">
                <input type="hidden" name="method_name" id="{{$delOption->code}}" value="{{$delOption->code}}">

                <?php
                $creds = json_decode($delOption->credentials);
                $api_key = (isset($creds->api_key)) ? $creds->api_key : '';
                $secret_key = (isset($creds->secret_key)) ? $creds->secret_key : '';
                $country_key = (isset($creds->country_key)) ? $creds->country_key : '';
                $country_region = (isset($creds->country_region)) ? $creds->country_region : '';
                $locale_key = (isset($creds->locale_key)) ? $creds->locale_key : '';
                $service_type = (isset($creds->service_type)) ? $creds->service_type : '';

                $base_price = (isset($creds->base_price)) ? $creds->base_price : '';
                $distance = (isset($creds->distance)) ? $creds->distance : '';
                $amount_per_km = (isset($creds->amount_per_km)) ? $creds->amount_per_km : '';
                ?>

                <div class="card-box h-100">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h4 class="header-title mb-0">{{$delOption->title}}</h4>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group mb-0 switchery-demo">
                                <label for="" class="mr-3">{{ __("Enable") }}</label>
                                <input type="checkbox"  data-title="{{$delOption->code}}" data-plugin="switchery" name="active" class="chk_box all_select" data-color="#43bee1" @if($delOption->status == 1) checked @endif>
                            </div>
                        </div>
                       
                        <div class="col-6">
                            <div class="form-group mb-0 switchery-demo">
                                <label for="" class="mr-3">{{ __('Sandbox') }}</label>
                                <input type="checkbox"  data-title="{{$delOption->code}}" data-plugin="switchery" name="sandbox" class="chk_box" data-color="#43bee1" @if($delOption->test_mode == 1) checked @endif>
                            </div>
                        </div>
                       
                    </div>

                    @if ( (strtolower($delOption->code) == 'lalamove'))
                    <div id="lalamove_fields_wrapper" @if($delOption->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <label for="lalamove_api_key" class="mr-3">{{ __("Api key") }}</label>
                                    <input type="text" name="api_key" id="lalamove_api_key" class="form-control" value="{{$api_key}}" @if($delOption->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <label for="lalamove_secret_key" class="mr-3">{{ __("Secret key") }}</label>
                                    <input type="text" name="secret_key" id="lalamove_secret_key" class="form-control" value="{{$secret_key}}" @if($delOption->status == 1) required @endif>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <label for="lalamove_country_key class="mr-3">{{ __("Country") }}</label>

                                    <select name="country_key" class="form-control" id="lalamove_country_key" @if($delOption->status == 1) required @endif>
                                    <option value="">{{ __("Please Select Country") }}</option>    
                                    <option value="MY" {{(($country_key == 'MY')?'Selected':'')}}>Malaysia</option>    
                                    {{-- <option value="MX" {{(($country_key == 'MX')?'Selected':'')}}>Mexico</option>     --}}
                                    </select> 
                                    
                                </div>
                            </div>


                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <label for="lalamove_country_key class="mr-3">{{ __("Country Region") }}</label>

                                    <select name="country_region" class="form-control" id="lalamove_country_region" @if($delOption->status == 1) required @endif>
                                    <option value="">{{ __("Please Select Country Region") }}</option>    
                                    <option value="MY_KUL" {{(($country_region == 'MY_KUL')?'Selected':'')}}>Kuala Lumpur</option>    
                                    <option value="MY_JHB" {{(($country_region == 'MY_JHB')?'Selected':'')}}>Johor Bahru</option>    
                                    <option value="MY_NTL" {{(($country_region == 'MY_NTL')?'Selected':'')}}>Penang</option>    
                                    </select> 
                                    
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <label for="lalamove_locale_key class="mr-3">{{ __("Country Region") }}</label>

                                    <select name="locale_key" class="form-control" id="lalamove_locale_key" @if($delOption->status == 1) required @endif>
                                    <option value="">{{ __("Please Select Locale Region") }}</option>    
                                    <option value="en_MY" {{(($locale_key == 'en_MY')?'Selected':'')}}>English</option>    
                                    <option value="ms_MY" {{(($locale_key == 'ms_MY')?'Selected':'')}}>Malaysia</option>      
                                    </select> 
                                    
                                </div>
                            </div>


                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <label for="lalamove_service_type class="mr-3">{{ __("Country Region") }}</label>

                                    <select name="service_type" class="form-control" id="lalamove_service_type" @if($delOption->status == 1) required @endif>
                                    <option value="">{{ __("Please Select Service Type") }}</option>    
                                    <option value="MOTORCYCLE" {{(($service_type == 'MOTORCYCLE')?'Selected':'')}}>Motor Cycle</option>    
                                    <option value="CAR" {{(($service_type == 'CAR')?'Selected':'')}}>Car</option>      
                                    <option value="VAN" {{(($service_type == 'VAN')?'Selected':'')}}>Van</option>      
                                    <option value="4X4" {{(($service_type == '4X4')?'Selected':'')}}>4X4</option>      
                                    <option value="TRUCK330" {{(($service_type == 'TRUCK330')?'Selected':'')}}>Truck 330</option>      
                                    <option value="TRUCK550" {{(($service_type == 'TRUCK550')?'Selected':'')}}>Truck 550</option>      
                                    </select> 
                                    
                                </div>
                            </div>


                            <div class="col-6 mt-4">
                                <div class="form-group mb-0 switchery-demo">
                                    <label for="" class="mr-3">{{ __("Set Base Price Fare") }}</label>
                                    <input type="checkbox"  data-title="{{$delOption->code}}" data-plugin="switchery" name="base_active" class="chk_box base_select" data-color="#43bee1" @if($base_price > 0) checked @endif>
                                </div>
                            </div>

                        <div id="lalamove_fields_wrapper_base" @if($base_price < 1) style="display:none" @endif class="col-12">
                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <label for="lalamove_base_price" class="mr-3">{{ __("Base Price") }}</label>
                                    <input type="text" name="base_price" id="lalamove_base_price" class="form-control" value="{{@$base_price}}" >
                                </div>  
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <label for="lalamove_distance" class="mr-3">{{ __("Distance") }}</label>
                                    <input type="text" name="distance" id="lalamove_distance" class="form-control" value="{{@$distance}}" >
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <label for="lalamove_amount_per_km" class="mr-3">{{ __("Amount Per Killometer") }}</label>
                                    <input type="text" name="amount_per_km" id="lalamove_amount_per_km" class="form-control" value="{{@$amount_per_km}}" >
                                </div>
                            </div>
                        </div>

                        </div>
                    </div>
                    @endif


                </div>
            </div>
            @endif
        </div>
    </form>

</div>

@endsection

@section('script')
<script type="text/javascript">
    $('.applyVendor').click(function() {
        $('#applyVendorModal').modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    $('.all_select').change(function() {
        var id = $(this).data('id');
         //console.log(id);
        var title = $(this).data('title');
        var code = title.toLowerCase();
        if ($(this).is(":checked")) {
            $("#" + code + "_fields_wrapper").show();
            $("#" + code + "_fields_wrapper").find('input').attr('required', true);
        } else {
            $("#" + code + "_fields_wrapper").hide();
            $("#" + code + "_fields_wrapper").find('input').removeAttr('required');
        }
    });

    $('.base_select').change(function() {
        var id = $(this).data('id');
        var title = $(this).data('title');
        var code = title.toLowerCase();
        if ($(this).is(":checked")) {
            $("#" + code + "_fields_wrapper_base").show();
            $("#" + code + "_fields_wrapper_base").find('input').attr('required', true);
        } else {
            $("#" + code + "_fields_wrapper_base").hide();
            $("#" + code + "_fields_wrapper_base").find('input').removeAttr('required');
        }
    });
</script>
@endsection