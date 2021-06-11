@extends('layouts.store', ['title' => 'Product'])
@section('content')
    <section class="wrapper-main mb-5 py-lg-5">
        <div class="container">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#pay-bill">
                Launch static backdrop modal
            </button>


            <div class="row">
                <div class="col-lg-6 mb-lg-0 mb-3 text-center border-right pb-4">
                    <h3 class="mb-2">Login to Your Account</h3>
                    <!-- <div class="sub_heading">Social media logins</div> -->
                    <ul class="social-links d-flex align-items-center mx-auto mb-4 mt-3">
                        <li>
                            <a href="#"><img src="{{asset('front-assets/images/google.svg')}}" alt=""></a>
                        </li>
                        <li>
                            <a href="#"><img src="{{asset('front-assets/images/facebook.svg')}}" alt=""></a>
                        </li>
                        <li>
                            <a href="#"><img src="{{asset('front-assets/images/twitter.svg')}}" alt=""></a>
                        </li>
                        <li>
                            <a href="#"><img src="{{asset('front-assets/images/apple.svg')}}" alt=""></a>
                        </li>
                    </ul>
                    <div class="divider_line m-auto">
                        <span>OR</span>
                    </div>

                    <div class="row mt-3">
                        <div class="offset-xl-2 col-xl-8 text-left">
                            <form class="px-lg-4">
                                <div class="form-group">
                                    <label for=""> address</label>
                                    <input type="" class="form-control" id="" aria-describedby="" placeholder="Enter ">
                                </div>
                                <div class="form-group">
                                    <label for="">Password</label>
                                    <input type="password" class="form-control" id="" placeholder="Password">
                                </div>
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-solid submitLogin">Submit</button>
                                    </div>
                                    <div class="col-md-6 text-md-right">
                                        <a class="forgot_btn" href="#">Forgot Password?</a>
                                    </div>
                                </div>
                               
                            </form>
                        </div>
                    </div>    
                </div>
                <div class="col-lg-6 text-center">
                    <h3 class="mb-md-5 mb-4">New Customer</h3>   
                    
                    <div class="create_box">
                        <h6>Create A Account</h6>
                        <p>Sign up for a free account at our store. Registration is quick and easy. It allows you to be able to order from our shop. To start shopping click register.</p>
                        <a href="#" class="btn btn-solid mt-4">Create an Account</a>
                    </div>
                </div>
            </div>
        </div>
    </section> 



    <!-- Register Page Start Form Here -->
    <section class="wrapper-main mb-5 py-lg-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 mb-lg-0 mb-3 text-center">
                    <h3 class="mb-2">New Customer</h3>
                    <!-- <div class="sub_heading">Social media logins</div> -->
                    <ul class="social-links d-flex align-items-center mx-auto mb-4 mt-3">
                        <li>
                            <a href="#"><img src="{{asset('front-assets/images/google.svg')}}" alt=""></a>
                        </li>
                        <li>
                            <a href="#"><img src="{{asset('front-assets/images/facebook.svg')}}" alt=""></a>
                        </li>
                        <li>
                            <a href="#"><img src="{{asset('front-assets/images/twitter.svg')}}" alt=""></a>
                        </li>
                        <li>
                            <a href="#"><img src="{{asset('front-assets/images/apple.svg')}}" alt=""></a>
                        </li>
                    </ul>
                    <div class="divider_line m-auto">
                        <span>OR</span>
                    </div>

                    <div class="row mt-3">
                        <div class="offset-xl-2 col-xl-8 text-left">
                            <form class="px-lg-4">
                                <div class="row form-group mb-0">
                                    <div class="col-md-6 mb-3">
                                        <label for="">Full Name</label>
                                        <input type="" class="form-control" id="" aria-describedby="" placeholder="Enter Name">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="">Phone No.</label>
                                        <input type="" class="form-control" id="" aria-describedby="" placeholder="Enter Phone No.">
                                    </div>                                    
                                </div>
                                <div class="row form-group mb-0">
                                    <div class="col-md-6 mb-3">
                                        <label for="">Email</label>
                                        <input type="" class="form-control" id="" aria-describedby="" placeholder="Enter email address">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="">Password</label>
                                        <input type="password" class="form-control" id="" placeholder="Enter password">
                                    </div>                                    
                                </div>
                                <div class="row form-group mb-0 align-items-end">
                                    <div class="col-md-6 mb-3">
                                        <label for="">Referral Code</label>
                                        <input type="" class="form-control" id="" aria-describedby="" placeholder="Enter Referral code">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <button type="submit" class="btn btn-solid submitLogin w-100">Create An Account</button>
                                    </div>                                    
                                </div>                               
                            </form>
                        </div>
                    </div>                        
                </div>
            </div>
        </div>
    </section> 


    <!-- Verify Page Start Form Here -->
    <section class="wrapper-main mb-5 py-lg-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-lg-0 mb-3 text-center border-right pb-4">
                    <img src="{{asset('front-assets/images/email_icon.svg')}}" alt="">
                    <h3 class="mb-2">Verify Email Address</h3>
                    <p>Enter the code we just sent you on your email address</p>

                    <div class="row mt-3">
                        <div class="offset-xl-3 col-xl-6 text-left">
                            <div class="verify_id input-group mb-3">
                                <input type="text" class="form-control" value="natish.designer@gmail.com" placeholder="natish.designer@gmail.com" aria-label="" aria-describedby="">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="">Edit</span>
                                </div>
                            </div>
                            <div method="get" class="digit-group otp_inputs d-flex justify-content-around" data-group-name="digits" data-autosubmit="false" autocomplete="off">
                                <input class="form-control" type="text" id="digit-1" name="digit-1" data-next="digit-2" />
                                <input class="form-control" type="text" id="digit-2" name="digit-2" data-next="digit-3" data-previous="digit-1" />
                                <input class="form-control" type="text" id="digit-3" name="digit-3" data-next="digit-4" data-previous="digit-2" />
                                <input class="form-control" type="text" id="digit-4" name="digit-4" data-next="digit-5" data-previous="digit-3" />
                            </div>
                            <div class="row text-center mt-2">
                                <div class="col-12 resend_txt">
                                    <p class="mb-1">If you didn’t receive a code?</p>
                                    <a href="#"><u>RESEND</u></a>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <button type="submit" class="btn btn-solid submitLogin">Verify</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 text-center">
                    <img src="{{asset('front-assets/images/phone-otp.svg')}}" alt="">
                    <h3 class="mb-2">Verify Phone</h3>
                    <p>Enter the code we just sent you on your email address</p>

                    <div class="row mt-3">
                        <div class="offset-xl-3 col-xl-6 text-left">
                            <div class="verify_id input-group mb-3">
                                <input type="text" class="form-control" placeholder="+91 8054433291" value="+91 8054433291" aria-label="" aria-describedby="">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="">Edit</span>
                                </div>
                            </div>
                            <div method="get" class="digit-group otp_inputs d-flex justify-content-around" data-group-name="digits" data-autosubmit="false" autocomplete="off">
                                <input class="form-control" type="text" id="digit-1" name="digit-1" data-next="digit-1" />
                                <input class="form-control" type="text" id="digit-2" name="digit-2" data-next="digit-2" data-previous="digit-1" />
                                <input class="form-control" type="text" id="digit-3" name="digit-3" data-next="digit-3" data-previous="digit-2" />
                                <input class="form-control" type="text" id="digit-4" name="digit-4" data-next="digit-4" data-previous="digit-3" />
                            </div>
                            <div class="row text-center mt-2">
                                <div class="col-12 resend_txt">
                                    <p class="mb-1">If you didn’t receive a code?</p>
                                    <a href="#"><u>RESEND</u></a>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <button type="submit" class="btn btn-solid submitLogin">Verify</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> 
    
    
    <!-- Verify-Two Page Start Form Here -->
    <section class="wrapper-main mb-5 py-lg-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-lg-0 mb-3 text-center">
                    <img src="{{asset('front-assets/images/verified.svg')}}" alt="">
                    <h3 class="mb-2">Email Address Verified!</h3>
                    <p>You have successfully verified the <br> email account.</p>
                </div>

                <div class="col-lg-6 text-center border-left py-4">
                    <img src="{{asset('front-assets/images/phone-otp.svg')}}" alt="">
                    <h3 class="mb-2">Verify Phone</h3>
                    <p>Enter the code we just sent you on your email address</p>

                    <div class="row mt-3">
                        <div class="offset-xl-3 col-xl-6 text-left">
                            <div class="verify_id input-group mb-3">
                                <input type="text" class="form-control" placeholder="+91 8054433291" value="+91 8054433291" aria-label="" aria-describedby="">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="">Edit</span>
                                </div>
                            </div>
                            <div method="get" class="digit-group-1 otp_inputs d-flex justify-content-around" data-group-name="digits" data-autosubmit="false" autocomplete="off">
                                <input class="form-control" type="text" id="digit-1" name="digit-1" data-next="digit-1" />
                                <input class="form-control" type="text" id="digit-2" name="digit-2" data-next="digit-2" data-previous="digit-1" />
                                <input class="form-control" type="text" id="digit-3" name="digit-3" data-next="digit-3" data-previous="digit-2" />
                                <input class="form-control" type="text" id="digit-4" name="digit-4" data-next="digit-4" data-previous="digit-3" />
                            </div>
                            <div class="row text-center mt-2">
                                <div class="col-12 resend_txt">
                                    <p class="mb-1">If you didn’t receive a code?</p>
                                    <a href="#"><u>RESEND</u></a>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <button type="submit" class="btn btn-solid submitLogin">Verify</button>
                                </div>
                            </div>

                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> 

    <!-- Modal -->
    <div class="modal fade" id="pay-bill"  data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="pay-billLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">           
                <div class="modal-body p-0">
                    <div class="row no-gutters pr-3">
                        @if ( (isset($active_methods))  && (!empty($active_methods)) )
                        <div class="col-4">
                            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            @foreach($active_methods as $key => $method)
                                <a class="nav-link" id="v-pills-{{strtolower($method->code)}}-tab" data-toggle="pill" href="#v-pills-{{strtolower($method->code)}}" role="tab" aria-controls="v-pills-{{strtolower($method->code)}}" aria-selected="true">{{$method->title}}</a>
                            @endforeach
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="tab-content-box pl-3">
                                <h5 class="modal-title pt-4" id="pay-billLabel">Pay Bill</h5>
                                <button type="button" class="close top_right" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <div class="tab-content h-100" id="v-pills-tabContent">
                                @foreach($active_methods as $key => $method)
                                    @if ( (strtolower($method->code) == 'paypal') )
                                    <div class="tab-pane fade" id="v-pills-paypal" role="tabpanel" aria-labelledby="v-pills-paypal-tab">
                                        <form method="POST" action="" id="paypal-payment-form">
                                        @csrf
                                        @method('POST')
                                        <div class="row form-group">
                                            <div class="col-sm-8">
                                                <label for="">Amount</label>
                                                <input class="form-control" name="amount" type="text" value="0.5">
                                            </div>                                                    
                                        </div>
                                        <div class="row mt-5">
                                            <div class="col-md-12 text-md-right">
                                                <button type="button" class="btn btn-solid" data-dismiss="modal">Cancel</button>
                                                <button type="button" class="btn btn-solid ml-1 payment_btn">Continue to Pay</button>
                                            </div>
                                        </div>
                                        </form>
                                    </div>
                                    @endif

                                    @if ( (strtolower($method->code) == 'stripe') )
                                    <div class="tab-pane fade" id="v-pills-stripe" role="tabpanel" aria-labelledby="v-pills-stripe-tab">
                                        <form method="POST" action="" id="stripe-payment-form">
                                            @csrf
                                            @method('POST')
                                            <div class="payment_resp" role="alert"></div>
                                            <div class="form_fields">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="row form-group">
                                                            <div class="col-sm-8">
                                                                <label for="">Amount</label>
                                                                <input class="form-control" name="amount" type="text" value="0.5">
                                                            </div>                                                    
                                                        </div>
                                                        <div id="card-element">
                                                        <!-- A Stripe Element will be inserted here. -->
                                                        </div>
                                                        <!--<div class="row form-group">
                                                            <div class="col-sm-4">
                                                                <label for="">Card Number</label>
                                                                <input class="form-control" name="number" type="text">
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <div class="row align-items-end">
                                                                    <div class="col-6">
                                                                        <label for="">EXPIRATION DATE</label>
                                                                        <input class="form-control" name="expiry" type="text">
                                                                    </div>
                                                                    <div class="col-4">
                                                                        <label for="">CVV</label>
                                                                        <input class="form-control" name="cvv" type="text">
                                                                    </div>
                                                                    <div class="col-2 pb-1">
                                                                        <i class="fa fa-credit-card-alt" aria-hidden="true"></i>
                                                                    </div>
                                                                </div>
                                                            </div>                                                        
                                                        </div>-->
                                                    </div>
                                                </div>
                                                <div class="row mt-5">
                                                    <div class="col-md-12 text-md-right">
                                                        <button type="button" class="btn btn-solid" data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-solid ml-1 payment_btn">Continue to Pay</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    @endif
                                    
                                    @if ( (strtolower($method->code) == 'cod') )
                                        <div class="tab-pane fade" id="v-pills-cod" role="tabpanel" aria-labelledby="v-pills-cod-tab">Cash On Delivery</div>
                                    @endif

                                    @if ( (strtolower($method->code) == 'wallet') )
                                        <div class="tab-pane fade" id="v-pills-wallet" role="tabpanel" aria-labelledby="v-pills-wallet-tab">Wallet</div>
                                    @endif
                                    
                                    @if ( (strtolower($method->code) == 'loyalty-points') )
                                        <div class="tab-pane fade" id="v-pills-loyalty-points" role="tabpanel" aria-labelledby="v-pills-loyalty-points-tab">Loyality Points</div>
                                    @endif
                                @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        var stripe = Stripe('pk_test_51J0nVZSBx0AFwevbSTIDlYAaLjdsg4V4yoHpSo4BCZqGBzzGeU8Mnw1o0spfOYfMtyCXC11wEn6vBqbJeSNnAkw600U6jkzS3R');
        var elements = stripe.elements();
        
        // const token = stripe.tokens.create({
        //     card: {
        //         number: '4242424242424242',
        //         exp_month: 6,
        //         exp_year: 2022,
        //         cvc: '314',
        //     },
        // });
        // Custom styling can be passed to options when creating an Element.
        var style = {
            base: {
                // Add your base input styles here. For example:
                fontSize: '16px',
                color: '#32325d',
                borderColor: '#ced4da'
            },
        };

        // Create an instance of the card Element.
        var card = elements.create('card', {style: style});

        // Add an instance of the card Element into the `card-element` <div>.
        card.mount('#card-element');

        // Create a token or display an error when the form is submitted.
        var form = document.getElementById('stripe-payment-form');
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            stripe.createToken(card).then(function(result) {
                if (result.error) {
                // Inform the customer that there was an error.
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
                } else {
                // Send the token to your server.
                // stripeTokenHandler(result.token);

                $("#stripe_token").val(result.token.id);
                var amount = $("input[name='amount']").val();

                $.ajax({
                    type: "post",
                    url: "{{route('payment.stripe')}}",
                    data: {"_token": "{{ csrf_token() }}", 'stripe_token' : result.token.id, 'amount' : amount},
                    dataType: 'json',
                    success: function (resp) {
                        if(resp.success == 'false'){
                            alert(resp.msg);
                        }else{
                            $('#stripe-payment-form .form_fields').hide();
                            $('#stripe-payment-form .payment_resp').html('<h3>'+resp.msg+'<h3><h4>Transaction ID : '+resp.transactionReference+'</h4>');
                        }
                    },
                    error: function (resp) {
                        console.log('data2');
                    }
                });

                // form.submit();
                // console.log(result.token.id);
                }
            });
        });

        $('.digit-group').find('input').each(function() {
            $(this).attr('maxlength', 1);
            $(this).on('keyup', function(e) {
                var parent = $($(this).parent());
                
                if(e.keyCode === 8 || e.keyCode === 37) {
                    var prev = parent.find('input#' + $(this).data('previous'));
                    
                    if(prev.length) {
                        $(prev).select();
                    }
                } else if((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 65 && e.keyCode <= 90) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode === 39) {
                    var next = parent.find('input#' + $(this).data('next'));
                    
                    if(next.length) {
                        $(next).select();
                    } else {
                        if(parent.data('autosubmit')) {
                            parent.submit();
                        }
                    }
                }
            });
        });

        $(document).ready(function(){
            $(document).delegate("#paypal-payment-form .payment_btn", "click", function(){
                var amount = $("input[name='amount']").val();

                $.ajax({
                    type: "post",
                    url: "{{route('payment.paypal')}}",
                    data: {"_token": "{{ csrf_token() }}", 'amount' : amount},
                    dataType: 'json',
                    success: function (resp) {
                        if(resp.success == 'false'){
                            alert(resp.msg);
                        }else{

                            window.location = resp.redirect_url;

                            // $('#paypal-payment-form .form_fields').hide();
                            // $('#paypal-payment-form .payment_resp').html('<h3>'+resp.msg+'<h3><h4>Transaction ID : '+resp.transactionReference+'</h4>');
                        }
                    },
                    error: function (resp) {
                        console.log('data2');
                    }
                });
            });
        });

        function callRoute(route){
            window.location = route;
        }
    </script>

  

    
@endsection