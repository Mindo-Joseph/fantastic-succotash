<?php

namespace App\Http\Controllers\Payments;

use Auth;
use Session;
use Password;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Notifications\PasswordReset;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\{PaymentOption};
use Omnipay\Omnipay;
use Omnipay\Common\CreditCard;

class StripeGatewayController extends Controller
{
    public $gateway;

    public function __construct()
    {
        $paypal_creds = PaymentOption::select('credentials')->where('code', 'stripe')->where('status', 1)->first();
        $creds_arr = json_decode($paypal_creds->credentials);

        $api_key = (isset($creds_arr->api_key)) ? $creds_arr->api_key : '';

        $this->gateway = Omnipay::create('Stripe');
        $this->gateway->setApiKey($api_key);
        $this->gateway->setTestMode(true); //set it to 'false' when go live
    }

    public function stripeCharge(request $request)
    {
        $message = $html = $transactionReference = '';
        
        try{
            $token = $request->input('stripe_token');
            
            // Send purchase request
            $response = $this->gateway->purchase(
                [
                    'amount' => $request->input('amount'),
                    'currency' => 'INR',
                    'description' => 'This is a test purchase transaction.',
                    'token' => $token,
                    'testMode' => true,
                    // 'returnUrl' => route('demo'),
                    // 'cancelUrl' => route('demo')
                ]
            )->send();

            // Process response
            if ($response->isSuccessful()) {

                // $html = file_get_contents($response->getData()['receipt_url']);
                return response()->json(array('success' => true, 'transactionReference'=>$response->getTransactionReference(), 'msg'=>"Thankyou for your payment"));

            } elseif ($response->isRedirect()) {
                
                // Redirect to offsite payment gateway
                return response()->json(array('success' => true, 'redirect_url'=>$response->getRedirectUrl(), 'msg'=>''));

            } else {
                // Payment failed
                return response()->json(array('success' => false, 'msg'=>$response->getMessage()));
            }
        }
        catch(\Exception $ex){
            return response()->json(array('success' => false, 'msg'=>$ex->getMessage()));
        }
    }
}
