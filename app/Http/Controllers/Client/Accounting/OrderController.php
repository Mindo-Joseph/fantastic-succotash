<?php

namespace App\Http\Controllers\Client\Accounting;
use App\Models\OrderVendor;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use App\Http\Controllers\Controller;

class OrderController extends Controller{
    use ApiResponser;
    public function index(Request $request){
        return view('backend.accounting.order');
    }
    public function filter(Request $request){
        $total_order_count = 0;
        $total_delivery_fees = 0;
        $total_cash_to_collected = 0;
        $total_earnings_by_vendors = 0;
        $vendor_orders = OrderVendor::with(['orderDetail.paymentOption', 'user','vendor','payment'])->get();
        $data = ['vendor_orders' => $vendor_orders, 'total_order_count' => $total_order_count, 'total_delivery_fees' => $total_delivery_fees];
        return $this->successResponse($data, '');
    }
}
