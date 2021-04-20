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
}