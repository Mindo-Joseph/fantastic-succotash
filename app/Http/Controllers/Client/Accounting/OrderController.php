<?php

namespace App\Http\Controllers\Client\Accounting;

use App\Models\OrderVendor;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrderVendorListTaxExport;

class OrderController extends Controller{
    use ApiResponser;
    public function index(Request $request){
        return view('backend.accounting.order');
    }
    public function filter(Request $request){
        $user = Auth::user();
        $total_delivery_fees = 0;
        $total_cash_to_collected = 0;
        $total_earnings_by_vendors = 0;
        $timezone = $user->timezone ? $user->timezone : 'Asia/Kolkata';
        $vendor_orders = OrderVendor::with(['orderDetail.paymentOption', 'user','vendor','payment'])->get();
        foreach ($vendor_orders as $vendor_order) {
            $total_delivery_fees+= $vendor_order->delivery_fee;
            $total_earnings_by_vendors+= $vendor_order->payable_amount;
            if($vendor_order->orderDetail){
                if($vendor_order->orderDetail->paymentOption->id == 1){
                    $total_cash_to_collected += $vendor_order->payable_amount;
                }
            }
            $vendor_order->created_date = convertDateTimeInTimeZone($vendor_order->created_at, $timezone, 'Y-m-d h:i:s A');
        }
        $data = ['vendor_orders' => $vendor_orders, 'total_earnings_by_vendors' => number_format($total_earnings_by_vendors, 2), 'total_delivery_fees' => number_format($total_delivery_fees, 2), 'total_cash_to_collected' => number_format($total_cash_to_collected, 2)];
        return $this->successResponse($data, '');
    }

    public function export() {
        return Excel::download(new OrderVendorListTaxExport, 'order_list.xlsx');
    }
}
