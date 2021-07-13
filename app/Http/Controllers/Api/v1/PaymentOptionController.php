<?php

namespace App\Http\Controllers\Api\v1;
use Omnipay\Omnipay;
use Illuminate\Http\Request;
use App\Models\PaymentOption;
use Omnipay\Common\CreditCard;
use App\Http\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\v1\OrderController;
use App\Http\Controllers\Api\v1\WalletController;

class PaymentOptionController extends Controller{
    use ApiResponser;
    public $gateway;

    public function getPaymentOptions(Request $request){
        $code = array('paypal', 'stripe');
        $payment_options = PaymentOption::whereIn('code', $code)->get(['id', 'title', 'off_site']);
        return $this->successResponse($payment_options, '', 201);
    }

    public function postPayment(Request $request, $gateway = ''){
        if(!empty($gateway)){
            $function = 'postPaymentVia_'.$gateway;
            if(method_exists($this, $function)) {
                if(!empty($request->action)){
                    $response = $this->$function($request); // call related gateway for payment processing
                    // if($response->status == 'Success'){
                    //     if($gateway != 'paypal'){
                    //         $request->transaction_id = $response->data;
                    //         if($request->action == 'cart'){
                    //             $orderResponse = OrderController::postPlaceOrder($request);
                    //         }
                    //         else if($request->action == 'wallet'){
                    //             $walletResponse = WalletController::creditMyWallet($request);
                    //         }
                    //     }
                    // }
                    return $response;
                }
            }
            else{
                return $this->errorResponse("Invalid Gateway Request", 400);
            }
        }else{
            return $this->errorResponse("Invalid Gateway Request", 400);
        }
    }

    public function postPaymentVia_paypal(Request $request){
        try{
            $paypal_creds = PaymentOption::select('credentials')->where('code', 'paypal')->where('status', 1)->first();
            $creds_arr = json_decode($paypal_creds->credentials);
            $username = (isset($creds_arr->username)) ? $creds_arr->username : '';
            $password = (isset($creds_arr->password)) ? $creds_arr->password : '';
            $signature = (isset($creds_arr->signature)) ? $creds_arr->signature : '';
            $this->gateway = Omnipay::create('PayPal_Express');
            $this->gateway->setUsername($username);
            $this->gateway->setPassword($password);
            $this->gateway->setSignature($signature);
            $this->gateway->setTestMode(true); //set it to 'false' when go live
            $response = $this->gateway->purchase([
                'currency' => 'USD',
                'amount' => $request->amount,
                'cancelUrl' => url($request->cancelUrl),
                'returnUrl' => url($request->returnUrl . '?amount='.$request->amount),
            ])->send();
            if ($response->isSuccessful()) {
                return $this->successResponse($response->getData());
            } elseif ($response->isRedirect()) {
                return $this->successResponse($response->getRedirectUrl());
            } else {
                return $this->errorResponse($response->getMessage(), 400);
            }
        }
        catch(\Exception $ex){
            return $this->errorResponse($ex->getMessage(), 400);
        }
    }

    public function postPaymentVia_stripe(Request $request){
        try{
            $stripe_creds = PaymentOption::select('credentials')->where('code', 'stripe')->where('status', 1)->first();
            $creds_arr = json_decode($stripe_creds->credentials);
            $api_key = (isset($creds_arr->api_key)) ? $creds_arr->api_key : '';
            $this->gateway = Omnipay::create('Stripe');
            $this->gateway->setApiKey($api_key);
            $this->gateway->setTestMode(true); //set it to 'false' when go live
            $token = $request->stripe_token;
            $response = $this->gateway->purchase([
                'currency' => 'INR',
                'token' => $token,
                'amount' => $request->amount,
                'metadata' => ['order_id'=>'11'],
                'description' => 'Transaction type purchase',
            ])->send();
            if ($response->isSuccessful()) {
                return $this->successResponse($response->getTransactionReference());
            }
            else {
                return $this->errorResponse($response->getMessage(), 400);
            }
        }catch(\Exception $ex){
            return $this->errorResponse($ex->getMessage(), 400);
        }
    }
}
