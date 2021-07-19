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

<header>
    <div class="mobile-fix-option"></div>
    @include('layouts.store/left-sidebar')
</header>
<style type="text/css">
    .invalid-feedback {
        display: block;
    }
    .price-card{
        position: relative;
        max-width: 300px;
        height: auto;
        background: linear-gradient(-5deg, transparent 0%, var(--theme-deafult) 50%, var(--theme-deafult) 100%);
        border-radius: 15px;
        margin: 0 auto;
        padding: 40px 20px;
        -webkit-box-shadow: 0 10px 15px rgba(0,0,0,.1) ;
        box-shadow: 0 10px 15px rgba(0,0,0,.1) ;
        -webkit-transition: .5s;
        transition: .5s;
        overflow: hidden;
    }
    .price-card:hover{
        -webkit-transform: scale(1.1);
        transform: scale(1.1);
    }
    /* .col-sm-4:nth-child(1) .price-card{
        background: linear-gradient(-45deg,#f403d1,#64b5f6);
    }
    .col-sm-4:nth-child(2) .price-card{
        background: linear-gradient(-45deg,#ffec61,#f321d7);
    } */
    .price-card::before{
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 40%;
        background: rgba(255, 255, 255, .1);
        z-index: 0;
        -webkit-transform: skewY(-5deg) scale(1.5);
        transform: skewY(-5deg) scale(1.5);
    }
    .title h2 {
        position: relative;
        margin: 20px  0 0;
        padding: 0;
        color: #fff;
        font-size: 20px;
        z-index: 2;
    }
    .price,.option{
        position: relative;
        z-index: 2;
    }
    .price h4 {
        margin: 0;
        padding: 20px 0 ;
        color: #fff;
        font-size: 40px;
    }
    .option ul {
        margin: 0;
        padding: 0;

    }
    .option ul li {
        margin: 0 0 10px;
        padding: 0;
        list-style: none;
        color: #fff;
        font-size: 16px;
    }
    .price-card a {
        position: relative;
        z-index: 2;
        background: #fff;
        color : black;
        width: 150px;
        height: 40px;
        line-height: 40px;
        border-radius: 40px;
        display: block;
        text-align: center;
        margin: 20px auto 0 ;
        font-size: 16px;
        cursor: pointer;
        -webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, .1);
        box-shadow: 0 5px 10px rgba(0, 0, 0, .1);
    }
    .price-card a:hover{
        text-decoration: none;
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
                            <h2>My Subscriptions</h2>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="card subscript-box">
                            <div class="row align-items-center">
                                <div class="col-sm-3 text-center">
                                    <div class="gold-icon">
                                        <img src="{{asset('assets/images/gold-icon.png')}}" alt="">
                                    </div>
                                </div>
                                <div class="col-sm-9 mt-3 mt-sm-0">
                                    <div class="row align-items-end border-left-top pt-sm-0 pt-2">
                                        <div class="col-12">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h3 class="d-inline-block"><b>Gold Membership</b></h3>
                                                <span class="plan-price">$20 / mo</span>
                                            </div>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Pariatur ea unde libero architecto numquam eum?</p>
                                        </div>
                                        <div class="col-sm-6 form-group mb-0">
                                            <b class="mr-2">Upcoming Billing Date</b>
                                            <span>(16-May-2022)</span>
                                        </div>
                                        <div class="col-sm-6 mb-0 text-center text-sm-right">
                                            <a href="#">Cancel</a>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
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
                                        <button class="btn btn-solid w-100 subscribe_btn" data-id="{{ $plan->slug }}">Subscribe</button>
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
    var subscription_payment_options_url = "{{route('user.buySubscription', ':id')}}";
    var user_subscription_purchase_url = "";
    var payment_stripe_url = "{{route('payment.stripe')}}";
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
</script>

@endsection