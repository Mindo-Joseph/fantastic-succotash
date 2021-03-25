<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Front\FrontController;
use App\Models\{Currency, Banner, Category, Brand, Product, ClientLanguage, Vendor};
use Illuminate\Http\Request;
use Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;

class ProductPageController extends FrontController
{
    private $field_status = 2;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($domain, $sku)
    {
        /*$product = Product::with(['vendor', 'primary', 'addOn', 'media.image', 'translation' => function($qq) use(Session::get('lang_id'))])
                ->select('id', 'sku', 'title', 'url_slug', 'sell_when_out_of_stock', 'Requires_last_mile', 'vendor_id', 'weight_unit', 'weight', 'has_inventory', 'has_variant', 'requires_shipping', 'averageRating')
                ->where('sku', $sku)
                ->firstOrFail();*/

        $categories = Category::join('category_translations as cts', 'categories.id', 'cts.category_id', 'type')
                        ->select('categories.id', 'categories.icon', 'categories.slug', 'categories.parent_id', 'cts.name')
                        ->where('categories.id', '>', '1')
                        ->where('categories.status', '!=', $this->field_status)
                        ->where('cts.language_id', Session::get('customerLanguage'))
                        ->orderBy('categories.parent_id', 'asc')
                        ->orderBy('categories.position', 'asc')->get();
        if($categories){
            $categories = $this->buildTree($categories->toArray());
        }

        $langId = Session::get('customerLanguage');

        $product = Product::with(['variant',  'variant.vimage.pimage.image', 'related', 'upSell', 'crossSell', 'vendor', 'media.image', 'addOn' => function($q1) use($langId){
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
                    ])->select('id', 'sku', 'url_slug', 'weight', 'weight_unit', 'vendor_id', 'has_variant', 'has_inventory')
                    ->where('sku', $sku)
                    ->firstOrFail();

        $clientCurrency = ClientCurrency::where('is_primary', '1')->first();
        foreach ($products->variant as $key => $value) {
            $products->variant[$key]->multiplier = $clientCurrency->doller_compare;
        }

        foreach ($products->addOn as $key => $value) {
            foreach ($value->setoptions as $k => $v) {
                $v->multiplier = $clientCurrency->doller_compare;
            }
        }
        

        /*$upSellProducts = $this->subProducts($sku, Session::get('lang_id'), 'USD', 'ProductUpSell');
        $croSellProducts = $this->subProducts($sku, Session::get('lang_id'), 'USD', 'ProductCrossSell');
        $relatedProducts = $this->subProducts($sku, Session::get('lang_id'), 'USD', 'ProductRelated');*/

        /*return view('forntend/product')->with(['product' => $product, 'banners' => $banners, 'categories' => $categories, 'brands' =>$brands, 'vendors' => $vendorData, 'featuredProducts' => $featuredPro, 'newProducts' => $newProducts, 'onSaleProducts' => $onSaleProds]);*/

        return view('forntend/product')->with(['product' => $product, 'categories' => $categories]);
    }

    public function productList($venderIds, $langID, $currency = 'USD', $where = '')
    {
        $products = Product::with('pimage', 'baseprice', 'primary')
                        //->leftjoin('product_translations as trans', 'trans.product_id', 'products.id')
                        ->select('id', 'sku');
                       // ->where('trans.language_id', $langID);
        if($where !== ''){
            $products = $products->where('products.'.$where, 1);
        }
        if(is_array($venderIds) && count($venderIds) > 0){
            $products = $products->whereIn('vendor_id', $venderIds);
        }
        $products = $products->get();

        $folderPath = env('IMG_URL1').'300/300'.env('IMG_URL2');

        if(!empty($products)){

            foreach ($products as $key => $value) {
                if(!empty($value->primary)){
                    $products[$key]->product_name = $value->primary->title;
                }else{
                    $products[$key]->product_name = $value->sku;
                }

                if(!empty($value->pimage) && count($value->pimage) > 0){
                    $imgs = array();
                    foreach ($value->pimage as $k => $v) {
                        $products[$key]->image = $folderPath.'/'.\Storage::disk('s3')->url($v->path);
                    }
                }else{
                    $products[$key]->image = $folderPath.'/'.\Storage::disk('s3')->url('default/default_image.png');
                }

                unset($products[$key]->pimage);

                $prodPrice = '0.00';

                if(!empty($value->baseprice) && count($value->baseprice) > 0){

                    //echo '<pre>';print_r($value->baseprice->toArray());die;
                    $prodPrice = $value->baseprice[0]->price;

                    if(!empty($value->baseprice[0]->price) && $value->baseprice[0]->price > 0 && $currency != 'USD'){

                        //$prodPrice = $this->changeCurrency($currency, $value->baseprice[0]->price);
                        //$products[$key]->price = $amount;
                        $prodPrice = $value->baseprice[0]->price;

                    }
                }
                $products[$key]->price = $prodPrice;
                unset($products[$key]->baseprice);

            }
        }
        return $products;
    }

    public function categoryData($cid)
    {
        $categories = Category::with('translation')->->where('id', $cid)->first();

        print_r($categories->toArray());die;

        $categories = Category::join('category_translations as cts', 'categories.id', 'cts.category_id', 'type')
                        ->select('categories.id', 'categories.icon', 'categories.slug', 'categories.parent_id', 'cts.name')
                        ->where('categories.id', '>', '1')
                        ->where('categories.status', '!=', $this->field_status)
                        ->where('cts.language_id', Session::get('customerLanguage'))
                        ->orderBy('categories.parent_id', 'asc')
                        ->orderBy('categories.position', 'asc')->get();
    }

}
