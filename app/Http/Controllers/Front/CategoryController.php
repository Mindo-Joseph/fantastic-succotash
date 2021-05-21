<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Front\FrontController;
use App\Models\{Currency, Banner, Category, Brand, Product, ClientLanguage, Vendor, ClientCurrency, ProductVariantSet};
use Illuminate\Http\Request;
use Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Hash;

class CategoryController extends FrontController
{
    private $field_status = 2;
    
    /** 
     * Display product and vendor list By Category id
     *
     * @return \Illuminate\Http\Response
     */
    public function categoryProduct(Request $request, $domain = '', $cid = 0)
    {
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $category = Category::with(['tags', 'brands.translation' => function($q) use($langId){
                        $q->where('brand_translations.language_id', $langId);
                    },
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

        $variantSets = ProductVariantSet::with(['options' => function($zx) use($langId){
                            $zx->join('variant_option_translations as vt','vt.variant_option_id','variant_options.id');
                            $zx->select('variant_options.*', 'vt.title');
                            $zx->where('vt.language_id', $langId);
                        }
                    ])->join('variants as vr', 'product_variant_sets.variant_type_id', 'vr.id')
                    ->join('variant_translations as vt','vt.variant_id','vr.id')
                    ->select('product_variant_sets.product_id', 'product_variant_sets.product_variant_id', 'product_variant_sets.variant_type_id', 'vr.type', 'vt.title')
                    ->where('vt.language_id', $langId)
                    ->whereIn('product_variant_sets.product_id', function($qry) use($cid){ 
                        $qry->select('product_id')->from('product_categories')
                            ->where('category_id', $cid);
                        })
                    ->groupBy('product_variant_sets.variant_type_id')->get();

        //dd($variantSets->toArray());

        $listData = $this->listData($langId, $cid, $category->type->redirect_to);

        //dd($listData->toArray());
        $category->type->redirect_to;
        $page = ($category->type->redirect_to == 'vendor' || $category->type->redirect_to == 'Vendor') ? 'vendor' : 'product';

        $np = $this->productList($vendorIds, $langId, $curId, 'is_new');
        $newProducts = ($np->count() > 0) ? array_chunk($np->toArray(), ceil(count($np) / 2)) : $np;

        //dd($category->toArray());
        return view('forntend/cate-'.$page.'s')->with(['listData' => $listData, 'category' => $category, 'navCategories' => $navCategories, 'newProducts' => $newProducts, 'variantSets' => $variantSets]);
    }

    public function listData($langId, $cid, $tpye = ''){

        $pagiNate = (Session::has('cus_paginate')) ? Session::get('cus_paginate') : 12;
        
        if($tpye == 'vendor' || $tpye == 'Vendor'){

            $vendorData = Vendor::select('id', 'name', 'logo', 'banner', 'order_pre_time', 'order_min_amount');

            /*if($preferences->is_hyperlocal == 1){
                $vendorData = $vendorData->whereIn('id', function($query) use($lats, $longs){
                        $query->select('vendor_id')
                        ->from(with(new ServiceArea)->getTable())
                        ->whereRaw("ST_Contains(polygon, GeomFromText('POINT(".$lats." ".$longs.")'))");
                });
            }*/
            $vendorData = $vendorData->where('status', '!=', $this->field_status)->paginate($pagiNate);

            return $vendorData;

        //}elseif($tpye == 'product' || $tpye == 'Product'){
            }else{
            $clientCurrency = ClientCurrency::where('currency_id', Session::get('customerCurrency'))->first();

            $products = Product::join('product_categories as pc', 'pc.product_id', 'products.id')
                    ->with(['media.image',
                        'translation' => function($q) use($langId){
                        $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                        },
                        'variant' => function($q) use($langId){
                            $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                            $q->groupBy('product_id');
                        },
                    ])->select('products.id', 'products.sku', 'products.url_slug', 'products.weight_unit', 'products.weight', 'products.vendor_id', 'products.has_variant', 'products.has_inventory', 'products.sell_when_out_of_stock', 'products.requires_shipping', 'products.Requires_last_mile', 'products.averageRating')
                    ->where('pc.category_id', $cid)->where('products.is_live', 1)->paginate($pagiNate);

            if(!empty($products)){
                foreach ($products as $key => $value) {
                    foreach ($value->variant as $k => $v) {
                        $value->variant[$k]->multiplier = $clientCurrency->doller_compare;
                    }
                }
            }
            $listData = $products;
            return $listData;
        }
    }

    /**
     * Product filters on category Page
     * @return \Illuminate\Http\Response
     */
    public function categoryFilters(Request $request, $domain = '', $cid = 0)
    {
        /*$products = Product::join('product_categories as pc', 'pc.product_id', 'products.id')
                    ->with('variant1.vset')
                    ->select('id as pro_id', 'sku')
                    ->where('pc.category_id', $cid)->get();
        dd($products->toArray());*/

        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $setArray = $optionArray = array();
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

        //$combinations = $this->array_combinations($multiArray);
        //$variantSetData = ProductVariantSet::select('product_id', 'product_variant_id');
        /*if(!empty($multiArray)){
            foreach ($multiArray as $key => $value) {
                $variantSetData = $variantSetData->whereIn('product_variant_id', function($qry) use($key, $value){ 
                        $qry->select('product_variant_id')->from('product_variant_sets')
                            ->whereIn('variant_type_id', $key)
                            ->whereIn('variant_option_id', $value);
                        })
            }
        }*/
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

        /*if($request->has('options') && !empty($request->options)){
            $optionArray = $request->options;
        }
        $variantSetData = ProductVariantSet::join('product_categories as pc', 'product_variant_sets.product_id', 'pc.product_id' )->select('product_variant_sets.*');
        if(!empty($setArray)){
            $variantSetData = $variantSetData->whereIn('product_variant_sets.variant_type_id', $setArray);
        }
        if(!empty($optionArray)){
            $variantSetData = $variantSetData->whereIn('product_variant_sets.variant_option_id', $optionArray);
        }
        echo $variantSetData = $variantSetData->groupBy('product_variant_sets.product_id')->toSql();die;

        dd($variantSetData->toArray());
        
        foreach ($variantSetData as $key => $value) {
            $variantIds[] = $value->product_variant_id;
            $productIds[] = $value->product_id;
        }*/
       // print_r($variantIds);die;

        $products = Product::join('product_categories as pc', 'pc.product_id', 'products.id')
                    ->with(['media.image',
                        'translation' => function($q) use($langId){
                        $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                        },
                        'variant' => function($q) use($langId, $variantIds){
                            $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                            if(!empty($variantIds)){
                                $q->whereIn('id', $variantIds);
                            }
                            $q->groupBy('product_id');
                        },
                    ])->select('products.id', 'products.sku', 'products.url_slug', 'products.weight_unit', 'products.weight', 'products.vendor_id', 'products.has_variant', 'products.has_inventory', 'products.sell_when_out_of_stock', 'products.requires_shipping', 'products.Requires_last_mile', 'products.averageRating')
                    ->where('pc.category_id', $cid)
                    ->where('products.is_live', 1)
                    ->whereIn('id', function($qr) use($startRange, $endRange){ 
                        $qr->select('product_id')->from('product_variants')
                            ->where('price',  '>=', $startRange)
                            ->where('price',  '<=', $endRange);
                        });

        if(!empty($productIds)){
            $products = $products->whereIn('id', $productIds);
        }
        
        if($request->has('brands') && !empty($request->brands)){
            $products = $products->whereIn('products.brand_id', $request->brands);
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
        //dd($listData->toArray());

        $returnHTML = view('forntend.ajax.productList')->with(['listData' => $listData])->render();
        return response()->json(array('success' => true, 'html'=>$returnHTML));
    }

    private function array_combinations($arrays)
    {
        $result = array();
        $arrays = array_values($arrays);
        $sizeIn = sizeof($arrays);
        $size = $sizeIn > 0 ? 1 : 0;
        foreach ($arrays as $array)
            $size = $size * sizeof($array);
        for ($i = 0; $i < $size; $i ++)
        {
            $result[$i] = array();
            for ($j = 0; $j < $sizeIn; $j ++)
                array_push($result[$i], current($arrays[$j]));
            for ($j = ($sizeIn -1); $j >= 0; $j --)
            {
                if (next($arrays[$j]))
                    break;
                elseif (isset ($arrays[$j]))
                    reset($arrays[$j]);
            }
        }
        return $result;
    }

}