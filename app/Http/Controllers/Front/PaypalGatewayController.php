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

    public function postPaymentViaPaypal(Request $request)
    {
        $paypal_creds = PaymentOption::select('credentials')->where('code', 'paypal')->where('status', 1)->first();
        $creds_arr = json_decode($paypal_creds->credentials);

        $username = (isset($creds_arr->username)) ? $creds_arr->username : '';
        $password = (isset($creds_arr->password)) ? $creds_arr->password : '';
        $signature = (isset($creds_arr->signature)) ? $creds_arr->signature : '';

        $gateway = Omnipay::create('PayPal_Express');
        $gateway->setUsername($username);
        $gateway->setPassword($password);
        $gateway->setSignature($signature);
        $gateway->setTestMode(true); //set it to 'false' when go live
        try{
            $response = $gateway->purchase([
                'amount' => $request->input('amount'),
                'currency' => 'USD',
                'returnUrl' => url('/viewcart'),
                'cancelUrl' => url('/viewcart')
            ])->send();

            if ($response->isSuccessful()) {
                return $this->successResponse(['status' => 'success', 'response' => $response->getData()]);
            } elseif ($response->isRedirect()) {
                return $this->successResponse(['status' => 'success', 'response' => $response->getRedirectUrl()]);
            } else {
                return $this->errorResponse(['status' => 'error', 'message'=>$response->getMessage()], 400);
            }
        }
        catch(\Exception $ex){
            return $this->errorResponse(['status' => 'error', 'message'=>$ex->getMessage()]);
        }
    }

    public function paypalSuccess(Request $request)
    {
        // Once the transaction has been approved, we need to complete it.
        if($request->has(['token', 'PayerID'])){
            $gateway = Omnipay::create('PayPal_Express');
            $gateway->setUsername('sb-r6ryi6463363_api1.business.example.com');
            $gateway->setPassword('2WT35LCJ73SYWLMD');
            $gateway->setSignature('Ai9cuHQXupERagE016AbIPpQXy9fAgblu9y2ZXrzYkt1e0GUY.EPoJBl');
            $gateway->setTestMode(true); //set it to 'false' when go live

            $transaction = $gateway->completePurchase(array(
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
         
                // $message = "Payment is successful. Your transaction id is: ". $arr_body['TOKEN'];
                return $this->successResponse(['status' => 'success', 'id' => $response->getTransactionReference()]);
            } else {
                $message = $response->getMessage();
                return $this->errorResponse(['status' => 'error', 'message' => $response->getMessage()]);
            }
        } else {
            return $this->errorResponse(['status' => 'error', 'message' => 'Transaction is declined']);
        }
    }
}
