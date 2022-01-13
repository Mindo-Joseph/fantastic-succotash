<?php

namespace App\Http\Controllers\Front;

use Auth;
use Omnipay\Omnipay;
use Illuminate\Http\Request;
use Omnipay\Common\CreditCard;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Front\FrontController;
use App\Models\{PaymentOption, Client, ClientPreference, ClientCurrency};

class PaystackGatewayController extends FrontController
{
    use ApiResponser;
    public $gateway;
    public $currency;

    public function __construct()
    {
        $paystack_creds = PaymentOption::select('credentials', 'test_mode')->where('code', 'paystack')->where('status', 1)->first();
        $creds_arr = json_decode($paystack_creds->credentials);
        $secret_key = (isset($creds_arr->secret_key)) ? $creds_arr->secret_key : '';
        $public_key = (isset($creds_arr->public_key)) ? $creds_arr->public_key : '';
        $testmode = (isset($paystack_creds->test_mode) && ($paystack_creds->test_mode == '1')) ? true : false;
        $this->gateway = Omnipay::create('Paystack');
        $this->gateway->setSecretKey($secret_key);
        $this->gateway->setPublicKey($public_key);
        $this->gateway->setTestMode($testmode); //set it to 'false' when go live
        // dd($this->gateway);

        $primaryCurrency = ClientCurrency::where('is_primary', '=', 1)->first();
        $this->currency = (isset($primaryCurrency->currency->iso_code)) ? $primaryCurrency->currency->iso_code : 'USD';
    }

    public function paystackPurchase(Request $request){
        try{
            $user = Auth::user();
            $amount = $this->getDollarCompareAmount($request->amount);
            $returnUrlParams = '?amount='.$amount;
            if($request->has('tip')){
                $returnUrlParams = $returnUrlParams.'&tip='.$request->tip.'&gateway=paystack';
            }
            if ($request->has('order_number')) {
                $returnUrlParams = $returnUrlParams . '&ordernumber=' . $request->order_number;
            }
            $returnUrlParams = $returnUrlParams.'&gateway=paystack';
            $response = $this->gateway->purchase([
                'amount' => $amount,
                'currency' => 'ZAR', //$this->currency,
                'email' => $user->email,
                'returnUrl' => url($request->returnUrl . $returnUrlParams),
                'cancelUrl' => url($request->cancelUrl),
                'metadata' => ['user_id' => $user->id],
                'description' => 'This is a test purchase transaction.',
            ])->send();
            if ($response->isSuccessful()) {
                return $this->successResponse($response->getData());
            }
            elseif ($response->isRedirect()) {
                $this->failMail();
                return $this->successResponse($response->getRedirectUrl());
            }
            else {
                $this->failMail();
                return $this->errorResponse($response->getMessage(), 400);
            }
        }
        catch(\Exception $ex){
            $this->failMail();
            return $this->errorResponse($ex->getMessage(), 400);
        }
    }

    public function paystackCompletePurchase(Request $request)
    {
        // Once the transaction has been approved, we need to complete it.
        if($request->has(['reference'])){
            $amount = $this->getDollarCompareAmount($request->amount);
            $transaction = $this->gateway->completePurchase(array(
                'amount'                => $amount,
                'transactionReference'  => $request->reference
            ));
            $response = $transaction->send();
            if ($response->isSuccessful()){
            //    $this->successMail();
                return $this->successResponse($response->getTransactionReference());
            } else {
                $this->failMail();
                return $this->errorResponse($response->getMessage(), 400);
            }
        } else {
            $this->failMail();
            return $this->errorResponse('Transaction has been declined', 400);
        }
    }


    public function paystackCompletePurchaseApp(Request $request)
    {
        // Once the transaction has been approved, we need to complete it.
        if($request->has(['reference'])){
            $amount = $this->getDollarCompareAmount($request->amount);
            $transaction = $this->gateway->completePurchase(array(
                'amount'                => $amount,
                'transactionReference'  => $request->reference
            ));
            $response = $transaction->send();
            if ($response->isSuccessful()){
            //    $this->successMail();
                $url = 'payment/gateway/returnResponse?status=200&gateway=paystack&action='.$request->action.'&transaction_id='.$response->getTransactionReference();
                return Redirect::to($url);
            } else {
                // $this->failMail();
                $url = 'payment/gateway/returnResponse?status=0&gateway=paystack&action='.$request->action;
                return Redirect::to($url);
            }
        } else {
            // $this->failMail();
            return $this->errorResponse('Transaction has been declined', 400);
        }
    }
}
