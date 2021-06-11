<?php

namespace App\Http\Controllers\Payments;

use Auth;
use Session;
use Password;
use Carbon\Carbon;
use Omnipay\Omnipay;
use Illuminate\Http\Request;
use Omnipay\Common\CreditCard;
use App\Models\{PaymentOption};
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use App\Notifications\PasswordReset;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class StripeGatewayController extends Controller{
    use ApiResponser;
    public $gateway;

    public function __construct(){
        $paypal_creds = PaymentOption::select('credentials')->where('code', 'stripe')->where('status', 1)->first();
        $creds_arr = json_decode($paypal_creds->credentials);
        $api_key = (isset($creds_arr->api_key)) ? $creds_arr->api_key : '';
        $this->gateway = Omnipay::create('Stripe');
        $this->gateway->setApiKey($api_key);
        $this->gateway->setTestMode(true);
    }
    public function stripeCharge(request $request){
        try{
            $token = $request->input('stripe_token');
            $response = $this->gateway->purchase([
                    'token' => $token,
                    'testMode' => true,
                    'currency' => 'INR',
                    'amount' => $request->input('amount'),
                    'description' => 'This is a test purchase transaction.',
                ])->send();
            if ($response->isSuccessful()) {
                return response()->json(array('success' => true, 'transactionReference'=>$response->getTransactionReference(), 'msg'=>"Thankyou for your payment"));
            } elseif ($response->isRedirect()) {
                return response()->json(array('success' => true, 'redirect_url'=>$response->getRedirectUrl(), 'msg'=>''));
            } else {
                return response()->json(array('success' => false, 'msg'=>$response->getMessage()));
            }
        }catch(\Exception $ex){
            return response()->json(array('success' => false, 'msg'=>$ex->getMessage()));
        }
    }
}
