<?php

namespace App\Http\Controllers\Client\Accounting;
use DB;
use App\Models\User;
use App\Models\Vendor;
use App\Models\OrderVendor;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use App\Http\Controllers\Controller;

class VendorController extends Controller{
    use ApiResponser;
    public function index(Request $request){
        return view('backend.accounting.vendor');
    }

    public function filter(Request $request){
        $month_number = '';
        $month_picker_filter = $request->month_picker_filter;
        if($month_picker_filter){
            $temp_arr = explode(' ', $month_picker_filter);
            $month_number =  getMonthNumber($temp_arr[0]);
        }
        if($month_number){
            $total_order_value = OrderVendor::whereMonth('created_at', $month_number)->sum('payable_amount');
            $total_delivery_fees = OrderVendor::whereMonth('created_at', $month_number)->sum('delivery_fee');
            $total_admin_commissions = OrderVendor::whereMonth('created_at', $month_number)->sum(DB::raw('admin_commission_percentage_amount + admin_commission_fixed_amount'));
        }else{
            $total_order_value = OrderVendor::sum('payable_amount');
            $total_delivery_fees = OrderVendor::sum('delivery_fee');
            $total_admin_commissions = OrderVendor::sum(DB::raw('admin_commission_percentage_amount + admin_commission_fixed_amount'));
        }
        $vendors = Vendor::with(['orders' => function($query) use($month_number) {
            if($month_number){
                $query->whereMonth('created_at', $month_number);
            }
        }])->get();
        foreach ($vendors as $vendor) {
            $vendor->url = route('vendor.show', $vendor->id);
            $vendor->order_value = $vendor->orders->sum('payable_amount');
            $vendor->delivery_fee = $vendor->orders->sum('delivery_fee');
        }
        $data = ['vendors' => $vendors, 'total_order_value' => $total_order_value, 'total_delivery_fees' => $total_delivery_fees, 'total_admin_commissions' => $total_admin_commissions];
        return $this->successResponse($data, '');
    }
}
