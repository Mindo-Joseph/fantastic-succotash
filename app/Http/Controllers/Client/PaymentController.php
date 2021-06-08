<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Omnipay\Omnipay;

class PaymentController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = array();
        return view('backend/payment/index')->with(['payments' => $payments]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        //
    }

    public function showForm()
    {
        return view('backend/stripe/form');
    }

    public function showFormApp($domain='',$token)
    {
        // dd($token);
        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);
        return view('frontend/payment')->with(['navCategories' => $navCategories]);
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
