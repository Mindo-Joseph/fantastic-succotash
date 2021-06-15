<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\{User, Vendor, Order,UserVendor, PaymentOption, VendorCategory, Product};

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
			$user_vendor_ids = UserVendor::where('user_id', $user->id)->pluck('vendor_id');
			if($user_vendor_ids){
				$is_selected_vendor_id = $selected_vendor_id ? $selected_vendor_id : $user_vendor_ids->first();
			}
			$vendor_list = Vendor::whereIn('id', $user_vendor_ids)->get(['id','name','logo']);
			foreach ($vendor_list as $vendor) {
				$vendor->is_selected = ($is_selected_vendor_id == $vendor->id) ? true : false;
			}
			$vendor_categories = VendorCategory::where('vendor_id', $is_selected_vendor_id)->get('category_id');
			$selected_category_id = $vendor_categories->first()->category_id;
			foreach ($vendor_categories as $vendor_category) {
				$category_list []= array(
					'id' => $vendor_category->category->id,
					'name' => $vendor_category->category->slug,
					'is_selected' => $selected_category_id == $vendor_category->category_id ? true : false
				);
			}
			$products = Product::has('vendor')->with(['media.image', 'translation' => function($q) use($langId){
                        	$q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                    	},'variant' => function($q) use($langId){
                            $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                            $q->groupBy('product_id');
                    	},
                    ])->select('products.id', 'products.sku', 'products.url_slug', 'products.weight_unit', 'products.weight', 'products.vendor_id', 'products.has_variant', 'products.has_inventory', 'products.sell_when_out_of_stock', 'products.requires_shipping', 'products.Requires_last_mile', 'products.averageRating')
                    ->where('category_id', $selected_category_id)->where('products.is_live', 1)->paginate($paginate);
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
			$order_list = Order::select('id','order_number','payable_amount','payment_option_id','user_id')
						->whereHas('vendors', function($query) use ($is_selected_vendor_id){
						   $query->where('vendor_id', $is_selected_vendor_id);
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
