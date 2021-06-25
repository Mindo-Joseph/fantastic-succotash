<?php

namespace App\Http\Controllers\Client\Accounting;
use App\Models\OrderVendor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request){
    	$delivery_fee_sum = OrderVendor::sum('delivery_fee');
        return view('backend/accounting/order', compact('delivery_fee_sum'));
    }
}
