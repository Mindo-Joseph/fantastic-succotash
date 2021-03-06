@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Payment Options'])

@section('css')
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
@endsection

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


    <form method="POST" id="payment_option_form" action="{{route('payoption.updateAll')}}">
        @csrf
        @method('POST')
        <div class="row align-items-center">
            <div class="col-sm-8">
                <div class="text-sm-left">
                    <div class="page-title-box">
                        <h4 class="page-title">{{ __("Payment Options") }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 text-right">
                <button class="btn btn-info waves-effect waves-light save_btn" type="submit"> {{ __("Save") }}</button>
            </div>
        </div>
        <div class="row">
            @foreach($payOption as $key => $opt)
            <div class="col-6 col-md-3 col-xl-2 mb-3">

                <input type="hidden" name="method_id[]" id="{{$opt->id}}" value="{{$opt->id}}">
                <input type="hidden" name="method_name[]" id="{{$opt->code}}" value="{{$opt->code}}">

                <?php
                $creds = json_decode($opt->credentials);
                $username = (isset($creds->username)) ? $creds->username : '';
                $password = (isset($creds->password)) ? $creds->password : '';
                $signature = (isset($creds->signature)) ? $creds->signature : '';
                $api_key = (isset($creds->api_key)) ? $creds->api_key : '';
                $location_id= (isset($creds->location_id)) ? $creds->location_id : '';
                $application_id = (isset($creds->application_id)) ? $creds->application_id : '';
                $api_access_token = (isset($creds->api_access_token)) ? $creds->api_access_token : '';
                $api_secret_key = (isset($creds->api_secret_key)) ? $creds->api_secret_key : '';
                $publishable_key = (isset($creds->publishable_key)) ? $creds->publishable_key : '';
                $secret_key = (isset($creds->secret_key)) ? $creds->secret_key : '';
                $public_key = (isset($creds->public_key)) ? $creds->public_key : '';
                $private_key = (isset($creds->private_key)) ? $creds->private_key : '';
                $site_code = (isset($creds->site_code)) ? $creds->site_code : '';
                $merchant_id = (isset($creds->merchant_id)) ? $creds->merchant_id : '';
                $merchant_key = (isset($creds->merchant_key)) ? $creds->merchant_key : '';
                $passphrase = (isset($creds->passphrase)) ? $creds->passphrase : '';
                $merchant_account = (isset($creds->merchant_account)) ? $creds->merchant_account : '';
                $multiplier = (isset($creds->multiplier)) ? $creds->multiplier : '';
                ?>

                <div class="card-box h-100 mb-0">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h4 class="header-title mb-0">{{$opt->title}}</h4>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group mb-0 switchery-demo">
                                <label for="" class="mr-0 d-block">{{ __("Enable") }}</label>
                                <input type="checkbox" data-id="{{$opt->id}}" data-title="{{$opt->code}}" data-plugin="switchery" name="active[{{$opt->id}}]" class="chk_box all_select" data-color="#43bee1" @if($opt->status == 1) checked @endif>
                            </div>
                        </div>
                        @if ( (strtolower($opt->code) != 'cod') &&  (strtolower($opt->code) != 'razorpay') &&  (strtolower($opt->code) != 'simplify'))
                        <div class="col-6">
                            <div class="form-group mb-0 switchery-demo">
                                <label for="" class="mr-0 d-block">{{ __('Sandbox') }}</label>
                                <input type="checkbox" data-id="{{$opt->id}}" data-title="{{$opt->code}}" data-plugin="switchery" name="sandbox[{{$opt->id}}]" class="chk_box" data-color="#43bee1" @if($opt->test_mode == 1) checked @endif>
                            </div>
                        </div>
                        @endif
                    </div>

                    @if ( (strtolower($opt->code) == 'stripe') )
                    <div class="mt-2" id="stripe_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="stripe_api_key" class="mr-3">{{ __("Secret Key") }}</label>
                                    <input type="password" name="stripe_api_key" id="stripe_api_key" class="form-control" value="{{$api_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="stripe_publishable_key" class="mr-3">{{ __("Publishable Key") }}</label>
                                    <input type="password" name="stripe_publishable_key" id="stripe_publishable_key" class="form-control" value="{{$publishable_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ( (strtolower($opt->code) == 'paypal') )
                    <div class="mt-2" id="paypal_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="paypal_username" class="mr-3">{{ __("Username") }}</label>
                                    <input type="textbox" name="paypal_username" id="paypal_username" class="form-control" value="{{$username}}" @if($opt->status == 1) value="" required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="paypal_password" class="mr-3">{{ __("Password") }}</label>
                                    <input type="password" name="paypal_password" id="paypal_password" class="form-control" value="{{$password}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="paypal_signature" class="mr-3">{{ __("Signature") }}</label>
                                    <input type="password" name="paypal_signature" id="paypal_signature" class="form-control" value="{{$signature}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ( (strtolower($opt->code) == 'paystack') )
                    <div class="mt-2" id="paystack_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="paystack_secret_key" class="mr-3">{{ __("Secret Key") }}</label>
                                    <input type="password" name="paystack_secret_key" id="paystack_secret_key" class="form-control" value="{{$secret_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="paystack_public_key" class="mr-3">{{ __("Publishable Key") }}</label>
                                    <input type="password" name="paystack_public_key" id="paystack_public_key" class="form-control" value="{{$public_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ( (strtolower($opt->code) == 'payfast') )
                    <div class="mt-2" id="payfast_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="payfast_merchant_id" class="mr-3">{{ __("Merchant ID") }}</label>
                                    <input type="text" name="payfast_merchant_id" id="payfast_merchant_id" class="form-control" value="{{$merchant_id}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="payfast_merchant_key" class="mr-3">{{ __("Merchant Key") }}</label>
                                    <input type="password" name="payfast_merchant_key" id="payfast_merchant_key" class="form-control" value="{{$merchant_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="payfast_passphrase" class="mr-3">{{ __("Passphrase") }}</label>
                                    <input type="text" name="payfast_passphrase" id="payfast_passphrase" class="form-control" value="{{$passphrase}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ( (strtolower($opt->code) == 'mobbex') )
                    <div class="mt-2" id="mobbex_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="mobbex_api_key" class="mr-3">{{ __("API Key") }}</label>
                                    <input type="text" name="mobbex_api_key" id="mobbex_api_key" class="form-control" value="{{$api_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="mobbex_api_access_token" class="mr-3">{{ __("API Access Token") }}</label>
                                    <input type="password" name="mobbex_api_access_token" id="mobbex_api_access_token" class="form-control" value="{{$api_access_token}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if ( (strtolower($opt->code) == 'yoco') )
                    <div class="mt-2" id="yoco_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="yoco_secret_key" class="mr-3">{{ __("Secret Key") }}</label>
                                    <input type="password" name="yoco_secret_key" id="yoco_secret_key" class="form-control" value="{{$secret_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="yoco_public_key" class="mr-3">{{ __("Public Key") }}</label>
                                    <input type="password" name="yoco_public_key" id="yoco_public_key" class="form-control" value="{{$public_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if ( (strtolower($opt->code) == 'paylink') )
                    <div class="mt-2" id="paylink_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="paylink_api_key" class="mr-3">{{ __("Api Key") }}</label>
                                    <input type="password" name="paylink_api_key" id="paylink_api_key" class="form-control" value="{{$api_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="paylink_api_secret_key" class="mr-3">{{ __("Api Secret Key") }}</label>
                                    <input type="password" name="paylink_api_secret_key" id="paylink_api_secret_key" class="form-control" value="{{$api_secret_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ( (strtolower($opt->code) == 'razorpay') )
                    <div class="2" id="razorpay_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="razorpay_api_key" class="mr-3">{{ __("Api Key") }}</label>
                                    <input type="text" name="razorpay_api_key" id="razorpay_api_key" class="form-control" value="{{$api_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="razorpay_api_secret_key" class="mr-3">{{ __("Api Secret Key") }}</label>
                                    <input type="text" name="razorpay_api_secret_key" id="razorpay_api_secret_key" class="form-control" value="{{$api_secret_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ( (strtolower($opt->code) == 'gcash') )
                    <div class="mt-2" id="gcash_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="gcash_public_key" class="mr-3">{{ __("Public Key") }}</label>
                                    <input type="text" name="gcash_public_key" id="gcash_public_key" class="form-control" value="{{$public_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ( (strtolower($opt->code) == 'simplify') )
                    <div class="mt-2" id="simplify_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="simplify_public_key" class="mr-3">{{ __("Public Key") }}</label>
                                    <input type="text" name="simplify_public_key" id="simplify_public_key" class="form-control" value="{{$public_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="simplify_private_key" class="mr-3">{{ __("Private Key") }}</label>
                                    <input type="password" name="simplify_private_key" id="simplify_private_key" class="form-control" value="{{$private_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ( (strtolower($opt->code) == 'square') )
                    <div class="mt-2" id="square_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="square_application_id" class="mr-3">{{ __("Application ID") }}</label>
                                    <input type="text" name="square_application_id" id="square_application_id" class="form-control" value="{{$application_id}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="square_access_token" class="mr-3">{{ __("Access Token") }}</label>
                                    <input type="password" name="square_access_token" id="square_access_token" class="form-control" value="{{$api_access_token}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="square_location_id" class="mr-3">{{ __("Location ID") }}</label>
                                    <input type="text" name="square_location_id" id="square_location_id" class="form-control" value="{{$location_id}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ( (strtolower($opt->code) == 'ozow') )
                    <div class="mt-2" id="ozow_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="ozow_site_code" class="mr-3">{{ __("Site Code") }}</label>
                                    <input type="text" name="ozow_site_code" id="ozow_site_code" class="form-control" value="{{$site_code}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="ozow_private_key" class="mr-3">{{ __("Private Key") }}</label>
                                    <input type="password" name="ozow_private_key" id="ozow_private_key" class="form-control" value="{{$private_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="ozow_api_key" class="mr-3">{{ __("Api Key") }}</label>
                                    <input type="text" name="ozow_api_key" id="ozow_api_key" class="form-control" value="{{$api_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ( (strtolower($opt->code) == 'pagarme') )
                    <div class="mt-2" id="pagarme_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="pagarme_api_key" class="mr-3">{{ __("Api Key") }}</label>
                                    <input type="text" name="pagarme_api_key" id="pagarme_api_key" class="form-control" value="{{$api_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="pagarme_secret_key" class="mr-3">{{ __("Secret Key") }}</label>
                                    <input type="password" name="pagarme_secret_key" id="pagarme_secret_key" class="form-control" value="{{$secret_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="pagarme_multiplier" class="mr-3">{{ __("Multiplier") }}</label>
                                    <input type="number" name="pagarme_multiplier" id="pagarme_multiplier" class="form-control" value="{{$multiplier}}" step="0.01" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ( (strtolower($opt->code) == 'checkout') )
                    <div class="mt-2" id="checkout_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="checkout_secret_key" class="mr-3">{{ __("Secret Key") }}</label>
                                    <input type="password" name="checkout_secret_key" id="checkout_secret_key" class="form-control" value="{{$secret_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="checkout_public_key" class="mr-3">{{ __("Public Key") }}</label>
                                    <input type="password" name="checkout_public_key" id="checkout_public_key" class="form-control" value="{{$public_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </form>

    <form method="POST" id="payout_option_form" action="{{route('payoutOption.payoutUpdateAll')}}">
        @csrf
        @method('POST')
        <div class="row align-items-center">
            <div class="col-sm-8">
                <div class="text-sm-left">
                    <div class="page-title-box">
                        <h4 class="page-title">{{ __("Payout Options") }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 text-right">
                <button class="btn btn-info waves-effect waves-light save_btn" type="submit"> {{ __("Save") }}</button>
            </div>
        </div>
        <div class="row">
            @foreach($payoutOption as $key => $opt)
            <div class="col-md-4 mb-3">

                <input type="hidden" name="method_id[]" id="{{$opt->id}}" value="{{$opt->id}}">
                <input type="hidden" name="method_name[]" id="{{$opt->code}}" value="{{$opt->code}}">

                <?php
                $creds = json_decode($opt->credentials);
                $payout_secret_key = (isset($creds->secret_key)) ? $creds->secret_key : '';
                $payout_publishable_key = (isset($creds->publishable_key)) ? $creds->publishable_key : '';
                $payout_client_id = (isset($creds->client_id)) ? $creds->client_id : '';
                ?>

                <div class="card-box h-100">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h4 class="header-title mb-0">{{$opt->title}}</h4>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group mb-0 switchery-demo">
                                <label for="" class="mr-0 d-block">{{ __("Enable") }}</label>
                                <input type="checkbox" data-id="{{$opt->id}}" data-title="{{$opt->code}}" data-plugin="switchery" name="active[{{$opt->id}}]" class="chk_box payout_all_select" data-color="#43bee1" @if($opt->status == 1) checked @endif>
                            </div>
                        </div>
                        @if ( (strtolower($opt->code) != 'cash') )
                        <div class="col-6">
                            <div class="form-group mb-0 switchery-demo">
                                <label for="" class="mr-0 d-block">{{ __('Sandbox') }}</label>
                                <input type="checkbox" data-id="{{$opt->id}}" data-title="{{$opt->code}}" data-plugin="switchery" name="sandbox[{{$opt->id}}]" class="chk_box" data-color="#43bee1" @if($opt->test_mode == 1) checked @endif>
                            </div>
                        </div>
                        @endif
                    </div>

                    @if ( (strtolower($opt->code) == 'stripe') )
                    <div class="mt-2" id="stripe_payout_fields_wrapper" @if($opt->status != 1) style="display:none" @endif>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="stripe_payout_secret_key" class="mr-3">{{ __("Secret Key") }}</label>
                                    <input type="password" name="stripe_payout_secret_key" id="stripe_payout_secret_key" class="form-control" value="{{$payout_secret_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="stripe_payout_publishable_key" class="mr-3">{{ __("Publishable Key") }}</label>
                                    <input type="password" name="stripe_payout_publishable_key" id="stripe_payout_publishable_key" class="form-control" value="{{$payout_publishable_key}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-2">
                                    <label for="stripe_payout_client_id" class="mr-3">{{ __("Client ID") }}</label>
                                    <input type="password" name="stripe_payout_client_id" id="stripe_payout_client_id" class="form-control" value="{{$payout_client_id}}" @if($opt->status == 1) required @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
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
        // console.log(id);
        var title = $(this).data('title');
        var code = title.toLowerCase();
        if ($(this).is(":checked")) {
            $("#" + code + "_fields_wrapper").show();
            $("#" + code + "_fields_wrapper").find('input').attr('required', true);
        } else {
            $("#" + code + "_fields_wrapper").hide();
            $("#" + code + "_fields_wrapper").find('input').removeAttr('required');
        }

        // if( title.toLowerCase() == 'stripe' ){
        //     if($(this).is(":checked")){
        //         $("#stripe_fields_wrapper").show();
        //         $("#stripe_fields_wrapper").find('input').attr('required', true);
        //     }
        //     else{
        //         $("#stripe_fields_wrapper").hide();
        //         $("#stripe_fields_wrapper").find('input').removeAttr('required');
        //     }
        // }
        // if( title.toLowerCase() == 'paypal' ){
        //     if($(this).is(":checked")){
        //         $("#paypal_fields_wrapper").show();
        //         $("#paypal_fields_wrapper").find('input').attr('required', true);
        //     }
        //     else{
        //         $("#paypal_fields_wrapper").hide();
        //         $("#paypal_fields_wrapper").find('input').removeAttr('required');
        //     }
        // }
        // if( title.toLowerCase() == 'paystack' ){
        //     if($(this).is(":checked")){
        //         $("#paystack_fields_wrapper").show();
        //         $("#paystack_fields_wrapper").find('input').attr('required', true);
        //     }
        //     else{
        //         $("#paystack_fields_wrapper").hide();
        //         $("#paystack_fields_wrapper").find('input').removeAttr('required');
        //     }
        // }
        // if( title.toLowerCase() == 'payfast' ){
        //     if($(this).is(":checked")){
        //         $("#payfast_fields_wrapper").show();
        //         $("#payfast_fields_wrapper").find('input').attr('required', true);
        //     }
        //     else{
        //         $("#payfast_fields_wrapper").hide();
        //         $("#payfast_fields_wrapper").find('input').removeAttr('required');
        //     }
        // }

        // $('#form_'+id).submit();

        //$('.vendorRow').toggle();
    });

    $('.payout_all_select').change(function() {
        var id = $(this).data('id');
        // console.log(id);
        var title = $(this).data('title');
        var code = title.toLowerCase();
        if ($(this).is(":checked")) {
            $("#" + code + "_payout_fields_wrapper").show();
            $("#" + code + "_payout_fields_wrapper").find('input').attr('required', true);
        } else {
            $("#" + code + "_payout_fields_wrapper").hide();
            $("#" + code + "_payout_fields_wrapper").find('input').removeAttr('required');
        }
    });
</script>
@endsection