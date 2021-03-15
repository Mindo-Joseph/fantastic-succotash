<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\v1\BaseController;
use App\Model\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\{User, Product, Category, ProductVariantSet, ProductVariant, ProductAddon, ProductRelated, ProductUpSell, ProductCrossSell};
use Validation;
use DB;

class ProductController extends BaseController
{
    /**
     * Get Company ShortCode
     *
     */
    public function productById(Request $request)
    {
        $pid = $request->product_id;
        $langId = Auth::user()->language;
        if(!$request->has('product_id')){
            return response()->json(['error' => 'No record found.'], 404);
        }
        $products = Product::with(['vendor', 'pimage', 'variants.set', 'addOn', 'translation' => function($q) use($langId){
                        $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description');
                        $q->where('language_id', $langId);
                    }] )
                    ->select('id', 'sku', 'url_slug', 'weight', 'weight_unit')
                    ->where('id', $pid)
                    ->get();

        if(!$products){
            return response()->json(
                ['error' => 'No record found.'], 404);
        }


        foreach ($products as $key => $value) {

            if(!empty($value->pimage) && count($value->pimage) > 0){
                $imgs = array();
                foreach ($value->pimage as $k => $v) {
                    //$imgs['small'] = url('showImage/small/' . $v->path);
                    //$imgs['medium'] = url('showImage/medium/' . $v->path);
                    //$imgs['large'] = url('showImage/large/' . $v->path);

                    $products{$key}->image = \Storage::disk('s3')->url($v->path);

                    //unset($products{$key}->pimage{$k}->path);
                }
            }else{
                $products{$key}->image = \Storage::disk('s3')->url('default/default_image.png');
            }

            unset($products{$key}->pimage);

            if(!empty($value->variants) && count($value->variants) > 0){

                foreach ($value->variants as $row => $varRow) {
                    $prodPrice = '0.00';

                    if(!empty($varRow->price) && $varRow->price > 0 && Auth::user()->currency != 'USD'){

                        $prodPrice = $this->changeCurrency(Auth::user()->currency, $varRow->price);
                        //$products{$key}->price = $amount;
                        //$prodPrice = $value->baseprice[0]->price;
                        $value->variants{$row}->price = $prodPrice;
                    }
                }
            }
        }

        $response['products'] = $products;

        $response['variants'] = ProductVariantSet::join('variants as vs', 'vs.id', 'product_variant_sets.variant_type_id')
                    ->join('variant_translations as trans', 'vs.id', 'trans.variant_id')
                    ->select('product_variant_sets.product_id', 'trans.title', 'vs.id as varId', 'vs.type', 'vs.position')
                    ->where('trans.language_id', $langId)
                    ->where('product_variant_sets.product_id', $pid)
                    ->groupBy('product_variant_sets.variant_type_id')
                    ->orderBy('vs.position', 'asc')->get()->each(function ($variants, $key) use($langId, $pid){
                           
                    $options = ProductVariantSet::join('variant_options as opt', 'opt.id', 'product_variant_sets.variant_option_id')
                            ->join('variant_option_translations as opTrans', 'opt.id', 'opTrans.variant_option_id')
                            ->select('opTrans.title', 'opt.hexacode', 'opt.variant_id')
                            ->where('opTrans.language_id', $langId)
                            ->where('product_variant_sets.variant_type_id', $variants->varId)
                            ->where('product_variant_sets.product_id', $pid)
                            ->groupBy('product_variant_sets.variant_option_id')
                            ->orderBy('opt.id', 'asc')->get();

                            $variants->ashdhasd = $options->toArray();

                    });

        $response['product_addons'] = ProductAddon::with('addOn')
                                ->where('product_id', $pid)->get();

        $response['related_product'] = ProductRelated::with('detail.english', 'detail.variant')
                                ->where('product_id', $pid)->paginate(10);

        $response['up_sell_product'] = ProductUpSell::with('detail.english', 'detail.variant')
                                ->where('product_id', $pid)->paginate(10);

        $response['cross_sell_product'] = ProductCrossSell::with('detail.english', 'detail.variant')
                                ->where('product_id', $pid)->paginate(10);

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
                    $products{$key}->image = \Storage::disk('s3')->url($v->path);
                }
            }else{
                $products{$key}->image = \Storage::disk('s3')->url('default/default_image.png');
            }

            unset($products{$key}->pimage);

            if(!empty($value->baseprice) && count($value->baseprice) > 0){

                    //echo '<pre>';print_r($value->baseprice->toArray());die;
                $prodPrice = $value->baseprice[0]->price;

                if(!empty($value->baseprice[0]->price) && $value->baseprice[0]->price > 0 && Auth::user()->currency != 'USD'){

                    $prodPrice = $this->changeCurrency(Auth::user()->currency, $value->baseprice[0]->price);
                    //$products{$key}->price = $amount;
                    //$prodPrice = $value->baseprice[0]->price;

                }
            }
            $products{$key}->price = $prodPrice;
            unset($products{$key}->baseprice);

        }

        return response()->json([
            'data' => $products,
        ]);
    }

    public function productByCategory(Request $request)
    {
        $product = Product::with('variant.set', 'english', 'category.cat','variantSet', 'addOn', 'media')->where('id', $request->id)->firstOrFail();

        echo '<pre>'; print_r($product->toArray());die;

        return response()->json([
            'data' => $product,
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
