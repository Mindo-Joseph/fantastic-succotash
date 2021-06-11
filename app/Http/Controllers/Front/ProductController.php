<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Front\FrontController;
use App\Models\{AddonSet, Cart, CartAddon, CartProduct, User, Product, ClientCurrency, ProductVariant, ProductVariantSet};
use Illuminate\Http\Request;
use Session;
use Auth;


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
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $navCategories = $this->categoryNav($langId);
        $product = Product::select('id')->where('sku', $sku)->firstOrFail();
        $p_id = $product->id;
        $product = Product::with([
            'variant' => function ($sel) {
                $sel->groupBy('product_id');
            },
            'variant.set' => function ($sel) {
                $sel->select('product_variant_id', 'variant_option_id');
            },
            'variant.vimage.pimage.image', 'related', 'upSell', 'crossSell', 'vendor', 'media.image', 'translation' => function ($q) use ($langId) {
                $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description');
                $q->where('language_id', $langId);
            },
            'addOn' => function ($q1) use ($langId) {
                $q1->join('addon_sets as set', 'set.id', 'product_addons.addon_id');
                $q1->join('addon_set_translations as ast', 'ast.addon_id', 'set.id');
                $q1->select('product_addons.product_id', 'set.min_select', 'set.max_select', 'ast.title', 'product_addons.addon_id');
                $q1->where('ast.language_id', $langId);
            },
            'variantSet' => function ($z) use ($langId, $p_id) {
                $z->join('variants as vr', 'product_variant_sets.variant_type_id', 'vr.id');
                $z->join('variant_translations as vt', 'vt.variant_id', 'vr.id');
                $z->select('product_variant_sets.product_id', 'product_variant_sets.product_variant_id', 'product_variant_sets.variant_type_id', 'vr.type', 'vt.title');
                $z->where('vt.language_id', $langId);
                $z->where('product_variant_sets.product_id', $p_id);
            },
            'variantSet.option2' => function ($zx) use ($langId, $p_id) {
                $zx->where('vt.language_id', $langId)
                    ->where('product_variant_sets.product_id', $p_id);
            },
            'addOn.setoptions' => function ($q2) use ($langId) {
                $q2->join('addon_option_translations as apt', 'apt.addon_opt_id', 'addon_options.id');
                $q2->select('addon_options.id', 'addon_options.title', 'addon_options.price', 'apt.title', 'addon_options.addon_id');
                $q2->where('apt.language_id', $langId);
            },
        ])->select('id', 'sku', 'url_slug', 'weight', 'weight_unit', 'vendor_id', 'has_variant', 'has_inventory')
            ->where('sku', $sku)
            ->where('is_live', 1)
            ->firstOrFail();
        $clientCurrency = ClientCurrency::where('currency_id', Session::get('customerCurrency'))->first();
        foreach ($product->variant as $key => $value) {
            $product->variant[$key]->multiplier = $clientCurrency ? $clientCurrency->doller_compare : '1.00';
        }
        $vendorIds[] = $product->vendor_id;
        $np = $this->productList($vendorIds, $langId, $curId, 'is_new');
        $newProducts = ($np->count() > 0) ? array_chunk($np->toArray(), ceil(count($np) / 2)) : $np;
        foreach ($product->addOn as $key => $value) {
            foreach ($value->setoptions as $k => $v) {
                $v->multiplier = $clientCurrency->doller_compare;
            }
        }
        return view('frontend.product')->with(['product' => $product, 'navCategories' => $navCategories, 'newProducts' => $newProducts]);
    }

    /**
     * Display product variant data
     *
     * @return \Illuminate\Http\Response
     */
    public function getVariantData(Request $request, $domain = '', $sku)
    {
        $product = Product::select('id')->where('sku', $sku)->firstOrFail();
        $pv_ids = array();
        if ($request->has('options') && !empty($request->options)) {
            foreach ($request->options as $key => $value) {
                $newIds = array();

                $product_variant = ProductVariantSet::where('variant_type_id', $request->variants[$key])
                    ->where('variant_option_id', $request->options[$key]);

                if (!empty($pv_ids)) {
                    $product_variant = $product_variant->whereIn('product_variant_id', $pv_ids);
                }
                $product_variant = $product_variant->where('product_id', $product->id)->get();

                if ($product_variant) {
                    foreach ($product_variant as $key => $value) {
                        $newIds[] = $value->product_variant_id;
                    }
                }
                $pv_ids = $newIds;
            }
        }
        $clientCurrency = ClientCurrency::where('currency_id', Session::get('customerCurrency'))->first();
        $variantData = ProductVariant::select('id', 'sku', 'quantity', 'price',  'barcode', 'product_id')
            ->where('id', $pv_ids[0])->first();
        if ($variantData) {
            $variantData->productPrice = Session::get('currencySymbol') . $variantData->price * $clientCurrency->doller_compare;
            return response()->json(array('success' => true, 'result' => $variantData->toArray()));
        }

        return response()->json(array('error' => true, 'result' => NULL));
    }

  
}
