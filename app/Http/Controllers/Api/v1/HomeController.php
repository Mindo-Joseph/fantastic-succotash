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
        $categories = Category::join('category_translations as cts', 'categories.id', 'cts.category_id')
                        ->leftjoin('types', 'types.id', 'categories.type_id')
                        ->select('categories.id', 'categories.icon', 'categories.slug', 'categories.parent_id', 'cts.name', 'types.title as redirect_to')
                        ->where('categories.id', '>', '1')
                        ->where('categories.status', '!=', $this->field_status)
                        ->where('cts.language_id', Auth::user()->language)
                        ->orderBy('categories.parent_id', 'asc')
                        ->orderBy('categories.position', 'asc')->get();
        if($categories){
            $categories = $this->buildTree($categories->toArray());
        }
        //print_r($categories->toArray());
        $homeData['reqData'] = $request->all();
        $homeData['categories'] = $categories;
        $homeData['vendors'] = $vendorData;

        $homeData['brands'] = Brand::select('id', 'title', 'image')
                        ->where('status', '!=', $this->field_status)->orderBy('position', 'asc')->get();

        $homeData['featuredProducts'] = $this->productList($vends, Auth::user()->currency, 'is_featured');
        $homeData['newProducts'] = $this->productList($vends, Auth::user()->currency, 'is_new');
        
        $homeData['onSale'] = $this->productList($vends, Auth::user()->currency);

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

    public function productList($venderIds, $currency = 'USD', $where = '')
    {
        $clientCurrency = ClientCurrency::where('currency_id', Auth::user()->currency)->first();
        $products = Product::with('pimage', 'baseprice')
                        ->join('product_translations as trans', 'trans.product_id', 'products.id')
                        ->select('trans.title', 'trans.body_html', 'products.sku', 'products.id')
                        ->where('trans.language_id', Auth::user()->language);
        if($where !== ''){
            $products = $products->where('products.'.$where, 1);
        }
        if(is_array($venderIds) && count($venderIds) > 0){
            $products = $products->whereIn('vendor_id', $venderIds);
        }
        $products = $products->get();

        if(!empty($products)){

            foreach ($products as $key => $value) {

                if(!empty($value->pimage) && count($value->pimage) > 0){
                    $imgs = array();
                    foreach ($value->pimage as $k => $v) {
                        $products{$key}->image = \Storage::disk('s3')->url($v->path);
                    }
                }else{
                    $products{$key}->image = \Storage::disk('s3')->url('default/default_image.png');
                }

                unset($products{$key}->pimage);

                $prodPrice = '0.00';

                if(!empty($value->baseprice) && count($value->baseprice) > 0){

                    $prodPrice = $value->baseprice[0]->price;
                }
                $products{$key}->price = $prodPrice;
                $products{$key}->multiplier = $clientCurrency->doller_compare;
                unset($products{$key}->baseprice);

            }
        }
        return $products;
    }

}