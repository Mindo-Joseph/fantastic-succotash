<?php

namespace App\Http\Controllers\Client\Accounting;
use DataTables;
use Carbon\Carbon;
use App\Models\Order;
use Illuminate\Support\Str; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class LoyaltyController extends Controller
{
    public function index(Request $request){
        return view('backend.accounting.loyality');
    }

    public function filter(Request $request){
        $month_number = '';
        $user = Auth::user();
        $search_value = $request->get('search');
        $timezone = $user->timezone ? $user->timezone : 'Asia/Kolkata';
        $month_picker_filter = $request->month_picker_filter;
        if($month_picker_filter){
            $temp_arr = explode(' ', $month_picker_filter);
            $month_number =  getMonthNumber($temp_arr[0]);
        }
        $orders = Order::with('user')->orderBy('id', 'desc')->get();
        foreach ($orders as $order) {
            $order->loyalty_membership = '';
            $order->created_date = convertDateTimeInTimeZone($order->created_at, $timezone, 'Y-m-d h:i:s A');
        }
        return Datatables::of($orders)
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
