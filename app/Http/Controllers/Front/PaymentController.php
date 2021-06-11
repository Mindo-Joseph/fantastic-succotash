<?php

namespace App\Http\Controllers\Front;

use Auth;
use Omnipay\Omnipay;
use App\Models\Payment;
use App\Models\PaymentOption;
use Illuminate\Http\Request;
use App\Models\{Order, User};
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Front\FrontController;


class PaymentController extends FrontController{

    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $payment_options = PaymentOption::select('id', 'code', 'title')->where('status', 1)->get();
        foreach ($payment_options as $payment_option) {
           $payment_option->slug = strtolower(str_replace(' ', '_', $payment_option->title));
        }
        return $this->successResponse($payment_options);
    }
    public function showForm()
    {
        return view('backend/stripe/form');
    }

    public function showFormApp($domain='',$token = '')
    {
        $uid = 0;
        if(Auth::user()){
            $uid = Auth::user()->id;
        }elseif(!empty($token)){
            $userCart = Cart::where('uniqueIdenteri', $token)->first();
            if(!$userCart){
                // sent to error page
            }
            $uid = $userCart->user_id;
        }else{
            // sent to error page
        }

        $user = User::select()->where('id', $uid);

        $userId = Auth::user()->id;
        // dd($token);
        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);
        return view('frontend.payment')->with(['navCategories' => $navCategories]);
    }

    public function makePayment(Request $request)
    {
        // dd($request->all());

        $token = $request->stripeToken;

        // Setup payment gateway
        $gateway = Omnipay::create('Stripe');
        $gateway->setApiKey('sk_test_51IhpwhSFHEA938FwRPiQSAH5xF6DcjO5GCASiud9cGMJ0v8UJyRfCb7IQAMbXbuPMe7JphA1izxZOsIclvmOgqUV00Zpk85xfl');

        // Example form data
        $formData = [
            'number' => '4000056655665556',
            'description' => '4242424242424242',
            'expiryMonth' => '6',
            'expiryYear' => '2026',
            'cvv' => '123'
        ];

        // try {

        // Send purchase request
        $response = $gateway->purchase(
            [
                'amount' => '10.00',
                'currency' => 'INR',
                'card' => $formData,
                'token' => $token,
            ]
        )->send();

            if ($response->isSuccessful()) {
                // mark order as complete
                dd("successfull");
            } elseif ($response->isRedirect()) {
                $response->redirect();
            } else {
                // display error to customer
                exit($response->getMessage());
            }
       
    }
}
