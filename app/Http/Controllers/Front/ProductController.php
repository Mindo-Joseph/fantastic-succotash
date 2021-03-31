<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Front\FrontController;
use App\Models\{Currency, Banner, Category, Brand, Product, ClientLanguage, Vendor, ClientCurrency};
use Illuminate\Http\Request;
use Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Hash;

class ProductController extends FrontController
{
    private $field_status = 2;
    
    /**
     * Display product By Id
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


        $product = Product::select('id')->where('sku', $sku)->firstOrFail();
        $p_id = $product->id;

        $product = Product::with(['variant' => function($sel){
                        $sel->groupBy('product_id');
                    },
                    'variant.set'=> function($sel){
                        $sel->select('product_variant_id', 'variant_option_id');
                    },
                    'variant.vimage.pimage.image', 'related', 'upSell', 'crossSell', 'vendor', 'media.image', 'translation' => function($q) use($langId){
                        $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword','meta_description');
                        $q->where('language_id', $langId);
                    },
                    'addOn' => function($q1) use($langId){
                        $q1->join('addon_sets as set', 'set.id', 'product_addons.addon_id');
                        $q1->join('addon_set_translations as ast', 'ast.addon_id', 'set.id');
                        $q1->select('product_addons.product_id', 'set.min_select', 'set.max_select', 'ast.title', 'product_addons.addon_id');
                        $q1->where('ast.language_id', $langId);
                    },
                    'variantSet' => function($z) use($langId, $p_id){
                        $z->join('variants as vr', 'product_variant_sets.variant_type_id', 'vr.id');
                        $z->join('variant_translations as vt','vt.variant_id','vr.id');
                        $z->select('product_variant_sets.product_id', 'product_variant_sets.product_variant_id', 'product_variant_sets.variant_type_id', 'vr.type', 'vt.title');
                        $z->where('vt.language_id', $langId);
                        $z->where('product_variant_sets.product_id', $p_id);
                    },
                    'variantSet.option2' => function($zx) use($langId, $p_id){
                        $zx->where('vt.language_id', $langId)
                            ->where('product_variant_sets.product_id', $p_id);
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

        //dd($product->toArray());

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
}