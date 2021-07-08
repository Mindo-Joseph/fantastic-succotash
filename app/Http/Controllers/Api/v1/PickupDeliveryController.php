<?php

namespace App\Http\Controllers\Api\v1;

use DB;
use Config;
use Validation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Api\v1\BaseController;
use App\Http\Requests\OrderProductRatingRequest;
use App\Models\{Category,ClientPreference,ClientCurrency,Vendor,ProductVariantSet,Product};
use App\Http\Traits\ApiResponser;
use GuzzleHttp\Client as GCLIENT;
class PickupDeliveryController extends BaseController{
	
    use ApiResponser;
    


    # get all vehicles category by vendor

    public function productsByVendorInPickupDelivery(Request $request, $vid = 0){
        try {
            if($vid == 0){
                return response()->json(['error' => 'No record found.'], 404);
            }
            $userid = Auth::user()->id;
            $paginate = $request->has('limit') ? $request->limit : 12;
            $clientCurrency = ClientCurrency::where('currency_id', Auth::user()->currency)->first();
            $langId = Auth::user()->language;
            $vendor = Vendor::select('id', 'name', 'desc', 'logo', 'banner', 'address', 'latitude', 'longitude', 
                        'order_min_amount', 'order_pre_time', 'auto_reject_time', 'dine_in', 'takeaway', 'delivery')
                        ->where('id', $vid)->first();
            if(!$vendor){
                return response()->json(['error' => 'No record found.'], 200);
            }
            // $variantSets =  ProductVariantSet::with(['options' => function($zx) use($langId){
            //                     $zx->join('variant_option_translations as vt','vt.variant_option_id','variant_options.id');
            //                     $zx->select('variant_options.*', 'vt.title');
            //                     $zx->where('vt.language_id', $langId);
            //                 }])->join('variants as vr', 'product_variant_sets.variant_type_id', 'vr.id')
            //                 ->join('variant_translations as vt','vt.variant_id','vr.id')
            //                 ->select('product_variant_sets.product_id', 'product_variant_sets.product_variant_id', 'product_variant_sets.variant_type_id', 'vr.type', 'vt.title')
            //                 ->where('vt.language_id', $langId)
            //                 ->whereIn('product_id', function($qry) use($vid){ 
            //                 $qry->select('id')->from('products')
            //                     ->where('vendor_id', $vid);
            //                 })
            //             ->groupBy('product_variant_sets.variant_type_id')->get();
            $products = Product::with(['category.categoryDetail', 'inwishlist' => function($qry) use($userid){
                            $qry->where('user_id', $userid);
                        },
                        'media.image', 'translation' => function($q) use($langId){
                        $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                        },
                        'variant' => function($q) use($langId){
                            $q->select('id','sku', 'product_id', 'quantity', 'price', 'barcode');
                            $q->groupBy('product_id');
                        },
                    ])->join('product_categories as pc', 'pc.product_id', 'products.id')
                    ->whereNotIn('pc.category_id', function($qr) use($vid){ 
                                $qr->select('category_id')->from('vendor_categories')
                                    ->where('vendor_id', $vid)->where('status', 0);
                    })
                    ->select('products.id', 'products.sku', 'products.requires_shipping', 'products.sell_when_out_of_stock', 'products.url_slug', 'products.weight_unit', 'products.weight', 'products.vendor_id', 'products.has_variant', 'products.has_inventory', 'products.Requires_last_mile', 'products.averageRating', 'pc.category_id','products.tags as tags_price')
                    ->where('products.vendor_id', $vid)
                    ->where('products.is_live', 1)->paginate($paginate);
            if(!empty($products)){
                foreach ($products as $key => $product) {
                    $product->tags_price = $this->getDeliveryFeeDispatcher($request);
                    $product->is_wishlist = $product->category->categoryDetail->show_wishlist;
                    foreach ($product->variant as $k => $v) {
                        $product->variant[$k]->multiplier = $clientCurrency->doller_compare;
                    }
                }
            }
            $response['vendor'] = $vendor;
            $response['products'] = $products;
           // $response['filterData'] = $variantSets;
            return response()->json(['data' => $response]);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage().''.$e->getLineNo(), $e->getCode());
        }
    }
    /**
     * list of vehicles details
    */
     /**     * Get Company ShortCode     *     */
     public function getListOfVehicles(Request $request, $cid = 0){
        try{
           
            if($cid == 0){
                return response()->json(['error' => 'No record found.'], 404);
            }
            $userid = Auth::user()->id;
            $langId = Auth::user()->language;
            $category = Category::with(['tags','type'  => function($q){
                            $q->select('id', 'title as redirect_to');
                        },
                        'childs.translation'  => function($q) use($langId){
                            $q->select('category_translations.name', 'category_translations.meta_title', 'category_translations.meta_description', 'category_translations.meta_keywords', 'category_translations.category_id')
                            ->where('category_translations.language_id', $langId);
                        },
                        'translation' => function($q) use($langId){
                            $q->select('category_translations.name', 'category_translations.meta_title', 'category_translations.meta_description', 'category_translations.meta_keywords', 'category_translations.category_id')
                            ->where('category_translations.language_id', $langId);
                        }])
                        ->select('id', 'icon', 'image', 'slug', 'type_id', 'can_add_products')
                        ->where('id', $cid)->first();
            

            if(!$category){
                return response()->json(['error' => 'No record found.'], 200);
            }
            $response['category'] = $category;
            $response['listData'] = $this->listData($langId, $cid, $category->type->redirect_to, $userid,$request);
            return $this->successResponse($response);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
        
    }

    public function listData($langId, $category_id, $type = '', $userid,$request){
        if ($type == 'Pickup/Delivery') {
            $category_details = [];
            $deliver_charge = $this->getDeliveryFeeDispatcher($request);
            $deliver_charge = $deliver_charge??0.00;
            $category_list = Category::where('parent_id', $category_id)->get();
            foreach ($category_list as $category) {
                $category_details[] = array(
                    'id' => $category->id,
                    'name' => $category->slug,
                    'icon' => $category->icon,
                    'image' => $category->image,
                    'price' => $deliver_charge
                );
            }
            return $category_details;
        }
        else{
            $arr = array();
            return $arr;
        }
    }


     # get delivery fee from dispatcher 
     public function getDeliveryFeeDispatcher($request){
        try {
                $dispatch_domain = $this->checkIfPickupDeliveryOn();
                if ($dispatch_domain && $dispatch_domain != false) {
                            $all_location = array();
                            $postdata =  ['locations' => $request->locations];
                            $client = new GCLIENT(['headers' => ['personaltoken' => $dispatch_domain->pickup_delivery_service_key,
                                                        'shortcode' => $dispatch_domain->pickup_delivery_service_key_code,
                                                        'content-type' => 'application/json']
                                                            ]);
                            $url = $dispatch_domain->pickup_delivery_service_key_url;                      
                            $res = $client->post($url.'/api/get-delivery-fee',
                                ['form_params' => ($postdata)]
                            );
                            $response = json_decode($res->getBody(), true); 
                            if($response && $response['message'] == 'success'){
                                return $response['total'];
                            }
                    
                }
            }    
            catch(\Exception $e){
              
            }
    }
    # check if last mile delivery on 
    public function checkIfPickupDeliveryOn(){
        $preference = ClientPreference::first();
        if($preference->need_dispacher_ride == 1 && !empty($preference->pickup_delivery_service_key) && !empty($preference->pickup_delivery_service_key_code) && !empty($preference->pickup_delivery_service_key_url))
            return $preference;
        else
            return false;
    }



    
    /**
     * create order for booking
    */
     public function createOrder(Request $request){
        DB::beginTransaction();
        try {
            $data = [];
            $request_to_dispatch = $this->placeRequestToDispatch($request);
                if($request_to_dispatch && isset($request_to_dispatch['task_id']) && $request_to_dispatch['task_id'] > 0){
                    DB::commit();
                    return response()->json([
                        'status' => 200,
                        'data' => $data,
                        'message' => 'Order Successfully.'
                    ]);
                }else{
                    DB::rollback();
                    return $request_to_dispatch;
                }
              
            }
            catch(\Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
           
        }
     
    }
     // place Request To Dispatch
    public function placeRequestToDispatch($request){
        try {
            $dispatch_domain = $this->checkIfPickupDeliveryOn();
            $customer = Auth::user();
            if ($dispatch_domain && $dispatch_domain != false) {
                $tasks = array();
                if ($request->payment_method == 2) {
                    $cash_to_be_collected = 'Yes';
                    $payable_amount = $request->amount;
                } else {
                    $cash_to_be_collected = 'No';
                    $payable_amount = 0.00;
                }
                $dynamic = uniqid();
                $call_back_url = route('dispatch-pickup-delivery', $dynamic);

                $tasks = array();
                $meta_data = '';
               
                               
                $postdata =  ['customer_name' => $customer->name ?? 'Dummy Customer',
                                                    'customer_phone_number' => $customer->phone_number??rand(111111,11111),
                                                    'customer_email' => $customer->email ?? '',
                                                    'recipient_phone' => $request->phone_number ?? $customer->phone_number,
                                                    'recipient_email' => $request->email ?? $customer->email,
                                                    'task_description' => "Pickup & Delivery From order",
                                                    'allocation_type' => 'u',
                                                    'task_type' => $request->task_type,
                                                    'schedule_time' => $request->schedule_time ?? null,
                                                    'cash_to_be_collected' => $payable_amount??0.00,
                                                    'barcode' => '',
                                                    'call_back_url' => $call_back_url??null,
                                                    'task' => $request->tasks
                                                    ];

                  
                $client = new GClient(['headers' => ['personaltoken' => $dispatch_domain->pickup_delivery_service_key,
                                                    'shortcode' => $dispatch_domain->pickup_delivery_service_key_code,
                                                    'content-type' => 'application/json']
                                                        ]);
                                            
                $url = $dispatch_domain->pickup_delivery_service_key_url;
                $res = $client->post(
                    $url.'/api/task/create',
                    ['form_params' => (
                            $postdata
                        )]
                );
                $response = json_decode($res->getBody(), true);
                if ($response && isset($response['task_id']) && $response['task_id'] > 0) {
                   return $response;
                }
                return $response;
                }
            }catch(\Exception $e)
                    {   
                        $data = [];
                        $data['status'] = 400;
                        $data['message'] =  $e->getMessage();
                        return $data;
                                
                    }
                
            
           
    }

}
