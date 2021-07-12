@extends('layouts.store', ['title' => 'Buy Subscription'])

@section('css')
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
<style type="text/css">
    .main-menu .brand-logo {
        display: inline-block;
        padding-top: 20px;
        padding-bottom: 20px;
    }
</style>

@endsection

@section('content')

<header>
    <div class="mobile-fix-option"></div>
    @include('layouts.store/left-sidebar')
</header>
<style type="text/css">
    .invalid-feedback {
        display: block;
    }
</style>

<section class="section-b-space">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="text-sm-left">
                    @if (\Session::has('success'))
                        <div class="alert alert-success">
                            <span>{!! \Session::get('success') !!}</span>
                        </div>
                    @endif
                    @if ( ($errors) && (count($errors) > 0) )
                        <div class="alert alert-danger">
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
        <div class="row">
            <div class="col-lg-3">
                <div class="account-sidebar"><a class="popup-btn">my account</a></div>
                @include('layouts.store/profile-sidebar')
            </div>
            <div class="col-lg-9">
                <div class="dashboard-right">
                    <div class="dashboard">
                        <div class="page-title">
                            <h2>Buy Subscription</h2>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4" id="subscription_payment_methods">
                @if($payment_options == '')
                    <div class="col-md-12">
                        <h6>Payment Methods Not Avaialable</h6>
                    </div>
                @else
                    <div class="col-md-12">
                        <h5><b>Plan :</b> {{ $subscription->title }}</h5>
                        <h5><b>Price :</b> ${{ $subscription->price }}</h5>
                    </div>
                    @foreach($payment_options as $payment_option)
                        @if( ($payment_option->slug != 'cash_on_delivery') && ($payment_option->slug != 'loyalty_points') )
                        <div class="col-md-12">
                            <label class="radio mt-2">
                                {{ $payment_option->title }}
                                <input type="radio" name="subscription_payment_method" id="radio-{{ $payment_option->slug }}" value="{{ $payment_option->slug }}" data-payment_option_id="{{ $payment_option->id }}">
                                <span class="checkround"></span>
                            </label>
                            @if($payment_option->slug == 'stripe')
                                <div class="col-md-12 mt-3 mb-3 stripe_element_wrapper d-none">
                                    <div class="form-control">
                                        <label class="d-flex flex-row pt-1 pb-1 mb-0">
                                            <div id="stripe-card-element"></div>
                                        </label>
                                    </div>
                                    <span class="error text-danger" id="stripe_card_error"></span>
                                </div>
                            @endif
                        </div>
                        @endif
                    @endforeach
                    <div class="col-md-12">
                        <button type="button" class="btn btn-solid mt-2 buy_subscription_confirm">Buy Now</button>
                        <button type="button" class="btn btn-solid mt-2">Cancel</button>
                    </div>
                @endif
                </div>
                
            </div>
        </div>
    </div>
</section>

@endsection

@section('script')
<script src="{{asset('assets/libs/dropzone/dropzone.min.js')}}"></script>
<script src="{{asset('assets/libs/dropify/dropify.min.js')}}"></script>
<script src="https://js.stripe.com/v3/"></script>
<script type="text/javascript">
    var payment_option_list_url = "{{route('payment.option.list')}}";

    $(document).on('change', '#subscription_payment_methods input[name="subscription_payment_method"]', function() {
        var method = $(this).val();
        if(method == 'stripe'){
            $("#subscription_payment_methods .stripe_element_wrapper").removeClass('d-none');
        }else{
            $("#subscription_payment_methods .stripe_element_wrapper").addClass('d-none');
        }
    });
</script>

@endsection