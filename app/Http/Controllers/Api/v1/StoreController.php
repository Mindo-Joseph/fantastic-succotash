<?php

namespace App\Http\Controllers\Api\v1;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\{User, Vendor, Order,UserVendor};

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
			$order_list = Order::with(array(
                'vendors' => function($query) use ($selected_vendor_id){
                    $query->where('vendor_id', $selected_vendor_id);
                }))->paginate($paginate);
			$vendor_list = Vendor::whereIn('id', $user_vendor_ids)->get(['id','name']);
			foreach ($vendor_list as $vendor) {
				$vendor->is_selected = $selected_vendor_id == $vendor->id ? true : false;
			}
			$data = ['order_list' => $order_list, 'vendor_list' => $vendor_list];
            return $this->successResponse($data, '', 200);
    	} catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
    	}
    }
}
