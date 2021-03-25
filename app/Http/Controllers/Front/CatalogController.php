<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Front\FrontController;
use App\Models\{Currency, Banner, Category, Brand, Product, ClientLanguage, Vendor, ClientCurrency};
use Illuminate\Http\Request;
use Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Hash;

class CatalogController extends FrontController
{
    private $field_status = 2;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $domain = '', $sku)
    {
        foreach ($request->only('') as $key => $value) {
            # code...
        }
        /*$product = Product::with(['vendor', 'primary', 'addOn', 'media.image', 'translation' => function($qq) use(Session::get('lang_id'))])
                ->select('id', 'sku', 'title', 'url_slug', 'sell_when_out_of_stock', 'Requires_last_mile', 'vendor_id', 'weight_unit', 'weight', 'has_inventory', 'has_variant', 'requires_shipping', 'averageRating')
                ->where('sku', $sku)
                ->firstOrFail();*/
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $navCategories = $this->categoryNav($langId);

        $product = Product::with(['variant',  'variant.vimage.pimage.image', 'related', 'upSell', 'crossSell', 'vendor', 'media.image', 'addOn' => function($q1) use($langId){
                        $q1->join('addon_sets as set', 'set.id', 'product_addons.addon_id');
                        $q1->join('addon_set_translations as ast', 'ast.addon_id', 'set.id');
                        $q1->select('product_addons.product_id', 'set.min_select', 'set.max_select', 'ast.title', 'product_addons.addon_id');
                        $q1->where('ast.language_id', $langId);
                    },
                    'variantSet' => function($z) use($langId){
                        $z->join('variants as vr', 'product_variant_sets.variant_type_id', 'vr.id');
                        $z->join('variant_translations as vt','vt.variant_id','vr.id');
                        $z->select('product_variant_sets.product_id', 'product_variant_sets.product_variant_id', 'product_variant_sets.variant_type_id', 'vr.type', 'vt.title');
                        $z->where('vt.language_id', $langId);
                    },
                    'variantSet.options' => function($zx) use($langId){
                        $zx->join('variant_option_translations as vt','vt.variant_option_id','variant_options.id');
                        $zx->select('variant_options.*', 'vt.title');
                        $zx->where('vt.language_id', $langId);
                    },
                    'translation' => function($q) use($langId){
                        $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description');
                        $q->where('language_id', $langId);
                    },
                    'addOn.setoptions' => function($q2) use($langId){
                        $q2->join('addon_option_translations as apt', 'apt.addon_opt_id', 'addon_options.id');
                        $q2->select('addon_options.id', 'addon_options.title', 'addon_options.price', 'apt.title', 'addon_options.addon_id');
                        $q2->where('apt.language_id', $langId);
                    },
                    ])->select('id', 'sku', 'url_slug', 'weight', 'weight_unit', 'vendor_id', 'has_variant', 'has_inventory')
                    ->where('sku', $sku)
                    ->where('is_live', 1)
                    ->firstOrFail();

        $clientCurrency = ClientCurrency::where('is_primary', '1')->first();
        foreach ($product->variant as $key => $value) {
            $product->variant[$key]->multiplier = $clientCurrency->doller_compare;
        }

        $vendorIds[] = $product->vendor_id;

        $np = $this->productList($vendorIds, $langId, $curId, 'is_new');
        $newProducts = ($np->count() > 0) ? array_chunk($np->toArray(), ceil(count($np) / 2)) : $np;

        foreach ($product->addOn as $key => $value) {
            foreach ($value->setoptions as $k => $v) {
                $v->multiplier = $clientCurrency->doller_compare;
            }
        }
        return view('forntend/product')->with(['product' => $product, 'navCategories' => $navCategories, 'newProducts' => $newProducts]);
    }

    public function categoryData(Request $request, $domain = '', $cid = 0)
    {
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $category = Category::with(['tags',
                    'type'  => function($q){
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
                    ->where('id', $cid)->firstOrFail();


        $navCategories = $this->categoryNav($langId);
        $vendorIds = array();
        $vendorList = Vendor::select('id', 'name')->where('status', '!=', $this->field_status)->get();
        if(!empty($vendorList)){
            foreach ($vendorList as $key => $value) {
                $vendorIds[] = $value->id;
            }
        }

        $listData = $this->listData($langId, $cid, $category->type->redirect_to);
        $category->type->redirect_to;
        $page = ($category->type->redirect_to == 'vendor' || $category->type->redirect_to == 'Vendor') ? 'vendor' : 'product';

        $np = $this->productList($vendorIds, $langId, $curId, 'is_new');
        $newProducts = ($np->count() > 0) ? array_chunk($np->toArray(), ceil(count($np) / 2)) : $np;

        //dd($listData->toArray());
        return view('forntend/cate-'.$page.'s')->with(['listData' => $listData, 'category' => $category, 'navCategories' => $navCategories, 'newProducts' => $newProducts]);
    }

    public function listData($langId, $cid, $tpye = ''){
        
        if($tpye == 'vendor' || $tpye == 'Vendor'){

            $vendorData = Vendor::select('id', 'name', 'logo', 'banner', 'order_pre_time', 'order_min_amount');

            /*if($preferences->is_hyperlocal == 1){
                $vendorData = $vendorData->whereIn('id', function($query) use($lats, $longs){
                        $query->select('vendor_id')
                        ->from(with(new ServiceArea)->getTable())
                        ->whereRaw("ST_Contains(polygon, GeomFromText('POINT(".$lats." ".$longs.")'))");
                });
            }*/
            $vendorData = $vendorData->where('status', '!=', $this->field_status)->paginate(8);

            return $vendorData;

        }elseif($tpye == 'product' || $tpye == 'Product'){

            $clientCurrency = ClientCurrency::where('currency_id', Session::get('customerCurrency'))->first();

            $products = Product::join('product_categories as pc', 'pc.product_id', 'products.id')
                    ->with(['media' => function($q){
                            $q->groupBy('product_id');
                        }, 'media.image',
                        'translation' => function($q) use($langId){
                        $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                        },
                        'variant' => function($q) use($langId){
                            $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                            $q->groupBy('product_id');
                        },
                    ])->select('products.id', 'products.sku', 'products.url_slug', 'products.weight_unit', 'products.weight', 'products.vendor_id', 'products.has_variant', 'products.has_inventory', 'products.sell_when_out_of_stock', 'products.requires_shipping', 'products.Requires_last_mile', 'products.averageRating')
                    ->where('pc.category_id', $cid)->where('products.is_live', 1)->paginate(8);

            if(!empty($products)){
                foreach ($products as $key => $value) {
                    foreach ($value->variant as $k => $v) {
                        $value->variant{$k}->multiplier = $clientCurrency->doller_compare;
                    }
                }
            }
            $listData = $products;
            return $listData;

        }else{
            $arr = array();
            return $arr;
        }
    }

    public function productsByVendor(Request $request, $domain = '', $vid = 0)
    {
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $clientCurrency = ClientCurrency::where('currency_id', $curId)->first();

        $vendor = Vendor::select('id', 'name', 'desc', 'logo', 'banner', 'address', 'latitude', 'longitude', 'order_min_amount', 'order_pre_time', 'auto_reject_time', 'dine_in', 'takeaway', 'delivery')
                ->where('id', $vid)->firstOrFail();

        $listData = Product::with(['media' => function($q){
                            $q->groupBy('product_id');
                        }, 'media.image',
                        'translation' => function($q) use($langId){
                        $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                        },
                        'variant' => function($q) use($langId){
                            $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                            $q->groupBy('product_id');
                        },
                    ])->select('id', 'sku', 'requires_shipping', 'sell_when_out_of_stock', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'Requires_last_mile', 'averageRating')
                    ->where('is_live', 1)->where('vendor_id', $vid)->paginate(8);

        if(!empty($listData)){
            foreach ($listData as $key => $value) {
                foreach ($value->variant as $k => $v) {
                    $value->variant{$k}->multiplier = $clientCurrency->doller_compare;
                }
            }
        }

        $navCategories = Session::get('navCategories');

        if(empty($navCategories)){
            $navCategories = $this->categoryNav($langId);
        }
        $vendorIds[] = $vid;

        $np = $this->productList($vendorIds, $langId, $curId, 'is_new');
        $newProducts = ($np->count() > 0) ? array_chunk($np->toArray(), ceil(count($np) / 2)) : $np;

        return view('forntend/vendor-products')->with(['vendor' => $vendor, 'listData' => $listData, 'navCategories' => $navCategories, 'newProducts' => $newProducts]);
    }

}