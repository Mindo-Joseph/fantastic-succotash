<?php

namespace App\Http\Controllers\Api\v1;
use Omnipay\Omnipay;
use Illuminate\Http\Request;
use App\Models\PaymentOption;
use Omnipay\Common\CreditCard;
use App\Http\Traits\ApiResponser;
use App\Http\Controllers\Controller;

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
                $response = $this->$function($request);
                return $response;
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
            $token = $request->stripe_token;
            $response = $this->gateway->purchase([
                'currency' => 'USD',
                'token' => $token,
                'amount' => $request->amount,
                'metadata' => [],
                'description' => 'Transaction type purchase',
            ])->send();
            if ($response->isSuccessful()) {
                return $this->successResponse($response->getData());
            }
            else {
                return $this->errorResponse($response->getMessage(), 400);
            }
        }catch(\Exception $ex){
            return $this->errorResponse($ex->getMessage(), 400);
        }
    }
}
