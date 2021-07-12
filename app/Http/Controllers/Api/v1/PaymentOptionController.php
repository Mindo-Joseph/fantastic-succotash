<?php

namespace App\Http\Controllers\Api\v1;
use Illuminate\Http\Request;
use App\Models\PaymentOption;
use App\Http\Traits\ApiResponser;
use App\Http\Controllers\Controller;

class PaymentOptionController extends Controller{
    use ApiResponser;

    public function getPaymentOptions(Request $request){
        $code = array('paypal', 'stripe');
        $payment_options = PaymentOption::whereIn('code', $code)->get(['id', 'title']);
        return $this->successResponse($payment_options, '', 201);
    }
}
