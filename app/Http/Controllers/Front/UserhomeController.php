<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Front\FrontController;
use App\Models\{Currency, Banner, Category, Brand, Product, ClientLanguage, Vendor, ClientCurrency};
use Illuminate\Http\Request;
use Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;

class UserhomeController extends FrontController
{
    private $field_status = 2;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $home = array();
        $client_config = Session::get('client_config');
        $value = Session::get('preferences');
        //$clientLanguage = ClientLanguage::where('is_primary', 1)->first();
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $vends = array();

        $vendorData = Vendor::select('id', 'name', 'banner', 'order_pre_time', 'order_min_amount', 'logo');

        if(session('preferences.is_hyperlocal') == 1){
            /*$vendorData = $vendorData->whereIn('id', function($query) use($lats, $longs){
                    $query->select('vendor_id')
                    ->from(with(new ServiceArea)->getTable())
                    ->whereRaw("ST_Contains(polygon, GeomFromText('POINT(".$lats." ".$longs.")'))");
            });*/
        }
        $vendorData = $vendorData->where('status', '!=', $this->field_status)->get();

        foreach ($vendorData as $key => $value) {
            $vends[] = $value->id;
        }

        $banners = Banner::where('status', 1)
                    ->where(function($q){
                        $q->whereNull('start_date_time')->orWhere(function($q2){
                            $q2->whereDate('start_date_time', '<=', Carbon::now())
                                ->whereDate('end_date_time', '>=', Carbon::now());
                        });
                    })
                    ->orderBy('sorting', 'asc')->get();

        $navCategories = $this->categoryNav($langId);

        Session::put('navCategories', $navCategories);
        $brands = Brand::with(['translation' => function($q) use($langId){
                        $q->select('brand_id', 'title')->where('language_id', $langId);
                        }])
                    ->select('id', 'image')
                    ->where('status', '!=', $this->field_status)
                    ->orderBy('position', 'asc')->get();

        $fp = $this->productList($vends, $langId, $curId, 'is_featured');
        $np = $this->productList($vends, $langId, $curId, 'is_new');
        
        $onSP = $this->productList($vends, $langId, 'USD');
        //dd($banners->toArray());
        $featuredPro = ($fp->count() > 0) ? array_chunk($fp->toArray(), ceil(count($fp) / 2)) : $fp;
        $newProducts = ($np->count() > 0) ? array_chunk($np->toArray(), ceil(count($np) / 2)) : $np;
        $onSaleProds = ($onSP->count() > 0) ? array_chunk($onSP->toArray(), ceil(count($onSP) / 2)) : $onSP;

        return view('forntend/home')->with(['home' => $home, 'banners' => $banners, 'navCategories' => $navCategories, 'brands' => $brands, 'vendors' => $vendorData, 'featuredProducts' => $featuredPro, 'newProducts' => $newProducts, 'onSaleProducts' => $onSaleProds]);
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
}