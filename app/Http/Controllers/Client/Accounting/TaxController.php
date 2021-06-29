<?php

namespace App\Http\Controllers\Client\Accounting;

use App\Models\OrderVendor;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class TaxController extends Controller
{
    public function index(Request $request){
        return view('backend.accounting.tax');
    }
    public function filter(Request $request){
        $user = Auth::user();
        $total_tax_collected = 0;;
        $type_of_taxes_applied_count = 0;;
        $timezone = $user->timezone ? $user->timezone : 'Asia/Kolkata';
        $vendor_orders = OrderVendor::with(['orderDetail.paymentOption', 'user','vendor','payment'])->get();
        foreach ($vendor_orders as $vendor_order) {
            $total_tax_collected+= $vendor_order->taxable_amount;
            $vendor_order->created_date = convertDateTimeInTimeZone($vendor_order->created_at, $timezone, 'Y-m-d h:i:s A');
        }
        $data = ['vendor_orders' => $vendor_orders, 'total_tax_collected'=> $total_tax_collected, 'type_of_taxes_applied_count' => $type_of_taxes_applied_count];
        return $this->successResponse($data, '');
    }
}
