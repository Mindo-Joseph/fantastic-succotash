<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\v1\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\{User, Category, Brand, Client, ClientPreference, Cms, Order, Banner, Vendor, Category_translation, ClientLanguage, Product, Country, Currency, ServiceArea, ClientCurrency};
use Validation;
use DB;
use Illuminate\Support\Facades\Storage;
use Config;
use ConvertCurrency;

class HomeController extends BaseController
{
    private $field_status = 2;
    private $curLang = 0;

    public function __construct(){
        
    }

    /**
     * update driver availability status if 0 than 1 if 1 than 0

     */
    public function headerContent(Request $request)
    {
        $homeData = array();

        $homeData['profile'] = Client::with('preferences')->select('company_name', 'code', 'logo', 'company_address', 'phone_number', 'email')->first();

        $homeData['languages'] = ClientLanguage::with('language')->select('language_id', 'is_primary')
                                ->where('is_active', 1)->orderBy('is_primary', 'desc')->get();

        $homeData['banners'] = Banner::select("id", "name", "description", "image", "link")->orderBy('sorting', 'asc')->get();

        $homeData['currencies'] = ClientCurrency::with('currency')->select('currency_id', 'is_primary', 'doller_compare')->orderBy('is_primary', 'desc')->get();
        return response()->json([
            'data' => $homeData,
        ]);
    }

    /**
     * update driver availability status if 0 than 1 if 1 than 0
    */
    public function homepage(Request $request)
    {
        $preferences = ClientPreference::select('is_hyperlocal', 'client_code', 'language_id')->first();
        $lats = $request->latitude;
        $longs = $request->longitude;
        $user_geo[] = $lats;
        $user_geo[] = $longs;

        $vends = array();

        $vendorData = Vendor::select('id', 'name', 'banner', 'order_pre_time', 'order_min_amount');

        if($preferences->is_hyperlocal == 1){
            $vendorData = $vendorData->whereIn('id', function($query) use($lats, $longs){
                    $query->select('vendor_id')
                    ->from(with(new ServiceArea)->getTable())
                    ->whereRaw("ST_Contains(polygon, GeomFromText('POINT(".$lats." ".$longs.")'))");
            });
        }
        $vendorData = $vendorData->where('status', '!=', $this->field_status)->get();

        foreach ($vendorData as $key => $value) {
            $vends[] = $value->id;
        }
        $isVendorArea = 0;
        $langId = Auth::user()->language;

        $homeData = array();
        /*$categories = Category::join('category_translations as cts', 'categories.id', 'cts.category_id')
                        ->leftjoin('types', 'types.id', 'categories.type_id')
                        ->select('categories.id', 'categories.icon', 'categories.slug', 'categories.parent_id', 'cts.name', 'types.title as redirect_to')
                        ->where('categories.id', '>', '1')
                        ->where('categories.status', '!=', $this->field_status)
                        ->where('cts.language_id', Auth::user()->language)
                        ->orderBy('categories.parent_id', 'asc')
                        ->orderBy('categories.position', 'asc')->get();
        if($categories){
            $categories = $this->buildTree($categories->toArray());
        }*/

        $categories = $this->categoryNav($langId);
        //print_r($categories->toArray());
        $homeData['reqData'] = $request->all();
        $homeData['categories'] = $categories;
        $homeData['vendors'] = $vendorData;

        $homeData['brands'] = Brand::with(['translation' => function($q) use($langId){
                        $q->select('brand_id', 'title')->where('language_id', $langId);
                        }])
                    ->select('id', 'image')
                    ->where('status', '!=', $this->field_status)
                    ->orderBy('position', 'asc')->get();

        $homeData['featuredProducts'] = $this->productList($vends, $langId, Auth::user()->currency, 'is_featured');
        $homeData['newProducts'] = $this->productList($vends, $langId, Auth::user()->currency, 'is_new');
        
        $homeData['onSale'] = $this->productList($vends, $langId, Auth::user()->currency);

        return response()->json([
            'data' => $homeData,
        ]);
    }

    /*public function inServiceArea($user_geo, $area, $count = 0)
    {
        //echo '<pre>';print_r($area->toArray()); die;
        foreach ($area as $geokey => $geovalue) {
            $availables = $this->contains($user_geo, $geovalue->geo_coordinates);
            if($availables){
                return 1;
            }
            $count++;
        }
        return 0;
    }*/

    public function productList($venderIds, $langId = 1, $currency = 147, $where = '')
    {
        $clientCurrency = ClientCurrency::where('currency_id', $currency)->first();
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
                    $value->variant{$k}->multiplier = $clientCurrency->doller_compare;
                }
            }
        }
        return $products;
    }

}