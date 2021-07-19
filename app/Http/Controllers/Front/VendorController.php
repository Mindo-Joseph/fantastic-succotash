<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Front\FrontController;
use App\Models\{Currency, Banner, Category, Brand, Product, ClientLanguage, Vendor, ClientCurrency, ProductVariantSet};
use Illuminate\Http\Request;
use Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Hash;

class VendorController extends FrontController
{
    private $field_status = 2;
    
    /**
     * Display product By Vendor
     *
     * @return \Illuminate\Http\Response
     */
    public function vendorProducts(Request $request, $domain = '', $slug = 0){
        $preferences = Session::get('preferences');
        $vendor = Vendor::select('id', 'name', 'slug', 'desc', 'logo', 'banner', 'address', 'latitude', 'longitude', 'order_min_amount', 'order_pre_time', 'auto_reject_time', 'dine_in', 'takeaway', 'delivery', 'vendor_templete_id', 'is_show_vendor_details')->where('slug', $slug)->where('status', 1)->firstOrFail();
        if( (isset($preferences->is_hyperlocal)) && ($preferences->is_hyperlocal == 1) ){
            if(Session::has('vendors')){
                $vendors = Session::get('vendors');
                if(!in_array($vendor->id, $vendors)){
                    abort(404);
                }
            }else{
                // abort(404);
            }
        }
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $clientCurrency = ClientCurrency::where('currency_id', $curId)->first();
        $brands = Product::with(['brand.translation'=> function($q) use($langId){
                    $q->select('title', 'brand_id')->where('brand_translations.language_id', $langId);
                }])->select('brand_id')->where('vendor_id', $vendor->id)
                ->where('brand_id', '>', 0)->groupBy('brand_id')->get();
        $variantSets = ProductVariantSet::with(['options' => function($zx) use($langId){
                            $zx->join('variant_option_translations as vt','vt.variant_option_id','variant_options.id');
                            $zx->select('variant_options.*', 'vt.title');
                            $zx->where('vt.language_id', $langId);
                        }
                    ])->join('variants as vr', 'product_variant_sets.variant_type_id', 'vr.id')
                    ->join('variant_translations as vt','vt.variant_id','vr.id')
                    ->select('product_variant_sets.product_id', 'product_variant_sets.product_variant_id', 'product_variant_sets.variant_type_id', 'vr.type', 'vt.title')
                    ->where('vt.language_id', $langId)
                    ->whereIn('product_id', function($qry) use($vendor){ 
                        $qry->select('id')->from('products')
                            ->where('vendor_id', $vendor->id);
                    })->groupBy('product_variant_sets.variant_type_id')->get();
        $navCategories = $this->categoryNav($langId);
        $vendorIds[] = $vendor->id;
        $np = $this->productList($vendorIds, $langId, $curId, 'is_new');
        $newProducts = ($np->count() > 0) ? array_chunk($np->toArray(), ceil(count($np) / 2)) : $np;
        $listData = $this->listData($langId, $vendor->id, $vendor->vendor_templete_id);
        $page = ($vendor->vendor_templete_id == 2) ? 'categories' : 'products';
        return view('frontend/vendor-'.$page)->with(['vendor' => $vendor, 'listData' => $listData, 'navCategories' => $navCategories, 'newProducts' => $newProducts, 'variantSets' => $variantSets, 'brands' => $brands]);
    }

    /**
     * Display product By Vendor Category
     *
     * @return \Illuminate\Http\Response
     */
    public function vendorCategoryProducts(Request $request, $domain = '', $slug1 = 0, $slug2 = 0){
        $preferences = Session::get('preferences');
        $vendor = Vendor::select('id', 'name', 'slug', 'desc', 'logo', 'banner', 'address', 'latitude', 'longitude', 'order_min_amount', 'order_pre_time', 'auto_reject_time', 'dine_in', 'takeaway', 'delivery', 'vendor_templete_id')->where('slug', $slug1)->where('status', 1)->firstOrFail();
        if( (isset($preferences->is_hyperlocal)) && ($preferences->is_hyperlocal == 1) ){
            if(Session::has('vendors')){
                $vendors = Session::get('vendors');
                if(!in_array($vendor->id, $vendors)){
                    abort(404);
                }
            }else{
                // abort(404);
            }
        }
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $clientCurrency = ClientCurrency::where('currency_id', $curId)->first();
        
        $brands = Product::with(['brand.translation'=> function($q) use($langId){
                    $q->select('title', 'brand_id')->where('brand_translations.language_id', $langId);
                }])->select('brand_id')->where('vendor_id', $vendor->id)
                ->where('brand_id', '>', 0)->groupBy('brand_id')->get();
                $variantSets = ProductVariantSet::with(['options' => function($zx) use($langId){
                    $zx->join('variant_option_translations as vt','vt.variant_option_id','variant_options.id');
                    $zx->select('variant_options.*', 'vt.title');
                    $zx->where('vt.language_id', $langId);
                }
                ])->join('variants as vr', 'product_variant_sets.variant_type_id', 'vr.id')
                ->join('variant_translations as vt','vt.variant_id','vr.id')
                ->select('product_variant_sets.product_id', 'product_variant_sets.product_variant_id', 'product_variant_sets.variant_type_id', 'vr.type', 'vt.title')
                ->where('vt.language_id', $langId)
                ->whereIn('product_id', function($qry) use($vendor){ 
                    $qry->select('id')->from('products')
                    ->where('vendor_id', $vendor->id);
                })->groupBy('product_variant_sets.variant_type_id')->get();
                $navCategories = $this->categoryNav($langId);
                $vendorIds[] = $vendor->id;
                $np = $this->productList($vendorIds, $langId, $curId, 'is_new');
                // pr($np->toArray());die;
                $newProducts = ($np->count() > 0) ? array_chunk($np->toArray(), ceil(count($np) / 2)) : $np;
                if(!empty($slug2)){
                    $vendor->vendor_templete_id = '';
                }
                $listData = $this->listData($langId, $vendor->id, $vendor->vendor_templete_id, $slug2);
                // dd($listData);
        $page = ($vendor->vendor_templete_id == 2) ? 'categories' : 'products';
        return view('frontend/vendor-'.$page)->with(['vendor' => $vendor, 'listData' => $listData, 'navCategories' => $navCategories, 'newProducts' => $newProducts, 'variantSets' => $variantSets, 'brands' => $brands]);
    }

    public function listData($langId, $vid, $type = '', $categorySlug = ''){

        $pagiNate = (Session::has('cus_paginate')) ? Session::get('cus_paginate') : 12;
        
        if($type == 2){
            // display categories
            $products = Product::select('category_id')->distinct()->where('vendor_id', $vid)->where('is_live', 1)->get();
            $vendor_categories = array();
            foreach($products as $key => $product ){
                $vendor_categories[] = $product->category_id;
            }
            $categoryData = Category::select('id', 'icon', 'slug', 'type_id', 'image')
                            ->whereIn('id', $vendor_categories);
                            //->where('categories.parent_id', 1);
            // $categoryData = $categoryData->join('vendor_categories as vct', 'vct.category_id', 'categories.id')->where('vct.vendor_id', $vid)->where('vct.status', 1);
            $categoryData = $categoryData->paginate($pagiNate);
            return $categoryData;
        }
        else{
            $clientCurrency = ClientCurrency::where('currency_id', Session::get('customerCurrency'))->first();
            $products = Product::with(['media.image',
                        'translation' => function($q) use($langId){
                        $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                        },
                        'variant' => function($q) use($langId){
                            $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                            $q->groupBy('product_id');
                        },
                    ])->select('id', 'sku', 'requires_shipping', 'sell_when_out_of_stock', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'Requires_last_mile', 'averageRating');
            if(!empty($categorySlug)){
                $category = Category::select('id')->where('slug', $categorySlug)->firstOrFail();
                $products = $products->where('category_id', $category->id);
            }
            // $sample = $products->where('is_live', 1)->where('vendor_id', $vid);
            $products = $products->where('is_live', 1)->where('vendor_id', $vid)->paginate($pagiNate);
            // $sample = $sample->join('product_variants', 'product_variants.product_id', '=', 'products.id')->orderBy('product_variants.price', 'DESC')->get();
            // pr($sample->toArray());die; 
            if(!empty($products)){
                foreach ($products as $key => $value) {
                    foreach ($value->variant as $k => $v) {
                        $value->variant[$k]->multiplier = $clientCurrency ? $clientCurrency->doller_compare : 1;
                    }
                }
            }
        // pr($products->toArray());die;
            $listData = $products;
            return $listData;
        }
    }

    /**
     * Product filters on category Page
     * @return \Illuminate\Http\Response
     */
    public function vendorFilters(Request $request, $domain = '', $vid = 0)
    {
        $setArray = $optionArray = array();
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $clientCurrency = ClientCurrency::where('currency_id', $curId)->first();
        if($request->has('variants') && !empty($request->variants)){
            $setArray = array_unique($request->variants);
        }
        $startRange = 0; $endRange = 20000;
        if($request->has('range') && !empty($request->range)){
            $range = explode(';', $request->range);
            $clientCurrency->doller_compare;
            $startRange = $range[0] * $clientCurrency->doller_compare;
            $endRange = $range[1] * $clientCurrency->doller_compare;
        }
        $multiArray = array();
        if($request->has('options') && !empty($request->options)){
            foreach ($request->options as $key => $value) {
                $multiArray[$request->variants[$key]][] = $value;
            }
        }
        $variantIds = $productIds = array();
        if(!empty($multiArray)){
            foreach ($multiArray as $key => $value) {
                $new_pIds = $new_vIds = array();
                $vResult = ProductVariantSet::join('product_categories as pc', 'product_variant_sets.product_id', 'pc.product_id')->select('product_variant_sets.product_variant_id', 'product_variant_sets.product_id')
                    ->where('product_variant_sets.variant_type_id', $key)
                    ->whereIn('product_variant_sets.variant_option_id', $value);

                if(!empty($variantIds)){
                    $vResult  = $vResult->whereIn('product_variant_sets.product_variant_id', $variantIds);
                }
                $vResult  = $vResult->groupBy('product_variant_sets.product_variant_id')->get();

                if($vResult){
                    foreach ($vResult as $key => $value) {
                        $new_vIds[] = $value->product_variant_id;
                        $new_pIds[] = $value->product_id;
                    }
                }
                $variantIds = $new_vIds;
                $productIds = $new_pIds;
            }
        }
        $order_type = $request->has('order_type') ? $request->order_type : '';
        $products = Product::with(['media.image', 'translation' => function($q) use($langId){
                        $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                        },
                        'variant' => function($q) use($langId, $variantIds){
                            $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                            if(!empty($variantIds)){
                                $q->whereIn('id', $variantIds);
                            }
                            if(!empty($order_type) && $order_type == 'low_to_high'){
                                $q->orderBy('price', 'asc');
                            }
                            if(!empty($order_type) && $order_type == 'high_to_low'){
                                $q->orderBy('price', 'desc');
                            }
                            $q->groupBy('product_id');
                        },
                    ])->select('id', 'sku', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'sell_when_out_of_stock', 'requires_shipping', 'Requires_last_mile', 'averageRating')
                    ->where('vendor_id', $vid)
                    ->where('is_live', 1)
                    ->whereIn('id', function($qr) use($startRange, $endRange){ 
                        $qr->select('product_id')->from('product_variants')
                            ->where('status', 1)
                            ->where('price',  '>=', $startRange)
                            ->where('price',  '<=', $endRange);
                        });
        if(!empty($productIds)){
            $products = $products->whereIn('id', $productIds);
        }
        if($request->has('brands') && !empty($request->brands)){
            $products = $products->whereIn('brand_id', $request->brands);
        }
        if(!empty($order_type) && $request->order_type == 'rating'){
            $products = $products->orderBy('averageRating', 'desc');
        }
        $pagiNate = (Session::has('cus_paginate')) ? Session::get('cus_paginate') : 12;
        $products = $products->paginate($pagiNate);
        if(!empty($products)){
            foreach ($products as $key => $value) {
                foreach ($value->variant as $k => $v) {
                    $value->variant[$k]->multiplier = $clientCurrency->doller_compare;
                }
            }
        }
        $listData = $products;
        $returnHTML = view('frontend.ajax.productList')->with(['listData' => $listData])->render();
        return response()->json(array('success' => true, 'html'=>$returnHTML));
    }

}