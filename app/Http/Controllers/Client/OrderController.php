<?php

namespace App\Http\Controllers\Client;

use App\Models\Tax;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Client\BaseController;

class OrderController extends BaseController{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $orders = Order::with(['products', 'address','user'])->orderBy('id', 'DESC')->paginate();
        return view('backend.order.index', compact('orders'));
    }

    /**
     * Display the order.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */

    public function getOrderDetail($domain = '', $order_id, $vendor_id){
        $order = Order::with(array('products' => function($query) use ($vendor_id){
                    $query->where('vendor_id', $vendor_id);
                }))->findOrFail($order_id);
        return view('backend.order.view', compact('order'));
    }
}
