<?php

namespace App\Http\Controllers\Front;

use Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Front\FrontController;
use App\Models\{Currency, Banner, Category, Brand, Product, ClientLanguage, Vendor, ClientCurrency, ClientPreference};


class UserhomeController extends FrontController
{
    use ApiResponser;
    private $field_status = 2;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        try {
            $home = array();
            $vendor_ids = array();
            $latitude = Session::get('latitude');
            $longitude = Session::get('longitude');
            $curId = Session::get('customerCurrency');
            $preferences = Session::get('preferences');
            $langId = Session::get('customerLanguage');
            $client_config = Session::get('client_config');
            $selectedAddress = Session::get('selectedAddress');
            $navCategories = $this->categoryNav($langId);
            Session::put('navCategories', $navCategories);
            $clientPreferences = ClientPreference::first();
            $count = 0;
            if($clientPreferences->dinein_check == 1){$count++;}
            if($clientPreferences->takeaway_check == 1){$count++;}
            if($clientPreferences->delivery_check == 1){$count++;}
            if($preferences){
                if( (empty($latitude)) && (empty($longitude)) && (empty($selectedAddress)) ){
                    $selectedAddress = $preferences->Default_location_name;
                    $latitude = $preferences->Default_latitude;
                    $longitude = $preferences->Default_longitude;
                    Session::put('latitude', $latitude);
                    Session::put('longitude', $longitude);
                    Session::put('selectedAddress', $selectedAddress);
                }
            }
            $banners = Banner::where('status', 1)->where('validity_on', 1)
                        ->where(function($q){
                            $q->whereNull('start_date_time')->orWhere(function($q2){
                                $q2->whereDate('start_date_time', '<=', Carbon::now())
                                    ->whereDate('end_date_time', '>=', Carbon::now());
                            });
                        })->orderBy('sorting', 'asc')->get();
            return view('frontend.home')->with(['home' => $home, 'count' => $count, 'clientPreferences' => $clientPreferences, 'banners' => $banners, 'navCategories' => $navCategories, 'selectedAddress' => $selectedAddress, 'latitude' => $latitude, 'longitude' => $longitude]);
        } catch (Exception $e) {
            pr($e->getCode());die;
        }
    }
    public function postHomePageData(Request $request){
        // dd($request->all());
        $vendor_ids = [];
        $new_products = [];
        $feature_products = [];
        $on_sale_products = [];
        if($request->has('latitude')) {
            $latitude = $request->latitude;
            Session::put('latitude', $latitude);
        }else{
            $latitude = Session::get('latitude');
        }
        if($request->has('longitude')) {
            $longitude = $request->longitude;
            Session::put('longitude', $longitude);
        }else{
            $longitude = Session::get('longitude');
        }
        $selectedAddress = ($request->has('selectedAddress')) ? Session::put('selectedAddress', $request->selectedAddress) : Session::get('selectedAddress');
        $preferences = Session::get('preferences');
        $currency_id = Session::get('customerCurrency');
        $language_id = Session::get('customerLanguage');
        $brands = Brand::select('id', 'image')->where('status', '!=', $this->field_status)->orderBy('position', 'asc')->get();
        foreach ($brands as $brand) {
            $brand->redirect_url = route('brandDetail', $brand->id);
        }
        $vendors = Vendor::select('id', 'name', 'banner', 'order_pre_time', 'order_min_amount', 'logo','slug')->where($request->type, 1);
        if($preferences){
            if( (empty($latitude)) && (empty($longitude)) && (empty($selectedAddress)) ){
                $selectedAddress = $preferences->Default_location_name;
                $latitude = $preferences->Default_latitude;
                $longitude = $preferences->Default_longitude;
                Session::put('latitude', $latitude);
                Session::put('longitude', $longitude);
                Session::put('selectedAddress', $selectedAddress);
            }
            if(($preferences->is_hyperlocal == 1) && ($latitude) && ($longitude) ){
                $vendors = $vendors->whereHas('serviceArea', function($query) use($latitude, $longitude){
                    $query->select('vendor_id')
                    ->whereRaw("ST_Contains(POLYGON, ST_GEOMFROMTEXT('POINT(".$latitude." ".$longitude.")'))");
                });
            }
        }
        $vendors = $vendors->where('status', '!=', $this->field_status)->inRandomOrder()->get();
        foreach ($vendors as $key => $value) {
            $vendor_ids[] = $value->id;
        }
        if(($latitude) && ($longitude)) {
            Session::put('vendors', $vendor_ids);
        }
        $navCategories = $this->categoryNav($language_id);
        Session::put('navCategories', $navCategories);
        $on_sale_product_details = $this->vendorProducts($vendor_ids, $language_id, 'USD', '', $request->type);
        $new_product_details = $this->vendorProducts($vendor_ids, $language_id, $currency_id, 'is_new', $request->type);
        $feature_product_details = $this->vendorProducts($vendor_ids, $language_id, $currency_id, 'is_featured',$request->type);
        foreach ($new_product_details as  $new_product_detail) {
            $multiply = $new_product_detail->variant->first() ? $new_product_detail->variant->first()->multiplier : 1;
            $title = $new_product_detail->translation ? $new_product_detail->translation->first()->title : $on_sale_product_detail->sku;
            $image_url = $new_product_detail->media->first() ? $new_product_detail->media->first()->image->path['proxy_url'].'300/300'.$new_product_detail->media->first()->image->path['image_path'] : '';
            $new_products[]=array(
                'image_url' => $image_url,
                'sku' => $new_product_detail->sku,
                'title' => Str::limit($title, 18, '..'),
                'url_slug' => $new_product_detail->url_slug,
                'vendor_name' => $new_product_detail->vendor ? $new_product_detail->vendor->name : '',
                'price' => Session::get('currencySymbol').' '.($new_product_detail->variant->first()->price * $multiply),
            );
        }
        foreach ($feature_product_details as  $feature_product_detail) {
            $multiply = $feature_product_detail->variant->first() ? $feature_product_detail->variant->first()->multiplier : 1;
            $title = $feature_product_detail->translation ? $feature_product_detail->translation->first()->title : $on_sale_product_detail->sku;
            $image_url = $feature_product_detail->media->first() ? $feature_product_detail->media->first()->image->path['proxy_url'].'300/300'.$feature_product_detail->media->first()->image->path['image_path'] : '';
            $feature_products[]=array(
                'image_url' => $image_url,
                'sku' => $feature_product_detail->sku,
                'title' => Str::limit($title, 18, '..'),
                'url_slug' => $feature_product_detail->url_slug,
                'vendor_name' => $feature_product_detail->vendor ? $feature_product_detail->vendor->name : '',
                'price' => Session::get('currencySymbol').' '.($feature_product_detail->variant->first()->price * $multiply),
            );
        }
        foreach ($on_sale_product_details as  $on_sale_product_detail) {
            $multiply = $on_sale_product_detail->variant->first() ? $on_sale_product_detail->variant->first()->multiplier : 1;
            $title = $on_sale_product_detail->translation ? $on_sale_product_detail->translation->first()->title : $on_sale_product_detail->sku;
            $image_url = $on_sale_product_detail->media->first() ? $on_sale_product_detail->media->first()->image->path['proxy_url'].'300/300'.$on_sale_product_detail->media->first()->image->path['image_path'] : '';
            $on_sale_products[]=array(
                'image_url' => $image_url,
                'sku' => $on_sale_product_detail->sku,
                'title' => Str::limit($title, 18, '..'),
                'url_slug' => $on_sale_product_detail->url_slug,
                'vendor_name' => $on_sale_product_detail->vendor ? $on_sale_product_detail->vendor->name : '',
                'price' => Session::get('currencySymbol').' '.($on_sale_product_detail->variant->first()->price * $multiply),
            );
        }
        $data = [
            'brands' => $brands, 
            'vendors' => $vendors,
            'new_products' => $new_products, 
            'navCategories' => $navCategories,
            'feature_products' => $feature_products,
            'on_sale_products' => $on_sale_products, 
        ];
        return $this->successResponse($data);
    }
    public function homepage(Request $request)
    {
        try{
            $preferences = ClientPreference::select('is_hyperlocal', 'client_code', 'language_id', 'pharmacy_check')->first();
            Session::put('deliveryAddress', $request->selectedAddress);
            Session::put('latitude', $request->latitude);
            Session::put('longitude', $request->longitude);
            $curId = Session::get('customerCurrency');
            $latitude = $request->latitude;
            $longitude = $request->longitude;
            $user_geo[] = $latitude;
            $user_geo[] = $longitude;
            $vendors = array();
            $vendorData = Vendor::select('id', 'name', 'banner', 'order_pre_time', 'order_min_amount', 'logo');
            if( ($preferences->is_hyperlocal == 1) && (!empty($latitude)) && (!empty($longitude)) ){
                $vendorData = $vendorData->whereHas('serviceArea', function($query) use($latitude, $longitude){
                        $query->select('vendor_id')
                        ->whereRaw("ST_Contains(POLYGON, ST_GEOMFROMTEXT('POINT(".$latitude." ".$longitude.")'))");
                });
            }
            $vendorData = $vendorData->where('status', '!=', $this->field_status)->get();
            
            $isVendorArea = 0;
            $langId = (isset(Auth::user()->language)) ? Auth::user()->language : Session::get('customerLanguage');
            $homeData = array();
            $homeData['reqData'] = $request->all();
            $homeData['vendors'] = $vendorData;
            $homeData['brands'] = Brand::with(['translation' => function($q) use($langId){
                            $q->select('brand_id', 'title')->where('language_id', $langId);
                            }])->select('id', 'image')->where('status', '!=', $this->field_status)->orderBy('position', 'asc')->get();
            foreach($vendorData as $key => $data){
                $vendors[] = $data->id;
            }
            Session::put('vendors', $vendors);
            $navCategories = $this->categoryNav($langId);
            Session::put('navCategories', $navCategories);
            $homeData['navCategories'] = $navCategories;
            $fp = $this->vendorProducts($vendors, $langId, $curId, 'is_featured', 'delivery');
            $np = $this->vendorProducts($vendors, $langId, $curId, 'is_new', 'delivery');
            $onSP = $this->vendorProducts($vendors, $langId, 'USD', "", 'delivery');
            $homeData['featuredProducts'] = ($fp->count() > 0) ? array_chunk($fp->toArray(), ceil(count($fp) / 2)) : $fp;
            $homeData['newProducts'] = ($np->count() > 0) ? array_chunk($np->toArray(), ceil(count($np) / 2)) : $np;
            $homeData['onSaleProducts'] = ($onSP->count() > 0) ? array_chunk($onSP->toArray(), ceil(count($onSP) / 2)) : $onSP;
            return $this->successResponse($homeData);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function vendorProducts($venderIds, $langId, $currency = 'USD', $where = '', $type)
    {
        $products = Product::with(['vendor' => function($q) use($type){
                                        $q->where($type, 1);
                                    },
                                    'media' => function($q){
                                        $q->groupBy('product_id');
                                    }, 'media.image',
                                    'translation' => function($q) use($langId){
                                        $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                                    },
                                    'variant' => function($q) use($langId){
                                        $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                                        $q->groupBy('product_id');
                                    },
                                    ])->select('id', 'sku', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'sell_when_out_of_stock', 'requires_shipping', 'Requires_last_mile', 'averageRating');
        
                    if($where !== ''){
            $products = $products->where($where, 1);
        }
        if(is_array($venderIds)){
            $products = $products->whereIn('vendor_id', $venderIds);
        }
        $products = $products->where('is_live', 1)->take(6)->inRandomOrder()->get();

        if(!empty($products)){
            foreach ($products as $key => $value) {
                foreach ($value->variant as $k => $v) {
                    $value->variant[$k]->multiplier = Session::get('currencyMultiplier');
                }
            }
        }
        return $products;
    }

    public function changePrimaryData(Request $request)
    {
        if($request->has('type') && $request->type == 'language'){
            $clientLanguage = ClientLanguage::where('language_id', $request->value1)->first();
            if($clientLanguage){
                Session::put('customerLanguage', $request->value1);
            }
        }

        if($request->has('type') && $request->type == 'currency'){
            $clientCurrency = ClientCurrency::where('currency_id', $request->value1)->first();
            if($clientCurrency){
                Session::put('customerCurrency', $request->value1);
                Session::put('currencySymbol', $request->value2);
                Session::put('currencyMultiplier', $clientCurrency->doller_compare);
            }
        }

        $data['customerLanguage'] = Session::get('customerLanguage');
        $data['customerCurrency'] = Session::get('customerCurrency');
        $data['currencySymbol'] = Session::get('currencySymbol');

        return response()->json(['status'=>'success', 'message' => 'Saved Successfully!', 'data' => $data]);
    }

    public function changePaginate(Request $request)
    {
        $perPage = 12;
        if($request->has('itemPerPage')){
             $perPage = $request->itemPerPage;
        }
        Session::put('cus_paginate', $perPage);
        return response()->json(['status'=>'success', 'message' => 'Saved Successfully!', 'data' => $perPage]);
    }

    public function getClientPreferences(Request $request)
    {
       $clientPreferences = ClientPreference::first();
       if($clientPreferences){
           $dinein_check = $clientPreferences->dinein_check;
           $delivery_check = $clientPreferences->delivery_check;
           $takeaway_check = $clientPreferences->takeaway_check;
           return response()->json(["dinein_check" => $dinein_check, "delivery_check" => $delivery_check, "takeaway_check" => $takeaway_check]);
       }
    }
}