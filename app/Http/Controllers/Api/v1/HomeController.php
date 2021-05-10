<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\v1\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\{User, Category, Brand, Client, ClientPreference, Cms, Order, Banner, Vendor, Category_translation, ClientLanguage, Product, Country, Currency, ServiceArea, ClientCurrency, ProductCategory, BrandTranslation};
use Validation;
use DB;
use Illuminate\Support\Facades\Storage;
use Config;
use ConvertCurrency;

use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

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

        $banners = Banner::select("id", "name", "description", "image", "link", 'redirect_category_id', 'redirect_vendor_id')
                    ->where('status', 1)
                    ->where(function($q){
                        $q->whereNull('start_date_time')->orWhere(function($q2){
                            $q2->whereDate('start_date_time', '<=', Carbon::now())
                                ->whereDate('end_date_time', '>=', Carbon::now());
                        });
                    })
                    ->orderBy('sorting', 'asc')->get();

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
                    ->whereRaw("ST_Contains(polygon, ST_GeomFromText('POINT(".$lats." ".$longs.")'))");
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
                    $value->variant{$k}->multiplier = $clientCurrency->doller_compare;
                }
            }
        }
        return $products;
    }

    public function searchDataOld(Request $request, $for = 'all', $dataId = 0)
    {
        $langId = Auth::user()->language;
        $curId = Auth::user()->language;
        if($for != 'all' && $for != 'category' && $for != 'vendor'  && $for != 'brand'){
            return response()->json(['error' => 'No record found.'], 404);
        } 
        if($for != 'all' && $dataId < 1){
            return response()->json(['error' => 'Invalid request.'], 404);
        }

        if (empty($request->keyword)) {
            return response()->json([
                'data' => array('products' => [],  'categories' => [], 'vendors' => [], 'brands' => []),
            ]);
        }

        $response = array();
        switch ($for) {
            case 'category':
                $category = Category::find($dataId);
                if(!$category){
                    return response()->json(['error' => 'No record found.'], 404);
                }
                break;
            case 'vendor':
                $vendor = Vendor::find($dataId);
                if(!$vendor){
                    return response()->json(['error' => 'No record found.'], 404);
                }
                break;
            case 'brand':
                $brand = Brand::find($dataId);
                if(!$brand){
                    return response()->json(['error' => 'No record found.'], 404);
                }
                break;
            default:
                $a = 'hello';
        }
        $userid = Auth::user()->id;
        $response = $this->search($langId, $curId, $for, $request->keyword, $dataId, $userid);
        return response()->json([
            'data' => $response,
            'keyword' => $request->keyword,
        ]);
    }

    public function searchOld($langId, $curId, $for, $keyword, $dataId, $userid)
    {
        $response = array();
        if($for == 'all'){
            $response['categories'] = Category::join('category_translations as ct', 'ct.category_id', 'categories.id')
            ->select('categories.id', 'categories.icon', 'categories.slug', 'categories.type_id', 'categories.image', 'ct.name', 'ct.trans-slug', 'ct.meta_title', 'ct.meta_description', 'ct.meta_keywords', 'ct.category_id')
            ->where('ct.language_id', $langId)
            ->where(function ($q) use ($keyword) {
                $q->where('ct.name', ' LIKE', '%' . $keyword . '%')
                    ->orWhere('ct.trans-slug', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('ct.meta_title', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('ct.meta_description', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('ct.meta_keywords', 'LIKE', '%' . $keyword . '%');
            })->where('categories.status', '!=', '2')->get();

            $response['brands'] = Brand::join('brand_translations as bt', 'bt.brand_id', 'brands.id')
            ->select('brands.id', 'brands.image', 'bt.title')
            ->where('bt.title', 'LIKE', '%' . $keyword . '%')
            ->where('brands.status', '!=', '2')
            ->orderBy('brands.position', 'asc')->get();

            $response['vendors']  = Vendor::where(function ($q) use ($keyword) {
                $q->where('name', ' LIKE', '%' . $keyword . '%')->orWhere('address', 'LIKE', '%' . $keyword . '%');
            })->where('vendors.status', '!=', '2')->get();
        }

        $products = Product::join('product_translations as pt', 'pt.product_id', 'products.id')
                    ->with(['media.image', 'inwishlist' => function($qry) use($userid){
                        $qry->where('user_id', $userid);
                    },
                    'variant' => function($q) use($langId){
                            $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                            $q->groupBy('product_id');
                    },
                    'translation' => function($q) use($langId){
                        $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                        },
                    ])
                    ->select('products.id', 'products.sku', 'products.url_slug', 'products.weight_unit', 'products.weight', 'products.vendor_id', 'products.has_variant', 'products.has_inventory', 'products.sell_when_out_of_stock', 'products.requires_shipping', 'products.Requires_last_mile', 'products.averageRating')
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
            foreach ($productCategory as $key => $value) {
                $prodIds[] = $value->product_id;
            }

            $products = $products->whereIn('products.id', $prodIds);
        }

        if($for == 'vendor'){
            $products = $products->where('products.vendor_id', $dataId);
        }

        if($for == 'brand'){
            $products = $products->where('products.brand_id', $dataId);
        }

        $products = $products->where('products.is_live', 1)->get();
        $clientCurrency = ClientCurrency::where('currency_id', $curId)->first();
        if(!empty($products)){
            foreach ($products as $key => $value) {
                foreach ($value->variant as $k => $v) {
                    $value->variant{$k}->multiplier = $clientCurrency->doller_compare;
                }
            }
        }

        $response['products'] = $products;

        return $response;
    }

    public function globalSearch(Request $request, $for = 'all', $dataId = 0)
    {
        $keyword = $request->keyword;
        $langId = Auth::user()->language;
        $curId = Auth::user()->language;

        if($for == 'all'){
            $categories = Category::join('category_translations as ct', 'ct.category_id', 'categories.id')
                ->select('categories.id', 'categories.slug', 'ct.name as dataname', 'ct.trans-slug', 'ct.meta_title', 'ct.meta_description', 'ct.meta_keywords', 'ct.category_id')
                ->where('ct.language_id', $langId)
                ->where(function ($q) use ($keyword) {
                    $q->where('ct.name', ' LIKE', '%' . $keyword . '%')
                        ->orWhere('ct.trans-slug', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('ct.meta_title', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('ct.meta_description', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('ct.meta_keywords', 'LIKE', '%' . $keyword . '%');
                })->where('categories.status', '!=', '2')->get();

            $response = array();
            foreach ($categories as $key => $value) {
                $value->type = 'category';

                $response[] = $value;
            }

            $brands = Brand::join('brand_translations as bt', 'bt.brand_id', 'brands.id')
                    ->select('brands.id', 'bt.title  as dataname')
                    ->where('bt.title', 'LIKE', '%' . $keyword . '%')
                    ->where('brands.status', '!=', '2')
                    ->where('bt.language_id', $langId)
                    ->orderBy('brands.position', 'asc')->get();

            foreach ($brands as $key => $value) {
                $value->type = 'brand';

                $response[] = $value;
            }


            $vendors  = Vendor::select('id', 'name  as dataname', 'address')->where(function ($q) use ($keyword) {
                    $q->where('name', ' LIKE', '%' . $keyword . '%')->orWhere('address', 'LIKE', '%' . $keyword . '%');
                })->where('vendors.status', '!=', '2')->get();

            foreach ($vendors as $key => $value) {
                $value->type = 'vendor';

                $response[] = $value;
            }

            $products = Product::join('product_translations as pt', 'pt.product_id', 'products.id')
                        ->select('products.id', 'products.sku', 'pt.title  as dataname', 'pt.body_html', 'pt.meta_title', 'pt.meta_keyword', 'pt.meta_description')
                        ->where('pt.language_id', $langId)
                        ->where(function ($q) use ($keyword) {
                                $q->where('products.sku', ' LIKE', '%' . $keyword . '%')
                                ->orWhere('products.url_slug', 'LIKE', '%' . $keyword . '%')
                                 ->orWhere('pt.title', 'LIKE', '%' . $keyword . '%')
                                // ->orWhere('pt.body_html', 'LIKE', '%' . $keyword . '%')
                                // ->orWhere('pt.meta_title', 'LIKE', '%' . $keyword . '%')
                                // ->orWhere('pt.meta_keyword', 'LIKE', '%' . $keyword . '%')
                                // ->orWhere('pt.meta_description', 'LIKE', '%' . $keyword . '%');
                    })->where('products.is_live', 1)->get();
          
            foreach ($products as $key => $value) {
                $value->type = 'product';

                $response[] = $value;
            }
            return response()->json([
                'data' => $response,
                'keyword' => $request->keyword,
            ]);
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

            $products = $products->where('products.is_live', 1)->get();

            foreach ($products as $key => $value) {
                $value->type = 'product';

                $response[] = $value;
            }
            return response()->json([
                'data' => $response,
                'keyword' => $request->keyword,
            ]);
        }
        
    }
}