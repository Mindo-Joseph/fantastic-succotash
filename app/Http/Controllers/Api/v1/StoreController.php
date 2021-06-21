<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\{User, Vendor, Order,UserVendor, PaymentOption, VendorCategory, Product, VendorOrderStatus, OrderStatusOption};

class StoreController extends Controller{
    use ApiResponser;
    public function getMyStoreProductList(Request $request){
    	try {
			$category_list = [];
    		$user = Auth::user();
    		$langId = $user->language;
    		$is_selected_vendor_id = 0;
            $paginate = $request->has('limit') ? $request->limit : 12;
            $selected_vendor_id = $request->has('selected_vendor_id') ? $request->selected_vendor_id : '';
            $selected_category_id = $request->has('selected_category_id') ? $request->selected_category_id : '';
			$user_vendor_ids = UserVendor::where('user_id', $user->id)->pluck('vendor_id');
			if($user_vendor_ids){
				$is_selected_vendor_id = $selected_vendor_id ? $selected_vendor_id : $user_vendor_ids->first();
			}
			$vendor_list = Vendor::whereIn('id', $user_vendor_ids)->get(['id','name','logo']);
			foreach ($vendor_list as $vendor) {
				$vendor->is_selected = ($is_selected_vendor_id == $vendor->id) ? true : false;
			}
			$vendor_categories = VendorCategory::where('vendor_id', $is_selected_vendor_id)->whereHas('category', function($query) {
						   $query->whereIn('type_id', [1]);
						})->get('category_id');
			$vendor_category_id = 0;
			if($vendor_categories->count()){
				$vendor_category_id = $vendor_categories->first()->category_id;
			}
			$is_selected_category_id = $selected_category_id ? $selected_category_id : $vendor_category_id;
			foreach ($vendor_categories as $vendor_category) {
				$category_list []= array(
					'id' => $vendor_category->category->id,
					'name' => $vendor_category->category->slug,
					'type_id' => $vendor_category->category->type_id,
					'is_selected' => $is_selected_category_id == $vendor_category->category_id ? true : false
				);
			}
			$products = Product::select('id', 'sku', 'url_slug','is_live','category_id')->has('vendor')
						->with(['media.image', 'translation' => function($q) use($langId){
                        	$q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                    	},'variant' => function($q) use($langId){
                            $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                            $q->groupBy('product_id');
                    	},
                    ])->where('category_id', $is_selected_category_id)->where('is_live', 1)->paginate($paginate);
			$data = ['vendor_list' => $vendor_list,'category_list' => $category_list,'products'=> $products];
            return $this->successResponse($data, '', 200);
    	} catch (Exception $e) {
    		
    	}
    }
    public function getMyStoreDetails(Request $request){
    	try {
    		$user = Auth::user();
    		$is_selected_vendor_id = 0;
            $paginate = $request->has('limit') ? $request->limit : 12;
            $selected_vendor_id = $request->has('selected_vendor_id') ? $request->selected_vendor_id : '';
			$user_vendor_ids = UserVendor::where('user_id', $user->id)->pluck('vendor_id');
			if($user_vendor_ids){
				$is_selected_vendor_id = $selected_vendor_id ? $selected_vendor_id : $user_vendor_ids->first();
			}
			$order_list = Order::select('id','order_number','payable_amount','payment_option_id','user_id','vendor_id')
						->whereHas('vendors', function($query) use ($is_selected_vendor_id){
						   $query->where('vendor_id', $is_selected_vendor_id);
						})->paginate($paginate);
			foreach ($order_list as $key => $order) {
				$order_status = [];
				$product_details = [];
				$order_item_count = 0;
				$order->user_name = $order->user->name;
				$order->user_image = $order->user->image;
				$order->date_time = convertDateTimeInTimeZone($order->created_at, $user->timezone);
				$order->payment_option_title = $order->paymentOption->title;
				foreach ($order->vendors as $vendor) {
					$vendor_order_status = VendorOrderStatus::where('order_id', $order->id)->where('vendor_id', $vendor->id)->first();
					if($vendor_order_status){
						$order_status[] = [
							'current' => OrderStatusOption::find($vendor_order_status->order_status_option_id),
							'upcoming' => OrderStatusOption::findNext($vendor_order_status->order_status_option_id)
						];
					}
				}
				$order->order_status = $order_status;
				foreach ($order->products as $product) {
    				$order_item_count += $product->quantity;
    				if($is_selected_vendor_id == $product->vendor_id){
	    				$product_details[]= array(
	    					'image_path' => $product->media->first() ? $product->media->first()->image->path : $product->image,
	    					'price' => $product->price,
	    					'qty' => $product->quantity,
	    				);
    				}
				}
				$order->product_details = $product_details;
				$order->item_count = $order_item_count;
				unset($order->user);
				unset($order->products);
				unset($order->paymentOption);
				unset($order->payment_option_id);
			}
			$vendor_list = Vendor::whereIn('id', $user_vendor_ids)->get(['id','name','logo']);
			foreach ($vendor_list as $vendor) {
				$vendor->is_selected = ($is_selected_vendor_id == $vendor->id) ? true : false;
			}
			$data = ['order_list' => $order_list, 'vendor_list' => $vendor_list];
            return $this->successResponse($data, '', 200);
    	} catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
    	}
    }
}
