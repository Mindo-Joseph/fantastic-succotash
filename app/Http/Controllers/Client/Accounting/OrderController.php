<?php

namespace App\Http\Controllers\Client\Accounting;
use DataTables;
use App\Models\User;
use App\Models\Vendor;
use App\Models\OrderVendor;
use Illuminate\Support\Str; 
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use App\Models\OrderStatusOption;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\DispatcherStatusOption;
use App\Exports\OrderVendorListTaxExport;

class OrderController extends Controller{
    use ApiResponser;
    public function index(Request $request){
        $vendors = Vendor::get();
        $total_order_count = 0;
        $total_delivery_fees = 0;
        $total_cash_to_collected = 0;
        $total_earnings_by_vendors = 0;
        $dispatcher_status_options = DispatcherStatusOption::get();
        $order_status_options = OrderStatusOption::where('type', 1)->get();
        $vendor_orders = OrderVendor::with(['orderDetail.paymentOption', 'user','vendor','payment'])->get();
        foreach ($vendor_orders as $vendor_order) {
            $total_delivery_fees+= $vendor_order->delivery_fee;
            $total_earnings_by_vendors+= $vendor_order->payable_amount;
            if($vendor_order->orderDetail){
                if($vendor_order->orderDetail->paymentOption->id == 1){
                    $total_cash_to_collected += $vendor_order->payable_amount;
                }
            }
        }
        $total_order_count = $vendor_orders->count();
        return view('backend.accounting.order', compact('vendors','order_status_options', 'dispatcher_status_options'))->with(['total_earnings_by_vendors' => number_format($total_earnings_by_vendors, 2), 'total_delivery_fees' => number_format($total_delivery_fees, 2), 'total_cash_to_collected' => number_format($total_cash_to_collected, 2), 'total_order_count' => $total_order_count, 2]);
    }
    public function filter(Request $request){
        $user = Auth::user();
        $search_value = $request->get('search');
        $timezone = $user->timezone ? $user->timezone : 'Asia/Kolkata';
        $vendor_orders = OrderVendor::with(['orderDetail.paymentOption', 'user','vendor','payment'])->get();
        foreach ($vendor_orders as $vendor_order) {
            $vendor_order->created_date = convertDateTimeInTimeZone($vendor_order->created_at, $timezone, 'Y-m-d h:i:s A');
            $vendor_order->user_name = $vendor_order->user ? $vendor_order->user->name : '';
        }
        return Datatables::of($vendor_orders)
                    ->addIndexColumn()
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('vendor_id'))) {
                            $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                                return Str::contains($row['vendor_id'], $request->get('vendor_id')) ? true : false;
                            });
                        }
                        if (!empty($request->get('date_filter'))) {
                            $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                                return Str::contains($row['vendor_id'], $request->get('vendor_id')) ? true : false;
                            });
                        }
                        if (!empty($request->get('search'))) {
                            $instance->collection = $instance->collection->filter(function ($row) use ($request){
                                if (Str::contains(Str::lower($row['order_detail']['order_number']), Str::lower($request->get('search')))){
                                    return true;
                                }else if (Str::contains(Str::lower($row['user_name']), Str::lower($request->get('search')))) {
                                    return true;
                                }else if (Str::contains(Str::lower($row['vendor']['name']), Str::lower($request->get('search')))) {
                                    return true;
                                }
                                return false;
                            });
                        }
                    })->make(true);
        $data = ['vendor_orders' => $vendor_orders, ];
    }

    public function export() {
        return Excel::download(new OrderVendorListTaxExport, 'order_list.xlsx');
    }
}
