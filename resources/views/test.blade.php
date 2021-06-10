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
                </div
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
                </div
            </div>
        </div>
    </section> 

    <!-- Modal -->
    <div class="modal fade" id="pay-bill"  data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="pay-billLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">           
                <div class="modal-body p-0">
                    <div class="row no-gutters pr-3">
                        <div class="col-4">
                            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">Debit/Credit Card</a>
                            <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">Wallets</a>
                            <a class="nav-link" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false">loyalty</a>
                            <a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false">loyalty</a>
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="tab-content-box pl-3">
                                <h5 class="modal-title pt-4" id="pay-billLabel">Pay Bill</h5>
                                <button type="button" class="close top_right" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <div class="tab-content h-100" id="v-pills-tabContent">
                                    <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <form action="">
                                                    <div class="row form-group">
                                                        <div class="col-sm-8">
                                                            <label for="">Name</label>
                                                            <input class="form-control" type="text">
                                                        </div>                                                    
                                                    </div>
                                                    <div class="row form-group">
                                                        <div class="col-sm-4">
                                                            <label for="">Card Number</label>
                                                            <input class="form-control" type="text">
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <div class="row align-items-end">
                                                                <div class="col-6">
                                                                    <label for="">EXPIRATION DATE</label>
                                                                    <input class="form-control" type="text">
                                                                </div>
                                                                <div class="col-4">
                                                                    <label for="">CVV</label>
                                                                    <input class="form-control" type="text">
                                                                </div>
                                                                <div class="col-2 pb-1">
                                                                    <i class="fa fa-credit-card-alt" aria-hidden="true"></i>
                                                                </div>
                                                            </div>
                                                        </div>                                                        
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="row mt-5">
                                            <div class="col-md-12 text-md-right">
                                                <button type="button" class="btn btn-solid" data-dismiss="modal">Cancel</button>
                                                <button type="button" class="btn btn-solid ml-1">Continue to Pay</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                       
                                    </div>
                                    <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">...</div>
                                    <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">...</div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
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
    </script>

  

    
@endsection