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

class PaystackGatewayController extends Controller
{
    use ApiResponser;
    public $gateway;

    public function __construct()
    {
        $paystack_creds = PaymentOption::select('credentials')->where('code', 'paystack')->where('status', 1)->first();
        $creds_arr = json_decode($paystack_creds->credentials);
        $secret_key = (isset($creds_arr->secret_key)) ? $creds_arr->secret_key : '';
        $public_key = (isset($creds_arr->public_key)) ? $creds_arr->public_key : '';
        $this->gateway = Omnipay::create('Paystack');
        $this->gateway->setSecretKey($secret_key);
        $this->gateway->setPublicKey($public_key);
        $this->gateway->setTestMode(true); //set it to 'false' when go live
        dd($this->gateway);
    }

    public function paystackPurchase(Request $request){
        try{
            $user = Auth::user();
            // $token = $request->paystack_token;
            $response = $this->gateway->purchase([
                'amount' => $request->amount,
                'currency' => 'USD',
                'email' => $user->email,
                // 'callback_url' => $request->returnUrl,
                'metadata' => ['user_id' => $user->id],
                'description' => 'This is a test purchase transaction.',
            ])->send();
            if ($response->isSuccessful()) {
                return $this->successResponse($response->getData());
            } 
            // elseif ($response->isRedirect()) {
            //     return $this->errorResponse($response->getRedirectUrl(), 400);
            // } 
            else {
                return $this->errorResponse($response->getMessage(), 400);
            }
        }
        catch(\Exception $ex){
            return $this->errorResponse($ex->getMessage(), 400);
        }
    }

    /**
     * Obtain Paystack payment information
     * @return void
     */
    public function handleGatewayCallback()
    {
        // $paymentDetails = Paystack::getPaymentData();

        // dd($paymentDetails);
        // Now you have the payment details,
        // you can store the authorization_code in your db to allow for recurrent subscriptions
        // you can then redirect or do whatever you want
    }
}
