<?php

namespace App\Http\Controllers\Client;

use App\Models\Tax;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Client\BaseController;
use App\Models\{OrderStatusOption,DispatcherStatusOption, VendorOrderStatus};
use Auth;
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
        $order = Order::with(array(
                'vendors' => function($query) use ($vendor_id){
                    $query->where('vendor_id', $vendor_id);
                },
                'vendors.products' => function($query) use ($vendor_id){
                    $query->where('vendor_id', $vendor_id);
                }))->findOrFail($order_id);
        $order_status_option = OrderStatusOption::all();

        $dispatcher_status_option = DispatcherStatusOption::all();

        $order_status = VendorOrderStatus::where('order_id', $order_id)->where('vendor_id', $vendor_id)->get();

        return view('backend.order.view')->with(['vendor_id' => $vendor_id, 'order' => $order, 'order_status' => $order_status,'order_status_option' => $order_status_option, 'dispatcher_status_option' => $dispatcher_status_option ]);
    }

     /**
     * Change the status of order
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request, $domain = '')
    {
        $vendor_order_status = new VendorOrderStatus();
        $vendor_order_status->order_id = $request->order_id;
        $vendor_order_status->order_status_option_id = $request->status_option_id;
        $vendor_order_status->vendor_id = $request->vendor_id;
        $vendor_order_status->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Updated Successfully'
        ]);
    }
}
