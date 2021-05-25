<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Front\FrontController;
use App\Models\{Currency, Banner, Category, Brand, Product, ClientLanguage, Vendor, ClientCurrency, ProductVariantSet};
use Illuminate\Http\Request;
use Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Hash;

class BrandController extends FrontController
{
    private $field_status = 2;
    
    /**
     * Display product By Vendor
     *
     * @return \Illuminate\Http\Response
     */
    public function brandProducts(Request $request, $domain = '', $brandId = 0)
    {
        $vid = '';
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $clientCurrency = ClientCurrency::where('currency_id', $curId)->first();
        $navCategories = $this->categoryNav($langId);

        $brand = Brand::with(['translation' => function($q) use($langId){
                    $q->where('language_id', $langId);
                    }])->select('id', 'image')
                    ->where('status', '!=', 2)
                    ->where('id', $brandId)->firstOrFail();

        $products = Product::with(['media.image', 'translation' => function($q) use($langId){
                    $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                    },
                    'variant' => function($q) use($langId){
                        $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                        $q->groupBy('product_id');
                    },
                ])
                ->select('id', 'sku', 'requires_shipping', 'sell_when_out_of_stock', 'url_slug', 'weight_unit', 'weight', 'brand_id', 'has_variant', 'has_inventory', 'Requires_last_mile', 'averageRating')
                ->where('brand_id', $brandId)
                ->where('is_live', 1)->paginate(8);
        
        $clientCurrency = ClientCurrency::where('currency_id', $curId)->first();
        if(!empty($products)){
            foreach ($products as $key => $value) {
                foreach ($value->variant as $k => $v) {
                    $value->variant[$k]->multiplier = $clientCurrency->doller_compare;
                }
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
                    /*->whereIn('product_id', function($qry) use($vid){ 
                        $qry->select('id')->from('products')
                            ->where('vendor_id', $vid);
                        })*/
                    ->groupBy('product_variant_sets.variant_type_id')->get();

        $navCategories = Session::get('navCategories');

        if(empty($navCategories)){
            $navCategories = $this->categoryNav($langId);
        }
        $vendorIds[] = $vid;
        //dd($brand->toArray());

        $np = $this->productList($vendorIds, $langId, $curId, 'is_new');
        $newProducts = ($np->count() > 0) ? array_chunk($np->toArray(), ceil(count($np) / 2)) : $np;

        return view('forntend/brand-products')->with(['brand' => $brand, 'products' => $products, 'newProducts' => $newProducts, 'navCategories' => $navCategories, 'variantSets' => $variantSets]);
    }

    /**
     * Product filters on category Page
     * @return \Illuminate\Http\Response
     */
    public function brandFilters(Request $request, $domain = '', $brandId = 0)
    {
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

        $products = Product::with(['media.image', 'translation' => function($q) use($langId){
                        $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                        },
                        'variant' => function($q) use($langId, $variantIds){
                            $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                            if(!empty($variantIds)){
                                $q->whereIn('id', $variantIds);
                            }
                            $q->groupBy('product_id');
                        },
                    ])->select('id', 'sku', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'sell_when_out_of_stock', 'requires_shipping', 'Requires_last_mile', 'averageRating')
                    ->where('brand_id', $brandId)
                    ->where('is_live', 1)
                    ->whereIn('id', function($qr) use($startRange, $endRange){ 
                        $qr->select('product_id')->from('product_variants')
                            ->where('price',  '>=', $startRange)
                            ->where('price',  '<=', $endRange);
                        });

        if(!empty($productIds)){
            $products = $products->whereIn('id', $productIds);
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

        $returnHTML = view('forntend.ajax.productList')->with(['listData' => $listData])->render();
        return response()->json(array('success' => true, 'html'=>$returnHTML));
    }

}