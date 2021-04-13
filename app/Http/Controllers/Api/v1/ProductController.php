<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\v1\BaseController;
use App\Model\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\{User, Product, Category, ProductVariantSet, ProductVariant, ProductAddon, ProductRelated, ProductUpSell, ProductCrossSell, ClientCurrency, Vendor, Brand};
use Validation;
use DB;

class ProductController extends BaseController
{
    private $field_status = 2;
    /**
     * Get Company ShortCode
     *
     */
    public function productById(Request $request, $pid)
    {
        //$pid = $request->product_id;
        $langId = Auth::user()->language;
        /*if(!$request->has('product_sku')){
            return response()->json(['error' => 'No record found.'], 404);
        }*/
        $products = Product::with(['variant' => function($v){
                        $v->select('id', 'sku', 'product_id', 'title', 'quantity','price','barcode','tax_category_id');
                    },
                    'variant.vimage.pimage.image', 'related', 'upSell', 'crossSell', 'vendor', 'media.image', 'addOn' => function($q1) use($langId){
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
                    ])->select('id', 'sku', 'url_slug', 'weight', 'weight_unit', 'vendor_id')
                   // ->where('sku', $request->product_sku)
                    ->where('id', $pid)
                    ->first();

        if(!$products){
            return response()->json(['error' => 'No record found.'], 404);
        }

        $clientCurrency = ClientCurrency::where('currency_id', Auth::user()->currency)->first();
        foreach ($products->variant as $key => $value) {
            $products->variant[$key]->multiplier = $clientCurrency->doller_compare;
        }

        foreach ($products->addOn as $key => $value) {
            foreach ($value->setoptions as $k => $v) {
                $v->multiplier = $clientCurrency->doller_compare;
            }
        }

        $response['products'] = $products;

        return response()->json([
            'data' => $response,
        ]);
    }

    public function productList(Request $request)
    {
        $did = $request->id;
        $langId = Auth::user()->language;
        $for = $request->has('for') ? $request->for : 'category';

        $products = Product::with('pimage', 'baseprice')
                        ->join('product_translations as trans', 'trans.product_id', 'products.id');
        if($for == 'category'){
            $products = $products->join('product_categories as pc', 'pc.product_id', 'products.id')
                        ->where('pc.category_id', $did);
        }
        if($for == 'vendor'){
            $products = $products->where('products.vendor_id', $did);
        }
        $products = $products->select('trans.title', 'trans.body_html', 'products.sku', 'products.id')->get();


        if(!$products || count($products) < 1){
            $reps['message'] = 'No record found.';
            return response()->json(['data' => $reps]);
        }

        foreach ($products as $key => $value) {

            if(!empty($value->pimage) && count($value->pimage) > 0){
                $imgs = array();
                foreach ($value->pimage as $k => $v) {
                    $products[$key]->image = \Storage::disk('s3')->url($v->path);
                }
            }else{
                $products[$key]->image = \Storage::disk('s3')->url('default/default_image.png');
            }

            unset($products[$key]->pimage);

            if(!empty($value->baseprice) && count($value->baseprice) > 0){

                    //echo '<pre>';print_r($value->baseprice->toArray());die;
                $prodPrice = $value->baseprice[0]->price;

                if(!empty($value->baseprice[0]->price) && $value->baseprice[0]->price > 0 && Auth::user()->currency != 'USD'){
                    $value->variants{$row}->multiplier = $clientCurrency->doller_compare;
                }
            }
            $products[$key]->price = $prodPrice;
            unset($products[$key]->baseprice);

        }

        return response()->json([
            'data' => $products,
        ]);
    }

    public function productsByVendor(Request $request, $vid = 0)
    {
        if($vid == 0){
            return response()->json(['error' => 'No record found.'], 404);
        }
        $paginate = $request->has('limit') ? $request->limit : 12;
        $clientCurrency = ClientCurrency::where('currency_id', Auth::user()->currency)->first();
        $langId = Auth::user()->language;
        $vendor = Vendor::select('id', 'name', 'desc', 'logo', 'banner', 'address', 'latitude', 'longitude', 
                    'order_min_amount', 'order_pre_time', 'auto_reject_time', 'dine_in', 'takeaway', 'delivery')
                    ->where('id', $vid)->first();
        if(!$vendor){
            return response()->json(['error' => 'No record found.'], 200);
        }

        $products = Product::with(['media.image', 'translation' => function($q) use($langId){
                    $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                    },
                    'variant' => function($q) use($langId){
                        $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                        $q->groupBy('product_id');
                    },
                ])
                ->select('id', 'sku', 'requires_shipping', 'sell_when_out_of_stock', 'url_slug', 'weight_unit', 'weight', 'vendor_id', 'has_variant', 'has_inventory', 'Requires_last_mile', 'averageRating')
                ->where('vendor_id', $vid)
                ->where('is_live', 1)->paginate($paginate);
        
        if(!empty($products)){
            foreach ($products as $key => $value) {
                foreach ($value->variant as $k => $v) {
                    $value->variant{$k}->multiplier = $clientCurrency->doller_compare;
                }
            }
        }

        $response['vendor'] = $vendor;
        $response['products'] = $products;

        return response()->json([
            'data' => $response,
        ]);
    }

    public function categoryData(Request $request, $cid = 0)
    {
        $paginate = $request->has('limit') ? $request->limit : 12;
        if($cid == 0){
            return response()->json(['error' => 'No record found.'], 404);
        }
        $langId = Auth::user()->language;
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
                    ->where('id', $cid)->first();

        if(!$category){
            return response()->json(['error' => 'No record found.'], 200);
        }
        $response['category'] = $category;
        $response['listData'] = $this->listData($langId, $cid, $category->type->redirect_to, $paginate);

        return response()->json([
            'data' => $response,
        ]);
    }

    public function listData($langId, $cid, $tpye = '', $limit = 12){
        
        if($tpye == 'vendor' || $tpye == 'Vendor'){

            /*$vendorIds = array();

            $vendorWithCategory = Product::join('product_categories as pc', 'pc.product_id', 'products.id')
                        ->select('products.id', 'products.vendor_id')
                        ->where('pc.category_id', $cid)->groupBy('products.vendor_id')->get();
            if($vendorWithCategory){
                foreach ($vendorWithCategory as $key => $value) {
                    $vendorIds[] = $value->vendor_id;
                }
            }*/

            $vendorData = Vendor::select('id', 'name', 'banner', 'order_pre_time', 'order_min_amount');

            /*if($preferences->is_hyperlocal == 1){
                $vendorData = $vendorData->whereIn('id', function($query) use($lats, $longs){
                        $query->select('vendor_id')
                        ->from(with(new ServiceArea)->getTable())
                        ->whereRaw("ST_Contains(polygon, GeomFromText('POINT(".$lats." ".$longs.")'))");
                });
            }*/
            $vendorData = $vendorData->where('status', '!=', $this->field_status)->paginate($limit);
                            //->whereIn('id', $vendorIds)

            return $vendorData;

        }elseif($tpye == 'product' || $tpye == 'Product'){

            $clientCurrency = ClientCurrency::where('currency_id', Auth::user()->currency)->first();

            $products = Product::join('product_categories as pc', 'pc.product_id', 'products.id')
                    ->with(['media.image', 'translation' => function($q) use($langId){
                        $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                        },
                        'variant' => function($q) use($langId){
                            $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                            $q->groupBy('product_id');
                        },
                    ])->select('products.id', 'products.sku', 'products.url_slug', 'products.weight_unit', 'products.weight', 'products.vendor_id', 'products.has_variant', 'products.has_inventory', 'products.sell_when_out_of_stock', 'products.requires_shipping', 'products.Requires_last_mile', 'products.averageRating')
                    ->where('pc.category_id', $cid)->paginate($limit);

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

    public function productsByBrand(Request $request, $brandId = 0)
    {
        if($brandId == 0 || $brandId < 0){
            return response()->json(['error' => 'No record found.'], 404);
        }
        $langId = Auth::user()->language;
        $paginate = $request->has('limit') ? $request->limit : 12;
        $brand = Brand::with(['translation' => function($q) use($langId){
                        $q->select('title', 'brand_id');
                        $q->where('language_id', $langId);
                    }])->select('id', 'image')
                    ->where('status', '!=', 2)
                    ->where('id', $brandId)->first();

        if(!$brand){
            return response()->json(['error' => 'No record found.'], 200);
        }

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
                ->where('is_live', 1)->paginate($paginate);
        
        $clientCurrency = ClientCurrency::where('currency_id', Auth::user()->currency)->first();
        if(!empty($products)){
            foreach ($products as $key => $value) {
                foreach ($value->variant as $k => $v) {
                    $value->variant{$k}->multiplier = $clientCurrency->doller_compare;
                }
            }
        }

        $response['brand'] = $brand;
        $response['products'] = $products;

        return response()->json([
            'data' => $response,
        ]);
    }

    /*public function getCode(Request $request)
    {
        $user = Client::select('id', 'company_name', 'database_name')
                    ->where('is_deleted', 0)->where('is_blocked', 0)->get();

        if($user){
            return response()->json([
                'data' => $user,
            ]);
        }else{
            
        }
    }*/

}
