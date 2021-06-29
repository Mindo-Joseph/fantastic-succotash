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
        $total_delivery_fees = 0;
        $total_cash_to_collected = 0;
        $total_earnings_by_vendors = 0;
        $vendor_orders = OrderVendor::with(['orderDetail.paymentOption', 'user','vendor','payment'])->get();
        foreach ($vendor_orders as $vendor_order) {
            $total_delivery_fees+= $vendor_order->delivery_fee;
            $total_earnings_by_vendors+= $vendor_order->payable_amount;
            if($vendor_order->orderDetail->paymentOption->id == 1){
                $total_cash_to_collected += $vendor_order->payable_amount;
            }
        }
        $data = ['vendor_orders' => $vendor_orders, 'total_earnings_by_vendors' => $total_earnings_by_vendors, 'total_delivery_fees' => $total_delivery_fees, 'total_cash_to_collected' => $total_cash_to_collected];
        return $this->successResponse($data, '');
    }
}
