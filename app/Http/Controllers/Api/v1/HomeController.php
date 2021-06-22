<?php

namespace App\Http\Controllers\Api\v1;

use DB;
use Config;
use Validation;
use Carbon\Carbon;
use ConvertCurrency;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Api\v1\BaseController;
use App\Models\{User, Category, Brand, Client, ClientPreference, Cms, Order, Banner, Vendor, Category_translation, ClientLanguage, Product, Country, Currency, ServiceArea, ClientCurrency, ProductCategory, BrandTranslation, Celebrity, UserVendor, AppStyling};

class HomeController extends BaseController{
    use ApiResponser;

    private $curLang = 0;
    private $field_status = 2;

    /** Return header data, client profile and configure data */
    public function headerContent(Request $request){
        try {
            $homeData = array();
            $homeData['profile'] = Client::with('preferences')->select('company_name', 'code', 'logo', 'company_address', 'phone_number', 'email')->first();
            $app_styling_detail = AppStyling::getSelectedData();
            foreach ($app_styling_detail as $app_styling) {
                $key = $app_styling['key'];
                $homeData['profile']->preferences->$key = $app_styling['value'];
            }
            $homeData['languages'] = ClientLanguage::with('language')->select('language_id', 'is_primary')->where('is_active', 1)->orderBy('is_primary', 'desc')->get();
            $banners = Banner::select("id", "name", "description", "image", "link", 'redirect_category_id', 'redirect_vendor_id')
                        ->where('status', 1)->where('validity_on', 1)
                        ->where(function($q){
                            $q->whereNull('start_date_time')->orWhere(function($q2){
                                $q2->whereDate('start_date_time', '<=', Carbon::now())
                                    ->whereDate('end_date_time', '>=', Carbon::now());
                            });
                        })->orderBy('sorting', 'asc')->get();
            if($banners){
                foreach ($banners as $key => $value) {
                    $bannerLink = '';
                    if(!empty($value->link) && $value->link == 'category'){
                        $bannerLink = $value->redirect_category_id;
                    }
                    if(!empty($value->link) && $value->link == 'vendor'){
                        $bannerLink = $value->redirect_vendor_id;
                    }
                    $value->redirect_to = ucwords($value->link);
                    $value->redirect_id = $bannerLink;
                    unset($value->redirect_category_id);
                    unset($value->redirect_vendor_id);
                }
            }
            $homeData['banners'] = $banners;
            $homeData['currencies'] = ClientCurrency::with('currency')->select('currency_id', 'is_primary', 'doller_compare')->orderBy('is_primary', 'desc')->get();
            return $this->successResponse($homeData);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /** return dashboard content like categories, vendors, brands, products     */
    public function homepage(Request $request)
    {
        try{
            $vends = [];
            $homeData = [];
            $user = Auth::user();
            $latitude = $request->latitude;
            $longitude = $request->longitude;
            $user_geo[] = $latitude;
            $user_geo[] = $longitude;
            $preferences = ClientPreference::select('is_hyperlocal', 'client_code', 'language_id')->first();
            $vendorData = Vendor::select('id', 'name', 'banner', 'order_pre_time', 'order_min_amount');
            if($preferences->is_hyperlocal == 1){
                $vendorData = $vendorData->whereHas('serviceArea', function($query) use($latitude, $longitude){
                        $query->select('vendor_id')
                        ->whereRaw("ST_Contains(polygon, ST_GeomFromText('POINT(".$latitude." ".$longitude.")'))");
                });
            }
            $vendorData = $vendorData->where('status', '!=', $this->field_status)->get();
            foreach ($vendorData as $key => $value) {
                $vends[] = $value->id;
            }
            $isVendorArea = 0;
            $langId = $user->language;
            $categories = $this->categoryNav($langId);
            $homeData['vendors'] = $vendorData;
            $homeData['categories'] = $categories;
            $homeData['reqData'] = $request->all();
            $homeData['brands'] = Brand::with(['translation' => function($q) use($langId){
                                    $q->select('brand_id', 'title')->where('language_id', $langId);
                                }])->select('id', 'image')->where('status', '!=', $this->field_status)
                                ->orderBy('position', 'asc')->get();
            $user_vendor_count = UserVendor::where('user_id', $user->id)->count();
            $homeData['is_admin'] = $user_vendor_count > 0 ? 1 : 0;                  
            return $this->successResponse($homeData);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /** return product meta data for new products, featured products, onsale products     */
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
                    ])->select('id', 'sku', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'sell_when_out_of_stock', 'requires_shipping', 'Requires_last_mile', 'averageRating')
                    ->where('is_live', 1);
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
                    $value->variant[$k]->multiplier = $clientCurrency->doller_compare;
                }
            }
        }
        return $products;
    }

    public function globalSearch(Request $request, $for = 'all', $dataId = 0)
    {
        try {
            $keyword = $request->keyword;
            $langId = Auth::user()->language;
            $curId = Auth::user()->language;
            $response = array();
            if($for == 'all'){
                $categories = Category::with(['type'  => function($q){
                            $q->select('id', 'title as redirect_to');
                        }])
                        ->join('category_translations as ct', 'ct.category_id', 'categories.id')
                        ->select('categories.id', 'categories.slug', 'categories.type_id', 'ct.name as dataname', 'ct.trans-slug', 'ct.meta_title', 'ct.meta_description', 'ct.meta_keywords', 'ct.category_id')
                        ->where('ct.language_id', $langId)
                        ->where(function ($q) use ($keyword) {
                            $q->where('ct.name', ' LIKE', '%' . $keyword . '%')
                            ->orWhere('categories.slug', 'LIKE', '%' . $keyword . '%')
                                ->orWhere('ct.trans-slug', 'LIKE', '%' . $keyword . '%');
                        })->where('categories.status', '!=', '2')->get();
                
                foreach ($categories as $key => $value) {
                    $value->response_type = 'category';
                    $response[] = $value;
                }
                $brands = Brand::join('brand_translations as bt', 'bt.brand_id', 'brands.id')
                        ->select('brands.id', 'bt.title  as dataname')
                        ->where('bt.title', 'LIKE', '%' . $keyword . '%')
                        ->where('brands.status', '!=', '2')
                        ->where('bt.language_id', $langId)
                        ->orderBy('brands.position', 'asc')->get();

                foreach ($brands as $brand) {
                    $brand->response_type = 'brand';
                    $response[] = $brand;
                }
                $vendors  = Vendor::select('id', 'name  as dataname', 'address')->where(function ($q) use ($keyword) {
                        $q->where('name', ' LIKE', '%' . $keyword . '%')->orWhere('address', 'LIKE', '%' . $keyword . '%');
                    })->where('vendors.status', '!=', '2')->get();
                foreach ($vendors as $vendor) {
                    $vendor->response_type = 'vendor';
                    $response[] = $vendor;
                }
                $products = Product::join('product_translations as pt', 'pt.product_id', 'products.id')
                            ->select('products.id', 'products.sku', 'pt.title  as dataname', 'pt.body_html', 'pt.meta_title', 'pt.meta_keyword', 'pt.meta_description')
                            ->where('pt.language_id', $langId)
                            ->where(function ($q) use ($keyword) {
                                $q->where('products.sku', ' LIKE', '%' . $keyword . '%')->orWhere('products.url_slug', 'LIKE', '%' . $keyword . '%')->orWhere('pt.title', 'LIKE', '%' . $keyword . '%');
                            })->where('products.is_live', 1)->whereNull('deleted_at')->get();
                foreach ($products as $product) {
                    $product->response_type = 'product';
                    $response[] = $product;
                }
                return $this->successResponse($response);
            }else{
                $products = Product::join('product_translations as pt', 'pt.product_id', 'products.id')
                            ->select('products.id', 'products.sku', 'pt.title', 'pt.body_html', 'pt.meta_title', 'pt.meta_keyword', 'pt.meta_description')
                            ->where('pt.language_id', $langId)
                            ->where(function ($q) use ($keyword) {
                                    $q->where('products.sku', ' LIKE', '%' . $keyword . '%')
                                    ->orWhere('products.url_slug', 'LIKE', '%' . $keyword . '%')
                                    ->orWhere('pt.title', 'LIKE', '%' . $keyword . '%')
                                    ->orWhere('pt.body_html', 'LIKE', '%' . $keyword . '%')
                                    ->orWhere('pt.meta_title', 'LIKE', '%' . $keyword . '%')
                                    ->orWhere('pt.meta_keyword', 'LIKE', '%' . $keyword . '%')
                                    ->orWhere('pt.meta_description', 'LIKE', '%' . $keyword . '%');
                    });
                if($for == 'category'){
                    $prodIds = array();
                    $productCategory = ProductCategory::select('product_id')->where('category_id', $dataId)->get();
                    if($productCategory){
                        foreach ($productCategory as $key => $value) {
                            $prodIds[] = $value->product_id;
                        }
                    }
                    $products = $products->whereIn('products.id', $prodIds);
                }
                if($for == 'vendor'){
                    $products = $products->where('products.vendor_id', $dataId);
                }
                if($for == 'brand'){
                    $products = $products->where('products.brand_id', $dataId);
                }
                $products = $products->where('products.is_live', 1)->whereNull('deleted_at')->get();
                foreach ($products as $product) {
                    $product->response_type = 'product';
                    $response[] = $product;
                }
            }
            return $this->successResponse($response);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}