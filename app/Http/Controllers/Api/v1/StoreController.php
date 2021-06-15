<?php

namespace App\Http\Controllers\Api\v1;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\{User, Vendor, Order,UserVendor, PaymentOption};

class StoreController extends Controller{
    use ApiResponser;

    public function getMyStoreDetails(Request $request){
    	try {
    		$user = Auth::user();
    		$selected_vendor_id = 0;
            $paginate = $request->has('limit') ? $request->limit : 12;
			$user_vendor_ids = UserVendor::where('user_id', $user->id)->pluck('vendor_id');
			if($user_vendor_ids){
				$selected_vendor_id = $user_vendor_ids->first();
			}
			$order_list = Order::select('id','order_number','payable_amount','payment_option_id','user_id')
						->whereHas('vendors', function($query) use ($selected_vendor_id){
						   $query->where('vendor_id', $selected_vendor_id);
						})->paginate($paginate);
			foreach ($order_list as $key => $order) {
				$order_item_count = 0;
				$order->user_name = $order->user->name;
				$order->user_image = $order->user->image;
				$order->date_time = convertDateTimeInTimeZone($order->created_at, $user->timezone);
				$order->payment_option_title = $order->paymentOption->title;
				foreach ($order->products as $product) {
    				$order_item_count += $product->quantity;
				}
				$order->item_count = $order_item_count;
				unset($order->payment_option_id);
				unset($order->products);
				unset($order->paymentOption);
				unset($order->user);
			}
			$vendor_list = Vendor::whereIn('id', $user_vendor_ids)->get(['id','name']);
			foreach ($vendor_list as $vendor) {
				$vendor->is_selected = ($selected_vendor_id == $vendor->id) ? true : false;
			}
			$data = ['order_list' => $order_list, 'vendor_list' => $vendor_list];
            return $this->successResponse($data, '', 200);
    	} catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
    	}
    }
}
