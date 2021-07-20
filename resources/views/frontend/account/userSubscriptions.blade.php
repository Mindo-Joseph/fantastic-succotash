@extends('layouts.store', ['title' => 'My Subscriptions'])

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
@php
$timezone = Auth::user()->timezone;
@endphp
<header>
    <div class="mobile-fix-option"></div>
    @include('layouts.store/left-sidebar')
</header>
<style type="text/css">
    .invalid-feedback {
        display: block;
    }
    ul li {
        margin: 0 0 10px;
        color: #6c757d;
    }
</style>

<section class="section-b-space">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="text-sm-left">
                    @if (\Session::has('success'))
                        <div class="alert alert-success">
                            <button type="button" class="close p-0" data-dismiss="alert">x</button>
                            <span>{!! \Session::get('success') !!}</span>
                        </div>
                        @php
                            \Session::forget('success');
                        @endphp
                    @endif
                    @if (\Session::has('error'))
                        <div class="alert alert-danger">
                            <button type="button" class="close p-0" data-dismiss="alert">x</button>
                            <span>{!! \Session::get('error') !!}</span>
                        </div>
                        @php
                            \Session::forget('error');
                        @endphp
                    @endif
                    @if ( ($errors) && (count($errors) > 0) )
                        <div class="alert alert-danger">
                            <button type="button" class="close p-0" data-dismiss="alert">x</button>
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
                            <h2>My Subscriptions</h2>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 mb-4">
                        @if(!empty($active_subscriptions))
                            <div class="card subscript-box">
                                @foreach($active_subscriptions as $subscription)
                                <div class="row align-items-center mb-2">
                                    <div class="col-sm-3 text-center">
                                        <div class="gold-icon">
                                            <img src="{{$subscription->plan->image['proxy_url'].'100/100'.$subscription->plan->image['image_path']}}" alt="">
                                        </div>
                                    </div>
                                    <div class="col-sm-9 mt-3 mt-sm-0">
                                        <div class="row align-items-end border-left-top pt-sm-0 pt-2">
                                            <div class="col-12">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h3 class="d-inline-block"><b>{{ $subscription->plan->title }}</b></h3>
                                                    <span class="plan-price">${{ $subscription->subscription_amount }} / {{ $subscription->frequency }}</span>
                                                </div>
                                                <p>{{ $subscription->plan->description }}</p>
                                                <?php /* ?><ul class="mb-3">
                                                    @foreach($subscription->features as $feature)
                                                        <li><i class="fa fa-check"></i> {{ $feature->feature->title }}</li>
                                                    @endforeach
                                                </ul><?php */ ?>
                                            </div>
                                            <div class="col-sm-6 form-group mb-0">
                                                <b class="mr-2">Upcoming Billing Date</b>
                                                <span>{{ convertDateTimeInTimeZone($subscription->next_date, $timezone, 'F d, Y, H:i A') }}</span>
                                            </div>
                                            <div class="col-sm-6 mb-0 text-center text-sm-right">
                                                <a class="cancel-subscription-link" href="#cancel-subscription" data-toggle="modal" data-id="{{ $subscription->slug }}">Cancel</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    
                    @if($subscriptions->isNotEmpty())
                        @foreach($subscriptions as $plan)
                            <div class="col-md-4 col-sm-6">
                                <div class="pricingtable">
                                    <div class="gold-icon position-relative">
                                        <img src="{{ $plan->image['proxy_url'].'100/100'.$plan->image['image_path'] }}">
                                        <div class="pricingtable-header position-absolute">
                                            <div class="price-value"> <b>${{ $plan->price }}</b> <span class="month">{{ $plan->frequency }}</span> </div>
                                        </div>
                                    </div>
                                    <div class="p-2">
                                        <h3 class="heading mt-0 mb-2"><b>{{ $plan->title }}</b></h3>
                                        <div class="pricing-content">
                                            <p class="mb-0">{{ $plan->description }}</p>
                                        </div>
                                        <ul class="mb-3">
                                            @foreach($plan->features as $feature)
                                                <li><i class="fa fa-check"></i> {{ $feature }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="pricingtable-purchase">
                                        @if(in_array($plan->id, $active_subscription_plan_ids))
                                            <button class="btn btn-solid black-btn disabled w-100">Subscribed</button>
                                        @else
                                            <button class="btn btn-solid w-100 subscribe_btn" data-id="{{ $plan->slug }}">Subscribe</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="confirm-buy-subscription" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="confirm_buy_subscriptionLabel">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header pb-0">
        <h5 class="modal-title" id="confirm_buy_subscriptionLabel">Confirm Subscription</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <h6 class="m-0">Do you really want to buy this subscription ?</h6>
      </div>
      <div class="modal-footer flex-nowrap justify-content-center align-items-center">
        <button type="button" class="btn btn-solid" id="continue_buy_subscription_btn" data-id="">Continue</button>
        <button type="button" class="btn btn-solid black-btn" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="cancel-subscription" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="cancel_subscriptionLabel">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header pb-0">
        <h5 class="modal-title" id="cancel_subscriptionLabel">Unsubscribe</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <form id="cancel-subscription-form" method="POST" action="">
        @csrf
        <div class="modal-body">
            <h6 class="m-0">Do you really want to cancel this subscription ?</h6>
        </div>
        <div class="modal-footer flex-nowrap justify-content-center align-items-center">
            <button type="submit" class="btn btn-solid">Continue</a>
            <button type="button" class="btn btn-solid black-btn" data-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="subscription_payment" tabindex="-1" aria-labelledby="subscription_paymentLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-bottom">
        <h5 class="modal-title text-17 mb-0 mt-0" id="subscription_paymentLabel">Subscription</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="" id="subscription_payment_form">
        @csrf
        @method('POST')
        <div class="modal-body pb-0">
            <div class="form-group">
                <h5 class="text-17 mb-2" id="subscription_title"></h5>
                <div class="text-36"><span id="subscription_price"></span></div>
                <div><input type="hidden" name="subscription_amount" id="subscription_amount" value=""></div>
            </div>
            <hr class="mb-1" />
            <div class="payment_response">
                <div class="alert p-0 m-0" role="alert"></div>
            </div>
            <h5 class="text-17 mb-2">Debit From</h5>
            <div class="form-group" id="subscription_payment_methods">
            </div>
        </div>
        <div class="modal-footer d-block text-center">
            <div class="row">
                <div class="col-sm-6 pl-sm-0 pr-sm-1"><button type="button" class="btn btn-block btn-solid mt-2 subscription_confirm_btn">Buy Now</button></div>
                <div class="col-sm-6 pr-sm-0 pl-sm-1"><button type="button" class="btn btn-block btn-solid mt-2" data-dismiss="modal">Cancel</button></div>
            </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script type="text/template" id="payment_method_template">
    <% if(payment_options == '') { %>
        <h6>Payment Methods Not Avaialable</h6>
    <% }else{ %>
        <% _.each(payment_options, function(payment_option, k){%>
            <% if( (payment_option.slug != 'cash_on_delivery') && (payment_option.slug != 'loyalty_points') ) { %>
                <label class="radio mt-2">
                    <%= payment_option.title %> 
                    <input type="radio" name="subscription_payment_method" id="radio-<%= payment_option.slug %>" value="<%= payment_option.slug %>" data-payment_option_id="<%= payment_option.id %>">
                    <span class="checkround"></span>
                </label>
                <% if(payment_option.slug == 'stripe') { %>
                    <div class="col-md-12 mt-3 mb-3 stripe_element_wrapper d-none">
                        <div class="form-control">
                            <label class="d-flex flex-row pt-1 pb-1 mb-0">
                                <div id="stripe-card-element"></div>
                            </label>
                        </div>
                        <span class="error text-danger" id="stripe_card_error"></span>
                    </div>
                <% } %>
            <% } %>
        <% }); %>
    <% } %>
</script>

@endsection

@section('script')
<script src="https://js.stripe.com/v3/"></script>
<script type="text/javascript">
    var subscription_payment_options_url = "{{route('user.subscription.plan.select', ':id')}}";
    var user_subscription_purchase_url = "{{route('user.subscription.plan.purchase', ':id')}}";
    var user_subscription_cancel_url = "{{route('user.subscription.plan.cancel', ':id')}}";
    var payment_stripe_url = "{{route('subscription.payment.stripe')}}";
    var payment_paypal_url = "{{route('payment.paypalPurchase')}}";
    var payment_success_paypal_url = "{{route('payment.paypalCompletePurchase')}}";

    $(document).on('change', '#subscription_payment_methods input[name="subscription_payment_method"]', function() {
        var method = $(this).data("payment_option_id");
        if(method == 4){
            $("#subscription_payment_methods .stripe_element_wrapper").removeClass('d-none');
        }else{
            $("#subscription_payment_methods .stripe_element_wrapper").addClass('d-none');
        }
    });

    $(document).on('click', '.cancel-subscription-link', function(){
        var id = $(this).attr('data-id');
        $('#cancel-subscription-form').attr('action', user_subscription_cancel_url.replace(":id", id));
    });
</script>

@endsection