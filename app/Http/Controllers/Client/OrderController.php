<?php

namespace App\Http\Controllers\Client;

use Auth;
use App\Models\Tax;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Client\BaseController;
use App\Models\{OrderStatusOption,DispatcherStatusOption, Vendor, VendorOrderStatus};
class OrderController extends BaseController{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $orders = Order::with(['vendors', 'address','user'])->orderBy('id', 'DESC');
        if (Auth::user()->is_superadmin == 0) {
            $orders = $orders->whereHas('vendors.vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $orders = $orders->paginate(10);
        return view('backend.order.index', compact('orders'));
    }

    /**
     * Display the order.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */

    public function getOrderDetail($domain = '', $order_id, $vendor_id){
        $vendor_order_status_created_dates = [];
        $order = Order::with(array(
                'vendors' => function($query) use ($vendor_id){
                    $query->where('vendor_id', $vendor_id);
                },
                'vendors.products' => function($query) use ($vendor_id){
                    $query->where('vendor_id', $vendor_id);
                }))->findOrFail($order_id);
        $order_status_options = OrderStatusOption::all();
        $dispatcher_status_options = DispatcherStatusOption::all();
        $vendor_order_statuses = VendorOrderStatus::where('order_id', $order_id)->where('vendor_id', $vendor_id)->get();
        foreach ($vendor_order_statuses as $vendor_order_status) {
            $vendor_order_status_created_dates[$vendor_order_status->order_status_option_id]= $vendor_order_status->created_at;
            $vendor_order_status_option_ids[]= $vendor_order_status->order_status_option_id;
        }
        return view('backend.order.view')->with(['vendor_id' => $vendor_id, 'order' => $order, 'vendor_order_status_option_ids' => $vendor_order_status_option_ids,'order_status_options' => $order_status_options, 'dispatcher_status_options' => $dispatcher_status_options, 'vendor_order_status_created_dates'=> $vendor_order_status_created_dates]);
    }

     /**
     * Change the status of order
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request, $domain = '')
    {
        try{
            $timezone = Auth::user()->timezone;
            $vendor_order_status_check = VendorOrderStatus::where('order_id', $request->order_id)->where('vendor_id', $request->vendor_id)->where('order_status_option_id', $request->status_option_id)->first();
            if(!$vendor_order_status_check){
                $vendor_order_status = new VendorOrderStatus();
                $vendor_order_status->order_id = $request->order_id;
                $vendor_order_status->order_status_option_id = $request->status_option_id;
                $vendor_order_status->vendor_id = $request->vendor_id;
                $vendor_order_status->save();
                return response()->json([
                    'status' => 'success',
                    'created_date' => convertDateTimeInTimeZone($vendor_order_status->created_at, $timezone, 'l, F d, Y, H:i A'),
                    'message' => 'Order Status Updated Successfully.'
                ]);
            }
        }catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update!'
            ]);
        }
    }
}
