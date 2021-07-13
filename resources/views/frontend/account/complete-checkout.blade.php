<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Complete Checkout</title>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/font-awesome.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/themify-icons.css')}}">
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/custom.css')}}">
</head>
<body>

<section class="section-b-space">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12"> 
                <h4><i class="fa fa-spinner fa-pulse mr-2"></i> Please wait until your payment is completed...</h4>
            </div>
        </div>
    </div>
</section>

<script src="{{asset('front-assets/js/jquery-3.3.1.min.js')}}"></script>
<script>
var payment_success_paypal_url = "{{route('payment.paypalSuccess')}}";
let path = window.location.pathname;
let queryString = window.location.search;
let urlParams = new URLSearchParams(queryString);
if( (urlParams.has('PayerID')) && (urlParams.has('token')) ){
    paymentSuccessViaPaypal(urlParams.get('amount'), urlParams.get('token'), urlParams.get('PayerID'), path);
}

function paymentSuccessViaPaypal(amount, token, payer_id, path){
    let address_id = 0;
    if(path.indexOf("cart") !== -1){
    }
    else if(path.indexOf("wallet") !== -1){
    }
    $.ajax({
        type: "GET",
        dataType: 'json',
        url: payment_success_paypal_url,
        data: {'amount': amount, 'token': token, 'PayerID': payer_id},
        success: function (response) {
            if(response.status == "Success"){
                if(path.indexOf("cart") !== -1){
                    placeOrder(address_id, 3, response.data);
                }
                else if(path.indexOf("wallet") !== -1){
                    creditWallet(amount, 3, response.data);
                }
            }else{
                if(path.indexOf("cart") !== -1){
                    success_error_alert('error', response.message, "#paypal-payment-form .payment_response");
                    $("#order_palced_btn, .proceed_to_pay").removeAttr("disabled");
                }
                else if(path.indexOf("wallet") !== -1){
                    success_error_alert('error', response.message, "#wallet_topup_form .payment_response");
                    $(".topup_wallet_confirm").removeAttr("disabled");
                }
            }
        },
        error: function(error){
            var response = $.parseJSON(error.responseText);
            if(path.indexOf("cart") !== -1){
                success_error_alert('error', response.message, "#paypal-payment-form .payment_response");
                $("#order_palced_btn, .proceed_to_pay").removeAttr("disabled");
            }
            else if(path.indexOf("wallet") !== -1){
                success_error_alert('error', response.message, "#wallet_topup_form .payment_response");
                $(".topup_wallet_confirm").removeAttr("disabled");
            }
        }
    });
}

</script>
</body>
</html>
