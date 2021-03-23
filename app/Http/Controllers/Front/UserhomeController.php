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

        $vends = array();

        $vendorData = Vendor::select('id', 'name', 'banner', 'order_pre_time', 'order_min_amount');

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
                            $q2->where('start_date_time', '<=', Carbon::now())
                                ->where('end_date_time', '>=', Carbon::now());
                        });
                    })
                    ->orderBy('sorting', 'asc')->get();

        $navCategories = $this->categoryNav($langId);

        Session::put('navCategories', $navCategories);


        $brands = Brand::join('brand_translations as bt', 'bt.brand_id', 'brands.id')
                    ->select('brands.id', 'brands.image', 'bt.title', 'bt.language_id')
                    ->where('brands.status', '!=', $this->field_status)
                    ->where('bt.language_id', $langId)
                    ->orderBy('position', 'asc')->get();

        $featured = $this->productList($vends, $langId, 'USD', 'is_featured');
        $newProdu = $this->productList($vends, $langId, 'USD', 'is_new');
        
        $onSale = $this->productList($vends, $langId, 'USD');
        //dd($banners->toArray());
        $featuredPro = array_chunk($featured->toArray(), ceil(count($featured) / 2));
        $newProducts = array_chunk($newProdu->toArray(), ceil(count($newProdu) / 2));
        $onSaleProds = array_chunk($onSale->toArray(), ceil(count($onSale) / 2));

        return view('forntend/home')->with(['home' => $home, 'banners' => $banners, 'navCategories' => $navCategories, 'brands' => $brands, 'vendors' => $vendorData, 'featuredProducts' => $featuredPro, 'newProducts' => $newProducts, 'onSaleProducts' => $onSaleProds]);
    }

    public function productList($venderIds, $langId, $currency = 'USD', $where = '')
    {
        $products = Product::with(['media' => function($q){
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
        if(is_array($venderIds) && count($venderIds) > 0){
            $products = $products->whereIn('vendor_id', $venderIds);
        }
        $products = $products->get();

        if(!empty($products)){
            foreach ($products as $key => $value) {
                foreach ($value->variant as $k => $v) {
                    $value->variant{$k}->multiplier = Session::get('currencyMultiplier');
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
}