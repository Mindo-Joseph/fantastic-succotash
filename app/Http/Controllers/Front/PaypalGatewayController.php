<?php

namespace App\Http\Controllers\Front;

use Auth;
use Omnipay\Omnipay;
use Illuminate\Http\Request;
use Omnipay\Common\CreditCard;
use App\Models\{PaymentOption};
use App\Http\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PaypalGatewayController extends Controller
{
    use ApiResponser;
    public $gateway;

    public function __construct()
    {
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
    }

    public function paypalPurchase(Request $request){
        try{
            $response = $this->gateway->purchase([
                'currency' => 'USD',
                'amount' => $request->input('amount'),
                'cancelUrl' => url($request->cancelUrl),
                'returnUrl' => url($request->returnUrl . '?amount=' . $request->input('amount')),
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

    public function paypalCompletePurchase(Request $request)
    {
        // Once the transaction has been approved, we need to complete it.
        if($request->has(['token', 'PayerID'])){
            $transaction = $this->gateway->completePurchase(array(
                'amount'                => $request->amount,
                'payer_id'              => $request->PayerID,
                'transactionReference'  => $request->token
            ));
            $response = $transaction->send();
            if ($response->isSuccessful()){
                return $this->successResponse($response->getTransactionReference());
            } else {
                return $this->errorResponse($response->getMessage(), 400);
            }
        } else {
            return $this->errorResponse('Transaction has been declined', 400);
        }
    }
}
