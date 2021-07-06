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
use App\Models\{Category,ClientPreference};
use App\Http\Traits\ApiResponser;
use GuzzleHttp\Client as GCLIENT;
class PickupDeliveryController extends BaseController{
	
    use ApiResponser;
    
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



}
