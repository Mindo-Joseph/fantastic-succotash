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
        $userid = Auth::user()->id;

        $products = Product::with(['inwishlist' => function($qry) use($userid){
                        $qry->where('user_id', $userid);
                    },
                    'variant' => function($v){
                        $v->select('id', 'sku', 'product_id', 'title', 'quantity','price','barcode','tax_category_id');
                    },
                    'variant.vimage.pimage.image', 'vendor', 'media.image', 'related', 'upSell', 'crossSell', 
                    'addOn' => function($q1) use($langId){
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
        $response['relatedProducts'] = $this->metaProduct($langId, $clientCurrency->doller_compare, 'relate', $products->related);
        $response['upSellProducts'] = $this->metaProduct($langId, $clientCurrency->doller_compare, 'upSell', $products->upSell);
        $response['crossProducts'] = $this->metaProduct($langId, $clientCurrency->doller_compare, 'cross', $products->crossSell);

        unset($products->related);
        unset($products->upSell);
        unset($products->crossSell);
        $response['products'] = $products;

        return response()->json([
            'data' => $response,
        ]);
    }

    public function metaProduct($langId, $multiplier, $for = 'relate', $productArray = [])
    {
        if(empty($productArray)){
            return $productArray;
        }

        $productIds = array();
        foreach ($productArray as $key => $value) {
            if($for == 'relate'){
                $productIds[] = $value->related_product_id;
            }
            if($for == 'upSell'){
                $productIds[] = $value->upsell_product_id;
            }
            if($for == 'cross'){
                $productIds[] = $value->cross_product_id;
            }
        }

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
                    ])->select('id', 'sku', 'averageRating')
                    ->whereIn('id', $productIds);

        $products = $products->get();

        if(!empty($products)){
            foreach ($products as $key => $value) {
                foreach ($value->variant as $k => $v) {
                    $value->variant{$k}->multiplier = $multiplier;
                }
            }
        }
        return $products;
    }
}