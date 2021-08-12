<?php

namespace App\Http\Controllers\Api\v1;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\{User, Vendor, Order,UserVendor, PaymentOption, VendorCategory, Product, VendorOrderStatus, OrderStatusOption,ClientCurrency, Category_translation, OrderVendor};

class StoreController extends Controller{
    use ApiResponser;

    public function getMyStoreProductList(Request $request){
    	try {
			$category_list = [];
    		$user = Auth::user();
    		$langId = $user->language;
    		$is_selected_vendor_id = 0;
            $paginate = $request->has('limit') ? $request->limit : 12;
            $client_currency_detail = ClientCurrency::where('currency_id', $user->currency)->first();
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
			$vendor_categories = VendorCategory::where('vendor_id', $is_selected_vendor_id)
							->whereHas('category', function($query) {
							   	$query->whereIn('type_id', [1]);
							})->where('status', 1)->get('category_id');
			$vendor_category_id = 0;
			if($vendor_categories->count()){
				$vendor_category_id = $vendor_categories->first()->category_id;
			}
			$is_selected_category_id = $selected_category_id ? $selected_category_id : $vendor_category_id;
			foreach ($vendor_categories as $vendor_category) {
				$Category_translation = Category_translation::where('category_id', $vendor_category->category->id)->where('language_id', $langId)->first();
				if(!$Category_translation){
					$Category_translation = Category_translation::where('category_id', $vendor_category->category->id)->first();
				}
				$category_list []= array(
					'id' => $vendor_category->category->id,
					'name' => $Category_translation ? $Category_translation->name : $vendor_category->category->slug,
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
			foreach ($products as $product) {
                foreach ($product->variant as $k => $v) {
                    $product->variant[$k]->multiplier = $client_currency_detail->doller_compare;
                }
            }
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
			$order_list = Order::with('orderStatusVendor')->select('id','order_number','payable_amount','payment_option_id','user_id')
						->whereHas('vendors', function($query) use ($is_selected_vendor_id){
						   $query->where('vendor_id', $is_selected_vendor_id);
						})->orderBy('id', 'DESC')->paginate($paginate);
			foreach ($order_list as $order) {
				$order_status = [];
				$product_details = [];
				$order_item_count = 0;
				$order->user_name = $order->user->name;
				$order->user_image = $order->user->image;
				$order->date_time = convertDateTimeInTimeZone($order->created_at, $user->timezone);
				$order->payment_option_title = $order->paymentOption->title;
				foreach ($order->vendors as $vendor) {
					$vendor_order_status = VendorOrderStatus::where('order_id', $order->id)->where('vendor_id', $is_selected_vendor_id)->orderBy('id', 'DESC')->first();
					if($vendor_order_status){
						$order_status_option_id = $vendor_order_status->order_status_option_id;
						$current_status = OrderStatusOption::select('id','title')->find($order_status_option_id);
						if($order_status_option_id == 2){
							$upcoming_status = OrderStatusOption::select('id','title')->where('id', '>', 3)->first();
						}elseif ($order_status_option_id == 3) {
							$upcoming_status = null;
						}elseif ($order_status_option_id == 6) {
							$upcoming_status = null;
						}else{
							$upcoming_status = OrderStatusOption::select('id','title')->where('id', '>', $order_status_option_id)->first();
						}
						$order->order_status = [
							'current_status' => $current_status,
							'upcoming_status' => $upcoming_status,
						];
					}
				}
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
    public function getMyStoreRevenueDetails(Request $request){
        $dates = [];
        $sales = [];
        $revenue = [];
        $type = $request->type;
        $vendor_id = $request->vendor_id;
        $monthly_sales_query = OrderVendor::select(\DB::raw('sum(payable_amount) as y'), \DB::raw('count(*) as z'), \DB::raw('date(created_at) as x'));
        switch ($type) {
        	case 'monthly':
        		$created_at = $monthly_sales_query->whereRaw('MONTH(created_at) = ?', [date('m')]);
    		break;
    		case 'weekly':
    			Carbon::setWeekStartsAt(Carbon::SUNDAY);
        		$created_at = $monthly_sales_query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]); 
    		break;
    		case 'yearly':
        		$created_at = $monthly_sales_query->whereRaw('YEAR(created_at) = ?', [date('Y')]);
    		break;
        	default:
    			$created_at = $monthly_sales_query->whereRaw('MONTH(created_at) = ?', [date('m')]);
    		break;
        }
 		$monthlysales = $monthly_sales_query->where('vendor_id', $vendor_id)->groupBy('x')->get();
        foreach ($monthlysales as $monthly) {
            $dates[] = $monthly->x;
            $sales[] = $monthly->z;
            $revenue[] = $monthly->y;
        }
        $data = ['dates' => $dates, 'revenue' => $revenue, 'sales' => $sales];
        return $this->successResponse($data, '', 200);
    }
}
