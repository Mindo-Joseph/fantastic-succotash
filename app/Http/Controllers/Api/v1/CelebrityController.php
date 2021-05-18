<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\v1\BaseController;
use App\Model\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\{User, Product, Category, ProductVariantSet, ProductVariant, ProductAddon, ProductRelated, ProductUpSell, ProductCrossSell, ClientCurrency, Vendor, Celebrity, ProductCelebrity};
use Validation;
use DB;
use App\Http\Traits\ApiResponser;

class CelebrityController extends BaseController
{
    private $field_status = 2;
    use ApiResponser;

    /**     *       Get Celebrity     *       */
    public function celebrityList($keyword = 'all')
    {
        try {
            if(empty($keyword) || strtolower($keyword) == 'all'){
                $celebrity = Celebrity::with('country')->where('status', '!=', 3)
                            ->select('id', 'name', 'avatar', 'description', 'country_id')->get();
                return $this->successResponse($celebrity);
            }
            $chars = str_split($keyword);

            $celebrity = Celebrity::with('country')->select('id', 'name', 'avatar', 'description', 'country_id')
                            ->where('status', '!=', 3)
                            ->where(function ($q) use ($chars) {
                                foreach ($chars as $key => $value) {
                                    if($key == 0){
                                        $q->where('name', 'LIKE', $value . '%');
                                    }else{
                                        $q->orWhere('name', 'LIKE', $value . '%');
                                    }
                                }
                            })->orderBy('name', 'asc')->get();
            return $this->successResponse($celebrity);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**     *       Get Celebrity Products    *       */
    public function celebrityProducts(Request $request, $cid = 0)
    {
        try {
            $userid = Auth::user()->id;
            $paginate = $request->has('limit') ? $request->limit : 12;
            $clientCurrency = ClientCurrency::where('currency_id', Auth::user()->currency)->first();
            $langId = Auth::user()->language;
            $celebrity = Celebrity::where('status', '!=', 3)
                            ->select('id', 'name', 'avatar', 'description', 'country_id')->where('id', $cid)->get();
            if(!$celebrity){
                return $this->errorResponse('Celebrity not found.', 404);
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
                        ->whereIn('product_variant_sets.product_id', function($qry) use($cid){ 
                            $qry->select('product_id')->from('product_celebrities')
                                ->where('celebrity_id', $cid);
                            })
                        ->groupBy('product_variant_sets.variant_type_id')->get();

            $products = Product::join('product_celebrities as pc', 'pc.product_id', 'products.id')
                        ->with(['inwishlist' => function($qry) use($userid){
                        $qry->where('user_id', $userid);
                    },
                    'media.image', 'translation' => function($q) use($langId){
                    $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $langId);
                    },
                    'variant' => function($q) use($langId){
                        $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                        $q->groupBy('product_id');
                    },
                ])
                ->select('products.id', 'products.sku', 'products.requires_shipping', 'products.sell_when_out_of_stock', 'products.url_slug', 'products.weight_unit', 'products.weight', 'products.vendor_id', 'products.has_variant', 'products.has_inventory', 'products.Requires_last_mile', 'products.averageRating', 'pc.celebrity_id')
                ->where('pc.celebrity_id', $cid)
                ->paginate($paginate);
        
            if(!empty($products)){
                foreach ($products as $key => $value) {
                    foreach ($value->variant as $k => $v) {
                        $value->variant{$k}->multiplier = $clientCurrency->doller_compare;
                    }
                }
            }
            
            $response['celebrity'] = $celebrity;
            $response['products'] = $products;
            $response['filterVariant'] = $variantSets;
            return $this->successResponse($response);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }

























    }
}