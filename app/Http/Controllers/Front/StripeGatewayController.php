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
class StripeGatewayController extends Controller{

    use ApiResponser;

    public $gateway;

    public function postPaymentViaStripe(request $request){
        $paypal_creds = PaymentOption::select('credentials')->where('code', 'stripe')->where('status', 1)->first();
        $creds_arr = json_decode($paypal_creds->credentials);
        $api_key = (isset($creds_arr->api_key)) ? $creds_arr->api_key : 'sk_test_51J0nVZSBx0AFwevbyrp3ocFS2NMHiCZ5tE0VkUDwCDre9KKbPP608R2jJtkVlov89yTteY0lAmKXo7nwUBOdWczT006D8uMeqL';
        try{
            $gateway = Omnipay::create('Stripe');
            $token = $request->input('stripe_token');
            $gateway->setApiKey($api_key);
            $gateway->setTestMode(true);
            $response = $gateway->purchase([
                                'currency' => 'INR',
                                'token' => $token,
                                'amount' => $request->input('amount'),
                                'metadata' => ['order_id' => "dfngjdfbgjbd"],
                                'description' => 'This is a test purchase transaction.',
                        ])->send();
            if ($response->isSuccessful()) {
                return $this->successResponse(['status' => 'success', 'response' => $response->getData()]);
            } elseif ($response->isRedirect()) {
                return $this->errorResponse(['status' => 'error', 'response' => $response->getRedirectUrl()], 400);
                // return response()->json();
            } else {
                return $this->errorResponse(['status' => 'error', 'message'=>$response->getMessage()], 400);
            }
        }catch(\Exception $ex){
            return $this->errorResponse($ex->getMessage(), $ex->getCode());
        }
    }

}
