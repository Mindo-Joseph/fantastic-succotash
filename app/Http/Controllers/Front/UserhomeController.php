<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Front\FrontController;
use App\Models\{Currency, Banner, Category, Brand, Product, ClientLanguage, Vendor, ClientCurrency, ClientPreference};
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;
use App\Http\Traits\ApiResponser;

class UserhomeController extends FrontController
{
    use ApiResponser;
    private $field_status = 2;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $home = array();
        $vendor_ids = array();
        $latitude = Session::get('latitude');
        $longitude = Session::get('longitude');
        $curId = Session::get('customerCurrency');
        $preferences = Session::get('preferences');
        $langId = Session::get('customerLanguage');
        $client_config = Session::get('client_config');
        $deliveryAddress = Session::get('deliveryAddress');
        $navCategories = $this->categoryNav($langId);
        Session::put('navCategories', $navCategories); 
        $vendorData = Vendor::select('id', 'name', 'banner', 'order_pre_time', 'order_min_amount', 'logo');
        if($preferences->is_hyperlocal == 1){
            $vendorData = $vendorData->whereHas('serviceArea', function($query) use($latitude, $longitude){
                $query->select('vendor_id')
                ->whereRaw("ST_Contains(POLYGON, ST_GEOMFROMTEXT('POINT(".$latitude." ".$longitude.")'))");
            });
        }
        $vendorData = $vendorData->where('status', '!=', $this->field_status)->get();
        foreach ($vendorData as $key => $value) {
            $vendor_ids[] = $value->id;
        }
        $banners = Banner::where('status', 1)->where('validity_on', 1)
                    ->where(function($q){
                        $q->whereNull('start_date_time')->orWhere(function($q2){
                            $q2->whereDate('start_date_time', '<=', Carbon::now())
                                ->whereDate('end_date_time', '>=', Carbon::now());
                        });
                    })
                    ->orderBy('sorting', 'asc')->get();
        $brands = Brand::with(['translation' => function($q) use($langId){
                        $q->select('brand_id', 'title')->where('language_id', $langId);
                        }])
                    ->select('id', 'image')
                    ->where('status', '!=', $this->field_status)
                    ->orderBy('position', 'asc')->get();
        $fp = $this->productList($vendor_ids, $langId, $curId, 'is_featured');
        $np = $this->productList($vendor_ids, $langId, $curId, 'is_new');
        $onSP = $this->productList($vendor_ids, $langId, 'USD');
        $featuredPro = ($fp->count() > 0) ? array_chunk($fp->toArray(), ceil(count($fp) / 2)) : $fp;
        $newProducts = ($np->count() > 0) ? array_chunk($np->toArray(), ceil(count($np) / 2)) : $np;
        $onSaleProds = ($onSP->count() > 0) ? array_chunk($onSP->toArray(), ceil(count($onSP) / 2)) : $onSP;
        return view('frontend.home')->with(['home' => $home, 'banners' => $banners, 'navCategories' => $navCategories, 'brands' => $brands, 'vendors' => $vendorData, 'featuredProducts' => $featuredPro, 'newProducts' => $newProducts, 'onSaleProducts' => $onSaleProds, 'deliveryAddress' => $deliveryAddress, 'latitude' => $latitude, 'longitude' => $longitude]);
    }

    public function homepage(Request $request)
    {
        try{
            $preferences = ClientPreference::select('is_hyperlocal', 'client_code', 'language_id')->first();
            Session::put('deliveryAddress', $request->selectedAddress);
            Session::put('latitude', $request->latitude);
            Session::put('longitude', $request->longitude);
            $curId = Session::get('customerCurrency');
            $lats = $request->latitude;
            $longs = $request->longitude;
            $user_geo[] = $lats;
            $user_geo[] = $longs;
            $vendors = array();
            $vendorData = Vendor::select('id', 'name', 'banner', 'order_pre_time', 'order_min_amount', 'logo');
            if($preferences->is_hyperlocal == 1){
                $vendorData = $vendorData->whereHas('serviceArea', function($query) use($lats, $longs){
                        $query->select('vendor_id')
                        ->whereRaw("ST_Contains(POLYGON, ST_GEOMFROMTEXT('POINT(".$lats." ".$longs.")'))");
                });
            }
            $vendorData = $vendorData->where('status', '!=', $this->field_status)->get();
            
            $isVendorArea = 0;
            $langId = (isset(Auth::user()->language)) ? Auth::user()->language : Session::get('customerLanguage');
            $homeData = array();
            $categories = $this->categoryNav($langId);
            $homeData['reqData'] = $request->all();
            $homeData['categories'] = $categories;
            $homeData['vendors'] = $vendorData;
            $homeData['brands'] = Brand::with(['translation' => function($q) use($langId){
                            $q->select('brand_id', 'title')->where('language_id', $langId);
                            }])->select('id', 'image')->where('status', '!=', $this->field_status)->orderBy('position', 'asc')->get();
            foreach($vendorData as $key => $data){
                $vendors[] = $data->id;
            }
            Session::put('vendors', $vendors);
            
            $fp = $this->productList($vendors, $langId, $curId, 'is_featured');
            $np = $this->productList($vendors, $langId, $curId, 'is_new');
            $onSP = $this->productList($vendors, $langId, 'USD');
            $homeData['featuredProducts'] = ($fp->count() > 0) ? array_chunk($fp->toArray(), ceil(count($fp) / 2)) : $fp;
            $homeData['newProducts'] = ($np->count() > 0) ? array_chunk($np->toArray(), ceil(count($np) / 2)) : $np;
            $homeData['onSaleProducts'] = ($onSP->count() > 0) ? array_chunk($onSP->toArray(), ceil(count($onSP) / 2)) : $onSP;

            return $this->successResponse($homeData);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
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
}