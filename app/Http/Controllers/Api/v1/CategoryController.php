<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\v1\BaseController;
use App\Model\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\{User, Product, Category, ProductVariantSet, ProductVariant, ProductAddon, ProductRelated, ProductUpSell, ProductCrossSell, ClientCurrency, Vendor, Brand, VendorCategory};
use Validation;
use DB;
use App\Http\Traits\ApiResponser;

class CategoryController extends BaseController
{
    private $field_status = 2;
    use ApiResponser;
    /**     * Get Company ShortCode     *     */
    public function categoryData(Request $request, $cid = 0){
        try{
            $paginate = $request->has('limit') ? $request->limit : 12;
            if($cid == 0){
                return response()->json(['error' => 'No record found.'], 404);
            }
            $userid = Auth::user()->id;
            $langId = Auth::user()->language;
            $category = Category::with(['tags','type'  => function($q){
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
                        ->where('id', $cid)->first();

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

            if(!$category){
                return response()->json(['error' => 'No record found.'], 200);
            }
            $response['category'] = $category;
            $response['filterData'] = $variantSets;
            $response['listData'] = $this->listData($langId, $cid, $category->type->redirect_to, $paginate, $userid);
            return $this->successResponse($response);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
        
    }

    public function listData($langId, $category_id, $tpye = '', $limit = 12, $userid){
        if($tpye == 'vendor' || $tpye == 'Vendor'){
            $blockedVendor = VendorCategory::where('category_id', $category_id)->where('status', 0)->pluck('vendor_id')->toArray();
            $vendorData = Vendor::select('id', 'name', 'banner', 'order_pre_time', 'order_min_amount', 'vendor_templete_id');
            $vendorData = $vendorData->whereHas('products.category', function($qry) use($category_id){
                        $qry->where('category_id', $category_id);
                    })->where('status', '!=', $this->field_status)->whereNotIn('id', $blockedVendor)->paginate($limit);
            foreach ($vendorData as $vendor) {
                unset($vendor->products);
                $vendor->is_show_category = ($vendor->vendor_templete_id == 1) ? 0 : 1;
            }
            return $vendorData;
        }elseif($tpye == 'product' || $tpye == 'Product'){
            $clientCurrency = ClientCurrency::where('currency_id', Auth::user()->currency)->first();
            $vendor_ids = Vendor::where('status', 1)->pluck('id')->toArray();
            $products = Product::has('vendor')->join('product_categories as pc', 'pc.product_id', 'products.id')
                    ->with(['category.categoryDetail','inwishlist' => function($qry) use($userid){
                        $qry->where('user_id', $userid);
                    },
                    'media.image', 'translation' => function($q) use($langId){
                        $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                    },
                    'variant' => function($q) use($langId){
                            $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                            $q->groupBy('product_id');
                    },
                    ])->select('products.id', 'products.sku', 'products.url_slug', 'products.weight_unit', 'products.weight', 'products.vendor_id', 'products.has_variant', 'products.has_inventory', 'products.sell_when_out_of_stock', 'products.requires_shipping', 'products.Requires_last_mile', 'products.averageRating')
                    ->where('pc.category_id', $category_id)->where('products.is_live', 1)->whereIn('vendor_id', $vendor_ids)->paginate($limit);
            if(!empty($products)){
                foreach ($products as $key => $product) {
                    $product->is_wishlist = $product->category->categoryDetail->show_wishlist;
                    foreach ($product->variant as $k => $v) {
                        $product->variant[$k]->multiplier = $clientCurrency->doller_compare;
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

    /**
     * Product filters on category Page
     * @return \Illuminate\Http\Response
     */
    public function categoryFilters(Request $request, $cid = 0){
        try {
            if($cid == 0 || $cid < 0){
                return response()->json(['error' => 'No record found.'], 404);
            }
            $langId = Auth::user()->language;
            $curId = Auth::user()->currency;
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
            $order_type = $request->has('order_type') ? $request->order_type : '';
            $products = Product::join('product_categories as pc', 'pc.product_id', 'products.id')
                        ->with(['category.categoryDetail','media.image',
                            'translation' => function($q) use($langId){
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
            if(!empty($order_type) && $request->order_type == 'rating'){
                $products = $products->orderBy('averageRating', 'desc');
            }
            $paginate = $request->has('limit') ? $request->limit : 12;
            
            $products = $products->paginate($paginate);

            if(!empty($products)){
                foreach ($products as $key => $product) {
                    $product->is_wishlist = $product->category->categoryDetail->show_wishlist;
                    foreach ($product->variant as $k => $v) {
                        $product->variant[$k]->multiplier = $clientCurrency->doller_compare;
                    }
                }
            }
            return $this->successResponse($products);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}