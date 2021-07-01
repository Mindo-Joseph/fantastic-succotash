<?php

namespace App\Http\Controllers\Client\Accounting;
use DataTables;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\LoyaltyCard;
use Illuminate\Support\Str; 
use Illuminate\Http\Request;
use App\Models\PaymentOption;
use App\Models\TaxCategory;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrderVendorTaxExport;

class TaxController extends Controller{


    public function index(Request $request){
        $tax_category_options = TaxCategory::get();
        $total_tax_collected = Order::sum('taxable_amount');
        $payment_options = PaymentOption::where('status', 1)->get();
        return view('backend.accounting.tax', compact('total_tax_collected','payment_options','tax_category_options'));
    }

    public function filter(Request $request){
        $user = Auth::user();
        $total_tax_collected = 0;;
        $type_of_taxes_applied_count = 0;;
        $timezone = $user->timezone ? $user->timezone : 'Asia/Kolkata';
        $orders_query = Order::with('user','paymentOption');
        $orders = $orders_query->orderBy('id', 'desc')->get();
        foreach ($orders as $order) {
            $order->payment_method = $order->paymentOption ? $order->paymentOption->title : '';
            $order->tax_types = $order->loyalty_points_earned ? $order->loyalty_points_earned : '0.00';
            $order->customer_name = $order->user ? $order->user->name : '-';
            $order->created_date = convertDateTimeInTimeZone($order->created_at, $timezone, 'Y-m-d h:i:s A');
        }
        return Datatables::of($orders)
        ->addIndexColumn()
        ->filter(function ($instance) use ($request) {
            if (!empty($request->get('search'))) {
                $instance->collection = $instance->collection->filter(function ($row) use ($request){
                    if (Str::contains(Str::lower($row['order_number']), Str::lower($request->get('search')))){
                        return true;
                    }elseif(Str::contains(Str::lower($row['user']['name']), Str::lower($request->get('search')))){
                        return true;
                    }
                    return false;
                });
            }
            if (!empty($request->get('payment_option'))) {
                $instance->collection = $instance->collection->filter(function ($row) use ($request){
                    if (Str::contains(Str::lower($row['payment_option_id']), Str::lower($request->get('payment_option')))){
                        return true;
                    }
                    return false;
                });
            }
        })->make(true);
    }

    public function export() {
        return Excel::download(new OrderVendorTaxExport, 'tax.xlsx');
    }
}
