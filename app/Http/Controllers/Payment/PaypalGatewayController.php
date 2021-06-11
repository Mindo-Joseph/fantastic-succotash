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

class PaypalGatewayController extends Controller
{
    public $gateway;
    // public $amount;

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

    public function paypalCharge(Request $request)
    {
        $message = '';

        if($request->input('submit'))
        {
            try {
                $response = $this->gateway->purchase(array(
                    'amount' => $request->input('amount'),
                    'currency' => 'USD',
                    'returnUrl' => route('payment.paypalSuccess'),
                    'cancelUrl' => route('payment.paypalError'),
                ))->send();
          
                if ($response->isRedirect()) {
                    $response->redirect(); // this will automatically forward the customer
                } else {
                    // not successful
                    // return $response->getMessage();
                    $message = $response->getMessage();
                }
            } catch(Exception $e) {
                // return $e->getMessage();
                $message = $ex->getMessage();
            }
        // }

        return view('gateway')->with('setupResponse', $message);
    }

    public function paypalSuccess(Request $request)
    {
        $message = '';

        // Once the transaction has been approved, we need to complete it.
        if ($request->input('token') && $request->input('PayerID'))
        {
            $transaction = $this->gateway->completePurchase(array(
                'amount'                => $request->input('amount'),
                'payer_id'              => $request->input('PayerID'),
                'transactionReference'  => $request->input('token'),
            ));
            $response = $transaction->send();
         
            if ($response->isSuccessful())
            {
                // The customer has successfully paid.
                $arr_body = $response->getData();
         
                // Insert transaction data into the database
         
                // return "Payment is successful. Your transaction id is: ". $arr_body['TOKEN'];
                $message = "Payment is successful. Your transaction id is: ". $arr_body['TOKEN'];
            } else {
                // return $response->getMessage();
                $message = $response->getMessage();
            }
        } else {
            // return 'Transaction is declined';
            $message = 'Transaction is declined';
        }

        return view('gateway')->with('setupResponse', $message);
    }

    public function paypalError(Request $request)
    {
        $message = '';

        dd($request);
        exit;

        return view('gateway')->with('setupResponse', $message);
    }
}
