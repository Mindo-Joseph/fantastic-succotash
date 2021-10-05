<?php

namespace App\Http\Controllers\Api\v1;

use DB;
use Validation;
use Carbon\Carbon;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\v1\BaseController;
use App\Models\{Type, User, Product, Category, ProductVariantSet, ProductVariant, ProductAddon, ProductRelated, ProductUpSell, ProductCrossSell, ClientCurrency, ClientPreference, Vendor, Brand, VendorCategory};

class VendorController extends BaseController{
    use ApiResponser;
    private $field_status = 2;

    public function postVendorCategoryList(Request $request){
        try {
            $vendor_ids = [];
            $category_details = [];
            $vendor_id = $request->vendor_id;
            $type = Type::where('title' ,'Vendor')->first();
            $vendor = Vendor::select('name', 'latitude', 'longitude')->where('id', $vendor_id)->first();
            $vendor_products = Product::with('category.categoryDetail')->where('vendor_id', $vendor_id)->where('is_live', 1)->get(['id']);
            foreach ($vendor_products as $vendor_product) {
                if(!in_array($vendor_product->category->categoryDetail->id, $vendor_ids)){
                    if($vendor_product->category->categoryDetail->id != $type->id){
                        $vendor_ids[] = $vendor_product->category->categoryDetail->id;
                        $category_details[] = array(
                            'id' => $vendor_product->category->categoryDetail->id,
                            'slug' => $vendor_product->category->categoryDetail->slug,
                            'name' => $vendor_product->category->categoryDetail->translation_one ? $vendor_product->category->categoryDetail->translation_one->name :$vendor_product->category->categoryDetail->slug,
                            'icon' => $vendor_product->category->categoryDetail->icon,
                            'image' => $vendor_product->category->categoryDetail->image
                        );
                    }
                }
            }
            return $this->successResponse($category_details, '', 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function productsByVendor(Request $request, $vid = 0){
        try {
            if($vid == 0){
                return response()->json(['error' => 'No record found.'], 404);
            }
            $user = Auth::user();
            $userid = $user->id;
            $latitude = $user->latitude;
            $longitude = $user->longitude;
            $paginate = $request->has('limit') ? $request->limit : 12;
            $clientCurrency = ClientCurrency::where('currency_id', Auth::user()->currency)->first();
            $preferences = ClientPreference::select('distance_to_time_multiplier','distance_unit_for_time', 'is_hyperlocal', 'Default_location_name', 'Default_latitude', 'Default_longitude')->first();
            $langId = Auth::user()->language;
            $vendor = Vendor::select('id', 'name', 'desc', 'logo', 'banner', 'address', 'latitude', 'longitude', 'slug', 'show_slot',
                        'order_min_amount', 'vendor_templete_id', 'order_pre_time', 'auto_reject_time', 'dine_in', 'takeaway', 'delivery')
                        ->withAvg('product', 'averageRating')
                        ->where('id', $vid)->first();
            if(!$vendor){
                return response()->json(['error' => 'No record found.'], 200);
            }
            $vendor->is_vendor_closed = 0;
            if($vendor->show_slot == 0){
                if( ($vendor->slotDate->isEmpty()) && ($vendor->slot->isEmpty()) ){
                    $vendor->is_vendor_closed = 1;
                }else{
                    $vendor->is_vendor_closed = 0;
                    if($vendor->slotDate->isNotEmpty()){
                        $vendor->opening_time = Carbon::parse($vendor->slotDate->first()->start_time)->format('g:i A');
                        $vendor->closing_time = Carbon::parse($vendor->slotDate->first()->end_time)->format('g:i A');
                    }elseif($vendor->slot->isNotEmpty()){
                        $vendor->opening_time = Carbon::parse($vendor->slot->first()->start_time)->format('g:i A');
                        $vendor->closing_time = Carbon::parse($vendor->slot->first()->end_time)->format('g:i A');
                    }
                }
            }
            
            $vendor->is_show_category = ($vendor->vendor_templete_id == 2 || $vendor->vendor_templete_id == 4 ) ? 1 : 0;

            $vendorCategories = VendorCategory::with('category.translation_one')->where('vendor_id', $vendor->id)->where('status', 1)->get();
            $categoriesList = '';
            foreach($vendorCategories as $key => $category){
                if($category->category){
                    $categoriesList = $categoriesList . $category->category->translation_one->name??'';
                    if( $key !=  $vendorCategories->count()-1 ){
                        $categoriesList = $categoriesList . ', ';
                    }
                }
            }
            $vendor->categoriesList = $categoriesList;
            if (($preferences) && ($preferences->is_hyperlocal == 1) && ($latitude) && ($longitude)) {
                $vendor = $this->getVendorDistanceWithTime($latitude, $longitude, $vendor, $preferences);
            }
            
            $code = $request->header('code');
            $client = Client::where('code',$code)->first();
            $vendor->share_link = "https://".$client->sub_domain.env('SUBMAINDOMAIN')."/vendor/".$vendor->slug;
            $variantSets =  ProductVariantSet::with(['options' => function($zx) use($langId){
                                $zx->join('variant_option_translations as vt','vt.variant_option_id','variant_options.id');
                                $zx->select('variant_options.*', 'vt.title');
                                $zx->where('vt.language_id', $langId);
                            }])->join('variants as vr', 'product_variant_sets.variant_type_id', 'vr.id')
                            ->join('variant_translations as vt','vt.variant_id','vr.id')
                            ->select('product_variant_sets.product_id', 'product_variant_sets.product_variant_id', 'product_variant_sets.variant_type_id', 'vr.type', 'vt.title')
                            ->where('vt.language_id', $langId)
                            ->whereIn('product_id', function($qry) use($vid){ 
                            $qry->select('id')->from('products')
                                ->where('vendor_id', $vid);
                            })
                        ->groupBy('product_variant_sets.variant_type_id')->get();
                    // ->join('product_categories as pc', 'pc.product_id', 'products.id')
                    // ->whereNotIn('pc.category_id', function($qr) use($vid){ 
                    //             $qr->select('category_id')->from('vendor_categories')
                    //                 ->where('vendor_id', $vid)->where('status', 0);
                    // })
            $products = Product::with(['category.categoryDetail', 'category.categoryDetail.translation' => function($q) use($langId){
                            $q->select('category_translations.name', 'category_translations.meta_title', 'category_translations.meta_description', 'category_translations.meta_keywords', 'category_translations.category_id')
                            ->where('category_translations.language_id', $langId);
                        }, 'inwishlist' => function($qry) use($userid){
                            $qry->where('user_id', $userid);
                        },
                        'media.image',
                        'addOn' => function($q1) use($langId){
                            $q1->join('addon_sets as set', 'set.id', 'product_addons.addon_id');
                            $q1->join('addon_set_translations as ast', 'ast.addon_id', 'set.id');
                            $q1->select('product_addons.product_id', 'set.min_select', 'set.max_select', 'ast.title', 'product_addons.addon_id');
                            $q1->where('set.status', 1)->where('ast.language_id', $langId);
                        },
                        'addOn.setoptions' => function($q2) use($langId){
                            $q2->join('addon_option_translations as apt', 'apt.addon_opt_id', 'addon_options.id');
                            $q2->select('addon_options.id', 'addon_options.title', 'addon_options.price', 'apt.title', 'addon_options.addon_id');
                            $q2->where('apt.language_id', $langId);
                        },
                        'translation' => function($q) use($langId){
                            $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                        },
                        'variant' => function($q) use($langId){
                            $q->select('id','sku', 'product_id', 'title', 'quantity', 'price', 'barcode');
                            // $q->groupBy('product_id');
                        }, 'variant.checkIfInCartApp',
                    ])->select('products.id', 'products.sku', 'products.requires_shipping', 'products.sell_when_out_of_stock', 'products.url_slug', 'products.weight_unit', 'products.weight', 'products.vendor_id', 'products.has_variant', 'products.has_inventory', 'products.Requires_last_mile', 'products.averageRating', 'products.category_id')
                    ->where('products.vendor_id', $vid)
                    ->where('products.is_live', 1)->paginate($paginate);
            if(!empty($products)){
                foreach ($products as $key => $product) {
                    foreach ($product->addOn as $key => $value) {
                        foreach ($value->setoptions as $k => $v) {
                            if($v->price == 0){
                                $v->is_free = true;
                            }else{
                                $v->is_free = false;
                            }
                            $v->multiplier = $clientCurrency->doller_compare;
                        }
                    }

                    $p_id = $product->id;
                    $variantData = $product->with(['variantSet' => function ($z) use ($langId, $p_id) {
                        $z->join('variants as vr', 'product_variant_sets.variant_type_id', 'vr.id');
                        $z->join('variant_translations as vt', 'vt.variant_id', 'vr.id');
                        $z->select('product_variant_sets.product_id', 'product_variant_sets.product_variant_id', 'product_variant_sets.variant_type_id', 'vr.type', 'vt.title');
                        $z->where('vt.language_id', $langId);
                        $z->where('product_variant_sets.product_id', $p_id)->orderBy('product_variant_sets.variant_type_id', 'asc');
                    },'variantSet.options'=> function($zx) use($langId, $p_id){
                        $zx->join('variant_option_translations as vt','vt.variant_option_id','variant_options.id')
                        ->select('variant_options.*', 'vt.title', 'pvs.product_variant_id', 'pvs.variant_type_id')
                        ->where('pvs.product_id', $p_id)
                        ->where('vt.language_id', $langId);
                    }])->where('id', $p_id)->first();
                    $product->variantSet = $variantData->variantSet;
                    $product->is_wishlist = $product->category->categoryDetail->show_wishlist;
                    foreach ($product->variant as $k => $v) {
                        $product->variant[$k]->multiplier = $clientCurrency->doller_compare;
                    }
                }
            }
            $response['vendor'] = $vendor;
            $response['products'] = $products;
            $response['filterData'] = $variantSets;
            return response()->json(['data' => $response]);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage().''.$e->getLineNo(), $e->getCode());
        }
    }

    /**
     * Display product By Vendor Category
     *
     * @return \Illuminate\Http\Response
     */
    public function vendorCategoryProducts(Request $request, $slug1 = 0, $slug2 = 0){
        try{
            $paginate = $request->has('limit') ? $request->limit : 12;
            // $preferences = Session::get('preferences');
            $vendor = Vendor::select('id', 'name', 'slug', 'desc', 'logo', 'show_slot', 'banner', 'address', 'latitude', 'longitude', 'order_min_amount', 'order_pre_time', 'auto_reject_time', 'dine_in', 'takeaway', 'delivery', 'vendor_templete_id')->where('slug', $slug1)->where('status', 1)->firstOrFail();
            if(!empty($vendor)){
                $vendor->is_vendor_closed = 0;
                if($vendor->show_slot == 0){
                    if( ($vendor->slotDate->isEmpty()) && ($vendor->slot->isEmpty()) ){
                        $vendor->is_vendor_closed = 1;
                    }else{
                        $vendor->is_vendor_closed = 0;
                        if($vendor->slotDate->isNotEmpty()){
                            $vendor->opening_time = Carbon::parse($vendor->slotDate->first()->start_time)->format('g:i A');
                            $vendor->closing_time = Carbon::parse($vendor->slotDate->first()->end_time)->format('g:i A');
                        }elseif($vendor->slot->isNotEmpty()){
                            $vendor->opening_time = Carbon::parse($vendor->slot->first()->start_time)->format('g:i A');
                            $vendor->closing_time = Carbon::parse($vendor->slot->first()->end_time)->format('g:i A');
                        }
                    }
                }

                $code = $request->header('code');
                $client = Client::where('code',$code)->first();
                $vendor->share_link = "https://".$client->sub_domain.env('SUBMAINDOMAIN')."/vendor/".$vendor->slug;
            }
            $user = Auth::user();
            $userid = $user->id;
            $langId = $user->language;
            $clientCurrency = ClientCurrency::where('currency_id', Auth::user()->currency)->first();
            
            $products = Product::with(['media.image',
                        'category.categoryDetail', 'category.categoryDetail.translation' => function($q) use($langId){
                            $q->select('category_translations.name', 'category_translations.meta_title', 'category_translations.meta_description', 'category_translations.meta_keywords', 'category_translations.category_id')
                            ->where('category_translations.language_id', $langId);
                        }, 'inwishlist' => function($qry) use($userid){
                            $qry->where('user_id', $userid);
                        },
                        'addOn' => function($q1) use($langId){
                            $q1->join('addon_sets as set', 'set.id', 'product_addons.addon_id');
                            $q1->join('addon_set_translations as ast', 'ast.addon_id', 'set.id');
                            $q1->select('product_addons.product_id', 'set.min_select', 'set.max_select', 'ast.title', 'product_addons.addon_id');
                            $q1->where('set.status', 1)->where('ast.language_id', $langId);
                        },
                        'addOn.setoptions' => function($q2) use($langId){
                            $q2->join('addon_option_translations as apt', 'apt.addon_opt_id', 'addon_options.id');
                            $q2->select('addon_options.id', 'addon_options.title', 'addon_options.price', 'apt.title', 'addon_options.addon_id');
                            $q2->where('apt.language_id', $langId);
                        },
                        'translation' => function($q) use($langId){
                        $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                        },
                        'variant' => function($q) use($langId){
                            $q->select('id','sku', 'product_id', 'title', 'quantity', 'price', 'barcode');
                            // $q->groupBy('product_id');
                        }, 'variant.checkIfInCartApp',
                    ])->select('id', 'sku', 'description', 'requires_shipping', 'sell_when_out_of_stock', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'Requires_last_mile', 'averageRating', 'inquiry_only');
            if(!empty($slug2)){
                $category = Category::select('id')->where('slug', $slug2)->firstOrFail();
                $products = $products->where('category_id', $category->id);
            }
            $products = $products->where('is_live', 1)->where('vendor_id', $vendor->id)->paginate($paginate);
            if(!empty($products)){
                foreach ($products as $key => $product) {
                    foreach ($product->addOn as $key => $value) {
                        foreach ($value->setoptions as $k => $v) {
                            if($v->price == 0){
                                $v->is_free = true;
                            }else{
                                $v->is_free = false;
                            }
                            $v->multiplier = $clientCurrency->doller_compare;
                        }
                    }

                    $p_id = $product->id;
                    $variantData = $product->with(['variantSet' => function ($z) use ($langId, $p_id) {
                        $z->join('variants as vr', 'product_variant_sets.variant_type_id', 'vr.id');
                        $z->join('variant_translations as vt', 'vt.variant_id', 'vr.id');
                        $z->select('product_variant_sets.product_id', 'product_variant_sets.product_variant_id', 'product_variant_sets.variant_type_id', 'vr.type', 'vt.title');
                        $z->where('vt.language_id', $langId);
                        $z->where('product_variant_sets.product_id', $p_id)->orderBy('product_variant_sets.variant_type_id', 'asc');
                    },'variantSet.options'=> function($zx) use($langId, $p_id){
                        $zx->join('variant_option_translations as vt','vt.variant_option_id','variant_options.id')
                        ->select('variant_options.*', 'vt.title', 'pvs.product_variant_id', 'pvs.variant_type_id')
                        ->where('pvs.product_id', $p_id)
                        ->where('vt.language_id', $langId);
                    }])->where('id', $p_id)->first();
                    $product->variantSet = $variantData->variantSet;

                    $product->is_wishlist = $product->category->categoryDetail->show_wishlist;
                    foreach ($product->variant as $k => $v) {
                        $product->variant[$k]->multiplier = $clientCurrency ? $clientCurrency->doller_compare : 1;
                    }
                }
            }
            $vid = $vendor->id;
            $categoryId = $category->id;
            $variantSets =  ProductVariantSet::with(['options' => function($zx) use($langId){
                $zx->join('variant_option_translations as vt','vt.variant_option_id','variant_options.id');
                $zx->select('variant_options.*', 'vt.title');
                $zx->where('vt.language_id', $langId);
            }])->join('variants as vr', 'product_variant_sets.variant_type_id', 'vr.id')
            ->join('variant_translations as vt','vt.variant_id','vr.id')
            ->select('product_variant_sets.product_id', 'product_variant_sets.product_variant_id', 'product_variant_sets.variant_type_id', 'vr.type', 'vt.title')
            ->where('vt.language_id', $langId)
            ->whereIn('product_id', function($qry) use($vid, $categoryId){ 
                $qry->select('id')->from('products')->where('vendor_id', $vid)->where('category_id', $categoryId);
            })
            ->groupBy('product_variant_sets.variant_type_id')->get();
            $response['vendor'] = $vendor;
            $response['products'] = $products;
            $response['filterData'] = $variantSets;
            return response()->json(['data' => $response]);
        }
        catch (Exception $e) {
            return $this->errorResponse($e->getMessage().''.$e->getLineNo(), $e->getCode());
        }
    }

    /**
     * Product filters on category Page
     * @return \Illuminate\Http\Response
     */
    public function vendorFilters(Request $request, $vid = 0){
        if($vid == 0 || $vid < 0){
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
                $vResult = ProductVariantSet::select('product_variant_sets.product_variant_id', 'product_variant_sets.product_id')
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
        $products = Product::with(['category.categoryDetail', 'media.image', 'translation' => function($q) use($langId){
                        $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                        },'variant' => function($q) use($langId, $variantIds){
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
                        $qr->select('product_id')->from('product_variants')->where('price',  '>=', $startRange)->where('price',  '<=', $endRange);
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
        return response()->json([
            'data' => $products,
        ]);
    }

    /**
     * Product filters on category Page
     * @return \Illuminate\Http\Response
     */
    // public function vendorCategoryProductsFilters(Request $request, $vid = 0, $cid = 0){
    //     if( ($vid == 0 || $vid < 0) && ($cid == 0 || $cid < 0) ){
    //         return response()->json(['error' => 'No record found.'], 404);
    //     }
    //     $langId = Auth::user()->language;
    //     $curId = Auth::user()->currency;
    //     $setArray = $optionArray = array();
    //     $clientCurrency = ClientCurrency::where('currency_id', $curId)->first();
    //     if($request->has('variants') && !empty($request->variants)){
    //         $setArray = array_unique($request->variants);
    //     }
    //     $startRange = 0; $endRange = 20000;
    //     if($request->has('range') && !empty($request->range)){
    //         $range = explode(';', $request->range);
    //         $clientCurrency->doller_compare;
    //         $startRange = $range[0] * $clientCurrency->doller_compare;
    //         $endRange = $range[1] * $clientCurrency->doller_compare;
    //     }
    //     $multiArray = array();
    //     if($request->has('options') && !empty($request->options)){
    //         foreach ($request->options as $key => $value) {
    //             $multiArray[$request->variants[$key]][] = $value;
    //         }
    //     }
    //     $variantIds = $productIds = array();
    //     if(!empty($multiArray)){
    //         foreach ($multiArray as $key => $value) {
    //             $new_pIds = $new_vIds = array();
    //             $vResult = ProductVariantSet::select('product_variant_sets.product_variant_id', 'product_variant_sets.product_id')
    //                 ->where('product_variant_sets.variant_type_id', $key)
    //                 ->whereIn('product_variant_sets.variant_option_id', $value);
    //             if(!empty($variantIds)){
    //                 $vResult  = $vResult->whereIn('product_variant_sets.product_variant_id', $variantIds);
    //             }
    //             $vResult  = $vResult->groupBy('product_variant_sets.product_variant_id')->get();
    //             if($vResult){
    //                 foreach ($vResult as $key => $value) {
    //                     $new_vIds[] = $value->product_variant_id;
    //                     $new_pIds[] = $value->product_id;
    //                 }
    //             }
    //             $variantIds = $new_vIds;
    //             $productIds = $new_pIds;
    //         }
    //     }
    //     $order_type = $request->has('order_type') ? $request->order_type : '';
    //     $products = Product::with(['category.categoryDetail', 'media.image', 'translation' => function($q) use($langId){
    //                     $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
    //                     },'variant' => function($q) use($langId, $variantIds){
    //                         $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
    //                         if(!empty($variantIds)){
    //                             $q->whereIn('id', $variantIds);
    //                         }
    //                         if(!empty($order_type) && $order_type == 'low_to_high'){
    //                             $q->orderBy('price', 'asc');
    //                         }
    //                         if(!empty($order_type) && $order_type == 'high_to_low'){
    //                             $q->orderBy('price', 'desc');
    //                         }
    //                         $q->groupBy('product_id');
    //                     },
    //                 ])->select('id', 'sku', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'sell_when_out_of_stock', 'requires_shipping', 'Requires_last_mile', 'averageRating')
    //                 ->where('vendor_id', $vid)
    //                 ->where('category_id', $cid)
    //                 ->where('is_live', 1)
    //                 ->whereIn('id', function($qr) use($startRange, $endRange){
    //                     $qr->select('product_id')->from('product_variants')->where('price',  '>=', $startRange)->where('price',  '<=', $endRange);
    //                 });
    //     if(!empty($productIds)){
    //         $products = $products->whereIn('id', $productIds);
    //     }
    //     if($request->has('brands') && !empty($request->brands)){
    //         $products = $products->whereIn('brand_id', $request->brands);
    //     }
    //     if(!empty($order_type) && $request->order_type == 'rating'){
    //         $products = $products->orderBy('averageRating', 'desc');
    //     }
    //     $paginate = $request->has('limit') ? $request->limit : 12;
    //     $products = $products->paginate($paginate);
    //     if(!empty($products)){
    //         foreach ($products as $key => $product) {
    //             $product->is_wishlist = $product->category->categoryDetail->show_wishlist;
    //             foreach ($product->variant as $k => $v) {
    //                 $product->variant[$k]->multiplier = $clientCurrency->doller_compare;
    //             }
    //         }
    //     }
    //     return response()->json([
    //         'data' => $products,
    //     ]);
    // }
}