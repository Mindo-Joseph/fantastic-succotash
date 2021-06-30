<?php

namespace App\Http\Controllers\Client\Accounting;
use DB;
use DataTables;
use App\Models\User;
use App\Models\Vendor;
use App\Models\OrderVendor;
use Illuminate\Support\Str; 
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use App\Http\Controllers\Controller;

class VendorController extends Controller{
    use ApiResponser;

    public function index(Request $request){
        $total_order_value = OrderVendor::sum('payable_amount');
        $total_delivery_fees = OrderVendor::sum('delivery_fee');
        $total_admin_commissions = OrderVendor::sum(DB::raw('admin_commission_percentage_amount + admin_commission_fixed_amount'));
        return view('backend.accounting.vendor')->with(['total_order_value' => number_format($total_order_value, 2), 'total_delivery_fees' => number_format($total_delivery_fees, 2), 'total_admin_commissions' => number_format($total_admin_commissions, 2)]);
    }

    public function filter(Request $request){
        $month_number = '';
        $month_picker_filter = $request->month_picker_filter;
        if($month_picker_filter){
            $temp_arr = explode(' ', $month_picker_filter);
            $month_number =  getMonthNumber($temp_arr[0]);
        }
        $vendors = Vendor::with(['orders' => function($query) use($month_number) {
            if($month_number){
                $query->whereMonth('created_at', $month_number);
            }
        }])->where('status', '!=', '2')->orderBy('id', 'desc')->get();
        foreach ($vendors as $vendor) {
            $vendor->total_paid = 0.00;
            $vendor->url = route('vendor.show', $vendor->id);
            $vendor->view_url = route('vendor.show', $vendor->id);
            $vendor->delivery_fee = number_format($vendor->orders->sum('delivery_fee'), 2);
            $vendor->order_value = number_format($vendor->orders->sum('payable_amount'),2);
            $vendor->vendor_earning = number_format(($vendor->orders->sum('payable_amount') - $vendor->delivery_fee), 2);
            $vendor->payment_method = number_format($vendor->orders->whereIn('payment_option_id', [2,3, 4])->sum('payable_amount'), 2);
            $vendor->promo_admin_amount = number_format($vendor->orders->where('coupon_paid_by', 1)->sum('discount_amount'), 2);
            $vendor->promo_vendor_amount = number_format($vendor->orders->where('coupon_paid_by', 0)->sum('discount_amount'), 2);
            $vendor->cash_collected_amount = number_format($vendor->orders->where('payment_option_id', 1)->sum('payable_amount'), 2);
            $vendor->admin_commission_amount = number_format($vendor->orders->sum('admin_commission_percentage_amount')+ $vendor->orders->sum('admin_commission_percentage_amount'), 2);
        }
        return Datatables::of($vendors)
            ->addIndexColumn()
            ->filter(function ($instance) use ($request) {
                if (!empty($request->get('search'))) {
                    $instance->collection = $instance->collection->filter(function ($row) use ($request){
                        if (Str::contains(Str::lower($row['name']), Str::lower($request->get('search')))){
                            return true;
                        }
                        return false;
                    });
                }
            })->make(true);
    }
}
